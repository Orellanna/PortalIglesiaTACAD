<?php
/**
 * Himnario Page Template
 */
get_header();
iglesia_page_banner('Himnario', 'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=1600&auto=format&fit=crop&q=60');

$himnos = [
    ['1', 'A Dios Sea La Gloria', 'To God Be the Glory', "A Dios sea la gloria, grande cosas Él hizo,\nTan amó al mundo que a Su Hijo nos dio.\nQuien en Él creyere no será perdido,\nSi en fe a Jesucristo recibimos hoy.\n\nEstribillo:\nAlabad al Señor, alabad al Señor,\n¡Que todo el que cree a Sus pies se postrará!\nAlabad al Señor, alabad al Señor,\n¡Que todo el que cree a Sus pies se postrará!"],
    ['2', 'Qué Alegría Cuando Me Dijeron', 'How Great Thou Art', "Señor mi Dios, al contemplar los cielos,\nEl firmamento y las estrellas mil,\nAl oír tu voz en los poderosos truenos,\nY ver brillar al sol en su cenit.\n\nEstribillo:\n¡Mi corazón, oh Dios, entona su canción!\nQué grande es Él, qué grande es Él.\n¡Mi corazón, oh Dios, entona su canción!\nQué grande es Él, qué grande es Él."],
    ['3', 'Santo, Santo, Santo', 'Holy, Holy, Holy', "Santo, santo, santo, Señor omnipotente,\nSiempre el labio mío loores te dará.\nSanto, santo, santo, te adoro reverente,\nDios en tres personas, bendita Trinidad.\n\nSanto, santo, santo, la inmensa muchedumbre\nDe ángeles que cumplen tu santa voluntad,\nAnte Ti se postra, bañada de tu lumbre,\nAnte el mar de vidrio, coros de deidad."],
    ['4', 'Grande Es Tu Fidelidad', 'Great Is Thy Faithfulness', "Oh Dios eterno, tu misericordia\nNi una sombra de duda tendrá;\nTu compasión y bondad nunca fallan,\nY por los siglos el mismo serás.\n\nEstribillo:\nGrande es tu fidelidad, grande es tu fidelidad,\nMañana tras mañana nuevas misericordias veo;\nToda la gracia que necesito, tú me lo das,\n¡Grande es tu fidelidad, Señor, en mí!"],
    ['5', 'Castillo Fuerte Es Nuestro Dios', 'A Mighty Fortress', "Castillo fuerte es nuestro Dios,\nDefensa y buen escudo;\nCon su poder nos libertará\nEn todo trance agudo.\nCon furia y con afán\nAcósanos Satán;\nPor armas deja ver\nAstucia y gran poder;\nCual él no hay en la tierra.\n\nNuestro valor es nada aquí,\nCon él todo es perdido;\nMas por nosotros pugnará\nDe Dios el escogido."],
    ['6', 'Cristo Me Ama', 'Jesus Loves Me', "Cristo me ama, bien lo sé,\nSu Palabra me hace ver\nQue los niños son de aquel\nQuien es nuestro amigo fiel.\n\nEstribillo:\nSí, Cristo me ama,\nSí, Cristo me ama,\nSí, Cristo me ama,\nLa Biblia dice así."],
    ['7', 'Sublime Gracia', 'Amazing Grace', "Sublime gracia del Señor\nQue a mí, pecador, salvó;\nFui ciego mas hoy veo yo,\nPerdido y Él me halló.\n\nSu gracia me enseñó a temer,\nMis dudas ahuyentó;\nOh, cuán precioso fue a mi ser\nCuando Él me transformó."],
    ['8', 'Hay Poder en la Sangre', 'There Is Power in the Blood', "¿Quieres ser libre de la carga del mal?\nHay poder en la sangre del Cordero;\n¿Quieres sobrar, sobre el mundo triunfal?\nHay poder en la sangre del Cordero.\n\nEstribillo:\nHay poder, poder, sin igual poder,\nEn Jesús, quien murió;\nHay poder, poder, sin igual poder,\nEn la sangre que Él vertió."],
    ['9', 'Al Mundo Paz', 'Joy to the World', "Al mundo paz, nació Jesús,\nHoy es el Rey;\nQue todo el mundo le reciba\nY goce en buena lid;\nY goce en buena lid,\nY goce, goce en buena lid."],
    ['10', 'Firmes y Adelante', 'Onward, Christian Soldiers', "Firmes y adelante, huestes de la fe,\nSin temor alguno, que Jesús nos ve.\nJefe soberano, Cristo al frente va,\nY la regia enseña tremolando está.\n\nEstribillo:\nFirmes y adelante, huestes de la fe,\nSin temor alguno, que Jesús nos ve."],
    ['11', 'Usa Mi Vida', 'Use My Life', "Usa mi vida, usa mis manos,\nÚsalas para Ti Señor;\nUsa mi vida, usa mis labios,\nPara alabar tu gran amor.\n\nHazme un instrumento,\nHazme un instrumento,\nHazme un instrumento de Tu paz de Señor."],
    ['12', 'El Señor Es Mi Pastor', 'The Lord Is My Shepherd', "El Señor es mi pastor, nada me faltará;\nEn lugares de delicados pastos me hará descansar;\nJunto a aguas de reposo me pastoreará,\nConfortará mi alma, por amor de Su nombre me guiará."],
];
?>

<section class="hymnal-section">
    <div class="container">
        <p class="section-title">Adoración</p>
        <h2 class="section-heading" style="margin-bottom:30px;">Himnario y Alabanzas</h2>
        <div class="hymnal-search">
            <input type="text" id="hymn-search" placeholder="🔍 Buscar himno por nombre...">
        </div>
        <div class="hymns-grid" id="hymns-grid">
            <?php foreach ($himnos as $h) { ?>
                <div class="hymn-card" 
                     data-title="<?php echo esc_attr(strtolower($h[1])); ?>"
                     data-lyrics="<?php echo esc_attr($h[3]); ?>"
                     onclick="openHymn('<?php echo esc_js($h[1]); ?>', `<?php echo esc_js($h[3]); ?>`)">
                    <p class="hymn-num">Himno #<?php echo esc_html($h[0]); ?></p>
                    <h3><?php echo esc_html($h[1]); ?></h3>
                    <p><?php echo esc_html($h[2]); ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Modal for lyrics -->
<div class="hymn-modal" id="hymn-modal">
    <div class="hymn-modal-inner">
        <span class="hymn-modal-close" onclick="closeHymn()">&times;</span>
        <h2 id="hymn-modal-title"></h2>
        <div class="hymn-lyrics" id="hymn-modal-lyrics"></div>
    </div>
</div>

<script>
function openHymn(title, lyrics) {
    document.getElementById('hymn-modal-title').textContent = title;
    document.getElementById('hymn-modal-lyrics').textContent = lyrics;
    document.getElementById('hymn-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeHymn() {
    document.getElementById('hymn-modal').classList.remove('open');
    document.body.style.overflow = '';
}

document.getElementById('hymn-modal').addEventListener('click', function(e){
    if (e.target === this) closeHymn();
});

document.getElementById('hymn-search').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.hymn-card').forEach(card => {
        card.style.display = card.dataset.title.includes(q) ? '' : 'none';
    });
});
</script>

<?php get_footer(); ?>
