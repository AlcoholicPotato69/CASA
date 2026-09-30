<?php
/**
 * 404 real: status HTTP correcto + rutas canónicas de Casa de Piedra.
 */
get_header();
status_header(404);
nocache_headers();
?>
<section class="casa-404" style="min-height:70vh; display:flex; align-items:center; justify-content:center; padding:6rem 1.25rem 4rem; background:#080808;">
    <div style="max-width:720px; text-align:center;">
        <span class="text-script" style="color:var(--color-accent); display:block; margin-bottom:0.4rem;">Página no encontrada</span>
        <h1 class="text-hero" style="color:#fff; font-size:clamp(2rem,4vw,3.1rem); margin:0 0 1rem;">Esta ruta no existe</h1>
        <p style="color:rgba(255,255,255,0.74); line-height:1.65; margin:0 0 1.8rem;">
            Casa de Piedra es el recinto de eventos y restaurantes en Cerro Gordo, León.
            Usa estas secciones canónicas para jardín, salones, gastronomía o cotizar.
        </p>
        <p style="display:flex; flex-wrap:wrap; gap:0.75rem; justify-content:center; margin:0;">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary">Inicio</a>
            <a href="<?php echo esc_url(home_url('/espacios/')); ?>" class="btn-primary">Espacios</a>
            <a href="<?php echo esc_url(home_url('/restaurantes/')); ?>" class="btn-primary">Restaurantes</a>
            <a href="<?php echo esc_url(home_url('/contacto/')); ?>" class="btn-primary">Cotizar</a>
        </p>
    </div>
</section>
<?php
get_footer();
