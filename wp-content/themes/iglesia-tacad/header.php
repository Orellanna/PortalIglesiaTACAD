<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tabernáculo Cristiano AD - Una iglesia de fe, esperanza y amor. Te esperamos con los brazos abiertos.">
    <meta name="theme-color" content="#1a237e">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header id="site-header">
    <div class="header-inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo-wrap">
            <?php
            $logo_path = get_template_directory() . '/images/logo.jpeg';
            $logo_url  = get_template_directory_uri() . '/images/logo.jpeg';
            if (file_exists($logo_path)) :
            ?>
                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" class="site-logo-img">
            <?php elseif (function_exists('the_custom_logo') && has_custom_logo()) :
                the_custom_logo();
            else :
                ?>
                <div class="site-logo">
                    <span class="logo-line1">Tabernáculo Cristiano</span>
                    <span class="logo-line2">Asambleas de Dios</span>
                    <span class="logo-line3">La Palma</span>
                </div>
            <?php endif;
            ?>
        </a>

        <nav id="primary-nav" aria-label="Menú principal">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary_menu',
                'menu_class'     => 'nav-menu',
                'container'      => false,
                'fallback_cb'    => function() {
                    // Fallback hardcoded menu
                    echo '<ul>';
                    $pages = [
                        'inicio'            => 'Inicio',
                        'jesus'             => 'Jesús',
                        'acerca-de'         => 'Acerca de',
                        'sermones'          => 'Sermones',
                        'himnario'          => 'Himnario',
                        'blog'              => 'Blog',
                        'ofrenda'           => 'Ofrenda',
                    ];
                    foreach ($pages as $slug => $label) {
                        $page = get_page_by_path($slug);
                        $url  = $page ? get_permalink($page->ID) : home_url('/' . $slug . '/');
                        $current = (is_page($slug) || ($slug === 'inicio' && is_front_page())) ? ' class="current-menu-item"' : '';

                        // Submenus
                        if ($slug === 'acerca-de') {
                            echo '<li' . $current . '><a href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
                            echo '<ul>';
                            $subs = ['visitanos'=>'Visítanos','historia'=>'Historia','mision-y-vision'=>'Misión y Visión','ministerios'=>'Ministerios','preguntas-frecuentes'=>'Preguntas Frecuentes','contacto'=>'Contacto'];
                            foreach ($subs as $s_slug => $s_label) {
                                $sp  = get_page_by_path($s_slug);
                                $su  = $sp ? get_permalink($sp->ID) : home_url('/' . $s_slug . '/');
                                echo '<li><a href="' . esc_url($su) . '">' . esc_html($s_label) . '</a></li>';
                            }
                            echo '</ul></li>';
                        } elseif ($slug === 'sermones') {
                            echo '<li' . $current . '><a href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
                            echo '<ul>';
                            $subs = ['pastor-melky'=>'Pastor Melky','pastor-orlando'=>'Pastor Orlando','pastor-toby-jr'=>'Pastor Toby Jr.','pastor-fundador'=>'Pastor Fundador'];
                            foreach ($subs as $s_slug => $s_label) {
                                $sp  = get_page_by_path($s_slug);
                                $su  = $sp ? get_permalink($sp->ID) : home_url('/' . $s_slug . '/');
                                echo '<li><a href="' . esc_url($su) . '">' . esc_html($s_label) . '</a></li>';
                            }
                            echo '</ul></li>';
                        } else {
                            echo '<li' . $current . '><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
                        }
                    }
                    echo '</ul>';
                }
            ]);
            ?>
        </nav>

        <div class="nav-social">
            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        </div>

        <a id="live-status-btn" href="#" class="live-status-btn live-offline" aria-label="En vivo">
            <span id="live-indicator" class="live-indicator"></span>
            <span class="live-text">En vivo</span>
        </a>

        <button class="hamburger" id="nav-toggle" aria-label="Abrir menú">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

<div id="content-wrap">
