<?php
/**
 * Plugin Name: PopUp CTA Promocional Futturu
 * Plugin URI: https://futturu.com.br
 * Description: Exibe um popup promocional leve, moderno e altamente configurável para divulgar páginas internas da Futturu.
 * Version: 1.0.0
 * Author: Futturu
 * Author URI: https://futturu.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: futturu-popup-cta
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('FUTTURU_POPUP_VERSION', '1.0.0');
define('FUTTURU_POPUP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FUTTURU_POPUP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once FUTTURU_POPUP_PLUGIN_DIR . 'includes/class-futturu-popup-admin.php';
require_once FUTTURU_POPUP_PLUGIN_DIR . 'includes/class-futturu-popup-frontend.php';

/**
 * Initialize the plugin
 */
function futturu_popup_init() {
    // Load text domain
    load_plugin_textdomain('futturu-popup-cta', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize admin
    if (is_admin()) {
        new Futturu_Popup_Admin();
    }
    
    // Initialize frontend
    new Futturu_Popup_Frontend();
}
add_action('plugins_loaded', 'futturu_popup_init');

/**
 * Activation hook
 */
function futturu_popup_activate() {
    // Set default options
    $default_options = array(
        'enabled' => 1,
        'title' => 'OFERTA ESPECIAL',
        'content' => 'Crie seu Site Institucional Profissional por um preço imperdível! Apenas hoje ou enquanto durarem as vagas.',
        'cta_text' => 'Acesse agora o Hotsite!',
        'cta_url' => home_url('/promocao-site-institucional/'),
        'show_decline_button' => 1,
        'decline_text' => 'Não, obrigado',
        'width' => 'medium',
        'max_height' => '',
        'bg_color' => '#ffffff',
        'text_color' => '#333333',
        'cta_bg_color' => '#0073aa',
        'cta_text_color' => '#ffffff',
        'close_btn_color' => '#666666',
        'font_family' => 'inherit',
        'font_size' => '16',
        'font_weight' => '400',
        'enable_blur' => 1,
        'blur_intensity' => '5',
        'enable_animation' => 1,
        'animation_type' => 'fade-in',
        'display_pages' => 'all',
        'display_page_ids' => array(),
        'exclude_page_ids' => array(),
        'display_categories' => array(),
        'display_time' => 'immediate',
        'display_delay' => '2',
        'scroll_percentage' => '50',
        'frequency' => 'once_per_session',
        'frequency_days' => '7',
        'frequency_count' => '3'
    );
    
    if (!get_option('futturu_popup_options')) {
        add_option('futturu_popup_options', $default_options);
    }
    
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'futturu_popup_activate');

/**
 * Deactivation hook
 */
function futturu_popup_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'futturu_popup_deactivate');
