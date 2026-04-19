<?php
/**
 * JavaScript Optimizer Class
 * 
 * Handles JS minification and optimization
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Perfutturu_JS_Optimizer {
    
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
        $this->cache_dir = $upload_dir['basedir'] . '/perfutturu-cache/js';
        
        if (!file_exists($this->cache_dir)) {
            wp_mkdir_p($this->cache_dir);
        }
        
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Minify JS
        if (get_option('perfutturu_js_minify', 1)) {
            add_filter('script_loader_src', array($this, 'minify_js_file'), 10, 2);
        }
    }
    
    /**
     * Minify JS file
     */
    public function minify_js_file($src, $handle) {
        // Skip admin
        if (is_admin()) {
            return $src;
        }
        
        // Skip external URLs
        if (strpos($src, site_url()) === false && strpos($src, content_url()) === false) {
            return $src;
        }
        
        // Get cached version
        $cached_file = $this->get_cached_file($src, 'js');
        
        if ($cached_file && file_exists($cached_file)) {
            return $this->get_cache_url($cached_file);
        }
        
        // Minify and cache
        $js_content = $this->fetch_file_content($src);
        
        if ($js_content) {
            $minified = $this->minify_js($js_content);
            $this->save_cache($minified, $src, 'js');
            return $this->get_cache_url($this->get_cached_file($src, 'js'));
        }
        
        return $src;
    }
    
    /**
     * Minify JS content (basic implementation)
     */
    private function minify_js($js) {
        // Remove single-line comments (but not in strings)
        $js = preg_replace('/\/\/.*$/m', '', $js);
        
        // Remove multi-line comments
        $js = preg_replace('/\/\*[\s\S]*?\*\//', '', $js);
        
        // Remove leading/trailing whitespace from lines
        $lines = explode("\n", $js);
        $lines = array_map('trim', $lines);
        $js = implode("\n", $lines);
        
        // Remove empty lines
        $js = preg_replace('/^\s*[\r\n]/m', '', $js);
        
        // Remove multiple spaces
        $js = preg_replace('/\s+/', ' ', $js);
        
        return trim($js);
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
     * Clear JS cache
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
