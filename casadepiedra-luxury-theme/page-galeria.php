<?php
/**
 * Template Name: Galería
 * Description: Plantilla para la Galería Collage Masonry.
 */
get_header(); 

$portada_url = get_option('casa_opt_galeria_portada');
$gallery_ids_str = get_option('casa_opt_galeria_imagenes', '');
$gallery_ids = array_values(array_filter(array_map('absint', explode(',', (string) $gallery_ids_str))));
$current_ids = $gallery_ids;

$gallery_items_data = array();
$tag_terms = function_exists('casa_galeria_ensure_tags') ? casa_galeria_ensure_tags() : array();
$tags = array();
if (!empty($tag_terms) && !is_wp_error($tag_terms)) {
    foreach ($tag_terms as $tag_term) {
        $tags[] = $tag_term->name;
    }
}
if (empty($tags)) {
    $tags = array_values(array_filter(array_map('trim', explode(',', get_option('casa_opt_galeria_etiquetas', 'Boda, Cumpleaños, Eventos empresariales, Convenciones')))));
}

if (!empty($current_ids)) {
    foreach ($current_ids as $id) {
        $item = function_exists('casa_galeria_attachment_item') ? casa_galeria_attachment_item($id) : null;
        if ($item) {
            $gallery_items_data[] = $item;
        }
    }
}

// Galería: solo adjuntos del panel. Sin fotos empaquetadas en el tema.

if (!empty($gallery_items_data)) {
    shuffle($gallery_items_data);
}
?>

<?php
$portada_url = function_exists('casa_opt_media') ? casa_opt_media('casa_opt_galeria_portada') : casa_usable_media(get_option('casa_opt_galeria_portada', ''));
?>
<style>
@media (max-width: 767px) {
    .gallery-hero-banner { height: 280px !important; margin-bottom: 1.4rem !important; }
}
</style>
<section class="gallery-hero-banner" style="position: relative; z-index: 2; width: 100%; height: clamp(400px, 48vh, 550px); display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 2.5rem; background: #080808;">
    <img src="<?php echo esc_url($portada_url); ?>" alt="Galería Portada" fetchpriority="high" decoding="async" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; filter: brightness(0.68);" class="gs-zoom-in" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.2) 0%, rgba(8,8,8,0.55) 75%, #080808 100%); z-index: 2; pointer-events: none;"></div>
    
    <div style="position: relative; z-index: 3; text-align: center; padding: 40px clamp(1rem, 4vw, 3rem) 0; max-width: 900px; margin: 0 auto;">
        <span class="text-script" style="color: var(--color-accent); font-size: 1.4rem; display: block; margin-bottom: 0.2rem;"><?php echo esc_html(get_option('casa_opt_galeria_subtitle') ?: 'Nuestra Esencia'); ?></span>
        <h1 style="color: #fff; font-size: clamp(2.3rem, 4.5vw, 3.6rem); margin: 0 0 0.6rem 0; font-family: var(--font-heading); line-height: 1.1; text-shadow: 0 10px 30px rgba(0,0,0,0.85);"><?php echo esc_html(get_option('casa_opt_galeria_title') ?: 'Galería Oficial'); ?></h1>
        
        <!-- Ornament -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.8rem; margin: 0.8rem 0;">
            <span style="height: 1px; width: 50px; background: linear-gradient(to right, transparent, rgba(193,98,30,0.7)); display: inline-block;"></span>
            <span style="color: var(--color-accent); font-size: 0.85rem;">✦</span>
            <span style="height: 1px; width: 50px; background: linear-gradient(to left, transparent, rgba(193,98,30,0.7)); display: inline-block;"></span>
        </div>

        <p style="color: #eaeaea; font-size: clamp(1.1rem, 2vw, 1.4rem); font-family: var(--font-heading); font-style: italic; margin: 0.5rem auto 0.8rem; line-height: 1.4; text-shadow: 0 4px 15px rgba(0,0,0,0.85);">
            &ldquo;<?php echo esc_html(ucfirst(get_option('casa_opt_galeria_desc') ?: 'Momentos inolvidables y celebraciones extraordinarias')); ?>&rdquo;
        </p>

        <span style="color: var(--color-accent); font-size: 0.74rem; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 600; text-shadow: 0 2px 10px rgba(0,0,0,0.9); display: block; margin-top: 0.4rem;">
            Ex Hacienda Casa de Piedra &bull; Colección Visual
        </span>
    </div>
</section>

