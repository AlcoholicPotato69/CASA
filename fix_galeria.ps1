$f = "C:\Users\johan\OneDrive\Desktop\repos git\CASA\casadepiedra-luxury-theme\page-galeria.php"
$c = Get-Content $f -Raw

# Replace WP attachment logic with hardcoded logic
$c = $c -replace '(?s)\$gallery_items_data = array\(\);.*?<\?php', '$data = json_decode(file_get_contents(get_template_directory() . "/inc/default_data.json"), true);
$gallery_items_data = array();
$tags = explode(",", get_option("casa_opt_galeria_etiquetas", "Boda, Cumpleaños, Eventos empresariales, Convenciones"));
foreach ($data["galeria"] as $idx => $url) {
    $full = get_template_directory_uri() . "/assets/images/mirror/" . basename($url);
    $tag = trim($tags[$idx % count($tags)]);
    $slug = "tag-" . sanitize_title($tag);
    $gallery_items_data[] = array(
        "full" => $full,
        "thumb" => $full,
        "title" => "Fotografía " . ($idx+1),
        "tags_classes" => $slug,
        "class" => "gallery-item luxury-card " . $slug
    );
}
?>

<?php'

# Replace the categories loop
$c = $c -replace '(?s)\$terms = get_terms\(array\(.*?\)\);\s*\$default_covers.*?\s*\$categories = array\(\);\s*\$cover_idx = 0;\s*if \(\!empty\(\$terms\).*?\n\s*\}\s*\}', '
    $tags = explode(",", get_option("casa_opt_galeria_etiquetas", "Boda, Cumpleaños, Eventos empresariales, Convenciones"));
    $default_covers = array(
        get_template_directory_uri() . "/assets/images/mirror/salon_principal_1779523069698.png",
        get_template_directory_uri() . "/assets/images/mirror/salon_pavorreales_1779523097528.png",
        get_template_directory_uri() . "/assets/images/mirror/jardin_principal_1779523113451.png"
    );
    $categories = array();
    $cover_idx = 0;
    foreach ($tags as $tag) {
        $name = trim($tag);
        if (empty($name)) continue;
        $slug = sanitize_title($name);
        $found_cover = "";
        foreach ($gallery_items_data as $g_item) {
            if (strpos($g_item["tags_classes"], "tag-" . $slug) !== false) {
                $found_cover = $g_item["full"];
                break;
            }
        }
        if (empty($found_cover)) {
            $found_cover = $default_covers[$cover_idx % count($default_covers)] ?? get_template_directory_uri() . "/assets/images/mirror/1-scaled.jpg";
        }
        $cover_idx++;
        $categories[$slug] = array(
            "title" => $name,
            "subtitle" => "Galería Exclusiva",
            "cover" => $found_cover
        );
    }'
Set-Content -Path $f -Value $c
