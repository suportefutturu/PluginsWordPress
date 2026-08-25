<?php
/**
 * Admin Settings Class
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class LSF_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Lead Scoring Form', 'lead-scoring-form'),
            __('Lead Scoring', 'lead-scoring-form'),
            'manage_options',
            'lead-scoring-form',
            array($this, 'render_settings_page'),
            'dashicons-analytics',
            30
        );
        
        add_submenu_page(
            'lead-scoring-form',
            __('Configurações', 'lead-scoring-form'),
            __('Configurações', 'lead-scoring-form'),
            'manage_options',
            'lead-scoring-form',
            array($this, 'render_settings_page')
        );
        
        add_submenu_page(
            'lead-scoring-form',
            __('Leads Recebidos', 'lead-scoring-form'),
            __('Leads Recebidos', 'lead-scoring-form'),
            'manage_options',
            'lsf-leads',
            array($this, 'render_leads_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('lsf_settings_group', 'lsf_settings', array(
            'sanitize_callback' => array($this, 'sanitize_settings')
        ));
        
        add_settings_section(
            'lsf_email_section',
            __('Configurações de E-mail', 'lead-scoring-form'),
            array($this, 'render_email_section'),
            'lead-scoring-form'
        );
        
        add_settings_field(
            'lsf_email_to',
            __('E-mail de Destino', 'lead-scoring-form'),
            array($this, 'render_email_to_field'),
            'lead-scoring-form',
            'lsf_email_section'
        );
        
        add_settings_section(
            'lsf_texts_section',
            __('Textos do Formulário', 'lead-scoring-form'),
            array($this, 'render_texts_section'),
            'lead-scoring-form'
        );
        
        add_settings_field(
            'lsf_form_title',
            __('Título do Formulário', 'lead-scoring-form'),
            array($this, 'render_text_field'),
            'lead-scoring-form',
            'lsf_texts_section',
            array('field' => 'form_title', 'default' => 'Pare de perder dinheiro com um site que não converte.')
        );
        
        add_settings_field(
            'lsf_form_subtitle',
            __('Subtítulo', 'lead-scoring-form'),
            array($this, 'render_textarea_field'),
            'lead-scoring-form',
            'lsf_texts_section',
            array('field' => 'form_subtitle', 'default' => 'Preencha o breve diagnóstico abaixo. Em até 24h, um de nossos especialistas em criação de site em Belém enviará uma análise personalizada do seu cenário atual e as melhores opções de investimento para o seu negócio.')
        );
        
        add_settings_field(
            'lsf_button_text',
            __('Texto do Botão', 'lead-scoring-form'),
            array($this, 'render_text_field'),
            'lead-scoring-form',
            'lsf_texts_section',
            array('field' => 'button_text', 'default' => 'Solicitar Meu Diagnóstico de Criação de Site e Receber Opções')
        );
        
        add_settings_field(
            'lsf_success_message',
            __('Mensagem de Sucesso', 'lead-scoring-form'),
            array($this, 'render_textarea_field'),
            'lead-scoring-form',
            'lsf_texts_section',
            array('field' => 'success_message', 'default' => "Obrigado! Seu diagnóstico foi enviado com sucesso.\n\nNossa equipe analisará suas respostas e entrará em contato em até 24 horas pelo WhatsApp ou E-mail informado.\n\n📋 Próximos passos:\n1. Nossa equipe irá analisar seu perfil e necessidades\n2. Você receberá uma análise personalizada do seu cenário atual\n3. Enviaremos as melhores opções de investimento para o seu negócio\n\nFique atento ao seu e-mail e WhatsApp!")
        );
        
        add_settings_section(
            'lsf_design_section',
            __('Cores e Design', 'lead-scoring-form'),
            array($this, 'render_design_section'),
            'lead-scoring-form'
        );
        
        add_settings_field(
            'lsf_primary_color',
            __('Cor Primária', 'lead-scoring-form'),
            array($this, 'render_color_field'),
            'lead-scoring-form',
            'lsf_design_section',
            array('field' => 'primary_color')
        );
        
        add_settings_field(
            'lsf_secondary_color',
            __('Cor Secundária', 'lead-scoring-form'),
            array($this, 'render_color_field'),
            'lead-scoring-form',
            'lsf_design_section',
            array('field' => 'secondary_color')
        );
        
        add_settings_field(
            'lsf_background_color',
            __('Cor de Fundo', 'lead-scoring-form'),
            array($this, 'render_color_field'),
            'lead-scoring-form',
            'lsf_design_section',
            array('field' => 'background_color')
        );
        
        add_settings_field(
            'lsf_text_color',
            __('Cor do Texto', 'lead-scoring-form'),
            array($this, 'render_color_field'),
            'lead-scoring-form',
            'lsf_design_section',
            array('field' => 'text_color')
        );
        
        add_settings_field(
            'lsf_button_color',
            __('Cor do Botão', 'lead-scoring-form'),
            array($this, 'render_color_field'),
            'lead-scoring-form',
            'lsf_design_section',
            array('field' => 'button_color')
        );
        
        add_settings_field(
            'lsf_button_text_color',
            __('Cor do Texto do Botão', 'lead-scoring-form'),
            array($this, 'render_color_field'),
            'lead-scoring-form',
            'lsf_design_section',
            array('field' => 'button_text_color')
        );
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        $sanitized['email_to'] = sanitize_email($input['email_to']);
        $sanitized['primary_color'] = sanitize_hex_color($input['primary_color']);
        $sanitized['secondary_color'] = sanitize_hex_color($input['secondary_color']);
        $sanitized['background_color'] = sanitize_hex_color($input['background_color']);
        $sanitized['text_color'] = sanitize_hex_color($input['text_color']);
        $sanitized['button_color'] = sanitize_hex_color($input['button_color']);
        $sanitized['button_text_color'] = sanitize_hex_color($input['button_text_color']);
        $sanitized['form_title'] = sanitize_text_field($input['form_title']);
        $sanitized['form_subtitle'] = wp_kses_post($input['form_subtitle']);
        $sanitized['button_text'] = sanitize_text_field($input['button_text']);
        $sanitized['success_message'] = wp_kses_post($input['success_message']);
        
        return $sanitized;
    }
    
    /**
     * Render texts section description
     */
    public function render_texts_section() {
        echo '<p>' . __('Personalize os textos do formulário para adaptar à sua comunicação.', 'lead-scoring-form') . '</p>';
    }
    
    /**
     * Render text field
     */
    public function render_text_field($args) {
        $options = get_option('lsf_settings');
        $field = $args['field'];
        $default = isset($args['default']) ? $args['default'] : '';
        $value = isset($options[$field]) ? $options[$field] : $default;
        
        echo '<input type="text" name="lsf_settings[' . esc_attr($field) . ']" value="' . esc_attr($value) . '" class="regular-text" style="max-width: 600px;" />';
    }
    
    /**
     * Render textarea field
     */
    public function render_textarea_field($args) {
        $options = get_option('lsf_settings');
        $field = $args['field'];
        $default = isset($args['default']) ? $args['default'] : '';
        $value = isset($options[$field]) ? $options[$field] : $default;
        
        echo '<textarea name="lsf_settings[' . esc_attr($field) . ']" rows="3" class="large-text" style="max-width: 600px;">' . esc_textarea($value) . '</textarea>';
    }
    
    /**
     * Render email section description
     */
    public function render_email_section() {
        echo '<p>' . __('Configure o e-mail que receberá as notificações dos leads.', 'lead-scoring-form') . '</p>';
    }
    
    /**
     * Render design section description
     */
    public function render_design_section() {
        echo '<p>' . __('Personalize as cores do formulário para combinar com sua marca.', 'lead-scoring-form') . '</p>';
    }
    
    /**
     * Render email to field
     */
    public function render_email_to_field() {
        $options = get_option('lsf_settings');
        $value = isset($options['email_to']) ? $options['email_to'] : 'suporte@futturu.com.br';
        echo '<input type="email" name="lsf_settings[email_to]" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . __('E-mail que receberá as notificações de novos leads.', 'lead-scoring-form') . '</p>';
    }
    
    /**
     * Render color field
     */
    public function render_color_field($args) {
        $options = get_option('lsf_settings');
        $field = $args['field'];
        $default_colors = array(
            'primary_color' => '#2563eb',
            'secondary_color' => '#1e40af',
            'background_color' => '#ffffff',
            'text_color' => '#1f2937'
        );
        $value = isset($options[$field]) ? $options[$field] : $default_colors[$field];
        
        echo '<input type="color" name="lsf_settings[' . esc_attr($field) . ']" value="' . esc_attr($value) . '" class="lsf-color-picker" />';
        echo '<span class="lsf-color-value">' . esc_html($value) . '</span>';
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('lsf_settings_group');
                do_settings_sections('lead-scoring-form');
                submit_button(__('Salvar Configurações', 'lead-scoring-form'));
                ?>
            </form>
            
            <hr style="margin: 40px 0;" />
            
            <h2><?php _e('Como usar o formulário', 'lead-scoring-form'); ?></h2>
            <p><?php _e('Utilize o shortcode abaixo em qualquer página ou post do seu WordPress:', 'lead-scoring-form'); ?></p>
            <code style="display: block; padding: 15px; background: #f5f5f5; font-size: 16px; margin: 10px 0;">[lead_scoring_form]</code>
            
            <h3><?php _e('Funcionalidades', 'lead-scoring-form'); ?></h3>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><?php _e('Formulário inteligente com lead scoring automático', 'lead-scoring-form'); ?></li>
                <li><?php _e('Classificação de prioridade (Alta, Média, Baixa)', 'lead-scoring-form'); ?></li>
                <li><?php _e('Notificações por e-mail personalizadas', 'lead-scoring-form'); ?></li>
                <li><?php _e('Personalização de cores', 'lead-scoring-form'); ?></li>
                <li><?php _e('Design moderno e responsivo', 'lead-scoring-form'); ?></li>
            </ul>
        </div>
        <?php
    }
    
    /**
     * Render leads page
     */
    public function render_leads_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'lsf_leads';
        
        // Handle delete action
        if (isset($_GET['delete']) && wp_verify_nonce($_GET['_wpnonce'], 'lsf_delete_lead')) {
            $lead_id = intval($_GET['delete']);
            $wpdb->delete($table_name, array('id' => $lead_id));
            echo '<div class="notice notice-success"><p>' . __('Lead excluído com sucesso!', 'lead-scoring-form') . '</p></div>';
        }
        
        // Pagination
        $per_page = 20;
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $total_pages = ceil($total_items / $per_page);
        $offset = ($current_page - 1) * $per_page;
        
        $leads = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name ORDER BY submitted_at DESC LIMIT %d OFFSET %d",
                $per_page,
                $offset
            )
        );
        ?>
        <div class="wrap">
            <h1><?php _e('Leads Recebidos', 'lead-scoring-form'); ?></h1>
            
            <?php if ($leads): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('ID', 'lead-scoring-form'); ?></th>
                            <th><?php _e('Nome', 'lead-scoring-form'); ?></th>
                            <th><?php _e('Empresa', 'lead-scoring-form'); ?></th>
                            <th><?php _e('Contato', 'lead-scoring-form'); ?></th>
                            <th><?php _e('Objetivo', 'lead-scoring-form'); ?></th>
                            <th><?php _e('Prazo', 'lead-scoring-form'); ?></th>
                            <th><?php _e('Investimento', 'lead-scoring-form'); ?></th>
                            <th><?php _e('Score', 'lead-scoring-form'); ?></th>
                            <th><?php _e('Prioridade', 'lead-scoring-form'); ?></th>
                            <th><?php _e('Data', 'lead-scoring-form'); ?></th>
                            <th><?php _e('Ações', 'lead-scoring-form'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leads as $lead): ?>
                            <tr>
                                <td><?php echo esc_html($lead->id); ?></td>
                                <td><?php echo esc_html($lead->name); ?></td>
                                <td><?php echo esc_html($lead->company ?: '-'); ?></td>
                                <td><?php echo esc_html($lead->contact); ?></td>
                                <td><?php echo esc_html($lead->objective); ?></td>
                                <td><?php echo esc_html($lead->deadline); ?></td>
                                <td><?php echo esc_html($lead->investment_model); ?></td>
                                <td><strong><?php echo esc_html($lead->score); ?></strong></td>
                                <td>
                                    <?php
                                    $priority_class = '';
                                    switch($lead->priority) {
                                        case 'high':
                                            $priority_class = 'background: #dc3545; color: white;';
                                            break;
                                        case 'medium':
                                            $priority_class = 'background: #ffc107; color: black;';
                                            break;
                                        case 'low':
                                            $priority_class = 'background: #28a745; color: white;';
                                            break;
                                    }
                                    ?>
                                    <span style="<?php echo $priority_class; ?> padding: 3px 8px; border-radius: 3px; font-size: 12px;">
                                        <?php 
                                        $priority_labels = array('high' => 'ALTA', 'medium' => 'MÉDIA', 'low' => 'BAIXA');
                                        echo esc_html($priority_labels[$lead->priority] ?? $lead->priority); 
                                        ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html(date_i18n('d/m/Y H:i', strtotime($lead->submitted_at))); ?></td>
                                <td>
                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=lsf-leads&delete=' . $lead->id), 'lsf_delete_lead'); ?>" 
                                       onclick="return confirm('<?php _e('Tem certeza que deseja excluir este lead?', 'lead-scoring-form'); ?>')"
                                       class="button button-small button-link-delete">
                                        <?php _e('Excluir', 'lead-scoring-form'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <?php
                // Pagination
                if ($total_pages > 1) {
                    echo '<div style="margin-top: 20px;">';
                    echo paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => __('&laquo; Anterior'),
                        'next_text' => __('Próxima &raquo;'),
                        'total' => $total_pages,
                        'current' => $current_page
                    ));
                    echo '</div>';
                }
                ?>
                
            <?php else: ?>
                <p><?php _e('Nenhum lead recebido ainda.', 'lead-scoring-form'); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'lsf-') !== false || strpos($hook, 'lead-scoring') !== false) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            
            wp_add_inline_script('wp-color-picker', '
                jQuery(document).ready(function($) {
                    $(".lsf-color-picker").wpColorPicker({
                        change: function(event, ui) {
                            $(this).next(".lsf-color-value").text(ui.color.toString());
                        }
                    });
                });
            ');
        }
    }
}