<div style="width: 100%; max-width: 100%; margin: 0 auto; padding: 0 clamp(0.75rem, 1.5vw, 2.2rem) 6rem;">
    <?php
    $default_covers = array();
    $categories = array();
    $cover_idx = 0;
    foreach ($tags as $tag) {
        $name = trim($tag);
        if (empty($name)) continue;
        $slug = sanitize_title($name);
        $found_cover = "";
        $cover_pool = function_exists('casa_galeria_cover_urls') ? casa_galeria_cover_urls($gallery_items_data, $slug) : array();
        if (!empty($cover_pool)) {
            $found_cover = $cover_pool[array_rand($cover_pool)];
        }
        if (empty($found_cover)) {
            $found_cover = $portada_url;
        }
        $cover_idx++;
        $categories[$slug] = array(
            "title" => $name,
            "subtitle" => "Galería Exclusiva",
            "cover" => $found_cover,
            "covers" => $cover_pool
        );
    }
    ?>

    <!-- VISTA 1: TARJETAS DE ETIQUETA / CATEGORÍA DINÁMICAS DESDE EL BACKEND -->
    <div id="gallery-categories-view">
        
        <!-- TARJETA GENERAL (Todas las fotografías) -->
        <?php
        $all_covers = function_exists('casa_galeria_cover_urls') ? casa_galeria_cover_urls($gallery_items_data) : array();
        $general_item = function_exists('casa_galeria_random_from') ? casa_galeria_random_from($gallery_items_data) : null;
        $general_cover = $general_item ? $general_item['thumb'] : (!empty($gallery_items_data) ? $gallery_items_data[0]['thumb'] : $default_covers[0]);
        if (empty($all_covers) && $general_cover) {
            $all_covers = array($general_cover);
        }
        ?>
        <div class="gallery-tag-card luxury-card group" data-category="all" style="position: relative; height: clamp(280px, 35vh, 400px); cursor: pointer; overflow: hidden; border-radius: 20px; background-color: #111111; border: 1px solid rgba(193,98,30,0.4); display: flex; flex-direction: column; justify-content: flex-end; padding: 1.5rem 1.8rem; box-shadow: 0 15px 45px rgba(0,0,0,0.8); z-index: 1; transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease; margin-bottom: 1.5rem;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 25px 60px rgba(0,0,0,0.95), 0 0 30px rgba(193,98,30,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 45px rgba(0,0,0,0.8)';">
            <div style="position: absolute; inset: 0; overflow: hidden; z-index: 0;">
                <img src="<?php echo esc_url($general_cover); ?>" alt="Todas las Fotografías" loading="eager" decoding="async" data-covers="<?php echo esc_attr(wp_json_encode($all_covers)); ?>" class="img-cover gallery-cover-img js-random-cover" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);" />
            </div>
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.2) 60%, rgba(0,0,0,0.05) 100%); z-index: 1; pointer-events: none;"></div>
            
            <div style="position: relative; z-index: 2; display: flex; justify-content: flex-start; align-items: flex-end; width: 100%;">
                <h3 style="font-family: var(--font-heading); font-size: clamp(1.6rem, 2.5vw, 2.2rem); margin: 0; color: #ffffff; text-shadow: 0 2px 8px rgba(0,0,0,0.8); font-weight: 400;">Ver Toda la Colección</h3>
            </div>
        </div>

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
                font-size: clamp(1.2rem, 2vw, 2rem);
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
            .panel-title {
                margin: 0; 
                font-size: clamp(1.5rem, 2.5vw, 2rem); 
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
            $shuffled_keys = array_keys($categories);
            shuffle($shuffled_keys);
            
            foreach ($shuffled_keys as $slug): 
                $cat = $categories[$slug];
            ?>
            <div class="espacio-panel gallery-tag-card" data-category="<?php echo esc_attr($slug); ?>">
                <img src="<?php echo esc_url($cat['cover']); ?>" alt="<?php echo esc_attr($cat['title']); ?>" class="img-cover js-random-cover" loading="lazy" decoding="async" data-covers="<?php echo esc_attr(wp_json_encode(!empty($cat['covers']) ? $cat['covers'] : array($cat['cover']))); ?>" />
                <div class="panel-overlay"></div>
                
                <div class="panel-spine">
                    <?php echo esc_html($cat['title']); ?>
                </div>
                
                <div class="panel-content">
                    <div class="panel-text-wrapper">
                        <span class="capacity-badge" style="display:inline-block; font-size:0.75rem; margin-bottom:1rem;">
                            Galería
                        </span>
                        <h3 class="panel-title">
                            <?php echo esc_html($cat['title']); ?>
                        </h3>
                        <span class="btn-view">Explorar Fotografías &rarr;</span>
                    </div>
                </div>
            </div>
            <?php 
            endforeach; 
            ?>
        </div>
    </div>

    <!-- VISTA 2: SUBPÁGINA CON 2 COLUMNAS (GRID DE FOTOS + PANEL LATERAL DERECHO DE CHECKBOX) -->
    <div id="gallery-subpage-view" style="display: none;">
        <div style="margin-bottom: 2.5rem;">
            <button id="btn-back-categories" style="background: rgba(17,17,17,0.9); border: 1px solid rgba(193,98,30,0.35); color: var(--color-accent); padding: 0.7rem 1.4rem; border-radius: 999px; cursor: pointer; font-size: 0.88rem; font-weight: 600; letter-spacing: 0.6px; display: inline-flex; align-items: center; gap: 0.6rem; transition: all 0.3s ease;" onmouseover="this.style.background='var(--color-accent)'; this.style.color='#000000';" onmouseout="this.style.background='rgba(17,17,17,0.9)'; this.style.color='var(--color-accent)';">
                <span>←</span> Volver a Categorías
            </button>
        </div>

        <div style="margin-bottom: 1.4rem; text-align: center;">
            <span id="subpage-subtitle" class="text-script" style="font-size: clamp(1.2rem, 2.4vw, 1.7rem); color: var(--color-accent);"></span>
            <h2 id="subpage-title" class="text-hero" style="margin-top: 0.15rem; font-size: clamp(1.6rem, 3.2vw, 2.4rem);"></h2>
        </div>

        <style>
            .gallery-filter-bar {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                gap: 0.45rem;
                margin: 0 auto 1.8rem;
                max-width: 920px;
            }
            .sidebar-filter-option {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                cursor: pointer;
                padding: 0.38rem 0.75rem;
                border-radius: 999px;
                border: 1px solid rgba(255,255,255,0.12);
                background: rgba(17,17,17,0.72);
                color: rgba(255,255,255,0.78);
                transition: all 0.25s ease;
                user-select: none;
                font-size: 0.78rem;
                letter-spacing: 0.04em;
                line-height: 1.2;
            }
            .sidebar-filter-option.is-active {
                background: rgba(193,98,30,0.16);
                border-color: rgba(193,98,30,0.55);
                color: #fff;
            }
            @media (max-width: 767px) {
                .gallery-filter-bar { gap: 0.35rem; margin-bottom: 1.2rem; }
                .sidebar-filter-option { padding: 0.3rem 0.58rem; font-size: 0.7rem; }
                #subpage-photo-grid .gallery-item { height: 168px; }
                #subpage-photo-grid .gallery-item:nth-child(5n) { height: 196px; }
            }
            .sidebar-filter-option .sidebar-cb {
                accent-color: #c1621e;
                width: 13px;
                height: 13px;
                cursor: pointer;
                margin: 0;
            }
            #subpage-photo-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.7rem;
                width: 100%;
            }
            @media (min-width: 768px) {
                #subpage-photo-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 0.9rem;
                }
            }
            @media (min-width: 1280px) {
                #subpage-photo-grid {
                    grid-template-columns: repeat(4, minmax(0, 1fr));
                    gap: 1rem;
                }
            }
            #subpage-photo-grid .gallery-item {
                height: clamp(180px, 28vw, 320px);
                cursor: zoom-in;
                overflow: hidden;
                border-radius: 12px;
                background: #111;
                border: 1px solid rgba(193,98,30,0.16);
                position: relative;
            }
            #subpage-photo-grid .gallery-item:nth-child(5n) {
                height: clamp(220px, 34vw, 380px);
            }
            #subpage-photo-grid .gallery-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.55s cubic-bezier(0.25, 1, 0.5, 1);
            }
            #subpage-photo-grid .gallery-item:hover img {
                transform: scale(1.06);
            }
        </style>

        <div class="gallery-filter-bar" id="gallery-sidebar-filters">
            <label class="sidebar-filter-option" data-tag="all">
                <input type="checkbox" class="sidebar-cb" value="all" />
                <span>Todas</span>
            </label>
            <?php foreach ($categories as $cat_slug => $cat_data): ?>
            <label class="sidebar-filter-option" data-tag="<?php echo esc_attr($cat_slug); ?>">
                <input type="checkbox" class="sidebar-cb" value="<?php echo esc_attr($cat_slug); ?>" />
                <span><?php echo esc_html($cat_data['title']); ?></span>
            </label>
            <?php endforeach; ?>
        </div>

        <div id="subpage-photo-grid">
            <?php if (!empty($gallery_items_data)): ?>
                <?php foreach ($gallery_items_data as $item): ?>
                    <div class="<?php echo esc_attr($item['class']); ?>" data-tag="<?php echo esc_attr($item['tag'] ?? ''); ?>">
                        <a href="<?php echo esc_url($item['full']); ?>" class="glightbox" data-gallery="subpage-gallery" style="display: block; width: 100%; height: 100%; cursor: zoom-in;">
                            <img src="<?php echo esc_url($item['thumb']); ?>" alt="<?php echo esc_attr($item['title']); ?>" loading="lazy" decoding="async"<?php echo !empty($item['srcset']) ? ' srcset="' . esc_attr($item['srcset']) . '"' : ''; ?><?php echo !empty($item['sizes']) ? ' sizes="' . esc_attr($item['sizes']) . '"' : ''; ?> style="pointer-events: none;" />
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
    (function () {
        const pick = (list) => list[Math.floor(Math.random() * list.length)];
        document.querySelectorAll('.js-random-cover').forEach((img) => {
            try {
                const covers = JSON.parse(img.getAttribute('data-covers') || '[]');
                if (covers.length) {
                    img.src = pick(covers);
                }
            } catch (e) {}
        });
        const shuffleChildren = (parent) => {
            const nodes = Array.from(parent.children);
            for (let i = nodes.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                const tmp = nodes[i];
                nodes[i] = nodes[j];
                nodes[j] = tmp;
            }
            nodes.forEach((node) => parent.appendChild(node));
        };
        const acc = document.querySelector('.espacios-accordion');
        if (acc) shuffleChildren(acc);
        const grid = document.getElementById('subpage-photo-grid');
        if (grid) shuffleChildren(grid);
    })();
    document.addEventListener('DOMContentLoaded', () => {
        const catCards = document.querySelectorAll('.gallery-tag-card');
        const categoriesView = document.getElementById('gallery-categories-view');
        const subpageView = document.getElementById('gallery-subpage-view');
        const btnBack = document.getElementById('btn-back-categories');
        const subpageTitle = document.getElementById('subpage-title');
        const subpageSubtitle = document.getElementById('subpage-subtitle');
        const sidebarOptions = document.querySelectorAll('.sidebar-filter-option');
        const photoItems = document.querySelectorAll('#subpage-photo-grid .gallery-item');

        const catData = {
            'all': { title: 'Todas las Fotografías', subtitle: 'Colección General' },
            <?php foreach($categories as $s => $c): ?>
            '<?php echo esc_js($s); ?>': { title: '<?php echo esc_js($c['title']); ?>', subtitle: '<?php echo esc_js($c['subtitle']); ?>' },
            <?php endforeach; ?>
        };

        let activeSlugs = new Set(['all']);

        const applyFilters = () => {
            sidebarOptions.forEach(opt => {
                const optTag = opt.getAttribute('data-tag');
                const cb = opt.querySelector('.sidebar-cb');
                const isActive = activeSlugs.has(optTag);
                cb.checked = isActive;
                opt.classList.toggle('is-active', isActive);
            });

            if (activeSlugs.has('all') || activeSlugs.size === 0) {
                subpageTitle.textContent = catData['all'].title;
                subpageSubtitle.textContent = catData['all'].subtitle;
            } else if (activeSlugs.size === 1) {
                const slug = Array.from(activeSlugs)[0];
                subpageTitle.textContent = catData[slug]?.title || slug;
                subpageSubtitle.textContent = catData[slug]?.subtitle || 'Colección';
            } else {
                const titles = Array.from(activeSlugs).map(slug => catData[slug]?.title || slug);
                subpageTitle.textContent = titles.join(' + ');
                subpageSubtitle.textContent = 'Filtro combinado';
            }

            // Filtrar instantáneamente fotos (condición AND)
            photoItems.forEach(item => {
                item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                let match = true;
                
                if (!activeSlugs.has('all') && activeSlugs.size > 0) {
                    activeSlugs.forEach(slug => {
                        // For it to be a match in an AND condition, the item MUST have this tag class
                        if (!item.classList.contains('tag-' + slug) && !item.classList.contains(slug) && item.getAttribute('data-tag') !== slug) {
                            match = false;
                        }
                    });
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

            // Re-inicializar GLightbox
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

        const toggleTag = (slug) => {
            if (slug === 'all') {
                activeSlugs.clear();
                activeSlugs.add('all');
            } else {
                activeSlugs.delete('all');
                if (activeSlugs.has(slug)) {
                    activeSlugs.delete(slug);
                    if (activeSlugs.size === 0) activeSlugs.add('all');
                } else {
                    activeSlugs.add(slug);
                }
            }
            applyFilters();
        };

        const showCategorySubpage = (slug) => {
            categoriesView.style.display = 'none';
            subpageView.style.display = 'block';
            activeSlugs.clear();
            activeSlugs.add(slug);
            applyFilters();
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
                toggleTag(slug);
            });
        });

        if (btnBack) {
            btnBack.addEventListener('click', () => {
                subpageView.style.display = 'none';
                categoriesView.style.display = 'block';
                window.scrollTo({ top: 100, behavior: 'smooth' });
            });
        }

        // Accordion animation logic
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
        });
        window.addEventListener('resize', resetFlex);
        resetFlex();
    });
    </script>

</div>



<?php get_footer(); ?>

