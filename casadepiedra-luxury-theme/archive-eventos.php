<?php
/**
 * Template Name: Archivo de Eventos
 * Description: Plantilla para mostrar el grid de eventos públicos.
 */
get_header(); 

$portada_url = get_option('casa_opt_eventos_portada') ?: get_template_directory_uri() . '/assets/images/salon_pavorreales_1779523097528.png';
?>

<section style="position: relative; width: 100%; height: 50vh; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 4rem;">
    <img src="<?php echo esc_url($portada_url); ?>" alt="Eventos Portada" style="position: absolute; width: 100%; height: 100%; object-fit: cover; z-index: -1; filter: brightness(0.6);" class="gs-zoom-in" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(10,10,10,0.2) 0%, rgba(10,10,10,0.5) 70%, var(--color-bg, #0a0a0a) 100%); z-index: 0; pointer-events: none;"></div>
    <div style="position: relative; z-index: 1; text-align: center;">
        <span class="text-script reveal-text" style="font-size: 3.5rem; color: #fff;">Celebraciones</span>
        <h1 class="text-hero reveal-text" style="color: #fff; text-shadow: 0 10px 30px rgba(0,0,0,0.5); font-size: clamp(3rem, 6vw, 5rem);"><?php echo esc_html(get_option('casa_opt_global_eventos_title', 'Próximos Eventos')); ?></h1>
    </div>
</section>

<div style="padding-bottom: 6rem; min-height: 50vh;">
    <div class="container">
        <div style="margin-bottom: 4rem; text-align: center; max-width: 700px; margin-left: auto; margin-right: auto;">
            <p class="text-body-lg" style="color: rgba(255,255,255,0.8); font-size: 1.2rem;">
                <?php echo esc_html(get_option('casa_opt_global_eventos_desc', 'Experiencias a tu medida.')); ?>
            </p>
        </div>

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
                                        <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="img-cover hover:scale-105 transition-transform duration-700" style="transition: transform 0.7s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';" />
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
