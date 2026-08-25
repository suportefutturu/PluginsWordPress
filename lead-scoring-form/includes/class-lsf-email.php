<?php
/**
 * Email Handler Class
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class LSF_Email {
    
    public function __construct() {
        add_action('lsf_send_email', array($this, 'send_notification_email'));
    }
    
    /**
     * Send notification email to admin
     */
    public function send_notification_email($data) {
        $options = get_option('lsf_settings');
        $to = isset($options['email_to']) ? $options['email_to'] : 'suporte@futturu.com.br';
        
        // Priority labels
        $priority_labels = array(
            'high' => 'ALTA PRIORIDADE 🔴',
            'medium' => 'MÉDIA PRIORIDADE 🟡',
            'low' => 'BAIXA PRIORIDADE 🟢'
        );
        
        $priority_label = isset($priority_labels[$data['priority']]) ? $priority_labels[$data['priority']] : $data['priority'];
        
        // Email subject
        $subject = sprintf(
            __('[Lead Scoring] Novo Lead - %s - Score: %d (%s)', 'lead-scoring-form'),
            $data['name'],
            $data['score'],
            $priority_label
        );
        
        // Email headers
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'Reply-To: ' . $data['contact']
        );
        
        // Email body
        $message = $this->get_email_template($data, $priority_label);
        
        // Send email
        wp_mail($to, $subject, $message, $headers);
    }
    
    /**
     * Get email HTML template
     */
    private function get_email_template($data, $priority_label) {
        $options = get_option('lsf_settings');
        $primary_color = isset($options['primary_color']) ? $options['primary_color'] : '#2563eb';
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                    line-height: 1.6;
                    color: #1f2937;
                    background-color: #f3f4f6;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    max-width: 600px;
                    margin: 40px auto;
                    background: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
                .email-header {
                    background: <?php echo esc_attr($primary_color); ?>;
                    color: #ffffff;
                    padding: 30px;
                    text-align: center;
                }
                .email-header h1 {
                    margin: 0 0 10px 0;
                    font-size: 24px;
                }
                .priority-badge {
                    display: inline-block;
                    background: rgba(255, 255, 255, 0.2);
                    padding: 8px 16px;
                    border-radius: 20px;
                    font-size: 14px;
                    font-weight: 600;
                }
                .email-body {
                    padding: 30px;
                }
                .info-section {
                    margin-bottom: 25px;
                }
                .info-label {
                    font-size: 12px;
                    text-transform: uppercase;
                    color: #6b7280;
                    font-weight: 600;
                    letter-spacing: 0.5px;
                    margin-bottom: 5px;
                }
                .info-value {
                    font-size: 16px;
                    color: #1f2937;
                    padding: 12px;
                    background: #f9fafb;
                    border-radius: 6px;
                    border-left: 3px solid <?php echo esc_attr($primary_color); ?>;
                }
                .score-box {
                    background: linear-gradient(135deg, <?php echo esc_attr($primary_color); ?> 0%, <?php echo esc_attr($this->adjust_color($primary_color, -20)); ?> 100%);
                    color: #ffffff;
                    padding: 20px;
                    border-radius: 8px;
                    text-align: center;
                    margin: 25px 0;
                }
                .score-number {
                    font-size: 48px;
                    font-weight: 700;
                    display: block;
                }
                .score-label {
                    font-size: 14px;
                    opacity: 0.9;
                }
                .cta-button {
                    display: inline-block;
                    background: <?php echo esc_attr($primary_color); ?>;
                    color: #ffffff;
                    text-decoration: none;
                    padding: 14px 28px;
                    border-radius: 6px;
                    font-weight: 600;
                    margin-top: 20px;
                }
                .email-footer {
                    background: #f9fafb;
                    padding: 20px 30px;
                    text-align: center;
                    font-size: 12px;
                    color: #6b7280;
                    border-top: 1px solid #e5e7eb;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">
                    <h1>🎯 Novo Lead Recebido</h1>
                    <div class="priority-badge"><?php echo esc_html($priority_label); ?></div>
                </div>
                
                <div class="email-body">
                    <div class="score-box">
                        <span class="score-number"><?php echo intval($data['score']); ?>/100</span>
                        <span class="score-label">Score de Qualificação</span>
                    </div>
                    
                    <div class="info-section">
                        <div class="info-label"><?php _e('Nome Completo', 'lead-scoring-form'); ?></div>
                        <div class="info-value"><?php echo esc_html($data['name']); ?></div>
                    </div>
                    
                    <?php if (!empty($data['company'])): ?>
                    <div class="info-section">
                        <div class="info-label"><?php _e('Empresa / Site', 'lead-scoring-form'); ?></div>
                        <div class="info-value"><?php echo esc_html($data['company']); ?></div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="info-section">
                        <div class="info-label"><?php _e('Contato', 'lead-scoring-form'); ?></div>
                        <div class="info-value"><?php echo esc_html($data['contact']); ?></div>
                    </div>
                    
                    <div class="info-section">
                        <div class="info-label"><?php _e('Objetivo Principal', 'lead-scoring-form'); ?></div>
                        <div class="info-value"><?php echo esc_html($data['objective']); ?></div>
                    </div>
                    
                    <div class="info-section">
                        <div class="info-label"><?php _e('Prazo Ideal', 'lead-scoring-form'); ?></div>
                        <div class="info-value"><?php echo esc_html($data['deadline']); ?></div>
                    </div>
                    
                    <div class="info-section">
                        <div class="info-label"><?php _e('Modelo de Investimento', 'lead-scoring-form'); ?></div>
                        <div class="info-value"><?php echo esc_html($data['investment']); ?></div>
                    </div>
                    
                    <div style="text-align: center;">
                        <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^0-9]/', '', $data['contact'])); ?>" class="cta-button">
                            💬 Entrar em contato via WhatsApp
                        </a>
                    </div>
                </div>
                
                <div class="email-footer">
                    <p><?php _e('Este lead foi classificado automaticamente pelo sistema Lead Scoring Form.', 'lead-scoring-form'); ?></p>
                    <p><?php _e('Entre em contato o mais breve possível para maximizar as chances de conversão.', 'lead-scoring-form'); ?></p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Adjust color brightness
     */
    private function adjust_color($hex, $steps) {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));
        
        return '#' . substr('0' . dechex($r), -2) . substr('0' . dechex($g), -2) . substr('0' . dechex($b), -2);
    }
}
