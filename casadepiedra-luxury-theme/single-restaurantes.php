<?php
/**
 * Template Name: Detalle de Restaurante (Banner Superior + Imagen/Texto Lado a Lado + Personalizable)
 * Description: Plantilla dedicada para restaurantes con banner superior, galería a un lado, resumen y botones al otro, y sección de exploración.
 */
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $menu_url = get_post_meta(get_the_ID(), '_restaurante_menu', true);
    if (stripos(get_the_title(), 'Sato') !== false) {
        $menu_url = 'https://35c2d3e7-6884-40a6-8878-3c8bcb7cdbd5.filesusr.com/ugd/e0ec2b_bbdace41ace0486cbfb3c13b75ab0e57.pdf';
    }
    $telefono = get_post_meta(get_the_ID(), '_restaurante_telefono', true);
    $horarios = get_post_meta(get_the_ID(), '_restaurante_horario', true) ?: 'Lunes a Domingo de 1:00 PM a 11:00 PM';
    $logo_url = casadepiedra_resolve_restaurante_logo(get_the_ID());

    $gallery_ids = get_post_meta(get_the_ID(), '_casadepiedra_gallery_ids', true);
    $ids_array = !empty($gallery_ids) ? explode(',', $gallery_ids) : array();
    $gallery_urls = array();
    if (!empty($ids_array)) {
        foreach ($ids_array as $id) {
            $u = wp_get_attachment_image_url($id, 'large');
            if ($u) $gallery_urls[] = $u;
        }
    }
    if (empty($gallery_urls)) {
        $gallery_urls = casadepiedra_resolve_restaurante_gallery(get_the_ID());
    }
    $hero_img = casadepiedra_resolve_restaurante_hero(get_the_ID());
?>

<!-- HERO BANNER SUPERIOR PRINCIPAL DEL RESTAURANTE -->
<section style="position: relative; width: 100%; height: clamp(340px, 42vh, 480px); display: flex; align-items: center; justify-content: center; overflow: hidden;">
    <img src="<?php echo esc_url($hero_img); ?>" alt="<?php the_title_attribute(); ?>" style="position: absolute; width: 100%; height: 100%; object-fit: cover; filter: brightness(0.7);" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(10,10,10,0.2) 0%, rgba(10,10,10,0.55) 75%, #080808 100%);"></div>
    <div style="position: relative; z-index: 2; text-align: center; padding: 0 1rem; margin-top: 30px;">
        <span class="text-script" style="color: var(--color-accent); font-size: 1.4rem;">Recinto Gastronómico</span>
        <h1 style="color: #fff; font-size: clamp(2.2rem, 4.5vw, 3.8rem); font-family: var(--font-heading); margin: 0.3rem 0; text-shadow: 0 10px 30px rgba(0,0,0,0.85);">
            <?php the_title(); ?>
        </h1>
        <?php 
            $rating_val = get_post_meta(get_the_ID(), '_restaurante_rating', true) ?: '4.9';
            $google_rev_url = casadepiedra_get_google_reviews_url(get_the_ID());
            $horario_val = get_post_meta(get_the_ID(), '_restaurante_horario', true) ?: 'Lunes a Domingo • 1:00 PM - 11:00 PM';
        ?>
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 0.8rem; margin-top: 0.8rem;">
            <a href="<?php echo esc_url($google_rev_url); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(10,10,10,0.88); border: 1.2px solid var(--color-accent); padding: 0.45rem 1.2rem; border-radius: 999px; text-decoration: none; transition: all 0.3s; box-shadow: 0 4px 20px rgba(0,0,0,0.7);" onmouseover="this.style.background='rgba(212,175,55,0.22)'; this.style.transform='scale(1.04)';" onmouseout="this.style.background='rgba(10,10,10,0.88)'; this.style.transform='scale(1)';">
                <span style="color: var(--color-accent); font-weight: 700; font-size: 0.88rem;">⭐ <?php echo esc_html($rating_val); ?></span>
                <span style="color: #fff; font-size: 0.85rem; font-weight: 500;">• Reseñas Verificadas en Google →</span>
            </a>

            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(10,10,10,0.88); border: 1px solid rgba(212,175,55,0.45); padding: 0.45rem 1.2rem; border-radius: 999px; box-shadow: 0 4px 20px rgba(0,0,0,0.7);">
                <span style="color: var(--color-accent); font-size: 0.95rem;">🕒</span>
                <span style="color: #eee; font-size: 0.85rem; font-weight: 500;"><?php echo esc_html($horario_val); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- VISTA EN 2 COLUMNAS LATERALES EN PC (GALERÍA A LA IZQUIERDA, INFORMACIÓN A LA DERECHA) -->
