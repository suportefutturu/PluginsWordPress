<?php
/**
 * Classe Frontend do Simulador Futturu Cloud
 * Gerencia a renderização do shortcode e funcionalidades do frontend
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_HospedagemCloud_Frontend {

    public function __construct() {
        add_shortcode('futturu_hospedagemcloud_annual_monthly_sim', array($this, 'render_shortcode'));
        add_action('wp_ajax_futturu_hospedagemcloud_send_quote', array($this, 'ajax_send_quote'));
        add_action('wp_ajax_nopriv_futturu_hospedagemcloud_send_quote', array($this, 'ajax_send_quote'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    /**
     * Carrega assets do frontend
     */
    public function enqueue_assets() {
        // Só carrega se o shortcode estiver na página
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'futturu_hospedagemcloud_annual_monthly_sim')) {
            wp_enqueue_style(
                'futturu-hospedagemcloud-frontend-css',
                FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/css/frontend.css',
                array(),
                FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION
            );

            wp_enqueue_script(
                'futturu-hospedagemcloud-frontend-js',
                FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/js/frontend.js',
                array('jquery'),
                FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION,
                true
            );

            $settings = Futturu_HospedagemCloud_Data::get_settings();
            wp_localize_script('futturu-hospedagemcloud-frontend-js', 'futturuHospedagemCloudFrontend', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('futturu_hospedagemcloud_frontend_nonce'),
                'descontoAnual' => $settings['desconto_anual'],
                'strings' => array(
                    'loading' => 'Carregando...',
                    'error' => 'Erro ao enviar. Tente novamente.',
                    'success' => 'Cotação enviada com sucesso! Entraremos em contato em breve.',
                    'monthly' => '/mês',
                    'annual' => '/ano',
                    'saveAnnual' => 'Economize R$ %s anualmente!',
                    'discountBadge' => '%d%% OFF',
                    'recommended' => 'Recomendado'
                )
            ));
        }
    }

    /**
     * Renderiza o shortcode
     */
    public function render_shortcode($atts) {
        $settings = Futturu_HospedagemCloud_Data::get_settings();
        $categories = Futturu_HospedagemCloud_Data::get_categories();
        $features = Futturu_HospedagemCloud_Data::get_features();
        $faqs = Futturu_HospedagemCloud_Data::get_faqs();

        ob_start();
        ?>
        <div class="futturu-cloud-simulator" id="futturu-cloud-sim">
            
            <!-- Introdução -->
            <div class="fcs-intro">
                <h2><?php esc_html_e('Simulador de Hospedagem na Nuvem Futturu', 'futturu-hospedagemcloud-sim'); ?></h2>
                <p class="fcs-intro-text"><?php echo wp_kses_post($settings['texto_introducao']); ?></p>
                
                <!-- Features em destaque -->
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

            <!-- Seletor de Recorrência -->
            <div class="fcs-recurrence-selector">
                <div class="fcs-toggle-container">
                    <button class="fcs-toggle-btn" data-recurrence="monthly">
                        <?php esc_html_e('Mensal', 'futturu-hospedagemcloud-sim'); ?>
                    </button>
                    <button class="fcs-toggle-btn active" data-recurrence="annual">
                        <?php esc_html_e('Anual', 'futturu-hospedagemcloud-sim'); ?>
                        <span class="fcs-discount-badge"><?php printf(esc_html__('%d%% OFF', 'futturu-hospedagemcloud-sim'), $settings['desconto_anual']); ?></span>
                        <span class="fcs-recommended"><?php esc_html_e('Recomendado', 'futturu-hospedagemcloud-sim'); ?></span>
                    </button>
                </div>
                <p class="fcs-recurrence-note">
                    <?php printf(esc_html__('Contrate anualmente e economize %d%%!', 'futturu-hospedagemcloud-sim'), $settings['desconto_anual']); ?>
                </p>
            </div>

            <!-- Navegação por Categorias -->
            <div class="fcs-categories-nav">
                <?php foreach ($categories as $cat_key => $cat_name): ?>
                    <button class="fcs-cat-btn <?php echo $cat_key === 'padrao' ? 'active' : ''; ?>" 
                            data-category="<?php echo esc_attr($cat_key); ?>">
                        <?php echo esc_html($cat_name); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Slider de Planos -->
            <?php foreach ($categories as $cat_key => $cat_name): ?>
                <div class="fcs-plans-section <?php echo $cat_key === 'padrao' ? 'active' : ''; ?>" 
                     data-category="<?php echo esc_attr($cat_key); ?>">
                    
                    <div class="fcs-slider-container">
                        <button class="fcs-slider-btn fcs-slider-prev" aria-label="<?php esc_attr_e('Anterior', 'futturu-hospedagemcloud-sim'); ?>">
                            <span>&#8592;</span>
                        </button>
                        
                        <div class="fcs-slider-wrapper">
                            <div class="fcs-slider-track" id="slider-track-<?php echo esc_attr($cat_key); ?>">
                                <?php
                                $plans = Futturu_HospedagemCloud_Data::get_plans_by_category($cat_key);
                                foreach ($plans as $plan):
                                    $preco_mensal = floatval($plan['preco_mensal']);
                                    $preco_anual = $preco_mensal * 12 * (1 - $settings['desconto_anual'] / 100);
                                    $economia = ($preco_mensal * 12) - $preco_anual;
                                ?>
                                    <div class="fcs-plan-card" 
                                         data-modelo="<?php echo esc_attr($plan['modelo']); ?>"
                                         data-nome="<?php echo esc_attr($plan['nome_exibido']); ?>"
                                         data-preco-mensal="<?php echo esc_attr($preco_mensal); ?>"
                                         data-preco-anual="<?php echo esc_attr($preco_anual); ?>"
                                         data-economia="<?php echo esc_attr($economia); ?>"
                                         data-ram="<?php echo esc_attr($plan['ram']); ?>"
                                         data-cpu="<?php echo esc_attr($plan['cpu']); ?>"
                                         data-disco="<?php echo esc_attr($plan['disco']); ?>"
                                         data-visualizacoes="<?php echo esc_attr($plan['visualizacoes']); ?>"
                                         data-uso-indicado="<?php echo esc_attr($plan['uso_indicado']); ?>">
                                        
                                        <div class="fcs-plan-header">
                                            <h3 class="fcs-plan-name"><?php echo esc_html($plan['nome_exibido']); ?></h3>
                                            <?php if ($cat_key !== 'email' && !empty($plan['visualizacoes'])): ?>
                                                <span class="fcs-plan-views"><?php echo esc_html($plan['visualizacoes']); ?> <?php esc_html_e('visitas/mês', 'futturu-hospedagemcloud-sim'); ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="fcs-plan-resources">
                                            <div class="fcs-resource">
                                                <span class="fcs-resource-label"><?php esc_html_e('RAM', 'futturu-hospedagemcloud-sim'); ?></span>
                                                <span class="fcs-resource-value"><?php echo esc_html($plan['ram']); ?></span>
                                            </div>
                                            <div class="fcs-resource">
                                                <span class="fcs-resource-label"><?php esc_html_e('CPU', 'futturu-hospedagemcloud-sim'); ?></span>
                                                <span class="fcs-resource-value"><?php echo esc_html($plan['cpu']); ?></span>
                                            </div>
                                            <div class="fcs-resource">
                                                <span class="fcs-resource-label"><?php esc_html_e('SSD', 'futturu-hospedagemcloud-sim'); ?></span>
                                                <span class="fcs-resource-value"><?php echo esc_html($plan['disco']); ?></span>
                                            </div>
                                        </div>

                                        <?php if (!empty($plan['uso_indicado'])): ?>
                                            <div class="fcs-plan-usage">
                                                <span class="fcs-usage-label"><?php esc_html_e('Indicado para:', 'futturu-hospedagemcloud-sim'); ?></span>
                                                <span class="fcs-usage-value"><?php echo esc_html($plan['uso_indicado']); ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <div class="fcs-plan-pricing">
                                            <div class="fcs-price-display annual" style="display: block;">
                                                <span class="fcs-price-value">R$ <?php echo number_format($preco_anual, 2, ',', '.'); ?></span>
                                                <span class="fcs-price-period"><?php esc_html_e('/ano', 'futturu-hospedagemcloud-sim'); ?></span>
                                            </div>
                                            <div class="fcs-price-display monthly" style="display: none;">
                                                <span class="fcs-price-value">R$ <?php echo number_format($preco_mensal, 2, ',', '.'); ?></span>
                                                <span class="fcs-price-period"><?php esc_html_e('/mês', 'futturu-hospedagemcloud-sim'); ?></span>
                                            </div>
                                            <div class="fcs-annual-savings" style="display: none;">
                                                <?php printf(esc_html__('Economize R$ %s anualmente!', 'futturu-hospedagemcloud-sim'), number_format($economia, 2, ',', '.')); ?>
                                            </div>
                                        </div>

                                        <div class="fcs-plan-actions">
                                            <button class="fcs-btn fcs-btn-secondary fcs-more-info-btn" 
                                                    data-modelo="<?php echo esc_attr($plan['modelo']); ?>">
                                                <?php esc_html_e('Mais Info', 'futturu-hospedagemcloud-sim'); ?>
                                            </button>
                                            <button class="fcs-btn fcs-btn-primary fcs-quote-btn" 
                                                    data-modelo="<?php echo esc_attr($plan['modelo']); ?>"
                                                    data-nome="<?php echo esc_attr($plan['nome_exibido']); ?>">
                                                <?php esc_html_e('Solicitar Cotação', 'futturu-hospedagemcloud-sim'); ?>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <button class="fcs-slider-btn fcs-slider-next" aria-label="<?php esc_attr_e('Próximo', 'futturu-hospedagemcloud-sim'); ?>">
                            <span>&#8594;</span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- FAQ -->
            <div class="fcs-faq-section">
                <h3><?php esc_html_e('Dúvidas Frequentes', 'futturu-hospedagemcloud-sim'); ?></h3>
                <div class="fcs-faq-accordion">
                    <?php foreach ($faqs as $faq): ?>
                        <div class="fcs-faq-item">
                            <button class="fcs-faq-question">
                                <span><?php echo esc_html($faq['pergunta']); ?></span>
                                <span class="fcs-faq-icon">+</span>
                            </button>
                            <div class="fcs-faq-answer">
                                <p><?php echo esc_html($faq['resposta']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <!-- Modal Mais Info -->
        <div class="fcs-modal-overlay" id="fcs-info-modal">
            <div class="fcs-modal">
                <button class="fcs-modal-close">&times;</button>
                <div class="fcs-modal-content">
                    <h3 id="modal-plan-name"></h3>
                    <div class="fcs-modal-resources"></div>
                    <div class="fcs-modal-features">
                        <h4><?php esc_html_e('Funcionalidades Incluídas', 'futturu-hospedagemcloud-sim'); ?></h4>
                        <ul>
                            <?php foreach ($features as $feature): ?>
                                <li><?php echo esc_html($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Cotação -->
        <div class="fcs-modal-overlay" id="fcs-quote-modal">
            <div class="fcs-modal">
                <button class="fcs-modal-close">&times;</button>
                <div class="fcs-modal-content">
                    <h3><?php esc_html_e('Solicitar Cotação', 'futturu-hospedagemcloud-sim'); ?></h3>
                    <form id="fcs-quote-form">
                        <?php wp_nonce_field('futturu_hospedagemcloud_quote', 'quote_nonce'); ?>
                        <input type="hidden" name="plano_interesse" id="quote-plano" value="">
                        <input type="hidden" name="recorrencia" id="quote-recorrencia" value="annual">
                        
                        <div class="fcs-form-group">
                            <label for="quote-nome"><?php esc_html_e('Nome *', 'futturu-hospedagemcloud-sim'); ?></label>
                            <input type="text" name="nome" id="quote-nome" required>
                        </div>
                        
                        <div class="fcs-form-group">
                            <label for="quote-email"><?php esc_html_e('E-mail *', 'futturu-hospedagemcloud-sim'); ?></label>
                            <input type="email" name="email" id="quote-email" required>
                        </div>
                        
                        <div class="fcs-form-group">
                            <label for="quote-telefone"><?php esc_html_e('Telefone/WhatsApp', 'futturu-hospedagemcloud-sim'); ?></label>
                            <input type="tel" name="telefone" id="quote-telefone">
                        </div>
                        
                        <div class="fcs-form-group">
                            <label for="quote-mensagem"><?php esc_html_e('Mensagem (opcional)', 'futturu-hospedagemcloud-sim'); ?></label>
                            <textarea name="mensagem" id="quote-mensagem" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="fcs-btn fcs-btn-primary fcs-full-width">
                            <?php esc_html_e('Enviar Cotação', 'futturu-hospedagemcloud-sim'); ?>
                        </button>
                        
                        <div class="fcs-form-message"></div>
                    </form>
                </div>
            </div>
        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * AJAX: Enviar cotação
     */
    public function ajax_send_quote() {
        check_ajax_referer('futturu_hospedagemcloud_quote', 'nonce');

        // Sanitização dos dados
        $nome = sanitize_text_field(isset($_POST['nome']) ? $_POST['nome'] : '');
        $email = sanitize_email(isset($_POST['email']) ? $_POST['email'] : '');
        $telefone = sanitize_text_field(isset($_POST['telefone']) ? $_POST['telefone'] : '');
        $plano = sanitize_text_field(isset($_POST['plano_interesse']) ? $_POST['plano_interesse'] : '');
        $recorrencia = sanitize_text_field(isset($_POST['recorrencia']) ? $_POST['recorrencia'] : 'annual');
        $mensagem = sanitize_textarea_field(isset($_POST['mensagem']) ? $_POST['mensagem'] : '');

        // Validação básica
        if (empty($nome) || empty($email)) {
            wp_send_json_error(array('message' => __('Nome e e-mail são obrigatórios.', 'futturu-hospedagemcloud-sim')));
        }

        if (!is_email($email)) {
            wp_send_json_error(array('message' => __('E-mail inválido.', 'futturu-hospedagemcloud-sim')));
        }

        $settings = Futturu_HospedagemCloud_Data::get_settings();
        $to = $settings['email_destino'];
        $subject = sprintf(__('Nova Cotação - Plano: %s (%s)', 'futturu-hospedagemcloud-sim'), $plano, $recorrencia === 'annual' ? 'Anual' : 'Mensal');
        
        $body = sprintf(
            __("Nova solicitação de cotação recebida:\n\nNome: %s\nE-mail: %s\nTelefone: %s\nPlano de Interesse: %s\nRecorrência: %s\n\nMensagem:\n%s", 'futturu-hospedagemcloud-sim'),
            $nome,
            $email,
            $telefone,
            $plano,
            $recorrencia === 'annual' ? 'Anual (10% OFF)' : 'Mensal',
            $mensagem
        );

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'Reply-To: ' . $email
        );

        // Envia e-mail
        $sent = wp_mail($to, $subject, $body, $headers);

        if ($sent) {
            wp_send_json_success(array('message' => __('Cotação enviada com sucesso! Entraremos em contato em breve.', 'futturu-hospedagemcloud-sim')));
        } else {
            wp_send_json_error(array('message' => __('Erro ao enviar cotação. Tente novamente ou entre em contato diretamente.', 'futturu-hospedagemcloud-sim')));
        }
    }
}
