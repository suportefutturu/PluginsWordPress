/**
 * Futuru Cloud Simulator JavaScript
 * Handles frontend interactions, quiz, modals, and AJAX form submission
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        initQuiz();
        initPlanButtons();
        initContactForm();
        initAdminTabs();
        initAdminModals();
    });

    /**
     * Initialize Quiz Functionality
     */
    function initQuiz() {
        $('.futturu-quiz-option').on('click', function() {
            // Remove selected class from all options
            $('.futturu-quiz-option').removeClass('selected');
            
            // Add selected class to clicked option
            $(this).addClass('selected');
            
            // Get recommended plan
            var planId = $(this).data('recommended-plan');
            
            // Show recommendation
            showRecommendation(planId);
        });
    }

    /**
     * Show Plan Recommendation
     */
    function showRecommendation(planId) {
        // Get plan data from the table
        var planRow = $('.futturu-plan-row[data-plan-id="' + planId + '"]');
        
        if (planRow.length === 0) {
            return;
        }
        
        var planName = planRow.find('.futturu-plan-name strong').text();
        var planPrice = planRow.find('.price-value').text();
        var planRam = planRow.find('.futturu-plan-resources').text();
        
        var recommendationHtml = `
            <p style="font-size: 1.3em; margin-bottom: 15px;">
                <strong>${planName}</strong> por <strong>R$ ${planPrice}/mês</strong>
            </p>
            <p style="color: #666; margin-bottom: 20px;">
                Este plano é ideal para o seu perfil de tráfego e oferece recursos adequados 
                para o crescimento do seu negócio.
            </p>
        `;
        
        $('#recommended-plan-info').html(recommendationHtml);
        $('#futturu-recommendation').fadeIn(300);
        
        // Smooth scroll to recommendation
        $('html, body').animate({
            scrollTop: $('#futturu-recommendation').offset().top - 100
        }, 500);
    }

    /**
     * Initialize Plan Buttons (More Info and Select Plan)
     */
    function initPlanButtons() {
        // More Info button
        $('.more-info-btn').on('click', function() {
            var planId = $(this).data('plan-id');
            showPlanDetails(planId);
        });

        // Select Plan button
        $('.select-plan-btn').on('click', function() {
            var planName = $(this).data('plan-name');
            futturuOpenModal(planId, planName);
        });
    }

    /**
     * Show Plan Details Modal
     */
    function showPlanDetails(planId) {
        var planRow = $('.futturu-plan-row[data-plan-id="' + planId + '"]');
        
        if (planRow.length === 0) {
            return;
        }
        
        var planName = planRow.find('.futturu-plan-name strong').text();
        var planCategory = planRow.find('.futturu-plan-category').text();
        var resources = planRow.find('.futturu-plan-resources').html();
        var views = planRow.find('td:nth-child(3)').text();
        var sites = planRow.find('td:nth-child(4)').text();
        var price = planRow.find('.futturu-plan-price').html();
        var upgradePath = planRow.find('.futturu-upgrade-path').length > 0 ? 
            planRow.find('.futturu-upgrade-path').html() : 
            '<span style="color: #27ae60; font-weight: 600;">Plano Máximo</span>';
        
        var features = getPlanFeatures(planId);
        var featuresHtml = features.length > 0 ? 
            '<ul style="text-align: left; margin-top: 15px;">' + 
            features.map(f => '<li style="padding: 5px 0;">✓ ' + f + '</li>').join('') + 
            '</ul>' : '';
        
        var detailsHtml = `
            <h2 style="color: #2c3e50; margin-bottom: 10px;">${planName}</h2>
            <span style="background: #667eea; color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.85em;">${planCategory}</span>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 25px 0;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <h4 style="margin: 0 0 10px 0; color: #666;">Recursos</h4>
                    <div style="font-size: 1.1em;">${resources}</div>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <h4 style="margin: 0 0 10px 0; color: #666;">Preço</h4>
                    <div style="font-size: 1.3em; color: #667eea; font-weight: 700;">${price}</div>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
                <div>
                    <strong>Visualizações/Mês:</strong><br>
                    ${views}
                </div>
                <div>
                    <strong>Sites por Cloud:</strong><br>
                    ${sites}
                </div>
            </div>
            
            <div style="background: #e8f4fd; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                <strong style="color: #667eea;">📈 Caminho de Escalabilidade:</strong><br>
                <span style="font-size: 1.1em;">${upgradePath}</span>
            </div>
            
            ${featuresHtml}
            
            <div style="margin-top: 25px; text-align: center;">
                <button class="futturu-btn futturu-btn-primary futturu-btn-large" onclick="futturuOpenModal('${planId}', '${planName}')">
                    Contratar este Plano
                </button>
            </div>
        `;
        
        $('#futturu-plan-details-content').html(detailsHtml);
        $('#futturu-plan-details-modal').fadeIn(200);
    }

    /**
     * Get Plan Features (mock data - can be expanded)
     */
    function getPlanFeatures(planId) {
        var featuresMap = {
            'br1g': ['Hospedagem Gerenciada', 'CDN Automático', 'Backups Diários', 'SSL Gratuito', 'Monitoramento 24/7', 'Redimensionamento com 1 clique'],
            'br2g': ['Todos recursos do BR1G', 'Mais RAM e CPU', 'Escalabilidade Automática', 'Suporte Prioritário'],
            'br4g': ['Performance Otimizada', 'Recursos Dedicados', 'Migração Gratuita', 'Backup Hourly Disponível'],
            'br8g': ['Alta Performance', 'Recursos Expandidos', 'SLA Garantido', 'Suporte Especializado'],
            'br16g': ['Performance Máxima', 'Recursos Enterprise', 'Arquitetura Escalável', 'Consultoria Dedicada']
        };
        
        return featuresMap[planId] || [];
    }

    /**
     * Initialize Contact Form
     */
    function initContactForm() {
        $('#futturu-contact-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var $messageDiv = $('#futturu-form-message');
            
            // Disable button during submission
            $submitBtn.prop('disabled', true).text('Enviando...');
            
            // Serialize form data
            var formData = $form.serialize();
            
            // AJAX submission
            $.ajax({
                url: futturuCloudSim.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $messageDiv.removeClass('error').addClass('success')
                            .text(response.data.message);
                        $form[0].reset();
                        
                        // Close modal after 2 seconds
                        setTimeout(function() {
                            futturuCloseModal();
                            $messageDiv.hide().removeClass('success');
                        }, 2000);
                    } else {
                        $messageDiv.removeClass('success').addClass('error')
                            .text(response.data.message || futturuCloudSim.messages.error);
                    }
                },
                error: function() {
                    $messageDiv.removeClass('success').addClass('error')
                        .text(futturuCloudSim.messages.error);
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).text('Enviar Solicitação');
                }
            });
        });
    }

    /**
     * Initialize Admin Tabs
     */
    function initAdminTabs() {
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            $('.nav-tab').removeClass('nav-tab-active');
            $('.tab-content').hide();
            
            // Add active class to clicked tab
            $(this).addClass('nav-tab-active');
            
            // Show corresponding content
            var target = $(this).attr('href');
            $(target).fadeIn(200);
        });
    }

    /**
     * Initialize Admin Modals (Plan and Profile editing)
     */
    function initAdminModals() {
        // Plan Modal
        $('#add-new-plan').on('click', function() {
            openPlanModal(-1);
        });
        
        $('.edit-plan').on('click', function() {
            var index = $(this).closest('tr').data('plan-index');
            openPlanModal(index);
        });
        
        $('.delete-plan').on('click', function() {
            var index = $(this).closest('tr').data('plan-index');
            deletePlan(index);
        });
        
        $('.futturu-modal-close').on('click', function() {
            $(this).closest('.futturu-modal').hide();
        });
        
        $('#plan-form').on('submit', function(e) {
            e.preventDefault();
            savePlan();
        });
        
        // Profile Modal
        $('#add-new-profile').on('click', function() {
            openProfileModal(-1);
        });
        
        $('.edit-profile').on('click', function() {
            var index = $(this).closest('tr').data('profile-index');
            openProfileModal(index);
        });
        
        $('.delete-profile').on('click', function() {
            var index = $(this).closest('tr').data('profile-index');
            deleteProfile(index);
        });
        
        $('#profile-form').on('submit', function(e) {
            e.preventDefault();
            saveProfile();
        });
    }

    /**
     * Open Plan Modal
     */
    function openPlanModal(index) {
        $('#plan-index').val(index);
        
        if (index >= 0) {
            var row = $('tr[data-plan-index="' + index + '"]');
            $('#plan-id').val(row.find('td:eq(0)').text());
            $('#plan-name').val(row.find('td:eq(0)').text());
            $('#modal-title').text('Editar Plano');
        } else {
            $('#plan-form')[0].reset();
            $('#modal-title').text('Adicionar Novo Plano');
        }
        
        $('#plan-modal').fadeIn(200);
    }

    /**
     * Save Plan
     */
    function savePlan() {
        var index = $('#plan-index').val();
        
        var data = {
            action: 'futturu_save_plan',
            nonce: futturuCloudSim.nonce || '',
            index: index,
            id: $('#plan-id').val().toLowerCase(),
            name: $('#plan-name').val(),
            ram: $('#plan-ram').val(),
            cpu: $('#plan-cpu').val(),
            disk: $('#plan-disk').val(),
            views: $('#plan-views').val(),
            sites: $('#plan-sites').val(),
            price: $('#plan-price').val(),
            category: $('#plan-category').val(),
            next_plan: $('#plan-next-plan').val()
        };
        
        $.ajax({
            url: futturuCloudSim.ajaxUrl,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message || 'Erro ao salvar plano');
                }
            },
            error: function() {
                alert('Erro na comunicação com o servidor');
            }
        });
    }

    /**
     * Delete Plan
     */
    function deletePlan(index) {
        if (!confirm('Tem certeza que deseja excluir este plano?')) {
            return;
        }
        
        $.ajax({
            url: futturuCloudSim.ajaxUrl,
            type: 'POST',
            data: {
                action: 'futturu_delete_plan',
                nonce: futturuCloudSim.nonce || '',
                index: index
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message || 'Erro ao excluir plano');
                }
            },
            error: function() {
                alert('Erro na comunicação com o servidor');
            }
        });
    }

    /**
     * Open Profile Modal
     */
    function openProfileModal(index) {
        $('#profile-index').val(index);
        
        if (index >= 0) {
            var row = $('tr[data-profile-index="' + index + '"]');
            $('#profile-name').val(row.find('td:eq(0)').text());
            $('#profile-modal-title').text('Editar Perfil');
        } else {
            $('#profile-form')[0].reset();
            $('#profile-modal-title').text('Adicionar Novo Perfil');
        }
        
        $('#profile-modal').fadeIn(200);
    }

    /**
     * Save Profile
     */
    function saveProfile() {
        var index = $('#profile-index').val();
        var name = $('#profile-name').val();
        
        var data = {
            action: 'futturu_save_profile',
            nonce: futturuCloudSim.nonce || '',
            index: index,
            id: name.toLowerCase().replace(/[^a-z0-9]/g, '-'),
            name: name,
            views_min: $('#profile-views-min').val(),
            views_max: $('#profile-views-max').val(),
            recommended_plan: $('#profile-recommended-plan').val(),
            description: $('#profile-description').val()
        };
        
        $.ajax({
            url: futturuCloudSim.ajaxUrl,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message || 'Erro ao salvar perfil');
                }
            },
            error: function() {
                alert('Erro na comunicação com o servidor');
            }
        });
    }

    /**
     * Delete Profile
     */
    function deleteProfile(index) {
        if (!confirm('Tem certeza que deseja excluir este perfil?')) {
            return;
        }
        
        $.ajax({
            url: futturuCloudSim.ajaxUrl,
            type: 'POST',
            data: {
                action: 'futturu_delete_profile',
                nonce: futturuCloudSim.nonce || '',
                index: index
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert(response.data.message || 'Erro ao excluir perfil');
                }
            },
            error: function() {
                alert('Erro na comunicação com o servidor');
            }
        });
    }

})(jQuery);
