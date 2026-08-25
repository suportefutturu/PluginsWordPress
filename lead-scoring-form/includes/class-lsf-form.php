<?php
/**
 * Form Handler Class
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class LSF_Form {
    
    public function __construct() {
        add_action('wp_ajax_lsf_submit_form', array($this, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_lsf_submit_form', array($this, 'handle_form_submission'));
    }
    
    /**
     * Render the form
     */
    public static function render_form($atts) {
        $options = get_option('lsf_settings');
        
        // Get colors from settings
        $primary_color = isset($options['primary_color']) ? $options['primary_color'] : '#2563eb';
        $secondary_color = isset($options['secondary_color']) ? $options['secondary_color'] : '#1e40af';
        $background_color = isset($options['background_color']) ? $options['background_color'] : '#ffffff';
        $text_color = isset($options['text_color']) ? $options['text_color'] : '#1f2937';
        $button_color = isset($options['button_color']) ? $options['button_color'] : '#2563eb';
        $button_text_color = isset($options['button_text_color']) ? $options['button_text_color'] : '#ffffff';
        
        // Get texts from settings
        $form_title = isset($options['form_title']) ? $options['form_title'] : 'Pare de perder dinheiro com um site que não converte.';
        $form_subtitle = isset($options['form_subtitle']) ? $options['form_subtitle'] : 'Preencha o breve diagnóstico abaixo. Em até 24h, um de nossos especialistas em criação de site em Belém enviará uma análise personalizada do seu cenário atual e as melhores opções de investimento para o seu negócio.';
        $button_text = isset($options['button_text']) ? $options['button_text'] : 'Solicitar Meu Diagnóstico de Criação de Site e Receber Opções';
        
        ob_start();
        ?>
        <div class="lsf-form-container" style="--lsf-primary: <?php echo esc_attr($primary_color); ?>; --lsf-secondary: <?php echo esc_attr($secondary_color); ?>; --lsf-bg: <?php echo esc_attr($background_color); ?>; --lsf-text: <?php echo esc_attr($text_color); ?>; --lsf-button: <?php echo esc_attr($button_color); ?>; --lsf-button-text: <?php echo esc_attr($button_text_color); ?>;">
            <div class="lsf-form-wrapper">
                <div class="lsf-form-header">
                    <h2><?php echo esc_html($form_title); ?></h2>
                    <p class="lsf-subtitle"><?php echo esc_html($form_subtitle); ?></p>
                </div>
                
                <form id="lsf-lead-form" class="lsf-form" method="post">
                    <?php wp_nonce_field('lsf_form_nonce', 'lsf_nonce'); ?>
                    
                    <div class="lsf-form-row">
                        <div class="lsf-form-group">
                            <label for="lsf_name"><?php _e('Nome Completo', 'lead-scoring-form'); ?> <span class="lsf-required">*</span></label>
                            <input type="text" id="lsf_name" name="lsf_name" required placeholder="<?php _e('Digite seu nome completo', 'lead-scoring-form'); ?>" />
                        </div>
                    </div>
                    
                    <div class="lsf-form-row">
                        <div class="lsf-form-group">
                            <label for="lsf_company"><?php _e('Nome da Empresa / Site Atual (se houver)', 'lead-scoring-form'); ?></label>
                            <input type="text" id="lsf_company" name="lsf_company" placeholder="<?php _e('Digite o nome da sua empresa ou site', 'lead-scoring-form'); ?>" />
                        </div>
                    </div>
                    
                    <div class="lsf-form-row">
                        <div class="lsf-form-group">
                            <label for="lsf_contact"><?php _e('WhatsApp / E-mail', 'lead-scoring-form'); ?> <span class="lsf-required">*</span></label>
                            <input type="text" id="lsf_contact" name="lsf_contact" required placeholder="<?php _e('(XX) XXXXX-XXXX ou seu@email.com', 'lead-scoring-form'); ?>" />
                        </div>
                    </div>
                    
                    <div class="lsf-form-row">
                        <div class="lsf-form-group">
                            <label for="lsf_objective"><?php _e('1. Qual é o seu principal objetivo hoje?', 'lead-scoring-form'); ?> <span class="lsf-required">*</span></label>
                            <select id="lsf_objective" name="lsf_objective" required>
                                <option value=""><?php _e('Selecione uma opção', 'lead-scoring-form'); ?></option>
                                <option value="new_clients"><?php _e('Atrair novos clientes e aumentar vendas', 'lead-scoring-form'); ?></option>
                                <option value="redesign"><?php _e('Reformular um site atual que está lento/inseguro', 'lead-scoring-form'); ?></option>
                                <option value="new_project"><?php _e('Criar um novo projeto do zero', 'lead-scoring-form'); ?></option>
                                <option value="research"><?php _e('Apenas estou pesquisando preços no momento', 'lead-scoring-form'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="lsf-form-row">
                        <div class="lsf-form-group">
                            <label for="lsf_deadline"><?php _e('2. Qual o prazo ideal para o início do projeto?', 'lead-scoring-form'); ?> <span class="lsf-required">*</span></label>
                            <select id="lsf_deadline" name="lsf_deadline" required>
                                <option value=""><?php _e('Selecione uma opção', 'lead-scoring-form'); ?></option>
                                <option value="immediate"><?php _e('Imediato (Preciso resolver essa semana)', 'lead-scoring-form'); ?></option>
                                <option value="1_3_months"><?php _e('1 a 3 meses (Estou planejando)', 'lead-scoring-form'); ?></option>
                                <option value="no_deadline"><?php _e('Sem prazo definido', 'lead-scoring-form'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="lsf-form-row">
                        <div class="lsf-form-group">
                            <label for="lsf_investment"><?php _e('3. Qual modelo de investimento faz mais sentido para sua empresa hoje?', 'lead-scoring-form'); ?> <span class="lsf-required">*</span></label>
                            <select id="lsf_investment" name="lsf_investment" required>
                                <option value=""><?php _e('Selecione uma opção', 'lead-scoring-form'); ?></option>
                                <option value="one_time"><?php _e('Pagamento Único (Investimento inicial maior, escopo fechado)', 'lead-scoring-form'); ?></option>
                                <option value="waas"><?php _e('WaaS - Mensalidade previsível (Inclui hospedagem, manutenção e otimização contínua)', 'lead-scoring-form'); ?></option>
                                <option value="unsure"><?php _e('Ainda não sei, preciso de orientação', 'lead-scoring-form'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="lsf-form-row lsf-submit-row">
                        <button type="submit" id="lsf-submit-btn" class="lsf-submit-button">
                            <span class="lsf-btn-text"><?php echo esc_html($button_text); ?></span>
                            <span class="lsf-btn-loading" style="display: none;"><?php _e('Enviando...', 'lead-scoring-form'); ?></span>
                        </button>
                    </div>
                    
                    <div id="lsf-message" class="lsf-message" style="display: none;"></div>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Handle form submission
     */
    public function handle_form_submission() {
        // Verify nonce
        if (!isset($_POST['lsf_nonce']) || !wp_verify_nonce($_POST['lsf_nonce'], 'lsf_form_nonce')) {
            wp_send_json_error(array('message' => __('Erro de segurança. Por favor, tente novamente.', 'lead-scoring-form')));
        }
        
        // Sanitize and validate inputs
        $name = sanitize_text_field($_POST['lsf_name']);
        $company = sanitize_text_field($_POST['lsf_company']);
        $contact = sanitize_text_field($_POST['lsf_contact']);
        $objective = sanitize_text_field($_POST['lsf_objective']);
        $deadline = sanitize_text_field($_POST['lsf_deadline']);
        $investment = sanitize_text_field($_POST['lsf_investment']);
        
        // Validation
        if (empty($name) || empty($contact) || empty($objective) || empty($deadline) || empty($investment)) {
            wp_send_json_error(array('message' => __('Por favor, preencha todos os campos obrigatórios.', 'lead-scoring-form')));
        }
        
        // Calculate lead score
        $score = $this->calculate_score($objective, $deadline, $investment);
        $priority = $this->determine_priority($score);
        
        // Save to database
        global $wpdb;
        $table_name = $wpdb->prefix . 'lsf_leads';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'company' => $company,
                'contact' => $contact,
                'objective' => $objective,
                'deadline' => $deadline,
                'investment_model' => $investment,
                'score' => $score,
                'priority' => $priority
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s')
        );
        
        if ($result === false) {
            wp_send_json_error(array('message' => __('Erro ao salvar lead. Por favor, tente novamente.', 'lead-scoring-form')));
        }
        
        // Send email notification
        $email_data = array(
            'name' => $name,
            'company' => $company,
            'contact' => $contact,
            'objective' => $this->get_label('objective', $objective),
            'deadline' => $this->get_label('deadline', $deadline),
            'investment' => $this->get_label('investment', $investment),
            'score' => $score,
            'priority' => $priority
        );
        
        do_action('lsf_send_email', $email_data);
        
        // Get success message from settings
        $options = get_option('lsf_settings');
        $success_message = isset($options['success_message']) ? $options['success_message'] : 'Obrigado! Seu diagnóstico foi enviado com sucesso. Nossa equipe entrará em contato em até 24 horas.';
        
        wp_send_json_success(array(
            'message' => $success_message
        ));
    }
    
    /**
     * Calculate lead score based on responses
     */
    private function calculate_score($objective, $deadline, $investment) {
        $score = 0;
        
        // Objective scoring (max 40 points)
        switch($objective) {
            case 'new_clients':
                $score += 40; // High intent to buy
                break;
            case 'redesign':
                $score += 35; // Already has site, ready to invest
                break;
            case 'new_project':
                $score += 30; // New project, good potential
                break;
            case 'research':
                $score += 10; // Just researching, low priority
                break;
        }
        
        // Deadline scoring (max 35 points)
        switch($deadline) {
            case 'immediate':
                $score += 35; // Urgent, high priority
                break;
            case '1_3_months':
                $score += 25; // Planning, medium priority
                break;
            case 'no_deadline':
                $score += 10; // No urgency, low priority
                break;
        }
        
        // Investment model scoring (max 25 points)
        switch($investment) {
            case 'one_time':
                $score += 25; // Clear budget, ready to invest
                break;
            case 'waas':
                $score += 20; // Recurring revenue model
                break;
            case 'unsure':
                $score += 15; // Needs guidance, but interested
                break;
        }
        
        return $score;
    }
    
    /**
     * Determine priority based on score
     */
    private function determine_priority($score) {
        if ($score >= 70) {
            return 'high';
        } elseif ($score >= 45) {
            return 'medium';
        } else {
            return 'low';
        }
    }
    
    /**
     * Get human-readable labels
     */
    private function get_label($type, $value) {
        $labels = array(
            'objective' => array(
                'new_clients' => __('Atrair novos clientes e aumentar vendas', 'lead-scoring-form'),
                'redesign' => __('Reformular um site atual que está lento/inseguro', 'lead-scoring-form'),
                'new_project' => __('Criar um novo projeto do zero', 'lead-scoring-form'),
                'research' => __('Apenas estou pesquisando preços no momento', 'lead-scoring-form')
            ),
            'deadline' => array(
                'immediate' => __('Imediato (Preciso resolver essa semana)', 'lead-scoring-form'),
                '1_3_months' => __('1 a 3 meses (Estou planejando)', 'lead-scoring-form'),
                'no_deadline' => __('Sem prazo definido', 'lead-scoring-form')
            ),
            'investment' => array(
                'one_time' => __('Pagamento Único (Investimento inicial maior, escopo fechado)', 'lead-scoring-form'),
                'waas' => __('WaaS - Mensalidade previsível (Inclui hospedagem, manutenção e otimização contínua)', 'lead-scoring-form'),
                'unsure' => __('Ainda não sei, preciso de orientação', 'lead-scoring-form')
            )
        );
        
        return isset($labels[$type][$value]) ? $labels[$type][$value] : $value;
    }
}
