/**
 * Futturu Popup CTA - Frontend JavaScript
 */

(function($) {
    'use strict';

    // Popup state
    var popupShown = false;
    var isExiting = false;

    // Initialize popup
    function initPopup() {
        if (!window.futturuPopupConfig) {
            return;
        }

        var $overlay = $('#futturu-popup-overlay');
        var $container = $overlay.find('.futturu-popup-container');
        var $closeBtn = $overlay.find('.futturu-popup-close');
        var $declineBtn = $overlay.find('.futturu-popup-decline-button');
        var $ctaBtn = $overlay.find('.futturu-popup-cta-button');

        if ($overlay.length === 0) {
            return;
        }

        // Apply custom styles from template
        if (window.futturuPopupStyles) {
            var styles = window.futturuPopupStyles;
            
            $container.css({
                'background-color': styles.bgColor,
                'font-family': styles.fontFamily,
                'font-size': styles.fontSize + 'px',
                'font-weight': styles.fontWeight
            });

            if (styles.enableBlur) {
                $('body').addClass('futturu-popup-blur-enabled');
                $('body').css('--futturu-blur-intensity', styles.blurIntensity + 'px');
            }
        }

        // Show popup function
        function showPopup() {
            if (popupShown) {
                return;
            }

            popupShown = true;
            $('body').addClass('futturu-popup-active');
            $overlay.css('display', 'flex');
            
            // Trigger reflow for animation
            void $overlay[0].offsetWidth;
            
            $overlay.addClass('futturu-popup-visible');
            $container.addClass('futturu-popup-visible');

            // Set focus to close button for accessibility
            setTimeout(function() {
                $closeBtn.focus();
            }, 100);

            // Setup focus trap
            setupFocusTrap($container);

            // Set frequency cookies
            setFrequencyCookies();
        }

        // Hide popup function
        function hidePopup() {
            $('body').removeClass('futturu-popup-active');
            $overlay.removeClass('futturu-popup-visible');
            $container.removeClass('futturu-popup-visible');

            setTimeout(function() {
                $overlay.css('display', 'none');
                // Return focus to the element that triggered the popup (if any)
                $('body').focus();
            }, 300);
        }

        // Setup focus trap
        function setupFocusTrap($element) {
            var $focusableElements = $element.find(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            ).filter(':visible').filter(function() {
                return $(this).attr('tabindex') !== '-1' && !$(this).is('[disabled]');
            });

            var $firstFocusable = $focusableElements.first();
            var $lastFocusable = $focusableElements.last();

            $element.on('keydown.futturuPopup', function(e) {
                if (e.key !== 'Tab') {
                    return;
                }

                if (e.shiftKey) {
                    if (document.activeElement === $firstFocusable[0]) {
                        e.preventDefault();
                        $lastFocusable.focus();
                    }
                } else {
                    if (document.activeElement === $lastFocusable[0]) {
                        e.preventDefault();
                        $firstFocusable.focus();
                    }
                }
            });
        }

        // Remove focus trap
        function removeFocusTrap($element) {
            $element.off('keydown.futturuPopup');
        }

        // Set frequency cookies
        function setFrequencyCookies() {
            var config = window.futturuPopupConfig;
            var date = new Date();

            switch (config.frequency) {
                case 'once_per_session':
                    document.cookie = 'futturu_popup_session=1; path=/';
                    break;

                case 'once_per_days':
                    date.setTime(date.getTime() + (config.frequencyDays * 24 * 60 * 60 * 1000));
                    document.cookie = 'futturu_popup_last_shown=' + Math.floor(Date.now() / 1000) + '; expires=' + date.toUTCString() + '; path=/';
                    break;

                case 'max_count':
                    var currentCount = getCookie('futturu_popup_view_count') || 0;
                    var newCount = parseInt(currentCount) + 1;
                    date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
                    document.cookie = 'futturu_popup_view_count=' + newCount + '; expires=' + date.toUTCString() + '; path=/';
                    break;
            }
        }

        // Get cookie value
        function getCookie(name) {
            var value = '; ' + document.cookie;
            var parts = value.split('; ' + name + '=');
            if (parts.length === 2) {
                return parts.pop().split(';').shift();
            }
            return null;
        }

        // Event listeners
        $closeBtn.on('click', function(e) {
            e.preventDefault();
            hidePopup();
            removeFocusTrap($container);
        });

        if ($declineBtn.length > 0) {
            $declineBtn.on('click', function(e) {
                e.preventDefault();
                hidePopup();
                removeFocusTrap($container);
            });
        }

        // Close on overlay click
        $overlay.on('click', function(e) {
            if ($(e.target).is($overlay)) {
                hidePopup();
                removeFocusTrap($container);
            }
        });

        // Close on ESC key
        $(document).on('keydown.futturuPopup', function(e) {
            if (e.key === 'Escape' && $overlay.hasClass('futturu-popup-visible')) {
                hidePopup();
                removeFocusTrap($container);
            }
        });

        // CTA click tracking (optional)
        if ($ctaBtn.length > 0) {
            $ctaBtn.on('click', function() {
                // You can add analytics tracking here
                console.log('CTA clicked');
            });
        }

        // Display triggers
        var config = window.futturuPopupConfig;

        switch (config.displayTime) {
            case 'immediate':
                setTimeout(showPopup, 100);
                break;

            case 'delay':
                setTimeout(showPopup, config.displayDelay * 1000);
                break;

            case 'scroll':
                var scrollThreshold = $(document).height() * (config.scrollPercentage / 100);
                $(window).on('scroll.futturuPopup', function() {
                    if ($(window).scrollTop() >= scrollThreshold && !popupShown) {
                        showPopup();
                        $(window).off('scroll.futturuPopup');
                    }
                });
                break;

            case 'exit_intent':
                $(document).on('mouseleave.futturuPopup', function(e) {
                    if (e.clientY <= 0 && !popupShown && !isExiting) {
                        isExiting = true;
                        showPopup();
                        $(document).off('mouseleave.futturuPopup');
                    }
                });
                break;
        }

        // Handle mobile exit intent (when user taps outside)
        if ('ontouchstart' in window) {
            $(document).on('touchstart.futturuPopup', function(e) {
                if ($overlay.hasClass('futturu-popup-visible') && 
                    !$(e.target).closest('.futturu-popup-container').length) {
                    hidePopup();
                    removeFocusTrap($container);
                }
            });
        }
    }

    // Initialize on document ready
    $(document).ready(function() {
        initPopup();
    });

})(jQuery);
