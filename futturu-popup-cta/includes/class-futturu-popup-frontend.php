<?php
/**
 * Frontend Class for Futturu Popup CTA
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Futturu_Popup_Frontend {
    
    private $option_name = 'futturu_popup_options';
    
    public function __construct() {
        add_action('wp_footer', array($this, 'render_popup'), 9999);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    /**
     * Check if popup should be displayed on current page
     */
    private function should_display() {
        $options = get_option($this->option_name);
        
        // Check if enabled
        if (empty($options['enabled'])) {
            return false;
        }
        
        $display_pages = isset($options['display_pages']) ? $options['display_pages'] : 'all';
        
        // Check display rules
        switch ($display_pages) {
            case 'posts_only':
                if (!is_single()) {
                    return false;
                }
                break;
                
            case 'pages_only':
                if (!is_page()) {
                    return false;
                }
                break;
                
            case 'specific_pages':
                $page_ids = isset($options['display_page_ids']) ? $options['display_page_ids'] : array();
                if (!is_page($page_ids)) {
                    return false;
                }
                break;
                
            case 'except_pages':
                $exclude_ids = isset($options['exclude_page_ids']) ? $options['exclude_page_ids'] : array();
                if (is_page($exclude_ids)) {
                    return false;
                }
                break;
                
            case 'categories':
                if (!is_category() && !is_single()) {
                    return false;
                }
                
                $categories = isset($options['display_categories']) ? $options['display_categories'] : array();
                if (!empty($categories)) {
                    $current_categories = is_single() ? wp_get_post_categories(get_the_ID()) : array(get_queried_object_id());
                    if (!array_intersect($categories, $current_categories)) {
                        return false;
                    }
                }
                break;
        }
        
        // Check frequency
        if (!$this->check_frequency()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check frequency cookies
     */
    private function check_frequency() {
        $options = get_option($this->option_name);
        $frequency = isset($options['frequency']) ? $options['frequency'] : 'once_per_session';
        
        switch ($frequency) {
            case 'once_per_session':
                if (isset($_COOKIE['futturu_popup_session'])) {
                    return false;
                }
                break;
                
            case 'once_per_days':
                $days = isset($options['frequency_days']) ? $options['frequency_days'] : 7;
                $last_shown = isset($_COOKIE['futturu_popup_last_shown']) ? intval($_COOKIE['futturu_popup_last_shown']) : 0;
                $seconds = $days * DAY_IN_SECONDS;
                
                if ($last_shown > 0 && (time() - $last_shown) < $seconds) {
                    return false;
                }
                break;
                
            case 'max_count':
                $max_count = isset($options['frequency_count']) ? $options['frequency_count'] : 3;
                $view_count = isset($_COOKIE['futturu_popup_view_count']) ? intval($_COOKIE['futturu_popup_view_count']) : 0;
                
                if ($view_count >= $max_count) {
                    return false;
                }
                break;
        }
        
        return true;
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        if (!$this->should_display()) {
            return;
        }
        
        wp_enqueue_style(
            'futturu-popup-style',
            FUTTURU_POPUP_PLUGIN_URL . 'assets/css/futturu-popup.css',
            array(),
            FUTTURU_POPUP_VERSION
        );
        
        wp_enqueue_script(
            'futturu-popup-script',
            FUTTURU_POPUP_PLUGIN_URL . 'assets/js/futturu-popup.js',
            array('jquery'),
            FUTTURU_POPUP_VERSION,
            true
        );
        
        $options = get_option($this->option_name);
        
        wp_localize_script('futturu-popup-script', 'futturuPopupConfig', array(
            'displayTime' => isset($options['display_time']) ? $options['display_time'] : 'immediate',
            'displayDelay' => isset($options['display_delay']) ? $options['display_delay'] : 2,
            'scrollPercentage' => isset($options['scroll_percentage']) ? $options['scroll_percentage'] : 50,
            'enableAnimation' => isset($options['enable_animation']) ? $options['enable_animation'] : 1,
            'animationType' => isset($options['animation_type']) ? $options['animation_type'] : 'fade-in',
            'frequency' => isset($options['frequency']) ? $options['frequency'] : 'once_per_session',
            'frequencyDays' => isset($options['frequency_days']) ? $options['frequency_days'] : 7,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('futturu_popup_nonce')
        ));
    }
    
    /**
     * Render popup HTML
     */
    public function render_popup() {
        if (!$this->should_display()) {
            return;
        }
        
        $options = get_option($this->option_name);
        
        include FUTTURU_POPUP_PLUGIN_DIR . 'includes/class-futturu-popup-template.php';
    }
}
