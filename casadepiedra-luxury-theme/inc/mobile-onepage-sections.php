<?php
/**
 * Secciones de Scroll Continuo para Dispositivos Móviles (< 1024px)
 * Se incluye en front-page.php para brindar una experiencia One-Page en teléfonos.
 */
?>
<style>
/* Por defecto oculto en escritorio */
.mobile-onepage-wrapper {
    display: none;
}
@media (max-width: 1023px) {
    .mobile-onepage-wrapper {
        display: block;
        padding-bottom: 4rem;
    }
    .mobile-section-block {
        padding: 5rem 0 3rem 0;
        border-bottom: 1px solid rgba(212, 175, 55, 0.15);
        position: relative;
    }
    .mobile-section-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .mobile-section-header .text-script {
        font-size: clamp(1.8rem, 7vw, 2.5rem);
        color: var(--color-accent);
        display: block;
        margin-bottom: 0.2rem;
    }
    .mobile-section-header .text-h2 {
        font-size: clamp(1.6rem, 6vw, 2.2rem);
        color: #fff;
        margin: 0;
    }
    .mobile-card-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    .mobile-luxury-card {
        background: rgba(20, 20, 20, 0.6);
        border: 1px solid rgba(212, 175, 55, 0.25);
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.3s ease, border-color 0.3s ease;
        text-decoration: none;
        display: block;
    }
    .mobile-luxury-card:active {
        transform: scale(0.98);
        border-color: var(--color-accent);
    }
    .mobile-card-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .mobile-card-content {
        padding: 1.5rem;
    }
    .mobile-card-title {
        color: #fff;
        font-family: var(--font-heading);
        font-size: 1.5rem;
        margin: 0 0 0.5rem 0;
    }
    .mobile-card-desc {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 1.2rem;
    }
    .mobile-card-link {
        color: var(--color-accent);
        font-family: var(--font-heading);
        font-size: 1.1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .mobile-view-all-btn {
        display: block;
        text-align: center;
        margin: 2.5rem auto 0 auto;
        background: transparent;
        border: 1px solid var(--color-accent);
        color: var(--color-accent);
        padding: 0.8rem 2rem;
        border-radius: 9999px;
        font-family: var(--font-heading);
        font-size: 1.15rem;
        text-decoration: none;
        width: fit-content;
    }
}
</style>

<div class="mobile-onepage-wrapper">
    <!-- 1. QUIÉNES SOMOS -->
    <?php if (get_option('casa_opt_status_nosotros', '1') === '1') : ?>
    <section id="quienes-somos" class="mobile-section-block">
        <div class="container">
            <div class="mobile-section-header">
                <span class="text-script">Nuestra Historia</span>
                <h2 class="text-h2">Quiénes Somos</h2>
            </div>
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(212,175,55,0.2); border-radius: 20px; padding: 2rem; text-align: center;">
                <p style="color: rgba(255,255,255,0.85); line-height: 1.7; font-size: 1.05rem; margin-bottom: 1.5rem;">
                    <?php echo esc_html(get_option('casa_opt_nosotros_subtitle', 'Donde el pasado y el presente se encuentran. Desde 1845, sus muros de cantera han sido testigos de amor y celebración. Hoy, en el corazón dorado de la ciudad, cada rincón invita a vivir experiencias únicas, donde la sofisticación se fusiona con la tradición, creando un espacio solo para los más exigentes.')); ?>
                </p>

                <?php 
                $virtual_tour = get_option('casa_opt_nosotros_virtual_tour');
                if (empty(trim($virtual_tour))) {
                    $virtual_tour = 'https://publicmss.s3.amazonaws.com/Adivor/CasaDePiedra/V3/index.htm';
                }
                ?>
                <div style="margin: 1.5rem 0; border-radius: 16px; overflow: hidden; border: 1px solid rgba(212,175,55,0.4); background: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.8); text-align: left;">
                    <div style="padding: 0.8rem 1.2rem; background: rgba(20,20,20,0.95); border-bottom: 1px solid rgba(212,175,55,0.25); display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--color-accent); font-family: var(--font-heading); font-size: 1.05rem; font-weight: 600;">🌐 Recorrido 3D Interactivo</span>
                        <span style="color: rgba(255,255,255,0.6); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Toca y explora</span>
                    </div>
                    <div style="height: 380px; width: 100%; position: relative;">
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

    <!-- 2. ESPACIOS -->
    <?php if (get_option('casa_opt_status_espacios', '1') === '1') : ?>
    <section id="espacios" class="mobile-section-block">
        <div class="container">
            <div class="mobile-section-header">
                <span class="text-script">Exclusividad</span>
                <h2 class="text-h2">Nuestros Espacios</h2>
            </div>
        </div>
        <!-- Luxury Editorial Intro Block (Full Screen Width) -->
        <div class="luxury-editorial-intro" style="margin-bottom: 2.5rem;">
            <div class="luxury-intro-ornament">
                <span class="line-left"></span>
                <span class="diamond-icon">✦</span>
                <span class="line-right"></span>
            </div>
            <blockquote class="luxury-intro-quote">
                &ldquo;<?php echo esc_html(ucfirst(get_option('casa_opt_global_espacios_desc', 'Escenarios para grandes historias'))); ?>&rdquo;
            </blockquote>
            <div class="luxury-intro-footer">
                <span class="small-caps-label">Hacienda Casa de Piedra</span>
            </div>
        </div>
        <div class="container">
            <div class="mobile-card-grid">
                <?php 
                $espacios_query = new WP_Query(array(
                    'post_type' => 'espacios',
                    'posts_per_page' => 4,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));
                if ($espacios_query->have_posts()) : 
                    while ($espacios_query->have_posts()) : $espacios_query->the_post(); 
                ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" onclick="window.location.assign('<?php echo esc_js(get_permalink()); ?>'); return false;" class="mobile-luxury-card" style="cursor: pointer; position: relative; z-index: 10; display: block;">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="mobile-card-img" />
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/salon_principal_1779523069698.png" alt="<?php the_title_attribute(); ?>" class="mobile-card-img" />
                        <?php endif; ?>
                        <div class="mobile-card-content">
                            <?php 
                            $cap_meta = get_post_meta(get_the_ID(), '_espacio_capacidad', true);
                            $clean_cap = !empty($cap_meta) ? trim(str_ireplace(array('personas', 'px', 'hasta'), '', $cap_meta)) : '';
                            if (empty($clean_cap)) {
                                $t = strtolower(get_the_title());
                                if (strpos($t, 'jardín') !== false || strpos($t, 'jardin') !== false) $clean_cap = '1,500';
                                elseif (strpos($t, 'principal') !== false) $clean_cap = '800';
                                elseif (strpos($t, 'pavorreales') !== false) $clean_cap = '90';
                                elseif (strpos($t, 'mezquite') !== false) $clean_cap = '150';
                            }
                            if (!empty($clean_cap)) :
                            ?>
                                <span style="display: inline-block; padding: 0.2rem 0.75rem; background: rgba(212, 175, 55, 0.15); border: 1px solid rgba(212, 175, 55, 0.4); border-radius: 999px; color: var(--color-accent); font-size: 0.72rem; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 500;">Hasta <?php echo esc_html($clean_cap); ?> personas</span>
                            <?php endif; ?>
                            <h3 class="mobile-card-title"><?php the_title(); ?></h3>
                            <p class="mobile-card-desc"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                            <span class="mobile-card-link">Ver Espacio <span>→</span></span>
                        </div>
                    </a>
                <?php 
                    endwhile; 
                    wp_reset_postdata(); 
                else : 
                ?>
                    <p style="text-align: center; color: #aaa;">No hay espacios publicados por el momento.</p>
                <?php endif; ?>
            </div>
            <a href="<?php echo esc_url(home_url('/espacios')); ?>" onclick="window.location.assign('<?php echo esc_js(home_url('/espacios')); ?>'); return false;" class="mobile-view-all-btn">
                Ver Todos los Espacios →
            </a>
        </div>
    </section>
    <?php endif; ?>

    <!-- 3. RESTAURANTES -->
    <?php if (get_option('casa_opt_status_restaurantes', '1') === '1') : ?>
    <section id="restaurantes" class="mobile-section-block">
        <div class="container">
            <div class="mobile-section-header">
                <span class="text-script"><?php echo esc_html(get_option('casa_opt_global_restaurantes_subtitle', 'Alta cocina')); ?></span>
                <h2 class="text-h2">Restaurantes</h2>
            </div>
        </div>
        <!-- Luxury Editorial Intro Block (Full Screen Width) -->
        <div class="luxury-editorial-intro" style="margin-bottom: 2.5rem;">
            <div class="luxury-intro-ornament">
                <span class="line-left"></span>
                <span class="diamond-icon">✦</span>
                <span class="line-right"></span>
            </div>
            <blockquote class="luxury-intro-quote">
                &ldquo;<?php echo esc_html(ucfirst(get_option('casa_opt_global_restaurantes_desc', 'La cúspide de la gastronomía en el Bajío. Una experiencia inigualable que reúne la oferta gastronómica más exclusiva de la región, ofreciendo un viaje de sabores únicos.'))); ?>&rdquo;
            </blockquote>
            <div class="luxury-intro-footer">
                <span class="small-caps-label">Hacienda Casa de Piedra &bull; Alta Cocina</span>
            </div>
        </div>
        <div class="container">
            <div class="mobile-card-grid">
                <?php 
                $restaurantes_query = new WP_Query(array(
                    'post_type' => 'restaurantes',
                    'posts_per_page' => 4,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));
                if ($restaurantes_query->have_posts()) : 
                    while ($restaurantes_query->have_posts()) : $restaurantes_query->the_post(); 
                ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" onclick="window.location.assign('<?php echo esc_js(get_permalink()); ?>'); return false;" class="mobile-luxury-card" style="cursor: pointer; position: relative; z-index: 10; display: block;">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="mobile-card-img" />
                        <?php else : ?>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/terraza_mezquite_1779523084857.png" alt="<?php the_title_attribute(); ?>" class="mobile-card-img" />
                        <?php endif; ?>
                        <div class="mobile-card-content">
                            <h3 class="mobile-card-title"><?php the_title(); ?></h3>
                            <p class="mobile-card-desc"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                            <span class="mobile-card-link">Ver Experiencia <span>→</span></span>
                        </div>
                    </a>
                <?php 
                    endwhile; 
                    wp_reset_postdata(); 
                else : 
                ?>
                    <p style="text-align: center; color: #aaa;">No hay restaurantes publicados por el momento.</p>
                <?php endif; ?>
            </div>
            <a href="<?php echo esc_url(home_url('/restaurantes')); ?>" onclick="window.location.assign('<?php echo esc_js(home_url('/restaurantes')); ?>'); return false;" class="mobile-view-all-btn">
                Ver Todos los Restaurantes →
            </a>
        </div>
    </section>
    <?php endif; ?>

    <!-- 4. EVENTOS -->
    <?php if (get_option('casa_opt_status_eventos', '1') === '1') : ?>
    <section id="eventos" class="mobile-section-block">
        <div class="container">
            <div class="mobile-section-header">
                <span class="text-script">Celebraciones</span>
                <h2 class="text-h2"><?php echo esc_html(get_option('casa_opt_global_eventos_title', 'Próximos Eventos')); ?></h2>
            </div>
            <div class="mobile-card-grid">
                <?php 
                $eventos_query = new WP_Query(array(
                    'post_type' => 'eventos',
                    'posts_per_page' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                if ($eventos_query->have_posts()) : 
                    while ($eventos_query->have_posts()) : $eventos_query->the_post(); 
                        $btn_enlace = get_post_meta(get_the_ID(), '_evento_enlace', true);
                        $link_url = !empty($btn_enlace) ? esc_url($btn_enlace) : get_permalink();
                ?>
                    <a href="<?php echo esc_url($link_url); ?>" class="mobile-luxury-card" style="cursor: pointer; position: relative; z-index: 10; display: block;">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="mobile-card-img" />
                        <?php endif; ?>
                        <div class="mobile-card-content">
                            <span style="display: inline-block; padding: 0.2rem 0.75rem; background: rgba(212, 175, 55, 0.15); border: 1px solid rgba(212, 175, 55, 0.4); border-radius: 999px; color: var(--color-accent); font-size: 0.72rem; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 0.5rem; font-weight: 500;">Próximamente</span>
                            <h3 class="mobile-card-title"><?php the_title(); ?></h3>
                            <p class="mobile-card-desc"><?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(), 15); ?></p>
                            <span class="mobile-card-link">Ver Detalles <span>→</span></span>
                        </div>
                    </a>
                <?php 
                    endwhile; 
                    wp_reset_postdata(); 
                else : 
                ?>
                    <p style="text-align: center; color: #aaa;">Estamos creando nuevas experiencias para ti.</p>
                <?php endif; ?>
            </div>
            <a href="<?php echo esc_url(home_url('/eventos')); ?>" onclick="window.location.assign('<?php echo esc_js(home_url('/eventos')); ?>'); return false;" class="mobile-view-all-btn">
                Ver Todos los Eventos →
            </a>
        </div>
    </section>
    <?php endif; ?>

    <!-- 5. GALERÍA -->
    <?php if (get_option('casa_opt_status_galeria', '1') === '1') : ?>
    <section id="galeria" class="mobile-section-block">
        <div class="container">
            <div class="mobile-section-header">
                <span class="text-script">Esplendor Visual</span>
                <h2 class="text-h2">Galería</h2>
            </div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <?php 
                $gallery_ids_str = get_option('casa_opt_galeria_imagenes', '');
                $gallery_ids = !empty($gallery_ids_str) ? explode(',', $gallery_ids_str) : array();
                $mobile_gallery_ids = array_slice($gallery_ids, 0, 8);

                if (!empty($mobile_gallery_ids)) :
                    foreach ($mobile_gallery_ids as $img_id) :
                        $full_url = wp_get_attachment_image_url($img_id, 'full');
                        $thumb_url = wp_get_attachment_image_url($img_id, 'medium');
                        if (!$thumb_url) continue;
                ?>
                    <a href="<?php echo esc_url($full_url); ?>" class="glightbox" data-gallery="mobile-section-galeria" style="height: 160px; border-radius: 12px; overflow: hidden; border: 1px solid rgba(212,175,55,0.2); display: block; position: relative; cursor: zoom-in;">
                        <img src="<?php echo esc_url($thumb_url); ?>" style="width: 100%; height: 100%; object-fit: cover; pointer-events: none;" alt="Galería Casa de Piedra" />
                    </a>
                <?php 
                    endforeach;
                else :
                    $galeria_query = new WP_Query(array(
                        'post_type' => 'attachment',
                        'post_status' => 'inherit',
                        'post_mime_type' => 'image',
                        'posts_per_page' => 8,
                        'orderby' => 'rand'
                    ));
                    if ($galeria_query->have_posts()) : 
                        while ($galeria_query->have_posts()) : $galeria_query->the_post(); 
                            $full_url = wp_get_attachment_image_url(get_the_ID(), 'full');
                            $thumb_url = wp_get_attachment_image_url(get_the_ID(), 'medium');
                    ?>
                        <a href="<?php echo esc_url($full_url); ?>" class="glightbox" data-gallery="mobile-section-galeria" style="height: 160px; border-radius: 12px; overflow: hidden; border: 1px solid rgba(212,175,55,0.2); display: block; position: relative; cursor: zoom-in;">
                            <img src="<?php echo esc_url($thumb_url); ?>" style="width: 100%; height: 100%; object-fit: cover; pointer-events: none;" alt="Galería Casa de Piedra" />
                        </a>
                    <?php 
                        endwhile; 
                        wp_reset_postdata(); 
                    else : 
                    ?>
                        <div style="grid-column: 1 / -1; text-align: center; color: #aaa;">Explora nuestros rincones y detalles arquitectónicos.</div>
                    <?php 
                    endif;
                endif; 
                ?>
            </div>
            <a href="<?php echo esc_url(home_url('/galeria')); ?>" onclick="window.location.assign('<?php echo esc_js(home_url('/galeria')); ?>'); return false;" class="mobile-view-all-btn">
                Ver Galería Completa →
            </a>
        </div>
    </section>
    <?php endif; ?>

    <!-- 5. CONTACTO -->
    <?php if (get_option('casa_opt_status_contacto', '1') === '1') : ?>
    <section id="contacto" class="mobile-section-block" style="border-bottom: none;">
        <div class="container">
            <div class="mobile-section-header">
                <span class="text-script">Atención Personalizada</span>
                <h2 class="text-h2">Contacto</h2>
            </div>
            <div style="background: rgba(20,20,20,0.8); border: 1px solid rgba(212,175,55,0.3); border-radius: 20px; padding: 2rem; text-align: center;">
                <p style="color: rgba(255,255,255,0.85); margin-bottom: 1.5rem; font-size: 1.05rem;">
                    Estamos a su disposición para coordinar su evento exclusivo o reservar su mesa.
                </p>
                <div style="margin-bottom: 1.5rem; color: var(--color-accent); font-family: var(--font-heading); font-size: 1.3rem;">
                    📍 León, Guanajuato
                </div>
                <button type="button" class="btn-open-quote-modal" style="display: inline-block; background: var(--color-accent); color: #000; padding: 1.2rem 2.8rem; border-radius: 9999px; font-weight: 600; font-family: var(--font-body); text-decoration: none; border: none; cursor: pointer; box-shadow: 0 10px 25px rgba(212,175,55,0.3); font-size: 1.15rem;">
                    Solicitar Cotización →
                </button>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>
