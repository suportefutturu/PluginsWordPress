<?php
/**
 * Plugin Name: Simulador de Hospedagem na Nuvem Futturu - Anual ou Mensal
 * Plugin URI: https://futturu.com.br
 * Description: Simulador de planos de hospedagem em nuvem com opção de visualização mensal ou anual (10% OFF no anual). Parceria Cloudez.
 * Version: 1.0.0
 * Author: Futturu
 * Author URI: https://futturu.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: futturu-hospedagemcloud-sim
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION', '1.0.0');
define('FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-hospedagemcloud-admin.php';
require_once FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-hospedagemcloud-frontend.php';
require_once FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-hospedagemcloud-data.php';

/**
 * Initialize the plugin
 */
function futturu_hospedagemcloud_simulator_init() {
    // Load text domain
    load_plugin_textdomain('futturu-hospedagemcloud-sim', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize admin
    if (is_admin()) {
        new Futturu_HospedagemCloud_Admin();
    }
    
    // Initialize frontend
    new Futturu_HospedagemCloud_Frontend();
}
add_action('plugins_loaded', 'futturu_hospedagemcloud_simulator_init');

/**
 * Activation hook
 */
function futturu_hospedagemcloud_simulator_activate() {
    // Set default options
    $default_plans = Futturu_HospedagemCloud_Data::get_default_plans();
    $default_faqs = Futturu_HospedagemCloud_Data::get_default_faqs();
    $default_features = Futturu_HospedagemCloud_Data::get_default_features();
    
    if (!get_option('futturu_hospedagemcloud_plans')) {
        update_option('futturu_hospedagemcloud_plans', $default_plans);
    }
    
    if (!get_option('futturu_hospedagemcloud_faqs')) {
        update_option('futturu_hospedagemcloud_faqs', $default_faqs);
    }
    
    if (!get_option('futturu_hospedagemcloud_features')) {
        update_option('futturu_hospedagemcloud_features', $default_features);
    }
    
    if (!get_option('futturu_hospedagemcloud_discount')) {
        update_option('futturu_hospedagemcloud_discount', 10);
    }
    
    if (!get_option('futturu_hospedagemcloud_cta_email')) {
        update_option('futturu_hospedagemcloud_cta_email', 'suporte@futturu.com.br');
    }
    
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'futturu_hospedagemcloud_simulator_activate');

/**
 * Deactivation hook
 */
function futturu_hospedagemcloud_simulator_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'futturu_hospedagemcloud_simulator_deactivate');
