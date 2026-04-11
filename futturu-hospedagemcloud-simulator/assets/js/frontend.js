/**
 * Futturu Cloud Simulator - Frontend JavaScript
 */

(function($) {
    'use strict';
    
    // Initialize on document ready
    $(document).ready(function() {
        initSimulator();
    });
    
    function initSimulator() {
        var $simulator = $('.futturu-hospedagemcloud-simulator');
        if (!$simulator.length) return;
        
        var discount = parseInt($simulator.data('discount')) || 10;
        
        // Initialize sliders for each category
        initSliders();
        
        // Handle recurrence toggle
        handleRecurrenceToggle(discount);
        
        // Handle category tabs
        handleCategoryTabs();
        
        // Handle FAQ accordion
        handleFAQAccordion();
        
        // Handle more info modal
        handleMoreInfoModal();
        
        // Handle quote modal
        handleQuoteModal();
        
        // Handle quote form submission
        handleQuoteFormSubmission();
    }
    
    // Initialize sliders for each category
    function initSliders() {
        $('.fcs-slider').each(function() {
            var $slider = $(this);
            var $track = $slider.find('.fcs-slider-track');
            var $wrapper = $slider.find('.fcs-slider-wrapper');
            var $prevBtn = $slider.find('.fcs-slider-prev');
            var $nextBtn = $slider.find('.fcs-slider-next');
            
            var scrollAmount = 320; // Width of card + gap
            var currentScroll = 0;
            var isDragging = false;
            var startX = 0;
            var startScroll = 0;
            
            // Update button states function
            var updateButtons = function() {
                var trackWidth = $track[0].scrollWidth;
                var wrapperWidth = $track[0].clientWidth;
                var maxScroll = Math.max(0, trackWidth - wrapperWidth);
                
                if (currentScroll <= 0) {
                    $prevBtn.css('opacity', '0.5').css('pointer-events', 'none');
                } else {
                    $prevBtn.css('opacity', '1').css('pointer-events', 'auto');
                }
                
                if (currentScroll >= maxScroll - 1) {
                    $nextBtn.css('opacity', '0.5').css('pointer-events', 'none');
                } else {
                    $nextBtn.css('opacity', '1').css('pointer-events', 'auto');
                }
            };
            
            // Handle prev button click
            $prevBtn.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var trackWidth = $track[0].scrollWidth;
                var wrapperWidth = $track[0].clientWidth;
                var maxScroll = Math.max(0, trackWidth - wrapperWidth);
                currentScroll = Math.max(0, currentScroll - scrollAmount);
                $wrapper.css('transform', 'translateX(-' + currentScroll + 'px)');
                updateButtons();
            });
            
            // Handle next button click
            $nextBtn.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var trackWidth = $track[0].scrollWidth;
                var wrapperWidth = $track[0].clientWidth;
                var maxScroll = Math.max(0, trackWidth - wrapperWidth);
                currentScroll = Math.min(maxScroll, currentScroll + scrollAmount);
                $wrapper.css('transform', 'translateX(-' + currentScroll + 'px)');
                updateButtons();
            });
            
            // Handle touch/drag events on track
            $track.on('mousedown touchstart', function(e) {
                isDragging = true;
                startX = e.type === 'touchstart' ? e.originalEvent.touches[0].pageX : e.pageX;
                startScroll = currentScroll;
                $wrapper.css('transition', 'none');
                e.preventDefault();
            });
            
            $(document).on('mousemove touchmove', function(e) {
                if (!isDragging) return;
                
                var currentX = e.type === 'touchmove' ? e.originalEvent.touches[0].pageX : e.pageX;
                var diff = startX - currentX;
                currentScroll = startScroll + diff;
                
                var trackWidth = $track[0].scrollWidth;
                var wrapperWidth = $track[0].clientWidth;
                var maxScroll = Math.max(0, trackWidth - wrapperWidth);
                currentScroll = Math.max(0, Math.min(maxScroll, currentScroll));
                
                $wrapper.css('transform', 'translateX(-' + currentScroll + 'px)');
            });
            
            $(document).on('mouseup touchend', function() {
                if (isDragging) {
                    isDragging = false;
                    $wrapper.css('transition', 'transform 0.3s ease');
                    updateButtons();
                }
            });
            
            // Prevent click event after drag
            $track.on('click', function(e) {
                if (Math.abs(startX - (e.pageX || 0)) > 5) {
                    e.preventDefault();
                }
            });
            
            // Handle window resize
            $(window).on('resize', function() {
                currentScroll = 0;
                $wrapper.css('transform', 'translateX(0)');
                updateButtons();
            });
            
            // Initial button state
            updateButtons();
        });
    }
    
    // Handle recurrence toggle (Monthly/Annual)
    function handleRecurrenceToggle(discount) {
        $('input[name="fcs-recurrence"]').on('change', function() {
            var isAnnual = $(this).val() === 'annual';
            
            $('.fcs-plan-card').each(function() {
                var $card = $(this);
                var $monthlyPrice = $card.find('.fcs-price-monthly');
                var $annualPrice = $card.find('.fcs-price-annual');
                
                if (isAnnual) {
                    $monthlyPrice.hide();
                    $annualPrice.fadeIn();
                } else {
                    $annualPrice.hide();
                    $monthlyPrice.fadeIn();
                }
            });
            
            // Update recurrence in quote modal
            $('#fcs-recurrence').val(isAnnual ? 'annual' : 'monthly');
        });
    }
    
    // Handle category tabs
    function handleCategoryTabs() {
        $('.fcs-tab-button').on('click', function() {
            var $tab = $(this);
            var category = $tab.data('category');
            
            // Update active tab
            $('.fcs-tab-button').removeClass('active');
            $tab.addClass('active');
            
            // Show corresponding content
            $('.fcs-category-content').removeClass('active');
            $('.fcs-category-content[data-category="' + category + '"]').addClass('active');
            
            // Reset slider position for new category and reinitialize
            setTimeout(function() {
                var $slider = $('.fcs-category-content.active .fcs-slider');
                var $track = $slider.find('.fcs-slider-track');
                var $wrapper = $slider.find('.fcs-slider-wrapper');
                
                // Reset scroll position
                $wrapper.css('transform', 'translateX(0)');
                $wrapper.css('transition', 'transform 0.3s ease');
                
                // Trigger button state update by simulating a resize
                $(window).trigger('resize');
            }, 100);
        });
    }
    
    // Handle FAQ accordion
    function handleFAQAccordion() {
        $('.fcs-faq-question').on('click', function() {
            var $item = $(this).closest('.fcs-faq-item');
            var isActive = $item.hasClass('active');
            
            // Close all items
            $('.fcs-faq-item').removeClass('active');
            
            // Toggle clicked item
            if (!isActive) {
                $item.addClass('active');
            }
        });
    }
    
    // Handle more info modal
