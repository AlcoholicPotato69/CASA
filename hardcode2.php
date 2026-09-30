<?php
$json = file_get_contents("C:\Users\johan\Local Sites\casa-de-piedra\app\public\casa_export.json");
$data = json_decode($json, true);

$theme_dir = "C:\\Users\\johan\\OneDrive\\Desktop\\repos git\\CASA\\casadepiedra-luxury-theme";

// Generate PHP array string for Espacios
$espacios_array = "<?php\n\$hardcoded_espacios = array(\n";
foreach ($data["espacios"] as $e) {
    $title = addslashes($e["title"]);
    $content = addslashes($e["content"]);
    $thumb = basename($e["thumb"] ?? '');
    if ($thumb) $thumb = "get_template_directory_uri() . '/assets/images/mirror/" . $thumb . "'";
    else $thumb = "''";
    
    // Attempt to extract meta correctly
    $meta_str = $e["meta"];
    $personas = '';
    $m2 = '';
    $capacidad = '';
    $portada_url = '';
    // Actually the JSON export failed to export meta properly. It output "System.Object[]".
    // I need to re-export the meta properly first!
}
echo "Need to fix meta export first.";
