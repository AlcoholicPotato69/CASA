<?php
/**
 * SEO + GEO/AEO para Casa de Piedra León
 * Complejo social: venues de eventos + restaurantes (LocalBusiness / EventVenue).
 */
if (!defined('ABSPATH')) {
    exit;
}

function casa_seo_site_name() {
    $from_panel = get_option('casa_opt_seo_site_name', '');
    if (is_string($from_panel) && trim($from_panel) !== '') {
        return $from_panel;
    }
    return get_option('casa_opt_seo_brand', 'Casa de Piedra León');
}

function casa_seo_logo_url() {
    return function_exists('casa_logo_url') ? casa_logo_url() : casa_usable_media(get_option('casa_opt_global_logo', ''));
}

function casa_seo_default_image() {
    $og = casa_usable_media(get_option('casa_opt_seo_og_image', ''));
    if ($og) {
        return $og;
    }
    $img = casa_usable_media(get_option('casa_opt_home_hero_img', ''));
    if ($img) {
        return $img;
    }
    return casa_seo_logo_url();
}

function casa_seo_phone() {
    $raw = function_exists('casa_get_display_phone') ? casa_get_display_phone() : get_option('casa_opt_global_phone', '477 289 25 21');
    $digits = preg_replace('/[^0-9+]/', '', $raw);
    if (strpos($digits, '+') !== 0 && strpos($digits, '52') !== 0) {
        return '+52' . $digits;
    }
    return $digits;
}

function casa_seo_opt_or($key, $default) {
    $value = get_option('casa_opt_seo_' . $key, $default);
    return (is_string($value) && trim($value) !== '') ? $value : $default;
}

function casa_seo_address() {
    $street = casa_seo_opt_or('street', 'Av. Cerro Gordo 270');
    $hood   = casa_seo_opt_or('neighborhood', 'Casa de Piedra');
    return array(
        '@type' => 'PostalAddress',
        'streetAddress' => trim($street . ($hood !== '' ? ', ' . $hood : '')),
        'addressLocality' => casa_seo_opt_or('locality', 'León de los Aldama'),
        'addressRegion' => casa_seo_opt_or('region', 'Guanajuato'),
        'postalCode' => casa_seo_opt_or('postal', '37120'),
        'addressCountry' => 'MX',
    );
}

function casa_seo_hours() {
    return array(
        array(
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
            'opens' => casa_seo_opt_or('open_week', '09:00'),
            'closes' => casa_seo_opt_or('close_week', '18:00'),
        ),
        array(
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => 'Saturday',
            'opens' => casa_seo_opt_or('open_sat', '09:00'),
            'closes' => casa_seo_opt_or('close_sat', '14:00'),
        ),
    );
}

function casa_seo_title_defaults() {
    return array(
        'home' => array(
            'title' => 'Jardín de eventos y salones en León | Casa de Piedra',
            'desc'  => 'Ex hacienda 1845 en Cerro Gordo. Jardín, salones y restaurantes para bodas, XV años y eventos empresariales. Cotiza tu evento.',
        ),
        'espacios' => array(
            'title' => 'Venues y salones para eventos en León | Casa de Piedra',
            'desc'  => 'Jardín Principal, Salón Principal, Terraza Mezquite y Salón Pavorreales. Recinto para bodas, fiestas y convenciones en León, Gto.',
        ),
        'restaurantes' => array(
            'title' => 'Restaurantes en Casa de Piedra León',
            'desc'  => 'Argentilia, Lucio, Manolo, Sato y Valentina: alta cocina en el complejo Casa de Piedra, Cerro Gordo, León.',
        ),
        'galeria' => array(
            'title' => 'Galería de Casa de Piedra León',
            'desc'  => 'Fotos de jardín, salones, terrazas y restaurantes de Casa de Piedra, ex hacienda 1845 en León, Guanajuato.',
        ),
        'contacto' => array(
            'title' => 'Cotizar evento en Casa de Piedra León',
            'desc'  => 'Cotiza bodas, XV años y eventos empresariales. Av. Cerro Gordo 270, León. Tel. 477 289 25 21.',
        ),
        'nosotros' => array(
            'title' => 'Quiénes somos | Casa de Piedra León',
            'desc'  => 'Ex hacienda de 1845 en Cerro Gordo. Complejo social y gastronómico de Grupo AlCon en León, Guanajuato.',
        ),
        'eventos' => array(
            'title' => 'Eventos en Casa de Piedra León',
            'desc'  => 'Calendario de eventos en el jardín y salones de Casa de Piedra, León, Guanajuato.',
        ),
    );
}

function casa_seo_visit_defaults() {
    return array(
        'kicker' => 'Casa de Piedra León · Guanajuato',
        'title'  => 'Por qué elegir Casa de Piedra',
        'lead'   => 'Ex hacienda de 1845 en Cerro Gordo: jardín, salones y restaurantes para bodas, XV años y eventos empresariales, a minutos de Plaza Mayor.',
        'highlights' => array(
            array('value' => '1845', 'label' => 'Ex hacienda'),
            array('value' => '4', 'label' => 'Venues y salones'),
            array('value' => '5', 'label' => 'Restaurantes'),
            array('value' => 'Valet', 'label' => 'Estacionamiento'),
        ),
        'items' => array(
            array(
                'name' => 'Jardín y salones para eventos',
                'text' => 'Bodas, XV años, graduaciones y fiestas en jardín, salón principal, terraza y salón privado.',
                'url'  => '/espacios/',
            ),
            array(
                'name' => 'Eventos empresariales',
                'text' => 'Convenciones, congresos y reuniones de empresa en Cerro Gordo, zona norte de León.',
                'url'  => '/espacios/',
            ),
            array(
                'name' => 'Restaurantes del complejo',
                'text' => 'Argentilia, Lucio, Manolo, Sato y Valentina: alta cocina dentro de Casa de Piedra.',
                'url'  => '/restaurantes/',
            ),
            array(
                'name' => 'Galería del recinto',
                'text' => 'Ve el jardín, los salones y las terrazas de la ex hacienda antes de cotizar.',
                'url'  => '/galeria/',
            ),
            array(
                'name' => 'Ubicación Cerro Gordo',
                'text' => 'Av. Cerro Gordo 270, cerca de Plaza Mayor, con estacionamiento y valet.',
                'url'  => '/contacto/',
            ),
            array(
                'name' => 'Cotizar tu evento',
                'text' => 'Habla con el equipo al 477 289 25 21 o escribe a eventos@casadepiedraleon.mx.',
                'url'  => '/contacto/',
            ),
        ),
    );
}

