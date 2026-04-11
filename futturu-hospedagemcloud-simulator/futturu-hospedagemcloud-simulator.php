<?php
/**
 * Plugin Name: Simulador de Hospedagem Cloud Futturu
 * Plugin URI: https://futturu.com.br
 * Description: Simulador de planos de hospedagem em nuvem com opção mensal e anual (10% OFF).
 * Version: 1.0.0
 * Author: Futturu
 * License: GPL v2 or later
 * Text Domain: futturu-hospedagemcloud-sim
 */

if (!defined('ABSPATH')) {
    exit;
}

define('FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION', '1.0.0');
define('FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Carregar classes
require_once FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-hospedagemcloud-data.php';
require_once FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-hospedagemcloud-admin.php';
require_once FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-hospedagemcloud-frontend.php';

// Inicializar
function futturu_hospedagemcloud_sim_init() {
    new Futturu_HospedagemCloud_Data();
    new Futturu_HospedagemCloud_Admin();
    new Futturu_HospedagemCloud_Frontend();
}
add_action('plugins_loaded', 'futturu_hospedagemcloud_sim_init');

// Activation hook
register_activation_hook(__FILE__, 'futturu_hospedagemcloud_sim_activate');
function futturu_hospedagemcloud_sim_activate() {
    // Salvar dados padrão se não existirem
    if (!get_option('futturu_hospedagemcloud_plans')) {
        $data = new Futturu_HospedagemCloud_Data();
        update_option('futturu_hospedagemcloud_plans', $data->get_default_plans());
    }
    if (!get_option('futturu_hospedagemcloud_faqs')) {
        $data = new Futturu_HospedagemCloud_Data();
        update_option('futturu_hospedagemcloud_faqs', $data->get_default_faqs());
    }
    if (!get_option('futturu_hospedagemcloud_features')) {
        $data = new Futturu_HospedagemCloud_Data();
        update_option('futturu_hospedagemcloud_features', $data->get_default_features());
    }
    if (!get_option('futturu_hospedagemcloud_settings')) {
        update_option('futturu_hospedagemcloud_settings', array(
            'discount_rate' => 10,
            'default_view' => 'annual',
            'contact_email' => 'suporte@futturu.com.br'
        ));
    }
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'futturu_hospedagemcloud_sim_deactivate');
function futturu_hospedagemcloud_sim_deactivate() {
    flush_rewrite_rules();
}
