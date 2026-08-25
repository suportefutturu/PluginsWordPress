<?php
/**
 * Plugin Name: Lead Scoring Form
 * Plugin URI: https://futturu.com.br
 * Description: Formulário inteligente de lead scoring com classificação de prioridade para criação de sites em Belém.
 * Version: 1.0.0
 * Author: Futturu
 * Author URI: https://futturu.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lead-scoring-form
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('LSF_VERSION', '1.0.0');
define('LSF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LSF_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once LSF_PLUGIN_DIR . 'includes/class-lsf-admin.php';
require_once LSF_PLUGIN_DIR . 'includes/class-lsf-form.php';
require_once LSF_PLUGIN_DIR . 'includes/class-lsf-email.php';

/**
 * Initialize the plugin
 */
function lsf_init() {
    // Load text domain
    load_plugin_textdomain('lead-scoring-form', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize admin settings
    if (is_admin()) {
        new LSF_Admin();
    }
    
    // Initialize form handler
    new LSF_Form();
    
    // Initialize email handler
    new LSF_Email();
}
add_action('plugins_loaded', 'lsf_init');

/**
 * Register activation hook
 */
function lsf_activate() {
    // Set default options
    $default_options = array(
        'lsf_email_to' => 'suporte@futturu.com.br',
        'lsf_primary_color' => '#2563eb',
        'lsf_secondary_color' => '#1e40af',
        'lsf_background_color' => '#ffffff',
        'lsf_text_color' => '#1f2937'
    );
    
    if (!get_option('lsf_settings')) {
        add_option('lsf_settings', $default_options);
    }
    
    // Create custom table for leads if needed
    global $wpdb;
    $table_name = $wpdb->prefix . 'lsf_leads';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        company varchar(255) DEFAULT '',
        contact varchar(255) NOT NULL,
        objective varchar(100) NOT NULL,
        deadline varchar(100) NOT NULL,
        investment_model varchar(100) NOT NULL,
        score int(3) DEFAULT 0,
        priority varchar(20) DEFAULT 'medium',
        submitted_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'lsf_activate');

/**
 * Register deactivation hook
 */
function lsf_deactivate() {
    // Clean up if needed
}
register_deactivation_hook(__FILE__, 'lsf_deactivate');

/**
 * Shortcode to display the form
 */
function lsf_form_shortcode($atts) {
    return LSF_Form::render_form($atts);
}
add_shortcode('lead_scoring_form', 'lsf_form_shortcode');

/**
 * Enqueue frontend scripts and styles
 */
function lsf_enqueue_scripts() {
    wp_enqueue_style(
        'lsf-style',
        LSF_PLUGIN_URL . 'assets/css/style.css',
        array(),
        LSF_VERSION
    );
    
    wp_enqueue_script(
        'lsf-script',
        LSF_PLUGIN_URL . 'assets/js/script.js',
        array('jquery'),
        LSF_VERSION,
        true
    );
    
    wp_localize_script('lsf-script', 'lsf_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('lsf_form_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'lsf_enqueue_scripts');
