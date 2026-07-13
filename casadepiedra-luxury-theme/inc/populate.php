<?php
/**
 * Script para auto-poblar los datos originales de la plantilla,
 * crear menús y accesos directos de forma automática (Plug & Play).
 */

function casadepiedra_plug_and_play_setup() {
    $log_file = get_template_directory() . '/debug.log';
    file_put_contents($log_file, "Running setup...\n", FILE_APPEND);

    // Solo ejecutar una vez por versión de poblamiento
    if (get_option('casadepiedra_fully_populated_v10_clean_6restaurantes')) {
        return;
    }
    file_put_contents($log_file, "Option v10 not found, proceeding with clean 6 restaurants populate...\n", FILE_APPEND);

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
        $page_check = get_page_by_title($title);
        if (!isset($page_check->ID)) {
            $page_id = wp_insert_post(array(
                'post_title'    => $title,
                'post_content'  => $content,
                'post_status'   => 'publish',
                'post_type'     => 'page'
            ));
            file_put_contents($log_file, "Inserted page $title with ID $page_id\n", FILE_APPEND);
        } else {
            $page_id = $page_check->ID;
            file_put_contents($log_file, "Found existing page $title with ID $page_id\n", FILE_APPEND);
        }
        
        $page_ids[$title] = $page_id;
        update_option('casadepiedra_page_id_' . sanitize_title($title), $page_id);
        
        if ($title === 'Inicio') {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $page_id);
            file_put_contents($log_file, "Set Inicio as front page\n", FILE_APPEND);
        }
    }

    // 2. Crear Menú Principal
    $menu_name = 'Menú Principal Premium';
    $menu_location = 'menu-principal';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);
        file_put_contents($log_file, "Created menu with ID $menu_id\n", FILE_APPEND);

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
            'logo' => get_template_directory_uri() . '/assets/images/logos/argentilia-logo.png',
            'menu' => 'https://argentilia.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://argentilia.mx',
            'telefono' => '+52 477 717 1727'
        ),
        array(
            'title' => 'Lucio Ítalo-Argentino',
            'desc' => 'Una propuesta exquisita que entrelaza la tradición artesanal de las pastas y risottos italianos con la maestría de los cortes asados a la brasa argentina. Sabor contemporáneo en un entorno de lujo y distinción.',
            'cocina' => 'Cocina Ítalo-Argentina de Autor',
            'logo' => get_template_directory_uri() . '/assets/images/logos/lucio-logo.png',
            'menu' => 'https://grupomrl.com.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://grupomrl.com.mx',
            'telefono' => '+52 477 717 2600'
        ),
        array(
            'title' => 'Manolo Taberna Española',
            'desc' => 'Auténtica esencia del tapeo y la gastronomía ibérica. Desde jamón ibérico de bellota y paellas tradicionales hasta mariscos frescos y selectos vinos españoles en una atmósfera cálida y festiva.',
            'cocina' => 'Auténtica Taberna Española & Tapeo Ibérico',
            'logo' => get_template_directory_uri() . '/assets/images/logos/manolo-logo.png',
            'menu' => 'https://grupomrl.com.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://grupomrl.com.mx',
            'telefono' => '+52 477 717 2600'
        ),
        array(
            'title' => 'Sato Cocina Nikkei',
            'desc' => 'Fusión sublime entre la milenaria tradición culinaria japonesa y la vibrante cocina peruana. Omakase, nigiris de autor, ceviches y robatayaki con ingredientes premium en un ambiente minimalista de nivel internacional.',
            'cocina' => 'Alta Cocina Japonesa & Nikkei',
            'logo' => get_template_directory_uri() . '/assets/images/logos/sato-logo.png',
            'menu' => 'https://satorestaurante.com.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://satorestaurante.com.mx',
            'telefono' => '+52 477 394 9444'
        ),
        array(
            'title' => 'Casa Mía Trattoria & Wine Bar',
            'desc' => 'Un rincón íntimo y elegante dedicado a la alta cocina italiana y a la cultura del vino. Pastas artesanales hechas en casa, risottos trufados, carpaccios y una selección extraordinaria de etiquetas internacionales para maridajes inolvidables.',
            'cocina' => 'Cocina Italiana de Autor & Wine Bar',
            'logo' => get_template_directory_uri() . '/assets/images/logos/casa-mia-logo.png',
            'menu' => 'https://casadepiedraleon.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://casadepiedraleon.mx',
            'telefono' => '+52 477 717 2600'
        ),
        array(
            'title' => 'Valentina Cocina Contemporánea',
            'desc' => 'Experiencia sensorial que combina técnicas culinarias de vanguardia con los mejores ingredientes de origen. Cortes selectos, pescados de importación y coctelería de autor en una atmósfera sofisticada y vibrante dentro de Casa de Piedra.',
            'cocina' => 'Alta Cocina Contemporánea & Asador',
            'logo' => get_template_directory_uri() . '/assets/images/logos/valentina-logo.png',
            'menu' => 'https://casadepiedraleon.mx',
            'reserva_tipo' => 'web',
            'reserva_valor' => 'https://casadepiedraleon.mx',
            'telefono' => '+52 477 717 2600'
        )
    );
    foreach ($restaurantes as $rest) {
        $existing_rest = get_page_by_title($rest['title'], OBJECT, 'restaurantes');
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
    }

    // Eliminar restaurantes dummy de la base de datos de WordPress (Corazón de Alcachofa, Agaves, Mochomos, etc.)
    $official_titles_wp = array(
        'Argentilia',
        'Lucio Ítalo-Argentino',
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
        $existing = get_page_by_title($esp['title'], OBJECT, 'espacios');
        if (!$existing && $esp['title'] === 'Terraza Mezquite') {
            $existing = get_page_by_title('Terraza del Mezquite', OBJECT, 'espacios');
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
    }

    // 4. Poblar Valores por Defecto del Panel Casa (Plug & Play)
    $panel_defaults = array(
        'casa_opt_global_logo' => 'https://casadepiedraleon.mx/wp-content/uploads/2023/09/Logo_Header.png',
        'casa_opt_global_address' => 'Blvd. Juan Alonso de Torres 2002, Col. Valle del Campestre, León, Gto.',
        'casa_opt_global_phone' => '477 717 2600',
        'casa_opt_global_email' => 'eventos@casadepiedraleon.mx',
        'casa_opt_global_espacios_title' => 'Nuestros Espacios',
        'casa_opt_global_espacios_desc' => 'Escenarios para grandes historias',
        'casa_opt_global_restaurantes_title' => 'El epítome gastronómico del Bajío',
        'casa_opt_global_restaurantes_desc' => 'la cúspide de la gastronomía en el Bajío. Una experiencia inigualable que reúne la oferta gastronómica más exclusivas de la región, ofreciendo un viaje de sabores únicos.',
        'casa_opt_home_hero_img' => get_template_directory_uri() . '/assets/images/salon_principal_1779523069698.png',
        'casa_opt_home_hero_subtitle' => 'Desde 1845',
        'casa_opt_home_hero_title' => 'El recinto más exclusivo <br /> de León.',
        'casa_opt_home_hero_desc' => 'El símbolo de prestigio en el Bajío, ha sido un escenario de momentos extraordinarios. En la zona dorada de León, nuestros espacios han sido testigos de grandes celebraciones. Aquí, tu historia es parte de nuestra historia.',
        'casa_opt_nosotros_hero_img' => get_template_directory_uri() . '/assets/images/salon_principal_1779523069698.png',
        'casa_opt_nosotros_title' => 'Nuestra Historia',
        'casa_opt_nosotros_subtitle' => 'Donde el pasado y el presente se encuentran.',
        'casa_opt_nosotros_desc' => '<p style="margin-bottom: 1.5rem;">Desde 1845, sus muros de cantera han sido testigos de amor y celebración.</p><p>Hoy, en el corazón dorado de la ciudad, cada rincón invita a vivir experiencias únicas, donde la sofisticación se fusiona con la tradición, creando un espacio solo para los más exigentes.</p>',
        'casa_opt_contacto_title' => 'Contacto',
        'casa_opt_contacto_desc' => 'Comunícate con nosotros para agendar tu evento.',
        'casa_opt_contacto_map' => '',
        'casa_opt_nosotros_portada' => get_template_directory_uri() . '/assets/images/salon_principal_1779523069698.png',
        'casa_opt_espacios_portada' => get_template_directory_uri() . '/assets/images/jardin_principal_1779523113451.png',
        'casa_opt_restaurantes_portada' => get_template_directory_uri() . '/assets/images/terraza_mezquite_1779523084857.png',
        'casa_opt_eventos_portada' => get_template_directory_uri() . '/assets/images/salon_pavorreales_1779523097528.png',
        'casa_opt_contacto_portada' => get_template_directory_uri() . '/assets/images/jardin_principal_1779523113451.png',
        'casa_opt_status_nosotros' => '1',
        'casa_opt_status_espacios' => '1',
        'casa_opt_status_restaurantes' => '1',
        'casa_opt_status_galeria' => '1',
        'casa_opt_status_eventos' => '1',
        'casa_opt_status_contacto' => '1',
    );

    foreach ($panel_defaults as $key => $value) {
        // Solo añade la opción si no existe, para no sobrescribir configuraciones del usuario
        add_option($key, $value);
    }

    update_option('casadepiedra_fully_populated_v10_clean_6restaurantes', true);
    file_put_contents($log_file, "Finished setup successfully.\n", FILE_APPEND);
}
add_action('init', 'casadepiedra_plug_and_play_setup');
