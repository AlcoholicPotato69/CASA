<?php get_header(); ?>
<main style="padding-top: 150px; padding-bottom: 7rem; min-height: 80vh;">
    <div class="container">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <div class="grid md:grid-cols-12">
                <div class="md:col-span-7 reveal-text">
                    <div style="border-radius: 1.5rem; overflow: hidden; height: 500px;">
                        <?php if(has_post_thumbnail()) { ?><img src="<?php the_post_thumbnail_url('full'); ?>" class="img-cover" /><?php } ?>
                    </div>
                </div>
                <div class="md:col-span-5 reveal-text" style="display: flex; flex-direction: column; justify-content: center;">
                    <h1 class="text-h2" style="margin: 0 0 1.5rem 0; color: #fff;"><?php the_title(); ?></h1>
                    <div class="text-body-lg" style="margin-bottom: 2rem;"><?php the_content(); ?></div>
                    <button type="button" class="btn-open-quote-modal" style="align-self: flex-start; padding: 1rem 2.5rem; background: var(--color-accent); color: #000; border: none; cursor: pointer; border-radius: 9999px; text-decoration: none; font-weight: 600; font-family: var(--font-body); font-size: 1.05rem;">Cotizar</button>
                </div>
            </div>
        <?php endwhile; endif; ?>
    </div>
</main>
<?php get_footer(); ?>