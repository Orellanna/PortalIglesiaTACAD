<?php
get_header();
$s = get_search_query();
iglesia_page_banner('Buscar: ' . $s);
?>
<section class="blog-section">
    <div class="container">
        <div style="max-width:600px;margin:0 auto 40px;">
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <div style="display:flex;gap:10px;">
                    <input type="search" name="s" value="<?php echo esc_attr($s); ?>" placeholder="Buscar..." style="flex:1;padding:14px 20px;border:2px solid #e8e8e8;border-radius:50px;font-family:var(--font-body);font-size:0.95rem;outline:none;">
                    <button type="submit" class="btn btn-blue" style="padding:14px 28px;">Buscar</button>
                </div>
            </form>
        </div>
        <?php if (have_posts()) { ?>
        <p style="text-align:center;color:var(--text-muted);margin-bottom:30px;"><?php echo $wp_query->found_posts; ?> resultados para &ldquo;<strong><?php echo esc_html($s); ?></strong>&rdquo;</p>
        <div class="blog-grid">
            <?php while (have_posts()) { the_post(); ?>
            <article class="blog-card">
                <?php
                $thumb = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'medium_large') : 'https://images.unsplash.com/photo-1501167786227-4cba60f6d58f?w=600&auto=format&fit=crop';
                echo '<img src="' . esc_url($thumb) . '" alt="' . the_title_attribute(array('echo' => false)) . '">';
                ?>
                <div class="blog-card-body">
                    <?php $cat = get_the_category(); $cat_name = !empty($cat) ? $cat[0]->name : 'Blog'; ?>
                    <span class="category"><?php echo esc_html($cat_name); ?></span>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                    <a href="<?php the_permalink(); ?>" class="read-more">Leer m&aacute;s &rarr;</a>
                </div>
            </article>
            <?php } ?>
        </div>
        <?php } else { ?>
        <div style="text-align:center;padding:60px 0;">
            <p style="font-size:1.1rem;color:var(--text-muted);">No se encontraron resultados para &ldquo;<?php echo esc_html($s); ?>&rdquo;. Intenta con otros t&eacute;rminos.</p>
        </div>
        <?php } ?>
    </div>
</section>
<?php get_footer(); ?>
