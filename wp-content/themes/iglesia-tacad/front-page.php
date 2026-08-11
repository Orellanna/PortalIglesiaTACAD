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
$img_evento    = iglesia_img('Evento.jpeg',       'https://images.unsplash.com/photo-1548625361-ec8536098270?q=80&w=2000&auto=format&fit=crop');
$img_comunion  = iglesia_img('evento2.jpeg',       'https://images.unsplash.com/photo-1504052434569-70ad5836ab65?q=80&w=2000&auto=format&fit=crop');
$img_edificio  = iglesia_img('iglesia.jpeg',      'https://images.unsplash.com/photo-1500430855475-dc8d64c8acad?q=80&w=900&auto=format&fit=crop');
$img_gallery1  = 'https://images.unsplash.com/photo-1548625361-ec8536098270?q=80&w=900&auto=format&fit=crop';
$img_gallery2  = 'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?q=80&w=900&auto=format&fit=crop';
$img_gallery3  = 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?q=80&w=900&auto=format&fit=crop';
$img_gallery4  = 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?q=80&w=900&auto=format&fit=crop';
$img_gallery5  = 'https://images.unsplash.com/photo-1484820540004-14229fe36ca4?q=80&w=900&auto=format&fit=crop';
$img_gallery6  = 'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?q=80&w=900&auto=format&fit=crop';
?>

<!-- HERO SLIDER -->
<section class="hero-slider" id="hero" role="banner">
    <!-- Slide 1: Church name hero -->
    <div class="slide active">
        <img src="https://images.unsplash.com/photo-1438232992991-995b7058bbb3?q=80&w=2000&auto=format&fit=crop" alt="Tabernáculo Cristiano AD" class="hero-slide-img">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <h1>Tabernáculo Cristiano<br><em>Asambleas de Dios</em></h1>
            <p>La Palma, Chalatenango</p>
            <p class="verse">"Una iglesia de fe, esperanza y amor"</p>
        </div>
    </div>
    <!-- Slide 2 -->
    <div class="slide">
        <img src="<?php echo esc_url($img_evento); ?>" alt="Culto de celebración" class="hero-slide-img">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <h1>Una iglesia<br>de <em>Fe</em></h1>
            <p>Porque por fe andamos, no por vista.</p>
            <p class="verse">2 Corintios 5:7</p>
        </div>
    </div>
    <!-- Slide 3 -->
    <div class="slide">
        <img src="<?php echo esc_url($img_comunion); ?>" alt="Comunión en la Iglesia" class="hero-slide-img">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <h1>Tu familia<br>es <em>Bienvenida</em></h1>
            <p>Un lugar de restauración y esperanza para todos.</p>
            <p class="verse">"Bienvenido a Casa"</p>
        </div>
    </div>

    <button class="slider-arrow prev" id="slide-prev" aria-label="Anterior">&#8249;</button>
    <button class="slider-arrow next" id="slide-next" aria-label="Siguiente">&#8250;</button>
    <div class="hero-dots" id="hero-dots">
        <span class="hero-dot active" data-slide="0"></span>
        <span class="hero-dot" data-slide="1"></span>
        <span class="hero-dot" data-slide="2"></span>
    </div>
</section>

<!-- SPLIT PANEL -->
<section class="split-panel reveal">
    <div class="panel-img">
        <img src="<?php echo esc_url($img_interior); ?>" alt="Templo Tabernáculo Cristiano AD">
    </div>
    <div class="panel-content">
        <div class="panel-badge">Bienvenido a Casa</div>
        <h2>Tabernáculo Cristiano<br><em>Asambleas de Dios</em></h2>
        <p>3 Calle Poniente, Barrio El Centro, La Palma, Chalatenango</p>
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
            <p><span class="icon">📍</span> <span>3 Calle Poniente, Barrio El Centro, La Palma, Chalatenango, El Salvador</span></p>
            <p><span class="icon">📞</span> <span>(503) 0000-0000</span></p>
            <p><span class="icon">✉️</span> <span>iglesia@tacad.me</span></p>
            <p class="schedule-title">Horarios de Cultos</p>
            <p><span class="icon">🕐</span> <span><strong>Martes:</strong> Culto de Oración — 6:30pm</span></p>
            <p><span class="icon">🕐</span> <span><strong>Miércoles:</strong> Ayuno de Mujeres — 9:00am</span></p>
            <p><span class="icon">🕐</span> <span><strong>Jueves:</strong> Culto General — 6:30pm</span></p>
            <p><span class="icon">🕐</span> <span><strong>Sábado:</strong> Culto Matutino — 5:30am</span></p>
            <p><span class="icon">🕐</span> <span><strong>Sábado:</strong> Culto de CMF — 3:00pm</span></p>
            <p><span class="icon">🕐</span> <span><strong>Sábado:</strong> Culto Juvenil — 6:30pm</span></p>
            <p><span class="icon">🕐</span> <span><strong>Domingo:</strong> Culto General — 9:00am a 3:00pm</span></p>
        </div>
        <div class="map-embed">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d717.5877399125291!2d-89.17163624304584!3d14.31750191435547!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f63a732c2655619%3A0x167ba5b19832ca45!2sTabern%C3%A1culo%20Cristiano%20Asambleas%20de%20Dios%20La%20Palma!5e1!3m2!1ses-419!2ssv!4v1786321360425!5m2!1ses-419!2ssv" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
        </div>
    </div>
</section>

<?php get_footer(); ?>
