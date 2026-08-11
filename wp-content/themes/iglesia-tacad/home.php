<?php
/**
 * Blog/Archive page template
 */
get_header();
iglesia_page_banner('Blog', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1600&auto=format&fit=crop&q=60');

// Sample placeholder posts if no real posts exist
$blog_posts = [
    ['¿Qué dice la Biblia sobre la oración?', 'La oración es el puente que nos conecta con Dios. En este artículo exploramos los fundamentos bíblicos de esta práctica tan esencial para el creyente.', 'Espiritualidad', 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=600&auto=format&fit=crop'],
    ['La familia en el diseño de Dios', 'Desde el Génesis, Dios diseñó la familia como el fundamento de la sociedad. Descubramos juntos su propósito y cómo fortalecerla en la fe.', 'Familia', 'https://images.unsplash.com/photo-1484820540004-14229fe36ca4?w=600&auto=format&fit=crop'],
    ['Cómo estudiar la Biblia de forma efectiva', 'Muchos creyentes desean conocer más la Palabra de Dios pero no saben cómo comenzar. Compartimos métodos prácticos y sencillos.', 'Biblia', 'https://images.unsplash.com/photo-1501167786227-4cba60f6d58f?w=600&auto=format&fit=crop'],
    ['El poder de la alabanza en tiempos difíciles', 'La historia bíblica está llena de momentos donde la alabanza fue el arma de victoria. Aprende a adorar en medio de la tormenta.', 'Adoración', 'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=600&auto=format&fit=crop'],
    ['La fe: ¿Qué es y cómo crece?', 'La fe es la sustancia de lo que se espera y la evidencia de lo que no se ve. ¿Pero cómo la cultivamos en nuestra vida diaria?', 'Fe', 'https://images.unsplash.com/photo-1445887374063-34abd495852a?w=600&auto=format&fit=crop'],
    ['Servir: La llamada de todo creyente', 'Jesús nos llamó a ser siervos, no señores. Descoremos las formas en que podemos servir en nuestra iglesia y comunidad.', 'Ministerio', 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=600&auto=format&fit=crop'],
];
?>

<section class="blog-section">
    <div class="container">
        <p class="section-title">Reflexiones y Enseñanzas</p>
        <h2 class="section-heading" style="margin-bottom:40px;">Publicaciones del Blog</h2>

        <?php if (have_posts()): ?>
        <div class="blog-grid">
            <?php while (have_posts()): the_post(); ?>
            <article class="blog-card">
                <?php if (has_post_thumbnail()): ?>
                    <img src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="<?php the_title_attribute(); ?>">
                <?php else: ?>
                    <img src="https://images.unsplash.com/photo-1501167786227-4cba60f6d58f?w=600&auto=format&fit=crop" alt="Blog">
                <?php endif; ?>
                <div class="blog-card-body">
                    <span class="category">Blog</span>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                    <a href="<?php the_permalink(); ?>" class="read-more">Leer más &rarr;</a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <!-- Placeholder posts when none exist -->
        <div class="blog-grid">
            <?php foreach ($blog_posts as $post_plc): ?>
            <div class="blog-card">
                <img src="<?php echo esc_url($post_plc[3]); ?>" alt="<?php echo esc_attr($post_plc[0]); ?>">
                <div class="blog-card-body">
                    <span class="category"><?php echo esc_html($post_plc[2]); ?></span>
                    <h3><?php echo esc_html($post_plc[0]); ?></h3>
                    <p><?php echo esc_html(mb_substr($post_plc[1], 0, 100)) . '...'; ?></p>
                    <a href="#" class="read-more">Leer más &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
