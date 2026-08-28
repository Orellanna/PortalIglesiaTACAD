<?php
/**
 * Live Stream Settings Page
 */

// Register the settings page
add_action('admin_menu', function() {
    add_submenu_page(
        'themes.php',
        'Configuración de Live',
        'Transmisión Live',
        'manage_options',
        'iglesia-live-settings',
        'iglesia_live_settings_page'
    );
});

// Register settings
add_action('admin_init', function() {
    register_setting('iglesia_live_settings', 'iglesia_youtube_channel');
    register_setting('iglesia_live_settings', 'iglesia_youtube_embed');
    register_setting('iglesia_live_settings', 'iglesia_youtube_api_key');
    register_setting('iglesia_live_settings', 'iglesia_youtube_channel_id');
    register_setting('iglesia_live_settings', 'iglesia_live_auto_detect');
});

function iglesia_live_settings_page() {
    // Save settings
    if (isset($_POST['iglesia_live_save']) && wp_verify_nonce($_POST['iglesia_live_nonce'], 'iglesia_live_save')) {
        update_option('iglesia_youtube_channel', sanitize_url($_POST['youtube_channel']));
        update_option('iglesia_youtube_embed', sanitize_url($_POST['youtube_embed']));
        update_option('iglesia_youtube_api_key', sanitize_text_field($_POST['youtube_api_key']));
        update_option('iglesia_live_auto_detect', isset($_POST['auto_detect']) ? 1 : 0);
        // Clear cached channel ID to force re-fetch
        delete_option('iglesia_youtube_channel_id');
        echo '<div class="notice notice-success" style="border-radius:8px;padding:12px 20px;margin:15px 0;"><p>Configuración guardada correctamente.</p></div>';
    }

    $channel = get_option('iglesia_youtube_channel', 'https://youtube.com/@l3cheisen598');
    $embed = get_option('iglesia_youtube_embed', '');
    $api_key = get_option('iglesia_youtube_api_key', 'AIzaSyCJE1Wr6ThYp3BEfHpRRcW1zeOY82A7d0c');
    $channel_id = get_option('iglesia_youtube_channel_id', '');
    $auto_detect = get_option('iglesia_live_auto_detect', 1);
    ?>
    <div style="max-width:800px;padding:20px;">
        <div style="background:#fff;border:1px solid #e8ecf1;border-radius:16px;padding:30px 35px;margin-bottom:25px;">
            <h1 style="margin:0;font-size:1.5rem;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:12px;">
                <span style="background:#c00;color:#fff;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1rem;">🔴</span>
                Configuración de Transmisión en Vivo
            </h1>
            <p style="color:#64748b;margin-top:8px;font-size:0.9rem;">
                Configura el canal de YouTube. El sistema detectará automáticamente si hay una transmisión activa cuando uses la API key.
            </p>
        </div>

        <form method="post" style="background:#fff;border:1px solid #e8ecf1;border-radius:16px;padding:30px 35px;">
            <?php wp_nonce_field('iglesia_live_save', 'iglesia_live_nonce'); ?>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-weight:600;color:#475569;margin-bottom:8px;font-size:14px;">
                    URL del Canal de YouTube
                </label>
                <input type="url" name="youtube_channel" value="<?php echo esc_attr($channel); ?>"
                    style="width:100%;padding:12px 16px;border:1.5px solid #e8ecf1;border-radius:10px;font-size:14px;"
                    placeholder="https://www.youtube.com/@TuCanal">
                <p style="color:#94a3b8;font-size:12px;margin-top:6px;">
                    Ejemplo: https://www.youtube.com/@l3cheisen598
                </p>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-weight:600;color:#475569;margin-bottom:8px;font-size:14px;">
                    YouTube Data API Key
                </label>
                <input type="text" name="youtube_api_key" value="<?php echo esc_attr($api_key); ?>"
                    style="width:100%;padding:12px 16px;border:1.5px solid #e8ecf1;border-radius:10px;font-size:14px;"
                    placeholder="AIzaSy...">
                <p style="color:#94a3b8;font-size:12px;margin-top:6px;">
                    API Key de Google Cloud Console. Necesaria para detección automática de live.
                </p>
            </div>

            <?php if (!empty($channel_id)): ?>
            <div style="margin-bottom:24px;padding:12px 16px;background:#ecfdf5;border-radius:10px;border:1px solid #6ee7b7;">
                <p style="margin:0;color:#065f46;font-size:13px;">
                    <strong>Channel ID detectado:</strong> <?php echo esc_html($channel_id); ?>
                </p>
            </div>
            <?php endif; ?>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-weight:600;color:#475569;margin-bottom:8px;font-size:14px;">
                    URL de Embed del Live (opcional)
                </label>
                <input type="url" name="youtube_embed" value="<?php echo esc_attr($embed); ?>"
                    style="width:100%;padding:12px 16px;border:1.5px solid #e8ecf1;border-radius:10px;font-size:14px;"
                    placeholder="https://www.youtube.com/embed/VIDEO_ID">
                <p style="color:#94a3b8;font-size:12px;margin-top:6px;">
                    Si prefieres mostrar un video específico en lugar de detección automática, ingrésalo aquí.
                </p>
            </div>

            <div style="margin-bottom:24px;padding:16px;background:#f8fafc;border-radius:10px;border:1px solid #e8ecf1;">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="auto_detect" value="1" <?php checked($auto_detect, 1); ?>
                        style="width:18px;height:18px;">
                    <span style="font-weight:600;color:#475569;">Detectar automáticamente el estado del live</span>
                </label>
                <p style="color:#94a3b8;font-size:12px;margin-top:8px;margin-left:28px;">
                    El sitio verifica constantemente el estado del live. La consulta a YouTube se optimiza con caché interna para no gastar cuota de API.
                </p>
            </div>

            <input type="submit" name="iglesia_live_save" value="Guardar Configuración"
                style="background:#c00;color:#fff;border:none;padding:14px 28px;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;">
        </form>

        <div style="margin-top:20px;padding:20px;background:#1e3a5f;border-radius:16px;color:#fff;">
            <h4 style="margin:0 0 12px;font-size:14px;font-weight:700;">Cómo funciona:</h4>
            <ol style="margin:0;padding-left:20px;color:rgba(255,255,255,0.9);font-size:13px;line-height:1.8;">
                <li>El sistema usa YouTube Data API v3 para verificar si hay un live activo</li>
                <li>Solo necesita el link del canal y la API Key</li>
                <li>Cuando el pastor inicia un live en YouTube, el botón se vuelve rojo automáticamente</li>
                <li>El video se muestra en la página principal</li>
            </ol>
        </div>
    </div>
    <?php
}
