<?php
/**
 * Live Status REST API Endpoint
 *
 * Detección eficiente de transmisiones en vivo:
 *  - Usa la ruta económica de la API (uploads playlist → último video → estado live)
 *    que consume ~3 unidades de cuota por consulta, en lugar de search?eventType=live
 *    (100 unidades) que agota la cuota diaria y tiene retardo de indexación.
 *  - Caché compartida en servidor (default 15s) para soportar polling agresivo
 *    del frontend sin gastar cuota extra: todos los visitantes comparten resultado.
 *  - Respeta la API Key configurada en Apariencia > Transmisión Live.
 */

if (!defined('IGLESIA_LIVE_FALLBACK_API_KEY')) {
    define('IGLESIA_LIVE_FALLBACK_API_KEY', 'AIzaSyCJE1Wr6ThYp3BEfHpRRcW1zeOY82A7d0c');
}

add_action('rest_api_init', function() {
    register_rest_route('iglesia/v1', '/live-status', [
        'methods'             => 'GET',
        'callback'            => 'iglesia_get_live_status',
        'permission_callback' => '__return_true',
    ]);
});

function iglesia_live_api_key() {
    $key = trim((string) get_option('iglesia_youtube_api_key', ''));
    return $key !== '' ? $key : IGLESIA_LIVE_FALLBACK_API_KEY;
}

// TTL del caché del lado servidor. Ajustable con el filtro iglesia_live_cache_ttl.
function iglesia_live_cache_ttl() {
    return max(5, (int) apply_filters('iglesia_live_cache_ttl', 15));
}

function iglesia_live_yt_get($url) {
    return wp_remote_get($url, ['timeout' => 6, 'sslverify' => true]);
}

/**
 * Resuelve Channel ID + playlist de uploads (se cachean en opciones,
 * no consumen cuota recurrente).
 */
