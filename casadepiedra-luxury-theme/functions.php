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

require_once get_template_directory() . '/inc/media.php';

/**
 * Reemplazo seguro de get_page_by_title() (deprecado en WP 6.2+).
 * Evita avisos que rompen el login / redirecciones del admin.
 */
function casadepiedra_get_post_by_title($title, $post_type = 'page') {
    $found = get_posts(array(
        'post_type'        => $post_type,
        'title'            => $title,
        'post_status'      => 'any',
        'posts_per_page'   => 1,
        'suppress_filters' => true,
    ));
    return !empty($found) ? $found[0] : null;
}

function casa_repair_mojibake($text, $fallback = '') {
    $text = is_string($text) ? $text : '';
    if ($text === '') {
        return $fallback;
    }
    if (strpos($text, "\xEF\xBF\xBD") !== false || preg_match('/Ã.|Â[\x80-\xBF]/', $text)) {
        return $fallback !== '' ? $fallback : $text;
    }
    return $text;
}

function casa_get_display_phone() {
    $raw = trim((string) get_option('casa_opt_global_phone', '477 289 25 21'));
    return $raw !== '' ? $raw : '477 289 25 21';
}

function casa_get_whatsapp_url($message = '') {
    $raw = trim((string) get_option('casa_opt_global_whatsapp', ''));
    if ($raw === '') {
        $raw = casa_get_display_phone();
    }
    if (preg_match('#^https?://#i', $raw)) {
        if ($message === '' || strpos($raw, 'text=') !== false) {
            return $raw;
        }
        $join = (strpos($raw, '?') !== false) ? '&' : '?';
        return $raw . $join . 'text=' . rawurlencode($message);
    }
    $digits = preg_replace('/\D+/', '', $raw);
    if ($digits === '') {
        $digits = '4772892521';
    }
    if (strlen($digits) === 10) {
        $digits = '52' . $digits;
    } elseif (strlen($digits) === 11 && strpos($digits, '1') === 0) {
        $digits = '52' . substr($digits, 1);
    }
    $url = 'https://wa.me/' . $digits;
    if ($message !== '') {
        $url .= '?text=' . rawurlencode($message);
    }
    return $url;
}

function casa_get_venue_geo() {
    return array(
        'lat'     => '21.1585368',
        'lng'     => '-101.6992601',
        'name'    => 'Casa de Piedra',
        'address' => 'Av Cerro Gordo 270, Casa de Piedra, 37120 León de los Aldama, Gto.',
    );
}

function casa_get_google_maps_url() {
    $opt = trim((string) get_option('casa_opt_google_maps_link', ''));
    if ($opt !== '') {
        return $opt;
    }
    $geo = casa_get_venue_geo();
    return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($geo['lat'] . ',' . $geo['lng']);
}

function casa_get_google_maps_embed_url() {
    $opt = trim((string) get_option('casa_opt_google_maps_embed', ''));
    if ($opt !== '') {
        return $opt;
    }
    $geo = casa_get_venue_geo();
    return 'https://maps.google.com/maps?q=' . rawurlencode($geo['lat'] . ',' . $geo['lng']) . '&z=16&output=embed';
}

function casa_get_review_cards($prefix) {
    $cards = array();
    for ($i = 1; $i <= 3; $i++) {
        $text = trim((string) get_option($prefix . $i . '_text', ''));
        if ($text === '') {
            continue;
        }
        $url = trim((string) get_option($prefix . $i . '_url', ''));
        $cards[] = array(
            'text'   => $text,
            'author' => trim((string) get_option($prefix . $i . '_author', '')),
            'url'    => $url,
        );
    }
    return $cards;
}

function casa_get_apple_maps_url() {
    $geo = casa_get_venue_geo();
    return 'https://maps.apple.com/?ll=' . rawurlencode($geo['lat'] . ',' . $geo['lng']) . '&q=' . rawurlencode($geo['name']) . '&address=' . rawurlencode($geo['address']);
}

/**
 * Soporte Universal para Túneles de Cloudflare (Live Links / Local by Flywheel) y Múltiples Dominios:
 * Intercepta y corrige dinámicamente todas las URLs de imágenes (logos, portadas, metadatos y medios)
 * para que siempre utilicen el dominio activo actual en lugar de intentar cargar desde .local o localhost.
 */
function casadepiedra_theme_media_url($filename_or_url) {
    if (!is_string($filename_or_url) || $filename_or_url === '') {
        return '';
    }
    $path = $filename_or_url;
    if (preg_match('#^https?://#i', $filename_or_url)) {
        $parsed = wp_parse_url($filename_or_url, PHP_URL_PATH);
        $path = $parsed ? $parsed : $filename_or_url;
    }
    $base = rawurldecode(basename(str_replace('\\', '/', $path)));
    if ($base === '' || strpos($base, '.') === false) {
        return '';
    }
    static $index = null;
    if ($index === null) {
        $index = array();
        $root = get_template_directory() . '/assets';
        if (is_dir($root)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
                    $index[$file->getFilename()] = get_template_directory_uri() . '/assets/' . $rel;
                }
            }
        }
    }
    return isset($index[$base]) ? $index[$base] : '';
}

function casadepiedra_media_library_url_by_basename($filename_or_url) {
    static $guard = false;
    static $cache = array();

    if ($guard || !is_string($filename_or_url) || $filename_or_url === '' || !function_exists('get_posts') || !did_action('init')) {
        return '';
    }
    $needs_lookup = (
        strpos($filename_or_url, '/themes/') !== false
        || strpos($filename_or_url, '.local') !== false
        || strpos($filename_or_url, 'assets/pdfs') !== false
        || strpos($filename_or_url, 'assets/images') !== false
    );
    if (!$needs_lookup) {
        return '';
    }
    $base = rawurldecode(basename(str_replace('\\', '/', $filename_or_url)));
    if ($base === '' || strpos($base, '.') === false || stripos($base, 'Logo_Header') !== false) {
        return '';
    }
    if (array_key_exists($base, $cache)) {
        return $cache[$base];
    }

    $guard = true;
    $found = get_posts(array(
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'suppress_filters' => true,
        'meta_query' => array(
            array(
                'key' => '_wp_attached_file',
                'value' => $base,
                'compare' => 'LIKE',
            ),
        ),
    ));
    $guard = false;

    $url = '';
    $attachment_id = (is_array($found) && isset($found[0])) ? (int) $found[0] : 0;
    if ($attachment_id > 0) {
        $rel = (string) get_post_meta($attachment_id, '_wp_attached_file', true);
        if ($rel !== '' && stripos($rel, 'Logo_Header') === false) {
            $uploads = wp_get_upload_dir();
            $abs = !empty($uploads['basedir']) ? $uploads['basedir'] . '/' . ltrim($rel, '/') : '';
            if ($abs !== '' && file_exists($abs) && !empty($uploads['baseurl'])) {
                $url = trailingslashit($uploads['baseurl']) . ltrim($rel, '/');
            }
        }
    }
    $cache[$base] = $url;
    return $url;
}

