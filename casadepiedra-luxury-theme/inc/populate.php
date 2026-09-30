<?php
/**
 * Script para auto-poblar los datos originales de la plantilla,
 * crear menús y accesos directos de forma automática (Plug & Play).
 */

function casadepiedra_bind_packaged_media() {
    return;
}

function casadepiedra_bind_packaged_media_legacy_unused() {
    $logo_now = (string) get_option('casa_opt_global_logo', '');
    if ($logo_now === '' || strpos($logo_now, 'casadepiedraleon.mx') !== false || strpos($logo_now, 'Logo_Header') !== false) {
        update_option('casa_opt_global_logo', get_template_directory_uri() . '/assets/images/logo-navbar-oficial.png');
    }

    $packaged_media = array(
        'casa_opt_home_hero_img' => 'assets/images/mirror/Banner-inicio-scaled.webp',
        'casa_opt_nosotros_hero_img' => 'assets/images/mirror/Quienes-somos-scaled.webp',
        'casa_opt_nosotros_portada' => 'assets/images/mirror/20250520_105500-scaled.jpg',
        'casa_opt_espacios_portada' => 'assets/images/mirror/1-scaled.jpg',
        'casa_opt_restaurantes_portada' => 'assets/images/mirror/20250520_105227-scaled.jpg',
        'casa_opt_eventos_portada' => 'assets/images/mirror/DJI_0499-scaled.jpg',
        'casa_opt_galeria_portada' => 'assets/images/mirror/SP2-scaled.jpeg',
        'casa_opt_contacto_portada' => 'assets/images/mirror/20250520_103056-scaled.jpg',
        'casa_opt_footer_bg_image' => 'assets/images/mirror/20250520_105311-scaled.jpg',
        'casa_opt_privacidad_portada' => 'assets/images/mirror/20250520_103051-scaled.jpg',
        'casa_opt_global_transition_logo' => 'assets/images/escudo-animacion-blanco.png',
        'casa_opt_mail_logo' => 'assets/images/mirror/Recurso-5@4x.png',
    );
    foreach ($packaged_media as $opt => $rel) {
        $abs = get_template_directory() . '/' . $rel;
        if (!file_exists($abs)) {
            continue;
        }
        $url = get_template_directory_uri() . '/' . $rel;
        $current = (string) get_option($opt, '');
        if ($current === '' || strpos($current, 'casa-de-piedra.local') !== false || strpos($current, '/uploads/') !== false) {
            update_option($opt, $url);
        }
    }

    $lucio_pdf = get_template_directory() . '/assets/pdfs/LUCIO-LEON-DIGITAL.pdf';
    if (file_exists($lucio_pdf) && function_exists('casadepiedra_get_post_by_title')) {
        $lucio = casadepiedra_get_post_by_title('Lucio Ítalo-Argentino', 'restaurantes');
        if ($lucio) {
            $menu = (string) get_post_meta($lucio->ID, '_restaurante_menu', true);
            if ($menu === '' || strpos($menu, 'casa-de-piedra.local') !== false || strpos($menu, '/uploads/') !== false) {
                update_post_meta($lucio->ID, '_restaurante_menu', get_template_directory_uri() . '/assets/pdfs/LUCIO-LEON-DIGITAL.pdf');
            }
        }
    }

    $planos = array(
        'Jardín Principal' => 'assets/pdfs/Jardin-Principal_CdP.pdf',
        'Salon Pavorreales' => 'assets/pdfs/Salon-Pavorreales_CdP.pdf',
        'Salón Pavorreales' => 'assets/pdfs/Salon-Pavorreales_CdP.pdf',
        'Terraza Mezquite' => 'assets/pdfs/Terraza_CdP.pdf',
        'Salón Principal' => 'assets/pdfs/Salon-principal_CdP.pdf',
        'Salon Principal' => 'assets/pdfs/Salon-principal_CdP.pdf',
    );
    if (function_exists('casadepiedra_get_post_by_title')) {
        foreach ($planos as $title => $rel) {
            $abs = get_template_directory() . '/' . $rel;
            if (!file_exists($abs)) {
                continue;
            }
            $esp = casadepiedra_get_post_by_title($title, 'espacios');
            if (!$esp) {
                continue;
            }
            $current = (string) get_post_meta($esp->ID, '_espacio_plano_pdf', true);
            if ($current === '' || strpos($current, 'casa-de-piedra.local') !== false || strpos($current, '/uploads/') !== false) {
                update_post_meta($esp->ID, '_espacio_plano_pdf', get_template_directory_uri() . '/' . $rel);
            }
        }
    }

    update_option('casadepiedra_media_bound_v12', true);
}

