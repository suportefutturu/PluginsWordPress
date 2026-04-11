<?php
/**
 * Admin functionality for Futturu Professional Site Simulator
 */

if (!defined('ABSPATH')) {
    exit;
}

class Futturu_PSS_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_filter('plugin_action_links_' . FUTTURU_PSS_PLUGIN_BASENAME, array($this, 'add_plugin_links'));
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_options_page(
            __('Simulador Site Profissional Futturu', 'futturu-prof-site-sim'),
            __('Simulador Site Profissional', 'futturu-prof-site-sim'),
            'manage_options',
            'futturu-pss-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        // General Settings Section
        add_settings_section(
            'futturu_pss_general_section',
            __('Configurações Gerais', 'futturu-prof-site-sim'),
            array($this, 'general_section_callback'),
            'futturu-pss-settings'
        );
        
        // Site Types Section
        add_settings_section(
            'futturu_pss_types_section',
            __('Tipos de Site', 'futturu-prof-site-sim'),
            array($this, 'types_section_callback'),
            'futturu-pss-settings'
        );
        
        // Categories Section
        add_settings_section(
            'futturu_pss_categories_section',
            __('Categorias/Setores', 'futturu-prof-site-sim'),
            array($this, 'categories_section_callback'),
            'futturu-pss-settings'
        );
        
        // Email Settings Section
        add_settings_section(
            'futturu_pss_email_section',
            __('Configurações de E-mail', 'futturu-prof-site-sim'),
            array($this, 'email_section_callback'),
            'futturu-pss-settings'
        );
        
        // Templates Section
        add_settings_section(
            'futturu_pss_templates_section',
            __('Templates de Descrição', 'futturu-prof-site-sim'),
            array($this, 'templates_section_callback'),
            'futturu-pss-settings'
        );
        
        // Register fields
        $this->register_fields();
    }
    
    /**
     * Register all settings fields
     */
    private function register_fields() {
        // Active/Inactive
        add_settings_field(
            'futturu_pss_active',
            __('Status do Plugin', 'futturu-prof-site-sim'),
            array($this, 'active_callback'),
            'futturu-pss-settings',
            'futturu_pss_general_section'
        );
        register_setting('futturu-pss-settings-group', 'futturu_pss_active');
        
        // CTA Text
        add_settings_field(
            'futturu_pss_cta_text',
            __('Texto do CTA Final', 'futturu-prof-site-sim'),
            array($this, 'cta_text_callback'),
            'futturu-pss-settings',
            'futturu_pss_general_section'
        );
        register_setting('futturu-pss-settings-group', 'futturu_pss_cta_text');
        
        // Site Types
        add_settings_field(
            'futturu_pss_site_types',
            __('Tipos de Site Disponíveis', 'futturu-prof-site-sim'),
            array($this, 'site_types_callback'),
            'futturu-pss-settings',
            'futturu_pss_types_section'
        );
        register_setting('futturu-pss-settings-group', 'futturu_pss_site_types');
        
        // Categories
        add_settings_field(
            'futturu_pss_categories',
            __('Categorias/Setores', 'futturu-prof-site-sim'),
            array($this, 'categories_callback'),
            'futturu-pss-settings',
            'futturu_pss_categories_section'
        );
        register_setting('futturu-pss-settings-group', 'futturu_pss_categories');
        
        // Email To
        add_settings_field(
            'futturu_pss_email_to',
            __('E-mail de Destino', 'futturu-prof-site-sim'),
            array($this, 'email_to_callback'),
            'futturu-pss-settings',
            'futturu_pss_email_section'
        );
        register_setting('futturu-pss-settings-group', 'futturu_pss_email_to', 'sanitize_email');
        
        // Email Subject
        add_settings_field(
            'futturu_pss_email_subject',
            __('Assunto do E-mail', 'futturu-prof-site-sim'),
            array($this, 'email_subject_callback'),
            'futturu-pss-settings',
            'futturu_pss_email_section'
        );
        register_setting('futturu-pss-settings-group', 'futturu_pss_email_subject');
        
        // Description Templates
        add_settings_field(
            'futturu_pss_templates',
            __('Templates de Descrição para Preview', 'futturu-prof-site-sim'),
            array($this, 'templates_callback'),
            'futturu-pss-settings',
            'futturu_pss_templates_section'
        );
        register_setting('futturu-pss-settings-group', 'futturu_pss_templates');
    }
    
    /**
     * Section callbacks
     */
    public function general_section_callback() {
        echo '<p>' . __('Configure as opções gerais do simulador.', 'futturu-prof-site-sim') . '</p>';
    }
    
    public function types_section_callback() {
        echo '<p>' . __('Defina os tipos de site disponíveis para seleção. Um por linha no formato: slug|Nome Display', 'futturu-prof-site-sim') . '</p>';
    }
    
    public function categories_section_callback() {
        echo '<p>' . __('Defina as categorias/setores disponíveis. Uma categoria por linha.', 'futturu-prof-site-sim') . '</p>';
    }
    
    public function email_section_callback() {
        echo '<p>' . __('Configure o e-mail que receberá os leads gerados pelo simulador.', 'futturu-prof-site-sim') . '</p>';
    }
    
    public function templates_section_callback() {
        echo '<p>' . __('Templates usados para gerar a descrição "Sobre" no preview. Use placeholders: {nome}, {categoria}, {localidade}, {servicos}. Um template por linha.', 'futturu-prof-site-sim') . '</p>';
    }
    
    /**
     * Field callbacks
     */
    public function active_callback() {
        $value = get_option('futturu_pss_active', 'yes');
        echo '<label><input type="checkbox" name="futturu_pss_active" value="yes" ' . checked($value, 'yes', false) . '> ' . __('Ativar Simulador', 'futturu-prof-site-sim') . '</label>';
    }
    
    public function cta_text_callback() {
        $value = get_option('futturu_pss_cta_text', 'Solicite uma Proposta Personalizada');
        echo '<input type="text" name="futturu_pss_cta_text" value="' . esc_attr($value) . '" class="regular-text">';
    }
    
    public function email_to_callback() {
        $value = get_option('futturu_pss_email_to', 'suporte@futturu.com.br');
        echo '<input type="email" name="futturu_pss_email_to" value="' . esc_attr($value) . '" class="regular-text">';
    }
    
    public function email_subject_callback() {
        $value = get_option('futturu_pss_email_subject', 'Nova Proposta - Simulador de Site Profissional');
        echo '<input type="text" name="futturu_pss_email_subject" value="' . esc_attr($value) . '" class="regular-text">';
    }
    
    public function site_types_callback() {
        $value = get_option('futturu_pss_site_types');
        if (is_serialized($value)) {
            $types = unserialize($value);
            $text = '';
            foreach ($types as $slug => $name) {
                $text .= $slug . '|' . $name . "\n";
            }
        } else {
            $text = "professional|Site Profissional (Advogados, Médicos, Engenheiros...)\nservices|Site para Empresa de Serviços\nrestaurant|Site para Restaurante ou Delivery\ncatalog|Catálogo Digital / Loja Virtual Básica\nother|Outro";
        }
        echo '<textarea name="futturu_pss_site_types" rows="6" cols="50" class="large-text">' . esc_textarea($text) . '</textarea>';
        echo '<p class="description">' . __('Formato: slug|Nome Display (um por linha)', 'futturu-prof-site-sim') . '</p>';
    }
    
    public function categories_callback() {
        $value = get_option('futturu_pss_categories');
        if (is_serialized($value)) {
            $cats = unserialize($value);
            $text = implode("\n", $cats);
        } else {
            $text = "Advocacia\nMedicina\nEngenharia\nRestaurante\nDelivery\nConsultoria\nOficina\nComércio\nEducação\nSaúde\nTecnologia\nBeleza\nFitness\nImobiliário\nOutros";
        }
        echo '<textarea name="futturu_pss_categories" rows="8" cols="50" class="large-text">' . esc_textarea($text) . '</textarea>';
    }
    
    public function templates_callback() {
        $value = get_option('futturu_pss_templates');
        if (is_serialized($value)) {
            $templates = unserialize($value);
            $text = implode("\n", $templates);
        } else {
            $templates = $this->get_default_templates();
            $text = implode("\n", $templates);
        }
        echo '<textarea name="futturu_pss_templates" rows="12" cols="50" class="large-text">' . esc_textarea($text) . '</textarea>';
        echo '<p class="description">' . __('Use placeholders: {nome}, {categoria}, {localidade}, {servicos}', 'futturu-prof-site-sim') . '</p>';
    }
    
    /**
     * Get default description templates
     */
    private function get_default_templates() {
        return array(
            '{nome} é referência em {categoria} na região de {localidade}. Oferecemos soluções personalizadas em {servicos} com qualidade e profissionalismo.',
            'Especialistas em {categoria}, a {nome} atende clientes em {localidade} e região com excelência em {servicos}.',
            'A {nome} oferece serviços profissionais de {categoria} em {localidade}. Conheça nossos trabalhos em {servicos}.',
            'Com anos de experiência em {categoria}, a {nome} se destaca em {localidade} pela qualidade dos serviços: {servicos}.',
            '{nome} - Sua escolha certa em {categoria} em {localidade}. Solicite um orçamento para {servicos}.',
            'Profissionais qualificados em {categoria} esperam por você na {nome}, localizada em {localidade}. Especialidades: {servicos}.',
            'A {nome} é sinônimo de confiança em {categoria}. Atendemos {localidade} com excelência em {servicos}.',
            'Busca por {categoria} de qualidade em {localidade}? A {nome} oferece o melhor em {servicos}.',
            '{nome}: tradição e modernidade em {categoria}. Atendendo {localidade} com serviços especializados em {servicos}.',
            'Excelência em {categoria} você encontra na {nome}. Localizada em {localidade}, oferecemos {servicos} com o melhor custo-benefício.'
        );
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Save options on form submission
        if (isset($_POST['submit']) && isset($_POST['futturu_pss_site_types'])) {
            // Process site types
            $types_text = sanitize_textarea_field($_POST['futturu_pss_site_types']);
            $types_array = array();
            $lines = explode("\n", $types_text);
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line) && strpos($line, '|') !== false) {
                    list($slug, $name) = explode('|', $line, 2);
                    $types_array[sanitize_key($slug)] = sanitize_text_field($name);
                }
            }
            $_POST['futturu_pss_site_types'] = serialize($types_array);
        }
        
        if (isset($_POST['submit']) && isset($_POST['futturu_pss_categories'])) {
            // Process categories
            $cats_text = sanitize_textarea_field($_POST['futturu_pss_categories']);
            $cats_array = array();
            $lines = explode("\n", $cats_text);
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line)) {
                    $cats_array[] = sanitize_text_field($line);
                }
            }
            $_POST['futturu_pss_categories'] = serialize($cats_array);
        }
        
        if (isset($_POST['submit']) && isset($_POST['futturu_pss_templates'])) {
            // Process templates
            $templates_text = sanitize_textarea_field($_POST['futturu_pss_templates']);
            $templates_array = array();
            $lines = explode("\n", $templates_text);
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line)) {
                    $templates_array[] = sanitize_text_field($line);
                }
            }
            $_POST['futturu_pss_templates'] = serialize($templates_array);
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <?php if (isset($_GET['settings-updated'])) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e('Configurações salvas com sucesso!', 'futturu-prof-site-sim'); ?></p>
                </div>
            <?php endif; ?>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('futturu-pss-settings-group');
                do_settings_sections('futturu-pss-settings');
                submit_button(__('Salvar Configurações', 'futturu-prof-site-sim'));
                ?>
            </form>
            
            <hr>
            
            <h2><?php _e('Como Usar', 'futturu-prof-site-sim'); ?></h2>
            <p><?php _e('Utilize o shortcode abaixo em qualquer página ou post do seu WordPress:', 'futturu-prof-site-sim'); ?></p>
            <code style="display: block; padding: 10px; background: #f0f0f1; margin: 10px 0;">[futturu_prof_site_sim]</code>
            
            <h3><?php _e('Shortcodes com Parâmetros', 'futturu-prof-site-sim'); ?></h3>
            <p><?php _e('Você pode personalizar o simulador com parâmetros:', 'futturu-prof-site-sim'); ?></p>
            <code style="display: block; padding: 10px; background: #f0f0f1; margin: 10px 0;">[futturu_prof_site_sim show_title="false" cta_text="Entre em Contato"]</code>
        </div>
        <?php
    }
    
    /**
     * Add plugin action links
     */
    public function add_plugin_links($links) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=futturu-pss-settings') . '">' . __('Configurações', 'futturu-prof-site-sim') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}
