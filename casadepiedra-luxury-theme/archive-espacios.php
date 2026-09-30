<?php
/**
 * Template Name: Archivo de Espacios
 * Description: Plantilla para mostrar el grid de espacios.
 */
get_header(); 

$portada_url = function_exists('casa_opt_media') ? casa_opt_media('casa_opt_espacios_portada') : '';
?>

<section style="position: relative; z-index: 2; width: 100%; height: clamp(400px, 48vh, 550px); display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 2.5rem; background: #080808;">
    <img src="<?php echo esc_url($portada_url); ?>" alt="Espacios Portada" fetchpriority="high" decoding="async" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; filter: brightness(0.68);" class="gs-zoom-in" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.2) 0%, rgba(8,8,8,0.55) 75%, #080808 100%); z-index: 2; pointer-events: none;"></div>
    
    <div style="position: relative; z-index: 3; text-align: center; padding: 40px clamp(1rem, 4vw, 3rem) 0; max-width: 900px; margin: 0 auto;">
        <span class="text-script" style="color: var(--color-accent); font-size: 1.4rem; display: block; margin-bottom: 0.2rem;"><?php echo esc_html(get_option('casa_opt_global_espacios_subtitle', 'Exclusividad')); ?></span>
        <h1 style="color: #fff; font-size: clamp(2.3rem, 4.5vw, 3.6rem); margin: 0 0 0.6rem 0; font-family: var(--font-heading); line-height: 1.1; text-shadow: 0 10px 30px rgba(0,0,0,0.85);"><?php echo esc_html(get_option('casa_opt_global_espacios_title', 'Nuestros Espacios')); ?></h1>
        
        <!-- Ornament -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.8rem; margin: 0.8rem 0;">
            <span style="height: 1px; width: 50px; background: linear-gradient(to right, transparent, rgba(193,98,30,0.7)); display: inline-block;"></span>
            <span style="color: var(--color-accent); font-size: 0.85rem;">✦</span>
            <span style="height: 1px; width: 50px; background: linear-gradient(to left, transparent, rgba(193,98,30,0.7)); display: inline-block;"></span>
        </div>

        <p style="color: #eaeaea; font-size: clamp(1.1rem, 2vw, 1.4rem); font-family: var(--font-heading); font-style: italic; margin: 0.5rem auto 0.8rem; line-height: 1.4; text-shadow: 0 4px 15px rgba(0,0,0,0.85);">
            &ldquo;<?php echo esc_html(ucfirst(get_option('casa_opt_global_espacios_desc', 'Escenarios para grandes historias'))); ?>&rdquo;
        </p>

        <span style="color: var(--color-accent); font-size: 0.74rem; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 600; text-shadow: 0 2px 10px rgba(0,0,0,0.9); display: block; margin-top: 0.4rem;">
            Ex Hacienda Casa de Piedra &bull; Exclusividad &amp; Elegancia
        </span>
    </div>
</section>

