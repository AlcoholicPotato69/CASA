<?php
// Auto-Installer to populate the DB with Espacios and Restaurantes when the theme is activated

function casadepiedra_demo_installer() {
    if (get_option('casa_demo_installed')) {
        return;
    }

    $json_path = get_template_directory() . "/inc/default_data.json";
    if (!file_exists($json_path)) {
        return;
    }

    $data = json_decode(file_get_contents($json_path), true);
    if (!$data) return;

    // Insert Options
    if (!empty($data["options"])) {
        foreach ($data["options"] as $key => $val) {
            // Rewrite image URLs to mirror folder
            if (preg_match("/^http.*(jpg|jpeg|png|webp|gif|svg)$/i", $val)) {
                $val = get_template_directory_uri() . "/assets/images/mirror/" . basename($val);
            }
            update_option($key, $val);
        }
    }

    // Insert Espacios
    if (!empty($data["espacios"])) {
        foreach ($data["espacios"] as $esp) {
            $post_id = wp_insert_post(array(
                'post_title'   => wp_strip_all_tags($esp['title']),
                'post_content' => $esp['content'],
                'post_status'  => 'publish',
                'post_type'    => 'espacios',
            ));
            
            if (!empty($esp['meta'])) {
                if (!empty($esp['meta']['_espacio_capacidad'][0])) update_post_meta($post_id, '_espacio_capacidad', $esp['meta']['_espacio_capacidad'][0]);
                if (!empty($esp['meta']['_espacio_rango_personas'][0])) update_post_meta($post_id, '_espacio_rango_personas', $esp['meta']['_espacio_rango_personas'][0]);
                if (!empty($esp['meta']['_espacio_m2'][0])) update_post_meta($post_id, '_espacio_m2', $esp['meta']['_espacio_m2'][0]);
                if (!empty($esp['thumb'])) {
                    $thumb_url = get_template_directory_uri() . "/assets/images/mirror/" . basename($esp['thumb']);
                    update_post_meta($post_id, '_espacio_portada_url', $thumb_url);
                }
            }
        }
    }

    // Insert Restaurantes
    if (!empty($data["restaurantes"])) {
        foreach ($data["restaurantes"] as $rest) {
            $post_id = wp_insert_post(array(
                'post_title'   => wp_strip_all_tags($rest['title']),
                'post_content' => $rest['content'],
                'post_status'  => 'publish',
                'post_type'    => 'restaurantes',
            ));
            
            if (!empty($rest['meta'])) {
                if (!empty($rest['meta']['_restaurante_cocina'][0])) update_post_meta($post_id, '_restaurante_cocina', $rest['meta']['_restaurante_cocina'][0]);
                if (!empty($rest['meta']['_restaurante_menu'][0])) update_post_meta($post_id, '_restaurante_menu', $rest['meta']['_restaurante_menu'][0]);
                if (!empty($rest['meta']['_restaurante_reserva_valor'][0])) update_post_meta($post_id, '_restaurante_reserva_valor', $rest['meta']['_restaurante_reserva_valor'][0]);
                
                if (!empty($rest['meta']['_restaurante_logo'][0])) {
                    update_post_meta($post_id, '_restaurante_logo_url', get_template_directory_uri() . "/assets/images/mirror/" . basename($rest['meta']['_restaurante_logo'][0]));
                }
                if (!empty($rest['meta']['_restaurante_card_image'][0])) {
                    update_post_meta($post_id, '_restaurante_card_image_url', get_template_directory_uri() . "/assets/images/mirror/" . basename($rest['meta']['_restaurante_card_image'][0]));
                }
            }
        }
    }

    update_option('casa_demo_installed', '1');
}
add_action('after_switch_theme', 'casadepiedra_demo_installer');
