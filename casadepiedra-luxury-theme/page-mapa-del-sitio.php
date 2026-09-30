<?php
/**
 * Template Name: Mapa del sitio
 * Description: Árbol HTML de sitelinks para Google y visitantes.
 */
get_header();
$tree = function_exists('casa_sitelink_tree') ? casa_sitelink_tree() : array();
?>
<section class="casa-html-sitemap" style="padding:7rem 1.25rem 4.5rem; background:#080808; min-height:70vh;">
    <div class="container" style="max-width:880px; margin:0 auto;">
        <span class="text-script" style="color:var(--color-accent); display:block; margin-bottom:0.4rem;">Casa de Piedra León</span>
        <h1 class="text-hero" style="color:#fff; font-size:clamp(2rem,4vw,3rem); margin:0 0 1rem;">Mapa del sitio</h1>
        <p style="color:rgba(255,255,255,0.72); line-height:1.65; margin:0 0 2rem;">
            Recinto de eventos y restaurantes en Cerro Gordo. Estas son las secciones canónicas que Google debe mostrar como sitelinks.
        </p>
        <nav aria-label="Mapa del sitio">
            <ul class="casa-sitelinks casa-sitelinks--page">
                <?php foreach ($tree as $item) : ?>
                    <li><a href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['name']); ?></a></li>
                <?php endforeach; ?>
            </ul>
            <h2 style="color:#fff; font-size:1.25rem; margin:2.2rem 0 0.8rem;">Venues</h2>
            <ul class="casa-sitelinks casa-sitelinks--page">
                <?php foreach (get_posts(array('post_type' => 'espacios', 'posts_per_page' => -1, 'post_status' => 'publish', 'orderby' => 'menu_order title', 'order' => 'ASC')) as $p) : ?>
                    <li><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></li>
                <?php endforeach; ?>
            </ul>
            <h2 style="color:#fff; font-size:1.25rem; margin:2.2rem 0 0.8rem;">Restaurantes</h2>
            <ul class="casa-sitelinks casa-sitelinks--page">
                <?php foreach (get_posts(array('post_type' => 'restaurantes', 'posts_per_page' => -1, 'post_status' => 'publish', 'orderby' => 'menu_order title', 'order' => 'ASC')) as $p) : ?>
                    <li><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</section>
<?php
get_footer();
