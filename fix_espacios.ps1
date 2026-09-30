$f = "C:\Users\johan\OneDrive\Desktop\repos git\CASA\casadepiedra-luxury-theme\archive-espacios.php"
$c = Get-Content $f -Raw
$c = $c -replace '(?s)\$args = array\(.*?\);\s*\$espacios_query = new WP_Query\(\$args\);\s*\$posts = \$espacios_query->posts;', '$data = json_decode(file_get_contents(get_template_directory() . "/inc/default_data.json"), true); $posts = $data["espacios"] ?? array();'
$c = $c -replace '(?s)setup_postdata\(\$post\);\s*\$cap_meta = get_post_meta\(get_the_ID\(\), ''_espacio_capacidad'', true\);', '$title = $post["title"]; $cap_meta = $post["meta"]["_espacio_capacidad"][0] ?? "";'
$c = $c -replace 'get_the_title\(\)', '$title'
$c = $c -replace '(?s)onclick="window\.location\.href=''<\?php the_permalink\(\); \?>''"', ''
$c = $c -replace '(?s)<\?php\s*\$tarjeta_inicio = get_post_meta\(get_the_ID\(\), ''_espacio_tarjeta_inicio'', true\);\s*if \(\!empty\(\$tarjeta_inicio\)\) : \?>\s*<img src="<\?php echo esc_url\(\$tarjeta_inicio\); \?>" alt="<\?php the_title_attribute\(\); \?>" class="img-cover" loading="lazy" />\s*<\?php elseif \(has_post_thumbnail\(\)\) : \?>\s*<img src="<\?php the_post_thumbnail_url\(''large''\); \?>" alt="<\?php the_title_attribute\(\); \?>" class="img-cover" loading="lazy" />\s*<\?php else : \?>', '<?php $thumb = !empty($post["thumb"]) ? get_template_directory_uri() . "/assets/images/mirror/" . basename($post["thumb"]) : ""; $tarjeta = !empty($post["meta"]["_espacio_tarjeta_inicio"][0]) ? get_template_directory_uri() . "/assets/images/mirror/" . basename($post["meta"]["_espacio_tarjeta_inicio"][0]) : $thumb; if (!empty($tarjeta)) : ?> <img src="<?php echo esc_url($tarjeta); ?>" alt="<?php echo esc_attr($title); ?>" class="img-cover" loading="lazy" /> <?php else : ?>'
$c = $c -replace '<\?php the_title\(\); \?>', '<?php echo esc_html($title); ?>'
$c = $c -replace '<span class="btn-view">Ver Detalles &rarr;</span>', ''
$c = $c -replace 'wp_reset_postdata\(\);', ''
Set-Content -Path $f -Value $c
