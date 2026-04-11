<?php
/**
 * FCS_Frontend Class
 * Handles frontend display of the simulator
 */

if (!defined('ABSPATH')) {
    exit;
}

class FCS_Frontend {

    /**
     * Enqueue frontend assets
     */
    public static function enqueue_assets() {
        wp_enqueue_style('fcs-frontend-css', FCS_PLUGIN_URL . 'assets/css/frontend.css', array(), FCS_VERSION);
        wp_enqueue_script('fcs-frontend-js', FCS_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), FCS_VERSION, true);
        
        wp_localize_script('fcs-frontend-js', 'fcsFrontend', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('fcs_frontend_nonce'),
            'strings' => array(
                'sending' => __('Enviando...', 'futturu-cloud-simulator'),
                'success' => __('Solicitação enviada com sucesso! Entraremos em contato em breve.', 'futturu-cloud-simulator'),
                'error' => __('Erro ao enviar solicitação. Tente novamente.', 'futturu-cloud-simulator'),
                'close' => __('Fechar', 'futturu-cloud-simulator'),
            )
        ));
    }

    /**
     * Render simulator shortcode
     */
    public static function render_simulator($atts) {
        $options = FCS_Plans::get_options();
        
        if (!$options['plugin_active']) {
            return '';
        }
        
        $categories = FCS_Plans::get_categories();
        $benefits = FCS_Plans::get_benefits();
        
        ob_start();
        ?>
        <div class="fcs-simulator-wrapper">
            <!-- Introduction Section -->
            <div class="fcs-intro-section">
                <h2 class="fcs-intro-title"><?php esc_html_e('Simulador de Hospedagem na Nuvem Futturu', 'futturu-cloud-simulator'); ?></h2>
                <p class="fcs-intro-text"><?php echo esc_html($options['intro_text']); ?></p>
                
                <!-- Benefits Grid -->
                <div class="fcs-benefits-grid">
                    <?php foreach ($benefits as $benefit): ?>
                        <div class="fcs-benefit-card">
                            <div class="fcs-benefit-icon">
                                <span class="dashicons dashicons-<?php echo esc_attr($benefit['icon']); ?>"></span>
                            </div>
                            <h4 class="fcs-benefit-title"><?php echo esc_html($benefit['title']); ?></h4>
                            <p class="fcs-benefit-desc"><?php echo esc_html($benefit['desc']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Category Navigation -->
            <div class="fcs-category-nav">
                <?php 
                $first = true;
                foreach ($categories as $category): 
                ?>
                    <button class="fcs-category-tab <?php echo $first ? 'active' : ''; ?>" 
                            data-category="<?php echo esc_attr($category->id); ?>">
                        <span class="fcs-category-icon"><?php echo esc_html($category->icon); ?></span>
                        <span class="fcs-category-name"><?php echo esc_html($category->name); ?></span>
                    </button>
                <?php 
                    $first = false;
                endforeach; 
                ?>
            </div>

            <!-- Plans Tables by Category -->
            <div class="fcs-plans-container">
                <?php foreach ($categories as $category): ?>
                    <div class="fcs-category-content <?php echo $category->id == $categories[0]->id ? 'active' : ''; ?>" 
                         data-category-id="<?php echo esc_attr($category->id); ?>">
                        
                        <h3 class="fcs-category-title">
                            <span><?php echo esc_html($category->icon); ?></span>
                            <?php echo esc_html($category->name); ?>
                        </h3>
                        
                        <?php
                        $plans = FCS_Plans::get_plans_by_category($category->id);
                        
                        if ($plans):
                        ?>
                            <div class="fcs-plans-table-wrapper">
                                <table class="fcs-plans-table">
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Modelo', 'futturu-cloud-simulator'); ?></th>
                                            <th><?php esc_html_e('Recursos', 'futturu-cloud-simulator'); ?></th>
                                            <?php if ($category->slug !== 'clouds-email'): ?>
                                                <th><?php esc_html_e('Visualizações/Mês', 'futturu-cloud-simulator'); ?></th>
                                            <?php endif; ?>
                                            <th><?php esc_html_e('Preço', 'futturu-cloud-simulator'); ?></th>
                                            <th><?php esc_html_e('Detalhes', 'futturu-cloud-simulator'); ?></th>
                                            <th><?php esc_html_e('Ação', 'futturu-cloud-simulator'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($plans as $plan): ?>
                                            <tr class="fcs-plan-row <?php echo $plan->is_featured ? 'featured' : ''; ?>">
                                                <?php if ($plan->is_featured): ?>
                                                    <td colspan="6" class="fcs-featured-badge">
                                                        ⭐ <?php esc_html_e('Mais Contratado', 'futturu-cloud-simulator'); ?>
                                                    </td>
                                                <?php endif; ?>
                                                
                                                <td class="fcs-model">
                                                    <strong><?php echo esc_html($plan->model); ?></strong>
                                                </td>
                                                <td class="fcs-resources">
                                                    <?php
                                                    // Display resources based on category
                                                    if ($category->slug === 'clouds-email') {
                                                        // Email plans: Disk | RAM | CPU
                                                        echo '<div class="fcs-resource-item"><span class="label">' . esc_html__('Disco:', 'futturu-cloud-simulator') . '</span> ' . esc_html($plan->disk) . '</div>';
                                                        echo '<div class="fcs-resource-item"><span class="label">' . esc_html__('RAM:', 'futturu-cloud-simulator') . '</span> ' . esc_html($plan->ram) . '</div>';
                                                        echo '<div class="fcs-resource-item"><span class="label">' . esc_html__('CPU:', 'futturu-cloud-simulator') . '</span> ' . esc_html($plan->cpu) . '</div>';
                                                    } else {
                                                        // Standard plans: RAM | CPU | Disk
                                                        echo '<div class="fcs-resource-item"><span class="label">' . esc_html__('RAM:', 'futturu-cloud-simulator') . '</span> ' . esc_html($plan->ram) . '</div>';
                                                        echo '<div class="fcs-resource-item"><span class="label">' . esc_html__('CPU:', 'futturu-cloud-simulator') . '</span> ' . esc_html($plan->cpu) . '</div>';
                                                        echo '<div class="fcs-resource-item"><span class="label">' . esc_html__('Disco SSD:', 'futturu-cloud-simulator') . '</span> ' . esc_html($plan->disk) . '</div>';
                                                    }
                                                    ?>
                                                </td>
                                                <?php if ($category->slug !== 'clouds-email'): ?>
                                                    <td class="fcs-views">
                                                        <?php echo esc_html($plan->views ? number_format($plan->views, 0, ',', '.') : '-'); ?>
                                                    </td>
                                                <?php endif; ?>
                                                <td class="fcs-price">
                                                    <span class="fcs-price-value">R$ <?php echo number_format($plan->price, 2, ',', '.'); ?></span>
                                                    <span class="fcs-price-period">/mês</span>
                                                </td>
                                                <td class="fcs-details">
                                                    <button class="fcs-info-btn" 
                                                            data-plan="<?php echo esc_attr($plan->model); ?>"
                                                            data-details="<?php echo esc_attr($plan->details); ?>"
                                                            data-ram="<?php echo esc_attr($plan->ram); ?>"
                                                            data-cpu="<?php echo esc_attr($plan->cpu); ?>"
                                                            data-disk="<?php echo esc_attr($plan->disk); ?>"
                                                            data-price="<?php echo esc_attr($plan->price); ?>">
                                                        <span class="dashicons dashicons-info"></span>
                                                        <?php esc_html_e('Mais Info', 'futturu-cloud-simulator'); ?>
                                                    </button>
                                                </td>
                                                <td class="fcs-cta">
                                                    <button class="fcs-cta-btn" 
                                                            data-plan="<?php echo esc_attr($plan->model); ?>"
                                                            data-price="<?php echo esc_attr($plan->price); ?>">
                                                        <?php esc_html_e('Contratar este Plano', 'futturu-cloud-simulator'); ?>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="fcs-no-plans"><?php esc_html_e('Nenhum plano disponível nesta categoria.', 'futturu-cloud-simulator'); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Global CTA Section -->
            <div class="fcs-global-cta">
                <p class="fcs-cta-text"><?php echo esc_html($options['cta_text']); ?></p>
                <button class="fcs-cta-btn-large" onclick="openQuoteModal()">
                    <?php esc_html_e('Fale com um Especialista', 'futturu-cloud-simulator'); ?>
                </button>
            </div>

            <!-- Partnership Badge -->
            <div class="fcs-partnership">
                <p><?php esc_html_e('Planos oferecidos em parceria com', 'futturu-cloud-simulator'); ?> <strong>Cloudez</strong></p>
                <p class="fcs-partnership-desc"><?php esc_html_e('Tecnologia de ponta e confiabilidade para o seu projeto.', 'futturu-cloud-simulator'); ?></p>
            </div>
        </div>

        <!-- Plan Details Modal -->
        <div id="fcs-plan-modal" class="fcs-modal-overlay" style="display:none;">
            <div class="fcs-modal-dialog">
                <button class="fcs-modal-close" onclick="closePlanModal()">&times;</button>
                <h3 id="fcs-modal-plan-name"></h3>
                <div class="fcs-modal-content">
                    <div class="fcs-modal-resources">
                        <div class="fcs-modal-resource">
                            <span class="dashicons dashicons-memory"></span>
                            <strong><?php esc_html_e('RAM:', 'futturu-cloud-simulator'); ?></strong>
                            <span id="fcs-modal-ram"></span>
                        </div>
                        <div class="fcs-modal-resource">
                            <span class="dashicons dashicons-admin-site"></span>
                            <strong><?php esc_html_e('CPU:', 'futturu-cloud-simulator'); ?></strong>
                            <span id="fcs-modal-cpu"></span>
                        </div>
                        <div class="fcs-modal-resource">
                            <span class="dashicons dashicons-database"></span>
                            <strong><?php esc_html_e('Disco SSD:', 'futturu-cloud-simulator'); ?></strong>
                            <span id="fcs-modal-disk"></span>
                        </div>
                    </div>
                    <p id="fcs-modal-description" class="fcs-modal-description"></p>
                    <div class="fcs-modal-price">
                        <span class="fcs-modal-price-label"><?php esc_html_e('Investimento mensal:', 'futturu-cloud-simulator'); ?></span>
                        <span id="fcs-modal-price-value" class="fcs-modal-price-value"></span>
                    </div>
                    <button class="fcs-cta-btn-full" onclick="openQuoteModalFromPlan()">
                        <?php esc_html_e('Solicitar Cotação', 'futturu-cloud-simulator'); ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- Quote Request Modal -->
        <div id="fcs-quote-modal" class="fcs-modal-overlay" style="display:none;">
            <div class="fcs-modal-dialog fcs-quote-dialog">
                <button class="fcs-modal-close" onclick="closeQuoteModal()">&times;</button>
                <h3><?php esc_html_e('Solicitar Cotação', 'futturu-cloud-simulator'); ?></h3>
                <p class="fcs-quote-subtitle"><?php esc_html_e('Preencha o formulário abaixo e nossa equipe entrará em contato.', 'futturu-cloud-simulator'); ?></p>
                
                <form id="fcs-quote-form" method="post">
                    <input type="hidden" name="action" value="fcs_send_quote_request" />
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('fcs_frontend_nonce'); ?>" />
                    <input type="hidden" name="plan_interest" id="quote-plan-interest" value="" />
                    
                    <div class="fcs-form-group">
                        <label for="quote-name"><?php esc_html_e('Nome Completo', 'futturu-cloud-simulator'); ?> *</label>
                        <input type="text" name="name" id="quote-name" required />
                    </div>
                    
                    <div class="fcs-form-group">
                        <label for="quote-email"><?php esc_html_e('E-mail', 'futturu-cloud-simulator'); ?> *</label>
                        <input type="email" name="email" id="quote-email" required />
                    </div>
                    
                    <div class="fcs-form-group">
                        <label for="quote-phone"><?php esc_html_e('Telefone/WhatsApp', 'futturu-cloud-simulator'); ?> *</label>
                        <input type="tel" name="phone" id="quote-phone" required />
                    </div>
                    
                    <div class="fcs-form-group">
                        <label for="quote-company"><?php esc_html_e('Empresa', 'futturu-cloud-simulator'); ?></label>
                        <input type="text" name="company" id="quote-company" />
                    </div>
                    
                    <div class="fcs-form-group">
                        <label for="quote-message"><?php esc_html_e('Mensagem (Opcional)', 'futturu-cloud-simulator'); ?></label>
                        <textarea name="message" id="quote-message" rows="4"></textarea>
                    </div>
                    
                    <div class="fcs-form-submit">
                        <button type="submit" class="fcs-submit-btn">
                            <span class="btn-text"><?php esc_html_e('Enviar Solicitação', 'futturu-cloud-simulator'); ?></span>
                            <span class="btn-loading" style="display:none;"><?php esc_html_e('Enviando...', 'futturu-cloud-simulator'); ?></span>
                        </button>
                    </div>
                    
                    <div id="fcs-form-response" class="fcs-form-response"></div>
                </form>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
}
