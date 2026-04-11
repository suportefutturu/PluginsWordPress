<?php
/**
 * Plugin Name: Simulador de Hospedagem na Nuvem Futturu
 * Plugin URI: https://futturu.com.br
 * Description: Simulador de planos de hospedagem em nuvem gerenciada com foco em economia e escalabilidade. Parceria Cloudez.
 * Version: 1.0.0
 * Author: Futturu
 * Author URI: https://futturu.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: futturu-cloud-simulator
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FUTTURU_CLOUD_SIM_VERSION', '1.0.0');
define('FUTTURU_CLOUD_SIM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FUTTURU_CLOUD_SIM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FUTTURU_CLOUD_SIM_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once FUTTURU_CLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-cloud-simulator-admin.php';
require_once FUTTURU_CLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-cloud-simulator-frontend.php';
require_once FUTTURU_CLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-cloud-simulator-ajax.php';

/**
 * Main plugin class
 */
class Futuru_Cloud_Simulator {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        
        // Initialize admin
        if (is_admin()) {
            Futuru_Cloud_Simulator_Admin::get_instance();
        }
        
        // Initialize frontend
        Futuru_Cloud_Simulator_Frontend::get_instance();
        
        // Initialize AJAX handlers
        Futuru_Cloud_Simulator_Ajax::get_instance();
    }
    
    public function activate() {
        // Set default options on activation
        $this->set_default_options();
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('futturu-cloud-simulator', false, dirname(FUTTURU_CLOUD_SIM_PLUGIN_BASENAME) . '/languages');
    }
    
    public function enqueue_assets() {
        wp_enqueue_style(
            'futturu-cloud-simulator-style',
            FUTTURU_CLOUD_SIM_PLUGIN_URL . 'assets/css/futturu-cloud-simulator.css',
            array(),
            FUTTURU_CLOUD_SIM_VERSION
        );
        
        wp_enqueue_script(
            'futturu-cloud-simulator-script',
            FUTTURU_CLOUD_SIM_PLUGIN_URL . 'assets/js/futturu-cloud-simulator.js',
            array('jquery'),
            FUTTURU_CLOUD_SIM_VERSION,
            true
        );
        
        wp_localize_script('futturu-cloud-simulator-script', 'futturuCloudSim', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_cloud_sim_nonce'),
            'messages' => array(
                'error' => __('Ocorreu um erro. Por favor, tente novamente.', 'futturu-cloud-simulator'),
                'success' => __('Mensagem enviada com sucesso! Entraremos em contato em breve.', 'futturu-cloud-simulator')
            )
        ));
    }
    
    private function set_default_options() {
        $default_plans = $this->get_default_plans();
        $default_profiles = $this->get_default_profiles();
        $default_texts = $this->get_default_texts();
        
        if (!get_option('futturu_cloud_plans')) {
            update_option('futturu_cloud_plans', $default_plans);
        }
        
        if (!get_option('futturu_cloud_profiles')) {
            update_option('futturu_cloud_profiles', $default_profiles);
        }
        
        if (!get_option('futturu_cloud_texts')) {
            update_option('futturu_cloud_texts', $default_texts);
        }
        
        if (!get_option('futturu_cloud_settings')) {
            update_option('futturu_cloud_settings', array(
                'cta_email' => 'suporte@futturu.com.br',
                'enabled' => true
            ));
        }
    }
    
    private function get_default_plans() {
        return array(
            array(
                'id' => 'br1g',
                'name' => 'BR1G',
                'ram' => '1 GB',
                'cpu' => '1 Core',
                'disk' => '25 GB SSD',
                'views' => '100.000',
                'sites' => '1-2',
                'price' => '239.00',
                'category' => 'inicial',
                'next_plan' => 'br2g',
                'features' => array(
                    'Hospedagem Gerenciada',
                    'CDN Automático',
                    'Backups Diários',
                    'SSL Gratuito',
                    'Monitoramento 24/7',
                    'Redimensionamento com 1 clique'
                )
            ),
            array(
                'id' => 'br2g',
                'name' => 'BR2G',
                'ram' => '2 GB',
                'cpu' => '2 Cores',
                'disk' => '40 GB SSD',
                'views' => '200.000',
                'sites' => '2-4',
                'price' => '479.00',
                'category' => 'crescimento',
                'next_plan' => 'br4g',
                'features' => array(
                    'Todos recursos do BR1G',
                    'Mais RAM e CPU',
                    'Escalabilidade Automática',
                    'Suporte Prioritário'
                )
            ),
            array(
                'id' => 'br4g',
                'name' => 'BR4G',
                'ram' => '4 GB',
                'cpu' => '4 Cores',
                'disk' => '80 GB SSD',
                'views' => '400.000',
                'sites' => '4-8',
                'price' => '1009.00',
                'category' => 'intermediario',
                'next_plan' => 'br8g',
                'features' => array(
                    'Performance Otimizada',
                    'Recursos Dedicados',
                    'Migração Gratuita',
                    'Backup Hourly Disponível'
                )
            ),
            array(
                'id' => 'br8g',
                'name' => 'BR8G',
                'ram' => '8 GB',
                'cpu' => '6 Cores',
                'disk' => '160 GB SSD',
                'views' => '800.000',
                'sites' => '8-15',
                'price' => '1589.00',
                'category' => 'avancado',
                'next_plan' => 'br16g',
                'features' => array(
                    'Alta Performance',
                    'Recursos Expandidos',
                    'SLA Garantido',
                    'Suporte Especializado'
                )
            ),
            array(
                'id' => 'br16g',
                'name' => 'BR16G',
                'ram' => '16 GB',
                'cpu' => '8 Cores',
                'disk' => '320 GB SSD',
                'views' => '1.500.000+',
                'sites' => '15-30',
                'price' => '2899.00',
                'category' => 'enterprise',
                'next_plan' => '',
                'features' => array(
                    'Performance Máxima',
                    'Recursos Enterprise',
                    'Arquitetura Escalável',
                    'Consultoria Dedicada'
                )
            )
        );
    }
    
    private function get_default_profiles() {
        return array(
            array(
                'id' => 'micro',
                'name' => 'Site Novo ou Institucional Simples',
                'views_min' => 0,
                'views_max' => 100000,
                'recommended_plan' => 'br1g',
                'description' => 'Ideal para quem está começando ou tem site institucional básico'
            ),
            array(
                'id' => 'small',
                'name' => 'Pequeno Negócio com Tráfego Moderado',
                'views_min' => 100000,
                'views_max' => 300000,
                'recommended_plan' => 'br2g',
                'description' => 'Para negócios estabelecidos com crescimento constante'
            ),
            array(
                'id' => 'medium',
                'name' => 'Negócio em Crescimento ou E-commerce Leve',
                'views_min' => 300000,
                'views_max' => 500000,
                'recommended_plan' => 'br4g',
                'description' => 'E-commerces em expansão ou sites com tráfego significativo'
            ),
            array(
                'id' => 'large',
                'name' => 'Tráfego Alto ou E-commerce Pesado',
                'views_min' => 500000,
                'views_max' => 999999999,
                'recommended_plan' => 'br8g',
                'description' => 'Sites de alto tráfego e e-commerces consolidados'
            )
        );
    }
    
    private function get_default_texts() {
        return array(
            'intro_title' => 'Descubra como começar com uma hospedagem poderosa e econômica',
            'intro_text' => 'Cresça com tranquilidade e segurança. Nossa parceria com a Cloudez oferece planos escalonáveis, gerenciados automaticamente, para que você se preocupe apenas com o seu negócio.',
            'quiz_question' => 'Quantas visualizações seu site recebe (ou espera receber) por mês?',
            'cta_main' => 'Pronto para começar com a hospedagem certa e crescer com tranquilidade? Fale com um especialista da Futturu.',
            'cta_secondary' => 'Quer ajuda para escolher o plano ideal para começar? Solicite uma consultoria gratuita.',
            'table_title' => 'Planos Econômicos & Escaláveis'
        );
    }
}

// Initialize plugin
function futturu_cloud_simulator_init() {
    return Futuru_Cloud_Simulator::get_instance();
}

futturu_cloud_simulator_init();

// Shortcode registration
add_shortcode('futturu_cloud_scalable_simulator', array('Futuru_Cloud_Simulator_Frontend', 'render_shortcode'));
