<?php
/**
 * Futturu Cloud Frontend Class
 * Handles frontend rendering of the simulator
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_HospedagemCloud_Frontend {
    
    public function __construct() {
        add_shortcode('futturu_hospedagemcloud_annual_monthly_sim', array($this, 'render_simulator'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_futturu_hospedagemcloud_send_quote', array($this, 'handle_quote_request'));
        add_action('wp_ajax_nopriv_futturu_hospedagemcloud_send_quote', array($this, 'handle_quote_request'));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        wp_enqueue_style('futturu-hospedagemcloud-sim-css', FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/css/frontend.css', array(), FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION);
        wp_enqueue_script('futturu-hospedagemcloud-sim-js', FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION, true);
        
        wp_localize_script('futturu-hospedagemcloud-sim-js', 'futturuCloudSim', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_hospedagemcloud_sim_nonce'),
            'discount' => get_option('futturu_hospedagemcloud_discount', 10),
            'i18n' => array(
                'monthly' => __('Mensal', 'futturu-hospedagemcloud-sim'),
                'annual' => __('Anual', 'futturu-hospedagemcloud-sim'),
                'save' => __('Economize', 'futturu-hospedagemcloud-sim'),
                'perYear' => __('/ano', 'futturu-hospedagemcloud-sim'),
                'perMonth' => __('/mês', 'futturu-hospedagemcloud-sim'),
                'discountApplied' => __('Desconto de', 'futturu-hospedagemcloud-sim'),
                'applied' => __('aplicado!', 'futturu-hospedagemcloud-sim'),
                'moreInfo' => __('Mais Info', 'futturu-hospedagemcloud-sim'),
                'quoteRequest' => __('Solicitar Cotação para', 'futturu-hospedagemcloud-sim'),
                'sending' => __('Enviando...', 'futturu-hospedagemcloud-sim'),
                'sent' => __('Enviado com sucesso!', 'futturu-hospedagemcloud-sim'),
                'error' => __('Erro ao enviar. Tente novamente.', 'futturu-hospedagemcloud-sim'),
            )
        ));
    }
    
    /**
     * Render simulator shortcode
     */
    public function render_simulator($atts) {
        $plans = get_option('futturu_hospedagemcloud_plans', Futturu_HospedagemCloud_Data::get_default_plans());
        $faqs = get_option('futturu_hospedagemcloud_faqs', Futturu_HospedagemCloud_Data::get_default_faqs());
        $features = get_option('futturu_hospedagemcloud_features', Futturu_HospedagemCloud_Data::get_default_features());
        $discount = get_option('futturu_hospedagemcloud_discount', 10);
        $cta_email = get_option('futturu_hospedagemcloud_cta_email', 'suporte@futturu.com.br');
        $intro_text = get_option('futturu_hospedagemcloud_intro_text', __('Descubra o plano de hospedagem em nuvem ideal para o seu projeto. Escolha o período de pagamento: Economize 10% contratando anualmente ou pague mensalmente com flexibilidade. Nossa parceria com a Cloudez oferece servidores de alto desempenho na nuvem com gerenciamento completo e suporte técnico especializado.', 'futturu-hospedagemcloud-sim'));
        $cta_text = get_option('futturu_hospedagemcloud_cta_text', __('Solicitar Cotação', 'futturu-hospedagemcloud-sim'));
        
        $categories = Futturu_HospedagemCloud_Data::get_categories($plans);
        
        ob_start();
        ?>
        <div class="futturu-hospedagemcloud-simulator" data-discount="<?php echo esc_attr($discount); ?>">
            
            <!-- Introduction Section -->
            <div class="fcs-intro">
                <h2><?php esc_html_e('Simulador de Hospedagem na Nuvem Futturu', 'futturu-hospedagemcloud-sim'); ?></h2>
                <p class="fcs-intro-text"><?php echo esc_html($intro_text); ?></p>
                
                <div class="fcs-features-highlight">
                    <div class="fcs-feature-badge">
                        <span class="dashicons dashicons-cloud"></span>
                        <?php esc_html_e('Hospedagem Gerenciada', 'futturu-hospedagemcloud-sim'); ?>
                    </div>
                    <div class="fcs-feature-badge">
                        <span class="dashicons dashicons-speedometer"></span>
                        <?php esc_html_e('CDN e Otimizações Automáticas', 'futturu-hospedagemcloud-sim'); ?>
                    </div>
                    <div class="fcs-feature-badge">
                        <span class="dashicons dashicons-backup"></span>
                        <?php esc_html_e('Backups Automáticos', 'futturu-hospedagemcloud-sim'); ?>
                    </div>
                    <div class="fcs-feature-badge">
                        <span class="dashicons dashicons-visibility"></span>
                        <?php esc_html_e('Monitoramento 24/7', 'futturu-hospedagemcloud-sim'); ?>
                    </div>
                    <div class="fcs-feature-badge">
                        <span class="dashicons dashicons-lock"></span>
                        <?php esc_html_e('Certificado SSL Grátis', 'futturu-hospedagemcloud-sim'); ?>
                    </div>
                    <div class="fcs-feature-badge">
                        <span class="dashicons dashicons-support"></span>
                        <?php esc_html_e('Suporte Técnico Especializado', 'futturu-hospedagemcloud-sim'); ?>
                    </div>
                </div>
            </div>
            
            <!-- Recurrence Selector -->
            <div class="fcs-recurrence-selector">
                <div class="fcs-toggle-container">
                    <label class="fcs-radio-label">
                        <input type="radio" name="fcs-recurrence" value="monthly" checked />
                        <span class="fcs-radio-text"><?php esc_html_e('Mensal', 'futturu-hospedagemcloud-sim'); ?></span>
                    </label>
                    
                    <label class="fcs-radio-label fcs-radio-annual">
                        <input type="radio" name="fcs-recurrence" value="annual" />
                        <span class="fcs-radio-text">
                            <?php esc_html_e('Anual', 'futturu-hospedagemcloud-sim'); ?>
                            <span class="fcs-discount-badge"><?php echo esc_html($discount); ?>% OFF</span>
                        </span>
                    </label>
                </div>
                <p class="fcs-annual-highlight">
                    <span class="dashicons dashicons-star-filled"></span>
                    <?php printf(esc_html__('Economize %d%% contratando anualmente!', 'futturu-hospedagemcloud-sim'), $discount); ?>
                </p>
            </div>
            
            <!-- Category Tabs -->
            <div class="fcs-category-tabs">
                <?php foreach ($categories as $index => $category) : ?>
                    <button class="fcs-tab-button <?php echo $index === 0 ? 'active' : ''; ?>" data-category="<?php echo esc_attr($category); ?>">
                        <?php echo esc_html($category); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <!-- Plans Slider -->
            <div class="fcs-plans-container">
                <?php foreach ($categories as $index => $category) : 
                    $category_plans = Futturu_HospedagemCloud_Data::get_plans_by_category($category, $plans);
                ?>
                    <div class="fcs-category-content <?php echo $index === 0 ? 'active' : ''; ?>" data-category="<?php echo esc_attr($category); ?>">
                        <h3 class="fcs-category-title"><?php echo esc_html($category); ?></h3>
                        
                        <div class="fcs-slider">
                            <button class="fcs-slider-nav fcs-slider-prev" aria-label="<?php esc_attr_e('Anterior', 'futturu-hospedagemcloud-sim'); ?>">
                                <span class="dashicons dashicons-arrow-left-alt2"></span>
                            </button>
                            
                            <div class="fcs-slider-track">
                                <div class="fcs-slider-wrapper">
                                    <?php foreach ($category_plans as $plan) : 
                                        $monthly_price = (float) $plan['preco_mensal'];
                                        $annual_price = $monthly_price * 12 * (1 - $discount / 100);
                                        $annual_savings = ($monthly_price * 12) - $annual_price;
                                    ?>
                                        <div class="fcs-plan-card" data-plan-model="<?php echo esc_attr($plan['modelo']); ?>">
                                            <div class="fcs-plan-header">
                                                <h4 class="fcs-plan-model"><?php echo esc_html($plan['modelo']); ?></h4>
                                                <?php if ($plan['uso_indicado']) : ?>
                                                    <span class="fcs-plan-usage"><?php echo esc_html($plan['uso_indicado']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="fcs-plan-resources">
                                                <?php if ($category === '📧 Clouds para E-mails') : ?>
                                                    <div class="fcs-resource">
                                                        <span class="fcs-resource-label"><?php esc_html_e('Disco:', 'futturu-hospedagemcloud-sim'); ?></span>
                                                        <span class="fcs-resource-value"><?php echo esc_html($plan['disco']); ?></span>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="fcs-resource">
                                                        <span class="fcs-resource-label"><?php esc_html_e('RAM:', 'futturu-hospedagemcloud-sim'); ?></span>
                                                        <span class="fcs-resource-value"><?php echo esc_html($plan['ram']); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="fcs-resource">
                                                    <span class="fcs-resource-label"><?php esc_html_e('CPU:', 'futturu-hospedagemcloud-sim'); ?></span>
                                                    <span class="fcs-resource-value"><?php echo esc_html($plan['cpu']); ?></span>
                                                </div>
                                                
                                                <div class="fcs-resource">
                                                    <span class="fcs-resource-label"><?php esc_html_e('SSD:', 'futturu-hospedagemcloud-sim'); ?></span>
                                                    <span class="fcs-resource-value"><?php echo esc_html($plan['disco']); ?></span>
                                                </div>
                                                
                                                <?php if ($plan['visualizacoes'] && $category !== '📧 Clouds para E-mails') : ?>
                                                    <div class="fcs-resource fcs-views">
                                                        <span class="fcs-resource-label"><?php esc_html_e('Visualizações/mês:', 'futturu-hospedagemcloud-sim'); ?></span>
                                                        <span class="fcs-resource-value"><?php echo esc_html($plan['visualizacoes']); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="fcs-plan-pricing">
                                                <div class="fcs-price-monthly">
                                                    <span class="fcs-price-label"><?php esc_html_e('Preço Mensal:', 'futturu-hospedagemcloud-sim'); ?></span>
                                                    <span class="fcs-price-value">R$ <?php echo number_format($monthly_price, 2, ',', '.'); ?></span>
                                                </div>
                                                
                                                <div class="fcs-price-annual" style="display: none;">
                                                    <span class="fcs-price-label"><?php esc_html_e('Preço Anual:', 'futturu-hospedagemcloud-sim'); ?></span>
                                                    <span class="fcs-price-value fcs-price-annual-value">R$ <?php echo number_format($annual_price / 12, 2, ',', '.'); ?>/mês</span>
                                                    <span class="fcs-price-total">R$ <?php echo number_format($annual_price, 2, ',', '.'); ?><?php esc_html_e('/ano', 'futturu-hospedagemcloud-sim'); ?></span>
                                                    <span class="fcs-savings-badge">
                                                        <?php printf(esc_html__('Economize R$ %s anualmente!', 'futturu-hospedagemcloud-sim'), number_format($annual_savings, 2, ',', '.')); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="fcs-plan-actions">
                                                <button class="fcs-btn-more-info" data-plan='<?php echo esc_attr(json_encode($plan)); ?>' data-features='<?php echo esc_attr(json_encode($features)); ?>'>
                                                    <span class="dashicons dashicons-info"></span>
                                                    <?php esc_html_e('Mais Info', 'futturu-hospedagemcloud-sim'); ?>
                                                </button>
                                                
                                                <button class="fcs-btn-quote" data-plan-model="<?php echo esc_attr($plan['modelo']); ?>">
                                                    <?php echo esc_html($cta_text); ?>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <button class="fcs-slider-nav fcs-slider-next" aria-label="<?php esc_attr_e('Próximo', 'futturu-hospedagemcloud-sim'); ?>">
                                <span class="dashicons dashicons-arrow-right-alt2"></span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Global CTA -->
            <div class="fcs-global-cta">
                <h3><?php esc_html_e('Pronto para escolher seu plano?', 'futturu-hospedagemcloud-sim'); ?></h3>
                <p><?php esc_html_e('Fale com um especialista da Futturu e contrate o plano ideal para o seu projeto.', 'futturu-hospedagemcloud-sim'); ?></p>
                <button class="fcs-btn-primary fcs-btn-global-quote"><?php echo esc_html($cta_text); ?></button>
            </div>
            
            <!-- FAQ Section -->
            <div class="fcs-faq-section">
                <h3><?php esc_html_e('Dúvidas Frequentes', 'futturu-hospedagemcloud-sim'); ?></h3>
                
                <div class="fcs-faq-accordion">
                    <?php foreach ($faqs as $faq) : ?>
                        <div class="fcs-faq-item">
                            <button class="fcs-faq-question">
                                <span><?php echo esc_html($faq['pergunta']); ?></span>
                                <span class="dashicons dashicons-plus-alt"></span>
                            </button>
                            <div class="fcs-faq-answer">
                                <p><?php echo esc_html($faq['resposta']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Quote Modal -->
            <div class="fcs-modal-overlay" id="fcs-quote-modal" style="display: none;">
                <div class="fcs-modal">
                    <button class="fcs-modal-close" aria-label="<?php esc_attr_e('Fechar', 'futturu-hospedagemcloud-sim'); ?>">
                        <span class="dashicons dashicons-no"></span>
                    </button>
                    
                    <h3 class="fcs-modal-title"><?php esc_html_e('Solicitar Cotação', 'futturu-hospedagemcloud-sim'); ?></h3>
                    
                    <form id="fcs-quote-form" class="fcs-quote-form">
                        <div class="fcs-form-group">
                            <label for="fcs-name"><?php esc_html_e('Nome Completo *', 'futturu-hospedagemcloud-sim'); ?></label>
                            <input type="text" id="fcs-name" name="name" required />
                        </div>
                        
                        <div class="fcs-form-group">
                            <label for="fcs-email"><?php esc_html_e('E-mail *', 'futturu-hospedagemcloud-sim'); ?></label>
                            <input type="email" id="fcs-email" name="email" required />
                        </div>
                        
                        <div class="fcs-form-group">
                            <label for="fcs-phone"><?php esc_html_e('Telefone/WhatsApp', 'futturu-hospedagemcloud-sim'); ?></label>
                            <input type="tel" id="fcs-phone" name="phone" />
                        </div>
                        
                        <div class="fcs-form-group">
                            <label for="fcs-plan-interest"><?php esc_html_e('Plano de Interesse *', 'futturu-hospedagemcloud-sim'); ?></label>
                            <select id="fcs-plan-interest" name="plan_interest" required>
                                <option value=""><?php esc_html_e('Selecione um plano...', 'futturu-hospedagemcloud-sim'); ?></option>
                                <?php foreach ($plans as $plan) : ?>
                                    <option value="<?php echo esc_attr($plan['modelo']); ?>"><?php echo esc_html($plan['modelo']); ?> - <?php echo esc_html($plan['categoria']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="fcs-form-group">
                            <label for="fcs-recurrence"><?php esc_html_e('Recorrência *', 'futturu-hospedagemcloud-sim'); ?></label>
                            <select id="fcs-recurrence" name="recurrence" required>
                                <option value="monthly"><?php esc_html_e('Mensal', 'futturu-hospedagemcloud-sim'); ?></option>
                                <option value="annual"><?php printf(esc_html__('Anual (%d%% OFF)', 'futturu-hospedagemcloud-sim'), $discount); ?></option>
                            </select>
                        </div>
                        
                        <div class="fcs-form-group">
                            <label for="fcs-message"><?php esc_html_e('Mensagem (Opcional)', 'futturu-hospedagemcloud-sim'); ?></label>
                            <textarea id="fcs-message" name="message" rows="4"></textarea>
                        </div>
                        
                        <div class="fcs-form-submit">
                            <button type="submit" class="fcs-btn-primary fcs-btn-submit">
                                <span class="fcs-submit-text"><?php esc_html_e('Enviar Solicitação', 'futturu-hospedagemcloud-sim'); ?></span>
                                <span class="fcs-submit-loading" style="display: none;"><?php esc_html_e('Enviando...', 'futturu-hospedagemcloud-sim'); ?></span>
                            </button>
                        </div>
                        
                        <div class="fcs-form-message" style="display: none;"></div>
                    </form>
                </div>
            </div>
            
            <!-- Plan Details Modal -->
            <div class="fcs-modal-overlay" id="fcs-details-modal" style="display: none;">
                <div class="fcs-modal fcs-modal-large">
                    <button class="fcs-modal-close" aria-label="<?php esc_attr_e('Fechar', 'futturu-hospedagemcloud-sim'); ?>">
                        <span class="dashicons dashicons-no"></span>
                    </button>
                    
                    <h3 class="fcs-modal-title" id="fcs-details-title"></h3>
                    
                    <div class="fcs-details-content">
                        <div class="fcs-details-section">
                            <h4><?php esc_html_e('Recursos do Plano', 'futturu-hospedagemcloud-sim'); ?></h4>
                            <ul class="fcs-details-list" id="fcs-details-resources"></ul>
                        </div>
                        
                        <div class="fcs-details-section">
                            <h4><?php esc_html_e('Funcionalidades e Benefícios', 'futturu-hospedagemcloud-sim'); ?></h4>
                            <ul class="fcs-features-list" id="fcs-details-features"></ul>
                        </div>
                        
                        <div class="fcs-details-section">
                            <button class="fcs-btn-primary fcs-btn-quote-from-details"><?php echo esc_html($cta_text); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Handle quote request
     */
    public function handle_quote_request() {
        check_ajax_referer('futturu_hospedagemcloud_sim_nonce', 'nonce');
        
        // Sanitize input
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $plan_interest = isset($_POST['plan_interest']) ? sanitize_text_field($_POST['plan_interest']) : '';
        $recurrence = isset($_POST['recurrence']) ? sanitize_text_field($_POST['recurrence']) : '';
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
        
        // Validate required fields
        if (empty($name) || empty($email) || empty($plan_interest) || empty($recurrence)) {
            wp_send_json_error(array('message' => __('Por favor, preencha todos os campos obrigatórios.', 'futturu-hospedagemcloud-sim')));
        }
        
        // Prepare email
        $to = get_option('futturu_hospedagemcloud_cta_email', 'suporte@futturu.com.br');
        $subject = sprintf(__('Nova Solicitação de Cotação - %s', 'futturu-hospedagemcloud-sim'), $plan_interest);
        
        $recurrence_label = $recurrence === 'annual' 
            ? __('Anual (10% OFF)', 'futturu-hospedagemcloud-sim') 
            : __('Mensal', 'futturu-hospedagemcloud-sim');
        
        $body = sprintf(
            __("Nova solicitação de cotação recebida:\n\n" .
            "Nome: %s\n" .
            "E-mail: %s\n" .
            "Telefone: %s\n" .
            "Plano de Interesse: %s\n" .
            "Recorrência: %s\n" .
            "Mensagem: %s\n\n" .
            "---\n" .
            "Enviado via Simulador Futturu Cloud", 'futturu-hospedagemcloud-sim'),
            $name,
            $email,
            $phone,
            $plan_interest,
            $recurrence_label,
            $message ?: __('Nenhuma mensagem adicional.', 'futturu-hospedagemcloud-sim')
        );
        
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'Reply-To: ' . $email,
            'From: WordPress <' . get_option('admin_email') . '>'
        );
        
        // Send email
        $sent = wp_mail($to, $subject, $body, $headers);
        
        if ($sent) {
            wp_send_json_success(array('message' => __('Solicitação enviada com sucesso! Entraremos em contato em breve.', 'futturu-hospedagemcloud-sim')));
        } else {
            wp_send_json_error(array('message' => __('Erro ao enviar solicitação. Por favor, tente novamente ou entre em contato diretamente por e-mail.', 'futturu-hospedagemcloud-sim')));
        }
    }
}
