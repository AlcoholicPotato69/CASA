<?php
/* Template Name: Plantilla de Inicio (Hero SPA) */
get_header();
$bg = get_option('casa_opt_home_hero_img') ?: (has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'full') : 'https://casadepiedraleon.mx/wp-content/uploads/2023/09/Fachada_Noche.jpg');
$sub = get_option('casa_opt_home_hero_subtitle') ?: (get_post_meta(get_the_ID(), '_hero_subtitle', true) ?: 'Desde 1845');
$title = get_option('casa_opt_home_hero_title') ?: (get_post_meta(get_the_ID(), '_hero_title', true) ?: 'El recinto más exclusivo <br /> de León.');
$desc = get_option('casa_opt_home_hero_desc') ?: (get_post_meta(get_the_ID(), '_hero_desc', true) ?: 'Arquitectura de época, lujo contemporáneo y servicio impecable para bodas, convenciones y eventos que hacen historia.');
?>
<section style="position: relative; z-index: 2; min-height: 100vh; display: flex; align-items: center; padding-top: 10rem; overflow: hidden; background: #080808;">
    <div style="position: absolute; inset: 0; z-index: 1;">
        <img id="hero-bg" src="<?php echo esc_url($bg); ?>" class="img-cover" fetchpriority="high" decoding="async" />
        <div style="position: absolute; inset: 0; background: rgba(8, 8, 8, 0.7);"></div>
    </div>
    <div class="container" style="position: relative; z-index: 10;">
        <div style="max-width: 900px;">
            <div style="overflow: hidden;"><span class="text-script reveal-text" style="display: block; margin-bottom: 1rem;"><?php echo esc_html($sub); ?></span></div>
            <div style="overflow: hidden;"><h1 class="text-hero reveal-text" style="margin-bottom: 1.5rem; text-shadow: 0 10px 30px rgba(0,0,0,0.5);"><?php echo wp_kses_post($title); ?></h1></div>
            <div style="overflow: hidden;"><p class="text-body-lg reveal-text" style="color: rgba(255,255,255,0.8); margin-bottom: 3rem; max-width: 600px;"><?php echo esc_html($desc); ?></p></div>
            <div class="reveal-text" style="display:flex; gap:1.5rem; align-items:center;">
                <a href="<?php echo esc_url(home_url('/espacios')); ?>" style="padding: 1rem 2rem; background: var(--color-accent); color: #0a0a0a; border-radius: 9999px; text-decoration: none; font-weight: 600;">Ver Espacios</a>
                <a href="<?php echo esc_url(home_url('/galeria')); ?>" style="color: var(--color-accent); text-decoration: none; font-family: var(--font-heading); font-size: 1.2rem; border-bottom: 1px solid var(--color-border);">Ver Galería</a>
            </div>
        </div>
    </div>
</section>
<div style="padding: 4rem 0;"><div class="container"><?php while(have_posts()): the_post(); the_content(); endwhile; ?></div></div>
<?php get_footer(); ?>