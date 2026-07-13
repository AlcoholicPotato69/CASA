<?php
/* Template Name: Plantilla Galería Nativa */
get_header(); ?>
<main style="padding-top: 150px; padding-bottom: 7rem; min-height: 85vh;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 4rem;">
            <h1 class="text-h2 reveal-text"><?php the_title(); ?></h1>
        </div>
        <div class="reveal-text"><?php while(have_posts()): the_post(); the_content(); endwhile; ?></div>
    </div>
</main>
<style>
    .wp-block-gallery, .gallery { display: grid !important; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)) !important; gap: 1.5rem !important; list-style: none; padding: 0; }
    .wp-block-image, .gallery-item { border-radius: 1rem; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); }
    .wp-block-image img, .gallery-item img { width: 100% !important; height: 260px !important; object-fit: cover !important; transition: transform 0.8s; }
    .wp-block-image img:hover, .gallery-item img:hover { transform: scale(1.05); }
</style>
<?php get_footer(); ?>