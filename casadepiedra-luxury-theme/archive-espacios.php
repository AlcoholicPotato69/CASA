<?php
/**
 * Template Name: Archivo de Espacios
 * Description: Plantilla para mostrar el grid de espacios.
 */
get_header(); 

$portada_url = get_option('casa_opt_espacios_portada') ?: get_template_directory_uri() . '/assets/images/jardin_principal_1779523113451.png';
?>

<section style="position: relative; z-index: 2; width: 100%; height: clamp(400px, 48vh, 550px); display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 2.5rem; background: #080808;">
    <img src="<?php echo esc_url($portada_url); ?>" alt="Espacios Portada" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; filter: brightness(0.68);" class="gs-zoom-in" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.2) 0%, rgba(8,8,8,0.55) 75%, #080808 100%); z-index: 2; pointer-events: none;"></div>
    
    <div style="position: relative; z-index: 3; text-align: center; padding: 40px clamp(1rem, 4vw, 3rem) 0; max-width: 900px; margin: 0 auto;">
        <span class="text-script" style="color: var(--color-accent); font-size: 1.4rem; display: block; margin-bottom: 0.2rem;"><?php echo esc_html(get_option('casa_opt_global_espacios_subtitle', 'Exclusividad')); ?></span>
        <h1 style="color: #fff; font-size: clamp(2.3rem, 4.5vw, 3.6rem); margin: 0 0 0.6rem 0; font-family: var(--font-heading); line-height: 1.1; text-shadow: 0 10px 30px rgba(0,0,0,0.85);"><?php echo esc_html(get_option('casa_opt_global_espacios_title', 'Nuestros Espacios')); ?></h1>
        
        <!-- Ornament -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.8rem; margin: 0.8rem 0;">
            <span style="height: 1px; width: 50px; background: linear-gradient(to right, transparent, rgba(212,175,55,0.7)); display: inline-block;"></span>
            <span style="color: var(--color-accent); font-size: 0.85rem;">✦</span>
            <span style="height: 1px; width: 50px; background: linear-gradient(to left, transparent, rgba(212,175,55,0.7)); display: inline-block;"></span>
        </div>

        <p style="color: #eaeaea; font-size: clamp(1.1rem, 2vw, 1.4rem); font-family: var(--font-heading); font-style: italic; margin: 0.5rem auto 0.8rem; line-height: 1.4; text-shadow: 0 4px 15px rgba(0,0,0,0.85);">
            &ldquo;<?php echo esc_html(ucfirst(get_option('casa_opt_global_espacios_desc', 'Escenarios para grandes historias'))); ?>&rdquo;
        </p>

        <span style="color: var(--color-accent); font-size: 0.74rem; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 600; text-shadow: 0 2px 10px rgba(0,0,0,0.9); display: block; margin-top: 0.4rem;">
            Ex Hacienda Casa de Piedra &bull; Exclusividad &amp; Elegancia
        </span>
    </div>
</section>

