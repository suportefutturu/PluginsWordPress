/**
 * Futturu Popup CTA - Admin JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize color pickers
        $('.color-picker').wpColorPicker();

        // Update blur intensity display
        $('input[name="futturu_popup_options[blur_intensity]"]').on('input change', function() {
            $(this).next('.blur-intensity-value').text($(this).val() + 'px');
        });

        // Preview functionality
        $('#futturu-popup-preview-btn').on('click', function() {
            var options = {};

            // Gather all form values
            $('#futturu-popup-cta input, #futturu-popup-cta select, #futturu-popup-cta textarea').each(function() {
                var name = $(this).attr('name');
                if (name && name.indexOf('futturu_popup_options') !== -1) {
                    var key = name.replace(/futturu_popup_options\[([^\]]+)\]/, '$1');
                    
                    if ($(this).is(':checkbox')) {
                        options[key] = $(this).is(':checked') ? 1 : 0;
                    } else if ($(this).is(':radio')) {
                        if ($(this).is(':checked')) {
                            options[key] = $(this).val();
                        }
                    } else if ($(this).is('select[multiple]')) {
                        options[key] = $(this).val() || [];
                    } else {
                        options[key] = $(this).val();
                    }
                }
            });

            // Handle checkbox arrays
            $('input[name^="futturu_popup_options["][type="checkbox"]').each(function() {
                var name = $(this).attr('name');
                var key = name.replace(/futturu_popup_options\[([^\]]+)\]\[\]/, '$1');
                
                if (!options[key]) {
                    options[key] = [];
                }
                
                if ($(this).is(':checked')) {
                    options[key].push($(this).val());
                }
            });

            // Show loading state
            var $btn = $(this);
            $btn.prop('disabled', true).text('Carregando...');

            // Make AJAX request
            $.ajax({
                url: futturuPopupAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'futturu_popup_preview',
                    nonce: futturuPopupAdmin.nonce,
                    options: options
                },
                success: function(response) {
                    if (response.success) {
                        // Display preview
                        $('#futturu-popup-preview-container').html(response.data.html);
                        
                        // Trigger popup after a short delay
                        setTimeout(function() {
                            var $previewOverlay = $('#futturu-popup-overlay');
                            $previewOverlay.css('display', 'flex');
                            
                            // Trigger reflow
                            void $previewOverlay[0].offsetWidth;
                            
                            $previewOverlay.addClass('futturu-popup-visible');
                            $previewOverlay.find('.futturu-popup-container').addClass('futturu-popup-visible');
                            
                            // Apply styles
                            if (window.futturuPopupStyles) {
                                var styles = window.futturuPopupStyles;
                                var $container = $previewOverlay.find('.futturu-popup-container');
                                
                                $container.css({
                                    'background-color': styles.bgColor,
                                    'font-family': styles.fontFamily,
                                    'font-size': styles.fontSize + 'px',
                                    'font-weight': styles.fontWeight
                                });
                            }
                            
                            // Add close functionality for preview
                            $previewOverlay.find('.futturu-popup-close, .futturu-popup-decline-button').on('click', function() {
                                $previewOverlay.removeClass('futturu-popup-visible');
                                $previewOverlay.find('.futturu-popup-container').removeClass('futturu-popup-visible');
                                
                                setTimeout(function() {
                                    $previewOverlay.css('display', 'none');
                                    $('#futturu-popup-preview-container').empty();
                                }, 300);
                            });
                            
                            $previewOverlay.on('click', function(e) {
                                if ($(e.target).is($previewOverlay)) {
                                    $previewOverlay.removeClass('futturu-popup-visible');
                                    $previewOverlay.find('.futturu-popup-container').removeClass('futturu-popup-visible');
                                    
                                    setTimeout(function() {
                                        $previewOverlay.css('display', 'none');
                                        $('#futturu-popup-preview-container').empty();
                                    }, 300);
                                }
                            });
                        }, 500);
                    } else {
                        alert('Erro ao carregar preview. Tente novamente.');
                    }
                },
                error: function() {
                    alert('Erro ao carregar preview. Tente novamente.');
                },
                complete: function() {
                    $btn.prop('disabled', false).text(futturuPopupAdmin.previewText || 'Ver Preview');
                }
            });
        });

        // Toggle field visibility based on display pages selection
        $('#display-pages-select').on('change', function() {
            var value = $(this).val();
            
            // Hide all conditional fields first
            $('.page-selection').closest('tr').hide();
            $('.category-selection').closest('tr').hide();
            
            // Show relevant fields
            if (value === 'specific_pages' || value === 'except_pages') {
                $('.page-selection').closest('tr').show();
            } else if (value === 'categories') {
                $('.category-selection').closest('tr').show();
            }
        }).trigger('change');

        // Toggle field visibility based on display time selection
        $('#display-time-select').on('change', function() {
            var value = $(this).val();
            
            // Hide delay and scroll fields
            $('input[name="futturu_popup_options[display_delay]"]').closest('tr').hide();
            $('input[name="futturu_popup_options[scroll_percentage]"]').closest('tr').hide();
            
            // Show relevant fields
            if (value === 'delay') {
                $('input[name="futturu_popup_options[display_delay]"]').closest('tr').show();
            } else if (value === 'scroll') {
                $('input[name="futturu_popup_options[scroll_percentage]"]').closest('tr').show();
            }
        }).trigger('change');

        // Toggle field visibility based on frequency selection
        $('#frequency-select').on('change', function() {
            var value = $(this).val();
            
            // Hide frequency fields
            $('input[name="futturu_popup_options[frequency_days]"]').closest('tr').hide();
            $('input[name="futturu_popup_options[frequency_count]"]').closest('tr').hide();
            
            // Show relevant fields
            if (value === 'once_per_days') {
                $('input[name="futturu_popup_options[frequency_days]"]').closest('tr').show();
            } else if (value === 'max_count') {
                $('input[name="futturu_popup_options[frequency_count]"]').closest('tr').show();
            }
        }).trigger('change');
    });

})(jQuery);
