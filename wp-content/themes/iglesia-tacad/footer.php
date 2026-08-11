</div><!-- /content-wrap -->

<footer id="site-footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <div class="logo">Tabernáculo <span>Cristiano</span></div>
            <div class="logo-sub">Asambleas de Dios La Palma</div>
            <p>Una iglesia que cree en la Palabra de Dios. Comprometidos con predicar el Evangelio puro de Jesucristo en nuestra ciudad y las naciones.</p>
            <div class="footer-social">
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="#" aria-label="Spotify"><i class="fab fa-spotify"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Páginas</h4>
            <ul>
                <li><a href="<?php echo esc_url(home_url('/')); ?>">Inicio</a></li>
                <li><a href="<?php echo esc_url(home_url('/jesus/')); ?>">Jesús</a></li>
                <li><a href="<?php echo esc_url(home_url('/acerca-de/')); ?>">Acerca de</a></li>
                <li><a href="<?php echo esc_url(home_url('/sermones/')); ?>">Sermones</a></li>
                <li><a href="<?php echo esc_url(home_url('/himnario/')); ?>">Himnario</a></li>
                <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">Blog</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Comunidad</h4>
            <ul>
                <li><a href="<?php echo esc_url(home_url('/visitanos/')); ?>">Visítanos</a></li>
                <li><a href="<?php echo esc_url(home_url('/ministerios/')); ?>">Ministerios</a></li>
                <li><a href="<?php echo esc_url(home_url('/ofrenda/')); ?>">Ofrenda</a></li>
                <li><a href="<?php echo esc_url(home_url('/contacto/')); ?>">Contacto</a></li>
                <li><a href="<?php echo esc_url(home_url('/preguntas-frecuentes/')); ?>">Preguntas Frecuentes</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Horarios</h4>
            <ul>
                <li><a href="#">Domingos 9:00am y 11:00am</a></li>
                <li><a href="#">Miércoles 7:00pm</a></li>
                <li><a href="#">Viernes Jóvenes 7:00pm</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-bottom-inner">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Todos los derechos reservados. | Diseñado para la gloria de Dios</p>
        </div>
    </div>
</footer>

<div class="cookie-consent" id="cookie-consent">
    <div class="cookie-consent-inner">
        <p>Este sitio utiliza cookies para mejorar tu experiencia. Al continuar navegando, aceptas nuestra <a href="#">política de privacidad</a>.</p>
        <button class="btn btn-gold btn-sm" onclick="acceptCookies()">Aceptar</button>
    </div>
</div>

<script>
function acceptCookies() {
    document.getElementById('cookie-consent').classList.remove('show');
    localStorage.setItem('cookies_accepted', 'true');
}
if (!localStorage.getItem('cookies_accepted')) {
    setTimeout(function() {
        document.getElementById('cookie-consent').classList.add('show');
    }, 2000);
}
</script>

<?php wp_footer(); ?>
</body>
</html>
