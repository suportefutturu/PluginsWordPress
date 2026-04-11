<?php
/**
 * Admin class for Futuru Cloud Simulator
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Futuru_Cloud_Simulator_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    public function add_admin_menu() {
        add_options_page(
            __('Simulador Cloud Escalável Futturu', 'futturu-cloud-simulator'),
            __('Simulador Cloud Futturu', 'futturu-cloud-simulator'),
            'manage_options',
            'futturu-cloud-simulator',
            array($this, 'render_admin_page'),
            'dashicons-cloud',
            100
        );
    }
    
    public function register_settings() {
        register_setting('futturu_cloud_group', 'futturu_cloud_plans');
        register_setting('futturu_cloud_group', 'futturu_cloud_profiles');
        register_setting('futturu_cloud_group', 'futturu_cloud_texts');
        register_setting('futturu_cloud_group', 'futturu_cloud_settings');
    }
    
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_futturu-cloud-simulator') {
            return;
        }
        
        wp_enqueue_style(
            'futturu-cloud-simulator-admin',
            FUTTURU_CLOUD_SIM_PLUGIN_URL . 'assets/css/futturu-cloud-simulator.css',
            array(),
            FUTTURU_CLOUD_SIM_VERSION
        );
        
        wp_enqueue_script(
            'futturu-cloud-simulator-admin',
            FUTTURU_CLOUD_SIM_PLUGIN_URL . 'assets/js/futturu-cloud-simulator.js',
            array('jquery'),
            FUTTURU_CLOUD_SIM_VERSION,
            true
        );
    }
    
    public function render_admin_page() {
        // Handle form submissions
        if (isset($_POST['futturu_action']) && check_admin_referer('futturu_cloud_admin_nonce', 'futturu_nonce')) {
            $this->handle_form_submission($_POST);
        }
        
        $plans = get_option('futturu_cloud_plans', array());
        $profiles = get_option('futturu_cloud_profiles', array());
        $texts = get_option('futturu_cloud_texts', array());
        $settings = get_option('futturu_cloud_settings', array());
        
        ?>
        <div class="wrap futturu-cloud-admin">
            <h1><?php echo esc_html__('Simulador de Hospedagem na Nuvem Futturu', 'futturu-cloud-simulator'); ?></h1>
            <p><?php echo esc_html__('Configure os planos, perfis e textos do simulador.', 'futturu-cloud-simulator'); ?></p>
            
            <h2 class="nav-tab-wrapper">
                <a href="#tab-plans" class="nav-tab nav-tab-active"><?php _e('Planos', 'futturu-cloud-simulator'); ?></a>
                <a href="#tab-profiles" class="nav-tab"><?php _e('Perfis de Uso', 'futturu-cloud-simulator'); ?></a>
                <a href="#tab-texts" class="nav-tab"><?php _e('Textos', 'futturu-cloud-simulator'); ?></a>
                <a href="#tab-settings" class="nav-tab"><?php _e('Configurações', 'futturu-cloud-simulator'); ?></a>
                <a href="#tab-shortcode" class="nav-tab"><?php _e('Shortcode', 'futturu-cloud-simulator'); ?></a>
            </h2>
            
            <form method="post" action="" id="futturu-admin-form">
                <?php wp_nonce_field('futturu_cloud_admin_nonce', 'futturu_nonce'); ?>
                
                <!-- Plans Tab -->
                <div id="tab-plans" class="tab-content" style="display: block;">
                    <h3><?php _e('Gerenciar Planos de Hospedagem', 'futturu-cloud-simulator'); ?></h3>
                    <p><?php _e('Configure os planos oferecidos pela Cloudez/Futturu.', 'futturu-cloud-simulator'); ?></p>
                    
                    <div class="futturu-plans-list">
                        <?php if (!empty($plans)) : ?>
                            <table class="wp-list-table widefat fixed striped">
                                <thead>
                                    <tr>
                                        <th><?php _e('Nome', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('RAM', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('CPU', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Disco', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Visualizações/mês', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Sites', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Preço (R$)', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Próximo Plano', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Ações', 'futturu-cloud-simulator'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($plans as $index => $plan) : ?>
                                        <tr data-plan-index="<?php echo esc_attr($index); ?>">
                                            <td><?php echo esc_html($plan['name']); ?></td>
                                            <td><?php echo esc_html($plan['ram']); ?></td>
                                            <td><?php echo esc_html($plan['cpu']); ?></td>
                                            <td><?php echo esc_html($plan['disk']); ?></td>
                                            <td><?php echo esc_html($plan['views']); ?></td>
                                            <td><?php echo esc_html($plan['sites']); ?></td>
                                            <td>R$ <?php echo esc_html(number_format($plan['price'], 2, ',', '.')); ?></td>
                                            <td><?php echo esc_html($plan['next_plan'] ? strtoupper($plan['next_plan']) : '-'); ?></td>
                                            <td>
                                                <button type="button" class="button edit-plan"><?php _e('Editar', 'futturu-cloud-simulator'); ?></button>
                                                <button type="button" class="button delete-plan"><?php _e('Excluir', 'futturu-cloud-simulator'); ?></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p><?php _e('Nenhum plano cadastrado.', 'futturu-cloud-simulator'); ?></p>
                        <?php endif; ?>
                        
                        <button type="button" class="button button-primary" id="add-new-plan">
                            <?php _e('+ Adicionar Novo Plano', 'futturu-cloud-simulator'); ?>
                        </button>
                    </div>
                </div>
                
                <!-- Profiles Tab -->
                <div id="tab-profiles" class="tab-content">
                    <h3><?php _e('Gerenciar Perfis de Uso', 'futturu-cloud-simulator'); ?></h3>
                    <p><?php _e('Defina os perfis de tráfego para recomendação de planos.', 'futturu-cloud-simulator'); ?></p>
                    
                    <div class="futturu-profiles-list">
                        <?php if (!empty($profiles)) : ?>
                            <table class="wp-list-table widefat fixed striped">
                                <thead>
                                    <tr>
                                        <th><?php _e('Perfil', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Min. Visualizações', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Máx. Visualizações', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Plano Recomendado', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Descrição', 'futturu-cloud-simulator'); ?></th>
                                        <th><?php _e('Ações', 'futturu-cloud-simulator'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($profiles as $index => $profile) : ?>
                                        <tr data-profile-index="<?php echo esc_attr($index); ?>">
                                            <td><?php echo esc_html($profile['name']); ?></td>
                                            <td><?php echo esc_html(number_format($profile['views_min'], 0, ',', '.')); ?></td>
                                            <td><?php echo esc_html(number_format($profile['views_max'], 0, ',', '.')); ?></td>
                                            <td><?php echo esc_html(strtoupper($profile['recommended_plan'])); ?></td>
                                            <td><?php echo esc_html($profile['description']); ?></td>
                                            <td>
                                                <button type="button" class="button edit-profile"><?php _e('Editar', 'futturu-cloud-simulator'); ?></button>
                                                <button type="button" class="button delete-profile"><?php _e('Excluir', 'futturu-cloud-simulator'); ?></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p><?php _e('Nenhum perfil cadastrado.', 'futturu-cloud-simulator'); ?></p>
                        <?php endif; ?>
                        
                        <button type="button" class="button button-primary" id="add-new-profile">
                            <?php _e('+ Adicionar Novo Perfil', 'futturu-cloud-simulator'); ?>
                        </button>
                    </div>
                </div>
                
                <!-- Texts Tab -->
                <div id="tab-texts" class="tab-content">
                    <h3><?php _e('Configurar Textos', 'futturu-cloud-simulator'); ?></h3>
                    <p><?php _e('Personalize as mensagens exibidas no simulador.', 'futturu-cloud-simulator'); ?></p>
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="intro_title"><?php _e('Título de Introdução', 'futturu-cloud-simulator'); ?></label></th>
                            <td>
                                <input type="text" name="futturu_cloud_texts[intro_title]" id="intro_title" 
                                       value="<?php echo esc_attr($texts['intro_title'] ?? ''); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="intro_text"><?php _e('Texto de Introdução', 'futturu-cloud-simulator'); ?></label></th>
                            <td>
                                <textarea name="futturu_cloud_texts[intro_text]" id="intro_text" rows="4" class="large-text"><?php echo esc_textarea($texts['intro_text'] ?? ''); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="quiz_question"><?php _e('Pergunta do Quiz', 'futturu-cloud-simulator'); ?></label></th>
                            <td>
                                <input type="text" name="futturu_cloud_texts[quiz_question]" id="quiz_question" 
                                       value="<?php echo esc_attr($texts['quiz_question'] ?? ''); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="cta_main"><?php _e('CTA Principal', 'futturu-cloud-simulator'); ?></label></th>
                            <td>
                                <textarea name="futturu_cloud_texts[cta_main]" id="cta_main" rows="3" class="large-text"><?php echo esc_textarea($texts['cta_main'] ?? ''); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="cta_secondary"><?php _e('CTA Secundário', 'futturu-cloud-simulator'); ?></label></th>
                            <td>
                                <input type="text" name="futturu_cloud_texts[cta_secondary]" id="cta_secondary" 
                                       value="<?php echo esc_attr($texts['cta_secondary'] ?? ''); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th><label for="table_title"><?php _e('Título da Tabela', 'futturu-cloud-simulator'); ?></label></th>
                            <td>
                                <input type="text" name="futturu_cloud_texts[table_title]" id="table_title" 
                                       value="<?php echo esc_attr($texts['table_title'] ?? ''); ?>" class="regular-text">
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Settings Tab -->
                <div id="tab-settings" class="tab-content">
                    <h3><?php _e('Configurações Gerais', 'futturu-cloud-simulator'); ?></h3>
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="cta_email"><?php _e('E-mail para CTA', 'futturu-cloud-simulator'); ?></label></th>
                            <td>
                                <input type="email" name="futturu_cloud_settings[cta_email]" id="cta_email" 
                                       value="<?php echo esc_attr($settings['cta_email'] ?? 'suporte@futturu.com.br'); ?>" class="regular-text">
                                <p class="description"><?php _e('E-mail que receberá as mensagens do formulário.', 'futturu-cloud-simulator'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="plugin_enabled"><?php _e('Ativar Plugin', 'futturu-cloud-simulator'); ?></label></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="futturu_cloud_settings[enabled]" id="plugin_enabled" 
                                           value="1" <?php checked($settings['enabled'] ?? true, true); ?>>
                                    <?php _e('Habilitar shortcode e funcionalidades', 'futturu-cloud-simulator'); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Shortcode Tab -->
                <div id="tab-shortcode" class="tab-content">
                    <h3><?php _e('Como Usar', 'futturu-cloud-simulator'); ?></h3>
                    <p><?php _e('Utilize o shortcode abaixo para inserir o simulador em qualquer página ou post:', 'futturu-cloud-simulator'); ?></p>
                    
                    <div class="futturu-shortcode-box">
                        <code>[futturu_cloud_scalable_simulator]</code>
                    </div>
                    
                    <h4><?php _e('Instruções:', 'futturu-cloud-simulator'); ?></h4>
                    <ol>
                        <li><?php _e('Copie o shortcode acima.', 'futturu-cloud-simulator'); ?></li>
                        <li><?php _e('Cole em qualquer página, post ou widget do WordPress.', 'futturu-cloud-simulator'); ?></li>
                        <li><?php _e('O simulador será exibido automaticamente.', 'futturu-cloud-simulator'); ?></li>
                    </ol>
                </div>
                
                <p class="submit">
                    <button type="submit" name="futturu_action" value="save_all" class="button button-primary button-large">
                        <?php _e('Salvar Todas as Configurações', 'futturu-cloud-simulator'); ?>
                    </button>
                </p>
            </form>
        </div>
        
        <!-- Modal for editing plans -->
        <div id="plan-modal" class="futturu-modal" style="display: none;">
            <div class="futturu-modal-content">
                <span class="futturu-modal-close">&times;</span>
                <h3 id="modal-title"><?php _e('Editar Plano', 'futturu-cloud-simulator'); ?></h3>
                <form id="plan-form">
                    <input type="hidden" id="plan-index" value="">
                    
                    <p>
                        <label><?php _e('ID do Plano:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="text" id="plan-id" class="widefat" required>
                    </p>
                    
                    <p>
                        <label><?php _e('Nome:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="text" id="plan-name" class="widefat" required>
                    </p>
                    
                    <p>
                        <label><?php _e('RAM:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="text" id="plan-ram" class="widefat" placeholder="ex: 1 GB">
                    </p>
                    
                    <p>
                        <label><?php _e('CPU:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="text" id="plan-cpu" class="widefat" placeholder="ex: 1 Core">
                    </p>
                    
                    <p>
                        <label><?php _e('Disco SSD:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="text" id="plan-disk" class="widefat" placeholder="ex: 25 GB SSD">
                    </p>
                    
                    <p>
                        <label><?php _e('Visualizações/mês:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="text" id="plan-views" class="widefat" placeholder="ex: 100.000">
                    </p>
                    
                    <p>
                        <label><?php _e('Sites por Cloud:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="text" id="plan-sites" class="widefat" placeholder="ex: 1-2">
                    </p>
                    
                    <p>
                        <label><?php _e('Preço (R$):', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="number" step="0.01" id="plan-price" class="widefat" required>
                    </p>
                    
                    <p>
                        <label><?php _e('Categoria:', 'futturu-cloud-simulator'); ?></label><br>
                        <select id="plan-category" class="widefat">
                            <option value="inicial"><?php _e('Inicial', 'futturu-cloud-simulator'); ?></option>
                            <option value="crescimento"><?php _e('Crescimento', 'futturu-cloud-simulator'); ?></option>
                            <option value="intermediario"><?php _e('Intermediário', 'futturu-cloud-simulator'); ?></option>
                            <option value="avancado"><?php _e('Avançado', 'futturu-cloud-simulator'); ?></option>
                            <option value="enterprise"><?php _e('Enterprise', 'futturu-cloud-simulator'); ?></option>
                        </select>
                    </p>
                    
                    <p>
                        <label><?php _e('Próximo Plano (Upgrade):', 'futturu-cloud-simulator'); ?></label><br>
                        <select id="plan-next-plan" class="widefat">
                            <option value=""><?php _e('Nenhum (último plano)', 'futturu-cloud-simulator'); ?></option>
                            <option value="br1g">BR1G</option>
                            <option value="br2g">BR2G</option>
                            <option value="br4g">BR4G</option>
                            <option value="br8g">BR8G</option>
                            <option value="br16g">BR16G</option>
                        </select>
                    </p>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php _e('Salvar Plano', 'futturu-cloud-simulator'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        
        <!-- Modal for editing profiles -->
        <div id="profile-modal" class="futturu-modal" style="display: none;">
            <div class="futturu-modal-content">
                <span class="futturu-modal-close">&times;</span>
                <h3 id="profile-modal-title"><?php _e('Editar Perfil', 'futturu-cloud-simulator'); ?></h3>
                <form id="profile-form">
                    <input type="hidden" id="profile-index" value="">
                    
                    <p>
                        <label><?php _e('Nome do Perfil:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="text" id="profile-name" class="widefat" required>
                    </p>
                    
                    <p>
                        <label><?php _e('Visualizações Mínimas:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="number" id="profile-views-min" class="widefat" required>
                    </p>
                    
                    <p>
                        <label><?php _e('Visualizações Máximas:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="number" id="profile-views-max" class="widefat" required>
                    </p>
                    
                    <p>
                        <label><?php _e('Plano Recomendado:', 'futturu-cloud-simulator'); ?></label><br>
                        <select id="profile-recommended-plan" class="widefat">
                            <option value="br1g">BR1G</option>
                            <option value="br2g">BR2G</option>
                            <option value="br4g">BR4G</option>
                            <option value="br8g">BR8G</option>
                            <option value="br16g">BR16G</option>
                        </select>
                    </p>
                    
                    <p>
                        <label><?php _e('Descrição:', 'futturu-cloud-simulator'); ?></label><br>
                        <textarea id="profile-description" class="widefat" rows="3"></textarea>
                    </p>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php _e('Salvar Perfil', 'futturu-cloud-simulator'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }
    
    private function handle_form_submission($post_data) {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        switch ($post_data['futturu_action']) {
            case 'save_all':
                // Sanitize and save texts
                if (isset($post_data['futturu_cloud_texts'])) {
                    $texts = array_map('sanitize_text_field', $post_data['futturu_cloud_texts']);
                    update_option('futturu_cloud_texts', $texts);
                }
                
                // Sanitize and save settings
                if (isset($post_data['futturu_cloud_settings'])) {
                    $settings = array(
                        'cta_email' => sanitize_email($post_data['futturu_cloud_settings']['cta_email']),
                        'enabled' => isset($post_data['futturu_cloud_settings']['enabled']) ? true : false
                    );
                    update_option('futturu_cloud_settings', $settings);
                }
                
                add_settings_error('futturu_cloud_messages', 'futturu_cloud_message', 
                    __('Configurações salvas com sucesso!', 'futturu-cloud-simulator'), 'success');
                break;
        }
    }
}