function casa_seo_visit() {
    $defaults = casa_seo_visit_defaults();
    $saved = get_option('casa_opt_seo_visit', array());
    if (!is_array($saved) || $saved === array()) {
        return $defaults;
    }
    $out = array_merge($defaults, $saved);
    if (empty($out['highlights']) || !is_array($out['highlights'])) {
        $out['highlights'] = $defaults['highlights'];
    }
    if (empty($out['items']) || !is_array($out['items'])) {
        $out['items'] = $defaults['items'];
    }
    return $out;
}

function casa_seo_visit_href($url) {
    $url = trim((string) $url);
    if ($url === '') {
        return home_url('/espacios/');
    }
    if (preg_match('#^https?://#i', $url) || strpos($url, '#') === 0) {
        return $url;
    }
    return home_url($url);
}

function casa_render_visit_section() {
    if (!is_front_page()) {
        return;
    }
    $visit = casa_seo_visit();
    echo '<section id="casa-seo-visit" class="casa-seo-visit" aria-label="' . esc_attr($visit['title']) . '">';
    echo '<div class="casa-seo-visit__inner">';
    echo '<p class="casa-seo-visit__kicker">' . esc_html($visit['kicker']) . '</p>';
    echo '<h2 class="casa-seo-visit__title">' . esc_html($visit['title']) . '</h2>';
    echo '<p class="casa-seo-visit__lead">' . esc_html($visit['lead']) . '</p>';
    if (!empty($visit['highlights'])) {
        echo '<ul class="casa-seo-visit__stats">';
        foreach ($visit['highlights'] as $row) {
            if (empty($row['value'])) {
                continue;
            }
            echo '<li><strong>' . esc_html($row['value']) . '</strong><span>' . esc_html($row['label'] ?? '') . '</span></li>';
        }
        echo '</ul>';
    }
    echo '<ol class="casa-seo-visit__reasons">';
    $i = 1;
    foreach ($visit['items'] as $item) {
        if (empty($item['name'])) {
            continue;
        }
        echo '<li><a href="' . esc_url(casa_seo_visit_href($item['url'] ?? '')) . '">';
        echo '<span class="casa-seo-visit__num">' . esc_html(str_pad((string) $i, 2, '0', STR_PAD_LEFT)) . '</span>';
        echo '<div><strong>' . esc_html($item['name']) . '</strong><p>' . esc_html($item['text'] ?? '') . '</p></div>';
        echo '</a></li>';
        $i++;
    }
    echo '</ol>';
    echo '<p class="casa-seo-visit__links">';
    echo '<a href="' . esc_url(home_url('/espacios/')) . '">Espacios</a>';
    echo '<a href="' . esc_url(home_url('/restaurantes/')) . '">Restaurantes</a>';
    echo '<a href="' . esc_url(home_url('/galeria/')) . '">Galería</a>';
    echo '<a href="' . esc_url(home_url('/contacto/')) . '">Cotizar</a>';
    echo '</p></div></section>';
}

function casa_seo_geo() {
    $lat = get_option('casa_opt_seo_lat', '');
    $lng = get_option('casa_opt_seo_lng', '');
    return array(
        '@type' => 'GeoCoordinates',
        'latitude' => is_string($lat) && $lat !== '' ? (float) $lat : 21.15854,
        'longitude' => is_string($lng) && $lng !== '' ? (float) $lng : -101.69926,
    );
}

