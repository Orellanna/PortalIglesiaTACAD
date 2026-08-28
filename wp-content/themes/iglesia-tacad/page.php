<?php
/**
 * Default Page Template
 */
get_header();

$page_slug = $post ? $post->post_name : '';

// Route to the right page content based on slug
switch ($page_slug) {
    case 'jesus':
        include get_template_directory() . '/page-jesus.php';
        break;
    case 'acerca-de':
        include get_template_directory() . '/page-acerca-de.php';
        break;
    case 'visitanos':
        iglesia_page_banner('Visítanos', 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?w=1600&auto=format&fit=crop&q=60');
        iglesia_page_visitanos();
        break;
    case 'historia':
        iglesia_page_banner('Nuestra Historia', 'https://images.unsplash.com/photo-1445887374063-34abd495852a?w=1600&auto=format&fit=crop&q=60');
        iglesia_page_historia();
        break;
    case 'mision-y-vision':
        iglesia_page_banner('Misión y Visión');
        iglesia_page_mision();
        break;
    case 'ministerios':
        iglesia_page_banner('Ministerios');
        iglesia_page_ministerios();
        break;
    case 'ministerio-alabanza':
        iglesia_page_banner('Ministerio de Alabanza');
        iglesia_page_ministerio_detalle('🎵', 'Ministerio de Alabanza', 'El equipo de adoración que conduce a los congregantes a la presencia de Dios. Nuestro objetivo es crear un ambiente de adoración donde las personas puedan encontrarse con Dios a través de la música.', ['Martes: Ensayo 7:00pm', 'Domingo: Servicio 9:00am'], 'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=800&auto=format&fit=crop');
        break;
    case 'ministerio-infantil':
        iglesia_page_banner('Ministerio Infantil');
        iglesia_page_ministerio_detalle('👶', 'Ministerio Infantil', 'Formamos niños con carácter y amor a Dios desde pequeños. A través de lecciones bíblicas interactivas, manualidades y canciones, los niños aprenden los fundamentos de la fe cristiana.', ['Domingo: Clases 9:00am', 'Sábado: Actividades 3:00pm'], 'https://images.unsplash.com/photo-1484820540004-14229fe36ca4?w=800&auto=format&fit=crop');
        break;
    case 'ministerio-oracion':
        iglesia_page_banner('Ministerio de Oración');
        iglesia_page_ministerio_detalle('🙏', 'Ministerio de Oración', 'Intercesores que sostienen a la iglesia en oración continua. Creemos en el poder de la oración y nos reunimos para interceder por las necesidades de la congregación, la ciudad y las naciones.', ['Martes: Oración 6:30pm', 'Sábado: Vigilia 5:30am'], 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=800&auto=format&fit=crop');
        break;
    case 'ministerio-mujeres':
        iglesia_page_banner('Ministerio de Mujeres');
        iglesia_page_ministerio_detalle('🌹', 'Ministerio de Mujeres', 'Mujeres fuertes en Dios que se edifican y apoyan mutuamente. Nos reunimos para estudiar la Palabra, compartir experiencias y crecer juntas en la fe.', ['Miércoles: Ayuno 9:00am', 'Sábado: Reunión 3:00pm'], 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=800&auto=format&fit=crop');
        break;
    case 'ministerio-hombres':
        iglesia_page_banner('Ministerio de Hombres');
        iglesia_page_ministerio_detalle('💪', 'Ministerio de Hombres', 'Hombres de Dios que lideran en el hogar, la iglesia y la sociedad. Nos enfocamos en desarrollar liderazgo bíblico, integridad y responsabilidad.', ['Sábado: Reunión 6:00am', 'Domingo: Servicio'], 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&auto=format&fit=crop');
        break;
    case 'ministerio-jovenes':
        iglesia_page_banner('Ministerio de Jóvenes');
        iglesia_page_ministerio_detalle('⚡', 'Ministerio de Jóvenes', 'Jóvenes apasionados que viven su fe con autenticidad y energía. Buscamos formar una generación comprometida con Dios que impacte su entorno.', ['Sábado: Culto Juvenil 6:30pm', 'Domingo: Escuela Dominical'], 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=800&auto=format&fit=crop');
        break;
    case 'preguntas-frecuentes':
        iglesia_page_banner('Preguntas Frecuentes');
        iglesia_page_faq();
        break;
    case 'contacto':
        iglesia_page_banner('Contacto');
        iglesia_page_contacto();
        break;
    case 'blog':
        // Blog mantenible: listado dinámico desde Entradas con categorías y paginación
        iglesia_page_banner('Blog', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1600&auto=format&fit=crop&q=60');
        iglesia_page_blog();
        break;
    case 'pastor-melky':
    case 'pastor-orlando':
    case 'pastor-toby-jr':
    case 'pastor-fundador':
        // Dynamic: look up predicador by post_name from CPT
        $predicador = get_page_by_path($page_slug, OBJECT, 'predicador');
        $predi_activo = $predicador ? get_post_meta($predicador->ID, '_iglesia_predicador_activo', true) : '';
        if ($predicador && ($predi_activo === '' || $predi_activo === '1')) {
            iglesia_page_banner(
                $predicador->post_title,
                'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1600&auto=format&fit=crop&q=60'
            );
            iglesia_page_pastor_dynamic($predicador);
        } else {
            $name = str_replace(['pastor-','-'],['',' '], $page_slug);
            $name = ucwords($name);
            iglesia_page_banner($name);
            echo '<section style="padding:60px 0;text-align:center;"><div class="container"><p style="color:var(--text-muted);">Predicador no encontrado. <a href="' . esc_url(admin_url('post-new.php?post_type=predicador')) . '">Añadir desde el admin</a>.</p></div></section>';
        }
        break;
    case 'ofrenda':
        iglesia_page_banner('Ofrenda y Donaciones');
        iglesia_page_ofrenda();
        break;
    default:
        // Generic page with title and content from WordPress editor
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                iglesia_page_banner(get_the_title());
                echo '<section style="padding: 60px 0;"><div class="container">';
                the_content();
                echo '</div></section>';
            }
        }
        break;
}

get_footer();

// ===========================
// VISITANOS TEMPLATE FUNCTION
// ===========================
function iglesia_page_visitanos() { ?>
<section style="padding:var(--section-pad);background:var(--section-bg);">
    <div class="container">
        <div class="section-header" style="text-align:left;">
            <span class="section-tag">Horarios de Servicio</span>
            <h2>Ven y Sé Parte</h2>
            <p>Te esperamos con los brazos abiertos</p>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <img src="https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=600&auto=format&fit=crop" alt="Culto General">
                <div class="service-card-body">
                    <h3>Culto de Oración</h3>
                    <p>🕕 Martes 6:30pm</p>
                </div>
            </div>
            <div class="service-card">
                <img src="https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=600&auto=format&fit=crop" alt="Ayuno de Mujeres">
                <div class="service-card-body">
                    <h3>Ayuno de Mujeres</h3>
                    <p>🕘 Miércoles 9:00am</p>
                </div>
            </div>
            <div class="service-card">
                <img src="https://images.unsplash.com/photo-1438232992991-995b7058bbb3?w=600&auto=format&fit=crop" alt="Culto General">
                <div class="service-card-body">
                    <h3>Culto General</h3>
                    <p>🕕 Jueves 6:30pm</p>
                </div>
            </div>
            <div class="service-card">
                <img src="https://images.unsplash.com/photo-1548625361-ec8536098270?w=600&auto=format&fit=crop" alt="Cultos Sábado">
                <div class="service-card-body">
                    <h3>Sábado</h3>
                    <p>🕔 Culto Matutino: 5:30am</p>
                    <p>🕒 Culto CMF: 3:00pm</p>
                    <p>🕕 Culto Juvenil: 6:30pm</p>
                </div>
            </div>
            <div class="service-card">
                <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=600&auto=format&fit=crop" alt="Culto Dominical">
                <div class="service-card-body">
                    <h3>Culto Dominical</h3>
                    <p>🕘 Domingo 9:00am – 3:00pm</p>
                    <p>¡Toda la familia bienvenida!</p>
                </div>
            </div>
        </div>
        <div class="map-embed" style="margin-top:40px;">
           <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d717.5877399125291!2d-89.17163624304584!3d14.31750191435547!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f63a732c2655619%3A0x167ba5b19832ca45!2sTabern%C3%A1culo%20Cristiano%20Asambleas%20de%20Dios%20La%20Palma!5e1!3m2!1ses-419!2ssv!4v1786321360425!5m2!1ses-419!2ssv" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
        </div>
    </div>
</section>
<?php }

// ===========================
// MISSION PAGE FUNCTION
// ===========================
function iglesia_page_mision() {
    $mv = iglesia_get_mision_vision_data();
    $misiones = !empty($mv['mision']) ? $mv['mision'] : [];
    $visiones = !empty($mv['vision']) ? $mv['vision'] : [];
?>
<section style="padding: 80px 20px;">
    <div class="container" style="max-width:900px;margin:0 auto;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:50px;margin-bottom:60px;" class="mv-grid">
            <?php foreach ($misiones as $i => $m) : ?>
            <div style="background:var(--blue-primary);color:#fff;padding:50px 40px;border-radius:16px;margin-bottom:<?php echo count($misiones) > 1 ? '30px' : '0'; ?>;">
                <div style="font-size:3rem;margin-bottom:20px;">🎯</div>
                <h2 style="font-family:'Montserrat',sans-serif;font-size:2rem;font-weight:800;margin-bottom:20px;"><?php echo esc_html($m['title'] ?: 'Misión'); ?></h2>
                <p style="opacity:.9;line-height:1.9;font-size:1.05rem;"><?php echo esc_html($m['content']); ?></p>
            </div>
            <?php endforeach; ?>
            <?php foreach ($visiones as $v) : ?>
            <div style="background:#f8f9ff;color:var(--text-dark);padding:50px 40px;border-radius:16px;border-top:5px solid var(--blue-primary);">
                <div style="font-size:3rem;margin-bottom:20px;">👁️</div>
                <h2 style="font-family:'Montserrat',sans-serif;font-size:2rem;font-weight:800;margin-bottom:20px;color:var(--blue-primary);"><?php echo esc_html($v['title'] ?: 'Visión'); ?></h2>
                <p style="color:var(--text-muted);line-height:1.9;font-size:1.05rem;"><?php echo esc_html($v['content']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <style>@media(max-width:768px){.mv-grid{grid-template-columns:1fr !important;gap:30px !important;}}</style>
        <div style="text-align:center;background:var(--blue-primary);padding:50px 40px;border-radius:16px;color:#fff;">
            <h2 style="font-family:'Montserrat',sans-serif;font-size:2rem;margin-bottom:15px;">Nuestros Valores</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:25px;margin-top:30px;">
                <?php
                $valores = [['📖','Palabra','Creemos en la Biblia como Palabra infalible de Dios'],['🙏','Oración','La oración es nuestro fundamento y sustento diario'],['💞','Amor','Amamos a Dios y al prójimo como a nosotros mismos'],['🤝','Comunidad','Somos familia que se apoya y crece juntos'],['🌎','Misiones','Comprometidos con llevar el Evangelio a las naciones'],['🌱','Discipulado','Formamos creyentes maduros en la fe']];
                foreach ($valores as $v) {
                    echo '<div style="text-align:center;"><div style="font-size:2.5rem;margin-bottom:10px;">'.$v[0].'</div><h3 style="font-family:Montserrat,sans-serif;font-weight:700;margin-bottom:5px;">'.$v[1].'</h3><p style="font-size:.85rem;opacity:.85;">'.$v[2].'</p></div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>
<?php }

// ===========================
// MINISTERIOS
// ===========================
function iglesia_page_ministerios() {
    $ministerios = new WP_Query([
        'post_type' => 'ministerio',
        'posts_per_page' => -1,
        'meta_query' => iglesia_meta_activa('ministerio_activo'),
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);
    ?>
<section style="padding:var(--section-pad);">
    <div class="container">
        <p class="section-title" style="text-align:center;">Nuestra Casa</p>
        <h2 class="section-heading" style="text-align:center;margin-bottom:40px;">Ministerios de la Iglesia</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:25px;">
            <?php if ($ministerios->have_posts()) : while ($ministerios->have_posts()) : $ministerios->the_post();
                $descripcion = get_post_meta(get_the_ID(), 'ministerio_descripcion', true);
                $imagen_id = get_post_meta(get_the_ID(), 'ministerio_imagen_id', true);
                $imagen_url = $imagen_id ? wp_get_attachment_image_url($imagen_id, 'medium') : 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?w=600&auto=format&fit=crop';
                $slug = get_post_field('post_name', get_the_ID());
            ?>
                <a href="<?php echo esc_url(home_url('/ministerio-' . $slug . '/')); ?>" class="ministry-card" style="display:block;">
                    <img src="<?php echo esc_url($imagen_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                    <div class="ministry-card-overlay">
                        <div>
                            <h3><?php echo esc_html(get_the_title()); ?></h3>
                            <p style="font-size:.8rem;color:rgba(255,255,255,.85);margin-top:5px;"><?php echo esc_html(wp_trim_words($descripcion, 15)); ?></p>
                        </div>
                    </div>
                </a>
            <?php endwhile; else : ?>
                <p style="text-align:center;grid-column:1/-1;color:var(--text-muted);">No hay ministerios registrados. <a href="<?php echo admin_url('post-new.php?post_type=ministerio'); ?>">Agregar uno nuevo</a></p>
            <?php endif; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php }

// ===========================
// FAQ
// ===========================
function iglesia_page_faq() {
    // Obtener secciones activas ordenadas
    $secciones = new WP_Query([
        'post_type' => 'faq_seccion',
        'posts_per_page' => -1,
        'meta_query' => iglesia_meta_activa('faq_seccion_activo'),
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);

    // Obtener todas las FAQs activas
    $faqs = new WP_Query([
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'meta_query' => [
            'relation' => 'AND',
            iglesia_meta_activa('faq_activo'),
            [
                'key' => 'faq_seccion_id',
                'compare' => 'EXISTS',
            ]
        ],
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);

    // Organizar FAQs por sección
    $faqs_por_seccion = [];
    if ($faqs->have_posts()) {
        while ($faqs->have_posts()) {
            $faqs->the_post();
            $seccion_id = get_post_meta(get_the_ID(), 'faq_seccion_id', true);
            if ($seccion_id) {
                if (!isset($faqs_por_seccion[$seccion_id])) {
                    $faqs_por_seccion[$seccion_id] = [];
                }
                $faqs_por_seccion[$seccion_id][] = [
                    'title' => get_the_title(),
                    'content' => get_post_meta(get_the_ID(), 'faq_descripcion', true),
                ];
            }
        }
        wp_reset_postdata();
    }

    // Obtener FAQs sin sección (se mostrarán al final)
    $faqs_sin_seccion = new WP_Query([
        'post_type' => 'faq',
        'posts_per_page' => -1,
        'meta_query' => [
            'relation' => 'AND',
            iglesia_meta_activa('faq_activo'),
            [
                'key' => 'faq_seccion_id',
                'compare' => 'NOT EXISTS',
            ]
        ],
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);

    $hay_sin_seccion = $faqs_sin_seccion->have_posts();
    ?>
    <section class="faq-section">
        <div class="container" style="max-width:800px;margin:0 auto;">
            <p class="section-title">Resolvemos tus dudas</p>
            <h2 class="section-heading" style="margin-bottom:40px;">Preguntas Frecuentes</h2>

            <?php if ($secciones->have_posts() || $hay_sin_seccion) : ?>

                <?php
                // Mostrar secciones con FAQs
                while ($secciones->have_posts()) : $secciones->the_post();
                    $seccion_id = get_the_ID();
                    $seccion_faqs = isset($faqs_por_seccion[$seccion_id]) ? $faqs_por_seccion[$seccion_id] : [];

                    if (empty($seccion_faqs)) {
                        continue; // No mostrar secciones vacías
                    }
                ?>
                    <div class="faq-section-wrap" style="margin-bottom:40px;">
                        <h3 class="faq-section-title" style="font-family:var(--font-display);font-size:1.3rem;font-weight:700;color:var(--blue-primary);margin-bottom:16px;padding-bottom:8px;border-bottom:2px solid var(--blue-light);">
                            <?php echo esc_html(get_the_title()); ?>
                        </h3>
                        <div class="accordion">
                            <?php foreach ($seccion_faqs as $index => $faq) : ?>
                                <div class="accordion-item <?php echo $index === 0 ? 'open' : ''; ?>">
                                    <button class="accordion-header">
                                        <?php echo esc_html($faq['title']); ?>
                                        <span class="accordion-icon">+</span>
                                    </button>
                                    <div class="accordion-body"><?php echo esc_html($faq['content']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>

                <?php
                // Mostrar FAQs sin sección
                if ($hay_sin_seccion) :
                ?>
                    <div class="faq-section-wrap" style="margin-bottom:40px;">
                        <h3 class="faq-section-title" style="font-family:var(--font-display);font-size:1.3rem;font-weight:700;color:var(--blue-primary);margin-bottom:16px;padding-bottom:8px;border-bottom:2px solid var(--blue-light);">
                            General
                        </h3>
                        <div class="accordion">
                            <?php $first = true; while ($faqs_sin_seccion->have_posts()) : $faqs_sin_seccion->the_post();
                                $descripcion = get_post_meta(get_the_ID(), 'faq_descripcion', true);
                            ?>
                                <div class="accordion-item <?php echo $first ? 'open' : ''; ?>">
                                    <button class="accordion-header">
                                        <?php echo esc_html(get_the_title()); ?>
                                        <span class="accordion-icon">+</span>
                                    </button>
                                    <div class="accordion-body"><?php echo esc_html($descripcion); ?></div>
                                </div>
                            <?php $first = false; endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <p style="text-align:center;color:var(--text-muted);">No hay preguntas frecuentes registradas. <a href="<?php echo admin_url('post-new.php?post_type=faq'); ?>">Agregar una nueva</a></p>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

// ===========================
// CONTACTO
// ===========================
function iglesia_page_contacto() { ?>
<section class="contact-section">
    <div class="container">
        <div class="contact-info">
            <h2>Contáctanos</h2>
            <p>Estamos aquí para servirte. No dudes en escribirnos o visitarnos. ¡Con gusto te atendemos!</p>
            <?php $dir = iglesia_direccion(); if ($dir): ?><div class="contact-info-item"><div class="icon-wrap"><i class="fas fa-map-marker-alt"></i></div><p><strong>Dirección</strong><?php echo esc_html($dir); ?></p></div><?php endif; ?>
            <?php $tel = iglesia_telefono(); if ($tel): ?><div class="contact-info-item"><div class="icon-wrap"><i class="fas fa-phone-alt"></i></div><p><strong>Teléfono</strong><?php echo esc_html($tel); ?></p></div><?php endif; ?>
            <?php $em = iglesia_email(); if ($em): ?><div class="contact-info-item"><div class="icon-wrap"><i class="fas fa-envelope"></i></div><p><strong>Email</strong><?php echo esc_html($em); ?></p></div><?php endif; ?>
            <?php $hor = iglesia_horarios(); if ($hor): ?><div class="contact-info-item"><div class="icon-wrap"><i class="fas fa-clock"></i></div><p><strong>Horarios de Cultos</strong><?php echo esc_html(str_replace("\n", ' · ', $hor)); ?></p></div><?php endif; ?>
        </div>
        <div class="contact-form">
            <h2 style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:var(--blue-primary);margin-bottom:25px;">Envíanos un Mensaje</h2>
            <form id="contact-form" method="POST">
                <input type="text" name="nombre" placeholder="Tu nombre completo" required>
                <input type="email" name="email" placeholder="Tu correo electrónico" required>
                <input type="text" name="asunto" placeholder="Asunto">
                <textarea name="mensaje" placeholder="Escribe tu mensaje aquí..." required></textarea>
                <button type="submit" class="submit-btn" id="contact-submit-btn"><i class="fas fa-paper-plane" style="margin-right:8px;"></i>Enviar Mensaje</button>
            </form>
            <div id="contact-form-message" style="display:none;margin-top:20px;padding:15px 20px;border-radius:8px;text-align:center;"></div>
        </div>
    </div>
</section>
<script>
(function() {
    var form = document.getElementById('contact-form');
    var submitBtn = document.getElementById('contact-submit-btn');
    var messageDiv = document.getElementById('contact-form-message');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(form);
            var btnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:8px;"></i>Enviando...';

            messageDiv.style.display = 'none';
            messageDiv.className = '';
            messageDiv.innerHTML = '';

            fetch('<?php echo esc_js(esc_url_raw(rest_url('iglesia/v1/contact'))); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    nombre: formData.get('nombre'),
                    email: formData.get('email'),
                    asunto: formData.get('asunto'),
                    mensaje: formData.get('mensaje')
                })
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    messageDiv.className = 'success';
                    messageDiv.style.cssText = 'background:#d4edda;color:#155724;padding:15px 20px;border-radius:8px;border:1px solid #c3e6cb;';
                    messageDiv.innerHTML = '<i class="fas fa-check-circle" style="margin-right:8px;"></i>' + data.message;
                    form.reset();
                } else {
                    messageDiv.className = 'error';
                    messageDiv.style.cssText = 'background:#f8d7da;color:#721c24;padding:15px 20px;border-radius:8px;border:1px solid #f5c6cb;';
                    messageDiv.innerHTML = '<i class="fas fa-exclamation-circle" style="margin-right:8px;"></i>Por favor corrige los errores: ' + data.errors.join(', ');
                }
                messageDiv.style.display = 'block';
            })
            .catch(function(error) {
                messageDiv.className = 'error';
                messageDiv.style.cssText = 'background:#f8d7da;color:#721c24;padding:15px 20px;border-radius:8px;border:1px solid #f5c6cb;';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-circle" style="margin-right:8px;"></i>Error al enviar. Por favor intenta más tarde.';
                messageDiv.style.display = 'block';
            })
            .finally(function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = btnText;
            });
        });
    }
})();
</script>
<?php }

// ===========================
// PASTOR TEMPLATE – Dinámico desde CPT «predicador»
// ===========================
function iglesia_page_pastor_dynamic($predicador) {
    $post_id       = $predicador->ID;
    $name          = $predicador->post_title;
    $rol           = get_post_meta($post_id, '_iglesia_rol', true);
    $bio           = $predicador->post_content ?: '';
    $photo_url     = iglesia_get_predicador_foto($post_id) ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop';
    $videos        = iglesia_get_predicador_videos($post_id);
    ?>
    <section class="pastors-section">
        <div class="container">
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
            <p style="text-align:center;color:var(--text-muted);padding:30px 0;">Aún no hay sermones publicados. Visita el panel de administración &gt; Predicadores &gt; <?php echo esc_html($name); ?> para añadir videos.</p>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

// ===========================
// OFRENDA
// ===========================
// ===========================
// MINISTERIO DETALLE
// ===========================
function iglesia_page_ministerio_detalle($icon, $title, $description, $schedules, $image) { ?>
<section style="padding:var(--section-pad);">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:50px;align-items:center;margin-bottom:50px;">
            <div>
                <div style="font-size:4rem;margin-bottom:20px;"><?php echo $icon; ?></div>
                <h2 style="font-family:var(--font-display);font-size:2rem;font-weight:800;color:var(--blue-primary);margin-bottom:16px;"><?php echo esc_html($title); ?></h2>
                <p style="font-size:1.05rem;line-height:1.9;color:var(--text-muted);"><?php echo esc_html($description); ?></p>
                <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:700;color:var(--text-dark);margin:24px 0 12px;">Horarios</h3>
                <ul style="list-style:none;">
                    <?php foreach ($schedules as $s) { echo '<li style="padding:8px 0;color:var(--text-muted);">🕐 ' . esc_html($s) . '</li>'; } ?>
                </ul>
                <a href="<?php echo esc_url(home_url('/contacto/')); ?>" class="btn btn-blue" style="margin-top:20px;">Únete a este Ministerio</a>
            </div>
            <div>
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" style="width:100%;border-radius:20px;box-shadow:var(--shadow-lg);">
            </div>
        </div>
    </div>
</section>
<?php }

// ===========================
// JESUS PAGE
// ===========================
function iglesia_page_jesus() { ?>
<style>
.jesus-hero { text-align: center; padding: 80px 0 60px; background: linear-gradient(135deg, #1a237e 0%, #283593 100%); color: #fff; border-radius: 20px; margin-bottom: 60px; position: relative; overflow: hidden; }
.jesus-hero::before { content: '✝'; position: absolute; top: -30px; right: -10px; font-size: 12rem; opacity: 0.06; }
.jesus-hero h1 { font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 900; margin-bottom: 16px; }
.jesus-hero h1 span { color: var(--accent-gold); }
.jesus-hero p { font-size: 1.15rem; opacity: 0.9; max-width: 600px; margin: 0 auto; line-height: 1.8; }

.jesus-section { padding: 60px 0; }
.jesus-section-alt { padding: 60px 0; background: var(--section-bg); }

.jesus-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; }
.jesus-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
.jesus-grid-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; }

.jesus-card { background: #fff; border-radius: 16px; padding: 35px 28px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.04); transition: var(--transition); }
.jesus-card:hover { transform: translateY(-4px); box-shadow: var(--shadow); }
.jesus-card .icon { font-size: 2.5rem; margin-bottom: 16px; display: block; }
.jesus-card h3 { font-family: var(--font-display); font-size: 1.15rem; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
.jesus-card p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.7; }

.verse-card { background: var(--gradient-blue); color: #fff; border-radius: 16px; padding: 40px 35px; text-align: center; }
.verse-card .verse-text { font-family: var(--font-serif); font-size: clamp(1.2rem, 2.5vw, 1.6rem); font-style: italic; line-height: 1.8; margin-bottom: 12px; }
.verse-card .verse-ref { color: var(--accent-gold); font-weight: 600; font-size: 0.95rem; }

.steps-list { counter-reset: step; display: grid; gap: 24px; max-width: 700px; margin: 0 auto; }
.step-item { background: #fff; border-radius: 16px; padding: 32px 32px 32px 80px; position: relative; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.04); }
.step-item::before { counter-increment: step; content: counter(step); position: absolute; left: 24px; top: 50%; transform: translateY(-50%); width: 40px; height: 40px; background: var(--gradient-blue); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-weight: 800; font-size: 1.1rem; }
.step-item h3 { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 6px; }
.step-item p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.7; }

.faq-mini-item { border: 1px solid #eee; border-radius: 12px; margin-bottom: 12px; overflow: hidden; }
.faq-mini-header { padding: 18px 22px; background: #fff; cursor: pointer; font-family: var(--font-display); font-weight: 600; font-size: 0.95rem; display: flex; justify-content: space-between; align-items: center; transition: var(--transition); }
.faq-mini-header:hover { background: var(--blue-light); }
.faq-mini-item.open .faq-mini-header { background: var(--blue-primary); color: #fff; }
.faq-mini-body { display: none; padding: 18px 22px; color: var(--text-muted); line-height: 1.8; font-size: 0.9rem; }
.faq-mini-item.open .faq-mini-body { display: block; }

@media (max-width: 768px) {
    .jesus-grid-2 { grid-template-columns: 1fr; }
    .jesus-grid-3 { grid-template-columns: 1fr; }
    .step-item { padding: 24px 24px 24px 70px; }
}
</style>

<div class="container-narrow">
    <div class="jesus-hero">
        <h1>Jesús: El Camino, la Verdad y la <span>Vida</span></h1>
        <p>Jesucristo es el centro de nuestra fe. Él es el Hijo de Dios que vino al mundo para salvar a los pecadores y darnos vida eterna. Conoce más acerca de Él.</p>
    </div>

    <!-- ¿Quién es Jesús? -->
    <div class="jesus-section">
        <div class="section-header">
            <span class="section-tag">Conoce a Cristo</span>
            <h2>¿Quién es Jesús?</h2>
            <p>Descubre la persona más importante de la historia</p>
        </div>
        <div class="jesus-grid-2">
            <div>
                <p style="font-size:1.05rem;line-height:1.9;color:var(--text-muted);">Jesús de Nazaret es el Hijo de Dios, el Mesías prometido en el Antiguo Testamento. Él es Dios hecho hombre, que vivió una vida perfecta, murió en la cruz por nuestros pecados, resucitó al tercer día y ascendió al cielo. Hoy está sentado a la diestra de Dios Padre, intercediendo por nosotros.</p>
                <p style="font-size:1.05rem;line-height:1.9;color:var(--text-muted);margin-top:16px;">Jesús no fue solo un profeta o un maestro moral. Él es el Salvador del mundo, el único camino para llegar a Dios. Como Él mismo dijo: <em>"Yo soy el camino, y la verdad, y la vida; nadie viene al Padre sino por mí"</em> (Juan 14:6).</p>
            </div>
            <div class="verse-card">
                <div class="verse-text">"Porque de tal manera amó Dios al mundo, que ha dado a su Hijo unigénito, para que todo aquel que en Él cree no se pierda, mas tenga vida eterna."</div>
                <div class="verse-ref">Juan 3:16</div>
            </div>
        </div>
    </div>

    <!-- Plan de Salvación -->
    <div class="jesus-section-alt">
        <div class="section-header">
            <span class="section-tag">El Plan de Dios</span>
            <h2>Plan de Salvación</h2>
            <p>Dios tiene un propósito maravilloso para tu vida</p>
        </div>
        <div class="steps-list">
            <div class="step-item">
                <h3>Dios te ama</h3>
                <p>Dios te creó y tiene un plan de amor y bendición para tu vida. Él desea tener una relación personal contigo. <em>"Porque de tal manera amó Dios al mundo..."</em> (Juan 3:16)</p>
            </div>
            <div class="step-item">
                <h3>El pecado nos separa de Dios</h3>
                <p>Todos hemos pecado y estamos separados de Dios. El pecado es todo pensamiento, palabra u obra que desobedece la voluntad de Dios. <em>"Por cuanto todos pecaron y están destituidos de la gloria de Dios."</em> (Romanos 3:23)</p>
            </div>
            <div class="step-item">
                <h3>Dios pagó el precio por ti</h3>
                <p>Jesús, el Hijo de Dios, murió en la cruz para pagar el castigo de tus pecados. Él tomó tu lugar. <em>"Mas Dios muestra su amor para con nosotros, en que siendo aún pecadores, Cristo murió por nosotros."</em> (Romanos 5:8)</p>
            </div>
            <div class="step-item">
                <h3>Recibe a Cristo hoy</h3>
                <p>Si confiesas con tu boca que Jesús es el Señor y crees en tu corazón que Dios le levantó de los muertos, serás salvo. <em>"que si confesares con tu boca que Jesús es el Señor, y creyeres en tu corazón que Dios le levantó de los muertos, serás salvo."</em> (Romanos 10:9)</p>
            </div>
        </div>
    </div>

    <!-- Versículos Destacados -->
    <div class="jesus-section">
        <div class="section-header">
            <span class="section-tag">Palabra de Dios</span>
            <h2>Versículos Destacados</h2>
        </div>
        <div class="jesus-grid-3">
            <div class="verse-card"><div class="verse-text">"Venid a mí todos los que estáis trabajados y cargados, y yo os haré descansar."</div><div class="verse-ref">Mateo 11:28</div></div>
            <div class="verse-card"><div class="verse-text">"Yo he venido para que tengan vida, y para que la tengan en abundancia."</div><div class="verse-ref">Juan 10:10</div></div>
            <div class="verse-card"><div class="verse-text">"De cierto, de cierto os digo: El que oye mi palabra, y cree al que me envió, tiene vida eterna."</div><div class="verse-ref">Juan 5:24</div></div>
        </div>
    </div>

    <!-- Enseñanzas Bíblicas -->
    <div class="jesus-section-alt">
        <div class="section-header">
            <span class="section-tag">Enseñanzas</span>
            <h2>Enseñanzas Bíblicas</h2>
            <p>Principios fundamentales de la fe cristiana</p>
        </div>
        <div class="jesus-grid-4">
            <div class="jesus-card"><span class="icon">🙏</span><h3>La Oración</h3><p>Comunícate con Dios en oración. Él escucha tu voz y responderá conforme a su voluntad. La oración es el aliento del alma.</p></div>
            <div class="jesus-card"><span class="icon">📖</span><h3>La Biblia</h3><p>Es la Palabra inspirada de Dios. Léela diariamente para conocer más de Dios y crecer en tu fe.</p></div>
            <div class="jesus-card"><span class="icon">🤝</span><h3>La Comunidad</h3><p>No fuimos creados para estar solos. La iglesia es la familia de Dios donde podemos crecer, servir y amarnos.</p></div>
            <div class="jesus-card"><span class="icon">🕊️</span><h3>El Espíritu Santo</h3><p>Dios te ha dado al Espíritu Santo como guía, consolador y sello de tu salvación.</p></div>
        </div>
    </div>

    <!-- Recursos para nuevos creyentes -->
    <div class="jesus-section">
        <div class="section-header">
            <span class="section-tag">Nuevos Creyentes</span>
            <h2>Recursos para Nuevos Creyentes</h2>
            <p>Pasos prácticos para comenzar tu caminar con Dios</p>
        </div>
        <div class="jesus-grid-4">
            <div class="jesus-card"><span class="icon">📅</span><h3>Lee la Biblia Diariamente</h3><p>Comienza con el Evangelio de Juan. Lee un capítulo cada día y medita en lo que Dios te habla.</p></div>
            <div class="jesus-card"><span class="icon">🙌</span><h3>Asiste a la Iglesia</h3><p>Únete a nuestra comunidad. Los cultos son cada domingo a las 9:00am y durante la semana.</p></div>
            <div class="jesus-card"><span class="icon">💬</span><h3>Habla con Dios</h3><p>Ora todos los días. No necesitas palabras especiales, solo habla con Él como tu Padre amoroso.</p></div>
            <div class="jesus-card"><span class="icon">👥</span><h3>Comparte tu Fe</h3><p>Cuéntales a otros lo que Jesús ha hecho en tu vida. Tu testimonio puede cambiar vidas.</p></div>
        </div>
    </div>

    <!-- Preguntas sobre la fe -->
    <div class="jesus-section-alt">
        <div class="section-header">
            <span class="section-tag">Dudas Comunes</span>
            <h2>Preguntas sobre la Fe Cristiana</h2>
        </div>
        <div style="max-width:800px;margin:0 auto;">
            <?php
            $fe_faqs = [
                ['¿Cómo puedo estar seguro de que soy salvo?', 'La seguridad de tu salvación no se basa en tus sentimientos, sino en la promesa de Dios. Si has confesado a Jesús como Señor y crees en tu corazón que Dios le resucitó, eres salvo. La Biblia dice: "Estas cosas os he escrito a vosotros que creéis en el nombre del Hijo de Dios, para que sepáis que tenéis vida eterna" (1 Juan 5:13).'],
                ['¿Por qué Dios permite el sufrimiento?', 'Vivimos en un mundo caído donde el pecado trajo sufrimiento. Sin embargo, Dios usa las pruebas para fortalecer nuestra fe y moldear nuestro carácter. Además, Él promete estar con nosotros en medio de la prueba y nunca permitir más de lo que podamos soportar (1 Corintios 10:13).'],
                ['¿Qué pasa después de la muerte?', 'La Biblia enseña que después de la muerte viene el juicio (Hebreos 9:27). Los que han puesto su fe en Cristo irán a la presencia de Dios para estar con Él por toda la eternidad. Los que rechazaron a Cristo enfrentarán la separación eterna de Dios. Por eso es tan importante recibir a Jesús hoy.'],
                ['¿Una vez salvo, siempre salvo?', 'La Biblia enseña que aquellos que verdaderamente han nacido de nuevo están seguros en Cristo. Jesús dijo: "Yo les doy vida eterna; y no perecerán jamás, ni nadie las arrebatará de mi mano" (Juan 10:28). La salvación es un regalo de Dios que no se pierde.'],
                ['¿Cómo puedo vencer el pecado?', 'La victoria sobre el pecado viene por el poder del Espíritu Santo que vive en ti. No se trata de esforzarte más, sino de rendirte al control del Espíritu. La Biblia dice: "Andad en el Espíritu, y no satisfagáis los deseos de la carne" (Gálatas 5:16). También es importante tener comunión con otros creyentes.'],
                ['¿Debo ser bautizado?', 'El bautismo es un paso importante de obediencia después de recibir a Cristo. No salva, pero es un testimonio público de tu fe. Jesús mismo fue bautizado y mandó a sus discípulos a bautizar a los nuevos creyentes (Mateo 28:19).'],
            ];
            foreach ($fe_faqs as $i => $faq) { ?>
                <div class="faq-mini-item <?php echo $i === 0 ? 'open' : ''; ?>">
                    <div class="faq-mini-header" onclick="this.parentElement.classList.toggle('open')"><?php echo esc_html($faq[0]); ?> <span style="font-size:1.2rem;transition:transform 0.3s;">+</span></div>
                    <div class="faq-mini-body"><?php echo esc_html($faq[1]); ?></div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Llamado -->
    <div class="jesus-section" style="text-align:center;padding-bottom:0;">
        <div style="background:var(--gradient-blue);color:#fff;padding:60px 40px;border-radius:20px;">
            <div style="font-size:4rem;margin-bottom:16px;">✝</div>
            <h2 style="font-family:var(--font-display);font-size:clamp(1.5rem,3vw,2.5rem);font-weight:900;margin-bottom:16px;">¿Quieres Recibir a Jesús Hoy?</h2>
            <p style="opacity:0.9;max-width:600px;margin:0 auto 30px;font-size:1.05rem;line-height:1.8;">Si deseas entregar tu vida a Cristo, puedes hacer esta oración: <em>"Señor Jesús, reconozco que soy pecador y necesito tu perdón. Creo que moriste por mí y resucitaste. Te recibo como mi Señor y Salvador. Te pido que entres en mi corazón y transformes mi vida. Amén."</em></p>
            <p style="opacity:0.85;">Si hiciste esta oración, ¡bienvenido a la familia de Dios! <a href="<?php echo esc_url(home_url('/contacto/')); ?>" style="color:var(--accent-gold);font-weight:700;">Contáctanos</a> para acompañarte en tu nuevo caminar.</p>
        </div>
    </div>
</div>
<?php }

// ===========================
// HISTORIA PAGE (dinámica desde CPT)
// ===========================
function iglesia_page_historia() {
    $etapas = iglesia_get_historia_data(); ?>
<style>
.timeline { position: relative; padding: 40px 0; }
.timeline::before { content: ''; position: absolute; left: 50%; top: 0; bottom: 0; width: 3px; background: var(--gradient-blue); transform: translateX(-50%); border-radius: 2px; }
.timeline-item { display: flex; align-items: flex-start; margin-bottom: 40px; position: relative; }
.timeline-item:nth-child(odd) { flex-direction: row; }
.timeline-item:nth-child(even) { flex-direction: row-reverse; }
.timeline-content { width: calc(50% - 40px); background: #fff; padding: 30px; border-radius: 16px; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.04); transition: var(--transition); }
.timeline-content:hover { transform: translateY(-4px); box-shadow: var(--shadow); }
.timeline-content .year { font-family: var(--font-display); font-size: 1.8rem; font-weight: 900; color: var(--blue-primary); margin-bottom: 8px; }
.timeline-content h3 { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
.timeline-content p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.7; }
.timeline-content img { width:100%; height:170px; object-fit:cover; border-radius:10px; margin-top:14px; }
.timeline-dot { position: absolute; left: 50%; transform: translateX(-50%); width: 20px; height: 20px; background: var(--accent-gold); border: 4px solid #fff; border-radius: 50%; top: 30px; box-shadow: 0 0 0 3px var(--blue-primary); z-index: 2; }
@media (max-width: 768px) {
    .timeline::before { left: 20px; }
    .timeline-item, .timeline-item:nth-child(even) { flex-direction: row; }
    .timeline-content { width: calc(100% - 60px); margin-left: 60px; }
    .timeline-dot { left: 20px; }
}
</style>

<div class="container">
    <div class="section-header">
        <span class="section-tag">Nuestro Legado</span>
        <h2>Historia de la Iglesia</h2>
        <p>Conoce el camino que Dios ha recorrido con nosotros desde nuestros inicios</p>
    </div>
    <div style="text-align:center;max-width:800px;margin:0 auto 50px;">
        <p style="font-size:1.05rem;line-height:1.9;color:var(--text-muted);">Nuestra iglesia nació del deseo de predicar el Evangelio puro de Jesucristo en nuestra comunidad. Desde sus humildes comienzos hasta el día de hoy, hemos visto la mano fiel de Dios guiando cada paso.</p>
    </div>
    <div class="timeline">
        <?php foreach ($etapas as $etapa): ?>
        <div class="timeline-item">
            <div class="timeline-content">
                <div class="year"><?php echo esc_html($etapa['anio']); ?></div>
                <h3><?php echo esc_html($etapa['title']); ?></h3>
                <p><?php echo esc_html($etapa['content']); ?></p>
                <?php if (!empty($etapa['imagen'])): ?>
                    <img src="<?php echo esc_url($etapa['imagen']); ?>" alt="<?php echo esc_attr($etapa['title']); ?>">
                <?php endif; ?>
            </div>
            <div class="timeline-dot"></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php }

// ===========================
// OFRENDA
// ===========================
function iglesia_page_ofrenda() { ?>
<section class="ofrenda-section">
    <span class="ofrenda-icon">🙏</span>
    <h2>Da Tu Ofrenda</h2>
    <p class="ofrenda-verse">Tu generosidad hace posible el ministerio de esta iglesia. Cada ofrenda es semilla que Dios multiplica para su gloria y la bendición de muchos. <em>"El que siembra escasamente, también segará escasamente; y el que siembra generosamente, generosamente también segará."</em> (2 Corintios 9:6)</p>
    <div class="ofrenda-methods">
        <div class="ofrenda-method"><div class="ico">💳</div><h3>Tarjeta de Débito/Crédito</h3><p>Próximamente disponible</p></div>
        <div class="ofrenda-method"><div class="ico">📱</div><h3>Pago Móvil</h3><p>Via WhatsApp o transferencia</p></div>
        <div class="ofrenda-method"><div class="ico">🏦</div><h3>Transferencia Bancaria</h3><p>Contáctanos para los datos</p></div>
        <div class="ofrenda-method"><div class="ico">🕊️</div><h3>En el Culto</h3><p>Ofrenda durante el servicio</p></div>
    </div>
    <p style="color:var(--text-muted);font-size:.9rem;">Para más información, contáctanos al <strong>(503) 0000-0000</strong> o en <strong>iglesia@portaliglesia.com</strong></p>
    <a href="<?php echo esc_url(home_url('/contacto/')); ?>" class="btn btn-blue" style="margin-top:24px;display:inline-block;">Contáctanos</a>
</section>
<?php }
