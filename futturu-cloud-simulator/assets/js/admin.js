/**
 * Futturu Cloud Simulator - Admin JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Edit Plan function
        window.editPlan = function(planId) {
            // In a real implementation, you would fetch the plan data via AJAX
            // and populate the form fields. For now, we'll just show the modal.
            $('#add-plan-modal').show();
            $('#modal-title').text('Editar Plano');
            $('#plan_action').val('update_plan');
            $('#plan_id').val(planId);
            
            // Note: In production, you'd want to fetch the actual plan data here
            // and populate the form fields with existing values
        };

        // Delete Plan function
        window.deletePlan = function(planId) {
            if (confirm(fcsAdmin.strings.confirmDelete)) {
                // Create a form and submit it
                const form = $('<form>', {
                    method: 'post',
                    html: [
                        $('<input>', { type: 'hidden', name: 'fcs_nonce', value: fcsAdmin.nonce }),
                        $('<input>', { type: 'hidden', name: 'fcs_action', value: 'delete_plan' }),
                        $('<input>', { type: 'hidden', name: 'plan_id', value: planId })
                    ]
                });
                
                $('body').append(form);
                form.submit();
            }
        };

        // Edit Category function
        window.editCategory = function(categoryId) {
            $('#add-category-modal').show();
            $('#category-modal-title').text('Editar Categoria');
            $('#category_action').val('update_category');
            $('#category_id').val(categoryId);
            
            // Note: In production, you'd want to fetch the actual category data here
        };

        // Delete Category function
        window.deleteCategory = function(categoryId) {
            if (confirm(fcsAdmin.strings.confirmDelete)) {
                const form = $('<form>', {
                    method: 'post',
                    html: [
                        $('<input>', { type: 'hidden', name: 'fcs_nonce', value: fcsAdmin.nonce }),
                        $('<input>', { type: 'hidden', name: 'fcs_action', value: 'delete_category' }),
                        $('<input>', { type: 'hidden', name: 'category_id', value: categoryId })
                    ]
                });
                
                $('body').append(form);
                form.submit();
            }
        };

        // Close modals on outside click
        $('.fcs-modal').on('click', function(e) {
            if ($(e.target).hasClass('fcs-modal')) {
                $(this).hide();
            }
        });

        // Close modal on ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.fcs-modal').hide();
            }
        });

        // Form validation for plan form
        $('#plan-form').on('submit', function(e) {
            const model = $('#model').val().trim();
            const price = $('#price').val();
            
            if (!model) {
                e.preventDefault();
                alert('Por favor, informe o modelo do plano.');
                return false;
            }
            
            if (!price || price <= 0) {
                e.preventDefault();
                alert('Por favor, informe um preço válido.');
                return false;
            }
        });

        // Form validation for category form
        $('#category-form').on('submit', function(e) {
            const name = $('#cat_name').val().trim();
            const slug = $('#cat_slug').val().trim();
            
            if (!name) {
                e.preventDefault();
                alert('Por favor, informe o nome da categoria.');
                return false;
            }
            
            if (!slug) {
                e.preventDefault();
                alert('Por favor, informe o slug da categoria.');
                return false;
            }
        });

        // Auto-generate slug from name
        $('#cat_name').on('blur', function() {
            const name = $(this).val();
            const slugField = $('#cat_slug');
            
            if (slugField.val() === '') {
                // Simple slug generation
                const slug = name.toLowerCase()
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Remove accents
                    .replace(/[^a-z0-9]+/g, '-') // Replace non-alphanumeric with hyphens
                    .replace(/^-|-$/g, ''); // Trim hyphens from ends
                
                slugField.val(slug);
            }
        });
    });

})(jQuery);
