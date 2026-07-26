    </main> <!-- Cerrando main de header.php -->
    
    <?php $footer_bg = get_option('casa_opt_footer_bg_image', ''); ?>
    <footer style="background: <?php echo !empty($footer_bg) ? '#080808' : 'linear-gradient(to bottom, rgba(12, 12, 12, 0.95) 0%, #060606 100%)'; ?>; border-top: 1px solid rgba(212, 175, 55, 0.25); padding-top: 2.5rem; padding-bottom: 1.5rem; position: relative; z-index: 5; overflow: hidden;">
        <?php if (!empty($footer_bg)): ?>
        <img src="<?php echo esc_url($footer_bg); ?>" alt="Footer Background" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; filter: brightness(0.22) contrast(1.1); pointer-events: none;" />
        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.7) 0%, rgba(8,8,8,0.88) 100%); z-index: 1; pointer-events: none;"></div>
        <?php endif; ?>
        <div class="container" style="position: relative; z-index: 2;">
            <!-- 2 COLUMNAS PRINCIPALES: REDES SOCIALES & CONTACTO EJECUTIVO -->
            <div class="grid md:grid-cols-12 gap-8 lg:gap-12" style="padding-bottom: 2.2rem; align-items: start;">
                
                <!-- Columna 1: Identidad & Redes Sociales Destacadas -->
                <div class="md:col-span-5 lg:col-span-5" style="display: flex; flex-direction: column; gap: 1.2rem;">
                    <?php 
                    $panel_logo = get_option('casa_opt_global_logo');
                    $logo_url = $panel_logo ? esc_url($panel_logo) : esc_url(get_template_directory_uri() . '/assets/images/logo-navbar-oficial.png');
                    ?>
                    <div>
                        <img 
                            src="<?php echo $logo_url; ?>" 
                            alt="Casa de Piedra" 
                            style="width: clamp(150px, 14vw, 210px); height: auto; opacity: 0.95; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.8));" 
                        />
                    </div>
                    <p class="text-body-lg" style="font-size: 0.88rem; line-height: 1.6; color: #b0b0b0; max-width: 400px; margin: 0;">
                        La sede más emblemática de León, Guanajuato. Eventos sociales y empresariales de alto nivel desde 1845.
                    </p>
                    
                    <!-- REDES SOCIALES CON PROTAGONISMO ESPECIAL (BOTONES PILL CON ICONO Y TEXTO) -->
                    <div style="margin-top: 0.4rem;">
                        <span style="color: var(--color-accent); font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block; margin-bottom: 0.7rem;">
                            ✦ Síguenos en Redes Sociales
                        </span>
                        <?php 
                        $ig_url = get_option('casa_opt_global_instagram', 'https://instagram.com');
                        $fb_url = get_option('casa_opt_global_facebook', 'https://facebook.com');
                        $tk_url = get_option('casa_opt_global_tiktok', 'https://tiktok.com');
                        
                        $pill_style = "display: inline-flex; align-items: center; gap: 0.55rem; padding: 0.5rem 1.15rem; background: rgba(255,255,255,0.04); border: 1.2px solid rgba(212,175,55,0.45); border-radius: 999px; color: #fff; font-size: 0.85rem; font-weight: 500; text-decoration: none; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); box-shadow: 0 4px 12px rgba(0,0,0,0.5);";
                        $pill_hover = "onmouseover=\"this.style.background='var(--color-accent)'; this.style.color='#000'; this.style.borderColor='var(--color-accent)'; this.style.transform='translateY(-2px)';\" onmouseout=\"this.style.background='rgba(255,255,255,0.04)'; this.style.color='#fff'; this.style.borderColor='rgba(212,175,55,0.45)'; this.style.transform='translateY(0)';\"";
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
                <div class="md:col-span-7 lg:col-span-7" style="display: flex; flex-direction: column; gap: 1.2rem; background: rgba(255,255,255,0.025); border: 1px solid rgba(212,175,55,0.25); border-radius: 1.2rem; padding: 1.6rem clamp(1.2rem, 3vw, 2.2rem); box-shadow: 0 15px 35px rgba(0,0,0,0.6);">
                    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 0.8rem;">
                        <div>
                            <span style="color: var(--color-accent); font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; display: block;">
                                ✦ Atención Directa
                            </span>
                            <h4 style="color: #fff; font-size: clamp(1.3rem, 2.2vw, 1.65rem); font-family: var(--font-heading); margin: 0.2rem 0 0 0;">
                                Contacto &amp; Ubicación
                            </h4>
                        </div>
                        <a href="#open-quote-modal" class="btn-open-quote-modal" style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--color-accent); color: #000; font-weight: 600; padding: 0.55rem 1.3rem; border-radius: 999px; text-decoration: none; font-size: 0.86rem; transition: all 0.3s; box-shadow: 0 4px 15px rgba(212,175,55,0.3);" onmouseover="this.style.transform='scale(1.04)'; this.style.boxShadow='0 6px 20px rgba(212,175,55,0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 15px rgba(212,175,55,0.3)';">
                            ✦ Solicitar Cotización
                        </a>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.1rem; font-size: 0.88rem;">
                        <div style="display: flex; gap: 0.8rem; align-items: flex-start; color: #ccc;">
                            <span style="color: var(--color-accent); flex-shrink: 0; margin-top: 3px; display: inline-flex;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </span>
                            <div>
                                <strong style="color: #fff; display: block; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Dirección</strong>
                                <span><?php 
                                    $address_footer = get_option('casa_opt_global_address', 'Av Cerro Gordo 270, Casa de Piedra, 37120 León de los Aldama, Gto.');
                                    if (empty($address_footer) || stripos($address_footer, 'Alonso') !== false || stripos($address_footer, 'Lomas') !== false || stripos($address_footer, '2002') !== false || stripos($address_footer, 'Valle del Campestre') !== false) {
                                        $address_footer = 'Av Cerro Gordo 270, Casa de Piedra, 37120 León de los Aldama, Gto.';
                                        update_option('casa_opt_global_address', $address_footer);
                                    }
                                    echo esc_html($address_footer);
                                ?></span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.8rem; align-items: flex-start; color: #ccc;">
                            <span style="color: var(--color-accent); flex-shrink: 0; margin-top: 3px; display: inline-flex;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </span>
                            <div>
                                <strong style="color: #fff; display: block; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Teléfono Directo</strong>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', get_option('casa_opt_global_phone', '477 717 2600'))); ?>" style="color: inherit; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--color-accent)';" onmouseout="this.style.color='inherit';">
                                    <?php echo esc_html(get_option('casa_opt_global_phone', '477 717 2600')); ?>
                                </a>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.8rem; align-items: flex-start; color: #ccc;">
                            <span style="color: var(--color-accent); flex-shrink: 0; margin-top: 3px; display: inline-flex;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            </span>
                            <div>
                                <strong style="color: #fff; display: block; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Correo Electrónico</strong>
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
                                <strong style="color: #fff; display: block; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Horario de Atención</strong>
                                <span><?php echo esc_html(get_option('casa_opt_global_office_hours', 'Lunes a Viernes 9:00 am - 6:00 pm | Sábados 9:00 am - 2:00 pm')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- BARRA INFERIOR DE COPYRIGHT & PRIVACIDAD COMPACTA -->
            <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.2rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; color: #777; font-size: 0.78rem;">
                <div>COPYRIGHT © <?php echo date('Y'); ?> CASA DE PIEDRA. TODOS LOS DERECHOS RESERVADOS.</div>
                <div style="display: flex; gap: 1.5rem; align-items: center;">
                    <a href="javascript:void(0);" onclick="window.history.back();" style="color: var(--color-accent); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">← Regresar a página anterior</a>
                    <a href="<?php echo esc_url(home_url('/aviso-de-privacidad')); ?>" style="color: inherit; text-decoration: underline; transition: color 0.2s;" onmouseover="this.style.color='#fff';" onmouseout="this.style.color='inherit';">Aviso de Privacidad</a>
                </div>
            </div>
        </div>
    </footer>
    <?php 
    if (file_exists(get_template_directory() . '/inc/quote-modal.php')) {
        include get_template_directory() . '/inc/quote-modal.php';
    }
    if (file_exists(get_template_directory() . '/inc/privacy-cookie-modal.php')) {
        include get_template_directory() . '/inc/privacy-cookie-modal.php';
    }
    wp_footer(); 
    ?>
</body>
</html>