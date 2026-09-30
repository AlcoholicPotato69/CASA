<?php
/**
 * Plantilla para el CPT individual de Eventos
 */
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $btn_texto = get_post_meta(get_the_ID(), '_evento_boton_texto', true) ?: 'Me interesa';
    $btn_enlace = get_post_meta(get_the_ID(), '_evento_enlace', true);
    $link_url = !empty($btn_enlace) ? esc_url($btn_enlace) : '#';
    $hero_img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : (function_exists('casa_opt_media') ? casa_opt_media('casa_opt_eventos_portada') : '');
    $desc_ev = has_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : 'Una celebración inolvidable en un entorno arquitectónico sin precedentes.';
?>

<!-- HERO BANNER SUPERIOR DEL EVENTO -->
<section style="position: relative; z-index: 2; width: 100%; height: clamp(400px, 48vh, 550px); display: flex; align-items: center; justify-content: center; overflow: hidden; background: #080808;">
    <img src="<?php echo esc_url($hero_img); ?>" alt="<?php the_title_attribute(); ?>" fetchpriority="high" decoding="async" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; filter: brightness(0.68);" class="gs-zoom-in" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.2) 0%, rgba(8,8,8,0.55) 75%, #080808 100%); z-index: 2; pointer-events: none;"></div>

    <div style="position: relative; z-index: 3; text-align: center; padding: 40px clamp(1rem, 4vw, 3rem) 0; max-width: 900px; margin: 0 auto;">
        <span class="text-script" style="color: var(--color-accent); font-size: 1.4rem; display: block; margin-bottom: 0.2rem;">Evento Especial</span>
        <h1 style="color: #fff; font-size: clamp(2.3rem, 5vw, 4.2rem); margin: 0 0 0.6rem 0; font-family: var(--font-heading); line-height: 1.1; text-shadow: 0 10px 30px rgba(0,0,0,0.85);">
            <?php the_title(); ?>
        </h1>
        
        <!-- Ornament -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.8rem; margin: 0.8rem 0;">
            <span style="height: 1px; width: 50px; background: linear-gradient(to right, transparent, rgba(193,98,30,0.7)); display: inline-block;"></span>
            <span style="color: var(--color-accent); font-size: 0.85rem;">✦</span>
            <span style="height: 1px; width: 50px; background: linear-gradient(to left, transparent, rgba(193,98,30,0.7)); display: inline-block;"></span>
        </div>

        <p style="color: #eaeaea; font-size: clamp(1.1rem, 2vw, 1.4rem); font-family: var(--font-heading); font-style: italic; margin: 0.5rem auto 0.8rem; line-height: 1.4; text-shadow: 0 4px 15px rgba(0,0,0,0.85);">
            &ldquo;<?php echo esc_html(ucfirst($desc_ev)); ?>&rdquo;
        </p>

        <span style="color: var(--color-accent); font-size: 0.74rem; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 600; text-shadow: 0 2px 10px rgba(0,0,0,0.9); display: block; margin-top: 0.4rem;">
            Ex Hacienda Casa de Piedra &bull; Celebración Exclusiva
        </span>
    </div>
</section>

<main style="padding-top: clamp(3.5rem, 7vw, 5.5rem); padding-bottom: 6rem; min-height: 60vh;">
    <div class="container">
            <div style="margin-bottom: 2rem;">
                <a href="<?php echo esc_url(home_url('/eventos')); ?>" style="color: var(--color-accent); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-family: var(--font-body); font-size: 0.9rem;">
                    ← Volver a Eventos
                </a>
            </div>

            <div class="grid md:grid-cols-12 gap-12">
                <div class="md:col-span-7 lg:col-span-8 reveal-text">
                    <div style="border-radius: var(--radius-lg); overflow: hidden; height: 500px; position: relative;">
                        <?php if(has_post_thumbnail()) { ?>
                            <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title_attribute(); ?>" class="img-cover" style="width: 100%; height: 100%; object-fit: cover;" />
                        <?php } else { ?>
                            <div style="width: 100%; height: 100%; background-color: var(--color-surface); display: flex; align-items: center; justify-content: center; color: var(--color-text-secondary);">
                                Sin imagen destacada
                            </div>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="md:col-span-5 lg:col-span-4 reveal-text" style="display: flex; flex-direction: column; justify-content: center;">
                    <span class="text-caption" style="margin-bottom: 0.5rem; display: block;">Detalles del Evento</span>
                    <h1 class="text-h2" style="margin: 0 0 1.5rem 0; color: #fff;"><?php the_title(); ?></h1>
                    
                    <div class="text-body-lg" style="margin-bottom: 2rem;">
                        <?php the_content(); ?>
                    </div>
                    
                    <div style="margin-top: 1rem; border-top: 1px solid var(--color-border-inner); padding-top: 2rem;">
                        <a href="<?php echo $link_url; ?>" <?php if(!empty($btn_enlace)) echo 'target="_blank" rel="noopener noreferrer"'; ?> style="display: inline-flex; padding: 1rem 2.5rem; background: var(--color-accent); color: #000; border-radius: 9999px; text-decoration: none; font-weight: 600; font-family: var(--font-body); transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                            <?php echo esc_html($btn_texto); ?>
                        </a>
                        <?php if(empty($btn_enlace)): ?>
                            <p style="margin-top: 1rem; font-size: 0.85rem; color: var(--color-text-secondary);">* Añade un enlace en la configuración del evento para que el botón funcione.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer(); ?>
