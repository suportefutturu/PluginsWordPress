<?php
/**
 * Class Futuru_Cloud_Frontend
 * Handles frontend display of the simulator
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futuru_Cloud_Frontend {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'futturu-cloud-frontend-css',
            FUTTURU_CLOUD_SIMULATOR_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            FUTTURU_CLOUD_SIMULATOR_VERSION
        );
        
        wp_enqueue_script(
            'futturu-cloud-frontend-js',
            FUTTURU_CLOUD_SIMULATOR_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            FUTTURU_CLOUD_SIMULATOR_VERSION,
            true
        );
        
        wp_localize_script('futturu-cloud-frontend-js', 'futturuCloudFrontend', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_cloud_frontend_nonce'),
            'ctaText' => __('Solicitar Cotação', 'futturu-cloud-simulator')
        ));
    }
    
    /**
     * Render the simulator template
     */
    public static function render_simulator() {
        $settings = Futuru_Cloud_Data::get_settings();
        $categories = Futuru_Cloud_Data::get_categories();
        $features = Futuru_Cloud_Data::get_features();
        
        // Create features lookup map
        $features_map = array();
        foreach ($features as $feature) {
            $features_map[$feature['id']] = $feature;
        }
        ?>
        <div class="futturu-cloud-simulator">
            <!-- Introduction Section -->
            <div class="futturu-intro">
                <h2><?php esc_html_e('Simulador de Hospedagem na Nuvem Futturu', 'futturu-cloud-simulator'); ?></h2>
                <p class="intro-text"><?php echo esc_html($settings['intro_text']); ?></p>
                
                <!-- Benefits Grid -->
                <div class="futturu-benefits">
                    <div class="benefit-item">
                        <span class="benefit-icon">🔒</span>
                        <span class="benefit-text"><?php esc_html_e('Hospedagem Gerenciada', 'futturu-cloud-simulator'); ?></span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">⚡</span>
                        <span class="benefit-text"><?php esc_html_e('CDN e Otimizações Automáticas', 'futturu-cloud-simulator'); ?></span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">💾</span>
                        <span class="benefit-text"><?php esc_html_e('Backups Automáticos e Seguros', 'futturu-cloud-simulator'); ?></span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">👁️</span>
                        <span class="benefit-text"><?php esc_html_e('Monitoramento 24/7', 'futturu-cloud-simulator'); ?></span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">🛡️</span>
                        <span class="benefit-text"><?php esc_html_e('SSL Grátis + Firewall', 'futturu-cloud-simulator'); ?></span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">🎛️</span>
                        <span class="benefit-text"><?php esc_html_e('Painel Automatizado', 'futturu-cloud-simulator'); ?></span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">🚚</span>
                        <span class="benefit-text"><?php esc_html_e('Migração Grátis', 'futturu-cloud-simulator'); ?></span>
                    </div>
                    <div class="benefit-item">
                        <span class="benefit-icon">👨‍💻</span>
                        <span class="benefit-text"><?php esc_html_e('Suporte Especializado', 'futturu-cloud-simulator'); ?></span>
                    </div>
                </div>
                
                <p class="partnership-note">
                    <strong><?php esc_html_e('Parceria Cloudez:', 'futturu-cloud-simulator'); ?></strong>
                    <?php esc_html_e('Servidores de alto desempenho em Dallas e Newark (USA) com mais de 200.000 automações inteligentes.', 'futturu-cloud-simulator'); ?>
                </p>
            </div>
            
            <!-- Category Tabs -->
            <div class="futturu-tabs">
                <?php 
                $first = true;
                foreach ($categories as $category): 
                ?>
                <button class="tab-button <?php echo $first ? 'active' : ''; ?>" data-category="<?php echo esc_attr($category['slug']); ?>">
                    <?php echo esc_html($category['name']); ?>
                </button>
                <?php 
                $first = false;
                endforeach; 
                ?>
            </div>
            
            <!-- Plans Tables by Category -->
            <?php foreach ($categories as $category): ?>
            <div class="plans-section" data-category="<?php echo esc_attr($category['slug']); ?>" style="display: <?php echo $category['slug'] === 'clouds-padrao' ? 'block' : 'none'; ?>;">
                <h3 class="category-title"><?php echo esc_html($category['name']); ?></h3>
                <p class="category-description"><?php echo esc_html($category['description']); ?></p>
                
                <div class="plans-table-wrapper">
                    <table class="plans-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Modelo', 'futturu-cloud-simulator'); ?></th>
                                <th><?php esc_html_e('Recursos', 'futturu-cloud-simulator'); ?></th>
                                <?php if ($category['slug'] !== 'clouds-email'): ?>
                                <th><?php esc_html_e('Visualizações/mês', 'futturu-cloud-simulator'); ?></th>
                                <?php endif; ?>
                                <th><?php esc_html_e('Sites', 'futturu-cloud-simulator'); ?></th>
                                <th><?php esc_html_e('Preço', 'futturu-cloud-simulator'); ?></th>
                                <th><?php esc_html_e('Detalhes', 'futturu-cloud-simulator'); ?></th>
                                <th><?php esc_html_e('Ação', 'futturu-cloud-simulator'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $plans = Futuru_Cloud_Data::get_plans_by_category($category['slug']);
                            foreach ($plans as $plan):
                                $plan_features = isset($plan['features']) ? $plan['features'] : array();
                            ?>
                            <tr>
                                <td class="plan-model">
                                    <strong><?php echo esc_html($plan['modelo']); ?></strong>
                                </td>
                                <td class="plan-resources">
                                    <?php if ($category['slug'] === 'clouds-email'): ?>
                                        <div class="resource-item">
                                            <span class="resource-label"><?php esc_html_e('Disco:', 'futturu-cloud-simulator'); ?></span>
                                            <span class="resource-value"><?php echo esc_html(Futuru_Cloud_Data::format_number($plan['disco'], 'storage')); ?></span>
                                        </div>
                                        <div class="resource-item">
                                            <span class="resource-label"><?php esc_html_e('RAM:', 'futturu-cloud-simulator'); ?></span>
                                            <span class="resource-value"><?php echo esc_html(Futuru_Cloud_Data::format_number($plan['ram'], 'memory')); ?></span>
                                        </div>
                                        <div class="resource-item">
                                            <span class="resource-label"><?php esc_html_e('CPU:', 'futturu-cloud-simulator'); ?></span>
                                            <span class="resource-value"><?php echo esc_html(Futuru_Cloud_Data::format_number($plan['cpu'], 'cpu')); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="resource-item">
                                            <span class="resource-label"><?php esc_html_e('RAM:', 'futturu-cloud-simulator'); ?></span>
                                            <span class="resource-value"><?php echo esc_html(Futuru_Cloud_Data::format_number($plan['ram'], 'memory')); ?></span>
                                        </div>
                                        <div class="resource-item">
                                            <span class="resource-label"><?php esc_html_e('CPU:', 'futturu-cloud-simulator'); ?></span>
                                            <span class="resource-value"><?php echo esc_html(Futuru_Cloud_Data::format_number($plan['cpu'], 'cpu')); ?></span>
                                        </div>
                                        <div class="resource-item">
                                            <span class="resource-label"><?php esc_html_e('SSD:', 'futturu-cloud-simulator'); ?></span>
                                            <span class="resource-value"><?php echo esc_html(Futuru_Cloud_Data::format_number($plan['disco'], 'storage')); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <?php if ($category['slug'] !== 'clouds-email'): ?>
                                <td class="plan-views">
                                    <?php echo $plan['visualizacoes'] ? esc_html(Futuru_Cloud_Data::format_number($plan['visualizacoes'], 'views')) : 'N/A'; ?>
                                </td>
                                <?php endif; ?>
                                <td class="plan-sites">
                                    <?php echo esc_html($plan['sites']); ?>
                                </td>
                                <td class="plan-price">
                                    <span class="price-value"><?php echo esc_html(Futuru_Cloud_Data::format_price($plan['preco'])); ?></span>
                                    <span class="price-period"><?php esc_html_e('/mês', 'futturu-cloud-simulator'); ?></span>
                                </td>
                                <td class="plan-details">
                                    <button class="more-info-btn" data-plan-id="<?php echo esc_attr($plan['id']); ?>">
                                        <?php esc_html_e('Mais Info', 'futturu-cloud-simulator'); ?>
                                    </button>
                                </td>
                                <td class="plan-cta">
                                    <button class="cta-btn" data-plan-model="<?php echo esc_attr($plan['modelo']); ?>">
                                        <?php echo esc_html($settings['cta_text']); ?>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endforeach; ?>
            
            <!-- Global CTA -->
            <div class="futturu-global-cta">
                <h3><?php esc_html_e('Pronto para escolher seu plano?', 'futturu-cloud-simulator'); ?></h3>
                <p><?php esc_html_e('Fale com um especialista da Futturu e descubra a solução ideal para seu projeto.', 'futturu-cloud-simulator'); ?></p>
                <button class="cta-btn global" id="global-cta-btn">
                    <?php echo esc_html($settings['cta_text']); ?>
                </button>
            </div>
        </div>
        
        <!-- Plan Details Modal -->
        <div id="plan-details-modal" class="modal-overlay" style="display:none;">
            <div class="modal-container">
                <button class="modal-close">&times;</button>
                <div class="modal-header">
                    <h3 id="modal-plan-name"></h3>
                    <span class="modal-price" id="modal-plan-price"></span>
                </div>
                <div class="modal-body">
                    <div class="modal-section">
                        <h4><?php esc_html_e('Recursos Técnicos', 'futturu-cloud-simulator'); ?></h4>
                        <div class="resources-grid" id="modal-resources"></div>
                    </div>
                    
                    <div class="modal-section">
                        <h4><?php esc_html_e('Automações e Benefícios Inclusos', 'futturu-cloud-simulator'); ?></h4>
                        <ul class="features-list" id="modal-features"></ul>
                    </div>
                    
                    <div class="modal-section">
                        <h4><?php esc_html_e('Recomendações', 'futturu-cloud-simulator'); ?></h4>
                        <div id="modal-recommendations"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="cta-btn" id="modal-cta-btn"></button>
                </div>
            </div>
        </div>
        
        <!-- Contact Form Modal -->
        <div id="contact-modal" class="modal-overlay" style="display:none;">
            <div class="modal-container contact-form">
                <button class="modal-close">&times;</button>
                <div class="modal-header">
                    <h3><?php esc_html_e('Solicitar Cotação', 'futturu-cloud-simulator'); ?></h3>
                </div>
                <div class="modal-body">
                    <form id="quote-form">
                        <input type="hidden" name="action" value="futturu_cloud_submit_quote">
                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('futturu_cloud_frontend_nonce'); ?>">
                        <input type="hidden" name="plan_model" id="quote-plan-model" value="">
                        
                        <div class="form-group">
                            <label for="quote-name"><?php esc_html_e('Nome Completo *', 'futturu-cloud-simulator'); ?></label>
                            <input type="text" name="name" id="quote-name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="quote-email"><?php esc_html_e('E-mail Corporativo *', 'futturu-cloud-simulator'); ?></label>
                            <input type="email" name="email" id="quote-email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="quote-phone"><?php esc_html_e('Telefone/WhatsApp *', 'futturu-cloud-simulator'); ?></label>
                            <input type="tel" name="phone" id="quote-phone" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="quote-company"><?php esc_html_e('Empresa', 'futturu-cloud-simulator'); ?></label>
                            <input type="text" name="company" id="quote-company">
                        </div>
                        
                        <div class="form-group">
                            <label for="quote-plan"><?php esc_html_e('Plano de Interesse *', 'futturu-cloud-simulator'); ?></label>
                            <input type="text" name="plan" id="quote-plan-display" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="quote-message"><?php esc_html_e('Mensagem (opcional)', 'futturu-cloud-simulator'); ?></label>
                            <textarea name="message" id="quote-message" rows="4"></textarea>
                        </div>
                        
                        <div class="form-submit">
                            <button type="submit" class="cta-btn"><?php esc_html_e('Enviar Solicitação', 'futturu-cloud-simulator'); ?></button>
                        </div>
                        
                        <div class="form-notice" id="form-notice"></div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
}
