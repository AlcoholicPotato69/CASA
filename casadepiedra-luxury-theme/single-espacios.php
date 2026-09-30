<?php
/**
 * Template Name: Detalle de Espacio (Banner Superior + Imagen/Texto Lado a Lado 100% Responsivo)
 * Description: Plantilla con banner superior a todo lo ancho con el nombre del espacio, y disposición de texto a un lado e imagen al otro totalmente responsiva.
 */
get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $eid = get_the_ID();
    $clean_cap = function_exists('casa_espacio_capacidad') ? casa_espacio_capacidad($eid) : trim((string) get_post_meta($eid, '_espacio_capacidad', true));
    $gallery_ids = get_post_meta($eid, '_casadepiedra_gallery_ids', true);
    $ids_array = !empty($gallery_ids) ? array_filter(array_map('trim', explode(',', $gallery_ids))) : array();
    $hero_img = casadepiedra_resolve_espacio_hero($eid);
    $card_img = casadepiedra_resolve_espacio_card_img($eid);
    $tour_stops = function_exists('casa_espacio_tour_stations') ? casa_espacio_tour_stations($eid) : array();
    $panorama = !empty($tour_stops[0]['src']) ? $tour_stops[0]['src'] : '';
?>

<!-- 1. BANNER HASTA ARRIBA CON EL NOMBRE DEL ESPACIO (100% ANCHO) -->
<?php
$subt_esp = trim((string) get_post_meta($eid, '_espacio_subt', true));
$desc_esp = has_excerpt() ? wp_strip_all_tags(get_the_excerpt()) : '';
?>
<section style="position: relative; z-index: 2; width: 100%; height: clamp(400px, 48vh, 550px); display: flex; align-items: center; justify-content: center; overflow: hidden; background: #080808;">
    <?php if (!empty($hero_img)) : ?>
    <img src="<?php echo esc_url($hero_img); ?>" alt="<?php the_title_attribute(); ?>" fetchpriority="high" decoding="async" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; filter: brightness(0.68);" class="gs-zoom-in" />
    <?php endif; ?>
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.2) 0%, rgba(8,8,8,0.55) 75%, #080808 100%); z-index: 2; pointer-events: none;"></div>

    <div style="position: relative; z-index: 3; text-align: center; padding: 40px clamp(1rem, 4vw, 3rem) 0; max-width: 900px; margin: 0 auto;">
        <?php if ($subt_esp) : ?>
        <span class="text-script" style="color: var(--color-accent); font-size: 1.4rem; display: block; margin-bottom: 0.2rem;"><?php echo esc_html($subt_esp); ?></span>
        <?php endif; ?>
        <h1 style="color: #fff; font-size: clamp(2.3rem, 5vw, 4.2rem); margin: 0 0 0.6rem 0; font-family: var(--font-heading); line-height: 1.1; text-shadow: 0 10px 30px rgba(0,0,0,0.85);">
            <?php the_title(); ?>
        </h1>
        
        <!-- Ornament -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.8rem; margin: 0.8rem 0;">
            <span style="height: 1px; width: 50px; background: linear-gradient(to right, transparent, rgba(193,98,30,0.7)); display: inline-block;"></span>
            <span style="color: var(--color-accent); font-size: 0.85rem;">✦</span>
            <span style="height: 1px; width: 50px; background: linear-gradient(to left, transparent, rgba(193,98,30,0.7)); display: inline-block;"></span>
        </div>

        <?php if ($desc_esp) : ?>
        <p style="color: #eaeaea; font-size: clamp(1.1rem, 2vw, 1.4rem); font-family: var(--font-heading); font-style: italic; margin: 0.5rem auto 0.8rem; line-height: 1.4; text-shadow: 0 4px 15px rgba(0,0,0,0.85);">
            &ldquo;<?php echo esc_html($desc_esp); ?>&rdquo;
        </p>
        <?php endif; ?>

        <span style="color: var(--color-accent); font-size: 0.74rem; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 600; text-shadow: 0 2px 10px rgba(0,0,0,0.9); display: block; margin-top: 0.4rem;">
            Ex Hacienda Casa de Piedra &bull; Venue Exclusivo
        </span>
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
            <div class="espacio-col-gallery" id="espacio-galeria" style="scroll-margin-top: 120px;">
                <div class="espacio-gallery-frame">
                    <style>
                    .espacio-gallery-frame { border-radius: 1.4rem; overflow: hidden; height: clamp(340px, 42vw, 520px); position: relative; border: 1px solid rgba(193,98,30,0.32); background: #111; box-shadow: 0 25px 55px rgba(0,0,0,0.8); }
                    .espacio-gallery-frame .espacio-tour__stage { height: 100%; border: 0; border-radius: 0; box-shadow: none; }
                    .espacio-slider { position: relative; width: 100%; height: 100%; overflow: hidden; }
                    .espacio-slider-track { display: flex; height: 100%; transition: transform 0.55s cubic-bezier(0.25, 1, 0.5, 1); }
                    .espacio-slide { position: relative; cursor: zoom-in; flex: 0 0 100%; height: 100%; display: block; }
                    .espacio-slide img { width: 100%; height: 100%; object-fit: cover; }
                    .espacio-gallery-photo { position: absolute; inset: 0; z-index: 5; display: block; cursor: zoom-in; }
                    .espacio-gallery-photo[hidden] { display: none !important; }
                    .espacio-gallery-photo img { width: 100%; height: 100%; object-fit: cover; }
                    .espacio-thumb { position: relative; flex: 0 0 clamp(80px, 16vw, 105px); height: clamp(56px, 11vw, 72px); border-radius: 0.7rem; overflow: hidden; border: 1.5px solid rgba(193,98,30,0.4); background: #000; cursor: pointer; padding: 0; }
                    .espacio-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
                    .espacio-thumb.is-active { border-color: var(--color-accent); box-shadow: 0 0 0 1px var(--color-accent); }
                    .espacio-thumb-badge { position: absolute; left: 6px; bottom: 6px; background: rgba(8,8,8,0.82); color: #fff; border: 1px solid rgba(193,98,30,0.7); border-radius: 999px; font-size: 0.62rem; letter-spacing: 0.4px; padding: 0.1rem 0.35rem; }
                    </style>

                    <?php if ($panorama) : ?>
                    <div class="espacio-tour__stage" id="espacioPanoramaPane" data-panorama="<?php echo esc_url($panorama); ?>" data-tour="<?php echo esc_attr(wp_json_encode($tour_stops)); ?>" data-lenis-prevent tabindex="0" role="application" aria-label="Visor 360. Arrastra para mirar, o pulsa una flecha para avanzar.">
                        <canvas class="espacio-tour__canvas"></canvas>
                        <p class="espacio-tour__status">Cargando recorrido…</p>
                        <p class="espacio-tour__hint">Arrastra para mirar alrededor</p>
                        <p class="espacio-tour__place" <?php echo count($tour_stops) > 1 ? '' : 'hidden'; ?>><?php echo esc_html($tour_stops[0]['name'] ?? ''); ?></p>
                        <div class="espacio-tour__hotspots"></div>
                        <div class="espacio-tour__controls">
                            <div class="espacio-tour__pad" role="group" aria-label="Mover la vista">
                                <button type="button" data-pan="up" aria-label="Mirar arriba"><svg viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 4.5L14 11.5H4L9 4.5Z" fill="currentColor"/></svg></button>
                                <button type="button" data-pan="left" aria-label="Mirar a la izquierda"><svg viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M4.5 9L11.5 4V14L4.5 9Z" fill="currentColor"/></svg></button>
                                <span class="espacio-tour__pad-core" aria-hidden="true">360</span>
                                <button type="button" data-pan="right" aria-label="Mirar a la derecha"><svg viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M13.5 9L6.5 4V14L13.5 9Z" fill="currentColor"/></svg></button>
                                <button type="button" data-pan="down" aria-label="Mirar abajo"><svg viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 13.5L4 6.5H14L9 13.5Z" fill="currentColor"/></svg></button>
                            </div>
                            <div class="espacio-tour__zoom" role="group" aria-label="Zoom y pantalla completa">
                                <button type="button" data-pan="in" aria-label="Acercar"><svg viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M9 3.5V14.5M3.5 9H14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
                                <button type="button" data-pan="out" aria-label="Alejar"><svg viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M3.5 9H14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
                                <button type="button" data-pan="full" aria-label="Pantalla completa"><svg viewBox="0 0 18 18" fill="none" aria-hidden="true"><path d="M3.5 7V3.5H7M11 3.5H14.5V7M14.5 11V14.5H11M7 14.5H3.5V11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
                            </div>
                        </div>
                    </div>
                    <a href="#" id="espacioPhotoPane" class="espacio-gallery-photo" hidden>
                        <img alt="<?php the_title_attribute(); ?>" />
                    </a>
                    <?php elseif (!empty($ids_array)) : ?>
                        <div class="espacio-slider" id="espacioSlider">
                            <div class="espacio-slider-track" id="espacioSliderTrack">
                            <?php foreach ($ids_array as $id) :
                                $img_url = wp_get_attachment_image_url($id, 'large');
                                $img_full = wp_get_attachment_image_url($id, 'full');
                                if (!$img_url) continue;
                            ?>
                                <a href="<?php echo esc_url($img_full); ?>" class="espacio-slide glightbox" data-gallery="espacio-gallery">
                                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
                                </a>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else :
                        $fallback_img = $card_img ?: $hero_img;
                        $fallback_full = $hero_img ?: $card_img;
                        if (!empty($fallback_img)) : ?>
                        <a href="<?php echo esc_url($fallback_full); ?>" class="glightbox" data-gallery="espacio-gallery" style="display:block; height:100%;">
                            <img src="<?php echo esc_url($fallback_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" decoding="async" style="width:100%; height:100%; object-fit:cover;" />
                        </a>
                        <?php else : ?>
                        <div style="width:100%; height:100%; background:#181818;"></div>
                        <?php endif;
                    endif; ?>
                </div>

                <?php if ($panorama) : ?>
                    <div class="espacio-lightbox-sources" hidden>
                        <?php foreach ($ids_array as $lb_i => $lb_id) :
                            $lb_full = wp_get_attachment_image_url($lb_id, 'full');
                            if (!$lb_full) continue;
                        ?>
                            <a href="<?php echo esc_url($lb_full); ?>" class="glightbox" data-gallery="espacio-gallery" data-photo-index="<?php echo (int) $lb_i; ?>"></a>
                        <?php endforeach; ?>
                    </div>
                    <div style="display: flex; gap: 0.75rem; margin-top: 1rem; overflow-x: auto; padding-bottom: 0.5rem; -webkit-overflow-scrolling: touch;">
                        <button type="button" class="espacio-thumb is-active" data-espacio-thumb="360" aria-label="Ver recorrido 360">
                            <img src="<?php echo esc_url($panorama); ?>" alt="Recorrido 360" />
                            <span class="espacio-thumb-badge">360°</span>
                        </button>
                        <?php foreach ($ids_array as $t_idx => $t_id) :
                            $t_src = wp_get_attachment_image_url($t_id, 'medium');
                            $t_large = wp_get_attachment_image_url($t_id, 'large');
                            if (!$t_src) continue;
                        ?>
                            <button type="button" class="espacio-thumb" data-espacio-thumb="photo" data-photo-index="<?php echo (int) $t_idx; ?>" data-photo-src="<?php echo esc_url($t_large ?: $t_src); ?>" aria-label="<?php echo esc_attr('Ver fotografía ' . ($t_idx + 1)); ?>">
                                <img src="<?php echo esc_url($t_src); ?>" alt="Miniatura" loading="lazy" decoding="async" />
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (!empty($ids_array) && count($ids_array) > 1) : ?>
                    <div style="display: flex; gap: 0.75rem; margin-top: 1rem; overflow-x: auto; padding-bottom: 0.5rem; -webkit-overflow-scrolling: touch;">
                        <?php foreach ($ids_array as $t_idx => $t_id) :
                            $t_src = wp_get_attachment_image_url($t_id, 'medium');
                            if (!$t_src) continue;
                        ?>
                            <button type="button" class="espacio-thumb" onclick="window.goToEspacioSlide && window.goToEspacioSlide(<?php echo (int) $t_idx; ?>)" aria-label="<?php echo esc_attr('Ver fotografía ' . ($t_idx + 1)); ?>">
                                <img src="<?php echo esc_url($t_src); ?>" alt="Miniatura" loading="lazy" decoding="async" />
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    if (typeof GLightbox !== 'undefined') GLightbox({ selector: '.glightbox' });

                    const pano = document.getElementById('espacioPanoramaPane');
                    const photo = document.getElementById('espacioPhotoPane');
                    if (pano && photo) {
                        const thumbs = document.querySelectorAll('[data-espacio-thumb]');
                        const mark = (active) => {
                            thumbs.forEach((thumb) => thumb.classList.toggle('is-active', thumb === active));
                        };
                        thumbs.forEach((thumb) => {
                            thumb.addEventListener('click', () => {
                                mark(thumb);
                                if (thumb.getAttribute('data-espacio-thumb') === '360') {
                                    photo.hidden = true;
                                    pano.hidden = false;
                                    window.dispatchEvent(new Event('resize'));
                                    return;
                                }
                                const img = photo.querySelector('img');
                                img.src = thumb.getAttribute('data-photo-src');
                                photo.hidden = false;
                                pano.hidden = true;
                            });
                        });
                        photo.addEventListener('click', (event) => {
                            event.preventDefault();
                            const active = document.querySelector('[data-espacio-thumb="photo"].is-active');
                            const index = active ? active.getAttribute('data-photo-index') : '';
                            const source = document.querySelector('.espacio-lightbox-sources a[data-photo-index="' + index + '"]');
                            if (source) source.click();
                        });
                        return;
                    }

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
                });
                </script>
            </div>

            <!-- LADO DERECHO EN PC / PRIMERO EN MÓVIL: TARJETA OFICIAL -->
            <div class="espacio-col-info">
                <div class="luxury-info-panel" style="padding: clamp(1.8rem, 4vw, 3rem); border-radius: 1.4rem; background: #111111; border: 1px solid rgba(193, 98, 30, 0.35); box-shadow: 0 20px 50px rgba(0,0,0,0.7); position: relative; z-index: 2;">
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
                    $clean_m2 = function_exists('casa_espacio_m2') ? casa_espacio_m2($eid) : trim((string) get_post_meta($eid, '_espacio_m2', true));
                    $pdf_meta = function_exists('casadepiedra_resolve_espacio_plano')
                        ? casadepiedra_resolve_espacio_plano($eid)
                        : '';
                    ?>
                    <?php if ($clean_m2 || $clean_cap) : ?>
                    <div style="border-top: 1px solid rgba(255,255,255,0.12); border-bottom: 1px solid rgba(255,255,255,0.12); padding: 1.25rem 0; margin-bottom: 1.8rem; display: grid; grid-template-columns: repeat(<?php echo ($clean_m2 && $clean_cap) ? '2' : '1'; ?>, 1fr); gap: 1rem;">
                        <?php if ($clean_cap) : ?>
                        <div>
                            <span style="color: #888; display: block; font-size: 0.74rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.2rem;">Capacidad</span>
                            <strong style="color: var(--color-accent); font-size: 1.15rem; font-family: var(--font-heading);">Hasta <?php echo esc_html($clean_cap); ?> personas</strong>
                        </div>
                        <?php endif; ?>
                        <?php if ($clean_m2) : ?>
                        <div>
                            <span style="color: #888; display: block; font-size: 0.74rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.2rem;">Superficie</span>
                            <strong style="color: var(--color-accent); font-size: 1.15rem; font-family: var(--font-heading);"><?php echo esc_html($clean_m2); ?> m²</strong>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- BOTONES: Cotizar siempre; Planos solo si hay PDF en WordPress -->
                    <div style="display: grid; grid-template-columns: <?php echo $pdf_meta ? 'repeat(2, 1fr)' : '1fr'; ?>; gap: 1rem; margin-top: 1.8rem;">
                        <!-- Cuadro 1: Cotizar -->
                        <button type="button" class="btn-open-quote-modal" data-salon="<?php echo esc_attr(get_the_title()); ?>"
                           style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.35rem 0.6rem 1.15rem; background: #111111; border: 1.5px solid var(--color-accent); border-radius: 1.25rem; text-decoration: none; cursor: pointer; transition: all 0.3s; box-shadow: 0 8px 25px rgba(193,98,30,0.15); position: relative; z-index: 3;"
                           onmouseover="this.style.background='#161616'; this.style.transform='translateY(-3px)';"
                           onmouseout="this.style.background='#111111'; this.style.transform='translateY(0)';">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; margin-bottom:0.5rem;">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="7" y="2.5" width="2.5" height="4" rx="1.25" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <rect x="12.75" y="2.5" width="2.5" height="4" rx="1.25" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <rect x="18.5" y="2.5" width="2.5" height="4" rx="1.25" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <path d="M5 6.5H23C24.1 6.5 25 7.4 25 8.5V21C25 22.1 24.1 23 23 23H5C3.9 23 3 22.1 3 21V8.5C3 7.4 3.9 6.5 5 6.5Z" fill="rgba(193,98,30,0.1)" stroke="var(--color-accent)" stroke-width="1.8"/>
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

                        <?php if ($pdf_meta) : ?>
                        <button type="button" class="js-open-pdf-modal"
                           data-pdf-url="<?php echo esc_url($pdf_meta); ?>"
                           data-pdf-title="<?php echo esc_attr('Planos de ' . get_the_title()); ?>"
                           aria-label="<?php echo esc_attr('Ver planos de ' . get_the_title()); ?>"
                           style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 1.35rem 0.6rem 1.15rem; background: #111111; border: 1.2px solid rgba(193,98,30,0.38); border-radius: 1.25rem; text-decoration: none; transition: all 0.3s; position: relative; z-index: 3; cursor: pointer;"
                           onmouseover="this.style.background='#161616'; this.style.transform='translateY(-3px)'; this.style.borderColor='var(--color-accent)';"
                           onmouseout="this.style.background='#111111'; this.style.transform='translateY(0)'; this.style.borderColor='rgba(193,98,30,0.38)';">
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; margin-bottom:0.5rem;">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 3C3.5 3 2.5 4.5 2.5 6V22C2.5 23.5 3.5 25 5 25C6.5 25 7.5 23.5 7.5 22V6C7.5 4.5 6.5 3 5 3Z" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <path d="M23 3C21.5 3 20.5 4.5 20.5 6V22C20.5 23.5 21.5 25 23 25C24.5 25 25.5 23.5 25.5 22V6C25.5 4.5 24.5 3 23 3Z" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <path d="M7.5 5.5H20.5V21.5H7.5" stroke="var(--color-accent)" stroke-width="1.8"/>
                                    <path d="M10.5 9.5H16.5V14.5H10.5V9.5Z" fill="rgba(193,98,30,0.18)" stroke="var(--color-accent)" stroke-width="1.5"/>
                                    <path d="M16.5 14.5H18.5V18.5H13.5V14.5" stroke="var(--color-accent)" stroke-width="1.5"/>
                                    <path d="M16.5 9.5C17.6 9.5 18.5 10.4 18.5 11.5" stroke="var(--color-accent)" stroke-width="1.4"/>
                                </svg>
                            </span>
                            <span style="color: rgba(255,255,255,0.65); font-size: 0.74rem; font-weight: 300; letter-spacing: 0.8px; text-transform: uppercase; text-align: center;">Planos</span>
                        </button>
                        <?php endif; ?>
                    </div>

                    <?php if ($pdf_meta) : ?>
                    <p style="color: #999; font-size: 0.81rem; text-align: center; margin: 0.6rem 0 0 0; font-style: italic; line-height: 1.4;">
                        Consulta la distribución arquitectónica o solicita tu cotización directa
                    </p>
                    <?php endif; ?>

                    <?php if ($panorama) : ?>
                    <a class="espacio-tour__jump" href="#espacio-galeria">Ver recorrido 360°</a>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <?php
        $esp_reviews = function_exists('casa_get_review_cards') ? casa_get_review_cards('casa_opt_home_rev') : array();
        if (!empty($esp_reviews)) :
        ?>
        <div style="margin-top: clamp(4rem, 7vw, 5.5rem); border-top: 1px solid rgba(255,255,255,0.12); padding-top: clamp(3rem, 5vw, 4rem);">
            <div style="margin-bottom: 2.5rem;">
                <span style="color: var(--color-accent); font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block;">Testimonios de invitados</span>
                <h3 style="color: #fff; font-size: clamp(1.6rem, 3vw, 2.3rem); font-family: var(--font-heading); margin: 0.3rem 0 0 0;">Experiencias en Casa de Piedra</h3>
            </div>
            <?php if (!empty($esp_reviews)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" style="margin-bottom: 2rem;">
                <?php foreach ($esp_reviews as $rev) : ?>
                <?php if (!empty($rev['url'])) : ?>
                <a href="<?php echo esc_url($rev['url']); ?>" class="google-review-card" target="_blank" rel="noopener noreferrer" style="padding: 1.8rem; border-radius: 1.2rem; background: #111111; border: 1px solid rgba(193,98,30,0.28); display: flex; flex-direction: column; justify-content: space-between; text-decoration: none;">
                <?php else : ?>
                <div class="google-review-card" style="padding: 1.8rem; border-radius: 1.2rem; background: #111111; border: 1px solid rgba(193,98,30,0.28); display: flex; flex-direction: column; justify-content: space-between;">
                <?php endif; ?>
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                            <div style="color: var(--color-accent); font-size: 1rem;">★★★★★</div>
                            <?php if (!empty($rev['url'])) : ?>
                            <span style="font-size: 0.72rem; color: var(--color-accent); font-weight: 500;">Ver en Google ↗</span>
                            <?php endif; ?>
                        </div>
                        <p style="color: #ddd; font-size: 0.96rem; line-height: 1.65; font-style: italic; margin-bottom: 1.4rem;">
                            &ldquo;<?php echo esc_html($rev['text']); ?>&rdquo;
                        </p>
                    </div>
                    <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 0.8rem;">
                        <strong style="color: #fff; font-size: 0.92rem;"><?php echo esc_html($rev['author'] !== '' ? $rev['author'] : 'Google'); ?></strong>
                    </div>
                <?php echo !empty($rev['url']) ? '</a>' : '</div>'; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- 3. EXPLORA OTROS ESPACIOS (SECCIÓN AMPLIADA Y MAJESTUOSA) -->
        <div style="margin-top: clamp(4.5rem, 8vw, 6.5rem); border-top: 1px solid rgba(193,98,30,0.25); padding-top: clamp(3.5rem, 6vw, 4.5rem);">
            <div style="display: flex; flex-direction: column; sm:flex-row; justify-content: space-between; align-items: flex-start; sm:align-items: flex-end; margin-bottom: 2.8rem; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <span style="color: var(--color-accent); font-size: 0.82rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block;">Más Opciones de Celebración</span>
                    <h3 style="color: #fff; font-size: clamp(1.7rem, 3.5vw, 2.5rem); font-family: var(--font-heading); margin: 0.4rem 0 0 0;">Explora otros Escenarios</h3>
                </div>
                <a href="<?php echo esc_url(get_post_type_archive_link('espacios')); ?>" style="color: var(--color-accent); font-size: 0.95rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.3rem; border: 1px solid rgba(193,98,30,0.4); border-radius: 999px; transition: all 0.3s;" onmouseover="this.style.background='rgba(193,98,30,0.18)';" onmouseout="this.style.background='transparent';">
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
                            box-shadow: 0 25px 50px rgba(193, 98, 30, 0.25);
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
                        $clean_o_cap = function_exists('casa_espacio_capacidad') ? casa_espacio_capacidad(get_the_ID()) : trim((string) get_post_meta(get_the_ID(), '_espacio_capacidad', true));
                        $o_img = casadepiedra_resolve_espacio_card_img(get_the_ID());
                    ?>
                        <a href="<?php the_permalink(); ?>" class="other-space-card">
                            <div style="height: clamp(240px, 30vw, 280px); position: relative; overflow: hidden;">
                                <?php if (!empty($o_img)) : ?>
                                    <img src="<?php echo esc_url($o_img); ?>" alt="<?php the_title_attribute(); ?>" class="other-space-img" loading="lazy" decoding="async" />
                                <?php else : ?>
                                    <div style="background: #181818; width: 100%; height: 100%;"></div>
                                <?php endif; ?>
                                <?php if (!empty($clean_o_cap)) : ?>
                                    <span class="card-kicker" style="position: absolute; bottom: 16px; left: 16px; font-size: 0.8rem;">
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
