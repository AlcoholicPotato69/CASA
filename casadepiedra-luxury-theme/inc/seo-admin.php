<?php
/**
 * SEO operativo para Panel Casa: lee casa_opt_seo_* e inyecta
 * Search Console, GTM, GA4, robots y verificación.
 */
if (!defined('ABSPATH')) {
    exit;
}

function casa_seo_opt($key, $default = '') {
    $value = get_option('casa_opt_seo_' . $key, $default);
    if (is_string($value) && trim($value) === '') {
        return $default;
    }
    return $value;
}

function casa_seo_gtm_id() {
    $id = strtoupper(trim((string) casa_seo_opt('gtm')));
    return preg_match('/^GTM-[A-Z0-9]+$/', $id) ? $id : '';
}

add_action('init', function () {
    if (get_option('casa_opt_seo_gtm_5n5vjxl4') === '1') {
        return;
    }
    if (trim((string) get_option('casa_opt_seo_gtm', '')) === '') {
        update_option('casa_opt_seo_gtm', 'GTM-5N5VJXL4');
    }
    update_option('casa_opt_seo_gtm_5n5vjxl4', '1');
}, 4);

function casa_seo_ga4_id() {
    $id = strtoupper(trim((string) casa_seo_opt('ga4')));
    return preg_match('/^G-[A-Z0-9]+$/', $id) ? $id : '';
}

function casa_seo_current_page_key() {
    if (is_singular(array('espacios', 'restaurantes', 'eventos'))) {
        return '';
    }
    if (is_front_page() || is_home()) {
        return 'home';
    }
    if (is_post_type_archive('espacios') || is_page('espacios') || is_page('venues')) {
        return 'espacios';
    }
    if (is_post_type_archive('restaurantes') || is_page('restaurantes')) {
        return 'restaurantes';
    }
    if (is_post_type_archive('eventos') || is_page('eventos')) {
        return 'eventos';
    }
    if (is_page('galeria') || is_page_template('page-galeria.php')) {
        return 'galeria';
    }
    if (is_page('contacto') || is_page_template('page-contacto.php')) {
        return 'contacto';
    }
    if (is_page('quienes-somos') || is_page_template('page-quienes-somos.php')) {
        return 'nosotros';
    }
    return '';
}

function casa_seo_path_noindex($path = '') {
    if ($path === '') {
        $path = isset($_SERVER['REQUEST_URI']) ? parse_url(wp_unslash($_SERVER['REQUEST_URI']), PHP_URL_PATH) : '/';
    }
    $path = untrailingslashit((string) $path);
    if ($path === '') {
        $path = '/';
    }
    $raw = casa_seo_opt('noindex_paths', "/aviso-de-privacidad\n/private");
    foreach (preg_split("/\r\n|\n|\r/", (string) $raw) as $line) {
        $line = untrailingslashit(trim($line));
        if ($line !== '' && ($path === $line || strpos($path, $line . '/') === 0)) {
            return true;
        }
    }
    return false;
}

function casa_seo_apply_panel($ctx) {
    $key = casa_seo_current_page_key();
    if ($key) {
        $title = casa_seo_opt($key . '_title');
        $desc  = casa_seo_opt($key . '_desc');
        if ($title !== '') {
            $ctx['title'] = $title;
        }
        if ($desc !== '') {
            $ctx['desc'] = $desc;
        }
    }
    $og = casa_seo_opt('og_image');
    if ($og && !is_singular(array('espacios', 'restaurantes', 'eventos'))) {
        $ctx['image'] = $og;
    }
    $robots = casa_seo_opt('robots');
    if ($robots !== '') {
        $ctx['robots'] = $robots;
    }
    if (casa_seo_path_noindex() || ($key === 'eventos' && get_option('casa_opt_status_eventos', '0') !== '1')) {
        $ctx['robots'] = 'noindex, follow';
    }
    return $ctx;
}

function casa_seo_write_verify_file($name, $token) {
    $name = strtolower(trim((string) $name));
    if (!preg_match('/^google[a-z0-9]+\.html$/', $name)) {
        return false;
    }
    $token = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $token);
    if ($token === '') {
        return false;
    }
    $file = trailingslashit(ABSPATH) . $name;
    if (file_exists($file) && !is_writable($file)) {
        return false;
    }
    if (!file_exists($file) && !is_writable(ABSPATH)) {
        return false;
    }
    return false !== file_put_contents($file, 'google-site-verification: ' . $token . "\n", LOCK_EX);
}

