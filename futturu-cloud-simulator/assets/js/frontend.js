/**
 * Futuru Cloud Simulator - Frontend JavaScript
 * Handles tab navigation, modals, and form submissions
 */

(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {
        initTabNavigation();
        initPlanDetailsModal();
        initContactModal();
        initQuoteForm();
    });

    /**
     * Initialize category tab navigation
     */
    function initTabNavigation() {
        $('.tab-button').on('click', function() {
            var category = $(this).data('category');
            
            // Update active tab
            $('.tab-button').removeClass('active');
            $(this).addClass('active');
            
            // Show corresponding plans section
            $('.plans-section').hide();
            $('.plans-section[data-category="' + category + '"]').fadeIn(300);
        });
    }

    /**
     * Initialize plan details modal
     */
    function initPlanDetailsModal() {
        // Open modal on "Mais Info" button click
        $('.more-info-btn').on('click', function() {
            var planId = $(this).data('plan-id');
            openPlanDetailsModal(planId);
        });

        // Close modal
        $('#plan-details-modal .modal-close').on('click', function() {
            $('#plan-details-modal').fadeOut(300);
        });

        // Close on overlay click
        $('#plan-details-modal').on('click', function(e) {
            if ($(e.target).hasClass('modal-overlay')) {
                $(this).fadeOut(300);
            }
        });

        // Modal CTA button
        $('#modal-cta-btn').on('click', function() {
            var planModel = $(this).data('plan-model');
            $('#plan-details-modal').fadeOut(300, function() {
                openContactModal(planModel);
            });
        });
    }

    /**
     * Open plan details modal with AJAX
     */
    function openPlanDetailsModal(planId) {
        // Show loading state
        $('#modal-plan-name').html('<span class="futturu-loading"></span>');
        $('#plan-details-modal').fadeIn(300);

        $.ajax({
            url: futturuCloudFrontend.ajaxUrl,
            type: 'POST',
            data: {
                action: 'futturu_cloud_get_plan_details',
                nonce: futturuCloudFrontend.nonce,
                plan_id: planId
            },
            success: function(response) {
                if (response.success) {
                    populatePlanDetailsModal(response.data);
                } else {
                    alert(response.data.message || 'Erro ao carregar detalhes do plano.');
                    $('#plan-details-modal').fadeOut(300);
                }
            },
            error: function() {
                alert('Erro de conexão. Por favor, tente novamente.');
                $('#plan-details-modal').fadeOut(300);
            }
        });
    }

    /**
     * Populate plan details modal with data
     */
    function populatePlanDetailsModal(data) {
        var plan = data.plan;
        var features = data.features;

        // Header
        $('#modal-plan-name').text(plan.modelo);
        $('#modal-plan-price').text(data.formatted_price + '/mês');

        // Resources grid
        var resourcesHtml = '';
        
        // RAM
        resourcesHtml += '<div class="resource-card">';
        resourcesHtml += '<div class="label">Memória RAM</div>';
        resourcesHtml += '<div class="value">' + data.formatted_ram + '</div>';
        resourcesHtml += '</div>';
        
        // CPU
        resourcesHtml += '<div class="resource-card">';
        resourcesHtml += '<div class="label">Processador</div>';
        resourcesHtml += '<div class="value">' + data.formatted_cpu + '</div>';
        resourcesHtml += '</div>';
        
        // SSD
        resourcesHtml += '<div class="resource-card">';
        resourcesHtml += '<div class="label">Armazenamento SSD</div>';
        resourcesHtml += '<div class="value">' + data.formatted_disco + '</div>';
        resourcesHtml += '</div>';
        
        // Views (if applicable)
        if (plan.visualizacoes) {
            resourcesHtml += '<div class="resource-card">';
            resourcesHtml += '<div class="label">Visualizações/mês</div>';
            resourcesHtml += '<div class="value">' + data.formatted_views + '</div>';
            resourcesHtml += '</div>';
        }
        
        // Sites
        resourcesHtml += '<div class="resource-card">';
        resourcesHtml += '<div class="label">Sites Recomendados</div>';
        resourcesHtml += '<div class="value">' + plan.sites + '</div>';
        resourcesHtml += '</div>';

        $('#modal-resources').html(resourcesHtml);

        // Features list
        var featuresHtml = '';
        if (features.length > 0) {
            features.forEach(function(feature) {
                featuresHtml += '<li>';
                featuresHtml += '<strong>' + feature.name + '</strong>';
                featuresHtml += '<br><small>' + feature.description + '</small>';
                featuresHtml += '</li>';
            });
        } else {
            featuresHtml = '<li>Todas as funcionalidades padrão inclusas</li>';
        }
        $('#modal-features').html(featuresHtml);

        // Recommendations
        var recommendationsHtml = '';
        if (plan.visualizacoes) {
            recommendationsHtml += '<p><strong>Tráfego:</strong> Recomendado para sites com até ' + data.formatted_views + ' visualizações mensais.</p>';
        }
        recommendationsHtml += '<p><strong>Uso:</strong> Ideal para ' + plan.sites.toLowerCase() + '.</p>';
        recommendationsHtml += '<p><strong>Infraestrutura:</strong> Servidores de alta performance localizados nos EUA (Dallas/Newark) com redundância e uptime garantido.</p>';
        $('#modal-recommendations').html(recommendationsHtml);

        // CTA button
        $('#modal-cta-btn')
            .text(futturuCloudFrontend.ctaText + ' - ' + plan.modelo)
            .data('plan-model', plan.modelo);
    }

    /**
     * Initialize contact modal
     */
    function initContactModal() {
        // Open from plan CTA buttons
        $('.cta-btn').not('.global').on('click', function() {
            var planModel = $(this).data('plan-model');
            openContactModal(planModel);
        });

        // Open from global CTA
        $('#global-cta-btn').on('click', function() {
            openContactModal('Em aberto - Preciso de ajuda para escolher');
        });

        // Close modal
        $('#contact-modal .modal-close').on('click', function() {
            $('#contact-modal').fadeOut(300);
        });

        // Close on overlay click
        $('#contact-modal').on('click', function(e) {
            if ($(e.target).hasClass('modal-overlay')) {
                $(this).fadeOut(300);
            }
        });
    }

    /**
     * Open contact modal
     */
    function openContactModal(planModel) {
        $('#quote-plan-model').val(planModel);
        $('#quote-plan-display').val(planModel);
        $('#form-notice').removeClass('success error').hide();
        $('#quote-form')[0].reset();
        $('#quote-plan-display').val(planModel);
        $('#quote-plan-model').val(planModel);
        $('#contact-modal').fadeIn(300);
    }

    /**
     * Initialize quote form submission
     */
    function initQuoteForm() {
        $('#quote-form').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var originalText = $submitBtn.text();

            // Show loading state
            $submitBtn.prop('disabled', true).html('<span class="futturu-loading"></span> Enviando...');

            $.ajax({
                url: futturuCloudFrontend.ajaxUrl,
                type: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#form-notice')
                            .removeClass('error')
                            .addClass('success')
                            .text(response.data.message)
                            .fadeIn(300);
                        
                        // Reset form after successful submission
                        setTimeout(function() {
                            $form[0].reset();
                            $('#contact-modal').fadeOut(300);
                        }, 2000);
                    } else {
                        $('#form-notice')
                            .removeClass('success')
                            .addClass('error')
                            .text(response.data.message || 'Erro ao enviar solicitação.')
                            .fadeIn(300);
                    }
                },
                error: function() {
                    $('#form-notice')
                        .removeClass('success')
                        .addClass('error')
                        .text('Erro de conexão. Por favor, verifique sua internet e tente novamente.')
                        .fadeIn(300);
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });
    }

    // Keyboard accessibility for modals
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('.modal-overlay').fadeOut(300);
        }
    });

})(jQuery);
