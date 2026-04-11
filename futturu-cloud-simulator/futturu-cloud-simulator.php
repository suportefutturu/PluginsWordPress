<?php
/**
 * Plugin Name: Simulador de Hospedagem na Nuvem Futturu
 * Plugin URI: https://futturu.com.br
 * Description: Simulador de planos de hospedagem em nuvem gerenciada Futturu (parceria Cloudez). Explore, compare e contrate o plano ideal para seu projeto.
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
define('FUTTURU_CLOUD_SIMULATOR_VERSION', '1.0.0');
define('FUTTURU_CLOUD_SIMULATOR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FUTTURU_CLOUD_SIMULATOR_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once FUTTURU_CLOUD_SIMULATOR_PLUGIN_DIR . 'includes/class-futturu-cloud-data.php';
require_once FUTTURU_CLOUD_SIMULATOR_PLUGIN_DIR . 'includes/class-futturu-cloud-admin.php';
require_once FUTTURU_CLOUD_SIMULATOR_PLUGIN_DIR . 'includes/class-futturu-cloud-frontend.php';
require_once FUTTURU_CLOUD_SIMULATOR_PLUGIN_DIR . 'includes/class-futturu-cloud-ajax.php';

/**
 * Initialize the plugin
 */
function futturu_cloud_simulator_init() {
    // Load text domain
    load_plugin_textdomain('futturu-cloud-simulator', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize admin
    if (is_admin()) {
        new Futuru_Cloud_Admin();
    }
    
    // Initialize frontend
    new Futuru_Cloud_Frontend();
    
    // Initialize AJAX handlers
    new Futuru_Cloud_Ajax();
}
add_action('plugins_loaded', 'futturu_cloud_simulator_init');

/**
 * Activation hook
 */
function futturu_cloud_simulator_activate() {
    // Create default options
    $default_plans = Futuru_Cloud_Data::get_default_plans();
    update_option('futturu_cloud_plans', $default_plans);
    
    $default_categories = Futuru_Cloud_Data::get_default_categories();
    update_option('futturu_cloud_categories', $default_categories);
    
    $default_features = Futuru_Cloud_Data::get_default_features();
    update_option('futturu_cloud_features', $default_features);
    
    $default_settings = array(
        'intro_text' => 'Descubra o plano de hospedagem em nuvem ideal para o seu projeto com a Futturu. Nossa parceria com a Cloudez oferece servidores de alto desempenho na nuvem (USA/Dallas e Newark), com gerenciamento completo, automações inteligentes e suporte técnico especializado, tudo para que você se preocupe apenas com o seu negócio.',
        'cta_text' => 'Solicitar Cotação',
        'cta_email' => 'suporte@futturu.com.br',
        'plugin_enabled' => true
    );
    update_option('futturu_cloud_settings', $default_settings);
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'futturu_cloud_simulator_activate');

/**
 * Deactivation hook
 */
function futturu_cloud_simulator_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'futturu_cloud_simulator_deactivate');

/**
 * Shortcode to display the simulator
 * Usage: [futturu_cloud_simulator]
 */
function futturu_cloud_simulator_shortcode($atts) {
    $atts = shortcode_atts(array(), $atts, 'futturu_cloud_simulator');
    
    $settings = get_option('futturu_cloud_settings', array());
    if (isset($settings['plugin_enabled']) && !$settings['plugin_enabled']) {
        return '';
    }
    
    ob_start();
    include FUTTURU_CLOUD_SIMULATOR_PLUGIN_DIR . 'templates/simulator.php';
    return ob_get_clean();
}
add_shortcode('futturu_cloud_simulator', 'futturu_cloud_simulator_shortcode');
