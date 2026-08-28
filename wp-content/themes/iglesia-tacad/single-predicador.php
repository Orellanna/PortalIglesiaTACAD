<?php
/**
 * Single Predicador — Página individual generada desde el CPT
 */
get_header();
iglesia_page_banner(
    get_the_title(),
    'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1600&auto=format&fit=crop&q=60'
);
?>
<section class="pastors-section">
    <div class="container">
        <?php
        $post_id   = get_the_ID();
        $name      = get_the_title();
        $rol       = get_post_meta($post_id, '_iglesia_rol', true);
        $bio       = get_the_content();
        $photo_url = iglesia_get_predicador_foto($post_id) ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop';
        $videos    = iglesia_get_predicador_videos($post_id);
        ?>
        <div class="pastor-header">
            <img src="<?php echo esc_url($photo_url); ?>" alt="<?php echo esc_attr($name); ?>" class="pastor-photo">
            <div class="pastor-info">
                <?php if ($rol): ?><p class="role"><?php echo esc_html($rol); ?></p><?php endif; ?>
                <h2><?php echo esc_html($name); ?></h2>
                <?php if ($bio): ?><p><?php echo nl2br(esc_html($bio)); ?></p><?php endif; ?>
            </div>
        </div>

        <?php if (!empty($videos)): ?>
        <h3 style="font-family:var(--font-display);font-size:1.5rem;font-weight:800;color:var(--blue-primary);margin-bottom:25px;">📹 Sermones de <?php echo esc_html($name); ?></h3>
        <div class="sermons-grid">
            <?php foreach ($videos as $v): ?>
                <div class="sermon-card">
                    <div class="sermon-video">
                        <iframe src="<?php echo esc_url($v['embed_url']); ?>"
                                title="Sermón"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                    <div class="sermon-body">
                        <h3><?php echo esc_html($v['title'] ?: 'Sermón'); ?></h3>
                        <p class="date">📅 Publicado</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="text-align:center;color:var(--text-muted);padding:30px 0;">Aún no hay sermones publicados para este predicador.</p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
