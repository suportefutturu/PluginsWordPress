/**
 * Frontend JavaScript - Simulador Cloud Futturu
 * Interatividade, quiz, modal e envio de formulário
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Quiz interaction
        $('.fcs-quiz-option').on('click', function() {
            var $this = $(this);
            var profileId = $this.data('profile-id');
            var recommendedPlan = $this.data('recommended-plan');
            
            // Remove active class from all options
            $('.fcs-quiz-option').removeClass('active');
            $this.addClass('active');
            
            // Get plan name from data attribute or find in plans section
            var planName = getPlanName(recommendedPlan);
            
            // Show result
            $('#fcsRecommendedPlanName').text(planName + ' - Ideal para seu perfil!');
            $('#fcsQuizResult').slideDown(300);
            
            // Store profile selection
            $('#fcsTrafficProfile').val(profileId);
            
            // Scroll to plans section smoothly
            $('html, body').animate({
                scrollTop: $('#fcsPlansSection').offset().top - 100
            }, 500);
            
            // Highlight recommended plan
            highlightRecommendedPlan(recommendedPlan);
        });
        
        // Select plan button - open modal
        $(document).on('click', '.fcs-select-plan-btn', function() {
            var planId = $(this).data('plan-id');
            var planName = $(this).data('plan-name');
            var planPrice = $(this).data('plan-price');
            
            $('#fcsSelectedPlan').val(planId);
            $('#fcsModalTitle').text('Solicitar Cotação - ' + planName);
            $('#fcsModalSubtitle').text('Plano selecionado: ' + planName + ' (' + formatPrice(planPrice) + '/mês). Preencha o formulário e entraremos em contato.');
            
            openModal();
        });
        
        // Open modal button (general CTA)
        $('#fcsOpenModalBtn').on('click', function() {
            $('#fcsModalTitle').text('Fale com um Especialista');
            $('#fcsModalSubtitle').text('Preencha o formulário e nossa equipe entrará em contato para entender suas necessidades e recomendar o melhor plano.');
            openModal();
        });
        
        // Close modal
        $('#fcsCloseModalBtn').on('click', closeModal);
        $('.fcs-modal-overlay').on('click', closeModal);
        
        // Close modal with ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        
        // Form submission
        $('#fcsLeadForm').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('.fcs-submit-btn');
            var $btnText = $submitBtn.find('.fcs-btn-text');
            var $btnLoading = $submitBtn.find('.fcs-btn-loading');
            var $message = $('#fcsFormMessage');
            
            // Validate
            var name = $form.find('[name="name"]').val().trim();
            var email = $form.find('[name="email"]').val().trim();
            
            if (!name || !email) {
                showMessage('Por favor, preencha nome e e-mail.', 'error');
                return;
            }
            
            // Disable button and show loading
            $submitBtn.prop('disabled', true);
            $btnText.hide();
            $btnLoading.show();
            $message.hide();
            
            // Submit via AJAX
            $.ajax({
                url: futturuCloudSim.ajaxUrl,
                type: 'POST',
                data: $form.serialize() + '&action=futturu_send_lead',
                success: function(response) {
                    if (response.success) {
                        showMessage(response.data.message || 'Obrigado! Entraremos em contato em breve.', 'success');
                        $form[0].reset();
                        setTimeout(closeModal, 3000);
                    } else {
                        showMessage(response.data.message || 'Ocorreu um erro. Tente novamente.', 'error');
                    }
                },
                error: function() {
                    showMessage('Erro de conexão. Verifique sua internet e tente novamente.', 'error');
                },
                complete: function() {
                    $submitBtn.prop('disabled', false);
                    $btnText.show();
                    $btnLoading.hide();
                }
            });
        });
        
        // Helper functions
        function openModal() {
            $('#fcsContactModal').addClass('active').fadeIn(300);
            $('body').addClass('fcs-modal-open');
            $('#fcsName').focus();
        }
        
        function closeModal() {
            $('#fcsContactModal').removeClass('active').fadeOut(300);
            $('body').removeClass('fcs-modal-open');
        }
        
        function showMessage(text, type) {
            var $message = $('#fcsFormMessage');
            $message.removeClass('success error').addClass(type).text(text).fadeIn(300);
        }
        
        function getPlanName(planId) {
            var planNames = {
                'br1g': 'BR1G - Inicial',
                'br2g': 'BR2G - Crescimento',
                'br4g': 'BR4G - Profissional',
                'br8g': 'BR8G - Avançado',
                'br16g': 'BR16G - Enterprise'
            };
            return planNames[planId] || 'Plano Recomendado';
        }
        
        function formatPrice(price) {
            return 'R$ ' + parseFloat(price).toFixed(2).replace('.', ',');
        }
        
        function highlightRecommendedPlan(planId) {
            // Remove previous highlights
            $('.fcs-plan-card').removeClass('fcs-recommended');
            
            // Add highlight to recommended plan
            var $targetCard = $('.fcs-plan-card[data-plan-id="' + planId + '"]');
            if ($targetCard.length) {
                $targetCard.addClass('fcs-recommended');
                
                // Smooth scroll to the card
                $('html, body').animate({
                    scrollTop: $targetCard.offset().top - 100
                }, 500);
            }
        }
        
        // Add hover effects to plan cards
        $('.fcs-plan-card').on('mouseenter', function() {
            $('.fcs-plan-card').not(this).css('opacity', '0.7');
        }).on('mouseleave', function() {
            $('.fcs-plan-card').css('opacity', '1');
        });
        
        // Animate benefits on scroll
        $(window).on('scroll', function() {
            var windowHeight = $(window).height();
            var scrollTop = $(window).scrollTop();
            
            $('.fcs-benefit-item').each(function() {
                var elementTop = $(this).offset().top;
                if (elementTop < scrollTop + windowHeight - 100) {
                    $(this).addClass('fcs-animate-in');
                }
            });
        });
        
        // Trigger animation check on load
        $(window).trigger('scroll');
    });
    
})(jQuery);
