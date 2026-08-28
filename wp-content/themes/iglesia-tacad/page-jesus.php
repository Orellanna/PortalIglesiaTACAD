<?php
/**
 * Template: Jesús — Cristiana Evangélica Pentecostal
 */
get_header();
?>

<style>
/* ===== JESUS PAGE — REDISEÑO VISUAL ===== */
.jesus-wrap { overflow: hidden; }

/* Hero Parallax-style */
.jesus-hero {
    position: relative;
    min-height: 520px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    background: radial-gradient(ellipse at 20% 50%, #1a237e 0%, #0d1542 60%, #060b24 100%);
    color: #fff;
    overflow: hidden;
    margin-bottom: 0;
}
.jesus-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.jesus-hero .particles {
    position: absolute; inset: 0; overflow: hidden;
}
.jesus-hero .particle {
    position: absolute;
    width: 4px; height: 4px;
    background: rgba(255,255,255,0.25);
    border-radius: 50%;
    animation: jesusFloat linear infinite;
}
.jesus-hero .particle:nth-child(1) { left:10%; top:20%; animation-duration:12s; width:6px;height:6px; }
.jesus-hero .particle:nth-child(2) { left:25%; top:60%; animation-duration:18s; width:3px;height:3px; }
.jesus-hero .particle:nth-child(3) { left:45%; top:15%; animation-duration:14s; }
.jesus-hero .particle:nth-child(4) { left:60%; top:70%; animation-duration:20s; width:5px;height:5px; }
.jesus-hero .particle:nth-child(5) { left:75%; top:30%; animation-duration:16s; }
.jesus-hero .particle:nth-child(6) { left:85%; top:55%; animation-duration:22s; width:3px;height:3px; }
.jesus-hero .particle:nth-child(7) { left:15%; top:45%; animation-duration:11s; width:5px;height:5px; }
.jesus-hero .particle:nth-child(8) { left:55%; top:80%; animation-duration:19s; }

@keyframes jesusFloat {
    0% { transform: translateY(0) scale(1); opacity:0; }
    10% { opacity:1; }
    90% { opacity:1; }
    100% { transform: translateY(-400px) scale(0.4); opacity:0; }
}

.jesus-hero .hero-inner { position: relative; z-index: 2; padding: 40px 24px; max-width: 750px; }
.jesus-hero .hero-cruz {
    font-size: 2.5rem; display: block; margin-bottom: 16px;
    animation: cruzGlow 3s ease-in-out infinite;
}
@keyframes cruzGlow {
    0%,100% { text-shadow: 0 0 20px rgba(255,215,0,0.3); }
    50% { text-shadow: 0 0 50px rgba(255,215,0,0.7), 0 0 80px rgba(255,215,0,0.4); }
}
.jesus-hero h1 {
    font-family: var(--font-display);
    font-size: clamp(2.4rem, 6vw, 4.5rem);
    font-weight: 900;
    line-height: 1.12;
    margin-bottom: 20px;
    letter-spacing: -0.5px;
}
.jesus-hero h1 .gold { color: var(--accent-gold); }
.jesus-hero .hero-sub {
    font-size: 1.15rem;
    opacity: 0.85;
    max-width: 580px;
    margin: 0 auto;
    line-height: 1.8;
    font-weight: 300;
}
.jesus-hero .hero-scroll {
    position: absolute; bottom: 28px; left: 50%; transform: translateX(-50%); z-index: 3;
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    color: rgba(255,255,255,0.5); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px;
    animation: bounceDown 2s ease infinite;
}
.jesus-hero .hero-scroll span:last-child { font-size: 1.2rem; }
@keyframes bounceDown {
    0%,100% { transform: translateX(-50%) translateY(0); }
    50% { transform: translateX(-50%) translateY(8px); }
}

/* Section utils */
.jesus-sec { padding: 90px 0; position: relative; }
.jesus-sec-alt { padding: 90px 0; background: #f7f8fc; }
.jesus-sec-dark { padding: 90px 0; background: linear-gradient(180deg, #0d1542 0%, #141c5e 100%); color: #fff; }

.jesus-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items: center; }
.jesus-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }
.jesus-grid-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 28px; }
.jesus-grid-22 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 28px; }

