<?php
/**
 * Template for the Home/Front Page
 */
get_header();

function iglesia_img($file, $fallback) {
    $path = get_template_directory() . '/images/' . $file;
    $url  = get_template_directory_uri() . '/images/' . $file;
    return file_exists($path) ? $url : $fallback;
}

$img_interior  = iglesia_img('iglesia.jpeg',      'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?q=80&w=2000&auto=format&fit=crop');
$carrusel_slides = iglesia_get_carrusel_data();
?>

<!-- HERO SLIDER (dinámico desde el admin: Carrusel) -->
<section class="hero-slider" id="hero" role="banner">
    <?php foreach ($carrusel_slides as $i => $slide):
        // Cada slide admite título con "em" opcional; la primera línea/negritas se respetan tal cual
        $title_lines = array_map('trim', explode("\n", str_replace('<br>', "\n", $slide['title'])));
        ?>
    <div class="slide <?php echo $i === 0 ? 'active' : ''; ?>">
        <img src="<?php echo esc_url($slide['imagen']); ?>" alt="<?php echo esc_attr($slide['title']); ?>" class="hero-slide-img">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <h1><?php echo implode('<br>', array_map('esc_html', $title_lines)); ?></h1>
            <?php if ($slide['subtitle']): ?><p><?php echo esc_html($slide['subtitle']); ?></p><?php endif; ?>
            <?php if ($slide['enlace']): ?>
                <a href="<?php echo esc_url($slide['enlace']); ?>" class="btn btn-gold" style="margin-top:18px;display:inline-block;">Conocer más</a>
            <?php else: ?>
                <p class="verse">"Bienvenido a Casa"</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <button class="slider-arrow prev" id="slide-prev" aria-label="Anterior">&#8249;</button>
    <button class="slider-arrow next" id="slide-next" aria-label="Siguiente">&#8250;</button>
    <div class="hero-dots" id="hero-dots">
        <?php foreach ($carrusel_slides as $i => $s): ?>
        <span class="hero-dot <?php echo $i === 0 ? 'active' : ''; ?>" data-slide="<?php echo (int)$i; ?>"></span>
        <?php endforeach; ?>
    </div>
</section>

<!-- SPLIT PANEL -->
<section class="split-panel reveal">
    <div class="panel-img">
        <img src="<?php echo esc_url($img_interior); ?>" alt="Templo Tabernáculo Cristiano AD">
    </div>
    <div class="panel-content">
        <div class="panel-badge">Bienvenido a Casa</div>
        <h2><?php echo esc_html(iglesia_nombre()); ?></h2>
        <p><?php echo esc_html(iglesia_direccion()); ?></p>
        <p class="verse-ref">"Una iglesia cerca de ti"</p>
        <a href="<?php echo esc_url(home_url('/visitanos/')); ?>" class="btn btn-gold" style="margin-top:24px;">Conoce más</a>
    </div>
</section>

<!-- EN VIVO 24/7 -->
<section id="live-stream-section" class="en-vivo-section reveal" style="display:none;">
    <div class="container">
        <span class="section-label">Transmisión en Vivo</span>
        <h2>En Vivo <span style="color:var(--blue-primary);">24/7</span></h2>
        <p class="subtitle">Música, enseñanza y adoración desde Tabernáculo Cristiano</p>
        <div class="en-vivo-iframe-wrap">
            <iframe
                id="live-stream-iframe"
                src=""
                title="Transmisión en Vivo"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        <p style="margin-top:15px; color: #888; font-size:0.9rem;">Activa el sonido para escuchar el servicio en vivo.</p>
    </div>
</section>

<!-- INFO CARDS: Asientos, Parqueo, Ministerio -->
<section class="info-cards">
    <div class="container">
        <p class="section-title">Conoce Nuestro Templo</p>
        <h2 class="section-heading">Un Lugar Preparado Para Ti</h2>
        <div class="cards-grid">
            <div class="info-card">
                <span class="card-icon">⛪</span>
                <h3>Capacidad</h3>
                <p>Auditorio amplio, moderno y climatizado para toda tu familia.</p>
            </div>
            <div class="info-card">
                <span class="card-icon">🚗</span>
                <h3>Parqueo Seguro</h3>
                <p>Estacionamiento amplio y vigilado justo al lado de la iglesia.</p>
            </div>
            <div class="info-card">
                <span class="card-icon">👶</span>
                <h3>Ministerio Infantil</h3>
                <p>Aulas especializadas y maestros con vocación para tus hijos.</p>
            </div>
            <div class="info-card">
                <span class="card-icon">🎵</span>
                <h3>Alabanza Moderna</h3>
                <p>Un equipo de adoración que lleva a la congregación a la presencia de Dios.</p>
            </div>
        </div>
    </div>
</section>

<!-- MISSION / VISION BANNER -->
<section class="mission-banner">
    <div class="container">
        <h2>Nuestra Misión y Visión</h2>
        <p>Conoce el propósito que Dios ha puesto en nuestro corazón para nuestra ciudad y las naciones. Estamos comprometidos con predicar el Evangelio puro y restaurar familias enteras.</p>
        <?php
        $mv_page = get_page_by_path('mision-y-vision');
        $mv_url  = $mv_page ? get_permalink($mv_page->ID) : home_url('/mision-y-vision/');
        ?>
        <a href="<?php echo esc_url($mv_url); ?>" class="btn btn-white">Conocer más</a>
    </div>
</section>

<!-- MAP SECTION -->
<section class="map-section reveal">
    <div class="container">
        <div class="map-info">
            <h2>Encuéntranos</h2>
            <?php $dir = iglesia_direccion(); if ($dir): ?><p><span class="icon">📍</span> <span><?php echo esc_html($dir); ?></span></p><?php endif; ?>
            <?php $tel = iglesia_telefono(); if ($tel): ?><p><span class="icon">📞</span> <span><?php echo esc_html($tel); ?></span></p><?php endif; ?>
            <?php $em = iglesia_email(); if ($em): ?><p><span class="icon">✉️</span> <span><?php echo esc_html($em); ?></span></p><?php endif; ?>
            <?php $hor = iglesia_horarios(); if ($hor): ?>
            <p class="schedule-title">Horarios de Cultos</p>
            <?php foreach (explode("\n", $hor) as $line): $line = trim($line); if ($line): ?>
            <p><span class="icon">🕐</span> <span><?php echo esc_html($line); ?></span></p>
            <?php endif; endforeach; endif; ?>
        </div>
        <div class="map-embed">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d717.5877399125291!2d-89.17163624304584!3d14.31750191435547!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f63a732c2655619%3A0x167ba5b19832ca45!2sTabern%C3%A1culo%20Cristiano%20Asambleas%20de%20Dios%20La%20Palma!5e1!3m2!1ses-419!2ssv!4v1786321360425!5m2!1ses-419!2ssv" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
        </div>
    </div>
</section>

<?php get_footer(); ?>
