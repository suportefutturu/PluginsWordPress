<?php
/**
 * Classe Admin do Simulador Futturu Cloud
 * Gerencia o painel administrativo do plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_HospedagemCloud_Admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_futturu_hospedagemcloud_save_plans', array($this, 'ajax_save_plans'));
        add_action('wp_ajax_futturu_hospedagemcloud_reset_plans', array($this, 'ajax_reset_plans'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Adiciona menu no admin
     */
    public function add_admin_menu() {
        add_options_page(
            'Simulador Cloud Futturu',
            'Simulador Cloud Futturu',
            'manage_options',
            'futturu-hospedagemcloud-simulator',
            array($this, 'render_admin_page')
        );
    }

    /**
     * Registra configurações
     */
    public function register_settings() {
        register_setting('futturu_hospedagemcloud_group', 'futturu_hospedagemcloud_settings');
        register_setting('futturu_hospedagemcloud_group', 'futturu_hospedagemcloud_plans');
    }

    /**
     * Carrega assets do admin
     */
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_futturu-hospedagemcloud-simulator') {
            return;
        }

        wp_enqueue_style(
            'futturu-hospedagemcloud-admin-css',
            FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION
        );

        wp_enqueue_script(
            'futturu-hospedagemcloud-admin-js',
            FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION,
            true
        );

        wp_localize_script('futturu-hospedagemcloud-admin-js', 'futturuHospedagemCloudAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_hospedagemcloud_admin_nonce'),
            'strings' => array(
                'confirmReset' => 'Tem certeza que deseja restaurar os planos padrão? Esta ação não pode ser desfeita.',
                'saving' => 'Salvando...',
                'saved' => 'Salvo com sucesso!',
                'error' => 'Erro ao salvar. Tente novamente.'
            )
        ));
    }

    /**
     * Renderiza página admin
     */
    public function render_admin_page() {
        $settings = Futturu_HospedagemCloud_Data::get_settings();
        $plans = Futturu_HospedagemCloud_Data::get_plans();
        $categories = Futturu_HospedagemCloud_Data::get_categories();
        ?>
        <div class="wrap futturu-hospedagemcloud-admin">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <!-- Tabs -->
            <h2 class="nav-tab-wrapper">
                <a href="#tab-plans" class="nav-tab nav-tab-active"><?php esc_html_e('Gerenciar Planos', 'futturu-hospedagemcloud-sim'); ?></a>
                <a href="#tab-settings" class="nav-tab"><?php esc_html_e('Configurações', 'futturu-hospedagemcloud-sim'); ?></a>
            </h2>

            <!-- Tab Planos -->
            <div id="tab-plans" class="tab-content active">
                <h3><?php esc_html_e('Gerenciar Planos de Hospedagem', 'futturu-hospedagemcloud-sim'); ?></h3>
                <p class="description"><?php esc_html_e('Configure os planos de hospedagem por categoria. Os preços são mensais em R$. Ordem atual: conforme tabela oficial. No frontend, os planos são exibidos do mais barato para o mais caro.', 'futturu-hospedagemcloud-sim'); ?></p>

                <form id="futturu-plans-form" method="post">
                    <?php wp_nonce_field('futturu_hospedagemcloud_save_plans', 'futturu_plans_nonce'); ?>
                    
                    <?php foreach ($categories as $cat_key => $cat_name): ?>
                        <div class="category-section">
                            <h4><?php echo esc_html($cat_name); ?></h4>
                            <table class="wp-list-table widefat fixed striped">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Nome Exibido', 'futturu-hospedagemcloud-sim'); ?></th>
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
                                    <?php
                                    $cat_plans = array_filter($plans, function($plan) use ($cat_key) {
                                        return isset($plan['categoria']) && $plan['categoria'] === $cat_key;
                                    });
                                    
                                    foreach ($cat_plans as $index => $plan):
                                        $safe_index = esc_attr($index);
                                    ?>
                                        <tr data-category="<?php echo esc_attr($cat_key); ?>">
                                            <td>
                                                <input type="text" 
                                                       name="plans[<?php echo $safe_index; ?>][nome_exibido]" 
                                                       value="<?php echo esc_attr(isset($plan['nome_exibido']) ? $plan['nome_exibido'] : ''); ?>" 
                                                       class="regular-text"/>
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       name="plans[<?php echo $safe_index; ?>][modelo]" 
                                                       value="<?php echo esc_attr(isset($plan['modelo']) ? $plan['modelo'] : ''); ?>" 
                                                       class="regular-text"/>
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       name="plans[<?php echo $safe_index; ?>][ram]" 
                                                       value="<?php echo esc_attr(isset($plan['ram']) ? $plan['ram'] : ''); ?>" 
                                                       class="small-text"/>
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       name="plans[<?php echo $safe_index; ?>][cpu]" 
                                                       value="<?php echo esc_attr(isset($plan['cpu']) ? $plan['cpu'] : ''); ?>" 
                                                       class="small-text"/>
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       name="plans[<?php echo $safe_index; ?>][disco]" 
                                                       value="<?php echo esc_attr(isset($plan['disco']) ? $plan['disco'] : ''); ?>" 
                                                       class="small-text"/>
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       name="plans[<?php echo $safe_index; ?>][visualizacoes]" 
                                                       value="<?php echo esc_attr(isset($plan['visualizacoes']) ? $plan['visualizacoes'] : ''); ?>" 
                                                       class="regular-text"/>
                                            </td>
                                            <td>
                                                <input type="number" 
                                                       name="plans[<?php echo $safe_index; ?>][preco_mensal]" 
                                                       value="<?php echo esc_attr(isset($plan['preco_mensal']) ? floatval($plan['preco_mensal']) : '0'); ?>" 
                                                       step="0.01" 
                                                       min="0" 
                                                       class="small-text"/>
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       name="plans[<?php echo $safe_index; ?>][uso_indicado]" 
                                                       value="<?php echo esc_attr(isset($plan['uso_indicado']) ? $plan['uso_indicado'] : ''); ?>" 
                                                       class="regular-text"/>
                                                <input type="hidden" 
                                                       name="plans[<?php echo $safe_index; ?>][categoria]" 
                                                       value="<?php echo esc_attr($cat_key); ?>"/>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>

                    <p class="submit">
                        <button type="submit" class="button button-primary" id="save-plans-btn">
                            <?php esc_html_e('Salvar Planos', 'futturu-hospedagemcloud-sim'); ?>
                        </button>
                        <button type="button" class="button" id="reset-plans-btn">
                            <?php esc_html_e('Restaurar Padrão', 'futturu-hospedagemcloud-sim'); ?>
                        </button>
                        <span class="spinner" id="plans-spinner"></span>
                        <span class="notice-message"></span>
                    </p>
                </form>
            </div>

            <!-- Tab Configurações -->
            <div id="tab-settings" class="tab-content">
                <h3><?php esc_html_e('Configurações Gerais', 'futturu-hospedagemcloud-sim'); ?></h3>
                <form method="post" action="options.php">
                    <?php settings_fields('futturu_hospedagemcloud_group'); ?>
                    <?php do_settings_sections('futturu_hospedagemcloud_group'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="desconto_anual"><?php esc_html_e('Desconto Anual (%)', 'futturu-hospedagemcloud-sim'); ?></label>
                            </th>
                            <td>
                                <input type="number" 
                                       name="futturu_hospedagemcloud_settings[desconto_anual]" 
                                       id="desconto_anual" 
                                       value="<?php echo esc_attr($settings['desconto_anual']); ?>" 
                                       class="small-text" 
                                       min="0" 
                                       max="100" 
                                       step="1"/>
                                <p class="description"><?php esc_html_e('Percentual de desconto para planos anuais.', 'futturu-hospedagemcloud-sim'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="email_destino"><?php esc_html_e('E-mail de Destino', 'futturu-hospedagemcloud-sim'); ?></label>
                            </th>
                            <td>
                                <input type="email" 
                                       name="futturu_hospedagemcloud_settings[email_destino]" 
                                       id="email_destino" 
                                       value="<?php echo esc_attr($settings['email_destino']); ?>" 
                                       class="regular-text"/>
                                <p class="description"><?php esc_html_e('E-mail que receberá as cotações.', 'futturu-hospedagemcloud-sim'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="texto_introducao"><?php esc_html_e('Texto Introdutório', 'futturu-hospedagemcloud-sim'); ?></label>
                            </th>
                            <td>
                                <textarea name="futturu_hospedagemcloud_settings[texto_introducao]" 
                                          id="texto_introducao" 
                                          rows="4" 
                                          class="large-text"><?php echo esc_textarea($settings['texto_introducao']); ?></textarea>
                                <p class="description"><?php esc_html_e('Texto exibido no topo do simulador.', 'futturu-hospedagemcloud-sim'); ?></p>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button(__('Salvar Configurações', 'futturu-hospedagemcloud-sim')); ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX: Salvar planos
     */
    public function ajax_save_plans() {
        check_ajax_referer('futturu_hospedagemcloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permissão negada'));
        }

        if (!isset($_POST['plans']) || !is_array($_POST['plans'])) {
            wp_send_json_error(array('message' => 'Dados inválidos'));
        }

        $result = Futturu_HospedagemCloud_Data::save_plans($_POST['plans']);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Planos salvos com sucesso!'));
        } else {
            wp_send_json_error(array('message' => 'Erro ao salvar planos'));
        }
    }

    /**
     * AJAX: Resetar planos
     */
    public function ajax_reset_plans() {
        check_ajax_referer('futturu_hospedagemcloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permissão negada'));
        }

        $result = Futturu_HospedagemCloud_Data::reset_to_defaults();
        
        if ($result) {
            wp_send_json_success(array('message' => 'Planos restaurados para o padrão!'));
        } else {
            wp_send_json_error(array('message' => 'Erro ao restaurar planos'));
        }
    }
}
