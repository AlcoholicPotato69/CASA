<?php
/**
 * Template Name: Archivo de Restaurantes (Luxury Accordion 7-Showcase)
 * Description: Plantilla que muestra los 7 restaurantes en un acordeón horizontal responsivo (estilo Espacios), donde cada tarjeta se expande e ilumina al pasar el puntero para revelar su información.
 */
get_header(); 

// Asegurar que existan los 7 restaurantes en la BD
if (function_exists('casadepiedra_populate_data')) {
    casadepiedra_populate_data();
}

$portada_url = get_option('casa_opt_restaurantes_portada') ?: get_template_directory_uri() . '/assets/images/terraza_mezquite_1779523084857.png';
?>

<section style="position: relative; z-index: 2; width: 100%; height: clamp(400px, 48vh, 550px); display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 2.5rem; background: #080808;">
    <img src="<?php echo esc_url($portada_url); ?>" alt="Restaurantes Portada" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; filter: brightness(0.68);" class="gs-zoom-in" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.2) 0%, rgba(8,8,8,0.55) 75%, #080808 100%); z-index: 2; pointer-events: none;"></div>
    
    <div style="position: relative; z-index: 3; text-align: center; padding: 40px clamp(1rem, 4vw, 3rem) 0; max-width: 900px; margin: 0 auto;">
        <span class="text-script" style="color: var(--color-accent); font-size: 1.4rem; display: block; margin-bottom: 0.2rem;"><?php echo esc_html(get_option('casa_opt_global_restaurantes_subtitle', 'Alta cocina')); ?></span>
        <h1 style="color: #fff; font-size: clamp(2.3rem, 4.5vw, 3.6rem); margin: 0 0 0.6rem 0; font-family: var(--font-heading); line-height: 1.1; text-shadow: 0 10px 30px rgba(0,0,0,0.85);"><?php echo esc_html(get_option('casa_opt_global_restaurantes_title', 'El epítome gastronómico del Bajío')); ?></h1>
        
        <!-- Ornament -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.8rem; margin: 0.8rem 0;">
            <span style="height: 1px; width: 50px; background: linear-gradient(to right, transparent, rgba(212,175,55,0.7)); display: inline-block;"></span>
            <span style="color: var(--color-accent); font-size: 0.85rem;">✦</span>
            <span style="height: 1px; width: 50px; background: linear-gradient(to left, transparent, rgba(212,175,55,0.7)); display: inline-block;"></span>
        </div>

        <p style="color: #eaeaea; font-size: clamp(1.1rem, 2vw, 1.4rem); font-family: var(--font-heading); font-style: italic; margin: 0.5rem auto 0.8rem; line-height: 1.4; text-shadow: 0 4px 15px rgba(0,0,0,0.85);">
            &ldquo;<?php echo esc_html(ucfirst(get_option('casa_opt_global_restaurantes_desc', 'La cúspide de la gastronomía en el Bajío. Una experiencia inigualable que reúne la oferta gastronómica más exclusiva de la región.'))); ?>&rdquo;
        </p>

        <span style="color: var(--color-accent); font-size: 0.74rem; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 600; text-shadow: 0 2px 10px rgba(0,0,0,0.9); display: block; margin-top: 0.4rem;">
            Ex Hacienda Casa de Piedra &bull; Alta Cocina &amp; Tradición
        </span>
    </div>
</section>

