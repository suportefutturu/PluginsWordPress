/**
 * Frontend JavaScript for Futturu Promo Plugin
 */

(function($) {
    'use strict';
    
    // Countdown Timer
    function initCountdown() {
        var endDateStr = futturuPromo.end_date;
        var endDate = new Date(endDateStr).getTime();
        
        function updateCountdown() {
            var now = new Date().getTime();
            var distance = endDate - now;
            
            if (distance < 0) {
                // Timer expired
                $('#futturu-promo-countdown').html('<div style="color: #ff6b6b; font-weight: bold; font-size: 24px;">' + futturuPromo.i18n.expired + '</div>');
                $('.futturu-promo-btn-primary').hide();
                return;
            }
            
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            $('#countdown-days').text(String(days).padStart(2, '0'));
            $('#countdown-hours').text(String(hours).padStart(2, '0'));
            $('#countdown-minutes').text(String(minutes).padStart(2, '0'));
            $('#countdown-seconds').text(String(seconds).padStart(2, '0'));
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
    
    // Form Submission
    function initFormSubmission() {
        $('#futturu-promo-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('.futturu-promo-btn-submit');
            var originalText = $submitBtn.text();
            
            // Validate form
            var name = $('#futturu_name').val().trim();
            var email = $('#futturu_email').val().trim();
            var phone = $('#futturu_phone').val().trim();
            var company = $('#futturu_company').val().trim();
            var message = $('#futturu_message').val().trim();
            var paymentMethod = $('input[name="futturu_payment_method"]:checked').val();
            var amount = $('#futturu_amount').val();
            
            // Basic validation
            if (!name || !email || !phone || !paymentMethod || !amount) {
                alert('Por favor, preencha todos os campos obrigatórios.');
                return;
            }
            
            // Email validation
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Por favor, insira um e-mail válido.');
                return;
            }
            
            // Disable submit button
            $submitBtn.prop('disabled', true).text(futturuPromo.i18n.loading);
            
            // Prepare FormData
            var formData = new FormData();
            formData.append('action', 'futturu_promo_submit');
            formData.append('nonce', futturuPromo.nonce);
            formData.append('name', name);
            formData.append('email', email);
            formData.append('phone', phone);
            formData.append('company', company);
            formData.append('message', message);
            formData.append('payment_method', paymentMethod);
            formData.append('amount', amount);
            
            // Add file if present
            var fileInput = document.getElementById('futturu_receipt');
            if (fileInput.files.length > 0) {
                formData.append('receipt', fileInput.files[0]);
            }
            
            // Send AJAX request
            $.ajax({
                url: futturuPromo.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Hide form and show success message
                        $form.hide();
                        $('.futturu-promo-whatsapp-cta').hide();
                        $('.futturu-promo-alternative-cta').hide();
                        $('#futturu-promo-success').fadeIn();
                        
                        // Scroll to success message
                        $('html, body').animate({
                            scrollTop: $('#futturu-promo-success').offset().top - 100
                        }, 500);
                    } else {
                        alert(response.data.message || futturuPromo.i18n.error);
                        $submitBtn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    alert(futturuPromo.i18n.error);
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });
    }
    
    // Smooth scroll for anchor links
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 600);
            }
        });
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        if (typeof futturuPromo !== 'undefined') {
            initCountdown();
            initFormSubmission();
            initSmoothScroll();
        }
    });
    
})(jQuery);

// Copy PIX Key function (global scope)
function futturuCopyPixKey() {
    var copyText = document.getElementById('pix-key-input');
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    
    navigator.clipboard.writeText(copyText.value).then(function() {
        var msg = document.getElementById('pix-copied-msg');
        msg.style.display = 'block';
        setTimeout(function() {
            msg.style.display = 'none';
        }, 2000);
    }).catch(function(err) {
        // Fallback for older browsers
        document.execCommand('copy');
        var msg = document.getElementById('pix-copied-msg');
        msg.style.display = 'block';
        setTimeout(function() {
            msg.style.display = 'none';
        }, 2000);
    });
}