function casa_seo_recommended_robots() {
    $sitemap = trailingslashit(home_url('/')) . 'sitemap.xml';
    $body  = "User-agent: *\nAllow: /\nDisallow: /wp-admin/\nAllow: /wp-admin/admin-ajax.php\n";
    $body .= "Disallow: /wp-includes/\nDisallow: /wp-login.php\nDisallow: /xmlrpc.php\n";
    $body .= "Disallow: /?s=\nDisallow: /*?s=\nDisallow: /private/\nDisallow: /aviso-de-privacidad/\nDisallow: /feed/\n\n";
    foreach (array('Googlebot', 'OAI-SearchBot', 'Claude-SearchBot', 'PerplexityBot', 'GPTBot', 'ChatGPT-User') as $bot) {
        $body .= "User-agent: {$bot}\nAllow: /\n\n";
    }
    $body .= "Sitemap: {$sitemap}\n";
    return $body;
}

function casa_seo_write_robots_file($body) {
    $file = trailingslashit(ABSPATH) . 'robots.txt';
    $body = str_replace(array("\0", "\r\n", "\r"), array('', "\n", "\n"), (string) $body);
    $body = preg_replace('/https?:\/\/[^\/\s]*\.local[^\s]*/i', trailingslashit(home_url('/')) . 'sitemap.xml', $body);
    if (file_exists($file) && !is_writable($file)) {
        return false;
    }
    if (!file_exists($file) && !is_writable(dirname($file))) {
        return false;
    }
    return false !== file_put_contents($file, trim($body) . "\n", LOCK_EX);
}

function casa_seo_seed_defaults() {
    if (get_option('casa_opt_seo_seeded_v1') === '1') {
        return;
    }
    $nap = array(
        'street'       => 'Av. Cerro Gordo 270',
        'neighborhood' => 'Casa de Piedra',
        'locality'     => 'León de los Aldama',
        'region'       => 'Guanajuato',
        'postal'       => '37120',
        'open_week'    => '09:00',
        'close_week'   => '18:00',
        'open_sat'     => '09:00',
        'close_sat'    => '14:00',
        'lat'          => '21.15854',
        'lng'          => '-101.69926',
        'site_name'    => 'Casa de Piedra León',
        'robots'       => 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1',
        'noindex_paths'=> "/aviso-de-privacidad\n/private",
    );
    foreach ($nap as $key => $value) {
        if (get_option('casa_opt_seo_' . $key, '') === '') {
            update_option('casa_opt_seo_' . $key, $value);
        }
    }
    foreach (casa_seo_title_defaults() as $key => $pair) {
        if (get_option('casa_opt_seo_' . $key . '_title', '') === '') {
            update_option('casa_opt_seo_' . $key . '_title', $pair['title']);
        }
        if (get_option('casa_opt_seo_' . $key . '_desc', '') === '') {
            update_option('casa_opt_seo_' . $key . '_desc', $pair['desc']);
        }
    }
    if (get_option('casa_opt_seo_keywords', '') === '') {
        update_option('casa_opt_seo_keywords', casa_seo_primary_keywords());
    }
    if (!is_array(get_option('casa_opt_seo_visit', null))) {
        update_option('casa_opt_seo_visit', casa_seo_visit_defaults());
    }
    update_option('casa_opt_seo_seeded_v1', '1');
}

function casa_seo_after_options_saved() {
    $gsc  = casa_seo_opt('gsc');
    $file = casa_seo_opt('gsc_html_file');
    if ($gsc && $file) {
        casa_seo_write_verify_file($file, $gsc);
    }
    if (get_option('casa_opt_seo_sync_robots', '0') === '1') {
        $custom = casa_seo_opt('robots_txt');
        casa_seo_write_robots_file($custom !== '' ? $custom : casa_seo_recommended_robots());
    }
    if (!empty($_POST['casa_seo_fix_siteurl'])) {
        $host = isset($_SERVER['HTTP_HOST']) ? strtolower(preg_replace('/:\d+$/', '', wp_unslash($_SERVER['HTTP_HOST']))) : '';
        if ($host !== '' && strpos($host, '.local') === false) {
            $url = (is_ssl() ? 'https://' : 'http://') . $host;
            update_option('home', $url);
            update_option('siteurl', $url);
        }
    }
}

