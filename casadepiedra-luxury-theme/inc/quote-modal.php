<?php
/**
 * Modal Global de Solicitud de Cotización (Quote Modal)
 * Disponible en todo el sitio para botones con ID open-quote-modal o clase btn-open-quote-modal
 */
?>
<!-- Quote Modal -->
<div id="quote-modal-overlay" data-lenis-prevent="true" style="display: none; position: fixed !important; inset: 0 !important; width: 100vw !important; height: 100vh !important; background: rgba(0,0,0,0.88) !important; z-index: 9999999 !important; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); align-items: center; justify-content: center; padding: 12px; box-sizing: border-box; overflow-y: auto; overscroll-behavior: contain;">
    <style>
        .quote-grid { display: grid; grid-template-columns: 1fr; gap: 0.75rem; }
        @media (min-width: 640px) { .quote-grid { grid-template-columns: 1fr 1fr; } }
        .luxury-card-scroll::-webkit-scrollbar { width: 6px; }
        .luxury-card-scroll::-webkit-scrollbar-track { background: transparent; }
        .luxury-card-scroll::-webkit-scrollbar-thumb { background: rgba(212, 175, 55, 0.5); border-radius: 10px; }
        .luxury-card-scroll::-webkit-scrollbar-thumb:hover { background: rgba(212, 175, 55, 0.8); }
        .quote-select option { background-color: #1a1a1a; color: #ffffff; }
        .flatpickr-calendar { background: #1a1a1a !important; border: 1px solid rgba(255,255,255,0.1) !important; box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important; z-index: 99999999 !important; }
        .flatpickr-day { color: #fff !important; }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay { background: var(--color-accent) !important; border-color: var(--color-accent) !important; color: #000 !important; }
        .flatpickr-day.inRange { background: rgba(212, 175, 55, 0.2) !important; border-color: transparent !important; box-shadow: none !important; }
        .flatpickr-month, .flatpickr-weekday { color: #fff !important; fill: #fff !important; }
        .flatpickr-time input { color: #fff !important; }
        
        .iti { width: 100%; color: #000 !important; }
        .iti__country-list { background-color: #1a1a1a !important; color: #fff !important; border: 1px solid rgba(255,255,255,0.2) !important; border-radius: 8px !important; z-index: 99999999 !important; text-align: left !important; }
        .iti__country.iti__highlight { background-color: rgba(255,255,255,0.1) !important; }
        .iti__divider { border-bottom: 1px solid rgba(255,255,255,0.1) !important; }
        .iti__dial-code { color: #aaa !important; }
        .iti__selected-dial-code { color: #fff !important; }
        .iti__country-name { color: #fff !important; }
    </style>
    
    <!-- Libraries CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <div class="luxury-card" style="position: relative; width: 100%; max-width: 660px; padding: 0; max-height: 94vh; display: flex; flex-direction: column; overflow: hidden; box-sizing: border-box; background: #0e0e0e; border: 1px solid rgba(212,175,55,0.45); border-radius: 18px; box-shadow: 0 25px 70px rgba(0,0,0,0.95), 0 0 40px rgba(212,175,55,0.18); margin: auto;">
        <button id="close-quote-modal" type="button" style="position: absolute; top: 0.8rem; right: 0.8rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(212,175,55,0.3); color: #fff; font-size: 1.4rem; cursor: pointer; z-index: 20; border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--color-accent)'; this.style.color='#000';" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';">&times;</button>
        
        <div class="luxury-card-scroll" data-lenis-prevent="true" style="padding: 1.5rem 1.8rem; overflow-y: auto; overscroll-behavior: contain; flex: 1; box-sizing: border-box; width: 100%;">
            <h3 class="text-h3" style="margin-bottom: 0.6rem; color: var(--color-accent); text-align: center; font-size: clamp(1.35rem, 3.2vw, 1.7rem); line-height: 1.2;">Solicitar Cotización</h3>
            <?php
            $horario_modal = get_option('casa_opt_global_office_hours', 'Lunes a Viernes de 9:00 am a 6:00 pm | Sábados de 9:00 am a 2:00 pm');
            ?>
            <div style="background: rgba(212,175,55,0.08); border: 1px solid rgba(212,175,55,0.28); border-radius: 8px; padding: 0.45rem 0.8rem; margin-bottom: 1rem; text-align: center; color: rgba(255,255,255,0.92); font-size: 0.78rem; line-height: 1.35;">
                <span style="color: var(--color-accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; display: inline-block; margin-right: 6px;">🕒 Horario:</span>
                <span><?php echo esc_html($horario_modal); ?></span>
            </div>
        
            <form id="quote-form" style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div class="quote-grid">
                    <div>
                        <label style="display: block; margin-bottom: 0.35rem; color: var(--color-accent); font-family: var(--font-body); font-size: 0.84rem; font-weight: 600;">Categoría de Solicitud *</label>
                        <select name="quote_category" id="quote_category" class="quote-select" required style="width: 100%; padding: 0.65rem 0.75rem; background: rgba(212,175,55,0.08); border: 1.5px solid var(--color-accent); color: #fff; border-radius: 8px; font-size: 0.86rem; font-weight: 600; cursor: pointer;">
                            <option value="cotizacion">Cotización de Espacios / Eventos</option>
                            <option value="generales">Temas generales / Información</option>
                            <option value="proveedores">Propuestas de proveedores</option>
                            <option value="propuesta_eventos">Propuesta de eventos corporativos</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.35rem; color: rgba(255,255,255,0.85); font-family: var(--font-body); font-size: 0.84rem;">Teléfono de Contacto *</label>
                        <input type="tel" id="quote_phone" name="quote_phone" required style="width: 100%; padding: 0.65rem 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 8px; font-size: 0.86rem;">
                    </div>
                </div>

                <!-- Datos de Contacto comunes (siempre visibles) -->
                <div class="quote-grid">
                    <div>
                        <label style="display: block; margin-bottom: 0.35rem; color: rgba(255,255,255,0.85); font-family: var(--font-body); font-size: 0.84rem;">Nombre Completo *</label>
                        <input type="text" name="quote_name" required placeholder="Ej. Roberto Martínez" style="width: 100%; padding: 0.65rem 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 8px; font-size: 0.86rem;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.35rem; color: rgba(255,255,255,0.85); font-family: var(--font-body); font-size: 0.84rem;">Correo Electrónico *</label>
                        <input type="email" name="quote_email" required placeholder="nombre@empresa.com" style="width: 100%; padding: 0.65rem 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 8px; font-size: 0.86rem;">
                    </div>
                </div>

                <!-- BLOQUE 1: Cotización de Espacios -->
                <div id="fields-cotizacion" style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div class="quote-grid">
                        <div>
                            <label style="display: block; margin-bottom: 0.35rem; color: rgba(255,255,255,0.85); font-family: var(--font-body); font-size: 0.84rem;">Fecha del Evento *</label>
                            <input type="text" name="quote_date" id="quote_date" placeholder="Selecciona fecha(s)" style="width: 100%; padding: 0.65rem 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 8px; font-size: 0.86rem; cursor: pointer;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.35rem; color: rgba(255,255,255,0.85); font-family: var(--font-body); font-size: 0.84rem;">Tipo de Evento *</label>
                            <select name="quote_type" id="quote_type" class="quote-select" style="width: 100%; padding: 0.65rem 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 8px; font-size: 0.86rem;">
                                <option value="" disabled selected>Seleccione el tipo de evento</option>
                                <option value="Boda">Boda</option>
                                <option value="Festejos corporativos">Festejos corporativos</option>
                                <option value="Cumpleaños / Aniversario">Cumpleaños / Aniversario</option>
                                <option value="Bautizos / Primera Comunión">Bautizos / Primera Comunión</option>
                                <option value="Convención / Congreso">Convención / Congreso</option>
                                <option value="Evento especial">Evento especial</option>
                            </select>
                        </div>
                    </div>

                    <div class="quote-grid">
                        <div>
                            <label style="display: block; margin-bottom: 0.35rem; color: rgba(255,255,255,0.85); font-family: var(--font-body); font-size: 0.84rem;">Salón o Espacio *</label>
                            <select name="quote_salon" id="quote_salon" class="quote-select" style="width: 100%; padding: 0.65rem 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 8px; font-size: 0.86rem; appearance: none;">
                                <option value="" disabled selected>Seleccione un salón</option>
                                <?php
                                $espacios = new WP_Query(array('post_type' => 'espacios', 'post_status' => 'publish', 'posts_per_page' => -1));
                                if($espacios->have_posts()) :
                                    while($espacios->have_posts()) : $espacios->the_post();
                                        $rango_personas = get_post_meta(get_the_ID(), '_espacio_rango_personas', true);
                                        echo '<option value="' . esc_attr(get_the_title()) . '" data-ranges="' . esc_attr($rango_personas) . '">' . esc_html(get_the_title()) . '</option>';
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.35rem; color: rgba(255,255,255,0.85); font-family: var(--font-body); font-size: 0.84rem;">Capacidad / Personas *</label>
                            <select name="quote_capacity" id="quote_capacity" class="quote-select" style="width: 100%; padding: 0.65rem 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 8px; font-size: 0.86rem; appearance: none; cursor: pointer;">
                                <option value="" disabled selected>Primero elige un espacio</option>
                            </select>
                            <div id="quote_capacity_warning" style="display: none; color: #fbbf24; font-size: 0.76rem; margin-top: 4px; line-height: 1.3; background: rgba(251, 191, 36, 0.1); padding: 4px 8px; border-radius: 6px; border: 1px solid rgba(251, 191, 36, 0.3);">
                                ⚠️ Elige primero un espacio para ver los rangos.
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label id="label-comments" style="display: block; margin-bottom: 0.35rem; color: rgba(255,255,255,0.85); font-family: var(--font-body); font-size: 0.84rem;">Comentarios / Detalles *</label>
                    <textarea name="quote_comments" id="quote_comments" rows="2" placeholder="Escribe aquí tu consulta, horario deseado o requerimientos especiales..." style="width: 100%; padding: 0.65rem 0.75rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 8px; font-size: 0.86rem; resize: none;"></textarea>
                </div>

                <div id="quote-disclaimer" style="font-size: 0.75rem; color: rgba(255,255,255,0.55); font-family: var(--font-body); text-align: center; margin-top: 0.1rem; line-height: 1.3;">
                    <em>* Al seleccionar una fecha verificaremos disponibilidad inmediata para tu evento.</em>
                </div>
            
            <button type="submit" id="quote-submit-btn" style="width: 100%; padding: 0.75rem 1.5rem; background: var(--color-accent); color: #000; border: none; cursor: pointer; border-radius: 9999px; font-weight: 700; font-size: 0.94rem; font-family: var(--font-body); transition: all 0.25s; margin-top: 0.3rem; box-shadow: 0 4px 15px rgba(212,175,55,0.3);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(212,175,55,0.45)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 15px rgba(212,175,55,0.3)';">
                Enviar Solicitud
            </button>
            <div id="quote-form-msg" style="text-align: center; margin-top: 0.4rem; font-family: var(--font-body); display: none; font-size: 0.86rem;"></div>
        </form>

        <div id="quote-success-screen" style="display: none; text-align: center; padding: 2rem 0;">
            <div style="font-size: 4rem; color: #4ade80; margin-bottom: 1rem;">&#10003;</div>
            <h3 class="text-h3" style="color: var(--color-accent); margin-bottom: 1rem;">¡Solicitud Enviada!</h3>
            <p style="color: rgba(255,255,255,0.8); font-family: var(--font-body); margin-bottom: 2rem; line-height: 1.6;">Gracias por tu interés en Casa de Piedra.<br>Hemos enviado una confirmación a tu correo electrónico con los datos de tu evento. Pronto un asesor se pondrá en contacto contigo.</p>
            <button type="button" id="quote-close-success" style="padding: 1rem 2.5rem; background: var(--color-accent); color: #000; border: none; cursor: pointer; border-radius: 9999px; font-weight: 600; font-family: var(--font-body); transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                Cerrar
            </button>
        </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#quote_date", {
            mode: "range",
            minDate: "today",
            dateFormat: "d-m-Y",
            locale: "es",
            disableMobile: "true"
        });
    }
    const btnClose = document.getElementById('close-quote-modal');
    const overlay = document.getElementById('quote-modal-overlay');
    const form = document.getElementById('quote-form');
    const msgDiv = document.getElementById('quote-form-msg');
    const submitBtn = document.getElementById('quote-submit-btn');
    const successScreen = document.getElementById('quote-success-screen');
    const btnCloseSuccess = document.getElementById('quote-close-success');

    const phoneInputField = document.querySelector("#quote_phone");
    let phoneInput = null;
    if(typeof window.intlTelInput !== 'undefined' && phoneInputField) {
        phoneInput = window.intlTelInput(phoneInputField, {
            preferredCountries: ["mx", "us", "es"],
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
    }

    const quoteSalon = document.getElementById('quote_salon');
    const quoteCapacity = document.getElementById('quote_capacity');
    const quoteCapacityWarning = document.getElementById('quote_capacity_warning');

    const normalizeStr = (str) => (str || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();

    const updateCapacityOptions = () => {
        if (!quoteSalon || !quoteCapacity) return;
        const selectedVal = quoteSalon.value ? quoteSalon.value.trim().toLowerCase() : '';
        
        quoteCapacity.innerHTML = '';

        if (!selectedVal) {
            quoteCapacity.innerHTML = '<option value="" disabled selected>Primero elige un espacio</option>';
            return;
        }

        if (quoteCapacityWarning) {
            quoteCapacityWarning.style.display = 'none';
        }

        let ranges = [];
        const selectedOpt = quoteSalon.options[quoteSalon.selectedIndex];
        const customRanges = selectedOpt ? selectedOpt.getAttribute('data-ranges') : '';
        if (customRanges && customRanges.trim() !== '') {
            ranges = customRanges.split(/[,;\n]/).map(r => r.trim()).filter(Boolean);
        } else if (selectedVal.includes('jardín') || selectedVal.includes('jardin')) {
            ranges = [
                '1 a 300 personas',
                'de 301 a 900 personas',
                'de 901 a 1500 personas'
            ];
        } else if (selectedVal.includes('pavorreales')) {
            ranges = [
                '1 a 90 personas'
            ];
        } else if (selectedVal.includes('mezquite')) {
            ranges = [
                '1 a 150 personas'
            ];
        } else if (selectedVal.includes('principal')) {
            ranges = [
                '1 a 400 personas',
                '401 a 800 personas'
            ];
        } else {
            ranges = [
                '1 a 150 personas',
                '151 a 400 personas',
                'Más de 400 personas'
            ];
        }

        const defaultOpt = document.createElement('option');
        defaultOpt.value = '';
        defaultOpt.disabled = true;
        defaultOpt.selected = true;
        defaultOpt.textContent = 'Selecciona cantidad de personas';
        quoteCapacity.appendChild(defaultOpt);

        ranges.forEach(range => {
            const opt = document.createElement('option');
            opt.value = range;
            opt.textContent = range;
            quoteCapacity.appendChild(opt);
        });
    };

    if (quoteSalon) {
        quoteSalon.addEventListener('change', updateCapacityOptions);
    }

    if (quoteCapacity) {
        const warnIfNoSalon = () => {
            if (!quoteSalon || !quoteSalon.value) {
                if (quoteCapacityWarning) {
                    quoteCapacityWarning.style.display = 'block';
                }
                if (quoteSalon) {
                    quoteSalon.style.borderColor = 'var(--color-accent)';
                    quoteSalon.style.boxShadow = '0 0 12px rgba(212, 175, 55, 0.5)';
                    setTimeout(() => {
                        quoteSalon.style.borderColor = 'rgba(255,255,255,0.1)';
                        quoteSalon.style.boxShadow = 'none';
                    }, 2000);
                }
            }
        };
        quoteCapacity.addEventListener('mousedown', warnIfNoSalon);
        quoteCapacity.addEventListener('focus', warnIfNoSalon);
        quoteCapacity.addEventListener('click', warnIfNoSalon);
    }

    const quoteCategory = document.getElementById('quote_category');
    const fieldsCotizacion = document.getElementById('fields-cotizacion');
    const quoteDateInput = document.getElementById('quote_date');
    const quoteTypeInput = document.getElementById('quote_type');

    const updateCategoryFields = () => {
        if (!quoteCategory || !fieldsCotizacion) return;
        const isCotizacion = (quoteCategory.value === 'cotizacion');
        if (isCotizacion) {
            fieldsCotizacion.style.display = 'flex';
            if (quoteSalon) quoteSalon.required = true;
            if (quoteCapacity) quoteCapacity.required = true;
            if (quoteDateInput) quoteDateInput.required = true;
            if (quoteTypeInput) quoteTypeInput.required = true;
        } else {
            fieldsCotizacion.style.display = 'none';
            if (quoteSalon) quoteSalon.required = false;
            if (quoteCapacity) quoteCapacity.required = false;
            if (quoteDateInput) quoteDateInput.required = false;
            if (quoteTypeInput) quoteTypeInput.required = false;
        }
    };

    if (quoteCategory) {
        quoteCategory.addEventListener('change', updateCategoryFields);
        updateCategoryFields();
    }

    const resetModal = () => {
        if (!overlay) return;
        overlay.style.display = 'none';
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
        if (window.lenis && typeof window.lenis.start === 'function') {
            window.lenis.start();
        }
        if (form) {
            form.style.display = 'flex';
            form.reset();
            updateCategoryFields();
        }
        if (quoteCapacity) {
            quoteCapacity.innerHTML = '<option value="" disabled selected>Primero elige un espacio</option>';
        }
        if (quoteCapacityWarning) quoteCapacityWarning.style.display = 'none';
        if (successScreen) successScreen.style.display = 'none';
        if (msgDiv) msgDiv.style.display = 'none';
        if (submitBtn) {
            submitBtn.textContent = 'Enviar Solicitud';
            submitBtn.disabled = false;
        }
    };

    if(overlay) {
        const openQuoteModalForSalon = (salonAttr) => {
            if (quoteSalon && salonAttr && salonAttr.trim() !== '') {
                if (quoteCategory) {
                    quoteCategory.value = 'cotizacion';
                    updateCategoryFields();
                }
                const targetNorm = normalizeStr(salonAttr);
                let matched = false;
                for(let i = 0; i < quoteSalon.options.length; i++) {
                    const optNorm = normalizeStr(quoteSalon.options[i].value);
                    if (optNorm === targetNorm || optNorm.includes(targetNorm) || targetNorm.includes(optNorm)) {
                        quoteSalon.selectedIndex = i;
                        matched = true;
                        break;
                    }
                }
                if (!matched) {
                    const newOpt = document.createElement('option');
                    newOpt.value = salonAttr.trim();
                    newOpt.textContent = salonAttr.trim();
                    quoteSalon.appendChild(newOpt);
                    quoteSalon.value = salonAttr.trim();
                }
                updateCapacityOptions();
            } else {
                if (quoteCategory) {
                    quoteCategory.value = 'generales';
                    updateCategoryFields();
                }
                if (quoteSalon) {
                    quoteSalon.selectedIndex = 0;
                    updateCapacityOptions();
                }
            }
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
            if (window.lenis && typeof window.lenis.stop === 'function') {
                window.lenis.stop();
            }
        };

        window.openQuoteModal = openQuoteModalForSalon;

        // Delegación global en fase de CAPTURA para garantizar que TODOS los botones de cotizar o contacto abran el modal en la página actual sin redireccionar
        document.addEventListener('click', (e) => {
            const quoteBtn = e.target.closest('#open-quote-modal, .btn-open-quote-modal, [href="#quote-modal"], [data-open-quote="true"], [data-salon], a[href*="contacto"], a[href*="Contacto"], .nav-item-contacto a, [href*="page-contacto"]');
            if (quoteBtn) {
                e.preventDefault();
                e.stopPropagation();
                const salonAttr = quoteBtn.getAttribute('data-salon') || '';
                openQuoteModalForSalon(salonAttr);
            }
        }, true);
        
        if (btnClose) btnClose.addEventListener('click', resetModal);
        if (btnCloseSuccess) btnCloseSuccess.addEventListener('click', resetModal);
        
        overlay.addEventListener('click', (e) => {
            if(e.target === overlay) {
                resetModal();
            }
        });
    }

    if(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if(phoneInput && !phoneInput.isValidNumber()) {
                msgDiv.style.display = 'block';
                msgDiv.style.color = '#f87171';
                msgDiv.textContent = 'Por favor, ingrese un número de teléfono válido.';
                return;
            }

            const formData = new FormData(form);
            formData.append('action', 'casa_send_cotizacion');
            
            if(phoneInput) {
                formData.set('quote_phone', phoneInput.getNumber());
            }
            
            submitBtn.textContent = 'Enviando...';
            submitBtn.disabled = true;

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    form.style.display = 'none';
                    successScreen.style.display = 'block';
                } else {
                    msgDiv.style.display = 'block';
                    msgDiv.style.color = '#f87171';
                    msgDiv.textContent = data.data || 'Ocurrió un error. Inténtalo de nuevo.';
                    submitBtn.textContent = 'Enviar Solicitud';
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                msgDiv.style.display = 'block';
                msgDiv.style.color = '#f87171';
                msgDiv.textContent = 'Ocurrió un error de conexión.';
                submitBtn.textContent = 'Enviar Solicitud';
                submitBtn.disabled = false;
            });
        });
    }
});
</script>
