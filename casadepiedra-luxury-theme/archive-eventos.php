<?php
/**
 * Template Name: Archivo de Eventos
 * Description: Plantilla para mostrar el grid de eventos públicos.
 */
get_header(); 

$portada_url = function_exists('casa_opt_media') ? casa_opt_media('casa_opt_eventos_portada') : '';
?>

<section style="position: relative; z-index: 2; width: 100%; height: clamp(400px, 48vh, 550px); display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 2.5rem; background: #080808;">
    <img src="<?php echo esc_url($portada_url); ?>" alt="Eventos Portada" fetchpriority="high" decoding="async" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; filter: brightness(0.68);" class="gs-zoom-in" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.2) 0%, rgba(8,8,8,0.55) 75%, #080808 100%); z-index: 2; pointer-events: none;"></div>
    
    <div style="position: relative; z-index: 3; text-align: center; padding: 40px clamp(1rem, 4vw, 3rem) 0; max-width: 900px; margin: 0 auto;">
        <span class="text-script" style="color: var(--color-accent); font-size: 1.4rem; display: block; margin-bottom: 0.2rem;"><?php echo esc_html(get_option('casa_opt_global_eventos_subtitle', 'Celebraciones')); ?></span>
        <h1 style="color: #fff; font-size: clamp(2.3rem, 4.5vw, 3.6rem); margin: 0 0 0.6rem 0; font-family: var(--font-heading); line-height: 1.1; text-shadow: 0 10px 30px rgba(0,0,0,0.85);"><?php echo esc_html(get_option('casa_opt_global_eventos_title', 'Próximos Eventos')); ?></h1>
        
        <!-- Ornament -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.8rem; margin: 0.8rem 0;">
            <span style="height: 1px; width: 50px; background: linear-gradient(to right, transparent, rgba(193,98,30,0.7)); display: inline-block;"></span>
            <span style="color: var(--color-accent); font-size: 0.85rem;">✦</span>
            <span style="height: 1px; width: 50px; background: linear-gradient(to left, transparent, rgba(193,98,30,0.7)); display: inline-block;"></span>
        </div>

        <p style="color: #eaeaea; font-size: clamp(1.1rem, 2vw, 1.4rem); font-family: var(--font-heading); font-style: italic; margin: 0.5rem auto 0.8rem; line-height: 1.4; text-shadow: 0 4px 15px rgba(0,0,0,0.85);">
            &ldquo;<?php echo esc_html(ucfirst(get_option('casa_opt_global_eventos_desc', 'Experiencias únicas y celebraciones a tu medida.'))); ?>&rdquo;
        </p>

        <span style="color: var(--color-accent); font-size: 0.74rem; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 600; text-shadow: 0 2px 10px rgba(0,0,0,0.9); display: block; margin-top: 0.4rem;">
            Ex Hacienda Casa de Piedra &bull; Eventos Exclusivos
        </span>
    </div>
</section>

<div style="padding-bottom: 6rem; min-height: 50vh;">
    <div class="container">

        <?php
        $args = array(
            'post_type'      => 'eventos',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC'
        );
        $eventos_query = new WP_Query($args);

        if ($eventos_query->have_posts()) : ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12">
                <?php while ($eventos_query->have_posts()) : $eventos_query->the_post(); 
                    $btn_texto = get_post_meta(get_the_ID(), '_evento_boton_texto', true) ?: 'Me interesa';
                    $btn_enlace = get_post_meta(get_the_ID(), '_evento_enlace', true);
                    $link_url = !empty($btn_enlace) ? esc_url($btn_enlace) : '#';
                ?>
                    <div class="reveal-text">
                        <div class="luxury-card" style="height: 100%; display: flex; flex-direction: column;">
                            <a href="<?php the_permalink(); ?>" style="text-decoration: none; display: block;">
                                <div style="height: 300px; overflow: hidden; position: relative;">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="img-cover hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async" style="transition: transform 0.7s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';" />
                                    <?php else : ?>
                                        <div class="img-cover" style="background-color: var(--color-surface);"></div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <div style="padding: 2.5rem; flex-grow: 1; display: flex; flex-direction: column;">
                                <span class="text-caption">Próximamente</span>
                                <h3 class="text-h3" style="color: var(--color-text-primary); margin-top: 0.5rem; margin-bottom: 1rem;">
                                    <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;"><?php the_title(); ?></a>
                                </h3>
                                <div class="text-body-lg" style="margin-bottom: 2rem; flex-grow: 1;">
                                    <?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(), 20, '...'); ?>
                                </div>
                                
                                <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; border-top: 1px solid var(--color-border-inner); padding-top: 1.5rem; margin-top: auto;">
                                    <a href="<?php the_permalink(); ?>" style="color: var(--color-accent); font-family: var(--font-heading); font-size: 1.2rem; border-bottom: 1px solid var(--color-border); padding-bottom: 4px; text-decoration: none;">
                                        Ver detalles
                                    </a>
                                    
                                    <a href="<?php echo $link_url; ?>" <?php if(!empty($btn_enlace)) echo 'target="_blank" rel="noopener noreferrer"'; ?> style="color: #000; background: var(--color-accent); padding: 0.5rem 1rem; border-radius: 4px; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                        <?php echo esc_html($btn_texto); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div style="text-align: center; padding: 6rem 0;">
                <span class="text-script" style="font-size: 3rem; color: var(--color-accent); display: block; margin-bottom: 1rem;">Estamos creando nuevas experiencias para ti</span>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
