<?php
/**
 * FCS_Plans Class
 * Handles plan data management and database operations
 */

if (!defined('ABSPATH')) {
    exit;
}

class FCS_Plans {

    private static $table_name = 'fcs_plans';
    private static $categories_table = 'fcs_categories';

    /**
     * Initialize the class
     */
    public static function init() {
        // Load default data on activation
    }

    /**
     * Create database tables on plugin activation
     */
    public static function activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $plans_table = $wpdb->prefix . self::$table_name;
        $categories_table = $wpdb->prefix . self::$categories_table;

        $sql_plans = "CREATE TABLE $plans_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            model varchar(50) NOT NULL,
            category_id mediumint(9) NOT NULL,
            ram varchar(20) DEFAULT '',
            cpu varchar(20) DEFAULT '',
            disk varchar(20) DEFAULT '',
            views varchar(50) DEFAULT '',
            price decimal(10,2) NOT NULL,
            details text DEFAULT '',
            is_featured tinyint(1) DEFAULT 0,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY category_id (category_id)
        ) $charset_collate;";

        $sql_categories = "CREATE TABLE $categories_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            slug varchar(100) NOT NULL,
            icon varchar(50) DEFAULT '',
            description text DEFAULT '',
            display_order int DEFAULT 0,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY slug (slug)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_plans);
        dbDelta($sql_categories);

        // Insert default categories if none exist
        self::insert_default_categories();
        
        // Insert default plans if none exist
        self::insert_default_plans();

        // Add default options
        add_option('fcs_intro_text', 'Descubra o plano de hospedagem em nuvem ideal para o seu projeto. Escolha entre nossos planos otimizados, seguros e com suporte gerenciado pela Futturu.');
        add_option('fcs_cta_text', 'Pronto para escolher seu plano? Fale com um especialista da Futturu.');
        add_option('fcs_cta_email', 'suporte@futturu.com.br');
        add_option('fcs_plugin_active', true);
    }

    /**
     * Cleanup on plugin deactivation
     */
    public static function deactivate() {
        // We don't delete data on deactivation to preserve user data
    }

    /**
     * Insert default categories
     */
    private static function insert_default_categories() {
        global $wpdb;
        $table = $wpdb->prefix . self::$categories_table;

        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
        if ($count > 0) {
            return;
        }

        $categories = array(
            array(
                'name' => '☁️ Clouds Padrão (Uso Geral)',
                'slug' => 'clouds-padrao',
                'icon' => 'cloud',
                'description' => 'Planos balanceados para uso geral',
                'display_order' => 1
            ),
            array(
                'name' => '🧠 Clouds Focados em RAM',
                'slug' => 'clouds-ram',
                'icon' => 'memory',
                'description' => 'Planos otimizados para aplicações que demandam muita memória',
                'display_order' => 2
            ),
            array(
                'name' => '⚙️ Clouds Focados em CPU',
                'slug' => 'clouds-cpu',
                'icon' => 'cpu',
                'description' => 'Planos otimizados para processamento intensivo',
                'display_order' => 3
            ),
            array(
                'name' => '📧 Clouds para E-mails',
                'slug' => 'clouds-email',
                'icon' => 'email',
                'description' => 'Planos especializados para servidores de e-mail',
                'display_order' => 4
            )
        );

        foreach ($categories as $cat) {
            $wpdb->insert($table, $cat);
        }
    }

    /**
     * Insert default plans
     */
    private static function insert_default_plans() {
        global $wpdb;
        $table = $wpdb->prefix . self::$table_name;

        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
        if ($count > 0) {
            return;
        }

        // Get category IDs
        $cats_table = $wpdb->prefix . self::$categories_table;
        $padrao_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $cats_table WHERE slug = %s", 'clouds-padrao'));
        $ram_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $cats_table WHERE slug = %s", 'clouds-ram'));
        $cpu_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $cats_table WHERE slug = %s", 'clouds-cpu'));
        $email_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $cats_table WHERE slug = %s", 'clouds-email'));

        $plans = array();

        // ☁️ Clouds Padrão (Uso Geral) - Ordenados por preço (maior para menor)
        $padrao_plans = array(
            array('Default USA192G', '192 GB', '32 Cores', '3840 GB', '24.000.000', 18879.00, 'Servidor nos EUA para missões críticas de grande escala'),
            array('Default USA128G', '128 GB', '24 Cores', '2560 GB', '16.000.000', 12629.00, 'Servidor nos EUA para operações enterprise pesadas'),
            array('Default USA64G', '64 GB', '12 Cores', '1280 GB', '6.000.000', 6249.00, 'Servidor nos EUA para grandes operações'),
            array('Default USA32G', '32 GB', '8 Cores', '640 GB', '3.000.000', 3129.00, 'Servidor nos EUA enterprise'),
            array('BR16G', '16 GB', '6 Cores', '320 GB', '1.400.000', 2969.00, 'Servidor no Brasil para sites de alto tráfego'),
            array('Default USA16G', '16 GB', '6 Cores', '320 GB', '1.400.000', 1629.00, 'Servidor nos EUA para alto tráfego'),
            array('BR8G', '8 GB', '4 Cores', '160 GB', '1.000.000', 1589.00, 'Servidor no Brasil para lojas virtuais médias'),
            array('BR4G', '4 GB', '2 Cores', '80 GB', '500.000', 1009.00, 'Servidor no Brasil para e-commerces pequenos', 1),
            array('Default USA8G', '8 GB', '4 Cores', '160 GB', '1.000.000', 799.00, 'Servidor nos EUA para lojas médias'),
            array('BR2G', '2 GB', '1 Core', '50 GB', '300.000', 559.00, 'Servidor no Brasil para sites em crescimento'),
            array('Default USA4G', '4 GB', '2 Cores', '80 GB', '500.000', 439.00, 'Servidor nos EUA para e-commerces'),
            array('BR1G', '1 GB', '1 Core', '25 GB', '100.000', 239.00, 'Servidor no Brasil para sites pequenos'),
            array('Default USA2G', '2 GB', '1 Core', '50 GB', '300.000', 229.00, 'Servidor nos EUA em crescimento'),
            array('BR1G Individual', '1 GB', '1 Core', '25 GB', '100.000 (1 site)', 219.00, 'Servidor no Brasil para site único'),
            array('Default USA1G', '1 GB', '1 Core', '25 GB', '100.000', 119.00, 'Servidor nos EUA para sites pequenos'),
            array('Default USA1G Individual', '1 GB', '1 Core', '25 GB', '100.000 (1 site)', 89.00, 'Servidor nos EUA para site único'),
        );

        foreach ($padrao_plans as $plan) {
            $is_featured = isset($plan[7]) ? 1 : 0;
            $plans[] = array(
                'model' => $plan[0],
                'category_id' => $padrao_id,
                'ram' => $plan[1],
                'cpu' => $plan[2],
                'disk' => $plan[3],
                'views' => $plan[4],
                'price' => $plan[5],
                'details' => $plan[6],
                'is_featured' => $is_featured
            );
        }

        // 🧠 Clouds Focados em Memória RAM - Ordenados por preço (maior para menor)
        $ram_plans = array(
            array('Default USAElite RAM', '300 GB', '16 Cores', '340 GB', 17579.00, 'Memória máxima para bancos de dados e aplicações pesadas enterprise'),
            array('Default USAUltra RAM', '150 GB', '8 Cores', '200 GB', 8759.00, 'Memória extrema para virtualização e big data'),
            array('Default USAHyper RAM', '90 GB', '4 Cores', '90 GB', 4739.00, 'Alta memória para aplicações especializadas'),
            array('Default USASuper RAM', '48 GB', '2 Cores', '40 GB', 2409.00, 'Memória elevada para workloads específicos'),
            array('Default USAMax RAM', '24 GB', '1 Core', '20 GB', 1299.00, 'Foco em memória para aplicações que demandam RAM'),
        );

        foreach ($ram_plans as $plan) {
            $plans[] = array(
                'model' => $plan[0],
                'category_id' => $ram_id,
                'ram' => $plan[1],
                'cpu' => $plan[2],
                'disk' => $plan[3],
                'views' => '',
                'price' => $plan[4],
                'details' => $plan[5],
                'is_featured' => ($plan[0] == 'Default USAHyper RAM') ? 1 : 0
            );
        }

        // ⚙️ Clouds Focados em Processamento (CPU) - Ordenados por preço (maior para menor)
        $cpu_plans = array(
            array('Default USAPrestige CPU', '48 Cores', '96 GB', '1920 GB', 13149.00, 'Processamento máximo para renderização e cálculos intensivos'),
            array('Default USAElite CPU', '32 Cores', '64 GB', '1280 GB', 8879.00, 'Potência extrema para servidores de aplicação pesados'),
            array('Default USAUltra CPU', '16 Cores', '32 GB', '640 GB', 4499.00, 'Alto processamento para workloads exigentes'),
            array('Default USAHyper CPU', '8 Cores', '16 GB', '320 GB', 2249.00, 'Processamento elevado para aplicações multi-thread'),
            array('Default USASuper CPU', '4 Cores', '8 GB', '160 GB', 1199.00, 'Bom processamento para tarefas moderadas'),
            array('Default USAMax CPU', '2 Cores', '4 GB', '80 GB', 649.00, 'Entrada focada em processamento'),
        );

        foreach ($cpu_plans as $plan) {
            $plans[] = array(
                'model' => $plan[0],
                'category_id' => $cpu_id,
                'ram' => $plan[2],
                'cpu' => $plan[1],
                'disk' => $plan[3],
                'views' => '',
                'price' => $plan[4],
                'details' => $plan[5],
                'is_featured' => ($plan[0] == 'Default USAUltra CPU') ? 1 : 0
            );
        }

        // 📧 Clouds para E-mails - Ordenados por disco (maior para menor)
        $email_plans = array(
            array('Default USAEmail 1000G', '1000 GB', '4 GB', '2 Cores', 'Pequenas Empresas', 2049.00, 'Servidor de e-mail enterprise com máximo armazenamento'),
            array('Default USAEmail 900G', '900 GB', '4 GB', '2 Cores', 'Pequenas Empresas', 1629.00, 'Servidor de e-mail com grande capacidade'),
            array('Default USAEmail 800G', '800 GB', '4 GB', '2 Cores', 'Pequenas Empresas', 1519.00, 'Servidor de e-mail para operações consolidadas'),
            array('Default USAEmail 700G', '700 GB', '4 GB', '2 Cores', 'Pequenas Empresas', 1419.00, 'Servidor de e-mail para equipes maiores'),
            array('Default USAEmail 600G', '600 GB', '2 GB', '1 Core', 'Pequenas Empresas', 869.00, 'Servidor de e-mail para empresas em expansão'),
            array('Default USAEmail 500G', '500 GB', '2 GB', '1 Core', 'Pequenas Empresas', 549.00, 'Servidor de e-mail para médias empresas', 1),
            array('Default USAEmail 400G', '400 GB', '2 GB', '1 Core', 'Pequenas Empresas', 489.00, 'Servidor de e-mail para equipes médias'),
            array('Default USAEmail 300G', '300 GB', '1 GB', '1 Core', 'Pequenas Empresas', 359.00, 'Servidor de e-mail para pequenas operações'),
            array('Default USAEmail 200G', '200 GB', '1 GB', '1 Core', 'Pequenas Empresas', 289.00, 'Servidor de e-mail básico'),
            array('Default USAEmail 100G', '100 GB', '1 GB', '1 Core', 'Pequenas Empresas', 209.00, 'Servidor de e-mail inicial'),
            array('Default USAEmail 60G', '60 GB', '1 GB', '1 Core', 'Pequenas Empresas', 149.00, 'Servidor de e-mail compacto'),
            array('Default USAEmail 20G', '20 GB', '1 GB', '1 Core', 'Pequenas Empresas', 129.00, 'Servidor de e-mail essencial'),
        );

        foreach ($email_plans as $plan) {
            $is_featured = isset($plan[6]) ? 1 : 0;
            $plans[] = array(
                'model' => $plan[0],
                'category_id' => $email_id,
                'ram' => $plan[2],
                'cpu' => $plan[3],
                'disk' => $plan[1],
                'views' => '',
                'price' => $plan[5],
                'details' => $plan[6],
                'is_featured' => $is_featured
            );
        }

        // Insert all plans
        foreach ($plans as $plan) {
            $wpdb->insert($table, $plan);
        }
    }

    /**
     * Get all active categories
     */
    public static function get_categories() {
        global $wpdb;
        $table = $wpdb->prefix . self::$categories_table;
        
        return $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table WHERE is_active = 1 ORDER BY display_order ASC")
        );
    }

    /**
     * Get plans by category (ordered by price DESC - highest to lowest)
     */
    public static function get_plans_by_category($category_id) {
        global $wpdb;
        $table = $wpdb->prefix . self::$table_name;
        
        return $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table WHERE category_id = %d AND is_active = 1 ORDER BY price DESC", $category_id)
        );
    }

    /**
     * Get a single plan by ID
     */
    public static function get_plan($id) {
        global $wpdb;
        $table = $wpdb->prefix . self::$table_name;
        
        return $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id)
        );
    }

    /**
     * Get a single category by ID
     */
    public static function get_category($id) {
        global $wpdb;
        $table = $wpdb->prefix . self::$categories_table;
        
        return $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id)
        );
    }

    /**
     * Update plan
     */
    public static function update_plan($id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . self::$table_name;
        
        $data['updated_at'] = current_time('mysql');
        
        return $wpdb->update($table, $data, array('id' => $id));
    }

    /**
     * Update category
     */
    public static function update_category($id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . self::$categories_table;
        
        return $wpdb->update($table, $data, array('id' => $id));
    }

    /**
     * Delete plan
     */
    public static function delete_plan($id) {
        global $wpdb;
        $table = $wpdb->prefix . self::$table_name;
        
        return $wpdb->delete($table, array('id' => $id));
    }

    /**
     * Delete category
     */
    public static function delete_category($id) {
        global $wpdb;
        $table = $wpdb->prefix . self::$categories_table;
        
        return $wpdb->delete($table, array('id' => $id));
    }

    /**
     * Get plugin options
     */
    public static function get_options() {
        return array(
            'intro_text' => get_option('fcs_intro_text'),
            'cta_text' => get_option('fcs_cta_text'),
            'cta_email' => get_option('fcs_cta_email'),
            'plugin_active' => get_option('fcs_plugin_active', true)
        );
    }

    /**
     * Update plugin options
     */
    public static function update_options($options) {
        update_option('fcs_intro_text', sanitize_textarea_field($options['intro_text']));
        update_option('fcs_cta_text', sanitize_textarea_field($options['cta_text']));
        update_option('fcs_cta_email', sanitize_email($options['cta_email']));
        update_option('fcs_plugin_active', isset($options['plugin_active']) ? true : false);
    }

    /**
     * Get benefits list
     */
    public static function get_benefits() {
        return array(
            array('icon' => 'dashboard', 'title' => 'Hospedagem Gerenciada', 'desc' => 'Gerenciamos tudo para você'),
            array('icon' => 'speed', 'title' => 'CDN e Otimizações', 'desc' => 'Performance máxima'),
            array('icon' => 'backup', 'title' => 'Backups Automáticos', 'desc' => 'Seus dados sempre seguros'),
            array('icon' => 'visibility', 'title' => 'Monitoramento 24/7', 'desc' => 'Sempre online'),
            array('icon' => 'security', 'title' => 'Certificado SSL Grátis', 'desc' => 'Segurança incluída'),
            array('icon' => 'support', 'title' => 'Suporte Técnico Especializado', 'desc' => 'Equipe pronta para ajudar'),
        );
    }
}
