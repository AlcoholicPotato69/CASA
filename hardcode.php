<?php
$json = file_get_contents("C:\Users\johan\Local Sites\casa-de-piedra\app\public\casa_export.json");
$data = json_decode($json, true);

// Update admin-panel.php defaults
$admin_panel_path = "C:\\Users\\johan\\OneDrive\\Desktop\\repos git\\CASA\\casadepiedra-luxury-theme\\inc\\admin-panel.php";
$admin_panel = file_get_contents($admin_panel_path);

foreach ($data["options"] as $key => $val) {
    if (empty($val)) continue;
    // If it's an image URL, update to local path
    if (preg_match("/^http.*(jpg|jpeg|png|webp|gif|svg)$/i", $val)) {
        $filename = basename($val);
        $new_val = '<?php echo get_template_directory_uri(); ?>/assets/images/mirror/' . $filename;
        // Wait, inside get_option it's PHP context, so:
        $new_val_php = "get_template_directory_uri() . '/assets/images/mirror/" . $filename . "'";
        // We need to replace get_option('key', 'default') with get_option('key', $new_val_php)
        // Regex to match get_option('$key', '.*')
        $admin_panel = preg_replace("/get_option\(\s*['\"]" . preg_quote($key) . "['\"]\s*,\s*['\"].*?['\"]\s*\)/", "get_option('$key', $new_val_php)", $admin_panel);
    } else {
        // Text values
        $escaped_val = addslashes($val);
        $admin_panel = preg_replace("/get_option\(\s*['\"]" . preg_quote($key) . "['\"]\s*,\s*['\"].*?['\"]\s*\)/", "get_option('$key', '$escaped_val')", $admin_panel);
    }
}
file_put_contents($admin_panel_path, $admin_panel);
echo "Admin panel defaults updated.\n";

// Generate Static HTML for Espacios
$espacios_html = "";
foreach ($data["espacios"] as $i => $esp) {
    // Extract thumbnail
    $thumb = $esp["thumb"];
    if (empty($thumb)) {
        $meta = $esp["meta"];
        // In the json meta is stringified, wait, the meta in JSON is broken. 
    }
}