function casadepiedra_fix_tunnel_urls($value) {
    if (!is_string($value) || $value === '') {
        return $value;
    }
    $packaged = casadepiedra_theme_media_url($value);
    if ($packaged) {
        return $packaged;
    }
    $from_library = casadepiedra_media_library_url_by_basename($value);
    if ($from_library) {
        return $from_library;
    }
    if (function_exists('casa_is_theme_packaged_url') && casa_is_theme_packaged_url($value)) {
        return $from_library ?: '';
    }
    if (strpos($value, '/wp-content/') !== false) {
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
    'casa_opt_nosotros_hero_img',
    'casa_opt_nosotros_img1',
    'casa_opt_nosotros_img2',
    'casa_opt_contacto_bg',
    'casa_opt_contacto_portada',
    'casa_opt_espacios_portada',
    'casa_opt_restaurantes_portada',
    'casa_opt_eventos_portada',
    'casa_opt_galeria_portada',
    'casa_opt_privacidad_portada',
    'casa_opt_footer_bg_image',
    'casa_opt_mail_logo'
);
foreach ($casa_image_options as $opt) {
    add_filter("option_{$opt}", 'casadepiedra_fix_tunnel_urls', 99);
}

add_filter('option_casa_opt_global_logo', function ($value) {
    return function_exists('casa_usable_media') ? casa_usable_media($value) : $value;
}, 100);
add_filter('option_casa_opt_global_transition_logo', function ($value) {
    return function_exists('casa_usable_media') ? casa_usable_media($value) : $value;
}, 100);

// 2. Filtrar metadatos de imágenes/PDFs en Custom Post Types (Restaurantes, Espacios)
add_filter('get_post_metadata', function($check, $object_id, $meta_key, $single) {
    static $guard = false;
    static $keys = array(
        '_restaurante_logo',
        '_espacio_portada',
        '_restaurante_portada',
        '_restaurante_hero_image',
        '_restaurante_card_image',
        '_restaurante_menu',
        '_espacio_tarjeta_inicio',
        '_espacio_portada_url',
        '_espacio_plano_pdf',
        '_espacio_panorama',
    );
    if ($guard || $check !== null || !in_array($meta_key, $keys, true) || !function_exists('get_metadata_raw')) {
        return $check;
    }
    $guard = true;
    $stored = get_metadata_raw('post', $object_id, $meta_key, false);
    $guard = false;
    if (!is_array($stored) || $stored === array()) {
        return $check;
    }
    $mapped = array();
    foreach ($stored as $item) {
        $mapped[] = is_string($item) ? casadepiedra_fix_tunnel_urls($item) : $item;
    }
    if (!$single) {
        return $mapped;
    }
    if (array_key_exists(0, $mapped)) {
        return $mapped[0];
    }
    $first = reset($mapped);
    return ($first !== false) ? $first : $check;
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
    if (!is_object($args) || empty($args->theme_location) || $args->theme_location !== 'menu-principal') {
        return $items;
    }
    if (!is_array($items)) {
        return $items;
    }
    $espacios_parent = null;
    $restaurantes_parent = null;

    foreach ($items as $item) {
        if (!is_object($item) || empty($item->title)) {
            continue;
        }
        $title = strtolower(trim((string) $item->title));
        if ($title === 'espacios') {
            $espacios_parent = $item->ID;
            if (!isset($item->classes) || !is_array($item->classes)) {
                $item->classes = array();
            }
            $item->classes[] = 'menu-item-has-children';
        } elseif ($title === 'restaurantes') {
            $restaurantes_parent = $item->ID;
            if (!isset($item->classes) || !is_array($item->classes)) {
                $item->classes = array();
            }
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
    return $items;
}

function casadepiedra_resolve_restaurante_logo($post_id) {
    return casa_usable_media(get_post_meta($post_id, '_restaurante_logo', true));
}

function casadepiedra_resolve_restaurante_short_name($title) {
    if (stripos($title, 'Valentina') !== false) return 'Valentina';
    if (stripos($title, 'Casa M') !== false || stripos($title, 'Casa-Mia') !== false) return 'Casa Mía';
    if (stripos($title, 'Sole Mio') !== false) return 'Sole Mio';
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
    return casa_actionable_url(get_post_meta((int) $post_id, '_restaurante_google_reviews_url', true));
}

function casa_actionable_url($url) {
    $url = trim((string) $url);
    if ($url === '' || $url === '#' || stripos($url, 'javascript:') === 0) {
        return '';
    }
    if (stripos($url, 'tel:') === 0) {
        $digits = preg_replace('/[^0-9+]/', '', substr($url, 4));
        return $digits !== '' ? 'tel:' . $digits : '';
    }
    if (function_exists('casa_usable_media')) {
        $ok = casa_usable_media($url);
        if ($ok) {
            return $ok;
        }
    }
    if (function_exists('casa_is_theme_packaged_url') && casa_is_theme_packaged_url($url)) {
        return '';
    }
    if (function_exists('casa_is_dead_media_url') && casa_is_dead_media_url($url)) {
        return '';
    }
    return $url;
}

function casadepiedra_get_restaurante_maps_url($post_id) {
    return casa_actionable_url(get_post_meta((int) $post_id, '_restaurante_maps_url', true));
}

function casadepiedra_get_menu_url($post_id) {
    return casa_actionable_url(get_post_meta((int) $post_id, '_restaurante_menu', true));
}

function casadepiedra_resolve_restaurante_card_img($post_id) {
    $custom = casa_usable_media(get_post_meta($post_id, '_restaurante_card_image', true));
    if ($custom) {
        return $custom;
    }
    return casa_usable_media(has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'large') : '');
}

function casadepiedra_resolve_restaurante_hero($post_id) {
    $custom = casa_usable_media(get_post_meta($post_id, '_restaurante_hero_image', true));
    if ($custom) {
        return $custom;
    }
    $card = casadepiedra_resolve_restaurante_card_img($post_id);
    if ($card) {
        return $card;
    }
    return casa_usable_media(has_post_thumbnail($post_id) ? get_the_post_thumbnail_url($post_id, 'full') : '');
}

function casadepiedra_theme_file_url($relative) {
    $rel = ltrim(str_replace('\\', '/', (string) $relative), '/');
    if ($rel === '') {
        return '';
    }
    $abs = get_template_directory() . '/' . $rel;
    if (file_exists($abs)) {
        return get_template_directory_uri() . '/' . $rel;
    }
    return '';
}

function casadepiedra_resolve_theme_image_from_url($url) {
    return casa_usable_media($url);
}

function casadepiedra_espacio_fallback_by_title($title) {
    return '';
}

function casa_espacio_capacidad($post_id) {
    $raw = trim((string) get_post_meta((int) $post_id, '_espacio_capacidad', true));
    if ($raw === '') {
        return '';
    }
    return trim(str_ireplace(array('personas', 'px', 'hasta'), '', $raw));
}

function casa_espacio_m2($post_id) {
    $raw = trim((string) get_post_meta((int) $post_id, '_espacio_m2', true));
    if ($raw === '') {
        return '';
    }
    return trim(str_ireplace(array('m2', 'm²', 'metros'), '', $raw));
}

function casa_fix_stripped_unicode($text) {
    $text = (string) $text;
    return preg_replace_callback('/u00([0-9a-fA-F]{2})/', function ($match) {
        $char = html_entity_decode('&#x' . $match[1] . ';', ENT_QUOTES, 'UTF-8');
        return ($char !== '' && $char !== '&#x' . $match[1] . ';') ? $char : $match[0];
    }, $text);
}

function casa_espacio_tour_decode($post_id) {
    $raw = get_post_meta((int) $post_id, '_espacio_panorama_tour', true);
    $decoded = is_string($raw) ? json_decode($raw, true) : null;
    if (!is_array($decoded)) {
        $decoded = array();
    }
    $stops = array();
    foreach ($decoded as $stop) {
        if (!is_array($stop)) {
            continue;
        }
        $id = sanitize_key($stop['id'] ?? '');
        $src = esc_url_raw($stop['src'] ?? '');
        $name = casa_fix_stripped_unicode(sanitize_text_field($stop['name'] ?? ''));
        if ($id === '' || ($src === '' && $name === '')) {
            continue;
        }
        $links = array();
        if (!empty($stop['links']) && is_array($stop['links'])) {
            foreach ($stop['links'] as $link) {
                if (!is_array($link)) {
                    continue;
                }
                $to = sanitize_key($link['to'] ?? '');
                if ($to === '' || $to === $id) {
                    continue;
                }
                $links[] = array(
                    'to' => $to,
                    'yaw' => (float) ($link['yaw'] ?? 0),
                    'pitch' => (float) ($link['pitch'] ?? -0.45),
                );
            }
        }
        $stops[] = array(
            'id' => $id,
            'name' => $name !== '' ? $name : 'Parada',
            'src' => $src,
            'links' => $links,
        );
    }
    if (!$stops) {
        $single = trim((string) get_post_meta((int) $post_id, '_espacio_panorama', true));
        if ($single !== '') {
            $stops[] = array(
                'id' => 'principal',
                'name' => 'Punto principal',
                'src' => $single,
                'links' => array(),
            );
        }
    }
    $ids = array();
    foreach ($stops as $stop) {
        $ids[$stop['id']] = true;
    }
    foreach ($stops as $index => $stop) {
        $stops[$index]['links'] = array_values(array_filter($stop['links'], function ($link) use ($ids) {
            return isset($ids[$link['to']]);
        }));
    }
    return $stops;
}

function casa_espacio_tour_stations($post_id) {
    $stops = casa_espacio_tour_decode($post_id);
    return array_values(array_filter($stops, function ($stop) {
        return $stop['src'] !== '';
    }));
}

function casadepiedra_resolve_espacio_plano($post_id) {
    $custom = trim((string) get_post_meta((int) $post_id, '_espacio_plano_pdf', true));
    if ($custom === '') {
        return '';
    }
    if (function_exists('casa_usable_media')) {
        $ok = casa_usable_media($custom);
        if ($ok) {
            return $ok;
        }
    }
    if (function_exists('casa_is_theme_packaged_url') && casa_is_theme_packaged_url($custom)) {
        return '';
    }
    if (function_exists('casa_is_dead_media_url') && casa_is_dead_media_url($custom)) {
        return '';
    }
    return $custom;
}

function casadepiedra_resolve_espacio_card_img($post_id) {
    $post_id = (int) $post_id;
    foreach (array('_espacio_tarjeta_inicio', '_espacio_portada', '_espacio_portada_url') as $key) {
        $val = get_post_meta($post_id, $key, true);
        if (!empty($val)) {
            return casadepiedra_resolve_theme_image_from_url($val);
        }
    }
    if (has_post_thumbnail($post_id)) {
        $url = get_the_post_thumbnail_url($post_id, 'large');
        if ($url) {
            return casadepiedra_resolve_theme_image_from_url($url);
        }
    }
    return '';
}

function casadepiedra_resolve_espacio_hero($post_id) {
    $post_id = (int) $post_id;
    foreach (array('_espacio_portada', '_espacio_tarjeta_inicio', '_espacio_portada_url') as $key) {
        $val = get_post_meta($post_id, $key, true);
        if (!empty($val)) {
            return casadepiedra_resolve_theme_image_from_url($val);
        }
    }
    if (has_post_thumbnail($post_id)) {
        $url = get_the_post_thumbnail_url($post_id, 'full');
        if ($url) {
            return casadepiedra_resolve_theme_image_from_url($url);
        }
    }
    return '';
}


function casadepiedra_resolve_restaurante_gallery($post_id) {
    $urls = casa_gallery_urls_from_ids(get_post_meta($post_id, '_casadepiedra_gallery_ids', true));
    foreach (array(
        casadepiedra_resolve_restaurante_hero($post_id),
        casadepiedra_resolve_restaurante_card_img($post_id),
    ) as $extra) {
        if ($extra && !in_array($extra, $urls, true)) {
            $urls[] = $extra;
        }
    }
    return $urls;
}

add_filter('wp_nav_menu_objects', 'casadepiedra_add_dynamic_dropdowns', 10, 2);

function casa_galeria_attachment_item($id) {
    $id = (int) $id;
    if ($id <= 0) {
        return null;
    }
    $full = wp_get_attachment_image_url($id, 'full');
    if (!$full) {
        return null;
    }
    $thumb = wp_get_attachment_image_url($id, 'medium_large');
    if (!$thumb) {
        $thumb = wp_get_attachment_image_url($id, 'large') ?: $full;
    }
    $terms = get_the_terms($id, 'galeria_tag');
    $slugs = array();
    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $slugs[] = $term->slug;
        }
    }
    $tag_classes = $slugs ? implode(' ', array_map(function ($s) {
        return 'tag-' . $s;
    }, $slugs)) : '';
    return array(
        'full' => $full,
        'thumb' => $thumb,
        'srcset' => wp_get_attachment_image_srcset($id, 'medium_large') ?: '',
        'sizes' => '(max-width: 767px) 50vw, (max-width: 1279px) 33vw, 25vw',
        'title' => get_the_title($id) ?: 'Fotografía',
        'tag' => $slugs ? $slugs[0] : '',
        'tags_classes' => $tag_classes,
        'class' => trim('gallery-item luxury-card ' . $tag_classes),
    );
}

function casa_galeria_random_from($items, $slug = '') {
    $pool = array();
    foreach ((array) $items as $item) {
        if ($slug === '' || (!empty($item['tags_classes']) && strpos($item['tags_classes'], 'tag-' . $slug) !== false)) {
            $pool[] = $item;
        }
    }
    if (empty($pool)) {
        return null;
    }
    return $pool[array_rand($pool)];
}

function casa_galeria_cover_urls($items, $slug = '') {
    $urls = array();
    foreach ((array) $items as $item) {
        if ($slug === '' || (!empty($item['tags_classes']) && strpos($item['tags_classes'], 'tag-' . $slug) !== false)) {
            $url = !empty($item['thumb']) ? $item['thumb'] : $item['full'];
            if ($url) {
                $urls[] = $url;
            }
        }
    }
    return array_values(array_unique($urls));
}

add_action('template_redirect', function () {
    if (is_page('galeria')) {
        nocache_headers();
        if (!headers_sent()) {
            header('Cache-Control: private, no-store, no-cache, must-revalidate, max-age=0');
            header('Pragma: no-cache');
        }
    }
});

function casa_needs_gallery_assets() {
    return is_front_page() || is_page('galeria') || is_singular(array('espacios', 'restaurantes'));
}

function casa_lcp_image_url() {
    if (is_front_page()) {
        return casa_opt_media('casa_opt_home_hero_img');
    }
    if (is_post_type_archive('espacios') || is_page('espacios')) {
        return casa_opt_media('casa_opt_espacios_portada');
    }
    if (is_post_type_archive('restaurantes') || is_page('restaurantes')) {
        return casa_opt_media('casa_opt_restaurantes_portada');
    }
    if (is_post_type_archive('eventos') || is_page('eventos')) {
        return casa_opt_media('casa_opt_eventos_portada');
    }
    if (is_page('galeria')) {
        return casa_opt_media('casa_opt_galeria_portada');
    }
    if (is_page(array('quienes-somos', 'nosotros'))) {
        return casa_opt_media('casa_opt_nosotros_portada');
    }
    if (is_page('contacto')) {
        return casa_opt_media('casa_opt_contacto_portada');
    }
    if (is_page('aviso-de-privacidad')) {
        return casa_opt_media('casa_opt_privacidad_portada');
    }
    if (is_singular('espacios')) {
        return casadepiedra_resolve_espacio_hero(get_the_ID());
    }
    if (is_singular('restaurantes')) {
        return casadepiedra_resolve_restaurante_hero(get_the_ID());
    }
    if (is_singular('eventos')) {
        if (has_post_thumbnail()) {
            return get_the_post_thumbnail_url(null, 'full');
        }
        return casa_opt_media('casa_opt_eventos_portada');
    }
    return '';
}

add_action('wp_head', function () {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>' . "\n";
    $lcp = casa_lcp_image_url();
    if ($lcp) {
        echo '<link rel="preload" as="image" href="' . esc_url($lcp) . '" fetchpriority="high">' . "\n";
    }
}, 0);

function casadepiedra_scripts() {
    wp_enqueue_style(
        'casa-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Great+Vibes&family=Inter:wght@400;500;600&display=swap',
        array(),
        null
    );
    wp_enqueue_style('casadepiedra-style', get_stylesheet_uri(), array('casa-fonts'), '4.0.9');

    $defer = array('in_footer' => true, 'strategy' => 'defer');
    $gallery = casa_needs_gallery_assets();

    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', $defer);
    wp_enqueue_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), '3.12.2', $defer);

    if ($gallery) {
        wp_enqueue_style('glightbox-css', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css', array(), '3.2.0');
        wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', array(), '3.2.0', $defer);
    }

    wp_enqueue_script('animejs', get_template_directory_uri() . '/assets/js/anime.min.js', array(), '3.2.1', $defer);

    $deps = array('gsap', 'gsap-scrolltrigger', 'animejs');
    if (!wp_is_mobile()) {
        wp_enqueue_script('lenis', 'https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.29/bundled/lenis.min.js', array(), '1.0.29', $defer);
        $deps[] = 'lenis';
    }
    if ($gallery) {
        $deps[] = 'glightbox';
    }
    wp_enqueue_script('casadepiedra-app', get_template_directory_uri() . '/assets/js/app.js', $deps, '3.6.7', $defer);
    wp_enqueue_script('casadepiedra-animated-favicon', get_template_directory_uri() . '/assets/js/animated-favicon.js', array(), '4.0.1', $defer);
    wp_add_inline_script('casadepiedra-animated-favicon', 'window.casadepiedraThemeUrl = "' . esc_js(get_template_directory_uri()) . '";', 'before');

    if (is_singular('espacios') && function_exists('casa_espacio_tour_stations')) {
        $tour = casa_espacio_tour_stations(get_queried_object_id());
        if (!empty($tour)) {
            wp_enqueue_script('casa-espacio-panorama', get_template_directory_uri() . '/assets/js/espacio-panorama.js', array(), '1.2.0', $defer);
        }
    }
}
add_action('wp_enqueue_scripts', 'casadepiedra_scripts');

