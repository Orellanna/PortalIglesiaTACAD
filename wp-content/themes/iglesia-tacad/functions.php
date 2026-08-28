<?php
/**
 * Iglesia TACAD Theme Functions
 */

// Theme Support
add_action('after_setup_theme', function() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo');
    register_nav_menus([
        'primary_menu' => 'Menú Principal',
        'footer_menu'  => 'Menú Pie de Página',
    ]);
});

// Enqueue Styles & Scripts
add_action('wp_enqueue_scripts', function() {
    $theme_version = '6.0.0';
    wp_enqueue_style('iglesia-style', get_stylesheet_uri(), [], $theme_version);
    // Font Awesome 6 (icons)
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], '6.5.0');
    wp_enqueue_script('iglesia-main', get_template_directory_uri() . '/js/main.js', [], $theme_version, true);
    wp_enqueue_script('iglesia-live-status', get_template_directory_uri() . '/js/live-status.js', ['iglesia-main'], '6.1.0', true);
    // Endpoint REST del propio sitio disponible para el checker de live
    wp_localize_script('iglesia-live-status', 'iglesiaLive', [
        'restUrl' => esc_url_raw(rest_url('iglesia/v1/live-status')),
    ]);
});

// Include Live Status files
require_once get_template_directory() . '/inc/live-status.php';
require_once get_template_directory() . '/inc/live-settings.php';

// Custom Favicon
add_action('wp_head', function() {
    $fav = get_template_directory_uri() . '/images/logo.jpeg';
    echo '<link rel="icon" href="' . esc_url($fav) . '" sizes="32x32">';
    echo '<link rel="apple-touch-icon" href="' . esc_url($fav) . '">';
});

