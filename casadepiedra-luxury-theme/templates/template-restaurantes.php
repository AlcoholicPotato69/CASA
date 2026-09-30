<?php
/* Template Name: Plantilla Lista de Restaurantes */
get_header(); ?>
<main style="padding-top: 150px; padding-bottom: 7rem; min-height: 85vh;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 5rem;">
            <span class="text-caption reveal-text">Gastronomía</span>
            <h1 class="text-h2 reveal-text"><?php the_title(); ?></h1>
            <div class="text-body-lg reveal-text" style="max-width: 750px; margin: 0 auto;"><?php while(have_posts()): the_post(); the_content(); endwhile; ?></div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 2.5rem;">
            <?php $q = new WP_Query(array('post_type' => 'restaurantes', 'posts_per_page' => -1));
            if ($q->have_posts()) : while ($q->have_posts()) : $q->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="luxury-card reveal-text">
                    <div style="height: 270px; overflow: hidden;">
                        <?php if(has_post_thumbnail()) { ?><img src="<?php the_post_thumbnail_url('large'); ?>" class="img-cover" loading="lazy" decoding="async" /><?php } ?>
                    </div>
                    <div style="padding: 2rem;">
                        <h3 class="text-h3" style="color: #fff; margin-bottom:0.5rem;"><?php the_title(); ?></h3>
                        <p class="text-body-lg" style="font-size: 0.95rem;"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                        <span style="color: var(--color-accent); font-family: var(--font-heading); font-size: 1.15rem; margin-top: 1rem; display: inline-block;">Ver restaurante →</span>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>
    </div>
</main>
<?php get_footer(); ?>