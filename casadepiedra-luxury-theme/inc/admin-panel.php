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
            if ($key === 'casa_opt_smtp_password') {
                $raw = is_string($value) ? wp_unslash($value) : '';
                if ($raw === '') {
                    continue;
                }
                update_option($key, $raw);
                continue;
            }
            update_option($key, casa_sanitize_opt_value($value));
        }
    }

    if (function_exists('casa_seo_after_options_saved')) {
        casa_seo_after_options_saved();
    }

    $redirect = wp_get_referer();
    if (!empty($_POST['casa_seo_tab'])) {
        $redirect = add_query_arg('tab', sanitize_key(wp_unslash($_POST['casa_seo_tab'])), $redirect);
    }
    wp_redirect(add_query_arg('updated', 'true', $redirect));
    exit;
}

function casa_sanitize_opt_value($value) {
    if (is_array($value)) {
        $out = array();
        foreach ($value as $k => $v) {
            $key = (is_int($k) || (is_string($k) && ctype_digit((string) $k))) ? (int) $k : sanitize_key((string) $k);
            $out[$key] = casa_sanitize_opt_value($v);
        }
        return $out;
    }
    return wp_kses_post(wp_unslash((string) $value));
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
    add_submenu_page('casa-panel', 'SEO', 'SEO', 'manage_options', 'casa-panel-seo', 'casa_admin_page_seo');
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
    echo '<h1 style="border-bottom:2px solid #c1621e; padding-bottom:12px; margin-bottom:25px; color:#111; font-size:1.85rem;">' . esc_html($title) . '</h1>';
    if (isset($_GET['updated']) && $_GET['updated'] == 'true') {
        echo '<div class="updated notice is-dismissible" style="margin-bottom:20px;"><p><strong>Ajustes guardados correctamente.</strong></p></div>';
    }
    echo '<form method="POST" action="' . esc_url(admin_url('admin-post.php')) . '">';
    echo '<input type="hidden" name="action" value="casa_save_options">';
    wp_nonce_field('casa_save_options', 'casa_options_nonce');
}

