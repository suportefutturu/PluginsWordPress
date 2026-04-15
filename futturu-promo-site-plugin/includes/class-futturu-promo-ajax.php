<?php
/**
 * AJAX handlers for Futturu Promo Plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_Promo_Ajax {
    
    public function __construct() {
        // For logged-in users
        add_action('wp_ajax_futturu_promo_submit', array($this, 'handle_form_submission'));
        // For non-logged-in users
        add_action('wp_ajax_nopriv_futturu_promo_submit', array($this, 'handle_form_submission'));
    }
    
    /**
     * Handle form submission
     */
    public function handle_form_submission() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'futturu_promo_nonce')) {
            wp_send_json_error(array('message' => __('Erro de segurança. Tente novamente.', 'futturu-promo-site')));
        }
        
        // Sanitize and validate input
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $company = isset($_POST['company']) ? sanitize_text_field($_POST['company']) : '';
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
        $payment_method = isset($_POST['payment_method']) ? sanitize_text_field($_POST['payment_method']) : '';
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        
        // Validation
        $errors = array();
        
        if (empty($name)) {
            $errors[] = __('Nome é obrigatório.', 'futturu-promo-site');
        }
        
        if (empty($email) || !is_email($email)) {
            $errors[] = __('E-mail válido é obrigatório.', 'futturu-promo-site');
        }
        
        if (empty($phone)) {
            $errors[] = __('Telefone é obrigatório.', 'futturu-promo-site');
        }
        
        if (empty($payment_method)) {
            $errors[] = __('Forma de pagamento é obrigatória.', 'futturu-promo-site');
        }
        
        if ($amount <= 0) {
            $errors[] = __('Valor pago deve ser maior que zero.', 'futturu-promo-site');
        }
        
        if (!empty($errors)) {
            wp_send_json_error(array('message' => implode(' ', $errors)));
        }
        
        // Handle file upload if present
        $receipt_url = '';
        if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = array('image/jpeg', 'image/png', 'image/jpg');
            $file_type = $_FILES['receipt']['type'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($file_type, $allowed_types)) {
                wp_send_json_error(array('message' => __('Apenas imagens JPG e PNG são permitidas.', 'futturu-promo-site')));
            }
            
            if ($_FILES['receipt']['size'] > $max_size) {
                wp_send_json_error(array('message' => __('O arquivo deve ter no máximo 5MB.', 'futturu-promo-site')));
            }
            
            // Upload file
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            
            $upload_overrides = array('test_form' => false);
            $uploaded_file = wp_handle_upload($_FILES['receipt'], $upload_overrides);
            
            if ($uploaded_file && !isset($uploaded_file['error'])) {
                $receipt_url = $uploaded_file['url'];
            } else {
                wp_send_json_error(array('message' => __('Erro ao fazer upload do comprovante.', 'futturu-promo-site')));
            }
        }
        
        // Get admin email
        $admin_email = get_option('futturu_promo_email', 'suporte@futturu.com.br');
        
        // Prepare email content
        $subject = sprintf(__('Novo Lead - Promoção Site Institucional | %s', 'futturu-promo-site'), $name);
        
        $email_body = sprintf(
            __("Novo lead capturado pela promoção do Site Institucional!\n\n" .
            "=== DADOS DO LEAD ===\n" .
            "Nome: %s\n" .
            "E-mail: %s\n" .
            "Telefone: %s\n" .
            "Empresa: %s\n" .
            "Mensagem: %s\n\n" .
            "=== DADOS DO PAGAMENTO ===\n" .
            "Forma de Pagamento: %s\n" .
            "Valor Pago: R$ %.2f\n" .
            "Comprovante: %s\n\n" .
            "=== DATA/HORA ===\n" .
            "Data: %s\n" .
            "IP: %s\n\n" .
            "---\n" .
            "Este e-mail foi enviado automaticamente pelo plugin Futturu Promoção.", 'futturu-promo-site'),
            $name,
            $email,
            $phone,
            $company,
            $message,
            $payment_method,
            $amount,
            $receipt_url ? $receipt_url : __('Não enviado', 'futturu-promo-site'),
            current_time('mysql'),
            $_SERVER['REMOTE_ADDR']
        );
        
        // Set headers
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'Reply-To: ' . $email
        );
        
        // Format email as HTML
        $html_email_body = $this->format_email_html($name, $email, $phone, $company, $message, $payment_method, $amount, $receipt_url);
        
        // Send email
        $email_sent = wp_mail($admin_email, $subject, $html_email_body, $headers);
        
        // Store lead in database (custom post type or option)
        $lead_data = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'message' => $message,
            'payment_method' => $payment_method,
            'amount' => $amount,
            'receipt_url' => $receipt_url,
            'date' => current_time('mysql'),
            'ip' => $_SERVER['REMOTE_ADDR']
        );
        
        // Save to options table (simple approach for MVP)
        $leads = get_option('futturu_promo_leads', array());
        $leads[] = $lead_data;
        update_option('futturu_promo_leads', $leads, false);
        
        if ($email_sent) {
            wp_send_json_success(array(
                'message' => __('Dados enviados com sucesso! Entraremos em contato em breve.', 'futturu-promo-site')
            ));
        } else {
            // Even if email fails, we saved the lead
            wp_send_json_success(array(
                'message' => __('Dados registrados com sucesso! Entraremos em contato em breve.', 'futturu-promo-site'),
                'warning' => __('Houve um problema ao enviar o e-mail, mas seus dados foram registrados.', 'futturu-promo-site')
            ));
        }
    }
    
    /**
     * Format email as HTML
     */
    private function format_email_html($name, $email, $phone, $company, $message, $payment_method, $amount, $receipt_url) {
        $html = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
        $html .= '<h2 style="color: #333; border-bottom: 2px solid #0073aa; padding-bottom: 10px;">' . __('Novo Lead - Promoção Site Institucional', 'futturu-promo-site') . '</h2>';
        
        $html .= '<div style="background: #f9f9f9; padding: 20px; margin: 20px 0; border-radius: 5px;">';
        $html .= '<h3 style="color: #0073aa; margin-top: 0;">' . __('Dados do Lead', 'futturu-promo-site') . '</h3>';
        $html .= '<p><strong>' . __('Nome:', 'futturu-promo-site') . '</strong> ' . esc_html($name) . '</p>';
        $html .= '<p><strong>' . __('E-mail:', 'futturu-promo-site') . '</strong> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></p>';
        $html .= '<p><strong>' . __('Telefone:', 'futturu-promo-site') . '</strong> ' . esc_html($phone) . '</p>';
        $html .= '<p><strong>' . __('Empresa:', 'futturu-promo-site') . '</strong> ' . esc_html($company) . '</p>';
        if ($message) {
            $html .= '<p><strong>' . __('Mensagem:', 'futturu-promo-site') . '</strong><br>' . nl2br(esc_html($message)) . '</p>';
        }
        $html .= '</div>';
        
        $html .= '<div style="background: #f0f9ff; padding: 20px; margin: 20px 0; border-radius: 5px;">';
        $html .= '<h3 style="color: #0073aa; margin-top: 0;">' . __('Dados do Pagamento', 'futturu-promo-site') . '</h3>';
        $html .= '<p><strong>' . __('Forma de Pagamento:', 'futturu-promo-site') . '</strong> ' . esc_html($payment_method) . '</p>';
        $html .= '<p><strong>' . __('Valor Pago:', 'futturu-promo-site') . '</strong> R$ ' . number_format($amount, 2, ',', '.') . '</p>';
        if ($receipt_url) {
            $html .= '<p><strong>' . __('Comprovante:', 'futturu-promo-site') . '</strong> <a href="' . esc_url($receipt_url) . '" target="_blank">' . __('Ver Comprovante', 'futturu-promo-site') . '</a></p>';
        }
        $html .= '</div>';
        
        $html .= '<div style="color: #666; font-size: 12px; margin-top: 20px;">';
        $html .= '<p><strong>' . __('Data/Hora:', 'futturu-promo-site') . '</strong> ' . current_time('d/m/Y H:i:s') . '</p>';
        $html .= '<p><strong>IP:</strong> ' . $_SERVER['REMOTE_ADDR'] . '</p>';
        $html .= '</div>';
        
        $html .= '<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #999; font-size: 11px;">';
        $html .= __('Este e-mail foi enviado automaticamente pelo plugin Futturu Promoção.', 'futturu-promo-site');
        $html .= '</div>';
        
        $html .= '</div>';
        
        return $html;
    }
}
