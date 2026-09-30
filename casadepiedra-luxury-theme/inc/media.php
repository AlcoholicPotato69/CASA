<?php
/**
 * Medios de la plantilla: solo Medios/Panel. Nunca fotos empaquetadas del tema.
 */
if (!defined('ABSPATH')) {
    exit;
}

function casa_is_theme_packaged_url($url) {
    if (!is_string($url) || $url === '') {
        return false;
    }
    return (bool) preg_match('#(/wp-content/themes/|/assets/images/)#i', $url);
}

function casa_is_brand_chrome_url($url) {
    if (!is_string($url) || $url === '') {
        return false;
    }
    return (bool) preg_match('#/(logo-navbar-oficial\.png|escudo-animacion-blanco\.png|favicon-casa\.svg|favicons/favicon-(?:google|\d+)\.png)(\?|$)#i', $url);
}

function casa_is_dead_media_url($url) {
    if (!is_string($url) || $url === '') {
        return false;
    }
    if (preg_match('#Logo_Header#i', $url)) {
        return true;
    }
    if (!function_exists('wp_get_upload_dir')) {
        return false;
    }
    $uploads = wp_get_upload_dir();
    if (empty($uploads['baseurl']) || empty($uploads['basedir'])) {
        return false;
    }
    $base = trailingslashit($uploads['baseurl']);
    if (strpos($url, $base) !== 0) {
        return false;
    }
    $rel = ltrim(substr($url, strlen($base)), '/');
    if ($rel === '') {
        return false;
    }
    return !file_exists($uploads['basedir'] . '/' . $rel);
}

function casa_google_favicon_url() {
    return function_exists('casa_theme_asset_url')
        ? casa_theme_asset_url('/assets/images/favicons/favicon-google.png')
        : '';
}

function casa_theme_asset_url($rel) {
    $abs = get_template_directory() . $rel;
    if (!file_exists($abs)) {
        return '';
    }
    return get_template_directory_uri() . $rel . '?v=' . filemtime($abs);
}

function casa_is_incomplete_logo_url($url) {
    return (bool) preg_match('#(Logo_Header|Recurso-5@4x|cropped-Recurso)#i', (string) $url);
}

function casa_official_logo_url() {
    return casa_theme_asset_url('/assets/images/logo-navbar-oficial.png');
}

function casa_official_transition_logo_url() {
    $rel = '/assets/images/escudo-animacion-blanco.png';
    if (file_exists(get_template_directory() . $rel)) {
        return get_template_directory_uri() . $rel;
    }
    return casa_official_logo_url();
}

function casa_usable_media($url) {
    $url = is_string($url) ? trim($url) : '';
    if ($url === '' || casa_is_dead_media_url($url)) {
        return '';
    }
    if (casa_is_brand_chrome_url($url)) {
        return $url;
    }
    if (function_exists('casadepiedra_media_library_url_by_basename')) {
        $from_lib = casadepiedra_media_library_url_by_basename($url);
        if ($from_lib && !casa_is_dead_media_url($from_lib)) {
            return $from_lib;
        }
    }
    if (casa_is_theme_packaged_url($url)) {
        return '';
    }
    return $url;
}

function casa_opt_media($key) {
    return casa_usable_media(get_option($key, ''));
}

function casa_logo_url() {
    $logo = casa_usable_media(get_option('casa_opt_global_logo', ''));
    if ($logo && !casa_is_incomplete_logo_url($logo)) {
        return $logo;
    }
    return casa_official_logo_url();
}

function casa_transition_logo_url() {
    $logo = casa_usable_media(get_option('casa_opt_global_transition_logo', ''));
    return $logo ? $logo : casa_official_transition_logo_url();
}

function casa_sitelink_tree() {
    $links = array(
        array('name' => 'Inicio', 'url' => trailingslashit(home_url('/'))),
        array('name' => 'Venues', 'url' => trailingslashit(home_url('/espacios/'))),
        array('name' => 'Restaurantes', 'url' => trailingslashit(home_url('/restaurantes/'))),
        array('name' => 'Galería', 'url' => trailingslashit(home_url('/galeria/'))),
        array('name' => 'Cotizar', 'url' => trailingslashit(home_url('/contacto/'))),
    );
    if (get_option('casa_opt_status_eventos', '0') === '1') {
        array_splice($links, 4, 0, array(array(
            'name' => 'Eventos',
            'url'  => trailingslashit(home_url('/eventos/')),
        )));
    }
    return $links;
}

function casa_render_sitelink_nav($class = 'casa-sitelinks') {
    echo '<nav class="' . esc_attr($class) . '" aria-label="Secciones principales">';
    echo '<ul>';
    foreach (casa_sitelink_tree() as $item) {
        echo '<li><a href="' . esc_url($item['url']) . '">' . esc_html($item['name']) . '</a></li>';
    }
    echo '</ul></nav>';
}

add_action('init', 'casa_ensure_html_sitemap_page', 40);

function casa_ensure_html_sitemap_page() {
    if (get_page_by_path('mapa-del-sitio')) {
        return;
    }
    $id = wp_insert_post(array(
        'post_title'   => 'Mapa del sitio',
        'post_name'    => 'mapa-del-sitio',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '',
    ));
    if ($id && !is_wp_error($id)) {
        update_post_meta($id, '_wp_page_template', 'page-mapa-del-sitio.php');
    }
}

function casa_gallery_urls_from_ids($ids) {
    $urls = array();
    if (is_string($ids)) {
        $ids = explode(',', $ids);
    }
    foreach ((array) $ids as $id) {
        $id = (int) $id;
        if ($id <= 0) {
            continue;
        }
        $url = wp_get_attachment_image_url($id, 'large');
        if ($url) {
            $urls[] = $url;
        }
    }
    return $urls;
}
