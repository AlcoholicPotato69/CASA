jQuery(document).ready(function($){
    var meta_image_frame;

    $('#casadepiedra_upload_gallery_btn').on('click', function(e){
        e.preventDefault();
        
        if (meta_image_frame) {
            meta_image_frame.open();
            return;
        }
        
        meta_image_frame = wp.media.frames.file_frame = wp.media({
            title: 'Seleccionar Imágenes para la Galería',
            button: { text: 'Usar estas imágenes' },
            multiple: 'add'
        });

        meta_image_frame.on('select', function(){
            var selection = meta_image_frame.state().get('selection');
            var ids = [];
            var html = '';
            
            selection.map(function(attachment){
                attachment = attachment.toJSON();
                ids.push(attachment.id);
                var url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                html += '<div class="casa-gallery-item" data-id="'+attachment.id+'" style="display:inline-block; position:relative; margin-right:12px; margin-bottom:12px;">';
                html += '<img src="'+url+'" style="width:95px; height:75px; object-fit:cover; display:block; border: 1px solid #cbd5e1; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.05);" />';
                html += '<button type="button" class="casa-remove-single-img-btn" data-id="'+attachment.id+'" title="Eliminar foto individual" style="position:absolute; top:-6px; right:-6px; background:#dc2626; color:#fff; border:2px solid #fff; border-radius:50%; width:24px; height:24px; font-size:12px; font-weight:bold; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,0.25); line-height:1;">🗑️</button>';
                html += '</div>';
            });
            
            $('#casadepiedra_gallery_ids').val(ids.join(','));
            $('#casadepiedra_gallery_preview').html(html);
        });

        meta_image_frame.open();
    });

    $('#casadepiedra_clear_gallery_btn').on('click', function(e){
        e.preventDefault();
        if (confirm('¿Estás seguro de eliminar todas las imágenes de la galería?')) {
            $('#casadepiedra_gallery_ids').val('');
            $('#casadepiedra_gallery_preview').html('');
        }
    });

    // Eliminación granular de una sola imagen (Icono Bote de Basura 🗑️)
    $(document).on('click', '.casa-remove-single-img-btn', function(e){
        e.preventDefault();
        var btn = $(this);
        var idToRemove = btn.data('id').toString();
        var currentIds = $('#casadepiedra_gallery_ids').val().split(',').map(function(item){ return item.trim(); });
        
        var updatedIds = currentIds.filter(function(id){ return id !== idToRemove && id !== ''; });
        $('#casadepiedra_gallery_ids').val(updatedIds.join(','));
        
        btn.closest('.casa-gallery-item').fadeOut(220, function(){
            $(this).remove();
        });
    });

    // Eliminación granular de una etiqueta individual (Icono Bote de Basura 🗑️)
    $(document).on('click', '.casadepiedra_remove_tag_btn', function(e){
        e.preventDefault();
        var tagToRemove = $(this).data('tag');
        if (confirm('¿Estás seguro de quitar la etiqueta «' + tagToRemove + '» de la galería?')) {
            var inputTags = $('input[name="casa_opt_galeria_etiquetas"]');
            if (inputTags.length) {
                var currentTags = inputTags.val().split(',').map(function(item){ return item.trim(); });
                var updatedTags = currentTags.filter(function(t){ return t !== tagToRemove && t !== ''; });
                inputTags.val(updatedTags.join(', '));
            }
            $(this).closest('.casa-rest-card').fadeOut(250, function(){
                $(this).remove();
            });
        }
    });

    // Subida directa por etiqueta en la sección de Galería
    $(document).on('click', '.casadepiedra_upload_tag_btn', function(e){
        e.preventDefault();
        var tag = $(this).data('tag');
        $('#casadepiedra_upload_gallery_btn').trigger('click');
    });

    // Admin Panel Single Image Uploader
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
