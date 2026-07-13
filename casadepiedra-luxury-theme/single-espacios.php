<?php
/**
 * Template Name: Detalle de Espacio (Banner Superior + Imagen/Texto Lado a Lado 100% Responsivo)
 * Description: Plantilla con banner superior a todo lo ancho con el nombre del espacio, y disposición de texto a un lado e imagen al otro totalmente responsiva.
 */
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $cap_meta = get_post_meta(get_the_ID(), '_espacio_capacidad', true);
    $clean_cap = !empty($cap_meta) ? trim(str_ireplace(array('personas', 'px', 'hasta'), '', $cap_meta)) : '';
    $t = strtolower(get_the_title());
    if (empty($clean_cap)) {
        if (strpos($t, 'jardín') !== false || strpos($t, 'jardin') !== false) $clean_cap = '1,500';
        elseif (strpos($t, 'principal') !== false) $clean_cap = '800';
        elseif (strpos($t, 'pavorreales') !== false) $clean_cap = '90';
        elseif (strpos($t, 'mezquite') !== false) $clean_cap = '150';
    }

    $gallery_ids = get_post_meta(get_the_ID(), '_casadepiedra_gallery_ids', true);
    $ids_array = !empty($gallery_ids) ? explode(',', $gallery_ids) : array();
    $portada_meta = get_post_meta(get_the_ID(), '_espacio_portada', true);
    $hero_img = !empty($portada_meta) ? $portada_meta : (has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : get_template_directory_uri() . '/assets/images/jardin_principal_1779523113451.png');
?>

<!-- 1. BANNER HASTA ARRIBA CON EL NOMBRE DEL ESPACIO (100% ANCHO) -->
<section style="position: relative; width: 100%; height: clamp(340px, 42vh, 480px); display: flex; align-items: center; justify-content: center; overflow: hidden; background: #080808;">
    <img src="<?php echo esc_url($hero_img); ?>" alt="<?php the_title_attribute(); ?>" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; filter: brightness(0.78); transform: scale(1.03);" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.15) 0%, rgba(8,8,8,0.42) 75%, #080808 100%); z-index: 1; pointer-events: none;"></div>

    <div style="position: relative; z-index: 2; text-align: center; padding: 0 clamp(1rem, 4vw, 3rem); max-width: 1100px; margin-top: 30px;">
        <span class="text-script" style="color: var(--color-accent); font-size: 1.4rem;">Casa de Piedra</span>
        <h1 style="color: #fff; font-size: clamp(2.3rem, 5vw, 4.2rem); margin: 0.3rem 0; font-family: var(--font-heading); line-height: 1.1; text-shadow: 0 10px 30px rgba(0,0,0,0.85);">
            <?php the_title(); ?>
        </h1>
    </div>
</section>

