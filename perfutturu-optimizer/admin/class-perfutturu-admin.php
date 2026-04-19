<?php
/**
 * Perfutturu Admin Class
 * 
 * Handles admin functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Perfutturu_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Add action links
        add_filter('plugin_action_links_' . PERFUTTURU_PLUGIN_BASENAME, array($this, 'add_action_links'));
        
        // Add admin notices
        add_action('admin_notices', array($this, 'admin_notices'));
        
        // Clear cache on post save
        add_action('save_post', array($this, 'clear_cache_on_post_save'));
        
        // Add dashboard widget
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widget'));
    }
    
    /**
     * Add action links to plugin page
     */
    public function add_action_links($links) {
        $custom_links = array(
            '<a href="' . admin_url('admin.php?page=perfutturu') . '">' . __('Dashboard', 'perfutturu') . '</a>',
            '<a href="' . admin_url('admin.php?page=perfutturu-settings') . '">' . __('Configurações', 'perfutturu') . '</a>',
        );
        
        return array_merge($custom_links, $links);
    }
    
    /**
     * Show admin notices
     */
    public function admin_notices() {
        // Check if plugin is enabled
        if (!get_option('perfutturu_enabled', 1)) {
            ?>
            <div class="notice notice-warning">
                <p>
                    <strong><?php _e('Perfutturu:', 'perfutturu'); ?></strong>
                    <?php _e('O plugin está desativado. Ative nas configurações para começar a otimizar.', 'perfutturu'); ?>
                    <a href="<?php echo admin_url('admin.php?page=perfutturu-settings'); ?>">
                        <?php _e('Ir para Configurações', 'perfutturu'); ?>
                    </a>
                </p>
            </div>
            <?php
        }
        
        // Test mode notice
        if (get_option('perfutturu_test_mode', 0)) {
            ?>
            <div class="notice notice-warning">
                <p>
                    <strong><?php _e('Perfutturu - Modo de Teste:', 'perfutturu'); ?></strong>
                    <?php _e('As otimizações não estão sendo aplicadas para usuários logados.', 'perfutturu'); ?>
                    <a href="<?php echo admin_url('admin.php?page=perfutturu-settings'); ?>">
                        <?php _e('Desativar Modo de Teste', 'perfutturu'); ?>
                    </a>
                </p>
            </div>
            <?php
        }
        
        // Cache plugin detected notice
        $cache_plugin = false;
        if (class_exists('Perfutturu_Cache')) {
            $cache_instance = Perfutturu_Cache::get_instance();
            $cache_plugin = $cache_instance->check_cache_plugin();
        }
        
        if ($cache_plugin) {
            ?>
            <div class="notice notice-info">
                <p>
                    <strong><?php _e('Perfutturu:', 'perfutturu'); ?></strong>
                    <?php printf(__('Plugin de cache detectado: %s. O Perfutturu complementa as otimizações de cache.', 'perfutturu'), esc_html($cache_plugin)); ?>
                </p>
            </div>
            <?php
        }
    }
    
    /**
     * Clear cache when post is saved
     */
    public function clear_cache_on_post_save($post_id) {
        // Skip autosaves
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Skip revisions
        if (wp_is_post_revision($post_id)) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Clear cache
        if (class_exists('Perfutturu_Cache')) {
            $cache_instance = Perfutturu_Cache::get_instance();
            $cache_instance->clear_all();
        }
    }
    
    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'perfutturu_dashboard_widget',
            __('Perfutturu - Status', 'perfutturu'),
            array($this, 'render_dashboard_widget')
        );
    }
    
    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        $enabled = get_option('perfutturu_enabled', 1);
        $optimizations_count = 0;
        
        // Count active optimizations
        if (get_option('perfutturu_css_minify', 1)) $optimizations_count++;
        if (get_option('perfutturu_js_minify', 1)) $optimizations_count++;
        if (get_option('perfutturu_html_minify', 1)) $optimizations_count++;
        if (get_option('perfutturu_lazy_load_images', 1)) $optimizations_count++;
        if (get_option('perfutturu_remove_emojis', 1)) $optimizations_count++;
        
        ?>
        <div class="perfutturu-widget-status">
            <p>
                <strong><?php _e('Status:', 'perfutturu'); ?></strong>
                <span class="<?php echo $enabled ? 'status-active' : 'status-inactive'; ?>">
                    <?php echo $enabled ? __('Ativo', 'perfutturu') : __('Inativo', 'perfutturu'); ?>
                </span>
            </p>
            
            <p>
                <strong><?php _e('Otimizações Ativas:', 'perfutturu'); ?></strong>
                <?php echo $optimizations_count; ?>
            </p>
            
            <p class="perfutturu-widget-links">
                <a href="<?php echo admin_url('admin.php?page=perfutturu'); ?>" class="button button-primary">
                    <?php _e('Dashboard', 'perfutturu'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=perfutturu-settings'); ?>" class="button">
                    <?php _e('Configurar', 'perfutturu'); ?>
                </a>
            </p>
        </div>
        <?php
    }
}
