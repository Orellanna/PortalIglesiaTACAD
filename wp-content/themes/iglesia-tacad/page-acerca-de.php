<?php
/**
 * Template: Acerca de
 */
get_header();

$img_iglesia = 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?q=80&w=800&auto=format&fit=crop';
$img_legado = 'https://images.unsplash.com/photo-1519834785169-98be25ec3f84?q=80&w=800&auto=format&fit=crop';
?>

<style>
.acerca-section {
    padding: var(--section-pad);
    background: var(--bg-page);
    min-height: 80vh;
}

.acerca-header {
    text-align: center;
    margin-bottom: 60px;
}

.acerca-header h1 {
    font-family: var(--font-display);
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 900;
    color: var(--blue-primary);
    margin-bottom: 16px;
}

.acerca-header p {
    font-family: var(--font-body);
    font-size: 1.1rem;
    color: var(--text-muted);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.8;
}

.cards-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
    max-width: 900px;
    margin: 0 auto;
}

.info-card-acerca {
    background: #fff;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    cursor: pointer;
    transition: all 0.3s ease;
}

.info-card-acerca:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-md);
}

.info-card-acerca img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.info-card-acerca:hover img {
    transform: scale(1.05);
}

.info-card-acerca-body {
    padding: 28px;
}

.info-card-acerca-body h3 {
    font-family: var(--font-display);
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--blue-primary);
    margin-bottom: 12px;
}

.info-card-acerca-body p {
    font-size: 0.95rem;
    color: var(--text-muted);
    line-height: 1.7;
}

.info-card-acerca-body .card-footer {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 16px;
    color: var(--blue-primary);
    font-weight: 600;
    font-size: 0.85rem;
}

.info-card-acerca-body .card-footer i {
    transition: transform 0.3s ease;
}

.info-card-acerca:hover .card-footer i {
    transform: translateX(4px);
}

/* Modal Styles */
.modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.modal-overlay.active {
    display: flex;
}

.modal-content-acerca {
    background: #fff;
    border-radius: var(--radius);
    max-width: 800px;
    width: 100%;
    max-height: 85vh;
    overflow-y: auto;
    position: relative;
    animation: modalIn 0.3s ease;
}

@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.modal-close-btn {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0,0,0,0.1);
    border: none;
    font-size: 1.3rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    transition: all 0.3s ease;
    z-index: 10;
}

.modal-close-btn:hover {
    background: rgba(0,0,0,0.2);
    color: var(--text-dark);
}

/* Modal 1 - La Iglesia */
.modal-header-m1 {
    background: var(--blue-primary);
    padding: 50px 40px 40px;
    text-align: center;
    color: #fff;
}

.modal-header-m1 h2 {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 900;
    margin-bottom: 8px;
}

.modal-header-m1 p {
    font-size: 1rem;
    opacity: 0.9;
}

.modal-body-m1 {
    padding: 40px;
}

.modal-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 40px;
}

.modal-col {
    text-align: center;
    padding: 30px 20px;
    background: var(--bg-page);
    border-radius: var(--radius);
}

.modal-col-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 1.8rem;
}

.modal-col-icon.purple {
    background: var(--blue-primary);
    color: #fff;
}

.modal-col-icon.yellow {
    background: var(--accent-gold);
    color: #fff;
}

.modal-col h3 {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 12px;
}

.modal-col p {
    font-size: 0.9rem;
    color: var(--text-muted);
    line-height: 1.7;
}

.modal-historico {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.modal-historico h3 {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 12px;
    position: relative;
    display: inline-block;
}

.modal-historico h3::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 3px;
    background: var(--accent-gold);
    border-radius: 2px;
}

.modal-historico p {
    font-size: 0.95rem;
    color: var(--text-muted);
    line-height: 1.8;
    max-width: 600px;
    margin: 20px auto 0;
}

/* Modal 2 - Nuestro Legado */
.modal-header-m2 {
    background: var(--blue-dark);
    padding: 50px 40px 40px;
    text-align: center;
    color: #fff;
}

.modal-header-m2 h2 {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 900;
    margin-bottom: 8px;
}

.modal-header-m2 p {
    font-size: 1rem;
    opacity: 0.9;
}

.modal-header-m2 .modal-close-btn {
    background: rgba(255,255,255,0.15);
    color: #fff;
}

.modal-header-m2 .modal-close-btn:hover {
    background: rgba(255,255,255,0.25);
}

.modal-body-m2 {
    padding: 40px;
}

.modal-intro p {
    font-size: 0.95rem;
    color: var(--text-muted);
    line-height: 1.8;
    text-align: center;
    max-width: 700px;
    margin: 0 auto 40px;
}

.pastores-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.pastor-card {
    background: #fff;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid #eee;
    text-align: center;
    padding-bottom: 24px;
}

.pastor-avatar {
    width: 100%;
    height: 180px;
    background: var(--bg-page);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: var(--blue-primary);
}

.pastor-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pastor-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 16px 0 12px;
}

.pastor-badge.fundador {
    background: var(--accent-gold);
    color: #fff;
}

.pastor-badge.actual {
    background: var(--blue-primary);
    color: #fff;
}

.pastor-card h4 {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 6px;
    padding: 0 16px;
}

.pastor-card .dates {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 12px;
}

.pastor-card .dates i {
    color: var(--blue-primary);
}

