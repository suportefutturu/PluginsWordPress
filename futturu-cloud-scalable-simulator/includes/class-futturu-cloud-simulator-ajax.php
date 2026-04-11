<?php
/**
 * AJAX handler class for Futuru Cloud Simulator
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Futuru_Cloud_Simulator_Ajax {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Contact form submission
        add_action('wp_ajax_futturu_send_contact', array($this, 'handle_contact_form'));
        add_action('wp_ajax_nopriv_futturu_send_contact', array($this, 'handle_contact_form'));
        
        // Save plan (admin)
        add_action('wp_ajax_futturu_save_plan', array($this, 'handle_save_plan'));
        
        // Delete plan (admin)
        add_action('wp_ajax_futturu_delete_plan', array($this, 'handle_delete_plan'));
        
        // Save profile (admin)
        add_action('wp_ajax_futturu_save_profile', array($this, 'handle_save_profile'));
        
        // Delete profile (admin)
        add_action('wp_ajax_futturu_delete_profile', array($this, 'handle_delete_profile'));
    }
    
    /**
     * Handle contact form submission
     */
    public function handle_contact_form() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'futturu_cloud_sim_nonce')) {
            wp_send_json_error(array('message' => __('Erro de segurança. Por favor, tente novamente.', 'futturu-cloud-simulator')));
        }
        
        // Sanitize inputs
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $traffic_profile = isset($_POST['traffic_profile']) ? sanitize_text_field($_POST['traffic_profile']) : '';
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
        $selected_plan = isset($_POST['selected_plan']) ? sanitize_text_field($_POST['selected_plan']) : '';
        
        // Validate required fields
        if (empty($name) || empty($email)) {
            wp_send_json_error(array('message' => __('Por favor, preencha os campos obrigatórios.', 'futturu-cloud-simulator')));
        }
        
        if (!is_email($email)) {
            wp_send_json_error(array('message' => __('Por favor, informe um e-mail válido.', 'futturu-cloud-simulator')));
        }
        
        // Get settings
        $settings = get_option('futturu_cloud_settings', array());
        $to_email = $settings['cta_email'] ?? 'suporte@futturu.com.br';
        
        // Prepare email subject
        $subject = sprintf(
            __('Nova Solicitação de Cotação - %s', 'futturu-cloud-simulator'),
            $name
        );
        
        if (!empty($selected_plan)) {
            $subject .= ' - Plano: ' . $selected_plan;
        }
        
        // Prepare email body
        $body = sprintf(__("Nova solicitação de cotação recebida do simulador:\n\n", 'futturu-cloud-simulator'));
        $body .= sprintf(__("Nome: %s\n", 'futturu-cloud-simulator'), $name);
        $body .= sprintf(__("E-mail: %s\n", 'futturu-cloud-simulator'), $email);
        $body .= sprintf(__("Telefone: %s\n", 'futturu-cloud-simulator'), $phone);
        $body .= sprintf(__("Perfil de Tráfego: %s\n", 'futturu-cloud-simulator'), $traffic_profile);
        $body .= sprintf(__("Plano Selecionado: %s\n", 'futturu-cloud-simulator'), $selected_plan);
        $body .= sprintf(__("Mensagem:\n%s\n", 'futturu-cloud-simulator'), $message);
        $body .= "\n" . sprintf(__("Enviado em: %s", 'futturu-cloud-simulator'), current_time('mysql'));
        
        // Set headers
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'Reply-To: ' . $email
        );
        
        // Send email
        $sent = wp_mail($to_email, $subject, $body, $headers);
        
        if ($sent) {
            wp_send_json_success(array('message' => __('Mensagem enviada com sucesso! Entraremos em contato em breve.', 'futturu-cloud-simulator')));
        } else {
            wp_send_json_error(array('message' => __('Erro ao enviar mensagem. Por favor, tente novamente ou entre em contato diretamente.', 'futturu-cloud-simulator')));
        }
    }
    
    /**
     * Handle save plan (admin)
     */
    public function handle_save_plan() {
        // Check admin capability
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permissão negada.', 'futturu-cloud-simulator')));
        }
        
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'futturu_cloud_admin_nonce')) {
            wp_send_json_error(array('message' => __('Erro de segurança.', 'futturu-cloud-simulator')));
        }
        
        $plan_index = isset($_POST['index']) ? intval($_POST['index']) : -1;
        
        $plan_data = array(
            'id' => sanitize_text_field($_POST['id']),
            'name' => sanitize_text_field($_POST['name']),
            'ram' => sanitize_text_field($_POST['ram']),
            'cpu' => sanitize_text_field($_POST['cpu']),
            'disk' => sanitize_text_field($_POST['disk']),
            'views' => sanitize_text_field($_POST['views']),
            'sites' => sanitize_text_field($_POST['sites']),
            'price' => floatval($_POST['price']),
            'category' => sanitize_text_field($_POST['category']),
            'next_plan' => sanitize_text_field($_POST['next_plan']),
            'features' => array() // Can be expanded later
        );
        
        $plans = get_option('futturu_cloud_plans', array());
        
        if ($plan_index >= 0 && isset($plans[$plan_index])) {
            // Update existing plan
            $plans[$plan_index] = $plan_data;
        } else {
            // Add new plan
            $plans[] = $plan_data;
        }
        
        update_option('futturu_cloud_plans', $plans);
        
        wp_send_json_success(array(
            'message' => __('Plano salvo com sucesso!', 'futturu-cloud-simulator'),
            'plans' => $plans
        ));
    }
    
    /**
     * Handle delete plan (admin)
     */
    public function handle_delete_plan() {
        // Check admin capability
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permissão negada.', 'futturu-cloud-simulator')));
        }
        
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'futturu_cloud_admin_nonce')) {
            wp_send_json_error(array('message' => __('Erro de segurança.', 'futturu-cloud-simulator')));
        }
        
        $plan_index = isset($_POST['index']) ? intval($_POST['index']) : -1;
        
        if ($plan_index < 0) {
            wp_send_json_error(array('message' => __('Índice inválido.', 'futturu-cloud-simulator')));
        }
        
        $plans = get_option('futturu_cloud_plans', array());
        
        if (isset($plans[$plan_index])) {
            array_splice($plans, $plan_index, 1);
            update_option('futturu_cloud_plans', $plans);
            
            wp_send_json_success(array(
                'message' => __('Plano excluído com sucesso!', 'futturu-cloud-simulator'),
                'plans' => $plans
            ));
        } else {
            wp_send_json_error(array('message' => __('Plano não encontrado.', 'futturu-cloud-simulator')));
        }
    }
    
    /**
     * Handle save profile (admin)
     */
    public function handle_save_profile() {
        // Check admin capability
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permissão negada.', 'futturu-cloud-simulator')));
        }
        
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'futturu_cloud_admin_nonce')) {
            wp_send_json_error(array('message' => __('Erro de segurança.', 'futturu-cloud-simulator')));
        }
        
        $profile_index = isset($_POST['index']) ? intval($_POST['index']) : -1;
        
        $profile_data = array(
            'id' => sanitize_text_field($_POST['id']),
            'name' => sanitize_text_field($_POST['name']),
            'views_min' => intval($_POST['views_min']),
            'views_max' => intval($_POST['views_max']),
            'recommended_plan' => sanitize_text_field($_POST['recommended_plan']),
            'description' => sanitize_textarea_field($_POST['description'])
        );
        
        // Generate ID if not provided
        if (empty($profile_data['id'])) {
            $profile_data['id'] = sanitize_title($profile_data['name']);
        }
        
        $profiles = get_option('futturu_cloud_profiles', array());
        
        if ($profile_index >= 0 && isset($profiles[$profile_index])) {
            // Update existing profile
            $profiles[$profile_index] = $profile_data;
        } else {
            // Add new profile
            $profiles[] = $profile_data;
        }
        
        update_option('futturu_cloud_profiles', $profiles);
        
        wp_send_json_success(array(
            'message' => __('Perfil salvo com sucesso!', 'futturu-cloud-simulator'),
            'profiles' => $profiles
        ));
    }
    
    /**
     * Handle delete profile (admin)
     */
    public function handle_delete_profile() {
        // Check admin capability
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permissão negada.', 'futturu-cloud-simulator')));
        }
        
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'futturu_cloud_admin_nonce')) {
            wp_send_json_error(array('message' => __('Erro de segurança.', 'futturu-cloud-simulator')));
        }
        
        $profile_index = isset($_POST['index']) ? intval($_POST['index']) : -1;
        
        if ($profile_index < 0) {
            wp_send_json_error(array('message' => __('Índice inválido.', 'futturu-cloud-simulator')));
        }
        
        $profiles = get_option('futturu_cloud_profiles', array());
        
        if (isset($profiles[$profile_index])) {
            array_splice($profiles, $profile_index, 1);
            update_option('futturu_cloud_profiles', $profiles);
            
            wp_send_json_success(array(
                'message' => __('Perfil excluído com sucesso!', 'futturu-cloud-simulator'),
                'profiles' => $profiles
            ));
        } else {
            wp_send_json_error(array('message' => __('Perfil não encontrado.', 'futturu-cloud-simulator')));
        }
    }
}