add_filter('style_loader_tag', function ($html, $handle) {
    if ($handle === 'glightbox-css') {
        $html = str_replace("media='all'", "media='print' onload=\"this.onload=null;this.media='all'\"", $html);
        $html = str_replace('media="all"', 'media="print" onload="this.onload=null;this.media=\'all\'"', $html);
    }
    return $html;
}, 10, 2);

add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
}, 20);

// Favicon estático para Google (/favicon.ico + apple-touch). El de la pestaña se anima en JS.
remove_action('wp_head', 'wp_site_icon', 99);
add_filter('site_icon_meta_tags', '__return_empty_array', 999);
remove_action('do_faviconico', 'do_faviconico');
add_action('do_faviconico', function () {
    $url = function_exists('casa_google_favicon_url') ? casa_google_favicon_url() : '';
    if ($url) {
        wp_redirect($url, 301);
        exit;
    }
});

add_action('wp_head', function () {
    $static = function_exists('casa_google_favicon_url') ? casa_google_favicon_url() : '';
    $animated = get_template_directory_uri() . '/assets/images/favicons/favicon-1.png';
    echo "\n<!-- Casa de Piedra: favicon Google (estático) + pestaña (animado) -->\n";
    if ($static) {
        echo '<link id="casa-google-favicon" rel="icon" type="image/png" sizes="48x48" href="' . esc_url($static) . '" />' . "\n";
        echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url($static) . '" />' . "\n";
    }
    echo '<link id="casa-dynamic-favicon" rel="icon" type="image/png" sizes="32x32" href="' . esc_url($animated) . '" />' . "\n";
}, 1);

