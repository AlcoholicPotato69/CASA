<?php
/**
 * Motor de SEO Avanzado, Core Web Vitals y Sitelinks (Schema.org / Google Rich Results)
 * Desarrollado para Casa de Piedra León v3.8
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Inyectar Etiquetas SEO, Schema.org (Sitelinks + SearchBox) y Core Web Vitals en <head>
 */
function casa_render_advanced_seo_and_sitelinks() {
    global $post;

    $site_name = get_bloginfo('name') ?: 'Casa de Piedra León';
    $site_url = trailingslashit(home_url('/'));
    $logo_url = get_template_directory_uri() . '/assets/images/logo-casa-de-piedra.png';

    // Determinar Título y Descripción dinámicos según la página actual
    $page_title = $site_name;
    $page_desc = 'Recinto histórico en León, Guanajuato para bodas, eventos sociales, congresos corporativos, salones exclusivos y restaurantes de alta cocina desde 1845.';
    $canonical_url = $site_url;
    $og_image = get_template_directory_uri() . '/assets/images/jardin_principal_1779523113451.png';
    $og_type = 'website';

    if (is_front_page() || is_home()) {
        $page_title = 'INICIO | ' . strtoupper($site_name);
        $canonical_url = $site_url;
    } elseif (is_singular('espacios')) {
        $page_title = strtoupper(get_the_title()) . ' | VENUES ' . strtoupper($site_name);
        $excerpt = get_the_excerpt($post);
        if ($excerpt) {
            $page_desc = wp_strip_all_tags($excerpt);
        } else {
            $page_desc = 'Descubre las instalaciones, capacidad y especificaciones técnicas de ' . get_the_title() . ' en Casa de Piedra León.';
        }
        $canonical_url = get_permalink($post);
        if (has_post_thumbnail($post)) {
            $og_image = get_the_post_thumbnail_url($post, 'large');
        }
        $og_type = 'article';
    } elseif (is_singular('restaurantes')) {
        $page_title = strtoupper(get_the_title()) . ' | RESTAURANTES ' . strtoupper($site_name);
        $cocina = get_post_meta($post->ID, '_restaurante_cocina', true);
        $page_desc = 'Disfruta de la mejor propuesta gastronómica (' . ($cocina ?: 'Alta Cocina') . ') en ' . get_the_title() . ', ubicado dentro de Casa de Piedra León.';
        $canonical_url = get_permalink($post);
        if (has_post_thumbnail($post)) {
            $og_image = get_the_post_thumbnail_url($post, 'large');
        }
        $og_type = 'article';
    } elseif (is_page('galeria') || is_page_template('page-galeria.php') || (isset($post->post_name) && $post->post_name === 'galeria')) {
        $page_title = 'GALERÍA OFICIAL | ' . strtoupper($site_name);
        $page_desc = 'Explora nuestra galería de bodas, eventos corporativos, arquitectura y gastronomía en la ex hacienda más emblemática de León.';
        $canonical_url = trailingslashit(home_url('/galeria/'));
    } elseif (is_page()) {
        $page_title = strtoupper(get_the_title()) . ' | ' . strtoupper($site_name);
        $canonical_url = get_permalink($post);
    } elseif (is_post_type_archive('espacios') || is_page('espacios')) {
        $page_title = 'VENUES & SALONES DE EVENTOS | ' . strtoupper($site_name);
        $page_desc = 'Conoce nuestros exclusivos salones, terrazas y jardines para bodas y congresos en León, Guanajuato.';
        $canonical_url = trailingslashit(home_url('/espacios/'));
    } elseif (is_post_type_archive('restaurantes') || is_page('restaurantes')) {
        $page_title = 'RESTAURANTES DE ALTA COCINA | ' . strtoupper($site_name);
        $page_desc = 'Descubre los restaurantes más reconocidos y exclusivos reunidos en el entorno histórico de Casa de Piedra.';
        $canonical_url = trailingslashit(home_url('/restaurantes/'));
    }

    ?>
    <!-- ================= SEO OPTIMIZER & CORE WEB VITALS (v3.8) ================= -->
    <link rel="canonical" href="<?php echo esc_url($canonical_url); ?>" />
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>" />
    <meta property="og:title" content="<?php echo esc_attr($page_title); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($page_desc); ?>" />
    <meta property="og:image" content="<?php echo esc_url($og_image); ?>" />
    <meta property="og:url" content="<?php echo esc_url($canonical_url); ?>" />
    <meta property="og:type" content="<?php echo esc_attr($og_type); ?>" />
    <meta property="og:locale" content="es_MX" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr($page_title); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($page_desc); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>" />

    <!-- Core Web Vitals: Preload LCP y Speculation Rules -->
    <?php if ($og_image): ?>
    <link rel="preload" href="<?php echo esc_url($og_image); ?>" as="image" fetchpriority="high" />
    <?php endif; ?>

    <script type="speculationrules">
    {
      "prerender": [{
        "where": { "href_matches": "/*" },
        "eagerness": "moderate"
      }]
    }
    </script>

    <!-- Schema.org JSON-LD (Sitelinks Search Box + SiteNavigationElement + EventVenue) -->
    <?php
    // Construir Breadcrumbs dinámicos
    $breadcrumbs = array(
        array(
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Inicio',
            'item' => $site_url
        )
    );

    if (is_singular('espacios')) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Venues & Salones',
            'item' => trailingslashit(home_url('/espacios/'))
        );
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => 3,
            'name' => get_the_title(),
            'item' => $canonical_url
        );
    } elseif (is_singular('restaurantes')) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Restaurantes',
            'item' => trailingslashit(home_url('/restaurantes/'))
        );
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => 3,
            'name' => get_the_title(),
            'item' => $canonical_url
        );
    } elseif (is_page('galeria')) {
        $breadcrumbs[] = array(
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Galería',
            'item' => $canonical_url
        );
    }

    $schema_graph = array(
        '@context' => 'https://schema.org',
        '@graph' => array(
            // 1. WebSite con Sitelinks Search Box
            array(
                '@type' => 'WebSite',
                '@id' => $site_url . '#website',
                'url' => $site_url,
                'name' => $site_name,
                'description' => $page_desc,
                'publisher' => array(
                    '@id' => $site_url . '#organization'
                ),
                'potentialAction' => array(
                    '@type' => 'SearchAction',
                    'target' => array(
                        '@type' => 'EntryPoint',
                        'urlTemplate' => $site_url . '?s={search_term_string}'
                    ),
                    'query-input' => 'required name=search_term_string'
                )
            ),
            // 2. Organización y EventVenue (Sede)
            array(
                '@type' => array('Organization', 'EventVenue'),
                '@id' => $site_url . '#organization',
                'name' => $site_name,
                'url' => $site_url,
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => $logo_url
                ),
                'description' => $page_desc,
                'address' => array(
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Av Cerro Gordo 270, Casa de Piedra',
                    'addressLocality' => 'León de los Aldama',
                    'addressRegion' => 'Gto',
                    'postalCode' => '37120',
                    'addressCountry' => 'MX'
                ),
                'telephone' => '+52-477-717-2600',
                'contactPoint' => array(
                    '@type' => 'ContactPoint',
                    'telephone' => '+52-477-717-2600',
                    'contactType' => 'reservations',
                    'areaServed' => 'MX',
                    'availableLanguage' => array('Spanish', 'English')
                ),
                'sameAs' => array(
                    'https://www.facebook.com/casadepiedraleon',
                    'https://www.instagram.com/casadepiedraleon'
                ),
                'aggregateRating' => array(
                    '@type' => 'AggregateRating',
                    'ratingValue' => '4.9',
                    'reviewCount' => '1420'
                )
            ),
            // 3. SiteNavigationElement (Pestañas Recomendadas/Sitelinks de Google)
            array(
                '@type' => 'SiteNavigationElement',
                '@id' => $site_url . '#navigation',
                'name' => array(
                    'Venues & Salones de Eventos',
                    'Restaurantes de Alta Cocina',
                    'Galería de Fotos y Eventos',
                    'Solicitud de Cotizaciones',
                    'Ubicación y Contacto'
                ),
                'url' => array(
                    trailingslashit(home_url('/espacios/')),
                    trailingslashit(home_url('/restaurantes/')),
                    trailingslashit(home_url('/galeria/')),
                    trailingslashit(home_url('/galeria/#quote-modal')),
                    trailingslashit(home_url('/galeria/#quote-modal'))
                )
            ),
            // 4. BreadcrumbList
            array(
                '@type' => 'BreadcrumbList',
                '@id' => $canonical_url . '#breadcrumb',
                'itemListElement' => $breadcrumbs
            )
        )
    );
    ?>
    <script type="application/ld+json">
    <?php echo wp_json_encode($schema_graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>
    <!-- ======================================================================= -->
    <?php
}
add_action('wp_head', 'casa_render_advanced_seo_and_sitelinks', 1);

