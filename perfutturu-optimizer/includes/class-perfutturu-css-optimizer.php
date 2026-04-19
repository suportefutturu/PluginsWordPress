<?php
/**
 * CSS Optimizer Class
 * 
 * Handles CSS minification and optimization
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Perfutturu_CSS_Optimizer {
    
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
        $this->cache_dir = $upload_dir['basedir'] . '/perfutturu-cache/css';
        
        if (!file_exists($this->cache_dir)) {
            wp_mkdir_p($this->cache_dir);
        }
        
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Minify CSS
        if (get_option('perfutturu_css_minify', 1)) {
            add_filter('style_loader_src', array($this, 'minify_css_file'), 10, 2);
        }
    }
    
    /**
     * Minify CSS file
     */
    public function minify_css_file($src, $handle) {
        // Skip admin
        if (is_admin()) {
            return $src;
        }
        
        // Skip external URLs
        if (strpos($src, site_url()) === false && strpos($src, content_url()) === false) {
            return $src;
        }
        
        // Get cached version
        $cached_file = $this->get_cached_file($src, 'css');
        
        if ($cached_file && file_exists($cached_file)) {
            return $this->get_cache_url($cached_file);
        }
        
        // Minify and cache
        $css_content = $this->fetch_file_content($src);
        
        if ($css_content) {
            $minified = $this->minify_css($css_content);
            $this->save_cache($minified, $src, 'css');
            return $this->get_cache_url($this->get_cached_file($src, 'css'));
        }
        
        return $src;
    }
    
    /**
     * Minify CSS content
     */
    private function minify_css($css) {
        // Remove comments
        $css = preg_replace('/\/\*[^!](.*?)\*\//', '', $css);
        
        // Remove whitespace around symbols
        $css = preg_replace('/\s*([{}:;,+\-\/])\s*/', '$1', $css);
        
        // Remove trailing semicolons before closing brace
        $css = preg_replace('/;}/', '}', $css);
        
        // Remove multiple spaces
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Remove leading/trailing whitespace
        $css = trim($css);
        
        return $css;
    }
    
    /**
     * Fetch file content from URL or path
     */
    private function fetch_file_content($src) {
        // Convert URL to path if local
        $path = str_replace(content_url(), WP_CONTENT_DIR, $src);
        $path = str_replace(site_url(), ABSPATH, $path);
        
        if (file_exists($path)) {
            return file_get_contents($path);
        }
        
        // Try remote fetch
        $response = wp_remote_get($src, array('timeout' => 15));
        
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            return wp_remote_retrieve_body($response);
        }
        
        return false;
    }
    
    /**
     * Get cached file path
     */
    private function get_cached_file($src, $type) {
        $cache_key = md5($src);
        return $this->cache_dir . '/' . $cache_key . '.' . $type;
    }
    
    /**
     * Get cache URL
     */
    private function get_cache_url($file_path) {
        $upload_dir = wp_upload_dir();
        $relative_path = str_replace($upload_dir['basedir'], '', $file_path);
        return $upload_dir['baseurl'] . $relative_path;
    }
    
    /**
     * Save cache file
     */
    private function save_cache($content, $src, $type) {
        $cache_file = $this->get_cached_file($src, $type);
        file_put_contents($cache_file, $content);
    }
    
    /**
     * Generate critical CSS (advanced feature)
     */
    public function generate_critical_css() {
        // This would require more complex implementation
        // For now, we provide a placeholder for manual critical CSS input
        return get_option('perfutturu_critical_css', array());
    }
    
    /**
     * Clear CSS cache
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
    }
}