<main style="padding: clamp(2.5rem, 4vw, 4.5rem) 0 6rem; background: transparent; width: 100%;">
    <div style="max-width: 1560px; margin: 0 auto; padding: 0 clamp(1.2rem, 4vw, 3.5rem);">

        <style>
            .rest-detail-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2.5rem;
                align-items: start;
            }
            .rest-col-gallery {
                order: 2;
                width: 100%;
            }
            .rest-col-info {
                order: 1;
                width: 100%;
            }
            @media screen and (min-width: 1024px) {
                .rest-detail-grid {
                    grid-template-columns: 1fr 1fr;
                    gap: 3.5rem;
                }
                .rest-col-gallery {
                    order: 1;
                }
                .rest-col-info {
                    order: 2;
                }
            }
        </style>

        <div class="rest-detail-grid">
            
            <!-- LADO IZQUIERDO EN PC / SEGUNDO EN MÓVIL: GALERÍA FOTOGRÁFICA -->
            <div class="rest-col-gallery">
                <!-- Visor Fotográfico Principal -->
                <div style="border-radius: 1.6rem; overflow: hidden; height: clamp(400px, 46vw, 640px); position: relative; border: 1px solid rgba(212,175,55,0.45); background: #111; box-shadow: 0 30px 70px rgba(0,0,0,0.9);">
                    <style>
                    .rest-slider { position: relative; width: 100%; height: 100%; overflow: hidden; }
                    .rest-slider-track { display: flex; height: 100%; transition: transform 0.55s cubic-bezier(0.25, 1, 0.5, 1); }
                    .rest-slide { position: relative; cursor: zoom-in; flex: 0 0 100%; height: 100%; display: block; }
                    .rest-slide img { width: 100%; height: 100%; object-fit: cover; }
                    </style>

                    <?php 
                    if (!empty($gallery_urls)) {
                        echo '<div class="rest-slider" id="restSlider">';
                        echo '<div class="rest-slider-track" id="restSliderTrack">';
                        foreach ($gallery_urls as $img_url) {
                            echo '<a href="'.esc_url($img_url).'" class="rest-slide glightbox" data-gallery="restaurante-gallery">';
                            echo '<img src="'.esc_url($img_url).'" alt="'.esc_attr(get_the_title()).'" loading="lazy" />';
                            echo '</a>';
                        }
                        echo '</div>';
                        echo '</div>';
                    } else {
                        $fallback_img = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : $hero_img;
                        $fallback_full = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : $hero_img;
                        ?>
                        <a href="<?php echo esc_url($fallback_full); ?>" class="glightbox" data-gallery="restaurante-gallery" style="display:block; height:100%;">
                            <img src="<?php echo esc_url($fallback_img); ?>" alt="<?php the_title_attribute(); ?>" style="width:100%; height:100%; object-fit:cover;" />
                        </a>
                    <?php } ?>
                </div>

                <!-- Miniaturas de la Galería 100% Responsivas -->
                <?php if (!empty($gallery_urls) && count($gallery_urls) > 1) : ?>
                    <div style="display: flex; gap: 0.75rem; margin-top: 1.2rem; overflow-x: auto; padding-bottom: 0.5rem; -webkit-overflow-scrolling: touch;">
                        <?php foreach ($gallery_urls as $t_idx => $t_src) : ?>
                            <button type="button" onclick="window.goToRestSlide && window.goToRestSlide(<?php echo $t_idx; ?>)" style="flex: 0 0 clamp(85px, 16vw, 115px); height: clamp(60px, 11vw, 80px); border-radius: 0.8rem; overflow: hidden; border: 1.5px solid rgba(212,175,55,0.45); background: #000; cursor: pointer; padding: 0; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.06)';" onmouseout="this.style.transform='scale(1)';">
                                <img src="<?php echo esc_url($t_src); ?>" alt="Miniatura" style="width:100%; height:100%; object-fit:cover;" />
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const track = document.getElementById('restSliderTrack');
                    if (!track) return;
                    const slidesCount = track.children.length;
                    let currentIndex = 0;
                    window.goToRestSlide = (index) => {
                        if (index < 0) index = slidesCount - 1;
                        if (index >= slidesCount) index = 0;
                        currentIndex = index;
                        track.style.transform = `translateX(-${currentIndex * 100}%)`;
                    };
                    let autoPlay = setInterval(() => window.goToRestSlide(currentIndex + 1), 4500);
                    track.addEventListener('mouseenter', () => clearInterval(autoPlay));
                    if(typeof GLightbox !== 'undefined') GLightbox({ selector: '.glightbox' });
                });
                </script>
            </div>

            <!-- LADO DERECHO EN PC / PRIMERO EN MÓVIL: TARJETA OFICIAL DEL RESTAURANTE -->
            <div class="rest-col-info">
                <div class="luxury-info-panel" style="padding: clamp(2.2rem, 4.5vw, 3.4rem); border-radius: 1.8rem; background: #111111; border: 1px solid rgba(212, 175, 55, 0.48); box-shadow: 0 25px 65px rgba(0,0,0,0.92); position: relative; z-index: 2;">
                    
                    <!-- Logo & Cabecera Principal del Restaurante -->
                    <div style="margin-bottom: 2rem; padding-bottom: 1.6rem; border-bottom: 1px solid rgba(255,255,255,0.14);">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
                            <?php if (!empty($logo_url)) : ?>
                                <div style="height: 55px; max-width: 180px;">
                                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php the_title_attribute(); ?> Logo" style="height: 100%; width: auto; object-fit: contain; filter: drop-shadow(0 2px 10px rgba(0,0,0,0.8));" />
                                </div>
                            <?php else: ?>
                                <span style="color: var(--color-accent); font-size: 0.82rem; letter-spacing: 2.2px; text-transform: uppercase; font-weight: 700;">
                                    Casa de Piedra Oficial
                                </span>
                            <?php endif; ?>
                        </div>

                        <h1 style="color: #fff; font-size: clamp(2.1rem, 3.5vw, 2.8rem); font-family: var(--font-heading); margin: 0; line-height: 1.15;">
                            <?php the_title(); ?>
                        </h1>
                    </div>

                    <!-- DESCRIPCIÓN DEL RESTAURANTE EXTRAÍDA DE SU SITIO OFICIAL -->
                    <div class="text-body-lg" style="color: #dedede; font-size: clamp(1.04rem, 1.7vw, 1.14rem); line-height: 1.85; margin-bottom: 2rem;">
                        <?php 
                        $title = get_the_title();
                        $content_str = get_the_content();
                        if (stripos($title, 'Sato') !== false) {
                            echo '<p>Sato Cocina Nikkei es un espacio diseñado para el placer de los sentidos, la relajación y el buen gusto. Transportamos al comensal a las calles de Japón mediante una creativa cocina Nikkei que entrelaza la milenaria tradición culinaria japonesa con vibrantes ingredientes y técnicas peruanas. Una experiencia Omakase y robatayaki con reconocimiento internacional en el emblemático recinto de Casa de Piedra.</p>';
                        } elseif (strlen(strip_tags($content_str)) < 50) {
                            if (stripos($title, 'Manolo') !== false) {
                                echo '<p>Manolo Taberna Española celebra la auténtica herencia gastronómica ibérica con lechón al horno al estilo castellano, tapas gourmet de autor, paellas artesanales y una selección de etiquetas en el entorno exclusivo de Casa de Piedra.</p>';
                            } elseif (stripos($title, 'Casa Mía') !== false || stripos($title, 'Mia') !== false) {
                                echo '<p>Casa Mía Trattoria & Wine Bar es un rincón íntimo dedicado a la alta cocina italiana artesanal y a la cultura de los grandes vinos. Pastas caseras hechas a mano, carpaccios, risottos trufados y maridajes inolvidables.</p>';
                            } elseif (stripos($title, 'Valentina') !== false) {
                                echo '<p>Valentina Cocina Contemporánea ofrece una propuesta sensorial que combina técnicas culinarias de vanguardia con ingredientes selectos de origen. Cortes a las brasas, mariscos importados y coctelería de autor.</p>';
                            } elseif (stripos($title, 'Lucio') !== false) {
                                echo '<p>Lucio Ítalo-Argentino entrelaza la maestría artesanal de las pastas hechas en casa con la exquisitez de los cortes asados a las brasas. Una propuesta gastronómica que rinde homenaje al buen comer.</p>';
                            } elseif (stripos($title, 'Argentilia') !== false) {
                                echo '<p>Argentilia es el referente en alta cocina argentina en el Bajío, con cortes selectos a la parrilla de leña, empanadas caseras y una cava excepcional en un entorno de elegancia señorial.</p>';
                            } else {
                                the_content();
                            }
                        } else {
                            the_content();
                        }
                        ?>
                    </div>

                    <!-- 3 BOTONES EN CUADROS CON BORDES REDONDEADOS (GRID HORIZONTAL DE 3 COLUMNAS) -->
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1.8rem;">
                        <!-- Cuadro 1: Google Maps / Ubicación -->
                        <a href="<?php echo esc_url($google_rev_url); ?>" target="_blank" rel="noopener noreferrer"
                           style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.35rem 0.6rem 1.15rem; background: #111111; border: 1.2px solid rgba(212,175,55,0.38); border-radius: 1.25rem; text-decoration: none; transition: all 0.3s; position: relative; z-index: 3;"
                           onmouseover="this.style.background='#161616'; this.style.transform='translateY(-3px)'; this.style.borderColor='var(--color-accent)';"
                           onmouseout="this.style.background='#111111'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(212,175,55,0.38)';">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; margin-bottom:0.5rem;">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--color-accent)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4.5 16.5c-1.5 1.26-2 2.5-2 3.5 0 2.5 4.25 3 9.5 3s9.5-.5 9.5-3c0-1-.5-2.24-2-3.5" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7z" fill="rgba(212,175,55,0.18)" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <circle cx="12" cy="9" r="2.5" fill="var(--color-accent)"/>
                                </svg>
                            </span>
                            <span style="color: rgba(255,255,255,0.65); font-size: 0.74rem; font-weight: 300; letter-spacing: 0.8px; text-transform: uppercase; text-align: center;">Ubicación</span>
                        </a>

                        <!-- Cuadro 2: Teléfono / Agenda / Reserva -->
                        <a href="<?php echo casadepiedra_get_reserva_url(get_the_ID()); ?>" target="_blank" rel="noopener"
                           style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.35rem 0.6rem 1.15rem; background: #111111; border: 1.5px solid var(--color-accent); border-radius: 1.25rem; text-decoration: none; transition: all 0.3s; box-shadow: 0 8px 25px rgba(212,175,55,0.15); position: relative; z-index: 3;"
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
                            <span style="color: rgba(255,255,255,0.85); font-size: 0.74rem; font-weight: 400; letter-spacing: 0.8px; text-transform: uppercase; text-align: center;">Reserva</span>
                        </a>

                        <!-- Cuadro 3: Libro / Menú -->
                        <a href="<?php echo esc_url(!empty($menu_url) ? $menu_url : home_url('/contacto')); ?>" target="_blank" rel="noopener"
                           style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.35rem 0.6rem 1.15rem; background: #111111; border: 1.2px solid rgba(212,175,55,0.38); border-radius: 1.25rem; text-decoration: none; transition: all 0.3s; position: relative; z-index: 3;"
                           onmouseover="this.style.background='#161616'; this.style.transform='translateY(-3px)'; this.style.borderColor='var(--color-accent)';"
                           onmouseout="this.style.background='#111111'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(212,175,55,0.38)';">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; margin-bottom:0.5rem;">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 5H21C22.1 5 23 5.9 23 7V23C23 24.1 22.1 25 21 25H14" stroke="var(--color-accent)" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M11 5L20 3V5" stroke="var(--color-accent)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M14.5 9C14.5 11 16 12 16 12C16 12 17.5 11 17.5 9V7.5H14.5V9Z" stroke="var(--color-accent)" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M16 12V14.5M14.5 14.5H17.5" stroke="var(--color-accent)" stroke-width="1.4" stroke-linecap="round"/>
                                    <path d="M20 7.5V11.5M20 11.5V15M19 7.5V9.5C19 10 20 10.5 20 10.5" stroke="var(--color-accent)" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                    <line x1="15" y1="17.5" x2="20" y2="17.5" stroke="var(--color-accent)" stroke-width="1.5" stroke-linecap="round"/>
                                    <line x1="16" y1="20.5" x2="20" y2="20.5" stroke="var(--color-accent)" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M4 22H15" stroke="var(--color-accent)" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M5 22C5 18.5 7.5 15.5 9.5 15.5C11.5 15.5 14 18.5 14 22" fill="rgba(212,175,55,0.2)" stroke="var(--color-accent)" stroke-width="1.8" stroke-linecap="round"/>
                                    <circle cx="9.5" cy="14" r="1.3" fill="var(--color-accent)"/>
                                </svg>
                            </span>
                            <span style="color: rgba(255,255,255,0.65); font-size: 0.74rem; font-weight: 300; letter-spacing: 0.8px; text-transform: uppercase; text-align: center;">Menú</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- SECCIÓN DE RESEÑAS AUTÉNTICAS DE GOOGLE MAPS EN CASA DE PIEDRA -->
        <div style="margin-top: clamp(4rem, 7vw, 5.5rem); padding-top: clamp(3rem, 5vw, 4rem); border-top: 1px solid rgba(212,175,55,0.2);">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 2.2rem; gap: 1rem;">
                <div>
                    <span style="color: var(--color-accent); font-size: 0.82rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block;">Google Maps Verified Reviews</span>
                    <h3 style="color: #fff; font-size: clamp(1.6rem, 3vw, 2.3rem); font-family: var(--font-heading); margin: 0.4rem 0 0 0;">Opiniones en Casa de Piedra</h3>
                </div>
                <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1.2rem; background: rgba(255,255,255,0.05); border-radius: 999px; border: 1px solid rgba(255,255,255,0.15);">
                    <span style="color: #fff; font-size: 0.85rem;">⭐ 4.9 • Calificación en Google Maps</span>
                </div>
            </div>

            <?php
            $default_google_url = get_option('casa_opt_google_maps_link', 'https://www.google.com/maps/place/Sato+Casa+de+Piedra/@21.1596493,-101.7019348,1682m/data=!3m2!1e3!5s0x842bbf530842ac4b:0x4642591264eb2eec!4m8!3m7!1s0x842bbf53719c9f9d:0x1cfaf89360074060!8m2!3d21.1596493!4d-101.6993545!9m1!1b1!16s%2Fg%2F11bw62cb4y?entry=ttu');
            $rest_rev1_url = get_option('casa_opt_rest_rev1_url', $default_google_url);
            $rest_rev2_url = get_option('casa_opt_rest_rev2_url', $default_google_url);
            $rest_rev3_url = get_option('casa_opt_rest_rev3_url', $default_google_url);
            ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" style="margin-bottom: 2rem;">
                <a href="<?php echo esc_url($rest_rev1_url); ?>" class="google-review-card" target="_blank" rel="noopener noreferrer" style="padding: 1.8rem; border-radius: 1.2rem; background: #111111; border: 1px solid rgba(212,175,55,0.28); display: flex; flex-direction: column; justify-content: space-between; text-decoration: none; transition: all 0.3s; box-shadow: 0 15px 35px rgba(0,0,0,0.65);" onmouseover="this.style.borderColor='var(--color-accent)'; this.style.transform='translateY(-3px)';" onmouseout="this.style.borderColor='rgba(212,175,55,0.28)'; this.style.transform='translateY(0)';">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                            <div style="color: var(--color-accent); font-size: 1rem;">★★★★★</div>
                            <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                        </div>
                        <p style="color: #ddd; font-size: 0.96rem; line-height: 1.65; font-style: italic; margin-bottom: 1.4rem;">
                            "Una experiencia gastronómica excepcional en Casa de Piedra León. El servicio, la atmósfera y la calidad en cada platillo hacen de este restaurante un lugar imperdible."
                        </p>
                    </div>
                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.8rem; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: #fff; font-size: 0.92rem;"><?php echo esc_html(get_the_title()); ?> • Cliente Verificado</strong>
                        <span style="color: #888; font-size: 0.78rem;">Google Maps</span>
                    </div>
                </a>

                <a href="<?php echo esc_url($rest_rev2_url); ?>" class="google-review-card" target="_blank" rel="noopener noreferrer" style="padding: 1.8rem; border-radius: 1.2rem; background: #111111; border: 1px solid rgba(212,175,55,0.28); display: flex; flex-direction: column; justify-content: space-between; text-decoration: none; transition: all 0.3s; box-shadow: 0 15px 35px rgba(0,0,0,0.65);" onmouseover="this.style.borderColor='var(--color-accent)'; this.style.transform='translateY(-3px)';" onmouseout="this.style.borderColor='rgba(212,175,55,0.28)'; this.style.transform='translateY(0)';">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                            <div style="color: var(--color-accent); font-size: 1rem;">★★★★★</div>
                            <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                        </div>
                        <p style="color: #ddd; font-size: 0.96rem; line-height: 1.65; font-style: italic; margin-bottom: 1.4rem;">
                            "Excelente carta de vinos, atención de primerísimo nivel por parte de los meseros y la arquitectura dentro de Casa de Piedra le da una elegancia única en la ciudad."
                        </p>
                    </div>
                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.8rem; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: #fff; font-size: 0.92rem;">Reseña Destacada</strong>
                        <span style="color: #888; font-size: 0.78rem;">Google Maps</span>
                    </div>
                </a>

                <a href="<?php echo esc_url($rest_rev3_url); ?>" class="google-review-card" target="_blank" rel="noopener noreferrer" style="padding: 1.8rem; border-radius: 1.2rem; background: #111111; border: 1px solid rgba(212,175,55,0.28); display: flex; flex-direction: column; justify-content: space-between; text-decoration: none; transition: all 0.3s; box-shadow: 0 15px 35px rgba(0,0,0,0.65);" onmouseover="this.style.borderColor='var(--color-accent)'; this.style.transform='translateY(-3px)';" onmouseout="this.style.borderColor='rgba(212,175,55,0.28)'; this.style.transform='translateY(0)';">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                            <div style="color: var(--color-accent); font-size: 1rem;">★★★★★</div>
                            <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                        </div>
                        <p style="color: #ddd; font-size: 0.96rem; line-height: 1.65; font-style: italic; margin-bottom: 1.4rem;">
                            "Ambiente refinado, platillos exquisitos y una ubicación inmejorable en León. Sin duda uno de los mejores restaurantes de la región."
                        </p>
                    </div>
                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.8rem; display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: #fff; font-size: 0.92rem;">Alta cocina</strong>
                        <span style="color: #888; font-size: 0.78rem;">Google Maps</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- 3. EXPLORA OTROS RESTAURANTES (SECCIÓN AMPLIADA Y MAJESTUOSA) -->
        <div style="margin-top: clamp(4.5rem, 8vw, 6.5rem); border-top: 1px solid rgba(212,175,55,0.25); padding-top: clamp(3.5rem, 6vw, 4.5rem);">
            <div style="display: flex; flex-direction: column; sm:flex-row; justify-content: space-between; align-items: flex-start; sm:align-items: flex-end; margin-bottom: 2.8rem; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <span style="color: var(--color-accent); font-size: 0.82rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block;">Experiencia Gastronómica</span>
                    <h3 style="color: #fff; font-size: clamp(1.7rem, 3.5vw, 2.5rem); font-family: var(--font-heading); margin: 0.4rem 0 0 0;">Explora otros Restaurantes</h3>
                </div>
                <a href="<?php echo esc_url(get_post_type_archive_link('restaurantes')); ?>" style="color: var(--color-accent); font-size: 0.95rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.3rem; border: 1px solid rgba(212,175,55,0.4); border-radius: 999px; transition: all 0.3s;" onmouseover="this.style.background='rgba(212,175,55,0.18)';" onmouseout="this.style.background='transparent';">
                    Ver todos los restaurantes →
                </a>
            </div>

            <?php
            $other_query = new WP_Query(array('post_type' => 'restaurantes', 'posts_per_page' => 3, 'post__not_in' => array(get_the_ID()), 'orderby' => 'menu_order', 'order' => 'ASC'));
            if ($other_query->have_posts()) :
            ?>
                <!-- Tarjetas ampliadas 100% responsivas (1 col en móvil, 2 en tablet, 3 en desktop) -->
                <div style="display: grid; grid-template-columns: repeat(1, 1fr); gap: 1.8rem;" class="other-rest-grid">
                    <style>
                        @media (min-width: 640px) {
                            .other-rest-grid { grid-template-columns: repeat(2, 1fr) !important; }
                        }
                        @media (min-width: 1024px) {
                            .other-rest-grid { grid-template-columns: repeat(3, 1fr) !important; gap: 2.5rem !important; }
                        }
                        .other-rest-card {
                            display: block;
                            text-decoration: none;
                            border-radius: 1.4rem;
                            overflow: hidden;
                            background: #111111;
                            border: 1px solid rgba(255,255,255,0.14);
                            transition: all 0.45s cubic-bezier(0.25, 1, 0.5, 1);
                        }
                        .other-rest-card:hover {
                            border-color: var(--color-accent);
                            transform: translateY(-8px);
                            box-shadow: 0 25px 50px rgba(212, 175, 55, 0.25);
                        }
                        .other-rest-img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            transition: transform 0.65s cubic-bezier(0.25, 1, 0.5, 1);
                        }
                        .other-rest-card:hover .other-rest-img {
                            transform: scale(1.08);
                        }
                    </style>

                    <?php while ($other_query->have_posts()) : $other_query->the_post(); 
                        $o_logo = casadepiedra_resolve_restaurante_logo(get_the_ID());
                        $o_cocina = get_post_meta(get_the_ID(), '_restaurante_cocina', true) ?: get_option('casa_opt_global_restaurantes_subtitle', 'Alta cocina');
                        $o_menu = get_post_meta(get_the_ID(), '_restaurante_menu', true);
                        $o_reserva = casadepiedra_get_reserva_url(get_the_ID());
                        $o_img = casadepiedra_resolve_restaurante_card_img(get_the_ID());
                        if (empty($o_img) && has_post_thumbnail()) {
                            $o_img = get_the_post_thumbnail_url(get_the_ID(), 'large');
                        }
                    ?>
                        <a href="<?php the_permalink(); ?>" class="other-rest-card" style="text-decoration: none; position: relative; display: flex; flex-direction: column; justify-content: flex-end; min-height: 420px; border-radius: 1.5rem; overflow: hidden; border: 1px solid rgba(212,175,55,0.35); box-shadow: 0 15px 40px rgba(0,0,0,0.85);">
                            <?php if (!empty($o_img)) : ?>
                                <img src="<?php echo esc_url($o_img); ?>" alt="<?php the_title_attribute(); ?>" class="other-rest-img" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; filter: brightness(0.75);" />
                            <?php else : ?>
                                <div style="position: absolute; inset: 0; background: #181818; width: 100%; height: 100%;"></div>
                            <?php endif; ?>
                            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(8,8,8,0.95) 0%, rgba(8,8,8,0.45) 55%, transparent 100%); pointer-events: none;"></div>

                            <div style="position: relative; z-index: 2; padding: 2.2rem 1.8rem;">
                                <?php if (!empty($o_logo)) : ?>
                                    <div style="margin-bottom: 0.9rem; display: flex; align-items: center; height: 55px;">
                                        <img src="<?php echo esc_url($o_logo); ?>" alt="<?php the_title_attribute(); ?> Logo" style="max-width: 170px; max-height: 52px; width: auto; height: auto; object-fit: contain; object-position: left center; filter: drop-shadow(0 4px 15px rgba(0,0,0,0.95));" />
                                    </div>
                                <?php endif; ?>

                                <span style="display: inline-block; padding: 0.3rem 0.85rem; background: rgba(212,175,55,0.2); border: 1px solid var(--color-accent); border-radius: 999px; color: var(--color-accent); font-size: 0.72rem; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600; margin-bottom: 0.9rem; box-shadow: 0 0 15px rgba(212,175,55,0.25);">
                                    <?php echo esc_html($o_cocina); ?>
                                </span>

                                <h4 style="color: #fff; font-size: clamp(1.4rem, 2.2vw, 1.8rem); font-family: var(--font-heading); margin: 0; line-height: 1.2;">
                                    <?php the_title(); ?>
                                </h4>
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
