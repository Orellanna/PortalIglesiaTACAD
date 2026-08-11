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
    $theme_version = '5.1.0';
    wp_enqueue_style('iglesia-style', get_stylesheet_uri(), [], $theme_version);
    // Font Awesome 6 (icons)
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], '6.5.0');
    wp_enqueue_script('iglesia-main', get_template_directory_uri() . '/js/main.js', [], $theme_version, true);
    wp_enqueue_script('iglesia-live-status', get_template_directory_uri() . '/js/live-status.js', [], $theme_version, true);
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