function casadepiedra_admin_scripts($hook) {
    if (strpos($hook, 'casa-panel') !== false || in_array($hook, array('post.php', 'post-new.php'))) {
        wp_enqueue_media();
        wp_enqueue_script('casadepiedra-admin-gallery', get_template_directory_uri() . '/assets/js/admin-gallery.js', array('jquery'), '4.1.0', true);
        $screen = function_exists('get_current_screen') ? get_current_screen() : null;
        if ($screen && $screen->post_type === 'espacios' && in_array($hook, array('post.php', 'post-new.php'), true)) {
            wp_enqueue_script('casa-espacio-panorama', get_template_directory_uri() . '/assets/js/espacio-panorama.js', array(), '1.2.0', true);
            wp_enqueue_script('casa-espacio-panorama-admin', get_template_directory_uri() . '/assets/js/espacio-panorama-admin.js', array('jquery', 'casa-espacio-panorama'), '1.2.0', true);
        }
        wp_localize_script('casadepiedra-admin-gallery', 'casaGaleriaAdmin', array(
            'ajax' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('casa_galeria_tags'),
        ));
    }
}
add_action('admin_enqueue_scripts', 'casadepiedra_admin_scripts');

function casa_galeria_sync_tags_option() {
    $terms = get_terms(array('taxonomy' => 'galeria_tag', 'hide_empty' => false));
    if (is_wp_error($terms) || empty($terms)) {
        update_option('casa_opt_galeria_etiquetas', '');
        return array();
    }
    $names = wp_list_pluck($terms, 'name');
    update_option('casa_opt_galeria_etiquetas', implode(', ', $names));
    return $terms;
}

function casa_galeria_ensure_tags() {
    $terms = get_terms(array('taxonomy' => 'galeria_tag', 'hide_empty' => false));
    if (!is_wp_error($terms) && !empty($terms)) {
        return $terms;
    }
    $raw = get_option('casa_opt_galeria_etiquetas', 'Boda, Cumpleaños, Eventos empresariales, Convenciones');
    $names = array_filter(array_map('trim', explode(',', (string) $raw)));
    if (empty($names)) {
        $names = array('Boda', 'Cumpleaños', 'Eventos empresariales', 'Convenciones');
    }
    foreach ($names as $name) {
        if (!term_exists($name, 'galeria_tag')) {
            wp_insert_term($name, 'galeria_tag');
        }
    }
    return casa_galeria_sync_tags_option();
}

function casa_galeria_ajax_guard() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Sin permisos.'), 403);
    }
    check_ajax_referer('casa_galeria_tags', 'nonce');
}

