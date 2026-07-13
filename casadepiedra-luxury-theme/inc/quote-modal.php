<?php
/**
 * Modal Global de Solicitud de Cotización (Quote Modal)
 * Disponible en todo el sitio para botones con ID open-quote-modal o clase btn-open-quote-modal
 */
?>
<!-- Quote Modal -->
<div id="quote-modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 99999; backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: clamp(0.75rem, 3vw, 1.5rem); box-sizing: border-box;">
    <style>
        .quote-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; }
        @media (min-width: 640px) { .quote-grid { grid-template-columns: 1fr 1fr; } }
        .luxury-card-scroll::-webkit-scrollbar { width: 6px; }
        .luxury-card-scroll::-webkit-scrollbar-track { background: transparent; }
        .luxury-card-scroll::-webkit-scrollbar-thumb { background: rgba(212, 175, 55, 0.5); border-radius: 10px; }
        .luxury-card-scroll::-webkit-scrollbar-thumb:hover { background: rgba(212, 175, 55, 0.8); }
        .quote-select option { background-color: #1a1a1a; color: #ffffff; }
        .flatpickr-calendar { background: #1a1a1a !important; border: 1px solid rgba(255,255,255,0.1) !important; box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important; }
        .flatpickr-day { color: #fff !important; }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay { background: var(--color-accent) !important; border-color: var(--color-accent) !important; color: #000 !important; }
        .flatpickr-day.inRange { background: rgba(212, 175, 55, 0.2) !important; border-color: transparent !important; box-shadow: none !important; }
        .flatpickr-month, .flatpickr-weekday { color: #fff !important; fill: #fff !important; }
        .flatpickr-time input { color: #fff !important; }
        
        .iti { width: 100%; color: #000 !important; }
        .iti__country-list { background-color: #1a1a1a !important; color: #fff !important; border: 1px solid rgba(255,255,255,0.2) !important; border-radius: 8px !important; z-index: 99999 !important; text-align: left !important; }
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

    <div class="luxury-card" style="position: relative; width: 100%; max-width: 620px; padding: 0; max-height: 90vh; display: flex; flex-direction: column; overflow: hidden; box-sizing: border-box; background: #0e0e0e; border: 1px solid rgba(212,175,55,0.4); border-radius: 20px; box-shadow: 0 25px 70px rgba(0,0,0,0.95), 0 0 40px rgba(212,175,55,0.15); margin: auto;">
        <button id="close-quote-modal" style="position: absolute; top: 1rem; right: 1rem; background: rgba(0,0,0,0.5); border: none; color: #fff; font-size: 1.8rem; cursor: pointer; z-index: 10; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">&times;</button>
        <div class="luxury-card-scroll" style="padding: 2.5rem 2rem; overflow-y: auto; flex: 1; box-sizing: border-box; width: 100%;">
            <h3 class="text-h3" style="margin-bottom: 1rem; color: var(--color-accent); text-align: center; font-size: clamp(1.5rem, 4vw, 2rem);">Solicitar Cotización</h3>
            <?php
            $horario_modal = get_option('casa_opt_global_office_hours', 'Lunes a Viernes de 9:00 am a 6:00 pm | Sábados de 9:00 am a 2:00 pm');
            ?>
            <div style="background: rgba(212,175,55,0.08); border: 1px solid rgba(212,175,55,0.28); border-radius: 12px; padding: 0.65rem 1rem; margin-bottom: 1.5rem; text-align: center; color: rgba(255,255,255,0.92); font-size: 0.84rem; line-height: 1.4;">
                <span style="color: var(--color-accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; display: block; margin-bottom: 2px;">Horario de Atención</span>
                <span><?php echo esc_html($horario_modal); ?></span>
            </div>
        
            <form id="quote-form" style="display: flex; flex-direction: column; gap: 1.2rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--color-accent); font-family: var(--font-body); font-size: 0.92rem; font-weight: 600;">Tipo de Solicitud / Categoría *</label>
                    <select name="quote_category" id="quote_category" class="quote-select" required style="width: 100%; padding: 0.85rem; background: rgba(212,175,55,0.08); border: 1.5px solid var(--color-accent); color: #fff; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        <option value="cotizacion">Cotización de Espacios / Eventos</option>
                        <option value="generales">Temas generales / Información</option>
                        <option value="proveedores">Propuestas de proveedores</option>
                        <option value="propuesta_eventos">Propuesta de eventos comerciales / corporativos</option>
                    </select>
                </div>

                <!-- Datos de Contacto comunes (siempre visibles) -->
                <div class="quote-grid">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: rgba(255,255,255,0.8); font-family: var(--font-body); font-size: 0.9rem;">Nombre Completo *</label>
                        <input type="text" name="quote_name" required style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: rgba(255,255,255,0.8); font-family: var(--font-body); font-size: 0.9rem;">Correo *</label>
                        <input type="email" name="quote_email" required style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px;">
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: rgba(255,255,255,0.8); font-family: var(--font-body); font-size: 0.9rem;">Teléfono *</label>
                    <input type="tel" id="quote_phone" name="quote_phone" required style="width: 100%; padding-top: 0.8rem; padding-bottom: 0.8rem; padding-right: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px;">
                </div>

                <!-- BLOQUE 1: Cotización de Espacios -->
                <div id="fields-cotizacion" style="display: flex; flex-direction: column; gap: 1.2rem;">
                    <div class="quote-grid">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: rgba(255,255,255,0.8); font-family: var(--font-body); font-size: 0.9rem;">Fecha de Evento *</label>
                            <input type="text" name="quote_date" id="quote_date" placeholder="Selecciona fecha(s)" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px; cursor: pointer;">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; color: rgba(255,255,255,0.8); font-family: var(--font-body); font-size: 0.9rem;">Tipo de Evento *</label>
                            <select name="quote_type" id="quote_type" class="quote-select" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px;">
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
                            <label style="display: block; margin-bottom: 0.5rem; color: rgba(255,255,255,0.8); font-family: var(--font-body); font-size: 0.9rem;">Salón (Espacio) *</label>
                            <select name="quote_salon" id="quote_salon" class="quote-select" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px; appearance: none;">
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
                            <label style="display: block; margin-bottom: 0.5rem; color: rgba(255,255,255,0.8); font-family: var(--font-body); font-size: 0.9rem;">Cantidad de Personas *</label>
                            <select name="quote_capacity" id="quote_capacity" class="quote-select" style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px; appearance: none; cursor: pointer;">
                                <option value="" disabled selected>Primero elige un espacio</option>
                            </select>
                            <div id="quote_capacity_warning" style="display: none; color: #fbbf24; font-size: 0.8rem; margin-top: 6px; line-height: 1.35; background: rgba(251, 191, 36, 0.1); padding: 6px 10px; border-radius: 6px; border: 1px solid rgba(251, 191, 36, 0.3);">
                                ⚠️ Por favor, elige primero un espacio para ver los rangos disponibles.
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label id="label-comments" style="display: block; margin-bottom: 0.5rem; color: rgba(255,255,255,0.8); font-family: var(--font-body); font-size: 0.9rem;">Comentarios / Detalles *</label>
                    <textarea name="quote_comments" id="quote_comments" rows="3" placeholder="Escribe aquí tu mensaje, detalles o consulta..." style="width: 100%; padding: 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; border-radius: 8px; resize: none;"></textarea>
                </div>

                <div id="quote-disclaimer" style="font-size: 0.8rem; color: rgba(255,255,255,0.6); font-family: var(--font-body); text-align: center; margin-top: 0.2rem;">
                    <em>* El seleccionar una fecha no garantiza una reservación, la fecha real está sujeta a disponibilidad.</em>
                </div>
            
            <button type="submit" id="quote-submit-btn" style="width: 100%; padding: 1rem; background: var(--color-accent); color: #000; border: none; cursor: pointer; border-radius: 9999px; font-weight: 600; font-family: var(--font-body); transition: transform 0.3s; margin-top: 0.5rem;">
                Enviar Solicitud
            </button>
            <div id="quote-form-msg" style="text-align: center; margin-top: 0.5rem; font-family: var(--font-body); display: none; font-size: 0.9rem;"></div>
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
