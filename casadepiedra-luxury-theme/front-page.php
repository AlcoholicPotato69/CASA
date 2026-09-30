<?php
/**
 * Template Name: Front Page
 * Description: Plantilla para la página de inicio.
 */
get_header(); 

// Obtener datos del hero centralizados del Panel Casa
$hero_subtitle = get_option('casa_opt_home_hero_subtitle', 'Desde 1845');
$hero_title = get_option('casa_opt_home_hero_title', 'El recinto más exclusivo <br /> de León.');
$hero_desc = get_option('casa_opt_home_hero_desc', 'El símbolo de prestigio en el Bajío, ha sido un escenario de momentos extraordinarios. En la zona dorada de León, nuestros espacios han sido testigos de grandes celebraciones. Aquí, tu historia es parte de nuestra historia.');
$hero_image = function_exists('casa_opt_media') ? casa_opt_media('casa_opt_home_hero_img') : '';
?>

<section id="inicio" style="position: relative; z-index: 2; min-height: 100vh; min-height: 100dvh; display: flex; align-items: center; overflow: hidden; padding-top: 5rem; background: #080808;">
    <div style="position: absolute; inset: 0; z-index: 1;">
        <?php if ($hero_image) : ?>
        <img src="<?php echo esc_url($hero_image); ?>" alt="Casa de Piedra" class="img-cover gs-zoom-in" fetchpriority="high" decoding="async" style="width: 100%; height: 100%; object-fit: cover; object-position: center; transform: scale(1.1); transition: transform 2.5s cubic-bezier(0.32, 0.72, 0, 1);" />
        <?php endif; ?>
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(5,5,5,0.3) 0%, rgba(5,5,5,0.6) 60%, #080808 100%);"></div>
    </div>

    <div class="container" style="position: relative; z-index: 10;">
        <div style="max-width: 900px;">
            <div style="overflow: hidden; padding-top: 20px; margin-top: -20px;">
                <span class="text-script reveal-text" style="display: block; margin-bottom: 1rem; padding-top: 10px;">
                    <?php echo esc_html($hero_subtitle); ?>
                </span>
            </div>
            
            <div style="overflow: hidden;">
                <h1 class="text-hero reveal-text" style="margin-bottom: 1.5rem; text-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    <?php echo wp_kses_post($hero_title); ?>
                </h1>
            </div>

            <div style="overflow: hidden;">
                <p class="text-body-lg reveal-text" style="color: rgba(255,255,255,0.8); margin-bottom: clamp(1.5rem, 5vh, 3.5rem); max-width: 600px;">
                    <?php echo esc_html($hero_desc); ?>
                </p>
            </div>

            <!-- Contenedor de Botones Hero - 100% visible sin cortes ni desfases -->
            <div style="padding: 1rem 0 3.5rem; overflow: visible;">
                <div class="reveal-text hero-actions-bar" style="display: flex; flex-wrap: wrap; gap: clamp(1.5rem, 3vw, 2.8rem); align-items: center; justify-content: flex-start;">
                    <?php if (get_option('casa_opt_status_espacios', '1') === '1') : ?>
                    <a href="<?php echo esc_url(home_url('/espacios')); ?>" class="hero-cta-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; background: var(--color-accent); color: #000; padding: clamp(1.1rem, 1.4vw, 1.35rem) clamp(2.4rem, 3.5vw, 3.5rem); border-radius: 9999px; font-weight: 600; font-size: clamp(1.05rem, 1.25vw, 1.22rem); font-family: var(--font-body); letter-spacing: 0.04em; box-shadow: 0 12px 35px rgba(193, 98, 30, 0.38); transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);" onmouseover="this.style.transform='scale(1.05) translateY(-3px)'; this.style.boxShadow='0 18px 45px rgba(193, 98, 30, 0.55)';" onmouseout="this.style.transform='scale(1) translateY(0)'; this.style.boxShadow='0 12px 35px rgba(193, 98, 30, 0.38)';">
                        Ver Espacios
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
</section>

