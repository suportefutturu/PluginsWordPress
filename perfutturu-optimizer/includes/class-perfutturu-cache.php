<?php
/**
 * Cache Class
 * 
 * Handles caching optimizations and integration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Perfutturu_Cache {
    
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
        $this->cache_dir = $upload_dir['basedir'] . '/perfutturu-cache';
        
        if (!file_exists($this->cache_dir)) {
            wp_mkdir_p($this->cache_dir);
        }
        
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Add cache headers for static resources
        add_filter('wp_headers', array($this, 'add_cache_headers'), 10, 2);
        
        // Browser caching hints
        add_action('wp_head', array($this, 'add_cache_hints'), 1);
        
        // Object caching for queries
        if (get_option('perfutturu_object_cache', 0)) {
            add_filter('posts_results', array($this, 'cache_query_results'), 10, 2);
        }
    }
    
    /**
     * Add cache headers for static resources
     */
    public function add_cache_headers($headers, $path) {
        // Add long-term cache headers for static assets
        if (strpos($path, '.css') !== false || strpos($path, '.js') !== false) {
            $headers['Cache-Control'] = 'public, max-age=31536000';
        }
        
        return $headers;
    }
    
    /**
     * Add cache hints to head
     */
    public function add_cache_hints() {
        // Preconnect to CDN if configured
        $cdn_url = get_option('perfutturu_cdn_url', '');
        
        if (!empty($cdn_url)) {
            echo '<link rel="preconnect" href="' . esc_url($cdn_url) . '" crossorigin>' . "\n";
            echo '<link rel="dns-prefetch" href="' . esc_url($cdn_url) . '">' . "\n";
        }
        
        // Preconnect to Gravatar
        if (get_option('perfutturu_preconnect_gravatar', 1)) {
            echo '<link rel="preconnect" href="https://secure.gravatar.com">' . "\n";
            echo '<link rel="dns-prefetch" href="https://secure.gravatar.com">' . "\n";
        }
    }
    
    /**
     * Cache query results
     */
    public function cache_query_results($posts, $query) {
        // Only cache certain types of queries
        if ($query->is_main_query() && !is_admin()) {
            $cache_key = 'perfutturu_query_' . md5(serialize($query->query_vars));
            $cached = get_transient($cache_key);
            
            if ($cached !== false) {
                return $cached;
            }
            
            set_transient($cache_key, $posts, HOUR_IN_SECONDS);
        }
        
        return $posts;
    }
    
    /**
     * Generate page cache (advanced feature)
     */
    public function generate_page_cache() {
        if (!get_option('perfutturu_page_cache', 0)) {
            return;
        }
        
        // This would require more complex implementation
        // For now, we provide basic static HTML generation
        if (is_singular() && !is_user_logged_in()) {
            $cache_file = $this->get_page_cache_file();
            
            if (file_exists($cache_file) && filemtime($cache_file) > time() - HOUR_IN_SECONDS) {
                readfile($cache_file);
                exit;
            }
            
            ob_start();
        }
    }
    
    /**
     * Get page cache file path
     */
    private function get_page_cache_file() {
        $url = $_SERVER['REQUEST_URI'];
        $url = rtrim($url, '/');
        
        if (empty($url)) {
            $url = '/index';
        }
        
        $url = preg_replace('/[^a-zA-Z0-9\/\-]/', '-', $url);
        
        return $this->cache_dir . '/page-' . md5($url) . '.html';
    }
    
    /**
     * Save page cache
     */
    public function save_page_cache($content) {
        if (!get_option('perfutturu_page_cache', 0)) {
            return $content;
        }
        
        $cache_file = $this->get_page_cache_file();
        file_put_contents($cache_file, $content);
        
        return $content;
    }
    
    /**
     * Clear all caches
     */
    public function clear_all() {
        // Clear CSS cache
        $css_dir = $this->cache_dir . '/css';
        if (file_exists($css_dir)) {
            $files = glob($css_dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        
        // Clear JS cache
        $js_dir = $this->cache_dir . '/js';
        if (file_exists($js_dir)) {
            $files = glob($js_dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        
        // Clear font cache
        $fonts_dir = $this->cache_dir . '/fonts';
        if (file_exists($fonts_dir)) {
            $files = glob($fonts_dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
        
        // Clear page cache
        $page_files = glob($this->cache_dir . '/page-*.html');
        foreach ($page_files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        
        // Clear transients
        delete_transient('perfutturu_critical_css');
        
        // Action for other plugins to hook into
        do_action('perfutturu_cache_cleared');
    }
    
    /**
     * Get cache size
     */
    public function get_cache_size() {
        $size = 0;
        
        if (file_exists($this->cache_dir)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($this->cache_dir, RecursiveDirectoryIterator::SKIP_DOTS)
            );
            
            foreach ($files as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }
        }
        
        return size_format($size, 2);
    }
    
    /**
     * Check if cache plugin is active
     */
    public function check_cache_plugin() {
        $cache_plugins = array(
            'wp-rocket/wp-rocket.php' => 'WP Rocket',
            'w3-total-cache/w3-total-cache.php' => 'W3 Total Cache',
            'wp-fastest-cache/wpFastestCache.php' => 'WP Fastest Cache',
            'litespeed-cache/litespeed-cache.php' => 'LiteSpeed Cache',
            'hummingbird-performance/wp-hummingbird.php' => 'Hummingbird',
        );
        
        foreach ($cache_plugins as $plugin => $name) {
            if (is_plugin_active($plugin)) {
                return $name;
            }
        }
        
        return false;
    }
}
