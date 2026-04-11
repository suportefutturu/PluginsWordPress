<?php
/**
 * FCS_Admin Class
 * Handles admin panel functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class FCS_Admin {

    /**
     * Initialize admin hooks
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_admin_assets'));
    }

    /**
     * Add admin menu page
     */
    public static function add_admin_menu() {
        add_options_page(
            __('Simulador Cloud Futturu', 'futturu-cloud-simulator'),
            __('Simulador Cloud Futturu', 'futturu-cloud-simulator'),
            'manage_options',
            'futturu-cloud-simulator',
            array(__CLASS__, 'render_admin_page'),
            'dashicons-cloud',
            30
        );
    }

    /**
     * Enqueue admin assets
     */
    public static function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_futturu-cloud-simulator') {
            return;
        }

        wp_enqueue_style('fcs-admin-css', FCS_PLUGIN_URL . 'assets/css/admin.css', array(), FCS_VERSION);
        wp_enqueue_script('fcs-admin-js', FCS_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), FCS_VERSION, true);
        
        wp_localize_script('fcs-admin-js', 'fcsAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('fcs_admin_nonce'),
            'strings' => array(
                'confirmDelete' => __('Tem certeza que deseja excluir este item?', 'futturu-cloud-simulator'),
                'saveSuccess' => __('Salvo com sucesso!', 'futturu-cloud-simulator'),
                'saveError' => __('Erro ao salvar.', 'futturu-cloud-simulator'),
            )
        ));
    }

    /**
     * Render admin page
     */
    public static function render_admin_page() {
        // Handle form submissions
        if (isset($_POST['fcs_action'])) {
            check_admin_referer('fcs_admin_nonce', 'fcs_nonce');
            
            switch ($_POST['fcs_action']) {
                case 'save_options':
                    self::handle_save_options();
                    break;
                case 'add_plan':
                    self::handle_add_plan();
                    break;
                case 'update_plan':
                    self::handle_update_plan();
                    break;
                case 'delete_plan':
                    self::handle_delete_plan();
                    break;
                case 'add_category':
                    self::handle_add_category();
                    break;
                case 'update_category':
                    self::handle_update_category();
                    break;
                case 'delete_category':
                    self::handle_delete_category();
                    break;
            }
        }

        $options = FCS_Plans::get_options();
        $categories = FCS_Plans::get_categories();
        $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'plans';
        ?>
        <div class="wrap fcs-admin-wrap">
            <h1><?php echo esc_html__('Simulador de Hospedagem na Nuvem Futturu', 'futturu-cloud-simulator'); ?></h1>
            
            <nav class="nav-tab-wrapper">
                <a href="?page=futturu-cloud-simulator&tab=plans" class="nav-tab <?php echo $current_tab === 'plans' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Planos', 'futturu-cloud-simulator'); ?>
                </a>
                <a href="?page=futturu-cloud-simulator&tab=categories" class="nav-tab <?php echo $current_tab === 'categories' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Categorias', 'futturu-cloud-simulator'); ?>
                </a>
                <a href="?page=futturu-cloud-simulator&tab=settings" class="nav-tab <?php echo $current_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Configurações', 'futturu-cloud-simulator'); ?>
                </a>
                <a href="?page=futturu-cloud-simulator&tab=shortcode" class="nav-tab <?php echo $current_tab === 'shortcode' ? 'nav-tab-active' : ''; ?>">
                    <?php esc_html_e('Shortcode', 'futturu-cloud-simulator'); ?>
                </a>
            </nav>

            <div class="tab-content">
                <?php
                switch ($current_tab) {
                    case 'plans':
                        self::render_plans_tab($categories);
                        break;
                    case 'categories':
                        self::render_categories_tab($categories);
                        break;
                    case 'settings':
                        self::render_settings_tab($options);
                        break;
                    case 'shortcode':
                        self::render_shortcode_tab();
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
    private static function render_plans_tab($categories) {
        global $wpdb;
        $plans_table = $wpdb->prefix . FCS_Plans::$table_name;
        ?>
        <h2><?php esc_html_e('Gerenciar Planos', 'futturu-cloud-simulator'); ?></h2>
        
        <button class="button button-primary" onclick="jQuery('#add-plan-modal').show();">
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
                    <th><?php esc_html_e('Visualizações', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Preço (R$)', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Destaque', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Ações', 'futturu-cloud-simulator'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $plans = $wpdb->get_results("SELECT * FROM $plans_table ORDER BY category_id, price ASC");
                if ($plans) {
                    foreach ($plans as $plan) {
                        $category = FCS_Plans::get_category($plan->category_id);
                        ?>
                        <tr>
                            <td><strong><?php echo esc_html($plan->model); ?></strong></td>
                            <td><?php echo esc_html($category ? $category->name : ''); ?></td>
                            <td><?php echo esc_html($plan->ram); ?></td>
                            <td><?php echo esc_html($plan->cpu); ?></td>
                            <td><?php echo esc_html($plan->disk); ?></td>
                            <td><?php echo esc_html($plan->views ? $plan->views : '-'); ?></td>
                            <td>R$ <?php echo number_format($plan->price, 2, ',', '.'); ?></td>
                            <td><?php echo $plan->is_featured ? '✓' : '-'; ?></td>
                            <td>
                                <button class="button button-small" onclick="editPlan(<?php echo esc_attr($plan->id); ?>)">
                                    <?php esc_html_e('Editar', 'futturu-cloud-simulator'); ?>
                                </button>
                                <button class="button button-small button-link-delete" onclick="deletePlan(<?php echo esc_attr($plan->id); ?>)">
                                    <?php esc_html_e('Excluir', 'futturu-cloud-simulator'); ?>
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="9"><?php esc_html_e('Nenhum plano encontrado.', 'futturu-cloud-simulator'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <!-- Add/Edit Plan Modal -->
        <div id="add-plan-modal" class="fcs-modal" style="display:none;">
            <div class="fcs-modal-content">
                <span class="fcs-modal-close" onclick="jQuery('#add-plan-modal').hide();">&times;</span>
                <h3 id="modal-title"><?php esc_html_e('Adicionar Plano', 'futturu-cloud-simulator'); ?></h3>
                <form method="post" id="plan-form">
                    <input type="hidden" name="fcs_nonce" value="<?php echo wp_create_nonce('fcs_admin_nonce'); ?>" />
                    <input type="hidden" name="plan_id" id="plan_id" value="" />
                    <input type="hidden" name="fcs_action" id="plan_action" value="add_plan" />
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="model"><?php esc_html_e('Modelo', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="text" name="model" id="model" class="regular-text" required /></td>
                        </tr>
                        <tr>
                            <th><label for="category_id"><?php esc_html_e('Categoria', 'futturu-cloud-simulator'); ?></label></th>
                            <td>
                                <select name="category_id" id="category_id" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo esc_attr($cat->id); ?>"><?php echo esc_html($cat->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="ram"><?php esc_html_e('RAM', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="text" name="ram" id="ram" class="regular-text" placeholder="ex: 4GB" /></td>
                        </tr>
                        <tr>
                            <th><label for="cpu"><?php esc_html_e('CPU', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="text" name="cpu" id="cpu" class="regular-text" placeholder="ex: 2 Cores" /></td>
                        </tr>
                        <tr>
                            <th><label for="disk"><?php esc_html_e('Disco SSD', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="text" name="disk" id="disk" class="regular-text" placeholder="ex: 60GB SSD" /></td>
                        </tr>
                        <tr>
                            <th><label for="views"><?php esc_html_e('Visualizações/Mês', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="text" name="views" id="views" class="regular-text" placeholder="ex: 50.000" /></td>
                        </tr>
                        <tr>
                            <th><label for="price"><?php esc_html_e('Preço (R$)', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="number" step="0.01" name="price" id="price" class="regular-text" required /></td>
                        </tr>
                        <tr>
                            <th><label for="details"><?php esc_html_e('Detalhes', 'futturu-cloud-simulator'); ?></label></th>
                            <td><textarea name="details" id="details" class="large-text" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="is_featured"><?php esc_html_e('Plano em Destaque', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="checkbox" name="is_featured" id="is_featured" value="1" /></td>
                        </tr>
                        <tr>
                            <th><label for="is_active"><?php esc_html_e('Ativo', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="checkbox" name="is_active" id="is_active" value="1" checked /></td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php esc_html_e('Salvar Plano', 'futturu-cloud-simulator'); ?></button>
                        <button type="button" class="button" onclick="jQuery('#add-plan-modal').hide();"><?php esc_html_e('Cancelar', 'futturu-cloud-simulator'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render categories tab
     */
    private static function render_categories_tab($categories) {
        ?>
        <h2><?php esc_html_e('Gerenciar Categorias', 'futturu-cloud-simulator'); ?></h2>
        
        <button class="button button-primary" onclick="jQuery('#add-category-modal').show();">
            <?php esc_html_e('+ Adicionar Nova Categoria', 'futturu-cloud-simulator'); ?>
        </button>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Nome', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Slug', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Ícone', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Ordem', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Ativa', 'futturu-cloud-simulator'); ?></th>
                    <th><?php esc_html_e('Ações', 'futturu-cloud-simulator'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($categories) {
                    foreach ($categories as $cat) {
                        ?>
                        <tr>
                            <td><strong><?php echo esc_html($cat->name); ?></strong></td>
                            <td><?php echo esc_html($cat->slug); ?></td>
                            <td><?php echo esc_html($cat->icon); ?></td>
                            <td><?php echo esc_html($cat->display_order); ?></td>
                            <td><?php echo $cat->is_active ? '✓' : '-'; ?></td>
                            <td>
                                <button class="button button-small" onclick="editCategory(<?php echo esc_attr($cat->id); ?>)">
                                    <?php esc_html_e('Editar', 'futturu-cloud-simulator'); ?>
                                </button>
                                <button class="button button-small button-link-delete" onclick="deleteCategory(<?php echo esc_attr($cat->id); ?>)">
                                    <?php esc_html_e('Excluir', 'futturu-cloud-simulator'); ?>
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6"><?php esc_html_e('Nenhuma categoria encontrada.', 'futturu-cloud-simulator'); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <!-- Add/Edit Category Modal -->
        <div id="add-category-modal" class="fcs-modal" style="display:none;">
            <div class="fcs-modal-content">
                <span class="fcs-modal-close" onclick="jQuery('#add-category-modal').hide();">&times;</span>
                <h3 id="category-modal-title"><?php esc_html_e('Adicionar Categoria', 'futturu-cloud-simulator'); ?></h3>
                <form method="post" id="category-form">
                    <input type="hidden" name="fcs_nonce" value="<?php echo wp_create_nonce('fcs_admin_nonce'); ?>" />
                    <input type="hidden" name="category_id" id="category_id" value="" />
                    <input type="hidden" name="fcs_action" id="category_action" value="add_category" />
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="cat_name"><?php esc_html_e('Nome', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="text" name="name" id="cat_name" class="regular-text" required /></td>
                        </tr>
                        <tr>
                            <th><label for="cat_slug"><?php esc_html_e('Slug', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="text" name="slug" id="cat_slug" class="regular-text" required /></td>
                        </tr>
                        <tr>
                            <th><label for="cat_icon"><?php esc_html_e('Ícone (emoji)', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="text" name="icon" id="cat_icon" class="regular-text" placeholder="ex: ☁️" /></td>
                        </tr>
                        <tr>
                            <th><label for="cat_description"><?php esc_html_e('Descrição', 'futturu-cloud-simulator'); ?></label></th>
                            <td><textarea name="description" id="cat_description" class="large-text" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="cat_order"><?php esc_html_e('Ordem de Exibição', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="number" name="display_order" id="cat_order" class="small-text" value="0" /></td>
                        </tr>
                        <tr>
                            <th><label for="cat_active"><?php esc_html_e('Ativa', 'futturu-cloud-simulator'); ?></label></th>
                            <td><input type="checkbox" name="is_active" id="cat_active" value="1" checked /></td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php esc_html_e('Salvar Categoria', 'futturu-cloud-simulator'); ?></button>
                        <button type="button" class="button" onclick="jQuery('#add-category-modal').hide();"><?php esc_html_e('Cancelar', 'futturu-cloud-simulator'); ?></button>
                    </p>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Render settings tab
     */
    private static function render_settings_tab($options) {
        ?>
        <h2><?php esc_html_e('Configurações do Simulador', 'futturu-cloud-simulator'); ?></h2>
        
        <form method="post">
            <input type="hidden" name="fcs_nonce" value="<?php echo wp_create_nonce('fcs_admin_nonce'); ?>" />
            <input type="hidden" name="fcs_action" value="save_options" />
            
            <table class="form-table">
                <tr>
                    <th><label for="intro_text"><?php esc_html_e('Texto Introdutório', 'futturu-cloud-simulator'); ?></label></th>
                    <td>
                        <textarea name="intro_text" id="intro_text" class="large-text" rows="4"><?php echo esc_textarea($options['intro_text']); ?></textarea>
                        <p class="description"><?php esc_html_e('Texto exibido no topo do simulador.', 'futturu-cloud-simulator'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="cta_text"><?php esc_html_e('Texto do CTA Global', 'futturu-cloud-simulator'); ?></label></th>
                    <td>
                        <textarea name="cta_text" id="cta_text" class="large-text" rows="2"><?php echo esc_textarea($options['cta_text']); ?></textarea>
                        <p class="description"><?php esc_html_e('Texto exibido após a tabela de planos.', 'futturu-cloud-simulator'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="cta_email"><?php esc_html_e('E-mail para Contato', 'futturu-cloud-simulator'); ?></label></th>
                    <td>
                        <input type="email" name="cta_email" id="cta_email" class="regular-text" value="<?php echo esc_attr($options['cta_email']); ?>" />
                        <p class="description"><?php esc_html_e('E-mail que receberá as solicitações de cotação.', 'futturu-cloud-simulator'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="plugin_active"><?php esc_html_e('Plugin Ativo', 'futturu-cloud-simulator'); ?></label></th>
                    <td>
                        <input type="checkbox" name="plugin_active" id="plugin_active" value="1" <?php checked($options['plugin_active'], true); ?> />
                        <p class="description"><?php esc_html_e('Desmarque para desativar o shortcode.', 'futturu-cloud-simulator'); ?></p>
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
     * Render shortcode tab
     */
    private static function render_shortcode_tab() {
        ?>
        <h2><?php esc_html_e('Como Usar o Shortcode', 'futturu-cloud-simulator'); ?></h2>
        
        <div class="card" style="max-width: 600px;">
            <h3><?php esc_html_e('Shortcode Principal', 'futturu-cloud-simulator'); ?></h3>
            <p><?php esc_html_e('Para exibir o simulador em qualquer página ou post do WordPress, use o seguinte shortcode:', 'futturu-cloud-simulator'); ?></p>
            <code style="display: block; padding: 15px; background: #f0f0f1; font-size: 16px; margin: 10px 0;">[futturu_cloud_simulator]</code>
            
            <h3><?php esc_html_e('Exemplo de Uso', 'futturu-cloud-simulator'); ?></h3>
            <ol>
                <li><?php esc_html_e('Crie uma nova página no WordPress', 'futturu-cloud-simulator'); ?></li>
                <li><?php esc_html_e('Adicione um bloco de Shortcode', 'futturu-cloud-simulator'); ?></li>
                <li><?php esc_html_e('Cole o shortcode acima', 'futturu-cloud-simulator'); ?></li>
                <li><?php esc_html_e('Publique a página', 'futturu-cloud-simulator'); ?></li>
            </ol>
            
            <h3><?php esc_html_e('Benefícios da Hospedagem Futturu', 'futturu-cloud-simulator'); ?></h3>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><?php esc_html_e('Hospedagem Gerenciada', 'futturu-cloud-simulator'); ?></li>
                <li><?php esc_html_e('CDN e Otimizações', 'futturu-cloud-simulator'); ?></li>
                <li><?php esc_html_e('Backups Automáticos', 'futturu-cloud-simulator'); ?></li>
                <li><?php esc_html_e('Monitoramento 24/7', 'futturu-cloud-simulator'); ?></li>
                <li><?php esc_html_e('Certificado SSL Grátis', 'futturu-cloud-simulator'); ?></li>
                <li><?php esc_html_e('Suporte Técnico Especializado', 'futturu-cloud-simulator'); ?></li>
            </ul>
        </div>
        <?php
    }

    /**
     * Handle save options
     */
    private static function handle_save_options() {
        $options = array(
            'intro_text' => isset($_POST['intro_text']) ? $_POST['intro_text'] : '',
            'cta_text' => isset($_POST['cta_text']) ? $_POST['cta_text'] : '',
            'cta_email' => isset($_POST['cta_email']) ? $_POST['cta_email'] : '',
            'plugin_active' => isset($_POST['plugin_active']) ? true : false
        );
        
        FCS_Plans::update_options($options);
        
        echo '<div class="notice notice-success"><p>' . esc_html__('Configurações salvas com sucesso!', 'futturu-cloud-simulator') . '</p></div>';
    }

    /**
     * Handle add plan
     */
    private static function handle_add_plan() {
        global $wpdb;
        $table = $wpdb->prefix . FCS_Plans::$table_name;
        
        $data = array(
            'model' => sanitize_text_field($_POST['model']),
            'category_id' => intval($_POST['category_id']),
            'ram' => sanitize_text_field($_POST['ram']),
            'cpu' => sanitize_text_field($_POST['cpu']),
            'disk' => sanitize_text_field($_POST['disk']),
            'views' => sanitize_text_field($_POST['views']),
            'price' => floatval($_POST['price']),
            'details' => sanitize_textarea_field($_POST['details']),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        );
        
        $wpdb->insert($table, $data);
        
        echo '<div class="notice notice-success"><p>' . esc_html__('Plano adicionado com sucesso!', 'futturu-cloud-simulator') . '</p></div>';
    }

    /**
     * Handle update plan
     */
    private static function handle_update_plan() {
        $plan_id = intval($_POST['plan_id']);
        
        $data = array(
            'model' => sanitize_text_field($_POST['model']),
            'category_id' => intval($_POST['category_id']),
            'ram' => sanitize_text_field($_POST['ram']),
            'cpu' => sanitize_text_field($_POST['cpu']),
            'disk' => sanitize_text_field($_POST['disk']),
            'views' => sanitize_text_field($_POST['views']),
            'price' => floatval($_POST['price']),
            'details' => sanitize_textarea_field($_POST['details']),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        );
        
        FCS_Plans::update_plan($plan_id, $data);
        
        echo '<div class="notice notice-success"><p>' . esc_html__('Plano atualizado com sucesso!', 'futturu-cloud-simulator') . '</p></div>';
    }

    /**
     * Handle delete plan
     */
    private static function handle_delete_plan() {
        if (isset($_POST['plan_id'])) {
            $plan_id = intval($_POST['plan_id']);
            FCS_Plans::delete_plan($plan_id);
            echo '<div class="notice notice-success"><p>' . esc_html__('Plano excluído com sucesso!', 'futturu-cloud-simulator') . '</p></div>';
        }
    }

    /**
     * Handle add category
     */
    private static function handle_add_category() {
        global $wpdb;
        $table = $wpdb->prefix . FCS_Plans::$categories_table;
        
        $data = array(
            'name' => sanitize_text_field($_POST['name']),
            'slug' => sanitize_title($_POST['slug']),
            'icon' => sanitize_text_field($_POST['icon']),
            'description' => sanitize_textarea_field($_POST['description']),
            'display_order' => intval($_POST['display_order']),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        );
        
        $wpdb->insert($table, $data);
        
        echo '<div class="notice notice-success"><p>' . esc_html__('Categoria adicionada com sucesso!', 'futturu-cloud-simulator') . '</p></div>';
    }

    /**
     * Handle update category
     */
    private static function handle_update_category() {
        $category_id = intval($_POST['category_id']);
        
        $data = array(
            'name' => sanitize_text_field($_POST['name']),
            'slug' => sanitize_title($_POST['slug']),
            'icon' => sanitize_text_field($_POST['icon']),
            'description' => sanitize_textarea_field($_POST['description']),
            'display_order' => intval($_POST['display_order']),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        );
        
        FCS_Plans::update_category($category_id, $data);
        
        echo '<div class="notice notice-success"><p>' . esc_html__('Categoria atualizada com sucesso!', 'futturu-cloud-simulator') . '</p></div>';
    }

    /**
     * Handle delete category
     */
    private static function handle_delete_category() {
        if (isset($_POST['category_id'])) {
            $category_id = intval($_POST['category_id']);
            FCS_Plans::delete_category($category_id);
            echo '<div class="notice notice-success"><p>' . esc_html__('Categoria excluída com sucesso!', 'futturu-cloud-simulator') . '</p></div>';
        }
    }
}