function casa_galeria_term_payload($term) {
    return array(
        'id' => (int) $term->term_id,
        'name' => $term->name,
        'slug' => $term->slug,
        'count' => (int) $term->count,
    );
}

add_action('wp_ajax_casa_galeria_create_tag', function () {
    casa_galeria_ajax_guard();
    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    if ($name === '') {
        wp_send_json_error(array('message' => 'Escribe un nombre para la etiqueta.'));
    }
    if (term_exists($name, 'galeria_tag')) {
        wp_send_json_error(array('message' => 'Esa etiqueta ya existe. Elígela en la lista.'));
    }
    $created = wp_insert_term($name, 'galeria_tag');
    if (is_wp_error($created)) {
        wp_send_json_error(array('message' => $created->get_error_message()));
    }
    $term = get_term((int) $created['term_id'], 'galeria_tag');
    casa_galeria_sync_tags_option();
    wp_send_json_success(array('tag' => casa_galeria_term_payload($term)));
});

add_action('wp_ajax_casa_galeria_rename_tag', function () {
    casa_galeria_ajax_guard();
    $id = absint($_POST['id'] ?? 0);
    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    if (!$id || $name === '') {
        wp_send_json_error(array('message' => 'Nombre no válido.'));
    }
    $updated = wp_update_term($id, 'galeria_tag', array('name' => $name));
    if (is_wp_error($updated)) {
        wp_send_json_error(array('message' => $updated->get_error_message()));
    }
    $term = get_term($id, 'galeria_tag');
    casa_galeria_sync_tags_option();
    wp_send_json_success(array('tag' => casa_galeria_term_payload($term)));
});

add_action('wp_ajax_casa_galeria_delete_tag', function () {
    casa_galeria_ajax_guard();
    $id = absint($_POST['id'] ?? 0);
    if (!$id) {
        wp_send_json_error(array('message' => 'Etiqueta no válida.'));
    }
    $deleted = wp_delete_term($id, 'galeria_tag');
    if (is_wp_error($deleted) || !$deleted) {
        wp_send_json_error(array('message' => 'No se pudo eliminar.'));
    }
    casa_galeria_sync_tags_option();
    wp_send_json_success();
});

add_action('wp_ajax_casa_galeria_set_image_tags', function () {
    casa_galeria_ajax_guard();
    $attachment_id = absint($_POST['attachment_id'] ?? 0);
    $term_ids = isset($_POST['term_ids']) ? array_map('absint', (array) $_POST['term_ids']) : array();
    $term_ids = array_values(array_filter($term_ids));
    if (!$attachment_id || get_post_type($attachment_id) !== 'attachment') {
        wp_send_json_error(array('message' => 'Fotografía no válida.'));
    }
    $result = wp_set_object_terms($attachment_id, $term_ids, 'galeria_tag', false);
    if (is_wp_error($result)) {
        wp_send_json_error(array('message' => $result->get_error_message()));
    }
    wp_send_json_success(array('term_ids' => $term_ids));
});

add_action('wp_ajax_casa_galeria_save_ids', function () {
    casa_galeria_ajax_guard();
    $ids = sanitize_text_field(wp_unslash($_POST['ids'] ?? ''));
    $clean = array();
    foreach (explode(',', $ids) as $id) {
        $id = absint($id);
        if ($id && get_post_type($id) === 'attachment') {
            $clean[] = $id;
        }
    }
    update_option('casa_opt_galeria_imagenes', implode(',', array_unique($clean)));
    wp_send_json_success(array('ids' => implode(',', $clean)));
});

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
        'show_ui' => false,
        'show_admin_column' => false,
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
    $maps_url = get_post_meta($post->ID, '_restaurante_maps_url', true);
    $rating = get_post_meta($post->ID, '_restaurante_rating', true);
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

    echo '<div class="cdp-meta-field"><label for="restaurante_rating">Calificación ⭐ (solo si es real, no inventar):</label>';
    echo '<input type="text" id="restaurante_rating" name="restaurante_rating" value="' . esc_attr($rating) . '" placeholder="Dejar vacío si no hay calificación oficial" /></div>';

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

    echo '<div class="cdp-meta-field"><label for="restaurante_maps_url">Ubicación en Google Maps (si es distinta al recinto):</label>';
    echo '<input type="url" id="restaurante_maps_url" name="restaurante_maps_url" value="' . esc_attr($maps_url) . '" placeholder="Vacío = se oculta el botón Ubicación" /></div>';

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
        '_restaurante_maps_url' => 'restaurante_maps_url',
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
    $valor = trim((string) get_post_meta($post_id, '_restaurante_reserva_valor', true));
    if ($valor === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#i', $valor) || stripos($valor, 'wa.me') !== false || stripos($valor, 'wa.link') !== false) {
        return casa_actionable_url($valor);
    }
    $tipo = get_post_meta($post_id, '_restaurante_reserva_tipo', true);
    if ($tipo === 'tel') {
        $tel = preg_replace('/[^0-9+]/', '', $valor);
        return $tel !== '' ? 'tel:' . $tel : '';
    }
    if ($tipo === 'whatsapp') {
        $digits = preg_replace('/[^0-9]/', '', $valor);
        if ($digits === '') {
            return '';
        }
        if (strlen($digits) === 10) {
            $digits = '52' . $digits;
        }
        return 'https://wa.me/' . $digits;
    }
    return '';
}

