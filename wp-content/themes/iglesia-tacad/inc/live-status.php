<?php
/**
 * Live Status REST API Endpoint
 * Uses YouTube Data API v3 for accurate live detection
 */

add_action('rest_api_init', function() {
    register_rest_route('iglesia/v1', '/live-status', [
        'methods'  => 'GET',
        'callback' => 'iglesia_get_live_status',
        'permission_callback' => '__return_true',
    ]);
});

function iglesia_get_live_status($request) {
    $api_key = 'AIzaSyCJE1Wr6ThYp3BEfHpRRcW1zeOY82A7d0c';
    $channel_url = get_option('iglesia_youtube_channel', 'https://youtube.com/@l3cheisen598');
    $embed_url = get_option('iglesia_youtube_embed', '');
    $cached_channel_id = get_option('iglesia_youtube_channel_id', '');

    $is_live = false;
    $video_id = '';
    $stream_url = '';

    // First check if manual embed URL is set
    if (!empty($embed_url)) {
        if (preg_match('/youtube\.com\/live\/([a-zA-Z0-9_-]+)/', $embed_url, $matches)) {
            $video_id = $matches[1];
            $is_live = true;
            $stream_url = "https://www.youtube.com/embed/{$video_id}?autoplay=1&mute=1";
        } elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $embed_url, $matches)) {
            $video_id = $matches[1];
            $is_live = true;
            $stream_url = "https://www.youtube.com/embed/{$video_id}?autoplay=1&mute=1";
        }
    }

    // If not manually set, use API for automatic detection
    if (!$is_live) {
        // Get channel ID from handle if not cached
        if (empty($cached_channel_id)) {
            $handle = preg_replace('/.*@(.*)/', '$1', $channel_url);
            $channel_api_url = "https://www.googleapis.com/youtube/v3/channels?part=id&forHandle=@{$handle}&key={$api_key}";

            $response = wp_remote_get($channel_api_url, [
                'timeout' => 10,
                'sslverify' => true,
            ]);

            if (!is_wp_error($response)) {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body, true);

                if (!empty($data['items'][0]['id'])) {
                    $cached_channel_id = $data['items'][0]['id'];
                    update_option('iglesia_youtube_channel_id', $cached_channel_id);
                }
            }
        }

        // Check for live stream if we have channel ID
        if (!empty($cached_channel_id)) {
            $live_api_url = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId={$cached_channel_id}&eventType=live&type=video&key={$api_key}";

            $response = wp_remote_get($live_api_url, [
                'timeout' => 10,
                'sslverify' => true,
            ]);

            if (!is_wp_error($response)) {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body, true);

                if (!empty($data['items']) && count($data['items']) > 0) {
                    $is_live = true;
                    $video_id = $data['items'][0]['id']['videoId'];
                    $stream_url = "https://www.youtube.com/embed/{$video_id}?autoplay=1&mute=1";
                }
            }
        }
    }

    return new WP_REST_Response([
        'is_live' => $is_live,
        'video_id' => $video_id,
        'stream_url' => $stream_url,
        'channel_url' => $channel_url,
        'channel_id' => $cached_channel_id,
    ], 200);
}
