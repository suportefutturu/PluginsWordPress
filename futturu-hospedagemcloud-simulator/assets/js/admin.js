jQuery(document).ready(function($) {
    // Tabs
    $('.fcs-tab-btn').on('click', function() {
        var tab = $(this).data('tab');
        
        $('.fcs-tab-btn').removeClass('active');
        $(this).addClass('active');
        
        $('.fcs-tab-content').removeClass('active');
        $('#fcs-tab-' + tab).addClass('active');
    });
    
    // Save Plans
    $('#fcs-save-plans').on('click', function() {
        var $spinner = $('#fcs-plans-spinner');
        var $message = $('#fcs-plans-message');
        
        $spinner.addClass('is-active');
        $message.html('');
        
        var data = {
            action: 'futturu_hospedagemcloud_save_plans',
            nonce: futturuHospedagemCloudAdmin.nonce
        };
        
        $('input[name^="plan_"]').each(function() {
            var name = $(this).attr('name');
            var value = $(this).val();
            data[name] = value;
        });
        
        $.post(futturuHospedagemCloudAdmin.ajaxUrl, data, function(response) {
            $spinner.removeClass('is-active');
            if (response.success) {
                $message.html('<span style="color: green;">' + response.data + '</span>');
            } else {
                $message.html('<span style="color: red;">Erro: ' + response.data + '</span>');
            }
        });
    });
    
    // Add FAQ
    $('#fcs-add-faq').on('click', function() {
        var index = $('#fcs-faqs-container .fcs-faq-item').length;
        var html = '<div class="fcs-faq-item">' +
            '<input type="text" name="faq_pergunta[' + index + ']" class="regular-text" placeholder="Pergunta">' +
            '<textarea name="faq_resposta[' + index + ']" rows="3" placeholder="Resposta"></textarea>' +
            '<button type="button" class="button remove-faq">Remover</button>' +
            '</div>';
        $('#fcs-faqs-container').append(html);
    });
    
    // Remove FAQ
    $(document).on('click', '.remove-faq', function() {
        $(this).parent().remove();
    });
    
    // Save FAQs
    $('#fcs-save-faqs').on('click', function() {
        var $spinner = $('#fcs-faqs-spinner');
        var $message = $('#fcs-faqs-message');
        
        $spinner.addClass('is-active');
        $message.html('');
        
        var data = {
            action: 'futturu_hospedagemcloud_save_faqs',
            nonce: futturuHospedagemCloudAdmin.nonce
        };
        
        $('input[name^="faq_pergunta"]').each(function() {
            var name = $(this).attr('name');
            var value = $(this).val();
            data[name] = value;
        });
        
        $('textarea[name^="faq_resposta"]').each(function() {
            var name = $(this).attr('name');
            var value = $(this).val();
            data[name] = value;
        });
        
        $.post(futturuHospedagemCloudAdmin.ajaxUrl, data, function(response) {
            $spinner.removeClass('is-active');
            if (response.success) {
                $message.html('<span style="color: green;">' + response.data + '</span>');
            } else {
                $message.html('<span style="color: red;">Erro: ' + response.data + '</span>');
            }
        });
    });
    
    // Save Settings
    $('#fcs-save-settings').on('click', function() {
        var $spinner = $('#fcs-settings-spinner');
        var $message = $('#fcs-settings-message');
        
        $spinner.addClass('is-active');
        $message.html('');
        
        var data = {
            action: 'futturu_hospedagemcloud_save_settings',
            nonce: futturuHospedagemCloudAdmin.nonce,
            discount_rate: $('#fcs-discount-rate').val(),
            contact_email: $('#fcs-contact-email').val(),
            default_view: $('input[name="fcs-default-view"]:checked').val()
        };
        
        $.post(futturuHospedagemCloudAdmin.ajaxUrl, data, function(response) {
            $spinner.removeClass('is-active');
            if (response.success) {
                $message.html('<span style="color: green;">' + response.data + '</span>');
            } else {
                $message.html('<span style="color: red;">Erro: ' + response.data + '</span>');
            }
        });
    });
});
