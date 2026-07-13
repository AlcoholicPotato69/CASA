<?php
/**
 * Template Name: Galería
 * Description: Plantilla para la Galería Collage Masonry.
 */
get_header(); 

$portada_url = get_option('casa_opt_galeria_portada');
$gallery_ids_str = get_option('casa_opt_galeria_imagenes', '');
$gallery_ids = !empty($gallery_ids_str) ? explode(',', $gallery_ids_str) : [];

// Paginación
$per_page = 20;
$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
$offset = ($paged - 1) * $per_page;
$total_pages = ceil(count($gallery_ids) / $per_page);
$current_ids = array_slice($gallery_ids, $offset, $per_page);

// Inicializar arreglo $gallery_items_data de forma segura para evitar warnings
$gallery_items_data = array();
if (!empty($current_ids)) {
    foreach ($current_ids as $idx => $id) {
        $full = wp_get_attachment_url($id);
        if ($full) {
            $terms = get_the_terms($id, 'galeria_tag');
            $tag_slug = ($terms && !is_wp_error($terms)) ? $terms[0]->slug : 'bodas';
            $gallery_items_data[] = array(
                'full' => $full,
                'thumb' => $full,
                'title' => get_the_title($id) ?: 'Fotografía',
                'tag' => $tag_slug,
                'class' => 'gallery-item luxury-card tag-' . $tag_slug
            );
        }
    }
}

?>

