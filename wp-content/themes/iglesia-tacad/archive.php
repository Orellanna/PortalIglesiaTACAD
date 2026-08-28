<?php
get_header();
$title = get_the_archive_title();
iglesia_page_banner($title);
?>
<section class="blog-section">
    <div class="container">
        <?php if (have_posts()) { ?>
        <div class="blog-grid">
            <?php while (have_posts()) { the_post(); ?>
            <article class="blog-card">
                <?php if (has_post_thumbnail()) { echo '<img src="' . get_the_post_thumbnail_url(null, 'medium_large') . '" alt="' . the_title_attribute(['echo'=>false]) . '">'; } else { echo '<img src="https://images.unsplash.com/photo-1501167786227-4cba60f6d58f?w=600&auto=format&fit=crop" alt="Blog">'; } ?>
                <div class="blog-card-body">
                    <span class="category"><?php $cat = get_the_category(); echo !empty($cat) ? $cat[0]->name : 'Blog'; ?></span>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                    <a href="<?php the_permalink(); ?>" class="read-more">Leer más &rarr;</a>
                </div>
            </article>
            <?php } ?>
        </div>
        <?php iglesia_render_pagination(); ?>
        <?php } else { ?>
        <p style="text-align:center;color:var(--text-muted);">No hay publicaciones en esta categoría.</p>
        <div style="text-align:center;margin-top:20px;">
            <a href="<?php echo esc_url(iglesia_blog_url()); ?>" class="btn btn-blue btn-sm">&larr; Ver todas las publicaciones</a>
        </div>
        <?php } ?>
    </div>
</section>
<?php get_footer(); ?>
