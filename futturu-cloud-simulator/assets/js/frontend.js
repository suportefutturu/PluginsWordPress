/**
 * Futturu Cloud Simulator - Frontend JavaScript
 * Handles category tabs, modals, and quote form submission
 */

(function($) {
    'use strict';

    // Current selected plan for quote
    let currentPlan = '';

    /**
     * Initialize simulator functionality
     */
    function initSimulator() {
        // Category tab switching
        $('.fcs-category-tab').on('click', function() {
            const categoryId = $(this).data('category');
            
            // Update active tab
            $('.fcs-category-tab').removeClass('active');
            $(this).addClass('active');
            
            // Show corresponding content
            $('.fcs-category-content').removeClass('active');
            $(`.fcs-category-content[data-category-id="${categoryId}"]`).addClass('active');
        });

        // Info button - open plan details modal
        $('.fcs-info-btn').on('click', function() {
            const plan = $(this).data('plan');
            const details = $(this).data('details');
            const ram = $(this).data('ram');
            const cpu = $(this).data('cpu');
            const disk = $(this).data('disk');
            const price = $(this).data('price');

            $('#fcs-modal-plan-name').text(plan);
            $('#fcs-modal-ram').text(ram);
            $('#fcs-modal-cpu').text(cpu);
            $('#fcs-modal-disk').text(disk);
            $('#fcs-modal-description').text(details);
            $('#fcs-modal-price-value').text(formatPrice(price));
            
            currentPlan = plan;
            
            $('#fcs-plan-modal').fadeIn(200);
            $('body').css('overflow', 'hidden');
        });

        // CTA button - open quote modal with plan
        $('.fcs-cta-btn').on('click', function() {
            const plan = $(this).data('plan');
            openQuoteModal(plan);
        });

        // Quote form submission
        $('#fcs-quote-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $submitBtn = $form.find('.fcs-submit-btn');
            const $btnText = $submitBtn.find('.btn-text');
            const $btnLoading = $submitBtn.find('.btn-loading');
            const $response = $('#fcs-form-response');

            // Show loading state
            $btnText.hide();
            $btnLoading.show();
            $submitBtn.prop('disabled', true);

            // Prepare form data
            const formData = new FormData(this);
            formData.append('nonce', fcsFrontend.nonce);

            // Send AJAX request
            $.ajax({
                url: fcsFrontend.ajaxUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $response.removeClass('error').addClass('success')
                                 .text(response.data.message).fadeIn(300);
                        $form[0].reset();
                        
                        // Close modal after 3 seconds
                        setTimeout(function() {
                            closeQuoteModal();
                        }, 3000);
                    } else {
                        $response.removeClass('success').addClass('error')
                                 .text(response.data.message || fcsFrontend.strings.error).fadeIn(300);
                    }
                },
                error: function() {
                    $response.removeClass('success').addClass('error')
                             .text(fcsFrontend.strings.error).fadeIn(300);
                },
                complete: function() {
                    $btnText.show();
                    $btnLoading.hide();
                    $submitBtn.prop('disabled', false);
                }
            });
        });

        // Close modal on overlay click
        $('.fcs-modal-overlay').on('click', function(e) {
            if ($(e.target).hasClass('fcs-modal-overlay')) {
                closeAllModals();
            }
        });

        // Close modal on ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAllModals();
            }
        });
    }

    /**
     * Open quote modal
     */
    window.openQuoteModal = function(planName) {
        currentPlan = planName || '';
        $('#quote-plan-interest').val(currentPlan);
        $('#fcs-quote-modal').fadeIn(200);
        $('#fcs-form-response').hide();
        $('body').css('overflow', 'hidden');
    };

    /**
     * Close quote modal
     */
    window.closeQuoteModal = function() {
        $('#fcs-quote-modal').fadeOut(200);
        $('body').css('overflow', '');
        $('#fcs-form-response').hide().removeClass('success error');
    };

    /**
     * Close plan details modal
     */
    window.closePlanModal = function() {
        $('#fcs-plan-modal').fadeOut(200);
        $('body').css('overflow', '');
    };

    /**
     * Open quote modal from plan details
     */
    window.openQuoteModalFromPlan = function() {
        closePlanModal();
        setTimeout(function() {
            openQuoteModal(currentPlan);
        }, 200);
    };

    /**
     * Close all modals
     */
    function closeAllModals() {
        closePlanModal();
        closeQuoteModal();
    }

    /**
     * Format price to Brazilian Real
     */
    function formatPrice(price) {
        return 'R$ ' + parseFloat(price).toFixed(2).replace('.', ',');
    }

    // Initialize on document ready
    $(document).ready(function() {
        initSimulator();
    });

})(jQuery);
