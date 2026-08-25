/**
 * Lead Scoring Form - Frontend JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        var $form = $('#lsf-lead-form');
        var $submitBtn = $('#lsf-submit-btn');
        var $btnText = $('.lsf-btn-text', $submitBtn);
        var $btnLoading = $('.lsf-btn-loading', $submitBtn);
        var $message = $('#lsf-message');
        
        // Handle form submission
        $form.on('submit', function(e) {
            e.preventDefault();
            
            // Validate required fields
            var isValid = true;
            $('input[required], select[required]', $form).each(function() {
                if (!$(this).val().trim()) {
                    isValid = false;
                    $(this).css('border-color', '#ef4444');
                } else {
                    $(this).css('border-color', '#e5e7eb');
                }
            });
            
            if (!isValid) {
                showMessage(__('Por favor, preencha todos os campos obrigatórios.', 'lead-scoring-form'), 'error');
                return;
            }
            
            // Disable submit button and show loading state
            setButtonLoading(true);
            
            // Prepare form data
            var formData = {
                action: 'lsf_submit_form',
                lsf_nonce: $('input[name="lsf_nonce"]', $form).val(),
                lsf_name: $('input[name="lsf_name"]', $form).val(),
                lsf_company: $('input[name="lsf_company"]', $form).val(),
                lsf_contact: $('input[name="lsf_contact"]', $form).val(),
                lsf_objective: $('select[name="lsf_objective"]', $form).val(),
                lsf_deadline: $('select[name="lsf_deadline"]', $form).val(),
                lsf_investment: $('select[name="lsf_investment"]', $form).val()
            };
            
            // Send AJAX request
            $.ajax({
                url: lsf_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showMessage(response.data.message, 'success');
                        $form[0].reset();
                        
                        // Scroll to message
                        $('html, body').animate({
                            scrollTop: $message.offset().top - 100
                        }, 500);
                    } else {
                        showMessage(response.data.message || __('Ocorreu um erro. Por favor, tente novamente.', 'lead-scoring-form'), 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Form submission error:', error);
                    showMessage(__('Ocorreu um erro ao enviar o formulário. Por favor, tente novamente.', 'lead-scoring-form'), 'error');
                },
                complete: function() {
                    setButtonLoading(false);
                }
            });
        });
        
        // Remove error styling on input
        $('input, select', $form).on('input change', function() {
            $(this).css('border-color', '#e5e7eb');
            $message.hide();
        });
        
        // Set button loading state
        function setButtonLoading(loading) {
            if (loading) {
                $submitBtn.prop('disabled', true);
                $btnText.hide();
                $btnLoading.show();
            } else {
                $submitBtn.prop('disabled', false);
                $btnText.show();
                $btnLoading.hide();
            }
        }
        
        // Show message
        function showMessage(text, type) {
            var icon = type === 'success' 
                ? '<span class="lsf-message-icon">✓</span>' 
                : '<span class="lsf-message-icon">!</span>';
            
            $message
                .html(icon + '<span class="lsf-message-text">' + text + '</span>')
                .removeClass('success error')
                .addClass(type)
                .fadeIn(300);

            // Scroll to message on success
            if (type === 'success') {
                $('html, body').animate({
                    scrollTop: $message.offset().top - 100
                }, 500);
                
                // Don't auto-hide success messages - user should see next steps
            }
        }
        
        // Input mask for WhatsApp/Email field
        var $contactField = $('#lsf_contact');
        $contactField.on('input', function() {
            var value = $(this).val();
            
            // Check if it looks like a phone number
            if (/^[\d\s\(\)\-\+]+$/.test(value)) {
                // Apply phone mask
                value = value.replace(/\D/g, '');
                
                if (value.length <= 11) {
                    if (value.length > 6) {
                        value = '(' + value.substring(0, 2) + ') ' + value.substring(2, 7) + '-' + value.substring(7, 11);
                    } else if (value.length > 2) {
                        value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
                    }
                }
                
                $(this).val(value);
            }
        });
        
        // Smooth scroll for better UX
        $('html').css('scroll-behavior', 'smooth');
        
    });
    
})(jQuery);
