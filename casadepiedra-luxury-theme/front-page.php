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
$hero_image = get_option('casa_opt_home_hero_img') ?: get_template_directory_uri() . '/assets/images/salon_principal_1779523069698.png';
?>

<section id="inicio" style="position: relative; min-height: 100vh; min-height: 100dvh; display: flex; align-items: center; overflow: hidden; padding-top: 5rem;">
    <div style="position: absolute; inset: 0; z-index: -1;">
        <img src="<?php echo esc_url($hero_image); ?>" alt="Casa de Piedra" class="img-cover gs-zoom-in" style="width: 100%; height: 100%; object-fit: cover; object-position: center; transform: scale(1.1); transition: transform 2.5s cubic-bezier(0.32, 0.72, 0, 1);" />
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(5,5,5,0.3) 0%, rgba(5,5,5,0.6) 60%, #050505 100%);"></div>
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
                    <a href="<?php echo esc_url(home_url('/espacios')); ?>" class="hero-cta-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; background: var(--color-accent); color: #000; padding: clamp(1.1rem, 1.4vw, 1.35rem) clamp(2.4rem, 3.5vw, 3.5rem); border-radius: 9999px; font-weight: 600; font-size: clamp(1.05rem, 1.25vw, 1.22rem); font-family: var(--font-body); letter-spacing: 0.04em; box-shadow: 0 12px 35px rgba(212, 175, 55, 0.38); transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);" onmouseover="this.style.transform='scale(1.05) translateY(-3px)'; this.style.boxShadow='0 18px 45px rgba(212, 175, 55, 0.55)';" onmouseout="this.style.transform='scale(1) translateY(0)'; this.style.boxShadow='0 12px 35px rgba(212, 175, 55, 0.38)';">
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
    border-top: 1px solid rgba(212,175,55,0.2);
    border-bottom: 1px solid rgba(212,175,55,0.2);
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
            <div style="position: relative; border-radius: 20px; overflow: hidden; border: 1px solid rgba(212,175,55,0.4); box-shadow: 0 20px 50px rgba(0,0,0,0.85);">
                <?php 
                $home_nosotros_img = get_option('casa_opt_nosotros_hero_img') ?: get_template_directory_uri() . '/assets/images/terraza_mezquite_1779523084857.png';
                ?>
                <img src="<?php echo esc_url($home_nosotros_img); ?>" alt="Arquitectura y Tradición" style="width: 100%; height: 420px; object-fit: cover; display: block;" />
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
        <div style="border-radius: 24px; overflow: hidden; border: 1px solid rgba(212,175,55,0.45); box-shadow: 0 25px 80px rgba(0,0,0,0.95); background: #000;">
            <div style="padding: 1.2rem 2.5rem; background: linear-gradient(90deg, rgba(20,20,20,0.98) 0%, rgba(35,30,15,0.98) 50%, rgba(20,20,20,0.98) 100%); border-bottom: 1px solid rgba(212,175,55,0.3); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
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
                    allow="accelerometer; gyroscope; vr; xr-spatial-tracking; fullscreen"
                    allowfullscreen="true"
                    webkitallowfullscreen="true"
                    mozallowfullscreen="true"
                    loading="lazy" 
                ></iframe>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- SECCIÓN DE RESEÑAS VERIFICADAS DE GOOGLE MAPS -->