<!-- 2. SECCIÓN PRINCIPAL: IMAGEN A UN LADO & TEXTO AL OTRO (100% RESPONSIVO) -->
<main style="padding: clamp(3.5rem, 7vw, 5.5rem) 0 6rem; background: #080808; width: 100%;">
    <div style="max-width: 1600px; margin: 0 auto; padding: 0 clamp(1.2rem, 4vw, 3.5rem);">

        <style>
            .espacio-detail-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2.5rem;
                align-items: start;
            }
            .espacio-col-gallery {
                order: 2;
                width: 100%;
            }
            .espacio-col-info {
                order: 1;
                width: 100%;
            }
            @media screen and (min-width: 1024px) {
                .espacio-detail-grid {
                    grid-template-columns: 1fr 1fr;
                    gap: 3.5rem;
                }
                .espacio-col-gallery {
                    order: 1;
                }
                .espacio-col-info {
                    order: 2;
                }
            }
        </style>

        <div class="espacio-detail-grid">
            
            <!-- LADO IZQUIERDO EN PC / SEGUNDO EN MÓVIL: GALERÍA FOTOGRÁFICA -->
            <div class="espacio-col-gallery">
                <div style="border-radius: 1.4rem; overflow: hidden; height: clamp(340px, 42vw, 520px); position: relative; border: 1px solid rgba(212,175,55,0.32); background: #111; box-shadow: 0 25px 55px rgba(0,0,0,0.8);">
                    <style>
                    .espacio-slider { position: relative; width: 100%; height: 100%; overflow: hidden; }
                    .espacio-slider-track { display: flex; height: 100%; transition: transform 0.55s cubic-bezier(0.25, 1, 0.5, 1); }
                    .espacio-slide { position: relative; cursor: zoom-in; flex: 0 0 100%; height: 100%; display: block; }
                    .espacio-slide img { width: 100%; height: 100%; object-fit: cover; }
                    </style>

                    <?php 
                    if (!empty($ids_array)) {
                        echo '<div class="espacio-slider" id="espacioSlider">';
                        echo '<div class="espacio-slider-track" id="espacioSliderTrack">';
                        foreach ($ids_array as $id) {
                            $img_url = wp_get_attachment_image_url($id, 'large');
                            $img_full = wp_get_attachment_image_url($id, 'full');
                            if ($img_url) {
                                echo '<a href="'.esc_url($img_full).'" class="espacio-slide glightbox" data-gallery="espacio-gallery">';
                                echo '<img src="'.esc_url($img_url).'" alt="'.esc_attr(get_the_title()).'" loading="lazy" />';
                                echo '</a>';
                            }
                        }
                        echo '</div>';
                        echo '</div>';
                    } else {
                        $fallback_img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : $hero_img;
                        $fallback_full = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : $hero_img;
                        ?>
                        <a href="<?php echo esc_url($fallback_full); ?>" class="glightbox" data-gallery="espacio-gallery" style="display:block; height:100%;">
                            <img src="<?php echo esc_url($fallback_img); ?>" alt="<?php the_title_attribute(); ?>" style="width:100%; height:100%; object-fit:cover;" />
                        </a>
                    <?php } ?>
                </div>

                <!-- Miniaturas de la Galería 100% Responsivas -->
                <?php if (!empty($ids_array) && count($ids_array) > 1) : ?>
                    <div style="display: flex; gap: 0.75rem; margin-top: 1rem; overflow-x: auto; padding-bottom: 0.5rem; -webkit-overflow-scrolling: touch;">
                        <?php foreach ($ids_array as $t_idx => $t_id) : 
                            $t_src = wp_get_attachment_image_url($t_id, 'medium');
                            if (!$t_src) continue;
                        ?>
                            <button type="button" onclick="window.goToEspacioSlide && window.goToEspacioSlide(<?php echo $t_idx; ?>)" style="flex: 0 0 clamp(80px, 16vw, 105px); height: clamp(56px, 11vw, 72px); border-radius: 0.7rem; overflow: hidden; border: 1.5px solid rgba(212,175,55,0.4); background: #000; cursor: pointer; padding: 0; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.06)';" onmouseout="this.style.transform='scale(1)';">
                                <img src="<?php echo esc_url($t_src); ?>" alt="Miniatura" style="width:100%; height:100%; object-fit:cover;" />
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const track = document.getElementById('espacioSliderTrack');
                    if (!track) return;
                    const slidesCount = track.children.length;
                    let currentIndex = 0;
                    window.goToEspacioSlide = (index) => {
                        if (index < 0) index = slidesCount - 1;
                        if (index >= slidesCount) index = 0;
                        currentIndex = index;
                        track.style.transform = `translateX(-${currentIndex * 100}%)`;
                    };
                    let autoPlay = setInterval(() => window.goToEspacioSlide(currentIndex + 1), 4000);
                    track.addEventListener('mouseenter', () => clearInterval(autoPlay));
                    if(typeof GLightbox !== 'undefined') GLightbox({ selector: '.glightbox' });
                });
                </script>
            </div>

            <!-- LADO DERECHO EN PC / PRIMERO EN MÓVIL: TARJETA OFICIAL -->
            <div class="espacio-col-info">
                <div class="luxury-info-panel" style="padding: clamp(1.8rem, 4vw, 3rem); border-radius: 1.4rem; background: #111111; border: 1px solid rgba(212, 175, 55, 0.35); box-shadow: 0 20px 50px rgba(0,0,0,0.7); position: relative; z-index: 2;">
                    <span style="color: var(--color-accent); font-size: 0.82rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block; margin-bottom: 0.7rem;">
                        Arquitectura & Legado
                    </span>
                    <h2 style="color: #fff; font-size: clamp(1.6rem, 3vw, 2.3rem); font-family: var(--font-heading); margin: 0 0 1.2rem 0; line-height: 1.2;">
                        <?php the_title(); ?>
                    </h2>
                    
                    <!-- TEXTO DEL SALÓN -->
                    <div class="text-body-lg" style="color: #ccc; font-size: clamp(0.95rem, 1.8vw, 1.05rem); line-height: 1.75; margin-bottom: 2rem;">
                        <?php the_content(); ?>
                    </div>

                    <!-- Ficha Técnica Rápida con m2 -->
                    <?php
                    $m2_meta = get_post_meta(get_the_ID(), '_espacio_m2', true);
                    $clean_m2 = !empty($m2_meta) ? trim(str_ireplace(array('m2', 'm²', 'metros'), '', $m2_meta)) : '';
                    if (empty($clean_m2)) {
                        if (strpos($t, 'jardín') !== false || strpos($t, 'jardin') !== false) $clean_m2 = '2,400';
                        elseif (strpos($t, 'principal') !== false) $clean_m2 = '850';
                        elseif (strpos($t, 'pavorreales') !== false) $clean_m2 = '180';
                        elseif (strpos($t, 'mezquite') !== false) $clean_m2 = '220';
                        else $clean_m2 = '450';
                    }
                    $pdf_meta = get_post_meta(get_the_ID(), '_espacio_plano_pdf', true);
                    ?>
                    <div style="border-top: 1px solid rgba(255,255,255,0.12); border-bottom: 1px solid rgba(255,255,255,0.12); padding: 1.25rem 0; margin-bottom: 1.8rem;">
                        <div>
                            <span style="color: #888; display: block; font-size: 0.74rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.2rem;">Superficie</span>
                            <strong style="color: var(--color-accent); font-size: 1.15rem; font-family: var(--font-heading);"><?php echo esc_html($clean_m2); ?> m²</strong>
                        </div>
                    </div>

                    <!-- 2 BOTONES EN CUADROS CON BORDES REDONDEADOS (GRID HORIZONTAL DE 2 COLUMNAS) -->
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1.8rem;">
                        <!-- Cuadro 1: Cotizar -->
                        <button type="button" class="btn-open-quote-modal" data-salon="<?php echo esc_attr(get_the_title()); ?>"
                           style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.35rem 0.6rem 1.15rem; background: #111111; border: 1.5px solid var(--color-accent); border-radius: 1.25rem; text-decoration: none; cursor: pointer; transition: all 0.3s; box-shadow: 0 8px 25px rgba(212,175,55,0.15); position: relative; z-index: 3;"
                           onmouseover="this.style.background='#161616'; this.style.transform='translateY(-3px)';"
                           onmouseout="this.style.background='#111111'; this.style.transform='translateY(0)';">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; margin-bottom:0.5rem;">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="7" y="2.5" width="2.5" height="4" rx="1.25" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <rect x="12.75" y="2.5" width="2.5" height="4" rx="1.25" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <rect x="18.5" y="2.5" width="2.5" height="4" rx="1.25" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <path d="M5 6.5H23C24.1 6.5 25 7.4 25 8.5V21C25 22.1 24.1 23 23 23H5C3.9 23 3 22.1 3 21V8.5C3 7.4 3.9 6.5 5 6.5Z" fill="rgba(212,175,55,0.1)" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <line x1="3" y1="10.5" x2="25" y2="10.5" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <rect x="6.5" y="13" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" stroke-width="1.4"/>
                                    <rect x="10.5" y="13" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" stroke-width="1.4"/>
                                    <rect x="14.5" y="13" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" stroke-width="1.4"/>
                                    <rect x="18.5" y="13" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" stroke-width="1.4"/>
                                    <rect x="6.5" y="17.5" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" stroke-width="1.4"/>
                                    <rect x="10.5" y="17.5" width="2.2" height="2.2" rx="0.5" stroke="var(--color-accent)" stroke-width="1.4"/>
                                    <circle cx="19.5" cy="20.5" r="5.5" fill="#111111" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <path d="M17.2 20.5L18.8 22L22 18.7" stroke="var(--color-accent)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span style="color: rgba(255,255,255,0.85); font-size: 0.74rem; font-weight: 400; letter-spacing: 0.8px; text-transform: uppercase; text-align: center;">Cotizar</span>
                        </button>

                        <!-- Cuadro 3: Libro / Planos -->
                        <?php 
                        $default_plano = get_option('casa_opt_espacios_default_plano');
                        $plano_href = !empty($pdf_meta) ? esc_url($pdf_meta) : (!empty($default_plano) ? esc_url($default_plano) : '');
                        $is_plano_modal = empty($plano_href);
                        ?>
                        <?php if ($is_plano_modal): ?>
                        <button type="button" class="btn-open-quote-modal" data-salon="<?php echo esc_attr(get_the_title()); ?>"
                           style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.35rem 0.6rem 1.15rem; background: #111111; border: 1.2px solid rgba(212,175,55,0.38); border-radius: 1.25rem; text-decoration: none; transition: all 0.3s; position: relative; z-index: 3; cursor: pointer;"
                           onmouseover="this.style.background='#161616'; this.style.transform='translateY(-3px)'; this.style.borderColor='var(--color-accent)';"
                           onmouseout="this.style.background='#111111'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(212,175,55,0.38)';">
                        <?php else: ?>
                        <a href="<?php echo $plano_href; ?>" target="_blank" rel="noopener" download
                           style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.35rem 0.6rem 1.15rem; background: #111111; border: 1.2px solid rgba(212,175,55,0.38); border-radius: 1.25rem; text-decoration: none; transition: all 0.3s; position: relative; z-index: 3;"
                           onmouseover="this.style.background='#161616'; this.style.transform='translateY(-3px)'; this.style.borderColor='var(--color-accent)';"
                           onmouseout="this.style.background='#111111'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(212,175,55,0.38)';">
                        <?php endif; ?>
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; margin-bottom:0.5rem;">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 3C3.5 3 2.5 4.5 2.5 6V22C2.5 23.5 3.5 25 5 25C6.5 25 7.5 23.5 7.5 22V6C7.5 4.5 6.5 3 5 3Z" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <path d="M23 3C21.5 3 20.5 4.5 20.5 6V22C20.5 23.5 21.5 25 23 25C24.5 25 25.5 23.5 25.5 22V6C25.5 4.5 24.5 3 23 3Z" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <path d="M7.5 5.5H20.5V21.5H7.5" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <path d="M10.5 9.5H16.5V14.5H10.5V9.5Z" fill="rgba(212,175,55,0.18)" stroke="var(--color-accent)" stroke-width="1.5"/>
                                    <path d="M16.5 14.5H18.5V18.5H13.5V14.5" stroke="var(--color-accent)" stroke-width="1.5"/>
                                    <path d="M16.5 9.5C17.6 9.5 18.5 10.4 18.5 11.5" stroke="var(--color-accent)" stroke-width="1.4"/>
                                </svg>
                            </span>
                            <span style="color: rgba(255,255,255,0.65); font-size: 0.74rem; font-weight: 300; letter-spacing: 0.8px; text-transform: uppercase; text-align: center;">Planos</span>
                        <?php if ($is_plano_modal): ?>
                        </button>
                        <?php else: ?>
                        </a>
                        <?php endif; ?>
                    </div>

                    <p style="color: #999; font-size: 0.81rem; text-align: center; margin: 0.6rem 0 0 0; font-style: italic; line-height: 1.4;">
                        Consulta la distribución arquitectónica o solicita tu cotización directa
                    </p>
                </div>
            </div>

        </div>

        <!-- SECCIÓN DE RESEÑAS VERIFICADAS DE GOOGLE MAPS PARA EVENTOS Y RECINTOS -->
        <div style="margin-top: clamp(4rem, 7vw, 5.5rem); border-top: 1px solid rgba(255,255,255,0.12); padding-top: clamp(3rem, 5vw, 4rem);">
            <div style="margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span style="color: var(--color-accent); font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block;">Google Maps Verified Reviews</span>
                    <h3 style="color: #fff; font-size: clamp(1.6rem, 3vw, 2.3rem); font-family: var(--font-heading); margin: 0.3rem 0 0 0;">Experiencias en Casa de Piedra</h3>
                </div>
                <div style="display: flex; align-items: center; gap: 0.6rem; background: rgba(255,255,255,0.05); padding: 0.6rem 1.1rem; border-radius: 999px; border: 1px solid rgba(212,175,55,0.35);">
                    <span style="color: #f39c12; font-size: 1.1rem;">★★★★★</span>
                    <strong style="color: #fff; font-size: 0.95rem;">4.9 / 5.0</strong>
                    <span style="color: #888; font-size: 0.85rem;">(Eventos & Salones)</span>
                </div>
            </div>

            <?php
            $default_google_url = get_option('casa_opt_google_maps_link', 'https://www.google.com/maps/place/Sato+Casa+de+Piedra/@21.1596493,-101.7019348,1682m/data=!3m2!1e3!5s0x842bbf530842ac4b:0x4642591264eb2eec!4m8!3m7!1s0x842bbf53719c9f9d:0x1cfaf89360074060!8m2!3d21.1596493!4d-101.6993545!9m1!1b1!16s%2Fg%2F11bw62cb4y?entry=ttu');
            $esp_rev1_url = get_option('casa_opt_espacios_rev1_url', $default_google_url);
            $esp_rev2_url = get_option('casa_opt_espacios_rev2_url', $default_google_url);
            $esp_rev3_url = get_option('casa_opt_espacios_rev3_url', $default_google_url);
            ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" style="margin-bottom: 2rem;">
                <a href="<?php echo esc_url($esp_rev1_url); ?>" class="google-review-card" target="_blank" rel="noopener noreferrer" style="padding: 1.8rem; border-radius: 1.2rem; background: #111111; border: 1px solid rgba(212,175,55,0.28); display: flex; flex-direction: column; justify-content: space-between; text-decoration: none; transition: all 0.3s; box-shadow: 0 15px 35px rgba(0,0,0,0.65);" onmouseover="this.style.borderColor='var(--color-accent)'; this.style.transform='translateY(-3px)';" onmouseout="this.style.borderColor='rgba(212,175,55,0.28)'; this.style.transform='translateY(0)';">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                            <div style="color: var(--color-accent); font-size: 1rem;">★★★★★</div>
                            <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                        </div>
                        <p style="color: #ddd; font-size: 0.96rem; line-height: 1.65; font-style: italic; margin-bottom: 1.4rem;">
                            "Celebramos nuestra boda en este recinto de Casa de Piedra y fue mágico. La arquitectura histórica, los jardines y la logística del salón superaron todas nuestras expectativas."
                        </p>
                    </div>
                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.8rem; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: #fff; font-size: 0.92rem;">Boda & Recepción • Cliente Verificado</strong>
                        <span style="color: #888; font-size: 0.78rem;">Google Maps</span>
                    </div>
                </a>

                <a href="<?php echo esc_url($esp_rev2_url); ?>" class="google-review-card" target="_blank" rel="noopener noreferrer" style="padding: 1.8rem; border-radius: 1.2rem; background: #111111; border: 1px solid rgba(212,175,55,0.28); display: flex; flex-direction: column; justify-content: space-between; text-decoration: none; transition: all 0.3s; box-shadow: 0 15px 35px rgba(0,0,0,0.65);" onmouseover="this.style.borderColor='var(--color-accent)'; this.style.transform='translateY(-3px)';" onmouseout="this.style.borderColor='rgba(212,175,55,0.28)'; this.style.transform='translateY(0)';">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                            <div style="color: var(--color-accent); font-size: 1rem;">★★★★★</div>
                            <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                        </div>
                        <p style="color: #ddd; font-size: 0.96rem; line-height: 1.65; font-style: italic; margin-bottom: 1.4rem;">
                            "Organizamos un congreso directivo en el Salón Principal y las instalaciones están impecables. Acústica de primera, seguridad privada y valet parking excelente."
                        </p>
                    </div>
                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.8rem; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: #fff; font-size: 0.92rem;">Evento Corporativo</strong>
                        <span style="color: #888; font-size: 0.78rem;">Google Maps</span>
                    </div>
                </a>

                <a href="<?php echo esc_url($esp_rev3_url); ?>" class="google-review-card" target="_blank" rel="noopener noreferrer" style="padding: 1.8rem; border-radius: 1.2rem; background: #111111; border: 1px solid rgba(212,175,55,0.28); display: flex; flex-direction: column; justify-content: space-between; text-decoration: none; transition: all 0.3s; box-shadow: 0 15px 35px rgba(0,0,0,0.65);" onmouseover="this.style.borderColor='var(--color-accent)'; this.style.transform='translateY(-3px)';" onmouseout="this.style.borderColor='rgba(212,175,55,0.28)'; this.style.transform='translateY(0)';">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                            <div style="color: var(--color-accent); font-size: 1rem;">★★★★★</div>
                            <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                        </div>
                        <p style="color: #ddd; font-size: 0.96rem; line-height: 1.65; font-style: italic; margin-bottom: 1.4rem;">
                            "El lugar más elegante de León por mucho. Cada rincón arquitectónico es hermoso para fotos y la atención del equipo de eventos es sumamente profesional."
                        </p>
                    </div>
                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.8rem; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: #fff; font-size: 0.92rem;">Celebración Exclusiva</strong>
                        <span style="color: #888; font-size: 0.78rem;">Google Maps</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- 3. EXPLORA OTROS ESPACIOS (SECCIÓN AMPLIADA Y MAJESTUOSA) -->
        <div style="margin-top: clamp(4.5rem, 8vw, 6.5rem); border-top: 1px solid rgba(212,175,55,0.25); padding-top: clamp(3.5rem, 6vw, 4.5rem);">
            <div style="display: flex; flex-direction: column; sm:flex-row; justify-content: space-between; align-items: flex-start; sm:align-items: flex-end; margin-bottom: 2.8rem; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <span style="color: var(--color-accent); font-size: 0.82rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block;">Más Opciones de Celebración</span>
                    <h3 style="color: #fff; font-size: clamp(1.7rem, 3.5vw, 2.5rem); font-family: var(--font-heading); margin: 0.4rem 0 0 0;">Explora otros Escenarios</h3>
                </div>
                <a href="<?php echo esc_url(get_post_type_archive_link('espacios')); ?>" style="color: var(--color-accent); font-size: 0.95rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.3rem; border: 1px solid rgba(212,175,55,0.4); border-radius: 999px; transition: all 0.3s;" onmouseover="this.style.background='rgba(212,175,55,0.18)';" onmouseout="this.style.background='transparent';">
                    Ver todos los salones →
                </a>
            </div>

            <?php
            $other_query = new WP_Query(array('post_type' => 'espacios', 'posts_per_page' => 3, 'post__not_in' => array(get_the_ID()), 'orderby' => 'menu_order', 'order' => 'ASC'));
            if ($other_query->have_posts()) :
            ?>
                <!-- Tarjetas ampliadas 100% responsivas (1 col en móvil, 2 en tablet, 3 en desktop) -->
                <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 1.8rem;" class="other-spaces-grid">
                    <style>
                        @media (min-width: 640px) {
                            .other-spaces-grid { grid-template-columns: repeat(2, 1fr) !important; }
                        }
                        @media (min-width: 1024px) {
                            .other-spaces-grid { grid-template-columns: repeat(3, 1fr) !important; gap: 2.5rem !important; }
                        }
                        .other-space-card {
                            display: block;
                            text-decoration: none;
                            border-radius: 1.4rem;
                            overflow: hidden;
                            background: #111111;
                            border: 1px solid rgba(255,255,255,0.14);
                            transition: all 0.45s cubic-bezier(0.25, 1, 0.5, 1);
                        }
                        .other-space-card:hover {
                            border-color: var(--color-accent);
                            transform: translateY(-8px);
                            box-shadow: 0 25px 50px rgba(212, 175, 55, 0.25);
                        }
                        .other-space-img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            transition: transform 0.65s cubic-bezier(0.25, 1, 0.5, 1);
                        }
                        .other-space-card:hover .other-space-img {
                            transform: scale(1.08);
                        }
                    </style>

                    <?php while ($other_query->have_posts()) : $other_query->the_post(); 
                        $o_cap = get_post_meta(get_the_ID(), '_espacio_capacidad', true);
                        $clean_o_cap = !empty($o_cap) ? trim(str_ireplace(array('personas', 'px', 'hasta'), '', $o_cap)) : '';
                    ?>
                        <a href="<?php the_permalink(); ?>" class="other-space-card">
                            <div style="height: clamp(240px, 30vw, 280px); position: relative; overflow: hidden;">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="other-space-img" />
                                <?php else : ?>
                                    <div style="background: #181818; width: 100%; height: 100%;"></div>
                                <?php endif; ?>
                                <?php if (!empty($clean_o_cap)) : ?>
                                    <span style="position: absolute; bottom: 16px; left: 16px; background: rgba(10,10,10,0.9); border: 1px solid var(--color-accent); color: var(--color-accent); padding: 0.4rem 1rem; border-radius: 999px; font-size: 0.8rem; font-weight: 600; letter-spacing: 1px; box-shadow: 0 6px 15px rgba(0,0,0,0.85);">
                                        Hasta <?php echo esc_html($clean_o_cap); ?> Personas
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div style="padding: 1.7rem 1.9rem;">
                                <h4 style="color: #fff; font-size: 1.4rem; font-family: var(--font-heading); margin: 0 0 0.5rem 0; line-height: 1.25;">
                                    <?php the_title(); ?>
                                </h4>
                                <span style="color: var(--color-accent); font-size: 0.9rem; font-weight: 600; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 0.4rem;">
                                    Conocer Salón →
                                </span>
                            </div>
                        </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php endwhile; endif; ?>
<?php get_footer(); ?>
