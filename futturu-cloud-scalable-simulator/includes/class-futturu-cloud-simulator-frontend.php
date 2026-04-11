<?php
/**
 * Frontend class for Futuru Cloud Simulator
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Futuru_Cloud_Simulator_Frontend {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Frontend hooks if needed
    }
    
    public static function render_shortcode($atts) {
        $settings = get_option('futturu_cloud_settings', array());
        
        if (empty($settings['enabled'])) {
            return '';
        }
        
        $plans = get_option('futturu_cloud_plans', array());
        $profiles = get_option('futturu_cloud_profiles', array());
        $texts = get_option('futturu_cloud_texts', array());
        
        ob_start();
        ?>
        <div class="futturu-cloud-simulator">
            <!-- Introduction Section -->
            <div class="futturu-intro-section">
                <h2 class="futturu-intro-title"><?php echo esc_html($texts['intro_title'] ?? __('Descubra como começar com uma hospedagem poderosa e econômica', 'futturu-cloud-simulator')); ?></h2>
                <p class="futturu-intro-text"><?php echo esc_html($texts['intro_text'] ?? __('Cresça com tranquilidade e segurança. Nossa parceria com a Cloudez oferece planos escalonáveis, gerenciados automaticamente, para que você se preocupe apenas com o seu negócio.', 'futturu-cloud-simulator')); ?></p>
                
                <div class="futturu-benefits-grid">
                    <div class="futturu-benefit-item">
                        <div class="futturu-benefit-icon">🚀</div>
                        <h4><?php _e('Comece com Planos Acessíveis', 'futturu-cloud-simulator'); ?></h4>
                    </div>
                    <div class="futturu-benefit-item">
                        <div class="futturu-benefit-icon">⚙️</div>
                        <h4><?php _e('Hospedagem Gerenciada', 'futturu-cloud-simulator'); ?></h4>
                    </div>
                    <div class="futturu-benefit-item">
                        <div class="futturu-benefit-icon">⚡</div>
                        <h4><?php _e('CDN e Otimizações Automáticas', 'futturu-cloud-simulator'); ?></h4>
                    </div>
                    <div class="futturu-benefit-item">
                        <div class="futturu-benefit-icon">🔒</div>
                        <h4><?php _e('Backups e Segurança Garantidos', 'futturu-cloud-simulator'); ?></h4>
                    </div>
                    <div class="futturu-benefit-item">
                        <div class="futturu-benefit-icon">📊</div>
                        <h4><?php _e('Monitoramento Proativo', 'futturu-cloud-simulator'); ?></h4>
                    </div>
                    <div class="futturu-benefit-item">
                        <div class="futturu-benefit-icon">📈</div>
                        <h4><?php _e('Escalabilidade Simples', 'futturu-cloud-simulator'); ?></h4>
                    </div>
                    <div class="futturu-benefit-item">
                        <div class="futturu-benefit-icon">❌</div>
                        <h4><?php _e('Evite Hospedagem Compartilhada', 'futturu-cloud-simulator'); ?></h4>
                    </div>
                    <div class="futturu-benefit-item">
                        <div class="futturu-benefit-icon">👨‍💼</div>
                        <h4><?php _e('Suporte Técnico Humano Especializado', 'futturu-cloud-simulator'); ?></h4>
                    </div>
                </div>
            </div>
            
            <!-- Quiz Section -->
            <div class="futturu-quiz-section">
                <h3 class="futturu-quiz-title"><?php echo esc_html($texts['quiz_question'] ?? __('Quantas visualizações seu site recebe (ou espera receber) por mês?', 'futturu-cloud-simulator')); ?></h3>
                
                <div class="futturu-quiz-options">
                    <?php foreach ($profiles as $profile) : ?>
                        <button class="futturu-quiz-option" 
                                data-profile-id="<?php echo esc_attr($profile['id']); ?>"
                                data-recommended-plan="<?php echo esc_attr($profile['recommended_plan']); ?>">
                            <span class="futturu-quiz-option-name"><?php echo esc_html($profile['name']); ?></span>
                            <span class="futturu-quiz-option-views">
                                <?php echo number_format($profile['views_min'], 0, ',', '.'); ?> - 
                                <?php echo $profile['views_max'] >= 999999999 ? '+' : number_format($profile['views_max'], 0, ',', '.'); ?>
                                <?php _e('visualizações/mês', 'futturu-cloud-simulator'); ?>
                            </span>
                        </button>
                    <?php endforeach; ?>
                </div>
                
                <div id="futturu-recommendation" class="futturu-recommendation" style="display: none;">
                    <div class="futturu-recommendation-content">
                        <h4><?php _e('Plano Recomendado para Você:', 'futturu-cloud-simulator'); ?></h4>
                        <div id="recommended-plan-info"></div>
                        <button class="futturu-btn futturu-btn-primary" onclick="futturuScrollToPlans()">
                            <?php _e('Ver Todos os Planos', 'futturu-cloud-simulator'); ?>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Plans Table Section -->
            <div class="futturu-plans-section" id="futturu-plans">
                <h3 class="futturu-plans-title"><?php echo esc_html($texts['table_title'] ?? __('Planos Econômicos & Escaláveis', 'futturu-cloud-simulator')); ?></h3>
                
                <div class="futturu-plans-table-wrapper">
                    <table class="futturu-plans-table">
                        <thead>
                            <tr>
                                <th><?php _e('Modelo', 'futturu-cloud-simulator'); ?></th>
                                <th><?php _e('Recursos', 'futturu-cloud-simulator'); ?></th>
                                <th><?php _e('Visualizações/Mês', 'futturu-cloud-simulator'); ?></th>
                                <th><?php _e('Sites por Cloud', 'futturu-cloud-simulator'); ?></th>
                                <th><?php _e('Preço', 'futturu-cloud-simulator'); ?></th>
                                <th><?php _e('Cresça com', 'futturu-cloud-simulator'); ?></th>
                                <th><?php _e('Ação', 'futturu-cloud-simulator'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Sort plans to show initial plans first
                            usort($plans, function($a, $b) {
                                $category_order = array('inicial' => 1, 'crescimento' => 2, 'intermediario' => 3, 'avancado' => 4, 'enterprise' => 5);
                                $order_a = $category_order[$a['category']] ?? 99;
                                $order_b = $category_order[$b['category']] ?? 99;
                                return $order_a - $order_b;
                            });
                            
                            foreach ($plans as $plan) : 
                                $next_plan = !empty($plan['next_plan']) ? self::get_plan_by_id($plan['next_plan'], $plans) : null;
                            ?>
                                <tr class="futturu-plan-row" data-plan-id="<?php echo esc_attr($plan['id']); ?>">
                                    <td class="futturu-plan-name">
                                        <strong><?php echo esc_html($plan['name']); ?></strong>
                                        <span class="futturu-plan-category"><?php echo esc_html(self::get_category_label($plan['category'])); ?></span>
                                    </td>
                                    <td>
                                        <div class="futturu-plan-resources">
                                            <div><span class="label">RAM:</span> <?php echo esc_html($plan['ram']); ?></div>
                                            <div><span class="label">CPU:</span> <?php echo esc_html($plan['cpu']); ?></div>
                                            <div><span class="label">SSD:</span> <?php echo esc_html($plan['disk']); ?></div>
                                        </div>
                                    </td>
                                    <td><?php echo esc_html($plan['views']); ?></td>
                                    <td><?php echo esc_html($plan['sites']); ?></td>
                                    <td class="futturu-plan-price">
                                        <span class="price-currency">R$</span>
                                        <span class="price-value"><?php echo number_format($plan['price'], 2, ',', '.'); ?></span>
                                        <span class="price-period">/mês</span>
                                    </td>
                                    <td>
                                        <?php if ($next_plan) : ?>
                                            <div class="futturu-upgrade-path">
                                                <span class="upgrade-arrow">→</span>
                                                <strong><?php echo esc_html($next_plan['name']); ?></strong>
                                                <span class="upgrade-price">(R$ <?php echo number_format($next_plan['price'], 2, ',', '.'); ?>)</span>
                                            </div>
                                        <?php else : ?>
                                            <span class="futturu-max-plan"><?php _e('Plano Máximo', 'futturu-cloud-simulator'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="futturu-btn futturu-btn-secondary more-info-btn" 
                                                data-plan-id="<?php echo esc_attr($plan['id']); ?>">
                                            <?php _e('Mais Info', 'futturu-cloud-simulator'); ?>
                                        </button>
                                        <button class="futturu-btn futturu-btn-primary select-plan-btn" 
                                                data-plan-id="<?php echo esc_attr($plan['id']); ?>"
                                                data-plan-name="<?php echo esc_attr($plan['name']); ?>"
                                                data-plan-price="<?php echo esc_attr($plan['price']); ?>">
                                            <?php _e('Contratar este Plano', 'futturu-cloud-simulator'); ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Growth Path Section -->
            <div class="futturu-growth-section">
                <h3><?php _e('Simule Seu Caminho de Crescimento', 'futturu-cloud-simulator'); ?></h3>
                <p><?php _e('Veja como você pode começar pequeno e crescer com segurança:', 'futturu-cloud-simulator'); ?></p>
                
                <div class="futturu-growth-timeline">
                    <div class="futturu-growth-step">
                        <div class="step-month"><?php _e('Mês 0', 'futturu-cloud-simulator'); ?></div>
                        <div class="step-plan">BR1G</div>
                        <div class="step-price">R$ 239/mês</div>
                        <div class="step-desc"><?php _e('Início seguro e econômico', 'futturu-cloud-simulator'); ?></div>
                    </div>
                    <div class="futturu-growth-arrow">→</div>
                    <div class="futturu-growth-step">
                        <div class="step-month"><?php _e('Mês 6', 'futturu-cloud-simulator'); ?></div>
                        <div class="step-plan">BR4G</div>
                        <div class="step-price">R$ 1.009/mês</div>
                        <div class="step-desc"><?php _e('Crescimento consolidado', 'futturu-cloud-simulator'); ?></div>
                    </div>
                    <div class="futturu-growth-arrow">→</div>
                    <div class="futturu-growth-step">
                        <div class="step-month"><?php _e('Mês 12', 'futturu-cloud-simulator'); ?></div>
                        <div class="step-plan">BR8G</div>
                        <div class="step-price">R$ 1.589/mês</div>
                        <div class="step-desc"><?php _e('Alta performance', 'futturu-cloud-simulator'); ?></div>
                    </div>
                </div>
                
                <div class="futturu-comparison-box">
                    <h4><?php _e('Vantagens vs Hospedagem Compartilhada', 'futturu-cloud-simulator'); ?></h4>
                    <ul>
                        <li>✓ <?php _e('Recursos dedicados e garantidos', 'futturu-cloud-simulator'); ?></li>
                        <li>✓ <?php _e('Escalabilidade com 1 clique', 'futturu-cloud-simulator'); ?></li>
                        <li>✓ <?php _e('Sem limitações ocultas', 'futturu-cloud-simulator'); ?></li>
                        <li>✓ <?php _e('Performance consistente', 'futturu-cloud-simulator'); ?></li>
                        <li>✓ <?php _e('Suporte especializado em cloud', 'futturu-cloud-simulator'); ?></li>
                    </ul>
                </div>
            </div>
            
            <!-- CTA Section -->
            <div class="futturu-cta-section">
                <div class="futturu-cta-content">
                    <h3><?php echo esc_html($texts['cta_main'] ?? __('Pronto para começar com a hospedagem certa e crescer com tranquilidade? Fale com um especialista da Futturu.', 'futturu-cloud-simulator')); ?></h3>
                    <button class="futturu-btn futturu-btn-large futturu-btn-primary" onclick="futturuOpenModal()">
                        <?php _e('Solicite uma Cotação', 'futturu-cloud-simulator'); ?>
                    </button>
                </div>
                <div class="futturu-cta-secondary">
                    <p><?php echo esc_html($texts['cta_secondary'] ?? __('Quer ajuda para escolher o plano ideal para começar? Solicite uma consultoria gratuita.', 'futturu-cloud-simulator')); ?></p>
                    <button class="futturu-btn futturu-btn-outline" onclick="futturuOpenModal()">
                        <?php _e('Falar com Especialista', 'futturu-cloud-simulator'); ?>
                    </button>
                </div>
            </div>
            
            <!-- Contact Modal -->
            <div id="futturu-contact-modal" class="futturu-modal-overlay" style="display: none;">
                <div class="futturu-modal-dialog">
                    <button class="futturu-modal-close-btn" onclick="futturuCloseModal()">&times;</button>
                    <h3><?php _e('Solicite uma Cotação', 'futturu-cloud-simulator'); ?></h3>
                    <p><?php _e('Preencha o formulário abaixo e entraremos em contato em breve.', 'futturu-cloud-simulator'); ?></p>
                    
                    <form id="futturu-contact-form" method="post">
                        <input type="hidden" name="action" value="futturu_send_contact">
                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('futturu_cloud_sim_nonce'); ?>">
                        <input type="hidden" id="selected-plan" name="selected_plan" value="">
                        
                        <div class="futturu-form-group">
                            <label for="futturu-name"><?php _e('Nome Completo *', 'futturu-cloud-simulator'); ?></label>
                            <input type="text" id="futturu-name" name="name" required>
                        </div>
                        
                        <div class="futturu-form-group">
                            <label for="futturu-email"><?php _e('E-mail *', 'futturu-cloud-simulator'); ?></label>
                            <input type="email" id="futturu-email" name="email" required>
                        </div>
                        
                        <div class="futturu-form-group">
                            <label for="futturu-phone"><?php _e('Telefone/WhatsApp', 'futturu-cloud-simulator'); ?></label>
                            <input type="tel" id="futturu-phone" name="phone">
                        </div>
                        
                        <div class="futturu-form-group">
                            <label for="futturu-traffic"><?php _e('Perfil de Tráfego', 'futturu-cloud-simulator'); ?></label>
                            <select id="futturu-traffic" name="traffic_profile">
                                <option value=""><?php _e('Selecione...', 'futturu-cloud-simulator'); ?></option>
                                <?php foreach ($profiles as $profile) : ?>
                                    <option value="<?php echo esc_attr($profile['id']); ?>"><?php echo esc_html($profile['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="futturu-form-group">
                            <label for="futturu-message"><?php _e('Mensagem (Opcional)', 'futturu-cloud-simulator'); ?></label>
                            <textarea id="futturu-message" name="message" rows="4"></textarea>
                        </div>
                        
                        <div class="futturu-form-submit">
                            <button type="submit" class="futturu-btn futturu-btn-primary futturu-btn-full">
                                <?php _e('Enviar Solicitação', 'futturu-cloud-simulator'); ?>
                            </button>
                        </div>
                        
                        <div id="futturu-form-message" class="futturu-form-message"></div>
                    </form>
                </div>
            </div>
            
            <!-- Plan Details Modal -->
            <div id="futturu-plan-details-modal" class="futturu-modal-overlay" style="display: none;">
                <div class="futturu-modal-dialog futturu-modal-large">
                    <button class="futturu-modal-close-btn" onclick="futturuClosePlanDetails()">&times;</button>
                    <div id="futturu-plan-details-content"></div>
                </div>
            </div>
        </div>
        
        <script>
        function futturuScrollToPlans() {
            document.getElementById('futturu-plans').scrollIntoView({ behavior: 'smooth' });
        }
        
        function futturuOpenModal(planId, planName) {
            if (planId) {
                document.getElementById('selected-plan').value = planName || '';
            }
            document.getElementById('futturu-contact-modal').style.display = 'flex';
        }
        
        function futturuCloseModal() {
            document.getElementById('futturu-contact-modal').style.display = 'none';
        }
        
        function futturuClosePlanDetails() {
            document.getElementById('futturu-plan-details-modal').style.display = 'none';
        }
        
        // Close modal on outside click
        window.onclick = function(event) {
            var contactModal = document.getElementById('futturu-contact-modal');
            var detailsModal = document.getElementById('futturu-plan-details-modal');
            if (event.target == contactModal) {
                futturuCloseModal();
            }
            if (event.target == detailsModal) {
                futturuClosePlanDetails();
            }
        }
        </script>
        <?php
        return ob_get_clean();
    }
    
    private static function get_plan_by_id($plan_id, $plans) {
        foreach ($plans as $plan) {
            if ($plan['id'] === $plan_id) {
                return $plan;
            }
        }
        return null;
    }
    
    private static function get_category_label($category) {
        $labels = array(
            'inicial' => __('Inicial', 'futturu-cloud-simulator'),
            'crescimento' => __('Crescimento', 'futturu-cloud-simulator'),
            'intermediario' => __('Intermediário', 'futturu-cloud-simulator'),
            'avancado' => __('Avançado', 'futturu-cloud-simulator'),
            'enterprise' => __('Enterprise', 'futturu-cloud-simulator')
        );
        return $labels[$category] ?? $category;
    }
}
