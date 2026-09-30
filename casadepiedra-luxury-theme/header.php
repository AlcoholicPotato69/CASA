<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<?php
$casa_gtm = function_exists('casa_seo_gtm_id') ? casa_seo_gtm_id() : '';
if ($casa_gtm === '') {
    $casa_gtm = 'GTM-5N5VJXL4';
}
?>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
window.gtag = window.gtag || gtag;
(function () {
    var consent = {
        ad_storage: 'denied',
        ad_user_data: 'denied',
        ad_personalization: 'denied',
        analytics_storage: 'denied',
        functionality_storage: 'granted',
        personalization_storage: 'denied',
        security_storage: 'granted',
        wait_for_update: 500
    };
    try {
        var raw = localStorage.getItem('casa_cookie_consent');
        var prefs = raw ? JSON.parse(raw) : null;
        if (!prefs) {
            var legacy = localStorage.getItem('casa_privacy_consent');
            if (legacy === 'accepted') prefs = { analytics: true, ads: true };
            if (legacy === 'denied') prefs = { analytics: false, ads: false };
        }
        if (prefs && prefs.analytics) consent.analytics_storage = 'granted';
        if (prefs && prefs.ads) {
            consent.ad_storage = 'granted';
            consent.ad_user_data = 'granted';
            consent.ad_personalization = 'granted';
            consent.personalization_storage = 'granted';
        }
    } catch (e) {}
    gtag('consent', 'default', consent);
})();
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js($casa_gtm); ?>');</script>
<!-- End Google Tag Manager -->
    <script>
    (function () {
        try {
            var fromNav = sessionStorage.getItem('casa_from_nav') === '1';
            sessionStorage.removeItem('casa_from_nav');
            document.documentElement.classList.add(fromNav ? 'casa-from-nav' : 'casa-first-visit');
        } catch (e) {
            document.documentElement.classList.add('casa-first-visit');
        }
    })();
    </script>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
    <script>
    (function() {
        window.addEventListener('DOMContentLoaded', () => {
            const activeTitle = document.title;
            const hiddenMessages = [
                '✨ TE EXTRAÑAMOS | CASA DE PIEDRA',
                '👑 TU EVENTO TE ESPERA | CASA DE PIEDRA',
                '💎 REGRESA A LA ELEGANCIA | CASA DE PIEDRA'
            ];
            let timer = null;
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    let idx = 0;
                    document.title = hiddenMessages[idx];
                    timer = setInterval(() => {
                        idx = (idx + 1) % hiddenMessages.length;
                        document.title = hiddenMessages[idx];
                    }, 2200);
                } else {
                    if (timer) clearInterval(timer);
                    document.title = activeTitle;
                }
            });
        });
    })();
    </script>
    <style>
        /* Navbar specific styles that were inline in React */
        .header-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100000 !important; 
            min-height: clamp(65px, 5vh + 15px, 85px);
            padding: clamp(0.5rem, 1.2vh, 0.8rem) clamp(1.5rem, 4vw, 4rem);
            background: rgba(8, 8, 8, 0.42);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(193, 98, 30, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .header-wrapper.scrolled {
            min-height: clamp(55px, 4vh + 10px, 68px);
            padding: 0.4rem clamp(1.5rem, 4vw, 4rem);
            background: rgba(6, 6, 6, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(193, 98, 30, 0.45);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.95);
        }
        .header-logo {
            height: clamp(45px, 6vh + 12px, 80px);
            max-height: 80px;
            width: auto;
            max-width: clamp(200px, 35vw, 450px);
            object-fit: contain;
            cursor: pointer;
            transition: transform 0.3s ease, height 0.4s ease, max-width 0.4s ease;
        }
        .header-wrapper.scrolled .header-logo {
            height: clamp(36px, 4vh + 8px, 56px);
            max-height: 56px;
            max-width: clamp(160px, 25vw, 320px);
        }
        .header-logo:hover {
            transform: scale(1.03);
        }
        .desktop-nav ul {
            display: flex;
            align-items: center;
            gap: 2.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .desktop-nav a {
            font-family: var(--font-body);
            font-size: 0.95rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--color-text-primary);
            text-decoration: none;
            transition: color 0.3s;
        }
        .desktop-nav a:hover,
        .desktop-nav .current-menu-item > a {
            color: var(--color-accent) !important;
        }
        .desktop-nav .nav-item-contacto,
        .desktop-nav > ul > li:last-child {
            isolation: isolate;
        }
        .desktop-nav .nav-item-contacto > a,
        .desktop-nav > ul > li:last-child > a {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            contain: paint;
            clip-path: inset(0 round 999px);
            padding: 0.5rem 1.3rem;
            border: 1px solid rgba(193, 98, 30, 0.55);
            border-radius: 999px;
            color: var(--color-accent) !important;
            transition: background 0.3s ease, color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        }
        .desktop-nav .nav-item-contacto > a::after,
        .desktop-nav > ul > li:last-child > a::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            z-index: 0;
            background: linear-gradient(
                115deg,
                transparent 0%,
                transparent 38%,
                rgba(255, 236, 210, 0.42) 50%,
                transparent 62%,
                transparent 100%
            );
            background-size: 240% 100%;
            background-repeat: no-repeat;
            background-position: 160% 0;
            opacity: 0;
            animation: casa-contact-reflect 6.8s ease-in-out infinite;
        }
        .desktop-nav .nav-item-contacto > a:hover,
        .desktop-nav > ul > li:last-child > a:hover {
            background: var(--color-accent);
            color: #000 !important;
            box-shadow: 0 0 18px rgba(193, 98, 30, 0.4);
            transform: translateY(-1px);
        }
        .desktop-nav .nav-item-contacto > a:hover::after,
        .desktop-nav > ul > li:last-child > a:hover::after {
            animation: casa-contact-reflect-fast 0.85s ease;
        }
        @keyframes casa-contact-reflect {
            0%, 70% { background-position: 160% 0; opacity: 0; }
            74% { opacity: 1; }
            84% { background-position: -60% 0; opacity: 1; }
            88%, 100% { background-position: -60% 0; opacity: 0; }
        }
        @keyframes casa-contact-reflect-fast {
            0% { background-position: 160% 0; opacity: 1; }
            100% { background-position: -60% 0; opacity: 0; }
        }
        @media (prefers-reduced-motion: reduce) {
            .desktop-nav .nav-item-contacto > a::after,
            .desktop-nav > ul > li:last-child > a::after,
            .fullscreen-menu .nav-item-contacto > a::after,
            .fullscreen-menu > ul > li:last-child > a::after {
                animation: none !important;
                opacity: 0 !important;
            }
        }

        /* Dropdown Menu (Sub-menu) */
        .desktop-nav .menu-item-has-children {
            position: relative;
            padding-bottom: 1rem; /* Increase hover area */
            margin-bottom: -1rem;
        }
        .desktop-nav .sub-menu {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(15px);
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 0.5rem 0;
            min-width: 250px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
            box-shadow: 0 10px 40px rgba(0,0,0,0.8);
            display: flex;
            flex-direction: column;
            gap: 0;
            list-style: none;
            margin: 0;
        }
        .desktop-nav .menu-item-has-children:hover .sub-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
        .desktop-nav .sub-menu li {
            width: 100%;
        }
        .desktop-nav .sub-menu a,
        .desktop-nav .sub-menu li:last-child > a {
            padding: 1rem 1.5rem !important;
            display: block !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.15em !important;
            color: var(--color-text-primary) !important;
            border: none !important;
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            transform: none !important;
            background: transparent !important;
            transition: background 0.3s, color 0.3s, padding-left 0.3s !important;
        }
        .desktop-nav .sub-menu li:last-child > a {
            border-bottom: none !important;
        }
        .desktop-nav .sub-menu a:hover,
        .desktop-nav .sub-menu li:last-child > a:hover {
            background: rgba(193,98,30,0.1) !important;
            color: var(--color-accent) !important;
            padding-left: 2rem !important;
            box-shadow: none !important;
            transform: none !important;
        }

        .mobile-hamburger {
            display: none;
            flex-direction: column;
            gap: 0.375rem;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            z-index: 101;
            width: 3rem; 
            height: 3rem;
            align-items: center;
            justify-content: center;
        }
        .hamburger-line {
            width: 1.5rem;
            height: 0.125rem;
            background-color: var(--color-accent);
            transition: transform 0.4s cubic-bezier(0.32, 0.72, 0, 1), background-color 0.4s;
        }
        /* Active states for hamburger */
        .mobile-hamburger.is-active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(0.3125rem, 0.375rem);
            background-color: var(--color-text-primary);
        }
        .mobile-hamburger.is-active .hamburger-line:nth-child(2) {
            transform: rotate(-45deg) translate(0.3125rem, -0.375rem);
            background-color: var(--color-text-primary);
        }

        @media (max-width: 1024px) {
            .desktop-nav { display: none; }
            .mobile-hamburger { display: flex; }
        }

        .fullscreen-menu {
            position: fixed;
            inset: 0;
            background-color: #0a0a0a;
            z-index: 99;
            clip-path: circle(0% at calc(100% - 40px) 40px);
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(circle at center, rgba(193,98,30,0.05) 0%, transparent 70%);
            transition: clip-path 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .fullscreen-menu.is-active {
            clip-path: circle(150% at calc(100% - 40px) 40px);
        }
        .fullscreen-menu ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            text-align: center;
            padding: 0;
            margin: 0;
        }
        .fullscreen-menu li {
            overflow: hidden;
        }
        .fullscreen-menu a {
            font-family: var(--font-heading);
            font-size: clamp(1.8rem, 8vw, 8rem);
            color: var(--color-text-primary);
            text-decoration: none;
            opacity: 0;
            transform: translateY(3rem);
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: opacity 0.4s ease, transform 0.4s ease, color 0.3s;
        }
        .fullscreen-menu.is-active a {
            opacity: 1;
            transform: translateY(0);
        }
        .fullscreen-menu .nav-item-contacto > a,
        .fullscreen-menu > ul > li:last-child > a {
            position: relative;
            overflow: hidden;
            isolation: isolate;
            contain: paint;
            clip-path: inset(0 round 999px);
            color: var(--color-accent);
            display: inline-block;
            margin-top: 0.5rem;
            padding: 0.6rem 2.2rem;
            border: 1px solid rgba(193,98,30,0.5);
            border-radius: 999px;
            font-size: clamp(1.4rem, 6vw, 2.5rem);
            background: rgba(193,98,30,0.08);
        }
        .fullscreen-menu .nav-item-contacto > a::after,
        .fullscreen-menu > ul > li:last-child > a::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            background: linear-gradient(
                115deg,
                transparent 0%,
                transparent 38%,
                rgba(255, 236, 210, 0.42) 50%,
                transparent 62%,
                transparent 100%
            );
            background-size: 240% 100%;
            background-repeat: no-repeat;
            background-position: 160% 0;
            opacity: 0;
            animation: casa-contact-reflect 6.8s ease-in-out infinite;
        }
        .fullscreen-menu .nav-item-contacto > a:hover::after,
        .fullscreen-menu > ul > li:last-child > a:hover::after {
            animation: casa-contact-reflect-fast 0.85s ease;
        }
        .fullscreen-menu a:hover,
        .fullscreen-menu .current-menu-item > a {
            color: var(--color-accent);
        }
        
        /* Hide sub-menu on fullscreen menu for cleaner UX */
        .fullscreen-menu .sub-menu {
            display: none !important;
        }
        
        /* Page Transition Overlay */
        #page-transition-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
            box-sizing: border-box;
            will-change: transform;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            background-color: #0a0a0a;
            background-image: 
                radial-gradient(circle at 10% 15%, rgba(193, 98, 30, 0.07) 0%, transparent 45%),
                radial-gradient(circle at 90% 85%, rgba(180, 140, 80, 0.05) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(20, 20, 20, 0.85) 0%, rgba(10, 10, 10, 1) 100%);
            z-index: 999999;
            pointer-events: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: clamp(2rem, 5vh, 4rem) 2rem;
            transform: translate3d(0, 100%, 0);
            visibility: hidden;
        }
        html.casa-first-visit #page-transition-overlay {
            transform: translate3d(0, 100%, 0);
            visibility: hidden;
            animation: none;
        }
        html.casa-leaving #page-transition-overlay {
            visibility: visible;
            pointer-events: auto;
            animation: casaOverlayIn 0.9s cubic-bezier(0.87, 0, 0.13, 1) forwards;
        }
        html.casa-from-nav #page-transition-overlay {
            visibility: visible;
            animation: casaOverlayOut 0.9s cubic-bezier(0.87, 0, 0.13, 1) forwards;
        }
        @keyframes casaOverlayIn {
            from { transform: translate3d(0, 100%, 0); }
            to { transform: translate3d(0, 0, 0); }
        }
        @keyframes casaOverlayOut {
            from { transform: translate3d(0, 0, 0); }
            to { transform: translate3d(0, -110%, 0); }
        }
        @media (prefers-reduced-motion: reduce) {
            #page-transition-overlay { display: none !important; animation: none !important; }
        }
        .transition-divider-bar {
            width: clamp(200px, 60vw, 700px);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            flex-shrink: 0;
            color: #c1621e;
            font-size: 0.85rem;
            opacity: 0.85;
        }
        .transition-divider-bar::before,
        .transition-divider-bar::after {
            content: '';
            flex: 1;
            height: 1px;
        }
        .transition-divider-bar::before {
            background: linear-gradient(90deg, transparent 0%, rgba(193, 98, 30, 0.35) 100%);
        }
        .transition-divider-bar::after {
            background: linear-gradient(90deg, rgba(193, 98, 30, 0.35) 0%, transparent 100%);
        }
        .transition-logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            width: 100%;
        }
        #page-transition-overlay img {
            width: auto;
            max-width: 85vw;
            height: clamp(220px, 40vh, 550px);
            object-fit: contain;
            filter: drop-shadow(0 0 25px rgba(255, 255, 255, 0.3));
            opacity: 0.95;
            animation: logoHeartbeat 3s infinite ease-in-out;
        }
        @keyframes logoHeartbeat {
            0%, 100% { transform: scale(0.95); opacity: 0.85; }
            50% { transform: scale(1.08); opacity: 1; }
        }
    </style>
