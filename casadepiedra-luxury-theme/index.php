<?php
/**
 * Template Name: Blog / Default Index
 * Description: Plantilla por defecto del tema.
 */
get_header(); ?>

<main style="padding-top: 150px; min-height: 75vh; padding-bottom: 5rem;">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="grid gap-8">
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="reveal-text" style="margin-bottom: 3rem;">
                    <h2 class="text-h2"><a href="<?php the_permalink(); ?>" style="color: inherit; text-decoration: none;"><?php the_title(); ?></a></h2>
                    <div class="text-body-lg">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
            </div>
            
            <div class="pagination reveal-text" style="margin-top: 2rem;">
                <?php echo paginate_links(); ?>
            </div>
        <?php else : ?>
            <h1 class="text-h2 reveal-text">No se encontraron resultados</h1>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>