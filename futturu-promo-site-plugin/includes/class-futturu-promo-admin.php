<?php
/**
 * Admin functionality for Futturu Promo Plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_Promo_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_options_page(
            __('Promoção Futturu', 'futturu-promo-site'),
            __('Promoção Futturu', 'futturu-promo-site'),
            'manage_options',
            'futturu-promo-site',
            array($this, 'render_admin_page'),
            'dashicons-megaphone',
            100
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_futturu-promo-site') {
            return;
        }
        
        wp_enqueue_style(
            'futturu-promo-admin-css',
            FUTTURU_PROMO_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            FUTTURU_PROMO_VERSION
        );
        
        wp_enqueue_script(
            'futturu-promo-admin-js',
            FUTTURU_PROMO_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            FUTTURU_PROMO_VERSION,
            true
        );
        
        wp_enqueue_media();
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        // General Settings Section
        add_settings_section(
            'futturu_promo_general_section',
            __('Configurações da Promoção', 'futturu-promo-site'),
            array($this, 'general_section_callback'),
            'futturu-promo-site'
        );
        
        // Status
        add_settings_field(
            'futturu_promo_active',
            __('Status da Promoção', 'futturu-promo-site'),
            array($this, 'active_callback'),
            'futturu-promo-site',
            'futturu_promo_general_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_active');
        
        // Normal Price
        add_settings_field(
            'futturu_promo_normal_price',
            __('Preço Normal (R$)', 'futturu-promo-site'),
            array($this, 'normal_price_callback'),
            'futturu-promo-site',
            'futturu_promo_general_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_normal_price');
        
        // Promo Price
        add_settings_field(
            'futturu_promo_promo_price',
            __('Preço Promocional (R$)', 'futturu-promo-site'),
            array($this, 'promo_price_callback'),
            'futturu-promo-site',
            'futturu_promo_general_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_promo_price');
        
        // First Installment
        add_settings_field(
            'futturu_promo_first_installment',
            __('Primeira Parcela (R$)', 'futturu-promo-site'),
            array($this, 'first_installment_callback'),
            'futturu-promo-site',
            'futturu_promo_general_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_first_installment');
        
        // End Date
        add_settings_field(
            'futturu_promo_end_date',
            __('Data/Hora Limite', 'futturu-promo-site'),
            array($this, 'end_date_callback'),
            'futturu-promo-site',
            'futturu_promo_general_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_end_date');
        
        // Payment Settings Section
        add_settings_section(
            'futturu_promo_payment_section',
            __('Configurações de Pagamento', 'futturu-promo-site'),
            array($this, 'payment_section_callback'),
            'futturu-promo-site'
        );
        
        // PIX Key
        add_settings_field(
            'futturu_promo_pix_key',
            __('Chave PIX', 'futturu-promo-site'),
            array($this, 'pix_key_callback'),
            'futturu-promo-site',
            'futturu_promo_payment_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_pix_key');
        
        // WhatsApp Number
        add_settings_field(
            'futturu_promo_whatsapp',
            __('WhatsApp (com código do país)', 'futturu-promo-site'),
            array($this, 'whatsapp_callback'),
            'futturu-promo-site',
            'futturu_promo_payment_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_whatsapp');
        
        // Email Settings Section
        add_settings_section(
            'futturu_promo_email_section',
            __('Configurações de E-mail', 'futturu-promo-site'),
            array($this, 'email_section_callback'),
            'futturu-promo-site'
        );
        
        // Destination Email
        add_settings_field(
            'futturu_promo_email',
            __('E-mail para Receber Leads', 'futturu-promo-site'),
            array($this, 'email_callback'),
            'futturu-promo-site',
            'futturu_promo_email_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_email');
        
        // Texts Section
        add_settings_section(
            'futturu_promo_texts_section',
            __('Textos e Mensagens', 'futturu-promo-site'),
            array($this, 'texts_section_callback'),
            'futturu-promo-site'
        );
        
        // Hero Title
        add_settings_field(
            'futturu_promo_hero_title',
            __('Título Principal', 'futturu-promo-site'),
            array($this, 'hero_title_callback'),
            'futturu-promo-site',
            'futturu_promo_texts_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_hero_title');
        
        // Hero Subtitle
        add_settings_field(
            'futturu_promo_hero_subtitle',
            __('Subtítulo', 'futturu-promo-site'),
            array($this, 'hero_subtitle_callback'),
            'futturu-promo-site',
            'futturu_promo_texts_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_hero_subtitle');
        
        // Success Message
        add_settings_field(
            'futturu_promo_success_message',
            __('Mensagem de Sucesso', 'futturu-promo-site'),
            array($this, 'success_message_callback'),
            'futturu-promo-site',
            'futturu_promo_texts_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_success_message');
        
        // QR Code Image
        add_settings_field(
            'futturu_promo_qr_code',
            __('QR Code PIX (Opcional)', 'futturu-promo-site'),
            array($this, 'qr_code_callback'),
            'futturu-promo-site',
            'futturu_promo_payment_section'
        );
        register_setting('futturu_promo_group', 'futturu_promo_qr_code');
    }
    
    /**
     * Section callbacks
     */
    public function general_section_callback() {
        echo '<p>' . __('Configure os valores e período da promoção.', 'futturu-promo-site') . '</p>';
    }
    
    public function payment_section_callback() {
        echo '<p>' . __('Configure as informações de pagamento PIX e WhatsApp.', 'futturu-promo-site') . '</p>';
    }
    
    public function email_section_callback() {
        echo '<p>' . __('Defina o e-mail que receberá os dados dos leads.', 'futturu-promo-site') . '</p>';
    }
    
    public function texts_section_callback() {
        echo '<p>' . __('Personalize os textos exibidos na página de promoção.', 'futturu-promo-site') . '</p>';
    }
    
    /**
     * Field callbacks
     */
    public function active_callback() {
        $value = get_option('futturu_promo_active', 'yes');
        echo '<select name="futturu_promo_active">';
        echo '<option value="yes"' . selected($value, 'yes', false) . '>' . __('Ativo', 'futturu-promo-site') . '</option>';
        echo '<option value="no"' . selected($value, 'no', false) . '>' . __('Inativo', 'futturu-promo-site') . '</option>';
        echo '</select>';
        echo '<p class="description">' . __('Ative ou desative a promoção.', 'futturu-promo-site') . '</p>';
    }
    
    public function normal_price_callback() {
        $value = get_option('futturu_promo_normal_price', '2500');
        echo '<input type="number" name="futturu_promo_normal_price" value="' . esc_attr($value) . '" class="regular-text" step="0.01" />';
        echo '<p class="description">' . __('Preço original do serviço (ex: 2500).', 'futturu-promo-site') . '</p>';
    }
    
    public function promo_price_callback() {
        $value = get_option('futturu_promo_promo_price', '1500');
        echo '<input type="number" name="futturu_promo_promo_price" value="' . esc_attr($value) . '" class="regular-text" step="0.01" />';
        echo '<p class="description">' . __('Preço promocional (ex: 1500).', 'futturu-promo-site') . '</p>';
    }
    
    public function first_installment_callback() {
        $value = get_option('futturu_promo_first_installment', '500');
        echo '<input type="number" name="futturu_promo_first_installment" value="' . esc_attr($value) . '" class="regular-text" step="0.01" />';
        echo '<p class="description">' . __('Valor da primeira parcela para garantir a vaga (ex: 500).', 'futturu-promo-site') . '</p>';
    }
    
    public function end_date_callback() {
        $value = get_option('futturu_promo_end_date', date('Y-m-d H:i:s', strtotime('+24 hours')));
        echo '<input type="datetime-local" name="futturu_promo_end_date" value="' . esc_attr(date('Y-m-d\TH:i', strtotime($value))) . '" class="regular-text" />';
        echo '<p class="description">' . __('Data e hora de término da promoção.', 'futturu-promo-site') . '</p>';
    }
    
    public function pix_key_callback() {
        $value = get_option('futturu_promo_pix_key', 'pix@futturu.com.br');
        echo '<input type="text" name="futturu_promo_pix_key" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . __('Chave PIX (e-mail, CPF, CNPJ ou telefone).', 'futturu-promo-site') . '</p>';
    }
    
    public function whatsapp_callback() {
        $value = get_option('futturu_promo_whatsapp', '5591993100621');
        echo '<input type="text" name="futturu_promo_whatsapp" value="' . esc_attr($value) . '" class="regular-text" placeholder="5591993100621" />';
        echo '<p class="description">' . __('Número no formato internacional (ex: 5591993100621).', 'futturu-promo-site') . '</p>';
    }
    
    public function email_callback() {
        $value = get_option('futturu_promo_email', 'suporte@futturu.com.br');
        echo '<input type="email" name="futturu_promo_email" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . __('E-mail que receberá os leads.', 'futturu-promo-site') . '</p>';
    }
    
    public function hero_title_callback() {
        $value = get_option('futturu_promo_hero_title', 'Crie seu Site Institucional Profissional por R$ 1.500!');
        echo '<input type="text" name="futturu_promo_hero_title" value="' . esc_attr($value) . '" class="large-text" />';
    }
    
    public function hero_subtitle_callback() {
        $value = get_option('futturu_promo_hero_subtitle', 'De R$ 2.500 por R$ 1.500. Apenas hoje ou enquanto durarem as vagas.');
        echo '<input type="text" name="futturu_promo_hero_subtitle" value="' . esc_attr($value) . '" class="large-text" />';
    }
    
    public function success_message_callback() {
        $value = get_option('futturu_promo_success_message', 'Parabéns! Recebemos seus dados e o comprovante de pagamento. Sua vaga na promoção de R$ 1.500 está garantida. Entraremos em contato em até 24h úteis para iniciar o briefing e a produção do seu novo site.');
        echo '<textarea name="futturu_promo_success_message" class="large-text" rows="4">' . esc_textarea($value) . '</textarea>';
    }
    
    public function qr_code_callback() {
        $value = get_option('futturu_promo_qr_code', '');
        if ($value) {
            echo '<div style="margin-bottom: 10px;"><img src="' . esc_url($value) . '" style="max-width: 200px;" /></div>';
        }
        echo '<input type="hidden" name="futturu_promo_qr_code" id="futturu_promo_qr_code" value="' . esc_attr($value) . '" />';
        echo '<button type="button" class="button" id="upload_qr_code">' . __('Upload QR Code', 'futturu-promo-site') . '</button>';
        echo '<button type="button" class="button" id="remove_qr_code">' . __('Remover QR Code', 'futturu-promo-site') . '</button>';
        echo '<p class="description">' . __('Faça upload de uma imagem QR Code PIX estática (opcional). Se não for fornecido, será gerado automaticamente.', 'futturu-promo-site') . '</p>';
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Handle form submission for resetting timer
        if (isset($_POST['futturu_promo_reset_timer']) && check_admin_referer('futturu_promo_reset_timer_nonce', 'futturu_promo_reset_timer')) {
            update_option('futturu_promo_end_date', date('Y-m-d H:i:s', strtotime('+24 hours')));
            echo '<div class="notice notice-success"><p>' . __('Timer resetado para 24 horas!', 'futturu-promo-site') . '</p></div>';
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="futturu-promo-admin-container">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('futturu_promo_group');
                    do_settings_sections('futturu-promo-site');
                    submit_button(__('Salvar Configurações', 'futturu-promo-site'));
                    ?>
                </form>
                
                <div class="futturu-promo-actions">
                    <h2><?php _e('Ações Rápidas', 'futturu-promo-site'); ?></h2>
                    
                    <form method="post" style="display: inline;">
                        <?php wp_nonce_field('futturu_promo_reset_timer_nonce', 'futturu_promo_reset_timer'); ?>
                        <button type="submit" name="futturu_promo_reset_timer" class="button button-secondary">
                            <?php _e('Resetar Timer para 24h', 'futturu-promo-site'); ?>
                        </button>
                    </form>
                    
                    <div style="margin-top: 20px;">
                        <h3><?php _e('Shortcode', 'futturu-promo-site'); ?></h3>
                        <p><?php _e('Use este shortcode em qualquer página ou post:', 'futturu-promo-site'); ?></p>
                        <code style="background: #f0f0f1; padding: 10px; display: block; margin: 10px 0;">[futturu_promo_site]</code>
                        
                        <h3><?php _e('URL Dedicada', 'futturu-promo-site'); ?></h3>
                        <p><?php _e('Ou acesse diretamente pela URL:', 'futturu-promo-site'); ?></p>
                        <code style="background: #f0f0f1; padding: 10px; display: block; margin: 10px 0;"><?php echo home_url('/promocao-site-institucional/'); ?></code>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
