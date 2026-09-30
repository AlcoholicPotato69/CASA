$f = "C:\Users\johan\OneDrive\Desktop\repos git\CASA\casadepiedra-luxury-theme\archive-restaurantes.php"
$c = Get-Content $f -Raw
$c = $c -replace '(?s)\$args = array\(.*?\);\s*\$restaurantes_query = new WP_Query\(\$args\);', '$data = json_decode(file_get_contents(get_template_directory() . "/inc/default_data.json"), true); $posts = $data["restaurantes"] ?? array();'
$c = $c -replace '\$posts = \$restaurantes_query->posts;', ''
$c = $c -replace '(?s)setup_postdata\(\$post\);', '$title = $post["title"]; $content = $post["content"];'
$c = $c -replace 'get_the_title\(\)', '$title'
$c = $c -replace 'get_the_content\(\)', '$content'
$c = $c -replace '(?s)onclick="window\.location\.href=''<\?php the_permalink\(\); \?>''"', ''
$c = $c -replace '<\?php the_permalink\(\); \?>', '#'
$c = $c -replace '(?s)<\?php\s*\$card_img = get_post_meta\(get_the_ID\(\), ''_restaurante_card_image'', true\);\s*if \(\!empty\(\$card_img\)\) : \?>\s*<img src="<\?php echo esc_url\(\$card_img\); \?>" alt="<\?php the_title_attribute\(\); \?>" class="rest-img-cover" loading="lazy" />\s*<\?php elseif \(has_post_thumbnail\(\)\) : \?>\s*<img src="<\?php the_post_thumbnail_url\(''large''\); \?>" alt="<\?php the_title_attribute\(\); \?>" class="rest-img-cover" loading="lazy" />\s*<\?php else : \?>', '<?php $thumb = !empty($post["thumb"]) ? get_template_directory_uri() . "/assets/images/mirror/" . basename($post["thumb"]) : ""; $card_img = !empty($post["meta"]["_restaurante_card_image"][0]) ? get_template_directory_uri() . "/assets/images/mirror/" . basename($post["meta"]["_restaurante_card_image"][0]) : $thumb; if (!empty($card_img)) : ?> <img src="<?php echo esc_url($card_img); ?>" alt="<?php echo esc_attr($title); ?>" class="rest-img-cover" loading="lazy" /> <?php else : ?>'
$c = $c -replace '(?s)<\?php\s*\$logo_url = get_post_meta\(get_the_ID\(\), ''_restaurante_logo'', true\);\s*if \(\!empty\(\$logo_url\)\) : \?>', '<?php $logo_url = !empty($post["meta"]["_restaurante_logo"][0]) ? get_template_directory_uri() . "/assets/images/mirror/" . basename($post["meta"]["_restaurante_logo"][0]) : ""; if (!empty($logo_url)) : ?>'
$c = $c -replace '<\?php the_title_attribute\(\); \?>', '<?php echo esc_attr($title); ?>'
$c = $c -replace '<\?php the_title\(\); \?>', '<?php echo esc_html($title); ?>'
$c = $c -replace 'wp_reset_postdata\(\);', ''
Set-Content -Path $f -Value $c