.pastor-card p {
    font-size: 0.8rem;
    color: var(--text-muted);
    line-height: 1.6;
    padding: 0 16px;
}

/* Responsive */
@media (max-width: 768px) {
    .cards-container { grid-template-columns: 1fr; }
    .modal-grid-2 { grid-template-columns: 1fr; }
    .pastores-grid { grid-template-columns: 1fr; }
    .modal-header-m1, .modal-header-m2 { padding: 40px 24px 30px; }
    .modal-body-m1, .modal-body-m2 { padding: 30px 24px; }
}
</style>

<div class="acerca-section">
    <div class="container">
        <div class="acerca-header">
            <h1>Nuestra Identidad y Propósito</h1>
            <p>Conoce quiénes somos y la historia que nos respalda. Somos una comunidad comprometida con la luz y la verdad.</p>
        </div>

        <div class="cards-container">
            <div class="info-card-acerca" onclick="openModal('modal-iglesia')">
                <img src="<?php echo esc_url($img_iglesia); ?>" alt="La Iglesia">
                <div class="info-card-acerca-body">
                    <h3>La Iglesia</h3>
                    <p>Somos un refugio espiritual diseñado para el encuentro. Creemos en una fe activa, en el poder de la comunidad y en la transformación a través de la enseñanza auténtica.</p>
                    <div class="card-footer">
                        <span>Ver más</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <div class="info-card-acerca" onclick="openModal('modal-legado')">
                <img src="<?php echo esc_url($img_legado); ?>" alt="Nuestro Legado">
                <div class="info-card-acerca-body">
                    <h3>Nuestro Legado</h3>
                    <p>Con décadas de historia, hemos construido cimientos sólidos de servicio y amor. Nuestro legado no son los edificios, sino las vidas restauradas que continúan esparciendo esperanza.</p>
                    <div class="card-footer">
                        <span>Ver más</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: La Iglesia -->
<div class="modal-overlay" id="modal-iglesia">
    <div class="modal-content-acerca">
        <button class="modal-close-btn" onclick="closeModal('modal-iglesia')">&times;</button>
        <div class="modal-header-m1">
            <h2>La Iglesia</h2>
            <p>Nuestra identidad, propósito y legado en Cristo.</p>
        </div>
        <div class="modal-body-m1">
            <div class="modal-grid-2">
                <div class="modal-col">
                    <div class="modal-col-icon purple">
                        <i class="fas fa-compass"></i>
                    </div>
                    <h3>Misión</h3>
                    <p>Iluminar el camino de toda persona hacia los pies de Cristo por medio de la proclamación del evangelio, siendo una comunidad de fe, esperanza y amor.</p>
                </div>
                <div class="modal-col">
                    <div class="modal-col-icon yellow">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Visión</h3>
                    <p>Ser una iglesia al servicio de Dios y la comunidad, llena de amor y empatía por todos, formando disciples que transformen su entorno.</p>
                </div>
            </div>
            <div class="modal-historico">
                <h3>Histórico</h3>
                <p>Desde nuestra fundación, hemos sido un faro de esperanza en la comunidad. A través de décadas de servicio, hemos visto vidas transformadas, familias restauradas y comunidades enteras enamoradas de Cristo. Nuestra historia es un testimonio del poder del evangelio para cambiar el corazón humano.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Nuestro Legado -->
<div class="modal-overlay" id="modal-legado">
    <div class="modal-content-acerca">
        <button class="modal-close-btn" onclick="closeModal('modal-legado')">&times;</button>
        <div class="modal-header-m2">
            <h2>Nuestro Legado</h2>
            <p>Histórico de Pastores Principales</p>
        </div>
        <div class="modal-body-m2">
            <div class="modal-intro">
                <p>A lo largo de nuestra historia, hombres de Dios han guiado esta congregación con sabiduría, amor y dedicación. Honramos su legado y el cimiento espiritual que establecieron para las generaciones venideras.</p>
            </div>
            <div class="pastores-grid">
                <div class="pastor-card">
                    <div class="pastor-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="pastor-badge fundador">Fundador</span>
                    <h4>Rev. Elías Mendoza</h4>
                    <div class="dates">
                        <i class="far fa-calendar"></i>
                        <span>1985 - 1998</span>
                    </div>
                    <p>Pionero de la obra, estableció los cimientos de fe y servicio que aún nos sostienen hoy.</p>
                </div>
                <div class="pastor-card">
                    <div class="pastor-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="pastor-badge" style="background:#64748b;color:#fff;">Pastor</span>
                    <h4>Dr. Samuel Ríos</h4>
                    <div class="dates">
                        <i class="far fa-calendar"></i>
                        <span>1998 - 2010</span>
                    </div>
                    <p>Expandió la visión pastoral y fortaleció la estructura eclesiástica con su sabiduría.</p>
                </div>
                <div class="pastor-card">
                    <div class="pastor-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="pastor-badge actual">★ Actual</span>
                    <h4>Pr. Melky</h4>
                    <div class="dates">
                        <i class="far fa-calendar"></i>
                        <span>2010 - Presente</span>
                    </div>
                    <p>Líder visionario que continúa el legado de fe, llevando la iglesia a nuevas alturas.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = '';
}

document.querySelectorAll('.modal-overlay').forEach(function(modal) {
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(function(modal) {
            modal.classList.remove('active');
        });
        document.body.style.overflow = '';
    }
});
</script>

<?php get_footer(); ?>