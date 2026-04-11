/**
 * Futturu Professional Site Simulator - Frontend JavaScript
 * Handles multi-step form navigation, preview generation, and lead submission
 */

(function($) {
    'use strict';

    var FutuuruPSS = {
        currentStep: 1,
        totalSteps: 3,
        formData: {},

        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.initPhoneMasks();
        },

        cacheElements: function() {
            this.$container = $('#futturu-pss-simulator');
            this.$form = $('#futturu-pss-form');
            this.$steps = this.$container.find('.futturu-pss-step-content');
            this.$progressSteps = this.$container.find('.futturu-pss-step');
            this.$nextBtns = this.$container.find('.futturu-pss-btn-next');
            this.$prevBtns = this.$container.find('.futturu-pss-btn-prev');
            this.$toContactBtn = this.$container.find('.futturu-pss-btn-to-contact');
            this.$submitBtn = this.$container.find('.futturu-pss-btn-submit');
            this.$siteTypeCards = this.$container.find('.futturu-pss-type-card');
        },

        bindEvents: function() {
            var self = this;

            // Site type selection
            this.$siteTypeCards.on('click', function() {
                var $radio = $(this).find('input[type="radio"]');
                $radio.prop('checked', true);
                self.$nextBtns.prop('disabled', false);
            });

            // Next button
            this.$nextBtns.on('click', function() {
                if (self.validateCurrentStep()) {
                    self.nextStep();
                }
            });

            // Previous button
            this.$prevBtns.on('click', function() {
                self.prevStep();
            });

            // Go to contact from preview
            this.$toContactBtn.on('click', function() {
                self.goToStep(3);
            });

            // Form submission
            this.$form.on('submit', function(e) {
                e.preventDefault();
                if (self.validateCurrentStep()) {
                    self.submitLead();
                }
            });

            // Real-time preview update on input change
            this.$form.on('input change', '.futturu-pss-field input, .futturu-pss-field select', function() {
                if (self.currentStep === 2 || self.currentStep === 'preview') {
                    self.updatePreview();
                }
            });
        },

        initPhoneMasks: function() {
            // Simple phone mask for Brazilian format
            $('.futturu-pss-phone-mask').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                if (value.length > 11) value = value.substring(0, 11);
                
                if (value.length > 6) {
                    value = '(' + value.substring(0, 2) + ') ' + value.substring(2, 7) + '-' + value.substring(7);
                } else if (value.length > 2) {
                    value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
                } else if (value.length > 0) {
                    value = '(' + value;
                }
                
                $(this).val(value);
            });

            $('.futturu-pss-phone-mask-full').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                if (value.length > 13) value = value.substring(0, 13);
                
                if (value.length > 9) {
                    value = '+' + value.substring(0, 2) + ' ' + value.substring(2, 4) + ' ' + value.substring(4, 9) + '-' + value.substring(9);
                } else if (value.length > 4) {
                    value = '+' + value.substring(0, 2) + ' ' + value.substring(2, 4) + ' ' + value.substring(4);
                } else if (value.length > 2) {
                    value = '+' + value.substring(0, 2) + ' ' + value.substring(2);
                } else if (value.length > 0) {
                    value = '+' + value;
                }
                
                $(this).val(value);
            });
        },

        validateCurrentStep: function() {
            var self = this;
            var $currentStepContent = this.$steps.filter('[data-step="' + this.currentStep + '"]');
            var isValid = true;

            // Remove previous error styles
            $currentStepContent.find('.futturu-pss-field input, .futturu-pss-field select').removeClass('error');

            // Validate required fields
            $currentStepContent.find('[required]').each(function() {
                var $field = $(this);
                var value = $field.val().trim();

                if (!value) {
                    isValid = false;
                    $field.addClass('error');
                    self.showMessage(futturuPSS.strings.required + ': ' + $field.prev('label').text(), 'error');
                }

                // Email validation
                if ($field.attr('type') === 'email' && value) {
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        $field.addClass('error');
                        self.showMessage(futturuPSS.strings.invalidEmail, 'error');
                    }
                }
            });

            return isValid;
        },

        nextStep: function() {
            if (this.currentStep === 2) {
                // Generate preview before showing
                this.updatePreview();
                this.showStep('preview');
            } else if (this.currentStep === 1) {
                this.saveFormData();
                this.showStep(2);
            }
        },

        prevStep: function() {
            if (this.currentStep === 'preview') {
                this.showStep(2);
            } else if (this.currentStep === 3) {
                this.showStep('preview');
            } else if (this.currentStep === 2) {
                this.showStep(1);
            }
        },

        goToStep: function(step) {
            this.saveFormData();
            this.updateSummary();
            this.showStep(step);
        },

        showStep: function(step) {
            var self = this;

            // Hide all steps
            this.$steps.hide();

            // Show target step
            this.$steps.filter('[data-step="' + step + '"]').fadeIn(300);

            // Update progress indicator
            this.$progressSteps.removeClass('active completed');
            
            var stepNumber = step === 'preview' ? 2 : step;
            
            this.$progressSteps.each(function() {
                var $step = $(this);
                var stepData = parseInt($step.data('step'));

                if (stepData < stepNumber) {
                    $step.addClass('completed');
                } else if (stepData === stepNumber) {
                    $step.addClass('active');
                }
            });

            this.currentStep = step;

            // Scroll to top of container
            $('html, body').animate({
                scrollTop: self.$container.offset().top - 20
            }, 300);
        },

        saveFormData: function() {
            var self = this;
            this.formData = {};
            
            this.$form.serializeArray().forEach(function(field) {
                self.formData[field.name] = field.value;
            });
        },

        updatePreview: function() {
            this.saveFormData();

            var data = this.formData;
            var templates = this.getDescriptionTemplates();

            // Update business name everywhere
            this.$container.find('.preview-business-name, .preview-business-name-footer').text(data.business_name || 'Nome do Negócio');

            // Update hero section
            this.$container.find('.preview-hero-title').text(data.business_name || 'Seu Negócio');
            
            var subtitleParts = [];
            if (data.business_service) {
                subtitleParts.push(data.business_service);
            }
            if (data.business_location) {
                subtitleParts.push('em ' + data.business_location);
            }
            this.$container.find('.preview-hero-subtitle').text(subtitleParts.join(' ') || 'Soluções profissionais para você');

            // Update about section with template
            var aboutText = this.generateAboutText(data, templates);
            this.$container.find('.preview-about-text').text(aboutText);

            // Update services
            var services = this.generateServicesList(data.business_category);
            this.$container.find('.preview-service-1').text(services[0]);
            this.$container.find('.preview-service-2').text(services[1]);
            this.$container.find('.preview-service-3').text(services[2]);

            // Update contact info
            this.$container.find('.preview-phone').text(data.business_phone || '(XX) XXXXX-XXXX');
            this.$container.find('.preview-address').text(data.business_address || data.business_location || 'Endereço não informado');
        },

        generateAboutText: function(data, templates) {
            if (!templates || templates.length === 0) {
                return data.business_name + ' é referência em ' + (data.business_category || 'sua categoria') + 
                       ' na região de ' + (data.business_location || 'sua localidade') + '. Oferecemos soluções personalizadas com qualidade e profissionalismo.';
            }

            // Select random template
            var template = templates[Math.floor(Math.random() * templates.length)];

            // Replace placeholders
            var text = template
                .replace(/{nome}/g, data.business_name || '[Nome]')
                .replace(/{categoria}/g, data.business_category || '[Categoria]')
                .replace(/{localidade}/g, data.business_location || '[Localidade]')
                .replace(/{servicos}/g, data.business_service || '[Serviços]');

            return text;
        },

        generateServicesList: function(category) {
            var defaultServices = [
                'Consultoria Especializada',
                'Atendimento Personalizado',
                'Orçamento sem Compromisso'
            ];

            var categoryServices = {
                'Advocacia': ['Consultoria Jurídica', 'Defesa em Processos', 'Elaboração de Contratos'],
                'Medicina': ['Consultas Médicas', 'Exames Clínicos', 'Acompanhamento de Saúde'],
                'Engenharia': ['Projetos de Engenharia', 'Consultoria Técnica', 'Gestão de Obras'],
                'Restaurante': ['Pratos Especiais', 'Delivery Rápido', 'Eventos e Festas'],
                'Delivery': ['Entrega Rápida', 'Cardápio Variado', 'Pedidos Online'],
                'Consultoria': ['Análise de Negócios', 'Planejamento Estratégico', 'Treinamentos'],
                'Oficina': ['Reparos Automotivos', 'Manutenção Preventiva', 'Diagnóstico Computadorizado'],
                'Comércio': ['Produtos de Qualidade', 'Atendimento Especial', 'Entrega em Domicílio'],
                'Educação': ['Cursos Profissionalizantes', 'Aulas Personalizadas', 'Material Didático'],
                'Saúde': ['Tratamentos Especializados', 'Prevenção e Bem-estar', 'Acompanhamento Profissional'],
                'Tecnologia': ['Desenvolvimento de Software', 'Suporte Técnico', 'Consultoria em TI'],
                'Beleza': ['Serviços Estéticos', 'Tratamentos Capilares', 'Maquiagem Profissional'],
                'Fitness': ['Treinamento Personalizado', 'Avaliação Física', 'Planos Nutricionais'],
                'Imobiliário': ['Compra e Venda', 'Avaliação de Imóveis', 'Administração de Aluguéis']
            };

            return categoryServices[category] || defaultServices;
        },

        getDescriptionTemplates: function() {
            // This would ideally come from a localized script variable
            // For now, return default templates
            return [
                '{nome} é referência em {categoria} na região de {localidade}. Oferecemos soluções personalizadas em {servicos} com qualidade e profissionalismo.',
                'Especialistas em {categoria}, a {nome} atende clientes em {localidade} e região com excelência em {servicos}.',
                'A {nome} oferece serviços profissionais de {categoria} em {localidade}. Conheça nossos trabalhos em {servicos}.'
            ];
        },

        updateSummary: function() {
            this.saveFormData();
            var data = this.formData;

            this.$container.find('.summary-site-type').text(this.getSiteTypeName(data.site_type));
            this.$container.find('.summary-business-name').text(data.business_name);
            this.$container.find('.summary-category').text(data.business_category);
            this.$container.find('.summary-location').text(data.business_location || 'Não informada');
        },

        getSiteTypeName: function(slug) {
            var names = {
                'professional': 'Site Profissional',
                'services': 'Site para Serviços',
                'restaurant': 'Restaurante/Delivery',
                'catalog': 'Catálogo/Loja Virtual',
                'other': 'Outro'
            };
            return names[slug] || slug;
        },

        showMessage: function(message, type) {
            var $messageEl = this.$container.find('.futturu-pss-message-' + type);
            $messageEl.text(message).fadeIn(300);

            setTimeout(function() {
                $messageEl.fadeOut(300);
            }, 5000);
        },

        submitLead: function() {
            var self = this;
            var $btn = this.$submitBtn;
            var originalText = $btn.text();

            // Disable button and show loading
            $btn.prop('disabled', true).html('<span class="futturu-pss-spinner"></span> ' + futturuPSS.strings.loading);

            // Collect all form data
            var formData = this.$form.serialize();

            $.ajax({
                url: futturuPSS.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        self.showMessage(response.data.message, 'success');
                        self.$form.trigger('reset');
                        self.showStep(1);
                        self.$progressSteps.removeClass('active completed');
                        self.$progressSteps.first().addClass('active');
                        self.$nextBtns.prop('disabled', true);
                        
                        // Reset form data
                        self.formData = {};
                        self.currentStep = 1;
                    } else {
                        self.showMessage(response.data.message || futturuPSS.strings.error, 'error');
                        $btn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    self.showMessage(futturuPSS.strings.error, 'error');
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        if ($('#futturu-pss-simulator').length) {
            FutuuruPSS.init();
        }
    });

})(jQuery);