function casa_seo_analytics_html() {
    $ga4 = casa_seo_ga4_id();
    if (!$ga4) {
        return '';
    }
    $id_js = wp_json_encode($ga4);
    $js    = <<<'JS'
(function(){
  window.dataLayer = window.dataLayer || [];
  function gtag(){ dataLayer.push(arguments); }
  window.gtag = window.gtag || gtag;
  gtag("js", new Date());
  gtag("config", GA_ID, { anonymize_ip: true });
  function loadGtag() {
    if (document.querySelector('script[src*="gtag/js?id="]')) return;
    var s = document.createElement("script");
    s.async = true;
    s.src = "https://www.googletagmanager.com/gtag/js?id=" + GA_ID;
    document.head.appendChild(s);
  }
  if (window.requestIdleCallback) requestIdleCallback(loadGtag, { timeout: 4000 });
  else if (document.readyState === "complete") setTimeout(loadGtag, 1);
  else window.addEventListener("load", function () { setTimeout(loadGtag, 1); });
  function send(name, params) { gtag("event", name, params || {}); }
  function linkText(el) {
    return ((el && (el.getAttribute("aria-label") || el.textContent)) || "").replace(/\s+/g, " ").trim().slice(0, 120);
  }
  document.addEventListener("click", function (e) {
    var t = e.target;
    if (!t || !t.closest) return;
    var a = t.closest("a");
    if (!a) return;
    var href = a.getAttribute("href") || "";
    var url;
    try { url = new URL(a.href, location.href); } catch (err) { return; }
    var text = linkText(a);
    var file = url.pathname.match(/\.([a-z0-9]{2,5})$/i);
    var fileExt = file ? file[1].toLowerCase() : "";
    if (url.protocol === "tel:") { send("click", { link_url: href, link_text: text, link_type: "phone" }); return; }
    if (url.protocol === "mailto:") { send("click", { link_url: url.href, link_text: text, link_type: "email" }); return; }
    if (/wa\.me|whatsapp\.com/i.test(url.href)) { send("click", { link_url: url.href, link_text: text, link_type: "whatsapp" }); return; }
    if (/google\.[^/]+\/maps|maps\.google/i.test(url.href)) { send("click", { link_url: url.href, link_text: text, link_type: "maps" }); return; }
    if (/facebook\.com|instagram\.com|tiktok\.com|youtube\.com/i.test(url.hostname)) {
      send("click", { link_url: url.href, link_text: text, link_type: "social" });
      return;
    }
    if (/(pdf|docx?|xlsx?)$/i.test(fileExt)) {
      send("file_download", { file_name: url.pathname.split("/").pop(), file_extension: fileExt });
      return;
    }
    if (a.classList.contains("btn-open-quote-modal") || href.indexOf("quote-modal") !== -1) {
      send("select_content", { content_type: "cta", item_id: "cotizar", item_name: text || "Cotizar" });
    }
  }, true);
  document.addEventListener("submit", function (e) {
    var form = e.target;
    if (form && (form.id === "casa-quote-form" || (form.closest && form.closest("#quote-modal")))) {
      send("generate_lead", { lead_source: "cotizar" });
    }
  }, true);
})();
JS;
    return '<script>var GA_ID = ' . $id_js . ';' . $js . '</script>' . "\n";
}

function casa_seo_print_head_tags() {
    $gsc = casa_seo_opt('gsc');
    $bing = casa_seo_opt('bing');
    $yandex = casa_seo_opt('yandex');
    $ga4 = casa_seo_ga4_id();
    if ($gsc) {
        echo '<meta name="google-site-verification" content="' . esc_attr($gsc) . '">' . "\n";
    }
    if ($bing) {
        echo '<meta name="msvalidate.01" content="' . esc_attr($bing) . '">' . "\n";
    }
    if ($yandex) {
        echo '<meta name="yandex-verification" content="' . esc_attr($yandex) . '">' . "\n";
    }
    if ($ga4) {
        echo casa_seo_analytics_html();
    }
}
add_action('wp_head', 'casa_seo_print_head_tags', 0);

function casa_seo_serve_verify_file() {
    $path = isset($_SERVER['REQUEST_URI']) ? parse_url(wp_unslash($_SERVER['REQUEST_URI']), PHP_URL_PATH) : '';
    $path = strtolower((string) $path);
    if (!preg_match('#^/(google[a-z0-9]+\.html)$#', $path, $m)) {
        return;
    }
    $gsc  = casa_seo_opt('gsc');
    $want = strtolower((string) casa_seo_opt('gsc_html_file'));
    if ($gsc === '') {
        return;
    }
    if ($want !== '' && $want !== $m[1]) {
        return;
    }
    status_header(200);
    header('Content-Type: text/html; charset=UTF-8');
    echo 'google-site-verification: ' . esc_html($gsc) . "\n";
    exit;
}
add_action('template_redirect', 'casa_seo_serve_verify_file', 0);

add_action('init', 'casa_seo_seed_defaults', 30);