function casa_seo_same_as() {
    // Strip UTM / tracking params from social URLs so sameAs uses canonical profile URLs.
    $strip_tracking = function ($url) {
        if (!$url) {
            return '';
        }
        $parsed = parse_url($url);
        if (!$parsed || empty($parsed['host'])) {
            return $url;
        }
        // Remove known tracking query params; preserve intentional path-only links.
        $blacklist = array('utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'igsh', '_r', '_t', 'entry');
        $query = array();
        if (!empty($parsed['query'])) {
            parse_str($parsed['query'], $query);
            foreach ($blacklist as $key) {
                unset($query[$key]);
            }
        }
        $canonical = $parsed['scheme'] . '://' . $parsed['host'] . ($parsed['path'] ?? '');
        if ($query) {
            $canonical .= '?' . http_build_query($query);
        }
        return rtrim($canonical, '/');
    };

    $links = array_filter(array(
        get_option('casa_opt_global_facebook', 'https://www.facebook.com/casadepiedraoficialleon'),
        $strip_tracking(get_option('casa_opt_global_instagram', 'https://www.instagram.com/casadepiedraoficial')),
        $strip_tracking(get_option('casa_opt_global_tiktok', '')),
        'https://www.google.com/maps/place/?q=place_id:ChIJ49DkolO_K4QRo7D2srdHH_4',
    ));
    return array_values($links);
}

function casa_seo_event_topics() {
    return array(
        'Bodas en León',
        'XV años León',
        'Bautizos y primeras comuniones',
        'Confirmaciones',
        'Bar Mitzvá y Bat Mitzvá',
        'Cumpleaños y aniversarios',
        'Graduaciones',
        'Baby shower y revelación de género',
        'Reuniones familiares',
        'Cenas de gala',
        'Eventos empresariales León',
        'Convenciones y congresos',
        'Jardín de eventos León',
        'Salón de fiestas zona norte León',
        'Hacienda para bodas Guanajuato',
        'Restaurantes de alta cocina León',
        'Eventos cerca de Plaza Mayor',
        'Estacionamiento y valet para eventos',
    );
}

function casa_seo_primary_keywords() {
    $from_panel = get_option('casa_opt_seo_keywords', '');
    if (is_string($from_panel) && trim($from_panel) !== '') {
        return $from_panel;
    }
    return 'jardín de eventos León, salón de fiestas León, hacienda para bodas León Guanajuato, bodas zona norte León, XV años León, bautizos León, cumpleaños León, bar mitzvá León, eventos empresariales León, convenciones León, salón cerca de Plaza Mayor, estacionamiento eventos León, restaurantes Casa de Piedra, salón de eventos Cerro Gordo, complejo gastronómico León';
}

function casa_seo_default_description() {
    $schema = get_option('casa_opt_seo_schema_desc', '');
    if (is_string($schema) && trim($schema) !== '') {
        return $schema;
    }
    $home = get_option('casa_opt_seo_home_desc', '');
    if (is_string($home) && trim($home) !== '') {
        return $home;
    }
    return get_option(
        'casa_opt_seo_home_desc',
        'Ex hacienda de 1845 en Cerro Gordo, León: jardín y salones para bodas, XV, empresariales y restaurantes. Estacionamiento y valet. Cotiza tu evento.'
    );
}

function casa_seo_page_context() {
    $ctx = casa_seo_page_context_base();
    return function_exists('casa_seo_apply_panel') ? casa_seo_apply_panel($ctx) : $ctx;
}

function casa_seo_page_context_base() {
    global $post;
    $site = casa_seo_site_name();
    $ctx = array(
        'title' => 'Jardín de eventos y salones en León | Casa de Piedra',
        'desc' => casa_seo_default_description(),
        'canonical' => trailingslashit(home_url('/')),
        'image' => casa_seo_default_image(),
        'type' => 'website',
        'robots' => 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1',
    );

    if (is_404()) {
        $ctx['title'] = 'Página no encontrada | ' . $site;
        $ctx['desc'] = 'Esta ruta no existe. Consulta los espacios, restaurantes o cotiza un evento en Casa de Piedra León.';
        $ctx['canonical'] = home_url(add_query_arg(array(), $GLOBALS['wp']->request ? user_trailingslashit($GLOBALS['wp']->request) : '/'));
        $ctx['robots'] = 'noindex, follow';
        return $ctx;
    }

    if (is_front_page() || is_home()) {
        return $ctx;
    }

    if (is_singular('espacios')) {
        $title = get_the_title();
        $excerpt = $post ? wp_strip_all_tags(get_the_excerpt($post)) : '';
        $ctx['title'] = $title . ' en Casa de Piedra | Eventos en León';
        $ctx['desc'] = $excerpt ?: ($title . ' en la ex hacienda Casa de Piedra, Cerro Gordo, León. Consulta capacidad y cotiza bodas, XV o eventos empresariales.');
        $ctx['canonical'] = get_permalink($post);
        $ctx['type'] = 'website';
        if (function_exists('casadepiedra_resolve_espacio_hero')) {
            $hero = casadepiedra_resolve_espacio_hero($post->ID);
            if ($hero) {
                $ctx['image'] = $hero;
            }
        } elseif (has_post_thumbnail($post)) {
            $ctx['image'] = get_the_post_thumbnail_url($post, 'large');
        }
        return $ctx;
    }

    if (is_singular('restaurantes')) {
        $title = get_the_title();
        $cocina = get_post_meta($post->ID, '_restaurante_cocina', true);
        $ctx['title'] = $title . ' | Restaurante León | Casa de Piedra';
        $ctx['desc'] = 'Reserva en ' . $title . ($cocina ? (', ' . $cocina) : '') . ', en Casa de Piedra, zona norte de León cerca de Plaza Mayor.';
        $ctx['canonical'] = get_permalink($post);
        $ctx['type'] = 'website';
        if (function_exists('casadepiedra_resolve_restaurante_hero')) {
            $hero = casadepiedra_resolve_restaurante_hero($post->ID);
            if ($hero) {
                $ctx['image'] = $hero;
            }
        } elseif (has_post_thumbnail($post)) {
            $ctx['image'] = get_the_post_thumbnail_url($post, 'large');
        }
        return $ctx;
    }

    if (is_singular('eventos')) {
        $ctx['title'] = get_the_title() . ' | Eventos en ' . $site;
        $ctx['desc'] = wp_strip_all_tags(get_the_excerpt($post) ?: get_the_title()) . ' en Casa de Piedra León.';
        $ctx['canonical'] = get_permalink($post);
        $ctx['type'] = 'event';
        return $ctx;
    }

    if (is_post_type_archive('espacios') || is_page('espacios') || is_page('venues')) {
        $ctx['title'] = 'Jardín y salones de eventos en León | Casa de Piedra';
        $ctx['desc'] = 'Cuatro espacios en Cerro Gordo, León: jardín, salón principal, terraza y salón privado para bodas, XV y convenciones. Estacionamiento y valet.';
        $ctx['canonical'] = trailingslashit(home_url('/espacios/'));
        return $ctx;
    }

    if (is_post_type_archive('restaurantes') || is_page('restaurantes')) {
        $ctx['title'] = 'Restaurantes en León | Casa de Piedra';
        $ctx['desc'] = 'Argentilia, Lucio, Manolo, Sato y Valentina: alta cocina en Cerro Gordo, zona norte de León, junto a jardín de eventos y estacionamiento.';
        $ctx['canonical'] = trailingslashit(home_url('/restaurantes/'));
        return $ctx;
    }

    if (is_post_type_archive('eventos') || is_page('eventos')) {
        $ctx['title'] = 'Eventos y festejos en León | Casa de Piedra';
        $ctx['desc'] = 'Celebraciones, fiestas y eventos en Casa de Piedra León: bodas, empresariales, sociales y gastronómicos en Cerro Gordo.';
        $ctx['canonical'] = trailingslashit(home_url('/eventos/'));
        if (get_option('casa_opt_status_eventos', '0') !== '1') {
            $ctx['robots'] = 'noindex, follow';
        }
        return $ctx;
    }

    if (is_page('galeria') || is_page_template('page-galeria.php')) {
        $ctx['title'] = 'Galería de bodas y eventos León | Casa de Piedra';
        $ctx['desc'] = 'Fotos de bodas, XV, bautizos, cumpleaños, bar mitzvá, convenciones y eventos empresariales en Casa de Piedra León.';
        $ctx['canonical'] = trailingslashit(home_url('/galeria/'));
        return $ctx;
    }

    if (is_page('contacto') || is_page_template('page-contacto.php')) {
        $ctx['title'] = 'Cotizar evento en León | Casa de Piedra';
        $ctx['desc'] = 'Cotiza bodas, bautizos, XV, cumpleaños o eventos empresariales en Cerro Gordo, zona norte de León cerca de Plaza Mayor. Tel. ' . (function_exists('casa_get_display_phone') ? casa_get_display_phone() : '477 289 25 21') . '.';
        $ctx['canonical'] = trailingslashit(home_url('/contacto/'));
        return $ctx;
    }

    if (is_page('quienes-somos') || is_page_template('page-quienes-somos.php')) {
        $ctx['title'] = 'Ex hacienda de 1845 en León | Casa de Piedra';
        $ctx['desc'] = 'Historia de Casa de Piedra: ex hacienda de 1845 en Cerro Gordo, León, hoy jardín de eventos, salones y restaurantes. Recorrido virtual 360°.';
        $ctx['canonical'] = trailingslashit(home_url('/quienes-somos/'));
        return $ctx;
    }

    if (is_page('mapa-del-sitio') || is_page_template('page-mapa-del-sitio.php')) {
        $ctx['title'] = 'Mapa del sitio | ' . $site;
        $ctx['desc'] = 'Árbol de páginas de Casa de Piedra León: venues, restaurantes, galería y cotizar evento.';
        $ctx['canonical'] = trailingslashit(home_url('/mapa-del-sitio/'));
        return $ctx;
    }

    if (is_page() && $post) {
        $ctx['title'] = get_the_title() . ' | ' . $site;
        $ctx['canonical'] = get_permalink($post);
    }

    return $ctx;
}

function casa_seo_nav_nodes($site_url) {
    $nodes = function_exists('casa_sitelink_tree') ? casa_sitelink_tree() : array(
        array('name' => 'Venues', 'url' => trailingslashit(home_url('/espacios/'))),
        array('name' => 'Restaurantes', 'url' => trailingslashit(home_url('/restaurantes/'))),
        array('name' => 'Galería', 'url' => trailingslashit(home_url('/galeria/'))),
        array('name' => 'Cotizar', 'url' => trailingslashit(home_url('/contacto/'))),
    );
    $out = array();
    $i = 1;
    foreach ($nodes as $node) {
        // SiteNavigationElement must be nested inside a ListItem for valid schema.
        $out[] = array(
            '@type' => 'ListItem',
            'position' => $i,
            'item' => array(
                '@type' => 'SiteNavigationElement',
                '@id' => $site_url . '#nav-' . $i,
                'name' => $node['name'],
                'url' => $node['url'],
            ),
        );
        $i++;
    }
    return $out;
}

function casa_seo_place_list($post_type, $resolver = null) {
    $items = array();
    $is_restaurant = ($post_type === 'restaurantes');
    $posts = get_posts(array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
    ));
    $pos = 1;
    foreach ($posts as $p) {
        $permalink = get_permalink($p);
        $inner = array(
            '@type' => $is_restaurant ? 'Restaurant' : array('EventVenue', 'Place'),
            '@id'   => $permalink . ($is_restaurant ? '#restaurant' : '#place'),
            'name'  => get_the_title($p),
            'url'   => $permalink,
        );
        if ($resolver && function_exists($resolver)) {
            $img = call_user_func($resolver, $p->ID);
            if ($img) {
                $inner['image'] = $img;
            }
        }
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $pos,
            'item'     => $inner,
        );
        $pos++;
    }
    return $items;
}

