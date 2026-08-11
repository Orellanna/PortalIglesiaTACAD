<?php
require_once __DIR__ . '/wp-load.php';

// Force flush rewrite rules for clean URL
global $wp_rewrite;
$wp_rewrite->set_permalink_structure('/%postname%/');
$wp_rewrite->flush_rules(true);

// Also verify Apache mod_rewrite via .htaccess
$htaccess = ABSPATH . '.htaccess';
$htaccess_content = file_get_contents($htaccess);
echo "<h2>Estado del Sitio</h2>";
echo "<p><b>.htaccess:</b> " . (file_exists($htaccess) ? "✅ Existe" : "❌ No existe") . "</p>";
echo "<p><b>Permalinks flush:</b> ✅ Hecho</p>";

// Show current pages
$pages = get_posts(['post_type' => 'page', 'numberposts' => -1, 'post_status' => 'publish']);
echo "<h3>Páginas publicadas:</h3><ul>";
foreach ($pages as $p) {
    echo "<li><a href='" . get_permalink($p->ID) . "'>" . $p->post_title . "</a> → " . get_page_uri($p->ID) . "</li>";
}
echo "</ul>";
echo "<p><a href='" . home_url() . "' style='background:#1a1aab;color:white;padding:10px 25px;border-radius:20px;text-decoration:none;'>🌐 Ir al Inicio</a></p>";
?>
