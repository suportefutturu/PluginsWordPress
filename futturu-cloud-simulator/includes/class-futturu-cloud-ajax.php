<?php
/**
 * Class Futuru_Cloud_Ajax
 * Handles AJAX requests for the frontend
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futuru_Cloud_Ajax {
    
    public function __construct() {
        add_action('wp_ajax_futturu_cloud_submit_quote', array($this, 'submit_quote'));
        add_action('wp_ajax_nopriv_futturu_cloud_submit_quote', array($this, 'submit_quote'));
        
        add_action('wp_ajax_futturu_cloud_get_plan_details', array($this, 'get_plan_details'));
        add_action('wp_ajax_nopriv_futturu_cloud_get_plan_details', array($this, 'get_plan_details'));
    }
    
    /**
     * Submit quote request
     */
    public function submit_quote() {
        check_ajax_referer('futturu_cloud_frontend_nonce', 'nonce');
        
        // Sanitize input
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $company = isset($_POST['company']) ? sanitize_text_field($_POST['company']) : '';
        $plan_model = sanitize_text_field($_POST['plan_model']);
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
        
        // Validate required fields
        if (empty($name) || empty($email) || empty($phone) || empty($plan_model)) {
            wp_send_json_error(array('message' => __('Por favor, preencha todos os campos obrigatórios.', 'futturu-cloud-simulator')));
        }
        
        if (!is_email($email)) {
            wp_send_json_error(array('message' => __('Por favor, informe um e-mail válido.', 'futturu-cloud-simulator')));
        }
        
        // Get settings
        $settings = Futuru_Cloud_Data::get_settings();
        $to_email = isset($settings['cta_email']) ? $settings['cta_email'] : 'suporte@futturu.com.br';
        
        // Prepare email subject
        $subject = sprintf(
            __('Nova Solicitação de Cotação - Plano: %s', 'futturu-cloud-simulator'),
            $plan_model
        );
        
        // Prepare email body
        $body = sprintf(
            __("Nova solicitação de cotação recebida pelo Simulador Cloud Futturu\n\n" .
            "Dados do Cliente:\n" .
            "----------------\n" .
            "Nome: %s\n" .
            "E-mail: %s\n" .
            "Telefone/WhatsApp: %s\n" .
            "Empresa: %s\n\n" .
            "Plano de Interesse: %s\n\n" .
            "Mensagem:\n" .
            "%s\n\n" .
            "---\n" .
            "Enviado via Simulador Cloud Futturu em %s",
            'futturu-cloud-simulator'),
            $name,
            $email,
            $phone,
            $company ?: 'Não informada',
            $plan_model,
            $message ?: 'Nenhuma mensagem adicional.',
            current_time('d/m/Y H:i:s')
        );
        
        // Email headers
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'Reply-To: ' . $email,
            'X-Mailer: PHP/' . phpversion()
        );
        
        // Send email
        $sent = wp_mail($to_email, $subject, $body, $headers);
        
        if ($sent) {
            wp_send_json_success(array(
                'message' => __('Solicitação enviada com sucesso! Entraremos em contato em breve.', 'futturu-cloud-simulator')
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('Erro ao enviar solicitação. Por favor, tente novamente ou entre em contato diretamente por e-mail.', 'futturu-cloud-simulator')
            ));
        }
    }
    
    /**
     * Get plan details
     */
    public function get_plan_details() {
        check_ajax_referer('futturu_cloud_frontend_nonce', 'nonce');
        
        $plan_id = sanitize_text_field($_POST['plan_id']);
        $plan = Futuru_Cloud_Data::get_plan($plan_id);
        
        if (!$plan) {
            wp_send_json_error(array('message' => __('Plano não encontrado.', 'futturu-cloud-simulator')));
        }
        
        $features = Futuru_Cloud_Data::get_features();
        $features_map = array();
        foreach ($features as $feature) {
            $features_map[$feature['id']] = $feature;
        }
        
        $plan_features = isset($plan['features']) ? $plan['features'] : array();
        $feature_details = array();
        foreach ($plan_features as $feature_id) {
            if (isset($features_map[$feature_id])) {
                $feature_details[] = $features_map[$feature_id];
            }
        }
        
        wp_send_json_success(array(
            'plan' => $plan,
            'features' => $feature_details,
            'formatted_price' => Futuru_Cloud_Data::format_price($plan['preco']),
            'formatted_ram' => Futuru_Cloud_Data::format_number($plan['ram'], 'memory'),
            'formatted_cpu' => Futuru_Cloud_Data::format_number($plan['cpu'], 'cpu'),
            'formatted_disco' => Futuru_Cloud_Data::format_number($plan['disco'], 'storage'),
            'formatted_views' => $plan['visualizacoes'] ? Futuru_Cloud_Data::format_number($plan['visualizacoes'], 'views') : 'N/A'
        ));
    }
}
