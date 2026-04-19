<?php
/**
 * Plugin Name: Perfutturu - Otimizador de Performance Profundo
 * Plugin URI: https://futturu.com.br/perfutturu
 * Description: Eleve drasticamente o desempenho do seu site WordPress com otimizações profundas de CSS, JS, imagens, fontes e controle granular de scripts. Focado em Core Web Vitals e maximização de performance em hospedagens gerenciadas.
 * Version: 1.0.0
 * Author: Futturu
 * Author URI: https://futturu.com.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: perfutturu
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PERFUTTURU_VERSION', '1.0.0');
define('PERFUTTURU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PERFUTTURU_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PERFUTTURU_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
final class Perfutturu_Optimizer {
    
    /**
     * Single instance of the class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->load_dependencies();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Activation/Deactivation
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Load text domain
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        
        // Admin menu and settings
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Frontend optimizations
        add_action('wp', array($this, 'init_optimizations'), 1);
        
        // HTML optimization
        add_action('wp_footer', array($this, 'buffer_start'), 0);
        add_action('shutdown', array($this, 'buffer_end'), 0);
        
        // Preload critical resources
        add_action('wp_head', array($this, 'add_preload_tags'), 1);
        
        // Font optimization
        add_filter('style_loader_tag', array($this, 'optimize_font_loading'), 10, 2);
        
        // Image optimization
        add_filter('wp_get_attachment_image_attributes', array($this, 'optimize_image_attributes'), 10, 3);
        
        // Remove unnecessary WordPress features
        add_action('init', array($this, 'cleanup_head'));
    }
    
    /**
     * Load plugin dependencies
     */
    private function load_dependencies() {
        // Load admin classes
        if (is_admin()) {
            require_once PERFUTTURU_PLUGIN_DIR . 'admin/class-perfutturu-admin.php';
        }
        
        // Load optimization classes
        require_once PERFUTTURU_PLUGIN_DIR . 'includes/class-perfutturu-css-optimizer.php';
        require_once PERFUTTURU_PLUGIN_DIR . 'includes/class-perfutturu-js-optimizer.php';
        require_once PERFUTTURU_PLUGIN_DIR . 'includes/class-perfutturu-image-optimizer.php';
        require_once PERFUTTURU_PLUGIN_DIR . 'includes/class-perfutturu-font-optimizer.php';
        require_once PERFUTTURU_PLUGIN_DIR . 'includes/class-perfutturu-cleanup.php';
        require_once PERFUTTURU_PLUGIN_DIR . 'includes/class-perfutturu-cache.php';
    }
    
    /**
     * Activation hook
     */
    public function activate() {
        // Set default options
        $defaults = array(
            'perfutturu_enabled' => 1,
            'perfutturu_test_mode' => 0,
            'perfutturu_css_minify' => 1,
            'perfutturu_js_minify' => 1,
            'perfutturu_html_minify' => 1,
            'perfutturu_lazy_load_images' => 1,
            'perfutturu_lazy_load_iframes' => 1,
            'perfutturu_preload_critical_images' => '',
            'perfutturu_host_fonts_locally' => 0,
            'perfutturu_font_display_swap' => 1,
            'perfutturu_remove_emojis' => 1,
            'perfutturu_remove_embeds' => 1,
            'perfutturu_remove_dashicons' => 0,
            'perfutturu_dns_prefetch' => '',
            'perfutturu_preconnect' => '',
            'perfutturu_critical_css' => array(),
            'perfutturu_db_cleanup_revisions' => 0,
            'perfutturu_db_cleanup_transients' => 0,
        );
        
        foreach ($defaults as $option => $value) {
            if (get_option($option) === false) {
                add_option($option, $value);
            }
        }
        
        // Create cache directory
        $upload_dir = wp_upload_dir();
        $cache_dir = $upload_dir['basedir'] . '/perfutturu-cache';
        if (!file_exists($cache_dir)) {
            wp_mkdir_p($cache_dir);
        }
        
        // Add .htaccess to protect cache directory
        $htaccess_file = $cache_dir . '/.htaccess';
        if (!file_exists($htaccess_file)) {
            file_put_contents($htaccess_file, 'deny from all');
        }
        
        flush_rewrite_rules();
    }
    
    /**
     * Deactivation hook
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Load text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain('perfutturu', false, dirname(PERFUTTURU_PLUGIN_BASENAME) . '/languages');
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Perfutturu', 'perfutturu'),
            __('Perfutturu', 'perfutturu'),
            'manage_options',
            'perfutturu',
            array($this, 'render_admin_page'),
            'dashicons-performance',
            99
        );
        
        add_submenu_page(
            'perfutturu',
            __('Configurações', 'perfutturu'),
            __('Configurações', 'perfutturu'),
            'manage_options',
            'perfutturu-settings',
            array($this, 'render_settings_page')
        );
        
        add_submenu_page(
            'perfutturu',
            __('Core Web Vitals', 'perfutturu'),
            __('Core Web Vitals', 'perfutturu'),
            'manage_options',
            'perfutturu-cwv',
            array($this, 'render_cwv_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('perfutturu_group', 'perfutturu_enabled');
        register_setting('perfutturu_group', 'perfutturu_test_mode');
        register_setting('perfutturu_group', 'perfutturu_css_minify');
        register_setting('perfutturu_group', 'perfutturu_js_minify');
        register_setting('perfutturu_group', 'perfutturu_html_minify');
        register_setting('perfutturu_group', 'perfutturu_lazy_load_images');
        register_setting('perfutturu_group', 'perfutturu_lazy_load_iframes');
        register_setting('perfutturu_group', 'perfutturu_preload_critical_images');
        register_setting('perfutturu_group', 'perfutturu_host_fonts_locally');
        register_setting('perfutturu_group', 'perfutturu_font_display_swap');
        register_setting('perfutturu_group', 'perfutturu_remove_emojis');
        register_setting('perfutturu_group', 'perfutturu_remove_embeds');
        register_setting('perfutturu_group', 'perfutturu_remove_dashicons');
        register_setting('perfutturu_group', 'perfutturu_dns_prefetch');
        register_setting('perfutturu_group', 'perfutturu_preconnect');
        register_setting('perfutturu_group', 'perfutturu_critical_css');
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Load on any Perfutturu admin page
        if (strpos($hook, 'perfutturu') === false) {
            return;
        }
        
        error_log('perfutturu: Enqueuing admin assets for hook: ' . $hook);
        
        wp_enqueue_style('perfutturu-admin', PERFUTTURU_PLUGIN_URL . 'assets/css/admin.css', array(), PERFUTTURU_VERSION);
        wp_enqueue_script('perfutturu-admin', PERFUTTURU_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), PERFUTTURU_VERSION, true);
        
        // Generate nonce specifically for AJAX actions
        $ajax_nonce = wp_create_nonce('perfutturu_admin_nonce');
        error_log('perfutturu: Generated nonce: ' . substr($ajax_nonce, 0, 10) . '...');
        
        wp_localize_script('perfutturu-admin', 'perfutturuAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => $ajax_nonce,
            'strings' => array(
                'saving' => __('Salvando...', 'perfutturu'),
                'saved' => __('Salvo com sucesso!', 'perfutturu'),
                'error' => __('Erro ao salvar', 'perfutturu'),
            ),
        ));
    }
    
    /**
     * Initialize optimizations
     */
    public function init_optimizations() {
        // Check if plugin is enabled
        if (!get_option('perfutturu_enabled', 1)) {
            return;
        }
        
        // Initialize optimization classes
        if (class_exists('Perfutturu_CSS_Optimizer')) {
            Perfutturu_CSS_Optimizer::get_instance();
        }
        
        if (class_exists('Perfutturu_JS_Optimizer')) {
            Perfutturu_JS_Optimizer::get_instance();
        }
        
        if (class_exists('Perfutturu_Image_Optimizer')) {
            Perfutturu_Image_Optimizer::get_instance();
        }
        
        if (class_exists('Perfutturu_Font_Optimizer')) {
            Perfutturu_Font_Optimizer::get_instance();
        }
        
        if (class_exists('Perfutturu_Cleanup')) {
            Perfutturu_Cleanup::get_instance();
        }
    }
    
    /**
     * Output buffering for HTML optimization
     */
    public function buffer_start() {
        if (!get_option('perfutturu_enabled', 1)) {
            return;
        }
        
        if (get_option('perfutturu_html_minify', 1) && !is_user_logged_in()) {
            ob_start(array($this, 'minify_html'));
        }
    }
    
    /**
     * End output buffering
     */
    public function buffer_end() {
        if (!get_option('perfutturu_enabled', 1)) {
            return;
        }
        
        if (get_option('perfutturu_html_minify', 1) && !is_user_logged_in() && ob_get_length()) {
            ob_end_flush();
        }
    }
    
    /**
     * Minify HTML
     */
    public function minify_html($html) {
        // Remove comments
        $html = preg_replace('/<!--[^>]*>/', '', $html);
        
        // Remove whitespace between tags
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Remove trailing whitespace
        $html = preg_replace('/\s+$/', '', $html);
        
        return trim($html);
    }
    
    /**
     * Add preload tags for critical resources
     */
    public function add_preload_tags() {
        if (!get_option('perfutturu_enabled', 1)) {
            return;
        }
        
        // Preload critical images
        $critical_images = get_option('perfutturu_preload_critical_images', '');
        if (!empty($critical_images)) {
            $images = array_map('trim', explode("\n", $critical_images));
            foreach ($images as $image_url) {
                if (!empty($image_url)) {
                    echo '<link rel="preload" as="image" href="' . esc_url($image_url) . '">' . "\n";
                }
            }
        }
        
        // DNS Prefetch
        $dns_prefetch = get_option('perfutturu_dns_prefetch', '');
        if (!empty($dns_prefetch)) {
            $domains = array_map('trim', explode("\n", $dns_prefetch));
            foreach ($domains as $domain) {
                if (!empty($domain)) {
                    echo '<link rel="dns-prefetch" href="' . esc_url($domain) . '">' . "\n";
                }
            }
        }
        
        // Preconnect
        $preconnect = get_option('perfutturu_preconnect', '');
        if (!empty($preconnect)) {
            $domains = array_map('trim', explode("\n", $preconnect));
            foreach ($domains as $domain) {
                if (!empty($domain)) {
                    echo '<link rel="preconnect" href="' . esc_url($domain) . '" crossorigin>' . "\n";
                }
            }
        }
    }
    
    /**
     * Optimize font loading
     */
    public function optimize_font_loading($html, $handle) {
        if (!get_option('perfutturu_enabled', 1)) {
            return $html;
        }
        
        // Add font-display: swap via media hack
        if (get_option('perfutturu_font_display_swap', 1)) {
            if (strpos($handle, 'google-fonts') !== false || strpos($handle, 'google_fonts') !== false) {
                $html = str_replace("media='all'", "media='print' onload=\"this.media='all'\"", $html);
            }
        }
        
        return $html;
    }
    
    /**
     * Optimize image attributes
     */
    public function optimize_image_attributes($attr, $attachment, $size) {
        if (!get_option('perfutturu_enabled', 1)) {
            return $attr;
        }
        
        // Add loading="lazy" to images
        if (get_option('perfutturu_lazy_load_images', 1)) {
            // Don't lazy load above-the-fold images (first few images)
            static $image_count = 0;
            $image_count++;
            
            if ($image_count > 3) {
                $attr['loading'] = 'lazy';
            }
            
            // Add fetchpriority="high" to LCP image (usually first image)
            if ($image_count === 1 && is_singular()) {
                $attr['fetchpriority'] = 'high';
            }
        }
        
        // Ensure width and height are present to prevent CLS
        if (empty($attr['width']) || empty($attr['height'])) {
            $meta = wp_get_attachment_metadata($attachment->ID);
            if (!empty($meta['width']) && !empty($meta['height'])) {
                $attr['width'] = $meta['width'];
                $attr['height'] = $meta['height'];
            }
        }
        
        return $attr;
    }
    
    /**
     * Cleanup WordPress head
     */
    public function cleanup_head() {
        if (!get_option('perfutturu_enabled', 1)) {
            return;
        }
        
        // Remove emojis
        if (get_option('perfutturu_remove_emojis', 1)) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
            
            // Remove emoji CSS
            add_filter('emoji_svg_url', '__return_false');
        }
        
        // Remove embeds
        if (get_option('perfutturu_remove_embeds', 1)) {
            remove_action('rest_api_init', 'wp_oembed_register_route');
            remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
            remove_action('wp_head', 'wp_oembed_add_host_js');
            wp_dequeue_script('wp-embed');
        }
        
        // Remove dashicons for non-logged-in users
        if (get_option('perfutturu_remove_dashicons', 0) && !is_user_logged_in()) {
            wp_dequeue_style('dashicons');
        }
        
        // Remove wlwmanifest link
        remove_action('wp_head', 'wlwmanifest_link');
        
        // Remove RSD link
        remove_action('wp_head', 'rsd_link');
        
        // Remove shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Remove generator meta tag
        remove_action('wp_head', 'wp_generator');
        
        // Remove WordPress version
        add_filter('the_generator', '__return_false');
    }
    
    /**
     * Render main admin page
     */
    public function render_admin_page() {
        include PERFUTTURU_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        include PERFUTTURU_PLUGIN_DIR . 'admin/views/settings.php';
    }
    
    /**
     * Render Core Web Vitals page
     */
    public function render_cwv_page() {
        include PERFUTTURU_PLUGIN_DIR . 'admin/views/cwv.php';
    }
}

// Initialize plugin
function perfutturu_init() {
    return Perfutturu_Optimizer::get_instance();
}
add_action('plugins_loaded', 'perfutturu_init');
