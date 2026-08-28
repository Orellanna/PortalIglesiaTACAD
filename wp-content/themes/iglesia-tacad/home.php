<?php
/**
 * Blog/Archive page template (índice de publicaciones)
 * El listado vive en iglesia_page_blog() — functions.php
 */
get_header();
iglesia_page_banner('Blog', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1600&auto=format&fit=crop&q=60');

iglesia_page_blog();

get_footer();
