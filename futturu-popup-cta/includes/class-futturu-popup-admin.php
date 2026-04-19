<?php
/**
 * Admin Class for Futturu Popup CTA
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Futturu_Popup_Admin {
    
    private $option_name = 'futturu_popup_options';
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_futturu_popup_preview', array($this, 'ajax_preview'));
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_options_page(
            __('PopUp Promocional Futturu', 'futturu-popup-cta'),
            __('PopUp Promocional Futturu', 'futturu-popup-cta'),
            'manage_options',
            'futturu-popup-cta',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('futturu_popup_group', $this->option_name, array($this, 'sanitize_options'));
        
        // Content Section
        add_settings_section(
            'futturu_popup_content_section',
            __('Conteúdo do PopUp', 'futturu-popup-cta'),
            array($this, 'content_section_callback'),
            'futturu-popup-cta'
        );
        
        add_settings_field(
            'enabled',
            __('Ativar/Desativar', 'futturu-popup-cta'),
            array($this, 'render_enabled_field'),
            'futturu-popup-cta',
            'futturu_popup_content_section'
        );
        
        add_settings_field(
            'title',
            __('Título', 'futturu-popup-cta'),
            array($this, 'render_title_field'),
            'futturu-popup-cta',
            'futturu_popup_content_section'
        );
        
        add_settings_field(
            'content',
            __('Texto de Apoio', 'futturu-popup-cta'),
            array($this, 'render_content_field'),
            'futturu-popup-cta',
            'futturu_popup_content_section'
        );
        
        add_settings_field(
            'cta_text',
            __('Texto do CTA Principal', 'futturu-popup-cta'),
            array($this, 'render_cta_text_field'),
            'futturu-popup-cta',
            'futturu_popup_content_section'
        );
        
        add_settings_field(
            'cta_url',
            __('Link do CTA Principal', 'futturu-popup-cta'),
            array($this, 'render_cta_url_field'),
            'futturu-popup-cta',
            'futturu_popup_content_section'
        );
        
        add_settings_field(
            'show_decline_button',
            __('Botão "Não, obrigado"', 'futturu-popup-cta'),
            array($this, 'render_show_decline_field'),
            'futturu-popup-cta',
            'futturu_popup_content_section'
        );
        
        add_settings_field(
            'decline_text',
            __('Texto do Botão "Não, obrigado"', 'futturu-popup-cta'),
            array($this, 'render_decline_text_field'),
            'futturu-popup-cta',
            'futturu_popup_content_section'
        );
        
        // Appearance Section
        add_settings_section(
            'futturu_popup_appearance_section',
            __('Aparência do PopUp', 'futturu-popup-cta'),
            array($this, 'appearance_section_callback'),
            'futturu-popup-cta'
        );
        
        add_settings_field(
            'width',
            __('Largura', 'futturu-popup-cta'),
            array($this, 'render_width_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'max_height',
            __('Altura Máxima', 'futturu-popup-cta'),
            array($this, 'render_max_height_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'bg_color',
            __('Cor de Fundo', 'futturu-popup-cta'),
            array($this, 'render_bg_color_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'text_color',
            __('Cor do Texto', 'futturu-popup-cta'),
            array($this, 'render_text_color_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'cta_bg_color',
            __('Cor de Fundo do CTA', 'futturu-popup-cta'),
            array($this, 'render_cta_bg_color_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'cta_text_color',
            __('Cor do Texto do CTA', 'futturu-popup-cta'),
            array($this, 'render_cta_text_color_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'close_btn_color',
            __('Cor do Botão Fechar', 'futturu-popup-cta'),
            array($this, 'render_close_btn_color_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'font_family',
            __('Fonte', 'futturu-popup-cta'),
            array($this, 'render_font_family_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'font_size',
            __('Tamanho da Fonte (px)', 'futturu-popup-cta'),
            array($this, 'render_font_size_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'font_weight',
            __('Peso da Fonte', 'futturu-popup-cta'),
            array($this, 'render_font_weight_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'enable_blur',
            __('Ativar Blur no Fundo', 'futturu-popup-cta'),
            array($this, 'render_enable_blur_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'blur_intensity',
            __('Intensidade do Blur', 'futturu-popup-cta'),
            array($this, 'render_blur_intensity_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'enable_animation',
            __('Ativar Animação', 'futturu-popup-cta'),
            array($this, 'render_enable_animation_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        add_settings_field(
            'animation_type',
            __('Tipo de Animação', 'futturu-popup-cta'),
            array($this, 'render_animation_type_field'),
            'futturu-popup-cta',
            'futturu_popup_appearance_section'
        );
        
        // Display Rules Section
        add_settings_section(
            'futturu_popup_display_section',
            __('Regras de Exibição', 'futturu-popup-cta'),
            array($this, 'display_section_callback'),
            'futturu-popup-cta'
        );
        
        add_settings_field(
            'display_pages',
            __('Páginas de Exibição', 'futturu-popup-cta'),
            array($this, 'render_display_pages_field'),
            'futturu-popup-cta',
            'futturu_popup_display_section'
        );
        
        add_settings_field(
            'display_page_ids',
            __('Páginas Específicas', 'futturu-popup-cta'),
            array($this, 'render_display_page_ids_field'),
            'futturu-popup-cta',
            'futturu_popup_display_section'
        );
        
        add_settings_field(
            'exclude_page_ids',
            __('Excluir Páginas', 'futturu-popup-cta'),
            array($this, 'render_exclude_page_ids_field'),
            'futturu-popup-cta',
            'futturu_popup_display_section'
        );
        
        add_settings_field(
            'display_categories',
            __('Categorias Específicas', 'futturu-popup-cta'),
            array($this, 'render_display_categories_field'),
            'futturu-popup-cta',
            'futturu_popup_display_section'
        );
        
        add_settings_field(
            'display_time',
            __('Tempo de Exibição', 'futturu-popup-cta'),
            array($this, 'render_display_time_field'),
            'futturu-popup-cta',
            'futturu_popup_display_section'
        );
        
        add_settings_field(
            'display_delay',
            __('Atraso (segundos)', 'futturu-popup-cta'),
            array($this, 'render_display_delay_field'),
            'futturu-popup-cta',
            'futturu_popup_display_section'
        );
        
        add_settings_field(
            'scroll_percentage',
            __('Porcentagem de Scroll', 'futturu-popup-cta'),
            array($this, 'render_scroll_percentage_field'),
            'futturu-popup-cta',
            'futturu_popup_display_section'
        );
        
        add_settings_field(
            'frequency',
            __('Controle de Frequência', 'futturu-popup-cta'),
            array($this, 'render_frequency_field'),
            'futturu-popup-cta',
            'futturu_popup_display_section'
        );
        
        add_settings_field(
            'frequency_days',
            __('Dias para Mostrar Novamente', 'futturu-popup-cta'),
            array($this, 'render_frequency_days_field'),
            'futturu-popup-cta',
            'futturu_popup_display_section'
        );
        
        add_settings_field(
            'frequency_count',
            __('Número Máximo de Visualizações', 'futturu-popup-cta'),
            array($this, 'render_frequency_count_field'),
            'futturu-popup-cta',
            'futturu_popup_display_section'
        );
    }
    
    /**
     * Sanitize options
     */
    public function sanitize_options($input) {
        $sanitized = array();
        
        $sanitized['enabled'] = isset($input['enabled']) ? 1 : 0;
        $sanitized['title'] = sanitize_text_field($input['title']);
        $sanitized['content'] = wp_kses_post($input['content']);
        $sanitized['cta_text'] = sanitize_text_field($input['cta_text']);
        $sanitized['cta_url'] = esc_url_raw($input['cta_url']);
        $sanitized['show_decline_button'] = isset($input['show_decline_button']) ? 1 : 0;
        $sanitized['decline_text'] = sanitize_text_field($input['decline_text']);
        
        $sanitized['width'] = sanitize_text_field($input['width']);
        $sanitized['max_height'] = sanitize_text_field($input['max_height']);
        $sanitized['bg_color'] = sanitize_hex_color($input['bg_color']);
        $sanitized['text_color'] = sanitize_hex_color($input['text_color']);
        $sanitized['cta_bg_color'] = sanitize_hex_color($input['cta_bg_color']);
        $sanitized['cta_text_color'] = sanitize_hex_color($input['cta_text_color']);
        $sanitized['close_btn_color'] = sanitize_hex_color($input['close_btn_color']);
        
        $sanitized['font_family'] = sanitize_text_field($input['font_family']);
        $sanitized['font_size'] = absint($input['font_size']);
        $sanitized['font_weight'] = sanitize_text_field($input['font_weight']);
        
        $sanitized['enable_blur'] = isset($input['enable_blur']) ? 1 : 0;
        $sanitized['blur_intensity'] = absint($input['blur_intensity']);
        $sanitized['enable_animation'] = isset($input['enable_animation']) ? 1 : 0;
        $sanitized['animation_type'] = sanitize_text_field($input['animation_type']);
        
        $sanitized['display_pages'] = sanitize_text_field($input['display_pages']);
        $sanitized['display_page_ids'] = isset($input['display_page_ids']) ? array_map('absint', (array)$input['display_page_ids']) : array();
        $sanitized['exclude_page_ids'] = isset($input['exclude_page_ids']) ? array_map('absint', (array)$input['exclude_page_ids']) : array();
        $sanitized['display_categories'] = isset($input['display_categories']) ? array_map('absint', (array)$input['display_categories']) : array();
        
        $sanitized['display_time'] = sanitize_text_field($input['display_time']);
        $sanitized['display_delay'] = absint($input['display_delay']);
        $sanitized['scroll_percentage'] = absint($input['scroll_percentage']);
        
        $sanitized['frequency'] = sanitize_text_field($input['frequency']);
        $sanitized['frequency_days'] = absint($input['frequency_days']);
        $sanitized['frequency_count'] = absint($input['frequency_count']);
        
        return $sanitized;
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
            <form action="options.php" method="post">
                <?php
                settings_fields('futturu_popup_group');
                do_settings_sections('futturu-popup-cta');
                submit_button(__('Salvar Configurações', 'futturu-popup-cta'));
                ?>
            </form>
            
            <hr style="margin: 30px 0;">
            
            <h2><?php _e('Preview', 'futturu-popup-cta'); ?></h2>
            <p><?php _e('Visualize como o popup ficará no seu site.', 'futturu-popup-cta'); ?></p>
            <button type="button" class="button button-primary" id="futturu-popup-preview-btn">
                <?php _e('Ver Preview', 'futturu-popup-cta'); ?>
            </button>
            <div id="futturu-popup-preview-container"></div>
        </div>
        <?php
    }
    
    /**
     * Section callbacks
     */
    public function content_section_callback() {
        echo '<p>' . __('Configure o conteúdo do popup promocional.', 'futturu-popup-cta') . '</p>';
    }
    
    public function appearance_section_callback() {
        echo '<p>' . __('Personalize a aparência visual do popup.', 'futturu-popup-cta') . '</p>';
    }
    
    public function display_section_callback() {
        echo '<p>' . __('Defina quando e onde o popup será exibido.', 'futturu-popup-cta') . '</p>';
    }
    
    /**
     * Field renderers
     */
    public function render_enabled_field() {
        $options = get_option($this->option_name);
        $enabled = isset($options['enabled']) ? $options['enabled'] : 1;
        ?>
        <label>
            <input type="checkbox" name="<?php echo $this->option_name; ?>[enabled]" value="1" <?php checked($enabled, 1); ?>>
            <?php _e('Habilitar popup', 'futturu-popup-cta'); ?>
        </label>
        <?php
    }
    
    public function render_title_field() {
        $options = get_option($this->option_name);
        $value = isset($options['title']) ? $options['title'] : 'OFERTA ESPECIAL';
        echo '<input type="text" name="' . $this->option_name . '[title]" value="' . esc_attr($value) . '" class="regular-text" />';
    }
    
    public function render_content_field() {
        $options = get_option($this->option_name);
        $value = isset($options['content']) ? $options['content'] : '';
        echo '<textarea name="' . $this->option_name . '[content]" rows="4" class="large-text">' . esc_textarea($value) . '</textarea>';
    }
    
    public function render_cta_text_field() {
        $options = get_option($this->option_name);
        $value = isset($options['cta_text']) ? $options['cta_text'] : 'Acesse agora o Hotsite!';
        echo '<input type="text" name="' . $this->option_name . '[cta_text]" value="' . esc_attr($value) . '" class="regular-text" />';
    }
    
    public function render_cta_url_field() {
        $options = get_option($this->option_name);
        $value = isset($options['cta_url']) ? $options['cta_url'] : home_url('/promocao-site-institucional/');
        echo '<input type="url" name="' . $this->option_name . '[cta_url]" value="' . esc_attr($value) . '" class="regular-text" />';
    }
    
    public function render_show_decline_field() {
        $options = get_option($this->option_name);
        $value = isset($options['show_decline_button']) ? $options['show_decline_button'] : 1;
        ?>
        <label>
            <input type="checkbox" name="<?php echo $this->option_name; ?>[show_decline_button]" value="1" <?php checked($value, 1); ?>>
            <?php _e('Mostrar botão "Não, obrigado"', 'futturu-popup-cta'); ?>
        </label>
        <?php
    }
    
    public function render_decline_text_field() {
        $options = get_option($this->option_name);
        $value = isset($options['decline_text']) ? $options['decline_text'] : 'Não, obrigado';
        echo '<input type="text" name="' . $this->option_name . '[decline_text]" value="' . esc_attr($value) . '" class="regular-text" />';
    }
    
    public function render_width_field() {
        $options = get_option($this->option_name);
        $value = isset($options['width']) ? $options['width'] : 'medium';
        ?>
        <select name="<?php echo $this->option_name; ?>[width]">
            <option value="small" <?php selected($value, 'small'); ?>><?php _e('Pequeno (400px)', 'futturu-popup-cta'); ?></option>
            <option value="medium" <?php selected($value, 'medium'); ?>><?php _e('Médio (500px)', 'futturu-popup-cta'); ?></option>
            <option value="large" <?php selected($value, 'large'); ?>><?php _e('Grande (600px)', 'futturu-popup-cta'); ?></option>
            <option value="custom" <?php selected($value, 'custom'); ?>><?php _e('Personalizado', 'futturu-popup-cta'); ?></option>
        </select>
        <?php
    }
    
    public function render_max_height_field() {
        $options = get_option($this->option_name);
        $value = isset($options['max_height']) ? $options['max_height'] : '';
        echo '<input type="text" name="' . $this->option_name . '[max_height]" value="' . esc_attr($value) . '" class="small-text" placeholder="ex: 400px" />';
        echo '<p class="description">' . __('Deixe em branco para altura automática.', 'futturu-popup-cta') . '</p>';
    }
    
    public function render_bg_color_field() {
        $options = get_option($this->option_name);
        $value = isset($options['bg_color']) ? $options['bg_color'] : '#ffffff';
        echo '<input type="color" name="' . $this->option_name . '[bg_color]" value="' . esc_attr($value) . '" class="color-picker" />';
    }
    
    public function render_text_color_field() {
        $options = get_option($this->option_name);
        $value = isset($options['text_color']) ? $options['text_color'] : '#333333';
        echo '<input type="color" name="' . $this->option_name . '[text_color]" value="' . esc_attr($value) . '" class="color-picker" />';
    }
    
    public function render_cta_bg_color_field() {
        $options = get_option($this->option_name);
        $value = isset($options['cta_bg_color']) ? $options['cta_bg_color'] : '#0073aa';
        echo '<input type="color" name="' . $this->option_name . '[cta_bg_color]" value="' . esc_attr($value) . '" class="color-picker" />';
    }
    
    public function render_cta_text_color_field() {
        $options = get_option($this->option_name);
        $value = isset($options['cta_text_color']) ? $options['cta_text_color'] : '#ffffff';
        echo '<input type="color" name="' . $this->option_name . '[cta_text_color]" value="' . esc_attr($value) . '" class="color-picker" />';
    }
    
    public function render_close_btn_color_field() {
        $options = get_option($this->option_name);
        $value = isset($options['close_btn_color']) ? $options['close_btn_color'] : '#666666';
        echo '<input type="color" name="' . $this->option_name . '[close_btn_color]" value="' . esc_attr($value) . '" class="color-picker" />';
    }
    
    public function render_font_family_field() {
        $options = get_option($this->option_name);
        $value = isset($options['font_family']) ? $options['font_family'] : 'inherit';
        ?>
        <select name="<?php echo $this->option_name; ?>[font_family]">
            <option value="inherit" <?php selected($value, 'inherit'); ?>><?php _e('Padrão do Tema', 'futturu-popup-cta'); ?></option>
            <option value="Arial, sans-serif" <?php selected($value, 'Arial, sans-serif'); ?>>Arial</option>
            <option value="'Helvetica Neue', Helvetica, sans-serif" <?php selected($value, "'Helvetica Neue', Helvetica, sans-serif"); ?>>Helvetica</option>
            <option value="'Times New Roman', serif" <?php selected($value, "'Times New Roman', serif"); ?>>Times New Roman</option>
            <option value="Georgia, serif" <?php selected($value, 'Georgia, serif'); ?>>Georgia</option>
            <option value="'Courier New', monospace" <?php selected($value, "'Courier New', monospace"); ?>>Courier New</option>
        </select>
        <?php
    }
    
    public function render_font_size_field() {
        $options = get_option($this->option_name);
        $value = isset($options['font_size']) ? $options['font_size'] : '16';
        echo '<input type="number" name="' . $this->option_name . '[font_size]" value="' . esc_attr($value) . '" class="small-text" min="10" max="32" />';
    }
    
    public function render_font_weight_field() {
        $options = get_option($this->option_name);
        $value = isset($options['font_weight']) ? $options['font_weight'] : '400';
        ?>
        <select name="<?php echo $this->option_name; ?>[font_weight]">
            <option value="300" <?php selected($value, '300'); ?>><?php _e('Light (300)', 'futturu-popup-cta'); ?></option>
            <option value="400" <?php selected($value, '400'); ?>><?php _e('Normal (400)', 'futturu-popup-cta'); ?></option>
            <option value="500" <?php selected($value, '500'); ?>><?php _e('Medium (500)', 'futturu-popup-cta'); ?></option>
            <option value="600" <?php selected($value, '600'); ?>><?php _e('Semi-bold (600)', 'futturu-popup-cta'); ?></option>
            <option value="700" <?php selected($value, '700'); ?>><?php _e('Bold (700)', 'futturu-popup-cta'); ?></option>
        </select>
        <?php
    }
    
    public function render_enable_blur_field() {
        $options = get_option($this->option_name);
        $enabled = isset($options['enable_blur']) ? $options['enable_blur'] : 1;
        ?>
        <label>
            <input type="checkbox" name="<?php echo $this->option_name; ?>[enable_blur]" value="1" <?php checked($enabled, 1); ?>>
            <?php _e('Ativar efeito blur no fundo', 'futturu-popup-cta'); ?>
        </label>
        <?php
    }
    
    public function render_blur_intensity_field() {
        $options = get_option($this->option_name);
        $value = isset($options['blur_intensity']) ? $options['blur_intensity'] : '5';
        echo '<input type="range" name="' . $this->option_name . '[blur_intensity]" value="' . esc_attr($value) . '" min="0" max="20" step="1" class="wp-slider" />';
        echo '<span class="blur-intensity-value">' . esc_html($value) . 'px</span>';
    }
    
    public function render_enable_animation_field() {
        $options = get_option($this->option_name);
        $enabled = isset($options['enable_animation']) ? $options['enable_animation'] : 1;
        ?>
        <label>
            <input type="checkbox" name="<?php echo $this->option_name; ?>[enable_animation]" value="1" <?php checked($enabled, 1); ?>>
            <?php _e('Ativar animação de abertura', 'futturu-popup-cta'); ?>
        </label>
        <?php
    }
    
    public function render_animation_type_field() {
        $options = get_option($this->option_name);
        $value = isset($options['animation_type']) ? $options['animation_type'] : 'fade-in';
        ?>
        <select name="<?php echo $this->option_name; ?>[animation_type]">
            <option value="fade-in" <?php selected($value, 'fade-in'); ?>><?php _e('Fade In', 'futturu-popup-cta'); ?></option>
            <option value="slide-up" <?php selected($value, 'slide-up'); ?>><?php _e('Slide Up', 'futturu-popup-cta'); ?></option>
            <option value="slide-down" <?php selected($value, 'slide-down'); ?>><?php _e('Slide Down', 'futturu-popup-cta'); ?></option>
            <option value="zoom-in" <?php selected($value, 'zoom-in'); ?>><?php _e('Zoom In', 'futturu-popup-cta'); ?></option>
        </select>
        <?php
    }
    
    public function render_display_pages_field() {
        $options = get_option($this->option_name);
        $value = isset($options['display_pages']) ? $options['display_pages'] : 'all';
        ?>
        <select name="<?php echo $this->option_name; ?>[display_pages]" id="display-pages-select">
            <option value="all" <?php selected($value, 'all'); ?>><?php _e('Todas as páginas', 'futturu-popup-cta'); ?></option>
            <option value="posts_only" <?php selected($value, 'posts_only'); ?>><?php _e('Apenas em Posts', 'futturu-popup-cta'); ?></option>
            <option value="pages_only" <?php selected($value, 'pages_only'); ?>><?php _e('Apenas em Páginas', 'futturu-popup-cta'); ?></option>
            <option value="specific_pages" <?php selected($value, 'specific_pages'); ?>><?php _e('Em Páginas Específicas', 'futturu-popup-cta'); ?></option>
            <option value="except_pages" <?php selected($value, 'except_pages'); ?>><?php _e('Exceto em Páginas Específicas', 'futturu-popup-cta'); ?></option>
            <option value="categories" <?php selected($value, 'categories'); ?>><?php _e('Em Categorias Específicas', 'futturu-popup-cta'); ?></option>
        </select>
        <?php
    }
    
    public function render_display_page_ids_field() {
        $options = get_option($this->option_name);
        $selected = isset($options['display_page_ids']) ? $options['display_page_ids'] : array();
        
        $pages = get_pages();
        ?>
        <div class="page-selection" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
        <?php foreach ($pages as $page) : ?>
            <label style="display: block; margin: 5px 0;">
                <input type="checkbox" name="<?php echo $this->option_name; ?>[display_page_ids][]" value="<?php echo $page->ID; ?>" <?php echo in_array($page->ID, $selected) ? 'checked' : ''; ?>>
                <?php echo esc_html($page->post_title); ?>
            </label>
        <?php endforeach; ?>
        </div>
        <?php
    }
    
    public function render_exclude_page_ids_field() {
        $options = get_option($this->option_name);
        $selected = isset($options['exclude_page_ids']) ? $options['exclude_page_ids'] : array();
        
        $pages = get_pages();
        ?>
        <div class="page-selection" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
        <?php foreach ($pages as $page) : ?>
            <label style="display: block; margin: 5px 0;">
                <input type="checkbox" name="<?php echo $this->option_name; ?>[exclude_page_ids][]" value="<?php echo $page->ID; ?>" <?php echo in_array($page->ID, $selected) ? 'checked' : ''; ?>>
                <?php echo esc_html($page->post_title); ?>
            </label>
        <?php endforeach; ?>
        </div>
        <?php
    }
    
    public function render_display_categories_field() {
        $options = get_option($this->option_name);
        $selected = isset($options['display_categories']) ? $options['display_categories'] : array();
        
        $categories = get_categories(array('hide_empty' => false));
        ?>
        <div class="category-selection" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;">
        <?php foreach ($categories as $category) : ?>
            <label style="display: block; margin: 5px 0;">
                <input type="checkbox" name="<?php echo $this->option_name; ?>[display_categories][]" value="<?php echo $category->term_id; ?>" <?php echo in_array($category->term_id, $selected) ? 'checked' : ''; ?>>
                <?php echo esc_html($category->name); ?>
            </label>
        <?php endforeach; ?>
        </div>
        <?php
    }
    
    public function render_display_time_field() {
        $options = get_option($this->option_name);
        $value = isset($options['display_time']) ? $options['display_time'] : 'immediate';
        ?>
        <select name="<?php echo $this->option_name; ?>[display_time]" id="display-time-select">
            <option value="immediate" <?php selected($value, 'immediate'); ?>><?php _e('Aparecer imediatamente', 'futturu-popup-cta'); ?></option>
            <option value="delay" <?php selected($value, 'delay'); ?>><?php _e('Aparecer após X segundos', 'futturu-popup-cta'); ?></option>
            <option value="scroll" <?php selected($value, 'scroll'); ?>><?php _e('Aparecer após rolar Y% da página', 'futturu-popup-cta'); ?></option>
            <option value="exit_intent" <?php selected($value, 'exit_intent'); ?>><?php _e('Aparecer ao tentar sair da página (Exit Intent)', 'futturu-popup-cta'); ?></option>
        </select>
        <?php
    }
    
    public function render_display_delay_field() {
        $options = get_option($this->option_name);
        $value = isset($options['display_delay']) ? $options['display_delay'] : '2';
        echo '<input type="number" name="' . $this->option_name . '[display_delay]" value="' . esc_attr($value) . '" class="small-text" min="1" max="60" />';
        echo ' ' . __('segundos', 'futturu-popup-cta');
    }
    
    public function render_scroll_percentage_field() {
        $options = get_option($this->option_name);
        $value = isset($options['scroll_percentage']) ? $options['scroll_percentage'] : '50';
        echo '<input type="number" name="' . $this->option_name . '[scroll_percentage]" value="' . esc_attr($value) . '" class="small-text" min="1" max="100" />';
        echo '% ' . __('da página', 'futturu-popup-cta');
    }
    
    public function render_frequency_field() {
        $options = get_option($this->option_name);
        $value = isset($options['frequency']) ? $options['frequency'] : 'once_per_session';
        ?>
        <select name="<?php echo $this->option_name; ?>[frequency]" id="frequency-select">
            <option value="every_visit" <?php selected($value, 'every_visit'); ?>><?php _e('Mostrar em todas as visitas', 'futturu-popup-cta'); ?></option>
            <option value="once_per_session" <?php selected($value, 'once_per_session'); ?>><?php _e('Mostrar uma vez por sessão', 'futturu-popup-cta'); ?></option>
            <option value="once_per_days" <?php selected($value, 'once_per_days'); ?>><?php _e('Mostrar uma vez por X dias', 'futturu-popup-cta'); ?></option>
            <option value="max_count" <?php selected($value, 'max_count'); ?>><?php _e('Mostrar no máximo N vezes', 'futturu-popup-cta'); ?></option>
        </select>
        <?php
    }
    
    public function render_frequency_days_field() {
        $options = get_option($this->option_name);
        $value = isset($options['frequency_days']) ? $options['frequency_days'] : '7';
        echo '<input type="number" name="' . $this->option_name . '[frequency_days]" value="' . esc_attr($value) . '" class="small-text" min="1" max="365" />';
        echo ' ' . __('dias', 'futturu-popup-cta');
    }
    
    public function render_frequency_count_field() {
        $options = get_option($this->option_name);
        $value = isset($options['frequency_count']) ? $options['frequency_count'] : '3';
        echo '<input type="number" name="' . $this->option_name . '[frequency_count]" value="' . esc_attr($value) . '" class="small-text" min="1" max="100" />';
        echo ' ' . __('visualizações', 'futturu-popup-cta');
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'settings_page_futturu-popup-cta') {
            return;
        }
        
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        wp_enqueue_script(
            'futturu-popup-admin',
            FUTTURU_POPUP_PLUGIN_URL . 'assets/js/futturu-popup-admin.js',
            array('jquery', 'wp-color-picker'),
            FUTTURU_POPUP_VERSION,
            true
        );
        
        wp_localize_script('futturu-popup-admin', 'futturuPopupAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_popup_preview_nonce')
        ));
    }
    
    /**
     * AJAX preview handler
     */
    public function ajax_preview() {
        check_ajax_referer('futturu_popup_preview_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error();
        }
        
        $options = isset($_POST['options']) ? $_POST['options'] : array();
        
        // Sanitize options
        $sanitized = array(
            'title' => sanitize_text_field($options['title']),
            'content' => wp_kses_post($options['content']),
            'cta_text' => sanitize_text_field($options['cta_text']),
            'cta_url' => esc_url_raw($options['cta_url']),
            'show_decline_button' => isset($options['show_decline_button']) ? 1 : 0,
            'decline_text' => sanitize_text_field($options['decline_text']),
            'width' => sanitize_text_field($options['width']),
            'bg_color' => sanitize_hex_color($options['bg_color']),
            'text_color' => sanitize_hex_color($options['text_color']),
            'cta_bg_color' => sanitize_hex_color($options['cta_bg_color']),
            'cta_text_color' => sanitize_hex_color($options['cta_text_color']),
            'close_btn_color' => sanitize_hex_color($options['close_btn_color']),
            'font_family' => sanitize_text_field($options['font_family']),
            'font_size' => absint($options['font_size']),
            'font_weight' => sanitize_text_field($options['font_weight']),
            'enable_animation' => isset($options['enable_animation']) ? 1 : 0,
            'animation_type' => sanitize_text_field($options['animation_type'])
        );
        
        ob_start();
        include FUTTURU_POPUP_PLUGIN_DIR . 'includes/class-futturu-popup-template.php';
        $html = ob_get_clean();
        
        wp_send_json_success(array('html' => $html));
    }
}