function iglesia_live_channel_context($channel_url) {
    $channel_id = get_option('iglesia_youtube_channel_id', '');
    $uploads_id = get_option('iglesia_youtube_uploads_playlist', '');

    if ($channel_id && $uploads_id) {
        return [$channel_id, $uploads_id];
    }

    $handle = preg_replace('/.*@/', '', rtrim(trim($channel_url), '/'));
    if (!$handle) {
        return [$channel_id, $uploads_id];
    }

    $response = iglesia_live_yt_get(
        "https://www.googleapis.com/youtube/v3/channels?part=id,contentDetails&forHandle=@{$handle}&key=" . iglesia_live_api_key()
    );
    if (is_wp_error($response)) {
        return [$channel_id, $uploads_id];
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (empty($data['items'][0]['id'])) {
        return [$channel_id, $uploads_id];
    }

    $channel_id = $data['items'][0]['id'];
    $uploads_id = isset($data['items'][0]['contentDetails']['relatedPlaylists']['uploads'])
        ? $data['items'][0]['contentDetails']['relatedPlaylists']['uploads']
        : '';

    update_option('iglesia_youtube_channel_id', $channel_id, false);
    if ($uploads_id) {
        update_option('iglesia_youtube_uploads_playlist', $uploads_id, false);
    }

    return [$channel_id, $uploads_id];
}

/**
 * Consulta real a YouTube.
 * Devuelve ['is_live' => bool, 'video_id' => string, 'error' => string|'']
 */
function iglesia_live_query_youtube() {
    $channel_url = get_option('iglesia_youtube_channel', 'https://youtube.com/@l3cheisen598');
    list($channel_id, $uploads_id) = iglesia_live_channel_context($channel_url);

    if (!$channel_id) {
        return ['is_live' => false, 'video_id' => '', 'error' => 'no_channel'];
    }

    $latest_video_id = '';

    // Ruta principal (1 unidad): video más reciente del canal vía uploads playlist.
    // YouTube registra los livestreams activos como el upload más reciente.
    if ($uploads_id) {
        $resp = iglesia_live_yt_get(
            "https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&playlistId={$uploads_id}&maxResults=1&key=" . iglesia_live_api_key()
        );
        if (!is_wp_error($resp)) {
            $data = json_decode(wp_remote_retrieve_body($resp), true);
            if (!empty($data['items'][0]['contentDetails']['videoId'])) {
                $latest_video_id = $data['items'][0]['contentDetails']['videoId'];
            }
        }
    }

    // Fallback legacy (100 unidades): búsqueda eventType=live por si la playlist falla.
    if (!$latest_video_id) {
        $resp = iglesia_live_yt_get(
            "https://www.googleapis.com/youtube/v3/search?part=id&channelId={$channel_id}&eventType=live&type=video&key=" . iglesia_live_api_key()
        );
        if (!is_wp_error($resp)) {
            $data = json_decode(wp_remote_retrieve_body($resp), true);
            if (!empty($data['items'][0]['id']['videoId'])) {
                $latest_video_id = $data['items'][0]['id']['videoId'];
            } elseif (!empty($data['error'])) {
                $msg = isset($data['error']['errors'][0]['reason']) ? $data['error']['errors'][0]['reason'] : 'search_fallback_failed';
                return ['is_live' => false, 'video_id' => '', 'error' => $msg];
            }
        }
    }

    if (!$latest_video_id) {
        return ['is_live' => false, 'video_id' => '', 'error' => 'no_videos_found'];
    }

    // Verificar estado del video (1 unidad).
    $resp = iglesia_live_yt_get(
        "https://www.googleapis.com/youtube/v3/videos?part=snippet,liveStreamingDetails&id={$latest_video_id}&key=" . iglesia_live_api_key()
    );
    if (is_wp_error($resp)) {
        return ['is_live' => false, 'video_id' => $latest_video_id, 'error' => 'videos_check_failed'];
    }

    $body  = wp_remote_retrieve_body($resp);
    $data  = json_decode($body, true);

    if (empty($data['items'][0])) {
        $reason = 'video_not_found';
        if (!empty($data['error']['errors'][0]['reason'])) {
            $reason = $data['error']['errors'][0]['reason']; // p.ej. quotaExceeded
        }
        return ['is_live' => false, 'video_id' => $latest_video_id, 'error' => $reason];
    }

    $broadcast = isset($data['items'][0]['snippet']['liveBroadcastContent'])
        ? $data['items'][0]['snippet']['liveBroadcastContent']
        : 'none';

    return [
        'is_live'   => ($broadcast === 'live'),
        'video_id'  => $latest_video_id,
        'error'     => '',
    ];
}

function iglesia_get_live_status($request) {
    // 1) Override manual: embed fijo siempre gana
    $embed_url = get_option('iglesia_youtube_embed', '');
    if (!empty($embed_url)) {
        if (preg_match('/(?:youtube\.com\/(?:live\/|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{6,})/', $embed_url, $m)) {
            return new WP_REST_Response([
                'is_live'    => true,
                'video_id'   => $m[1],
                'stream_url' => "https://www.youtube.com/embed/{$m[1]}?autoplay=1&mute=1",
                'source'     => 'manual',
                'channel_url' => get_option('iglesia_youtube_channel', ''),
            ], 200);
        }
    }

    // 2) Caché compartida para todo el sitio
    $cache_key  = 'iglesia_live_status_v2';
    $cached     = get_transient($cache_key);
    if (is_array($cached)) {
        $cached['cached'] = true;
        return new WP_REST_Response($cached, 200);
    }

    // 3) Consultar YouTube
    $result   = iglesia_live_query_youtube();
    $channel_url = get_option('iglesia_youtube_channel', '');

    if (!$result['error']) {
        $payload = [
            'is_live'     => $result['is_live'],
            'video_id'    => $result['video_id'],
            'stream_url'  => $result['is_live'] ? "https://www.youtube.com/embed/{$result['video_id']}?autoplay=1&mute=1" : '',
            'channel_url' => $channel_url,
            'checked_at'  => time(),
            'source'      => 'api',
        ];

        set_transient($cache_key, $payload, iglesia_live_cache_ttl());

        // Guardar último estado bueno conocido (para errores futuros de cuota/red)
        update_option('iglesia_live_last_good', $payload, false);

        return new WP_REST_Response($payload, 200);
    }

    // 4) En error (cuota, red, etc.) servir último estado conocido marcándolo como degradado
    $last_good = get_option('iglesia_live_last_good', '');
    if (is_array($last_good) && isset($last_good['is_live'])) {
        // Si el live terminó hace mucho (>6h), no forzarlo como vivo ante un error
        $age = time() - (int)($last_good['checked_at'] ?? 0);
        if (!$last_good['is_live'] || $age < 6 * HOUR_IN_SECONDS) {
            $payload = $last_good;
            $payload['degraded'] = true;
            $payload['error']    = $result['error'];
            set_transient($cache_key, $payload, iglesia_live_cache_ttl());
            return new WP_REST_Response($payload, 200);
        }
    }

    $payload = [
        'is_live'     => false,
        'video_id'    => '',
        'stream_url'  => '',
        'channel_url' => $channel_url,
        'degraded'    => true,
        'error'       => $result['error'],
        'checked_at'  => time(),
        'source'      => 'api-error',
    ];
    set_transient($cache_key, $payload, iglesia_live_cache_ttl());
    return new WP_REST_Response($payload, 200);
}
