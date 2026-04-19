<?php
/**
 * Cleanup Class
 * 
 * Handles WordPress cleanup and removal of unnecessary features
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Perfutturu_Cleanup {
    
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
        // Remove emojis
        if (get_option('perfutturu_remove_emojis', 1)) {
            $this->remove_emojis();
        }
        
        // Remove embeds
        if (get_option('perfutturu_remove_embeds', 1)) {
            $this->remove_embeds();
        }
        
        // Remove dashicons
        if (get_option('perfutturu_remove_dashicons', 0)) {
            add_action('wp_enqueue_scripts', array($this, 'remove_dashicons'));
        }
        
        // Remove WordPress version
        add_filter('the_generator', '__return_false');
        remove_action('wp_head', 'wp_generator');
        
        // Remove WLW manifest
        remove_action('wp_head', 'wlwmanifest_link');
        
        // Remove RSD link
        remove_action('wp_head', 'rsd_link');
        
        // Remove shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_shortlink_header', 'wp_shortlink_header');
        
        // Remove adjacent posts links
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        
        // Remove REST API links
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
        
        // Remove DNS prefetch for WordPress.com
        remove_action('wp_head', 'wp_resource_hints', 2);
        
        // Remove jQuery Migrate
        add_filter('wp_default_scripts', array($this, 'remove_jquery_migrate'));
        
        // Clean up head
        add_action('wp_head', array($this, 'cleanup_head'), 999);
    }
    
    /**
     * Remove emojis completely
     */
    private function remove_emojis() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        
        // Remove emoji CSS
        add_filter('emoji_svg_url', '__return_false');
        
        // Remove emoji script from admin
        add_action('admin_init', function() {
            wp_deregister_script('wp-emoji');
        });
    }
    
    /**
     * Remove embeds
     */
    private function remove_embeds() {
        remove_action('rest_api_init', 'wp_oembed_register_route');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        
        // Remove embed rewrite rule
        global $wp_rewrite;
        $wp_rewrite->embed_base = null;
        
        // Dequeue embed script
        add_action('wp_footer', function() {
            wp_dequeue_script('wp-embed');
        }, 100);
    }
    
    /**
     * Remove dashicons
     */
    public function remove_dashicons() {
        if (!is_user_logged_in()) {
            wp_dequeue_style('dashicons');
        }
    }
    
    /**
     * Remove jQuery Migrate
     */
    public function remove_jquery_migrate($scripts) {
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];
            
            if ($script->deps) {
                $script->deps = array_diff($script->deps, array('jquery-migrate'));
            }
        }
        
        return $scripts;
    }
    
    /**
     * Final head cleanup
     */
    public function cleanup_head() {
        // Remove any remaining unnecessary meta tags
        ob_start(function($buffer) {
            // Remove generator tag if still present
            $buffer = preg_replace('/<meta name="generator" content="WordPress[^"]*"[^>]*>/i', '', $buffer);
            
            // Remove WordPress version from scripts/styles
            $buffer = preg_replace('/(ver=' . get_bloginfo('version') . ')/i', '', $buffer);
            
            return $buffer;
        });
    }
    
    /**
     * Clean database - remove post revisions
     */
    public function clean_revisions() {
        global $wpdb;
        
        $revisions = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'revision'");
        
        foreach ($revisions as $revision_id) {
            wp_delete_post($revision_id, true);
        }
        
        return count($revisions);
    }
    
    /**
     * Clean database - remove expired transients
     */
    public function clean_transients() {
        global $wpdb;
        
        $expired = $wpdb->get_col(
            "SELECT option_name FROM {$wpdb->options} 
             WHERE option_name LIKE '%_transient_timeout_%' 
             AND option_value < UNIX_TIMESTAMP()"
        );
        
        $count = 0;
        
        foreach ($expired as $transient_timeout) {
            $transient_name = str_replace('_transient_timeout_', '', $transient_timeout);
            delete_transient($transient_name);
            $count++;
        }
        
        return $count;
    }
    
    /**
     * Clean database - remove spam comments
     */
    public function clean_spam_comments() {
        global $wpdb;
        
        $spam = $wpdb->get_col("SELECT comment_ID FROM {$wpdb->comments} WHERE comment_approved = 'spam'");
        
        foreach ($spam as $comment_id) {
            wp_delete_comment($comment_id, true);
        }
        
        return count($spam);
    }
    
    /**
     * Clean database - remove trashed posts
     */
    public function clean_trashed_posts() {
        global $wpdb;
        
        $trashed = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_status = 'trash'");
        
        foreach ($trashed as $post_id) {
            wp_delete_post($post_id, true);
        }
        
        return count($trashed);
    }
    
    /**
     * Optimize database tables
     */
    public function optimize_tables() {
        global $wpdb;
        
        $tables = $wpdb->get_col("SHOW TABLES");
        
        foreach ($tables as $table) {
            $wpdb->query("OPTIMIZE TABLE {$table}");
        }
    }
}
