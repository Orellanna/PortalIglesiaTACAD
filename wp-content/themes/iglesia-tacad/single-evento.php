<?php
/**
 * Single Evento — Página individual desde el CPT
 */
get_header();

while (have_posts()) : the_post();
    $post_id   = get_the_ID();
    $fecha     = get_post_meta($post_id, 'evento_fecha', true);
    $hora      = iglesia_evento_hora_rango($post_id);
    $ubi       = get_post_meta($post_id, 'evento_ubicacion', true);
    $resp      = get_post_meta($post_id, 'evento_responsable', true);
    $info      = get_post_meta($post_id, 'evento_info_adicional', true);
    $enlace    = get_post_meta($post_id, 'evento_enlace', true);
    $destacado = get_post_meta($post_id, 'evento_destacado', true);
    $whats     = get_post_meta($post_id, 'evento_whatsapp', true);
    $pasado    = iglesia_evento_es_pasado($post_id);
    $img       = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'large') : 'https://images.unsplash.com/photo-1548625361-ec8536098270?w=1200&auto=format&fit=crop';

    iglesia_page_banner(get_the_title(), $img);
?>
<section style="padding:var(--section-pad);">
    <div class="container" style="max-width:1000px;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:30px;flex-wrap:wrap;">
            <span style="padding:5px 16px;border-radius:20px;font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;<?php echo $pasado ? 'background:#f0f0f1;color:#646970;' : 'background:#00a32a;color:#fff;'; ?>">
                <?php echo $pasado ? 'Evento pasado' : 'Próximo evento'; ?>
            </span>
            <?php if ($destacado === '1'): ?>
            <span style="padding:5px 16px;border-radius:20px;font-size:0.78rem;font-weight:700;background:var(--accent-gold);color:#fff;">⭐ Destacado</span>
            <?php endif; ?>
            <?php if ($resp): ?>
            <span style="color:var(--text-muted);font-size:0.9rem;">Organiza: <?php echo esc_html($resp); ?></span>
            <?php endif; ?>
        </div>

        <div style="display:grid;grid-template-columns:1.2fr .8fr;gap:50px;align-items:start;" class="ev-detail-grid">
            <div>
                <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"
                     style="width:100%;height:340px;object-fit:cover;border-radius:20px;box-shadow:var(--shadow);margin-bottom:35px;" loading="lazy">
                <div style="font-size:1.05rem;line-height:1.9;color:var(--text-dark);">
                    <?php the_content(); ?>
                </div>
            </div>

            <aside style="background:#fff;border-radius:20px;box-shadow:var(--shadow-sm);border:1px solid rgba(0,0,0,0.04);padding:30px;position:sticky;top:90px;">
                <h3 style="font-family:var(--font-display);font-size:1.15rem;font-weight:800;color:var(--blue-primary);margin-bottom:22px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-circle-info"></i> Detalles
                </h3>

                <?php if ($fecha): ?>
                <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #f0f0f1;">
                    <span style="font-size:1.3rem;">📅</span>
                    <div>
                        <p style="margin:0;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Fecha</p>
                        <p style="margin:2px 0 0;font-size:0.95rem;color:var(--text-dark);"><?php echo esc_html(iglesia_fecha_es($fecha)); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($hora): ?>
                <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #f0f0f1;">
                    <span style="font-size:1.3rem;">⏰</span>
                    <div>
                        <p style="margin:0;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Horario</p>
                        <p style="margin:2px 0 0;font-size:0.95rem;color:var(--text-dark);"><?php echo esc_html($hora); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($ubi): ?>
                <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #f0f0f1;">
                    <span style="font-size:1.3rem;">📍</span>
                    <div>
                        <p style="margin:0;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Ubicación</p>
                        <p style="margin:2px 0 0;font-size:0.95rem;color:var(--text-dark);"><?php echo esc_html($ubi); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($resp): ?>
                <div style="display:flex;gap:12px;padding:12px 0;">
                    <span style="font-size:1.3rem;">👤</span>
                    <div>
                        <p style="margin:0;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);">Responsable</p>
                        <p style="margin:2px 0 0;font-size:0.95rem;color:var(--text-dark);"><?php echo esc_html($resp); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($info): ?>
                <div style="margin-top:18px;padding:16px;background:var(--section-bg);border-radius:12px;">
                    <p style="margin:0 0 6px;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--blue-primary);">Información adicional</p>
                    <?php echo nl2br(esc_html($info)); ?>
                </div>
                <?php endif; ?>

                <?php if (!$pasado): ?>
                <div style="display:grid;gap:12px;margin-top:24px;">
                    <?php if ($whats === '1' && ($wa = iglesia_whatsapp_link('Hola, quisiera inscribirme al evento: ' . get_the_title()))): ?>
                    <a href="<?php echo esc_url($wa); ?>" target="_blank" rel="noopener"
                       style="background:#25D366;color:#fff;text-align:center;padding:14px 20px;border-radius:12px;font-weight:700;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;">
                        <i class="fab fa-whatsapp" style="font-size:1.2rem;"></i> Inscribirme por WhatsApp
                    </a>
                    <?php endif; ?>
                    <?php if ($enlace): ?>
                    <a href="<?php echo esc_url($enlace); ?>" target="_blank" rel="noopener" class="btn btn-blue" style="text-align:center;display:block;">
                        Más información
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </aside>
        </div>

        <div style="margin-top:50px;">
            <a href="<?php echo esc_url(home_url('/eventos/')); ?>" class="btn btn-sm" style="background:var(--section-bg);color:var(--text-dark);">&larr; Ver todos los eventos</a>
        </div>
    </div>
</section>
<style>
@media (max-width:860px){ .ev-detail-grid{ grid-template-columns:1fr !important; } aside{ position:static !important; } }
</style>
<?php endwhile;
get_footer();
