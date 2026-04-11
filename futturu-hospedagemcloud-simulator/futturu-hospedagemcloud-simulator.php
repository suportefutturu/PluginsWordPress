<?php
/**
 * Plugin Name: Simulador de Hospedagem na Nuvem Futturu - Anual ou Mensal
 * Plugin URI: https://futturu.com.br
 * Description: Simulador de planos de hospedagem em nuvem com opção mensal e anual (10% OFF). Permite comparação, visualização detalhada e solicitação de cotação.
 * Version: 1.0.0
 * Author: Futturu
 * Author URI: https://futturu.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: futturu-hospedagemcloud-sim
 * Domain Path: /languages
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Define constantes do plugin
define('FUTTURU_HOSPEDAGEMCLOUD_SIM_VERSION', '1.0.0');
define('FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Carrega as classes do plugin
require_once FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-hospedagemcloud-data.php';
require_once FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-hospedagemcloud-admin.php';
require_once FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_DIR . 'includes/class-futturu-hospedagemcloud-frontend.php';

// Inicializa o plugin
function futturu_hospedagemcloud_sim_init() {
    // Carrega text domain para tradução
    load_plugin_textdomain('futturu-hospedagemcloud-sim', false, dirname(FUTTURU_HOSPEDAGEMCLOUD_SIM_PLUGIN_BASENAME) . '/languages');
    
    // Inicializa classes
    new Futturu_HospedagemCloud_Data();
    new Futturu_HospedagemCloud_Admin();
    new Futturu_HospedagemCloud_Frontend();
}
add_action('plugins_loaded', 'futturu_hospedagemcloud_sim_init');

// Hook de ativação
register_activation_hook(__FILE__, 'futturu_hospedagemcloud_sim_activate');
function futturu_hospedagemcloud_sim_activate() {
    // Limpa rewrite rules se necessário
    flush_rewrite_rules();
}

// Hook de desativação
register_deactivation_hook(__FILE__, 'futturu_hospedagemcloud_sim_deactivate');
function futturu_hospedagemcloud_sim_deactivate() {
    // Limpeza se necessário
    flush_rewrite_rules();
}
