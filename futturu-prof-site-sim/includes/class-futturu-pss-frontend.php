<?php
/**
 * Frontend functionality for Futturu Professional Site Simulator
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_PSS_Frontend {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        // Check if shortcode is being used on this page
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'futturu_prof_site_sim')) {
            wp_enqueue_style(
                'futturu-pss-frontend',
                FUTTURU_PSS_PLUGIN_URL . 'assets/css/frontend.css',
                array(),
                FUTTURU_PSS_VERSION
            );
            
            wp_enqueue_script(
                'futturu-pss-frontend',
                FUTTURU_PSS_PLUGIN_URL . 'assets/js/frontend.js',
                array('jquery'),
                FUTTURU_PSS_VERSION,
                true
            );
            
            wp_localize_script('futturu-pss-frontend', 'futturuPSS', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('futturu_pss_nonce'),
                'strings' => array(
                    'loading' => __('Carregando...', 'futturu-prof-site-sim'),
                    'error' => __('Ocorreu um erro. Tente novamente.', 'futturu-prof-site-sim'),
                    'required' => __('Campo obrigatório', 'futturu-prof-site-sim'),
                    'invalidEmail' => __('E-mail inválido', 'futturu-prof-site-sim'),
                    'invalidPhone' => __('Telefone inválido', 'futturu-prof-site-sim'),
                    'success' => __('Proposta enviada com sucesso! Entraremos em contato em breve.', 'futturu-prof-site-sim')
                )
            ));
        }
    }
    
    /**
     * Render the simulator
     */
    public function render_simulator($atts) {
        $active = get_option('futturu_pss_active', 'yes');
        if ($active !== 'yes') {
            return '<p>' . __('Simulador temporariamente indisponível.', 'futturu-prof-site-sim') . '</p>';
        }
        
        $atts = shortcode_atts(array(
            'show_title' => 'true',
            'cta_text' => get_option('futturu_pss_cta_text', 'Solicite uma Proposta Personalizada')
        ), $atts);
        
        // Get site types
        $site_types_raw = get_option('futturu_pss_site_types');
        $site_types = is_serialized($site_types_raw) ? unserialize($site_types_raw) : array();
        
        // Get categories
        $categories_raw = get_option('futturu_pss_categories');
        $categories = is_serialized($categories_raw) ? unserialize($categories_raw) : array();
        
        ob_start();
        ?>
        <div class="futturu-pss-container" id="futturu-pss-simulator">
            <?php if ($atts['show_title'] === 'true') : ?>
            <div class="futturu-pss-header">
                <h2><?php _e('Simule Seu Site Profissional', 'futturu-prof-site-sim'); ?></h2>
                <p><?php _e('Descubra como seria seu site profissional criado e hospedado pela Futturu', 'futturu-prof-site-sim'); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Progress Indicator -->
            <div class="futturu-pss-progress">
                <div class="futturu-pss-step active" data-step="1">
                    <span class="step-number">1</span>
                    <span class="step-label"><?php _e('Tipo de Site', 'futturu-prof-site-sim'); ?></span>
                </div>
                <div class="futturu-pss-step" data-step="2">
                    <span class="step-number">2</span>
                    <span class="step-label"><?php _e('Informações', 'futturu-prof-site-sim'); ?></span>
                </div>
                <div class="futturu-pss-step" data-step="3">
                    <span class="step-number">3</span>
                    <span class="step-label"><?php _e('Contato', 'futturu-prof-site-sim'); ?></span>
                </div>
            </div>
            
            <form id="futturu-pss-form" method="post">
                <!-- Step 1: Site Type Selection -->
                <div class="futturu-pss-step-content" data-step="1">
                    <h3><?php _e('Escolha o Tipo de Site', 'futturu-prof-site-sim'); ?></h3>
                    <div class="futturu-pss-site-types">
                        <?php foreach ($site_types as $slug => $name) : ?>
                        <label class="futturu-pss-type-card">
                            <input type="radio" name="site_type" value="<?php echo esc_attr($slug); ?>" required>
                            <div class="type-card-content">
                                <span class="type-icon"><?php echo $this->get_type_icon($slug); ?></span>
                                <span class="type-name"><?php echo esc_html($name); ?></span>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="futturu-pss-actions">
                        <button type="button" class="futturu-pss-btn futturu-pss-btn-next" disabled>
                            <?php _e('Próximo', 'futturu-prof-site-sim'); ?> →
                        </button>
                    </div>
                </div>
                
                <!-- Step 2: Business Information -->
                <div class="futturu-pss-step-content" data-step="2" style="display:none;">
                    <h3><?php _e('Informações do Negócio', 'futturu-prof-site-sim'); ?></h3>
                    
                    <div class="futturu-pss-grid">
                        <div class="futturu-pss-field">
                            <label for="business_name"><?php _e('Nome do Negócio ou Profissional', 'futturu-prof-site-sim'); ?> *</label>
                            <input type="text" id="business_name" name="business_name" required 
                                   placeholder="<?php _e('Ex: Dr. João Silva, Restaurante Remanso', 'futturu-prof-site-sim'); ?>">
                        </div>
                        
                        <div class="futturu-pss-field">
                            <label for="business_category"><?php _e('Categoria/Setor', 'futturu-prof-site-sim'); ?> *</label>
                            <select id="business_category" name="business_category" required>
                                <option value=""><?php _e('Selecione uma categoria', 'futturu-prof-site-sim'); ?></option>
                                <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo esc_attr($category); ?>"><?php echo esc_html($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="futturu-pss-field">
                            <label for="business_location"><?php _e('Localidade', 'futturu-prof-site-sim'); ?></label>
                            <input type="text" id="business_location" name="business_location" 
                                   placeholder="<?php _e('Ex: Belém, PA', 'futturu-prof-site-sim'); ?>">
                        </div>
                        
                        <div class="futturu-pss-field">
                            <label for="business_service"><?php _e('Principal Serviço/Produto', 'futturu-prof-site-sim'); ?></label>
                            <input type="text" id="business_service" name="business_service" 
                                   placeholder="<?php _e('Ex: Consultoria Jurídica, Marmitas Fitness', 'futturu-prof-site-sim'); ?>">
                        </div>
                        
                        <div class="futturu-pss-field">
                            <label for="business_phone"><?php _e('Telefone para Contato', 'futturu-prof-site-sim'); ?></label>
                            <input type="tel" id="business_phone" name="business_phone" 
                                   placeholder="<?php _e('(XX) XXXXX-XXXX', 'futturu-prof-site-sim'); ?>"
                                   class="futturu-pss-phone-mask">
                        </div>
                        
                        <div class="futturu-pss-field">
                            <label for="business_address"><?php _e('Endereço ou Bairro', 'futturu-prof-site-sim'); ?></label>
                            <input type="text" id="business_address" name="business_address" 
                                   placeholder="<?php _e('Ex: Centro, Rua das Flores, 123', 'futturu-prof-site-sim'); ?>">
                        </div>
                    </div>
                    
                    <div class="futturu-pss-actions">
                        <button type="button" class="futturu-pss-btn futturu-pss-btn-prev">
                            ← <?php _e('Voltar', 'futturu-prof-site-sim'); ?>
                        </button>
                        <button type="button" class="futturu-pss-btn futturu-pss-btn-primary futturu-pss-btn-next">
                            <?php _e('Ver Preview', 'futturu-prof-site-sim'); ?> →
                        </button>
                    </div>
                </div>
                
                <!-- Preview Section -->
                <div class="futturu-pss-step-content" data-step="preview" style="display:none;">
                    <h3><?php _e('Preview do Seu Site', 'futturu-prof-site-sim'); ?></h3>
                    <p class="futturu-pss-preview-intro"><?php _e('Veja como ficaria seu site profissional:', 'futturu-prof-site-sim'); ?></p>
                    
                    <div class="futturu-pss-preview-container">
                        <div class="futturu-pss-preview-mockup">
                            <!-- Header -->
                            <div class="preview-header">
                                <div class="preview-logo">
                                    <span class="preview-business-name"></span>
                                </div>
                                <nav class="preview-nav">
                                    <a href="#"><?php _e('Home', 'futturu-prof-site-sim'); ?></a>
                                    <a href="#"><?php _e('Sobre', 'futturu-prof-site-sim'); ?></a>
                                    <a href="#"><?php _e('Serviços', 'futturu-prof-site-sim'); ?></a>
                                    <a href="#"><?php _e('Contato', 'futturu-prof-site-sim'); ?></a>
                                </nav>
                            </div>
                            
                            <!-- Hero Section -->
                            <div class="preview-hero">
                                <h1 class="preview-hero-title"></h1>
                                <p class="preview-hero-subtitle"></p>
                                <a href="#" class="preview-cta-btn"><?php _e('Entre em Contato', 'futturu-prof-site-sim'); ?></a>
                            </div>
                            
                            <!-- About Section -->
                            <div class="preview-section preview-about">
                                <h2><?php _e('Sobre Nós', 'futturu-prof-site-sim'); ?></h2>
                                <p class="preview-about-text"></p>
                            </div>
                            
                            <!-- Services Section -->
                            <div class="preview-section preview-services">
                                <h2><?php _e('Nossos Serviços', 'futturu-prof-site-sim'); ?></h2>
                                <div class="preview-services-grid">
                                    <div class="preview-service-item">
                                        <span class="service-icon">✓</span>
                                        <span class="service-name preview-service-1"></span>
                                    </div>
                                    <div class="preview-service-item">
                                        <span class="service-icon">✓</span>
                                        <span class="service-name preview-service-2"></span>
                                    </div>
                                    <div class="preview-service-item">
                                        <span class="service-icon">✓</span>
                                        <span class="service-name preview-service-3"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Section -->
                            <div class="preview-section preview-contact">
                                <h2><?php _e('Entre em Contato', 'futturu-prof-site-sim'); ?></h2>
                                <div class="preview-contact-grid">
                                    <div class="preview-contact-form">
                                        <input type="text" placeholder="<?php _e('Seu Nome', 'futturu-prof-site-sim'); ?>" disabled>
                                        <input type="email" placeholder="<?php _e('Seu E-mail', 'futturu-prof-site-sim'); ?>" disabled>
                                        <textarea placeholder="<?php _e('Sua Mensagem', 'futturu-prof-site-sim'); ?>" disabled></textarea>
                                        <button type="button" disabled><?php _e('Enviar Mensagem', 'futturu-prof-site-sim'); ?></button>
                                    </div>
                                    <div class="preview-contact-info">
                                        <div class="contact-item preview-whatsapp">
                                            <span class="icon">📱</span>
                                            <span class="preview-phone"></span>
                                        </div>
                                        <div class="contact-item preview-location">
                                            <span class="icon">📍</span>
                                            <span class="preview-address"></span>
                                        </div>
                                        <div class="preview-social">
                                            <span class="social-icon">📘</span>
                                            <span class="social-icon">📷</span>
                                            <span class="social-icon">💼</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Footer -->
                            <div class="preview-footer">
                                <p>&copy; <span class="preview-business-name-footer"></span> - <?php _e('Todos os direitos reservados', 'futturu-prof-site-sim'); ?></p>
                                <div class="preview-badge">
                                    <span>🚀 <?php _e('Otimizado para Google', 'futturu-prof-site-sim'); ?></span>
                                    <span>☁️ <?php _e('Hospedado na Nuvem Futturu', 'futturu-prof-site-sim'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="futturu-pss-actions">
                        <button type="button" class="futturu-pss-btn futturu-pss-btn-prev">
                            ← <?php _e('Editar Informações', 'futturu-prof-site-sim'); ?>
                        </button>
                        <button type="button" class="futturu-pss-btn futturu-pss-btn-primary futturu-pss-btn-to-contact">
                            <?php _e('Solicitar Proposta', 'futturu-prof-site-sim'); ?> →
                        </button>
                    </div>
                </div>
                
                <!-- Step 3: Contact Form (Lead Capture) -->
                <div class="futturu-pss-step-content" data-step="3" style="display:none;">
                    <h3><?php _e('Solicite Sua Proposta', 'futturu-prof-site-sim'); ?></h3>
                    <p><?php _e('Preencha seus dados para receber uma proposta personalizada para seu site profissional.', 'futturu-prof-site-sim'); ?></p>
                    
                    <div class="futturu-pss-summary">
                        <h4><?php _e('Resumo do Seu Projeto', 'futturu-prof-site-sim'); ?>:</h4>
                        <ul class="summary-list">
                            <li><strong><?php _e('Tipo de Site:', 'futturu-prof-site-sim'); ?></strong> <span class="summary-site-type"></span></li>
                            <li><strong><?php _e('Negócio:', 'futturu-prof-site-sim'); ?></strong> <span class="summary-business-name"></span></li>
                            <li><strong><?php _e('Categoria:', 'futturu-prof-site-sim'); ?></strong> <span class="summary-category"></span></li>
                            <li><strong><?php _e('Localidade:', 'futturu-prof-site-sim'); ?></strong> <span class="summary-location"></span></li>
                        </ul>
                    </div>
                    
                    <div class="futturu-pss-grid">
                        <div class="futturu-pss-field">
                            <label for="lead_name"><?php _e('Nome Completo', 'futturu-prof-site-sim'); ?> *</label>
                            <input type="text" id="lead_name" name="lead_name" required>
                        </div>
                        
                        <div class="futturu-pss-field">
                            <label for="lead_phone"><?php _e('Telefone', 'futturu-prof-site-sim'); ?> *</label>
                            <input type="tel" id="lead_phone" name="lead_phone" required 
                                   placeholder="+55 XX XXXXX-XXXX" class="futturu-pss-phone-mask-full">
                        </div>
                        
                        <div class="futturu-pss-field futturu-pss-full-width">
                            <label for="lead_email"><?php _e('E-mail', 'futturu-prof-site-sim'); ?> *</label>
                            <input type="email" id="lead_email" name="lead_email" required>
                        </div>
                        
                        <div class="futturu-pss-field futturu-pss-full-width">
                            <label for="lead_message"><?php _e('Mensagem', 'futturu-prof-site-sim'); ?></label>
                            <textarea id="lead_message" name="lead_message" rows="4" 
                                      placeholder="<?php _e('Gostaria de informações sobre o site para...', 'futturu-prof-site-sim'); ?>"></textarea>
                        </div>
                    </div>
                    
                    <input type="hidden" name="action" value="futturu_pss_submit_lead">
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('futturu_pss_nonce'); ?>">
                    
                    <div class="futturu-pss-actions">
                        <button type="button" class="futturu-pss-btn futturu-pss-btn-prev">
                            ← <?php _e('Voltar', 'futturu-prof-site-sim'); ?>
                        </button>
                        <button type="submit" class="futturu-pss-btn futturu-pss-btn-primary futturu-pss-btn-submit">
                            <?php echo esc_html($atts['cta_text']); ?>
                        </button>
                    </div>
                    
                    <div class="futturu-pss-message futturu-pss-message-success" style="display:none;"></div>
                    <div class="futturu-pss-message futturu-pss-message-error" style="display:none;"></div>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get icon for site type
     */
    private function get_type_icon($slug) {
        $icons = array(
            'professional' => '👔',
            'services' => '🔧',
            'restaurant' => '🍽️',
            'catalog' => '🛒',
            'other' => '⭐'
        );
        return isset($icons[$slug]) ? $icons[$slug] : '📄';
    }
}
