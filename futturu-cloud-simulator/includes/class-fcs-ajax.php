<?php
/**
 * FCS_Ajax Class
 * Handles AJAX requests for quote submission
 */

if (!defined('ABSPATH')) {
    exit;
}

class FCS_Ajax {

    /**
     * Handle quote request submission
     */
    public static function handle_quote_request() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'fcs_frontend_nonce')) {
            wp_send_json_error(array('message' => __('Erro de validação. Tente novamente.', 'futturu-cloud-simulator')));
        }

        // Sanitize and validate input
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $company = isset($_POST['company']) ? sanitize_text_field($_POST['company']) : '';
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
        $plan_interest = isset($_POST['plan_interest']) ? sanitize_text_field($_POST['plan_interest']) : '';

        // Validate required fields
        if (empty($name) || empty($email) || empty($phone)) {
            wp_send_json_error(array('message' => __('Por favor, preencha todos os campos obrigatórios.', 'futturu-cloud-simulator')));
        }

        // Validate email
        if (!is_email($email)) {
            wp_send_json_error(array('message' => __('Por favor, informe um e-mail válido.', 'futturu-cloud-simulator')));
        }

        // Get admin email from options
        $options = FCS_Plans::get_options();
        $to_email = $options['cta_email'];

        // Prepare email subject
        $subject = sprintf(
            __('Nova Solicitação de Cotação - Plano: %s', 'futturu-cloud-simulator'),
            !empty($plan_interest) ? $plan_interest : __('Não especificado', 'futturu-cloud-simulator')
        );

        // Prepare email body
        $body = sprintf(
            __("Nova solicitação de cotação recebida pelo Simulador Futturu:\n\n" .
               "Dados do Cliente:\n" .
               "------------------\n" .
               "Nome: %s\n" .
               "E-mail: %s\n" .
               "Telefone/WhatsApp: %s\n" .
               "Empresa: %s\n" .
               "Plano de Interesse: %s\n" .
               "\n" .
               "Mensagem:\n" .
               "---------\n" .
               "%s\n" .
               "\n" .
               "---\n" .
               "Enviado via Simulador de Hospedagem Futturu\n" .
               "Data: %s",
               'futturu-cloud-simulator'),
            $name,
            $email,
            $phone,
            !empty($company) ? $company : __('Não informada', 'futturu-cloud-simulator'),
            !empty($plan_interest) ? $plan_interest : __('Não especificado', 'futturu-cloud-simulator'),
            !empty($message) ? $message : __('Sem mensagem adicional.', 'futturu-cloud-simulator'),
            current_time('d/m/Y H:i:s')
        );

        // Email headers
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'Reply-To: ' . $email,
            'From: Simulador Futturu <wordpress@' . self::get_site_domain() . '>'
        );

        // Send email
        $sent = wp_mail($to_email, $subject, $body, $headers);

        if ($sent) {
            // Optionally save to database for backup
            self::save_quote_request($name, $email, $phone, $company, $message, $plan_interest);

            wp_send_json_success(array(
                'message' => __('Solicitação enviada com sucesso! Nossa equipe entrará em contato em breve.', 'futturu-cloud-simulator')
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('Erro ao enviar solicitação. Por favor, tente novamente ou entre em contato diretamente por e-mail.', 'futturu-cloud-simulator')
            ));
        }
    }

    /**
     * Save quote request to database (optional backup)
     */
    private static function save_quote_request($name, $email, $phone, $company, $message, $plan_interest) {
        global $wpdb;
        $table = $wpdb->prefix . 'fcs_quotes';

        // Create table if not exists (on first save)
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(50) NOT NULL,
            company varchar(100) DEFAULT '',
            message text DEFAULT '',
            plan_interest varchar(100) DEFAULT '',
            status varchar(20) DEFAULT 'new',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY email (email)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Insert record
        $wpdb->insert($table, array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'message' => $message,
            'plan_interest' => $plan_interest,
            'status' => 'new'
        ));
    }

    /**
     * Get site domain for email sender
     */
    private static function get_site_domain() {
        return parse_url(home_url(), PHP_URL_HOST);
    }
}
