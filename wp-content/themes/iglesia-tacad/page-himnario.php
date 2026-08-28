<?php
/**
 * Himnario Page Template
 */
get_header();
iglesia_page_banner('Himnario', 'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=1600&auto=format&fit=crop&q=60');

// Obtener himnos activos ordenados (sin meta = activo)
$himnos = new WP_Query([
    'post_type' => 'himno',
    'posts_per_page' => -1,
    'meta_query' => iglesia_meta_activa('himno_activo'),
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);
?>

<section class="hymnal-section">
    <div class="container">
        <p class="section-title">Adoración</p>
        <h2 class="section-heading" style="margin-bottom:30px;">Himnario y Alabanzas</h2>
        <div class="hymnal-search">
            <input type="text" id="hymn-search" placeholder="🔍 Buscar himno por nombre...">
        </div>
        <?php if ($himnos->have_posts()) : ?>
        <div class="hymns-grid" id="hymns-grid">
            <?php $i = 1; while ($himnos->have_posts()) : $himnos->the_post();
                $descripcion = get_post_meta(get_the_ID(), 'himno_descripcion', true);
            ?>
                <div class="hymn-card"
                     data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>"
                     data-lyrics="<?php echo esc_attr($descripcion); ?>"
                     onclick="openHymn('<?php echo esc_js(get_the_title()); ?>', `<?php echo esc_js($descripcion); ?>`)">
                    <p class="hymn-num">Himno #<?php echo esc_html($i); ?></p>
                    <h3><?php echo esc_html(get_the_title()); ?></h3>
                    <p><?php echo esc_html(wp_trim_words($descripcion, 8)); ?></p>
                </div>
            <?php $i++; endwhile; wp_reset_postdata(); ?>
        </div>
        <?php else : ?>
        <p style="text-align:center;color:var(--text-muted);">No hay himnos registrados. <a href="<?php echo admin_url('post-new.php?post_type=himno'); ?>">Agregar uno nuevo</a></p>
        <?php endif; ?>
    </div>
</section>

<!-- Modal for lyrics -->
<div class="hymn-modal" id="hymn-modal">
    <div class="hymn-modal-inner">
        <span class="hymn-modal-close" onclick="closeHymn()">&times;</span>
        <h2 id="hymn-modal-title"></h2>
        <div class="hymn-lyrics" id="hymn-modal-lyrics"></div>
    </div>
</div>

<script>
function openHymn(title, lyrics) {
    document.getElementById('hymn-modal-title').textContent = title;
    document.getElementById('hymn-modal-lyrics').textContent = lyrics;
    document.getElementById('hymn-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeHymn() {
    document.getElementById('hymn-modal').classList.remove('open');
    document.body.style.overflow = '';
}

document.getElementById('hymn-modal').addEventListener('click', function(e){
    if (e.target === this) closeHymn();
});

document.getElementById('hymn-search').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.hymn-card').forEach(card => {
        card.style.display = card.dataset.title.includes(q) ? '' : 'none';
    });
});
</script>

<?php get_footer(); ?>