<?php 
$google_maps_pin = get_option('casa_opt_google_maps_link', 'https://www.google.com/maps/place/Casa+De+Piedra/@21.1511013,-101.6954442,3365m/data=!3m2!1e3!5s0x842bbf530842ac4b:0x4642591264eb2eec!4m6!3m5!1s0x842bbf53a2e4d0e3:0xfe1f47b7b2f6b0a3!8m2!3d21.1585368!4d-101.6992601!16s%2Fg%2F11f_b_l520?entry=ttu&g_ep=EgoyMDI2MDcwNy4wIKXMDSoASAFQAw%3D%3D#');
$reviews_subtitle = get_option('casa_opt_home_reviews_subtitle', 'Experiencias Inolvidables');
$reviews_title = get_option('casa_opt_home_reviews_title', 'Lo que dicen nuestros visitantes');
$rev1_text = get_option('casa_opt_home_rev1_text', 'Celebrar nuestra boda en Hacienda Casa de Piedra fue la mejor decisión. El Jardín Principal es majestuoso, las vistas de la cantera iluminada de noche son mágicas y todo el servicio superó nuestras expectativas.');
$rev1_author = get_option('casa_opt_home_rev1_author', 'Sofía & Alejandro M.');
$rev2_text = get_option('casa_opt_home_rev2_text', 'Sin duda el referente gastronómico y arquitectónico del Bajío. Sus restaurantes ofrecen una calidad de alta cocina excepcional en un entorno histórico invaluable en León.');
$rev2_author = get_option('casa_opt_home_rev2_author', 'Carlos Elizondo');
$rev3_text = get_option('casa_opt_home_rev3_text', 'Asistí a un evento de gala en el Salón Principal y quedé maravillado con la acústica, la elegancia de los muros coloniales y la atención personalizada del personal. Un lugar único de nivel internacional.');
$rev3_author = get_option('casa_opt_home_rev3_author', 'Valeria Santamarina');
$rev1_url = get_option('casa_opt_home_rev1_url', $google_maps_pin);
$rev2_url = get_option('casa_opt_home_rev2_url', $google_maps_pin);
$rev3_url = get_option('casa_opt_home_rev3_url', $google_maps_pin);
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
                <span style="color: rgba(255,255,255,0.7); font-size: 0.8rem;">4.8 / 5.0</span>
            </div>
            <span class="text-script" style="color: var(--color-accent); display: block; margin-bottom: 0.3rem;"><?php echo esc_html($reviews_subtitle); ?></span>
            <h2 class="text-hero" style="color: #fff; font-size: clamp(2rem, 4vw, 3.2rem); margin: 0;"><?php echo esc_html($reviews_title); ?></h2>
        </div>

        <div class="google-reviews-grid">
            <!-- Review 1 -->
            <a href="<?php echo esc_url($rev1_url); ?>" target="_blank" rel="noopener noreferrer" class="google-review-card" style="text-decoration: none; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                        <div class="google-review-stars" style="margin-bottom: 0;">★★★★★</div>
                        <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                    </div>
                    <p class="google-review-text" style="color: rgba(255,255,255,0.85);">
                        &ldquo;<?php echo esc_html($rev1_text); ?>&rdquo;
                    </p>
                </div>
                <div class="google-review-author">
                    <div class="google-review-avatar"><?php echo strtoupper(substr($rev1_author, 0, 1)); ?></div>
                    <div class="google-review-info">
                        <h4><?php echo esc_html($rev1_author); ?></h4>
                        <span>Reseña verificada en Google</span>
                    </div>
                </div>
            </a>

            <!-- Review 2 -->
            <a href="<?php echo esc_url($rev2_url); ?>" target="_blank" rel="noopener noreferrer" class="google-review-card" style="text-decoration: none; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                        <div class="google-review-stars" style="margin-bottom: 0;">★★★★★</div>
                        <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                    </div>
                    <p class="google-review-text" style="color: rgba(255,255,255,0.85);">
                        &ldquo;<?php echo esc_html($rev2_text); ?>&rdquo;
                    </p>
                </div>
                <div class="google-review-author">
                    <div class="google-review-avatar"><?php echo strtoupper(substr($rev2_author, 0, 1)); ?></div>
                    <div class="google-review-info">
                        <h4><?php echo esc_html($rev2_author); ?></h4>
                        <span>Reseña verificada en Google</span>
                    </div>
                </div>
            </a>

            <!-- Review 3 -->
            <a href="<?php echo esc_url($rev3_url); ?>" target="_blank" rel="noopener noreferrer" class="google-review-card" style="text-decoration: none; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.3s;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                        <div class="google-review-stars" style="margin-bottom: 0;">★★★★★</div>
                        <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                    </div>
                    <p class="google-review-text" style="color: rgba(255,255,255,0.85);">
                        &ldquo;<?php echo esc_html($rev3_text); ?>&rdquo;
                    </p>
                </div>
                <div class="google-review-author">
                    <div class="google-review-avatar"><?php echo strtoupper(substr($rev3_author, 0, 1)); ?></div>
                    <div class="google-review-info">
                        <h4><?php echo esc_html($rev3_author); ?></h4>
                        <span>Reseña verificada en Google</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Mapa Interactivo 100% Responsivo en Modo Oscuro Sólido -->
        <div style="margin-top: 3.5rem; border-radius: 24px; overflow: hidden; border: 1px solid rgba(212,175,55,0.35); background: #111111; box-shadow: 0 25px 65px rgba(0,0,0,0.95); height: clamp(360px, 46vw, 520px); width: 100%; position: relative;">
            <iframe 
                title="Casa de Piedra Ubicación en Google Maps"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3721.2825381283!2d-101.6992601!3d21.1585368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x842bbf53a2e4d0e3%3A0xfe1f47b7b2f6b0a3!2sCasa%20De%20Piedra!5e0!3m2!1ses-419!2smx!4v1710000000000!5m2!1ses-419!2smx"
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

<!-- Aquí Elementor puede inyectar el resto del contenido de la página de inicio -->
<?php if (trim(strip_tags(get_the_content()))) : ?>
<div class="elementor-content-area" style="padding: 4rem 0;">
    <div class="container">
        <?php the_content(); ?>
    </div>
</div>
<?php endif; ?>

<!-- Secciones exclusivas para scroll en versión Móvil (< 1024px) -->
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
});
</script>

<?php get_footer(); ?>
