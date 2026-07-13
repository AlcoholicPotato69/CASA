<?php
/**
 * Template Name: Contacto
 * Description: Redirige y abre automáticamente el Modal de Cotización de Casa de Piedra.
 */
get_header(); 
$horario_atencion = get_option('casa_opt_global_office_hours', 'Lunes a Viernes de 9:00 am a 6:00 pm | Sábados de 9:00 am a 2:00 pm');
?>

<div style="min-height: 70vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 4rem 1.5rem;">
    <span class="text-script" style="color: var(--color-accent); font-size: 1.8rem; margin-bottom: 0.5rem; display: block;">Casa de Piedra</span>
    <h1 class="text-hero" style="color: #fff; font-size: clamp(2.2rem, 4vw, 3.5rem); margin-bottom: 1.2rem;">Solicita tu Cotización</h1>
    
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
