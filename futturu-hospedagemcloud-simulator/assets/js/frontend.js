/**
 * Frontend JavaScript - Simulador de Hospedagem Futturu Cloud
 */

(function($) {
    'use strict';

    // Estado global
    let currentRecurrence = 'annual'; // Começa no anual como padrão
    let currentCategory = 'padrao';

    // Inicialização
    $(document).ready(function() {
        initSlider();
        initTabs();
        initRecurrenceToggle();
        initModals();
        initFAQ();
        initQuoteForm();
        updateAllPrices(); // Atualiza preços para o modo anual inicial
    });

    /**
     * Inicializa sliders para cada categoria
     */
    function initSlider() {
        $('.fcs-slider-container').each(function() {
            const $container = $(this);
            const $track = $container.find('.fcs-slider-track');
            const $wrapper = $container.find('.fcs-slider-wrapper');
            const $prevBtn = $container.find('.fcs-slider-prev');
            const $nextBtn = $container.find('.fcs-slider-next');
            
            let isDragging = false;
            let startX = 0;
            let scrollLeft = 0;
            let currentScroll = 0;

            // Função para atualizar estado dos botões
            function updateButtonStates() {
                const maxScroll = $track[0].scrollWidth - $wrapper[0].clientWidth;
                
                if (maxScroll <= 0) {
                    $prevBtn.prop('disabled', true);
                    $nextBtn.prop('disabled', true);
                    return;
                }
                
                $prevBtn.prop('disabled', currentScroll <= 0);
                $nextBtn.prop('disabled', currentScroll >= maxScroll - 1);
            }

            // Clique nos botões
            $prevBtn.on('click', function() {
                const scrollAmount = 320; // Largura do card + gap
                currentScroll = Math.max(0, currentScroll - scrollAmount);
                $track.css('transform', `translateX(-${currentScroll}px)`);
                setTimeout(updateButtonStates, 400);
            });

            $nextBtn.on('click', function() {
                const maxScroll = $track[0].scrollWidth - $wrapper[0].clientWidth;
                const scrollAmount = 320;
                currentScroll = Math.min(maxScroll, currentScroll + scrollAmount);
                $track.css('transform', `translateX(-${currentScroll}px)`);
                setTimeout(updateButtonStates, 400);
            });

            // Drag/Touch events
            $wrapper.on('mousedown touchstart', function(e) {
                isDragging = true;
                startX = e.pageX || e.originalEvent.touches[0].pageX;
                scrollLeft = $wrapper.scrollLeft();
                $track.css('transition', 'none');
                $wrapper.css('cursor', 'grabbing');
            });

            $(document).on('mousemove touchmove', function(e) {
                if (!isDragging) return;
                e.preventDefault();
                
                const x = e.pageX || e.originalEvent.touches[0].pageX;
                const walk = (x - startX) * 1.5;
                const newScroll = scrollLeft - walk;
                
                $wrapper.scrollLeft(newScroll);
            });

            $(document).on('mouseup touchend', function() {
                if (!isDragging) return;
                
                isDragging = false;
                $track.css('transition', '');
                $wrapper.css('cursor', 'grab');
                
                // Atualiza currentScroll baseado no scroll real
                currentScroll = $wrapper.scrollLeft();
                updateButtonStates();
            });

            // Previne clique ao arrastar
            let isClick = true;
            $track.on('mousedown', function() {
                isClick = true;
            }).on('mousemove', function() {
                isClick = false;
            }).on('mouseup', function(e) {
                if (!isClick) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });

            // Inicializa estado dos botões
            updateButtonStates();

            // Atualiza no resize
            let resizeTimeout;
            $(window).on('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    currentScroll = 0;
                    $track.css('transform', 'translateX(0)');
                    updateButtonStates();
                }, 200);
            });
        });
    }

    /**
     * Inicializa navegação por abas de categorias
     */
    function initTabs() {
        $('.fcs-cat-btn').on('click', function() {
            const category = $(this).data('category');
            
            // Atualiza botões
            $('.fcs-cat-btn').removeClass('active');
            $(this).addClass('active');
            
            // Atualiza seções
            $('.fcs-plans-section').removeClass('active');
            $(`.fcs-plans-section[data-category="${category}"]`).addClass('active');
            
            // Reseta slider da nova categoria
            setTimeout(function() {
                const $section = $(`.fcs-plans-section[data-category="${category}"]`);
                const $track = $section.find('.fcs-slider-track');
                const $container = $section.find('.fcs-slider-container');
                
                if ($track.length && $container.length) {
                    $track.css('transform', 'translateX(0)');
                    const $prevBtn = $container.find('.fcs-slider-prev');
                    const $nextBtn = $container.find('.fcs-slider-next');
                    $prevBtn.prop('disabled', true);
                    
                    const maxScroll = $track[0].scrollWidth - $container.find('.fcs-slider-wrapper')[0].clientWidth;
                    $nextBtn.prop('disabled', maxScroll <= 0);
                }
            }, 100);
        });
    }

    /**
     * Inicializa toggle de recorrência (Mensal/Anual)
     */
    function initRecurrenceToggle() {
        $('.fcs-toggle-btn').on('click', function() {
            const recurrence = $(this).data('recurrence');
            
            if (recurrence === currentRecurrence) return;
            
            currentRecurrence = recurrence;
            
            // Atualiza botões
            $('.fcs-toggle-btn').removeClass('active');
            $(this).addClass('active');
            
            // Atualiza preços em todos os cards
            updateAllPrices();
        });
    }

    /**
     * Atualiza exibição de preços em todos os cards
     */
    function updateAllPrices() {
        $('.fcs-plan-card').each(function() {
            const $card = $(this);
            const precoMensal = parseFloat($card.data('preco-mensal'));
            const precoAnual = parseFloat($card.data('preco-anual'));
            const economia = parseFloat($card.data('economia'));
            
            const $annualDisplay = $card.find('.fcs-price-display.annual');
            const $monthlyDisplay = $card.find('.fcs-price-display.monthly');
            const $savingsDisplay = $card.find('.fcs-annual-savings');
            
            if (currentRecurrence === 'annual') {
                // Exibe preço anual
                $annualDisplay.css('display', 'block');
                $monthlyDisplay.css('display', 'none');
                
                // Mostra economia
                if (economia > 0) {
                    $savingsDisplay.css('display', 'inline-block');
                    $savingsDisplay.text(futturuHospedagemCloudFrontend.strings.saveAnnual.replace('%s', economia.toFixed(2).replace('.', ',')));
                } else {
                    $savingsDisplay.css('display', 'none');
                }
                
                // Adiciona badge recomendado
                $card.addClass('recommended');
            } else {
                // Exibe preço mensal
                $annualDisplay.css('display', 'none');
                $monthlyDisplay.css('display', 'block');
                $savingsDisplay.css('display', 'none');
                
                // Remove badge recomendado
                $card.removeClass('recommended');
            }
        });
        
        // Atualiza campo oculto do formulário de cotação
        $('#quote-recorrencia').val(currentRecurrence);
    }

    /**
     * Inicializa modais (Mais Info e Cotação)
     */
    function initModals() {
        // Modal Mais Info
        $('.fcs-more-info-btn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const modelo = $(this).data('modelo');
            const planData = getPlanData($(this).closest('.fcs-plan-card'));
            
            // Preenche modal
            $('#modal-plan-name').text(planData.nome);
            
            let resourcesHtml = '<div class="fcs-plan-resources">';
            resourcesHtml += `<div class="fcs-resource"><span class="fcs-resource-label">RAM</span><span class="fcs-resource-value">${planData.ram}</span></div>`;
            resourcesHtml += `<div class="fcs-resource"><span class="fcs-resource-label">CPU</span><span class="fcs-resource-value">${planData.cpu}</span></div>`;
            resourcesHtml += `<div class="fcs-resource"><span class="fcs-resource-label">SSD</span><span class="fcs-resource-value">${planData.disco}</span></div>`;
            resourcesHtml += '</div>';
            
            if (planData.visualizacoes) {
                resourcesHtml += `<p style="text-align:center;margin-top:15px;color:#666;"><strong>Visitas recomendadas:</strong> ${planData.visualizacoes}/mês</p>`;
            }
            
            if (planData.usoIndicado) {
                resourcesHtml += `<p style="text-align:center;margin-top:10px;color:#666;"><strong>Indicado para:</strong> ${planData.usoIndicado}</p>`;
            }
            
            $('.fcs-modal-resources').html(resourcesHtml);
            
            // Abre modal
            openModal('#fcs-info-modal');
        });

        // Modal Cotação
        $('.fcs-quote-btn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const nome = $(this).data('nome');
            const modelo = $(this).data('modelo');
            
            $('#quote-plano').val(nome + ' (' + modelo + ')');
            $('#quote-recorrencia').val(currentRecurrence);
            
            // Abre modal
            openModal('#fcs-quote-modal');
        });

        // Fechar modal
        $('.fcs-modal-close, .fcs-modal-overlay').on('click', function(e) {
            if ($(e.target).hasClass('fcs-modal-overlay') || $(e.target).hasClass('fcs-modal-close')) {
                closeModal();
            }
        });

        // ESC fecha modal
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    }

    /**
     * Abre modal
     */
    function openModal(selector) {
        $('.fcs-modal-overlay').removeClass('active');
        $(selector).addClass('active');
        $('body').css('overflow', 'hidden');
    }

    /**
     * Fecha modal
     */
    function closeModal() {
        $('.fcs-modal-overlay').removeClass('active');
        $('body').css('overflow', '');
        $('.fcs-form-message').removeClass('success error').hide();
    }

    /**
     * Obtém dados do plano
     */
    function getPlanData($card) {
        return {
            nome: $card.data('nome'),
            modelo: $card.data('modelo'),
            ram: $card.data('ram'),
            cpu: $card.data('cpu'),
            disco: $card.data('disco'),
            visualizacoes: $card.data('visualizacoes'),
            usoIndicado: $card.data('uso-indicado')
        };
    }

    /**
     * Inicializa FAQ accordion
     */
    function initFAQ() {
        $('.fcs-faq-question').on('click', function() {
            const $question = $(this);
            const $answer = $question.next('.fcs-faq-answer');
            const isActive = $question.hasClass('active');
            
            // Fecha todos
            $('.fcs-faq-question').removeClass('active');
            $('.fcs-faq-answer').css('max-height', '0');
            
            // Abre o clicado se não estava ativo
            if (!isActive) {
                $question.addClass('active');
                $answer.css('max-height', $answer[0].scrollHeight + 'px');
            }
        });
    }

    /**
     * Inicializa formulário de cotação
     */
    function initQuoteForm() {
        $('#fcs-quote-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $message = $form.find('.fcs-form-message');
            const $submitBtn = $form.find('button[type="submit"]');
            
            // Dados do formulário
            const formData = {
                action: 'futturu_hospedagemcloud_send_quote',
                nonce: futturuHospedagemCloudFrontend.nonce,
                nome: $form.find('#quote-nome').val(),
                email: $form.find('#quote-email').val(),
                telefone: $form.find('#quote-telefone').val(),
                plano_interesse: $form.find('#quote-plano').val(),
                recorrencia: $form.find('#quote-recorrencia').val(),
                mensagem: $form.find('#quote-mensagem').val()
            };
            
            // Desabilita botão
            $submitBtn.prop('disabled', true).text(futturuHospedagemCloudFrontend.strings.loading);
            
            // Envia AJAX
            $.ajax({
                url: futturuHospedagemCloudFrontend.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $message.removeClass('error').addClass('success')
                                .text(response.data.message).show();
                        $form[0].reset();
                        
                        setTimeout(function() {
                            closeModal();
                            $message.hide();
                        }, 3000);
                    } else {
                        $message.removeClass('success').addClass('error')
                                .text(response.data.message || futturuHospedagemCloudFrontend.strings.error).show();
                    }
                },
                error: function() {
                    $message.removeClass('success').addClass('error')
                            .text(futturuHospedagemCloudFrontend.strings.error).show();
                },
                complete: function() {
                    $submitBtn.prop('disabled', false)
                              .text(futturuHospedagemCloudFrontend.strings.success.split('!')[0] + '!');
                }
            });
        });
    }

})(jQuery);
