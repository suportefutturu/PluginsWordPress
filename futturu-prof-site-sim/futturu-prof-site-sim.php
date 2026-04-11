<?php
/**
 * Plugin Name: Simulador de Site Profissional Futturu
 * Plugin URI: https://futturu.com.br
 * Description: Gere um protótipo visual e personalizado de como seria um site profissional criado e hospedado pela Futturu. Capture leads qualificados.
 * Version: 1.0.0
 * Author: Futturu
 * Author URI: https://futturu.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: futturu-prof-site-sim
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FUTTURU_PSS_VERSION', '1.0.0');
define('FUTTURU_PSS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FUTTURU_PSS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FUTTURU_PSS_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once FUTTURU_PSS_PLUGIN_DIR . 'includes/class-futturu-pss-admin.php';
require_once FUTTURU_PSS_PLUGIN_DIR . 'includes/class-futturu-pss-frontend.php';
require_once FUTTURU_PSS_PLUGIN_DIR . 'includes/class-futturu-pss-ajax.php';

/**
 * Initialize the plugin
 */
function futturu_pss_init() {
    // Load text domain
    load_plugin_textdomain('futturu-prof-site-sim', false, dirname(FUTTURU_PSS_PLUGIN_BASENAME) . '/languages');
    
    // Initialize admin
    if (is_admin()) {
        new Futturu_PSS_Admin();
    }
    
    // Initialize frontend
    new Futturu_PSS_Frontend();
    
    // Initialize AJAX handlers
    new Futturu_PSS_Ajax();
}
add_action('plugins_loaded', 'futturu_pss_init');

/**
 * Activation hook
 */
function futturu_pss_activate() {
    // Set default options
    $defaults = array(
        'futturu_pss_active' => 'yes',
        'futturu_pss_site_types' => serialize(array(
            'professional' => 'Site Profissional (Advogados, Médicos, Engenheiros...)',
            'services' => 'Site para Empresa de Serviços',
            'restaurant' => 'Site para Restaurante ou Delivery',
            'catalog' => 'Catálogo Digital / Loja Virtual Básica',
            'other' => 'Outro'
        )),
        'futturu_pss_categories' => serialize(array(
            'Advocacia', 'Medicina', 'Engenharia', 'Restaurante', 'Delivery',
            'Consultoria', 'Oficina', 'Comércio', 'Educação', 'Saúde',
            'Tecnologia', 'Beleza', 'Fitness', 'Imobiliário', 'Outros'
        )),
        'futturu_pss_email_to' => 'suporte@futturu.com.br',
        'futturu_pss_cta_text' => 'Solicite uma Proposta Personalizada',
        'futturu_pss_email_subject' => 'Nova Proposta - Simulador de Site Profissional'
    );
    
    foreach ($defaults as $option => $value) {
        if (get_option($option) === false) {
            add_option($option, $value);
        }
    }
    
    // Clear rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'futturu_pss_activate');

/**
 * Deactivation hook
 */
function futturu_pss_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'futturu_pss_deactivate');

/**
 * Shortcode to render the simulator
 */
function futturu_pss_shortcode($atts) {
    $frontend = new Futturu_PSS_Frontend();
    return $frontend->render_simulator($atts);
}
add_shortcode('futturu_prof_site_sim', 'futturu_pss_shortcode');