function casa_seo_restaurant_entities($site_url) {
    $out = array();
    $posts = get_posts(array(
        'post_type' => 'restaurantes',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'menu_order title',
        'order' => 'ASC',
    ));
    foreach ($posts as $p) {
        $cuisine = get_post_meta($p->ID, '_restaurante_cocina', true);
        $menu = get_post_meta($p->ID, '_restaurante_menu', true);
        $tel = get_post_meta($p->ID, '_restaurante_telefono', true);
        $img = function_exists('casadepiedra_resolve_restaurante_card_img') ? casadepiedra_resolve_restaurante_card_img($p->ID) : '';
        $entity = array(
            '@type' => 'Restaurant',
            '@id' => get_permalink($p) . '#restaurant',
            'name' => get_the_title($p),
            'url' => get_permalink($p),
            'description' => wp_strip_all_tags($p->post_excerpt ?: wp_trim_words($p->post_content, 28)),
            'parentOrganization' => array('@id' => $site_url . '#organization'),
            'containedInPlace' => array('@id' => $site_url . '#venue'),
            'address' => casa_seo_address(),
            'geo' => casa_seo_geo(),
            'telephone' => $tel ?: casa_seo_phone(),
            'servesCuisine' => $cuisine ?: 'Alta cocina',
            'acceptsReservations' => 'True',
            'priceRange' => '$$$',
        );
        if ($img) {
            $entity['image'] = $img;
        }
        if ($menu) {
            $entity['hasMenu'] = $menu;
        }
        $out[] = $entity;
    }
    return $out;
}

function casa_seo_faq_capacity_clause() {
    if (!function_exists('casadepiedra_get_post_by_title') || !function_exists('casa_espacio_capacidad')) {
        return '';
    }
    $bits = array();
    $jardin = casadepiedra_get_post_by_title('Jardín Principal', 'espacios');
    if ($jardin) {
        $cap = casa_espacio_capacidad($jardin->ID);
        if ($cap !== '') {
            $bits[] = 'El Jardín Principal admite hasta ' . $cap . ' personas';
        }
    }
    $salon = casadepiedra_get_post_by_title('Salón Principal', 'espacios');
    if ($salon) {
        $cap = casa_espacio_capacidad($salon->ID);
        if ($cap !== '') {
            $bits[] = 'el Salón Principal hasta ' . $cap;
        }
    }
    if (empty($bits)) {
        return '';
    }
    return ' ' . implode(' y ', $bits) . '.';
}

function casa_seo_faq_items() {
    return array(
        array(
            'name' => '¿Qué es Casa de Piedra León?',
            'text' => 'Casa de Piedra es un complejo social y gastronómico en Cerro Gordo, zona norte de León, Guanajuato: jardín de eventos, salones y terrazas para todo tipo de festejos, más restaurantes de alta cocina.',
        ),
        array(
            'name' => '¿Dónde está Casa de Piedra y cómo llego?',
            'text' => 'Av. Cerro Gordo 270, Casa de Piedra, 37120 León de los Aldama, Gto. Está en zona norte, cerca de Plaza Mayor. Hay estacionamiento y valet para invitados.',
        ),
        array(
            'name' => '¿Qué tipo de eventos y festejos se pueden realizar?',
            'text' => 'Bodas, XV años, bautizos, primeras comuniones, confirmaciones, bar mitzvá y bat mitzvá, cumpleaños, aniversarios, graduaciones, baby showers, reuniones familiares, cenas de gala, eventos empresariales, convenciones y congresos.' . casa_seo_faq_capacity_clause(),
        ),
        array(
            'name' => '¿Hay estacionamiento?',
            'text' => 'Sí. El recinto cuenta con estacionamiento y valet para eventos, reuniones y visitas a restaurantes.',
        ),
        array(
            'name' => '¿Qué restaurantes hay en Casa de Piedra?',
            'text' => 'El complejo reúne restaurantes de alta cocina como Argentilia, Lucio Ítalo-Argentino, Manolo, Sato Cocina Nikkei y Valentina, ideales para comidas, cenas y celebraciones íntimas.',
        ),
        array(
            'name' => '¿Cómo cotizo un evento o reservo mesa?',
            'text' => 'Solicita cotización en casadepiedraleon.mx o llama al ' . (function_exists('casa_get_display_phone') ? casa_get_display_phone() : '477 289 25 21') . '. También puedes escribir a eventos@casadepiedraleon.mx.',
        ),
        array(
            'name' => '¿Casa de Piedra ofrece hospedaje?',
            'text' => 'No. Casa de Piedra no es hotel ni ofrece habitaciones. Es un recinto para eventos (jardín, salones y terrazas) y un complejo de restaurantes en Cerro Gordo, León, Guanajuato.',
        ),
    );
}

