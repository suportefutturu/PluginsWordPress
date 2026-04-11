<?php
/**
 * Class Futuru_Cloud_Admin
 * Handles admin panel functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futuru_Cloud_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_ajax_futturu_cloud_save_plan', array($this, 'ajax_save_plan'));
        add_action('wp_ajax_futturu_cloud_delete_plan', array($this, 'ajax_delete_plan'));
        add_action('wp_ajax_futturu_cloud_save_settings', array($this, 'ajax_save_settings'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Simulador Cloud Futturu', 'futturu-cloud-simulator'),
            __('Simulador Cloud Futturu', 'futturu-cloud-simulator'),
            'manage_options',
            'futturu-cloud-simulator',
            array($this, 'render_admin_page'),
            'dashicons-cloud',
            30
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_futturu-cloud-simulator') {
            return;
        }
        
        wp_enqueue_style(
            'futturu-cloud-admin-css',
            FUTTURU_CLOUD_SIMULATOR_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            FUTTURU_CLOUD_SIMULATOR_VERSION
        );
        
        wp_enqueue_script(
            'futturu-cloud-admin-js',
            FUTTURU_CLOUD_SIMULATOR_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            FUTTURU_CLOUD_SIMULATOR_VERSION,
            true
        );
        
        wp_localize_script('futturu-cloud-admin-js', 'futturuCloudAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_cloud_admin_nonce')
        ));
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'plans';
        ?>
        <div class="wrap futturu-cloud-admin">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <nav class="nav-tab-wrapper">
                <a href="?page=futturu-cloud-simulator&tab=plans" class="nav-tab <?php echo $active_tab === 'plans' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Planos', 'futturu-cloud-simulator'); ?>
                </a>
                <a href="?page=futturu-cloud-simulator&tab=categories" class="nav-tab <?php echo $active_tab === 'categories' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Categorias', 'futturu-cloud-simulator'); ?>
                </a>
                <a href="?page=futturu-cloud-simulator&tab=features" class="nav-tab <?php echo $active_tab === 'features' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Funcionalidades', 'futturu-cloud-simulator'); ?>
                </a>
                <a href="?page=futturu-cloud-simulator&tab=settings" class="nav-tab <?php echo $active_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Configurações', 'futturu-cloud-simulator'); ?>
                </a>
                <a href="?page=futturu-cloud-simulator&tab=import" class="nav-tab <?php echo $active_tab === 'import' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Importar/Exportar', 'futturu-cloud-simulator'); ?>
                </a>
            </nav>
            
            <div class="tab-content">
                <?php
                switch ($active_tab) {
                    case 'plans':
                        $this->render_plans_tab();
                        break;
                    case 'categories':
                        $this->render_categories_tab();
                        break;
                    case 'features':
                        $this->render_features_tab();
                        break;
                    case 'settings':
                        $this->render_settings_tab();
                        break;
                    case 'import':
                        $this->render_import_tab();
                        break;
                }
                ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render plans tab
     */
    private function render_plans_tab() {
        $plans = Futuru_Cloud_Data::get_plans();
        $categories = Futuru_Cloud_Data::get_categories();
        $features = Futuru_Cloud_Data::get_features();
        
        $category_map = array();
        foreach ($categories as $cat) {
            $category_map[$cat['slug']] = $cat['name'];
        }
        ?>
        <h2><?php esc_html_e('Gerenciar Planos', 'futturu-cloud-simulator'); ?></h2>
        
        <button class="button button-primary" id="futturu-add-plan">
            <?php esc_html_e('+ Adicionar Novo Plano', 'futturu-cloud-simulator'); ?>
        </button>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Modelo', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Categoria', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('RAM', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('CPU', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Disco', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Visualizações/mês', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Preço (R$)', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Ações', 'futturu-cloud-simulator'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan): ?>
                <tr data-plan-id="<?php echo esc_attr($plan['id']); ?>">
                    <td><?php echo esc_html($plan['modelo']); ?></td>
                    <td><?php echo isset($category_map[$plan['categoria']]) ? esc_html($category_map[$plan['categoria']]) : esc_html($plan['categoria']); ?></td>
                    <td><?php echo esc_html($plan['ram']); ?> GB</td>
                    <td><?php echo esc_html($plan['cpu']); ?> <?php echo $plan['cpu'] > 1 ? 'Cores' : 'Core'; ?></td>
                    <td><?php echo esc_html($plan['disco']); ?> GB</td>
                    <td><?php echo $plan['visualizacoes'] ? number_format($plan['visualizacoes'], 0, ',', '.') : 'N/A'; ?></td>
                    <td><?php echo esc_html(number_format($plan['preco'], 2, ',', '.')); ?></td>
                    <td>
                        <button class="button button-small futturu-edit-plan" data-plan='<?php echo esc_attr(json_encode($plan)); ?>'>
                            <?php esc_html_e('Editar', 'futturu-cloud-simulator'); ?>
                        </button>
                        <button class="button button-small button-link-delete futturu-delete-plan" data-plan-id="<?php echo esc_attr($plan['id']); ?>">
                            <?php esc_html_e('Excluir', 'futturu-cloud-simulator'); ?>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Plan Form Modal -->
        <div id="futturu-plan-modal" class="modal" style="display:none;">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3 id="modal-title"><?php esc_html_e('Adicionar/Editar Plano', 'futturu-cloud-simulator'); ?></h3>
                <form id="futturu-plan-form">
                    <input type="hidden" name="action" value="futturu_cloud_save_plan">
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('futturu_cloud_admin_nonce'); ?>">
                    <input type="hidden" name="plan_id" id="plan-id">
                    
                    <p>
                        <label><?php esc_html_e('Nome do Modelo:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="text" name="modelo" id="plan-modelo" required style="width:100%;">
                    </p>
                    
                    <p>
                        <label><?php esc_html_e('Categoria:', 'futturu-cloud-simulator'); ?></label><br>
                        <select name="categoria" id="plan-categoria" required style="width:100%;">
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo esc_attr($cat['slug']); ?>"><?php echo esc_html($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    
                    <p>
                        <label><?php esc_html_e('RAM (GB):', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="number" name="ram" id="plan-ram" required min="0" step="0.5" style="width:100%;">
                    </p>
                    
                    <p>
                        <label><?php esc_html_e('CPU Cores:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="number" name="cpu" id="plan-cpu" required min="1" step="1" style="width:100%;">
                    </p>
                    
                    <p>
                        <label><?php esc_html_e('Disco SSD (GB):', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="number" name="disco" id="plan-disco" required min="0" step="1" style="width:100%;">
                    </p>
                    
                    <p>
                        <label><?php esc_html_e('Visualizações/mês (recomendado):', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="number" name="visualizacoes" id="plan-visualizacoes" min="0" step="1000" style="width:100%;">
                        <small><?php esc_html_e('Deixe em branco para planos de e-mail', 'futturu-cloud-simulator'); ?></small>
                    </p>
                    
                    <p>
                        <label><?php esc_html_e('Sites por Cloud:', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="text" name="sites" id="plan-sites" value="Múltiplos sites" style="width:100%;">
                    </p>
                    
                    <p>
                        <label><?php esc_html_e('Preço Mensal (R$):', 'futturu-cloud-simulator'); ?></label><br>
                        <input type="number" name="preco" id="plan-preco" required min="0" step="0.01" style="width:100%;">
                    </p>
                    
                    <p>
                        <label><?php esc_html_e('Funcionalidades:', 'futturu-cloud-simulator'); ?></label><br>
                        <div class="features-checkboxes">
                            <?php foreach ($features as $feature): ?>
                            <label style="display:block;">
                                <input type="checkbox" name="features[]" value="<?php echo esc_attr($feature['id']); ?>" checked>
                                <?php echo esc_html($feature['name']); ?>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </p>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php esc_html_e('Salvar Plano', 'futturu-cloud-simulator'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render categories tab
     */
    private function render_categories_tab() {
        $categories = Futuru_Cloud_Data::get_categories();
        ?>
        <h2><?php esc_html_e('Gerenciar Categorias', 'futturu-cloud-simulator'); ?></h2>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Nome', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Slug', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Descrição', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Ordem', 'futturu-cloud-simulator'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?php echo esc_html($cat['name']); ?></td>
                    <td><code><?php echo esc_html($cat['slug']); ?></code></td>
                    <td><?php echo esc_html($cat['description']); ?></td>
                    <td><?php echo esc_html($cat['order']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <p class="description">
            <?php esc_html_e('As categorias são pré-definidas. Para modificar, edite diretamente no código ou use a funcionalidade de importação/exportação.', 'futturu-cloud-simulator'); ?>
        </p>
        <?php
    }
    
    /**
     * Render features tab
     */
    private function render_features_tab() {
        $features = Futuru_Cloud_Data::get_features();
        ?>
        <h2><?php esc_html_e('Funcionalidades e Benefícios', 'futturu-cloud-simulator'); ?></h2>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('ID', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Nome', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Descrição', 'futturu-cloud-simulator'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($features as $feature): ?>
                <tr>
                    <td><code><?php echo esc_html($feature['id']); ?></code></td>
                    <td><?php echo esc_html($feature['name']); ?></td>
                    <td><?php echo esc_html($feature['description']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
    }
    
    /**
     * Render settings tab
     */
    private function render_settings_tab() {
        $settings = Futuru_Cloud_Data::get_settings();
        ?>
        <h2><?php esc_html_e('Configurações Gerais', 'futturu-cloud-simulator'); ?></h2>
        
        <form method="post" action="" id="futturu-settings-form">
            <input type="hidden" name="action" value="futturu_cloud_save_settings">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('futturu_cloud_admin_nonce'); ?>">
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="plugin-enabled"><?php esc_html_e('Plugin Ativo', 'futturu-cloud-simulator'); ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="plugin_enabled" id="plugin-enabled" value="1" <?php checked($settings['plugin_enabled'], true); ?>>
                        <label for="plugin-enabled"><?php esc_html_e('Ativar shortcode [futturu_cloud_simulator]', 'futturu-cloud-simulator'); ?></label>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="intro-text"><?php esc_html_e('Texto Introdutório', 'futturu-cloud-simulator'); ?></label>
                    </th>
                    <td>
                        <textarea name="intro_text" id="intro-text" rows="4" style="width:100%;"><?php echo esc_textarea($settings['intro_text']); ?></textarea>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="cta-text"><?php esc_html_e('Texto do CTA', 'futturu-cloud-simulator'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="cta_text" id="cta-text" value="<?php echo esc_attr($settings['cta_text']); ?>" style="width:100%;">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="cta-email"><?php esc_html_e('E-mail de Destino', 'futturu-cloud-simulator'); ?></label>
                    </th>
                    <td>
                        <input type="email" name="cta_email" id="cta-email" value="<?php echo esc_attr($settings['cta_email']); ?>" style="width:100%;">
                        <p class="description"><?php esc_html_e('E-mail que receberá as solicitações de cotação.', 'futturu-cloud-simulator'); ?></p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" class="button button-primary"><?php esc_html_e('Salvar Configurações', 'futturu-cloud-simulator'); ?></button>
            </p>
        </form>
        <?php
    }
    
    /**
     * Render import/export tab
     */
    private function render_import_tab() {
        ?>
        <h2><?php esc_html_e('Importar/Exportar Dados', 'futturu-cloud-simulator'); ?></h2>
        
        <div class="card">
            <h3><?php esc_html_e('Exportar Dados', 'futturu-cloud-simulator'); ?></h3>
            <p><?php esc_html_e('Baixe todos os dados dos planos, categorias e configurações em formato JSON.', 'futturu-cloud-simulator'); ?></p>
            <button class="button button-primary" id="futturu-export-data">
                <?php esc_html_e('Baixar JSON', 'futturu-cloud-simulator'); ?>
            </button>
        </div>
        
        <div class="card">
            <h3><?php esc_html_e('Importar Dados', 'futturu-cloud-simulator'); ?></h3>
            <p><?php esc_html_e('Importe dados de um arquivo JSON. Isso substituirá todas as configurações atuais.', 'futturu-cloud-simulator'); ?></p>
            <form method="post" enctype="multipart/form-data" id="futturu-import-form">
                <input type="file" name="import_file" accept=".json" required>
                <p class="submit">
                    <button type="submit" class="button button-primary" name="futturu_import_data">
                        <?php esc_html_e('Importar', 'futturu-cloud-simulator'); ?>
                    </button>
                </p>
            </form>
            <?php
            if (isset($_POST['futturu_import_data']) && isset($_FILES['import_file'])) {
                $this->handle_import($_FILES['import_file']);
            }
            ?>
        </div>
        <?php
    }
    
    /**
     * Handle file import
     */
    private function handle_import($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo '<div class="notice notice-error"><p>' . esc_html__('Erro no upload do arquivo.', 'futturu-cloud-simulator') . '</p></div>';
            return;
        }
        
        $content = file_get_contents($file['tmp_name']);
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            echo '<div class="notice notice-error"><p>' . esc_html__('Arquivo JSON inválido.', 'futturu-cloud-simulator') . '</p></div>';
            return;
        }
        
        if (isset($data['plans'])) {
            update_option('futturu_cloud_plans', $data['plans']);
        }
        if (isset($data['categories'])) {
            update_option('futturu_cloud_categories', $data['categories']);
        }
        if (isset($data['features'])) {
            update_option('futturu_cloud_features', $data['features']);
        }
        if (isset($data['settings'])) {
            update_option('futturu_cloud_settings', $data['settings']);
        }
        
        echo '<div class="notice notice-success"><p>' . esc_html__('Dados importados com sucesso!', 'futturu-cloud-simulator') . '</p></div>';
    }
    
    /**
     * AJAX: Save plan
     */
    public function ajax_save_plan() {
        check_ajax_referer('futturu_cloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $plan_id = sanitize_text_field($_POST['plan_id']);
        $modelo = sanitize_text_field($_POST['modelo']);
        $categoria = sanitize_text_field($_POST['categoria']);
        $ram = floatval($_POST['ram']);
        $cpu = intval($_POST['cpu']);
        $disco = intval($_POST['disco']);
        $visualizacoes = isset($_POST['visualizacoes']) && $_POST['visualizacoes'] !== '' ? intval($_POST['visualizacoes']) : null;
        $sites = sanitize_text_field($_POST['sites']);
        $preco = floatval($_POST['preco']);
        $features = isset($_POST['features']) ? array_map('sanitize_text_field', $_POST['features']) : array();
        
        $plans = Futuru_Cloud_Data::get_plans();
        
        $new_plan = array(
            'id' => $plan_id ? $plan_id : sanitize_title($modelo),
            'modelo' => $modelo,
            'categoria' => $categoria,
            'ram' => $ram,
            'cpu' => $cpu,
            'disco' => $disco,
            'visualizacoes' => $visualizacoes,
            'sites' => $sites,
            'preco' => $preco,
            'features' => $features
        );
        
        if ($plan_id) {
            // Update existing
            foreach ($plans as &$plan) {
                if ($plan['id'] === $plan_id) {
                    $plan = $new_plan;
                    break;
                }
            }
        } else {
            // Add new
            $plans[] = $new_plan;
        }
        
        update_option('futturu_cloud_plans', $plans);
        wp_send_json_success();
    }
    
    /**
     * AJAX: Delete plan
     */
    public function ajax_delete_plan() {
        check_ajax_referer('futturu_cloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $plan_id = sanitize_text_field($_POST['plan_id']);
        $plans = Futuru_Cloud_Data::get_plans();
        
        $plans = array_filter($plans, function($plan) use ($plan_id) {
            return $plan['id'] !== $plan_id;
        });
        
        update_option('futturu_cloud_plans', array_values($plans));
        wp_send_json_success();
    }
    
    /**
     * AJAX: Save settings
     */
    public function ajax_save_settings() {
        check_ajax_referer('futturu_cloud_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $settings = array(
            'plugin_enabled' => isset($_POST['plugin_enabled']) && $_POST['plugin_enabled'] === '1',
            'intro_text' => sanitize_textarea_field($_POST['intro_text']),
            'cta_text' => sanitize_text_field($_POST['cta_text']),
            'cta_email' => sanitize_email($_POST['cta_email'])
        );
        
        update_option('futturu_cloud_settings', $settings);
        wp_send_json_success();
    }
}