<!-- Fusión Quiénes Somos + Recorrido 3D en Escritorio (Resumen rápido e inmersivo en Inicio) -->
<?php if (get_option('casa_opt_status_nosotros', '1') === '1') : ?>
<style>
.desktop-home-nosotros {
    display: none;
    padding: 6rem 0 7rem;
    background: linear-gradient(180deg, #050505 0%, #0a0a0a 50%, #050505 100%);
    position: relative;
    border-top: 1px solid rgba(193,98,30,0.2);
    border-bottom: 1px solid rgba(193,98,30,0.2);
}
@media (min-width: 1024px) {
    .desktop-home-nosotros {
        display: block;
    }
}
</style>
<section class="desktop-home-nosotros">
    <div class="container">
        <!-- Resumen de Quiénes Somos integrado en Inicio -->
        <div style="display: grid; grid-template-columns: 1.25fr 1fr; gap: 4rem; align-items: center; margin-bottom: 4.5rem;">
            <div>
                <span class="text-script" style="font-size: clamp(2.2rem, 3.5vw, 3rem); color: var(--color-accent); display: block; margin-bottom: 0.5rem;">
                    Nuestra Historia
                </span>
                <h2 class="text-h2" style="font-size: clamp(2.2rem, 3.5vw, 3.2rem); color: #fff; margin-top: 0; margin-bottom: 1.5rem; line-height: 1.2;">
                    Quiénes Somos
                </h2>
                <div class="text-body-lg" style="color: rgba(255,255,255,0.85); line-height: 1.85; font-size: 1.1rem; margin-bottom: 2.2rem;">
                    <?php 
                    $subtitle = get_option('casa_opt_nosotros_subtitle', 'Donde el pasado y el presente se encuentran.');
                    echo '<p style="margin-bottom: 1.2rem; font-weight: 600; color: #fff; font-size: 1.2rem;">' . esc_html($subtitle) . '</p>';
                    ?>
                    <p>
                        Es un rincón mágico donde el tiempo parece detenerse para celebrar la vida. Nacida en 1845 como una imponente ex-hacienda, sus muros de cantera y sus arcos coloniales han sido testigos silenciosos de historias de amor y celebraciones memorables durante casi dos siglos.
                </div>
            </div>

            <!-- Tarjeta Visual de Resumen -->
            <div style="position: relative; border-radius: 20px; overflow: hidden; border: 1px solid rgba(193,98,30,0.4); box-shadow: 0 20px 50px rgba(0,0,0,0.85);">
                <?php 
                $home_nosotros_img = function_exists('casa_opt_media') ? casa_opt_media('casa_opt_nosotros_hero_img') : '';
                ?>
                <?php if ($home_nosotros_img) : ?>
                <img src="<?php echo esc_url($home_nosotros_img); ?>" alt="Arquitectura y Tradición" loading="lazy" decoding="async" style="width: 100%; height: 420px; object-fit: cover; display: block;" />
                <?php else : ?>
                <div class="casa-media-ph" style="height:420px;"></div>
                <?php endif; ?>
                <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.88) 0%, transparent 60%);"></div>
                <div style="position: absolute; bottom: 2.2rem; left: 2.2rem; right: 2.2rem;">
                    <span style="color: var(--color-accent); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.25em; display: block; margin-bottom: 0.4rem;">Ex-Hacienda 1845</span>
                    <h3 style="color: #fff; font-family: var(--font-heading); font-size: 1.6rem; margin: 0; line-height: 1.3;">Arquitectura Colonial & Lujo Contemporáneo</h3>
                </div>
            </div>
        </div>

        <!-- Visor Interactivo del Recorrido Virtual 3D en Inicio -->
        <?php 
        $virtual_tour = get_option('casa_opt_nosotros_virtual_tour');
        if (empty(trim($virtual_tour))) {
            $virtual_tour = 'https://publicmss.s3.amazonaws.com/Adivor/CasaDePiedra/V3/index.htm';
        }
        ?>
        <div style="border-radius: 24px; overflow: hidden; border: 1px solid rgba(193,98,30,0.45); box-shadow: 0 25px 80px rgba(0,0,0,0.95); background: #000;">
            <div style="padding: 1.2rem 2.5rem; background: linear-gradient(90deg, rgba(20,20,20,0.98) 0%, rgba(35,30,15,0.98) 50%, rgba(20,20,20,0.98) 100%); border-bottom: 1px solid rgba(193,98,30,0.3); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <span style="display: inline-block; width: 12px; height: 12px; background: #22c55e; border-radius: 50%; box-shadow: 0 0 10px #22c55e;"></span>
                    <span style="color: #fff; font-family: var(--font-heading); font-size: 1.25rem; letter-spacing: 0.5px;">EXPLORA CASA DE PIEDRA EN 3D — TOUR INTERACTIVO 360°</span>
                </div>
                <span style="color: var(--color-accent); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">
                    ◆ Navegación Libre por Salones y Jardines ◆
                </span>
            </div>
            <div style="height: 75vh; min-height: 620px; width: 100%; position: relative;">
                <iframe 
                    src="<?php echo esc_url($virtual_tour); ?>" 
                    width="100%" 
                    height="100%" 
                    style="border: 0; display: block;" 
                    allow="accelerometer; gyroscope; xr-spatial-tracking; fullscreen"
                    allowfullscreen
                    loading="lazy" 
                ></iframe>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- SECCIÓN DE ESPACIOS DESTACADOS (FRONT PAGE) -->
