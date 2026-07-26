<?php
/**
 * Casa de Piedra Luxury Theme - Functions
 */

// 1. Setup Theme
function casadepiedra_flush_rewrite() {
    if (!get_option('casadepiedra_rewrite_flushed')) {
        flush_rewrite_rules();
        update_option('casadepiedra_rewrite_flushed', true);
    }
}
add_action('admin_init', 'casadepiedra_flush_rewrite');

function casadepiedra_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    register_nav_menus(array(
        'menu-principal' => 'Menú Principal Premium',
        'menu-footer' => 'Menú Footer'
    ));
}
add_action('after_setup_theme', 'casadepiedra_setup');

/**
 * Soporte Universal para Túneles de Cloudflare (Live Links / Local by Flywheel) y Múltiples Dominios:
 * Intercepta y corrige dinámicamente todas las URLs de imágenes (logos, portadas, metadatos y medios)
 * para que siempre utilicen el dominio activo actual en lugar de intentar cargar desde .local o localhost.
 */
function casadepiedra_fix_tunnel_urls($value) {
    if (is_string($value) && strpos($value, '/wp-content/') !== false) {
        // Redirigir logotipos obsoletos, faltantes o solo-escudo al logotipo horizontal oficial entregado por el usuario
        if (strpos($value, 'Logo_Header.png') !== false || strpos($value, 'Logo_Footer.png') !== false || strpos($value, '/2023/09/') !== false || strpos($value, 'Recurso-5') !== false || strpos($value, 'CDP-LOGO') !== false) {
            return get_template_directory_uri() . '/assets/images/logo-navbar-oficial.png';
        }
        $parts = explode('/wp-content/', $value);
        if (count($parts) > 1 && !empty($parts[1])) {
            return content_url() . '/' . ltrim($parts[1], '/');
        }
    }
    return $value;
}

// 1. Filtrar todas las opciones de imágenes y logos del Panel Casa de Piedra
$casa_image_options = array(
    'casa_opt_global_logo',
    'casa_opt_global_transition_logo',
    'casa_opt_home_hero_img',
    'casa_opt_nosotros_portada',
    'casa_opt_nosotros_img1',
    'casa_opt_nosotros_img2',
    'casa_opt_contacto_bg'
);
foreach ($casa_image_options as $opt) {
    add_filter("option_{$opt}", 'casadepiedra_fix_tunnel_urls', 99);
}

// Forzar el logotipo horizontal oficial actual sin importar el valor en BD
add_filter('option_casa_opt_global_logo', function($value) {
    return get_template_directory_uri() . '/assets/images/logo-navbar-oficial.png';
}, 100);
add_filter('option_casa_opt_global_transition_logo', function($value) {
    return get_template_directory_uri() . '/assets/images/escudo-animacion-blanco.png';
}, 100);

// 2. Filtrar metadatos de imágenes en Custom Post Types (Restaurantes, Espacios, Eventos)
add_filter('get_post_metadata', function($value, $object_id, $meta_key, $single) {
    if (in_array($meta_key, array('_restaurante_logo', '_espacio_portada', '_restaurante_portada', '_casadepiedra_gallery_ids')) && !empty($value)) {
        if (is_string($value) && strpos($value, '/wp-content/') !== false) {
            return casadepiedra_fix_tunnel_urls($value);
        } elseif (is_array($value) && isset($value[0]) && is_string($value[0]) && strpos($value[0], '/wp-content/') !== false) {
            $value[0] = casadepiedra_fix_tunnel_urls($value[0]);
            return $value;
        }
    }
    return $value;
}, 99, 4);

// 3. Filtrar URLs de adjuntos nativos de WordPress (Media Library, thumbnails, galerías)
add_filter('wp_get_attachment_url', 'casadepiedra_fix_tunnel_urls', 99);
add_filter('wp_get_attachment_image_src', function($image) {
    if (is_array($image) && isset($image[0])) {
        $image[0] = casadepiedra_fix_tunnel_urls($image[0]);
    }
    return $image;
}, 99);

// Dynamically add Espacios and Restaurantes to menu
function casadepiedra_add_dynamic_dropdowns($items, $args) {
    if ($args->theme_location == 'menu-principal') {
        $espacios_parent = null;
        $restaurantes_parent = null;
        
        foreach ($items as $item) {
            $title = strtolower(trim($item->title));
            if ($title === 'espacios') {
                $espacios_parent = $item->ID;
                $item->classes[] = 'menu-item-has-children';
            } elseif ($title === 'restaurantes') {
                $restaurantes_parent = $item->ID;
                $item->classes[] = 'menu-item-has-children';
            }
        }
        
        // Espacios
        if ($espacios_parent) {
            $espacios = get_posts(array('post_type' => 'espacios', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'));
            $order = 1;
            foreach ($espacios as $espacio) {
                $new_item = new stdClass();
                $new_item->ID = 100000 + $espacio->ID; 
                $new_item->db_id = $new_item->ID;
                $new_item->menu_item_parent = $espacios_parent;
                $new_item->object_id = $espacio->ID;
                $new_item->object = 'espacios';
                $new_item->type = 'post_type';
                $new_item->type_label = 'Espacio';
                $new_item->title = $espacio->post_title;
                $new_item->url = get_permalink($espacio->ID);
                $new_item->target = '';
                $new_item->attr_title = '';
                $new_item->description = '';
                $new_item->classes = array('dropdown-espacio-item');
                $new_item->xfn = '';
                $new_item->menu_order = 1000 + $order; 
                $new_item->current = false; $new_item->current_item_ancestor = false; $new_item->current_item_parent = false; $new_item->has_children = false;
                $items[] = wp_setup_nav_menu_item($new_item);
                $order++;
            }
        }

        // Restaurantes
        if ($restaurantes_parent) {
            $restaurantes = get_posts(array('post_type' => 'restaurantes', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC'));
            $order = 1;
            foreach ($restaurantes as $restaurante) {
                $new_item = new stdClass();
                $new_item->ID = 200000 + $restaurante->ID; 
                $new_item->db_id = $new_item->ID;
                $new_item->menu_item_parent = $restaurantes_parent;
                $new_item->object_id = $restaurante->ID;
                $new_item->object = 'restaurantes';
                $new_item->type = 'post_type';
                $new_item->type_label = 'Restaurante';
                
                $logo_url = casadepiedra_resolve_restaurante_logo($restaurante->ID);
                $short_name = casadepiedra_resolve_restaurante_short_name($restaurante->post_title);
                
                if ($logo_url) {
                    $title_html = '<span style="display:flex; align-items:center; width:100%;"><span style="display:flex; align-items:center; justify-content:center; width:75px; height:32px; flex-shrink:0; margin-right:14px;"><img src="' . esc_url($logo_url) . '" alt="' . esc_attr($short_name) . '" style="max-width:70px; max-height:26px; width:auto; height:auto; object-fit:contain; filter:drop-shadow(0 2px 6px rgba(0,0,0,0.8));" /></span><span style="font-weight:600; letter-spacing:1px; white-space:nowrap;">' . esc_html($short_name) . '</span></span>';
                } else {
                    $title_html = esc_html($short_name);
                }
                $new_item->title = $title_html;
                
                $new_item->url = get_permalink($restaurante->ID);
                $new_item->target = '';
                $new_item->attr_title = '';
                $new_item->description = '';
                $new_item->classes = array('dropdown-restaurante-item');
                $new_item->xfn = '';
                $new_item->menu_order = 2000 + $order; 
                $new_item->current = false; $new_item->current_item_ancestor = false; $new_item->current_item_parent = false; $new_item->has_children = false;
                $items[] = wp_setup_nav_menu_item($new_item);
                $order++;
            }
        }
    }
    return $items;
}

function casadepiedra_resolve_restaurante_logo($post_id) {
    $logo = get_post_meta($post_id, '_restaurante_logo', true);
    if (!empty($logo)) {
        return $logo;
    }
    $title = get_the_title($post_id);
    if (stripos($title, 'Valentina') !== false) {
        return get_template_directory_uri() . '/assets/images/logos/valentina-logo.png';
    }
    if (stripos($title, 'Casa M') !== false || stripos($title, 'Casa-Mia') !== false) {
        return get_template_directory_uri() . '/assets/images/logos/casa-mia-logo.png';
    }
    if (stripos($title, 'Argentilia') !== false) {
        return get_template_directory_uri() . '/assets/images/logos/argentilia-logo.png';
    }
    if (stripos($title, 'Lucio') !== false) {
        return get_template_directory_uri() . '/assets/images/logos/lucio-logo.png';
    }
    if (stripos($title, 'Manolo') !== false) {
        return get_template_directory_uri() . '/assets/images/logos/manolo-logo.png';
    }
    if (stripos($title, 'Sato') !== false) {
        return get_template_directory_uri() . '/assets/images/logos/sato-logo.png';
    }
    return '';
}

function casadepiedra_resolve_restaurante_short_name($title) {
    if (stripos($title, 'Valentina') !== false) return 'Valentina';
    if (stripos($title, 'Casa M') !== false || stripos($title, 'Casa-Mia') !== false) return 'Casa Mía';
    if (stripos($title, 'Argentilia') !== false) return 'Argentilia';
    if (stripos($title, 'Lucio') !== false) return 'Lucio';
    if (stripos($title, 'Manolo') !== false) return 'Manolo';
    if (stripos($title, 'Sato') !== false) return 'Sato';
    
    $parts = explode(' ', trim($title));
    if (count($parts) > 2) {
        return $parts[0];
    }
    return $title;
}

function casadepiedra_get_google_reviews_url($post_id) {
    $custom = get_post_meta($post_id, '_restaurante_google_reviews_url', true);
    if (!empty($custom)) {
        return $custom;
    }
    $title = get_the_title($post_id);
    if (stripos($title, 'Sato') !== false) {
        return 'https://www.google.com/maps/place/Sato+Casa+de+Piedra/@21.1596493,-101.7019348,1682m/data=!3m2!1e3!5s0x842bbf530842ac4b:0x4642591264eb2eec!4m8!3m7!1s0x842bbf53719c9f9d:0x1cfaf89360074060!8m2!3d21.1596493!4d-101.6993545!9m1!1b1!16s%2Fg%2F11bw62cb4y?entry=ttu&g_ep=EgoyMDI2MDcwNy4wIKXMDSoASAFQAw%3D%3D';
    }
    return 'https://www.google.com/maps/search/Casa+de+Piedra+Le%C3%B3n+Guanajuato+Restaurantes';
}

function casadepiedra_resolve_restaurante_card_img($post_id) {
    $custom = get_post_meta($post_id, '_restaurante_card_image', true);
    if (!empty($custom)) {
        return $custom;
    }
    $title = get_the_title($post_id);
    if (stripos($title, 'Sato') !== false) {
        return get_template_directory_uri() . '/assets/images/sato/card.jpg';
    }
    return has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'large') : '';
}

function casadepiedra_resolve_restaurante_hero($post_id) {
    $custom = get_post_meta($post_id, '_restaurante_hero_image', true);
    if (!empty($custom)) {
        return $custom;
    }
    $title = get_the_title($post_id);
    if (stripos($title, 'Sato') !== false) {
        return get_template_directory_uri() . '/assets/images/sato/banner.jpg';
    }
    return has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : get_template_directory_uri() . '/assets/images/restaurante_hero_1779523131713.png';
}


function casadepiedra_resolve_restaurante_gallery($post_id) {
    $title = get_the_title($post_id);
    if (stripos($title, 'Sato') !== false) {
        return array(
            get_template_directory_uri() . '/assets/images/sato/gallery-1.jpg',
            get_template_directory_uri() . '/assets/images/sato/gallery-2.jpg',
            get_template_directory_uri() . '/assets/images/sato/gallery-3.jpg',
            get_template_directory_uri() . '/assets/images/sato/card.jpg',
            get_template_directory_uri() . '/assets/images/sato/banner.jpg'
        );
    }
    return array();
}

add_filter('wp_nav_menu_objects', 'casadepiedra_add_dynamic_dropdowns', 10, 2);

// 2. Enqueue Scripts & Styles
function casadepiedra_scripts() {
    // Estilos principales
    wp_enqueue_style('casadepiedra-style', get_stylesheet_uri(), array(), '3.5.0');
    
    // GSAP para animaciones
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), null, true);
    wp_enqueue_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), null, true);
    // JS Core and libraries
    wp_enqueue_script('imagesloaded');
    wp_enqueue_script('masonry');
    wp_enqueue_style('glightbox-css', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css', array(), '3.2.0');
    wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js', array(), '3.2.0', true);
    
    // Anime.js para transiciones fluidas de página
    wp_enqueue_script('animejs', 'https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js', array(), null, true);
    
    // Lenis Smooth Scroll
    wp_enqueue_script('lenis', 'https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.29/bundled/lenis.min.js', array(), null, true);
    
    // Vanilla JS App
    wp_enqueue_script('casadepiedra-app', get_template_directory_uri() . '/assets/js/app.js', array('gsap', 'gsap-scrolltrigger', 'glightbox', 'masonry', 'animejs', 'lenis'), '3.5.2', true);
    // Favicon Animado Continues Transmutation
    wp_enqueue_script('casadepiedra-animated-favicon', get_template_directory_uri() . '/assets/js/animated-favicon.js', array(), '3.6.0', true);
    wp_add_inline_script('casadepiedra-animated-favicon', 'window.casadepiedraThemeUrl = "' . esc_js(get_template_directory_uri()) . '";', 'before');
}
add_action('wp_enqueue_scripts', 'casadepiedra_scripts');

