<?php
/**
 * Single Ministerio — Página individual dinámica desde el CPT
 */
get_header();

while (have_posts()) : the_post();
    $post_id     = get_the_ID();
    $imagen_id   = get_post_meta($post_id, 'ministerio_imagen_id', true);
    $imagen_url  = $imagen_id ? wp_get_attachment_image_url($imagen_id, 'large') : 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?w=1200&auto=format&fit=crop';
    $responsable = get_post_meta($post_id, 'min_responsable', true);
    $horarios    = get_post_meta($post_id, 'min_horarios', true);
    $lugar       = get_post_meta($post_id, 'min_lugar', true);
    $contacto    = get_post_meta($post_id, 'min_contacto', true);
    $facebook    = get_post_meta($post_id, 'min_facebook', true);
    $instagram   = get_post_meta($post_id, 'min_instagram', true);
    $extra       = [];
    if ($responsable) $extra[] = ['icon' => '👤', 'label' => 'Responsable', 'value' => $responsable];
    if ($lugar)       $extra[] = ['icon' => '📍', 'label' => 'Lugar', 'value' => $lugar];
    if ($contacto)    $extra[] = ['icon' => '📞', 'label' => 'Contacto', 'value' => $contacto];

    iglesia_page_banner(get_the_title(), $imagen_url);
?>
<section style="padding:var(--section-pad);">
    <div class="container" style="max-width:1000px;">
        <div style="display:grid;grid-template-columns:1.2fr .8fr;gap:50px;align-items:start;" class="min-detail-grid">
            <!-- Contenido principal -->
            <div>
                <img src="<?php echo esc_url($imagen_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"
                     style="width:100%;height:320px;object-fit:cover;border-radius:20px;box-shadow:var(--shadow);margin-bottom:35px;" loading="lazy">
                <div style="font-size:1.05rem;line-height:1.9;color:var(--text-dark);">
                    <?php the_content(); ?>
                </div>
                <?php if (get_post_meta($post_id, 'ministerio_descripcion', true)): ?>
                <p style="margin-top:25px;padding:18px 22px;background:var(--section-bg);border-left:4px solid var(--blue-primary);border-radius:8px;color:var(--text-muted);font-size:0.95rem;">
                    <?php echo esc_html(get_post_meta($post_id, 'ministerio_descripcion', true)); ?>
                </p>
                <?php endif; ?>
            </div>

            <!-- Panel lateral de información -->
            <aside style="background:#fff;border-radius:20px;box-shadow:var(--shadow-sm);border:1px solid rgba(0,0,0,0.04);padding:30px;position:sticky;top:90px;">
                <h3 style="font-family:var(--font-display);font-size:1.15rem;font-weight:800;color:var(--blue-primary);margin-bottom:22px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-circle-info"></i> Información
                </h3>
                <?php foreach ($extra as $row): ?>
                <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #f0f0f1;">
                    <span style="font-size:1.3rem;"><?php echo $row['icon']; ?></span>
                    <div>
                        <p style="margin:0;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);"><?php echo esc_html($row['label']); ?></p>
                        <p style="margin:2px 0 0;font-size:0.95rem;color:var(--text-dark);"><?php echo esc_html($row['value']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php if ($horarios): ?>
                <div style="padding:12px 0;border-bottom:1px solid #f0f0f1;">
                    <div style="display:flex;gap:12px;">
                        <span style="font-size:1.3rem;">🕐</span>
                        <div style="flex:1;">
                            <p style="margin:0;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Horarios</p>
                            <?php foreach (preg_split('/\r\n|\r|\n/', $horarios) as $line): if (!trim($line)) continue; ?>
                            <p style="margin:4px 0 0;font-size:0.92rem;color:var(--text-dark);"><?php echo esc_html(trim($line)); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($facebook || $instagram): ?>
                <div style="display:flex;gap:12px;margin-top:20px;">
                    <?php if ($facebook): ?>
                    <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener" aria-label="Facebook"
                       style="width:42px;height:42px;border-radius:50%;background:#1877f2;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <?php endif; ?>
                    <?php if ($instagram): ?>
                    <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener" aria-label="Instagram"
                       style="width:42px;height:42px;border-radius:50%;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.1rem;text-decoration:none;">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <a href="<?php echo esc_url(home_url('/contacto/')); ?>" class="btn btn-blue" style="display:block;text-align:center;margin-top:24px;">
                    Únete a este Ministerio
                </a>
            </aside>
        </div>
    </div>
</section>
<style>
@media (max-width:860px){ .min-detail-grid{ grid-template-columns:1fr !important; } aside{ position:static !important; } }
</style>
<?php endwhile;
get_footer();
