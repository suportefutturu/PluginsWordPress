<?php
/**
 * Font Optimizer Class
 * 
 * Handles font optimization and local hosting
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Perfutturu_Font_Optimizer {
    
    private static $instance = null;
    private $cache_dir;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $upload_dir = wp_upload_dir();
        $this->cache_dir = $upload_dir['basedir'] . '/perfutturu-cache/fonts';
        
        if (!file_exists($this->cache_dir)) {
            wp_mkdir_p($this->cache_dir);
        }
        
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Host Google Fonts locally
        if (get_option('perfutturu_host_fonts_locally', 0)) {
            add_filter('wp_enqueue_scripts', array($this, 'dequeue_google_fonts'), 100);
            add_action('wp_enqueue_scripts', array($this, 'enqueue_local_fonts'), 100);
        }
        
        // Add font-display: swap
        if (get_option('perfutturu_font_display_swap', 1)) {
            add_filter('wp_resource_hints', array($this, 'add_font_preconnect'), 10, 2);
        }
    }
    
    /**
     * Dequeue Google Fonts
     */
    public function dequeue_google_fonts() {
        global $wp_styles;
        
        if (!$wp_styles instanceof WP_Styles) {
            return;
        }
        
        foreach ($wp_styles->queue as $handle) {
            if (strpos($handle, 'google-fonts') !== false || 
                strpos($handle, 'google_fonts') !== false ||
                (isset($wp_styles->registered[$handle]) && 
                 strpos($wp_styles->registered[$handle]->src, 'fonts.googleapis.com') !== false)) {
                $wp_styles->remove($handle);
            }
        }
    }
    
    /**
     * Enqueue local fonts
     */
    public function enqueue_local_fonts() {
        $local_fonts = get_option('perfutturu_local_fonts', array());
        
        if (empty($local_fonts)) {
            return;
        }
        
        foreach ($local_fonts as $font_name => $font_files) {
            $font_family = sanitize_title($font_name);
            
            wp_register_style(
                "perfutturu-font-{$font_family}",
                $this->get_font_css_url($font_name),
                array(),
                PERFUTTURU_VERSION
            );
            
            wp_enqueue_style("perfutturu-font-{$font_family}");
        }
    }
    
    /**
     * Download and host Google Font locally
     */
    public function download_google_font($font_name, $weights = '400,700', $subsets = 'latin') {
        $api_url = "https://fonts.googleapis.com/css?family={$font_name}:{$weights}&subset={$subsets}";
        
        $response = wp_remote_get($api_url, array('timeout' => 30));
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $css = wp_remote_retrieve_body($response);
        
        // Parse CSS to find font URLs
        preg_match_all('/url\(([^)]+)\)/', $css, $matches);
        
        if (empty($matches[1])) {
            return false;
        }
        
        $font_files = array();
        
        foreach ($matches[1] as $url) {
            $url = trim($url, '\'"');
            $filename = basename(parse_url($url, PHP_URL_PATH));
            
            // Download font file
            $font_response = wp_remote_get($url, array('timeout' => 30));
            
            if (!is_wp_error($font_response) && wp_remote_retrieve_response_code($font_response) === 200) {
                $font_data = wp_remote_retrieve_body($font_response);
                $file_path = $this->cache_dir . '/' . $filename;
                
                file_put_contents($file_path, $font_data);
                $font_files[] = $filename;
            }
        }
        
        // Save font CSS
        $css_filename = sanitize_title($font_name) . '.css';
        $css_path = $this->cache_dir . '/' . $css_filename;
        file_put_contents($css_path, $css);
        
        // Update local fonts option
        $local_fonts = get_option('perfutturu_local_fonts', array());
        $local_fonts[$font_name] = $font_files;
        update_option('perfutturu_local_fonts', $local_fonts);
        
        return true;
    }
    
    /**
     * Get font CSS URL
     */
    private function get_font_css_url($font_name) {
        $upload_dir = wp_upload_dir();
        $relative_path = str_replace($upload_dir['basedir'], '', $this->cache_dir);
        $css_filename = sanitize_title($font_name) . '.css';
        return $upload_dir['baseurl'] . $relative_path . '/' . $css_filename;
    }
    
    /**
     * Add preconnect for fonts
     */
    public function add_font_preconnect($urls, $relation_type) {
        if ($relation_type === 'preconnect') {
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin' => true,
            );
        }
        
        return $urls;
    }
    
    /**
     * Add font-display: swap to font CSS
     */
    public function add_font_display_swap($css) {
        if (get_option('perfutturu_font_display_swap', 1)) {
            $css = str_replace('@font-face', '@font-face{font-display:swap}', $css);
        }
        
        return $css;
    }
    
    /**
     * Preload critical fonts
     */
    public function preload_fonts() {
        $preload_fonts = get_option('perfutturu_preload_fonts', '');
        
        if (empty($preload_fonts)) {
            return;
        }
        
        $fonts = array_map('trim', explode("\n", $preload_fonts));
        
        foreach ($fonts as $font_url) {
            if (!empty($font_url)) {
                echo '<link rel="preload" href="' . esc_url($font_url) . '" as="font" type="font/woff2" crossorigin>' . "\n";
            }
        }
    }
    
    /**
     * Clear font cache
     */
    public function clear_cache() {
        if (file_exists($this->cache_dir)) {
            $files = glob($this->cache_dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        
        delete_option('perfutturu_local_fonts');
    }
}
