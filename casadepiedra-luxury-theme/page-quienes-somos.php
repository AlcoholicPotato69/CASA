<?php
/**
 * Template Name: Quiénes Somos
 * Description: Plantilla para la página de historia/nosotros.
 */
get_header(); 

$portada_url = get_option('casa_opt_nosotros_portada') ?: get_template_directory_uri() . '/assets/images/salon_principal_1779523069698.png';
?>

<section style="position: relative; width: 100%; height: 50vh; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 4rem;">
    <img src="<?php echo esc_url($portada_url); ?>" alt="Quiénes Somos Portada" style="position: absolute; width: 100%; height: 100%; object-fit: cover; z-index: -1; filter: brightness(0.6);" class="gs-zoom-in" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(10,10,10,0.2) 0%, rgba(10,10,10,0.5) 70%, var(--color-bg, #0a0a0a) 100%); z-index: 0; pointer-events: none;"></div>
    <div style="position: relative; z-index: 1; text-align: center;">
        <span class="text-script reveal-text" style="color: #fff;">Nuestra Historia</span>
        <h1 class="text-hero reveal-text" style="color: #fff; text-shadow: 0 10px 30px rgba(0,0,0,0.5);">Quiénes Somos</h1>
    </div>
</section>

<div style="padding-bottom: 6rem;">
    <style>
        .bento-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        @media (min-width: 992px) {
            .bento-grid {
                grid-template-columns: 1.5fr 1fr;
            }
            .bento-tour {
                grid-column: 1 / -1;
            }
        }
    </style>

    <section class="section" style="padding-top: 4rem;">
        <div class="container">
            <div class="bento-grid">
                <!-- Texto Descriptivo Principal -->
                <div class="reveal-text luxury-card" style="padding: clamp(2rem, 5vw, 4rem); border-radius: 20px; display: flex; flex-direction: column; justify-content: center; background: rgba(255,255,255,0.02);">
                    <span class="text-script" style="font-size: clamp(2rem, 4vw, 3rem); display: block; color: var(--color-accent); margin-bottom: 0.5rem;">
                        <?php echo esc_html(get_option('casa_opt_nosotros_title', 'Nuestra Historia')); ?>
                    </span>
                    <h2 class="text-h2" style="margin-top: 0; font-size: clamp(1.5rem, 3vw, 2.2rem); margin-bottom: 2rem; line-height: 1.3;">
                        <?php echo esc_html(get_option('casa_opt_nosotros_subtitle', 'Donde el pasado y el presente se encuentran.')); ?>
                    </h2>
                    
                    <div class="text-body-lg" style="line-height: 1.8; color: rgba(255,255,255,0.85); font-size: 1.05rem;">
                        <?php 
                        $nosotros_desc = get_option('casa_opt_nosotros_desc');
                        if (!empty($nosotros_desc)) {
                            echo wp_kses_post(stripslashes($nosotros_desc));
                        } else {
                            echo '<p style="margin-bottom: 1.5rem;">Desde 1845, sus muros de cantera han sido testigos de amor y celebración.</p><p>Hoy, en el corazón dorado de la ciudad, cada rincón invita a vivir experiencias únicas, donde la sofisticación se fusiona con la tradición, creando un espacio solo para los más exigentes.</p>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Imagen Destacada Interactiva -->
                <div class="reveal-text luxury-card" style="padding: 0; border-radius: 20px; overflow: hidden; position: relative; min-height: 400px; display: flex;">
                    <?php 
                    $hero_img = get_option('casa_opt_nosotros_hero_img') ?: get_template_directory_uri() . '/assets/images/terraza_mezquite_1779523084857.png';
                    ?>
                    <a href="<?php echo esc_url(home_url('/galeria/')); ?>" style="display: block; width: 100%; height: 100%; position: absolute; inset: 0;" title="Ver Galería">
                        <img src="<?php echo esc_url($hero_img); ?>" alt="Casa de Piedra - Historia" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);" class="gs-zoom-in" onmouseover="this.style.transform='scale(1.08)';" onmouseout="this.style.transform='scale(1)';" />
                        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 40%); pointer-events: none;"></div>
                        <div style="position: absolute; bottom: 2rem; left: 2rem; pointer-events: none;">
                            <span style="color: #fff; font-family: var(--font-heading); font-size: 1.2rem; display: flex; align-items: center; gap: 0.5rem; letter-spacing: 1px; text-transform: uppercase;">Ver Galería <span style="color: var(--color-accent); font-size: 1.5rem;">&rarr;</span></span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Recorrido Virtual 3D con Gran Protagonismo (Escritorio / Global) -->
    <?php 
    $virtual_tour = get_option('casa_opt_nosotros_virtual_tour');
    if (empty(trim($virtual_tour))) {
        $virtual_tour = 'https://publicmss.s3.amazonaws.com/Adivor/CasaDePiedra/V3/index.htm';
    }
    if (!empty($virtual_tour)) : 
    ?>
    <section class="section" style="padding-top: 2rem; padding-bottom: 6rem;">
        <div class="container">
            <div style="text-align: center; max-width: 850px; margin: 0 auto 3.5rem;">
                <span class="text-script reveal-text" style="font-size: clamp(2rem, 4vw, 3rem); color: var(--color-accent); display: block; margin-bottom: 0.5rem;">
                    Experiencia Inmersiva 360°
                </span>
                <h2 class="text-h2 reveal-text" style="font-size: clamp(2rem, 4.5vw, 3.5rem); margin-bottom: 1.2rem; color: #fff;">
                    Recorrido Virtual 3D
                </h2>
                <p class="text-body-lg reveal-text" style="font-size: 1.15rem; color: rgba(255,255,255,0.8);">
                    Explora libremente la majestuosa arquitectura de la ex-hacienda, nuestros salones de lujo, jardines y rincones icónicos desde cualquier lugar.
                </p>
            </div>

            <div class="reveal-text" style="border-radius: 24px; overflow: hidden; border: 1px solid rgba(212,175,55,0.45); box-shadow: 0 25px 80px rgba(0,0,0,0.95); background: #000;">
                <div style="padding: 1.2rem 2.5rem; background: linear-gradient(90deg, rgba(20,20,20,0.98) 0%, rgba(35,30,15,0.98) 50%, rgba(20,20,20,0.98) 100%); border-bottom: 1px solid rgba(212,175,55,0.3); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <span style="display: inline-block; width: 12px; height: 12px; background: #22c55e; border-radius: 50%; box-shadow: 0 0 10px #22c55e;"></span>
                        <span style="color: #fff; font-family: var(--font-heading); font-size: 1.25rem; letter-spacing: 0.5px;">CASA DE PIEDRA — TOUR INTERACTIVO 3D</span>
                    </div>
                    <span style="color: var(--color-accent); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">
                        ◆ Navegación Libre 360° ◆
                    </span>
                </div>
                <div style="height: 78vh; min-height: 650px; width: 100%; position: relative;">
                    <iframe 
                        src="<?php echo esc_url($virtual_tour); ?>" 
                        width="100%" 
                        height="100%" 
                        style="border: 0; display: block;" 
                        allowfullscreen="" 
                        loading="lazy" 
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
