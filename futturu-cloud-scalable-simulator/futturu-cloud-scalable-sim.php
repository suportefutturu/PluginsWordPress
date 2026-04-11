<?php
/**
 * Plugin Name: Simulador de Hospedagem na Nuvem Futturu
 * Plugin URI: https://futturu.com.br
 * Description: Simulador de planos de hospedagem em nuvem com foco em conversão, economia e escalabilidade. Parceria Cloudez.
 * Version: 2.0.0
 * Author: Futturu
 * Author URI: https://futturu.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: futturu-cloud-simulator
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

define('FUTTURU_CLOUD_SIM_VERSION', '2.0.0');
define('FUTTURU_CLOUD_SIM_PATH', plugin_dir_path(__FILE__));
define('FUTTURU_CLOUD_SIM_URL', plugin_dir_url(__FILE__));
define('FUTTURU_CLOUD_SIM_BASENAME', plugin_basename(__FILE__));

class Futturu_Cloud_Scalable_Simulator {
    
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
        
        add_action('init', array($this, 'load_textdomain'));
        add_action('init', array($this, 'register_shortcode'), 1);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // AJAX handlers
        add_action('wp_ajax_futturu_send_lead', array($this, 'handle_lead_submission'));
        add_action('wp_ajax_nopriv_futturu_send_lead', array($this, 'handle_lead_submission'));
        add_action('wp_ajax_futturu_save_plan', array($this, 'ajax_save_plan'));
        add_action('wp_ajax_futturu_delete_plan', array($this, 'ajax_delete_plan'));
        add_action('wp_ajax_futturu_save_profile', array($this, 'ajax_save_profile'));
        add_action('wp_ajax_futturu_delete_profile', array($this, 'ajax_delete_profile'));
        add_action('wp_ajax_futturu_save_texts', array($this, 'ajax_save_texts'));
        add_action('wp_ajax_futturu_save_cta', array($this, 'ajax_save_cta'));
    }
    
    public function activate() {
        $this->create_default_options();
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    private function create_default_options() {
        $default_plans = $this->get_default_plans();
        $default_profiles = $this->get_default_profiles();
        $default_texts = $this->get_default_texts();
        $default_cta = $this->get_default_cta();
        
        if (!get_option('futturu_cloud_plans')) {
            update_option('futturu_cloud_plans', $default_plans);
        }
        if (!get_option('futturu_cloud_profiles')) {
            update_option('futturu_cloud_profiles', $default_profiles);
        }
        if (!get_option('futturu_cloud_texts')) {
            update_option('futturu_cloud_texts', $default_texts);
        }
        if (!get_option('futturu_cloud_cta')) {
            update_option('futturu_cloud_cta', $default_cta);
        }
        if (!get_option('futturu_cloud_enabled')) {
            update_option('futturu_cloud_enabled', true);
        }
    }
    
    public function get_default_plans() {
        return array(
            array(
                'id' => 'br1g',
                'name' => 'BR1G - Inicial',
                'category' => 'starter',
                'ram' => 1,
                'cpu' => 1,
                'disk' => 25,
                'views' => 100000,
                'sites' => '1-2',
                'price' => 239.00,
                'next_plan' => 'br2g',
                'featured' => true,
                'description' => 'Ideal para sites institucionais novos ou blogs pessoais. Comece com economia e escale quando precisar.'
            ),
            array(
                'id' => 'br2g',
                'name' => 'BR2G - Crescimento',
                'category' => 'growth',
                'ram' => 2,
                'cpu' => 2,
                'disk' => 50,
                'views' => 200000,
                'sites' => '2-4',
                'price' => 479.00,
                'next_plan' => 'br4g',
                'featured' => false,
                'description' => 'Perfeito para pequenos negócios em expansão. Mais recursos sem complicação.'
            ),
            array(
                'id' => 'br4g',
                'name' => 'BR4G - Profissional',
                'category' => 'professional',
                'ram' => 4,
                'cpu' => 4,
                'disk' => 80,
                'views' => 400000,
                'sites' => '4-8',
                'price' => 1009.00,
                'next_plan' => 'br8g',
                'featured' => true,
                'description' => 'Recomendado para e-commerce leve e sites com tráfego moderado. Performance garantida.'
            ),
            array(
                'id' => 'br8g',
                'name' => 'BR8G - Avançado',
                'category' => 'advanced',
                'ram' => 8,
                'cpu' => 8,
                'disk' => 160,
                'views' => 800000,
                'sites' => '8-15',
                'price' => 1589.00,
                'next_plan' => 'br16g',
                'featured' => false,
                'description' => 'Para negócios estabelecidos com alto tráfego. Escalabilidade imediata.'
            ),
            array(
                'id' => 'br16g',
                'name' => 'BR16G - Enterprise',
                'category' => 'enterprise',
                'ram' => 16,
                'cpu' => 16,
                'disk' => 320,
                'views' => 1500000,
                'sites' => '15-30',
                'price' => 2899.00,
                'next_plan' => 'custom',
                'featured' => false,
                'description' => 'Solução completa para grandes operações. Suporte prioritário e recursos dedicados.'
            )
        );
    }
    
    public function get_default_profiles() {
        return array(
            array(
                'id' => 'new_site',
                'name' => 'Site Novo / Institucional',
                'min_views' => 0,
                'max_views' => 100000,
                'recommended_plan' => 'br1g',
                'description' => 'Você está começando agora? Este perfil é ideal para quem quer economizar no início sem perder qualidade.'
            ),
            array(
                'id' => 'small_business',
                'name' => 'Pequeno Negócio',
                'min_views' => 100001,
                'max_views' => 300000,
                'recommended_plan' => 'br2g',
                'description' => 'Seu negócio está crescendo? Tenha recursos adequados para acompanhar sua expansão.'
            ),
            array(
                'id' => 'ecommerce_light',
                'name' => 'E-commerce Leve / Blog Popular',
                'min_views' => 300001,
                'max_views' => 500000,
                'recommended_plan' => 'br4g',
                'description' => 'Vendas online ou conteúdo popular exigem performance. Não perca clientes por lentidão.'
            ),
            array(
                'id' => 'high_traffic',
                'name' => 'Alto Tráfego / E-commerce Pesado',
                'min_views' => 500001,
                'max_views' => 9999999,
                'recommended_plan' => 'br8g',
                'description' => 'Tráfego intenso requer infraestrutura robusta. Evite quedas e perda de receita.'
            )
        );
    }
    
    public function get_default_texts() {
        return array(
            'intro_title' => 'Comece Pequeno, Cresça com Segurança',
            'intro_subtitle' => 'Descubra como começar com uma hospedagem poderosa e econômica e crescer com tranquilidade.',
            'intro_description' => 'Nossa parceria com a Cloudez oferece planos escalonáveis, gerenciados automaticamente, para que você se preocupe apenas com o seu negócio. Evite os limites da hospedagem compartilhada e tenha controle total do seu crescimento.',
            'quiz_question' => 'Quantas visualizações seu site recebe (ou espera receber) por mês?',
            'benefits' => array(
                'Comece com Planos Acessíveis',
                'Hospedagem 100% Gerenciada',
                'CDN e Otimizações Automáticas',
                'Backups Diários e Segurança',
                'Monitoramento Proativo 24/7',
                'Escalabilidade com 1 Clique',
                'Sem Limites da Hospedagem Compartilhada',
                'Suporte Técnico Especializado'
            ),
            'comparison_title' => 'Por que evitar Hospedagem Compartilhada?',
            'comparison_items' => array(
                'Recursos divididos com centenas de outros sites',
                'Performance instável durante picos de acesso',
                'Risco de segurança elevado',
                'Sem possibilidade de escalabilidade real',
                'Suporte técnico limitado e impessoal'
            ),
            'timeline_title' => 'Seu Caminho de Crescimento',
            'timeline_description' => 'Veja como você pode começar econômico e escalar conforme seu negócio cresce, mantendo performance e segurança.'
        );
    }
    
    public function get_default_cta() {
        return array(
            'primary_text' => 'Pronto para começar com a hospedagem certa?',
            'primary_subtext' => 'Fale com um especialista da Futturu e receba uma consultoria gratuita.',
            'secondary_text' => 'Dúvidas sobre qual plano escolher?',
            'secondary_subtext' => 'Solicite ajuda personalizada sem compromisso.',
            'button_text' => 'Solicite uma Cotação Gratuita',
            'email' => 'suporte@futturu.com.br'
        );
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('futturu-cloud-simulator', false, dirname(FUTTURU_CLOUD_SIM_BASENAME) . '/languages');
    }
    
    public function register_shortcode() {
        add_shortcode('futturu_cloud_scalable_simulator', array($this, 'render_simulator'));
    }
    
    public function render_simulator($atts) {
        if (!get_option('futturu_cloud_enabled', true)) {
            return '';
        }
        
        wp_enqueue_style('futturu-cloud-css');
        wp_enqueue_script('futturu-cloud-js');
        
        $plans = get_option('futturu_cloud_plans', $this->get_default_plans());
        $profiles = get_option('futturu_cloud_profiles', $this->get_default_profiles());
        $texts = get_option('futturu_cloud_texts', $this->get_default_texts());
        $cta = get_option('futturu_cloud_cta', $this->get_default_cta());
        
        ob_start();
        include FUTTURU_CLOUD_SIM_PATH . 'includes/class-futturu-cloud-simulator-frontend.php';
        return ob_get_clean();
    }
    
    public function enqueue_assets() {
        wp_register_style(
            'futturu-cloud-css',
            FUTTURU_CLOUD_SIM_URL . 'assets/css/futturu-cloud-simulator.css',
            array(),
            FUTTURU_CLOUD_SIM_VERSION
        );
        
        wp_register_script(
            'futturu-cloud-js',
            FUTTURU_CLOUD_SIM_URL . 'assets/js/futturu-cloud-simulator.js',
            array('jquery'),
            FUTTURU_CLOUD_SIM_VERSION,
            true
        );
        
        wp_localize_script('futturu-cloud-js', 'futturuCloudSim', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_cloud_nonce'),
            'i18n' => array(
                'loading' => 'Carregando...',
                'error' => 'Ocorreu um erro. Tente novamente.',
                'success' => 'Mensagem enviada com sucesso!',
                'selectPlan' => 'Selecionar este plano'
            )
        ));
    }
    
    public function add_admin_menu() {
        add_options_page(
            'Simulador Cloud Futturu',
            'Simulador Cloud Futturu',
            'manage_options',
            'futturu-cloud-simulator',
            array($this, 'render_admin_page'),
            'dashicons-cloud',
            30
        );
    }
    
    public function register_settings() {
        register_setting('futturu_cloud_group', 'futturu_cloud_plans');
        register_setting('futturu_cloud_group', 'futturu_cloud_profiles');
        register_setting('futturu_cloud_group', 'futturu_cloud_texts');
        register_setting('futturu_cloud_group', 'futturu_cloud_cta');
        register_setting('futturu_cloud_group', 'futturu_cloud_enabled');
    }
    
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_futturu-cloud-simulator') {
            return;
        }
        
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }
    
    public function render_admin_page() {
        include FUTTURU_CLOUD_SIM_PATH . 'includes/class-futturu-cloud-simulator-admin.php';
    }
    
    public function handle_lead_submission() {
        check_ajax_referer('futturu_cloud_nonce', 'nonce');
        
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $traffic_profile = sanitize_text_field($_POST['traffic_profile'] ?? '');
        $selected_plan = sanitize_text_field($_POST['selected_plan'] ?? '');
        $message = sanitize_textarea_field($_POST['message'] ?? '');
        
        if (empty($name) || empty($email)) {
            wp_send_json_error(array('message' => 'Nome e e-mail são obrigatórios.'));
        }
        
        $to = get_option('futturu_cloud_cta', $this->get_default_cta())['email'];
        $subject = sprintf('Novo Lead - Simulador Cloud: %s (%s)', $name, $email);
        
        $body = "Novo lead do Simulador de Hospedagem Futturu\n\n";
        $body .= "Nome: {$name}\n";
        $body .= "E-mail: {$email}\n";
        $body .= "Telefone: {$phone}\n";
        $body .= "Perfil de Tráfego: {$traffic_profile}\n";
        $body .= "Plano Selecionado: {$selected_plan}\n";
        $body .= "Mensagem: {$message}\n";
        
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        if (wp_mail($to, $subject, $body, $headers)) {
            wp_send_json_success(array('message' => 'Obrigado! Entraremos em contato em breve.'));
        } else {
            wp_send_json_error(array('message' => 'Erro ao enviar. Tente novamente ou contate-nos diretamente.'));
        }
    }
    
    public function ajax_save_plan() {
        check_ajax_referer('futturu_cloud_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Sem permissão.'));
        }
        
        $plan_id = sanitize_text_field($_POST['plan_id'] ?? '');
        $action = sanitize_text_field($_POST['action_type'] ?? 'update');
        
        if ($action === 'delete') {
            $plans = get_option('futturu_cloud_plans', array());
            $plans = array_filter($plans, function($p) use ($plan_id) {
                return ($p['id'] ?? '') !== $plan_id;
            });
            update_option('futturu_cloud_plans', array_values($plans));
            wp_send_json_success(array('message' => 'Plano removido.'));
        }
        
        $new_plan = array(
            'id' => $plan_id ?: sanitize_title($_POST['name']),
            'name' => sanitize_text_field($_POST['name']),
            'category' => sanitize_text_field($_POST['category']),
            'ram' => intval($_POST['ram']),
            'cpu' => intval($_POST['cpu']),
            'disk' => intval($_POST['disk']),
            'views' => intval($_POST['views']),
            'sites' => sanitize_text_field($_POST['sites']),
            'price' => floatval($_POST['price']),
            'next_plan' => sanitize_text_field($_POST['next_plan']),
            'featured' => isset($_POST['featured']),
            'description' => sanitize_textarea_field($_POST['description'])
        );
        
        $plans = get_option('futturu_cloud_plans', array());
        
        if ($action === 'update') {
            foreach ($plans as &$plan) {
                if (($plan['id'] ?? '') === $new_plan['id']) {
                    $plan = $new_plan;
                    break;
                }
            }
        } else {
            $plans[] = $new_plan;
        }
        
        update_option('futturu_cloud_plans', $plans);
        wp_send_json_success(array('message' => 'Plano salvo com sucesso.'));
    }
    
    public function ajax_delete_plan() {
        $this->ajax_save_plan();
    }
    
    public function ajax_save_profile() {
        check_ajax_referer('futturu_cloud_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Sem permissão.'));
        }
        
        $profile_id = sanitize_text_field($_POST['profile_id'] ?? '');
        $action = sanitize_text_field($_POST['action_type'] ?? 'update');
        
        if ($action === 'delete') {
            $profiles = get_option('futturu_cloud_profiles', array());
            $profiles = array_filter($profiles, function($p) use ($profile_id) {
                return ($p['id'] ?? '') !== $profile_id;
            });
            update_option('futturu_cloud_profiles', array_values($profiles));
            wp_send_json_success(array('message' => 'Perfil removido.'));
        }
        
        $new_profile = array(
            'id' => $profile_id ?: sanitize_title($_POST['name']),
            'name' => sanitize_text_field($_POST['name']),
            'min_views' => intval($_POST['min_views']),
            'max_views' => intval($_POST['max_views']),
            'recommended_plan' => sanitize_text_field($_POST['recommended_plan']),
            'description' => sanitize_textarea_field($_POST['description'])
        );
        
        $profiles = get_option('futturu_cloud_profiles', array());
        
        if ($action === 'update') {
            foreach ($profiles as &$profile) {
                if (($profile['id'] ?? '') === $new_profile['id']) {
                    $profile = $new_profile;
                    break;
                }
            }
        } else {
            $profiles[] = $new_profile;
        }
        
        update_option('futturu_cloud_profiles', $profiles);
        wp_send_json_success(array('message' => 'Perfil salvo com sucesso.'));
    }
    
    public function ajax_delete_profile() {
        $this->ajax_save_profile();
    }
    
    public function ajax_save_texts() {
        check_ajax_referer('futturu_cloud_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Sem permissão.'));
        }
        
        $texts = array(
            'intro_title' => sanitize_text_field($_POST['intro_title']),
            'intro_subtitle' => sanitize_text_field($_POST['intro_subtitle']),
            'intro_description' => sanitize_textarea_field($_POST['intro_description']),
            'quiz_question' => sanitize_text_field($_POST['quiz_question']),
            'benefits' => array_map('sanitize_text_field', $_POST['benefits'] ?? array()),
            'comparison_title' => sanitize_text_field($_POST['comparison_title']),
            'comparison_items' => array_map('sanitize_text_field', $_POST['comparison_items'] ?? array()),
            'timeline_title' => sanitize_text_field($_POST['timeline_title']),
            'timeline_description' => sanitize_textarea_field($_POST['timeline_description'])
        );
        
        update_option('futturu_cloud_texts', $texts);
        wp_send_json_success(array('message' => 'Textos atualizados.'));
    }
    
    public function ajax_save_cta() {
        check_ajax_referer('futturu_cloud_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Sem permissão.'));
        }
        
        $cta = array(
            'primary_text' => sanitize_text_field($_POST['primary_text']),
            'primary_subtext' => sanitize_text_field($_POST['primary_subtext']),
            'secondary_text' => sanitize_text_field($_POST['secondary_text']),
            'secondary_subtext' => sanitize_text_field($_POST['secondary_subtext']),
            'button_text' => sanitize_text_field($_POST['button_text']),
            'email' => sanitize_email($_POST['email'])
        );
        
        update_option('futturu_cloud_cta', $cta);
        wp_send_json_success(array('message' => 'CTA atualizado.'));
    }
}

Futturu_Cloud_Scalable_Simulator::get_instance();
