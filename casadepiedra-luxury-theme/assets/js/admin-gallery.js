jQuery(document).ready(function($){
    var meta_image_frame;
    var selectedTagId = 0;
    var cfg = window.casaGaleriaAdmin || {};

    function status($el, msg, isError) {
        if (!$el.length) return;
        $el.toggleClass('is-error', !!isError).text(msg || '');
    }

    function tagsFromPills() {
        var tags = [];
        $('#casa-gal-pills .casa-gal-pill').each(function(){
            tags.push({
                id: parseInt($(this).data('id'), 10),
                name: $(this).find('.casa-gal-pill-name').text().trim()
            });
        });
        return tags;
    }

    function cardTerms($card) {
        return ($card.attr('data-terms') || '').split(',').map(function(v){ return parseInt(v, 10); }).filter(function(n){ return n > 0; });
    }

    function setCardTerms($card, ids) {
        $card.attr('data-terms', ids.join(','));
        $card.find('.casa-gal-assign').each(function(){
            var id = parseInt($(this).data('term'), 10);
            $(this).toggleClass('is-on', ids.indexOf(id) !== -1);
        });
    }

    function ajax(action, data) {
        data = data || {};
        data.action = action;
        data.nonce = cfg.nonce;
        return $.post(cfg.ajax, data);
    }

    function saveGalleryIds() {
        if (!$('#etiquetas-galeria').length) return;
        var ids = $('#casadepiedra_gallery_ids').val() || '';
        if (!cfg.ajax) return;
        ajax('casa_galeria_save_ids', { ids: ids }).done(function(res){
            if (res && res.success) {
                status($('#casa-gal-photo-status'), 'Galería actualizada.');
            } else {
                status($('#casa-gal-photo-status'), 'No se pudo guardar la galería.', true);
            }
        });
    }

    function saveImageTags($card, ids) {
        if (!cfg.ajax) return;
        ajax('casa_galeria_set_image_tags', {
            attachment_id: $card.data('id'),
            'term_ids[]': ids
        }).done(function(res){
            if (res && res.success) {
                status($('#casa-gal-photo-status'), 'Etiqueta guardada.');
            } else {
                status($('#casa-gal-photo-status'), (res && res.data && res.data.message) || 'No se pudo guardar.', true);
            }
        });
    }

    function renderMiniButtons($card) {
        var ids = cardTerms($card);
        var html = tagsFromPills().map(function(tag){
            var on = ids.indexOf(tag.id) !== -1 ? ' is-on' : '';
            return '<button type="button" class="casa-gal-assign' + on + '" data-term="' + tag.id + '">' + $('<div>').text(tag.name).html() + '</button>';
        }).join('');
        var $body = $card.find('.casa-gal-card-body');
        if (!$body.length) {
            $card.append('<div class="casa-gal-card-body"></div>');
            $body = $card.find('.casa-gal-card-body');
        }
        var $mini = $body.find('.casa-gal-mini');
        if (!$mini.length) {
            $body.prepend('<div class="casa-gal-mini"></div>');
            $mini = $body.find('.casa-gal-mini');
        }
        $mini.html(html);
        if (!$body.find('.casa-gal-remove-text').length) {
            $body.append('<button type="button" class="casa-gal-remove-text casa-remove-single-img-btn" data-id="' + $card.attr('data-id') + '">Quitar foto</button>');
        }
    }

    function refreshAllMinis() {
        $('#casadepiedra_gallery_preview .casa-gal-card').each(function(){
            renderMiniButtons($(this));
        });
    }

    function pillHtml(tag) {
        return '<span class="casa-gal-pill" data-id="' + tag.id + '" data-slug="' + (tag.slug || '') + '">' +
            '<span class="casa-gal-pill-name"></span>' +
            '<button type="button" class="casa-gal-rename" title="Renombrar">✎</button>' +
            '<button type="button" class="casa-gal-delete" title="Eliminar">×</button>' +
            '</span>';
    }

    function updatePaintHint() {
        var name = $('#casa-gal-pills .casa-gal-pill.is-selected .casa-gal-pill-name').text();
        if (name) {
            $('#casa-gal-paint-hint').text('Etiqueta activa: «' + name + '». Toca una foto para ponérsela o quitársela.');
        } else {
            $('#casa-gal-paint-hint').text('Elige una etiqueta para usarla. Luego, en el paso 2, toca las fotos.');
        }
    }

    $('#casa-gal-create-tag').on('click', function(e){
        e.preventDefault();
        var name = $.trim($('#casa-gal-new-tag').val() || '');
        if (!name) {
            status($('#casa-gal-tag-status'), 'Escribe el nombre de la etiqueta.', true);
            return;
        }
        ajax('casa_galeria_create_tag', { name: name }).done(function(res){
            if (!res || !res.success) {
                status($('#casa-gal-tag-status'), (res && res.data && res.data.message) || 'No se pudo crear.', true);
                return;
            }
            var $pill = $(pillHtml(res.data.tag));
            $pill.find('.casa-gal-pill-name').text(res.data.tag.name);
            $('#casa-gal-pills').append($pill);
            $('#casa-gal-new-tag').val('');
            refreshAllMinis();
            status($('#casa-gal-tag-status'), 'Etiqueta «' + res.data.tag.name + '» creada. Elígela y toca las fotos.');
        });
    });

    $('#casa-gal-new-tag').on('keydown', function(e){
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#casa-gal-create-tag').trigger('click');
        }
    });

    $(document).on('click', '.casa-gal-pill', function(e){
        if ($(e.target).closest('button').length) return;
        $('#casa-gal-pills .casa-gal-pill').removeClass('is-selected');
        $(this).addClass('is-selected');
        selectedTagId = parseInt($(this).data('id'), 10);
        updatePaintHint();
    });

    $(document).on('click', '.casa-gal-rename', function(e){
        e.preventDefault();
        e.stopPropagation();
        var $pill = $(this).closest('.casa-gal-pill');
        var current = $pill.find('.casa-gal-pill-name').text();
        var next = window.prompt('Nuevo nombre de la etiqueta:', current);
        if (next === null) return;
        next = $.trim(next);
        if (!next || next === current) return;
        ajax('casa_galeria_rename_tag', { id: $pill.data('id'), name: next }).done(function(res){
            if (!res || !res.success) {
                status($('#casa-gal-tag-status'), (res && res.data && res.data.message) || 'No se pudo renombrar.', true);
                return;
            }
            $pill.find('.casa-gal-pill-name').text(res.data.tag.name);
            refreshAllMinis();
            updatePaintHint();
            status($('#casa-gal-tag-status'), 'Etiqueta actualizada.');
        });
    });

    $(document).on('click', '.casa-gal-delete', function(e){
        e.preventDefault();
        e.stopPropagation();
        var $pill = $(this).closest('.casa-gal-pill');
        var name = $pill.find('.casa-gal-pill-name').text();
        if (!window.confirm('¿Eliminar la etiqueta «' + name + '»? Se quitará de todas las fotos.')) return;
        var id = parseInt($pill.data('id'), 10);
        ajax('casa_galeria_delete_tag', { id: id }).done(function(res){
            if (!res || !res.success) {
                status($('#casa-gal-tag-status'), 'No se pudo eliminar.', true);
                return;
            }
            if (selectedTagId === id) selectedTagId = 0;
            $pill.remove();
            $('#casadepiedra_gallery_preview .casa-gal-card').each(function(){
                var ids = cardTerms($(this)).filter(function(n){ return n !== id; });
                setCardTerms($(this), ids);
            });
            refreshAllMinis();
            updatePaintHint();
            status($('#casa-gal-tag-status'), 'Etiqueta eliminada.');
        });
    });

    $(document).on('click', '.casa-gal-card img', function(){
        if (!selectedTagId) {
            status($('#casa-gal-photo-status'), 'Primero elige una etiqueta arriba.', true);
            return;
        }
        var $card = $(this).closest('.casa-gal-card');
        var ids = cardTerms($card);
        var idx = ids.indexOf(selectedTagId);
        if (idx === -1) ids.push(selectedTagId);
        else ids.splice(idx, 1);
        setCardTerms($card, ids);
        $card.addClass('is-hit');
        setTimeout(function(){ $card.removeClass('is-hit'); }, 280);
        saveImageTags($card, ids);
    });

    $(document).on('click', '.casa-gal-assign', function(e){
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        var $card = $btn.closest('.casa-gal-card');
        var term = parseInt($btn.data('term'), 10);
        var ids = cardTerms($card);
        var idx = ids.indexOf(term);
        if (idx === -1) ids.push(term);
        else ids.splice(idx, 1);
        setCardTerms($card, ids);
        saveImageTags($card, ids);
    });

    function galleryIds() {
        return ($('#casadepiedra_gallery_ids').val() || '')
            .split(',')
            .map(function(v){ return parseInt(v, 10); })
            .filter(function(n){ return n > 0; });
    }

    function cardMarkup(id, url, termIds) {
        termIds = termIds || [];
        var minis = tagsFromPills().map(function(tag){
            var on = termIds.indexOf(tag.id) !== -1 ? ' is-on' : '';
            return '<button type="button" class="casa-gal-assign' + on + '" data-term="' + tag.id + '">' + $('<div>').text(tag.name).html() + '</button>';
        }).join('');
        return '<div class="casa-gal-card casa-gallery-item" data-id="' + id + '" data-terms="' + termIds.join(',') + '">' +
            '<div class="casa-gal-thumb">' +
                '<img src="' + url + '" alt="" />' +
                '<button type="button" class="casa-gal-remove casa-remove-single-img-btn" data-id="' + id + '" title="Quitar esta foto" aria-label="Quitar esta foto">×</button>' +
            '</div>' +
            '<div class="casa-gal-card-body"><div class="casa-gal-mini">' + minis + '</div>' +
            '<button type="button" class="casa-gal-remove-text casa-remove-single-img-btn" data-id="' + id + '">Quitar foto</button>' +
            '</div></div>';
    }

    $('#casadepiedra_upload_gallery_btn').on('click', function(e){
        e.preventDefault();
        if (meta_image_frame) {
            meta_image_frame.open();
            return;
        }
        meta_image_frame = wp.media.frames.file_frame = wp.media({
            title: 'Seleccionar fotografías para la galería',
            button: { text: 'Usar estas fotografías' },
            multiple: 'add'
        });
        meta_image_frame.on('select', function(){
            var selection = meta_image_frame.state().get('selection');
            var existing = ($('#casadepiedra_gallery_ids').val() || '').split(',').map(function(v){ return parseInt(v, 10); }).filter(function(n){ return n > 0; });
            $('#casadepiedra_gallery_preview .casa-gal-empty').remove();
            selection.map(function(attachment){
                attachment = attachment.toJSON();
                if (existing.indexOf(attachment.id) !== -1) return;
                existing.push(attachment.id);
                var url = attachment.sizes && (attachment.sizes.medium || attachment.sizes.thumbnail) ? (attachment.sizes.medium || attachment.sizes.thumbnail).url : attachment.url;
                var startTerms = selectedTagId ? [selectedTagId] : [];
                $('#casadepiedra_gallery_preview').append(cardMarkup(attachment.id, url, startTerms));
                if (startTerms.length) {
                    saveImageTags($('#casadepiedra_gallery_preview .casa-gal-card[data-id="' + attachment.id + '"]'), startTerms);
                }
            });
            $('#casadepiedra_gallery_ids').val(existing.join(','));
            saveGalleryIds();
        });
        meta_image_frame.open();
    });

    $('#casadepiedra_clear_gallery_btn').on('click', function(e){
        e.preventDefault();
        if (!confirm('¿Quitar todas las fotografías de la galería?')) return;
        $('#casadepiedra_gallery_ids').val('');
        $('#casadepiedra_gallery_preview').html('<div class="casa-gal-empty" style="grid-column:1/-1;">Aún no hay fotos. Usa el botón de arriba para subirlas o elegirlas.</div>');
        saveGalleryIds();
    });

    $(document).on('click', '.casa-remove-single-img-btn', function(e){
        e.preventDefault();
        e.stopPropagation();
        var btn = $(this);
        var idToRemove = parseInt(btn.attr('data-id') || btn.data('id'), 10);
        if (!idToRemove) return;
        if (!window.confirm('¿Quitar esta fotografía de la galería?')) return;
        var updatedIds = galleryIds().filter(function(id){ return id !== idToRemove; });
        $('#casadepiedra_gallery_ids').val(updatedIds.join(','));
        saveGalleryIds();
        var $item = btn.closest('.casa-gallery-item');
        $item.fadeOut(180, function(){
            $(this).remove();
            var remaining = $('#casadepiedra_gallery_preview .casa-gallery-item').length;
            if (!remaining) {
                $('#casadepiedra_gallery_preview').html('<div class="casa-gal-empty" style="grid-column:1/-1;">Aún no hay fotos. Usa el botón de arriba para subirlas o elegirlas.</div>');
            }
            status($('#casa-gal-photo-status'), remaining ? 'Fotografía quitada de la galería.' : 'Se quitaron todas las fotografías.');
        });
    });

    var admin_panel_frame;
    $('.casadepiedra_upload_image_btn').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var targetId = button.data('target');
        var previewId = button.data('preview');
        if (admin_panel_frame) {
            admin_panel_frame.open();
        } else {
            admin_panel_frame = wp.media({
                title: 'Seleccionar Imagen',
                button: { text: 'Usar imagen' },
                multiple: false
            });
        }
        admin_panel_frame.off('select').on('select', function() {
            var attachment = admin_panel_frame.state().get('selection').first().toJSON();
            $('#' + targetId).val(attachment.url);
            $('#' + previewId).html('<img src="' + attachment.url + '" style="max-height:80px; display:block; margin-top:10px;"/>');
        });
        admin_panel_frame.open();
    });

    $('.casadepiedra_clear_image_btn').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        $('#' + button.data('target')).val('');
        $('#' + button.data('preview')).html('');
    });
});
