<?php get_header(); ?>
<section style="padding:120px 24px;text-align:center;min-height:60vh;display:flex;align-items:center;justify-content:center;">
    <div class="container" style="max-width:600px;">
        <div style="font-size:6rem;margin-bottom:20px;line-height:1;">🔍</div>
        <h1 style="font-family:var(--font-display);font-size:2.5rem;font-weight:900;color:var(--blue-primary);margin-bottom:12px;">Página No Encontrada</h1>
        <p style="color:var(--text-muted);font-size:1.1rem;margin-bottom:30px;line-height:1.8;">Lo sentimos, la página que buscas no existe o ha sido movida. Por favor verifica la dirección o vuelve al inicio.</p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-blue">Volver al Inicio</a>
    </div>
</section>
<?php get_footer(); ?>
