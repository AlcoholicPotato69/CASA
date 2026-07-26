<?php
/**
 * Template Name: Contacto
 * Description: Redirige y abre automáticamente el Modal de Cotización de Casa de Piedra.
 */
get_header(); 
$horario_atencion = get_option('casa_opt_global_office_hours', 'Lunes a Viernes de 9:00 am a 6:00 pm | Sábados de 9:00 am a 2:00 pm');
?>

<?php $portada_url = get_option('casa_opt_contacto_portada') ?: get_template_directory_uri() . '/assets/images/salon_principal_1779523069698.png'; ?>
<section style="position: relative; z-index: 2; width: 100%; height: clamp(400px, 48vh, 550px); display: flex; align-items: center; justify-content: center; overflow: hidden; background: #080808;">
    <img src="<?php echo esc_url($portada_url); ?>" alt="Contacto Portada" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; filter: brightness(0.68);" class="gs-zoom-in" />
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(8,8,8,0.2) 0%, rgba(8,8,8,0.55) 75%, #080808 100%); z-index: 2; pointer-events: none;"></div>
    
    <div style="position: relative; z-index: 3; text-align: center; padding: 40px clamp(1rem, 4vw, 3rem) 0; max-width: 900px; margin: 0 auto;">
        <span class="text-script" style="color: var(--color-accent); font-size: 1.4rem; display: block; margin-bottom: 0.2rem;"><?php echo esc_html(get_option('casa_opt_contacto_subtitle', 'Atención Exclusiva')); ?></span>
        <h1 style="color: #fff; font-size: clamp(2.3rem, 4.5vw, 3.6rem); margin: 0 0 0.6rem 0; font-family: var(--font-heading); line-height: 1.1; text-shadow: 0 10px 30px rgba(0,0,0,0.85);"><?php echo esc_html(get_option('casa_opt_contacto_title', 'Contacto Oficial')); ?></h1>
        
        <!-- Ornament -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.8rem; margin: 0.8rem 0;">
            <span style="height: 1px; width: 50px; background: linear-gradient(to right, transparent, rgba(212,175,55,0.7)); display: inline-block;"></span>
            <span style="color: var(--color-accent); font-size: 0.85rem;">✦</span>
            <span style="height: 1px; width: 50px; background: linear-gradient(to left, transparent, rgba(212,175,55,0.7)); display: inline-block;"></span>
        </div>

        <p style="color: #eaeaea; font-size: clamp(1.1rem, 2vw, 1.4rem); font-family: var(--font-heading); font-style: italic; margin: 0.5rem auto 0.8rem; line-height: 1.4; text-shadow: 0 4px 15px rgba(0,0,0,0.85);">
            &ldquo;<?php echo esc_html(ucfirst(get_option('casa_opt_contacto_desc', 'Estamos a tu disposición para diseñar la celebración que mereces'))); ?>&rdquo;
        </p>

        <span style="color: var(--color-accent); font-size: 0.74rem; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 600; text-shadow: 0 2px 10px rgba(0,0,0,0.9); display: block; margin-top: 0.4rem;">
            Ex Hacienda Casa de Piedra &bull; Concierge &amp; Reservaciones
        </span>
    </div>
</section>

<div style="min-height: 40vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 3rem 1.5rem;">
    
    <div style="background: rgba(212,175,55,0.08); border: 1px solid rgba(212,175,55,0.28); border-radius: 12px; padding: 0.8rem 1.5rem; margin-bottom: 2rem; max-width: 550px; color: rgba(255,255,255,0.9); font-size: 0.95rem;">
        <span style="color: var(--color-accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; display: block; margin-bottom: 4px;">Horario de Atención</span>
        <span><?php echo esc_html($horario_atencion); ?></span>
    </div>

    <button type="button" id="open-quote-modal" class="btn-open-quote-modal" style="padding: 1.2rem 3rem; background: var(--color-accent); color: #000; border: none; border-radius: 9999px; font-weight: 600; font-size: 1.15rem; cursor: pointer; box-shadow: 0 10px 25px rgba(212,175,55,0.3);">
        Abrir Formulario de Cotización
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        if (typeof window.openQuoteModal === 'function') {
            window.openQuoteModal('');
        } else {
            const btn = document.querySelector('.btn-open-quote-modal');
            if (btn) btn.click();
        }
    }, 200);
});
</script>

<?php get_footer(); ?>
