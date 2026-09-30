    </main> <!-- Cerrando main de header.php -->
    <?php
    if (function_exists('casa_render_visit_section')) {
        casa_render_visit_section();
    }
    ?>
    
    <?php $footer_bg = function_exists('casa_opt_media') ? casa_opt_media('casa_opt_footer_bg_image') : ''; ?>
    <footer style="background: <?php echo !empty($footer_bg) ? '#080808' : 'linear-gradient(to bottom, rgba(12, 12, 12, 0.95) 0%, #060606 100%)'; ?>; border-top: 1px solid rgba(193, 98, 30, 0.25); padding-top: 2.5rem; padding-bottom: 1.5rem; position: relative; z-index: 5; overflow: hidden;">
        <?php if (!empty($footer_bg)): ?>
        <img src="<?php echo esc_url($footer_bg); ?>" alt="" loading="lazy" decoding="async" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; filter: brightness(0.5) contrast(1.1); pointer-events: none; opacity: 0.7;" />
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.7) 0%, rgba(8,8,8,0.95) 100%); z-index: 1; pointer-events: none;"></div>
        <?php endif; ?>
        <div class="container" style="position: relative; z-index: 2;">
            <!-- 2 COLUMNAS PRINCIPALES: REDES SOCIALES & CONTACTO EJECUTIVO -->
            <div class="grid md:grid-cols-12 gap-8 lg:gap-12" style="padding-bottom: 2.2rem; align-items: start;">
                
                <!-- Columna 1: Identidad & Redes Sociales Destacadas -->
                <div class="md:col-span-5 lg:col-span-5" style="display: flex; flex-direction: column; gap: 1.2rem;">
                    <?php 
                    $logo_url = function_exists('casa_official_logo_url') ? casa_official_logo_url() : (function_exists('casa_logo_url') ? casa_logo_url() : '');
                    ?>
                    <div>
                        <?php if ($logo_url) : ?>
                        <img 
                            src="<?php echo esc_url($logo_url); ?>" 
                            alt="Casa de Piedra" 
                            loading="lazy"
                            decoding="async"
                            style="width: clamp(150px, 14vw, 210px); height: auto; opacity: 0.95; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.8));" 
                        />
                        <?php else : ?>
                        <span class="casa-wordmark">Casa de Piedra</span>
                        <?php endif; ?>
                    </div>
                    <?php if (function_exists('casa_render_sitelink_nav')) { casa_render_sitelink_nav('casa-sitelinks casa-sitelinks--footer'); } ?>
                    <p class="text-body-lg" style="font-size: 0.88rem; line-height: 1.6; color: #b0b0b0; max-width: 400px; margin: 0;">
                        La sede m&aacute;s emblem&aacute;tica de Le&oacute;n, Guanajuato. Eventos sociales y empresariales de alto nivel desde 1845.
                    </p>
                    
                    <!-- REDES SOCIALES CON PROTAGONISMO ESPECIAL (BOTONES PILL CON ICONO Y TEXTO) -->
                    <div style="margin-top: 0.4rem;">
                        <span style="color: var(--color-accent); font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block; margin-bottom: 0.7rem;">
                            ✦ S&iacute;guenos en Redes Sociales
                        </span>
                        <?php 
                        $ig_url = get_option('casa_opt_global_instagram', 'https://instagram.com');
                        $fb_url = get_option('casa_opt_global_facebook', 'https://facebook.com');
                        $tk_url = get_option('casa_opt_global_tiktok', 'https://tiktok.com');
                        
                        $pill_style = "display: inline-flex; align-items: center; gap: 0.55rem; padding: 0.5rem 1.15rem; background: rgba(255,255,255,0.04); border: 1.2px solid rgba(193,98,30,0.45); border-radius: 999px; color: #fff; font-size: 0.85rem; font-weight: 500; text-decoration: none; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: 0 4px 12px rgba(0,0,0,0.5);";
                        $pill_hover = "onmouseover=\"this.style.background='var(--color-accent)'; this.style.color='#000'; this.style.borderColor='var(--color-accent)'; this.style.transform='translateY(-2px)';\" onmouseout=\"this.style.background='rgba(255,255,255,0.04)'; this.style.color='#fff'; this.style.borderColor='rgba(193,98,30,0.45)'; this.style.transform='translateY(0)';\"";
                        ?>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                            <?php if ($ig_url) : ?>
                            <a href="<?php echo esc_url($ig_url); ?>" target="_blank" rel="noreferrer" style="<?php echo $pill_style; ?>" <?php echo $pill_hover; ?> aria-label="Instagram">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                <span>Instagram</span>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($fb_url) : ?>
                            <a href="<?php echo esc_url($fb_url); ?>" target="_blank" rel="noreferrer" style="<?php echo $pill_style; ?>" <?php echo $pill_hover; ?> aria-label="Facebook">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/></svg>
                                <span>Facebook</span>
                            </a>
                            <?php endif; ?>

                            <?php if ($tk_url) : ?>
                            <a href="<?php echo esc_url($tk_url); ?>" target="_blank" rel="noreferrer" style="<?php echo $pill_style; ?>" <?php echo $pill_hover; ?> aria-label="TikTok">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                                <span>TikTok</span>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Columna 2: Contacto Ejecutivo & Atención Inmediata -->
                <div class="md:col-span-7 lg:col-span-7" style="display: flex; flex-direction: column; gap: 1.2rem; background: rgba(255,255,255,0.025); border: 1px solid rgba(193,98,30,0.25); border-radius: 1.2rem; padding: 1.6rem clamp(1.2rem, 3vw, 2.2rem); box-shadow: 0 15px 35px rgba(0,0,0,0.6);">
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.8rem;">
                        <div>
                            <span style="color: var(--color-accent); font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block;">
                                ✦ Atenci&oacute;n Directa
                            </span>
                            <h4 style="color: #fff; font-size: clamp(1.3rem, 2.2vw, 1.65rem); font-family: var(--font-heading); margin: 0.2rem 0 0 0;">
                                Contacto &amp; Ubicaci&oacute;n
                            </h4>
                        </div>
                        <a href="<?php echo esc_url(home_url('/contacto/')); ?>" class="btn-open-quote-modal" style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--color-accent); color: #000; font-weight: 600; padding: 0.55rem 1.3rem; border-radius: 999px; text-decoration: none; font-size: 0.86rem; transition: all 0.3s; box-shadow: 0 4px 15px rgba(193,98,30,0.3);" onmouseover="this.style.transform='scale(1.04)'; this.style.boxShadow='0 6px 20px rgba(193,98,30,0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 15px rgba(193,98,30,0.3)';">
                            ✦ Solicitar Cotizaci&oacute;n
                        </a>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.1rem; font-size: 0.88rem;">
                        <?php
                        $address_ok = 'Av Cerro Gordo 270, Casa de Piedra, 37120 León de los Aldama, Gto.';
                        $address_footer = get_option('casa_opt_global_address', $address_ok);
                        if (function_exists('casa_repair_mojibake')) {
                            $address_footer = casa_repair_mojibake($address_footer, $address_ok);
                        }
                        if (empty($address_footer) || stripos($address_footer, 'Alonso') !== false || stripos($address_footer, 'Lomas') !== false || stripos($address_footer, '2002') !== false || stripos($address_footer, 'Valle del Campestre') !== false) {
                            $address_footer = $address_ok;
                            update_option('casa_opt_global_address', $address_footer);
                        }
                        $maps_google = function_exists('casa_get_google_maps_url')
                            ? casa_get_google_maps_url()
                            : 'https://www.google.com/maps/search/?api=1&query=21.1585368,-101.6992601';
                        $maps_apple = function_exists('casa_get_apple_maps_url')
                            ? casa_get_apple_maps_url()
                            : 'https://maps.apple.com/?ll=21.1585368,-101.6992601&q=Casa%20de%20Piedra';
                        ?>
                        <a class="casa-footer-maps-link" href="<?php echo esc_url($maps_google); ?>" data-google-maps="<?php echo esc_url($maps_google); ?>" data-apple-maps="<?php echo esc_url($maps_apple); ?>" target="_blank" rel="noopener noreferrer" aria-label="Abrir la ubicaci&oacute;n de Casa de Piedra en el mapa">
                            <span class="casa-gmaps-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" width="22" height="22" focusable="false">
                                    <path fill="#34A853" d="M12 22s7.2-6.4 7.2-12.2A7.2 7.2 0 0 0 4.8 9.8C4.8 15.6 12 22 12 22z"/>
                                    <path fill="#EA4335" d="M12 22S4.8 15.6 4.8 9.8A7.2 7.2 0 0 1 12 2.6V22z"/>
                                    <path fill="#FBBC04" d="M12 12.7 7.4 15.4A7.17 7.17 0 0 1 4.8 9.8h7.2z"/>
                                    <path fill="#4285F4" d="m12 12.7 4.6 2.7A7.17 7.17 0 0 0 19.2 9.8H12z"/>
                                    <circle fill="#fff" cx="12" cy="9.6" r="2.35"/>
                                </svg>
                            </span>
                            <span class="casa-footer-maps-copy">
                                <strong>Direcci&oacute;n</strong>
                                <span><?php echo esc_html($address_footer); ?></span>
                            </span>
                        </a>

                        <div style="display: flex; gap: 0.8rem; align-items: flex-start; color: #ccc;">
                            <span style="color: var(--color-accent); flex-shrink: 0; margin-top: 3px; display: inline-flex;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </span>
                            <div>
                                <strong style="color: #fff; display: block; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Tel&eacute;fono Directo</strong>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', function_exists('casa_get_display_phone') ? casa_get_display_phone() : '477 289 25 21')); ?>" style="color: inherit; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--color-accent)';" onmouseout="this.style.color='inherit';">
                                    <?php echo esc_html(function_exists('casa_get_display_phone') ? casa_get_display_phone() : '477 289 25 21'); ?>
                                </a>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.8rem; align-items: flex-start; color: #ccc;">
                            <span style="color: var(--color-accent); flex-shrink: 0; margin-top: 3px; display: inline-flex;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            </span>
                            <div>
                                <strong style="color: #fff; display: block; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Correo Electr&oacute;nico</strong>
                                <a href="mailto:<?php echo esc_attr(get_option('casa_opt_global_email', 'eventos@casadepiedraleon.mx')); ?>" style="color: inherit; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--color-accent)';" onmouseout="this.style.color='inherit';">
                                    <?php echo esc_html(get_option('casa_opt_global_email', 'eventos@casadepiedraleon.mx')); ?>
                                </a>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.8rem; align-items: flex-start; color: #ccc;">
                            <span style="color: var(--color-accent); flex-shrink: 0; margin-top: 3px; display: inline-flex;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </span>
                            <div>
                                <strong style="color: #fff; display: block; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Horario de Atenci&oacute;n</strong>
                                <span><?php
                                    $hours_ok = 'Lunes a Viernes 9:00 am - 6:00 pm | Sábados 9:00 am - 2:00 pm';
                                    $hours = get_option('casa_opt_global_office_hours', $hours_ok);
                                    if (function_exists('casa_repair_mojibake')) {
                                        $hours = casa_repair_mojibake($hours, $hours_ok);
                                    }
                                    echo esc_html($hours);
                                ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- BARRA INFERIOR DE COPYRIGHT & PRIVACIDAD COMPACTA -->
            <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.2rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; color: #777; font-size: 0.78rem;">
                <div>COPYRIGHT © <?php echo date('Y'); ?> CASA DE PIEDRA. TODOS LOS DERECHOS RESERVADOS.</div>
                <div style="display: flex; gap: 1.5rem; align-items: center;">
                    <a href="javascript:void(0);" onclick="window.history.back();" style="color: var(--color-accent); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">&larr; Regresar a p&aacute;gina anterior</a>
                    <a href="<?php echo esc_url(home_url('/mapa-del-sitio/')); ?>" style="color: inherit; text-decoration: underline; transition: color 0.2s;" onmouseover="this.style.color='#fff';" onmouseout="this.style.color='inherit';">Mapa del sitio</a>
                    <a href="<?php echo esc_url(home_url('/aviso-de-privacidad')); ?>" style="color: inherit; text-decoration: underline; transition: color 0.2s;" onmouseover="this.style.color='#fff';" onmouseout="this.style.color='inherit';">Aviso de Privacidad</a>
                    <a href="#cookies" data-open-cookie-settings style="color: inherit; text-decoration: underline; transition: color 0.2s;" onmouseover="this.style.color='#fff';" onmouseout="this.style.color='inherit';">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
    <?php 
    if (file_exists(get_template_directory() . '/inc/quote-modal.php')) {
        include get_template_directory() . '/inc/quote-modal.php';
    }
    if (file_exists(get_template_directory() . '/inc/pdf-modal.php')) {
        include get_template_directory() . '/inc/pdf-modal.php';
    }
    if (file_exists(get_template_directory() . '/inc/privacy-cookie-modal.php')) {
        include get_template_directory() . '/inc/privacy-cookie-modal.php';
    }
    $wa_url = function_exists('casa_get_whatsapp_url')
        ? casa_get_whatsapp_url('Hola, me interesa cotizar un evento en Casa de Piedra.')
        : 'https://wa.me/524772892521?text=' . rawurlencode('Hola, me interesa cotizar un evento en Casa de Piedra.');
    ?>
    <script>
    (function () {
        function prefersAppleMaps() {
            var ua = navigator.userAgent || '';
            if (/iPhone|iPad|iPod/.test(ua)) return true;
            if (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1) return true;
            if (/Macintosh|Mac OS X/.test(ua) && !/Chrome|CriOS|Edg|Firefox|Android/.test(ua)) return true;
            return false;
        }
        document.querySelectorAll('.casa-footer-maps-link').forEach(function (el) {
            el.addEventListener('click', function (e) {
                var apple = el.getAttribute('data-apple-maps');
                if (apple && prefersAppleMaps()) {
                    e.preventDefault();
                    window.open(apple, '_blank', 'noopener');
                }
            });
        });
    })();
    </script>
    <a class="casa-wa-float" href="<?php echo esc_url($wa_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="Escribir por WhatsApp">
        <span class="casa-wa-float-pulse" aria-hidden="true"></span>
        <svg viewBox="0 0 24 24" width="28" height="28" aria-hidden="true" focusable="false">
            <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
        </svg>
    </a>
    <?php
    wp_footer(); 
    ?>
</body>
</html>