function casa_render_admin_footer() {
    echo '<p class="submit" style="margin-top:30px;"><input type="submit" name="submit" id="submit" class="button button-primary button-large" value="Guardar Cambios" style="background:#111; border-color:#111; color:#c1621e; font-weight:600; padding:6px 24px;"></p>';
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
    echo '</div>';

    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">2. Control Centralizado de Headers / Portadas de los Sitios</h2>';
    echo '<p class="description" style="margin-bottom:18px; color:#555;">Administra centralizadamente desde este panel global las imágenes de portada (Headers superiores), títulos, subtítulos y descripciones de absolutamente todas las páginas de tu sitio:</p>';
    
    echo '<div style="display:grid; grid-template-columns:1fr; gap:18px;">';
    
    // 1. INICIO
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #c1621e; padding-left:10px;">Header Página: Inicio (Hero Principal)</h3>';
    casa_opt_image('Imagen de Portada "Inicio"', 'casa_opt_home_hero_img');
    casa_opt_text('Subtítulo Superior (Ej. Desde 1845)', 'casa_opt_home_hero_subtitle');
    casa_opt_text('Título Principal H1', 'casa_opt_home_hero_title', true);
    casa_opt_text('Descripción Hero', 'casa_opt_home_hero_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 2. ESPACIOS
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #c1621e; padding-left:10px;">Header Página: Espacios</h3>';
    casa_opt_image('Imagen de Portada "Espacios"', 'casa_opt_espacios_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Exclusividad)', 'casa_opt_global_espacios_subtitle');
    casa_opt_text('Título H1 "Espacios"', 'casa_opt_global_espacios_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_global_espacios_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 3. RESTAURANTES
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #c1621e; padding-left:10px;">Header Página: Restaurantes</h3>';
    casa_opt_image('Imagen de Portada "Restaurantes"', 'casa_opt_restaurantes_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Alta cocina)', 'casa_opt_global_restaurantes_subtitle');
    casa_opt_text('Título H1 "Restaurantes"', 'casa_opt_global_restaurantes_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_global_restaurantes_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 4. EVENTOS
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #c1621e; padding-left:10px;">Header Página: Eventos</h3>';
    casa_opt_image('Imagen de Portada "Eventos"', 'casa_opt_eventos_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Celebraciones)', 'casa_opt_global_eventos_subtitle');
    casa_opt_text('Título H1 "Eventos"', 'casa_opt_global_eventos_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_global_eventos_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 5. GALERÍA
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #c1621e; padding-left:10px;">Header Página: Galería</h3>';
    casa_opt_image('Imagen de Portada "Galería"', 'casa_opt_galeria_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Nuestra Esencia)', 'casa_opt_galeria_subtitle');
    casa_opt_text('Título H1 "Galería"', 'casa_opt_galeria_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_galeria_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 6. CONTACTO
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #c1621e; padding-left:10px;">Header Página: Contacto</h3>';
    casa_opt_image('Imagen de Portada "Contacto"', 'casa_opt_contacto_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Atención Exclusiva)', 'casa_opt_contacto_subtitle');
    casa_opt_text('Título H1 "Contacto"', 'casa_opt_contacto_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_contacto_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 7. QUIÉNES SOMOS
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #c1621e; padding-left:10px;">Header Página: Quiénes Somos (Historia y Legado)</h3>';
    casa_opt_image('Imagen de Portada "Quiénes Somos"', 'casa_opt_nosotros_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Desde 1845)', 'casa_opt_nosotros_subtitle');
    casa_opt_text('Título H1 "Quiénes Somos"', 'casa_opt_nosotros_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_nosotros_header_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 8. AVISO DE PRIVACIDAD
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #c1621e; padding-left:10px;">Header Página: Aviso de Privacidad</h3>';
    casa_opt_image('Imagen de Portada "Aviso de Privacidad"', 'casa_opt_privacidad_portada');
    casa_opt_text('Subtítulo Superior / Etiqueta (Ej. Legal)', 'casa_opt_privacidad_subtitle');
    casa_opt_text('Título H1 "Aviso de Privacidad"', 'casa_opt_privacidad_title');
    casa_opt_text('Descripción / Cita en Portada', 'casa_opt_privacidad_header_desc', true);
    echo '</div>';

    echo '<hr style="border:0; border-top:1px solid #e5e7eb; margin:5px 0;">';
    
    // 9. FONDO FOOTER
    echo '<div>';
    echo '<h3 style="margin:0 0 10px 0; color:#1f2937; border-left:3px solid #c1621e; padding-left:10px;">Fondo del Pie de Página (Footer Banner opcional)</h3>';
    casa_opt_image('Imagen de Fondo para el Footer (Si no se elige, se mantiene fondo negro #080808)', 'casa_opt_footer_bg_image');
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">3. Datos de Ubicación y Horarios Globales</h2>';
    casa_opt_text('Dirección de las instalaciones', 'casa_opt_global_address', true);
    casa_opt_text('Teléfono Oficial', 'casa_opt_global_phone');
    casa_opt_text('WhatsApp (número con lada o enlace wa.me / wa.link)', 'casa_opt_global_whatsapp');
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
    casa_opt_text('URL del iframe de Google Maps (embed)', 'casa_opt_google_maps_embed');

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
            if (!$portada) $portada = '';
            $cap = get_post_meta($esp->ID, '_espacio_capacidad', true);
            $plano = get_post_meta($esp->ID, '_espacio_plano_pdf', true);
            $panorama = trim((string) get_post_meta($esp->ID, '_espacio_panorama', true));
            $tour_count = function_exists('casa_espacio_tour_stations') ? count(casa_espacio_tour_stations($esp->ID)) : ($panorama ? 1 : 0);
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
                        <p class="casa-rest-meta">Arquitectura histórica • Montaje versátil para bodas y convenciones<?php echo $tour_count > 1 ? ' • Recorrido de ' . (int) $tour_count . ' paradas' : ($tour_count === 1 ? ' • Foto 360 lista' : ' • Sin foto 360'); ?></p>
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
            casa_opt_text('Texto de la Reseña 1 (Restaurantes)', 'casa_opt_rest_rev1_text', true);
            casa_opt_text('Autor de la Reseña 1 (Restaurantes)', 'casa_opt_rest_rev1_author');
            casa_opt_text('URL directa de Reseña Google 1 (Restaurantes)', 'casa_opt_rest_rev1_url');
            casa_opt_text('Texto de la Reseña 2 (Restaurantes)', 'casa_opt_rest_rev2_text', true);
            casa_opt_text('Autor de la Reseña 2 (Restaurantes)', 'casa_opt_rest_rev2_author');
            casa_opt_text('URL directa de Reseña Google 2 (Restaurantes)', 'casa_opt_rest_rev2_url');
            casa_opt_text('Texto de la Reseña 3 (Restaurantes)', 'casa_opt_rest_rev3_text', true);
            casa_opt_text('Autor de la Reseña 3 (Restaurantes)', 'casa_opt_rest_rev3_author');
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
                    if (!$portada) $portada = '';
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

    echo '<div style="background:#fafafa; border:1px solid #eaeaea; border-radius:10px; padding:22px; margin-bottom:30px;">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #ddd; padding-bottom:10px;">3. Configuración SMTP (Envío Seguro de Correos)</h2>';
    echo '<p class="description" style="margin-bottom:16px; color:#555;">Sin SMTP el sitio usa el correo del servidor y suele fallar (sobre todo en Local). Completa host, puerto, usuario y contraseña del buzón institucional. Puerto 587 = TLS; puerto 465 = SSL.</p>';
    
    casa_opt_text('Servidor SMTP (Host)', 'casa_opt_smtp_host');
    casa_opt_text('Puerto SMTP (Ej. 465 o 587)', 'casa_opt_smtp_port');
    
    $encryption = get_option('casa_opt_smtp_encryption', 'tls');
    echo '<div style="margin-bottom:18px;"><label style="font-weight:600; display:block; margin-bottom:6px; color:#2c3338;">Cifrado de Seguridad</label>';
    echo '<select name="casa_opt_smtp_encryption" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;">';
    echo '<option value="tls" ' . selected($encryption, 'tls', false) . '>TLS (Recomendado para puerto 587)</option>';
    echo '<option value="ssl" ' . selected($encryption, 'ssl', false) . '>SSL (Recomendado para puerto 465)</option>';
    echo '<option value="none" ' . selected($encryption, 'none', false) . '>Ninguno</option>';
    echo '</select></div>';
    
    casa_opt_text('Usuario SMTP (Correo Electrónico)', 'casa_opt_smtp_username');
    
    $has_pass = get_option('casa_opt_smtp_password', '') !== '';
    echo '<div style="margin-bottom:18px;"><label style="font-weight:600; display:block; margin-bottom:6px; color:#2c3338;">Contraseña SMTP</label>';
    echo '<input type="password" name="casa_opt_smtp_password" value="" autocomplete="new-password" placeholder="' . esc_attr($has_pass ? 'Dejar vacío para conservar la contraseña actual' : 'Contraseña SMTP') . '" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;">';
    if ($has_pass) {
        echo '<p class="description" style="margin:6px 0 0; color:#15803d;">Hay una contraseña guardada. Escríbela de nuevo solo si quieres cambiarla.</p>';
    }
    echo '</div>';
    
    echo '<h3 style="margin:25px 0 10px 0; color:#1f2937; border-top:1px solid #ddd; padding-top:20px;">Remitente por Defecto</h3>';
    casa_opt_text('Correo del Remitente (From Email)', 'casa_opt_smtp_from_email');
    casa_opt_text('Nombre del Remitente (From Name)', 'casa_opt_smtp_from_name');

    $last_err = get_transient('casa_last_mail_error');
    if ($last_err) {
        echo '<p style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:10px 12px; border-radius:8px;"><strong>Último error de correo:</strong> ' . esc_html($last_err) . '</p>';
    }

    $test_to = get_option('casa_opt_global_email', get_option('admin_email'));
    echo '<div style="margin-top:18px; padding:14px; background:#fff7ed; border:1px solid #fdba74; border-radius:10px;">';
    echo '<label style="font-weight:600; display:block; margin-bottom:6px;">Probar envío SMTP</label>';
    echo '<p class="description" style="margin:0 0 10px;">Guarda primero los datos SMTP y luego envía un correo de prueba.</p>';
    echo '<input type="email" id="casa-smtp-test-to" value="' . esc_attr($test_to) . '" style="width:min(420px,100%); border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px; margin-right:8px;"> ';
    echo '<button type="button" class="button" id="casa-smtp-test-btn">Enviar correo de prueba</button>';
    echo '<p id="casa-smtp-test-status" style="margin:10px 0 0; min-height:1.2em;"></p>';
    echo '</div>';
    echo '</div>';

    echo '<script>
    document.getElementById("casa-smtp-test-btn") && document.getElementById("casa-smtp-test-btn").addEventListener("click", function() {
        var btn = this;
        var status = document.getElementById("casa-smtp-test-status");
        var to = document.getElementById("casa-smtp-test-to").value;
        btn.disabled = true;
        status.style.color = "#475569";
        status.textContent = "Enviando...";
        var data = new FormData();
        data.append("action", "casa_test_smtp");
        data.append("nonce", "' . esc_js(wp_create_nonce('casa_test_smtp')) . '");
        data.append("to", to);
        fetch("' . esc_url(admin_url('admin-ajax.php')) . '", { method: "POST", body: data, credentials: "same-origin" })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                btn.disabled = false;
                if (res && res.success) {
                    status.style.color = "#15803d";
                    status.textContent = res.data || "Correo de prueba enviado.";
                } else {
                    status.style.color = "#b91c1c";
                    status.textContent = (res && res.data) ? res.data : "No se pudo enviar. Revisa host, puerto, usuario y contraseña.";
                }
            })
            .catch(function() {
                btn.disabled = false;
                status.style.color = "#b91c1c";
                status.textContent = "Error de red al probar SMTP.";
            });
    });
    </script>';

    casa_render_admin_footer();
}

function casa_admin_page_galeria() {
    casa_render_admin_header('Panel Casa: Galería — etiquetas y fotografías');
    $terms = function_exists('casa_galeria_ensure_tags') ? casa_galeria_ensure_tags() : array();
    $gallery_ids = array_filter(array_map('absint', explode(',', (string) get_option('casa_opt_galeria_imagenes', ''))));
    ?>
    <style>
        .wrap { max-width: 1180px !important; }
        .casa-gal-help { color:#475569; font-size:14px; line-height:1.55; margin:0 0 16px; }
        .casa-gal-step { background:#fffbeb; border:1px solid #fde68a; color:#92400e; border-radius:10px; padding:10px 14px; font-size:13px; margin-bottom:16px; }
        .casa-gal-create { display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin-bottom:14px; }
        .casa-gal-create input[type="text"] { flex:1; min-width:220px; border:1px solid #cbd5e1; border-radius:8px; padding:10px 12px; font-size:14px; }
        .casa-gal-pills { display:flex; flex-wrap:wrap; gap:8px; }
        .casa-gal-pill { display:inline-flex; align-items:center; gap:6px; border:2px solid #e2e8f0; background:#fff; border-radius:999px; padding:7px 10px 7px 14px; cursor:pointer; font-weight:600; font-size:13px; color:#0f172a; }
        .casa-gal-pill.is-selected { border-color:#c1621e; background:#fff7ed; color:#9a3412; box-shadow:0 0 0 3px rgba(193,98,30,.15); }
        .casa-gal-pill button { border:0; background:transparent; cursor:pointer; color:#94a3b8; padding:0 2px; line-height:1; }
        .casa-gal-pill button:hover { color:#b91c1c; }
        .casa-gal-toolbar { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom:16px; }
        .casa-gal-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(190px, 1fr)); gap:14px; }
        .casa-gal-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; position:relative; box-shadow:0 2px 8px rgba(0,0,0,.04); }
        .casa-gal-card.is-hit { outline:3px solid #c1621e; }
        .casa-gal-thumb { position:relative; }
        .casa-gal-card img { width:100%; height:140px; object-fit:cover; display:block; background:#0f172a; cursor:pointer; position:relative; z-index:0; }
        .casa-gal-card-body { padding:10px; }
        .casa-gal-mini { display:flex; flex-wrap:wrap; gap:5px; }
        .casa-gal-mini button { border:1px solid #e2e8f0; background:#f8fafc; border-radius:999px; padding:3px 8px; font-size:11px; cursor:pointer; color:#475569; }
        .casa-gal-mini button.is-on { background:#c1621e; border-color:#c1621e; color:#fff; font-weight:700; }
        .casa-gal-remove { position:absolute; top:8px; right:8px; z-index:5; width:32px; height:32px; border:2px solid #fff; border-radius:50%; background:#dc2626; color:#fff; cursor:pointer; font-size:18px; line-height:1; box-shadow:0 2px 8px rgba(0,0,0,.35); display:flex; align-items:center; justify-content:center; }
        .casa-gal-remove:hover { background:#b91c1c; }
        .casa-gal-remove-text { display:block; width:100%; margin-top:8px; border:0; border-radius:8px; background:#fee2e2; color:#991b1b; font-weight:700; font-size:12px; padding:7px 8px; cursor:pointer; }
        .casa-gal-remove-text:hover { background:#fecaca; }
        .casa-gal-empty { color:#64748b; font-size:13px; padding:18px; background:#f8fafc; border-radius:10px; }
        .casa-gal-status { min-height:20px; font-size:13px; color:#15803d; margin-top:8px; }
        .casa-gal-status.is-error { color:#b91c1c; }
    </style>
    <div class="casa-rest-admin-wrap" style="margin-bottom:20px;">
        <div class="casa-rest-header-card">
            <div class="casa-rest-header-info">
                <h2>Galería: crea etiquetas y asígnalas a las fotos</h2>
                <p>Primero creas la etiqueta. Luego la eliges y tocas las fotografías. Sin escribir a mano, sin ir a Medios.</p>
            </div>
        </div>

        <div class="casa-section-box" id="etiquetas-galeria">
            <h3 style="margin-top:0; color:#0f172a; font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">1. Crear etiquetas</h3>
            <p class="casa-gal-help">Escribe el nombre (por ejemplo <strong>Boda</strong>) y pulsa Crear. No se pueden duplicar: si ya existe, aparece en la lista de abajo.</p>
            <div class="casa-gal-create">
                <input type="text" id="casa-gal-new-tag" maxlength="60" placeholder="Nombre de la etiqueta, ej. Convenciones" />
                <button type="button" class="casa-btn-gold" id="casa-gal-create-tag" style="border:none; cursor:pointer;">Crear etiqueta</button>
            </div>
            <div class="casa-gal-step" id="casa-gal-paint-hint">Elige una etiqueta para usarla. Luego, en el paso 2, toca las fotos.</div>
            <div class="casa-gal-pills" id="casa-gal-pills">
                <?php foreach ($terms as $term) : ?>
                    <span class="casa-gal-pill" data-id="<?php echo (int) $term->term_id; ?>" data-slug="<?php echo esc_attr($term->slug); ?>">
                        <span class="casa-gal-pill-name"><?php echo esc_html($term->name); ?></span>
                        <button type="button" class="casa-gal-rename" title="Renombrar">✎</button>
                        <button type="button" class="casa-gal-delete" title="Eliminar">×</button>
                    </span>
                <?php endforeach; ?>
            </div>
            <div class="casa-gal-status" id="casa-gal-tag-status"></div>
        </div>

        <div class="casa-section-box">
            <h3 style="margin-top:0; color:#0f172a; font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">2. Fotos: toca para etiquetar</h3>
            <p class="casa-gal-help">Con una etiqueta seleccionada, haz clic en la foto para ponérsela o quitársela. Para borrar una sola fotografía usa el aspa roja o el botón <strong>Quitar foto</strong> de esa tarjeta. Los cambios se guardan al instante.</p>
            <div class="casa-gal-toolbar">
                <input type="button" id="casadepiedra_upload_gallery_btn" class="casa-btn-gold" value="+ Subir o elegir fotos" style="cursor:pointer; border:none;" />
                <input type="button" id="casadepiedra_clear_gallery_btn" class="casa-btn-dark" value="Quitar todas las fotos" style="cursor:pointer; border:none;" />
            </div>
            <input type="hidden" id="casadepiedra_gallery_ids" name="casa_opt_galeria_imagenes" value="<?php echo esc_attr(implode(',', $gallery_ids)); ?>" />
            <div id="casadepiedra_gallery_preview" class="casa-gal-grid">
                <?php
                if (empty($gallery_ids)) {
                    echo '<div class="casa-gal-empty" style="grid-column:1/-1;">Aún no hay fotos. Usa el botón de arriba para subirlas o elegirlas.</div>';
                } else {
                    foreach ($gallery_ids as $id) {
                        $img = wp_get_attachment_image_src($id, 'medium');
                        if (!$img) {
                            continue;
                        }
                        $assigned = wp_get_object_terms($id, 'galeria_tag', array('fields' => 'ids'));
                        if (is_wp_error($assigned)) {
                            $assigned = array();
                        }
                        echo '<div class="casa-gal-card casa-gallery-item" data-id="' . esc_attr($id) . '" data-terms="' . esc_attr(implode(',', array_map('intval', $assigned))) . '">';
                        echo '<div class="casa-gal-thumb">';
                        echo '<img src="' . esc_url($img[0]) . '" alt="" />';
                        echo '<button type="button" class="casa-gal-remove casa-remove-single-img-btn" data-id="' . esc_attr($id) . '" title="Quitar esta foto" aria-label="Quitar esta foto">×</button>';
                        echo '</div>';
                        echo '<div class="casa-gal-card-body"><div class="casa-gal-mini">';
                        foreach ($terms as $term) {
                            $on = in_array((int) $term->term_id, array_map('intval', $assigned), true) ? ' is-on' : '';
                            echo '<button type="button" class="casa-gal-assign' . $on . '" data-term="' . (int) $term->term_id . '">' . esc_html($term->name) . '</button>';
                        }
                        echo '</div>';
                        echo '<button type="button" class="casa-gal-remove-text casa-remove-single-img-btn" data-id="' . esc_attr($id) . '">Quitar foto</button>';
                        echo '</div></div>';
                    }
                }
                ?>
            </div>
            <div class="casa-gal-status" id="casa-gal-photo-status"></div>
        </div>
    </div>
    <?php
    casa_render_admin_footer();
}

function casa_admin_seo_box_open($title, $note = '') {
    echo '<div class="casa-section-box">';
    echo '<h2 style="margin-top:0; color:#111; border-bottom:1px solid #e2e8f0; padding-bottom:10px;">' . esc_html($title) . '</h2>';
    if ($note !== '') {
        echo '<p class="description" style="margin:0 0 16px; color:#64748b;">' . $note . '</p>';
    }
}

function casa_admin_seo_box_close() {
    echo '</div>';
}

function casa_admin_page_seo() {
    if (function_exists('casa_seo_seed_defaults')) {
        casa_seo_seed_defaults();
    }

    $tab = isset($_GET['tab']) ? sanitize_key(wp_unslash($_GET['tab'])) : 'search';
    $tabs = array(
        'search' => 'Search Console',
        'metas'  => 'Títulos y descripciones',
        'negocio'=> 'Negocio (NAP)',
        'visit'  => 'Por qué elegirnos',
        'robots' => 'robots.txt',
        'extra'  => 'Indexación y AI',
    );
    if (!isset($tabs[$tab])) {
        $tab = 'search';
    }

    casa_render_admin_header('Panel Casa: SEO');
    echo '<input type="hidden" name="casa_seo_tab" value="' . esc_attr($tab) . '">';

    $base = admin_url('admin.php?page=casa-panel-seo');
    echo '<style>
        .casa-seo-tabs { display:flex; flex-wrap:wrap; gap:6px; margin:0 0 22px; padding:0; list-style:none; }
        .casa-seo-tabs a { display:inline-block; padding:9px 16px; border-radius:999px; text-decoration:none; font-weight:600; font-size:13px; background:#fff; color:#334155; border:1px solid #e2e8f0; }
        .casa-seo-tabs a.is-active { background:#111; color:#c5a059; border-color:#111; }
        .casa-seo-tabs a:hover { border-color:#c5a059; }
        .casa-seo-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
        @media (max-width:782px) { .casa-seo-grid-2 { grid-template-columns:1fr; } }
    </style>';
    echo '<nav class="casa-seo-tabs" aria-label="Secciones SEO">';
    foreach ($tabs as $key => $label) {
        $cls = $key === $tab ? ' is-active' : '';
        echo '<a class="' . esc_attr(trim($cls)) . '" href="' . esc_url(add_query_arg('tab', $key, $base)) . '">' . esc_html($label) . '</a>';
    }
    echo '</nav>';

    $home    = home_url('/');
    $siteurl = site_url('/');
    $host    = isset($_SERVER['HTTP_HOST']) ? preg_replace('/:\d+$/', '', wp_unslash($_SERVER['HTTP_HOST'])) : '';
    $bad_url = (stripos($home, '.local') !== false || stripos($siteurl, '.local') !== false) && strpos($host, '.local') === false;
    $robots_file = trailingslashit(ABSPATH) . 'robots.txt';
    $robots_physical = is_readable($robots_file) ? (string) file_get_contents($robots_file) : '';
    $robots_local = $robots_physical !== '' && stripos($robots_physical, '.local') !== false;

    if ($tab === 'search') {
        casa_admin_seo_box_open('Estado para Google', 'Teléfono y redes también viven en <strong>Globales</strong>. Aquí van Search Console, el tag de Google y la marca pública.');
        echo '<p style="margin:0 0 8px;"><strong>URL pública:</strong> <code>' . esc_html($home) . '</code></p>';
        echo '<p style="margin:0 0 8px;"><strong>URL de WordPress:</strong> <code>' . esc_html($siteurl) . '</code></p>';
        echo '<p style="margin:0 0 8px;"><strong>Sitemap:</strong> <a href="' . esc_url($home . 'sitemap.xml') . '" target="_blank" rel="noopener"><code>' . esc_html($home . 'sitemap.xml') . '</code></a></p>';
        echo '<p style="margin:0 0 14px;"><strong>robots.txt:</strong> <a href="' . esc_url($home . 'robots.txt') . '" target="_blank" rel="noopener">abrir</a> · <strong>llms.txt:</strong> <a href="' . esc_url($home . 'llms.txt') . '" target="_blank" rel="noopener">abrir</a></p>';
        if ($bad_url) {
            echo '<div class="notice notice-error" style="margin:0 0 14px;"><p>WordPress todavía usa una URL <code>.local</code>. Search Console fallará hasta corregirlo.</p></div>';
            echo '<div style="margin-bottom:14px; display:flex; align-items:center; gap:10px;">';
            echo '<input type="checkbox" name="casa_seo_fix_siteurl" value="1" id="casa_seo_fix_siteurl" style="margin:0; width:18px; height:18px;">';
            echo '<label for="casa_seo_fix_siteurl" style="font-weight:600;">Corregir home/siteurl a <code>' . esc_html($host) . '</code> al guardar</label>';
            echo '</div>';
        }
        if ($robots_local) {
            echo '<div class="notice notice-warning" style="margin:0 0 14px;"><p>Hay un <code>robots.txt</code> físico que apunta a <code>.local</code>. Ve a la pestaña <strong>robots.txt</strong> y actívalo.</p></div>';
        }
        casa_admin_seo_box_close();

        casa_admin_seo_box_open('Search Console y etiqueta de Google');
        casa_opt_text('Google Search Console (content de google-site-verification)', 'casa_opt_seo_gsc');
        echo '<p class="description" style="margin:-10px 0 16px; color:#64748b;">Search Console → Configuración → Verificación → etiqueta HTML. Pega solo el valor de content.</p>';
        casa_opt_text('Archivo HTML de verificación (ej. google123abc.html)', 'casa_opt_seo_gsc_html_file');
        casa_opt_text('Bing Webmaster', 'casa_opt_seo_bing');
        casa_opt_text('Yandex', 'casa_opt_seo_yandex');
        casa_opt_text('Google Tag Manager (GTM-XXXX)', 'casa_opt_seo_gtm');
        echo '<p class="description" style="margin:-10px 0 16px; color:#64748b;">Se inserta en el head y el noscript al abrir el body.</p>';
        casa_opt_text('GA4 (G-XXXXXXXX)', 'casa_opt_seo_ga4');
        echo '<p class="description" style="margin:-10px 0 16px; color:#64748b;">Si ya disparas GA4 desde GTM, deja este campo vacío para no duplicar visitas.</p>';
        casa_opt_text('Nombre en Google / Open Graph', 'casa_opt_seo_site_name');
        casa_opt_image('Imagen Open Graph (1200×630)', 'casa_opt_seo_og_image');
        casa_admin_seo_box_close();
    } elseif ($tab === 'metas') {
        casa_admin_seo_box_open('Títulos y meta descriptions', 'Title 50–60 caracteres y description 140–160. Las fichas de espacios y restaurantes usan su propio texto.');
        $pages = array(
            'home'         => array('Inicio', '/'),
            'espacios'     => array('Espacios / Venues', '/espacios/'),
            'restaurantes' => array('Restaurantes', '/restaurantes/'),
            'galeria'      => array('Galería', '/galeria/'),
            'contacto'     => array('Contacto / Cotizar', '/contacto/'),
            'nosotros'     => array('Quiénes somos', '/quienes-somos/'),
            'eventos'      => array('Eventos', '/eventos/'),
        );
        foreach ($pages as $key => $info) {
            echo '<h3 style="margin:18px 0 10px; color:#1f2937; border-left:3px solid #c1621e; padding-left:10px;">' . esc_html($info[0]) . ' <code>' . esc_html($info[1]) . '</code></h3>';
            casa_opt_text('Title SEO', 'casa_opt_seo_' . $key . '_title');
            casa_opt_text('Meta description', 'casa_opt_seo_' . $key . '_desc', true);
        }
        casa_opt_text('Keywords (opcional)', 'casa_opt_seo_keywords', true);
        casa_admin_seo_box_close();
    } elseif ($tab === 'negocio') {
        casa_admin_seo_box_open('Datos del negocio (NAP)', 'Estos datos alimentan schema, metas geo y llms.txt. El teléfono y el correo también se usan en el sitio.');
        casa_opt_text('Calle y número', 'casa_opt_seo_street');
        casa_opt_text('Colonia / recinto', 'casa_opt_seo_neighborhood');
        casa_opt_text('Ciudad', 'casa_opt_seo_locality');
        casa_opt_text('Estado', 'casa_opt_seo_region');
        casa_opt_text('C.P.', 'casa_opt_seo_postal');
        casa_opt_text('Teléfono público', 'casa_opt_global_phone');
        casa_opt_text('WhatsApp (solo dígitos con lada)', 'casa_opt_global_whatsapp');
        casa_opt_text('Correo', 'casa_opt_global_email');
        casa_opt_text('Dirección completa (footer y contacto)', 'casa_opt_global_address', true);
        casa_opt_text('Horario visible', 'casa_opt_global_office_hours', true);
        echo '<div class="casa-seo-grid-2">';
        casa_opt_text('Abre L–V (HH:MM)', 'casa_opt_seo_open_week');
        casa_opt_text('Cierra L–V (HH:MM)', 'casa_opt_seo_close_week');
        casa_opt_text('Abre sábado (HH:MM)', 'casa_opt_seo_open_sat');
        casa_opt_text('Cierra sábado (HH:MM)', 'casa_opt_seo_close_sat');
        casa_opt_text('Latitud', 'casa_opt_seo_lat');
        casa_opt_text('Longitud', 'casa_opt_seo_lng');
        echo '</div>';
        casa_opt_text('Descripción schema (si se deja vacía se usa la de Inicio)', 'casa_opt_seo_schema_desc', true);
        casa_admin_seo_box_close();
    } elseif ($tab === 'visit') {
        $visit = function_exists('casa_seo_visit') ? casa_seo_visit() : array();
        casa_admin_seo_box_open('Por qué elegir Casa de Piedra', 'Copia visible en el inicio para Google y buscadores de IA. Enlaza a espacios, restaurantes, galería y cotizar.');
        echo '<div style="margin-bottom:18px;"><label style="font-weight:600; display:block; margin-bottom:6px;">Kicker</label>';
        echo '<input type="text" name="casa_opt_seo_visit[kicker]" value="' . esc_attr($visit['kicker'] ?? '') . '" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;"></div>';
        echo '<div style="margin-bottom:18px;"><label style="font-weight:600; display:block; margin-bottom:6px;">Título</label>';
        echo '<input type="text" name="casa_opt_seo_visit[title]" value="' . esc_attr($visit['title'] ?? '') . '" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;"></div>';
        echo '<div style="margin-bottom:18px;"><label style="font-weight:600; display:block; margin-bottom:6px;">Lead</label>';
        echo '<textarea name="casa_opt_seo_visit[lead]" rows="3" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;">' . esc_textarea($visit['lead'] ?? '') . '</textarea></div>';
        echo '<h3 style="margin:20px 0 10px;">Cifras</h3>';
        $highlights = isset($visit['highlights']) && is_array($visit['highlights']) ? $visit['highlights'] : array();
        for ($i = 0; $i < 4; $i++) {
            $row = isset($highlights[$i]) ? $highlights[$i] : array('value' => '', 'label' => '');
            echo '<div class="casa-seo-grid-2">';
            echo '<div style="margin-bottom:12px;"><label style="font-weight:600; display:block; margin-bottom:6px;">Valor ' . ($i + 1) . '</label>';
            echo '<input type="text" name="casa_opt_seo_visit[highlights][' . $i . '][value]" value="' . esc_attr($row['value'] ?? '') . '" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;"></div>';
            echo '<div style="margin-bottom:12px;"><label style="font-weight:600; display:block; margin-bottom:6px;">Etiqueta ' . ($i + 1) . '</label>';
            echo '<input type="text" name="casa_opt_seo_visit[highlights][' . $i . '][label]" value="' . esc_attr($row['label'] ?? '') . '" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;"></div>';
            echo '</div>';
        }
        echo '<h3 style="margin:20px 0 10px;">Motivos (con enlace interno)</h3>';
        $items = isset($visit['items']) && is_array($visit['items']) ? $visit['items'] : array();
        for ($i = 0; $i < 6; $i++) {
            $item = isset($items[$i]) ? $items[$i] : array('name' => '', 'text' => '', 'url' => '');
            echo '<div style="border:1px solid #e2e8f0; border-radius:10px; padding:14px; margin-bottom:12px;">';
            echo '<div style="margin-bottom:10px;"><label style="font-weight:600; display:block; margin-bottom:6px;">Motivo ' . ($i + 1) . '</label>';
            echo '<input type="text" name="casa_opt_seo_visit[items][' . $i . '][name]" value="' . esc_attr($item['name'] ?? '') . '" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;"></div>';
            echo '<div style="margin-bottom:10px;"><label style="font-weight:600; display:block; margin-bottom:6px;">Texto</label>';
            echo '<textarea name="casa_opt_seo_visit[items][' . $i . '][text]" rows="2" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;">' . esc_textarea($item['text'] ?? '') . '</textarea></div>';
            echo '<div><label style="font-weight:600; display:block; margin-bottom:6px;">URL</label>';
            echo '<input type="text" name="casa_opt_seo_visit[items][' . $i . '][url]" value="' . esc_attr($item['url'] ?? '') . '" style="width:100%; border:1px solid #c3c4c7; border-radius:6px; padding:8px 10px;"></div>';
            echo '</div>';
        }
        casa_admin_seo_box_close();
    } elseif ($tab === 'robots') {
        casa_admin_seo_box_open('robots.txt', 'Si hay un archivo físico en la raíz, Google lo usa en lugar del virtual de WordPress. Activa la escritura para corregir URLs <code>.local</code>.');
        if ($robots_local) {
            echo '<div class="notice notice-error" style="margin:0 0 14px;"><p>El archivo físico apunta a <code>.local</code>. Márcalo y guarda.</p></div>';
        }
        echo '<p style="margin:0 0 12px;"><a href="' . esc_url($home . 'robots.txt') . '" target="_blank" rel="noopener">Ver robots.txt actual</a></p>';
        casa_opt_text('robots.txt personalizado (vacío = el del tema)', 'casa_opt_seo_robots_txt', true);
        casa_opt_checkbox('Escribir robots.txt en la raíz al guardar', 'casa_opt_seo_sync_robots');
        casa_admin_seo_box_close();
    } else {
        casa_admin_seo_box_open('Indexación y AI', 'Rutas que no deben indexarse, llms.txt para ChatGPT/Perplexity y directiva robots por defecto.');
        casa_opt_text('Robots por defecto (ej. index, follow)', 'casa_opt_seo_robots');
        casa_opt_text('Rutas noindex (una por línea)', 'casa_opt_seo_noindex_paths', true);
        casa_opt_text('llms.txt personalizado (vacío = se genera solo)', 'casa_opt_seo_llms', true);
        casa_admin_seo_box_close();
    }

    casa_render_admin_footer();
}


