<?php
/**
 * Class Futturu_HospedagemCloud_Frontend
 * Renderiza o shortcode do simulador no frontend
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_HospedagemCloud_Frontend {

    public function __construct() {
        add_shortcode('futturu_hospedagemcloud_annual_monthly_sim', array($this, 'render_simulator'));
        add_action('wp_ajax_futturu_hospedagemcloud_send_quote', array($this, 'ajax_send_quote'));
        add_action('wp_ajax_nopriv_futturu_hospedagemcloud_send_quote', array($this, 'ajax_send_quote'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function enqueue_assets() {
        wp_enqueue_style('futturu-hospedagemcloud-frontend-css', FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/css/frontend.css', array(), FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION);
        wp_enqueue_script('futturu-hospedagemcloud-frontend-js', FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION, true);
        wp_localize_script('futturu-hospedagemcloud-frontend-js', 'futturuHospedagemCloudFront', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_hospedagemcloud_frontend_nonce')
        ));
    }

    public function render_simulator() {
        $data = new Futturu_HospedagemCloud_Data();
        $categories = $data->get_categories();
        $settings = $data->get_settings();
        $features = $data->get_features();
        $faqs = $data->get_faqs();
        $default_view = $settings['default_view'];
        
        ob_start();
        ?>
        <div class="fcs-simulator" data-default-view="<?php echo esc_attr($default_view); ?>">
            
            <!-- Introdução -->
            <div class="fcs-intro">
                <h2>Descubra o plano de hospedagem em nuvem ideal para o seu projeto</h2>
                <p>Escolha o período de pagamento: <strong>Economize 10% contratando anualmente</strong> ou pague mensalmente com flexibilidade. Nossa parceria com a Cloudez oferece servidores de alto desempenho na nuvem com gerenciamento completo e suporte técnico especializado.</p>
                
                <div class="fcs-features-grid">
                    <?php foreach (array_slice($features, 0, 6) as $feature): ?>
                    <div class="fcs-feature-badge">✓ <?php echo esc_html($feature); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Seletor de Recorrência -->
            <div class="fcs-recurrence-selector">
                <button type="button" class="fcs-recurrence-btn <?php echo $default_view === 'monthly' ? 'active' : ''; ?>" data-view="monthly">Mensal</button>
                <button type="button" class="fcs-recurrence-btn <?php echo $default_view === 'annual' ? 'active' : ''; ?>" data-view="annual">
                    Anual <span class="fcs-discount-badge">10% OFF</span>
                </button>
            </div>

            <!-- Navegação por Categorias -->
            <div class="fcs-categories-nav">
                <?php foreach ($categories as $slug => $name): ?>
                <button type="button" class="fcs-category-btn <?php echo $slug === 'padrao' ? 'active' : ''; ?>" data-category="<?php echo esc_attr($slug); ?>">
                    <?php echo esc_html($name); ?>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- Slider de Planos -->
            <?php foreach ($categories as $slug => $name): 
                $plans = $data->get_plans_by_category($slug);
            ?>
            <div class="fcs-slider-container <?php echo $slug !== 'padrao' ? 'hidden' : ''; ?>" data-category="<?php echo esc_attr($slug); ?>">
                <h3 class="fcs-slider-title"><?php echo esc_html($name); ?></h3>
                
                <div class="fcs-slider">
                    <button class="fcs-slider-nav fcs-slider-prev" disabled>&#8249;</button>
                    
                    <div class="fcs-slider-track">
                        <div class="fcs-slider-wrapper">
                            <?php foreach ($plans as $plan): 
                                $preco_mensal = floatval($plan['preco_mensal']);
                                $preco_anual = $preco_mensal * 12 * (1 - $settings['discount_rate'] / 100);
                                $economia = ($preco_mensal * 12) - $preco_anual;
                            ?>
                            <div class="fcs-plan-card" data-plan-id="<?php echo esc_attr($plan['id']); ?>">
                                <?php if ($default_view === 'annual'): ?>
                                <div class="fcs-recommended-badge">Recomendado</div>
                                <?php endif; ?>
                                
                                <div class="fcs-plan-header">
                                    <h4><?php echo esc_html($plan['nome']); ?></h4>
                                </div>
                                
                                <div class="fcs-plan-resources">
                                    <div class="fcs-resource"><span class="fcs-resource-value"><?php echo esc_html($plan['ram']); ?></span><span class="fcs-resource-label">RAM</span></div>
                                    <div class="fcs-resource"><span class="fcs-resource-value"><?php echo esc_html($plan['cpu']); ?></span><span class="fcs-resource-label">CPU</span></div>
                                    <div class="fcs-resource"><span class="fcs-resource-value"><?php echo esc_html($plan['disco']); ?></span><span class="fcs-resource-label">SSD</span></div>
                                </div>
                                
                                <?php if (!empty($plan['visualizacoes'])): ?>
                                <div class="fcs-plan-views">~<?php echo esc_html($plan['visualizacoes']); ?>/mês</div>
                                <?php endif; ?>
                                
                                <?php if (!empty($plan['uso_indicado'])): ?>
                                <div class="fcs-plan-usage">Indicado: <?php echo esc_html($plan['uso_indicado']); ?></div>
                                <?php endif; ?>
                                
                                <div class="fcs-plan-pricing">
                                    <div class="fcs-price-display <?php echo $default_view === 'annual' ? 'show-annual' : ''; ?>">
                                        <div class="fcs-monthly-price">R$ <?php echo number_format($preco_mensal, 2, ',', '.'); ?>/mês</div>
                                        <div class="fcs-annual-price">
                                            <span class="fcs-price-value">R$ <?php echo number_format($preco_anual, 2, ',', '.'); ?>/ano</span>
                                            <span class="fcs-savings">Economize R$ <?php echo number_format($economia, 2, ',', '.'); ?>!</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="fcs-plan-actions">
                                    <button type="button" class="fcs-btn-more-info" data-plan='<?php echo esc_attr(json_encode($plan)); ?>'>Mais Info</button>
                                    <button type="button" class="fcs-btn-quote" data-plan-name="<?php echo esc_attr($plan['nome']); ?>">Solicitar Cotação</button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <button class="fcs-slider-nav fcs-slider-next">&#8250;</button>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- FAQ -->
            <div class="fcs-faq-section">
                <h3>Dúvidas Frequentes</h3>
                <div class="fcs-faq-list">
                    <?php foreach ($faqs as $faq): ?>
                    <div class="fcs-faq-item">
                        <div class="fcs-faq-question">
                            <strong><?php echo esc_html($faq['pergunta']); ?></strong>
                            <span class="fcs-faq-toggle">+</span>
                        </div>
                        <div class="fcs-faq-answer"><?php echo esc_html($faq['resposta']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Modal Mais Info -->
            <div id="fcs-modal-info" class="fcs-modal">
                <div class="fcs-modal-content">
                    <span class="fcs-modal-close">&times;</span>
                    <h3 id="fcs-modal-title"></h3>
                    <div id="fcs-modal-body"></div>
                </div>
            </div>

            <!-- Modal Cotação -->
            <div id="fcs-modal-quote" class="fcs-modal">
                <div class="fcs-modal-content">
                    <span class="fcs-modal-close">&times;</span>
                    <h3>Solicitar Cotação</h3>
                    <form id="fcs-quote-form">
                        <input type="hidden" name="plan_name" id="fcs-quote-plan-name">
                        <input type="hidden" name="recurrence" id="fcs-quote-recurrence" value="<?php echo esc_attr($default_view); ?>">
                        
                        <div class="fcs-form-group">
                            <label for="fcs-name">Nome *</label>
                            <input type="text" name="name" id="fcs-name" required>
                        </div>
                        <div class="fcs-form-group">
                            <label for="fcs-email">E-mail *</label>
                            <input type="email" name="email" id="fcs-email" required>
                        </div>
                        <div class="fcs-form-group">
                            <label for="fcs-phone">Telefone/WhatsApp</label>
                            <input type="tel" name="phone" id="fcs-phone">
                        </div>
                        <div class="fcs-form-group">
                            <label for="fcs-message">Mensagem (opcional)</label>
                            <textarea name="message" id="fcs-message" rows="4"></textarea>
                        </div>
                        
                        <button type="submit" class="fcs-btn-submit">Enviar Solicitação</button>
                    </form>
                    <div id="fcs-quote-message"></div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function ajax_send_quote() {
        check_ajax_referer('futturu_hospedagemcloud_frontend_nonce', 'nonce');
        
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $plan_name = sanitize_text_field($_POST['plan_name']);
        $recurrence = sanitize_text_field($_POST['recurrence']);
        $message = sanitize_textarea_field($_POST['message']);
        
        $data = new Futturu_HospedagemCloud_Data();
        $settings = $data->get_settings();
        $to = $settings['contact_email'];
        
        $subject = 'Nova Cotação - Plano: ' . $plan_name;
        $body = "Nova solicitação de cotação:\n\n";
        $body .= "Nome: {$name}\n";
        $body .= "E-mail: {$email}\n";
        $body .= "Telefone: {$phone}\n";
        $body .= "Plano de Interesse: {$plan_name}\n";
        $body .= "Recorrência: " . ($recurrence === 'annual' ? 'Anual (10% OFF)' : 'Mensal') . "\n";
        $body .= "Mensagem: {$message}\n";
        
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        $headers[] = 'Reply-To: ' . $email;
        
        if (wp_mail($to, $subject, $body, $headers)) {
            wp_send_json_success('Solicitação enviada com sucesso! Entraremos em contato em breve.');
        } else {
            wp_send_json_error('Erro ao enviar solicitação. Tente novamente ou entre em contato diretamente.');
        }
    }
}