// 5. Cargar Panel de Administración Global
require_once get_template_directory() . '/inc/admin-panel.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/seo-admin.php';

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
                echo '<div class="casa-gallery-item" data-id="'.esc_attr($id).'" style="display:inline-block; position:relative; overflow:visible; z-index:1;"><img src="'.esc_url($img[0]).'" style="width:95px; height:75px; object-fit:cover; display:block; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.05);" /><button type="button" class="casa-remove-single-img-btn" data-id="'.esc_attr($id).'" title="Eliminar foto individual" style="position:absolute; top:-6px; right:-6px; z-index:5; background:#dc2626; color:#fff; border:2px solid #fff; border-radius:50%; width:24px; height:24px; font-size:12px; font-weight:bold; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,0.25); line-height:1;">×</button></div>';
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
    $tarjeta_inicio = get_post_meta($post->ID, '_espacio_tarjeta_inicio', true);
    $subt = get_post_meta($post->ID, '_espacio_subt', true);
    
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

    echo '<div class="cdp-meta-field" style="margin-top:20px;"><label for="espacio_tarjeta_inicio">Imagen de Tarjeta del Inicio (Index):</label>';
    echo '<div style="display:flex; gap:8px;">';
    echo '<input type="url" id="espacio_tarjeta_inicio" name="espacio_tarjeta_inicio" value="' . esc_attr($tarjeta_inicio) . '" placeholder="https://..." />';
    echo '<button type="button" id="btn_upload_espacio_tarjeta" class="cdp-btn-gold" style="white-space:nowrap;">Seleccionar Imagen</button>';
    echo '</div>';
    if ($tarjeta_inicio) {
        echo '<img src="' . esc_url($tarjeta_inicio) . '" class="cdp-img-preview" />';
    }
    echo '<small style="color:#64748b; display:block; margin-top:6px;">Imagen que se mostrará en las tarjetas de la página de inicio para este salón.</small></div>';

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

    echo '<div class="cdp-meta-field"><label for="espacio_subt">Subtítulo del banner:</label>';
    echo '<input type="text" id="espacio_subt" name="espacio_subt" value="' . esc_attr($subt) . '" placeholder="Ej: Jardín de eventos" /></div>';

    echo '<div class="cdp-meta-field"><label for="espacio_capacidad">Capacidad Máxima de Personas (Pax):</label>';
    echo '<input type="text" id="espacio_capacidad" name="espacio_capacidad" value="' . esc_attr($capacidad) . '" placeholder="Ej: 1,500" />';
    echo '<small style="color:#64748b; display:block; margin-top:6px;">Ejemplo: "1,500" se mostrará como "Hasta 1,500 Pax".</small></div>';

    echo '<div class="cdp-meta-field"><label for="espacio_rango_personas">Rangos de Personas para Modal de Cotización:</label>';
    echo '<input type="text" id="espacio_rango_personas" name="espacio_rango_personas" value="' . esc_attr($rango_personas) . '" placeholder="Ej: 1 a 150 personas, 151 a 400 personas, Más de 400 personas" />';
    echo '<small style="color:#64748b; display:block; margin-top:6px;">Opciones separadas por coma para cotización en línea.</small></div>';

    echo '<div class="cdp-meta-field"><label for="espacio_m2">Superficie / Área en Metros Cuadrados (m²):</label>';
    echo '<input type="text" id="espacio_m2" name="espacio_m2" value="' . esc_attr($m2) . '" placeholder="Ej: 1,500" />';
    echo '<small style="color:#64748b; display:block; margin-top:6px;">Ejemplo: "1,500" se mostrará como "1,500 m²".</small></div>';

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

        $("#btn_upload_espacio_tarjeta").on("click", function(e){
            e.preventDefault();
            var frame = wp.media({
                title: "Seleccionar Imagen de Tarjeta de Inicio",
                button: { text: "Usar Imagen" },
                multiple: false
            });
            frame.on("select", function(){
                var attachment = frame.state().get("selection").first().toJSON();
                $("#espacio_tarjeta_inicio").val(attachment.url);
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
    if (isset($_POST['espacio_tarjeta_inicio'])) {
        update_post_meta($post_id, '_espacio_tarjeta_inicio', esc_url_raw($_POST['espacio_tarjeta_inicio']));
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
    if (isset($_POST['espacio_panorama_tour'])) {
        $tour_raw = wp_unslash($_POST['espacio_panorama_tour']);
        $tour_decoded = json_decode($tour_raw, true);
        $tour_clean = array();
        if (is_array($tour_decoded)) {
            foreach ($tour_decoded as $stop) {
                if (!is_array($stop)) {
                    continue;
                }
                $id = sanitize_key($stop['id'] ?? '');
                $src = esc_url_raw($stop['src'] ?? '');
                $name = casa_fix_stripped_unicode(sanitize_text_field($stop['name'] ?? ''));
                if ($id === '' || ($src === '' && $name === '')) {
                    continue;
                }
                $links = array();
                if (!empty($stop['links']) && is_array($stop['links'])) {
                    foreach ($stop['links'] as $link) {
                        if (!is_array($link)) {
                            continue;
                        }
                        $to = sanitize_key($link['to'] ?? '');
                        if ($to === '' || $to === $id) {
                            continue;
                        }
                        $links[] = array(
                            'to' => $to,
                            'yaw' => round((float) ($link['yaw'] ?? 0), 4),
                            'pitch' => round((float) ($link['pitch'] ?? -0.45), 4),
                        );
                    }
                }
                $tour_clean[] = array(
                    'id' => $id,
                    'name' => $name !== '' ? $name : 'Parada',
                    'src' => $src,
                    'links' => $links,
                );
            }
        }
        update_post_meta($post_id, '_espacio_panorama_tour', wp_json_encode($tour_clean, JSON_UNESCAPED_UNICODE));
        $first_src = '';
        foreach ($tour_clean as $stop) {
            if ($stop['src'] !== '') {
                $first_src = $stop['src'];
                break;
            }
        }
        update_post_meta($post_id, '_espacio_panorama', $first_src);
    } elseif (isset($_POST['espacio_panorama'])) {
        update_post_meta($post_id, '_espacio_panorama', esc_url_raw($_POST['espacio_panorama']));
    }
    if (isset($_POST['espacio_subt'])) {
        update_post_meta($post_id, '_espacio_subt', sanitize_text_field($_POST['espacio_subt']));
    }
}
add_action('save_post_espacios', 'casadepiedra_save_espacio_meta');

function casadepiedra_add_espacio_tour_meta() {
    add_meta_box(
        'casadepiedra_espacio_tour',
        'Recorrido 360 por paradas',
        'casadepiedra_espacio_tour_meta_callback',
        'espacios',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'casadepiedra_add_espacio_tour_meta');

function casadepiedra_espacio_tour_meta_callback($post) {
    $stops = function_exists('casa_espacio_tour_decode') ? casa_espacio_tour_decode($post->ID) : array();
    $first = '';
    foreach ($stops as $stop) {
        if (!empty($stop['src'])) {
            $first = $stop['src'];
            break;
        }
    }
    ?>
    <style>
        .casa-tour-editor { display: grid; grid-template-columns: minmax(260px, 340px) 1fr; gap: 18px; }
        .casa-tour-help { margin: 0 0 14px; color: #475569; line-height: 1.5; }
        .casa-tour-help ol { margin: 8px 0 0 18px; }
        .casa-tour-stop { border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; margin-bottom: 10px; background: #fff; }
        .casa-tour-stop label { display: block; font-weight: 600; margin-bottom: 4px; }
        .casa-tour-stop input[type="text"], .casa-tour-stop input[type="url"] { width: 100%; }
        .casa-tour-row { display: flex; gap: 8px; align-items: center; }
        .casa-tour-actions { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
        .casa-tour-preview-tools { display: flex; flex-wrap: wrap; gap: 8px; align-items: end; margin: 10px 0; }
        .casa-tour-preview-tools label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 4px; }
        .casa-tour-links { margin: 0; padding: 0; list-style: none; }
        .casa-tour-links li { display: flex; gap: 8px; align-items: center; justify-content: space-between; padding: 8px 0; border-top: 1px solid #e2e8f0; }
        .casa-tour-stage-wrap { --color-accent: #c1621e; }
        .casa-tour-stage-wrap .espacio-tour__stage { position: relative; height: 460px; border-radius: 12px; overflow: hidden; border: 1px solid rgba(193,98,30,.4); background: #050505; touch-action: none; cursor: grab; }
        .casa-tour-stage-wrap .espacio-tour__stage.is-dragging { cursor: grabbing; }
        .casa-tour-stage-wrap .espacio-tour__canvas { width: 100%; height: 100%; display: block; }
        .casa-tour-stage-wrap .espacio-tour__status, .casa-tour-stage-wrap .espacio-tour__hint, .casa-tour-stage-wrap .espacio-tour__place { position: absolute; z-index: 2; }
        .casa-tour-stage-wrap .espacio-tour__status { inset: 0; display: flex; align-items: center; justify-content: center; margin: 0; color: #eee; background: rgba(5,5,5,.55); pointer-events: none; }
        .casa-tour-stage-wrap .espacio-tour__stage.is-ready:not(.is-loading) .espacio-tour__status { display: none; }
        .casa-tour-stage-wrap .espacio-tour__hint { top: 12px; left: 50%; transform: translateX(-50%); margin: 0; padding: 6px 12px; border-radius: 999px; background: rgba(8,8,8,.62); border: 1px solid rgba(193,98,30,.35); color: #f3f3f3; font-size: 12px; pointer-events: none; }
        .casa-tour-stage-wrap .espacio-tour__stage.is-used .espacio-tour__hint { opacity: 0; }
        .casa-tour-stage-wrap .espacio-tour__controls { position: absolute; left: 12px; right: 12px; bottom: 12px; display: flex; justify-content: space-between; align-items: flex-end; z-index: 3; pointer-events: none; }
        .casa-tour-stage-wrap .espacio-tour__pad, .casa-tour-stage-wrap .espacio-tour__zoom { pointer-events: auto; }
        .casa-tour-stage-wrap .espacio-tour__pad { display: grid; grid-template-columns: repeat(3, 42px); grid-template-areas: ". up ." "left mid right" ". down ."; gap: 6px; }
        .casa-tour-stage-wrap .espacio-tour__pad button[data-pan="up"] { grid-area: up; }
        .casa-tour-stage-wrap .espacio-tour__pad button[data-pan="left"] { grid-area: left; }
        .casa-tour-stage-wrap .espacio-tour__pad button[data-pan="right"] { grid-area: right; }
        .casa-tour-stage-wrap .espacio-tour__pad button[data-pan="down"] { grid-area: down; }
        .casa-tour-stage-wrap .espacio-tour__pad-core { grid-area: mid; width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #c1621e; border: 1px solid rgba(193,98,30,.35); }
        .casa-tour-stage-wrap .espacio-tour__zoom { display: flex; flex-direction: column; gap: 6px; }
        .casa-tour-stage-wrap .espacio-tour__pad button, .casa-tour-stage-wrap .espacio-tour__zoom button { width: 42px; height: 42px; border-radius: 50%; border: 1px solid rgba(193,98,30,.55); background: rgba(8,8,8,.72); color: #fff; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
        .casa-tour-stage-wrap .espacio-tour__pad svg, .casa-tour-stage-wrap .espacio-tour__zoom svg { width: 16px; height: 16px; display: block; }
        .casa-tour-stage-wrap .espacio-tour__hotspots { position: absolute; inset: 0; z-index: 4; pointer-events: none; }
        .casa-tour-stage-wrap .espacio-tour__hotspot { position: absolute; transform: translate(-50%, -50%); pointer-events: auto; border: 0; background: transparent; color: #fff; cursor: grab; display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 0; }
        .casa-tour-stage-wrap .espacio-tour__hotspot:active { cursor: grabbing; }
        .casa-tour-stage-wrap .espacio-tour__hotspot-arrow { width: 42px; height: 42px; border-radius: 50%; background: #c1621e; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 18px rgba(0,0,0,.45); }
        .casa-tour-stage-wrap .espacio-tour__hotspot-label { font-size: 11px; background: rgba(8,8,8,.78); border: 1px solid rgba(193,98,30,.45); border-radius: 999px; padding: 2px 8px; }
        .casa-tour-stage-wrap .espacio-tour__place { top: 12px; left: 12px; margin: 0; color: #fff; font-size: 13px; letter-spacing: .4px; background: rgba(8,8,8,.62); border: 1px solid rgba(193,98,30,.35); border-radius: 999px; padding: 4px 10px; }
        @media (max-width: 960px) { .casa-tour-editor { grid-template-columns: 1fr; } }
    </style>
    <p class="casa-tour-help">Cada parada es una fotografía 360. Para caminar de una a otra:</p>
    <ol class="casa-tour-help">
        <li>Añade una parada por cada foto y súbela.</li>
        <li>Elige la parada de destino y pulsa <strong>Poner flecha en esta vista</strong>. También se crea la flecha de regreso.</li>
        <li>Arrastra la flecha naranja hasta el punto exacto del piso. Gira la vista: la flecha se queda en ese lugar y un clic salta a la otra foto.</li>
        <li>Cambia a la otra parada y arrastra también su flecha de regreso. Después pulsa <strong>Actualizar</strong>.</li>
    </ol>
    <div id="casa-tour-editor" class="casa-tour-editor" data-stops="<?php echo esc_attr(wp_json_encode($stops, JSON_UNESCAPED_UNICODE)); ?>">
        <div>
            <div data-tour-list></div>
            <button type="button" class="button button-primary" data-tour-add>Añadir parada</button>
        </div>
        <div class="casa-tour-stage-wrap">
            <div class="espacio-tour__stage" data-panorama="<?php echo esc_url($first); ?>" data-editor="1" data-lenis-prevent tabindex="0">
                <canvas class="espacio-tour__canvas"></canvas>
                <p class="espacio-tour__status"><?php echo $first ? 'Cargando recorrido…' : 'Sube la fotografía 360 de esta parada.'; ?></p>
                <p class="espacio-tour__hint">Arrastra la flecha hasta el punto exacto del piso</p>
                <p class="espacio-tour__place" hidden></p>
                <div class="espacio-tour__hotspots"></div>
                <div class="espacio-tour__controls">
                    <div class="espacio-tour__pad" role="group" aria-label="Mover la vista">
                        <button type="button" data-pan="up" aria-label="Mirar arriba"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M9 4.5L14 11.5H4L9 4.5Z" fill="currentColor"/></svg></button>
                        <button type="button" data-pan="left" aria-label="Mirar a la izquierda"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M4.5 9L11.5 4V14L4.5 9Z" fill="currentColor"/></svg></button>
                        <span class="espacio-tour__pad-core" aria-hidden="true">360</span>
                        <button type="button" data-pan="right" aria-label="Mirar a la derecha"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M13.5 9L6.5 4V14L13.5 9Z" fill="currentColor"/></svg></button>
                        <button type="button" data-pan="down" aria-label="Mirar abajo"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M9 13.5L4 6.5H14L9 13.5Z" fill="currentColor"/></svg></button>
                    </div>
                    <div class="espacio-tour__zoom">
                        <button type="button" data-pan="in" aria-label="Acercar"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M9 3.5V14.5M3.5 9H14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
                        <button type="button" data-pan="out" aria-label="Alejar"><svg viewBox="0 0 18 18" aria-hidden="true"><path d="M3.5 9H14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
                    </div>
                </div>
            </div>
            <div class="casa-tour-preview-tools">
                <div>
                    <label for="casa-tour-active">Parada en vista</label>
                    <select id="casa-tour-active" data-tour-active></select>
                </div>
                <div>
                    <label for="casa-tour-dest">Flecha hacia</label>
                    <select id="casa-tour-dest" data-tour-dest></select>
                </div>
                <button type="button" class="button button-primary" data-tour-drop>Poner flecha en esta vista</button>
            </div>
            <ul class="casa-tour-links" data-tour-links></ul>
        </div>
    </div>
    <input type="hidden" name="espacio_panorama_tour" id="espacio_panorama_tour" value="<?php echo esc_attr(wp_json_encode($stops, JSON_UNESCAPED_UNICODE)); ?>" />
    <?php
}

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

if (!function_exists('casadepiedra_populate_data')) {
    function casadepiedra_populate_data() {
        casadepiedra_plug_and_play_setup();
    }
}

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
        $evt_page = get_page_by_path('eventos') ?: casadepiedra_get_post_by_title('Eventos');
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
            $item->url = trailingslashit(home_url('/contacto/'));
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
        $contacto_item->url = trailingslashit(home_url('/contacto/'));
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
            .header h2 { color: #c1621e; margin: 0; font-weight: 300; letter-spacing: 2px; }
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
            .header h2 { color: #c1621e; margin: 0; font-weight: 300; letter-spacing: 2px; text-transform: uppercase; }
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
                
                <p style="margin-top: 30px;">Si necesitas atención inmediata, no dudes en llamarnos al <a href="tel:' . esc_attr(preg_replace('/\D+/', '', casa_get_display_phone())) . '" style="color: #c1621e;">' . esc_html(casa_get_display_phone()) . '</a>.</p>
            </div>
            <div class="footer">
                Ex Hacienda Casa de Piedra<br>
                León, Guanajuato, México
            </div>
        </div>
    </body>
    </html>';

    $headers = array('Content-Type: text/html; charset=UTF-8');
    if (is_email($email)) {
        $headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
    }

    // Send to admin
    $sent_admin = wp_mail($admin_email, $admin_subject, $admin_message, $headers);

    $client_headers = array('Content-Type: text/html; charset=UTF-8');
    $sent_client = wp_mail($email, $client_subject, $client_message, $client_headers);

    if ($sent_admin) {
        wp_send_json_success();
    }

    $mail_err = get_transient('casa_last_mail_error');
    wp_send_json_error($mail_err ? ('No se pudo enviar el correo: ' . $mail_err) : 'Hubo un problema al enviar el correo. Por favor intenta llamarnos.');
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
        update_option('casa_opt_global_restaurantes_title', 'La cúspide de la gastronomía en el Bajío.');
        update_option('casa_opt_global_restaurantes_desc', 'Una experiencia inigualable que reúne la oferta gastronómica más exclusivas de la región, ofreciendo un viaje de sabores únicos.');
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
            $post = casadepiedra_get_post_by_title($title, 'espacios');
            if (!$post && $title === 'Terraza Mezquite') {
                $post = casadepiedra_get_post_by_title('Terraza del Mezquite', 'espacios');
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
            'Manolo',
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

        update_option('casa_texts_synced_v6', '1');
        update_option('casa_texts_synced_v7_clean_restaurantes', '1');
    }
});

add_action('init', function() {
    if (get_option('casa_copy_synced_v8') === '1') {
        return;
    }
    update_option('casa_opt_global_restaurantes_title', 'La cúspide de la gastronomía en el Bajío.');
    update_option('casa_opt_global_restaurantes_desc', 'Una experiencia inigualable que reúne la oferta gastronómica más exclusivas de la región, ofreciendo un viaje de sabores únicos.');

    $jardin = casadepiedra_get_post_by_title('Jardín Principal', 'espacios');
    if ($jardin) {
        update_post_meta($jardin->ID, '_espacio_m2', '1,500');
    }
    update_option('casa_copy_synced_v8', '1');
});

/* Títulos SEO: los define inc/seo.php (keywords + entidad, no mayúsculas). */

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

/**
 * Configuración SMTP Global para WordPress
 * Conecta wp_mail() con las opciones del panel "Mails (Correos)"
 */
function casa_smtp_from_email() {
    $from = trim((string) get_option('casa_opt_smtp_from_email', ''));
    if (is_email($from)) {
        return $from;
    }
    $user = trim((string) get_option('casa_opt_smtp_username', ''));
    if (is_email($user)) {
        return $user;
    }
    $global = trim((string) get_option('casa_opt_global_email', ''));
    return is_email($global) ? $global : get_option('admin_email');
}

function casa_smtp_from_name() {
    $name = trim((string) get_option('casa_opt_smtp_from_name', ''));
    return $name !== '' ? $name : 'Casa de Piedra';
}

add_filter('wp_mail_from', function ($from) {
    $opt = casa_smtp_from_email();
    return is_email($opt) ? $opt : $from;
});

add_filter('wp_mail_from_name', function ($name) {
    return casa_smtp_from_name() ?: $name;
});

add_action('phpmailer_init', function ($phpmailer) {
    $smtp_host = trim((string) get_option('casa_opt_smtp_host', ''));
    if ($smtp_host === '') {
        return;
    }

    $smtp_user = trim((string) get_option('casa_opt_smtp_username', ''));
    $smtp_pass = (string) get_option('casa_opt_smtp_password', '');
    $smtp_enc  = strtolower(trim((string) get_option('casa_opt_smtp_encryption', 'tls')));
    $smtp_port = (int) get_option('casa_opt_smtp_port', 0);

    if ($smtp_port <= 0) {
        $smtp_port = ($smtp_enc === 'ssl') ? 465 : 587;
    }
    if ($smtp_enc === '' || $smtp_enc === 'auto') {
        $smtp_enc = ($smtp_port === 465) ? 'ssl' : 'tls';
    }

    $phpmailer->isSMTP();
    $phpmailer->Host = $smtp_host;
    $phpmailer->Port = $smtp_port;
    $phpmailer->Timeout = 20;
    $phpmailer->CharSet = 'UTF-8';
    $phpmailer->SMTPAutoTLS = true;

    if ($smtp_enc === 'ssl' || $smtp_enc === 'tls') {
        $phpmailer->SMTPSecure = $smtp_enc;
    } else {
        $phpmailer->SMTPSecure = '';
        $phpmailer->SMTPAutoTLS = false;
    }

    if ($smtp_user !== '' && $smtp_pass !== '') {
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = $smtp_user;
        $phpmailer->Password = $smtp_pass;
    } else {
        $phpmailer->SMTPAuth = false;
    }

    $from_email = casa_smtp_from_email();
    $from_name  = casa_smtp_from_name();
    if (is_email($from_email)) {
        try {
            $phpmailer->setFrom($from_email, $from_name, false);
        } catch (Exception $e) {
            $phpmailer->From = $from_email;
            $phpmailer->FromName = $from_name;
        }
        $phpmailer->Sender = $from_email;
    }
});

add_action('wp_mail_failed', function ($error) {
    if (is_wp_error($error)) {
        set_transient('casa_last_mail_error', $error->get_error_message(), 15 * MINUTE_IN_SECONDS);
    }
});

add_action('wp_ajax_casa_test_smtp', function () {
    if (!current_user_can('manage_options') || !check_ajax_referer('casa_test_smtp', 'nonce', false)) {
        wp_send_json_error('No autorizado.');
    }
    $to = sanitize_email(wp_unslash($_POST['to'] ?? ''));
    if (!is_email($to)) {
        $to = casa_smtp_from_email();
    }
    if (!is_email($to)) {
        wp_send_json_error('Indica un correo válido para la prueba.');
    }
    delete_transient('casa_last_mail_error');
    $host = trim((string) get_option('casa_opt_smtp_host', ''));
    $body = '<p>Este es un correo de prueba del panel Casa de Piedra.</p><p>Si lo recibiste, SMTP está funcionando.</p>';
    if ($host === '') {
        $body .= '<p><em>Nota: aún no hay host SMTP configurado; el envío usó el correo del servidor.</em></p>';
    }
    $sent = wp_mail($to, 'Prueba SMTP — Casa de Piedra', $body, array('Content-Type: text/html; charset=UTF-8'));
    if ($sent) {
        wp_send_json_success('Correo de prueba enviado a ' . $to . '. Revisa bandeja de entrada y spam.');
    }
    $err = get_transient('casa_last_mail_error');
    wp_send_json_error($err ? $err : 'El envío falló. Verifica host, puerto (587 TLS o 465 SSL), usuario y contraseña.');
});
