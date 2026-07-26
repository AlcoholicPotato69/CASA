<?php
/**
 * Panel de Opciones Global (Panel Casa de Piedra)
 */

// 1. Hook para guardar las opciones genéricamente
function casa_save_global_options() {
    if (!current_user_can('manage_options') || !isset($_POST['casa_options_nonce']) || !wp_verify_nonce($_POST['casa_options_nonce'], 'casa_save_options')) {
        wp_die('No tienes permisos o la sesión ha expirado.');
    }

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'casa_opt_') === 0) {
            if (is_array($value)) {
                $clean_arr = array();
                foreach ($value as $k => $v) {
                    $clean_arr[sanitize_key($k)] = sanitize_text_field(stripslashes((string)$v));
                }
                update_option($key, $clean_arr);
            } else {
                update_option($key, wp_kses_post(stripslashes($value)));
            }
        }
    }

    wp_redirect(add_query_arg('updated', 'true', wp_get_referer()));
    exit;
}
add_action('admin_post_casa_save_options', 'casa_save_global_options');

// 2. Registrar Menús
function casa_register_admin_panel() {
    add_menu_page(
        'Panel Casa',          
        'Panel Casa',          
        'manage_options',        
        'casa-panel',           
        'casa_admin_page_global', 
        'dashicons-admin-home',
        '2.1'
    );

    add_submenu_page('casa-panel', 'Globales', 'Globales', 'manage_options', 'casa-panel', 'casa_admin_page_global');
    add_submenu_page('casa-panel', 'Inicio', 'Inicio', 'manage_options', 'casa-panel-inicio', 'casa_admin_page_inicio');
    add_submenu_page('casa-panel', 'Restaurantes', 'Restaurantes', 'manage_options', 'casa-panel-restaurantes', 'casa_admin_page_restaurantes');
    add_submenu_page('casa-panel', 'Espacios', 'Espacios', 'manage_options', 'casa-panel-espacios', 'casa_admin_page_espacios');
    add_submenu_page('casa-panel', 'Eventos', 'Eventos', 'manage_options', 'casa-panel-eventos', 'casa_admin_page_eventos');
    add_submenu_page('casa-panel', 'Galería', 'Galería', 'manage_options', 'casa-panel-galeria', 'casa_admin_page_galeria');
    add_submenu_page('casa-panel', 'Mails', 'Mails (Correos)', 'manage_options', 'casa-panel-mails', 'casa_admin_page_mails');
}
add_action('admin_menu', 'casa_register_admin_panel');

