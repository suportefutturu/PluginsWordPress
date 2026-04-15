<?php
/**
 * Frontend functionality for Futturu Promo Plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_Promo_Frontend {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        wp_enqueue_style(
            'futturu-promo-css',
            FUTTURU_PROMO_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            FUTTURU_PROMO_VERSION
        );
        
        wp_enqueue_script(
            'futturu-promo-js',
            FUTTURU_PROMO_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            FUTTURU_PROMO_VERSION,
            true
        );
        
        // Localize script with AJAX URL and settings
        wp_localize_script('futturu-promo-js', 'futturuPromo', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_promo_nonce'),
            'promo_active' => get_option('futturu_promo_active', 'yes'),
            'end_date' => get_option('futturu_promo_end_date', date('Y-m-d H:i:s', strtotime('+24 hours'))),
            'i18n' => array(
                'expired' => __('Promoção Encerrada!', 'futturu-promo-site'),
                'loading' => __('Enviando...', 'futturu-promo-site'),
                'error' => __('Ocorreu um erro. Tente novamente.', 'futturu-promo-site'),
                'success' => __('Dados enviados com sucesso!', 'futturu-promo-site')
            )
        ));
    }
    
    /**
     * Render promotion page (for dedicated URL)
     */
    public function render_promotion_page() {
        // Load theme header
        get_header();
        
        // Render promotion content
        echo '<div class="futturu-promo-wrapper">';
        echo $this->get_promotion_html();
        echo '</div>';
        
        // Load theme footer
        get_footer();
    }
    
    /**
     * Render promotion via shortcode
     */
    public function render_promotion($atts) {
        return '<div class="futturu-promo-wrapper">' . $this->get_promotion_html() . '</div>';
    }
    
    /**
     * Get promotion HTML
     */
    private function get_promotion_html() {
        // Check if promotion is active
        if (get_option('futturu_promo_active', 'yes') !== 'yes') {
            return '<div class="futturu-promo-inactive">' . __('Esta promoção está temporariamente indisponível.', 'futturu-promo-site') . '</div>';
        }
        
        // Get settings
        $normal_price = get_option('futturu_promo_normal_price', '2500');
        $promo_price = get_option('futturu_promo_promo_price', '1500');
        $first_installment = get_option('futturu_promo_first_installment', '500');
        $pix_key = get_option('futturu_promo_pix_key', 'pix@futturu.com.br');
        $whatsapp = get_option('futturu_promo_whatsapp', '5591993100621');
        $email = get_option('futturu_promo_email', 'suporte@futturu.com.br');
        $hero_title = get_option('futturu_promo_hero_title', 'Crie seu Site Institucional Profissional por R$ 1.500!');
        $hero_subtitle = get_option('futturu_promo_hero_subtitle', 'De R$ 2.500 por R$ 1.500. Apenas hoje ou enquanto durarem as vagas.');
        $success_message = get_option('futturu_promo_success_message', 'Parabéns! Recebemos seus dados e o comprovante de pagamento.');
        $qr_code = get_option('futturu_promo_qr_code', '');
        
        // Format prices
        $normal_price_formatted = number_format($normal_price, 2, ',', '.');
        $promo_price_formatted = number_format($promo_price, 2, ',', '.');
        $first_installment_formatted = number_format($first_installment, 2, ',', '.');
        $savings = $normal_price - $promo_price;
        $savings_formatted = number_format($savings, 2, ',', '.');
        
        // Generate PIX QR Code payload
        $pix_payload = $this->generate_pix_payload($pix_key, $first_installment, 'Futturu - Site Institucional');
        
        ob_start();
        ?>
        
        <!-- Hero Section -->
        <section class="futturu-promo-hero">
            <div class="futturu-promo-container">
                <div class="futturu-promo-hero-content">
                    <span class="futturu-promo-badge"><?php _e('OFERTA ESPECIAL', 'futturu-promo-site'); ?></span>
                    <h1 class="futturu-promo-title"><?php echo esc_html($hero_title); ?></h1>
                    <p class="futturu-promo-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                    
                    <div class="futturu-promo-pricing">
                        <div class="futturu-promo-old-price">
                            <span class="futturu-promo-de"><?php _e('De', 'futturu-promo-site'); ?></span>
                            <span class="futturu-promo-value">R$ <?php echo $normal_price_formatted; ?></span>
                        </div>
                        <div class="futturu-promo-new-price">
                            <span class="futturu-promo-por"><?php _e('Por apenas', 'futturu-promo-site'); ?></span>
                            <span class="futturu-promo-value-highlight">R$ <?php echo $promo_price_formatted; ?></span>
                            <span class="futturu-promo-save"><?php _e('Economize R$', 'futturu-promo-site'); ?> <?php echo $savings_formatted; ?></span>
                        </div>
                    </div>
                    
                    <div class="futturu-promo-timer">
                        <span class="futturu-promo-timer-label"><?php _e('Oferta termina em:', 'futturu-promo-site'); ?></span>
                        <div class="futturu-promo-countdown" id="futturu-promo-countdown">
                            <div class="futturu-promo-countdown-item">
                                <span class="futturu-promo-countdown-number" id="countdown-days">00</span>
                                <span class="futturu-promo-countdown-text"><?php _e('Dias', 'futturu-promo-site'); ?></span>
                            </div>
                            <div class="futturu-promo-countdown-separator">:</div>
                            <div class="futturu-promo-countdown-item">
                                <span class="futturu-promo-countdown-number" id="countdown-hours">00</span>
                                <span class="futturu-promo-countdown-text"><?php _e('Horas', 'futturu-promo-site'); ?></span>
                            </div>
                            <div class="futturu-promo-countdown-separator">:</div>
                            <div class="futturu-promo-countdown-item">
                                <span class="futturu-promo-countdown-number" id="countdown-minutes">00</span>
                                <span class="futturu-promo-countdown-text"><?php _e('Min', 'futturu-promo-site'); ?></span>
                            </div>
                            <div class="futturu-promo-countdown-separator">:</div>
                            <div class="futturu-promo-countdown-item">
                                <span class="futturu-promo-countdown-number" id="countdown-seconds">00</span>
                                <span class="futturu-promo-countdown-text"><?php _e('Seg', 'futturu-promo-site'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="#futturu-promo-payment" class="futturu-promo-btn futturu-promo-btn-primary">
                        <?php _e('Garanta sua Vaga por R$', 'futturu-promo-site'); ?> <?php echo $promo_price_formatted; ?> <?php _e('Agora!', 'futturu-promo-site'); ?>
                    </a>
                    
                    <div class="futturu-promo-social-proof">
                        <div class="futturu-promo-proof-item">
                            <span class="dashicons dashicons-awards"></span>
                            <span><?php _e('Prêmio UI Design BR 2020', 'futturu-promo-site'); ?></span>
                        </div>
                        <div class="futturu-promo-proof-item">
                            <span class="dashicons dashicons-smiley"></span>
                            <span><?php _e('Clientes aumentam +40% no 1º ano', 'futturu-promo-site'); ?></span>
                        </div>
                        <div class="futturu-promo-proof-item">
                            <span class="dashicons dashicons-verified-alt"></span>
                            <span><?php _e('Baseado em valores Sinapro', 'futturu-promo-site'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Benefits Section -->
        <section class="futturu-promo-benefits">
            <div class="futturu-promo-container">
                <h2 class="futturu-promo-section-title"><?php _e('Por que esta promoção é imperdível?', 'futturu-promo-site'); ?></h2>
                
                <div class="futturu-promo-benefits-grid">
                    <div class="futturu-promo-benefit-item">
                        <div class="futturu-promo-benefit-icon">
                            <span class="dashicons dashicons-admin-multisite"></span>
                        </div>
                        <h3><?php _e('Site Institucional Profissional', 'futturu-promo-site'); ?></h3>
                        <p><?php _e('Até 5 páginas personalizadas para sua empresa', 'futturu-promo-site'); ?></p>
                    </div>
                    
                    <div class="futturu-promo-benefit-item">
                        <div class="futturu-promo-benefit-icon">
                            <span class="dashicons dashicons-smartphone"></span>
                        </div>
                        <h3><?php _e('Layout Responsivo', 'futturu-promo-site'); ?></h3>
                        <p><?php _e('Funciona perfeitamente em celular, tablet e desktop', 'futturu-promo-site'); ?></p>
                    </div>
                    
                    <div class="futturu-promo-benefit-item">
                        <div class="futturu-promo-benefit-icon">
                            <span class="dashicons dashicons-admin-settings"></span>
                        </div>
                        <h3><?php _e('CMS WordPress', 'futturu-promo-site'); ?></h3>
                        <p><?php _e('Sistema de Gestão de Conteúdo fácil de usar', 'futturu-promo-site'); ?></p>
                    </div>
                    
                    <div class="futturu-promo-benefit-item">
                        <div class="futturu-promo-benefit-icon">
                            <span class="dashicons dashicons-email"></span>
                        </div>
                        <h3><?php _e('Formulário de Contato', 'futturu-promo-site'); ?></h3>
                        <p><?php _e('Integrado para receber mensagens dos clientes', 'futturu-promo-site'); ?></p>
                    </div>
                    
                    <div class="futturu-promo-benefit-item">
                        <div class="futturu-promo-benefit-icon">
                            <span class="dashicons dashicons-chart-line"></span>
                        </div>
                        <h3><?php _e('SEO Básico', 'futturu-promo-site'); ?></h3>
                        <p><?php _e('Otimização para Motores de Busca', 'futturu-promo-site'); ?></p>
                    </div>
                    
                    <div class="futturu-promo-benefit-item">
                        <div class="futturu-promo-benefit-icon">
                            <span class="dashicons dashicons-groups"></span>
                        </div>
                        <h3><?php _e('Equipe Especializada', 'futturu-promo-site'); ?></h3>
                        <p><?php _e('Trabalho realizado pela equipe Futturu', 'futturu-promo-site'); ?></p>
                    </div>
                </div>
                
                <div class="futturu-promo-value-comparison">
                    <div class="futturu-promo-comparison-item">
                        <span class="futturu-promo-comparison-label"><?php _e('Valor de Mercado', 'futturu-promo-site'); ?></span>
                        <span class="futturu-promo-comparison-value old">R$ <?php echo $normal_price_formatted; ?></span>
                    </div>
                    <div class="futturu-promo-comparison-divider">→</div>
                    <div class="futturu-promo-comparison-item">
                        <span class="futturu-promo-comparison-label"><?php _e('Valor Promocional', 'futturu-promo-site'); ?></span>
                        <span class="futturu-promo-comparison-value new">R$ <?php echo $promo_price_formatted; ?></span>
                    </div>
                    <div class="futturu-promo-comparison-savings">
                        <?php _e('Você economiza R$', 'futturu-promo-site'); ?> <strong><?php echo $savings_formatted; ?></strong>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Payment Section -->
        <section class="futturu-promo-payment" id="futturu-promo-payment">
            <div class="futturu-promo-container">
                <h2 class="futturu-promo-section-title"><?php _e('Como garantir sua vaga no valor promocional?', 'futturu-promo-site'); ?></h2>
                
                <div class="futturu-promo-steps">
                    <div class="futturu-promo-step">
                        <div class="futturu-promo-step-number">1</div>
                        <p><?php _e('Pague a primeira parcela de', 'futturu-promo-site'); ?> <strong>R$ <?php echo $first_installment_formatted; ?></strong> <?php _e('via PIX ou parcelado', 'futturu-promo-site'); ?></p>
                    </div>
                    <div class="futturu-promo-step">
                        <div class="futturu-promo-step-number">2</div>
                        <p><?php _e('Preencha o formulário com seus dados', 'futturu-promo-site'); ?></p>
                    </div>
                    <div class="futturu-promo-step">
                        <div class="futturu-promo-step-number">3</div>
                        <p><?php _e('Envie o comprovante de pagamento', 'futturu-promo-site'); ?></p>
                    </div>
                    <div class="futturu-promo-step">
                        <div class="futturu-promo-step-number">4</div>
                        <p><?php _e('Sua vaga e o valor promocional são garantidos!', 'futturu-promo-site'); ?></p>
                    </div>
                </div>
                
                <div class="futturu-promo-payment-options">
                    <!-- PIX Option -->
                    <div class="futturu-promo-payment-option futturu-promo-pix">
                        <h3><span class="dashicons dashicons-money-alt"></span> <?php _e('Pagamento via PIX', 'futturu-promo-site'); ?></h3>
                        <p class="futturu-promo-pix-description"><?php _e('Pague a primeira parcela de', 'futturu-promo-site'); ?> <strong>R$ <?php echo $first_installment_formatted; ?></strong></p>
                        
                        <div class="futturu-promo-pix-content">
                            <?php if ($qr_code): ?>
                                <div class="futturu-promo-qr-code">
                                    <img src="<?php echo esc_url($qr_code); ?>" alt="QR Code PIX" />
                                </div>
                            <?php else: ?>
                                <div class="futturu-promo-qr-code">
                                    <div id="futturu-promo-qrcode"></div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="futturu-promo-pix-key">
                                <label><?php _e('Chave PIX:', 'futturu-promo-site'); ?></label>
                                <div class="futturu-promo-pix-key-value">
                                    <input type="text" value="<?php echo esc_attr($pix_key); ?>" readonly id="pix-key-input" />
                                    <button type="button" class="futturu-promo-copy-btn" onclick="futturuCopyPixKey()">
                                        <span class="dashicons dashicons-clipboard"></span>
                                        <?php _e('Copiar', 'futturu-promo-site'); ?>
                                    </button>
                                </div>
                                <span class="futturu-promo-copied-msg" id="pix-copied-msg"><?php _e('Copiado!', 'futturu-promo-site'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Installment Option -->
                    <div class="futturu-promo-payment-option futturu-promo-installment">
                        <h3><span class="dashicons dashicons-credit-card"></span> <?php _e('Parcelamento', 'futturu-promo-site'); ?></h3>
                        <p><?php _e('Ou pague em até 3x de', 'futturu-promo-site'); ?> <strong>R$ <?php echo $first_installment_formatted; ?></strong></p>
                        <p class="futturu-promo-installment-total">(total R$ <?php echo $promo_price_formatted; ?>)</p>
                        <p class="futturu-promo-installment-note"><?php _e('Entre em contato para gerar link de pagamento', 'futturu-promo-site'); ?></p>
                    </div>
                </div>
                
                <!-- Confirmation Form -->
                <div class="futturu-promo-form-section">
                    <h3><?php _e('Confirme seu Pagamento', 'futturu-promo-site'); ?></h3>
                    
                    <form id="futturu-promo-form" class="futturu-promo-form">
                        <div class="futturu-promo-form-row">
                            <div class="futturu-promo-form-group">
                                <label for="futturu_name"><?php _e('Nome Completo*', 'futturu-promo-site'); ?></label>
                                <input type="text" id="futturu_name" name="futturu_name" required />
                            </div>
                            <div class="futturu-promo-form-group">
                                <label for="futturu_email"><?php _e('E-mail*', 'futturu-promo-site'); ?></label>
                                <input type="email" id="futturu_email" name="futturu_email" required />
                            </div>
                        </div>
                        
                        <div class="futturu-promo-form-row">
                            <div class="futturu-promo-form-group">
                                <label for="futturu_phone"><?php _e('Telefone*', 'futturu-promo-site'); ?></label>
                                <input type="tel" id="futturu_phone" name="futturu_phone" required placeholder="(XX) XXXXX-XXXX" />
                            </div>
                            <div class="futturu-promo-form-group">
                                <label for="futturu_company"><?php _e('Nome da Empresa', 'futturu-promo-site'); ?></label>
                                <input type="text" id="futturu_company" name="futturu_company" />
                            </div>
                        </div>
                        
                        <div class="futturu-promo-form-group">
                            <label for="futturu_message"><?php _e('Mensagem (Opcional)', 'futturu-promo-site'); ?></label>
                            <textarea id="futturu_message" name="futturu_message" rows="3" placeholder="<?php _e('Ex: Desejo o pacote básico de 5 páginas', 'futturu-promo-site'); ?>"></textarea>
                        </div>
                        
                        <div class="futturu-promo-form-group">
                            <label><?php _e('Forma de Pagamento Escolhida*', 'futturu-promo-site'); ?></label>
                            <div class="futturu-promo-radio-group">
                                <label class="futturu-promo-radio">
                                    <input type="radio" name="futturu_payment_method" value="PIX" checked />
                                    <span><?php _e('PIX', 'futturu-promo-site'); ?></span>
                                </label>
                                <label class="futturu-promo-radio">
                                    <input type="radio" name="futturu_payment_method" value="Cartão" />
                                    <span><?php _e('Cartão - 3x', 'futturu-promo-site'); ?></span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="futturu-promo-form-group">
                            <label for="futturu_amount"><?php _e('Valor Pago (R$)*', 'futturu-promo-site'); ?></label>
                            <input type="number" id="futturu_amount" name="futturu_amount" step="0.01" value="<?php echo $first_installment; ?>" required />
                        </div>
                        
                        <div class="futturu-promo-form-group">
                            <label for="futturu_receipt"><?php _e('Comprovante de Pagamento', 'futturu-promo-site'); ?></label>
                            <input type="file" id="futturu_receipt" name="futturu_receipt" accept="image/*" />
                            <small><?php _e('Formatos aceitos: JPG, PNG. Máximo 5MB.', 'futturu-promo-site'); ?></small>
                        </div>
                        
                        <div class="futturu-promo-form-actions">
                            <button type="submit" class="futturu-promo-btn futturu-promo-btn-submit">
                                <?php _e('Enviar Dados e Confirmar Pagamento', 'futturu-promo-site'); ?>
                            </button>
                        </div>
                        
                        <div class="futturu-promo-alternative-cta">
                            <p><?php _e('Já paguei via PIX.', 'futturu-promo-site'); ?> 
                                <a href="#" class="futturu-promo-link" onclick="document.getElementById('futturu_receipt').scrollIntoView({behavior: 'smooth'});">
                                    <?php _e('Enviar Comprovante e Dados', 'futturu-promo-site'); ?>
                                </a>
                            </p>
                        </div>
                    </form>
                    
                    <!-- WhatsApp CTA -->
                    <div class="futturu-promo-whatsapp-cta">
                        <p><?php _e('Ou envie seu comprovante de pagamento via WhatsApp:', 'futturu-promo-site'); ?></p>
                        <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>?text=<?php echo urlencode('Olá, segue comprovante do pagamento da promoção do site institucional.'); ?>" 
                           class="futturu-promo-btn futturu-promo-btn-whatsapp" 
                           target="_blank" 
                           rel="noopener noreferrer">
                            <span class="dashicons dashicons-whatsapp"></span>
                            <?php _e('Enviar Comprovante via WhatsApp', 'futturu-promo-site'); ?>
                        </a>
                        <p class="futturu-promo-whatsapp-note"><?php _e('Lembre-se de mencionar que é referente à promoção do site institucional.', 'futturu-promo-site'); ?></p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Success Message (Hidden by default) -->
        <div id="futturu-promo-success" class="futturu-promo-success" style="display: none;">
            <div class="futturu-promo-container">
                <div class="futturu-promo-success-content">
                    <span class="dashicons dashicons-yes-alt"></span>
                    <h2><?php _e('Parabéns!', 'futturu-promo-site'); ?></h2>
                    <p><?php echo esc_html($success_message); ?></p>
                    <p><?php _e('Dúvidas? Envie um e-mail para', 'futturu-promo-site'); ?> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
                </div>
            </div>
        </div>
        
        <!-- Hidden data for JS -->
        <div id="futturu-promo-data" 
             data-pix-payload="<?php echo esc_attr($pix_payload); ?>"
             data-pix-key="<?php echo esc_attr($pix_key); ?>"
             data-amount="<?php echo esc_attr($first_installment); ?>"
             style="display: none;"></div>
        
        <?php
        return ob_get_clean();
    }
    
    /**
     * Generate PIX Payload (EMVCo standard)
     */
    private function generate_pix_payload($key, $amount, $description = '') {
        // PIX Payload Format Indicator
        $payloadFormatIndicator = '010212';
        
        // Merchant Account Information (ANVISA)
        $merchantAccountField = '0014br.gov.bcb.pix' . '01' . str_pad(strlen($key), 2, '0', STR_PAD_LEFT) . $key;
        $merchantAccountInfo = '26' . str_pad(strlen($merchantAccountField), 2, '0', STR_PAD_LEFT) . $merchantAccountField;
        
        // Merchant Category Code
        $merchantCategoryCode = '52040000';
        
        // Transaction Currency (BRL)
        $transactionCurrency = '5303986';
        
        // Transaction Amount
        $amountFormatted = number_format($amount, 2, '.', '');
        $transactionAmount = '54' . str_pad(strlen($amountFormatted), 2, '0', STR_PAD_LEFT) . $amountFormatted;
        
        // Country Code
        $countryCode = '5802BR';
        
        // Additional Data Field Template
        $txid = 'TXID' . (strlen($description) > 0 ? '*' : '') . substr(md5(uniqid()), 0, 15);
        $additionalDataField = '62' . str_pad(strlen($txid), 2, '0', STR_PAD_LEFT) . $txid;
        
        // CRC16
        $payloadForCrc = $payloadFormatIndicator . $merchantAccountInfo . $merchantCategoryCode . $transactionCurrency . $transactionAmount . $countryCode . $additionalDataField . '6304';
        $crc = $this->calculate_crc16($payloadForCrc);
        
        return $payloadForCrc . strtoupper($crc);
    }
    
    /**
     * Calculate CRC16 for PIX
     */
    private function calculate_crc16($payload) {
        $polynomial = 0x1021;
        $register = 0xFFFF;
        
        for ($i = 0; $i < strlen($payload); $i++) {
            $register ^= ord($payload[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                $register = ($register & 0x8000) ? (($register << 1) ^ $polynomial) : ($register << 1);
            }
        }
        
        return strtoupper(substr(dechex($register & 0xFFFF), -4));
    }
}
