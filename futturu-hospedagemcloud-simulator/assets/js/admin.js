/**
 * Admin JavaScript - Simulador de Hospedagem Futturu Cloud
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initTabs();
        initPlansForm();
        initResetButton();
    });

    /**
     * Inicializa tabs do admin
     */
    function initTabs() {
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            
            const target = $(this).attr('href');
            
            // Atualiza tabs
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Atualiza conteúdo
            $('.tab-content').removeClass('active');
            $(target).addClass('active');
        });
    }

    /**
     * Inicializa formulário de planos
     */
    function initPlansForm() {
        $('#futturu-plans-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $spinner = $('#plans-spinner');
            const $message = $('.notice-message');
            const $submitBtn = $('#save-plans-btn');
            
            // Coleta dados dos planos
            const plans = [];
            const $rows = $form.find('tbody tr');
            
            $rows.each(function() {
                const $row = $(this);
                const category = $row.data('category');
                
                plans.push({
                    categoria: category,
                    nome_exibido: $row.find('input[name*="nome_exibido"]').val(),
                    modelo: $row.find('input[name*="modelo"]').val(),
                    ram: $row.find('input[name*="ram"]').val(),
                    cpu: $row.find('input[name*="cpu"]').val(),
                    disco: $row.find('input[name*="disco"]').val(),
                    visualizacoes: $row.find('input[name*="visualizacoes"]').val(),
                    preco_mensal: parseFloat($row.find('input[name*="preco_mensal"]').val()) || 0,
                    uso_indicado: $row.find('input[name*="uso_indicado"]').val()
                });
            });
            
            // Envia AJAX
            $.ajax({
                url: futturuHospedagemCloudAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'futturu_hospedagemcloud_save_plans',
                    nonce: futturuHospedagemCloudAdmin.nonce,
                    plans: plans
                },
                beforeSend: function() {
                    $spinner.addClass('is-active');
                    $submitBtn.prop('disabled', true);
                    $message.removeClass('success error').text('');
                },
                success: function(response) {
                    if (response.success) {
                        $message.addClass('success').text(response.data.message);
                    } else {
                        $message.addClass('error').text(response.data.message);
                    }
                },
                error: function() {
                    $message.addClass('error').text(futturuHospedagemCloudAdmin.strings.error);
                },
                complete: function() {
                    $spinner.removeClass('is-active');
                    $submitBtn.prop('disabled', false);
                    
                    setTimeout(function() {
                        $message.text('');
                    }, 5000);
                }
            });
        });
    }

    /**
     * Inicializa botão de reset
     */
    function initResetButton() {
        $('#reset-plans-btn').on('click', function() {
            if (!confirm(futturuHospedagemCloudAdmin.strings.confirmReset)) {
                return;
            }
            
            const $spinner = $('#plans-spinner');
            const $message = $('.notice-message');
            
            $.ajax({
                url: futturuHospedagemCloudAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'futturu_hospedagemcloud_reset_plans',
                    nonce: futturuHospedagemCloudAdmin.nonce
                },
                beforeSend: function() {
                    $spinner.addClass('is-active');
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        $('.notice-message').addClass('error').text(response.data.message);
                    }
                },
                error: function() {
                    $('.notice-message').addClass('error').text(futturuHospedagemCloudAdmin.strings.error);
                },
                complete: function() {
                    $spinner.removeClass('is-active');
                }
            });
        });
    }

})(jQuery);
