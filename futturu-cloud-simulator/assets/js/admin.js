/**
 * Futuru Cloud Simulator - Admin JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        initPlanModal();
        initSettingsForm();
        initExportData();
    });

    /**
     * Initialize plan modal (add/edit)
     */
    function initPlanModal() {
        var $modal = $('#futturu-plan-modal');
        var $close = $modal.find('.close');
        var $form = $('#futturu-plan-form');

        // Open modal for new plan
        $('#futturu-add-plan').on('click', function() {
            openPlanModal();
        });

        // Open modal for edit
        $('.futturu-edit-plan').on('click', function() {
            var plan = $(this).data('plan');
            openPlanModal(plan);
        });

        // Close modal
        $close.on('click', function() {
            $modal.fadeOut(300);
        });

        // Close on overlay click
        $modal.on('click', function(e) {
            if ($(e.target).hasClass('modal')) {
                $modal.fadeOut(300);
            }
        });

        // Form submission
        $form.on('submit', function(e) {
            e.preventDefault();
            
            var $submitBtn = $(this).find('button[type="submit"]');
            var originalText = $submitBtn.text();
            $submitBtn.prop('disabled', true).text('Salvando...');

            $.ajax({
                url: futturuCloudAdmin.ajaxUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Erro ao salvar plano. Tente novamente.');
                        $submitBtn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    alert('Erro de conexão. Verifique sua internet.');
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });

        // Delete plan
        $('.futturu-delete-plan').on('click', function() {
            if (!confirm('Tem certeza que deseja excluir este plano? Esta ação não pode ser desfeita.')) {
                return;
            }

            var planId = $(this).data('plan-id');

            $.ajax({
                url: futturuCloudAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'futturu_cloud_delete_plan',
                    nonce: futturuCloudAdmin.nonce,
                    plan_id: planId
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Erro ao excluir plano. Tente novamente.');
                    }
                },
                error: function() {
                    alert('Erro de conexão. Verifique sua internet.');
                }
            });
        });
    }

    /**
     * Open plan modal with optional plan data
     */
    function openPlanModal(plan) {
        var $modal = $('#futturu-plan-modal');
        var $form = $('#futturu-plan-form');

        if (plan) {
            // Edit mode
            $('#modal-title').text('Editar Plano');
            $('#plan-id').val(plan.id);
            $('#plan-modelo').val(plan.modelo);
            $('#plan-categoria').val(plan.categoria);
            $('#plan-ram').val(plan.ram);
            $('#plan-cpu').val(plan.cpu);
            $('#plan-disco').val(plan.disco);
            $('#plan-visualizacoes').val(plan.visualizacoes || '');
            $('#plan-sites').val(plan.sites);
            $('#plan-preco').val(plan.preco);

            // Uncheck all features first
            $('#futturu-plan-form input[name="features[]"]').prop('checked', false);

            // Check features that belong to this plan
            if (plan.features && plan.features.length > 0) {
                plan.features.forEach(function(featureId) {
                    $('#futturu-plan-form input[name="features[]"][value="' + featureId + '"]').prop('checked', true);
                });
            }
        } else {
            // Add mode
            $('#modal-title').text('Adicionar Novo Plano');
            $('#plan-id').val('');
            $form[0].reset();
            // Check all features by default for new plans
            $('#futturu-plan-form input[name="features[]"]').prop('checked', true);
        }

        $modal.fadeIn(300);
    }

    /**
     * Initialize settings form
     */
    function initSettingsForm() {
        $('#futturu-settings-form').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var originalText = $submitBtn.text();
            $submitBtn.prop('disabled', true).text('Salvando...');

            $.ajax({
                url: futturuCloudAdmin.ajaxUrl,
                type: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    if (response.success) {
                        alert('Configurações salvas com sucesso!');
                        $submitBtn.prop('disabled', false).text(originalText);
                    } else {
                        alert('Erro ao salvar configurações. Tente novamente.');
                        $submitBtn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    alert('Erro de conexão. Verifique sua internet.');
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });
    }

    /**
     * Initialize export data functionality
     */
    function initExportData() {
        $('#futturu-export-data').on('click', function() {
            // Create a download link with all data
            var data = {
                plans: window.futturuCloudPlans || [],
                categories: window.futturuCloudCategories || [],
                features: window.futturuCloudFeatures || [],
                settings: window.futturuCloudSettings || {}
            };

            // For simplicity, we'll create a JSON blob and trigger download
            // In production, you might want to fetch fresh data from server
            var jsonStr = JSON.stringify(data, null, 2);
            var blob = new Blob([jsonStr], {type: 'application/json'});
            var url = URL.createObjectURL(blob);
            
            var a = document.createElement('a');
            a.href = url;
            a.download = 'futturu-cloud-data-' + new Date().toISOString().split('T')[0] + '.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    }

})(jQuery);

// Make data available for export (set by inline script in admin page)
window.futturuCloudPlans = window.futturuCloudPlans || [];
window.futturuCloudCategories = window.futturuCloudCategories || [];
window.futturuCloudFeatures = window.futturuCloudFeatures || [];
window.futturuCloudSettings = window.futturuCloudSettings || {};