<div style="padding-bottom: 6rem;">
    <div class="container">

        <style>
            .espacios-accordion {
                display: flex;
                flex-direction: column; /* Mobile: stack vertically */
                gap: 15px;
                width: 100%;
                height: auto;
                min-height: 70vh;
                padding-bottom: 4rem;
            }
            
            @media (min-width: 768px) {
                .espacios-accordion {
                    flex-direction: row; /* Desktop: horizontal accordion */
                    height: 75vh;
                    max-height: 800px;
                }
            }

            .espacio-panel {
                position: relative;
                flex: 1;
                border-radius: 20px;
                overflow: hidden;
                cursor: pointer;
                transition: flex 0.7s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.4s ease, border-color 0.4s ease;
                background: linear-gradient(135deg, #1c1c1c 0%, #0c0c0c 100%);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-top: 1px solid rgba(255, 255, 255, 0.18);
                box-shadow: 0 15px 35px rgba(0,0,0,0.7);
                min-height: 150px;
            }
            
            @media (min-width: 768px) {
                .espacio-panel:hover {
                    flex: 3.2;
                    border-color: rgba(212, 175, 55, 0.5);
                    box-shadow: 0 25px 60px rgba(0,0,0,0.9), 0 0 40px rgba(212, 175, 55, 0.22);
                    z-index: 10;
                }
            }

            .espacio-panel .img-cover {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                opacity: 0.88;
                filter: grayscale(12%) brightness(0.82);
                transition: all 0.7s cubic-bezier(0.25, 1, 0.5, 1);
            }

            .espacio-panel:hover .img-cover {
                opacity: 1;
                filter: grayscale(0%) brightness(0.96);
                transform: scale(1.05);
            }

            .panel-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(8,8,8,0.72) 0%, rgba(8,8,8,0.22) 45%, transparent 100%);
                z-index: 1;
                pointer-events: none;
                transition: opacity 0.5s;
            }
            
            .espacio-panel:hover .panel-overlay {
                background: linear-gradient(to top, rgba(8,8,8,0.78) 0%, rgba(8,8,8,0.25) 45%, transparent 100%);
            }

            .panel-content {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 2rem;
                z-index: 2;
                color: #fff;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                height: 100%;
            }

            /* Container that holds the text. On desktop it stays wide enough so text doesn't wrap weirdly */
            .panel-text-wrapper {
                width: 300px; /* Fixed width prevents text reflow during animation */
                max-width: 100%;
                opacity: 0;
                transform: translateY(30px); /* Slightly lower start for dynamic slide up */
                transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
            }

            @media (max-width: 767px) {
                /* On mobile, text is always visible */
                .panel-text-wrapper {
                    opacity: 1;
                    transform: translateY(0);
                    width: 100%;
                }
            }

            .espacio-panel:hover .panel-text-wrapper {
                opacity: 1;
                transform: translateY(0);
                transition-delay: 0.15s; /* Wait slightly for expansion to begin */
            }

            /* Vertical spine text when collapsed on desktop */
            .panel-spine {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                writing-mode: vertical-rl;
                text-orientation: mixed;
                font-family: var(--font-heading);
                font-size: clamp(1.5rem, 2.5vw, 2.5rem);
                letter-spacing: 0.1em;
                color: #fff;
                text-transform: uppercase;
                white-space: nowrap;
                opacity: 1;
                transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
                z-index: 3;
                pointer-events: none;
                text-shadow: 0 4px 15px rgba(0,0,0,0.8);
            }

            @media (max-width: 767px) {
                .panel-spine { display: none; }
            }

            .espacio-panel:hover .panel-spine {
                opacity: 0;
                transition: opacity 0.15s ease-out; /* Simplemente desaparece rápido sin moverse */
                transform: translate(-50%, -50%); /* Mantiene su posición original */
            }

            .capacity-badge {
                display: inline-block;
                padding: 6px 14px;
                border-radius: 4px;
                background: rgba(212, 175, 55, 0.2); /* Accent gold subtle background */
                backdrop-filter: blur(8px);
                border: 1px solid rgba(212, 175, 55, 0.5);
                font-size: 0.75rem;
                font-weight: 700;
                letter-spacing: 0.15em;
                margin-bottom: 1rem;
                text-transform: uppercase;
                color: var(--color-accent);
            }

            .panel-title {
                margin: 0; 
                font-size: clamp(1.8rem, 3vw, 2.5rem); 
                font-family: var(--font-heading);
                text-transform: uppercase;
                letter-spacing: 0.05em;
                text-shadow: 0 4px 15px rgba(0,0,0,1);
                line-height: 1.1;
                white-space: normal;
            }
            
            .btn-view {
                display: inline-block;
                margin-top: 1.5rem;
                padding: 0.5rem 0;
                color: #fff;
                font-family: var(--font-body);
                text-transform: uppercase;
                letter-spacing: 0.1em;
                font-size: 0.85rem;
                border-bottom: 1px solid var(--color-accent);
            }
        </style>

        <div class="espacios-container">
            <?php
            $args = array(
                'post_type'      => 'espacios',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC'
            );
            $espacios_query = new WP_Query($args);
            $posts = $espacios_query->posts;

            if (!empty($posts)) :
                $chunks = array_chunk($posts, 4);
                
                foreach ($chunks as $chunk) :
            ?>
                <div class="espacios-accordion">
                    <?php 
                    foreach ($chunk as $post) : 
                        setup_postdata($post);
                        $cap_meta = get_post_meta(get_the_ID(), '_espacio_capacidad', true);
                        $clean_cap = !empty($cap_meta) ? trim(str_ireplace(array('personas', 'px', 'hasta'), '', $cap_meta)) : '';
                        if (empty($clean_cap)) {
                            $t = strtolower(get_the_title());
                            if (strpos($t, 'jardín') !== false || strpos($t, 'jardin') !== false) $clean_cap = '1,500';
                            elseif (strpos($t, 'principal') !== false) $clean_cap = '800';
                            elseif (strpos($t, 'pavorreales') !== false) $clean_cap = '90';
                            elseif (strpos($t, 'mezquite') !== false) $clean_cap = '150';
                            else $clean_cap = '300';
                        }
                    ?>
                        <div class="espacio-panel reveal-text" onclick="window.location.href='<?php the_permalink(); ?>'">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="img-cover" loading="lazy" />
                            <?php else : ?>
                                <div class="img-cover" style="background-color: #222;"></div>
                            <?php endif; ?>
                            
                            <div class="panel-overlay"></div>

                            <!-- Spine text (visible only when collapsed on desktop) -->
                            <div class="panel-spine">
                                <?php the_title(); ?>
                            </div>

                            <!-- Expanded Content -->
                            <div class="panel-content">
                                <div class="panel-text-wrapper">
                                    <span class="capacity-badge">
                                        Hasta <?php echo esc_html($clean_cap); ?> personas
                                    </span>
                                    <h3 class="panel-title">
                                        <?php the_title(); ?>
                                    </h3>
                                    <span class="btn-view">Ver Detalles &rarr;</span>
                                </div>
                            </div>
                        </div>
                    <?php 
                    endforeach; 
                    ?>
                </div>
            <?php 
                endforeach;
                wp_reset_postdata();
            else : ?>
                <p style="text-align: center; width: 100%; color: var(--color-text-secondary);">Próximamente nuevos espacios.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* CSS para emular los estados hover del group-hover de Tailwind/React */
.luxury-card:hover .hover-arrow {
    transform: none !important;
    opacity: 1 !important;
}
.luxury-card:hover .img-cover {
    transform: scale(1.05);
}
</style>

<?php get_footer(); ?>