function casadepiedra_is_legacy_venue_phone($value) {
    $digits = preg_replace('/\D+/', '', (string) $value);
    return substr($digits, -10) === '4777172600';
}

function casadepiedra_sync_official_phone() {
    if (get_option('casadepiedra_phone_v14_4772892521')) {
        return;
    }

    $display = '477 289 25 21';
    update_option('casa_opt_global_phone', $display);
    update_option('casa_opt_global_whatsapp', $display);

    $restaurants = get_posts(array(
        'post_type' => 'restaurantes',
        'posts_per_page' => -1,
        'post_status' => 'any',
    ));
    foreach ($restaurants as $restaurant) {
        $tel = get_post_meta($restaurant->ID, '_restaurante_telefono', true);
        if (casadepiedra_is_legacy_venue_phone($tel)) {
            update_post_meta($restaurant->ID, '_restaurante_telefono', '+52 477 289 25 21');
        }
    }

    update_option('casadepiedra_phone_v14_4772892521', true);
    update_option('casadepiedra_phone_v13_4772892521', true);
}

function casadepiedra_plug_and_play_setup() {
    casadepiedra_bind_packaged_media();
    casadepiedra_sync_official_phone();

    // Solo ejecutar una vez por versión de poblamiento
    if (get_option('casadepiedra_fully_populated_v11_theme_assets')) {
        return;
    }

    // 1. Crear Páginas
    $pages = array(
        'Inicio' => 'Página principal de Casa de Piedra.',
        'Quiénes Somos' => 'Nuestra historia.',
        'Espacios' => 'Nuestros espacios.',
        'Restaurantes' => 'Nuestros restaurantes.',
        'Eventos' => 'Nuestros eventos.',
        'Galería' => 'Galería de imágenes.',
        'Contacto' => 'Contacto.'
    );

    $page_ids = array();

    foreach ($pages as $title => $content) {
        $page_check = function_exists('casadepiedra_get_post_by_title') ? casadepiedra_get_post_by_title($title) : get_page_by_path(sanitize_title($title));
        if (!isset($page_check->ID)) {
            $page_id = wp_insert_post(array(
                'post_title'    => $title,
                'post_content'  => $content,
                'post_status'   => 'publish',
                'post_type'     => 'page'
            ));
        } else {
            $page_id = $page_check->ID;
        }
        
        $page_ids[$title] = $page_id;
        update_option('casadepiedra_page_id_' . sanitize_title($title), $page_id);
        
        if ($title === 'Inicio') {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $page_id);
        }
        $templates = array(
            'Galería' => 'page-galeria.php',
            'Contacto' => 'page-contacto.php',
            'Quiénes Somos' => 'page-quienes-somos.php',
        );
        if (isset($templates[$title])) {
            update_post_meta($page_id, '_wp_page_template', $templates[$title]);
        }
    }

    // 2. Crear Menú Principal
    $menu_name = 'Menú Principal Premium';
    $menu_location = 'menu-principal';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);

        // Añadir elementos al menú en orden (sin Quiénes Somos al estar integrado en Inicio)
        $menu_items = array('Inicio', 'Venues', 'Restaurantes', 'Eventos', 'Galería', 'Contacto');
        foreach ($menu_items as $item_title) {
            if (isset($page_ids[$item_title])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $item_title,
                    'menu-item-object-id' => $page_ids[$item_title],
                    'menu-item-object' => 'page',
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'post_type',
                ));
            }
        }

        // Asignar el menú a la ubicación del tema
        $locations = get_theme_mod('nav_menu_locations');
        if (!is_array($locations)) {
            $locations = array();
        }
        $locations[$menu_location] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // 3. Crear los 7 Restaurantes Oficiales y Espacios
    // 3. Crear los 4 Restaurantes Oficiales de Casa de Piedra con sus Logos, Menús y Reservas
    $restaurantes = array(
        array(
            'title' => 'Argentilia',
            'desc' => 'Especialistas en alta cocina argentina y cortes a la parrilla de leña en horno Josper. Un ambiente sofisticado e inolvidable con pescados, mariscos, pastas y una cava excepcional en el corazón de Casa de Piedra.',
            'cocina' => 'Alta Cocina Argentina & Parrilla Josper',
            'logo' => '',
            'menu' => 'https://argentilia.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://argentilia.mx',
            'telefono' => '+52 477 717 1727'
        ),
        array(
            'title' => 'Lucio Ítalo-Argentino',
            'desc' => 'Una propuesta exquisita que entrelaza la tradición artesanal de las pastas y risottos italianos con la maestría de los cortes asados a la brasa argentina. Sabor contemporáneo en un entorno de lujo y distinción.',
            'cocina' => 'Cocina Ítalo-Argentina de Autor',
            'logo' => '',
            'menu' => get_template_directory_uri() . '/assets/pdfs/LUCIO-LEON-DIGITAL.pdf',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://grupomrl.com.mx',
            'telefono' => '+52 477 289 25 21'
        ),
        array(
            'title' => 'Manolo Taberna Española',
            'desc' => 'Auténtica esencia del tapeo y la gastronomía ibérica. Desde jamón ibérico de bellota y paellas tradicionales hasta mariscos frescos y selectos vinos españoles en una atmósfera cálida y festiva.',
            'cocina' => 'Auténtica Taberna Española & Tapeo Ibérico',
            'logo' => '',
            'menu' => 'https://grupomrl.com.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://grupomrl.com.mx',
            'telefono' => '+52 477 289 25 21'
        ),
        array(
            'title' => 'Sato Cocina Nikkei',
            'desc' => 'Fusión sublime entre la milenaria tradición culinaria japonesa y la vibrante cocina peruana. Omakase, nigiris de autor, ceviches y robatayaki con ingredientes premium en un ambiente minimalista de nivel internacional.',
            'cocina' => 'Alta Cocina Japonesa & Nikkei',
            'logo' => '',
            'menu' => 'https://satorestaurante.com.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://satorestaurante.com.mx',
            'telefono' => '+52 477 394 9444'
        ),
        array(
            'title' => 'Casa Mía Trattoria & Wine Bar',
            'desc' => 'Un rincón íntimo y elegante dedicado a la alta cocina italiana y a la cultura del vino. Pastas artesanales hechas en casa, risottos trufados, carpaccios y una selección extraordinaria de etiquetas internacionales para maridajes inolvidables.',
            'cocina' => 'Cocina Italiana de Autor & Wine Bar',
            'logo' => '',
            'menu' => 'https://casadepiedraleon.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://casadepiedraleon.mx',
            'telefono' => '+52 477 289 25 21'
        ),
        array(
            'title' => 'Valentina Cocina Contemporánea',
            'desc' => 'Experiencia sensorial que combina técnicas culinarias de vanguardia con los mejores ingredientes de origen. Cortes selectos, pescados de importación y coctelería de autor en una atmósfera sofisticada y vibrante dentro de Casa de Piedra.',
            'cocina' => 'Alta Cocina Contemporánea & Asador',
            'logo' => '',
            'menu' => 'https://casadepiedraleon.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://casadepiedraleon.mx',
            'telefono' => '+52 477 289 25 21'
        )
    );
    foreach ($restaurantes as $rest) {
        $new_rest_id = 0;
        $existing_rest = function_exists('casadepiedra_get_post_by_title') ? casadepiedra_get_post_by_title($rest['title'], 'restaurantes') : null;
        if ($existing_rest) {
            wp_update_post(array(
                'ID' => $existing_rest->ID,
                'post_content' => $rest['desc']
            ));
            update_post_meta($existing_rest->ID, '_restaurante_cocina', $rest['cocina']);
            update_post_meta($existing_rest->ID, '_restaurante_logo', $rest['logo']);
            update_post_meta($existing_rest->ID, '_restaurante_menu', $rest['menu']);
            update_post_meta($existing_rest->ID, '_restaurante_reserva_tipo', $rest['reserva_tipo']);
            update_post_meta($existing_rest->ID, '_restaurante_reserva_valor', $rest['reserva_valor']);
            update_post_meta($existing_rest->ID, '_restaurante_telefono', $rest['telefono']);
        } else {
            $new_rest_id = wp_insert_post(array(
                'post_title' => $rest['title'],
                'post_content' => $rest['desc'],
                'post_status' => 'publish',
                'post_type' => 'restaurantes'
            ));
            if ($new_rest_id && !is_wp_error($new_rest_id)) {
                update_post_meta($new_rest_id, '_restaurante_cocina', $rest['cocina']);
                update_post_meta($new_rest_id, '_restaurante_logo', $rest['logo']);
                update_post_meta($new_rest_id, '_restaurante_menu', $rest['menu']);
                update_post_meta($new_rest_id, '_restaurante_reserva_tipo', $rest['reserva_tipo']);
                update_post_meta($new_rest_id, '_restaurante_reserva_valor', $rest['reserva_valor']);
                update_post_meta($new_rest_id, '_restaurante_telefono', $rest['telefono']);
            }
        }
        $rest_id = $existing_rest ? $existing_rest->ID : (isset($new_rest_id) ? $new_rest_id : 0);
        if ($rest_id && function_exists('casadepiedra_resolve_restaurante_card_img')) {
            if (!get_post_meta($rest_id, '_restaurante_card_image', true)) {
                update_post_meta($rest_id, '_restaurante_card_image', casadepiedra_resolve_restaurante_card_img($rest_id));
            }
            if (!get_post_meta($rest_id, '_restaurante_hero_image', true) && function_exists('casadepiedra_resolve_restaurante_hero')) {
                update_post_meta($rest_id, '_restaurante_hero_image', casadepiedra_resolve_restaurante_hero($rest_id));
            }
        }
    }

    // Eliminar restaurantes dummy de la base de datos de WordPress (Corazón de Alcachofa, Agaves, Mochomos, etc.)
    $official_titles_wp = array(
        'Argentilia',
        'Lucio Ítalo-Argentino',
        'Manolo',
        'Manolo Taberna Española',
        'Sato Cocina Nikkei',
        'Casa Mía Trattoria & Wine Bar',
        'Valentina Cocina Contemporánea'
    );
    $all_wp_rests = get_posts(array('post_type' => 'restaurantes', 'posts_per_page' => -1, 'post_status' => 'any'));
    foreach ($all_wp_rests as $wr) {
        if (!in_array($wr->post_title, $official_titles_wp)) {
            wp_delete_post($wr->ID, true);
        }
    }

    $espacios = array(
        array(
            'title' => 'Salón Principal',
            'desc' => 'En el corazón de Casa de Piedra, se erige el salón con una arquitectura imponente, construcción de gran altura y detalles que reflejan el carácter histórico y emblemático del recinto. Su amplitud y versatilidad permiten recibir desde grandes celebraciones y eventos de alto nivel, ofreciendo el entorno más sofisticado para cualquier ocasión.',
            'cap' => '800'
        ),
        array(
            'title' => 'Terraza Mezquite',
            'desc' => 'Enmarcada por la arquitectura original de la ex hacienda y bajo la sombra de un majestuoso mezquite resguardado en un ojo de agua, que da nombre a este espacio, que invita a vivir celebraciones en un entorno donde la historia y la naturaleza conviven en perfecta armonía. Un escenario al aire libre, íntimo, elegante y lleno de encanto.',
            'cap' => '150'
        ),
        array(
            'title' => 'Salón Pavorreales',
            'desc' => 'Un espacio que combina privacidad, elegancia y calidez. Su diseño atemporal crea el ambiente ideal para eventos sociales, reuniones ejecutivas y celebraciones que buscan una experiencia más íntima, sin renunciar al sello distintivo de Casa de Piedra.',
            'cap' => '90'
        ),
        array(
            'title' => 'Jardín Principal',
            'desc' => 'Rodeado de vegetación y una atmósfera serena, el Jardín Principal es el escenario perfecto para celebraciones al aire libre con un trabajo selecto de paisajismo. Un espacio donde la naturaleza y la elegancia conviven para crear momentos memorables, desde ceremonias hasta recepciones bajo el cielo.',
            'cap' => '1,500'
        )
    );
    foreach ($espacios as $esp) {
        $new_id = 0;
        $existing = function_exists('casadepiedra_get_post_by_title') ? casadepiedra_get_post_by_title($esp['title'], 'espacios') : null;
        if (!$existing && $esp['title'] === 'Terraza Mezquite' && function_exists('casadepiedra_get_post_by_title')) {
            $existing = casadepiedra_get_post_by_title('Terraza del Mezquite', 'espacios');
        }
        if ($existing) {
            wp_update_post(array(
                'ID' => $existing->ID,
                'post_title' => $esp['title'],
                'post_content' => $esp['desc']
            ));
            update_post_meta($existing->ID, '_espacio_capacidad', $esp['cap']);
        } else {
            $new_id = wp_insert_post(array('post_title' => $esp['title'], 'post_content' => $esp['desc'], 'post_status' => 'publish', 'post_type' => 'espacios'));
            if ($new_id && !is_wp_error($new_id)) {
                update_post_meta($new_id, '_espacio_capacidad', $esp['cap']);
            }
        }
        $esp_id = $existing ? $existing->ID : (isset($new_id) && $new_id && !is_wp_error($new_id) ? $new_id : 0);
        if ($esp_id && function_exists('casadepiedra_resolve_espacio_card_img')) {
            $card = casadepiedra_resolve_espacio_card_img($esp_id);
            $hero = function_exists('casadepiedra_resolve_espacio_hero') ? casadepiedra_resolve_espacio_hero($esp_id) : $card;
            if ($card && !get_post_meta($esp_id, '_espacio_tarjeta_inicio', true)) {
                update_post_meta($esp_id, '_espacio_tarjeta_inicio', $card);
            }
            if ($hero && !get_post_meta($esp_id, '_espacio_portada', true)) {
                update_post_meta($esp_id, '_espacio_portada', $hero);
            }
        }
    }

    // 4. Poblar Valores por Defecto del Panel Casa (Plug & Play)
    $panel_defaults = array(
        'casa_opt_global_logo' => '',
        'casa_opt_galeria_etiquetas' => 'Boda, Cumpleaños, Eventos empresariales, Convenciones',
        'casa_opt_global_address' => 'Av Cerro Gordo 270, Casa de Piedra, 37120 León de los Aldama, Gto.',
        'casa_opt_global_phone' => '477 289 25 21',
        'casa_opt_global_whatsapp' => '477 289 25 21',
        'casa_opt_global_email' => 'eventos@casadepiedraleon.mx',
        'casa_opt_global_espacios_title' => 'Nuestros Espacios',
        'casa_opt_global_espacios_desc' => 'Escenarios para grandes historias',
        'casa_opt_global_restaurantes_title' => 'La cúspide de la gastronomía en el Bajío.',
        'casa_opt_global_restaurantes_desc' => 'Una experiencia inigualable que reúne la oferta gastronómica más exclusivas de la región, ofreciendo un viaje de sabores únicos.',
        'casa_opt_home_hero_img' => '',
        'casa_opt_home_hero_subtitle' => 'Desde 1845',
        'casa_opt_home_hero_title' => 'Tu historia es parte de nuestra historia.',
        'casa_opt_home_hero_desc' => 'El símbolo de prestigio en el Bajío, ha sido un escenario de momentos extraordinarios. En la zona dorada de León, nuestros espacios han sido testigos de grandes celebraciones. Aquí, tu historia es parte de nuestra historia.',
        'casa_opt_nosotros_hero_img' => '',
        'casa_opt_nosotros_title' => 'Nuestra Historia',
        'casa_opt_nosotros_subtitle' => 'Donde el pasado y el presente se encuentran.',
        'casa_opt_nosotros_desc' => '<p style="margin-bottom: 1.5rem;">Desde 1845, sus muros de cantera han sido testigos de amor y celebración.</p><p>Hoy, en el corazón dorado de la ciudad, cada rincón invita a vivir experiencias únicas, donde la sofisticación se fusiona con la tradición, creando un espacio solo para los más exigentes.</p>',
        'casa_opt_contacto_title' => 'Contacto',
        'casa_opt_contacto_desc' => 'Comunícate con nosotros para agendar tu evento.',
        'casa_opt_contacto_map' => '',
        'casa_opt_nosotros_portada' => '',
        'casa_opt_nosotros_subtitle' => 'Desde 1845',
        'casa_opt_nosotros_title' => 'Quiénes Somos',
        'casa_opt_nosotros_header_desc' => 'Donde la sofisticación contemporánea y el legado histórico se encuentran',
        'casa_opt_espacios_portada' => '',
        'casa_opt_global_espacios_subtitle' => 'Exclusividad',
        'casa_opt_global_espacios_title' => 'Nuestros Espacios',
        'casa_opt_global_espacios_desc' => 'Escenarios para grandes historias',
        'casa_opt_restaurantes_portada' => '',
        'casa_opt_global_restaurantes_subtitle' => 'Alta cocina',
        'casa_opt_global_restaurantes_title' => 'La cúspide de la gastronomía en el Bajío.',
        'casa_opt_global_restaurantes_desc' => 'Una experiencia inigualable que reúne la oferta gastronómica más exclusivas de la región, ofreciendo un viaje de sabores únicos.',
        'casa_opt_eventos_portada' => '',
        'casa_opt_global_eventos_subtitle' => 'Celebraciones',
        'casa_opt_global_eventos_title' => 'Próximos Eventos',
        'casa_opt_global_eventos_desc' => 'Experiencias únicas y celebraciones a tu medida.',
        'casa_opt_galeria_portada' => '',
        'casa_opt_galeria_subtitle' => 'Nuestra Esencia',
        'casa_opt_galeria_title' => 'Galería Oficial',
        'casa_opt_galeria_desc' => 'Momentos inolvidables y celebraciones extraordinarias',
        'casa_opt_contacto_portada' => '',
        'casa_opt_contacto_subtitle' => 'Atención Exclusiva',
        'casa_opt_contacto_title' => 'Contacto Oficial',
        'casa_opt_contacto_desc' => 'Estamos a tu disposición para diseñar la celebración que mereces',
        'casa_opt_status_nosotros' => '1',
        'casa_opt_status_espacios' => '1',
        'casa_opt_status_restaurantes' => '1',
        'casa_opt_status_galeria' => '1',
        'casa_opt_status_eventos' => '0',
        'casa_opt_status_contacto' => '1',
        'casa_opt_footer_bg_image' => '',
        'casa_opt_rest_rev1_text' => '',
        'casa_opt_rest_rev2_text' => '',
        'casa_opt_rest_rev3_text' => '',
        'casa_opt_rest_rev1_author' => '',
        'casa_opt_rest_rev2_author' => '',
        'casa_opt_rest_rev3_author' => '',
    );

    foreach ($panel_defaults as $key => $value) {
        // Solo añade la opción si no existe, para no sobrescribir configuraciones del usuario
        add_option($key, $value);
    }

    // Asegurar que si en la base de datos de WordPress estaba guardada la dirección antigua errónea, se actualice a la oficial
    $current_address = get_option('casa_opt_global_address', '');
    if (empty($current_address) || strpos($current_address, 'Juan Alonso') !== false || strpos($current_address, 'Lomas del Campestre') !== false || strpos($current_address, 'Cerro Gordo 270, Casa de Piedra') === false) {
        update_option('casa_opt_global_address', 'Av Cerro Gordo 270, Casa de Piedra, 37120 León de los Aldama, Gto.');
    }

    casadepiedra_bind_packaged_media();

    if (!get_option('casadepiedra_rewrites_flushed_v12')) {
        flush_rewrite_rules(false);
        update_option('casadepiedra_rewrites_flushed_v12', true);
        update_option('casadepiedra_rewrites_flushed_v11', true);
    }

    update_option('casadepiedra_fully_populated_v11_theme_assets', true);
    update_option('casadepiedra_fully_populated_v10_clean_6restaurantes', true);
}
add_action('init', 'casadepiedra_plug_and_play_setup');
add_action('after_switch_theme', 'casadepiedra_plug_and_play_setup');
