    </main> <!-- Cerrando main de header.php -->
    
    <footer style="background: linear-gradient(to bottom, rgba(8, 8, 8, 0.35) 0%, rgba(6, 6, 6, 0.88) 35%, #060606 100%); padding-top: 4rem; padding-bottom: 3rem; position: relative;">
        <div class="container">
            <!-- LUXURY DIVIDER justo donde termina el contenido de la página antes del footer -->
            <div class="luxury-divider" style="margin-top: 0; margin-bottom: 4.5rem;"></div>
            <div class="grid md:grid-cols-12 gap-12" style="padding-bottom: 4rem;">
                
                <!-- Brand & Socials -->
                <div class="md:col-span-4" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <?php 
                    $panel_logo = get_option('casa_opt_global_logo');
                    $logo_url = $panel_logo ? esc_url($panel_logo) : esc_url(get_template_directory_uri() . '/assets/images/logo-navbar-oficial.png');
                    ?>
                    <img 
                        src="<?php echo $logo_url; ?>" 
                        alt="Casa de Piedra" 
                        style="width: clamp(160px, 15vw, 240px); height: auto; opacity: 0.9;" 
                    />
                    <p class="text-body-lg" style="font-size: 0.9rem; max-width: 300px;">
                        La sede más emblemática de León, Guanajuato. Eventos sociales y empresariales de alto nivel desde 1845.
                    </p>
                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                        <?php 
                        $ig_url = get_option('casa_opt_global_instagram', 'https://instagram.com');
                        $fb_url = get_option('casa_opt_global_facebook', 'https://facebook.com');
                        $tk_url = get_option('casa_opt_global_tiktok', 'https://tiktok.com');
                        
                        $icon_style = "display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 1px solid var(--color-border); border-radius: 50%; color: var(--color-accent); text-decoration: none; transition: all 0.3s ease;";
                        $icon_hover = "onmouseover=\"this.style.background='var(--color-accent)'; this.style.color='#000';\" onmouseout=\"this.style.background='transparent'; this.style.color='var(--color-accent)';\"";
                        ?>
                        <?php if ($ig_url) : ?>
                        <a href="<?php echo esc_url($ig_url); ?>" target="_blank" rel="noreferrer" style="<?php echo $icon_style; ?>" <?php echo $icon_hover; ?> aria-label="Instagram">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($fb_url) : ?>
                        <a href="<?php echo esc_url($fb_url); ?>" target="_blank" rel="noreferrer" style="<?php echo $icon_style; ?>" <?php echo $icon_hover; ?> aria-label="Facebook">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/></svg>
                        </a>
                        <?php endif; ?>

                        <?php if ($tk_url) : ?>
                        <a href="<?php echo esc_url($tk_url); ?>" target="_blank" rel="noreferrer" style="<?php echo $icon_style; ?>" <?php echo $icon_hover; ?> aria-label="TikTok">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="md:col-span-4" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <h4 style="color: var(--color-text-primary); font-size: 1.2rem; font-family: var(--font-heading);">Contacto</h4>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; gap: 1rem; align-items: flex-start; color: var(--color-text-secondary);">
                            <span style="color: var(--color-accent); flex-shrink: 0; margin-top: 4px; display: inline-flex;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </span>
                            <span><?php echo esc_html(get_option('casa_opt_global_address', 'Av Cerro Gordo 270, Casa de Piedra, 37120 León de los Aldama, Gto.')); ?></span>
                        </div>
                        <div style="display: flex; gap: 1rem; align-items: center; color: var(--color-text-secondary);">
                            <span style="color: var(--color-accent); flex-shrink: 0; display: inline-flex;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </span>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', get_option('casa_opt_global_phone', '477 717 2600'))); ?>" style="color: inherit; text-decoration: none;">
                                <?php echo esc_html(get_option('casa_opt_global_phone', '477 717 2600')); ?>
                            </a>
                        </div>
                        <div style="display: flex; gap: 1rem; align-items: center; color: var(--color-text-secondary);">
                            <span style="color: var(--color-accent); flex-shrink: 0; display: inline-flex;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            </span>
                            <a href="mailto:<?php echo esc_attr(get_option('casa_opt_global_email', 'eventos@casadepiedraleon.mx')); ?>" style="color: inherit; text-decoration: none;">
                                <?php echo esc_html(get_option('casa_opt_global_email', 'eventos@casadepiedraleon.mx')); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="md:col-span-4" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <h4 style="color: var(--color-text-primary); font-size: 1.2rem; font-family: var(--font-heading);">Enlaces</h4>
                    <ul class="footer-links" style="margin-bottom: 1rem;">
                        <li>
                            <a href="javascript:void(0);" onclick="window.history.back();" style="color: var(--color-accent); font-weight: 600;">
                                ← Regresar a la página anterior
                            </a>
                        </li>
                    </ul>

                    <?php 
                    wp_nav_menu(array(
                        'theme_location' => 'menu-principal',
                        'container' => false,
                        'menu_class' => 'footer-links',
                        'depth' => 1 // Solo enlaces principales, ocultando submenús
                    ));
                    ?>
                    <style>
                        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.75rem; padding: 0; margin: 0; }
                        .footer-links a { color: var(--color-text-secondary); text-decoration: none; transition: color 0.3s; }
                        .footer-links a:hover { color: var(--color-accent); }
                    </style>
                </div>
            </div>

            <div style="border-top: 1px solid var(--color-border); padding-top: 2rem; display: flex; flex-direction: column; justify-content: space-between; align-items: center; gap: 1rem; text-align: center; color: var(--color-text-secondary); font-size: 0.8rem;">
                <div>COPYRIGHT © <?php echo date('Y'); ?> CASA DE PIEDRA. TODOS LOS DERECHOS RESERVADOS.</div>
                <a href="<?php echo esc_url(home_url('/aviso-de-privacidad')); ?>" style="color: inherit; text-decoration: underline;">Aviso de Privacidad</a>
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