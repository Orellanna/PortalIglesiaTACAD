<?php
/**
 * Sermones — Listado dinámico de predicadores desde el CPT
 */
get_header();
iglesia_page_banner('Sermones', 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1600&auto=format&fit=crop&q=60');

$predicadores = iglesia_get_predicadores();
$default_avatar = 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop';

// Collect all videos from all preachers for the "latest sermons" section
$todos_videos = [];
foreach ($predicadores as $p) {
    $vids = iglesia_get_predicador_videos($p->ID);
    foreach ($vids as $v) {
        $todos_videos[] = [
            'title'     => $v['title'] ?: 'Sermón de ' . $p->post_title,
            'embed_url' => $v['embed_url'],
            'predicador'=> $p->post_title,
        ];
    }
}
// Limit to latest 6
$todos_videos = array_slice($todos_videos, 0, 6);
?>

<section class="pastors-section">
    <div class="container">
        <p class="section-title-centered">La Palabra de Dios</p>
        <h2 class="section-heading-centered">Nuestros Predicadores</h2>

        <?php if (empty($predicadores)): ?>
            <p style="text-align:center;color:var(--text-muted);padding:40px 0;">Aún no hay predicadores registrados. Ve al panel de administración &gt; Predicadores para añadirlos.</p>
        <?php else: ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:25px;margin-bottom:60px;">
                <?php foreach ($predicadores as $p):
                    $foto = iglesia_get_predicador_foto($p->ID) ?: $default_avatar;
                    $rol  = get_post_meta($p->ID, '_iglesia_rol', true);
                ?>
                <div style="position:relative;border-radius:16px;overflow:hidden;height:300px;transition:transform 0.3s ease,box-shadow 0.3s ease;box-shadow:0 8px 30px rgba(0,0,0,0.15);" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 14px 40px rgba(0,0,0,0.22)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 30px rgba(0,0,0,0.15)';">
                    <img src="<?php echo esc_url($foto); ?>" alt="<?php echo esc_attr($p->post_title); ?>" style="width:100%;height:100%;object-fit:cover;">
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,20,100,0.88) 0%,rgba(0,20,100,0.2) 50%,transparent 80%);display:flex;flex-direction:column;justify-content:flex-end;padding:28px;color:#fff;">
                        <h3 style="font-family:Montserrat,sans-serif;font-size:1.5rem;font-weight:800;margin-bottom:5px;"><?php echo esc_html($p->post_title); ?></h3>
                        <?php if ($rol): ?>
                            <p style="color:rgba(255,220,80,0.95);font-size:.82rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;"><?php echo esc_html($rol); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($todos_videos)): ?>
        <h3 style="font-family:var(--font-display);font-size:1.5rem;font-weight:800;color:var(--blue-primary);margin-bottom:25px;">📹 Últimos Sermones</h3>
        <div class="sermons-grid">
            <?php foreach ($todos_videos as $i => $v): ?>
                <div class="sermon-card">
                    <div class="sermon-video">
                        <iframe src="<?php echo esc_url($v['embed_url']); ?>"
                                title="<?php echo esc_attr($v['title']); ?>"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                        </iframe>
                    </div>
                    <div class="sermon-body">
                        <h3><?php echo esc_html($v['title']); ?></h3>
                        <p class="date">👤 <?php echo esc_html($v['predicador']); ?> &nbsp;·&nbsp; 📅 Sermón</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