/* Card base */
.jesus-card {
    background: #fff;
    border-radius: 20px;
    padding: 42px 32px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 30px rgba(0,0,0,0.05);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position: relative;
    overflow: hidden;
}
.jesus-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
}
.jesus-card .card-stripe {
    position: absolute; top: 0; left: 0; right: 0; height: 5px;
    border-radius: 20px 20px 0 0;
    transform: scaleX(0); transform-origin: left;
    transition: transform 0.5s ease;
}
.jesus-card:hover .card-stripe { transform: scaleX(1); }
.jesus-card .card-icon { font-size: 3rem; margin-bottom: 20px; display: block; transition: transform 0.4s ease; }
.jesus-card:hover .card-icon { transform: scale(1.1) rotate(-5deg); }
.jesus-card h3 { font-family: var(--font-display); font-size: 1.2rem; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
.jesus-card p { font-size: 0.92rem; color: var(--text-muted); line-height: 1.75; }

/* Verse cards */
.verse-card {
    border-radius: 20px;
    padding: 50px 38px;
    text-align: center;
    color: #fff;
    position: relative;
    overflow: hidden;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}
.verse-card:hover { transform: translateY(-4px); box-shadow: 0 12px 36px rgba(0,0,0,0.2); }
.verse-card::before {
    content: '"';
    position: absolute; top: -40px; left: 18px;
    font-size: 12rem; opacity: 0.06; font-family: Georgia, serif; line-height: 1;
}
.verse-card .v-icon { font-size: 2.5rem; margin-bottom: 14px; display: block; }
.verse-card .v-text {
    font-family: var(--font-serif);
    font-size: clamp(1.2rem, 2.2vw, 1.55rem);
    font-style: italic; line-height: 1.85; margin-bottom: 16px;
}
.verse-card .v-ref {
    color: var(--accent-gold); font-weight: 700; font-size: 0.9rem;
    text-transform: uppercase; letter-spacing: 2px;
}

/* Plan de Salvacion steps */
.steps-wrap { max-width: 720px; margin: 0 auto; display: grid; gap: 30px; }
.step-item {
    background: #fff;
    border-radius: 20px;
    padding: 0;
    display: flex;
    align-items: stretch;
    box-shadow: 0 2px 16px rgba(0,0,0,0.05);
    transition: all 0.4s ease;
    overflow: hidden;
}
.step-item:hover { transform: translateX(6px); box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
.step-num {
    min-width: 90px;
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-display); font-size: 2.5rem; font-weight: 900;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.step-num.n1 { background: linear-gradient(135deg, #8B0000, #c0392b); }
.step-num.n2 { background: linear-gradient(135deg, #1a237e, #3949ab); }
.step-num.n3 { background: linear-gradient(135deg, #4a148c, #7b1fa2); }
.step-num.n4 { background: linear-gradient(135deg, #e65100, #ef6c00); }
.step-body { flex: 1; padding: 32px 36px; }
.step-body h3 { font-family: var(--font-display); font-size: 1.15rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; }
.step-body p { font-size: 0.92rem; color: var(--text-muted); line-height: 1.75; }
.step-body em { color: var(--blue-primary); font-weight: 500; }

/* Holy Spirit banner */
.espiritu-banner {
    border-radius: 24px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1fr 1fr;
    box-shadow: 0 12px 50px rgba(0,0,0,0.12);
}
.espiritu-banner .eb-img {
    background: linear-gradient(135deg, #c0392b, #e74c3c);
    display: flex; align-items: center; justify-content: center;
    padding: 60px 40px;
    position: relative; overflow: hidden;
}
.espiritu-banner .eb-img::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='0.06'%3E%3Cpath d='M20 0L24 16h16l-12 8 4 16-12-8-12 8 4-16-12-8h16z'/%3E%3C/g%3E%3C/svg%3E");
}
.espiritu-banner .eb-img .eb-flame {
    font-size: 6rem; position: relative; z-index: 1;
    animation: flameFlicker 2s ease-in-out infinite;
}
@keyframes flameFlicker {
    0%,100% { transform: scale(1); }
    50% { transform: scale(1.08); }
}
.espiritu-banner .eb-content {
    background: #fff; padding: 56px 48px;
    display: flex; flex-direction: column; justify-content: center;
}
.espiritu-banner .eb-content h2 {
    font-family: var(--font-display); font-size: clamp(1.6rem, 2.5vw, 2.2rem);
    font-weight: 800; color: var(--text-dark); margin-bottom: 16px;
}
.espiritu-banner .eb-content p {
    color: var(--text-muted); line-height: 1.85; font-size: 1rem;
}

/* Call to Action */
.jesus-cta {
    background: linear-gradient(135deg, #1a237e 0%, #0d1542 50%, #1a237e 100%);
    border-radius: 28px;
    padding: 80px 60px;
    text-align: center;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.jesus-cta::before {
    content: '✝'; position: absolute; top: -60px; right: -20px;
    font-size: 18rem; opacity: 0.04;
}
.jesus-cta::after {
    content: '🙏'; position: absolute; bottom: -40px; left: -20px;
    font-size: 10rem; opacity: 0.05;
}
.jesus-cta h2 {
    font-family: var(--font-display); font-size: clamp(1.8rem, 4vw, 3rem);
    font-weight: 900; margin-bottom: 20px; position: relative; z-index: 1;
}
.jesus-cta h2 .gold { color: var(--accent-gold); }
.jesus-cta > p {
    opacity: 0.9; max-width: 600px; margin: 0 auto 36px;
    font-size: 1.08rem; line-height: 1.8; position: relative; z-index: 1;
}
.jesus-cta .oracion-box {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 16px;
    padding: 40px 36px;
    font-family: var(--font-serif); font-style: italic;
    font-size: 1.15rem; line-height: 2.2;
    max-width: 680px; margin: 0 auto 32px;
    position: relative; z-index: 1;
}
.jesus-cta .btn-glow {
    display: inline-block;
    background: var(--accent-gold);
    color: #1a1a1a;
    padding: 18px 50px;
    border-radius: 50px;
    font-family: var(--font-display);
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-size: 0.85rem;
    position: relative; z-index: 1;
    transition: all 0.3s ease;
    box-shadow: 0 6px 30px rgba(201, 148, 42, 0.35);
}
.jesus-cta .btn-glow:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 40px rgba(201, 148, 42, 0.5);
    background: #e8b84a;
}

/* Floating images decoration */
.jesus-sec .cross-watermark {
    position: absolute; top: 20px; left: -40px;
    font-size: 18rem; opacity: 0.025; color: var(--blue-primary);
    pointer-events: none;
}

/* Image + text split */
.img-text-split {
    display: grid; grid-template-columns: 1.2fr 1fr; gap: 56px; align-items: center;
}
.img-text-split .split-img {
    border-radius: 24px; overflow: hidden;
    box-shadow: 0 16px 50px rgba(0,0,0,0.12);
}
.img-text-split .split-img img {
    width: 100%; height: 100%; object-fit: cover; display: block;
    transition: transform 0.7s ease;
}
.img-text-split .split-img:hover img { transform: scale(1.03); }
.img-text-split .split-text h2 {
    font-family: var(--font-display); font-size: clamp(1.6rem, 2.5vw, 2.2rem);
    font-weight: 800; color: var(--text-dark); margin-bottom: 16px;
}
.img-text-split .split-text p {
    font-size: 1.02rem; color: var(--text-muted); line-height: 1.85; margin-bottom: 12px;
}

/* Dones grid — more visual */
.don-card {
    background: #fff; border-radius: 20px; padding: 40px 30px;
    text-align: center;
    box-shadow: 0 2px 14px rgba(0,0,0,0.05);
    transition: all 0.4s ease;
    position: relative; overflow: hidden;
}
.don-card:hover { transform: translateY(-6px); box-shadow: 0 10px 36px rgba(0,0,0,0.1); }
.don-card .don-badge {
    width: 70px; height: 70px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 22px;
    font-size: 2.2rem;
    color: #fff;
}
.don-card .don-badge.dc-red { background: linear-gradient(135deg, #c0392b, #e74c3c); }
.don-card .don-badge.dc-orange { background: linear-gradient(135deg, #e67e22, #f39c12); }
.don-card .don-badge.dc-blue { background: linear-gradient(135deg, #2471a3, #2980b9); }
.don-card .don-badge.dc-green { background: linear-gradient(135deg, #1e8449, #27ae60); }
.don-card h3 { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
.don-card p { font-size: 0.88rem; color: var(--text-muted); line-height: 1.7; }

/* Responsive */
@media (max-width: 768px) {
    .jesus-grid-2, .img-text-split, .espiritu-banner { grid-template-columns: 1fr; }
    .jesus-grid-3, .jesus-grid-22 { grid-template-columns: 1fr; }
    .step-item { flex-direction: column; }
    .step-num { min-width: auto; padding: 18px 0; font-size: 2rem; }
    .step-body { padding: 24px 28px; }
    .jesus-cta { padding: 60px 28px; }
    .jesus-cta .oracion-box { padding: 28px 22px; font-size: 1rem; }
    .espiritu-banner .eb-content { padding: 40px 28px; }
    .jesus-sec, .jesus-sec-alt { padding: 60px 0; }
    .jesus-hero { min-height: 420px; }
    .jesus-hero h1 { font-size: clamp(1.8rem, 8vw, 2.8rem); }
}
</style>

<div class="jesus-wrap">

<!-- ==================== HERO ==================== -->
<div class="jesus-hero">
    <div class="particles">
        <div class="particle"></div><div class="particle"></div><div class="particle"></div>
        <div class="particle"></div><div class="particle"></div><div class="particle"></div>
        <div class="particle"></div><div class="particle"></div>
    </div>
    <div class="hero-inner">
        <span class="hero-cruz">✝</span>
        <h1>Jesús es el <span class="gold">Camino</span>,<br>la <span class="gold">Verdad</span> y la <span class="gold">Vida</span></h1>
        <p class="hero-sub">Creemos en Jesucristo como el Hijo de Dios, Salvador del mundo y quien bautiza con Espíritu Santo y fuego. Él transforma vidas, sana corazones y da esperanza eterna.</p>
    </div>
    <div class="hero-scroll">
        <span>Descubre más</span>
        <span>↓</span>
    </div>
</div>

<!-- ==================== ¿QUIÉN ES JESÚS? ==================== -->
<div class="jesus-sec">
    <div class="cross-watermark">✝</div>
    <div class="container">
        <div class="img-text-split">
            <div class="split-img">
                <img src="https://images.unsplash.com/photo-1504052434569-70ad5836ab65?w=800&auto=format&fit=crop&q=80"
                     alt="La cruz de Cristo">
            </div>
            <div class="split-text">
                <div class="section-header" style="text-align:left;margin-bottom:20px;">
                    <span class="section-tag">Conoce a Cristo</span>
                    <h2 style="font-size:clamp(1.6rem,2.5vw,2.2rem);margin-bottom:0;">¿Quién es Jesús?</h2>
                </div>
                <p>Jesús de Nazaret es el Hijo eterno de Dios, el Mesías prometido y el Salvador del mundo. Nació de una virgen, vivió sin pecado, predicó el Reino de Dios con autoridad, sanó enfermos, libertó oprimidos, y entregó su vida en la cruz por amor a nosotros.</p>
                <p>Al tercer día resucitó victorioso sobre la muerte, ascendió al cielo y está sentado a la diestra del Padre intercediendo por su Iglesia. Envió al Espíritu Santo para morar en cada creyente, dándonos poder para vivir en santidad y ser sus testigos.</p>
                <p style="font-weight:600;color:var(--text-dark);">Pronto viene en gloria. Vivimos preparados y expectantes para ese gran día.</p>
            </div>
        </div>
    </div>
</div>

<!-- ==================== PLAN DE SALVACIÓN ==================== -->
<div class="jesus-sec-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">El Mensaje que Transforma</span>
            <h2>Plan de Salvación</h2>
            <p>El Evangelio es poder de Dios para salvación a todo aquel que cree</p>
        </div>
        <div class="steps-wrap">
            <div class="step-item">
                <div class="step-num n1">1</div>
                <div class="step-body">
                    <h3>Dios te ama y tiene un plan para ti</h3>
                    <p>Fuiste creado por amor y con un propósito eterno. Dios desea tener una relación personal contigo y darte una vida plena y abundante. <em>"Yo he venido para que tengan vida, y para que la tengan en abundancia."</em> (Juan 10:10)</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num n2">2</div>
                <div class="step-body">
                    <h3>El pecado nos separó de Dios</h3>
                    <p>Todos hemos pecado. El pecado —toda desobediencia a la voluntad de Dios— nos aleja y nos condena. Por nosotros mismos no podemos reparar esta separación. <em>"Por cuanto todos pecaron y están destituidos de la gloria de Dios."</em> (Romanos 3:23)</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num n3">3</div>
                <div class="step-body">
                    <h3>Jesucristo es la única solución</h3>
                    <p>Dios, en su amor infinito, envió a su Hijo Jesús para morir en la cruz y pagar el precio de nuestros pecados. Su sacrificio es completo y suficiente. <em>"Jesús le dijo: Yo soy el camino, la verdad y la vida; nadie viene al Padre sino por mí."</em> (Juan 14:6)</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num n4">4</div>
                <div class="step-body">
                    <h3>Recibe a Jesús hoy</h3>
                    <p>La salvación es un regalo gratuito que se recibe por fe. Confiesa tus pecados, cree en Jesús como tu Salvador, y entrégale tu vida. <em>"Si confesares con tu boca que Jesús es el Señor, y creyeres en tu corazón que Dios le levantó de los muertos, serás salvo."</em> (Romanos 10:9)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== VERSÍCULOS ==================== -->
<div class="jesus-sec">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Palabra Viva</span>
            <h2>Promesas de Dios para Ti</h2>
        </div>
        <div class="jesus-grid-3">
            <div class="verse-card" style="background:linear-gradient(135deg,#1a237e,#283593);">
                <span class="v-icon">❤️</span>
                <div class="v-text">"Porque de tal manera amó Dios al mundo, que ha dado a su Hijo unigénito, para que todo aquel que en Él cree no se pierda, mas tenga vida eterna."</div>
                <div class="v-ref">Juan 3:16</div>
            </div>
            <div class="verse-card" style="background:linear-gradient(135deg,#4a148c,#6a1b9a);">
                <span class="v-icon">⚡</span>
                <div class="v-text">"Pero recibiréis poder cuando haya venido sobre vosotros el Espíritu Santo, y me seréis testigos en Jerusalén, en toda Judea, en Samaria, y hasta lo último de la tierra."</div>
                <div class="v-ref">Hechos 1:8</div>
            </div>
            <div class="verse-card" style="background:linear-gradient(135deg,#b71c1c,#c62828);">
                <span class="v-icon">🩸</span>
                <div class="v-text">"Si confesamos nuestros pecados, Él es fiel y justo para perdonar nuestros pecados, y limpiarnos de toda maldad."</div>
                <div class="v-ref">1 Juan 1:9</div>
            </div>
        </div>
        <div class="jesus-grid-3" style="margin-top:28px;">
            <div class="verse-card" style="background:linear-gradient(135deg,#0d47a1,#1565c0);">
                <span class="v-icon">🙏</span>
                <div class="v-text">"Clama a mí, y yo te responderé, y te enseñaré cosas grandes y ocultas que tú no conoces."</div>
                <div class="v-ref">Jeremías 33:3</div>
            </div>
            <div class="verse-card" style="background:linear-gradient(135deg,#004d40,#00695c);">
                <span class="v-icon">🏠</span>
                <div class="v-text">"En la casa de mi Padre muchas moradas hay... Voy, pues, a preparar lugar para vosotros."</div>
                <div class="v-ref">Juan 14:2</div>
            </div>
            <div class="verse-card" style="background:linear-gradient(135deg,#e65100,#ef6c00);">
                <span class="v-icon">👑</span>
                <div class="v-text">"He aquí, yo estoy con vosotros todos los días, hasta el fin del mundo."</div>
                <div class="v-ref">Mateo 28:20</div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== BAUTISMO EN EL ESPÍRITU SANTO ==================== -->
<div class="jesus-sec-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Doctrina Pentecostal</span>
            <h2>El Bautismo en el Espíritu Santo</h2>
            <p>La promesa del Padre derramada en Pentecostés, disponible hoy para todo creyente</p>
        </div>
        <div class="espiritu-banner">
            <div class="eb-img">
                <span class="eb-flame">🔥</span>
            </div>
            <div class="eb-content">
                <h2>Poder de lo Alto</h2>
                <p>El bautismo en el Espíritu Santo es una experiencia real y poderosa, prometida por Jesús antes de ascender al cielo. Es el poder sobrenatural que capacita al creyente para vivir en santidad, testificar con denuedo y ejercer los dones del Espíritu.</p>
                <p style="margin-top:12px;">La señal inicial de esta experiencia es <strong>hablar en otras lenguas</strong>, conforme al modelo bíblico en el día de Pentecostés (Hechos 2:4), en casa de Cornelio (Hechos 10:44-46) y en Efeso (Hechos 19:6). Esta promesa gloriosa sigue vigente para <strong>todo creyente que la busque con fe</strong>.</p>
            </div>
        </div>
    </div>
</div>

<!-- ==================== DONES DEL ESPÍRITU ==================== -->
<div class="jesus-sec">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Manifestaciones Divinas</span>
            <h2>Los Dones del Espíritu Santo</h2>
            <p>Dios reparte dones a cada creyente para la edificación del cuerpo de Cristo</p>
        </div>
        <div class="jesus-grid-22">
            <div class="don-card">
                <div class="don-badge dc-red">🗣️</div>
                <h3>Dones de Inspiración</h3>
                <p>Profecía para edificar, exhortar y consolar; diversidad de lenguas como lenguaje espiritual de oración; e interpretación de lenguas para edificación de la congregación. (1 Corintios 12:10)</p>
            </div>
            <div class="don-card">
                <div class="don-badge dc-orange">💡</div>
                <h3>Dones de Revelación</h3>
                <p>Palabra de sabiduría para conocer los propósitos de Dios; palabra de ciencia para recibir conocimiento sobrenatural; y discernimiento de espíritus para distinguir lo divino, lo humano y lo demoníaco. (1 Corintios 12:8-10)</p>
            </div>
            <div class="don-card">
                <div class="don-badge dc-blue">🛐</div>
                <h3>Dones de Poder</h3>
                <p>Fe sobrenatural que mueve montañas; dones de sanidades para ministrar restauración física y emocional; y el hacer milagros como intervenciones sobrenaturales de Dios. (1 Corintios 12:9-10)</p>
            </div>
            <div class="don-card">
                <div class="don-badge dc-green">🍇</div>
                <h3>El Fruto del Espíritu</h3>
                <p>Amor, gozo, paz, paciencia, benignidad, bondad, fe, mansedumbre y templanza. Los dones capacitan para servir; el fruto forma el carácter. Ambos son esenciales en la vida del creyente lleno del Espíritu. (Gálatas 5:22-23)</p>
            </div>
        </div>
    </div>
</div>

<!-- ==================== ENSEÑANZAS ==================== -->
<div class="jesus-sec-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Fundamentos</span>
            <h2>Pilares de Nuestra Fe</h2>
            <p>La doctrina apostólica que sostiene nuestra vida cristiana</p>
        </div>
        <div class="jesus-grid-4">
            <div class="jesus-card">
                <div class="card-stripe" style="background:linear-gradient(90deg,#c0392b,#e74c3c);"></div>
                <span class="card-icon">🙏</span>
                <h3>La Oración</h3>
                <p>El creyente habla con Dios en oración. Orar sin cesar, con fe y perseverancia. La oración ferviente del justo puede mucho.</p>
            </div>
            <div class="jesus-card">
                <div class="card-stripe" style="background:linear-gradient(90deg,#1a237e,#3949ab);"></div>
                <span class="card-icon">📖</span>
                <h3>La Palabra</h3>
                <p>La Biblia es la Palabra inspirada e infalible de Dios. Nuestra guía en doctrina, corrección e instrucción en justicia.</p>
            </div>
            <div class="jesus-card">
                <div class="card-stripe" style="background:linear-gradient(90deg,#4a148c,#7b1fa2);"></div>
                <span class="card-icon">🤝</span>
                <h3>La Comunión</h3>
                <p>Somos el cuerpo de Cristo. Nos congregamos fielmente para adorar, crecer juntos y servirnos unos a otros en amor.</p>
            </div>
            <div class="jesus-card">
                <div class="card-stripe" style="background:linear-gradient(90deg,#e65100,#ef6c00);"></div>
                <span class="card-icon">🔥</span>
                <h3>Poder de Dios</h3>
                <p>Creemos en milagros, sanidades y liberaciones. El mismo Espíritu que levantó a Cristo de entre los muertos mora en nosotros.</p>
            </div>
            <div class="jesus-card">
                <div class="card-stripe" style="background:linear-gradient(90deg,#d4a017,#f0c040);"></div>
                <span class="card-icon">🎵</span>
                <h3>Adoración</h3>
                <p>Adoramos en espíritu y verdad, con música y cántico espiritual. La alabanza transforma atmósferas y nos conecta con el cielo.</p>
            </div>
            <div class="jesus-card">
                <div class="card-stripe" style="background:linear-gradient(90deg,#2e7d32,#43a047);"></div>
                <span class="card-icon">🌍</span>
                <h3>Gran Comisión</h3>
                <p>Id y predicad el Evangelio. Todo creyente es un testigo que comparte lo que Cristo ha hecho en su vida.</p>
            </div>
            <div class="jesus-card">
                <div class="card-stripe" style="background:linear-gradient(90deg,#1565c0,#42a5f5);"></div>
                <span class="card-icon">🍞</span>
                <h3>Santa Cena</h3>
                <p>Recordamos el sacrificio de Cristo. El pan es su cuerpo partido; el fruto de la vid, su sangre derramada para perdón de pecados.</p>
            </div>
            <div class="jesus-card">
                <div class="card-stripe" style="background:linear-gradient(90deg,#00838f,#26c6da);"></div>
                <span class="card-icon">💧</span>
                <h3>Bautismo en Agua</h3>
                <p>Por inmersión, en obediencia a Cristo. Simboliza nuestra muerte al pecado y nueva vida en Él. Es testimonio público de fe.</p>
            </div>
        </div>
    </div>
</div>

<!-- ==================== GUÍA NUEVO CREYENTE ==================== -->
<div class="jesus-sec">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Tu Nueva Vida</span>
            <h2>Primeros Pasos en la Fe</h2>
            <p>Comienza a caminar con Cristo desde hoy</p>
        </div>
        <div class="jesus-grid-4">
            <div class="jesus-card" style="border:2px solid #e0e0ff;">
                <span class="card-icon">📅</span>
                <h3>Lee la Biblia</h3>
                <p>Empieza con el Evangelio de Juan. Un capítulo al día. Pide al Espíritu Santo que ilumine tu entendimiento.</p>
            </div>
            <div class="jesus-card" style="border:2px solid #ffe0e0;">
                <span class="card-icon">🙌</span>
                <h3>Congrégate</h3>
                <p>La iglesia es tu familia espiritual. Ven los domingos a las 9:00 a.m. y participa de los cultos semanales.</p>
            </div>
            <div class="jesus-card" style="border:2px solid #e0ffe0;">
                <span class="card-icon">💬</span>
                <h3>Ora cada día</h3>
                <p>Habla con Dios como con un Padre amoroso. Agradécele, confiésale tus faltas, pídele y alábalo.</p>
            </div>
            <div class="jesus-card" style="border:2px solid #ffe8d0;">
                <span class="card-icon">👥</span>
                <h3>Cuenta tu testimonio</h3>
                <p>Lo que Dios ha hecho en ti puede encender la fe en otros. No calles lo que Cristo ha hecho en tu vida.</p>
            </div>
            <div class="jesus-card" style="border:2px solid #e0e0ff;">
                <span class="card-icon">🕊️</span>
                <h3>Busca el Espíritu Santo</h3>
                <p>Pide al Padre la promesa del bautismo en el Espíritu Santo. Él desea llenarte con poder y darte un lenguaje de oración celestial.</p>
            </div>
            <div class="jesus-card" style="border:2px solid #ffe0e0;">
                <span class="card-icon">💧</span>
                <h3>Bautízate</h3>
                <p>Da testimonio público de tu fe en las aguas del bautismo. Acércate a un pastor y agenda la fecha de tu bautismo.</p>
            </div>
        </div>
    </div>
</div>

<!-- ==================== LLAMADO FINAL ==================== -->
<div class="jesus-sec">
    <div class="container">
        <div class="jesus-cta">
            <h2>¿Quieres Entregarle tu Vida a <span class="gold">Jesús</span>?</h2>
            <p>No esperes más. Cristo te ama y pagó el precio de tus pecados en la cruz. Hoy es el día de tu salvación, sanidad y libertad.</p>
            <div class="oracion-box">
                Señor Jesús, reconozco que soy pecador y necesito tu perdón. Creo que moriste por mí en la cruz y que resucitaste al tercer día para darme vida eterna. Hoy abro mi corazón y te recibo como mi único Salvador y Señor. Lávame con tu sangre, escribe mi nombre en el libro de la vida y lléname con tu Espíritu Santo. Transforma mi vida y ayúdame a vivir para tu gloria. En el nombre de Jesús, ¡Amén!
            </div>
            <p style="position:relative;z-index:1;opacity:0.85;font-size:0.95rem;margin-bottom:28px;">Si hiciste esta oración con fe, eres salvo. ¡Bienvenido a la familia de Dios!</p>
            <a href="<?php echo esc_url(home_url('/contacto/')); ?>" class="btn-glow">Quiero Conocerlos</a>
        </div>
    </div>
</div>

</div><!-- /jesus-wrap -->

<?php get_footer(); ?>