<?php if (get_option('casa_opt_status_espacios', '1') === '1') : ?>
<style>
@media (max-width: 1023px) {
    .home-espacios-section { display: none !important; }
}
</style>
<section class="home-espacios-section reveal-text" style="padding: 5rem 0; background: #0a0a0a; width: 100%;">
    <div style="width: 100%; max-width: 100%; margin: 0 auto; padding: 0 clamp(0.75rem, 1.5vw, 2.2rem);">
        <div style="text-align: center; margin-bottom: 3.5rem;">
            <span class="text-script" style="color: var(--color-accent); display: block; margin-bottom: 0.5rem; font-size: clamp(2rem, 3vw, 2.8rem);">Nuestros Espacios</span>
            <h2 class="text-hero" style="color: #fff; font-size: clamp(2.2rem, 4vw, 3.2rem); margin: 0;">Escenarios Extraordinarios</h2>
        </div>
        
        <style>
            .espacios-accordion {
                display: flex;
                flex-direction: column;
                gap: 1.2rem;
                width: 100%;
                height: auto;
                min-height: 0;
            }
            @media (min-width: 1024px) {
                .espacios-accordion {
                    flex-direction: row;
                    height: clamp(480px, 66vh, 840px);
                    gap: 0.85rem;
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
            .espacio-panel:hover .panel-text-wrapper {
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
            .espacio-panel:hover .panel-spine {
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

        <div class="espacios-accordion">
            <?php
            $home_espacios = new WP_Query(array(
                'post_type'      => 'espacios',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order title',
                'order'          => 'ASC',
                'post_status'    => 'publish',
            ));
            if ($home_espacios->have_posts()) :
                while ($home_espacios->have_posts()) : $home_espacios->the_post();
                    $title = get_the_title();
                    $clean_cap = function_exists('casa_espacio_capacidad') ? casa_espacio_capacidad(get_the_ID()) : trim((string) get_post_meta(get_the_ID(), '_espacio_capacidad', true));
                    $card_img = casadepiedra_resolve_espacio_card_img(get_the_ID());
                ?>
                    <div class="espacio-panel" data-url="<?php echo esc_url(get_permalink()); ?>">
                        <?php if (!empty($card_img)) : ?>
                            <img src="<?php echo esc_url($card_img); ?>" alt="<?php echo esc_attr($title); ?>" class="img-cover" loading="lazy" />
                        <?php else : ?>
                            <div class="img-cover" style="background-color: #222;"></div>
                        <?php endif; ?>
                        
                        <div class="panel-overlay"></div>

                        <div class="panel-spine">
                            <?php echo esc_html($title); ?>
                        </div>

                        <div class="panel-content">
                            <div class="panel-text-wrapper">
                                <?php if ($clean_cap) : ?>
                                <span class="capacity-badge">Hasta <?php echo esc_html($clean_cap); ?> personas</span>
                                <?php endif; ?>
                                <h3 class="panel-title"><?php echo esc_html($title); ?></h3>
                                <span class="btn-view">Ver Detalles &rarr;</span>
                            </div>
                        </div>
                    </div>
                <?php 
                endwhile;
                wp_reset_postdata();
            endif; 
            ?>
        </div>
        
        <div style="text-align: center; margin-top: 3.5rem;">
            <a href="<?php echo esc_url(home_url('/espacios')); ?>" style="color: var(--color-accent); text-decoration: none; border-bottom: 1px solid var(--color-accent); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 2px;">Descubrir todos los detalles &rarr;</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- SECCIÓN DE RESEÑAS VERIFICADAS DE GOOGLE MAPS -->
<?php 
$google_maps_pin = function_exists('casa_get_google_maps_url') ? casa_get_google_maps_url() : get_option('casa_opt_google_maps_link', '');
$maps_embed = function_exists('casa_get_google_maps_embed_url') ? casa_get_google_maps_embed_url() : '';
$reviews_subtitle = get_option('casa_opt_home_reviews_subtitle', 'Experiencias Inolvidables');
$reviews_title = get_option('casa_opt_home_reviews_title', 'Lo que dicen nuestros visitantes');
$home_reviews = function_exists('casa_get_review_cards') ? casa_get_review_cards('casa_opt_home_rev') : array();
?>
<section id="resenas-google" class="google-reviews-section reveal-text">
    <div class="container">
        <div class="google-reviews-header">
            <div class="google-badge-pill">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
                </svg>
                <span class="google-badge-text">Google Reviews</span>
                <span class="google-badge-stars">★★★★★</span>
                <span style="color: rgba(255,255,255,0.7); font-size: 0.8rem;">Google</span>
            </div>
            <span class="text-script" style="color: var(--color-accent); display: block; margin-bottom: 0.3rem;"><?php echo esc_html($reviews_subtitle); ?></span>
            <h2 class="text-hero" style="color: #fff; font-size: clamp(2rem, 4vw, 3.2rem); margin: 0;"><?php echo esc_html($reviews_title); ?></h2>
        </div>

        <?php if (!empty($home_reviews)) : ?>
        <div class="google-reviews-grid">
            <?php foreach ($home_reviews as $rev) : ?>
            <?php if (!empty($rev['url'])) : ?>
            <a href="<?php echo esc_url($rev['url']); ?>" target="_blank" rel="noopener noreferrer" class="google-review-card" style="text-decoration: none; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s;">
            <?php else : ?>
            <div class="google-review-card" style="display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s;">
            <?php endif; ?>
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                        <div class="google-review-stars" style="margin-bottom: 0;">★★★★★</div>
                        <?php if (!empty($rev['url'])) : ?>
                        <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                        <?php endif; ?>
                    </div>
                    <p class="google-review-text" style="color: rgba(255,255,255,0.85);">
                        &ldquo;<?php echo esc_html($rev['text']); ?>&rdquo;
                    </p>
                </div>
                <div class="google-review-author">
                    <div class="google-review-avatar"><?php echo esc_html($rev['author'] !== '' ? strtoupper(substr($rev['author'], 0, 1)) : 'G'); ?></div>
                    <div class="google-review-info">
                        <h4><?php echo esc_html($rev['author'] !== '' ? $rev['author'] : 'Google'); ?></h4>
                        <span>Reseña en Google</span>
                    </div>
                </div>
            <?php echo !empty($rev['url']) ? '</a>' : '</div>'; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Mapa Interactivo 100% Responsivo en Modo Oscuro Sólido -->
        <div style="margin-top: 3.5rem; border-radius: 24px; overflow: hidden; border: 1px solid rgba(193,98,30,0.35); background: #111111; box-shadow: 0 25px 65px rgba(0,0,0,0.95); height: clamp(360px, 46vw, 520px); width: 100%; position: relative;">
            <iframe 
                title="Casa de Piedra Ubicación en Google Maps"
                src="<?php echo esc_url($maps_embed); ?>"
                width="100%" 
                height="100%" 
                style="border: 0; display: block; width: 100%; height: 100%; filter: invert(100%) hue-rotate(180deg) contrast(112%) saturate(105%);" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
        </div>

        <div class="google-review-cta-wrapper" style="margin-top: 3.8rem; margin-bottom: 1.5rem;">
            <a href="<?php echo esc_url($google_maps_pin); ?>" target="_blank" rel="noopener noreferrer" class="google-maps-btn">
                <svg viewBox="0 0 24 24" width="18" height="18" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>
                </svg>
                Ver todas las reseñas en Google Maps &nearr;
            </a>
        </div>
    </div>
</section>

<?php if (get_option('casa_opt_status_restaurantes', '1') === '1') : ?>
<section class="home-restaurantes-section reveal-text" style="padding: 4.5rem 0 2rem; background: #0a0a0a; width: 100%;">
    <div style="width: 100%; max-width: 100%; margin: 0 auto; padding: 0 clamp(0.75rem, 1.5vw, 2.2rem);">
        <div style="text-align: center; margin-bottom: 2.8rem;">
            <span class="text-script" style="color: var(--color-accent); display: block; margin-bottom: 0.4rem; font-size: clamp(1.8rem, 3vw, 2.6rem);"><?php echo esc_html(get_option('casa_opt_global_restaurantes_subtitle', 'Alta cocina')); ?></span>
            <h2 class="text-hero" style="color: #fff; font-size: clamp(2rem, 4vw, 3rem); margin: 0;"><?php echo esc_html(get_option('casa_opt_global_restaurantes_title', 'La cúspide de la gastronomía en el Bajío.')); ?></h2>
        </div>
        <style>
            .home-rest-accordion { display: flex; flex-direction: column; gap: 1.1rem; width: 100%; }
            @media (min-width: 1024px) {
                .home-rest-accordion { flex-direction: row; height: clamp(420px, 58vh, 720px); gap: 0.75rem; }
            }
            .home-rest-panel {
                position: relative; flex: 1; min-height: 220px; border-radius: 1.3rem; overflow: hidden;
                cursor: pointer; border: 1px solid rgba(255,255,255,0.12); background: #111;
            }
            @media (max-width: 767px) {
                .home-restaurantes-section { padding: 3rem 0 1.2rem !important; }
                .home-rest-panel { min-height: 200px; border-radius: 1rem; }
            }
            .home-rest-panel img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; filter: brightness(0.72); transition: transform 0.5s ease, filter 0.4s ease; }
            .home-rest-panel:hover img { transform: scale(1.05); filter: brightness(0.9); }
            .home-rest-panel .home-rest-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(8,8,8,0.82) 0%, transparent 55%); }
            .home-rest-panel .home-rest-copy { position: absolute; left: 0; right: 0; bottom: 0; padding: 1.3rem 1.4rem; z-index: 2; }
            .home-rest-panel h3 { color: #fff; font-family: var(--font-heading); font-size: clamp(1.25rem, 2vw, 1.7rem); margin: 0 0 0.35rem; }
        </style>
        <div class="home-rest-accordion">
            <?php
            $home_rests = new WP_Query(array(
                'post_type' => 'restaurantes',
                'posts_per_page' => -1,
                'orderby' => 'menu_order title',
                'order' => 'ASC',
                'post_status' => 'publish',
            ));
            if ($home_rests->have_posts()) :
                while ($home_rests->have_posts()) : $home_rests->the_post();
                    $r_img = casadepiedra_resolve_restaurante_card_img(get_the_ID());
                    $r_cocina = get_post_meta(get_the_ID(), '_restaurante_cocina', true);
            ?>
                <div class="home-rest-panel" data-url="<?php echo esc_url(get_permalink()); ?>">
                    <?php if (!empty($r_img)) : ?>
                    <img src="<?php echo esc_url($r_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
                    <?php endif; ?>
                    <div class="home-rest-overlay"></div>
                    <div class="home-rest-copy">
                        <?php if ($r_cocina) : ?>
                            <span class="card-kicker" style="display:inline-block; margin-bottom:0.45rem; font-size:0.72rem;"><?php echo esc_html($r_cocina); ?></span>
                        <?php endif; ?>
                        <h3><?php echo esc_html(casadepiedra_resolve_restaurante_short_name(get_the_title())); ?></h3>
                        <span style="color:var(--color-accent); font-size:0.82rem; font-weight:600;">Conocer restaurante →</span>
                    </div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
        <div style="text-align:center; margin-top: 2.4rem;">
            <a href="<?php echo esc_url(home_url('/restaurantes')); ?>" style="color: var(--color-accent); text-decoration: none; border-bottom: 1px solid var(--color-accent); font-size: 0.88rem; text-transform: uppercase; letter-spacing: 2px;">Ver todos los restaurantes →</a>
        </div>
    </div>
</section>

<section id="resenas-restaurantes" class="google-reviews-section reveal-text" style="padding-top: 2.5rem;">
    <div class="container">
        <div class="google-reviews-header">
            <div class="google-badge-pill">
                <span class="google-badge-text">Google Reviews</span>
                <span class="google-badge-stars">★★★★★</span>
                <span style="color: rgba(255,255,255,0.7); font-size: 0.8rem;">Restaurantes</span>
            </div>
            <span class="text-script" style="color: var(--color-accent); display: block; margin-bottom: 0.3rem;">Alta cocina</span>
            <h2 class="text-hero" style="color: #fff; font-size: clamp(1.8rem, 3.6vw, 2.8rem); margin: 0;">Lo que dicen de nuestros restaurantes</h2>
        </div>
        <?php
        $rest_home_reviews = function_exists('casa_get_review_cards') ? casa_get_review_cards('casa_opt_rest_rev') : array();
        if (!empty($rest_home_reviews)) :
        ?>
        <div class="google-reviews-grid">
            <?php foreach ($rest_home_reviews as $rr) : ?>
            <?php if (!empty($rr['url'])) : ?>
            <a href="<?php echo esc_url($rr['url']); ?>" target="_blank" rel="noopener noreferrer" class="google-review-card" style="text-decoration: none;">
            <?php else : ?>
            <div class="google-review-card">
            <?php endif; ?>
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                        <div class="google-review-stars" style="margin-bottom: 0;">★★★★★</div>
                        <?php if (!empty($rr['url'])) : ?>
                        <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                        <?php endif; ?>
                    </div>
                    <p class="google-review-text">&ldquo;<?php echo esc_html($rr['text']); ?>&rdquo;</p>
                </div>
                <div class="google-review-author">
                    <div class="google-review-avatar"><?php echo esc_html($rr['author'] !== '' ? strtoupper(substr($rr['author'], 0, 1)) : 'G'); ?></div>
                    <div class="google-review-info">
                        <h4><?php echo esc_html($rr['author'] !== '' ? $rr['author'] : 'Google'); ?></h4>
                        <span>Reseña en Google</span>
                    </div>
                </div>
            <?php echo !empty($rr['url']) ? '</a>' : '</div>'; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php
$home_eventos_on = get_option('casa_opt_status_eventos', '0') === '1';
$home_eventos = $home_eventos_on ? new WP_Query(array(
    'post_type' => 'eventos',
    'posts_per_page' => 3,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
)) : null;
if ($home_eventos_on && $home_eventos && $home_eventos->have_posts()) :
?>
<section class="home-eventos-section reveal-text" style="padding: 4rem 0 5rem; background: #080808; width: 100%;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 2.6rem;">
            <span class="text-script" style="color: var(--color-accent); display: block; margin-bottom: 0.4rem; font-size: clamp(1.8rem, 3vw, 2.5rem);"><?php echo esc_html(get_option('casa_opt_global_eventos_subtitle', 'Celebraciones')); ?></span>
            <h2 class="text-hero" style="color: #fff; font-size: clamp(2rem, 4vw, 2.9rem); margin: 0;"><?php echo esc_html(get_option('casa_opt_global_eventos_title', 'Próximos Eventos')); ?></h2>
        </div>
        <div style="display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); gap: 1.4rem;">
            <style>
                @media (min-width: 768px) { .home-eventos-grid { grid-template-columns: repeat(2, minmax(0,1fr)) !important; } }
                @media (min-width: 1100px) { .home-eventos-grid { grid-template-columns: repeat(3, minmax(0,1fr)) !important; } }
            </style>
            <div class="home-eventos-grid" style="display:grid; grid-template-columns: repeat(1, minmax(0,1fr)); gap: 1.4rem; width: 100%;">
            <?php while ($home_eventos->have_posts()) : $home_eventos->the_post();
                $ev_img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : (function_exists('casa_opt_media') ? casa_opt_media('casa_opt_eventos_portada') : '');
            ?>
                <a href="<?php the_permalink(); ?>" style="display:block; text-decoration:none; border-radius:1.2rem; overflow:hidden; background:#111; border:1px solid rgba(255,255,255,0.12);">
                    <div style="height: clamp(200px, 28vw, 250px); overflow:hidden;">
                        <img src="<?php echo esc_url($ev_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" decoding="async" style="width:100%; height:100%; object-fit:cover;" />
                    </div>
                    <div style="padding: 1.3rem 1.4rem 1.5rem;">
                        <span style="color:var(--color-accent); font-size:0.72rem; letter-spacing:1.4px; text-transform:uppercase; font-weight:600;">Próximamente</span>
                        <h3 style="color:#fff; font-family:var(--font-heading); font-size:1.4rem; margin:0.4rem 0 0.5rem;"><?php the_title(); ?></h3>
                        <p style="color:rgba(255,255,255,0.7); font-size:0.92rem; line-height:1.5; margin:0 0 0.8rem;"><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 16)); ?></p>
                        <span style="color:var(--color-accent); font-size:0.85rem; font-weight:600;">Ver evento →</span>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <div style="text-align:center; margin-top: 2.2rem;">
            <a href="<?php echo esc_url(home_url('/eventos')); ?>" style="color: var(--color-accent); text-decoration: none; border-bottom: 1px solid var(--color-accent); font-size: 0.88rem; text-transform: uppercase; letter-spacing: 2px;">Ver todos los eventos →</a>
        </div>
    </div>
</section>
<?php
    wp_reset_postdata();
endif;
?>

<?php if (function_exists('casa_render_visible_faq')) { casa_render_visible_faq(); } ?>

<!-- Aquí Elementor puede inyectar el resto del contenido de la página de inicio -->
<?php if (trim(strip_tags(get_the_content()))) : ?>
<div class="elementor-content-area" style="padding: 4rem 0;">
    <div class="container">
        <?php the_content(); ?>
    </div>
</div>
<?php endif; ?>

<?php 
if (file_exists(get_template_directory() . '/inc/mobile-onepage-sections.php')) {
    include get_template_directory() . '/inc/mobile-onepage-sections.php';
}
?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Zoom out animado de la imagen del hero
    setTimeout(() => {
        const img = document.querySelector('.gs-zoom-in');
        if(img) img.style.transform = 'scale(1)';
    }, 100);

    // Accordion animation logic
    const panels = document.querySelectorAll('.espacio-panel, .home-rest-panel');
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
    });
    window.addEventListener('resize', resetFlex);
    resetFlex();
});
</script>

<?php get_footer(); ?>

