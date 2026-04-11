<?php
/**
 * Plugin Name: Simulador de Hospedagem na Nuvem Futturu
 * Plugin URI: https://futturu.com.br
 * Description: Simulador de planos de hospedagem em nuvem gerenciada em parceria com Cloudez. Permita que seus clientes explorem e comparem planos de hospedagem.
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
define('FCS_VERSION', '1.0.0');
define('FCS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FCS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FCS_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once FCS_PLUGIN_DIR . 'includes/class-fcs-plans.php';
require_once FCS_PLUGIN_DIR . 'admin/class-fcs-admin.php';
require_once FCS_PLUGIN_DIR . 'includes/class-fcs-frontend.php';
require_once FCS_PLUGIN_DIR . 'includes/class-fcs-ajax.php';

// Initialize plugin components
register_activation_hook(__FILE__, array('FCS_Plans', 'activate'));
register_deactivation_hook(__FILE__, array('FCS_Plans', 'deactivate'));

add_action('plugins_loaded', array('FCS_Plans', 'init'));
add_action('admin_init', array('FCS_Admin', 'init'));
add_action('wp_enqueue_scripts', array('FCS_Frontend', 'enqueue_assets'));
add_action('wp_ajax_fcs_send_quote_request', array('FCS_Ajax', 'handle_quote_request'));
add_action('wp_ajax_nopriv_fcs_send_quote_request', array('FCS_Ajax', 'handle_quote_request'));

// Register shortcode
add_shortcode('futturu_cloud_simulator', array('FCS_Frontend', 'render_simulator'));
?>