// Desactivar el favicon predeterminado de WordPress para evitar conflictos con nuestro Favicon programado
remove_action('wp_head', 'wp_site_icon', 99);
add_filter('site_icon_meta_tags', '__return_empty_array', 999);

// Output del Favicon Inicial Transparente de Casa de Piedra en <head>
add_action('wp_head', function() {
    $fav_init = get_template_directory_uri() . '/assets/images/favicons/favicon-1.png';
    echo "\n<!-- Ex Hacienda Casa de Piedra Favicon Oficial -->\n";
    echo '<link id="casa-dynamic-favicon" rel="icon" type="image/png" href="' . esc_url($fav_init) . '" />' . "\n";
}, 1);

function casadepiedra_admin_scripts($hook) {
    // Load on post pages AND our new admin panel pages
    if (strpos($hook, 'casa-panel') !== false || in_array($hook, array('post.php', 'post-new.php'))) {
        wp_enqueue_media();
        wp_enqueue_script('casadepiedra-admin-gallery', get_template_directory_uri() . '/assets/js/admin-gallery.js', array('jquery'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'casadepiedra_admin_scripts');

// 3. Register Custom Post Types & Taxonomies
function casadepiedra_register_cpts() {
    // Taxonomy: Media Tags (for filtering photos)
    register_taxonomy('galeria_tag', array('attachment'), array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Etiquetas de Galería',
            'singular_name' => 'Etiqueta de Galería',
            'search_items' => 'Buscar Etiquetas',
            'all_items' => 'Todas las Etiquetas',
            'edit_item' => 'Editar Etiqueta',
            'update_item' => 'Actualizar Etiqueta',
            'add_new_item' => 'Añadir Nueva Etiqueta',
            'new_item_name' => 'Nombre de Nueva Etiqueta',
            'menu_name' => 'Etiquetas de Fotos',
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'show_in_rest' => true,
    ));

    // Seed official primary gallery tags
    if (!get_option('casadepiedra_seeded_official_5_tags_v1')) {
        $default_tags = array(
            'bodas' => 'Bodas',
            'graduaciones' => 'Graduaciones',
            'sociales' => 'Sociales',
            'empresariales' => 'Empresariales',
            'eventos-especiales' => 'Eventos Especiales'
        );
        foreach ($default_tags as $slug => $name) {
            if (!term_exists($slug, 'galeria_tag')) {
                wp_insert_term($name, 'galeria_tag', array('slug' => $slug));
            }
        }
        update_option('casadepiedra_seeded_official_5_tags_v1', true);
    }

    // CPT: Espacios
    register_post_type('espacios', array(
        'labels' => array(
            'name' => 'Espacios',
            'singular_name' => 'Espacio',
            'add_new' => 'Añadir Nuevo',
            'add_new_item' => 'Añadir Nuevo Espacio',
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_menu' => false,
        'menu_icon' => 'dashicons-location-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite' => array('slug' => 'espacios'),
    ));

    // CPT: Restaurantes
    register_post_type('restaurantes', array(
        'labels' => array(
            'name' => 'Restaurantes',
            'singular_name' => 'Restaurante',
            'add_new' => 'Añadir Nuevo',
            'add_new_item' => 'Añadir Nuevo Restaurante',
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_menu' => false,
        'menu_icon' => 'dashicons-rest-api',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite' => array('slug' => 'restaurantes'),
    ));

    // CPT: Eventos
    register_post_type('eventos', array(
        'labels' => array(
            'name' => 'Eventos',
            'singular_name' => 'Evento',
            'add_new' => 'Añadir Nuevo',
            'add_new_item' => 'Añadir Nuevo Evento',
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_menu' => false,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite' => array('slug' => 'eventos'),
    ));
}
add_action('init', 'casadepiedra_register_cpts');

// 4. Meta Boxes para Restaurantes
function casadepiedra_add_restaurante_meta() {
    add_meta_box('casadepiedra_restaurante_info', 'Información del Restaurante', 'casadepiedra_restaurante_meta_callback', 'restaurantes', 'normal', 'high');
}
add_action('add_meta_boxes', 'casadepiedra_add_restaurante_meta');

function casadepiedra_restaurante_meta_callback($post) {
    wp_nonce_field('casadepiedra_save_restaurante_meta', 'casadepiedra_restaurante_meta_nonce');
    
    $menu_url = get_post_meta($post->ID, '_restaurante_menu', true);
    $telefono = get_post_meta($post->ID, '_restaurante_telefono', true);
    $horarios = get_post_meta($post->ID, '_restaurante_horario', true);
    $reserva_tipo = get_post_meta($post->ID, '_restaurante_reserva_tipo', true) ?: 'web';
    $reserva_valor = get_post_meta($post->ID, '_restaurante_reserva_valor', true);
    $logo_url = get_post_meta($post->ID, '_restaurante_logo', true);
    $hero_image = get_post_meta($post->ID, '_restaurante_hero_image', true);
    $card_image = get_post_meta($post->ID, '_restaurante_card_image', true);
    $google_reviews_url = get_post_meta($post->ID, '_restaurante_google_reviews_url', true);
    $rating = get_post_meta($post->ID, '_restaurante_rating', true) ?: '4.9';
    $cocina = get_post_meta($post->ID, '_restaurante_cocina', true);

    echo '<style>
        .cdp-meta-box { font-family: "Helvetica Neue", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #0f172a; }
        .cdp-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; margin-bottom: 24px; }
        .cdp-meta-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); transition: all 0.2s ease; }
        .cdp-meta-card:hover { border-color: #cbd5e1; box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
        .cdp-meta-card h4 { margin: 0 0 16px 0; font-size: 15px; font-weight: 800; color: #0f172a; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; display: flex; align-items: center; gap: 8px; }
        .cdp-meta-field { margin-bottom: 16px; }
        .cdp-meta-field label { font-weight: 700; display: block; margin-bottom: 6px; color: #1e293b; font-size: 13px; }
        .cdp-meta-field input[type="text"], .cdp-meta-field input[type="url"], .cdp-meta-field select { width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 13px; color: #0f172a; background: #f8fafc; transition: all 0.2s ease; }
        .cdp-meta-field input[type="text"]:focus, .cdp-meta-field input[type="url"]:focus, .cdp-meta-field select:focus { border-color: #c5a059; background: #ffffff; box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.15); }
        .cdp-img-preview { max-width: 100%; max-height: 140px; border-radius: 10px; margin-top: 10px; display: block; background: #0f172a; padding: 8px; object-fit: contain; border: 1px solid #334155; }
    </style>';

    echo '<div class="cdp-meta-box">';
    
    // FILA 1: IMÁGENES Y BRANDING
    echo '<div class="cdp-meta-grid">';
    
    // TARJETA BRANDING (LOGO & COCINA & RATING)
    echo '<div class="cdp-meta-card">';
    echo '<h4>🏛️ Identidad & Calificación del Restaurante</h4>';
    
    echo '<div class="cdp-meta-field"><label for="restaurante_cocina">Especialidad Gastronómica (Etiqueta Amarilla):</label>';
    echo '<input type="text" id="restaurante_cocina" name="restaurante_cocina" value="' . esc_attr($cocina) . '" placeholder="Ej: Alta Cocina Japonesa & Nikkei" /></div>';

    echo '<div class="cdp-meta-field"><label for="restaurante_rating">Calificación ⭐:</label>';
    echo '<input type="text" id="restaurante_rating" name="restaurante_rating" value="' . esc_attr($rating) . '" placeholder="Ej: 4.9" /></div>';

    echo '<div class="cdp-meta-field"><label>Logo Oficial PNG/SVG:</label>';
    echo '<input type="hidden" id="restaurante_logo" name="restaurante_logo" value="' . esc_attr($logo_url) . '" />';
    echo '<button type="button" class="button button-primary" id="btn_upload_restaurante_logo">Seleccionar Logo</button> ';
    echo '<button type="button" class="button" id="btn_remove_restaurante_logo" style="display: ' . ($logo_url ? 'inline-block' : 'none') . ';">Quitar</button>';
    echo '<img id="restaurante_logo_preview" class="cdp-img-preview" src="' . esc_url($logo_url) . '" style="display: ' . ($logo_url ? 'block' : 'none') . ';" /></div>';
    echo '</div>';

    // TARJETA BANNER HERO & TARJETA CATÁLOGO
    echo '<div class="cdp-meta-card">';
    echo '<h4>🖼️ Fotografías Principales (Banner & Tarjeta)</h4>';
    
    $nombre_rest = !empty($post->post_title) ? $post->post_title : 'Restaurante';
    echo '<div class="cdp-meta-field"><label>Imagen de Portada "' . esc_html($nombre_rest) . '":</label>';
    echo '<input type="hidden" id="restaurante_hero_image" name="restaurante_hero_image" value="' . esc_attr($hero_image) . '" />';
    echo '<button type="button" class="button button-secondary" id="btn_upload_rest_hero">Seleccionar Portada Hero</button> ';
    echo '<button type="button" class="button" id="btn_remove_rest_hero" style="display: ' . ($hero_image ? 'inline-block' : 'none') . ';">Quitar</button>';
    echo '<img id="rest_hero_preview" class="cdp-img-preview" src="' . esc_url($hero_image) . '" style="display: ' . ($hero_image ? 'block' : 'none') . ';" /></div>';

    echo '<div class="cdp-meta-field"><label>Imagen Tarjeta de Catálogo:</label>';
    echo '<input type="hidden" id="restaurante_card_image" name="restaurante_card_image" value="' . esc_attr($card_image) . '" />';
    echo '<button type="button" class="button button-secondary" id="btn_upload_rest_card">Seleccionar Imagen Tarjeta</button> ';
    echo '<button type="button" class="button" id="btn_remove_rest_card" style="display: ' . ($card_image ? 'inline-block' : 'none') . ';">Quitar</button>';
    echo '<img id="rest_card_preview" class="cdp-img-preview" src="' . esc_url($card_image) . '" style="display: ' . ($card_image ? 'block' : 'none') . ';" /></div>';
    echo '</div>';

    echo '</div>'; // fin fila 1

    // FILA 2: ENLACES DIRECTOS & RESERVACIÓN & RESEÑAS
    echo '<div class="cdp-meta-grid">';

    // TARJETA BOTONES DE ACCIÓN (RESEÑAS GOOGLE & MENÚ)
    echo '<div class="cdp-meta-card">';
    echo '<h4>🌟 Reseñas Google Maps & Menú Digital</h4>';

    echo '<div class="cdp-meta-field"><label for="restaurante_google_reviews_url">Enlace Directo a Reseñas en Google Maps (Botón ⭐):</label>';
    echo '<input type="url" id="restaurante_google_reviews_url" name="restaurante_google_reviews_url" value="' . esc_attr($google_reviews_url) . '" placeholder="https://www.google.com/maps/place/..." /></div>';

    echo '<div class="cdp-meta-field"><label for="restaurante_menu">Enlace al Menú Gastronómico (URL o PDF):</label>';
    echo '<div style="display:flex; gap:8px;"><input type="url" id="restaurante_menu" name="restaurante_menu" value="' . esc_attr($menu_url) . '" />';
    echo '<button type="button" id="btn_upload_rest_menu_pdf" class="button button-secondary">Subir PDF</button></div></div>';
    echo '</div>';

    // TARJETA RESERVACIÓN & HORARIOS
    echo '<div class="cdp-meta-card">';
    echo '<h4>📅 Reservación & Contacto Directo</h4>';

    echo '<div class="cdp-meta-field"><label for="restaurante_reserva_tipo">Tipo de Reserva:</label>';
    echo '<select id="restaurante_reserva_tipo" name="restaurante_reserva_tipo">';
    echo '<option value="web" ' . selected($reserva_tipo, 'web', false) . '>Sitio Web / Enlace Directo</option>';
    echo '<option value="whatsapp" ' . selected($reserva_tipo, 'whatsapp', false) . '>WhatsApp</option>';
    echo '<option value="tel" ' . selected($reserva_tipo, 'tel', false) . '>Llamada Telefónica</option>';
    echo '</select></div>';

    echo '<div class="cdp-meta-field"><label for="restaurante_reserva_valor">Enlace, Teléfono o WhatsApp para Reservar:</label>';
    echo '<input type="text" id="restaurante_reserva_valor" name="restaurante_reserva_valor" value="' . esc_attr($reserva_valor) . '" placeholder="Ej: https://api.whatsapp.com/send?phone=..." /></div>';

    echo '<div class="cdp-meta-field"><label for="restaurante_telefono">Teléfono:</label>';
    echo '<input type="text" id="restaurante_telefono" name="restaurante_telefono" value="' . esc_attr($telefono) . '" /></div>';

    echo '<div class="cdp-meta-field"><label for="restaurante_horario">Horarios de Atención:</label>';
    echo '<input type="text" id="restaurante_horario" name="restaurante_horario" value="' . esc_attr($horarios) . '" /></div>';
    echo '</div>';

    echo '</div>'; // fin fila 2

    echo '</div>';

    // SCRIPT DE MEDIOS WP PARA SUBIR/SELECCIONAR IMÁGENES
    echo '<script>
    jQuery(document).ready(function($){
        function bindMediaUploader(btnSelector, hiddenSelector, previewSelector, removeSelector) {
            $(btnSelector).on("click", function(e) {
                e.preventDefault();
                var frame = wp.media({ title: "Seleccionar Imagen", button: { text: "Usar esta imagen" }, multiple: false });
                frame.on("select", function() {
                    var att = frame.state().get("selection").first().toJSON();
                    $(hiddenSelector).val(att.url);
                    $(previewSelector).attr("src", att.url).show();
                    if(removeSelector) $(removeSelector).show();
                });
                frame.open();
            });
            if(removeSelector) {
                $(removeSelector).on("click", function(e) {
                    e.preventDefault();
                    $(hiddenSelector).val("");
                    $(previewSelector).hide();
                    $(this).hide();
                });
            }
        }
        bindMediaUploader("#btn_upload_restaurante_logo", "#restaurante_logo", "#restaurante_logo_preview", "#btn_remove_restaurante_logo");
        bindMediaUploader("#btn_upload_rest_hero", "#restaurante_hero_image", "#rest_hero_preview", "#btn_remove_rest_hero");
        bindMediaUploader("#btn_upload_rest_card", "#restaurante_card_image", "#rest_card_preview", "#btn_remove_rest_card");
        bindMediaUploader("#btn_upload_rest_menu_pdf", "#restaurante_menu");
    });
    </script>';
}

function casadepiedra_save_restaurante_meta($post_id) {
    if (!isset($_POST['casadepiedra_restaurante_meta_nonce']) || !wp_verify_nonce($_POST['casadepiedra_restaurante_meta_nonce'], 'casadepiedra_save_restaurante_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array(
        '_restaurante_menu' => 'restaurante_menu',
        '_restaurante_telefono' => 'restaurante_telefono',
        '_restaurante_horario' => 'restaurante_horario',
        '_restaurante_reserva_tipo' => 'restaurante_reserva_tipo',
        '_restaurante_reserva_valor' => 'restaurante_reserva_valor',
        '_restaurante_logo' => 'restaurante_logo',
        '_restaurante_hero_image' => 'restaurante_hero_image',
        '_restaurante_card_image' => 'restaurante_card_image',
        '_restaurante_google_reviews_url' => 'restaurante_google_reviews_url',
        '_restaurante_rating' => 'restaurante_rating',
        '_restaurante_cocina' => 'restaurante_cocina'
    );

    foreach ($fields as $meta_key => $post_key) {
        if (isset($_POST[$post_key])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$post_key]));
        }
    }
}
add_action('save_post_restaurantes', 'casadepiedra_save_restaurante_meta');

function casadepiedra_get_reserva_url($post_id) {
    $title = get_the_title($post_id);
    if (stripos($title, 'Sato') !== false) {
        return 'https://api.whatsapp.com/send?phone=5214773949444&text=!Hola!%20quiero%20hacer%20una%20reservación';
    }
    $tipo = get_post_meta($post_id, '_restaurante_reserva_tipo', true);
    $valor = get_post_meta($post_id, '_restaurante_reserva_valor', true);
    
    if (empty($valor)) return esc_url(home_url('/contacto'));
    
    if ($tipo === 'tel') {
        return 'tel:' . preg_replace('/[^0-9+]/', '', $valor);
    } elseif ($tipo === 'whatsapp') {
        return 'https://wa.me/' . preg_replace('/[^0-9]/', '', $valor);
    } else {
        return esc_url($valor);
    }
}

// 5. Cargar Panel de Administración Global
require_once get_template_directory() . '/inc/admin-panel.php';
require_once get_template_directory() . '/inc/seo.php';

// 7. Meta Box para Galería (Restaurantes y Espacios)
function casadepiedra_add_gallery_meta() { 
    $screens = array('restaurantes', 'espacios');
    foreach ($screens as $screen) {
        add_meta_box('casadepiedra_gallery', 'Galería de Imágenes Múltiples', 'casadepiedra_gallery_callback', $screen, 'normal', 'high');
    }
}
add_action('add_meta_boxes', 'casadepiedra_add_gallery_meta');

add_action('admin_head', function() {
    $screen = get_current_screen();
    if ($screen && in_array($screen->post_type, array('restaurantes', 'espacios', 'eventos'))) {
        echo '<style>
        .cdp-meta-box { font-family: "Helvetica Neue", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; color: #0f172a; }
        .cdp-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; margin-bottom: 24px; }
        .cdp-meta-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); transition: all 0.2s ease; }
        .cdp-meta-card:hover { border-color: #cbd5e1; box-shadow: 0 8px 20px rgba(0,0,0,0.06); }
        .cdp-meta-card h4 { margin: 0 0 16px 0; font-size: 15px; font-weight: 800; color: #0f172a; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; display: flex; align-items: center; gap: 8px; }
        .cdp-meta-field { margin-bottom: 16px; }
        .cdp-meta-field label { font-weight: 700; display: block; margin-bottom: 6px; color: #1e293b; font-size: 13px; }
        .cdp-meta-field input[type="text"], .cdp-meta-field input[type="url"], .cdp-meta-field input[type="datetime-local"], .cdp-meta-field select { width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 9px 12px; font-size: 13px; color: #0f172a; background: #f8fafc; transition: all 0.2s ease; }
        .cdp-meta-field input:focus, .cdp-meta-field select:focus { border-color: #c5a059; background: #ffffff; box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.15); }
        .cdp-img-preview { max-width: 100%; max-height: 140px; border-radius: 10px; margin-top: 10px; display: block; background: #0f172a; padding: 8px; object-fit: contain; border: 1px solid #334155; }
        .cdp-btn-gold { background: #c5a059 !important; color: #ffffff !important; border: none !important; padding: 9px 20px !important; border-radius: 8px !important; font-weight: 700 !important; cursor: pointer !important; box-shadow: 0 2px 8px rgba(197, 160, 89, 0.3) !important; font-size: 13px !important; }
        .cdp-btn-dark { background: #0f172a !important; color: #ffffff !important; border: none !important; padding: 9px 20px !important; border-radius: 8px !important; font-weight: 600 !important; cursor: pointer !important; font-size: 13px !important; }
        </style>';
    }
});

function casadepiedra_gallery_callback($post) {
    wp_nonce_field('casadepiedra_save_gallery_meta', 'casadepiedra_gallery_meta_nonce');
    $gallery_ids = get_post_meta($post->ID, '_casadepiedra_gallery_ids', true);
    
    echo '<div class="cdp-meta-box">';
    echo '<div class="cdp-meta-card">';
    echo '<h4>📸 Galería Mosaico de Imágenes Múltiples</h4>';
    echo '<p style="color:#64748b; font-size:13px; margin-bottom:16px;">Sube o selecciona múltiples fotografías para el mosaico dinámico del salón/restaurante. Puedes eliminar individualmente con el icono de papelera.</p>';
    echo '<div style="display:flex; gap:12px; margin-bottom:18px;">';
    echo '<input type="button" id="casadepiedra_upload_gallery_btn" class="cdp-btn-gold" value="+ Seleccionar / Subir Imágenes" /> ';
    echo '<input type="button" id="casadepiedra_clear_gallery_btn" class="cdp-btn-dark" value="🗑️ Limpiar Galería" />';
    echo '</div>';
    
    echo '<input type="hidden" id="casadepiedra_gallery_ids" name="_casadepiedra_gallery_ids" value="' . esc_attr($gallery_ids) . '" />';
    
    echo '<div id="casadepiedra_gallery_preview" style="display:flex; flex-wrap:wrap; gap:12px;">';
    if (!empty($gallery_ids)) {
        $ids_array = explode(',', $gallery_ids);
        foreach ($ids_array as $id) {
            $img = wp_get_attachment_image_src($id, 'thumbnail');
            if ($img) {
                echo '<div class="casa-gallery-item" data-id="'.esc_attr($id).'" style="display:inline-block; position:relative;"><img src="'.esc_url($img[0]).'" style="width:95px; height:75px; object-fit:cover; display:block; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.05);" /><button type="button" class="casa-remove-single-img-btn" data-id="'.esc_attr($id).'" title="Eliminar foto individual" style="position:absolute; top:-6px; right:-6px; background:#dc2626; color:#fff; border:2px solid #fff; border-radius:50%; width:24px; height:24px; font-size:12px; font-weight:bold; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,0.25); line-height:1;">🗑️</button></div>';
            }
        }
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

function casadepiedra_save_gallery_meta($post_id) {
    if (!isset($_POST['casadepiedra_gallery_meta_nonce']) || !wp_verify_nonce($_POST['casadepiedra_gallery_meta_nonce'], 'casadepiedra_save_gallery_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['_casadepiedra_gallery_ids'])) {
        update_post_meta($post_id, '_casadepiedra_gallery_ids', sanitize_text_field($_POST['_casadepiedra_gallery_ids']));
    }
}
add_action('save_post', 'casadepiedra_save_gallery_meta');

// 8. Meta Box para Espacios (Capacidad)
function casadepiedra_add_espacio_meta() {
    add_meta_box('casadepiedra_espacio_info', 'Información Ejecutiva del Espacio', 'casadepiedra_espacio_meta_callback', 'espacios', 'normal', 'high');
}
add_action('add_meta_boxes', 'casadepiedra_add_espacio_meta');

function casadepiedra_espacio_meta_callback($post) {
    wp_nonce_field('casadepiedra_save_espacio_meta', 'casadepiedra_espacio_meta_nonce');
    $portada = get_post_meta($post->ID, '_espacio_portada', true);
    $capacidad = get_post_meta($post->ID, '_espacio_capacidad', true);
    $rango_personas = get_post_meta($post->ID, '_espacio_rango_personas', true);
    $m2 = get_post_meta($post->ID, '_espacio_m2', true);
    $plano_pdf = get_post_meta($post->ID, '_espacio_plano_pdf', true);
    
    $nombre_espacio = !empty($post->post_title) ? $post->post_title : 'Espacio';

    echo '<div class="cdp-meta-box">';
    echo '<div class="cdp-meta-grid">';

    // TARJETA 1: IDENTIDAD Y PORTADA
    echo '<div class="cdp-meta-card">';
    echo '<h4>🏛️ Portada Institucional & Planos del Salón</h4>';
    
    echo '<div class="cdp-meta-field"><label for="espacio_portada">Imagen de Portada "' . esc_html($nombre_espacio) . '":</label>';
    echo '<div style="display:flex; gap:8px;">';
    echo '<input type="url" id="espacio_portada" name="espacio_portada" value="' . esc_attr($portada) . '" placeholder="https://..." />';
    echo '<button type="button" id="btn_upload_espacio_portada" class="cdp-btn-gold" style="white-space:nowrap;">Seleccionar Imagen</button>';
    echo '</div>';
    if ($portada) {
        echo '<img src="' . esc_url($portada) . '" class="cdp-img-preview" />';
    }
    echo '<small style="color:#64748b; display:block; margin-top:6px;">Imagen superior de alta resolución al entrar al salón.</small></div>';

    echo '<div class="cdp-meta-field"><label for="espacio_plano_pdf">Archivo PDF de Planos y Distribución:</label>';
    echo '<div style="display:flex; gap:8px;">';
    echo '<input type="url" id="espacio_plano_pdf" name="espacio_plano_pdf" value="' . esc_attr($plano_pdf) . '" placeholder="https://...plano.pdf" />';
    echo '<button type="button" id="btn_upload_espacio_pdf" class="cdp-btn-dark" style="white-space:nowrap;">Subir PDF</button>';
    echo '</div>';
    echo '<small style="color:#64748b; display:block; margin-top:6px;">Documento técnico de planos arquitectónicos o montajes para clientes.</small></div>';

    echo '</div>'; // Fin tarjeta 1

    // TARJETA 2: CAPACIDAD Y ESPECIFICACIONES
    echo '<div class="cdp-meta-card">';
    echo '<h4>👥 Capacidad & Especificaciones del Salón</h4>';

    echo '<div class="cdp-meta-field"><label for="espacio_capacidad">Capacidad Máxima de Personas (Pax):</label>';
    echo '<input type="text" id="espacio_capacidad" name="espacio_capacidad" value="' . esc_attr($capacidad) . '" placeholder="Ej: 1,500" />';
    echo '<small style="color:#64748b; display:block; margin-top:6px;">Ejemplo: "1,500" se mostrará como "Hasta 1,500 Pax".</small></div>';

    echo '<div class="cdp-meta-field"><label for="espacio_rango_personas">Rangos de Personas para Modal de Cotización:</label>';
    echo '<input type="text" id="espacio_rango_personas" name="espacio_rango_personas" value="' . esc_attr($rango_personas) . '" placeholder="Ej: 1 a 150 personas, 151 a 400 personas, Más de 400 personas" />';
    echo '<small style="color:#64748b; display:block; margin-top:6px;">Opciones separadas por coma para cotización en línea.</small></div>';

    echo '<div class="cdp-meta-field"><label for="espacio_m2">Superficie / Área en Metros Cuadrados (m²):</label>';
    echo '<input type="text" id="espacio_m2" name="espacio_m2" value="' . esc_attr($m2) . '" placeholder="Ej: 2,400" />';
    echo '<small style="color:#64748b; display:block; margin-top:6px;">Ejemplo: "2,400" se mostrará como "2,400 m²".</small></div>';

    echo '</div>'; // Fin tarjeta 2

    echo '</div>'; // Fin grid
    echo '</div>'; // Fin meta-box

    echo '<script>
    jQuery(document).ready(function($){
        $("#btn_upload_espacio_portada").on("click", function(e){
            e.preventDefault();
            var frame = wp.media({
                title: "Seleccionar Imagen de Header / Portada del Espacio",
                button: { text: "Usar Imagen" },
                multiple: false
            });
            frame.on("select", function(){
                var attachment = frame.state().get("selection").first().toJSON();
                $("#espacio_portada").val(attachment.url);
            });
            frame.open();
        });

        $("#btn_upload_espacio_pdf").on("click", function(e){
            e.preventDefault();
            var frame = wp.media({
                title: "Seleccionar Plano Arquitectónico PDF",
                button: { text: "Usar Archivo" },
                multiple: false
            });
            frame.on("select", function(){
                var attachment = frame.state().get("selection").first().toJSON();
                $("#espacio_plano_pdf").val(attachment.url);
            });
            frame.open();
        });
    });
    </script>';
}

function casadepiedra_save_espacio_meta($post_id) {
    if (!isset($_POST['casadepiedra_espacio_meta_nonce']) || !wp_verify_nonce($_POST['casadepiedra_espacio_meta_nonce'], 'casadepiedra_save_espacio_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['espacio_portada'])) {
        update_post_meta($post_id, '_espacio_portada', esc_url_raw($_POST['espacio_portada']));
    }
    if (isset($_POST['espacio_capacidad'])) {
        update_post_meta($post_id, '_espacio_capacidad', sanitize_text_field($_POST['espacio_capacidad']));
    }
    if (isset($_POST['espacio_rango_personas'])) {
        update_post_meta($post_id, '_espacio_rango_personas', sanitize_text_field($_POST['espacio_rango_personas']));
    }
    if (isset($_POST['espacio_m2'])) {
        update_post_meta($post_id, '_espacio_m2', sanitize_text_field($_POST['espacio_m2']));
    }
    if (isset($_POST['espacio_plano_pdf'])) {
        update_post_meta($post_id, '_espacio_plano_pdf', esc_url_raw($_POST['espacio_plano_pdf']));
    }
}
add_action('save_post_espacios', 'casadepiedra_save_espacio_meta');

// 9. Meta Box para Eventos (Botón y Enlace)
function casadepiedra_add_evento_meta() {
    add_meta_box('casadepiedra_evento_info', 'Información Ejecutiva del Evento', 'casadepiedra_evento_meta_callback', 'eventos', 'normal', 'high');
}
add_action('add_meta_boxes', 'casadepiedra_add_evento_meta');

function casadepiedra_evento_meta_callback($post) {
    wp_nonce_field('casadepiedra_save_evento_meta', 'casadepiedra_evento_meta_nonce');
    $btn_texto = get_post_meta($post->ID, '_evento_boton_texto', true) ?: 'Me interesa';
    $btn_enlace = get_post_meta($post->ID, '_evento_enlace', true);
    $expiracion = get_post_meta($post->ID, '_evento_expiracion', true);
    
    echo '<div class="cdp-meta-box">';
    echo '<div class="cdp-meta-grid">';

    // TARJETA 1: VIGENCIA AUTOMÁTICA
    echo '<div class="cdp-meta-card">';
    echo '<h4>⏰ Vigencia & Automatización del Evento</h4>';
    echo '<div class="cdp-meta-field"><label for="evento_expiracion">Fecha y Hora de Vigencia (Baja Automática):</label>';
    echo '<input type="datetime-local" id="evento_expiracion" name="evento_expiracion" value="' . esc_attr($expiracion) . '" />';
    echo '<small style="color:#64748b; display:block; margin-top:6px;">Pasada esta fecha y hora, la tarjeta del evento se ocultará automáticamente.</small></div>';
    echo '</div>';

    // TARJETA 2: BOTÓN Y ENLACE DE RESERVA
    echo '<div class="cdp-meta-card">';
    echo '<h4>🔗 Acción de Reserva & Contacto</h4>';
    echo '<div class="cdp-meta-field"><label for="evento_boton_texto">Texto del Botón:</label>';
    echo '<input type="text" id="evento_boton_texto" name="evento_boton_texto" value="' . esc_attr($btn_texto) . '" placeholder="Ej: Me interesa" /></div>';
    echo '<div class="cdp-meta-field"><label for="evento_enlace">Enlace Externo (Boletera / WhatsApp):</label>';
    echo '<input type="url" id="evento_enlace" name="evento_enlace" value="' . esc_attr($btn_enlace) . '" placeholder="https://" />';
    echo '<small style="color:#64748b; display:block; margin-top:6px;">URL a donde se dirigirá al usuario al pulsar en la tarjeta.</small></div>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
}

function casadepiedra_save_evento_meta($post_id) {
    if (!isset($_POST['casadepiedra_evento_meta_nonce']) || !wp_verify_nonce($_POST['casadepiedra_evento_meta_nonce'], 'casadepiedra_save_evento_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['evento_boton_texto'])) {
        update_post_meta($post_id, '_evento_boton_texto', sanitize_text_field($_POST['evento_boton_texto']));
    }
    if (isset($_POST['evento_enlace'])) {
        update_post_meta($post_id, '_evento_enlace', esc_url_raw($_POST['evento_enlace']));
    }
    if (isset($_POST['evento_expiracion'])) {
        update_post_meta($post_id, '_evento_expiracion', sanitize_text_field($_POST['evento_expiracion']));
    }
}
add_action('save_post_eventos', 'casadepiedra_save_evento_meta');

// 10. Sistema de Vigencia Automática para Eventos
if (!wp_next_scheduled('casadepiedra_daily_event_vigencia')) {
    // Programar para hoy a las 23:59 (hora de WP)
    $today_2359 = strtotime('today 23:59:00');
    if ($today_2359 < time()) {
        $today_2359 += DAY_IN_SECONDS;
    }
    wp_schedule_event($today_2359, 'daily', 'casadepiedra_daily_event_vigencia');
}

add_action('casadepiedra_daily_event_vigencia', 'casadepiedra_check_expired_events');
function casadepiedra_check_expired_events() {
    $current_datetime_wp = wp_date('Y-m-d\TH:i');
    
    $args = array(
        'post_type' => 'eventos',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_evento_expiracion',
                'value' => $current_datetime_wp,
                'compare' => '<',
                'type' => 'CHAR'
            ),
            array(
                'key' => '_evento_expiracion',
                'value' => '',
                'compare' => '!=',
            )
        )
    );

    $expired_events = new WP_Query($args);
    if ($expired_events->have_posts()) {
        foreach ($expired_events->posts as $evento) {
            $evento_data = array(
                'ID' => $evento->ID,
                'post_status' => 'draft'
            );
            wp_update_post($evento_data);
        }
    }
}

// 7. Cargar script de auto-poblado (Páginas, CPTs y Accesos Directos)
require_once get_template_directory() . '/inc/populate.php';

// Restaurar enlace de Eventos en el menú principal tras borrar la página
function casadepiedra_restore_eventos_menu_link() {
    // Solo purgar reglas de reescritura una vez para que el CPT /eventos/ no dé 404
    if (!get_option('casadepiedra_rewrites_flushed_eventos')) {
        flush_rewrite_rules();
        update_option('casadepiedra_rewrites_flushed_eventos', true);
    }

    $menu_name = 'Menú Principal Premium';
    $menu_exists = wp_get_nav_menu_object($menu_name);
    
    if ($menu_exists) {
        $items = wp_get_nav_menu_items($menu_exists->term_id);
        $has_eventos = false;
        
        // El link "Eventos" en el menú puede ser de tipo 'post_type' (página) o 'custom' (nuestro link).
        if ($items) {
            foreach ($items as $item) {
                if ($item->title === 'Eventos') {
                    $has_eventos = true;
                    // Si el usuario borró la página, el ítem del menú suele quedar huérfano y puede dar problemas.
                    // Si el objeto ya no existe, el title podría estar vacío, pero revisamos por si acaso.
                    break;
                }
            }
        }
        
        // Si no se encontró ningún ítem titulado "Eventos" válido, lo inyectamos.
        if (!$has_eventos) {
            wp_update_nav_menu_item($menu_exists->term_id, 0, array(
                'menu-item-title'  => 'Eventos',
                'menu-item-url'    => home_url('/eventos/'),
                'menu-item-status' => 'publish',
                'menu-item-type'   => 'custom',
            ));
        }
    }
}
add_action('init', 'casadepiedra_restore_eventos_menu_link', 99);

// Forzar que la URL /eventos/ SIEMPRE cargue la plantilla de tarjetas, 
// incluso si el usuario crea accidentalmente una Página estática llamada "Eventos".
function casadepiedra_force_eventos_template($template) {
    if (is_post_type_archive('eventos') || is_page('eventos')) {
        $archive_template = locate_template('archive-eventos.php');
        if ($archive_template) {
            return $archive_template;
        }
    }
    return $template;
}
add_filter('template_include', 'casadepiedra_force_eventos_template', 99);

// 9. Filtrado de Menú, Ordenamiento de Contacto hasta la Derecha y Restricción de Secciones Desactivadas
function casa_filter_nav_menu_items($items, $args) {
    $nosotros_active = get_option('casa_opt_status_nosotros', '1') === '1';
    $espacios_active = get_option('casa_opt_status_espacios', '1') === '1';
    $restaurantes_active = get_option('casa_opt_status_restaurantes', '1') === '1';
    $galeria_active = get_option('casa_opt_status_galeria', '1') === '1';
    $eventos_active = get_option('casa_opt_status_eventos', '1') === '1';
    $contacto_active = get_option('casa_opt_status_contacto', '1') === '1';

    // Verificar si Eventos está en el menú
    $has_eventos = false;
    foreach ($items as $item) {
        if (strpos($item->url, '/eventos') !== false || stripos($item->title, 'eventos') !== false) {
            $has_eventos = true;
            break;
        }
    }
    if (!$has_eventos && $eventos_active) {
        $evt_page = get_page_by_path('eventos') ?: get_page_by_title('Eventos');
        if ($evt_page) {
            $evt_item = new stdClass();
            $evt_item->ID = 999991;
            $evt_item->db_id = 999991;
            $evt_item->title = 'Eventos';
            $evt_item->url = get_permalink($evt_page->ID);
            $evt_item->menu_order = 999;
            $evt_item->menu_item_parent = 0;
            $evt_item->type = 'post_type';
            $evt_item->object = 'page';
            $evt_item->object_id = $evt_page->ID;
            $evt_item->classes = array('menu-item');
            $evt_item->target = '';
            $evt_item->attr_title = '';
            $evt_item->description = '';
            $evt_item->xfn = '';
            $evt_item->status = 'publish';
            $items[] = $evt_item;
        }
    }

    $contacto_item = null;
    $contacto_key = null;

    foreach ($items as $key => $item) {
        $url = $item->url;
        // Quitar siempre "Quiénes Somos / Nosotros" del menú porque está fusionado en Inicio
        if (strpos($url, '/quienes-somos') !== false || strpos($url, '/nosotros') !== false || stripos($item->title, 'quienes somos') !== false || stripos($item->title, 'quiénes somos') !== false) {
            unset($items[$key]);
            continue;
        }
        if (!$espacios_active && strpos($url, '/espacios') !== false) {
            unset($items[$key]);
            continue;
        } elseif (empty($item->menu_item_parent) && (rtrim(parse_url($url, PHP_URL_PATH), '/') === '/espacios' || strcasecmp(trim($item->title), 'espacios') === 0)) {
            $item->title = 'Venues';
        }
        elseif (!$restaurantes_active && strpos($url, '/restaurantes') !== false) {
            unset($items[$key]);
        }
        elseif (!$galeria_active && strpos($url, '/galeria') !== false) {
            unset($items[$key]);
        }
        elseif (!$eventos_active && strpos($url, '/eventos') !== false) {
            unset($items[$key]);
        }
        elseif (!$contacto_active && strpos($url, '/contacto') !== false) {
            unset($items[$key]);
        }
        elseif (strpos($url, '/contacto') !== false || stripos($item->title, 'contacto') !== false) {
            $item->url = '#quote-modal';
            $contacto_item = $item;
            $contacto_key = $key;
        }
    }

    // Asegurar que el botón de Contacto se posicione hasta la derecha en la navbar y abra el modal
    if ($contacto_item !== null && isset($items[$contacto_key])) {
        if (!is_array($contacto_item->classes)) {
            $contacto_item->classes = array();
        }
        $contacto_item->classes[] = 'nav-item-contacto';
        $contacto_item->classes[] = 'btn-open-quote-modal';
        $contacto_item->url = '#quote-modal';
        unset($items[$contacto_key]);
        $items[] = $contacto_item;
    }

    return array_values($items);
}
add_filter('wp_nav_menu_objects', 'casa_filter_nav_menu_items', 10, 2);

function casa_restrict_deactivated_pages() {
    $nosotros_active = get_option('casa_opt_status_nosotros', '1') === '1';
    $espacios_active = get_option('casa_opt_status_espacios', '1') === '1';
    $restaurantes_active = get_option('casa_opt_status_restaurantes', '1') === '1';
    $galeria_active = get_option('casa_opt_status_galeria', '1') === '1';
    $eventos_active = get_option('casa_opt_status_eventos', '1') === '1';
    $contacto_active = get_option('casa_opt_status_contacto', '1') === '1';

    if (!$nosotros_active && (is_page('quienes-somos') || is_page('nosotros'))) {
        wp_redirect(home_url());
        exit;
    }
    if (!$espacios_active && (is_post_type_archive('espacios') || is_page('espacios') || is_singular('espacios'))) {
        wp_redirect(home_url());
        exit;
    }
    if (!$restaurantes_active && (is_post_type_archive('restaurantes') || is_page('restaurantes') || is_singular('restaurantes'))) {
        wp_redirect(home_url());
        exit;
    }
    if (!$galeria_active && is_page('galeria')) {
        wp_redirect(home_url());
        exit;
    }
    if (!$eventos_active && (is_post_type_archive('eventos') || is_page('eventos') || is_singular('eventos'))) {
        wp_redirect(home_url());
        exit;
    }
    if (!$contacto_active && is_page('contacto')) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('template_redirect', 'casa_restrict_deactivated_pages');

// 10. AJAX Handle for Quote Request (Contacto)
add_action('wp_ajax_casa_send_cotizacion', 'casa_handle_send_cotizacion');
add_action('wp_ajax_nopriv_casa_send_cotizacion', 'casa_handle_send_cotizacion');

function casa_format_single_date_es($date_str) {
    $date_str = trim($date_str);
    $day = $month = $year = 0;
    if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $date_str, $m)) {
        $day = (int)$m[1];
        $month = (int)$m[2];
        $year = (int)$m[3];
    } elseif (preg_match('/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/', $date_str, $m)) {
        $year = (int)$m[1];
        $month = (int)$m[2];
        $day = (int)$m[3];
    }
    if ($day > 0 && $month > 0 && $month <= 12 && $year > 1900) {
        $ts = mktime(12, 0, 0, $month, $day, $year);
        $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
        $meses = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
        $dia_semana = $dias[(int)date('w', $ts)];
        $mes_nombre = $meses[$month];
        $dd_mm_yyyy = sprintf('%02d/%02d/%04d', $day, $month, $year);
        return "{$dia_semana} {$day} {$mes_nombre} {$year} ({$dd_mm_yyyy})";
    }
    return $date_str;
}

function casa_format_quote_date_es($raw_date) {
    $raw_date = trim($raw_date);
    if (empty($raw_date)) return '';
    if (strpos($raw_date, ' a ') !== false) {
        $parts = explode(' a ', $raw_date);
        return casa_format_single_date_es($parts[0]) . ' al ' . casa_format_single_date_es($parts[1]);
    } elseif (strpos($raw_date, ' to ') !== false) {
        $parts = explode(' to ', $raw_date);
        return casa_format_single_date_es($parts[0]) . ' al ' . casa_format_single_date_es($parts[1]);
    }
    return casa_format_single_date_es($raw_date);
}

function casa_handle_send_cotizacion() {
    $category = sanitize_text_field($_POST['quote_category'] ?? 'cotizacion');
    $name = sanitize_text_field($_POST['quote_name'] ?? '');
    $email = sanitize_email($_POST['quote_email'] ?? '');
    $phone = sanitize_text_field($_POST['quote_phone'] ?? '');
    $date = sanitize_text_field($_POST['quote_date'] ?? '');
    $salon = sanitize_text_field($_POST['quote_salon'] ?? '');
    $capacity = sanitize_text_field($_POST['quote_capacity'] ?? '');
    $type = sanitize_text_field($_POST['quote_type'] ?? '');
    $comments = sanitize_textarea_field($_POST['quote_comments'] ?? '');

    if (empty($name) || empty($email) || empty($phone)) {
        wp_send_json_error('Por favor, complete todos los campos obligatorios (Nombre, Correo, Teléfono).');
    }

    if ($category === 'cotizacion' && (empty($date) || empty($salon) || empty($capacity) || empty($type))) {
        wp_send_json_error('Por favor, complete todos los campos obligatorios de la cotización.');
    }

    $formatted_date = casa_format_quote_date_es($date);

    // Seleccionar destinatario en función de la categoría
    $dest_raw = '';
    $category_label = 'Cotización de Espacios / Eventos';
    if ($category === 'generales') {
        $dest_raw = get_option('casa_opt_email_generales', '');
        $category_label = 'Temas generales / Información';
    } elseif ($category === 'proveedores') {
        $dest_raw = get_option('casa_opt_email_proveedores', '');
        $category_label = 'Propuesta de proveedores';
    } elseif ($category === 'propuesta_eventos') {
        $dest_raw = get_option('casa_opt_email_propuestas_eventos', '');
        $category_label = 'Propuesta de eventos comerciales / corporativos';
    } else {
        $dest_raw = get_option('casa_opt_email_cotizacion', '');
        if (empty(trim($dest_raw))) {
            $dest_raw = get_option('casa_opt_mail_receiver', '');
        }
    }

    if (empty(trim($dest_raw))) {
        $dest_raw = get_option('casa_opt_global_email', '');
    }
    if (empty(trim($dest_raw))) {
        $dest_raw = get_option('admin_email');
    }

    $admin_email = array_map('trim', explode(',', $dest_raw));

    $mail_logo = get_option('casa_opt_mail_logo', '');
    $logo_html = '';
    if (!empty($mail_logo)) {
        $logo_html = '<div style="margin-bottom: 20px;"><img src="' . esc_url($mail_logo) . '" alt="Casa de Piedra" style="max-height: 80px; width: auto;"></div>';
    }

    // HTML Template for Admin
    $admin_subject = 'Nueva Solicitud: ' . $category_label . ' - Casa de Piedra';
    $admin_message = '
    <html>
    <head>
        <style>
            body { font-family: "Inter", sans-serif; background-color: #f4f4f5; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
            .header { background: #0a0a0a; padding: 30px; text-align: center; }
            .header h2 { color: #d4af37; margin: 0; font-weight: 300; letter-spacing: 2px; }
            .content { padding: 30px; color: #333333; line-height: 1.6; }
            .content strong { color: #0a0a0a; }
            .footer { background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                ' . $logo_html . '
                <h2>' . esc_html(mb_strtoupper($category_label, 'UTF-8')) . '</h2>
            </div>
            <div class="content">
                <p>Ha recibido una nueva solicitud (' . esc_html($category_label) . ') desde el sitio web.</p>
                <hr style="border: 0; border-top: 1px solid #eaeaea; margin: 20px 0;">
                <p><strong>Nombre:</strong> ' . esc_html($name) . '</p>
                <p><strong>Correo:</strong> ' . esc_html($email) . '</p>
                <p><strong>Teléfono:</strong> ' . esc_html($phone) . '</p>';
                if ($category === 'cotizacion') {
                    $admin_message .= '
                <p><strong>Fecha(s) de Interés:</strong> ' . esc_html($formatted_date) . '</p>
                <p><strong>Salón de Interés:</strong> ' . esc_html($salon) . '</p>
                <p><strong>Cantidad de Personas:</strong> ' . esc_html($capacity) . '</p>
                <p><strong>Tipo de Evento:</strong> ' . esc_html($type) . '</p>';
                }
                $admin_message .= '
                <p><strong>Comentarios / Detalles:</strong><br>' . nl2br(esc_html($comments)) . '</p>
            </div>
            <div class="footer">
                &copy; ' . date('Y') . ' Casa de Piedra. Todos los derechos reservados.
            </div>
        </div>
    </body>
    </html>';

    // HTML Template for Client (Confirmation)
    $client_subject = 'Hemos recibido tu solicitud - Casa de Piedra';
    $client_message = '
    <html>
    <head>
        <style>
            body { font-family: "Inter", sans-serif; background-color: #f4f4f5; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
            .header { background: #0a0a0a; padding: 40px 30px; text-align: center; }
            .header h2 { color: #d4af37; margin: 0; font-weight: 300; letter-spacing: 2px; text-transform: uppercase; }
            .content { padding: 40px 30px; color: #4b5563; line-height: 1.8; }
            .content h3 { color: #111827; margin-top: 0; }
            .details { background: #f9fafb; padding: 20px; border-radius: 6px; margin-top: 20px; }
            .footer { background: #111827; padding: 30px; text-align: center; color: #9ca3af; font-size: 13px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                ' . $logo_html . '
                <h2>CASA DE PIEDRA</h2>
            </div>
            <div class="content">
                <h3>Hola ' . esc_html($name) . ',</h3>
                <p>Gracias por considerar a Casa de Piedra para tu próximo evento. Hemos recibido tu solicitud de cotización exitosamente y uno de nuestros asesores se pondrá en contacto contigo a la brevedad.</p>
                
                <div class="details">
                    <p style="margin:0 0 10px 0;"><strong>Resumen de tu solicitud:</strong></p>
                    <ul style="margin:0; padding-left: 20px;">
                        <li><strong>Fecha(s):</strong> ' . esc_html($formatted_date) . '</li>
                        <li><strong>Salón:</strong> ' . esc_html($salon) . '</li>
                        <li><strong>Cantidad de Personas:</strong> ' . esc_html($capacity) . '</li>
                        <li><strong>Evento:</strong> ' . esc_html($type) . '</li>
                        <li><strong>Teléfono de contacto:</strong> ' . esc_html($phone) . '</li>
                    </ul>
                </div>
                
                <p style="margin-top: 20px; font-size: 13px; color: #6b7280;"><em>* Nota: La selección de la fecha no garantiza una reservación de la misma. La fecha real del evento está sujeta a disponibilidad.</em></p>
                
                <p style="margin-top: 30px;">Si necesitas atención inmediata, no dudes en llamarnos al <a href="tel:4777172600" style="color: #d4af37;">477 717 2600</a>.</p>
            </div>
            <div class="footer">
                Ex Hacienda Casa de Piedra<br>
                León, Guanajuato, México
            </div>
        </div>
    </body>
    </html>';

    $headers = array('Content-Type: text/html; charset=UTF-8');

    // Send to admin
    $sent_admin = wp_mail($admin_email, $admin_subject, $admin_message, $headers);
    
    // Send to client
    $sent_client = wp_mail($email, $client_subject, $client_message, $headers);

    if ($sent_admin && $sent_client) {
        wp_send_json_success();
    } else {
        wp_send_json_error('Hubo un problema al enviar el correo. Por favor intenta llamarnos.');
    }
}

// 12. Aviso de Privacidad Auto-create & Route
add_action('init', function() {
    if (!get_page_by_path('aviso-de-privacidad')) {
        wp_insert_post(array(
            'post_title'    => 'Aviso de Privacidad',
            'post_name'     => 'aviso-de-privacidad',
            'post_status'   => 'publish',
            'post_type'     => 'page'
        ));
    }
});

add_filter('template_include', function($template) {
    $req = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    if (is_page('aviso-de-privacidad') || is_page('terminos-y-condiciones') || $req === 'aviso-de-privacidad' || $req === 'terminos-y-condiciones' || $req === 'terminos') {
        $custom = get_template_directory() . '/page-aviso-de-privacidad.php';
        if (file_exists($custom)) {
            return $custom;
        }
    }
    return $template;
});

// 13. Sincronización Automática de Textos y Descripciones de Salones (Casa de Piedra)
add_action('init', function() {
    if (get_option('casa_texts_synced_v6') !== '1') {
        update_option('casa_opt_home_hero_desc', 'El símbolo de prestigio en el Bajío, ha sido un escenario de momentos extraordinarios. En la zona dorada de León, nuestros espacios han sido testigos de grandes celebraciones. Aquí, tu historia es parte de nuestra historia.');
        update_option('casa_opt_nosotros_subtitle', 'Donde el pasado y el presente se encuentran.');
        update_option('casa_opt_nosotros_desc', '<p style="margin-bottom: 1.5rem;">Desde 1845, sus muros de cantera han sido testigos de amor y celebración.</p><p>Hoy, en el corazón dorado de la ciudad, cada rincón invita a vivir experiencias únicas, donde la sofisticación se fusiona con la tradición, creando un espacio solo para los más exigentes.</p>');
        update_option('casa_opt_global_espacios_desc', 'Escenarios para grandes historias');
        update_option('casa_opt_global_restaurantes_title', 'El epítome gastronómico del Bajío');
        update_option('casa_opt_global_restaurantes_desc', 'la cúspide de la gastronomía en el Bajío. Una experiencia inigualable que reúne la oferta gastronómica más exclusivas de la región, ofreciendo un viaje de sabores únicos.');
        update_option('casa_opt_google_maps_link', 'https://www.google.com/maps/place/Casa+De+Piedra/@21.1539759,-101.6967297,2188m/data=!3m2!1e3!5s0x842bbf530842ac4b:0x4642591264eb2eec!4m6!3m5!1s0x842bbf53a2e4d0e3:0xfe1f47b7b2f6b0a3!8m2!3d21.1585368!4d-101.6992601!16s%2Fg%2F11f_b_l520?entry=ttu');

        $espacios_new = array(
            'Salón Principal' => array(
                'desc' => 'En el corazón de Casa de Piedra, se erige el salón con una arquitectura imponente, construcción de gran altura y detalles que reflejan el carácter histórico y emblemático del recinto. Su amplitud y versatilidad permiten recibir desde grandes celebraciones y eventos de alto nivel, ofreciendo el entorno más sofisticado para cualquier ocasión.',
                'cap' => '800'
            ),
            'Terraza Mezquite' => array(
                'desc' => 'Enmarcada por la arquitectura original de la ex hacienda y bajo la sombra de un majestuoso mezquite resguardado en un ojo de agua, que da nombre a este espacio, que invita a vivir celebraciones en un entorno donde la historia y la naturaleza conviven en perfecta armonía. Un escenario al aire libre, íntimo, elegante y lleno de encanto.',
                'cap' => '150'
            ),
            'Salón Pavorreales' => array(
                'desc' => 'Un espacio que combina privacidad, elegancia y calidez. Su diseño atemporal crea el ambiente ideal para eventos sociales, reuniones ejecutivas y celebraciones que buscan una experiencia más íntima, sin renunciar al sello distintivo de Casa de Piedra.',
                'cap' => '90'
            ),
            'Jardín Principal' => array(
                'desc' => 'Rodeado de vegetación y una atmósfera serena, el Jardín Principal es el escenario perfecto para celebraciones al aire libre con un trabajo selecto de paisajismo. Un espacio donde la naturaleza y la elegancia conviven para crear momentos memorables, desde ceremonias hasta recepciones bajo el cielo.',
                'cap' => '1,500'
            )
        );

        foreach ($espacios_new as $title => $data) {
            $post = get_page_by_title($title, OBJECT, 'espacios');
            if (!$post && $title === 'Terraza Mezquite') {
                $post = get_page_by_title('Terraza del Mezquite', OBJECT, 'espacios');
            }
            if ($post) {
                wp_update_post(array(
                    'ID' => $post->ID,
                    'post_title' => $title,
                    'post_content' => $data['desc']
                ));
                update_post_meta($post->ID, '_espacio_capacidad', $data['cap']);
            } else {
                $new_id = wp_insert_post(array(
                    'post_title' => $title,
                    'post_content' => $data['desc'],
                    'post_status' => 'publish',
                    'post_type' => 'espacios'
                ));
                if ($new_id && !is_wp_error($new_id)) {
                    update_post_meta($new_id, '_espacio_capacidad', $data['cap']);
                }
            }
        }

        // Eliminar cualquier restaurante dummy / antiguo que no sea uno de los 6 oficiales
        $official_titles = array(
            'Argentilia',
            'Lucio Ítalo-Argentino',
            'Manolo Taberna Española',
            'Sato Cocina Nikkei',
            'Casa Mía Trattoria & Wine Bar',
            'Valentina Cocina Contemporánea'
        );

        $all_rests = get_posts(array('post_type' => 'restaurantes', 'posts_per_page' => -1, 'post_status' => 'any'));
        foreach ($all_rests as $rp) {
            if (!in_array($rp->post_title, $official_titles)) {
                wp_delete_post($rp->ID, true);
            }
        }

        update_option('casa_texts_synced_v7_clean_restaurantes', '1');
    }
});

/**
 * Títulos Dinámicos en Mayúsculas (SEO & UX)
 * Garantiza que la pestaña del navegador siempre muestre "PÁGINA | CASA DE PIEDRA" en MAYÚSCULAS.
 */
add_filter('pre_get_document_title', function($title) {
    if (is_front_page() || is_home()) {
        return 'INICIO | CASA DE PIEDRA';
    }
    if (is_post_type_archive('espacios') || is_page_template('archive-espacios.php') || is_page('espacios') || is_page('venues')) {
        return 'VENUES | CASA DE PIEDRA';
    }
    if (is_post_type_archive('restaurantes') || is_page_template('archive-restaurantes.php') || is_page('restaurantes')) {
        return 'RESTAURANTES | CASA DE PIEDRA';
    }
    if (is_post_type_archive('eventos') || is_page_template('archive-eventos.php') || is_page('eventos')) {
        return 'EVENTOS | CASA DE PIEDRA';
    }
    if (is_page('galeria') || is_page_template('page-galeria.php')) {
        return 'GALERÍA | CASA DE PIEDRA';
    }
    if (is_page('contacto') || is_page_template('page-contacto.php')) {
        return 'CONTACTO | CASA DE PIEDRA';
    }
    if (is_page('quienes-somos') || is_page_template('page-quienes-somos.php')) {
        return 'QUIÉNES SOMOS | CASA DE PIEDRA';
    }
    $raw = get_the_title();
    if (empty($raw)) {
        return 'CASA DE PIEDRA | LEÓN, GTO.';
    }
    return mb_strtoupper($raw, 'UTF-8') . ' | CASA DE PIEDRA';
}, 99);

add_filter('document_title_parts', function($title) {
    if (is_array($title)) {
        foreach ($title as $k => $v) {
            $title[$k] = mb_strtoupper($v, 'UTF-8');
        }
    }
    return $title;
}, 999);

/**
 * Auto-corrección permanente del domicilio oficial si existía información anterior errónea en base de datos.
 */
add_action('init', function() {
    $curr_addr = get_option('casa_opt_global_address', '');
    if (empty($curr_addr) || stripos($curr_addr, 'Alonso') !== false || stripos($curr_addr, 'Lomas') !== false || stripos($curr_addr, '2002') !== false || stripos($curr_addr, 'Valle del Campestre') !== false) {
        update_option('casa_opt_global_address', 'Av Cerro Gordo 270, Casa de Piedra, 37120 León de los Aldama, Gto.');
    }
    $rev1 = get_option('casa_opt_home_rev1_text', '');
    if (!empty($rev1) && stripos($rev1, 'Hacienda Casa de Piedra') !== false && stripos($rev1, 'Ex Hacienda') === false && stripos($rev1, 'Ex-Hacienda') === false) {
        update_option('casa_opt_home_rev1_text', str_ireplace('Hacienda Casa de Piedra', 'Ex Hacienda Casa de Piedra', $rev1));
    }
});