// 3. Funciones Helper
function casa_render_admin_header($title) {
    echo '<style>
        .casa-rest-admin-wrap {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            color: #0f172a;
        }
        .casa-rest-header-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            border-radius: 16px;
            padding: 24px 30px;
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);
            border: 1px solid rgba(197, 160, 89, 0.25);
        }
        .casa-rest-header-info h2 {
            color: #ffffff !important;
            margin: 0 0 6px 0 !important;
            font-size: 22px !important;
            font-weight: 700;
            border: none !important;
            padding: 0 !important;
        }
        .casa-rest-header-info p {
            color: #94a3b8;
            margin: 0;
            font-size: 13.5px;
        }
        .casa-btn-gold {
            background: linear-gradient(135deg, #c5a059 0%, #9e7d3b 100%);
            color: #ffffff !important;
            padding: 11px 22px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(197, 160, 89, 0.35);
            transition: all 0.25s ease;
        }
        .casa-btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(197, 160, 89, 0.5);
        }
        .casa-rest-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 30px;
        }
        .casa-rest-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .casa-rest-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }
        .casa-rest-card-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .casa-rest-logo-box {
            width: 76px;
            height: 76px;
            background: #0f172a;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
            padding: 6px;
        }
        .casa-rest-logo-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .casa-rest-logo-box span {
            color: #c5a059;
            font-weight: 800;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .casa-rest-content {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .casa-rest-tag {
            font-size: 10px;
            font-weight: 800;
            color: #c5a059;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .casa-rest-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .casa-rest-title {
            margin: 0 !important;
            font-size: 18px !important;
            font-weight: 700;
            color: #0f172a !important;
        }
        .casa-rest-rating {
            background: #fef9c3;
            color: #854d0e;
            font-size: 12px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
        }
        .casa-rest-meta {
            margin: 2px 0 0 0 !important;
            font-size: 12.5px;
            color: #64748b;
        }
        .casa-rest-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .casa-btn-dark {
            background: #0f172a;
            color: #ffffff !important;
            padding: 9px 16px;
            border-radius: 24px;
            font-weight: 600;
            font-size: 12px;
            text-decoration: none;
            transition: background 0.2s ease;
        }
        .casa-btn-dark:hover {
            background: #1e293b;
        }
        .casa-btn-edit-gold {
            background: linear-gradient(135deg, #c5a059 0%, #9e7d3b 100%);
            color: #ffffff !important;
            padding: 9px 18px;
            border-radius: 24px;
            font-weight: 700;
            font-size: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 3px 10px rgba(197, 160, 89, 0.3);
            transition: all 0.2s ease;
        }
        .casa-btn-edit-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(197, 160, 89, 0.45);
        }
        .casa-btn-trash {
            background: #fee2e2;
            color: #dc2626 !important;
            border: 1px solid #fca5a5;
            padding: 9px 13px;
            border-radius: 24px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .casa-btn-trash:hover {
            background: #ef4444;
            color: #ffffff !important;
            border-color: #ef4444;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }
        .casa-section-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px 28px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
    </style>';

    echo '<div class="wrap" style="max-width:1050px; background:#f8fafc; padding:28px 34px; border-radius:16px; box-shadow:0 4px 18px rgba(0,0,0,0.06); margin-top:20px;">';
    echo '<h1 style="border-bottom:2px solid #d4af37; padding-bottom:12px; margin-bottom:25px; color:#111; font-size:1.85rem;">' . esc_html($title) . '</h1>';
    if (isset($_GET['updated']) && $_GET['updated'] == 'true') {
        echo '<div class="updated notice is-dismissible" style="margin-bottom:20px;"><p><strong>Ajustes guardados correctamente.</strong></p></div>';
    }
    echo '<form method="POST" action="' . esc_url(admin_url('admin-post.php')) . '">';
    echo '<input type="hidden" name="action" value="casa_save_options">';
    wp_nonce_field('casa_save_options', 'casa_options_nonce');
}

function casa_render_admin_footer() {
    echo '<p class="submit" style="margin-top:30px;"><input type="submit" name="submit" id="submit" class="button button-primary button-large" value="Guardar Cambios" style="background:#111; border-color:#111; color:#d4af37; font-weight:600; padding:6px 24px;"></p>';
    echo '</form></div>';
}

function casa_opt_text($label, $key, $is_textarea = false) {
    $value = get_option($key, '');
    echo '<div style="margin-bottom:18px;"><label style="font-weight:600; display:block; margin-bottom:6px; color:#2c3338;">' . esc_html($label) . '</label>';
    if ($is_textarea) {
        echo '<textarea name="'.esc_attr($key).'" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;" rows="4">'.esc_textarea($value).'</textarea>';
    } else {
        echo '<input type="text" name="'.esc_attr($key).'" value="'.esc_attr($value).'" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;">';
    }
    echo '</div>';
}

function casa_opt_editor($label, $key) {
    $value = get_option($key, '');
    echo '<div style="margin-bottom:18px;">';
    echo '<label style="font-weight:600; display:block; margin-bottom:10px; color:#2c3338;">' . esc_html($label) . '</label>';
    wp_editor(stripslashes($value), $key, array('textarea_name' => $key, 'textarea_rows' => 10, 'media_buttons' => true));
    echo '</div>';
}

function casa_opt_image($label, $key) {
    $value = get_option($key, '');
    echo '<div style="margin-bottom:18px; padding:15px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px;">';
    echo '<label style="font-weight:600; display:block; margin-bottom:8px; color:#1f2937;">' . esc_html($label) . '</label>';
    echo '<input type="text" id="'.esc_attr($key).'" name="'.esc_attr($key).'" value="'.esc_attr($value).'" style="width:68%; border:1px solid #c3c4c7; border-radius:6px; padding:6px 10px;" /> ';
    echo '<input type="button" class="button casadepiedra_upload_image_btn" data-target="'.esc_attr($key).'" data-preview="preview_'.esc_attr($key).'" value="Subir / Seleccionar" /> ';
    echo '<input type="button" class="button casadepiedra_clear_image_btn" data-target="'.esc_attr($key).'" data-preview="preview_'.esc_attr($key).'" value="Quitar" />';
    echo '<div id="preview_'.esc_attr($key).'"><img src="'.esc_url($value).'" style="max-height:85px; display:'.($value ? 'block' : 'none').'; margin-top:12px; border-radius:6px; border:1px solid #ddd;"/></div>';
    echo '</div>';
}

function casa_opt_checkbox($label, $key) {
    $value = get_option($key, '1');
    echo '<div style="margin-bottom:15px; display:flex; align-items:center; gap:10px;">';
    echo '<input type="hidden" name="'.esc_attr($key).'" value="0">';
    echo '<input type="checkbox" name="'.esc_attr($key).'" value="1" ' . checked($value, '1', false) . ' style="margin:0; width:18px; height:18px;">';
    echo '<label style="font-weight:600; cursor:pointer; color:#2c3338;">' . esc_html($label) . '</label>';
    echo '</div>';
}

/* ================= PAGINAS DE OPCIONES ================= */

// GLOBAL: Aspectos Globales del Sitio + Control Central de Headers
function casa_admin_page_global() {
    casa_render_admin_header('Panel Casa: Ajustes Globales y Control de Headers del Sitio');
    
    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">1. Logotipo y Branding Global</h2>';
    echo '<p class="description" style="margin-bottom:16px; color:#555;">Controla los elementos de identidad visual generales del sitio en el encabezado superior (Navbar) y transiciones.</p>';
    casa_opt_image('Logotipo Principal (Header / Navbar)', 'casa_opt_global_logo');
    casa_opt_image('Logo para Transición (Cortinilla Negra entre páginas)', 'casa_opt_global_transition_logo');
    casa_opt_image('Imagen de Fondo para el Footer (Opcional - si se deja vacío queda en negro)', 'casa_opt_footer_bg_image');
    echo '</div>';

    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">2. Control Centralizado de Headers / Portadas de los Sitios</h2>';
    echo '<p class="description" style="margin-bottom:18px; color:#555;">Administra centralizadamente desde este panel global las imágenes de portada (Headers superiores), títulos, subtítulos y descripciones de absolutamente todas las páginas de tu sitio:</p>';
    
    echo '<div style="display:grid; grid-template-columns:1fr; gap:18px;">';
    
    // 1. INICIO
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #d4af37; padding-left:10px;">Header Página: Inicio (Hero Principal)</h3>';
    casa_opt_image('Imagen de Portada "Inicio"', 'casa_opt_home_hero_img');
    casa_opt_text('Subtítulo Superior (Ej. Desde 1845)', 'casa_opt_home_hero_subtitle');
    casa_opt_text('Título Principal H1', 'casa_opt_home_hero_title', true);
    casa_opt_text('Descripción Hero', 'casa_opt_home_hero_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 2. ESPACIOS
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #d4af37; padding-left:10px;">Header Página: Espacios</h3>';
    casa_opt_image('Imagen de Portada "Espacios"', 'casa_opt_espacios_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Exclusividad)', 'casa_opt_global_espacios_subtitle');
    casa_opt_text('Título H1 "Espacios"', 'casa_opt_global_espacios_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_global_espacios_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 3. RESTAURANTES
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #d4af37; padding-left:10px;">Header Página: Restaurantes</h3>';
    casa_opt_image('Imagen de Portada "Restaurantes"', 'casa_opt_restaurantes_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Alta cocina)', 'casa_opt_global_restaurantes_subtitle');
    casa_opt_text('Título H1 "Restaurantes"', 'casa_opt_global_restaurantes_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_global_restaurantes_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 4. EVENTOS
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #d4af37; padding-left:10px;">Header Página: Eventos</h3>';
    casa_opt_image('Imagen de Portada "Eventos"', 'casa_opt_eventos_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Celebraciones)', 'casa_opt_global_eventos_subtitle');
    casa_opt_text('Título H1 "Eventos"', 'casa_opt_global_eventos_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_global_eventos_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 5. GALERÍA
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #d4af37; padding-left:10px;">Header Página: Galería</h3>';
    casa_opt_image('Imagen de Portada "Galería"', 'casa_opt_galeria_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Nuestra Esencia)', 'casa_opt_galeria_subtitle');
    casa_opt_text('Título H1 "Galería"', 'casa_opt_galeria_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_galeria_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 6. CONTACTO
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #d4af37; padding-left:10px;">Header Página: Contacto</h3>';
    casa_opt_image('Imagen de Portada "Contacto"', 'casa_opt_contacto_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Atención Exclusiva)', 'casa_opt_contacto_subtitle');
    casa_opt_text('Título H1 "Contacto"', 'casa_opt_contacto_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_contacto_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 7. QUIÉNES SOMOS
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #d4af37; padding-left:10px;">Header Página: Quiénes Somos (Historia y Legado)</h3>';
    casa_opt_image('Imagen de Portada "Quiénes Somos"', 'casa_opt_nosotros_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Desde 1845)', 'casa_opt_nosotros_subtitle');
    casa_opt_text('Título H1 "Quiénes Somos"', 'casa_opt_nosotros_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_nosotros_header_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 8. FONDO FOOTER
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #d4af37; padding-left:10px;">Fondo del Pie de Página (Footer Banner opcional)</h3>';
    casa_opt_image('Imagen de Fondo para el Footer (Si no se elige, se mantiene fondo negro #080808)', 'casa_opt_footer_bg_image');
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">3. Datos de Ubicación y Horarios Globales</h2>';
    casa_opt_text('Dirección de las instalaciones', 'casa_opt_global_address', true);
    casa_opt_text('Teléfono Oficial', 'casa_opt_global_phone');
    casa_opt_text('Horario de Atención (mostrado en Modal y Contacto)', 'casa_opt_global_office_hours');
    echo '</div>';

    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">4. Redes Sociales Oficiales</h2>';
    casa_opt_text('URL de Facebook', 'casa_opt_global_facebook');
    casa_opt_text('URL de Instagram', 'casa_opt_global_instagram');
    casa_opt_text('URL de TikTok', 'casa_opt_global_tiktok');
    echo '</div>';

    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">5. Estado de Secciones en Navegación (Activar / Desactivar)</h2>';
    echo '<p class="description" style="margin-bottom: 20px; color:#555;">Activa o desactiva la visibilidad de las secciones en los menús principales del sitio web:</p>';
    casa_opt_checkbox('Activar "Espacios"', 'casa_opt_status_espacios');
    casa_opt_checkbox('Activar "Restaurantes"', 'casa_opt_status_restaurantes');
    casa_opt_checkbox('Activar "Galería"', 'casa_opt_status_galeria');
    casa_opt_checkbox('Activar "Eventos"', 'casa_opt_status_eventos');
    casa_opt_checkbox('Activar "Contacto"', 'casa_opt_status_contacto');
    echo '</div>';

    casa_render_admin_footer();
}

// INICIO
function casa_admin_page_inicio() {
    casa_render_admin_header('Panel Casa: Página de Inicio (Textos, Historia y Reseñas)');
    
    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:20px; margin-bottom:30px;">';
    echo '<h2>1. Sección Historia y Legado (Presentación en Inicio)</h2>';
    casa_opt_editor('Descripción / Texto principal del recinto en Inicio', 'casa_opt_nosotros_desc');
    casa_opt_image('Imagen de Portada "Historia y Legado"', 'casa_opt_nosotros_hero_img');
    casa_opt_text('URL del Recorrido Virtual 3D (Iframe)', 'casa_opt_nosotros_virtual_tour');
    echo '</div>';

    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:20px; margin-bottom:30px;">';
    echo '<h2>2. Sección de Reseñas Verificadas de Google Maps</h2>';
    casa_opt_text('Subtítulo (Ej. Experiencias Inolvidables)', 'casa_opt_home_reviews_subtitle');
    casa_opt_text('Título de Sección (Ej. Lo que dicen nuestros visitantes)', 'casa_opt_home_reviews_title');
    casa_opt_text('Enlace Oficial a Reseñas en Google Maps', 'casa_opt_google_maps_link');

    echo '<h3>Reseña Destacada 1</h3>';
    casa_opt_text('Texto de la Reseña 1', 'casa_opt_home_rev1_text', true);
    casa_opt_text('Autor / Detalle de la Reseña 1', 'casa_opt_home_rev1_author');
    casa_opt_text('URL directa en Google Maps (Reseña 1)', 'casa_opt_home_rev1_url');

    echo '<h3>Reseña Destacada 2</h3>';
    casa_opt_text('Texto de la Reseña 2', 'casa_opt_home_rev2_text', true);
    casa_opt_text('Autor / Detalle de la Reseña 2', 'casa_opt_home_rev2_author');
    casa_opt_text('URL directa en Google Maps (Reseña 2)', 'casa_opt_home_rev2_url');

    echo '<h3>Reseña Destacada 3</h3>';
    casa_opt_text('Texto de la Reseña 3', 'casa_opt_home_rev3_text', true);
    casa_opt_text('Autor / Detalle de la Reseña 3', 'casa_opt_home_rev3_author');
    casa_opt_text('URL directa en Google Maps (Reseña 3)', 'casa_opt_home_rev3_url');
    echo '</div>';

    casa_render_admin_footer();
}

// ESPACIOS (RESEÑAS Y SALONES INDIVIDUALES)
function casa_admin_page_espacios() {
    casa_render_admin_header('Panel Casa: Sección de Espacios (Catálogo y Salones)');
    
    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">1. Reseñas Verificadas de Google Maps (Sección Espacios)</h2>';
    casa_opt_text('URL directa de Reseña Google 1 (Espacios)', 'casa_opt_espacios_rev1_url');
    casa_opt_text('URL directa de Reseña Google 2 (Espacios)', 'casa_opt_espacios_rev2_url');
    casa_opt_text('URL directa de Reseña Google 3 (Espacios)', 'casa_opt_espacios_rev3_url');
    echo '</div>';

    echo '<div class="casa-rest-admin-wrap" style="margin-bottom:35px;">';
    echo '<div class="casa-rest-header-card">';
    echo '<div class="casa-rest-header-info"><h2>🏛️ Directorio Ejecutivo de Salones & Jardines</h2><p>Administra capacidades, planos arquitectónicos PDF y portadas de cada espacio de Casa de Piedra.</p></div>';
    echo '<div><a href="' . admin_url('post-new.php?post_type=espacios') . '" class="casa-btn-gold"><span>+ Añadir Nuevo Salón</span></a></div>';
    echo '</div>';

    $espacios = get_posts(array('post_type' => 'espacios', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'));
    echo '<div class="casa-rest-grid">';
    if (!empty($espacios)) {
        foreach ($espacios as $esp) {
            $portada = get_post_meta($esp->ID, '_espacio_portada', true);
            if (!$portada) $portada = get_the_post_thumbnail_url($esp->ID, 'medium');
            if (!$portada) $portada = get_template_directory_uri() . '/assets/images/salon_principal_1779523069698.png';
            $cap = get_post_meta($esp->ID, '_espacio_capacidad', true);
            $plano = get_post_meta($esp->ID, '_espacio_plano_pdf', true);
            $edit_url = get_edit_post_link($esp->ID);
            ?>
            <div class="casa-rest-card">
                <div class="casa-rest-card-left">
                    <div class="casa-rest-logo-box" style="width:110px; height:74px; border-radius:10px;">
                        <img src="<?php echo esc_url($portada); ?>" alt="Portada" style="object-fit:cover; padding:0; width:100%; height:100%;" />
                    </div>
                    <div class="casa-rest-content">
                        <span class="casa-rest-tag">SALÓN INSTITUCIONAL CASA DE PIEDRA</span>
                        <div class="casa-rest-title-row">
                            <h3 class="casa-rest-title"><?php echo esc_html($esp->post_title); ?></h3>
                            <span class="casa-rest-rating">👥 <?php echo esc_html($cap ? 'Hasta ' . $cap . ' Pax' : 'A medida'); ?></span>
                        </div>
                        <p class="casa-rest-meta">Arquitectura histórica • Montaje versátil para bodas y convenciones</p>
                    </div>
                </div>
                <div class="casa-rest-actions">
                    <a href="<?php echo esc_url(get_permalink($esp->ID)); ?>" target="_blank" class="casa-btn-dark" style="background:#334155;">👁️ VER PÁGINA DEDICADA</a>
                    <?php if ($plano) : ?>
                        <a href="<?php echo esc_url($plano); ?>" target="_blank" class="casa-btn-dark">📥 VER PLANO PDF</a>
                    <?php else : ?>
                        <a href="<?php echo esc_url($edit_url); ?>" class="casa-btn-dark">📥 SUBIR PLANO PDF</a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url($edit_url); ?>" class="casa-btn-edit-gold">EDITAR SALÓN COMPLETO →</a>
                    <?php $del_url = get_delete_post_link($esp->ID); if ($del_url) : ?>
                        <a href="<?php echo esc_url($del_url); ?>" class="casa-btn-trash" onclick="return confirm('¿Estás seguro de mover este salón a la papelera?');" title="Mover a papelera">🗑️</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<p style="padding:20px; color:#64748b;">No hay salones registrados actualmente.</p>';
    }
    echo '</div></div>';

    casa_render_admin_footer();
}

// RESTAURANTES (DIRECTORIO UI/UX HIGH-END & RESEÑAS VERIFICADAS)
function casa_admin_page_restaurantes() {
    casa_render_admin_header('Panel Casa: Alta Gastronomía & Directorio de Restaurantes');
    ?>

    <div class="casa-rest-admin-wrap">
        <!-- HEADER EJECUTIVO DE ALTA GAMA -->
        <div class="casa-rest-header-card">
            <div class="casa-rest-header-info">
                <h2>🍽️ Centro de Gestión Gastronómica & Directorio</h2>
                <p>Administra con máxima elegancia visual cada restaurante, sus especialidades, cartas PDF y reservaciones.</p>
            </div>
            <div>
                <a href="<?php echo admin_url('post-new.php?post_type=restaurantes'); ?>" class="casa-btn-gold">
                    <span>+ Añadir Nuevo Restaurante</span>
                </a>
            </div>
        </div>

        <!-- TARJETAS VISUALES DE RESTAURANTES (ESTILO HIGH-END UI/UX) -->
        <div class="casa-rest-grid">
            <?php
            $restaurantes = get_posts(array('post_type' => 'restaurantes', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'));
            if (!empty($restaurantes)) :
                foreach ($restaurantes as $r) :
                    $subtitulo = get_post_meta($r->ID, '_restaurante_cocina', true);
                    if (!$subtitulo) $subtitulo = get_post_meta($r->ID, '_casa_rest_subtitulo', true);
                    if (!$subtitulo) $subtitulo = 'ALTA COCINA DE AUTOR & EXPERIENCIA SENSORIAL';

                    $calificacion = get_post_meta($r->ID, '_restaurante_calificacion', true);
                    if (!$calificacion) $calificacion = get_post_meta($r->ID, '_casa_rest_calificacion', true);
                    if (!$calificacion) $calificacion = '4.9';

                    $telefono = get_post_meta($r->ID, '_restaurante_telefono', true);
                    $horario = get_post_meta($r->ID, '_casa_rest_horario', true);
                    if (!$horario) {
                        $horario = ($telefono ? 'Teléfono Directo: ' . $telefono . ' • ' : '') . 'Horario de atención • Ubicación exclusiva en Casa de Piedra';
                    }

                    $logo = get_post_meta($r->ID, '_restaurante_logo', true);
                    if (!$logo) $logo = get_post_meta($r->ID, '_casa_rest_logo', true);

                    $menu_pdf = get_post_meta($r->ID, '_restaurante_menu', true);
                    if (!$menu_pdf) $menu_pdf = get_post_meta($r->ID, '_casa_rest_menu_pdf', true);

                    $edit_url = get_edit_post_link($r->ID);
            ?>
                    <div class="casa-rest-card">
                        <div class="casa-rest-card-left">
                            <div class="casa-rest-logo-box">
                                <?php if ($logo) : ?>
                                    <img src="<?php echo esc_url($logo); ?>" alt="Logo" />
                                <?php else : ?>
                                    <span>LOGO</span>
                                <?php endif; ?>
                            </div>
                            <div class="casa-rest-content">
                                <span class="casa-rest-tag">ESPECIALIDAD: <?php echo esc_html(strtoupper($subtitulo)); ?></span>
                                <div class="casa-rest-title-row">
                                    <h3 class="casa-rest-title"><?php echo esc_html($r->post_title); ?></h3>
                                    <span class="casa-rest-rating"><?php echo esc_html($calificacion); ?> ⭐</span>
                                </div>
                                <p class="casa-rest-meta"><?php echo esc_html($horario); ?></p>
                            </div>
                        </div>
                        <div class="casa-rest-actions">
                            <a href="<?php echo esc_url(get_permalink($r->ID)); ?>" target="_blank" class="casa-btn-dark" style="background:#334155;">👁️ VER PÁGINA DEDICADA</a>
                            <?php if ($menu_pdf) : ?>
                                <a href="<?php echo esc_url($menu_pdf); ?>" target="_blank" class="casa-btn-dark">📄 VER MENÚ PDF</a>
                            <?php else : ?>
                                <a href="<?php echo esc_url($edit_url); ?>" class="casa-btn-dark">📄 SUBIR MENÚ PDF</a>
                            <?php endif; ?>
                            <a href="<?php echo esc_url($edit_url); ?>" class="casa-btn-edit-gold">EDITAR FICHA →</a>
                            <?php $del_url = get_delete_post_link($r->ID); if ($del_url) : ?>
                                <a href="<?php echo esc_url($del_url); ?>" class="casa-btn-trash" onclick="return confirm('¿Estás seguro de mover este restaurante a la papelera?');" title="Mover a papelera">🗑️</a>
                            <?php endif; ?>
                        </div>
                    </div>
            <?php
                endforeach;
            else :
            ?>
                <!-- TARJETA MUESTRA VISUAL (EJEMPLO HIGH-END UI/UX SI NO HAY REGISTROS) -->
                <div class="casa-rest-card">
                    <div class="casa-rest-card-left">
                        <div class="casa-rest-logo-box">
                            <span>LOGO</span>
                        </div>
                        <div class="casa-rest-content">
                            <span class="casa-rest-tag">ESPECIALIDAD: ALTA COCINA JAPONESA & NIKKEI</span>
                            <div class="casa-rest-title-row">
                                <h3 class="casa-rest-title">Restaurante Sato → Calificación: 4.9</h3>
                                <span class="casa-rest-rating">4.9 ⭐</span>
                            </div>
                            <p class="casa-rest-meta">Horario de atención • Ubicación dentro del recinto • Ambiente exclusivo</p>
                        </div>
                    </div>
                    <div class="casa-rest-actions">
                        <a href="<?php echo admin_url('post-new.php?post_type=restaurantes'); ?>" class="casa-btn-dark">📄 VER MENÚ PDF</a>
                        <a href="<?php echo admin_url('post-new.php?post_type=restaurantes'); ?>" class="casa-btn-edit-gold">RESERVAR / CONFIGURAR →</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- RESEÑAS VERIFICADAS DE GOOGLE MAPS -->
        <div class="casa-section-box">
            <h3 style="margin-top:0; color:#0f172a; font-size:18px; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:20px;">
                ⭐ Enlaces de Reseñas Verificadas de Google Maps
            </h3>
            <?php
            casa_opt_text('URL directa de Reseña Google 1 (Restaurantes)', 'casa_opt_rest_rev1_url');
            casa_opt_text('URL directa de Reseña Google 2 (Restaurantes)', 'casa_opt_rest_rev2_url');
            casa_opt_text('URL directa de Reseña Google 3 (Restaurantes)', 'casa_opt_rest_rev3_url');
            ?>
        </div>
    </div>
    <?php
    casa_render_admin_footer();
}

// EVENTOS (DIRECTORIO UI/UX Y GESTIÓN INDIVIDUAL)
function casa_admin_page_eventos() {
    casa_render_admin_header('Panel Casa: Centro de Gestión de Eventos & Celebraciones');
    ?>
    <div class="casa-rest-admin-wrap" style="margin-bottom:35px;">
        <div class="casa-rest-header-card">
            <div class="casa-rest-header-info">
                <h2>🎉 Gestión de Tipos de Eventos & Bodas</h2>
                <p>Configura las fichas informativas, montajes y páginas dedicadas de cada tipología de evento en Casa de Piedra.</p>
            </div>
            <div>
                <a href="<?php echo admin_url('post-new.php?post_type=eventos'); ?>" class="casa-btn-gold">
                    <span>+ Añadir Nuevo Tipo de Evento</span>
                </a>
            </div>
        </div>

        <div class="casa-rest-grid">
            <?php
            $eventos = get_posts(array('post_type' => 'eventos', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC'));
            if (!empty($eventos)) {
                foreach ($eventos as $ev) {
                    $portada = get_post_meta($ev->ID, '_evento_portada', true);
                    if (!$portada) $portada = get_the_post_thumbnail_url($ev->ID, 'medium');
                    if (!$portada) $portada = get_template_directory_uri() . '/assets/images/salon_pavorreales_1779523097528.png';
                    $edit_url = get_edit_post_link($ev->ID);
                    ?>
                    <div class="casa-rest-card">
                        <div class="casa-rest-card-left">
                            <div class="casa-rest-logo-box" style="width:110px; height:74px; border-radius:10px;">
                                <img src="<?php echo esc_url($portada); ?>" alt="Portada" style="object-fit:cover; padding:0; width:100%; height:100%;" />
                            </div>
                            <div class="casa-rest-content">
                                <span class="casa-rest-tag">TIPOLOGÍA DE EVENTO EXCLUSIVO</span>
                                <div class="casa-rest-title-row">
                                    <h3 class="casa-rest-title"><?php echo esc_html($ev->post_title); ?></h3>
                                    <span class="casa-rest-rating">✨ Personalizable</span>
                                </div>
                                <p class="casa-rest-meta">Coordinación integral • Gastronomía de autor • Montaje arquitectónico</p>
                            </div>
                        </div>
                        <div class="casa-rest-actions">
                            <a href="<?php echo esc_url(get_permalink($ev->ID)); ?>" target="_blank" class="casa-btn-dark" style="background:#334155;">👁️ VER PÁGINA DEDICADA</a>
                            <a href="<?php echo esc_url($edit_url); ?>" class="casa-btn-edit-gold">EDITAR EVENTO →</a>
                            <?php $del_url = get_delete_post_link($ev->ID); if ($del_url) : ?>
                                <a href="<?php echo esc_url($del_url); ?>" class="casa-btn-trash" onclick="return confirm('¿Estás seguro de mover este evento a la papelera?');" title="Mover a papelera">🗑️</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p style="padding:20px; color:#64748b;">No hay eventos registrados actualmente.</p>';
            }
            ?>
        </div>
    </div>
    <?php
    casa_render_admin_footer();
}

function casa_admin_page_mails() {
    casa_render_admin_header('Panel Casa: Sección de Correos Electrónicos (Mails)');
    
    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">1. Correos Institucionales Receptores</h2>';
    echo '<p style="color:#555; margin-bottom:20px;">Configura todas las direcciones de correo electrónico donde llegarán las solicitudes y cotizaciones de cada sección del sitio web.</p>';
    casa_opt_text('Correo General / por Defecto', 'casa_opt_global_email');
    casa_opt_text('Correo para Cotizaciones de Espacios / Eventos', 'casa_opt_email_cotizacion');
    casa_opt_text('Correo para Temas Generales', 'casa_opt_email_generales');
    casa_opt_text('Correo para Propuestas de Proveedores', 'casa_opt_email_proveedores');
    casa_opt_text('Correo para Propuestas de Eventos', 'casa_opt_email_propuestas_eventos');
    casa_opt_text('Correo Receptor de Cotizaciones Adicional (Puedes separar varios con coma)', 'casa_opt_mail_receiver');
    echo '</div>';

    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">2. Branding Institucional para Plantillas de Correo</h2>';
    casa_opt_image('Logotipo exclusivo para Plantillas de Correo HTML', 'casa_opt_mail_logo');
    echo '</div>';

    casa_render_admin_footer();
}

// GALERÍA POR ETIQUETAS E IMÁGENES ESTELARES (UI/UX EJECUTIVO)
function casa_admin_page_galeria() {
    casa_render_admin_header('Panel Casa: Gestión Visual de Galería por Etiquetas');
    ?>
    <div class="casa-rest-admin-wrap" style="margin-bottom:35px;">
        <div class="casa-rest-header-card">
            <div class="casa-rest-header-info">
                <h2>📸 Galería Inteligente por Etiquetas y Formato Estelar</h2>
                <p>Administra las categorías de la galería (Bodas, Eventos, Arquitectura, Gastronomía) y sube fotografías directamente a cada etiqueta.</p>
            </div>
            <div>
                <a href="#etiquetas-galeria" class="casa-btn-gold">
                    <span>🏷️ Configurar Etiquetas</span>
                </a>
            </div>
        </div>

        <?php
        $etiquetas_raw = get_option('casa_opt_galeria_etiquetas', 'Bodas, Eventos Sociales, Convenciones, Arquitectura & Gastronomía');
        $etiquetas = array_map('trim', explode(',', $etiquetas_raw));
        $tamanos = get_option('casa_opt_galeria_etiqueta_tamanos', array());
        if (!is_array($tamanos)) $tamanos = array();
        ?>

        <div class="casa-section-box" id="etiquetas-galeria" style="margin-bottom:30px;">
            <h3 style="margin-top:0; color:#0f172a; font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
                🏷️ 1. Definir Categorías / Etiquetas Activas en la Galería
            </h3>
            <p style="color:#64748b; font-size:13px; margin-bottom:14px;">
                Escribe las etiquetas separadas por comas. Estas etiquetas generarán botones de filtrado interactivos en la página de Galería:
            </p>
            <?php casa_opt_text('Etiquetas Activas', 'casa_opt_galeria_etiquetas'); ?>
        </div>

        <div class="casa-section-box" style="margin-bottom:30px;">
            <h3 style="margin-top:0; color:#0f172a; font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
                🖼️ 2. Subir Fotografías y Asignación Directa a Etiquetas
            </h3>
            <p style="color:#64748b; font-size:13px; margin-bottom:18px;">
                Selecciona las fotografías para tu galería general o haz clic en cargar dentro de cada sección de etiqueta. Para destacar una fotografía en tamaño doble (2x2), escribe la palabra <strong>estelar</strong> en el campo <em>Leyenda</em> o <em>Descripción</em> dentro de la biblioteca de WordPress.
            </p>

            <div class="casa-rest-grid">
                <?php foreach ($etiquetas as $etiqueta) : 
                    if (empty($etiqueta)) continue;
                    $slug = sanitize_title($etiqueta);
                    $selected_tamano = isset($tamanos[$slug]) ? $tamanos[$slug] : 'grande';
                ?>
                    <div class="casa-rest-card">
                        <div class="casa-rest-card-left">
                            <div class="casa-rest-logo-box" style="width:55px; height:55px; background:#0f172a; color:#c5a059; font-size:22px; display:flex; align-items:center; justify-content:center; border-radius:10px;">
                                🏷️
                            </div>
                            <div class="casa-rest-content">
                                <span class="casa-rest-tag">ETIQUETA DE GALERÍA ACTIVA</span>
                                <div class="casa-rest-title-row">
                                    <h3 class="casa-rest-title"><?php echo esc_html($etiqueta); ?></h3>
                                </div>
                                <p class="casa-rest-meta" style="margin-bottom:8px;">Filtrado dinámico en tiempo real • Resolución recomendada: 1200×800 px</p>
                                <div style="display:flex; align-items:center; gap:8px; background:#f1f5f9; padding:6px 12px; border-radius:6px; border:1px solid #cbd5e1; width:fit-content;">
                                    <strong style="font-size:12px; color:#334155;">📐 Tamaño de Tarjeta:</strong>
                                    <select name="casa_opt_galeria_etiqueta_tamanos[<?php echo esc_attr($slug); ?>]" style="padding:4px 8px; border-radius:4px; border:1.5px solid #c5a059; font-size:12px; font-weight:700; color:#0f172a; background:#fff; cursor:pointer;">
                                        <option value="chico" <?php selected($selected_tamano, 'chico'); ?>>▪️ Chico (25% Ancho / Cabe 1 Chico + 1 Grande + 1 Chico en una fila)</option>
                                        <option value="mediano" <?php selected($selected_tamano, 'mediano'); ?>>▫️ Mediano (33% Ancho / Caben 3 tarjetas iguales por fila)</option>
                                        <option value="grande" <?php selected($selected_tamano, 'grande'); ?>>⭐ Grande (50% Ancho / Caben 2 Grandes o 1 Grande + 2 Chicos en una fila)</option>
                                        <option value="panoramico" <?php selected($selected_tamano, 'panoramico'); ?>>🌟 Panorámico (100% Ancho / 1 Tarjeta estelar por fila)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="casa-rest-actions">
                            <input type="button" class="casa-btn-edit-gold casadepiedra_upload_tag_btn" data-tag="<?php echo esc_attr($etiqueta); ?>" value="📤 SUBIR FOTOS A «<?php echo esc_attr(strtoupper($etiqueta)); ?>»" style="cursor:pointer; border:none;" />
                            <button type="button" class="casa-btn-trash casadepiedra_remove_tag_btn" data-tag="<?php echo esc_attr($etiqueta); ?>" title="Eliminar Etiqueta">🗑️</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="casa-section-box">
            <h3 style="margin-top:0; color:#0f172a; font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
                🗃️ 3. Biblioteca Central de Galería (Carga Masiva General)
            </h3>
            <?php
            $gallery_ids = get_option('casa_opt_galeria_imagenes', '');
            echo '<div style="margin-bottom:15px; padding: 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius:10px; display:flex; gap:12px; align-items:center;">';
            echo '<input type="button" id="casadepiedra_upload_gallery_btn" class="casa-btn-gold" value="+ Seleccionar / Subir Múltiples Imágenes" style="cursor:pointer; border:none;" /> ';
            echo '<input type="button" id="casadepiedra_clear_gallery_btn" class="casa-btn-dark" value="🗑️ Limpiar Todo" style="cursor:pointer; border:none;" />';
            echo '</div>';
            echo '<input type="hidden" id="casadepiedra_gallery_ids" name="casa_opt_galeria_imagenes" value="' . esc_attr($gallery_ids) . '" />';
            echo '<div id="casadepiedra_gallery_preview" style="display:flex; flex-wrap:wrap; gap:12px; margin-top: 15px;">';
            if (!empty($gallery_ids)) {
                $ids_array = explode(',', $gallery_ids);
                foreach ($ids_array as $id) {
                    $img = wp_get_attachment_image_src($id, 'thumbnail');
                    if ($img) {
                        echo '<div class="casa-gallery-item" data-id="'.esc_attr($id).'" style="display:inline-block; position:relative;"><img src="'.esc_url($img[0]).'" style="width:95px; height:75px; object-fit:cover; display:block; border: 1px solid #cbd5e1; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.05);" /><button type="button" class="casa-remove-single-img-btn" data-id="'.esc_attr($id).'" title="Eliminar foto individual" style="position:absolute; top:-6px; right:-6px; background:#dc2626; color:#fff; border:2px solid #fff; border-radius:50%; width:24px; height:24px; font-size:12px; font-weight:bold; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,0.25); line-height:1;">🗑️</button></div>';
                    }
                }
            }
            echo '</div>';
            ?>
        </div>
    </div>
    <?php
    casa_render_admin_footer();
}

