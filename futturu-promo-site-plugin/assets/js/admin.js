/**
 * Admin JavaScript for Futturu Promo Plugin
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Media uploader for QR Code
        var mediaUploader;
        
        $('#upload_qr_code').on('click', function(e) {
            e.preventDefault();
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media({
                title: 'Selecione ou faça upload do QR Code PIX',
                button: {
                    text: 'Usar esta imagem'
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#futturu_promo_qr_code').val(attachment.url);
                
                // Show preview
                var previewHtml = '<div style="margin-bottom: 10px;"><img src="' + attachment.url + '" style="max-width: 200px;" /></div>';
                $('.form-field.futturu_promo_qr_code').find('.description').before(previewHtml);
                
                // Show remove button
                $('#remove_qr_code').show();
            });
            
            mediaUploader.open();
        });
        
        // Remove QR Code
        $('#remove_qr_code').on('click', function(e) {
            e.preventDefault();
            $('#futturu_promo_qr_code').val('');
            $(this).siblings('img').parent().remove();
            $(this).hide();
        });
        
        // Hide remove button if no image
        if ($('#futturu_promo_qr_code').val() === '') {
            $('#remove_qr_code').hide();
        }
    });
    
})(jQuery);