function casa_seo_faq() {
    $entities = array();
    foreach (casa_seo_faq_items() as $item) {
        $entities[] = array(
            '@type' => 'Question',
            'name' => $item['name'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $item['text'],
            ),
        );
    }
    return array(
        '@type' => 'FAQPage',
        '@id' => trailingslashit(home_url('/')) . '#faq',
        'mainEntity' => $entities,
    );
}

function casa_render_visible_faq() {
    $items = casa_seo_faq_items();
    echo '<section id="faq" class="casa-faq-section" style="padding: 4.5rem 0 5rem; background: #080808; border-top: 1px solid rgba(193,98,30,0.18);">';
    echo '<div class="container" style="max-width: 920px;">';
    echo '<span class="text-script" style="color: var(--color-accent); display:block; text-align:center; margin-bottom:0.35rem;">Dudas frecuentes</span>';
    echo '<h2 class="text-hero" style="color:#fff; text-align:center; font-size: clamp(1.8rem, 3.5vw, 2.6rem); margin: 0 0 1.8rem;">Preguntas sobre eventos en León</h2>';
    echo '<div style="display:flex; flex-direction:column; gap:0.7rem;">';
    foreach ($items as $item) {
        echo '<details style="background:#111; border:1px solid rgba(193,98,30,0.28); border-radius:12px; padding:0.85rem 1.1rem;">';
        echo '<summary style="cursor:pointer; color:#fff; font-weight:600; font-size:1rem; list-style:none;">' . esc_html($item['name']) . '</summary>';
        echo '<p style="color:rgba(255,255,255,0.78); margin:0.75rem 0 0.2rem; line-height:1.65; font-size:0.95rem;">' . esc_html($item['text']) . '</p>';
        echo '</details>';
    }
    echo '</div></div></section>';
}