// =============================================
// CUSTOM POST TYPE: MINISTERIOS
// =============================================
add_action('init', function() {
    register_post_type('ministerio', [
        'labels' => [
            'name' => 'Ministerios',
            'singular_name' => 'Ministerio',
            'add_new' => 'Agregar Ministerio',
            'add_new_item' => 'Agregar Nuevo Ministerio',
            'edit_item' => 'Editar Ministerio',
            'new_item' => 'Nuevo Ministerio',
            'view_item' => 'Ver Ministerio',
            'search_items' => 'Buscar Ministerios',
            'not_found' => 'No se encontraron ministerios',
            'not_found_in_trash' => 'No hay ministerios en la papelera',
        ],
        'public' => true,
        'has_archive' => false,
        'show_in_menu' => false, // Lo agregamos manualmente
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'rewrite' => ['slug' => 'ministerios'],
        'show_in_rest' => true,
    ]);

    // CPT: PREGUNTAS FRECUENTES
    register_post_type('faq', [
        'labels' => [
            'name' => 'Preguntas Frecuentes',
            'singular_name' => 'Pregunta Frecuente',
            'add_new' => 'Agregar Pregunta',
            'add_new_item' => 'Agregar Nueva Pregunta',
            'edit_item' => 'Editar Pregunta',
            'new_item' => 'Nueva Pregunta',
            'view_item' => 'Ver Pregunta',
            'search_items' => 'Buscar Preguntas',
            'not_found' => 'No se encontraron preguntas',
            'not_found_in_trash' => 'No hay preguntas en la papelera',
        ],
        'public' => true,
        'has_archive' => false,
        'show_in_menu' => false,
        'menu_icon' => 'dashicons-editor-help',
        'supports' => ['title', 'page-attributes'],
        'rewrite' => ['slug' => 'faq'],
        'show_in_rest' => true,
    ]);

    // CPT: SECCIONES DE FAQ
    register_post_type('faq_seccion', [
        'labels' => [
            'name' => 'Secciones FAQ',
            'singular_name' => 'Sección FAQ',
            'add_new' => 'Agregar Sección',
            'add_new_item' => 'Agregar Nueva Sección',
            'edit_item' => 'Editar Sección',
            'new_item' => 'Nueva Sección',
            'view_item' => 'Ver Sección',
            'search_items' => 'Buscar Secciones',
            'not_found' => 'No se encontraron secciones',
            'not_found_in_trash' => 'No hay secciones en la papelera',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'menu_icon' => 'dashicons-category',
        'supports' => ['title', 'page-attributes'],
        'show_in_rest' => true,
    ]);

    // CPT: HIMNOS
    register_post_type('himno', [
        'labels' => [
            'name' => 'Himnario',
            'singular_name' => 'Himno',
            'add_new' => 'Agregar Himno',
            'add_new_item' => 'Agregar Nuevo Himno',
            'edit_item' => 'Editar Himno',
            'new_item' => 'Nuevo Himno',
            'view_item' => 'Ver Himno',
            'search_items' => 'Buscar Himnos',
            'not_found' => 'No se encontraron himnos',
            'not_found_in_trash' => 'No hay himnos en la papelera',
        ],
        'public' => true,
        'has_archive' => false,
        'show_in_menu' => false,
        'menu_icon' => 'dashicons-playlist-audio',
        'supports' => ['title', 'page-attributes'],
        'rewrite' => ['slug' => 'himnos'],
        'show_in_rest' => true,
    ]);
});

// Meta box para descripción e imagen del ministerio (patrón Iglesia)
add_action('add_meta_boxes', function() {
    add_meta_box(
        'ministerio_info',
        '📋 Información del Ministerio',
        function($post) {
            wp_nonce_field('ministerio_meta', 'ministerio_meta_nonce');

            $descripcion = get_post_meta($post->ID, 'ministerio_descripcion', true);
            $imagen_id = get_post_meta($post->ID, 'ministerio_imagen_id', true);
            $imagen_url = $imagen_id ? wp_get_attachment_image_url($imagen_id, 'medium') : '';
            $activo_min = get_post_meta($post->ID, 'ministerio_activo', true);
            $activo_min = ($activo_min === '' || $activo_min === '1') ? true : false;
            ?>
            <div class="igl-info-box">
                <p><span class="dashicons dashicons-lightbulb"></span>El nombre del Ministerio se configura en el campo "Título" de arriba. El contenido principal (editor grande) se muestra en su página de detalle.</p>
            </div>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-info"></span> Datos del Ministerio</h3>
                <div class="igl-set-field">
                    <label for="ministerio_descripcion">Descripción corta</label>
                    <textarea name="ministerio_descripcion" id="ministerio_descripcion" placeholder="Descripción que aparece en la tarjeta del listado de Ministerios..."><?php echo esc_textarea($descripcion); ?></textarea>
                    <p class="hint">Máx. ~150 caracteres. Se muestra en la tarjeta de la página Ministerios.</p>
                </div>
                <div class="igl-set-field">
                    <label for="ministerio_orden">Orden de aparición</label>
                    <input type="number" name="menu_order" id="ministerio_orden" value="<?php echo esc_attr($post->menu_order ?: 0); ?>" min="0" style="max-width:110px;">
                    <p class="hint">Número más bajo = aparece primero en el listado.</p>
                </div>
            </div>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-format-image"></span> Imagen del Ministerio</h3>
                <div style="display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap;">
                    <div class="igl-img-preview" id="min-img-preview" onclick="document.getElementById('min-img-btn').click()" style="flex-shrink:0;">
                        <?php if ($imagen_url): ?>
                            <img src="<?php echo esc_url($imagen_url); ?>" alt="Imagen">
                        <?php else: ?>
                            <div class="placeholder"><span class="dashicons dashicons-images-alt"></span><br><small>Click para subir imagen</small></div>
                        <?php endif; ?>
                    </div>
                    <div style="min-width:200px;">
                        <input type="hidden" id="ministerio_imagen_id" name="ministerio_imagen_id" value="<?php echo esc_attr($imagen_id); ?>">
                        <button type="button" class="button button-primary" id="min-img-btn">
                            <span class="dashicons dashicons-admin-media" style="font-size:14px;width:14px;height:14px;margin:4px 2px 0 0;"></span>
                            Seleccionar imagen
                        </button>
                        <p class="hint" style="margin-top:10px;">Recomendado: 600x400px horizontal.<br>Sin imagen se usa una predeterminada en las tarjetas.</p>
                    </div>
                </div>
            </div>

            <?php iglesia_render_toggle_box('ministerio_activo', $activo_min); ?>

            <script>
            jQuery(function($) {
                var imgFrame;
                $('#min-img-btn').on('click', function(e) {
                    e.preventDefault();
                    if (imgFrame) { imgFrame.open(); return; }
                    imgFrame = wp.media({
                        title: 'Seleccionar imagen del ministerio',
                        button: { text: 'Usar esta imagen' },
                        multiple: false,
                        library: { type: 'image' }
                    });
                    imgFrame.on('select', function() {
                        var attachment = imgFrame.state().get('selection').first().toJSON();
                        var url = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
                        $('#ministerio_imagen_id').val(attachment.id);
                        $('#min-img-preview').html('<img src="' + url + '" alt="Imagen">');
                        if (!$('#min-img-remove').length) {
                            $('#min-img-btn').after('<button type="button" class="button button-link-delete" id="min-img-remove">Quitar imagen</button>');
                        }
                    });
                    imgFrame.open();
                });
                $(document).on('click', '#min-img-remove', function() {
                    $('#ministerio_imagen_id').val('');
                    $('#min-img-preview').html('<div class="placeholder"><span class="dashicons dashicons-images-alt"></span><br><small>Click para subir imagen</small></div>');
                    $(this).remove();
                });
            });
            </script>
            <?php
        },
        'ministerio',
        'normal',
        'high'
    );
});

// Guardar meta box
add_action('save_post_ministerio', function($post_id) {
    if (!isset($_POST['ministerio_meta_nonce']) || !wp_verify_nonce($_POST['ministerio_meta_nonce'], 'ministerio_meta')) {
        return;
    }
    if (isset($_POST['ministerio_descripcion'])) {
        update_post_meta($post_id, 'ministerio_descripcion', sanitize_textarea_field($_POST['ministerio_descripcion']));
    }
    if (isset($_POST['ministerio_imagen_id'])) {
        update_post_meta($post_id, 'ministerio_imagen_id', intval($_POST['ministerio_imagen_id']));
    }
    update_post_meta($post_id, 'ministerio_activo', isset($_POST['ministerio_activo']) ? '1' : '0');
});

// =============================================
// FAQ META BOXES
// =============================================
add_action('add_meta_boxes', function() {
    add_meta_box(
        'faq_info',
        'Información de la Pregunta',
        function($post) {
            wp_nonce_field('faq_meta', 'faq_meta_nonce');

            $descripcion = get_post_meta($post->ID, 'faq_descripcion', true);
            $seccion_id = get_post_meta($post->ID, 'faq_seccion_id', true);
            $activo = get_post_meta($post->ID, 'faq_activo', true);
            $activo = ($activo === '' || $activo === '1') ? true : false;

            // Obtener secciones
            $secciones = get_posts(['post_type' => 'faq_seccion', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC']);
            ?>
            <style>
            .igl-set-card { background:#fff; border:1px solid #dcdcde; border-radius:12px; padding:20px; margin-bottom:20px; }
            .igl-set-card:last-child { margin-bottom:0; }
            .igl-set-card h3 { margin:0 0 16px; padding-bottom:12px; border-bottom:2px solid #f0f0f1; font-size:14px; font-weight:700; color:#1d2327; display:flex; align-items:center; gap:8px; }
            .igl-set-card h3 .dashicons { color:#2271b1; }
            .igl-set-field { margin-bottom:14px; }
            .igl-set-field:last-child { margin-bottom:0; }
            .igl-set-field label { display:block; font-weight:600; margin-bottom:6px; color:#3c434a; font-size:13px; }
            .igl-set-field input[type="number"],
            .igl-set-field input[type="text"],
            .igl-set-field select,
            .igl-set-field textarea { width:100%; border-radius:6px; border:1px solid #dcdcde; padding:8px 12px; font-size:14px; }
            .igl-set-field textarea { min-height:100px; resize:vertical; }
            .igl-set-field input:focus,
            .igl-set-field select:focus,
            .igl-set-field textarea:focus { border-color:#2271b1; outline:none; box-shadow:0 0 0 2px rgba(34,113,177,0.2); }
            .igl-set-field .hint { font-size:12px; color:#646970; margin-top:4px; }
            .igl-toggle { display:flex; align-items:center; gap:12px; }
            .igl-toggle .toggle { position:relative; width:50px; height:26px; flex-shrink:0; }
            .igl-toggle .toggle input { opacity:0; width:0; height:0; }
            .igl-toggle .slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:#ccc; transition:.3s; border-radius:26px; }
            .igl-toggle .slider:before { position:absolute; content:""; height:20px; width:20px; left:3px; bottom:3px; background-color:white; transition:.3s; border-radius:50%; }
            .igl-toggle .toggle input:checked + .slider { background-color:#2271b1; }
            .igl-toggle .toggle input:checked + .slider:before { transform:translateX(24px); }
            .igl-toggle .status-label { font-weight:600; font-size:13px; }
            .igl-toggle .status-active { color:#00a32a; }
            .igl-toggle .status-inactive { color:#646970; }
            .igl-set-field select { background:#fff; }
            </style>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-info"></span> Datos de la Pregunta</h3>
                <div class="igl-set-field">
                    <label for="faq_seccion_id">Sección</label>
                    <select name="faq_seccion_id" id="faq_seccion_id">
                        <option value="">— Sin sección —</option>
                        <?php foreach ($secciones as $sec) : ?>
                            <option value="<?php echo $sec->ID; ?>" <?php selected($seccion_id, $sec->ID); ?>><?php echo esc_html($sec->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="hint">A qué sección pertenece esta pregunta.</p>
                </div>
                <div class="igl-set-field">
                    <label for="faq_descripcion">Respuesta</label>
                    <textarea name="faq_descripcion" id="faq_descripcion" placeholder="Escribe la respuesta a esta pregunta frecuente..."><?php echo esc_textarea($descripcion); ?></textarea>
                    <p class="hint">Esta es la respuesta que se mostrará cuando el usuario haga clic en la pregunta.</p>
                </div>
            </div>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-sort"></span> Configuración</h3>
                <div class="igl-set-field">
                    <label for="faq_orden">Orden de aparición</label>
                    <input type="number" name="menu_order" id="faq_orden" value="<?php echo esc_attr($post->menu_order ?: 0); ?>" min="0">
                    <p class="hint">Número más bajo = aparece primero dentro de su sección.</p>
                </div>
            </div>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-<?php echo $activo ? 'visibility' : 'hidden'; ?>"></span> Estado</h3>
                <div class="igl-set-field">
                    <div class="igl-toggle">
                        <label class="toggle">
                            <input type="checkbox" name="faq_activo" id="faq_activo" value="1" <?php checked($activo, true); ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="status-label <?php echo $activo ? 'status-active' : 'status-inactive'; ?>" id="faq_status_label">
                            <?php echo $activo ? '✓ Activo - Visible en la web' : '✗ Inactivo - Oculto en la web'; ?>
                        </span>
                    </div>
                    <p class="hint" style="margin-top:10px;">Desactiva esta opción si no quieres que la pregunta aparezca en la página.</p>
                </div>
            </div>

            <script>
            jQuery(function($) {
                $('#faq_activo').on('change', function() {
                    var label = $('#faq_status_label');
                    var icon = $(this).closest('.igl-set-card').find('h3 .dashicons');
                    if ($(this).is(':checked')) {
                        label.text('✓ Activo - Visible en la web').removeClass('status-inactive').addClass('status-active');
                        icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
                    } else {
                        label.text('✗ Inactivo - Oculto en la web').removeClass('status-active').addClass('status-inactive');
                        icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
                    }
                });
            });
            </script>
            <?php
        },
        'faq',
        'normal',
        'high'
    );
});

// SECCIÓN FAQ META BOXES
add_action('add_meta_boxes', function() {
    add_meta_box(
        'faq_seccion_info',
        'Configuración de la Sección',
        function($post) {
            wp_nonce_field('faq_seccion_meta', 'faq_seccion_meta_nonce');

            $activo = get_post_meta($post->ID, 'faq_seccion_activo', true);
            $activo = ($activo === '' || $activo === '1') ? true : false;
            ?>
            <style>
            .igl-set-card { background:#fff; border:1px solid #dcdcde; border-radius:12px; padding:20px; margin-bottom:20px; }
            .igl-set-card:last-child { margin-bottom:0; }
            .igl-set-card h3 { margin:0 0 16px; padding-bottom:12px; border-bottom:2px solid #f0f0f1; font-size:14px; font-weight:700; color:#1d2327; display:flex; align-items:center; gap:8px; }
            .igl-set-card h3 .dashicons { color:#2271b1; }
            .igl-set-field { margin-bottom:14px; }
            .igl-set-field:last-child { margin-bottom:0; }
            .igl-set-field label { display:block; font-weight:600; margin-bottom:6px; color:#3c434a; font-size:13px; }
            .igl-set-field input[type="number"] { width:100px; border-radius:6px; border:1px solid #dcdcde; padding:8px 12px; font-size:14px; }
            .igl-set-field input:focus { border-color:#2271b1; outline:none; box-shadow:0 0 0 2px rgba(34,113,177,0.2); }
            .igl-set-field .hint { font-size:12px; color:#646970; margin-top:4px; }
            .igl-toggle { display:flex; align-items:center; gap:12px; }
            .igl-toggle .toggle { position:relative; width:50px; height:26px; flex-shrink:0; }
            .igl-toggle .toggle input { opacity:0; width:0; height:0; }
            .igl-toggle .slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:#ccc; transition:.3s; border-radius:26px; }
            .igl-toggle .slider:before { position:absolute; content:""; height:20px; width:20px; left:3px; bottom:3px; background-color:white; transition:.3s; border-radius:50%; }
            .igl-toggle .toggle input:checked + .slider { background-color:#2271b1; }
            .igl-toggle .toggle input:checked + .slider:before { transform:translateX(24px); }
            .igl-toggle .status-label { font-weight:600; font-size:13px; }
            .igl-toggle .status-active { color:#00a32a; }
            .igl-toggle .status-inactive { color:#646970; }
            .igl-info-box { background:#f0f0f1; border-radius:8px; padding:15px; margin-bottom:20px; }
            .igl-info-box p { margin:0; font-size:13px; color:#3c434a; }
            .igl-info-box .dashicons { color:#2271b1; margin-right:8px; }
            </style>

            <div class="igl-info-box">
                <p><span class="dashicons dashicons-info"></span>El nombre de la sección se configura en el campo "Título" de arriba.</p>
            </div>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-sort"></span> Configuración</h3>
                <div class="igl-set-field">
                    <label for="faq_seccion_orden">Orden de aparición</label>
                    <input type="number" name="menu_order" id="faq_seccion_orden" value="<?php echo esc_attr($post->menu_order ?: 0); ?>" min="0">
                    <p class="hint">Número más bajo = aparece primero en la lista de secciones.</p>
                </div>
            </div>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-<?php echo $activo ? 'visibility' : 'hidden'; ?>"></span> Estado</h3>
                <div class="igl-set-field">
                    <div class="igl-toggle">
                        <label class="toggle">
                            <input type="checkbox" name="faq_seccion_activo" id="faq_seccion_activo" value="1" <?php checked($activo, true); ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="status-label <?php echo $activo ? 'status-active' : 'status-inactive'; ?>" id="faq_seccion_status_label">
                            <?php echo $activo ? '✓ Activo - Visible en la web' : '✗ Inactivo - Oculto en la web'; ?>
                        </span>
                    </div>
                    <p class="hint" style="margin-top:10px;">Si está desactivada, toda la sección y sus preguntas no aparecerán.</p>
                </div>
            </div>

            <script>
            jQuery(function($) {
                $('#faq_seccion_activo').on('change', function() {
                    var label = $('#faq_seccion_status_label');
                    var icon = $(this).closest('.igl-set-card').find('h3 .dashicons');
                    if ($(this).is(':checked')) {
                        label.text('✓ Activo - Visible en la web').removeClass('status-inactive').addClass('status-active');
                        icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
                    } else {
                        label.text('✗ Inactivo - Oculto en la web').removeClass('status-active').addClass('status-inactive');
                        icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
                    }
                });
            });
            </script>
            <?php
        },
        'faq_seccion',
        'normal',
        'high'
    );
});

// Guardar FAQ meta
add_action('save_post_faq', function($post_id) {
    if (!isset($_POST['faq_meta_nonce']) || !wp_verify_nonce($_POST['faq_meta_nonce'], 'faq_meta')) {
        return;
    }
    if (isset($_POST['faq_descripcion'])) {
        update_post_meta($post_id, 'faq_descripcion', sanitize_textarea_field($_POST['faq_descripcion']));
    }
    if (isset($_POST['faq_seccion_id'])) {
        update_post_meta($post_id, 'faq_seccion_id', intval($_POST['faq_seccion_id']));
    }
    $activo = isset($_POST['faq_activo']) ? '1' : '0';
    update_post_meta($post_id, 'faq_activo', $activo);
});

// Guardar FAQ Sección meta
add_action('save_post_faq_seccion', function($post_id) {
    if (!isset($_POST['faq_seccion_meta_nonce']) || !wp_verify_nonce($_POST['faq_seccion_meta_nonce'], 'faq_seccion_meta')) {
        return;
    }
    $activo = isset($_POST['faq_seccion_activo']) ? '1' : '0';
    update_post_meta($post_id, 'faq_seccion_activo', $activo);
});

// =============================================
// HIMNO META BOXES
// =============================================
add_action('add_meta_boxes', function() {
    add_meta_box(
        'himno_info',
        'Información del Himno',
        function($post) {
            wp_nonce_field('himno_meta', 'himno_meta_nonce');

            $descripcion = get_post_meta($post->ID, 'himno_descripcion', true);
            $activo = get_post_meta($post->ID, 'himno_activo', true);
            $activo = ($activo === '' || $activo === '1') ? true : false;
            ?>
            <style>
            .igl-set-card { background:#fff; border:1px solid #dcdcde; border-radius:12px; padding:20px; margin-bottom:20px; }
            .igl-set-card:last-child { margin-bottom:0; }
            .igl-set-card h3 { margin:0 0 16px; padding-bottom:12px; border-bottom:2px solid #f0f0f1; font-size:14px; font-weight:700; color:#1d2327; display:flex; align-items:center; gap:8px; }
            .igl-set-card h3 .dashicons { color:#2271b1; }
            .igl-set-field { margin-bottom:14px; }
            .igl-set-field:last-child { margin-bottom:0; }
            .igl-set-field label { display:block; font-weight:600; margin-bottom:6px; color:#3c434a; font-size:13px; }
            .igl-set-field input[type="number"],
            .igl-set-field input[type="text"],
            .igl-set-field textarea { width:100%; border-radius:6px; border:1px solid #dcdcde; padding:8px 12px; font-size:14px; }
            .igl-set-field textarea { min-height:100px; resize:vertical; }
            .igl-set-field input:focus,
            .igl-set-field textarea:focus { border-color:#2271b1; outline:none; box-shadow:0 0 0 2px rgba(34,113,177,0.2); }
            .igl-set-field .hint { font-size:12px; color:#646970; margin-top:4px; }
            .igl-toggle { display:flex; align-items:center; gap:12px; }
            .igl-toggle .toggle { position:relative; width:50px; height:26px; flex-shrink:0; }
            .igl-toggle .toggle input { opacity:0; width:0; height:0; }
            .igl-toggle .slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:#ccc; transition:.3s; border-radius:26px; }
            .igl-toggle .slider:before { position:absolute; content:""; height:20px; width:20px; left:3px; bottom:3px; background-color:white; transition:.3s; border-radius:50%; }
            .igl-toggle .toggle input:checked + .slider { background-color:#2271b1; }
            .igl-toggle .toggle input:checked + .slider:before { transform:translateX(24px); }
            .igl-toggle .status-label { font-weight:600; font-size:13px; }
            .igl-toggle .status-active { color:#00a32a; }
            .igl-toggle .status-inactive { color:#646970; }
            </style>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-info"></span> Datos del Himno</h3>
                <div class="igl-set-field">
                    <label for="himno_descripcion">Descripción / Letra</label>
                    <textarea name="himno_descripcion" id="himno_descripcion" placeholder="Escribe la letra o descripción del himno..."><?php echo esc_textarea($descripcion); ?></textarea>
                    <p class="hint">Este texto se mostrará como contenido del himno.</p>
                </div>
            </div>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-sort"></span> Configuración</h3>
                <div class="igl-set-field">
                    <label for="himno_orden">Orden de aparición</label>
                    <input type="number" name="menu_order" id="himno_orden" value="<?php echo esc_attr($post->menu_order ?: 0); ?>" min="0">
                    <p class="hint">Número más bajo = aparece primero en la lista.</p>
                </div>
            </div>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-<?php echo $activo ? 'visibility' : 'hidden'; ?>"></span> Estado</h3>
                <div class="igl-set-field">
                    <div class="igl-toggle">
                        <label class="toggle">
                            <input type="checkbox" name="himno_activo" id="himno_activo" value="1" <?php checked($activo, true); ?>>
                            <span class="slider"></span>
                        </label>
                        <span class="status-label <?php echo $activo ? 'status-active' : 'status-inactive'; ?>" id="himno_status_label">
                            <?php echo $activo ? '✓ Activo - Visible en la web' : '✗ Inactivo - Oculto en la web'; ?>
                        </span>
                    </div>
                    <p class="hint" style="margin-top:10px;">Desactiva esta opción si no quieres que el himno aparezca en la página.</p>
                </div>
            </div>

            <script>
            jQuery(function($) {
                $('#himno_activo').on('change', function() {
                    var label = $('#himno_status_label');
                    var icon = $(this).closest('.igl-set-card').find('h3 .dashicons');
                    if ($(this).is(':checked')) {
                        label.text('✓ Activo - Visible en la web').removeClass('status-inactive').addClass('status-active');
                        icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
                    } else {
                        label.text('✗ Inactivo - Oculto en la web').removeClass('status-active').addClass('status-inactive');
                        icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
                    }
                });
            });
            </script>
            <?php
        },
        'himno',
        'normal',
        'high'
    );
});

// Guardar Himno meta
add_action('save_post_himno', function($post_id) {
    if (!isset($_POST['himno_meta_nonce']) || !wp_verify_nonce($_POST['himno_meta_nonce'], 'himno_meta')) {
        return;
    }
    if (isset($_POST['himno_descripcion'])) {
        update_post_meta($post_id, 'himno_descripcion', sanitize_textarea_field($_POST['himno_descripcion']));
    }
    $activo = isset($_POST['himno_activo']) ? '1' : '0';
    update_post_meta($post_id, 'himno_activo', $activo);
});

// Crear ministerios de ejemplo al activar el tema (solo si no existen)
add_action('after_switch_theme', function() {
    $count = wp_count_posts('ministerio');
    if ($count && $count->publish > 0) {
        return; // Ya existen ministerios, no crear de nuevo
    }

    $ministerios_sample = [
        [
            'title' => 'Ministerio de Alabanza',
            'descripcion' => 'El equipo de adoración que conduce a los congregantes a la presencia de Dios.',
            'order' => 1,
        ],
        [
            'title' => 'Ministerio Infantil',
            'descripcion' => 'Formamos niños con carácter y amor a Dios desde pequeños.',
            'order' => 2,
        ],
        [
            'title' => 'Ministerio de Oración',
            'descripcion' => 'Intercesores que sostienen a la iglesia en oración continua.',
            'order' => 3,
        ],
        [
            'title' => 'Ministerio de Mujeres',
            'descripcion' => 'Mujeres fuertes en Dios que se edifican y apoyan mutuamente.',
            'order' => 4,
        ],
        [
            'title' => 'Ministerio de Hombres',
            'descripcion' => 'Hombres de Dios que lideran en el hogar, la iglesia y la sociedad.',
            'order' => 5,
        ],
        [
            'title' => 'Ministerio de Jóvenes',
            'descripcion' => 'Jóvenes apasionados que viven su fe con autenticidad y energía.',
            'order' => 6,
        ],
    ];

    foreach ($ministerios_sample as $m) {
        $post_id = wp_insert_post([
            'post_type' => 'ministerio',
            'post_title' => $m['title'],
            'post_status' => 'publish',
            'menu_order' => $m['order'],
        ]);
        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, 'ministerio_descripcion', $m['descripcion']);
        }
    }

    // Crear Secciones FAQ y FAQs de ejemplo si no existen secciones
    $seccion_count = wp_count_posts('faq_seccion');
    if ($seccion_count && $seccion_count->publish > 0) {
        return; // Ya existen secciones
    }

    // Definir secciones con sus FAQs
    $secciones_faqs = [
        [
            'seccion' => ['title' => 'Conoce el Evangelio', 'order' => 1],
            'faqs' => [
                ['title' => '¿Qué enseña la Biblia?', 'content' => 'La Biblia es la Palabra de Dios que nos revela su plan de salvación para la humanidad. Enseña sobre el amor de Dios, la naturaleza del ser humano, el pecado y la redención por medio de Jesucristo. Es nuestra guía absoluta para la fe y la vida.', 'order' => 1],
                ['title' => '¿Qué es el pecado?', 'content' => 'El pecado es toda acción, pensamiento o actitud que desobedece la voluntad de Dios. Es la separación que existe entre nosotros y Dios. Todos hemos pecado (Romanos 3:23), pero Dios ofreció la solución a través de Su Hijo.', 'order' => 2],
                ['title' => '¿Por qué necesito a Jesucristo?', 'content' => 'Jesucristo es el Hijo de Dios que vino al mundo para salvarnos del pecado. Solo a través de Él podemos tener relación con Dios, perdón de nuestros pecados y la promesa de vida eterna.', 'order' => 3],
                ['title' => '¿Qué significa ser salvo?', 'content' => 'Ser salvo significa haber recibido el perdón de pecados y la vida eterna por medio de la fe en Jesucristo. Es nacer de nuevo, ser adoptado como hijo de Dios y tener la promesa de estar con Él eternamente.', 'order' => 4],
                ['title' => '¿Cómo puedo recibir la salvación?', 'content' => 'La salvación es un regalo de Dios que se recibe por gracia, mediante la fe en Jesucristo. Debes creer que Jesús es el Hijo de Dios, arrepentirte de tus pecados y confiar en Su sacrificio por ti. Ora y recibe a Jesús en tu corazón hoy.', 'order' => 5],
                ['title' => '¿Puedo acercarme a Dios aunque haya cometido muchos errores?', 'content' => '¡Sí! El amor de Dios es infinite y Su perdón está disponible para todos. No importa cuánto hayas fallado, Dios te recibe con brazos abiertos cuando te arrepientes y buscas Su rostro. Su gracia es suficiente para ti.', 'order' => 6],
            ],
        ],
        [
            'seccion' => ['title' => 'Conoce nuestra iglesia', 'order' => 2],
            'faqs' => [
                ['title' => '¿Puedo visitar la iglesia?', 'content' => '¡Por supuesto! Todas las personas son bienvenidas a nuestros cultos. No importa tu trasfondo religioso o situación actual, nuestra puerta está abierta para todos. Te esperamos con mucho cariño.', 'order' => 1],
                ['title' => '¿Cuáles son los horarios de los cultos?', 'content' => 'Martes 6:30pm - Culto de Oración | Miércoles 9:00am - Ayuno de Mujeres | Jueves 6:30pm - Culto General | Sábado 5:30am/3:00pm/6:30pm | Domingo 9:00am - Culto Dominical', 'order' => 2],
                ['title' => '¿Dónde está ubicada la iglesia?', 'content' => 'Estamos ubicados en 3 Calle Poniente, Barrio El Centro, La Palma, Chalatenango, El Salvador. Puedes visitarnos cualquier día de culto.', 'order' => 3],
                ['title' => '¿Cómo puedo comunicarme con la iglesia?', 'content' => 'Puedes contactarnos por teléfono, email o a través de nuestra página de contacto. También puedes seguirnos en redes sociales para estar al tanto de nuestras actividades.', 'order' => 4],
                ['title' => '¿Cómo puedo formar parte de la iglesia?', 'content' => 'Para formar parte de nuestra congregación, simplemente asiste a nuestros cultos y participa en las actividades. Nos puedes contactar para una conversación pastoral donde con gusto te orientaremos.', 'order' => 5],
                ['title' => '¿Puedo pedir oración?', 'content' => '¡Sí! Tenemos un equipo de oración disponible para interceder por tus necesidades. Puedes enviar tu petición de oración a través de nuestra página de contacto o hablar directamente con nuestros líderes.', 'order' => 6],
                ['title' => '¿Tienen actividades para niños y jóvenes?', 'content' => 'Sí, contamos con programas específicos para niños y jóvenes. Tenemos escuela dominical, ministerios especializados y actividades semanales diseñadas para cada grupo de edad.', 'order' => 7],
            ],
        ],
        [
            'seccion' => ['title' => 'Participa y sirve', 'order' => 3],
            'faqs' => [
                ['title' => '¿Cómo puedo servir en la iglesia?', 'content' => 'Hay muchas formas de servir: puedes unirte a algún ministerio (alabanza, oración, infantil, jóvenes, etc.), participar en equipos de accueil, o simplemente ofrecerte como voluntario según tus dones y habilidades.', 'order' => 1],
                ['title' => '¿Cómo puedo participar en los ministerios?', 'content' => 'Puedes unirte a cualquiera de nuestros ministerios acercándote a los líderes responsables. Cada ministerio tiene reuniones y actividades específicas. Contáctanos para más información.', 'order' => 2],
                ['title' => '¿Cómo puedo apoyar la obra de la iglesia?', 'content' => 'Puedes apoyar la obra de Dios mediante ofrendas, diezmos, o como voluntario en nuestras actividades. Cada contribución, ya sea financiera o de tiempo, es muy valiosa para el reino de Dios.', 'order' => 3],
                ['title' => '¿Cómo puedo hacer una ofrenda o donación?', 'content' => 'Puedes dar tu ofrenda durante los cultos presenciales, o visitar nuestra página de ofrenda donde encontrarás los métodos disponibles para hacerlo en línea o por transferencia.', 'order' => 4],
                ['title' => '¿Puedo colaborar como voluntario?', 'content' => '¡Sí! Valoramos mucho la participación voluntaria. Hay muchas áreas donde puedes ayudar. Contáctanos y cuéntanos cómo te gustaría servir.', 'order' => 5],
            ],
        ],
        [
            'seccion' => ['title' => 'Servicios y ministerios', 'order' => 4],
            'faqs' => [
                ['title' => '¿Dónde puedo conocer los próximos cultos y actividades?', 'content' => 'Puedes ver nuestro calendario de actividades en la página correspondiente, seguirnos en redes sociales, o preguntarle a cualquier miembro de la congregación.', 'order' => 1],
                ['title' => '¿Dónde puedo conocer nuestros ministerios?', 'content' => 'Visita nuestra página de Ministerios donde encontrarás información sobre cada uno de nuestros ministerios: Alabanza, Infantil, Oración, Mujeres, Hombres, Jóvenes y más.', 'order' => 2],
                ['title' => '¿Puedo ver los cultos en línea?', 'content' => 'Sí, transmitimos nuestros cultos en vivo por YouTube. Cuando hay transmisión en vivo, verás el botón "En Vivo" en la barra de navegación que te llevará directamente al stream.', 'order' => 3],
                ['title' => '¿Dónde puedo encontrar nuestras prédicas y enseñanzas?', 'content' => 'Puedes acceder a nuestros sermones y enseñanzas a través de la página de Sermones, donde encontrarás videos de nuestras prédicas organizados por pastor y fecha.', 'order' => 4],
                ['title' => '¿Dónde puedo encontrar información sobre eventos especiales?', 'content' => 'Publicamos todos nuestros eventos especiales en la página de Blog y en nuestras redes sociales. También puedes visitar la página de contacto para más información.', 'order' => 5],
            ],
        ],
    ];

    // Crear secciones y sus FAQs
    foreach ($secciones_faqs as $seccion_data) {
        // Crear sección
        $seccion_id = wp_insert_post([
            'post_type' => 'faq_seccion',
            'post_title' => $seccion_data['seccion']['title'],
            'post_status' => 'publish',
            'menu_order' => $seccion_data['seccion']['order'],
        ]);

        if ($seccion_id && !is_wp_error($seccion_id)) {
            update_post_meta($seccion_id, 'faq_seccion_activo', '1');

            // Crear FAQs de la sección
            foreach ($seccion_data['faqs'] as $faq_data) {
                $faq_id = wp_insert_post([
                    'post_type' => 'faq',
                    'post_title' => $faq_data['title'],
                    'post_status' => 'publish',
                    'menu_order' => $faq_data['order'],
                ]);

                if ($faq_id && !is_wp_error($faq_id)) {
                    update_post_meta($faq_id, 'faq_descripcion', $faq_data['content']);
                    update_post_meta($faq_id, 'faq_seccion_id', $seccion_id);
                    update_post_meta($faq_id, 'faq_activo', '1');
                }
            }
        }
    }

    // Crear himnos de ejemplo si no existen
    $himno_count = wp_count_posts('himno');
    if ($himno_count && $himno_count->publish > 0) {
        return;
    }

    $himnos_sample = [
        ['title' => 'A Dios Sea La Gloria', 'content' => "A Dios sea la gloria, grande cosas Él hizo,\nTan amó al mundo que a Su Hijo nos dio.\nQuien en Él creyere no será perdido,\nSi en fe a Jesucristo recibimos hoy.\n\nEstribillo:\nAlabad al Señor, alabad al Señor,\n¡Que todo el que cree a Sus pies se postrará!\nAlabad al Señor, alabad al Señor,\n¡Que todo el que cree a Sus pies se postrará!", 'order' => 1],
        ['title' => 'Qué Alegría Cuando Me Dijeron', 'content' => "Señor mi Dios, al contemplar los cielos,\nEl firmamento y las estrellas mil,\nAl oír tu voz en los poderosos truenos,\nY ver brillar al sol en su cenit.\n\nEstribillo:\n¡Mi corazón, oh Dios, entona su canción!\nQué grande es Él, qué grande es Él.\n¡Mi corazón, oh Dios, entona su canción!\nQué grande es Él, qué grande es Él.", 'order' => 2],
        ['title' => 'Santo, Santo, Santo', 'content' => "Santo, santo, santo, Señor omnipotente,\nSiempre el labio mío loores te dará.\nSanto, santo, santo, te adoro reverente,\nDios en tres personas, bendita Trinidad.\n\nSanto, santo, santo, la inmensa muchedumbre\nDe ángeles que cumplen tu santa voluntad,\nAnte Ti se postra, bañada de tu lumbre,\nAnte el mar de vidrio, coros de deidad.", 'order' => 3],
        ['title' => 'Grande Es Tu Fidelidad', 'content' => "Oh Dios eterno, tu misericordia\nNi una sombra de duda tendrá;\nTu compasión y bondad nunca fallan,\nY por los siglos el mismo serás.\n\nEstribillo:\nGrande es tu fidelidad, grande es tu fidelidad,\nMañana tras mañana nuevas misericordias veo;\nToda la gracia que necesito, tú me lo das,\n¡Grande es tu fidelidad, Señor, en mí!", 'order' => 4],
        ['title' => 'Castillo Fuerte Es Nuestro Dios', 'content' => "Castillo fuerte es nuestro Dios,\nDefensa y buen escudo;\nCon su poder nos libertará\nEn todo trance agudo.\nCon furia y con énfasis\nAcósanos Satán;\nPor armas deja ver\nAstucia y gran poder;\nCual él no hay en la tierra.\n\nNuestro valor es nada aquí,\nCon él todo es perdido;\nMas por nosotros pugnará\nDe Dios el escogido.", 'order' => 5],
        ['title' => 'Cristo Me Ama', 'content' => "Cristo me ama, bien lo sé,\nSu Palabra me hace ver\nQue los niños son de aquel\nQuien es nuestro amigo fiel.\n\nEstribillo:\nSí, Cristo me ama,\nSí, Cristo me ama,\nSí, Cristo me ama,\nLa Biblia dice así.", 'order' => 6],
        ['title' => 'Sublime Gracia', 'content' => "Sublime gracia del Señor\nQue a mí, pecador, salvó;\nFui ciego mas hoy veo yo,\nPerdido y Él me halló.\nSu gracia me enseñó a temer,\nMis dudas ahuyentó;\nOh, cuán precioso fue a mi ser\nCuando Él me transformó.", 'order' => 7],
        ['title' => 'Hay Poder en la Sangre', 'content' => "¿Quieres ser libre de la carga del mal?\nHay poder en la sangre del Cordero;\n¿Quieres sobrar, sobre el mundo triunfal?\nHay poder en la sangre del Cordero.\n\nEstribillo:\nHay poder, poder, sin igual poder,\nEn Jesús, quien murió;\nHay poder, poder, sin igual poder,\nEn la sangre que Él vertió.", 'order' => 8],
        ['title' => 'Al Mundo Paz', 'content' => "Al mundo paz, nació Jesús,\nHoy es el Rey;\nQue todo el mundo le reciba\nY goce en buena lid;\nY goce en buena lid,\nY goce, goce en buena lid.", 'order' => 9],
        ['title' => 'Firmes y Adelante', 'content' => "Firmes y adelante, huestes de la fe,\nSin temor alguno, que Jesús nos ve.\nJefe soberano, Cristo al frente va,\nY la regia enseña tremolando está.\n\nEstribillo:\nFirmes y adelante, huestes de la fe,\nSin temor alguno, que Jesús nos ve.", 'order' => 10],
        ['title' => 'Usa Mi Vida', 'content' => "Usa mi vida, usa mis manos,\nÚsalas para Ti Señor;\nUsa mi vida, usa mis labios,\nPara alabar tu gran amor.\n\nHazme un instrumento,\nHazme un instrumento,\nHazme un instrumento de Tu paz de Señor.", 'order' => 11],
        ['title' => 'El Señor Es Mi Pastor', 'content' => "El Señor es mi pastor, nada me faltará;\nEn lugares de delicados pastos me hará descansar;\nJunto a aguas de reposo me pastoreará,\nConfortará mi alma, por amor de Su nombre me guiará.", 'order' => 12],
    ];

    foreach ($himnos_sample as $h) {
        $post_id = wp_insert_post([
            'post_type' => 'himno',
            'post_title' => $h['title'],
            'post_status' => 'publish',
            'menu_order' => $h['order'],
        ]);
        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, 'himno_descripcion', $h['content']);
            update_post_meta($post_id, 'himno_activo', '1');
        }
    }
});

// =============================================
// CUSTOM POST TYPE: PREDICADOR
// =============================================
add_action('init', function() {
    $labels = [
        'name'               => 'Predicadores',
        'singular_name'      => 'Predicador',
        'menu_name'          => 'Predicadores',
        'add_new'            => 'Añadir Predicador',
        'add_new_item'       => 'Nuevo Predicador',
        'edit_item'          => 'Editar Predicador',
        'new_item'           => 'Nuevo',
        'view_item'          => 'Ver Predicador',
        'search_items'       => 'Buscar Predicadores',
        'not_found'          => 'No se encontraron predicadores',
        'not_found_in_trash' => 'No hay predicadores en la papelera',
    ];
    $args = [
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => ['slug' => 'predicador'],
        'supports'            => ['title', 'editor', 'thumbnail'],
        'menu_icon'           => 'dashicons-microphone',
        'show_in_rest'        => true,
        'show_in_menu'        => false, // Lo agregamos manualmente
        'menu_position'       => 25,
    ];
    register_post_type('predicador', $args);
});

// Meta boxes for Predicador – UI mejorada con previews y filas de video
add_action('add_meta_boxes', function() {
    add_meta_box(
        'predicador_info',
        '📋 Datos del Predicador',
        'iglesia_predicador_meta_box',
        'predicador',
        'normal',
        'high'
    );
});

function iglesia_predicador_meta_box($post) {
    wp_nonce_field('predicador_meta', 'predicador_meta_nonce');

    $rol     = get_post_meta($post->ID, '_iglesia_rol', true);
    $orden   = get_post_meta($post->ID, '_iglesia_orden', true) ?: '0';
    $foto_id = get_post_meta($post->ID, '_iglesia_foto_id', true);
    $foto_url = $foto_id ? wp_get_attachment_image_url($foto_id, 'medium') : '';
    $activo_pred = get_post_meta($post->ID, '_iglesia_predicador_activo', true);
    $activo_pred = ($activo_pred === '' || $activo_pred === '1') ? true : false;

    // Videos stored as JSON
    $videos_raw = get_post_meta($post->ID, '_iglesia_videos', true);
    $videos = [];
    if ($videos_raw) {
        $decoded = json_decode($videos_raw, true);
        if (is_array($decoded)) $videos = $decoded;
    }
    // Legacy: convert old \n format to JSON
    if (empty($videos) && $videos_raw && !is_array(json_decode($videos_raw, true))) {
        $lines = explode("\n", trim($videos_raw));
        foreach ($lines as $l) {
            $l = trim($l);
            if ($l) $videos[] = ['title' => '', 'url' => $l];
        }
    }
    $videos_json = esc_attr(json_encode($videos));
    ?>
    <style>
    /* ===== PREDICADOR META BOX UI — estándar Iglesia ===== */
    .predi-wrap { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .predi-card {
        background:#fff; border:1px solid #dcdcde; border-radius:12px;
        padding:20px; margin-bottom:20px;
    }
    .predi-card h3 {
        margin:0 0 16px; padding-bottom:12px; border-bottom:2px solid #f0f0f1;
        font-size:14px; font-weight:700; color:#1d2327; display:flex; align-items:center; gap:8px;
    }
    .predi-card h3 .dashicons { color:#2271b1; }
    .predi-field { margin-bottom:14px; }
    .predi-field:last-child { margin-bottom:0; }
    .predi-field label {
        display:block; font-weight:600; margin-bottom:6px; color:#3c434a;
        font-size:13px;
    }
    .predi-field input[type="text"],
    .predi-field input[type="number"],
    .predi-field textarea {
        width:100%; border-radius:6px; border:1px solid #dcdcde; padding:8px 12px; font-size:14px; background:#fff;
    }
    .predi-field input:focus,
    .predi-field textarea:focus {
        border-color:#2271b1; outline:none; box-shadow:0 0 0 2px rgba(34,113,177,0.2);
    }
    .predi-field .hint {
        font-size:12px; color:#646970; margin-top:4px;
    }

    /* Foto upload */
    .predi-foto-preview {
        width:160px; height:190px; border-radius:8px; border:2px dashed #c3c4c7;
        display:flex; align-items:center; justify-content:center;
        margin-bottom:10px; overflow:hidden; background:#f0f0f1;
        cursor:pointer; transition:border-color 0.2s;
    }
    .predi-foto-preview:hover { border-color:#2271b1; }
    .predi-foto-preview img { width:100%; height:100%; object-fit:cover; }
    .predi-foto-preview .placeholder {
        color:#8c8f94; font-size:13px; text-align:center; line-height:1.4;
    }
    .predi-foto-preview .placeholder small { display:block; font-size:11px; margin-top:4px; }
    .predi-foto-btns { display:flex; gap:8px; flex-wrap:wrap; }

    /* Video rows */
    .predi-video-list { list-style:none; margin:0; padding:0; }
    .predi-video-row {
        display:flex; gap:10px; align-items:start;
        background:#f6f7f7; border-radius:8px; padding:10px; margin-bottom:10px;
        border:1px solid #e2e4e7; position:relative;
    }
    .predi-video-thumb {
        width:100px; height:56px; border-radius:6px; overflow:hidden;
        flex-shrink:0; background:#ddd;
        display:flex; align-items:center; justify-content:center;
    }
    .predi-video-thumb img { width:100%; height:100%; object-fit:cover; }
    .predi-video-thumb .no-thumb {
        font-size:20px; color:#aaa;
    }
    .predi-video-fields { flex:1; display:flex; flex-direction:column; gap:6px; }
    .predi-video-fields input {
        width:100%; font-size:13px; border-radius:6px; border:1px solid #dcdcde; padding:6px 10px;
    }
    .predi-video-fields input:focus {
        border-color:#2271b1; outline:none; box-shadow:0 0 0 2px rgba(34,113,177,0.2);
    }
    .predi-video-row .btn-remove-video {
        flex-shrink:0; color:#b32d2e; cursor:pointer; margin-top:4px;
        border:none; background:none; font-size:18px; padding:2px 6px;
        border-radius:4px; line-height:1;
    }
    .predi-video-row .btn-remove-video:hover { background:#fdd; }
    .predi-video-msg { font-size:12px; color:#888; margin-top:2px; }
    .predi-video-msg.preview-ok { color:#00a32a; }
    .btn-add-video {
        display:inline-flex; align-items:center; gap:4px;
        margin-top:4px;
    }
    .predi-full { grid-column:1 / -1; }

    @media (max-width:850px) {
        .predi-wrap { grid-template-columns:1fr; }
    }
    </style>

    <div class="igl-info-box" style="grid-column:1 / -1;">
        <p><span class="dashicons dashicons-lightbulb"></span>El nombre del Predicador se configura en el campo "Título" de arriba. La biografía se escribe en el editor grande.</p>
    </div>

    <div class="predi-wrap">
        <!-- Columna izquierda: Datos basicos -->
        <div>
            <div class="predi-card">
                <h3><span class="dashicons dashicons-admin-users"></span> Información Básica</h3>
                <div class="predi-field">
                    <label for="iglesia_rol">Cargo / Rol</label>
                    <input type="text" id="iglesia_rol" name="iglesia_rol"
                           value="<?php echo esc_attr($rol); ?>"
                           placeholder="Ej: Pastor Principal, Evangelista, Maestro...">
                    <p class="hint">Aparecerá debajo del nombre en las tarjetas y páginas.</p>
                </div>
                <div class="predi-field">
                    <label for="iglesia_orden">Orden de aparición</label>
                    <input type="number" id="iglesia_orden" name="iglesia_orden"
                           value="<?php echo esc_attr($orden); ?>"
                           style="width:90px;" min="0" step="1">
                    <p class="hint">Número más bajo = aparece primero en la lista.</p>
                </div>
            </div>

            <div class="predi-card">
                <h3><span class="dashicons dashicons-format-image"></span> Foto del Predicador</h3>
                <div class="predi-foto-preview" id="predi-foto-preview" onclick="document.getElementById('predi-foto-btn').click()">
                    <?php if ($foto_url): ?>
                        <img src="<?php echo esc_url($foto_url); ?>" alt="Foto">
                    <?php else: ?>
                        <div class="placeholder">📷<small>Click para subir</small></div>
                    <?php endif; ?>
                </div>
                <input type="hidden" id="iglesia_foto_id" name="iglesia_foto_id" value="<?php echo esc_attr($foto_id); ?>">
                <div class="predi-foto-btns">
                    <button type="button" class="button button-primary" id="predi-foto-btn">
                        <span class="dashicons dashicons-admin-media" style="font-size:14px;width:14px;height:14px;margin:4px 2px 0 0;"></span>
                        Seleccionar foto
                    </button>
                    <?php if ($foto_id): ?>
                    <button type="button" class="button button-link-delete" id="predi-foto-remove">Quitar foto</button>
                    <?php endif; ?>
                </div>
                <p class="hint" style="margin-top:10px;">
                    Recomendado: retrato vertical 400x500px.<br>
                    Sin foto se muestra un ícono placeholder en las tarjetas.
                </p>
            </div>
        </div>

        <!-- Columna derecha: Videos -->
        <div>
            <div class="predi-card">
                <h3><span class="dashicons dashicons-video-alt3"></span> Sermones en Video (YouTube)</h3>
                <p class="hint" style="margin-bottom:12px;">
                    Copia la URL del video de YouTube y pégala aquí.
                    Pueden ser enlaces como:
                    <code>https://www.youtube.com/watch?v=xxxxx</code> o
                    <code>https://youtu.be/xxxxx</code>
                </p>

                <ul class="predi-video-list" id="predi-video-list">
                    <!-- filled by JS -->
                </ul>

                <button type="button" class="button btn-add-video" id="btn-add-video">
                    <span class="dashicons dashicons-plus-alt2" style="font-size:16px;width:16px;height:16px;"></span>
                    Añadir video
                </button>
            </div>
        </div>

        <!-- Biografia ocupa full width debajo -->
        <div class="predi-full predi-card">
            <h3><span class="dashicons dashicons-edit-page"></span> Biografía</h3>
            <p class="hint" style="margin-bottom:8px;">
                Escribe la biografía aquí o usa el editor principal de arriba. Ambos se combinarán en la página pública.
            </p>
            <?php
            // We use the main WP editor for content; this is just a reminder.
            ?>
            <p style="color:#646970;font-size:12px;">
                ✏️ El contenido principal (arriba) y el extracto se usarán para mostrar la biografía del predicador.
            </p>
        </div>
    </div>

    <?php iglesia_render_toggle_box('_iglesia_predicador_activo', $activo_pred); ?>

    <!-- Hidden input to store videos JSON -->
    <input type="hidden" id="iglesia_videos" name="iglesia_videos" value="<?php echo esc_attr(json_encode($videos)); ?>">

    <script>
    jQuery(function($) {

        // ===== FOTO UPLOAD =====
        var fotoFrame;
        $('#predi-foto-btn').on('click', function(e) {
            e.preventDefault();
            if (fotoFrame) { fotoFrame.open(); return; }
            fotoFrame = wp.media({ title:'Seleccionar foto del predicador', button:{text:'Usar esta foto'}, multiple:false });
            fotoFrame.on('select', function() {
                var attachment = fotoFrame.state().get('selection').first().toJSON();
                $('#iglesia_foto_id').val(attachment.id);
                $('#predi-foto-preview').html('<img src="'+attachment.sizes.medium.url+'" alt="Foto">');
                if (!$('#predi-foto-remove').length) {
                    $('.predi-foto-btns').append('<button type="button" class="button button-link-delete" id="predi-foto-remove">Quitar foto</button>');
                }
            });
            fotoFrame.open();
        });
        $(document).on('click', '#predi-foto-remove', function() {
            $('#iglesia_foto_id').val('');
            $('#predi-foto-preview').html('<div class="placeholder">📷<small>Click para subir</small></div>');
            $(this).remove();
        });

        // ===== VIDEOS =====
        var $list   = $('#predi-video-list');
        var $hidden = $('#iglesia_videos');
        var videos  = $hidden.val() ? JSON.parse($hidden.val()) : [];

        function renderVideos() {
            $list.empty();
            if (!videos.length) {
                $list.html('<li style="padding:20px;text-align:center;color:#999;border:2px dashed #dcdcde;border-radius:6px;">📹 No hay videos aún. Haz clic en <strong>"Añadir video"</strong> para agregar uno.</li>');
                return;
            }
            videos.forEach(function(v, i) {
                var thumb = getYtThumb(v.url);
                var $row = $('<li class="predi-video-row" data-index="'+i+'">'+
                    '<div class="predi-video-thumb">'+(thumb
                        ? '<img src="'+thumb+'" alt="">'
                        : '<span class="no-thumb">▶</span>')+
                    '</div>'+
                    '<div class="predi-video-fields">'+
                        '<input type="text" class="video-url" value="'+escAttr(v.url)+'" placeholder="Pega aquí la URL de YouTube" data-index="'+i+'">'+
                        '<span class="predi-video-msg'+(isValidYt(v.url)?' preview-ok':'')+'">'+(isValidYt(v.url)?'✓ Vista previa generada':(!v.url?'':'⚠ URL no reconocida'))+'</span>'+
                        '<input type="text" class="video-title" value="'+escAttr(v.title||'')+'" placeholder="Título del sermón (opcional)" data-index="'+i+'">'+
                    '</div>'+
                    '<button type="button" class="btn-remove-video" title="Eliminar video" data-index="'+i+'">✕</button>'+
                '</li>');
                $list.append($row);
            });
        }

        function saveVideos() {
            $list.find('.predi-video-row').each(function() {
                var idx = parseInt($(this).attr('data-index'));
                if (idx >= 0 && idx < videos.length) {
                    videos[idx].url   = $(this).find('.video-url').val().trim();
                    videos[idx].title = $(this).find('.video-title').val().trim();
                }
            });
            $hidden.val(JSON.stringify(videos));
        }

        function refreshThumbs() {
            $list.find('.predi-video-row').each(function() {
                var idx = parseInt($(this).attr('data-index'));
                var url = $(this).find('.video-url').val().trim();
                if (videos[idx]) videos[idx].url = url;
                var thumbDiv = $(this).find('.predi-video-thumb');
                var msgSpan  = $(this).find('.predi-video-msg');
                var t = getYtThumb(url);
                if (t) {
                    thumbDiv.html('<img src="'+t+'" alt="">');
                    msgSpan.text('✓ Vista previa generada').addClass('preview-ok');
                } else {
                    thumbDiv.html('<span class="no-thumb">▶</span>');
                    msgSpan.text(url ? '⚠ URL no reconocida' : '').removeClass('preview-ok');
                }
            });
            saveVideos();
        }

        function getYtThumb(url) {
            var m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
            return m ? 'https://img.youtube.com/vi/'+m[1]+'/hqdefault.jpg' : '';
        }
        function isValidYt(url) {
            return /(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/.test(url);
        }
        function escAttr(s) { return (s||'').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

        // Add video
        $('#btn-add-video').on('click', function() {
            videos.push({title:'', url:''});
            renderVideos();
        });

        // Remove video
        $list.on('click', '.btn-remove-video', function() {
            var idx = parseInt($(this).attr('data-index'));
            videos.splice(idx, 1);
            saveVideos();
            renderVideos();
        });

        // URL change -> update thumb
        $list.on('blur change', '.video-url', function() {
            refreshThumbs();
        });
        $list.on('blur change', '.video-title', function() {
            saveVideos();
        });

        renderVideos();
    });
    </script>
    <?php
}

add_action('save_post_predicador', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['predicador_meta_nonce'])) return;
    if (!wp_verify_nonce($_POST['predicador_meta_nonce'], 'predicador_meta')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['iglesia_rol'])) {
        update_post_meta($post_id, '_iglesia_rol', sanitize_text_field($_POST['iglesia_rol']));
    }
    if (isset($_POST['iglesia_orden'])) {
        update_post_meta($post_id, '_iglesia_orden', intval($_POST['iglesia_orden']));
    }
    if (isset($_POST['iglesia_foto_id'])) {
        update_post_meta($post_id, '_iglesia_foto_id', intval($_POST['iglesia_foto_id']));
    }
    update_post_meta($post_id, '_iglesia_predicador_activo', isset($_POST['_iglesia_predicador_activo']) ? '1' : '0');
    // Videos now stored as JSON with title+url per row
    if (isset($_POST['iglesia_videos'])) {
        $raw = wp_unslash($_POST['iglesia_videos']);
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            // Filter out empty URLs
            $clean = [];
            foreach ($decoded as $v) {
                if (!empty(trim($v['url']))) {
                    $clean[] = [
                        'title' => sanitize_text_field($v['title'] ?? ''),
                        'url'   => esc_url_raw(trim($v['url'])),
                    ];
                }
            }
            update_post_meta($post_id, '_iglesia_videos', wp_json_encode($clean));
        }
    }
});

/**
 * Meta query estándar para campos de estado activo/inactivo.
 *
 * Convención del portal: un registro SIN el meta se considera ACTIVO
 * (retrocompatibilidad con registros creados antes del toggle).
 * Importante: con `compare => IN` los posts SIN meta quedan excluidos
 * (JOIN de postmeta), por eso se necesita NOT EXISTS + OR.
 */
function iglesia_meta_activa($meta_key) {
    return [
        'relation' => 'OR',
        ['key' => $meta_key, 'compare' => 'NOT EXISTS'],
        ['key' => $meta_key, 'value' => '1', 'compare' => '='],
    ];
}

// Helper: Get all preachers ordered (solo activos; sin meta = activo)
function iglesia_get_predicadores() {
    return get_posts([
        'post_type'      => 'predicador',
        'posts_per_page' => 50,
        'meta_key'       => '_iglesia_orden',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
        'meta_query'     => iglesia_meta_activa('_iglesia_predicador_activo'),
    ]);
}

// Helper: Get photo URL for a predicador (priority: meta field > featured image > fallback)
function iglesia_get_predicador_foto($post_id) {
    $foto_id = get_post_meta($post_id, '_iglesia_foto_id', true);
    if ($foto_id && $url = wp_get_attachment_image_url($foto_id, 'medium')) {
        return $url;
    }
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, 'medium');
    }
    return '';
}

// Helper: Parse YouTube URL to embed URL
function iglesia_youtube_embed($url) {
    $url = trim($url);
    if (empty($url)) return '';

    // Already embed URL
    if (strpos($url, 'youtube.com/embed/') !== false) return $url;

    // youtube.com/watch?v=XXX
    if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    // youtu.be/XXX
    if (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    return $url;
}

// Helper: Get videos for a predicador as array of {title, embed_url}
function iglesia_get_predicador_videos($post_id) {
    $raw = get_post_meta($post_id, '_iglesia_videos', true);
    if (empty($raw)) return [];
    $data = json_decode($raw, true);
    if (!is_array($data)) return [];
    $videos = [];
    foreach ($data as $v) {
        $url = $v['url'] ?? '';
        if (empty(trim($url))) continue;
        $videos[] = [
            'title'     => $v['title'] ?? '',
            'embed_url' => iglesia_youtube_embed($url),
        ];
    }
    return $videos;
}

// Add custom image sizes
add_action('after_setup_theme', function() {
    add_image_size('iglesia-medium', 600, 400, true);
    add_image_size('iglesia-large', 1200, 675, true);
}, 20);

// Register Custom Sidebar (Footer Widget Area)
add_action('widgets_init', function() {
    register_sidebar([
        'name'          => 'Área de Widgets Pie',
        'id'            => 'footer-sidebar',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ]);
});

// Page Banner Helper
function iglesia_page_banner($title, $bg = '') {
    $style = $bg ? ' style="background-image: url(' . esc_url($bg) . '); background-size:cover; background-position:center;"' : '';
    echo '<div class="page-banner"' . $style . '>';
    echo '<div class="bg-overlay"></div>';
    echo '<div class="container"><h1>' . esc_html($title) . '</h1>';
    echo '<p class="breadcrumb"><a href="' . home_url('/') . '">Inicio</a> &rsaquo; ' . esc_html($title) . '</p>';
    echo '</div></div>';
}

// Helper for reading correct template based on page title/slug
function iglesia_get_page_template_part($slug) {
    $template = locate_template('page-' . $slug . '.php');
    if ($template) {
        include $template;
    } else {
        the_content();
    }
}

// Template routing for custom pages
add_filter('template_include', function($template) {
    if (is_page()) {
        global $post;
        $slug = $post->post_name;
        $custom_template = locate_template('page-' . $slug . '.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
}, 99);

// Contact Form REST API Endpoint
add_action('rest_api_init', function() {
    register_rest_route('iglesia/v1', '/contact', [
        'methods'  => 'POST',
        'callback' => 'iglesia_handle_contact_form',
        'permission_callback' => '__return_true',
    ]);
});

function iglesia_handle_contact_form($request) {
    $name = sanitize_text_field($request->get_param('nombre'));
    $email = sanitize_email($request->get_param('email'));
    $subject = sanitize_text_field($request->get_param('asunto'));
    $message = sanitize_textarea_field($request->get_param('mensaje'));

    $errors = [];

    if (empty($name)) {
        $errors[] = 'El nombre es requerido.';
    }
    if (empty($email) || !is_email($email)) {
        $errors[] = 'Un correo electrónico válido es requerido.';
    }
    if (empty($message)) {
        $errors[] = 'El mensaje es requerido.';
    }

    if (!empty($errors)) {
        return new WP_REST_Response([
            'success' => false,
            'errors' => $errors,
        ], 400);
    }

    // Build email content
    $email_subject = $subject ? "[Portal Web TACAD] $subject" : "[Portal Web TACAD] Nuevo mensaje de contacto";
    $email_body = "Nombre: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Asunto: $subject\n\n";
    $email_body .= "Mensaje:\n$message\n";
    $email_body .= "\n---\nEnviado desde el formulario de contacto del Portal Web TACAD.";

    $to = 'iglesia@portaliglesia.com';
    $headers = ["From: $name <$email>", "Reply-To: $email", 'Content-Type: text/plain; charset=UTF-8'];

    // Try to send email, but don't fail if mail server isn't configured
    $mail_sent = false;
    if (strpos(get_site_url(), 'localhost') === false) {
        $mail_sent = wp_mail($to, $email_subject, $email_body, $headers);
    }

    // Log the message regardless (for local development)
    $log_entry = date('[Y-m-d H:i:s]') . " Contact Form Submission\n";
    $log_entry .= "Name: $name\n";
    $log_entry .= "Email: $email\n";
    $log_entry .= "Subject: $subject\n";
    $log_entry .= "Message: $message\n";
    $log_entry .= "Email sent: " . ($mail_sent ? 'Yes' : 'No (local dev)') . "\n";
    $log_entry .= "---\n";

    $log_file = get_template_directory() . '/contact-log.txt';
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);

    return new WP_REST_Response([
        'success' => true,
        'message' => '¡Gracias por contactarnos! Te responderemos pronto.',
        'email_sent' => $mail_sent,
    ], 200);
}

// =============================================
// CONFIGURACION DE IGLESIA — Admin Page
// =============================================
add_action('admin_menu', function() {
    add_menu_page(
        'Configuración Iglesia',
        'Iglesia',
        'edit_posts',
        'iglesia-settings',
        'iglesia_settings_page',
        'dashicons-building',
        28
    );
});

add_action('admin_enqueue_scripts', function($hook) {
    // Media uploader en cualquier pantalla de edición de CPTs que usan imagen
    if (in_array($hook, ['post.php', 'post-new.php'], true)) {
        $screen = get_current_screen();
        $media_cpts = ['ministerio', 'predicador', 'historia', 'imagen', 'carrusel_slide', 'post'];
        if ($screen && in_array($screen->post_type, $media_cpts, true)) {
            wp_enqueue_media();
        }
    }
    if ($hook === 'toplevel_page_iglesia-settings' || $hook === 'iglesia_page_iglesia-settings') {
        wp_enqueue_media();
    }
});

// =============================================
// CSS ADMIN CENTRALIZADO — Estándar "Iglesia"
// Todos los mantenimientos usan estas clases (.igl-set-card, .igl-set-field,
// .igl-toggle) para una experiencia visual consistente en /admin.
// =============================================
add_action('admin_head', function() {
    $screens = get_current_screen();
    if (!$screens && !isset($_GET['page'])) return;
    ?>
    <style>
    /* Tarjeta estándar (patrón Iglesia) */
    .igl-set-card { background:#fff; border:1px solid #dcdcde; border-radius:12px; padding:20px; margin-bottom:20px; }
    .igl-set-card:last-child { margin-bottom:0; }
    .igl-set-card h2, .igl-set-card h3 {
        margin:0 0 16px; padding-bottom:12px; border-bottom:2px solid #f0f0f1;
        font-size:14px; font-weight:700; color:#1d2327; display:flex; align-items:center; gap:8px;
    }
    .igl-set-card h2 .dashicons, .igl-set-card h3 .dashicons { color:#2271b1; }
    /* Campos estándar */
    .igl-set-field { margin-bottom:14px; }
    .igl-set-field:last-child { margin-bottom:0; }
    .igl-set-field label { display:block; font-weight:600; margin-bottom:6px; color:#3c434a; font-size:13px; }
    .igl-set-field input[type="text"],
    .igl-set-field input[type="number"],
    .igl-set-field input[type="url"],
    .igl-set-field select,
    .igl-set-field textarea { width:100%; border-radius:6px; border:1px solid #dcdcde; padding:8px 12px; font-size:14px; background:#fff; }
    .igl-set-field textarea { min-height:100px; resize:vertical; }
    .igl-set-field input:focus,
    .igl-set-field select:focus,
    .igl-set-field textarea:focus { border-color:#2271b1; outline:none; box-shadow:0 0 0 2px rgba(34,113,177,0.2); }
    .igl-set-field .hint { font-size:12px; color:#646970; margin-top:4px; }
    /* Toggle Activo/Inactivo estándar */
    .igl-toggle { display:flex; align-items:center; gap:12px; }
    .igl-toggle .toggle { position:relative; width:50px; height:26px; flex-shrink:0; }
    .igl-toggle .toggle input { opacity:0; width:0; height:0; }
    .igl-toggle .slider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background-color:#ccc; transition:.3s; border-radius:26px; }
    .igl-toggle .slider:before { position:absolute; content:""; height:20px; width:20px; left:3px; bottom:3px; background-color:white; transition:.3s; border-radius:50%; }
    .igl-toggle .toggle input:checked + .slider { background-color:#2271b1; }
    .igl-toggle .toggle input:checked + .slider:before { transform:translateX(24px); }
    .igl-toggle .status-label { font-weight:600; font-size:13px; }
    .igl-toggle .status-active { color:#00a32a; }
    .igl-toggle .status-inactive { color:#646970; }
    /* Selector de imagen estándar */
    .igl-img-preview { width:100%; max-width:340px; height:170px; background:#f0f0f1; border:2px dashed #c3c4c7; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer; overflow:hidden; margin-bottom:10px; }
    .igl-img-preview img { width:100%; height:100%; object-fit:cover; }
    .igl-img-preview .placeholder { color:#8c8f94; font-size:13px; text-align:center; }
    .igl-img-preview .placeholder .dashicons { font-size:42px; width:42px; height:42px; }
    .igl-info-box { background:#f0f0f1; border-radius:8px; padding:15px; margin-bottom:20px; }
    .igl-info-box p { margin:0; font-size:13px; color:#3c434a; }
    .igl-info-box .dashicons { color:#2271b1; margin-right:8px; vertical-align:middle; }
    /* Columnas de listado estándar */
    .column-igl_estado .badge-active,
    .badge-active { display:inline-block; padding:3px 10px; border-radius:12px; font-size:11px; font-weight:600; background:#edfaef; color:#00a32a; }
    .column-igl_estado .badge-inactive,
    .badge-inactive { display:inline-block; padding:3px 10px; border-radius:12px; font-size:11px; font-weight:600; background:#f0f0f1; color:#646970; }
    .column-thumb img { width:60px; height:45px; object-fit:cover; border-radius:4px; }
    .column-menu_order { width:70px; }
    /* Compatibilidad con metas legacy (ministerio/predicador) usando mismo estilo base */
    .min-meta-wrap, .predi-wrap { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    @media (max-width:782px) { .min-meta-wrap, .predi-wrap { grid-template-columns:1fr; } }
    </style>
    <?php
});

function iglesia_settings_page() {
    // Save handler
    if (isset($_POST['iglesia_save']) && wp_verify_nonce($_POST['_iglesia_nonce'], 'iglesia_save_settings')) {
        $fields = ['iglesia_nombre','iglesia_direccion','iglesia_telefono','iglesia_email',
                   'iglesia_horarios','iglesia_instagram','iglesia_facebook','iglesia_youtube'];
        foreach ($fields as $f) {
            if (isset($_POST[$f])) {
                update_option($f, sanitize_text_field($_POST[$f]));
            }
        }
        // Horarios can be textarea
        if (isset($_POST['iglesia_horarios'])) {
            update_option('iglesia_horarios', sanitize_textarea_field($_POST['iglesia_horarios']));
        }
        // Logo ID
        if (isset($_POST['iglesia_logo_id'])) {
            update_option('iglesia_logo_id', intval($_POST['iglesia_logo_id']));
        }
        do_action('iglesia_settings_saved');
        echo '<div class="notice notice-success is-dismissible"><p>Configuración guardada correctamente.</p></div>';
    }

    $logo_id = get_option('iglesia_logo_id', '');
    $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : '';
    ?>
    <style>
    .igl-set-wrap { max-width:900px; margin:20px 0; }
    .igl-set-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .igl-set-card {
        background:#fff; border:1px solid #c3c4c7; border-radius:6px;
        padding:20px 24px; box-shadow:0 1px 3px rgba(0,0,0,0.04);
    }
    .igl-set-card h2 {
        margin:0 0 16px; padding-bottom:10px; border-bottom:2px solid #f0f0f1;
        font-size:15px; color:#1d2327; display:flex; align-items:center; gap:8px;
    }
    .igl-set-card h2 .dashicons { color:#2271b1; }
    .igl-set-field { margin-bottom:14px; }
    .igl-set-field:last-child { margin-bottom:0; }
    .igl-set-field label {
        display:block; font-weight:600; margin-bottom:4px; color:#3c434a; font-size:13px;
    }
    .igl-set-field input[type="text"],
    .igl-set-field textarea {
        width:100%; border-radius:4px; border:1px solid #8c8f94;
    }
    .igl-set-field textarea { min-height:90px; }
    .igl-set-field .hint { font-size:12px; color:#757575; margin-top:3px; }
    .igl-logo-preview {
        width:100px; height:60px; border-radius:6px; border:2px dashed #c3c4c7;
        display:flex; align-items:center; justify-content:center;
        margin-bottom:8px; overflow:hidden; background:#f9f9f9;
    }
    .igl-logo-preview img { width:100%; height:100%; object-fit:contain; }
    .igl-logo-preview .placeholder { text-align:center; color:#999; font-size:24px; }
    .igl-logo-preview .placeholder small { display:block; font-size:9px; margin-top:2px; }
    .igl-set-full { grid-column:1 / -1; }
    .igl-set-submit { margin-top:20px; padding-top:16px; border-top:1px solid #c3c4c7; text-align:right; }
    @media (max-width:782px) { .igl-set-grid { grid-template-columns:1fr; } }
    </style>

    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:10px;">
            <span class="dashicons dashicons-building" style="font-size:28px;width:28px;height:28px;color:#2271b1;"></span>
            Configuración de la Iglesia
        </h1>
        <p style="color:#646970;margin-bottom:20px;">Administra el logo, nombre, datos de contacto, horarios y redes sociales. Estos datos se reflejan en todo el sitio web.</p>

        <form method="post" class="igl-set-wrap">
            <?php wp_nonce_field('iglesia_save_settings', '_iglesia_nonce'); ?>

            <div class="igl-set-grid">
                <!-- Datos Principales -->
                <div class="igl-set-card">
                    <h2><span class="dashicons dashicons-admin-home"></span> Identidad</h2>
                    <div class="igl-set-field">
                        <label>Logo de la Iglesia</label>
                        <div class="igl-logo-preview" id="igl-logo-preview">
                            <?php if ($logo_url): ?>
                                <img src="<?php echo esc_url($logo_url); ?>" alt="Logo">
                            <?php else: ?>
                                <div class="placeholder">🏛<small>Subir</small></div>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" id="iglesia_logo_id" name="iglesia_logo_id" value="<?php echo esc_attr($logo_id); ?>">
                        <button type="button" class="button" id="igl-logo-btn">Seleccionar logo</button>
                        <?php if ($logo_id): ?>
                        <button type="button" class="button button-link-delete" id="igl-logo-remove">Quitar</button>
                        <?php endif; ?>
                        <p class="hint">Aparece en la barra de navegación superior. PNG o JPG, preferiblemente horizontal.</p>
                    </div>
                    <div class="igl-set-field">
                        <label for="iglesia_nombre">Nombre de la Iglesia</label>
                        <input type="text" id="iglesia_nombre" name="iglesia_nombre" value="<?php echo esc_attr(get_option('iglesia_nombre', '')); ?>" placeholder="Ej: Tabernáculo Cristiano Asambleas de Dios">
                        <p class="hint">Nombre completo que aparece en el hero, footer y metadatos del sitio.</p>
                    </div>
                </div>

                <!-- Redes Sociales -->
                <div class="igl-set-card">
                    <h2><span class="dashicons dashicons-share"></span> Redes Sociales</h2>
                    <div class="igl-set-field">
                        <label for="iglesia_instagram">Instagram</label>
                        <input type="text" id="iglesia_instagram" name="iglesia_instagram" value="<?php echo esc_attr(get_option('iglesia_instagram', '')); ?>" placeholder="https://instagram.com/tuiglesia">
                    </div>
                    <div class="igl-set-field">
                        <label for="iglesia_facebook">Facebook</label>
                        <input type="text" id="iglesia_facebook" name="iglesia_facebook" value="<?php echo esc_attr(get_option('iglesia_facebook', '')); ?>" placeholder="https://facebook.com/tuiglesia">
                    </div>
                    <div class="igl-set-field">
                        <label for="iglesia_youtube">YouTube</label>
                        <input type="text" id="iglesia_youtube" name="iglesia_youtube" value="<?php echo esc_attr(get_option('iglesia_youtube', '')); ?>" placeholder="https://youtube.com/@tuiglesia">
                    </div>
                    <p class="hint">Estos enlaces aparecen en los iconos de la barra de navegación y el footer.</p>
                </div>

                <!-- Contacto -->
                <div class="igl-set-card">
                    <h2><span class="dashicons dashicons-location"></span> Contacto</h2>
                    <div class="igl-set-field">
                        <label for="iglesia_direccion">Dirección</label>
                        <input type="text" id="iglesia_direccion" name="iglesia_direccion" value="<?php echo esc_attr(get_option('iglesia_direccion', '')); ?>" placeholder="Ej: 3 Calle Poniente, Barrio El Centro, La Palma, Chalatenango">
                    </div>
                    <div class="igl-set-field">
                        <label for="iglesia_telefono">Teléfono</label>
                        <input type="text" id="iglesia_telefono" name="iglesia_telefono" value="<?php echo esc_attr(get_option('iglesia_telefono', '')); ?>" placeholder="(503) 0000-0000">
                    </div>
                    <div class="igl-set-field">
                        <label for="iglesia_email">Correo Electrónico</label>
                        <input type="text" id="iglesia_email" name="iglesia_email" value="<?php echo esc_attr(get_option('iglesia_email', '')); ?>" placeholder="iglesia@ejemplo.com">
                    </div>
                    <p class="hint">Se muestran en la página de Contacto, el footer y la sección del mapa.</p>
                </div>

                <!-- Horarios -->
                <div class="igl-set-card">
                    <h2><span class="dashicons dashicons-clock"></span> Horarios de Culto</h2>
                    <div class="igl-set-field">
                        <label for="iglesia_horarios">Horarios</label>
                        <textarea id="iglesia_horarios" name="iglesia_horarios" placeholder="Escribe cada horario en una linea.&#10;Ej: Domingo: Culto General — 9:00am"><?php echo esc_textarea(get_option('iglesia_horarios', '')); ?></textarea>
                        <p class="hint">Una línea por cada día/horario. Se mostrarán en la página de Contacto y en el mapa del inicio.</p>
                    </div>
                </div>
            </div>

            <div class="igl-set-submit">
                <button type="submit" name="iglesia_save" class="button button-primary button-hero">
                    <span class="dashicons dashicons-yes-alt" style="margin:3px 4px 0 0;"></span>
                    Guardar Configuración
                </button>
            </div>
        </form>
    </div>

    <script>
    jQuery(function($) {
        var logoFrame;
        $('#igl-logo-btn').on('click', function(e) {
            e.preventDefault();
            if (logoFrame) { logoFrame.open(); return; }
            logoFrame = wp.media({title:'Seleccionar logo', button:{text:'Usar como logo'}, multiple:false});
            logoFrame.on('select', function() {
                var att = logoFrame.state().get('selection').first().toJSON();
                $('#iglesia_logo_id').val(att.id);
                $('#igl-logo-preview').html('<img src="'+att.sizes.medium.url+'" alt="Logo">');
                if (!$('#igl-logo-remove').length) {
                    $('#igl-logo-btn').after('<button type="button" class="button button-link-delete" id="igl-logo-remove">Quitar</button>');
                }
            });
            logoFrame.open();
        });
        $(document).on('click', '#igl-logo-remove', function() {
            $('#iglesia_logo_id').val('');
            $('#igl-logo-preview').html('<div class="placeholder">🏛<small>Subir</small></div>');
            $(this).remove();
        });
    });
    </script>
    <?php
}

// =============================================
// HELPERS — Obtener datos de configuracion
// =============================================
function iglesia_conf($key, $default = '') {
    return get_option($key, $default);
}

function iglesia_logo_url() {
    $id = get_option('iglesia_logo_id');
    if ($id) {
        $url = wp_get_attachment_image_url($id, 'full');
        if ($url) return $url;
    }
    // Fallback to file-based logo
    $path = get_template_directory() . '/images/logo.jpeg';
    return file_exists($path) ? get_template_directory_uri() . '/images/logo.jpeg' : '';
}

function iglesia_nombre() {
    return iglesia_conf('iglesia_nombre', get_bloginfo('name'));
}

function iglesia_direccion() {
    return iglesia_conf('iglesia_direccion');
}

function iglesia_telefono() {
    return iglesia_conf('iglesia_telefono');
}

function iglesia_email() {
    return iglesia_conf('iglesia_email');
}

function iglesia_horarios() {
    return iglesia_conf('iglesia_horarios');
}

function iglesia_instagram() {
    return iglesia_conf('iglesia_instagram');
}

function iglesia_facebook() {
    return iglesia_conf('iglesia_facebook');
}

function iglesia_youtube() {
    return iglesia_conf('iglesia_youtube');
}

// =============================================
// ROLES DE USUARIO — Administrador / Editor / Colaborador / Pastor
// =============================================
add_action('init', function() {
    // Pastor: solo lectura + acceso a Dashboard/Estadísticas
    if (!get_role('pastor')) {
        add_role('pastor', 'Pastor', ['read' => true]);
    }
    // Colaborador: flujo editorial de Blog (borrador → revisión → publicación)
    if (!get_role('colaborador')) {
        add_role('colaborador', 'Colaborador', [
            'read'                    => true,
            'edit_posts'              => true,
            'delete_posts'            => true,
            'edit_published_posts'    => false,
            'publish_posts'           => false,
            'upload_files'            => true,
            'level_1'                 => true,
            'level_0'                 => true,
        ]);
    }
}, 5);

function iglesia_user_role_slug() {
    $user = wp_get_current_user();
    if (!$user->ID) return null;
    if ($user->roles && in_array('administrator', $user->roles, true)) return 'administrator';
    foreach (['editor', 'pastor', 'colaborador'] as $r) {
        if (in_array($r, $user->roles, true)) return $r;
    }
    return $user->roles ? $user->roles[0] : null;
}

// =============================================
// MENÚ ADMIN SEGÚN ROL
// =============================================
$iglesia_content_cpts = [
    'ministerio'   => ['Ministerios', 'dashicons-groups'],
    'predicador'   => ['Predicadores', 'dashicons-microphone'],
    'faq_seccion'  => ['Secciones FAQ', 'dashicons-category'],
    'faq'          => ['Preguntas FAQ', 'dashicons-editor-help'],
    'himno'        => ['Himnario', 'dashicons-playlist-audio'],
    'mision_vision'=> ['Misión y Visión', 'dashicons-visibility'],
    'historia'     => ['Historia', 'dashicons-backup'],
    'imagen'       => ['Imágenes', 'dashicons-format-gallery'],
    'carrusel_slide' => ['Carrusel', 'dashicons-images-alt2'],
];

add_action('admin_menu', function() use (&$iglesia_content_cpts) {
    $role = iglesia_user_role_slug();

    // ---- ADMINISTRADOR: acceso total (menú nativo de WP + mantenimientos del portal)
    if ($role === 'administrator') {
        $pos = 20;
        foreach ($iglesia_content_cpts as $cpt => $info) {
            add_menu_page($info[0], $info[0], 'edit_posts', 'edit.php?post_type=' . $cpt, '', $info[1], $pos++);
        }
        add_menu_page('Configuración Iglesia', 'Iglesia', 'edit_posts', 'iglesia-settings', 'iglesia_settings_page', 'dashicons-building', 29);
        return;
    }

    // ---- EDITOR: administración de contenido
    if ($role === 'editor') {
        remove_menu_page('edit.php?post_type=page'); // Páginas
        remove_menu_page('edit-comments.php');   // Comentarios
        remove_menu_page('themes.php');          // Apariencia
        remove_menu_page('plugins.php');         // Plugins
        remove_menu_page('users.php');           // Usuarios
        remove_menu_page('tools.php');           // Herramientas
        remove_menu_page('options-general.php'); // Ajustes

        $pos = 18;
        foreach ($iglesia_content_cpts as $cpt => $info) {
            add_menu_page($info[0], $info[0], 'edit_posts', 'edit.php?post_type=' . $cpt, '', $info[1], $pos++);
        }
        add_menu_page('Configuración Iglesia', 'Iglesia', 'edit_posts', 'iglesia-settings', 'iglesia_settings_page', 'dashicons-building', 30);
        return;
    }

    // ---- COLABORADOR: únicamente Blog (Entradas + Medios para sus imágenes)
    if ($role === 'colaborador') {
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('edit-comments.php');
        remove_menu_page('themes.php');
        remove_menu_page('plugins.php');
        remove_menu_page('users.php');
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
        return;
    }

    // ---- PASTOR: Dashboard y Estadísticas únicamente
    if ($role === 'pastor') {
        remove_menu_page('index.php');
        remove_menu_page('edit.php');
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('edit-comments.php');
        remove_menu_page('themes.php');
        remove_menu_page('plugins.php');
        remove_menu_page('users.php');
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
        remove_menu_page('profile.php');

        global $submenu;
        unset($submenu['index.php']); // Por si queda algo del Escritorio

        add_menu_page('Dashboard', 'Dashboard', 'read', 'iglesia-dashboard', 'iglesia_dashboard_page', 'dashicons-dashboard', 2);
        add_menu_page('Estadísticas', 'Estadísticas', 'read', 'iglesia-stats', 'iglesia_stats_page', 'dashicons-chart-bar', 3);
        return;
    }
}, 998);

// Ocultar todos los widgets nativos del Escritorio para el Pastor
add_action('wp_dashboard_setup', function() {
    $role = iglesia_user_role_slug();
    if (!in_array($role, ['pastor', 'administrator'], true)) return;

    global $wp_meta_boxes;
    // Mantener únicamente nuestro widget; el resto se elimina para el Pastor
    if ($role === 'pastor') {
        $ours = isset($wp_meta_boxes['dashboard']['normal']['core']['iglesia_dashboard_widget'])
            ? $wp_meta_boxes['dashboard']['normal']['core']['iglesia_dashboard_widget']
            : null;
        $wp_meta_boxes['dashboard'] = [];
        if ($ours) {
            $wp_meta_boxes['dashboard']['normal']['core']['iglesia_dashboard_widget'] = $ours;
        }
        update_user_meta(get_current_user_id(), 'show_welcome_panel', 0);
    }
}, 1000);

// =============================================
// SEGURIDAD — Validación real de acceso por URL (backend)
// Cada rol solo puede abrir las pantallas autorizadas, aunque escriba la URL.
// =============================================
add_action('admin_init', function() {
    if (wp_doing_ajax()) return;
    $role = iglesia_user_role_slug();
    if (!$role || $role === 'administrator') return;

    $current = $_SERVER['REQUEST_URI'];
    $allow = false;

    // Tipos de contenido permitidos por rol (para post.php / edit.php)
    $content_types_editor = ['ministerio','predicador','faq','faq_seccion','himno','mision_vision','historia','imagen','carrusel_slide','post'];
    $content_types_colab  = ['post'];

    // Resolver tipo de contenido solicitado
    $requested_type = isset($_GET['post_type']) ? sanitize_key($_GET['post_type']) : '';
    if (!$requested_type && isset($_GET['post'])) {
        $requested_type = get_post_type(intval($_GET['post']));
    }

    switch ($role) {
        case 'editor':
            $base_pages = ['index.php','admin-ajax.php','async-upload.php','profile.php','upload.php',
                           'media-new.php','media.php','post.php','post-new.php','edit.php',
                           'edit-tags.php','term.php','iglesia-settings'];
            foreach ($base_pages as $p) {
                if (strpos($current, $p) !== false) { $allow = true; break; }
            }
            // Validar que el tipo de contenido sea del portal (no páginas/otros técnicos)
            if ($allow && $requested_type && !in_array($requested_type, $content_types_editor, true)) {
                $allow = false;
            }
            if ($allow && (strpos($current, 'edit-tags.php') !== false || strpos($current, 'term.php') !== false)) {
                $tax = isset($_GET['taxonomy']) ? sanitize_key($_GET['taxonomy']) : '';
                $allow = in_array($tax, ['category', 'post_tag'], true); // Solo taxonomías del Blog
            }
            break;

        case 'colaborador':
            $base_pages = ['index.php','admin-ajax.php','async-upload.php','profile.php','upload.php',
                           'media-new.php','media.php','post.php','post-new.php','edit.php',
                           'edit-tags.php','term.php'];
            foreach ($base_pages as $p) {
                if (strpos($current, $p) !== false) { $allow = true; break; }
            }
            // Colaborador SOLO Blog
            if ($allow && $requested_type && !in_array($requested_type, $content_types_colab, true)) {
                $allow = false;
            }
            if ($allow && (strpos($current, 'edit-tags.php') !== false || strpos($current, 'term.php') !== false)) {
                $tax = isset($_GET['taxonomy']) ? sanitize_key($_GET['taxonomy']) : '';
                $allow = in_array($tax, ['category', 'post_tag'], true);
            }
            break;

        case 'pastor':
            // El Pastor solo puede ver Dashboard, Estadísticas y su perfil no está disponible
            $allowed = ['index.php','admin-ajax.php','async-upload.php','iglesia-dashboard','iglesia-stats'];
            foreach ($allowed as $p) {
                if (strpos($current, $p) !== false) { $allow = true; break; }
            }
            break;
    }

    if (!$allow) {
        wp_redirect(admin_url(strpos($current, 'iglesia-dashboard') !== false || $role === 'pastor' ? 'admin.php?page=iglesia-dashboard' : 'index.php'));
        exit;
    }
}, 999);

// Blindar capacidades reales: aunque un rol intente una acción prohibida vía HTTP, WP la deniega
add_filter('map_meta_cap', function($caps, $cap, $user_id, $args) {
    $user = get_userdata($user_id);
    if (!$user) return $caps;
    $role = iglesia_user_role_slug();

    // Pastores y colaboradores jamás gestionan usuarios/roles/opciones/plugins/temas
    if (in_array($role, ['pastor', 'colaborador'], true)) {
        $forbidden = ['manage_options','manage_network_users','promote_users','create_users',
                      'edit_users','delete_users','list_users','remove_users','activate_plugins',
                      'switch_themes','edit_theme_options','install_plugins','update_plugins',
                      'delete_plugins','install_themes','update_themes','delete_themes','edit_files'];
        if (in_array($cap, $forbidden, true)) {
            $caps[] = 'do_not_allow';
        }
    }
    return $caps;
}, 10, 4);

// =============================================
// REGISTRO DE ACTIVIDAD (auditoría reutilizable)
// =============================================
function iglesia_log_activity($action, $object = '') {
    $user = wp_get_current_user();
    $log = get_option('iglesia_activity_log', []);
    if (!is_array($log)) $log = [];
    array_unshift($log, [
        't' => time(),
        'u' => $user->exists() ? $user->display_name : 'Sistema',
        'a' => $action,
        'o' => $object,
    ]);
    update_option('iglesia_activity_log', array_slice($log, 0, 80), false);
}

add_action('transition_post_status', function($new_status, $old_status, $post) {
    if (in_array($post->post_type, ['nav_menu_item', 'revision', 'attachment'], true)) return;
    $labels = [
        'publish' => 'Publicó',
        'draft'   => 'Guardó borrador de',
        'pending' => 'Envió a revisión',
        'trash'   => 'Movió a papelera',
    ];
    // Solo registrar cuando hay un cambio de estado significativo
    if ($new_status === $old_status) return;
    if (!isset($labels[$new_status]) && !isset($labels[$old_status])) {
        iglesia_log_activity('Actualizó', $post->post_title);
        return;
    }
    $action = isset($labels[$new_status]) ? $labels[$new_status] : 'Restauró';
    iglesia_log_activity($action, $post->post_title . ' (' . $post->post_type . ')');
}, 10, 3);

add_action('add_attachment', function($attachment_id) {
    $title = get_the_title($attachment_id);
    iglesia_log_activity('Agregó una imagen', $title);
});

add_action('user_register', function($user_id) {
    $u = get_userdata($user_id);
    iglesia_log_activity('Registró al usuario', $u ? $u->user_login : '#' . $user_id);
});

// =============================================
// CONTENIDO POR DEFECTO — Reutilizado por semillas y frontend
// =============================================
function iglesia_default_content() {
    return [
        'mision'  => 'Predicar el Evangelio de Jesucristo a toda criatura, haciendo discípulos que glorifiquen a Dios con su vida, sean transformados por su Palabra y sirvan a su generación con excelencia y amor.',
        'vision'  => 'Ser una iglesia de impacto regional que forme líderes íntegros, alcance a los perdidos y transforme comunidades enteras a través del poder del Evangelio y el amor de Cristo.',
        'historia' => [
            ['anio' => '2021', 'title' => 'Fundación de la Iglesia', 'content' => 'Un grupo de creyentes se reunió con la visión de establecer una iglesia comprometida con la Palabra de Dios, la oración y la comunidad. Los primeros cultos se realizaron en una casa adaptada como templo.', 'order' => 1],
            ['anio' => '2022', 'title' => 'Crecimiento y Consolidación', 'content' => 'La iglesia experimentó un crecimiento significativo. Se formaron los primeros ministerios: alabanza, infantil y de oración. La congregación creció y se hizo necesario buscar un espacio más amplio.', 'order' => 2],
            ['anio' => '2023', 'title' => 'Nuevo Templo', 'content' => 'Dios proveyó un nuevo local con capacidad para albergar a toda la congregación. Se inauguró el templo con un servicio de consagración y celebración al que asistieron más de 200 personas.', 'order' => 3],
            ['anio' => '2024', 'title' => 'Expansión Ministerial', 'content' => 'Se establecieron nuevos ministerios: jóvenes, mujeres y hombres. La iglesia comenzó a tener impacto en la comunidad a través de obras sociales, visitas a hospitales y ayuda a familias necesitadas.', 'order' => 4],
            ['anio' => '2025', 'title' => 'Proyección y Cobertura', 'content' => 'Se iniciaron transmisiones en vivo de los cultos, alcanzando a personas más allá de nuestra ciudad. La iglesia estableció alianzas con otras congregaciones y misiones en la región.', 'order' => 5],
            ['anio' => '2026', 'title' => 'Un Nuevo Capítulo', 'content' => 'Hoy continuamos firmes en la fe, mirando hacia adelante con la visión de alcanzar a más personas con el Evangelio. Planeamos iniciar nuevas obras misioneras y expandir nuestros ministerios.', 'order' => 6],
        ],
        'carrusel' => [
            ['title' => 'Tabernáculo Cristiano AD', 'subtitle' => "Una iglesia de fe, esperanza y amor", 'enlace' => '', 'imagen' => 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?q=80&w=2000&auto=format&fit=crop', 'order' => 1],
            ['title' => 'Una iglesia de Fe', 'subtitle' => "Porque por fe andamos, no por vista.", 'enlace' => '', 'imagen' => 'https://images.unsplash.com/photo-1548625361-ec8536098270?q=80&w=2000&auto=format&fit=crop', 'order' => 2],
            ['title' => 'Tu familia es Bienvenida', 'subtitle' => "Un lugar de restauración y esperanza para todos.", 'enlace' => '', 'imagen' => 'https://images.unsplash.com/photo-1504052434569-70ad5836ab65?q=80&w=2000&auto=format&fit=crop', 'order' => 3],
        ],
    ];
}

// Semillas automáticas para los nuevos módulos (se ejecuta una sola vez)
add_action('admin_init', function() {
    if (get_option('iglesia_seed_v2')) return;
    update_option('iglesia_seed_v2', 1, false);

    $d = iglesia_default_content();

    // Misión y Visión
    if (!wp_count_posts('mision_vision')->publish) {
        foreach ([['tipo' => 'mision', 'label' => 'Misión'], ['tipo' => 'vision', 'label' => 'Visión']] as $i => $mv) {
            wp_insert_post([
                'post_type' => 'mision_vision',
                'post_title' => $mv['label'],
                'post_status' => 'publish',
                'menu_order' => $i + 1,
                'meta_input' => [
                    'mv_tipo' => $mv['tipo'],
                    'mv_contenido' => $mv['tipo'] === 'mision' ? $d['mision'] : $d['vision'],
                    'mv_activo' => '1',
                ],
            ]);
        }
    }

    // Historia
    if (!wp_count_posts('historia')->publish) {
        foreach ($d['historia'] as $h) {
            wp_insert_post([
                'post_type' => 'historia',
                'post_title' => $h['title'],
                'post_status' => 'publish',
                'menu_order' => $h['order'],
                'meta_input' => [
                    'historia_anio' => $h['anio'],
                    'historia_contenido' => $h['content'],
                    'historia_activo' => '1',
                ],
            ]);
        }
    }

    // Carrusel
    if (!wp_count_posts('carrusel_slide')->publish) {
        foreach ($d['carrusel'] as $s) {
            wp_insert_post([
                'post_type' => 'carrusel_slide',
                'post_title' => $s['title'],
                'post_status' => 'publish',
                'menu_order' => $s['order'],
                'meta_input' => [
                    'carrusel_subtitulo' => $s['subtitle'],
                    'carrusel_enlace' => $s['enlace'],
                    'carrusel_imagen_url' => $s['imagen'],
                    'carrusel_activo' => '1',
                ],
            ]);
        }
    }

    iglesia_log_activity('Inicializó los mantenimientos del portal');
});

// =============================================
// CPT: MISIÓN Y VISIÓN
// =============================================
register_iglesia_cpt_definitions();

function register_iglesia_cpt_definitions() {
    add_action('init', function() {
        // Misión y Visión
        register_post_type('mision_vision', [
            'labels' => [
                'name' => 'Misión y Visión',
                'singular_name' => 'Entrada M/V',
                'add_new' => 'Agregar Entrada',
                'add_new_item' => 'Agregar Nueva Entrada',
                'edit_item' => 'Editar Entrada',
                'view_item' => 'Ver Entrada',
                'search_items' => 'Buscar Entradas',
                'not_found' => 'No se encontraron entradas',
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_icon' => 'dashicons-visibility',
            'supports' => ['title', 'page-attributes'],
            'show_in_rest' => true,
        ]);

        // Historia
        register_post_type('historia', [
            'labels' => [
                'name' => 'Historia',
                'singular_name' => 'Etapa Histórica',
                'add_new' => 'Agregar Etapa',
                'add_new_item' => 'Agregar Nueva Etapa',
                'edit_item' => 'Editar Etapa',
                'view_item' => 'Ver Etapa',
                'search_items' => 'Buscar Etapas',
                'not_found' => 'No se encontraron etapas',
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_icon' => 'dashicons-backup',
            'supports' => ['title', 'page-attributes'],
            'show_in_rest' => true,
        ]);

        // Imágenes (catálogo centralizado)
        register_post_type('imagen', [
            'labels' => [
                'name' => 'Imágenes',
                'singular_name' => 'Imagen',
                'add_new' => 'Agregar Imagen',
                'add_new_item' => 'Agregar Nueva Imagen',
                'edit_item' => 'Editar Imagen',
                'view_item' => 'Ver Imagen',
                'search_items' => 'Buscar Imágenes',
                'not_found' => 'No se encontraron imágenes',
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_icon' => 'dashicons-format-gallery',
            'supports' => ['title', 'page-attributes'],
            'show_in_rest' => true,
        ]);

        // Carrusel
        register_post_type('carrusel_slide', [
            'labels' => [
                'name' => 'Carrusel',
                'singular_name' => 'Slide del Carrusel',
                'add_new' => 'Agregar Slide',
                'add_new_item' => 'Agregar Nuevo Slide',
                'edit_item' => 'Editar Slide',
                'view_item' => 'Ver Slide',
                'search_items' => 'Buscar Slides',
                'not_found' => 'No se encontraron slides',
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'menu_icon' => 'dashicons-images-alt2',
            'supports' => ['title', 'page-attributes'],
            'show_in_rest' => true,
        ]);
    });
}

// Meta box: Misión / Visión
add_action('add_meta_boxes', function() {
    add_meta_box('mv_info', 'Contenido', function($post) {
        wp_nonce_field('mv_meta', 'mv_meta_nonce');
        $tipo = get_post_meta($post->ID, 'mv_tipo', true) ?: 'mision';
        $contenido = get_post_meta($post->ID, 'mv_contenido', true);
        $activo = get_post_meta($post->ID, 'mv_activo', true);
        $activo = ($activo === '' || $activo === '1') ? true : false;
        ?>
        <div class="igl-set-card">
            <h3><span class="dashicons dashicons-list-view"></span> Datos</h3>
            <div class="igl-set-field">
                <label for="mv_tipo">Tipo <span style="color:#d63638;">*</span></label>
                <select name="mv_tipo" id="mv_tipo" required>
                    <option value="mision" <?php selected($tipo, 'mision'); ?>>🎯 Misión</option>
                    <option value="vision" <?php selected($tipo, 'vision'); ?>>👁️ Visión</option>
                </select>
                <p class="hint">Dónde se mostrará este contenido en la página Misión y Visión.</p>
            </div>
            <div class="igl-set-field">
                <label for="mv_contenido">Contenido <span style="color:#d63638;">*</span></label>
                <textarea name="mv_contenido" id="mv_contenido" placeholder="Texto de la misión o visión..."><?php echo esc_textarea($contenido); ?></textarea>
                <p class="hint">Campo obligatorio. Se muestra tal cual en la tarjeta correspondiente.</p>
            </div>
            <div class="igl-set-field">
                <label for="mv_orden">Orden</label>
                <input type="number" name="menu_order" id="mv_orden" value="<?php echo esc_attr($post->menu_order ?: 0); ?>" min="0">
                <p class="hint">Número más bajo = aparece primero dentro de su tipo.</p>
            </div>
        </div>
        <?php iglesia_render_toggle_box('mv_activo', $activo); ?>
        <?php
    }, 'mision_vision', 'normal', 'high');
});

// Meta box: Historia
add_action('add_meta_boxes', function() {
    add_meta_box('historia_info', 'Etapa Histórica', function($post) {
        wp_nonce_field('historia_meta', 'historia_meta_nonce');
        $anio = get_post_meta($post->ID, 'historia_anio', true);
        $contenido = get_post_meta($post->ID, 'historia_contenido', true);
        $imagen_id = get_post_meta($post->ID, 'historia_imagen_id', true);
        $imagen_url = $imagen_id ? wp_get_attachment_image_url($imagen_id, 'medium') : '';
        $activo = get_post_meta($post->ID, 'historia_activo', true);
        $activo = ($activo === '' || $activo === '1') ? true : false;
        ?>
        <div class="igl-set-card">
            <h3><span class="dashicons dashicons-calendar-alt"></span> Datos de la Etapa</h3>
            <div class="igl-set-field">
                <label for="historia_anio">Año <span style="color:#d63638;">*</span></label>
                <input type="text" name="historia_anio" id="historia_anio" value="<?php echo esc_attr($anio); ?>" placeholder="Ej: 2021">
                <p class="hint">Se muestra grande en la línea de tiempo.</p>
            </div>
            <div class="igl-set-field">
                <label for="historia_contenido">Contenido <span style="color:#d63638;">*</span></label>
                <textarea name="historia_contenido" id="historia_contenido" placeholder="Qué sucedió en esta etapa..."><?php echo esc_textarea($contenido); ?></textarea>
                <p class="hint">Campo obligatorio. Describe la etapa histórica.</p>
            </div>
            <div class="igl-set-field">
                <label for="historia_orden">Orden cronológico</label>
                <input type="number" name="menu_order" id="historia_orden" value="<?php echo esc_attr($post->menu_order ?: 0); ?>" min="0">
                <p class="hint">Número más bajo = primera etapa de la línea de tiempo.</p>
            </div>
        </div>

        <div class="igl-set-card">
            <h3><span class="dashicons dashicons-format-image"></span> Imagen (opcional)</h3>
            <div class="igl-img-preview" id="hist-img-preview" onclick="document.getElementById('hist-img-btn').click()">
                <?php if ($imagen_url): ?>
                    <img src="<?php echo esc_url($imagen_url); ?>" alt="">
                <?php else: ?>
                    <div class="placeholder"><span class="dashicons dashicons-images-alt"></span><br><small>Click para subir imagen</small></div>
                <?php endif; ?>
            </div>
            <input type="hidden" id="historia_imagen_id" name="historia_imagen_id" value="<?php echo esc_attr($imagen_id); ?>">
            <button type="button" class="button" id="hist-img-btn">Seleccionar imagen</button>
            <?php if ($imagen_id): ?><button type="button" class="button button-link-delete" id="hist-img-remove">Quitar imagen</button><?php endif; ?>
        </div>
        <?php iglesia_render_toggle_box('historia_activo', $activo); ?>

        <script>
        jQuery(function($) {
            var frame;
            $('#hist-img-btn').on('click', function(e) {
                e.preventDefault();
                if (frame) { frame.open(); return; }
                frame = wp.media({ title:'Seleccionar imagen', button:{text:'Usar esta imagen'}, multiple:false });
                frame.on('select', function() {
                    var att = frame.state().get('selection').first().toJSON();
                    $('#historia_imagen_id').val(att.id);
                    $('#hist-img-preview').html('<img src="'+(att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url)+'" alt="">');
                    if (!$('#hist-img-remove').length) $('#hist-img-btn').after('<button type="button" class="button button-link-delete" id="hist-img-remove">Quitar imagen</button>');
                });
                frame.open();
            });
            $(document).on('click', '#hist-img-remove', function() {
                $('#historia_imagen_id').val('');
                $('#hist-img-preview').html('<div class="placeholder"><span class="dashicons dashicons-images-alt"></span><br><small>Click para subir imagen</small></div>');
                $(this).remove();
            });
        });
        </script>
        <?php
    }, 'historia', 'normal', 'high');
});

// Meta box: Imágenes
add_action('add_meta_boxes', function() {
    add_meta_box('imagen_info', 'Datos de la Imagen', function($post) {
        wp_nonce_field('imagen_meta', 'imagen_meta_nonce');
        $descripcion = get_post_meta($post->ID, 'imagen_descripcion', true);
        $categoria = get_post_meta($post->ID, 'imagen_categoria', true) ?: 'general';
        $imagen_id = get_post_meta($post->ID, 'imagen_attachment_id', true);
        $imagen_url = $imagen_id ? wp_get_attachment_image_url($imagen_id, 'medium') : '';
        $activo = get_post_meta($post->ID, 'imagen_activo', true);
        $activo = ($activo === '' || $activo === '1') ? true : false;

        $categorias = [
            'carrusel'      => 'Carrusel',
            'galeria'       => 'Galerías',
            'historia'      => 'Historia',
            'mision_vision' => 'Misión y Visión',
            'eventos'       => 'Eventos',
            'blog'          => 'Blog',
            'general'       => 'General',
        ];
        ?>
        <div class="igl-set-card">
            <h3><span class="dashicons dashicons-format-image"></span> Imagen <span style="color:#d63638;font-weight:normal;">*</span></h3>
            <div class="igl-img-preview" id="img-meta-preview" onclick="document.getElementById('img-meta-btn').click()">
                <?php if ($imagen_url): ?>
                    <img src="<?php echo esc_url($imagen_url); ?>" alt="">
                <?php else: ?>
                    <div class="placeholder"><span class="dashicons dashicons-images-alt"></span><br><small>Click para subir imagen</small></div>
                <?php endif; ?>
            </div>
            <input type="hidden" id="imagen_attachment_id" name="imagen_attachment_id" value="<?php echo esc_attr($imagen_id); ?>">
            <button type="button" class="button" id="img-meta-btn">Seleccionar imagen</button>
            <?php if ($imagen_id): ?><button type="button" class="button button-link-delete" id="img-meta-remove">Quitar imagen</button><?php endif; ?>
        </div>

        <div class="igl-set-card">
            <h3><span class="dashicons dashicons-info"></span> Metadatos</h3>
            <div class="igl-set-field">
                <label for="imagen_descripcion">Descripción</label>
                <textarea name="imagen_descripcion" id="imagen_descripcion" placeholder="Para qué se usa esta imagen..."><?php echo esc_textarea($descripcion); ?></textarea>
            </div>
            <div class="igl-set-field">
                <label for="imagen_categoria">Tipo / Categoría</label>
                <select name="imagen_categoria" id="imagen_categoria">
                    <?php foreach ($categorias as $key => $label): ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php selected($categoria, $key); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="hint">Clasifica la imagen según dónde se usa. Extensible para usos futuros.</p>
            </div>
            <div class="igl-set-field">
                <label for="imagen_orden">Orden</label>
                <input type="number" name="menu_order" id="imagen_orden" value="<?php echo esc_attr($post->menu_order ?: 0); ?>" min="0">
            </div>
        </div>
        <?php iglesia_render_toggle_box('imagen_activo', $activo); ?>

        <script>
        jQuery(function($) {
            var frame;
            $('#img-meta-btn').on('click', function(e) {
                e.preventDefault();
                if (frame) { frame.open(); return; }
                frame = wp.media({ title:'Seleccionar imagen', button:{text:'Usar esta imagen'}, multiple:false });
                frame.on('select', function() {
                    var att = frame.state().get('selection').first().toJSON();
                    $('#imagen_attachment_id').val(att.id);
                    $('#img-meta-preview').html('<img src="'+(att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url)+'" alt="">');
                    if (!$('#img-meta-remove').length) $('#img-meta-btn').after('<button type="button" class="button button-link-delete" id="img-meta-remove">Quitar imagen</button>');
                });
                frame.open();
            });
            $(document).on('click', '#img-meta-remove', function() {
                $('#imagen_attachment_id').val('');
                $('#img-meta-preview').html('<div class="placeholder"><span class="dashicons dashicons-images-alt"></span><br><small>Click para subir imagen</small></div>');
                $(this).remove();
            });
        });
        </script>
        <?php
    }, 'imagen', 'normal', 'high');
});

// Meta box: Carrusel
add_action('add_meta_boxes', function() {
    add_meta_box('carrusel_info', 'Slide del Carrusel', function($post) {
        wp_nonce_field('carrusel_meta', 'carrusel_meta_nonce');
        $subtitulo = get_post_meta($post->ID, 'carrusel_subtitulo', true);
        $enlace = get_post_meta($post->ID, 'carrusel_enlace', true);
        $imagen_url = get_post_meta($post->ID, 'carrusel_imagen_url', true);
        $activo = get_post_meta($post->ID, 'carrusel_activo', true);
        $activo = ($activo === '' || $activo === '1') ? true : false;
        ?>
        <div class="igl-info-box">
            <p><span class="dashicons dashicons-lightbulb"></span>El título del slide se configura en el campo "Título" de arriba. Los slides activos aparecen en el héroe de la página principal según su orden.</p>
        </div>

        <div class="igl-set-card">
            <h3><span class="dashicons dashicons-format-image"></span> Imagen de fondo <span style="color:#d63638;font-weight:normal;">*</span></h3>
            <div class="igl-img-preview" id="car-img-preview" onclick="document.getElementById('car-img-btn').click()">
                <?php if ($imagen_url): ?>
                    <img src="<?php echo esc_url($imagen_url); ?>" alt="" style="width:auto;">
                <?php else: ?>
                    <div class="placeholder"><span class="dashicons dashicons-images-alt"></span><br><small>Click para subir imagen</small></div>
                <?php endif; ?>
            </div>
            <input type="hidden" id="carrusel_imagen_url_media" name="carrusel_imagen_url_media" value="">
            <input type="text" id="carrusel_imagen_url" name="carrusel_imagen_url" value="<?php echo esc_url($imagen_url); ?>" placeholder="URL de la imagen (se llena al seleccionar)" readonly style="max-width:340px;">
            <button type="button" class="button" id="car-img-btn">Seleccionar imagen</button>
            <?php if ($imagen_url): ?><button type="button" class="button button-link-delete" id="car-img-remove">Quitar imagen</button><?php endif; ?>
        </div>

        <div class="igl-set-card">
            <h3><span class="dashicons dashicons-text"></span> Textos y Enlace</h3>
            <div class="igl-set-field">
                <label for="carrusel_subtitulo">Descripción / Subtítulo</label>
                <textarea name="carrusel_subtitulo" id="carrusel_subtitulo" placeholder="Frase o versículo del slide..." style="min-height:60px;"><?php echo esc_textarea($subtitulo); ?></textarea>
            </div>
            <div class="igl-set-field">
                <label for="carrusel_enlace">Enlace opcional</label>
                <input type="text" name="carrusel_enlace" id="carrusel_enlace" value="<?php echo esc_attr($enlace); ?>" placeholder="https://ejemplo.com o /contacto/">
                <p class="hint">Si defines un enlace, se agrega un botón sobre la imagen.</p>
            </div>
            <div class="igl-set-field">
                <label for="carrusel_orden">Orden</label>
                <input type="number" name="menu_order" id="carrusel_orden" value="<?php echo esc_attr($post->menu_order ?: 0); ?>" min="0">
                <p class="hint">Número más bajo = aparece primero en el carrusel.</p>
            </div>
        </div>
        <?php iglesia_render_toggle_box('carrusel_activo', $activo); ?>

        <script>
        jQuery(function($) {
            var frame;
            $('#car-img-btn').on('click', function(e) {
                e.preventDefault();
                if (frame) { frame.open(); return; }
                frame = wp.media({ title:'Seleccionar imagen de fondo', button:{text:'Usar esta imagen'}, multiple:false });
                frame.on('select', function() {
                    var att = frame.state().get('selection').first().toJSON();
                    var url = att.sizes && att.sizes.large ? att.sizes.large.url : att.url;
                    $('#carrusel_imagen_url').val(url);
                    $('#car-img-preview').html('<img src="'+url+'" alt="">');
                    if (!$('#car-img-remove').length) $('#car-img-btn').after('<button type="button" class="button button-link-delete" id="car-img-remove">Quitar imagen</button>');
                });
                frame.open();
            });
            $(document).on('click', '#car-img-remove', function() {
                $('#carrusel_imagen_url').val('');
                $('#car-img-preview').html('<div class="placeholder"><span class="dashicons dashicons-images-alt"></span><br><small>Click para subir imagen</small></div>');
                $(this).remove();
            });
        });
        </script>
        <?php
    }, 'carrusel_slide', 'normal', 'high');
});

// Toggle Activo/Inactivo estándar (reutilizable)
function iglesia_render_toggle_box($meta_key, $activo) {
    $label = ucfirst(str_replace('_', ' ', $meta_key));
    ?>
    <div class="igl-set-card">
        <h3><span class="dashicons dashicons-<?php echo $activo ? 'visibility' : 'hidden'; ?>"></span> Estado</h3>
        <div class="igl-set-field">
            <div class="igl-toggle">
                <label class="toggle">
                    <input type="checkbox" name="<?php echo esc_attr($meta_key); ?>" id="<?php echo esc_attr($meta_key); ?>" value="1" <?php checked($activo, true); ?>>
                    <span class="slider"></span>
                </label>
                <span class="status-label <?php echo $activo ? 'status-active' : 'status-inactive'; ?>" id="<?php echo esc_attr($meta_key); ?>_status_label">
                    <?php echo $activo ? '✓ Activo - Visible en la web' : '✗ Inactivo - Oculto en la web'; ?>
                </span>
            </div>
            <p class="hint" style="margin-top:10px;">Desactiva si no quieres que este registro aparezca en la web.</p>
        </div>
    </div>
    <script>
    jQuery(function($) {
        $('#<?php echo esc_js($meta_key); ?>').on('change', function() {
            var label = $('#<?php echo esc_js($meta_key); ?>_status_label');
            var icon = $(this).closest('.igl-set-card').find('h3 .dashicons');
            if ($(this).is(':checked')) {
                label.text('✓ Activo - Visible en la web').removeClass('status-inactive').addClass('status-active');
                icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            } else {
                label.text('✗ Inactivo - Oculto en la web').removeClass('status-active').addClass('status-inactive');
                icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            }
        });
    });
    </script>
    <?php
}

// Guardar metas de los nuevos CPTs
foreach ([
    'save_post_mision_vision' => ['nonce_action' => 'mv_meta', 'nonce_field' => 'mv_meta_nonce'],
] as $_hook => $_cfg) {
    add_action($_hook, function($post_id) use ($_cfg) {
        if (!isset($_POST[$_cfg['nonce_field']]) || !wp_verify_nonce($_POST[$_cfg['nonce_field']], $_cfg['nonce_action'])) return;
        if (isset($_POST['mv_tipo'])) update_post_meta($post_id, 'mv_tipo', in_array($_POST['mv_tipo'], ['mision','vision']) ? $_POST['mv_tipo'] : 'mision');
        if (isset($_POST['mv_contenido'])) update_post_meta($post_id, 'mv_contenido', sanitize_textarea_field($_POST['mv_contenido']));
        update_post_meta($post_id, 'mv_activo', isset($_POST['mv_activo']) ? '1' : '0');
        iglesia_log_activity('Actualizó contenido de Misión/Visión', get_the_title($post_id));
    });
}

add_action('save_post_historia', function($post_id) {
    if (!isset($_POST['historia_meta_nonce']) || !wp_verify_nonce($_POST['historia_meta_nonce'], 'historia_meta')) return;
    if (isset($_POST['historia_anio'])) update_post_meta($post_id, 'historia_anio', sanitize_text_field($_POST['historia_anio']));
    if (isset($_POST['historia_contenido'])) update_post_meta($post_id, 'historia_contenido', sanitize_textarea_field($_POST['historia_contenido']));
    if (isset($_POST['historia_imagen_id'])) update_post_meta($post_id, 'historia_imagen_id', intval($_POST['historia_imagen_id']));
    update_post_meta($post_id, 'historia_activo', isset($_POST['historia_activo']) ? '1' : '0');
    iglesia_log_activity('Actualizó la Historia', get_the_title($post_id));
});

add_action('save_post_imagen', function($post_id) {
    if (!isset($_POST['imagen_meta_nonce']) || !wp_verify_nonce($_POST['imagen_meta_nonce'], 'imagen_meta')) return;
    if (isset($_POST['imagen_descripcion'])) update_post_meta($post_id, 'imagen_descripcion', sanitize_textarea_field($_POST['imagen_descripcion']));
    if (isset($_POST['imagen_categoria'])) update_post_meta($post_id, 'imagen_categoria', sanitize_key($_POST['imagen_categoria']));
    if (isset($_POST['imagen_attachment_id'])) update_post_meta($post_id, 'imagen_attachment_id', intval($_POST['imagen_attachment_id']));
    update_post_meta($post_id, 'imagen_activo', isset($_POST['imagen_activo']) ? '1' : '0');
});

add_action('save_post_carrusel_slide', function($post_id) {
    if (!isset($_POST['carrusel_meta_nonce']) || !wp_verify_nonce($_POST['carrusel_meta_nonce'], 'carrusel_meta')) return;
    if (isset($_POST['carrusel_subtitulo'])) update_post_meta($post_id, 'carrusel_subtitulo', sanitize_textarea_field($_POST['carrusel_subtitulo']));
    if (isset($_POST['carrusel_enlace'])) update_post_meta($post_id, 'carrusel_enlace', esc_url_raw($_POST['carrusel_enlace']));
    if (isset($_POST['carrusel_imagen_url'])) update_post_meta($post_id, 'carrusel_imagen_url', esc_url_raw($_POST['carrusel_imagen_url']));
    update_post_meta($post_id, 'carrusel_activo', isset($_POST['carrusel_activo']) ? '1' : '0');
    iglesia_log_activity('Actualizó el Carrusel', get_the_title($post_id));
});

// Log de configuración institucional (Iglesia) — se dispara al guardar desde la pantalla Iglesia
add_action('iglesia_settings_saved', function() {
    iglesia_log_activity('Actualizó la información institucional', 'Configuración Iglesia');
});

// =============================================
// COLUMNAS DE LISTADO ESTÁNDAR PARA TODOS LOS CPTs
// =============================================
function iglesia_add_std_columns($cpt, $opts = []) {
    add_filter("manage_{$cpt}_posts_columns", function($columns) use ($opts) {
        $new = [];
        foreach ($columns as $k => $v) {
            $new[$k] = $v;
            if ($k === 'title') {
                if (!empty($opts['thumb'])) $new['igl_thumb'] = 'Imagen';
                if (!empty($opts['extra_column'])) $new['igl_extra'] = $opts['extra_label'];
                $new['igl_estado'] = 'Estado';
                if (empty($opts['no_orden'])) $new['menu_order'] = 'Orden';
            }
        }
        if (!isset($new['igl_estado'])) {
            $new['igl_estado'] = 'Estado';
        }
        return $new;
    });

    add_action("manage_{$cpt}_posts_custom_column", function($column, $post_id) use ($opts) {
        switch ($column) {
            case 'igl_thumb':
                $tid = 0;
                if (!empty($opts['thumb_meta'])) $tid = get_post_meta($post_id, $opts['thumb_meta'], true);
                $url = '';
                if (!empty($opts['thumb_url_meta'])) {
                    $url = get_post_meta($post_id, $opts['thumb_url_meta'], true);
                } elseif ($tid) {
                    $url = wp_get_attachment_image_url($tid, 'thumbnail');
                }
                if ($url) {
                    echo '<img src="' . esc_url($url) . '" alt="" style="width:60px;height:45px;object-fit:cover;border-radius:4px;">';
                } else {
                    echo '<span style="color:#a7aaad;">—</span>';
                }
                break;
            case 'igl_extra':
                echo esc_html(get_post_meta($post_id, $opts['extra_meta'], true) ?: '—');
                break;
            case 'igl_estado':
                $meta = !empty($opts['estado_meta']) ? get_post_meta($post_id, $opts['estado_meta'], true) : '';
                $active = ($meta === '' || $meta === '1');
                $published = get_post_status($post_id) === 'publish';
                if (!$published) {
                    echo '<span class="badge-inactive">' . esc_html(get_post_status_object(get_post_status($post_id))->label) . '</span>';
                } elseif ($active) {
                    echo '<span class="badge-active">Activo</span>';
                } else {
                    echo '<span class="badge-inactive">Inactivo</span>';
                }
                break;
        }
    }, 10, 2);
}

iglesia_add_std_columns('ministerio', ['estado_meta' => 'ministerio_activo']);
iglesia_add_std_columns('faq_seccion', ['estado_meta' => 'faq_seccion_activo']);
iglesia_add_std_columns('faq', ['estado_meta' => 'faq_activo']);
iglesia_add_std_columns('himno', ['estado_meta' => 'himno_activo']);
iglesia_add_std_columns('predicador', ['estado_meta' => '_iglesia_predicador_activo']);
iglesia_add_std_columns('mision_vision', ['estado_meta' => 'mv_activo', 'extra_column' => true, 'extra_label' => 'Tipo', 'extra_meta' => 'mv_tipo']);
iglesia_add_std_columns('historia', ['estado_meta' => 'historia_activo', 'thumb' => true, 'thumb_meta' => 'historia_imagen_id', 'extra_column' => true, 'extra_label' => 'Año', 'extra_meta' => 'historia_anio']);
iglesia_add_std_columns('imagen', ['estado_meta' => 'imagen_activo', 'thumb' => true, 'thumb_meta' => 'imagen_attachment_id', 'extra_column' => true, 'extra_label' => 'Categoría', 'extra_meta' => 'imagen_categoria']);
iglesia_add_std_columns('carrusel_slide', ['estado_meta' => 'carrusel_activo', 'thumb' => true, 'thumb_url_meta' => 'carrusel_imagen_url']);

// Asegurar columna Imagen en Historia/Carrusel aunque el helper la ordene después del título
add_filter('manage_carrusel_slide_posts_columns', function($columns) {
    if (!isset($columns['igl_thumb'])) {
        $new = [];
        foreach ($columns as $k => $v) {
            $new[$k] = $v;
            if ($k === 'title') $new['igl_thumb'] = 'Imagen';
        }
        return $new;
    }
    return $columns;
}, 20);

// =============================================
// HELPERS FRONTEND — Datos dinámicos con fallback
// =============================================
function iglesia_get_mision_vision_data() {
    $q = new WP_Query([
        'post_type' => 'mision_vision',
        'posts_per_page' => -1,
        'meta_query' => iglesia_meta_activa('mv_activo'),
        'orderby' => ['menu_order' => 'ASC', 'date' => 'ASC'],
    ]);
    $out = ['mision' => [], 'vision' => []];
    while ($q->have_posts()) {
        $q->the_post();
        $tipo = get_post_meta(get_the_ID(), 'mv_tipo', true);
        $tipo = in_array($tipo, ['mision','vision'], true) ? $tipo : 'mision';
        $out[$tipo][] = [
            'title' => get_the_title(),
            'content' => get_post_meta(get_the_ID(), 'mv_contenido', true),
        ];
    }
    wp_reset_postdata();

    if (empty($out['mision']) && empty($out['vision'])) {
        $d = iglesia_default_content();
        $out['mision'][] = ['title' => 'Misión', 'content' => $d['mision']];
        $out['vision'][] = ['title' => 'Visión', 'content' => $d['vision']];
    }
    return $out;
}

function iglesia_get_historia_data() {
    $q = new WP_Query([
        'post_type' => 'historia',
        'posts_per_page' => -1,
        'meta_query' => iglesia_meta_activa('historia_activo'),
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);
    $items = [];
    while ($q->have_posts()) {
        $q->the_post();
        $imagen_id = get_post_meta(get_the_ID(), 'historia_imagen_id', true);
        $items[] = [
            'anio' => get_post_meta(get_the_ID(), 'historia_anio', true),
            'title' => get_the_title(),
            'content' => get_post_meta(get_the_ID(), 'historia_contenido', true),
            'imagen' => $imagen_id ? wp_get_attachment_image_url($imagen_id, 'large') : '',
        ];
    }
    wp_reset_postdata();

    if (empty($items)) {
        foreach (iglesia_default_content()['historia'] as $h) {
            $items[] = ['anio' => $h['anio'], 'title' => $h['title'], 'content' => $h['content'], 'imagen' => ''];
        }
    }
    return $items;
}

function iglesia_get_carrusel_data() {
    $q = new WP_Query([
        'post_type' => 'carrusel_slide',
        'posts_per_page' => -1,
        'meta_query' => iglesia_meta_activa('carrusel_activo'),
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);
    $slides = [];
    while ($q->have_posts()) {
        $q->the_post();
        $imagen = get_post_meta(get_the_ID(), 'carrusel_imagen_url', true);
        if (!$imagen) continue; // Sin imagen no puede mostrarse
        $slides[] = [
            'title' => get_the_title(),
            'subtitle' => get_post_meta(get_the_ID(), 'carrusel_subtitulo', true),
            'enlace' => get_post_meta(get_the_ID(), 'carrusel_enlace', true),
            'imagen' => $imagen,
        ];
    }
    wp_reset_postdata();

    if (empty($slides)) {
        foreach (iglesia_default_content()['carrusel'] as $s) {
            $slides[] = ['title' => $s['title'], 'subtitle' => $s['subtitle'], 'enlace' => $s['enlace'], 'imagen' => $s['imagen']];
        }
    }
    return $slides;
}

// =============================================
// DASHBOARD + ESTADÍSTICAS (Administrador y Pastor)
// =============================================
function iglesia_dashboard_stats() {
    global $wpdb;
    $stats = [];

    // Blog
    $counts = wp_count_posts('post');
    $stats['blog_publicadas'] = isset($counts->publish) ? (int)$counts->publish : 0;
    $stats['blog_borradores'] = isset($counts->draft) ? (int)$counts->draft : 0;
    $stats['blog_pendientes'] = isset($counts->pending) ? (int)$counts->pending : 0;
    $stats['blog_total'] = $stats['blog_publicadas'] + $stats['blog_borradores'] + $stats['blog_pendientes'];

    // Contenido activo/inactivo total
    $content_types = ['ministerio','predicador','faq','faq_seccion','himno','mision_vision','historia','imagen','carrusel_slide'];
    $total = 0; $activos = 0;
    $por_tipo = [];
    foreach ($content_types as $ct) {
        $c = wp_count_posts($ct);
        $n = is_object($c) && isset($c->publish) ? (int)$c->publish : 0;
        $por_tipo[$ct] = $n;
        $total += $n;
    }
    $stats['contenido_total'] = $total;
    $stats['contenido_por_tipo'] = $por_tipo;
    $stats['imagenes_registradas'] = $por_tipo['imagen'];

    // Eventos (entradas etiquetadas evento, fallback blog events count via category)
    $event_cat = get_term_by('slug', 'eventos', 'category');
    $stats['eventos'] = $event_cat ? (int)$event_cat->count : 0;

    // Usuarios por rol
    $ru = count_users();
    $stats['usuarios_total'] = (int)$ru['total_users'];
    $stats['usuarios_por_rol'] = $ru['avail_roles'];

    // Publicaciones por mes (últimos 6 meses)
    $fmt = [];
    for ($i = 5; $i >= 0; $i--) {
        $ym = date('Y-m', strtotime("-$i months"));
        $fmt[$ym] = 0;
    }
    $rows = $wpdb->get_results(
        "SELECT DATE_FORMAT(post_date, '%Y-%m') AS ym, COUNT(*) AS c
         FROM {$wpdb->posts}
         WHERE post_type='post' AND post_status='publish'
           AND post_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
         GROUP BY ym"
    );
    foreach ((array)$rows as $r) {
        if (isset($fmt[$r->ym])) $fmt[$r->ym] = (int)$r->c;
    }
    $stats['posts_por_mes'] = $fmt;

    // Autores más activos
    $rows = $wpdb->get_results(
        "SELECT p.post_author AS uid, COUNT(*) AS c
         FROM {$wpdb->posts} p
         WHERE p.post_type='post' AND p.post_status IN ('publish','pending','draft')
         GROUP BY p.post_author ORDER BY c DESC LIMIT 5"
    );
    $autores = [];
    foreach ((array)$rows as $r) {
        $u = get_userdata($r->uid);
        $autores[] = ['nombre' => $u ? $u->display_name : '—', 'count' => (int)$r->c];
    }
    $stats['top_autores'] = $autores;

    // Publicaciones por categoría
    $cats = get_categories(['hide_empty' => false]);
    $por_cat = [];
    foreach ($cats as $cat) {
        $por_cat[$cat->name] = (int)$cat->count;
    }
    arsort($por_cat);
    $stats['posts_por_categoria'] = array_slice($por_cat, 0, 6, true);

    // Actividad reciente
    $stats['actividad'] = array_slice((array)get_option('iglesia_activity_log', []), 0, 12);

    return $stats;
}

function iglesia_render_kpi_cards($stats) {
    $kpis = [
        ['label' => 'Publicaciones totales', 'value' => $stats['blog_total'], 'icon' => 'fa-newspaper', 'color' => '#2271b1'],
        ['label' => 'Publicaciones publicadas', 'value' => $stats['blog_publicadas'], 'icon' => 'fa-circle-check', 'color' => '#00a32a'],
        ['label' => 'Pendientes de revisión', 'value' => $stats['blog_pendientes'], 'icon' => 'fa-hourglass-half', 'color' => '#dba617'],
        ['label' => 'Imágenes registradas', 'value' => $stats['imagenes_registradas'], 'icon' => 'fa-images', 'color' => '#8e44ad'],
        ['label' => 'Eventos registrados', 'value' => $stats['eventos'], 'icon' => 'fa-calendar-days', 'color' => '#d63638'],
        ['label' => 'Usuarios registrados', 'value' => $stats['usuarios_total'], 'icon' => 'fa-users', 'color' => '#044b7f'],
        ['label' => 'Contenido activo', 'value' => $stats['contenido_total'], 'icon' => 'fa-layer-group', 'color' => '#50575e'],
        ['label' => 'Borradores', 'value' => $stats['blog_borradores'], 'icon' => 'fa-file-pen', 'color' => '#996800'],
    ];
    ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:16px;margin-bottom:24px;">
        <?php foreach ($kpis as $kpi): ?>
        <div style="background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:18px;display:flex;align-items:center;gap:14px;">
            <div style="width:46px;height:46px;border-radius:10px;background:<?php echo esc_attr($kpi['color']); ?>1a;color:<?php echo esc_attr($kpi['color']); ?>;display:flex;align-items:center;justify-content:center;font-size:19px;flex-shrink:0;">
                <span class="fas <?php echo esc_attr($kpi['icon']); ?>"></span>
            </div>
            <div>
                <div style="font-size:26px;font-weight:700;line-height:1;color:#1d2327;"><?php echo esc_html(number_format_i18n($kpi['value'])); ?></div>
                <div style="font-size:12px;color:#646970;margin-top:4px;"><?php echo esc_html($kpi['label']); ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}

function iglesia_render_bar_chart($titulo, $data, $suffix = '') {
    if (empty($data)) return;
    $max = max(max($data), 1);
    ?>
    <div class="igl-set-card">
        <h3><span class="dashicons dashicons-chart-bar"></span> <?php echo esc_html($titulo); ?></h3>
        <?php foreach ($data as $label => $value):
            $pct = round(($value / $max) * 100); ?>
        <div style="margin-bottom:10px;">
            <div style="display:flex;justify-content:space-between;font-size:12px;color:#3c434a;margin-bottom:3px;">
                <span style="font-weight:600;"><?php echo esc_html($label); ?></span>
                <span><?php echo esc_html($value . $suffix); ?></span>
            </div>
            <div style="height:10px;background:#f0f0f1;border-radius:6px;overflow:hidden;">
                <div style="height:100%;width:<?php echo max($pct, 2); ?>%;background:linear-gradient(90deg,#2271b1,#72aee6);border-radius:6px;"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}

function iglesia_render_actividad_reciente($actividad) {
    ?>
    <div class="igl-set-card">
        <h3><span class="dashicons dashicons-clock"></span> Actividad Reciente</h3>
        <?php if (empty($actividad)): ?>
            <p style="color:#646970;font-size:13px;">Aún no hay actividad registrada.</p>
        <?php else: ?>
            <ul style="margin:0;list-style:none;">
                <?php foreach ($actividad as $item): ?>
                <li style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid #f0f0f1;align-items:flex-start;">
                    <span class="dashicons dashicons-marker" style="color:#2271b1;margin-top:1px;"></span>
                    <div style="font-size:13px;color:#3c434a;">
                        <strong><?php echo esc_html($item['u']); ?></strong> <?php echo esc_html($item['a']); ?>
                        <?php if ($item['o']): ?><em>“<?php echo esc_html(wp_trim_words($item['o'], 8)); ?>”</em><?php endif; ?>
                        <span style="display:block;font-size:11px;color:#a7aaad;margin-top:2px;"><?php echo esc_html(date_i18n('j M Y, H:i', $item['t'])); ?></span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php
}

// Widget en el Escritorio nativo (visible solo a Administrador y Pastor)
add_action('wp_dashboard_setup', function() {
    $role = iglesia_user_role_slug();
    if (!in_array($role, ['pastor', 'administrator'], true)) return;

    wp_add_dashboard_widget(
        'iglesia_dashboard_widget',
        '📊 Resumen del Portal',
        function() {
            $stats = iglesia_dashboard_stats();
            echo '<style>@media(min-width:1200px){#iglesia_dashboard_widget .igl-dash-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;}}</style>';
            echo '<div>';
            iglesia_render_kpi_cards($stats);
            echo '<div class="igl-dash-grid">';
            $meses = [];
            foreach ($stats['posts_por_mes'] as $ym => $c) {
                $meses[date_i18n('M y', strtotime($ym . '-01'))] = $c;
            }
            iglesia_render_bar_chart('Publicaciones por mes (Blog)', $meses);
            iglesia_render_actividad_reciente($stats['actividad']);
            echo '</div></div>';
        }
    );

    // El Pastor ve únicamente nuestro widget
    if ($role === 'pastor') {
        remove_action('welcome_panel', 'wp_welcome_panel');
    }
});

// Página Dashboard completa (menú Pastor; también utilizable por Administrador vía URL directa)
function iglesia_dashboard_page() {
    if (!in_array(iglesia_user_role_slug(), ['pastor', 'administrator'], true)) {
        wp_die('Acceso denegado.');
    }
    $stats = iglesia_dashboard_stats();
    ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:10px;">
            <span class="dashicons dashicons-dashboard" style="font-size:30px;width:30px;height:30px;"></span>
            Panel Ejecutivo
        </h1>
        <p style="color:#646970;">Estado general del portal actualizado en tiempo real.</p>
        <?php iglesia_render_kpi_cards($stats); ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="igl-dash-cols">
            <?php
            $meses = [];
            foreach ($stats['posts_por_mes'] as $ym => $c) {
                $meses[date_i18n('M y', strtotime($ym . '-01'))] = $c;
            }
            iglesia_render_bar_chart('Publicaciones por mes (Blog)', $meses);

            $roles_labels = ['administrator' => 'Administradores', 'editor' => 'Editores', 'colaborador' => 'Colaboradores', 'pastor' => 'Pastores', 'subscriber' => 'Suscriptores'];
            $roles_data = [];
            foreach ($stats['usuarios_por_rol'] as $rol => $n) {
                $roles_data[isset($roles_labels[$rol]) ? $roles_labels[$rol] : ucfirst($rol)] = $n;
            }
            iglesia_render_bar_chart('Usuarios por rol', $roles_data);

            iglesia_render_bar_chart('Publicaciones por categoría (Top)', $stats['posts_por_categoria']);
            iglesia_render_actividad_reciente($stats['actividad']);
            ?>
        </div>
        <style>@media(max-width:1100px){.igl-dash-cols{grid-template-columns:1fr !important;}}</style>
    </div>
    <?php
}

// Página Estadísticas completa
function iglesia_stats_page() {
    if (!in_array(iglesia_user_role_slug(), ['pastor', 'administrator'], true)) {
        wp_die('Acceso denegado.');
    }
    $stats = iglesia_dashboard_stats();
    $tipos_labels = [
        'ministerio' => 'Ministerios', 'predicador' => 'Predicadores', 'faq' => 'Preguntas FAQ',
        'faq_seccion' => 'Secciones FAQ', 'himno' => 'Himnos', 'mision_vision' => 'Misión/Visión',
        'historia' => 'Historia', 'imagen' => 'Imágenes', 'carrusel_slide' => 'Slides Carrusel',
    ];
    ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:10px;">
            <span class="dashicons dashicons-chart-bar" style="font-size:30px;width:30px;height:30px;"></span>
            Estadísticas e Indicadores
        </h1>
        <p style="color:#646970;">Datos reales del sistema actualizados ahora mismo.</p>
        <?php iglesia_render_kpi_cards($stats); ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;" class="igl-dash-cols">
            <?php
            $meses = [];
            foreach ($stats['posts_por_mes'] as $ym => $c) {
                $meses[date_i18n('M Y', strtotime($ym . '-01'))] = $c;
            }
            iglesia_render_bar_chart('Blog — Publicaciones por mes', $meses);

            $estados = ['Publicadas' => $stats['blog_publicadas'], 'Borradores' => $stats['blog_borradores'], 'Pendientes de revisión' => $stats['blog_pendientes']];
            iglesia_render_bar_chart('Blog — Estado de publicaciones', $estados);

            $contenido_data = [];
            foreach ($stats['contenido_por_tipo'] as $tipo => $n) {
                $contenido_data[isset($tipos_labels[$tipo]) ? $tipos_labels[$tipo] : $tipo] = $n;
            }
            iglesia_render_bar_chart('Contenido registrado por módulo', $contenido_data);

            $roles_labels = ['administrator' => 'Administradores', 'editor' => 'Editores', 'colaborador' => 'Colaboradores', 'pastor' => 'Pastores', 'subscriber' => 'Suscriptores'];
            $roles_data = [];
            foreach ($stats['usuarios_por_rol'] as $rol => $n) {
                $roles_data[isset($roles_labels[$rol]) ? $roles_labels[$rol] : ucfirst($rol)] = $n;
            }
            iglesia_render_bar_chart('Usuarios por rol', $roles_data);
            ?>
        </div>

        <div class="igl-set-card" style="max-width:480px;">
            <h3><span class="dashicons dashicons-superhero"></span> Autores más activos</h3>
            <?php if (empty($stats['top_autores'])): ?>
                <p style="color:#646970;font-size:13px;">Sin autores aún.</p>
            <?php else: ?>
                <?php foreach ($stats['top_autores'] as $a): ?>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0f0f1;font-size:13px;">
                    <span>👤 <?php echo esc_html($a['nombre']); ?></span>
                    <strong><?php echo esc_html($a['count']); ?> pub.</strong>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <style>@media(max-width:1100px){.igl-dash-cols{grid-template-columns:1fr !important;}}</style>
    </div>
    <?php
}

// =============================================
// BLOG — Listado mantenible (compartido por home.php y la página /blog/)
// =============================================
function iglesia_blog_url() {
    // Página "blog" > Página de entradas configurada en Ajustes > fallback al inicio
    $page = get_page_by_path('blog');
    if ($page) return get_permalink($page->ID);
    $for_posts = (int)get_option('page_for_posts');
    if ($for_posts) return get_permalink($for_posts);
    return home_url('/blog/');
}

function iglesia_default_blog_posts() {
    return [
        ['¿Qué dice la Biblia sobre la oración?', 'La oración es el puente que nos conecta con Dios. En este artículo exploramos los fundamentos bíblicos de esta práctica tan esencial para el creyente.', 'Espiritualidad', 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=600&auto=format&fit=crop'],
        ['La familia en el diseño de Dios', 'Desde el Génesis, Dios diseñó la familia como el fundamento de la sociedad. Descubramos juntos su propósito y cómo fortalecerla en la fe.', 'Familia', 'https://images.unsplash.com/photo-1484820540004-14229fe36ca4?w=600&auto=format&fit=crop'],
        ['Cómo estudiar la Biblia de forma efectiva', 'Muchos creyentes desean conocer más la Palabra de Dios pero no saben cómo comenzar. Compartimos métodos prácticos y sencillos.', 'Biblia', 'https://images.unsplash.com/photo-1501167786227-4cba60f6d58f?w=600&auto=format&fit=crop'],
        ['El poder de la alabanza en tiempos difíciles', 'La historia bíblica está llena de momentos donde la alabanza fue el arma de victoria. Aprende a adorar en medio de la tormenta.', 'Adoración', 'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=600&auto=format&fit=crop'],
        ['La fe: ¿Qué es y cómo crece?', 'La fe es la sustancia de lo que se espera y la evidencia de lo que no se ve. ¿Pero cómo la cultivamos en nuestra vida diaria?', 'Fe', 'https://images.unsplash.com/photo-1445887374063-34abd495852a?w=600&auto=format&fit=crop'],
        ['Servir: La llamada de todo creyente', 'Jesús nos llamó a ser siervos, no señores. Descubramos las formas en que podemos servir en nuestra iglesia y comunidad.', 'Ministerio', 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=600&auto=format&fit=crop'],
    ];
}

function iglesia_render_pagination($query = null) {
    static $css_printed = false;
    $total = 0;
    if ($query instanceof WP_Query) {
        $total = (int)$query->max_num_pages;
    } elseif (isset($GLOBALS['wp_query']->max_num_pages)) {
        $total = (int)$GLOBALS['wp_query']->max_num_pages;
    }
    if ($total < 2) return;

    if (!$css_printed) {
        echo '<style>
        .igl-pagination { display:flex; gap:8px; justify-content:center; margin-top:44px; flex-wrap:wrap; }
        .igl-pagination .page-numbers {
            min-width:38px; height:38px; display:inline-flex; align-items:center; justify-content:center;
            border-radius:10px; background:#fff; border:1px solid #e2e5ea; color:#3c434a;
            font-weight:600; text-decoration:none; padding:0 14px; transition:all .2s ease; font-size:.9rem;
        }
        .igl-pagination .page-numbers:hover { border-color:var(--blue-primary); color:var(--blue-primary); }
        .igl-pagination .page-numbers.current { background:var(--blue-primary); border-color:var(--blue-primary); color:#fff; }
        </style>';
        $css_printed = true;
    }

    $args = ['prev_text' => '&larr;', 'next_text' => '&rarr;', 'mid_size' => 2, 'total' => $total];
    if ($query instanceof WP_Query) {
        $args['current'] = max(1, (int)get_query_var('paged'), (int)get_query_var('page'));
    }

    echo '<nav class="igl-pagination">' . paginate_links($args) . '</nav>';
}

function iglesia_page_blog() {
    $paged = max(1, (int)get_query_var('paged'), (int)get_query_var('page'));

    // Publicaciones publicadas y activas, las más recientes primero, paginadas
    $blog_query = new WP_Query([
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 6,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'paged'               => $paged,
        'ignore_sticky_posts' => true,
        'meta_query'          => iglesia_meta_activa('blog_activo'),
    ]);

    $categorias = get_categories(['hide_empty' => false]);
    ?>
    <section class="blog-section">
        <div class="container">
            <p class="section-title">Reflexiones y Enseñanzas</p>
            <h2 class="section-heading" style="margin-bottom:26px;">Publicaciones del Blog</h2>

            <?php if (!empty($categorias)) : ?>
            <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin-bottom:36px;">
                <?php foreach ($categorias as $cat): ?>
                <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"
                   style="padding:7px 18px;border-radius:20px;font-size:.82rem;font-weight:600;background:#f0f3f8;color:#3c434a;text-decoration:none;transition:all .2s ease;"
                   onmouseover="this.style.background='var(--blue-primary)';this.style.color='#fff';"
                   onmouseout="this.style.background='#f0f3f8';this.style.color='#3c434a';">
                    <?php echo esc_html($cat->name); ?> <span style="opacity:.6;">(<?php echo (int)$cat->count; ?>)</span>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($blog_query->have_posts()) : ?>
            <div class="blog-grid">
                <?php while ($blog_query->have_posts()): $blog_query->the_post();
                    $cats = get_the_category();
                    $cat_name = !empty($cats) ? $cats[0]->name : 'Blog';
                    $extracto_custom = get_post_meta(get_the_ID(), 'blog_extracto', true);
                    $resumen = $extracto_custom ?: wp_trim_words(get_the_excerpt(), 20, '...');
                ?>
                <article class="blog-card">
                    <?php if (has_post_thumbnail()): ?>
                        <img src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1501167786227-4cba60f6d58f?w=600&auto=format&fit=crop" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                    <div class="blog-card-body">
                        <span class="category"><?php echo esc_html($cat_name); ?></span>
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php echo esc_html($resumen); ?></p>
                        <a href="<?php the_permalink(); ?>" class="read-more">Leer más &rarr;</a>
                    </div>
                </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <?php iglesia_render_pagination($blog_query); ?>

            <?php else: ?>
            <?php if (current_user_can('edit_posts')): ?>
            <div style="background:#f0f6ff;border:1px solid #c5d9f1;border-radius:10px;padding:16px 22px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <p style="margin:0;font-size:.92rem;color:#2c5f8a;">Aún no hay publicaciones. Estas tarjetas son ejemplos de demostración visibles solo para ti.</p>
                <a href="<?php echo esc_url(admin_url('post-new.php')); ?>" style="background:#fff;border:1px solid #c5d9f1;border-radius:8px;padding:8px 16px;font-weight:600;color:#2271b1;text-decoration:none;">+ Crear primera publicación</a>
            </div>
            <?php endif; ?>
            <div class="blog-grid">
                <?php foreach (iglesia_default_blog_posts() as $post_plc): ?>
                <div class="blog-card">
                    <img src="<?php echo esc_url($post_plc[3]); ?>" alt="<?php echo esc_attr($post_plc[0]); ?>">
                    <div class="blog-card-body">
                        <span class="category"><?php echo esc_html($post_plc[2]); ?></span>
                        <h3><?php echo esc_html($post_plc[0]); ?></h3>
                        <p><?php echo esc_html(mb_substr($post_plc[1], 0, 100)) . '...'; ?></p>
                        <span class="read-more" style="color:var(--text-muted);cursor:default;">Próximamente &rarr;</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

// =============================================
// BLOG / MINISTERIOS / PREDICADORES — Pantallas de edición al estándar "Iglesia"
// =============================================

// Editor clásico (sin bloques) para consistencia visual con los demás mantenimientos
add_filter('use_block_editor_for_post_type', function($use_block_editor, $post_type) {
    if (in_array($post_type, ['post', 'ministerio', 'predicador'], true)) return false;
    return $use_block_editor;
}, 10, 2);

// Placeholder del título acorde al módulo
add_filter('enter_title_here', function($text, $post) {
    if ($post instanceof WP_Post) {
        $placeholders = [
            'post'       => 'Título de la publicación…',
            'ministerio' => 'Nombre del Ministerio…',
            'predicador' => 'Nombre del Predicador…',
        ];
        if (isset($placeholders[$post->post_type])) return $placeholders[$post->post_type];
    }
    return $text;
}, 10, 2);

// Quitar cajas nativas que los formularios propios sustituyen
add_action('add_meta_boxes', function() {
    remove_meta_box('postimagediv', 'post', 'side');        // Imagen destacada
    remove_meta_box('postexcerpt', 'post', 'normal');       // Extracto manual
    remove_meta_box('postimagediv', 'ministerio', 'side');  // Imagen destacada
    remove_meta_box('postimagediv', 'predicador', 'side');  // Imagen destacada
}, 20);

// Formulario principal del Blog (patrón Iglesia)
add_action('add_meta_boxes', function() {
    add_meta_box(
        'blog_info',
        '📄 Contenido de la Publicación',
        function($post) {
            wp_nonce_field('blog_meta', 'blog_meta_nonce');

            $thumb_id = get_post_meta($post->ID, '_thumbnail_id', true);
            $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium') : '';
            $extracto = get_post_meta($post->ID, 'blog_extracto', true);
            $activo = get_post_meta($post->ID, 'blog_activo', true);
            $activo = ($activo === '' || $activo === '1') ? true : false;
            ?>
            <div class="igl-info-box">
                <p><span class="dashicons dashicons-edit"></span>Escribe el contenido principal en el editor grande de abajo. La imagen y la configuración se administran aquí.</p>
            </div>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-format-image"></span> Imagen de portada <span style="color:#d63638;font-weight:normal;">*</span></h3>
                <div style="display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap;">
                    <div class="igl-img-preview" id="blog-img-preview" onclick="document.getElementById('blog-img-btn').click()" style="flex-shrink:0;">
                        <?php if ($thumb_url): ?>
                            <img src="<?php echo esc_url($thumb_url); ?>" alt="">
                        <?php else: ?>
                            <div class="placeholder"><span class="dashicons dashicons-images-alt"></span><br><small>Click para subir imagen</small></div>
                        <?php endif; ?>
                    </div>
                    <div style="min-width:200px;">
                        <input type="hidden" id="blog_thumbnail_id" name="blog_thumbnail_id" value="<?php echo esc_attr($thumb_id); ?>">
                        <button type="button" class="button button-primary" id="blog-img-btn">
                            <span class="dashicons dashicons-admin-media" style="font-size:14px;width:14px;height:14px;margin:4px 2px 0 0;"></span>
                            Seleccionar imagen
                        </button>
                        <p class="hint" style="margin-top:10px;">Recomendado: 1200x700px horizontal.<br>Sin imagen se usa una predeterminada en las tarjetas.</p>
                    </div>
                </div>
            </div>

            <div class="igl-set-card">
                <h3><span class="dashicons dashicons-text"></span> Resumen corto</h3>
                <div class="igl-set-field">
                    <label for="blog_extracto">Extracto para las tarjetas</label>
                    <textarea name="blog_extracto" id="blog_extracto" placeholder="Resumen breve que aparece bajo el título en el listado del Blog..." style="min-height:60px;"><?php echo esc_textarea($extracto); ?></textarea>
                    <p class="hint">Máx. ~140 caracteres. Si lo dejas vacío, se genera automáticamente desde el contenido.</p>
                </div>
            </div>

            <?php iglesia_render_toggle_box('blog_activo', $activo); ?>

            <script>
            jQuery(function($) {
                var blogFrame;
                $('#blog-img-btn').on('click', function(e) {
                    e.preventDefault();
                    if (blogFrame) { blogFrame.open(); return; }
                    blogFrame = wp.media({
                        title: 'Seleccionar imagen de portada',
                        button: { text: 'Usar esta imagen' },
                        multiple: false,
                        library: { type: 'image' }
                    });
                    blogFrame.on('select', function() {
                        var att = blogFrame.state().get('selection').first().toJSON();
                        var url = att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url;
                        $('#blog_thumbnail_id').val(att.id);
                        $('#blog-img-preview').html('<img src="' + url + '" alt="">');
                    });
                });
            });
            </script>
            <?php
        },
        'post',
        'normal',
        'high'
    );
});

// Guardar formulario Blog
add_action('save_post_post', function($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['blog_meta_nonce']) || !wp_verify_nonce($_POST['blog_meta_nonce'], 'blog_meta')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['blog_thumbnail_id'])) {
        update_post_meta($post_id, '_thumbnail_id', intval($_POST['blog_thumbnail_id']));
    }
    if (isset($_POST['blog_extracto'])) {
        update_post_meta($post_id, 'blog_extracto', sanitize_textarea_field($_POST['blog_extracto']));
    }
    update_post_meta($post_id, 'blog_activo', isset($_POST['blog_activo']) ? '1' : '0');
});

// Archivos públicos del Blog (inicio de posts, categorías, etiquetas, fechas): solo activos
add_action('pre_get_posts', function($q) {
    if (is_admin() || !$q->is_main_query()) return;
    if ($q->is_home() || $q->is_category() || $q->is_tag() || $q->is_author() || $q->is_date()) {
        $meta = (array)$q->get('meta_query');
        $meta[] = iglesia_meta_activa('blog_activo');
        $q->set('meta_query', $meta);
    }
});

// Publicación desactivada → fuera del sitio público (redirige al listado)
add_action('template_redirect', function() {
    if (is_singular('post') && get_post_meta(get_the_ID(), 'blog_activo', true) === '0') {
        wp_redirect(iglesia_blog_url());
        exit;
    }
});

// Pulido visual de pantallas de edición (Blog, Ministerios, Predicadores)
add_action('admin_head-post.php', 'iglesia_blog_edit_screen_css');
add_action('admin_head-post-new.php', 'iglesia_blog_edit_screen_css');
function iglesia_blog_edit_screen_css() {
    global $post_type;
    if (!in_array($post_type, ['post', 'ministerio', 'predicador'], true)) return;
    ?>
    <style>
    /* Estándar visual "Iglesia" aplicado a las pantallas de edición */
    #post-body-content #titlewrap input {
        font-size: 26px; padding: 12px 16px; border-radius: 8px;
        border-color: #dcdcde; background: #fff; font-weight: 600;
    }
    #post-body-content #titlewrap input:focus {
        border-color:#2271b1; outline:none; box-shadow:0 0 0 2px rgba(34,113,177,0.2);
    }
    /* Editor centrado y ancho cómodo */
    .block-editor-page #poststuff, #poststuff {
        max-width: 980px; margin-left: auto; margin-right: auto;
    }
    /* Cajas de taxonomía (categorías/etiquetas) con estética de tarjeta */
    #category-tabs a, #tagsdiv-post_tag a { text-decoration:none; }
    /* Quitar ruido: slugs y revisions abajo */
    #poststuff #submitdiv .misc-pub-section.curtime { border-bottom:none; }
    </style>
    <?php
}

// Columnas estándar en el listado de publicaciones (miniatura + estado)
iglesia_add_std_columns('post', ['estado_meta' => 'blog_activo', 'thumb' => true, 'thumb_meta' => '_thumbnail_id', 'no_orden' => true]);
add_action('admin_menu', function() {
    global $menu, $submenu;

    foreach ($menu as &$item) {
        if (isset($item[2]) && $item[2] === 'edit.php') {
            $item[0] = 'Blog';
        }
    }
    unset($item);

    if (isset($submenu['edit.php'])) {
        foreach ($submenu['edit.php'] as &$sub) {
            $sub[0] = str_replace(
                ['Todas las entradas', 'Añadir nueva entrada', 'Añadir entrada', 'Agregar nueva entrada', 'Agregar entrada', 'Entradas', 'Buscar entradas'],
                ['Todas las publicaciones', 'Añadir nueva publicación', 'Añadir publicación', 'Agregar nueva publicación', 'Agregar publicación', 'Publicaciones', 'Buscar publicaciones'],
                $sub[0]
            );
        }
        unset($sub);
    }
}, 999);