/**
 * 2. Generador Dinámico de XML Sitemap en /sitemap.xml
 */
function casa_custom_sitemap_endpoint() {
    add_rewrite_rule('^sitemap\.xml$', 'index.php?casa_sitemap=1', 'top');
}
add_action('init', 'casa_custom_sitemap_endpoint');

function casa_custom_sitemap_query_vars($vars) {
    $vars[] = 'casa_sitemap';
    return $vars;
}
add_filter('query_vars', 'casa_custom_sitemap_query_vars');

function casa_render_sitemap_template() {
    if (get_query_var('casa_sitemap')) {
        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Home
        echo '  <url>' . "\n";
        echo '    <loc>' . esc_url(trailingslashit(home_url('/'))) . '</loc>' . "\n";
        echo '    <lastmod>' . gmdate('Y-m-d') . '</lastmod>' . "\n";
        echo '    <changefreq>daily</changefreq>' . "\n";
        echo '    <priority>1.0</priority>' . "\n";
        echo '  </url>' . "\n";

        // Páginas principales
        $pages = array('espacios', 'restaurantes', 'galeria');
        foreach ($pages as $p_slug) {
            echo '  <url>' . "\n";
            echo '    <loc>' . esc_url(trailingslashit(home_url('/' . $p_slug . '/'))) . '</loc>' . "\n";
            echo '    <lastmod>' . gmdate('Y-m-d') . '</lastmod>' . "\n";
            echo '    <changefreq>weekly</changefreq>' . "\n";
            echo '    <priority>0.9</priority>' . "\n";
            echo '  </url>' . "\n";
        }

        // Custom Post Types: Espacios
        $espacios = get_posts(array('post_type' => 'espacios', 'post_status' => 'publish', 'posts_per_page' => -1));
        foreach ($espacios as $post) {
            echo '  <url>' . "\n";
            echo '    <loc>' . esc_url(get_permalink($post)) . '</loc>' . "\n";
            echo '    <lastmod>' . get_the_modified_date('Y-m-d', $post) . '</lastmod>' . "\n";
            echo '    <changefreq>weekly</changefreq>' . "\n";
            echo '    <priority>0.85</priority>' . "\n";
            echo '  </url>' . "\n";
        }

        // Custom Post Types: Restaurantes
        $restaurantes = get_posts(array('post_type' => 'restaurantes', 'post_status' => 'publish', 'posts_per_page' => -1));
        foreach ($restaurantes as $post) {
            echo '  <url>' . "\n";
            echo '    <loc>' . esc_url(get_permalink($post)) . '</loc>' . "\n";
            echo '    <lastmod>' . get_the_modified_date('Y-m-d', $post) . '</lastmod>' . "\n";
            echo '    <changefreq>weekly</changefreq>' . "\n";
            echo '    <priority>0.8</priority>' . "\n";
            echo '  </url>' . "\n";
        }

        echo '</urlset>';
        exit;
    }
}
add_action('template_redirect', 'casa_render_sitemap_template');

/**
 * 3. Optimización Automática de robots.txt indicando el sitemap
 */
function casa_custom_robotstxt($output, $public) {
    $site_url = trailingslashit(home_url('/'));
    $custom  = "User-agent: *\n";
    $custom .= "Allow: /\n";
    $custom .= "Disallow: /wp-admin/\n";
    $custom .= "Disallow: /wp-includes/\n";
    $custom .= "Disallow: /private/\n\n";
    $custom .= "Sitemap: " . $site_url . "sitemap.xml\n";
    return $custom;
}
add_filter('robots_txt', 'casa_custom_robotstxt', 99, 2);
