<?php
/**
 * Sermones Archive Page
 */
get_header();
iglesia_page_banner('Sermones', 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1600&auto=format&fit=crop&q=60');
?>
<section class="pastors-section">
    <div class="container">
        <p class="section-title" style="text-align:center;">La Palabra de Dios</p>
        <h2 class="section-heading" style="text-align:center;margin-bottom:40px;">Nuestros Pastores</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:25px;margin-bottom:60px;">
            <?php
            $pastores = [
                ['Melky','Pastor Principal','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop','pastor-melky'],
                ['Orlando','Pastor de Jóvenes','https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&auto=format&fit=crop','pastor-orlando'],
                ['Toby Jr.','Pastor General','https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&auto=format&fit=crop','pastor-toby-jr'],
                ['Fundador','Pastor Fundador','https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&auto=format&fit=crop','pastor-fundador'],
            ];
            foreach ($pastores as $p) {
                $page = get_page_by_path($p[3]);
                $url  = $page ? get_permalink($page->ID) : home_url('/' . $p[3] . '/');
            ?>
            <a href="<?php echo esc_url($url); ?>" style="text-decoration:none;">
                <div style="position:relative;border-radius:16px;overflow:hidden;height:280px;cursor:pointer;transition:transform 0.3s ease;box-shadow:0 8px 30px rgba(0,0,0,0.2);" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='translateY(0)'">
                    <img src="<?php echo esc_url($p[2]); ?>" alt="Pastor <?php echo esc_html($p[0]); ?>" style="width:100%;height:100%;object-fit:cover;">
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,20,100,0.85) 0%,transparent 60%);display:flex;flex-direction:column;justify-content:flex-end;padding:30px;color:#fff;">
                        <h3 style="font-family:Montserrat,sans-serif;font-size:1.6rem;font-weight:800;margin-bottom:5px;">Pastor <?php echo esc_html($p[0]); ?></h3>
                        <p style="color:rgba(255,220,80,0.9);font-size:.85rem;font-weight:600;text-transform:uppercase;letter-spacing:1px;"><?php echo esc_html($p[1]); ?></p>
                        <p style="font-size:.9rem;margin-top:10px;opacity:.9;">Ver sermones &rarr;</p>
                    </div>
                </div>
            </a>
            <?php } ?>
        </div>
        <h3 style="font-family:Montserrat,sans-serif;font-size:1.5rem;font-weight:800;color:var(--blue-primary);margin-bottom:25px;">📹 Últimos Sermones</h3>
        <div class="sermons-grid">
            <?php
            $latest_videos = [
                ['La Fe que Vence al Mundo','https://www.youtube.com/embed/dQw4w9WgXcQ','Pastor Melky'],
                ['El Amor de Dios es Eterno','https://www.youtube.com/embed/dQw4w9WgXcQ','Pastor Orlando'],
                ['Vivir en Propósito','https://www.youtube.com/embed/dQw4w9WgXcQ','Pastor Toby Jr.'],
                ['El Espíritu Santo en Tu Vida','https://www.youtube.com/embed/dQw4w9WgXcQ','Pastor Orlando'],
                ['La Gracia que Nos Transforma','https://www.youtube.com/embed/dQw4w9WgXcQ','Pastor Fundador'],
                ['Jóvenes: Tu Tiempo es Ahora','https://www.youtube.com/embed/dQw4w9WgXcQ','Pastor Melky'],
            ];
            foreach ($latest_videos as $v) { ?>
                <div class="sermon-card">
                    <div class="sermon-video">
                        <iframe src="<?php echo esc_url($v[1]); ?>" title="<?php echo esc_attr($v[0]); ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="sermon-body">
                        <h3><?php echo esc_html($v[0]); ?></h3>
                        <p class="date">👤 <?php echo esc_html($v[2]); ?> &nbsp;·&nbsp; 📅 Reciente</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>