<div style="padding-bottom: 5rem; background: transparent; width: 100%;">
    <div style="width: 100%; max-width: 100%; margin: 0 auto; padding: 0 clamp(0.75rem, 1.5vw, 2.2rem);">

        <style>
            /* ACORDEÓN DE 7 RESTAURANTES A TODO EL ANCHO (ESTILO ESPACIOS) */
            .rest-accordion {
                display: flex;
                flex-direction: column;
                gap: 1.2rem;
                width: 100%;
            }

            @media (min-width: 1024px) {
                .rest-accordion {
                    flex-direction: row;
                    height: clamp(480px, 66vh, 840px);
                    gap: 0.85rem;
                }
            }

            .rest-panel {
                position: relative;
                flex: 1;
                border-radius: 1.4rem;
                overflow: hidden;
                cursor: pointer;
                transition: flex 0.7s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.5s ease, border-color 0.5s ease, opacity 0.5s ease;
                background: linear-gradient(135deg, #181818 0%, #080808 100%);
                border: 1px solid rgba(255, 255, 255, 0.12);
                min-height: 260px;
                display: block;
                text-decoration: none;
                opacity: 0.88;
            }

            @media (min-width: 1024px) {
                .rest-panel:hover,
                .rest-panel.is-active {
                    flex: 3.4;
                    border-color: var(--color-accent);
                    box-shadow: 0 25px 60px rgba(0,0,0,0.9), 0 0 40px rgba(212, 175, 55, 0.35);
                    z-index: 10;
                    opacity: 1;
                }
            }

            @media (max-width: 1023px) {
                .rest-panel:hover,
                .rest-panel.is-active {
                    border-color: var(--color-accent);
                    opacity: 1;
                    box-shadow: 0 15px 40px rgba(212, 175, 55, 0.3);
                }
            }

            /* Atenuación sutil de tarjetas inactivas al pasar el cursor */
            @media (hover: hover) and (pointer: fine) and (min-width: 1024px) {
                .rest-accordion:hover .rest-panel:not(:hover) {
                    opacity: 0.58;
                }
            }

            /* IMAGEN DE FONDO: LIGERA OPACIDAD EN REPOSO -> BRILLO PLENO Y VÍVIDO EN HOVER */
            .rest-panel .img-cover {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                opacity: 0.85;
                filter: brightness(0.75);
                transition: all 0.7s cubic-bezier(0.25, 1, 0.5, 1);
            }

            .rest-panel:hover .img-cover,
            .rest-panel.is-active .img-cover {
                opacity: 1;
                filter: brightness(0.92);
                transform: scale(1.06);
            }

            /* GRADIENTE DE VIÑETA SUAVE Y LIGERO */
            .rest-panel-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(8,8,8,0.78) 0%, rgba(8,8,8,0.25) 45%, transparent 100%);
                z-index: 1;
                pointer-events: none;
                transition: opacity 0.5s;
            }

            /* LOGO EMBLEMA CENTRAL EN ESTADO COLAPSADO / REPOSO */
            .rest-center-logo {
                position: absolute;
                inset: 0;
                z-index: 2;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 1.2rem;
                text-align: center;
                transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
            }

            .rest-panel:hover .rest-center-logo,
            .rest-panel.is-active .rest-center-logo {
                opacity: 0;
                transform: translateY(-30px);
                pointer-events: none;
            }

            .rest-logo-badge {
                width: 72px;
                height: 72px;
                border-radius: 50%;
                background: rgba(10, 10, 10, 0.88);
                border: 1.5px solid var(--color-accent);
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 0.8rem;
                box-shadow: 0 10px 25px rgba(0,0,0,0.85);
            }

            .rest-logo-initials {
                font-family: var(--font-heading);
                font-size: 1.35rem;
                color: var(--color-accent);
                font-weight: 700;
                letter-spacing: 2px;
            }

            /* CONTENIDO EXPANDIDO QUE DESPLAZA HACIA ARRIBA (ESTILO ESPACIOS) */
            .rest-panel-content {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 2.2rem 1.8rem;
                z-index: 3;
                color: #fff;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                height: 100%;
                pointer-events: none;
            }

            .rest-text-wrapper {
                width: 360px;
                max-width: 100%;
                opacity: 0;
                transform: translateY(35px);
                transition: all 0.6s cubic-bezier(0.25, 1, 0.5, 1);
            }

            @media screen and (max-width: 1024px) {
                .rest-text-wrapper {
                    opacity: 1;
                    transform: translateY(0);
                    width: 100%;
                }
                .rest-center-logo {
                    display: none;
                }
                .rest-card-desc {
                    display: none !important;
                    opacity: 0 !important;
                    height: 0 !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    overflow: hidden !important;
                }
            }

            .rest-panel:hover .rest-text-wrapper,
            .rest-panel.is-active .rest-text-wrapper {
                opacity: 1;
                transform: translateY(0);
                transition-delay: 0.12s;
                pointer-events: auto;
            }
        </style>

        <div class="rest-accordion">
            <?php
            $args = array(
                'post_type'      => 'restaurantes',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order title',
                'order'          => 'ASC'
            );
            $restaurantes_query = new WP_Query($args);

            $default_rest_images = array(
                get_template_directory_uri() . '/assets/images/salon_principal_1779523069698.png',
                get_template_directory_uri() . '/assets/images/terraza_mezquite_1779523084857.png',
                get_template_directory_uri() . '/assets/images/jardin_principal_1779523113451.png',
                get_template_directory_uri() . '/assets/images/salon_pavorreales_1779523097528.png',
                get_template_directory_uri() . '/assets/images/salon_principal_1779523069698.png',
                get_template_directory_uri() . '/assets/images/terraza_mezquite_1779523084857.png',
                get_template_directory_uri() . '/assets/images/jardin_principal_1779523113451.png'
            );

            $idx = 0;
            if ($restaurantes_query->have_posts()) :
                while ($restaurantes_query->have_posts()) : $restaurantes_query->the_post(); 
                    $cocina = get_post_meta(get_the_ID(), '_restaurante_cocina', true) ?: get_option('casa_opt_global_restaurantes_subtitle', 'Alta cocina');
                    $logo_url = casadepiedra_resolve_restaurante_logo(get_the_ID());
                    $menu_url = get_post_meta(get_the_ID(), '_restaurante_menu', true);
                    $reserva_url = casadepiedra_get_reserva_url(get_the_ID());
                    $resolved_img = casadepiedra_resolve_restaurante_card_img(get_the_ID());
                    $bg_img = !empty($resolved_img) ? $resolved_img : $default_rest_images[$idx % count($default_rest_images)];

                    // Iniciales oficiales como respaldo
                    $words = explode(' ', get_the_title());
                    $initials = '';
                    foreach ($words as $w) {
                        if (strlen($w) > 2 || count($words) === 1) {
                            $initials .= strtoupper(substr($w, 0, 1));
                        }
                        if (strlen($initials) >= 2) break;
                    }
                ?>
                    <div class="rest-panel" data-url="<?php the_permalink(); ?>" style="cursor: pointer;">
                        <!-- IMAGEN DE FONDO -->
                        <img src="<?php echo esc_url($bg_img); ?>" alt="<?php the_title_attribute(); ?>" class="img-cover" />
                        <div class="rest-panel-overlay"></div>

                        <!-- ESTADO EN REPOSO: PURO LOGO OFICIAL SIN MARCO NI CUADRO -->
                        <!-- ESTADO EN REPOSO: PURO LOGO OFICIAL SIN MARCO NI CUADRO A PROPORCIÓN CORRECTA -->
                        <div class="rest-center-logo">
                            <?php if (!empty($logo_url)) : ?>
                                <div style="display: flex; align-items: center; justify-content: center; width: 86%; max-width: 160px; height: 75px; margin: 0 auto;">
                                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php the_title_attribute(); ?> Logo" style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; object-position: center; filter: drop-shadow(0 4px 18px rgba(0,0,0,0.95));" />
                                </div>
                            <?php else : ?>
                                <h3 style="color: #fff; font-size: 1.25rem; font-family: var(--font-heading); margin: 0; line-height: 1.3; text-shadow: 0 4px 15px rgba(0,0,0,0.95);">
                                    <?php the_title(); ?>
                                </h3>
                            <?php endif; ?>
                        </div>

                        <!-- ESTADO EXPANDIDO (HOVER): DESPLAZA LA INFORMACIÓN Y DETALLES -->
                        <div class="rest-panel-content">
                            <div class="rest-text-wrapper">
                                <?php if (!empty($logo_url)) : ?>
                                    <div style="margin-bottom: 0.8rem; display: flex; align-items: center; height: 58px;">
                                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php the_title_attribute(); ?> Logo" style="max-width: 180px; max-height: 54px; width: auto; height: auto; object-fit: contain; object-position: left center; filter: drop-shadow(0 4px 15px rgba(0,0,0,0.95));" />
                                    </div>
                                <?php endif; ?>
                                <span style="display: inline-block; padding: 0.3rem 0.85rem; background: rgba(212,175,55,0.2); border: 1px solid var(--color-accent); border-radius: 999px; color: var(--color-accent); font-size: 0.72rem; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600; margin-bottom: 0.9rem; box-shadow: 0 0 15px rgba(212,175,55,0.25);">
                                    <?php echo esc_html($cocina); ?>
                                </span>
                                <h4 style="color: #fff; font-size: clamp(1.4rem, 2.2vw, 1.85rem); font-family: var(--font-heading); margin: 0 0 0.8rem 0; line-height: 1.2;">
                                    <?php the_title(); ?>
                                </h4>
                                <?php if (!wp_is_mobile()) : ?>
                                <p class="rest-card-desc" style="color: #ddd; font-size: 0.9rem; line-height: 1.6; margin: 0 0 1.4rem 0;">
                                    <?php echo wp_strip_all_tags(get_the_content()); ?>
                                </p>
                                <?php endif; ?>
                                
                                <div style="display: flex; flex-wrap: wrap; gap: 0.6rem; align-items: center;">
                                    <?php if (!empty($menu_url)) : ?>
                                        <a href="<?php echo esc_url($menu_url); ?>" target="_blank" rel="noopener" style="padding: 0.6rem 1.1rem; background: linear-gradient(135deg, #f3e5ab 0%, #d4af37 100%); color: #080808; border-radius: 999px; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; text-decoration: none; letter-spacing: 0.5px;">
                                            Ver Menú
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo esc_url($reserva_url); ?>" target="_blank" rel="noopener" style="padding: 0.6rem 1.1rem; background: transparent; border: 1.2px solid var(--color-accent); color: #fff; border-radius: 999px; font-weight: 600; font-size: 0.78rem; text-transform: uppercase; text-decoration: none; letter-spacing: 0.5px;">
                                        Reservar
                                    </a>

                                    <a href="<?php the_permalink(); ?>" style="padding: 0.6rem 1.1rem; color: var(--color-accent); font-size: 0.8rem; font-weight: 600; text-decoration: underline;">
                                        Detalles →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    $idx++;
                endwhile;
                wp_reset_postdata();
            endif; ?>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const panels = document.querySelectorAll('.rest-panel');
            panels.forEach(panel => {
                panel.addEventListener('click', (e) => {
                    if (e.target.closest('a')) return;
                    const url = panel.getAttribute('data-url');
                    if (url) window.location.href = url;
                });
                panel.addEventListener('touchstart', (e) => {
                    if (!panel.classList.contains('is-active')) {
                        panels.forEach(p => p.classList.remove('is-active'));
                        panel.classList.add('is-active');
                    }
                }, {passive: true});
            });
        });
        </script>

    </div>
</div>

<?php get_footer(); ?>
