<?php
/**
 * Class Futturu_HospedagemCloud_Admin
 * Gerencia o painel administrativo do plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_HospedagemCloud_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_futturu_hospedagemcloud_save_plans', array($this, 'ajax_save_plans'));
        add_action('wp_ajax_futturu_hospedagemcloud_save_faqs', array($this, 'ajax_save_faqs'));
        add_action('wp_ajax_futturu_hospedagemcloud_save_settings', array($this, 'ajax_save_settings'));
    }

    public function add_admin_menu() {
        add_options_page(
            'Simulador Cloud Futturu',
            'Simulador Cloud Futturu',
            'manage_options',
            'futturu-hospedagemcloud-sim',
            array($this, 'render_admin_page'),
            'dashicons-cloud',
            30
        );
    }

    public function enqueue_assets($hook) {
        if ($hook !== 'settings_page_futturu-hospedagemcloud-sim') {
            return;
        }
        wp_enqueue_style('futturu-hospedagemcloud-admin-css', FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/css/admin.css', array(), FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION);
        wp_enqueue_script('futturu-hospedagemcloud-admin-js', FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION, true);
        wp_localize_script('futturu-hospedagemcloud-admin-js', 'futturuHospedagemCloudAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_hospedagemcloud_admin_nonce')
        ));
    }

    public function render_admin_page() {
        $data = new Futturu_HospedagemCloud_Data();
        $categories = $data->get_categories();
        $saved_plans = get_option('futturu_hospedagemcloud_plans');
        $plans = is_array($saved_plans) && !empty($saved_plans) ? $saved_plans : $data->get_default_plans();
        $faqs = $data->get_faqs();
        $features = $data->get_features();
        $settings = $data->get_settings();
        ?>
        <div class="wrap">
            <h1>Simulador de Hospedagem Cloud Futturu</h1>
            
            <div class="fcs-admin-tabs">
                <button class="fcs-tab-btn active" data-tab="plans">Gerenciar Planos</button>
                <button class="fcs-tab-btn" data-tab="faqs">Gerenciar FAQs</button>
                <button class="fcs-tab-btn" data-tab="settings">Configurações</button>
            </div>

            <!-- Tab Planos -->
            <div id="fcs-tab-plans" class="fcs-tab-content active">
                <h2>Gerenciar Planos de Hospedagem</h2>
                <p>Configure os planos de hospedagem por categoria. Os preços são mensais em R$. No frontend, os planos são exibidos do mais barato para o mais caro.</p>
                
                <?php foreach ($categories as $slug => $name): ?>
                <div class="fcs-category-section">
                    <h3><?php echo esc_html($name); ?></h3>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Nome do Plano</th>
                                <th>RAM</th>
                                <th>CPU</th>
                                <th>Disco SSD</th>
                                <th>Visualizações/mês</th>
                                <th>Preço (R$)</th>
                                <th>Uso Indicado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $cat_plans = array_filter($plans, function($p) use ($slug) {
                                return isset($p['categoria']) && $p['categoria'] === $slug;
                            });
                            foreach ($cat_plans as $index => $plan): 
                                $key = array_search($plan, $plans, true);
                            ?>
                            <tr data-plan-index="<?php echo esc_attr($key); ?>">
                                <td><input type="text" name="plan_nome[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($plan['nome']); ?>" class="regular-text"></td>
                                <td><input type="text" name="plan_ram[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($plan['ram']); ?>" class="small-text"></td>
                                <td><input type="text" name="plan_cpu[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($plan['cpu']); ?>" class="small-text"></td>
                                <td><input type="text" name="plan_disco[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($plan['disco']); ?>" class="small-text"></td>
                                <td><input type="text" name="plan_visualizacoes[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($plan['visualizacoes']); ?>" class="small-text"></td>
                                <td><input type="number" step="0.01" name="plan_preco[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($plan['preco_mensal']); ?>" class="small-text"></td>
                                <td><input type="text" name="plan_uso[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($plan['uso_indicado']); ?>" class="regular-text"></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endforeach; ?>
                
                <button type="button" class="button button-primary" id="fcs-save-plans">Salvar Planos</button>
                <span class="spinner" id="fcs-plans-spinner"></span>
                <div id="fcs-plans-message"></div>
            </div>

            <!-- Tab FAQs -->
            <div id="fcs-tab-faqs" class="fcs-tab-content">
                <h2>Gerenciar FAQs</h2>
                <div id="fcs-faqs-container">
                    <?php foreach ($faqs as $index => $faq): ?>
                    <div class="fcs-faq-item">
                        <input type="text" name="faq_pergunta[<?php echo esc_attr($index); ?>]" value="<?php echo esc_attr($faq['pergunta']); ?>" class="regular-text" placeholder="Pergunta">
                        <textarea name="faq_resposta[<?php echo esc_attr($index); ?>]" rows="3" placeholder="Resposta"><?php echo esc_textarea($faq['resposta']); ?></textarea>
                        <button type="button" class="button remove-faq">Remover</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="button" id="fcs-add-faq">Adicionar FAQ</button>
                <button type="button" class="button button-primary" id="fcs-save-faqs">Salvar FAQs</button>
                <span class="spinner" id="fcs-faqs-spinner"></span>
                <div id="fcs-faqs-message"></div>
            </div>

            <!-- Tab Configurações -->
            <div id="fcs-tab-settings" class="fcs-tab-content">
                <h2>Configurações Gerais</h2>
                <table class="form-table">
                    <tr>
                        <th><label for="fcs-discount-rate">Desconto Anual (%)</label></th>
                        <td><input type="number" id="fcs-discount-rate" value="<?php echo esc_attr($settings['discount_rate']); ?>" class="small-text"> %</td>
                    </tr>
                    <tr>
                        <th><label for="fcs-contact-email">E-mail para Cotações</label></th>
                        <td><input type="email" id="fcs-contact-email" value="<?php echo esc_attr($settings['contact_email']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label>Visualização Padrão</label></th>
                        <td>
                            <label><input type="radio" name="fcs-default-view" value="annual" <?php checked($settings['default_view'], 'annual'); ?>> Anual (Recomendado)</label><br>
                            <label><input type="radio" name="fcs-default-view" value="monthly" <?php checked($settings['default_view'], 'monthly'); ?>> Mensal</label>
                        </td>
                    </tr>
                </table>
                <button type="button" class="button button-primary" id="fcs-save-settings">Salvar Configurações</button>
                <span class="spinner" id="fcs-settings-spinner"></span>
                <div id="fcs-settings-message"></div>
            </div>
        </div>
        <?php
    }

    public function ajax_save_plans() {
        check_ajax_referer('futturu_hospedagemcloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $plans = array();
        $keys = isset($_POST['plan_nome']) ? array_keys($_POST['plan_nome']) : array();
        
        foreach ($keys as $key) {
            $plans[] = array(
                'id' => sanitize_title($_POST['plan_nome'][$key]),
                'categoria' => $this->get_plan_category($key),
                'nome' => sanitize_text_field($_POST['plan_nome'][$key]),
                'ram' => sanitize_text_field($_POST['plan_ram'][$key]),
                'cpu' => sanitize_text_field($_POST['plan_cpu'][$key]),
                'disco' => sanitize_text_field($_POST['plan_disco'][$key]),
                'visualizacoes' => sanitize_text_field($_POST['plan_visualizacoes'][$key]),
                'preco_mensal' => floatval($_POST['plan_preco'][$key]),
                'uso_indicado' => sanitize_text_field($_POST['plan_uso'][$key])
            );
        }

        update_option('futturu_hospedagemcloud_plans', $plans);
        wp_send_json_success('Planos salvos com sucesso!');
    }

    private function get_plan_category($index) {
        $data = new Futturu_HospedagemCloud_Data();
        $default_plans = $data->get_default_plans();
        if (isset($default_plans[$index]['categoria'])) {
            return $default_plans[$index]['categoria'];
        }
        return 'padrao';
    }

    public function ajax_save_faqs() {
        check_ajax_referer('futturu_hospedagemcloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $faqs = array();
        $perguntas = isset($_POST['faq_pergunta']) ? $_POST['faq_pergunta'] : array();
        $respostas = isset($_POST['faq_resposta']) ? $_POST['faq_resposta'] : array();
        
        foreach ($perguntas as $index => $pergunta) {
            if (!empty($pergunta) && !empty($respostas[$index])) {
                $faqs[] = array(
                    'pergunta' => sanitize_text_field($pergunta),
                    'resposta' => sanitize_textarea_field($respostas[$index])
                );
            }
        }

        update_option('futturu_hospedagemcloud_faqs', $faqs);
        wp_send_json_success('FAQs salvos com sucesso!');
    }

    public function ajax_save_settings() {
        check_ajax_referer('futturu_hospedagemcloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $settings = array(
            'discount_rate' => intval($_POST['discount_rate']),
            'contact_email' => sanitize_email($_POST['contact_email']),
            'default_view' => sanitize_text_field($_POST['default_view'])
        );

        update_option('futturu_hospedagemcloud_settings', $settings);
        wp_send_json_success('Configurações salvas com sucesso!');
    }
}
