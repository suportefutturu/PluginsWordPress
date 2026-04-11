jQuery(document).ready(function($) {
    var $simulator = $('.fcs-simulator');
    var currentView = $simulator.data('default-view') || 'annual';
    
    // Inicializar visualização
    updatePriceDisplay(currentView);
    
    // Seletor de Recorrência
    $('.fcs-recurrence-btn').on('click', function() {
        var $btn = $(this);
        currentView = $btn.data('view');
        
        $('.fcs-recurrence-btn').removeClass('active');
        $btn.addClass('active');
        
        updatePriceDisplay(currentView);
        $('#fcs-quote-recurrence').val(currentView);
    });
    
    function updatePriceDisplay(view) {
        $('.fcs-price-display').each(function() {
            var $display = $(this);
            $display.removeClass('show-monthly show-annual');
            
            if (view === 'annual') {
                $display.addClass('show-annual');
            } else {
                $display.addClass('show-monthly');
            }
        });
        
        // Atualizar badges recomendados
        $('.fcs-recommended-badge').remove();
        if (view === 'annual') {
            $('.fcs-plan-card').prepend('<div class="fcs-recommended-badge">Recomendado</div>');
        }
    }
    
    // Navegação por Categorias
    $('.fcs-category-btn').on('click', function() {
        var category = $(this).data('category');
        
        $('.fcs-category-btn').removeClass('active');
        $(this).addClass('active');
        
        $('.fcs-slider-container').removeClass('active').addClass('hidden');
        $('.fcs-slider-container[data-category="' + category + '"]').removeClass('hidden').addClass('active');
        
        // Reset slider position
        setTimeout(function() {
            initSlider($('.fcs-slider-container[data-category="' + category + '"] .fcs-slider'));
        }, 100);
    });
    
    // Slider functionality
    function initSlider($slider) {
        if (!$slider.length) return;
        
        var $track = $slider.find('.fcs-slider-track');
        var $wrapper = $slider.find('.fcs-slider-wrapper');
        var $prevBtn = $slider.find('.fcs-slider-prev');
        var $nextBtn = $slider.find('.fcs-slider-next');
        
        var cardWidth = 300; // 280px + 20px gap
        var visibleCards = Math.floor($track.width() / cardWidth);
        var totalCards = $wrapper.children().length;
        var currentIndex = 0;
        var maxIndex = Math.max(0, totalCards - visibleCards);
        
        function updateSlider() {
            var offset = -currentIndex * cardWidth;
            $wrapper.css('transform', 'translateX(' + offset + 'px)');
            
            $prevBtn.prop('disabled', currentIndex <= 0);
            $nextBtn.prop('disabled', currentIndex >= maxIndex);
        }
        
        $prevBtn.on('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        });
        
        $nextBtn.on('click', function() {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateSlider();
            }
        });
        
        // Touch support
        var startX, currentX;
        var isDragging = false;
        
        $track.on('mousedown touchstart', function(e) {
            startX = e.type === 'touchstart' ? e.originalEvent.touches[0].clientX : e.clientX;
            isDragging = true;
            $wrapper.css('transition', 'none');
        });
        
        $(document).on('mousemove touchmove', function(e) {
            if (!isDragging) return;
            
            currentX = e.type === 'touchmove' ? e.originalEvent.touches[0].clientX : e.clientX;
            var diff = currentX - startX;
            var currentOffset = -currentIndex * cardWidth;
            $wrapper.css('transform', 'translateX(' + (currentOffset + diff) + 'px)');
        });
        
        $(document).on('mouseup touchend', function() {
            if (!isDragging) return;
            isDragging = false;
            $wrapper.css('transition', 'transform 0.3s ease');
            
            var diff = currentX - startX;
            if (Math.abs(diff) > 50) {
                if (diff > 0 && currentIndex > 0) {
                    currentIndex--;
                } else if (diff < 0 && currentIndex < maxIndex) {
                    currentIndex++;
                }
            }
            updateSlider();
        });
        
        // Initialize
        updateSlider();
        
        // Handle resize
        $(window).on('resize', function() {
            visibleCards = Math.floor($track.width() / cardWidth);
            maxIndex = Math.max(0, totalCards - visibleCards);
            currentIndex = Math.min(currentIndex, maxIndex);
            updateSlider();
        });
    }
    
    // Initialize all sliders
    $('.fcs-slider').each(function() {
        initSlider($(this));
    });
    
    // Modal Mais Info
    $('.fcs-btn-more-info').on('click', function(e) {
        e.stopPropagation();
        var planData = $(this).data('plan');
        
        $('#fcs-modal-title').text(planData.nome);
        
        var featuresList = '';
        var features = [
            'HTTPS Automático (Let\'s Encrypt)',
            'Backup Automático',
            'CDN + Cache Automática',
            'Pagespeed Optimizer',
            'Firewall e Proteção Anti-blocklist',
            'Monitoramento de Infraestrutura 24/7',
            'Atualizações de Segurança Automáticas',
            'Painel de Controle Automatizado',
            'Migração de Sites Grátis',
            'Banco de Dados Incluso',
            'DNS Gerenciado'
        ];
        
        $.each(features, function(i, f) {
            featuresList += '<li>✓ ' + f + '</li>';
        });
        
        var usageText = planData.uso_indicado ? '<p><strong>Uso Indicado:</strong> ' + planData.uso_indicado + '</p>' : '';
        var viewsText = planData.visualizacoes ? '<p><strong>Visualizações/mês:</strong> ~' + planData.visualizacoes + '</p>' : '';
        
        $('#fcs-modal-body').html('\n            <div class="fcs-modal-resources">\n                <p><strong>Recursos:</strong></p>\n                <ul>\n                    <li>RAM: ' + planData.ram + '</li>\n                    <li>CPU: ' + planData.cpu + '</li>\n                    <li>Disco SSD: ' + planData.disco + '</li>\n                </ul>\n            </div>\n            ' + viewsText + '\n            ' + usageText + '\n            <div class="fcs-modal-features">\n                <p><strong>Funcionalidades Incluídas:</strong></p>\n                <ul>' + featuresList + '</ul>\n            </div>\n        ');
        
        $('#fcs-modal-info').addClass('active');
    });
    
    // Modal Cotação
    $('.fcs-btn-quote').on('click', function() {
        var planName = $(this).data('plan-name');
        $('#fcs-quote-plan-name').val(planName);
        $('#fcs-modal-quote').addClass('active');
    });
    
    // Fechar modais
    $('.fcs-modal-close, .fcs-modal').on('click', function(e) {
        if (e.target === this || $(this).hasClass('fcs-modal-close')) {
            $('.fcs-modal').removeClass('active');
        }
    });
    
    // Submit cotação
    $('#fcs-quote-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $message = $('#fcs-quote-message');
        var $submitBtn = $form.find('.fcs-btn-submit');
        
        $submitBtn.prop('disabled', true).text('Enviando...');
        $message.removeClass('success error').html('');
        
        $.ajax({
            url: futturuHospedagemCloudFront.ajaxUrl,
            type: 'POST',
            data: $form.serialize() + '&action=futturu_hospedagemcloud_send_quote&nonce=' + futturuHospedagemCloudFront.nonce,
            success: function(response) {
                if (response.success) {
                    $message.addClass('success').text(response.data);
                    $form[0].reset();
                } else {
                    $message.addClass('error').text(response.data || 'Erro ao enviar.');
                }
            },
            error: function() {
                $message.addClass('error').text('Erro ao enviar. Tente novamente.');
            },
            complete: function() {
                $submitBtn.prop('disabled', false).text('Enviar Solicitação');
            }
        });
    });
    
    // FAQ Accordion
    $('.fcs-faq-question').on('click', function() {
        var $item = $(this).parent();
        $item.toggleClass('active');
    });
});
