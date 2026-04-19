<?php
/**
 * Perfutturu Script Manager Class
 * 
 * Handles script management functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Perfutturu_Script_Manager {
    
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
        // Add inline script for configuration
        add_action('wp_footer', array($this, 'output_script_configs'), 100);
        
        // Process delay scripts
        add_action('wp_enqueue_scripts', array($this, 'process_delay_scripts'), 999);
    }
    
    /**
     * Output script configurations as inline JS
     */
    public function output_script_configs() {
        if (is_admin()) {
            return;
        }
        
        $script_configs = get_option('perfutturu_script_configs', array());
        
        if (empty($script_configs)) {
            return;
        }
        
        // Filter configs for current page
        $active_configs = array();
        
        foreach ($script_configs as $handle => $config) {
            if (!empty($config['disabled'])) {
                // Check conditions
                $should_disable = false;
                
                if (!empty($config['disable_sitewide'])) {
                    $should_disable = true;
                }
                
                if (!empty($config['disable_on_front_page']) && is_front_page()) {
                    $should_disable = true;
                }
                
                if (!empty($config['disable_on_home']) && is_home()) {
                    $should_disable = true;
                }
                
                if (!empty($config['disable_on_singular']) && is_singular()) {
                    $should_disable = true;
                }
                
                if (!empty($config['disable_on_posts']) && is_singular('post')) {
                    $should_disable = true;
                }
                
                if (!empty($config['disable_on_pages']) && is_singular('page')) {
                    $should_disable = true;
                }
                
                if (!empty($config['disable_specific_ids'])) {
                    $ids = array_map('intval', explode(',', $config['disable_specific_ids']));
                    if (is_singular() && in_array(get_queried_object_id(), $ids)) {
                        $should_disable = true;
                    }
                }
                
                if ($should_disable) {
                    $active_configs[$handle] = 'disable';
                }
            }
            
            // Mark deferred/async scripts
            if (!empty($config['defer'])) {
                $active_configs[$handle] = 'defer';
            }
            
            if (!empty($config['async'])) {
                $active_configs[$handle] = 'async';
            }
        }
        
        if (!empty($active_configs)) {
            ?>
            <script id="perfutturu-script-configs" type="application/json">
                <?php echo json_encode($active_configs); ?>
            </script>
            <?php
        }
    }
    
    /**
     * Process delay scripts (load after user interaction)
     */
    public function process_delay_scripts() {
        $delay_scripts = get_option('perfutturu_delay_scripts', array());
        
        if (empty($delay_scripts)) {
            return;
        }
        
        // This would require more complex implementation
        // For now, we provide the framework
    }
    
    /**
     * Get all enqueued scripts for admin
     */
    public function get_enqueued_scripts() {
        global $wp_scripts, $wp_styles;
        
        $scripts = array();
        
        // Load a dummy query to populate scripts
        $dummy_query = new WP_Query(array('posts_per_page' => 1));
        if ($dummy_query->have_posts()) {
            $dummy_query->the_post();
        }
        
        // Get registered scripts
        if ($wp_scripts instanceof WP_Scripts) {
            foreach ($wp_scripts->registered as $handle => $script) {
                $scripts[$handle] = array(
                    'type' => 'script',
                    'src' => isset($script->src) ? $script->src : '',
                    'deps' => $script->deps,
                    'ver' => isset($script->ver) ? $script->ver : '',
                );
            }
        }
        
        // Get registered styles
        if ($wp_styles instanceof WP_Styles) {
            foreach ($wp_styles->registered as $handle => $style) {
                $scripts[$handle] = array(
                    'type' => 'style',
                    'src' => isset($style->src) ? $style->src : '',
                    'deps' => $style->deps,
                    'ver' => isset($style->ver) ? $style->ver : '',
                );
            }
        }
        
        wp_reset_postdata();
        
        return $scripts;
    }
    
    /**
     * Save script configuration
     */
    public function save_script_config($handle, $config) {
        $script_configs = get_option('perfutturu_script_configs', array());
        $script_configs[$handle] = $config;
        
        return update_option('perfutturu_script_configs', $script_configs);
    }
    
    /**
     * Delete script configuration
     */
    public function delete_script_config($handle) {
        $script_configs = get_option('perfutturu_script_configs', array());
        
        if (isset($script_configs[$handle])) {
            unset($script_configs[$handle]);
            return update_option('perfutturu_script_configs', $script_configs);
        }
        
        return false;
    }
    
    /**
     * Reset all script configurations
     */
    public function reset_all_configs() {
        return delete_option('perfutturu_script_configs');
    }
}