<div style="padding-bottom: 5rem; background: transparent; width: 100%;">
    <div style="width: 100%; max-width: 100%; margin: 0 auto; padding: 0 clamp(0.75rem, 1.5vw, 2.2rem);">

        <style>
            .espacios-accordion {
                display: flex;
                flex-direction: column;
                gap: 1.2rem;
                width: 100%;
                height: auto;
                min-height: 0;
                padding-bottom: 2rem;
            }
            
            @media (min-width: 1024px) {
                .espacios-accordion {
                    flex-direction: row;
                    height: clamp(480px, 66vh, 840px);
                    gap: 0.85rem;
                    padding-bottom: 0;
                }
            }

            .espacio-panel {
                position: relative;
                flex: 1;
                border-radius: 20px;
                overflow: hidden;
                cursor: pointer;
                transition: box-shadow 0.7s ease, border-color 0.7s ease;
                background: linear-gradient(135deg, #1c1c1c 0%, #0c0c0c 100%);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-top: 1px solid rgba(255, 255, 255, 0.18);
                box-shadow: 0 15px 35px rgba(0,0,0,0.7);
                min-height: 260px;
                transform: translateZ(0);
                backface-visibility: hidden;
                will-change: flex-grow, flex-basis;
            }
            
            @media (min-width: 1024px) {
                .espacio-panel:hover,
                .espacio-panel.is-active {
                    border-color: rgba(193, 98, 30, 0.5);
                    box-shadow: 0 25px 60px rgba(0,0,0,0.9), 0 0 40px rgba(193, 98, 30, 0.22);
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
                transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.4s ease, filter 0.4s ease;
                transform: translateZ(0);
                will-change: transform, opacity, filter;
                backface-visibility: hidden;
            }

            .espacio-panel:hover .img-cover,
            .espacio-panel.is-active .img-cover {
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
            
            .espacio-panel:hover .panel-overlay,
            .espacio-panel.is-active .panel-overlay {
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

            .panel-text-wrapper {
                width: 300px;
                max-width: 100%;
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
            }

            @media (max-width: 1023px) {
                .panel-text-wrapper {
                    opacity: 1;
                    transform: translateY(0);
                    width: 100%;
                }
            }

            .espacio-panel:hover .panel-text-wrapper,
            .espacio-panel.is-active .panel-text-wrapper {
                opacity: 1;
                transform: translateY(0);
                transition-delay: 0.15s;
            }

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

            @media (max-width: 1023px) {
                .panel-spine { display: none; }
            }

            .espacio-panel:hover .panel-spine,
            .espacio-panel.is-active .panel-spine {
                opacity: 0;
                transition: opacity 0.15s ease-out;
                transform: translate(-50%, -50%);
            }

            .capacity-badge {
                display: inline-block;
                margin-bottom: 1rem;
                padding: 0.28rem 0.78rem;
                background: rgba(12, 12, 12, 0.38);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 999px;
                font-size: 0.78rem;
                font-weight: 600;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: #f0c48a;
                text-shadow: 0 1px 8px rgba(0, 0, 0, 0.55);
                backdrop-filter: blur(8px);
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
            $espacios_query = new WP_Query(array(
                'post_type'      => 'espacios',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order title',
                'order'          => 'ASC',
                'post_status'    => 'publish',
            ));

            if ($espacios_query->have_posts()) :
            ?>
                <div class="espacios-accordion">
                    <?php 
                    while ($espacios_query->have_posts()) : $espacios_query->the_post();
                        $clean_cap = function_exists('casa_espacio_capacidad') ? casa_espacio_capacidad(get_the_ID()) : trim((string) get_post_meta(get_the_ID(), '_espacio_capacidad', true));
                        $card_img = casadepiedra_resolve_espacio_card_img(get_the_ID());
                    ?>
                        <div class="espacio-panel reveal-text" data-url="<?php echo esc_url(get_permalink()); ?>">
                            <?php if (!empty($card_img)) : ?>
                                <img src="<?php echo esc_url($card_img); ?>" alt="<?php the_title_attribute(); ?>" class="img-cover" loading="lazy" />
                            <?php else : ?>
                                <div class="img-cover" style="background-color: #222;"></div>
                            <?php endif; ?>
                            
                            <div class="panel-overlay"></div>

                            <div class="panel-spine">
                                <?php the_title(); ?>
                            </div>

                            <div class="panel-content">
                                <div class="panel-text-wrapper">
                                    <?php if ($clean_cap) : ?>
                                    <span class="capacity-badge">
                                        Hasta <?php echo esc_html($clean_cap); ?> personas
                                    </span>
                                    <?php endif; ?>
                                    <h3 class="panel-title">
                                        <?php the_title(); ?>
                                    </h3>
                                    <span class="btn-view">Ver Detalles &rarr;</span>
                                </div>
                            </div>
                        </div>
                    <?php 
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php else : ?>
                <p style="text-align: center; width: 100%; color: var(--color-text-secondary);">Próximamente nuevos espacios.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const panels = document.querySelectorAll('.espacio-panel');
    const accordionBp = 1024;

    const resetFlex = () => {
        if (window.innerWidth < accordionBp) {
            panels.forEach(panel => { panel.style.flexGrow = ''; });
        }
    };

    panels.forEach(panel => {
        panel.addEventListener('mouseenter', () => {
            if (window.innerWidth >= accordionBp && typeof anime !== 'undefined') {
                anime({
                    targets: panel,
                    flexGrow: 3.4,
                    duration: 700,
                    easing: 'easeOutCubic'
                });
            }
        });
        panel.addEventListener('mouseleave', () => {
            if (window.innerWidth >= accordionBp && typeof anime !== 'undefined') {
                anime({
                    targets: panel,
                    flexGrow: 1,
                    duration: 700,
                    easing: 'easeOutCubic'
                });
            }
        });
        panel.addEventListener('click', (e) => {
            if (e.target.closest('a')) return;
            const url = panel.getAttribute('data-url');
            if (url) window.location.href = url;
        });
        panel.addEventListener('touchstart', () => {
            if (!panel.classList.contains('is-active')) {
                panels.forEach(p => p.classList.remove('is-active'));
                panel.classList.add('is-active');
            }
        }, {passive: true});
    });

    window.addEventListener('resize', resetFlex);
    resetFlex();
});
</script>

<style>
.luxury-card:hover .hover-arrow {
    transform: none !important;
    opacity: 1 !important;
}
.luxury-card:hover .img-cover {
    transform: scale(1.05);
}
</style>

<?php get_footer(); ?>
