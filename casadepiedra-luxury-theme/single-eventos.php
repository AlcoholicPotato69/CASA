<?php
/**
 * Plantilla para el CPT individual de Eventos
 */
get_header(); ?>

<main style="padding-top: clamp(8rem, 15vh, 12rem); padding-bottom: 6rem; min-height: 80vh;">
    <div class="container">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
            $btn_texto = get_post_meta(get_the_ID(), '_evento_boton_texto', true) ?: 'Me interesa';
            $btn_enlace = get_post_meta(get_the_ID(), '_evento_enlace', true);
            $link_url = !empty($btn_enlace) ? esc_url($btn_enlace) : '#';
        ?>
            <div style="margin-bottom: 2rem;">
                <a href="<?php echo esc_url(home_url('/eventos')); ?>" style="color: var(--color-accent); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-family: var(--font-body); font-size: 0.9rem;">
                    ← Volver a Eventos
                </a>
            </div>

            <div class="grid md:grid-cols-12 gap-12">
                <div class="md:col-span-7 lg:col-span-8 reveal-text">
                    <div style="border-radius: var(--radius-lg); overflow: hidden; height: 500px; position: relative;">
                        <?php if(has_post_thumbnail()) { ?>
                            <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title_attribute(); ?>" class="img-cover" style="width: 100%; height: 100%; object-fit: cover;" />
                        <?php } else { ?>
                            <div style="width: 100%; height: 100%; background-color: var(--color-surface); display: flex; align-items: center; justify-content: center; color: var(--color-text-secondary);">
                                Sin imagen destacada
                            </div>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="md:col-span-5 lg:col-span-4 reveal-text" style="display: flex; flex-direction: column; justify-content: center;">
                    <span class="text-caption" style="margin-bottom: 0.5rem; display: block;">Detalles del Evento</span>
                    <h1 class="text-h2" style="margin: 0 0 1.5rem 0; color: #fff;"><?php the_title(); ?></h1>
                    
                    <div class="text-body-lg" style="margin-bottom: 2rem;">
                        <?php the_content(); ?>
                    </div>
                    
                    <div style="margin-top: 1rem; border-top: 1px solid var(--color-border-inner); padding-top: 2rem;">
                        <a href="<?php echo $link_url; ?>" <?php if(!empty($btn_enlace)) echo 'target="_blank" rel="noopener noreferrer"'; ?> style="display: inline-flex; padding: 1rem 2.5rem; background: var(--color-accent); color: #000; border-radius: 9999px; text-decoration: none; font-weight: 600; font-family: var(--font-body); transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                            <?php echo esc_html($btn_texto); ?>
                        </a>
                        <?php if(empty($btn_enlace)): ?>
                            <p style="margin-top: 1rem; font-size: 0.85rem; color: var(--color-text-secondary);">* Añade un enlace en la configuración del evento para que el botón funcione.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer(); ?>