function casa_render_advanced_seo_and_sitelinks() {
    $ctx = casa_seo_page_context();
    $site_name = casa_seo_site_name();
    $site_url = trailingslashit(home_url('/'));
    $logo = casa_seo_logo_url();
    $keywords = casa_seo_primary_keywords();

    $breadcrumbs = array(
        array('@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => $site_url),
    );
    if (is_singular('espacios')) {
        $breadcrumbs[] = array('@type' => 'ListItem', 'position' => 2, 'name' => 'Espacios y Salones', 'item' => trailingslashit(home_url('/espacios/')));
        $breadcrumbs[] = array('@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => $ctx['canonical']);
    } elseif (is_singular('restaurantes')) {
        $breadcrumbs[] = array('@type' => 'ListItem', 'position' => 2, 'name' => 'Restaurantes', 'item' => trailingslashit(home_url('/restaurantes/')));
        $breadcrumbs[] = array('@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => $ctx['canonical']);
    } elseif (is_singular('eventos')) {
        $breadcrumbs[] = array('@type' => 'ListItem', 'position' => 2, 'name' => 'Eventos', 'item' => trailingslashit(home_url('/eventos/')));
        $breadcrumbs[] = array('@type' => 'ListItem', 'position' => 3, 'name' => get_the_title(), 'item' => $ctx['canonical']);
    } elseif (!is_front_page()) {
        $breadcrumbs[] = array('@type' => 'ListItem', 'position' => 2, 'name' => wp_get_document_title(), 'item' => $ctx['canonical']);
    }

    $graph = array(
        array(
            '@type' => 'WebSite',
            '@id' => $site_url . '#website',
            'url' => $site_url,
            'name' => $site_name,
            'alternateName' => array('Casa de Piedra', 'Ex Hacienda Casa de Piedra', 'Casa de Piedra León Guanajuato'),
            'inLanguage' => 'es-MX',
            'description' => casa_seo_default_description(),
            'publisher' => array('@id' => $site_url . '#organization'),
        ),
        array(
            '@type' => array('Organization', 'LocalBusiness', 'EntertainmentBusiness'),
            '@id' => $site_url . '#organization',
            'name' => $site_name,
            'legalName' => 'Casa de Piedra León',
            'url' => $site_url,
            'logo' => array('@type' => 'ImageObject', 'url' => $logo),
            'image' => $ctx['image'],
            'description' => casa_seo_default_description(),
            'foundingDate' => '1845',
            'address' => casa_seo_address(),
            'geo' => casa_seo_geo(),
            'telephone' => casa_seo_phone(),
            'email' => get_option('casa_opt_global_email', 'eventos@casadepiedraleon.mx'),
            'priceRange' => '$$$',
            'currenciesAccepted' => 'MXN',
            'paymentAccepted' => 'Cash, Credit Card',
            'hasMap' => 'https://www.google.com/maps/place/?q=place_id:ChIJ49DkolO_K4QRo7D2srdHH_4',
            'areaServed' => array(
                array('@type' => 'City', 'name' => 'León', 'sameAs' => 'https://www.wikidata.org/wiki/Q200726'),
                array('@type' => 'AdministrativeArea', 'name' => 'Guanajuato'),
                array('@type' => 'Country', 'name' => 'México'),
                array('@type' => 'Place', 'name' => 'Zona norte de León'),
                array('@type' => 'Place', 'name' => 'Plaza Mayor, León'),
                array('@type' => 'Place', 'name' => 'Cerro Gordo, León'),
            ),
            'knowsAbout' => casa_seo_event_topics(),
            'amenityFeature' => array_merge(
                array(
                    array('@type' => 'LocationFeatureSpecification', 'name' => 'Jardín para eventos', 'value' => true),
                    array('@type' => 'LocationFeatureSpecification', 'name' => 'Salones techados', 'value' => true),
                    array('@type' => 'LocationFeatureSpecification', 'name' => 'Restaurantes', 'value' => true),
                    array('@type' => 'LocationFeatureSpecification', 'name' => 'Estacionamiento y valet', 'value' => true),
                ),
                array_map(function ($item) {
                    return array(
                        '@type' => 'LocationFeatureSpecification',
                        'name'  => $item['name'],
                        'value' => true,
                    );
                }, array_filter(casa_seo_visit()['items'], function ($item) {
                    return !empty($item['name']);
                }))
            ),
            'openingHoursSpecification' => casa_seo_hours(),
            'contactPoint' => array(
                array(
                    '@type' => 'ContactPoint',
                    'telephone' => casa_seo_phone(),
                    'contactType' => 'sales',
                    'areaServed' => 'MX',
                    'availableLanguage' => array('es', 'en'),
                    'email' => get_option('casa_opt_global_email', 'eventos@casadepiedraleon.mx'),
                ),
            ),
            'sameAs' => casa_seo_same_as(),
            'hasOfferCatalog' => array(
                '@type' => 'OfferCatalog',
                'name' => 'Venues, eventos y restaurantes',
                'itemListElement' => array(
                    array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Renta de jardín y salones para bodas, XV años y fiestas')),
                    array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Bautizos, comuniones, bar mitzvá, cumpleaños y reuniones familiares')),
                    array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Eventos empresariales, convenciones y congresos')),
                    array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Experiencia gastronómica en restaurantes del complejo')),
                ),
            ),
        ),
        array(
            '@type' => array('EventVenue', 'Place'),
            '@id' => $site_url . '#venue',
            'name' => 'Complejo Casa de Piedra León',
            'description' => 'Complejo social en Cerro Gordo, zona norte de León cerca de Plaza Mayor: venues para eventos y fiestas más polo gastronómico, con estacionamiento.',
            'address' => casa_seo_address(),
            'geo' => casa_seo_geo(),
            // Link ALL child places: restaurants + venue spaces.
            'containsPlace' => array_merge(
                array_map(function ($r) {
                    return array('@id' => $r['@id']);
                }, casa_seo_restaurant_entities($site_url)),
                array_map(function ($p) {
                    return array('@id' => get_permalink($p) . '#place');
                }, get_posts(array(
                    'post_type'      => 'espacios',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'orderby'        => 'menu_order title',
                    'order'          => 'ASC',
                )))
            ),
        ),
        array(
            '@type' => 'ItemList',
            '@id' => $site_url . '#venues-list',
            'name' => 'Venues y salones de Casa de Piedra',
            'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
            'numberOfItems' => count(casa_seo_place_list('espacios')),
            'itemListElement' => casa_seo_place_list('espacios', 'casadepiedra_resolve_espacio_card_img'),
        ),
        array(
            '@type' => 'ItemList',
            '@id' => $site_url . '#restaurants-list',
            'name' => 'Restaurantes de Casa de Piedra',
            'itemListOrder' => 'https://schema.org/ItemListOrderAscending',
            'numberOfItems' => count(casa_seo_place_list('restaurantes')),
            'itemListElement' => casa_seo_place_list('restaurantes', 'casadepiedra_resolve_restaurante_card_img'),
        ),
        array(
            '@type' => 'ItemList',
            '@id' => $site_url . '#sitelinks',
            'name' => 'Secciones principales',
            'itemListElement' => casa_seo_nav_nodes($site_url),
        ),
        array(
            '@type' => 'BreadcrumbList',
            '@id' => $ctx['canonical'] . '#breadcrumb',
            'itemListElement' => $breadcrumbs,
        ),
        array(
            '@type' => 'WebPage',
            '@id' => $ctx['canonical'] . '#webpage',
            'url' => $ctx['canonical'],
            'name' => $ctx['title'],
            'description' => $ctx['desc'],
            'keywords' => casa_seo_primary_keywords(),
            'isPartOf' => array('@id' => $site_url . '#website'),
            'about' => array('@id' => $site_url . '#organization'),
            'inLanguage' => 'es-MX',
            'primaryImageOfPage' => array('@type' => 'ImageObject', 'url' => $ctx['image']),
            'speakable' => array(
                '@type' => 'SpeakableSpecification',
                'cssSelector' => array('h1', '.text-hero', '.text-body-lg'),
            ),
        ),
    );

    foreach (casa_seo_restaurant_entities($site_url) as $rest) {
        $graph[] = $rest;
    }

    if (is_front_page() || is_home()) {
        // Add a typed EventVenue/Place entity for each published espacio so the homepage
        // graph has full @id anchors that other entities can reference via containedInPlace.
        $espacio_posts = get_posts(array(
            'post_type'      => 'espacios',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
        ));
        foreach ($espacio_posts as $ep) {
            $ep_url = get_permalink($ep);
            $ep_cap = get_post_meta($ep->ID, '_espacio_capacidad', true);
            $ep_img = function_exists('casadepiedra_resolve_espacio_card_img') ? casadepiedra_resolve_espacio_card_img($ep->ID) : '';
            $ep_entity = array(
                '@type'            => array('EventVenue', 'Place'),
                '@id'              => $ep_url . '#place',
                'name'             => get_the_title($ep),
                'url'              => $ep_url,
                'description'      => wp_strip_all_tags($ep->post_excerpt ?: wp_trim_words($ep->post_content, 28)),
                'address'          => casa_seo_address(),
                'geo'              => casa_seo_geo(),
                'containedInPlace' => array('@id' => $site_url . '#venue'),
            );
            if ($ep_cap) {
                $ep_entity['maximumAttendeeCapacity'] = (int) preg_replace('/[^0-9]/', '', $ep_cap);
            }
            if ($ep_img) {
                $ep_entity['image'] = $ep_img;
            }
            $graph[] = $ep_entity;
        }

        // FAQPage: Google retired FAQ rich results (May 7, 2026). Content is factual so left
        // in place for possible AI/GEO indexing benefit, but provides no Google SERP feature.
        $graph[] = casa_seo_faq();
    }

    if (is_singular('espacios')) {
        global $post;
        $cap = get_post_meta($post->ID, '_espacio_capacidad', true);
        $graph[] = array(
            '@type' => array('EventVenue', 'Place'),
            '@id' => $ctx['canonical'] . '#place',
            'name' => get_the_title($post),
            'url' => $ctx['canonical'],
            'description' => $ctx['desc'],
            'image' => $ctx['image'],
            'address' => casa_seo_address(),
            'geo' => casa_seo_geo(),
            'containedInPlace' => array('@id' => $site_url . '#venue'),
        );
        if ($cap) {
            $graph[array_key_last($graph)]['maximumAttendeeCapacity'] = (int) preg_replace('/[^0-9]/', '', $cap);
        }
    }

    if (is_singular('restaurantes')) {
        global $post;
        $graph[] = array(
            '@type' => 'Restaurant',
            '@id' => $ctx['canonical'] . '#page-restaurant',
            'name' => get_the_title($post),
            'url' => $ctx['canonical'],
            'description' => $ctx['desc'],
            'image' => $ctx['image'],
            'address' => casa_seo_address(),
            'geo' => casa_seo_geo(),
            'telephone' => get_post_meta($post->ID, '_restaurante_telefono', true) ?: casa_seo_phone(),
            'servesCuisine' => get_post_meta($post->ID, '_restaurante_cocina', true) ?: 'Alta cocina',
            'parentOrganization' => array('@id' => $site_url . '#organization'),
        );
    }
    ?>
    <link rel="canonical" href="<?php echo esc_url($ctx['canonical']); ?>" />
    <link rel="alternate" hreflang="es-MX" href="<?php echo esc_url($ctx['canonical']); ?>" />
    <link rel="alternate" hreflang="x-default" href="<?php echo esc_url($ctx['canonical']); ?>" />
    <meta name="description" content="<?php echo esc_attr(wp_html_excerpt($ctx['desc'], 160, '…')); ?>" />
    <meta name="keywords" content="<?php echo esc_attr($keywords); ?>" />
    <meta name="robots" content="<?php echo esc_attr($ctx['robots']); ?>" />
    <meta name="googlebot" content="<?php echo esc_attr($ctx['robots']); ?>" />
    <meta name="author" content="<?php echo esc_attr($site_name); ?>" />
    <?php $geo = casa_seo_geo(); ?>
    <meta name="geo.region" content="MX-GUA" />
    <meta name="geo.placename" content="León, Guanajuato" />
    <meta name="geo.position" content="<?php echo esc_attr($geo['latitude'] . ';' . $geo['longitude']); ?>" />
    <meta name="ICBM" content="<?php echo esc_attr($geo['latitude'] . ', ' . $geo['longitude']); ?>" />
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>" />
    <meta property="og:title" content="<?php echo esc_attr($ctx['title']); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($ctx['desc']); ?>" />
    <meta property="og:image" content="<?php echo esc_url($ctx['image']); ?>" />
    <meta property="og:image:alt" content="<?php echo esc_attr($ctx['title']); ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:url" content="<?php echo esc_url($ctx['canonical']); ?>" />
    <meta property="og:type" content="<?php echo esc_attr($ctx['type'] === 'event' ? 'article' : 'website'); ?>" />
    <meta property="og:locale" content="es_MX" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr($ctx['title']); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($ctx['desc']); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url($ctx['image']); ?>" />
    <meta name="twitter:image:alt" content="<?php echo esc_attr($ctx['title']); ?>" />
    <script type="application/ld+json">
    <?php
    echo wp_json_encode(array(
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_LINE_TERMINATORS);
    ?>
    </script>
    <?php
}
add_action('wp_head', 'casa_render_advanced_seo_and_sitelinks', 1);

add_filter('pre_get_document_title', function ($title) {
    $ctx = casa_seo_page_context();
    return $ctx['title'];
}, 99);

add_filter('document_title_parts', function ($parts) {
    $ctx = casa_seo_page_context();
    return array(
        'title' => $ctx['title'],
        'site' => '',
    );
}, 999);

function casa_custom_sitemap_endpoint() {
    add_rewrite_rule('^sitemap\\.xml/?$', 'index.php?casa_sitemap=1', 'top');
    add_rewrite_rule('^llms\\.txt/?$', 'index.php?casa_llms=1', 'top');
}
add_action('init', 'casa_custom_sitemap_endpoint');
add_action('init', function () {
    // Bump the version suffix whenever the sitemap rewrite rule changes.
    if (!get_option('casadepiedra_rewrites_flushed_v15')) {
        flush_rewrite_rules(false);
        update_option('casadepiedra_rewrites_flushed_v15', true);
        update_option('casadepiedra_rewrites_flushed_v14', true);
    }
}, 20);

function casa_custom_sitemap_query_vars($vars) {
    $vars[] = 'casa_sitemap';
    $vars[] = 'casa_llms';
    return $vars;
}
add_filter('query_vars', 'casa_custom_sitemap_query_vars');

function casa_sitemap_url_node($loc, $lastmod) {
    echo "  <url>\n";
    echo '    <loc>' . esc_url($loc) . "</loc>\n";
    if ($lastmod) {
        echo '    <lastmod>' . esc_html($lastmod) . "</lastmod>\n";
    }
    echo "  </url>\n";
}

function casa_render_sitemap_template() {
    if (get_query_var('casa_llms')) {
        casa_render_llms_txt();
        exit;
    }
    if (!get_query_var('casa_sitemap')) {
        return;
    }
    // Derive a stable fallback date from the last theme/option update.
    $static_lastmod = get_option('casa_sitemap_static_lastmod', gmdate('Y-m-d'));

    if (headers_sent()) {
        status_header(500);
        exit;
    }
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    casa_sitemap_url_node(trailingslashit(home_url('/')), $static_lastmod);

    $pages = array('espacios', 'restaurantes', 'galeria', 'quienes-somos', 'contacto', 'mapa-del-sitio');
    if (get_option('casa_opt_status_eventos', '0') === '1') {
        $pages[] = 'eventos';
    }
    foreach ($pages as $slug) {
        // Try to get lastmod from the WordPress page with that slug.
        $page = get_page_by_path($slug);
        $mod  = $page ? get_the_modified_date('Y-m-d', $page) : $static_lastmod;
        casa_sitemap_url_node(trailingslashit(home_url('/' . $slug . '/')), $mod ?: $static_lastmod);
    }

    foreach (array('espacios', 'restaurantes') as $cpt) {
        foreach (get_posts(array('post_type' => $cpt, 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC')) as $p) {
            $mod = get_the_modified_date('Y-m-d', $p);
            casa_sitemap_url_node(get_permalink($p), $mod ?: $static_lastmod);
        }
    }
    // Include published individual event posts only when eventos section is live.
    if (get_option('casa_opt_status_eventos', '0') === '1') {
        foreach (get_posts(array('post_type' => 'eventos', 'post_status' => 'publish', 'posts_per_page' => -1)) as $p) {
            $mod = get_the_modified_date('Y-m-d', $p);
            casa_sitemap_url_node(get_permalink($p), $mod ?: $static_lastmod);
        }
    }
    echo '</urlset>';
    exit;
}
add_action('template_redirect', 'casa_render_sitemap_template');

function casa_render_llms_txt() {
    header('Content-Type: text/plain; charset=utf-8');
    $custom = get_option('casa_opt_seo_llms', '');
    if (is_string($custom) && trim($custom) !== '') {
        echo $custom;
        return;
    }
    $site = casa_seo_site_name();
    $url = trailingslashit(home_url('/'));
    echo "# {$site}\n";
    echo "> Complejo social y gastronómico en León, Guanajuato (Av. Cerro Gordo 270, zona norte, cerca de Plaza Mayor). Ex hacienda de 1845 con jardín de eventos, salones, terrazas, estacionamiento y restaurantes de alta cocina.\n\n";
    echo "## Entidad\n";
    echo "- Nombre: Casa de Piedra León\n";
    echo "- Tipo: EventVenue + LocalBusiness + polo gastronómico\n";
    $addr = casa_seo_address();
    $geo  = casa_seo_geo();
    echo '- Dirección: ' . $addr['streetAddress'] . ', ' . $addr['postalCode'] . ' ' . $addr['addressLocality'] . ', ' . $addr['addressRegion'] . ", México\n";
    echo '- Teléfono: ' . (function_exists('casa_get_display_phone') ? casa_get_display_phone() : '477 289 25 21') . "\n";
    echo '- Email: ' . get_option('casa_opt_global_email', 'eventos@casadepiedraleon.mx') . "\n";
    echo '- Coordenadas: ' . $geo['latitude'] . ', ' . $geo['longitude'] . "\n\n";
    echo "## Para qué sirve el recinto\n";
    echo "- Bodas, XV años, bautizos, primeras comuniones, confirmaciones\n";
    echo "- Bar Mitzvá, Bat Mitzvá, cumpleaños, aniversarios y graduaciones\n";
    echo "- Baby showers, reuniones familiares, cenas de gala y festejos sociales\n";
    echo "- Eventos empresariales, convenciones y congresos\n";
    echo "- Comidas y cenas en restaurantes de alta cocina del complejo\n";
    echo "- Ubicación: zona norte de León, Cerro Gordo, cerca de Plaza Mayor, con estacionamiento y valet\n\n";
    echo "## Venues\n";
    foreach (get_posts(array('post_type' => 'espacios', 'posts_per_page' => -1, 'post_status' => 'publish')) as $p) {
        echo '- ' . get_the_title($p) . ': ' . get_permalink($p) . "\n";
    }
    echo "\n## Restaurantes\n";
    foreach (get_posts(array('post_type' => 'restaurantes', 'posts_per_page' => -1, 'post_status' => 'publish')) as $p) {
        echo '- ' . get_the_title($p) . ': ' . get_permalink($p) . "\n";
    }
    echo "\n## Enlaces canónicos\n";
    echo "- Inicio: {$url}\n";
    echo "- Espacios y salones: {$url}espacios/\n";
    echo "- Restaurantes: {$url}restaurantes/\n";
    echo "- Galería: {$url}galeria/\n";
    echo "- Cotización: {$url}contacto/\n";
    echo "- Quiénes somos: {$url}quienes-somos/\n";
    echo "- Mapa del sitio: {$url}mapa-del-sitio/\n";
    echo "- Sitemap: {$url}sitemap.xml\n";
    echo "- Hospedaje: no es hotel; no ofrece habitaciones.\n";
    echo "- Última actualización: " . gmdate('Y-m-d') . "\n";
}

function casa_custom_robotstxt($output, $public) {
    $override = get_option('casa_opt_seo_robots_txt', '');
    if (is_string($override) && trim($override) !== '') {
        $override = preg_replace('/https?:\/\/[^\/\s]*\.local[^\s]*/i', trailingslashit(home_url('/')) . 'sitemap.xml', $override);
        if (stripos($override, 'Sitemap:') === false) {
            $override = rtrim($override) . "\n\nSitemap: " . trailingslashit(home_url('/')) . "sitemap.xml\n";
        }
        return $override;
    }
    $site_url = trailingslashit(home_url('/'));
    $custom  = "User-agent: *\n";
    $custom .= "Allow: /\n";
    $custom .= "Disallow: /wp-admin/\n";
    $custom .= "Allow: /wp-admin/admin-ajax.php\n";
    $custom .= "Disallow: /wp-includes/\n";
    $custom .= "Disallow: /wp-login.php\n";
    $custom .= "Disallow: /?s=\n";
    $custom .= "Disallow: /*?s=\n";
    $custom .= "Disallow: /private/\n";
    $custom .= "Disallow: /aviso-de-privacidad/\n";
    $custom .= "Disallow: /feed/\n";
    $custom .= "Disallow: /comments/\n";
    $custom .= "Disallow: /*/feed/\n";
    $custom .= "Disallow: /*/comment-page-*\n\n";
    foreach (array('Googlebot', 'Googlebot-Image', 'Bingbot', 'GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'Claude-SearchBot', 'PerplexityBot', 'Google-Extended', 'Applebot-Extended') as $bot) {
        $custom .= "User-agent: {$bot}\nAllow: /\n\n";
    }
    $custom .= "Sitemap: {$site_url}sitemap.xml\n";
    return $custom;
}
add_filter('robots_txt', 'casa_custom_robotstxt', 99, 2);
add_filter('wp_sitemaps_enabled', '__return_false');

add_action('template_redirect', function () {
    $path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $path = rawurldecode(strtolower($path));
    $map = array(
        'zona-de-restaurantes' => home_url('/restaurantes/'),
        'conoce-casa-de-piedra' => home_url('/espacios/'),
        'servicios' => home_url('/espacios/'),
        'venues' => home_url('/espacios/'),
        'salon-de-eventos' => home_url('/espacios/'),
        'salon-de-fiestas' => home_url('/espacios/'),
        'jardin-de-eventos' => home_url('/espacios/'),
        'jardin-de-eventos-leon' => home_url('/espacios/'),
        'hacienda-bodas' => home_url('/espacios/'),
        'hacienda-para-bodas' => home_url('/espacios/'),
        'bodas' => home_url('/espacios/'),
        'xv-anos' => home_url('/espacios/'),
        'xv-años' => home_url('/espacios/'),
        'cotizar' => home_url('/contacto/'),
        'reservaciones' => home_url('/contacto/'),
        'nosotros' => home_url('/quienes-somos/'),
        'about' => home_url('/quienes-somos/'),
        'gallery' => home_url('/galeria/'),
    );
    if (isset($map[$path])) {
        wp_safe_redirect($map[$path], 301);
        exit;
    }

    $cpt_map = array(
        'argentilia' => 'restaurantes',
        'lucio' => 'restaurantes',
        'manolo' => 'restaurantes',
        'sato' => 'restaurantes',
        'valentina' => 'restaurantes',
        'jardin-principal' => 'espacios',
        'salon-principal' => 'espacios',
        'terraza-mezquite' => 'espacios',
        'salon-pavorreales' => 'espacios',
    );
    if (isset($cpt_map[$path])) {
        $post = get_page_by_path($path, OBJECT, $cpt_map[$path]);
        if ($post) {
            wp_safe_redirect(get_permalink($post), 301);
            exit;
        }
    }
}, 0);

add_filter('wp_headers', function ($headers) {
    $headers['X-Content-Type-Options'] = 'nosniff';
    $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
    $headers['X-Frame-Options'] = 'SAMEORIGIN';
    $headers['Permissions-Policy'] = 'camera=(), microphone=(), geolocation=()';
    if (is_ssl() && empty($headers['Strict-Transport-Security'])) {
        $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
    }
    return $headers;
});
