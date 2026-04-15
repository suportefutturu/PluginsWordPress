<?php
/**
 * Plugin Name: Futturu Promoção - Site Institucional Profissional
 * Plugin URI: https://futturu.com.br
 * Description: Plugin de promoção para criação de Sites Institucionais Profissionais com captura de leads e confirmação de pagamento via PIX.
 * Version: 1.0.0
 * Author: Futturu
 * Author URI: https://futturu.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: futturu-promo-site
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FUTTURU_PROMO_VERSION', '1.0.0');
define('FUTTURU_PROMO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FUTTURU_PROMO_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once FUTTURU_PROMO_PLUGIN_DIR . 'includes/class-futturu-promo-admin.php';
require_once FUTTURU_PROMO_PLUGIN_DIR . 'includes/class-futturu-promo-frontend.php';
require_once FUTTURU_PROMO_PLUGIN_DIR . 'includes/class-futturu-promo-ajax.php';

/**
 * Initialize the plugin
 */
function futturu_promo_init() {
    // Load text domain
    load_plugin_textdomain('futturu-promo-site', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize admin
    if (is_admin()) {
        new Futturu_Promo_Admin();
    }
    
    // Initialize frontend
    new Futturu_Promo_Frontend();
    
    // Initialize AJAX handlers
    new Futturu_Promo_Ajax();
}
add_action('plugins_loaded', 'futturu_promo_init');

/**
 * Activation hook
 */
function futturu_promo_activate() {
    // Set default options
    $defaults = array(
        'futturu_promo_active' => 'yes',
        'futturu_promo_normal_price' => '2500',
        'futturu_promo_promo_price' => '1500',
        'futturu_promo_first_installment' => '500',
        'futturu_promo_end_date' => date('Y-m-d H:i:s', strtotime('+24 hours')),
        'futturu_promo_pix_key' => 'pix@futturu.com.br',
        'futturu_promo_whatsapp' => '5591993100621',
        'futturu_promo_email' => 'suporte@futturu.com.br',
        'futturu_promo_hero_title' => 'Crie seu Site Institucional Profissional por R$ 1.500!',
        'futturu_promo_hero_subtitle' => 'De R$ 2.500 por R$ 1.500. Apenas hoje ou enquanto durarem as vagas.',
        'futturu_promo_success_message' => 'Parabéns! Recebemos seus dados e o comprovante de pagamento. Sua vaga na promoção de R$ 1.500 está garantida. Entraremos em contato em até 24h úteis para iniciar o briefing e a produção do seu novo site.'
    );
    
    foreach ($defaults as $key => $value) {
        if (get_option($key) === false) {
            update_option($key, $value);
        }
    }
    
    // Clear rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'futturu_promo_activate');

/**
 * Deactivation hook
 */
function futturu_promo_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'futturu_promo_deactivate');

/**
 * Shortcode to display the promotion
 * Usage: [futturu_promo_site]
 */
function futturu_promo_shortcode($atts) {
    $frontend = new Futturu_Promo_Frontend();
    return $frontend->render_promotion($atts);
}
add_shortcode('futturu_promo_site', 'futturu_promo_shortcode');

/**
 * Add custom rewrite rule for promotion page
 */
function futturu_promo_add_rewrite_rule() {
    add_rewrite_rule('^promocao-site-institucional/?$', 'index.php?futturu_promo=1', 'top');
}
add_action('init', 'futturu_promo_add_rewrite_rule');

/**
 * Query var for promotion page
 */
function futturu_promo_query_vars($vars) {
    $vars[] = 'futturu_promo';
    return $vars;
}
add_filter('query_vars', 'futturu_promo_query_vars');

/**
 * Template redirect for promotion page
 */
function futturu_promo_template_redirect() {
    if (get_query_var('futturu_promo')) {
        $frontend = new Futturu_Promo_Frontend();
        $frontend->render_promotion_page();
        exit;
    }
}
add_action('template_redirect', 'futturu_promo_template_redirect');