<?php if ($portada_url): ?>
<section style="position: relative; width: 100%; height: 70vh; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 4rem;">
    <img src="<?php echo esc_url($portada_url); ?>" alt="Galería Portada" style="position: absolute; width: 100%; height: 100%; object-fit: cover; z-index: -1; filter: brightness(0.7);" class="gs-zoom-in" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(10,10,10,0.2) 0%, rgba(10,10,10,0.5) 70%, var(--color-bg, #0a0a0a) 100%); z-index: 0; pointer-events: none;"></div>
    <div style="position: relative; z-index: 1; text-align: center;">
        <span class="text-script reveal-text" style="color: #fff;">Nuestra Esencia</span>
        <h1 class="text-hero reveal-text" style="color: #fff; text-shadow: 0 10px 30px rgba(0,0,0,0.5);">Galería</h1>
    </div>
</section>
<?php else: ?>
<div style="padding-top: clamp(8rem, 15vh, 12rem); padding-bottom: 2rem;">
    <div class="container text-center">
        <span class="text-script" style="font-size: 3rem;">Galería</span>
        <h2 class="text-h2" style="margin-top: 1rem;">Momentos inolvidables.</h2>
    </div>
</div>
<?php endif; ?>

<div class="container" style="padding-bottom: 6rem;">
    <?php
    $etiquetas_opt = get_option('casa_opt_galeria_etiquetas', 'Bodas, Eventos Sociales, Convenciones, Arquitectura & Gastronomía');
    $etiquetas_raw = array_filter(array_map('trim', explode(',', $etiquetas_opt)));
    if (empty($etiquetas_raw)) {
        $etiquetas_raw = array('Bodas', 'Eventos Sociales', 'Convenciones', 'Arquitectura & Gastronomía');
    }

    $default_covers = array(
        get_template_directory_uri() . '/assets/images/salon_principal_1779523069698.png',
        get_template_directory_uri() . '/assets/images/salon_pavorreales_1779523097528.png',
        get_template_directory_uri() . '/assets/images/jardin_principal_1779523113451.png',
        get_template_directory_uri() . '/assets/images/terraza_mezquite_1779523084857.png',
        'https://images.unsplash.com/photo-1519167758481-83f550bb49b3?q=80&w=2098&auto=format&fit=crop'
    );

    $categories = array();
    $cover_idx = 0;
    foreach ($etiquetas_raw as $tag_title) {
        $slug = sanitize_title($tag_title);
        $found_cover = '';
        foreach ($gallery_items_data as $g_item) {
            if (isset($g_item['tag']) && $g_item['tag'] === $slug) {
                $found_cover = $g_item['full'];
                break;
            }
        }
        if (empty($found_cover)) {
            $found_cover = $default_covers[$cover_idx % count($default_covers)];
        }
        $cover_idx++;

        $categories[$slug] = array(
            'title' => $tag_title,
            'subtitle' => 'Galería Exclusiva',
            'cover' => $found_cover
        );
    }
    ?>

    <!-- VISTA 1: TARJETAS DE ETIQUETA / CATEGORÍA DINÁMICAS DESDE EL BACKEND -->
    <div id="gallery-categories-view">
        <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 1.5rem; width: 100%;">
            <?php 
            $cat_index = 0;
            $total_cats = count($categories);
            foreach ($categories as $slug => $cat): 
                $col_span = ($cat_index < 3 || $total_cats <= 3) ? 'grid-column: span 2;' : 'grid-column: span 3;';
                $cat_index++;
            ?>
            <div class="gallery-tag-card luxury-card group" data-category="<?php echo esc_attr($slug); ?>" style="<?php echo $col_span; ?> position: relative; height: clamp(220px, 28vh, 300px); cursor: pointer; overflow: hidden; border-radius: 16px; background-color: #111111; border: 1px solid rgba(212,175,55,0.22); display: flex; flex-direction: column; justify-content: flex-end; padding: 1.8rem; box-shadow: 0 15px 45px rgba(0,0,0,0.8); z-index: 1; transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease, box-shadow 0.4s ease;" onmouseover="this.style.transform='translateY(-6px)'; this.style.borderColor='rgba(212,175,55,0.65)'; this.style.boxShadow='0 25px 60px rgba(0,0,0,0.95), 0 0 35px rgba(212,175,55,0.2)'; const img = this.querySelector('img'); if(img) img.style.transform='scale(1.05)'; const badge = this.querySelector('.venue-arrow-badge'); if(badge){ badge.style.opacity='1'; badge.style.transform='none'; }" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(212,175,55,0.22)'; this.style.boxShadow='0 15px 45px rgba(0,0,0,0.8)'; const img = this.querySelector('img'); if(img) img.style.transform='scale(1)'; const badge = this.querySelector('.venue-arrow-badge'); if(badge){ badge.style.opacity='0'; badge.style.transform='translateX(-10px) translateY(10px) scale(0.6)'; }">
                <div style="position: absolute; inset: 0; overflow: hidden; z-index: 0;">
                    <img src="<?php echo esc_url($cat['cover']); ?>" alt="<?php echo esc_attr($cat['title']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);" class="gallery-cover-img" />
                </div>
                <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(10,10,10,0.9) 0%, rgba(10,10,10,0.3) 50%, rgba(10,10,10,0.1) 100%); z-index: 1; pointer-events: none;"></div>
                
                <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: flex-end; width: 100%;">
                    <h3 class="text-h3" style="font-size: clamp(1.6rem, 2.2vw, 2.1rem); margin: 0; color: #ffffff; text-shadow: 0 2px 10px rgba(0,0,0,0.6);"><?php echo esc_html($cat['title']); ?></h3>
                    
                    <div class="venue-arrow-badge" style="width: 42px; height: 42px; border-radius: 50%; background: var(--color-accent); color: #000; display: flex; align-items: center; justify-content: center; transform: translateX(-10px) translateY(10px) scale(0.6); opacity: 0; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); flex-shrink: 0; margin-left: 1rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <style>
            @media (max-width: 1024px) {
                #gallery-categories-view > div {
                    grid-template-columns: repeat(2, 1fr) !important;
                }
                #gallery-categories-view .gallery-tag-card {
                    grid-column: span 1 !important;
                }
                #gallery-categories-view .gallery-tag-card:last-child {
                    grid-column: span 2 !important;
                }
            }
            @media (max-width: 640px) {
                #gallery-categories-view > div {
                    grid-template-columns: 1fr !important;
                }
                #gallery-categories-view .gallery-tag-card {
                    grid-column: span 1 !important;
                }
            }
        </style>
    </div>

    <!-- VISTA 2: SUBPÁGINA CON 2 COLUMNAS (GRID DE FOTOS + PANEL LATERAL DERECHO DE CHECKBOX) -->
    <div id="gallery-subpage-view" style="display: none;">
        <div style="margin-bottom: 2.5rem;">
            <button id="btn-back-categories" style="background: rgba(17,17,17,0.9); border: 1px solid rgba(212,175,55,0.35); color: var(--color-accent); padding: 0.7rem 1.4rem; border-radius: 999px; cursor: pointer; font-size: 0.88rem; font-weight: 600; letter-spacing: 0.6px; display: inline-flex; align-items: center; gap: 0.6rem; transition: all 0.3s ease;" onmouseover="this.style.background='var(--color-accent)'; this.style.color='#000000';" onmouseout="this.style.background='rgba(17,17,17,0.9)'; this.style.color='var(--color-accent)';">
                <span>←</span> Volver a Categorías
            </button>
        </div>

        <div style="margin-bottom: 3rem; text-align: center;">
            <span id="subpage-subtitle" class="text-script" style="font-size: 2.4rem; color: var(--color-accent);"></span>
            <h2 id="subpage-title" class="text-hero" style="margin-top: 0.4rem; font-size: clamp(2.2rem, 4.5vw, 3.4rem);"></h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2.5rem; align-items: start;">
            <!-- ÁREA DE FOTOS -->
            <div id="subpage-photo-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                <?php if (!empty($gallery_items_data)): ?>
                    <?php foreach ($gallery_items_data as $item): ?>
                        <div class="<?php echo esc_attr($item['class']); ?>">
                            <a href="<?php echo esc_url($item['full']); ?>" class="glightbox" data-gallery="subpage-gallery" style="display: block; width: 100%; height: 100%; cursor: zoom-in;">
                                <img src="<?php echo esc_url($item['thumb']); ?>" alt="<?php echo esc_attr($item['title']); ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; pointer-events: none;" />
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php
                    $attach_query = new WP_Query(array(
                        'post_type' => 'attachment',
                        'post_status' => 'inherit',
                        'post_mime_type' => 'image',
                        'posts_per_page' => 12,
                        'orderby' => 'rand'
                    ));
                    $idx = 0;
                    if ($attach_query->have_posts()):
                        while ($attach_query->have_posts()): $attach_query->the_post();
                            $full_url = wp_get_attachment_image_url(get_the_ID(), 'full');
                            $thumb_url = wp_get_attachment_image_url(get_the_ID(), 'large') ?: $full_url;
                            $first_slug = !empty($categories) ? array_key_first($categories) : 'all';
                    ?>
                    <div class="gallery-item luxury-card tag-<?php echo esc_attr($first_slug); ?>" data-tag="<?php echo esc_attr($first_slug); ?>" style="height: <?php echo ($idx % 3 === 0) ? '380px' : '300px'; ?>; cursor: zoom-in; overflow: hidden; border-radius: 14px; background-color: #111111; border: 1px solid rgba(212,175,55,0.18); position: relative; z-index: 1;">
                        <a href="<?php echo esc_url($full_url); ?>" class="glightbox" data-gallery="subpage-gallery" style="display: block; width: 100%; height: 100%;">
                            <img src="<?php echo esc_url($thumb_url); ?>" alt="Fotografía Galería" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;" />
                        </a>
                    </div>
                    <?php 
                        $idx++;
                        endwhile;
                        wp_reset_postdata();
                    endif; 
                    ?>
                <?php endif; ?>
            </div>

            <!-- PANEL LATERAL DERECHO CON CHECKBOXES DINÁMICOS DESDE EL BACKEND -->
            <aside class="luxury-card" style="background-color: #111111; border: 1px solid rgba(212,175,55,0.3); border-radius: 16px; padding: 2rem; position: sticky; top: 110px; z-index: 2; box-shadow: 0 15px 40px rgba(0,0,0,0.8);">
                <div style="margin-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem;">
                    <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: var(--color-accent); font-weight: 600; display: block; margin-bottom: 0.4rem;">
                        Filtrar por Etiqueta
                    </span>
                    <h4 style="font-size: 1.25rem; color: #ffffff; font-family: var(--font-heading); margin: 0;">
                        Categorías
                    </h4>
                </div>

                <div id="gallery-sidebar-filters" style="display: flex; flex-direction: column; gap: 0.85rem;">
                    <label class="sidebar-filter-option" data-tag="all" style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 0.65rem 0.85rem; border-radius: 8px; transition: all 0.25s ease; user-select: none;">
                        <input type="checkbox" class="sidebar-cb" value="all" style="accent-color: #d4af37; width: 18px; height: 18px; cursor: pointer;" />
                        <span style="font-size: 0.95rem;">Todas las fotografías</span>
                    </label>
                    <?php foreach ($categories as $cat_slug => $cat_data): ?>
                    <label class="sidebar-filter-option" data-tag="<?php echo esc_attr($cat_slug); ?>" style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 0.65rem 0.85rem; border-radius: 8px; transition: all 0.25s ease; user-select: none;">
                        <input type="checkbox" class="sidebar-cb" value="<?php echo esc_attr($cat_slug); ?>" style="accent-color: #d4af37; width: 18px; height: 18px; cursor: pointer;" />
                        <span style="font-size: 0.95rem;"><?php echo esc_html($cat_data['title']); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.08); font-size: 0.8rem; color: rgba(255,255,255,0.5); line-height: 1.4;">
                    Selecciona una etiqueta para ver instantáneamente su colección fotográfica sin recargar.
                </div>
            </aside>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const catCards = document.querySelectorAll('.gallery-tag-card');
        const categoriesView = document.getElementById('gallery-categories-view');
        const subpageView = document.getElementById('gallery-subpage-view');
        const btnBack = document.getElementById('btn-back-categories');
        const subpageTitle = document.getElementById('subpage-title');
        const subpageSubtitle = document.getElementById('subpage-subtitle');
        const sidebarOptions = document.querySelectorAll('.sidebar-filter-option');
        const sidebarCheckboxes = document.querySelectorAll('.sidebar-cb');
        const photoItems = document.querySelectorAll('#subpage-photo-grid .gallery-item');

        const catData = {
            'all': { title: 'Todas las Fotografías', subtitle: 'Colección General' },
            'bodas': { title: 'Bodas', subtitle: 'Ceremonias & Recepciones' },
            'graduaciones': { title: 'Graduaciones', subtitle: 'Gala & Celebración' },
            'sociales': { title: 'Sociales', subtitle: 'Aniversarios & Banquetes' },
            'empresariales': { title: 'Empresariales', subtitle: 'Congresos & Galas' },
            'eventos-especiales': { title: 'Eventos Especiales', subtitle: 'Exclusividad & Distinción' }
        };

        const applyTagFilter = (slug) => {
            const data = catData[slug] || catData['all'];
            subpageTitle.textContent = data.title;
            subpageSubtitle.textContent = data.subtitle;

            // Actualizar estilo del panel lateral (single-checkbox select)
            sidebarOptions.forEach(opt => {
                const optTag = opt.getAttribute('data-tag');
                const cb = opt.querySelector('.sidebar-cb');
                const isActive = (optTag === slug);
                cb.checked = isActive;
                opt.style.backgroundColor = isActive ? 'rgba(212,175,55,0.12)' : 'transparent';
                opt.style.border = isActive ? '1px solid rgba(212,175,55,0.4)' : '1px solid transparent';
                opt.style.color = isActive ? '#ffffff' : 'rgba(255,255,255,0.72)';
            });

            // Filtrar instantáneamente fotos sin recargar
            photoItems.forEach(item => {
                item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                let match = false;
                if (slug === 'all') {
                    match = true;
                } else {
                    const itemTagAttr = item.getAttribute('data-tag');
                    if (itemTagAttr && itemTagAttr === slug) match = true;
                    else if (item.classList.contains(slug) || item.classList.contains('tag-' + slug)) match = true;
                }

                if (match) {
                    item.style.display = '';
                    setTimeout(() => { item.style.opacity = '1'; item.style.transform = 'scale(1)'; }, 20);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.9)';
                    setTimeout(() => { item.style.display = 'none'; }, 300);
                }
            });

            // Re-inicializar GLightbox para que los botones laterales (Anterior / Siguiente) naveguen entre las fotos visibles
            setTimeout(() => {
                if (typeof GLightbox !== 'undefined') {
                    if (window.casaGalleryLightbox) {
                        try { window.casaGalleryLightbox.destroy(); } catch(e){}
                    }
                    window.casaGalleryLightbox = GLightbox({
                        selector: '#subpage-photo-grid .gallery-item:not([style*="display: none"]) .glightbox',
                        loop: true,
                        touchNavigation: true
                    });
                }
            }, 350);
        };

        const showCategorySubpage = (slug) => {
            categoriesView.style.display = 'none';
            subpageView.style.display = 'block';
            applyTagFilter(slug);
            window.scrollTo({ top: 120, behavior: 'smooth' });
        };

        catCards.forEach(card => {
            card.addEventListener('click', () => {
                const slug = card.getAttribute('data-category');
                showCategorySubpage(slug);
            });
        });

        sidebarOptions.forEach(opt => {
            opt.addEventListener('click', (e) => {
                e.preventDefault();
                const slug = opt.getAttribute('data-tag');
                applyTagFilter(slug);
            });
        });

        if (btnBack) {
            btnBack.addEventListener('click', () => {
                subpageView.style.display = 'none';
                categoriesView.style.display = 'block';
                window.scrollTo({ top: 100, behavior: 'smooth' });
            });
        }
    });
    </script>
</div>



<?php get_footer(); ?>
