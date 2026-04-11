<?php
/**
 * Futturu Cloud Admin Class
 * Handles backend administration panel
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_HospedagemCloud_Admin {
    
    private $menu_slug = 'futturu-hospedagemcloud-simulator';
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_ajax_futturu_hospedagemcloud_save_plans', array($this, 'ajax_save_plans'));
        add_action('wp_ajax_futturu_hospedagemcloud_save_faqs', array($this, 'ajax_save_faqs'));
        add_action('wp_ajax_futturu_hospedagemcloud_save_features', array($this, 'ajax_save_features'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Simulador Cloud Futturu', 'futturu-hospedagemcloud-sim'),
            __('Simulador Cloud Futturu', 'futturu-hospedagemcloud-sim'),
            'manage_options',
            $this->menu_slug,
            array($this, 'render_admin_page'),
            'dashicons-cloud',
            30
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('futturu_hospedagemcloud_group', 'futturu_hospedagemcloud_plans');
        register_setting('futturu_hospedagemcloud_group', 'futturu_hospedagemcloud_faqs');
        register_setting('futturu_hospedagemcloud_group', 'futturu_hospedagemcloud_features');
        register_setting('futturu_hospedagemcloud_group', 'futturu_hospedagemcloud_discount');
        register_setting('futturu_hospedagemcloud_group', 'futturu_hospedagemcloud_cta_email');
        register_setting('futturu_hospedagemcloud_group', 'futturu_hospedagemcloud_intro_text');
        register_setting('futturu_hospedagemcloud_group', 'futturu_hospedagemcloud_cta_text');
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_' . $this->menu_slug) {
            return;
        }
        
        wp_enqueue_style('futturu-hospedagemcloud-admin-css', FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/css/admin.css', array(), FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION);
        wp_enqueue_script('futturu-hospedagemcloud-admin-js', FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION, true);
        
        wp_localize_script('futturu-hospedagemcloud-admin-js', 'futturuCloudAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_hospedagemcloud_admin_nonce')
        ));
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap futturu-hospedagemcloud-admin">
            <h1><?php echo esc_html__('Simulador de Hospedagem na Nuvem Futturu - Configurações', 'futturu-hospedagemcloud-sim'); ?></h1>
            
            <div class="futturu-hospedagemcloud-tabs">
                <button class="tab-button active" data-tab="plans"><?php esc_html_e('Planos', 'futturu-hospedagemcloud-sim'); ?></button>
                <button class="tab-button" data-tab="faqs"><?php esc_html_e('FAQs', 'futturu-hospedagemcloud-sim'); ?></button>
                <button class="tab-button" data-tab="features"><?php esc_html_e('Funcionalidades', 'futturu-hospedagemcloud-sim'); ?></button>
                <button class="tab-button" data-tab="settings"><?php esc_html_e('Configurações Gerais', 'futturu-hospedagemcloud-sim'); ?></button>
                <button class="tab-button" data-tab="shortcode"><?php esc_html_e('Shortcode', 'futturu-hospedagemcloud-sim'); ?></button>
            </div>
            
            <form method="post" action="options.php" id="futturu-hospedagemcloud-form">
                <?php settings_fields('futturu_hospedagemcloud_group'); ?>
                
                <!-- Planos Tab -->
                <div class="tab-content active" id="tab-plans">
                    <h2><?php esc_html_e('Gerenciar Planos de Hospedagem', 'futturu-hospedagemcloud-sim'); ?></h2>
                    <p class="description"><?php esc_html_e('Configure os planos de hospedagem por categoria. Os preços são mensais em R$.', 'futturu-hospedagemcloud-sim'); ?></p>
                    
                    <div id="plans-container">
                        <?php
                        $plans = get_option('futturu_hospedagemcloud_plans', Futturu_HospedagemCloud_Data::get_default_plans());
                        $categories = Futturu_HospedagemCloud_Data::get_categories($plans);
                        
                        foreach ($categories as $category) :
                            // Get plans sorted by price for display
                            $category_plans_sorted = Futturu_HospedagemCloud_Data::get_plans_by_category($category, $plans);
                            // But keep original order for editing (use unsorted)
                            $category_plans = array();
                            foreach ($plans as $plan) {
                                if ($plan['categoria'] === $category) {
                                    $category_plans[] = $plan;
                                }
                            }
                        ?>
                            <div class="category-section">
                                <h3><?php echo esc_html($category); ?></h3>
                                <p class="description" style="margin-bottom: 10px;"><?php esc_html_e('Ordem atual: conforme tabela oficial. No frontend, os planos são exibidos do mais barato para o mais caro.', 'futturu-hospedagemcloud-sim'); ?></p>
                                <table class="wp-list-table widefat fixed striped">
                                    <thead>
                                        <tr>
                                            <th><?php esc_html_e('Modelo', 'futturu-hospedagemcloud-sim'); ?></th>
                                            <th><?php esc_html_e('RAM', 'futturu-hospedagemcloud-sim'); ?></th>
                                            <th><?php esc_html_e('CPU', 'futturu-hospedagemcloud-sim'); ?></th>
                                            <th><?php esc_html_e('Disco SSD', 'futturu-hospedagemcloud-sim'); ?></th>
                                            <th><?php esc_html_e('Visualizações/mês', 'futturu-hospedagemcloud-sim'); ?></th>
                                            <th><?php esc_html_e('Preço (R$)', 'futturu-hospedagemcloud-sim'); ?></th>
                                            <th><?php esc_html_e('Uso Indicado', 'futturu-hospedagemcloud-sim'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($category_plans as $index => $plan) : 
                                            $global_index = array_search($plan, $plans);
                                        ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="futturu_hospedagemcloud_plans[<?php echo $global_index; ?>][modelo]" value="<?php echo esc_attr($plan['modelo']); ?>" class="regular-text" />
                                                </td>
                                                <td>
                                                    <input type="text" name="futturu_hospedagemcloud_plans[<?php echo $global_index; ?>][ram]" value="<?php echo esc_attr($plan['ram']); ?>" class="small-text" />
                                                </td>
                                                <td>
                                                    <input type="text" name="futturu_hospedagemcloud_plans[<?php echo $global_index; ?>][cpu]" value="<?php echo esc_attr($plan['cpu']); ?>" class="small-text" />
                                                </td>
                                                <td>
                                                    <input type="text" name="futturu_hospedagemcloud_plans[<?php echo $global_index; ?>][disco]" value="<?php echo esc_attr($plan['disco']); ?>" class="small-text" />
                                                </td>
                                                <td>
                                                    <input type="text" name="futturu_hospedagemcloud_plans[<?php echo $global_index; ?>][visualizacoes]" value="<?php echo esc_attr($plan['visualizacoes']); ?>" class="small-text" />
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="futturu_hospedagemcloud_plans[<?php echo $global_index; ?>][preco_mensal]" value="<?php echo esc_attr($plan['preco_mensal']); ?>" class="small-text" />
                                                </td>
                                                <td>
                                                    <input type="text" name="futturu_hospedagemcloud_plans[<?php echo $global_index; ?>][uso_indicado]" value="<?php echo esc_attr($plan['uso_indicado']); ?>" class="regular-text" />
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <p class="submit">
                        <button type="submit" name="submit" id="submit" class="button button-primary"><?php esc_html_e('Salvar Planos', 'futturu-hospedagemcloud-sim'); ?></button>
                    </p>
                </div>
                
                <!-- FAQs Tab -->
                <div class="tab-content" id="tab-faqs">
                    <h2><?php esc_html_e('Gerenciar FAQs', 'futturu-hospedagemcloud-sim'); ?></h2>
                    <p class="description"><?php esc_html_e('Configure as perguntas e respostas frequentes.', 'futturu-hospedagemcloud-sim'); ?></p>
                    
                    <div id="faqs-container">
                        <?php
                        $faqs = get_option('futturu_hospedagemcloud_faqs', Futturu_HospedagemCloud_Data::get_default_faqs());
                        foreach ($faqs as $index => $faq) :
                        ?>
                            <div class="faq-item">
                                <label><?php esc_html_e('Pergunta:', 'futturu-hospedagemcloud-sim'); ?></label>
                                <input type="text" name="futturu_hospedagemcloud_faqs[<?php echo $index; ?>][pergunta]" value="<?php echo esc_attr($faq['pergunta']); ?>" class="large-text" />
                                
                                <label><?php esc_html_e('Resposta:', 'futturu-hospedagemcloud-sim'); ?></label>
                                <textarea name="futturu_hospedagemcloud_faqs[<?php echo $index; ?>][resposta]" rows="3" class="large-text"><?php echo esc_textarea($faq['resposta']); ?></textarea>
                                
                                <button type="button" class="button remove-faq"><?php esc_html_e('Remover', 'futturu-hospedagemcloud-sim'); ?></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="button" id="add-faq"><?php esc_html_e('Adicionar FAQ', 'futturu-hospedagemcloud-sim'); ?></button>
                    
                    <p class="submit">
                        <button type="submit" name="submit" id="submit" class="button button-primary"><?php esc_html_e('Salvar FAQs', 'futturu-hospedagemcloud-sim'); ?></button>
                    </p>
                </div>
                
                <!-- Features Tab -->
                <div class="tab-content" id="tab-features">
                    <h2><?php esc_html_e('Gerenciar Funcionalidades e Benefícios', 'futturu-hospedagemcloud-sim'); ?></h2>
                    <p class="description"><?php esc_html_e('Lista de funcionalidades técnicas exibidas nos detalhes dos planos.', 'futturu-hospedagemcloud-sim'); ?></p>
                    
                    <div id="features-container">
                        <?php
                        $features = get_option('futturu_hospedagemcloud_features', Futturu_HospedagemCloud_Data::get_default_features());
                        foreach ($features as $index => $feature) :
                        ?>
                            <div class="feature-item">
                                <input type="text" name="futturu_hospedagemcloud_features[<?php echo $index; ?>]" value="<?php echo esc_attr($feature); ?>" class="large-text" />
                                <button type="button" class="button remove-feature"><?php esc_html_e('Remover', 'futturu-hospedagemcloud-sim'); ?></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <button type="button" class="button" id="add-feature"><?php esc_html_e('Adicionar Funcionalidade', 'futturu-hospedagemcloud-sim'); ?></button>
                    
                    <p class="submit">
                        <button type="submit" name="submit" id="submit" class="button button-primary"><?php esc_html_e('Salvar Funcionalidades', 'futturu-hospedagemcloud-sim'); ?></button>
                    </p>
                </div>
                
                <!-- Settings Tab -->
                <div class="tab-content" id="tab-settings">
                    <h2><?php esc_html_e('Configurações Gerais', 'futturu-hospedagemcloud-sim'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="futturu_hospedagemcloud_discount"><?php esc_html_e('Desconto Anual (%)', 'futturu-hospedagemcloud-sim'); ?></label>
                            </th>
                            <td>
                                <input type="number" name="futturu_hospedagemcloud_discount" id="futturu_hospedagemcloud_discount" value="<?php echo esc_attr(get_option('futturu_hospedagemcloud_discount', 10)); ?>" class="small-text" min="0" max="100" step="1" />
                                <p class="description"><?php esc_html_e('Percentual de desconto para pagamento anual.', 'futturu-hospedagemcloud-sim'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="futturu_hospedagemcloud_cta_email"><?php esc_html_e('E-mail para CTA', 'futturu-hospedagemcloud-sim'); ?></label>
                            </th>
                            <td>
                                <input type="email" name="futturu_hospedagemcloud_cta_email" id="futturu_hospedagemcloud_cta_email" value="<?php echo esc_attr(get_option('futturu_hospedagemcloud_cta_email', 'suporte@futturu.com.br')); ?>" class="regular-text" />
                                <p class="description"><?php esc_html_e('E-mail que receberá as solicitações de cotação.', 'futturu-hospedagemcloud-sim'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="futturu_hospedagemcloud_intro_text"><?php esc_html_e('Texto Introdutório', 'futturu-hospedagemcloud-sim'); ?></label>
                            </th>
                            <td>
                                <textarea name="futturu_hospedagemcloud_intro_text" id="futturu_hospedagemcloud_intro_text" rows="4" class="large-text"><?php echo esc_textarea(get_option('futturu_hospedagemcloud_intro_text', __('Descubra o plano de hospedagem em nuvem ideal para o seu projeto. Escolha o período de pagamento: Economize 10% contratando anualmente ou pague mensalmente com flexibilidade. Nossa parceria com a Cloudez oferece servidores de alto desempenho na nuvem com gerenciamento completo e suporte técnico especializado.', 'futturu-hospedagemcloud-sim'))); ?></textarea>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="futturu_hospedagemcloud_cta_text"><?php esc_html_e('Texto do Botão CTA', 'futturu-hospedagemcloud-sim'); ?></label>
                            </th>
                            <td>
                                <input type="text" name="futturu_hospedagemcloud_cta_text" id="futturu_hospedagemcloud_cta_text" value="<?php echo esc_attr(get_option('futturu_hospedagemcloud_cta_text', __('Solicitar Cotação', 'futturu-hospedagemcloud-sim'))); ?>" class="regular-text" />
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <button type="submit" name="submit" id="submit" class="button button-primary"><?php esc_html_e('Salvar Configurações', 'futturu-hospedagemcloud-sim'); ?></button>
                    </p>
                </div>
                
                <!-- Shortcode Tab -->
                <div class="tab-content" id="tab-shortcode">
                    <h2><?php esc_html_e('Como Usar o Shortcode', 'futturu-hospedagemcloud-sim'); ?></h2>
                    <p><?php esc_html_e('Para exibir o simulador em qualquer página ou post do WordPress, utilize o seguinte shortcode:', 'futturu-hospedagemcloud-sim'); ?></p>
                    
                    <div class="shortcode-box">
                        <code>[futturu_hospedagemcloud_annual_monthly_sim]</code>
                    </div>
                    
                    <h3><?php esc_html_e('Instruções:', 'futturu-hospedagemcloud-sim'); ?></h3>
                    <ol>
                        <li><?php esc_html_e('Copie o shortcode acima.', 'futturu-hospedagemcloud-sim'); ?></li>
                        <li><?php esc_html_e('Cole em qualquer página, post ou widget do WordPress.', 'futturu-hospedagemcloud-sim'); ?></li>
                        <li><?php esc_html_e('Atualize a página para ver o simulador.', 'futturu-hospedagemcloud-sim'); ?></li>
                    </ol>
                </div>
            </form>
        </div>
        <?php
    }
    
    /**
     * AJAX handler for saving plans
     */
    public function ajax_save_plans() {
        check_ajax_referer('futturu_hospedagemcloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error();
        }
        
        $plans = isset($_POST['plans']) ? $_POST['plans'] : array();
        update_option('futturu_hospedagemcloud_plans', $plans);
        
        wp_send_json_success();
    }
    
    /**
     * AJAX handler for saving FAQs
     */
    public function ajax_save_faqs() {
        check_ajax_referer('futturu_hospedagemcloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error();
        }
        
        $faqs = isset($_POST['faqs']) ? $_POST['faqs'] : array();
        update_option('futturu_hospedagemcloud_faqs', $faqs);
        
        wp_send_json_success();
    }
    
    /**
     * AJAX handler for saving features
     */
    public function ajax_save_features() {
        check_ajax_referer('futturu_hospedagemcloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error();
        }
        
        $features = isset($_POST['features']) ? $_POST['features'] : array();
        update_option('futturu_hospedagemcloud_features', $features);
        
        wp_send_json_success();
    }
}
