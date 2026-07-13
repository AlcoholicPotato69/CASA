<?php
/**
 * Template Name: Page Normal
 * Description: Plantilla estándar para páginas.
 */
get_header(); ?>

<div style="padding-top: 150px; padding-bottom: 5rem; min-height: 80vh;">
    <div class="container">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            
            <h1 class="text-hero reveal-text" style="margin-bottom: 2rem;"><?php the_title(); ?></h1>
            
            <div class="page-content text-body-lg reveal-text">
                <?php the_content(); ?>
            </div>
            
        <?php endwhile; endif; ?>
    </div>
</div>

<?php get_footer(); ?>
