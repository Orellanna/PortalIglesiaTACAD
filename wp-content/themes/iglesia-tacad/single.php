<?php
get_header();
if (have_posts()) {
    while (have_posts()) {
        the_post();
        $cat = get_the_category();
        $cat_name = !empty($cat) ? $cat[0]->name : 'Blog';
        $img = has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'large') : 'https://images.unsplash.com/photo-1501167786227-4cba60f6d58f?w=1200&auto=format&fit=crop';
?>
<section style="padding:var(--section-pad);">
    <div class="container" style="max-width:800px;">
        <div style="margin-bottom:30px;">
            <span class="category" style="background:var(--blue-primary);color:#fff;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;padding:5px 14px;border-radius:20px;display:inline-block;margin-bottom:16px;"><?php echo esc_html($cat_name); ?></span>
            <h1 style="font-family:var(--font-display);font-size:clamp(1.5rem,3.5vw,2.5rem);font-weight:900;color:var(--text-dark);line-height:1.3;margin-bottom:16px;"><?php the_title(); ?></h1>
            <p style="color:var(--text-muted);font-size:0.9rem;">Publicado el <?php echo get_the_date(); ?></p>
        </div>
        <img src="<?php echo esc_url($img); ?>" alt="<?php the_title_attribute(); ?>" style="width:100%;border-radius:20px;margin-bottom:40px;box-shadow:var(--shadow);">
        <div style="font-size:1.05rem;line-height:1.9;color:var(--text-dark);">
            <?php the_content(); ?>
        </div>
        <div style="margin-top:50px;padding-top:30px;border-top:1px solid #eee;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
            <a href="<?php echo esc_url(iglesia_blog_url()); ?>" class="btn btn-blue btn-sm">&larr; Volver al Blog</a>
            <?php if (has_tag()) { the_tags('<div style="display:flex;gap:8px;flex-wrap:wrap;"><span style="font-size:0.8rem;color:var(--text-muted);">Etiquetas:</span>', '', '</div>'); } ?>
        </div>
        <?php if (comments_open() || get_comments_number()) { comments_template(); } ?>
    </div>
</section>
<?php
    }
}
get_footer();