</head>
<body <?php body_class(); ?><?php
    if (is_singular('espacios')) {
        echo ' data-current-espacio="' . esc_attr(get_the_title()) . '"';
    }
?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($casa_gtm); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>
    <div id="page-transition-overlay">
        <div class="transition-divider-bar"><span>◇</span></div>
        <div class="transition-logo-container">
            <?php 
            $transition_logo = function_exists('casa_transition_logo_url') ? casa_transition_logo_url() : '';
            if ($transition_logo) {
                echo '<img src="' . esc_url($transition_logo) . '" alt="" width="400" height="400" decoding="async" fetchpriority="low" />';
            } else {
                echo '<span class="casa-wordmark">Casa de Piedra</span>';
            }
            ?>
        </div>
        <div class="transition-divider-bar"><span>◇</span></div>
    </div>
    <nav class="header-wrapper">
        <div class="logo-container">
            <?php 
            $panel_logo = function_exists('casa_official_logo_url') ? casa_official_logo_url() : (function_exists('casa_logo_url') ? casa_logo_url() : '');
            if ($panel_logo) {
                ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" style="text-decoration: none;">
                    <img src="<?php echo esc_url($panel_logo); ?>" alt="<?php bloginfo('name'); ?>" class="header-logo" width="450" height="80" decoding="async" />
                </a>
                <?php
            } elseif (has_custom_logo()) {
                the_custom_logo();
            } else {
                ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="casa-wordmark-link" style="text-decoration: none;">
                    <span class="casa-wordmark">Casa de Piedra</span>
                </a>
                <?php
            }
            ?>
        </div>
        
        <!-- Desktop Navbar -->
        <div class="desktop-nav">
            <?php 
            wp_nav_menu(array(
                'theme_location' => 'menu-principal',
                'container' => false,
                'fallback_cb' => false
            )); 
            ?>
        </div>

        <!-- Mobile Hamburger -->
        <button class="mobile-nav-btn mobile-hamburger" id="nav-toggle" aria-label="Toggle Menu">
            <div class="hamburger-line"></div>
            <div class="hamburger-line"></div>
        </button>
    </nav>

    <!-- Mobile Fullscreen Menu -->
    <div class="fullscreen-menu" id="fullscreen-menu">
        <?php 
        wp_nav_menu(array(
            'theme_location' => 'menu-principal',
            'container' => false,
            'fallback_cb' => false,
            'menu_id' => 'mobile-menu-list'
        )); 
        ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const currentUrl = window.location.href.split('?')[0].replace(/\/$/, "");
        const navLinks = document.querySelectorAll('.desktop-nav a, .fullscreen-menu a');
        navLinks.forEach(link => {
            const linkUrl = link.getAttribute('href').split('?')[0].replace(/\/$/, "");
            if (linkUrl === currentUrl) {
                link.style.setProperty('color', 'var(--color-accent)', 'important');
            }
        });

        const headerWrapper = document.querySelector('.header-wrapper');
        if (headerWrapper) {
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    headerWrapper.classList.add('scrolled');
                } else {
                    headerWrapper.classList.remove('scrolled');
                }
            }, { passive: true });
        }
    });
    </script>

    <!-- Lenis integration wrapper (optional, for smooth scrolling support) -->
    <main id="main-content">