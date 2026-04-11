<?php
/**
 * AJAX handlers for Futturu Professional Site Simulator
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_PSS_Ajax {
    
    public function __construct() {
        add_action('wp_ajax_futturu_pss_submit_lead', array($this, 'submit_lead'));
        add_action('wp_ajax_nopriv_futturu_pss_submit_lead', array($this, 'submit_lead'));
    }
    
    /**
     * Handle lead submission
     */
    public function submit_lead() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'futturu_pss_nonce')) {
            wp_send_json_error(array('message' => __('Erro de segurança. Tente novamente.', 'futturu-prof-site-sim')));
        }
        
        // Sanitize and validate input
        $data = $this->sanitize_input($_POST);
        
        // Validation
        $errors = $this->validate_input($data);
        if (!empty($errors)) {
            wp_send_json_error(array('message' => implode('<br>', $errors)));
        }
        
        // Prepare email content
        $email_to = get_option('futturu_pss_email_to', 'suporte@futturu.com.br');
        $email_subject = get_option('futturu_pss_email_subject', 'Nova Proposta - Simulador de Site Profissional');
        
        $email_body = $this->prepare_email_body($data);
        
        // Send email
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'Reply-To: ' . $data['lead_email']
        );
        
        $sent = wp_mail($email_to, $email_subject, $email_body, $headers);
        
        if ($sent) {
            // Optionally save to database
            $this->save_lead_to_db($data);
            
            wp_send_json_success(array(
                'message' => __('Proposta enviada com sucesso! Entraremos em contato em breve.', 'futturu-prof-site-sim')
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('Erro ao enviar proposta. Por favor, tente novamente ou entre em contato diretamente.', 'futturu-prof-site-sim')
            ));
        }
    }
    
    /**
     * Sanitize input data
     */
    private function sanitize_input($input) {
        return array(
            'site_type' => sanitize_text_field(isset($input['site_type']) ? $input['site_type'] : ''),
            'business_name' => sanitize_text_field(isset($input['business_name']) ? $input['business_name'] : ''),
            'business_category' => sanitize_text_field(isset($input['business_category']) ? $input['business_category'] : ''),
            'business_location' => sanitize_text_field(isset($input['business_location']) ? $input['business_location'] : ''),
            'business_service' => sanitize_text_field(isset($input['business_service']) ? $input['business_service'] : ''),
            'business_phone' => sanitize_text_field(isset($input['business_phone']) ? $input['business_phone'] : ''),
            'business_address' => sanitize_text_field(isset($input['business_address']) ? $input['business_address'] : ''),
            'lead_name' => sanitize_text_field(isset($input['lead_name']) ? $input['lead_name'] : ''),
            'lead_phone' => sanitize_text_field(isset($input['lead_phone']) ? $input['lead_phone'] : ''),
            'lead_email' => sanitize_email(isset($input['lead_email']) ? $input['lead_email'] : ''),
            'lead_message' => sanitize_textarea_field(isset($input['lead_message']) ? $input['lead_message'] : '')
        );
    }
    
    /**
     * Validate input data
     */
    private function validate_input($data) {
        $errors = array();
        
        // Required fields
        if (empty($data['site_type'])) {
            $errors[] = __('Tipo de site é obrigatório.', 'futturu-prof-site-sim');
        }
        
        if (empty($data['business_name'])) {
            $errors[] = __('Nome do negócio é obrigatório.', 'futturu-prof-site-sim');
        }
        
        if (empty($data['business_category'])) {
            $errors[] = __('Categoria é obrigatória.', 'futturu-prof-site-sim');
        }
        
        if (empty($data['lead_name'])) {
            $errors[] = __('Nome completo é obrigatório.', 'futturu-prof-site-sim');
        }
        
        if (empty($data['lead_phone'])) {
            $errors[] = __('Telefone é obrigatório.', 'futturu-prof-site-sim');
        }
        
        if (empty($data['lead_email'])) {
            $errors[] = __('E-mail é obrigatório.', 'futturu-prof-site-sim');
        } elseif (!is_email($data['lead_email'])) {
            $errors[] = __('E-mail inválido.', 'futturu-prof-site-sim');
        }
        
        return $errors;
    }
    
    /**
     * Prepare email body
     */
    private function prepare_email_body($data) {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
        .section { margin: 20px 0; padding: 15px; background: #f9fafb; border-radius: 5px; }
        .section h3 { color: #2563eb; margin-top: 0; }
        .field { margin: 10px 0; }
        .label { font-weight: bold; color: #555; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nova Proposta - Simulador de Site Profissional</h1>
            <p>Futturu - Hospedagem Cloud Gerenciada</p>
        </div>
        
        <div class="section">
            <h3>📋 Informações do Projeto</h3>
            <div class="field">
                <span class="label">Tipo de Site:</span> ' . esc_html($this->get_site_type_name($data['site_type'])) . '
            </div>
            <div class="field">
                <span class="label">Nome do Negócio:</span> ' . esc_html($data['business_name']) . '
            </div>
            <div class="field">
                <span class="label">Categoria:</span> ' . esc_html($data['business_category']) . '
            </div>
            <div class="field">
                <span class="label">Localidade:</span> ' . esc_html($data['business_location']) . '
            </div>
            <div class="field">
                <span class="label">Serviço Principal:</span> ' . esc_html($data['business_service']) . '
            </div>
            <div class="field">
                <span class="label">Telefone do Negócio:</span> ' . esc_html($data['business_phone']) . '
            </div>
            <div class="field">
                <span class="label">Endereço:</span> ' . esc_html($data['business_address']) . '
            </div>
        </div>
        
        <div class="section">
            <h3>👤 Dados do Cliente</h3>
            <div class="field">
                <span class="label">Nome:</span> ' . esc_html($data['lead_name']) . '
            </div>
            <div class="field">
                <span class="label">Telefone:</span> ' . esc_html($data['lead_phone']) . '
            </div>
            <div class="field">
                <span class="label">E-mail:</span> ' . esc_html($data['lead_email']) . '
            </div>
        </div>
        
        ' . (!empty($data['lead_message']) ? '
        <div class="section">
            <h3>💬 Mensagem</h3>
            <p>' . nl2br(esc_html($data['lead_message'])) . '</p>
        </div>
        ' : '') . '
        
        <div class="footer">
            <p>Lead gerado pelo Simulador de Site Profissional Futturu</p>
            <p>Data: ' . date_i18n(get_option('date_format') . ' ' . get_option('time_format')) . '</p>
        </div>
    </div>
</body>
</html>';
        
        return $html;
    }
    
    /**
     * Get site type name from slug
     */
    private function get_site_type_name($slug) {
        $types_raw = get_option('futturu_pss_site_types');
        $types = is_serialized($types_raw) ? unserialize($types_raw) : array();
        
        return isset($types[$slug]) ? $types[$slug] : ucfirst($slug);
    }
    
    /**
     * Save lead to database (optional)
     */
    private function save_lead_to_db($data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'futturu_pss_leads';
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            return false;
        }
        
        $inserted = $wpdb->insert(
            $table_name,
            array(
                'site_type' => $data['site_type'],
                'business_name' => $data['business_name'],
                'business_category' => $data['business_category'],
                'business_location' => $data['business_location'],
                'business_service' => $data['business_service'],
                'business_phone' => $data['business_phone'],
                'business_address' => $data['business_address'],
                'lead_name' => $data['lead_name'],
                'lead_phone' => $data['lead_phone'],
                'lead_email' => $data['lead_email'],
                'lead_message' => $data['lead_message'],
                'created_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
        
        return $inserted !== false;
    }
}