function handleMoreInfoModal() {
        $('.fcs-btn-more-info').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(this);
            
            // Build plan data from individual data attributes
            var planData = {
                modelo: $btn.data('model'),
                ram: $btn.data('ram'),
                cpu: $btn.data('cpu'),
                disco: $btn.data('disco'),
                visualizacoes: $btn.data('visualizacoes'),
                uso_indicado: $btn.data('uso'),
                categoria: $btn.data('categoria')
            };
            
            var featuresData = $btn.data('features');
            
            // Populate modal content
            $('#fcs-details-title').text(planData.modelo);
            
            // Build resources list
            var resourcesHtml = '';
            if (planData.categoria && planData.categoria.indexOf('E-mails') !== -1) {
                resourcesHtml += '<li><strong>Disco:</strong> ' + planData.disco + '</li>';
            } else {
                resourcesHtml += '<li><strong>RAM:</strong> ' + planData.ram + '</li>';
            }
            resourcesHtml += '<li><strong>CPU:</strong> ' + planData.cpu + '</li>';
            resourcesHtml += '<li><strong>SSD:</strong> ' + planData.disco + '</li>';
            
            if (planData.visualizacoes) {
                resourcesHtml += '<li><strong>Visualizações/mês:</strong> ' + planData.visualizacoes + '</li>';
            }
            
            if (planData.uso_indicado) {
                resourcesHtml += '<li><strong>Uso Indicado:</strong> ' + planData.uso_indicado + '</li>';
            }
            
            $('#fcs-details-resources').html(resourcesHtml);
            
            // Build features list
            var featuresHtml = '';
            if (featuresData && featuresData.length > 0) {
                featuresData.forEach(function(feature) {
                    featuresHtml += '<li>' + feature + '</li>';
                });
            }
            $('#fcs-details-features').html(featuresHtml);
            
            // Store plan model for quote button
            $('.fcs-btn-quote-from-details').data('plan-model', planData.modelo);
            
            // Show modal
            $('#fcs-details-modal').fadeIn();
            $('body').css('overflow', 'hidden');
        });
        
        // Close details modal
        $('#fcs-details-modal .fcs-modal-close').on('click', function() {
            $('#fcs-details-modal').fadeOut();
            $('body').css('overflow', 'auto');
        });
        
        // Quote from details modal
        $('.fcs-btn-quote-from-details').on('click', function() {
            var planModel = $(this).data('plan-model');
            
            // Close details modal
            $('#fcs-details-modal').fadeOut();
            
            // Open quote modal with pre-selected plan
            setTimeout(function() {
                $('#fcs-plan-interest').val(planModel);
                $('#fcs-quote-modal').fadeIn();
            }, 300);
            
            $('body').css('overflow', 'hidden');
        });
    }
    
    // Handle quote modal
    function handleQuoteModal() {
        // Open quote modal from plan card
        $('.fcs-btn-quote').on('click', function() {
            var planModel = $(this).data('plan-model');
            $('#fcs-plan-interest').val(planModel);
            $('#fcs-quote-modal').fadeIn();
            $('body').css('overflow', 'hidden');
        });
        
        // Open quote modal from global CTA
        $('.fcs-btn-global-quote').on('click', function() {
            $('#fcs-quote-modal').fadeIn();
            $('body').css('overflow', 'hidden');
        });
        
        // Close quote modal
        $('#fcs-quote-modal .fcs-modal-close').on('click', function() {
            $('#fcs-quote-modal').fadeOut();
            $('#fcs-quote-form')[0].reset();
            $('.fcs-form-message').hide().removeClass('success error');
            $('body').css('overflow', 'auto');
        });
        
        // Close modal on overlay click
        $('.fcs-modal-overlay').on('click', function(e) {
            if ($(e.target).hasClass('fcs-modal-overlay')) {
                $(this).fadeOut();
                $('#fcs-quote-form')[0].reset();
                $('.fcs-form-message').hide().removeClass('success error');
                $('body').css('overflow', 'auto');
            }
        });
        
        // Close modal on ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.fcs-modal-overlay').fadeOut();
                $('#fcs-quote-form')[0].reset();
                $('.fcs-form-message').hide().removeClass('success error');
                $('body').css('overflow', 'auto');
            }
        });
    }
    
    // Handle quote form submission
    function handleQuoteFormSubmission() {
        $('#fcs-quote-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('.fcs-btn-submit');
            var $submitText = $submitBtn.find('.fcs-submit-text');
            var $submitLoading = $submitBtn.find('.fcs-submit-loading');
            var $messageDiv = $form.find('.fcs-form-message');
            
            // Get form data
            var formData = {
                action: 'futturu_hospedagemcloud_send_quote',
                nonce: futturuCloudSim.nonce,
                name: $form.find('#fcs-name').val(),
                email: $form.find('#fcs-email').val(),
                phone: $form.find('#fcs-phone').val(),
                plan_interest: $form.find('#fcs-plan-interest').val(),
                recurrence: $form.find('#fcs-recurrence').val(),
                message: $form.find('#fcs-message').val()
            };
            
            // Disable submit button
            $submitBtn.prop('disabled', true);
            $submitText.hide();
            $submitLoading.show();
            
            // Send AJAX request
            $.ajax({
                url: futturuCloudSim.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $messageDiv.text(response.data.message)
                            .removeClass('error')
                            .addClass('success')
                            .fadeIn();
                        
                        // Reset form after short delay
                        setTimeout(function() {
                            $form[0].reset();
                            $messageDiv.fadeOut();
                        }, 3000);
                    } else {
                        $messageDiv.text(response.data.message || futturuCloudSim.i18n.error)
                            .removeClass('success')
                            .addClass('error')
                            .fadeIn();
                    }
                },
                error: function() {
                    $messageDiv.text(futturuCloudSim.i18n.error)
                        .removeClass('success')
                        .addClass('error')
                        .fadeIn();
                },
                complete: function() {
                    // Re-enable submit button
                    $submitBtn.prop('disabled', false);
                    $submitText.show();
                    $submitLoading.hide();
                }
            });
        });
    }
    
})(jQuery);
