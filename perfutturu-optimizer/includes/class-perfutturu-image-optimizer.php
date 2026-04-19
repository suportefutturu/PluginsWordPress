<?php
/**
 * Image Optimizer Class
 * 
 * Handles image lazy loading and optimization
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Perfutturu_Image_Optimizer {
    
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
        // Lazy load images
        if (get_option('perfutturu_lazy_load_images', 1)) {
            add_filter('wp_get_attachment_image_attributes', array($this, 'add_lazy_loading'), 10, 3);
            add_filter('wp_calculate_image_srcset', array($this, 'lazy_load_srcset'), 10, 5);
        }
        
        // Lazy load iframes
        if (get_option('perfutturu_lazy_load_iframes', 1)) {
            add_filter('wp_video_shortcode', array($this, 'lazy_load_video'), 10, 2);
            add_filter('pre_oembed_result', array($this, 'lazy_load_embeds'), 10, 3);
        }
        
        // Add responsive image attributes
        add_filter('wp_calculate_image_attributes', array($this, 'add_responsive_attributes'), 10, 4);
    }
    
    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($attr, $attachment, $size) {
        // Skip admin
        if (is_admin()) {
            return $attr;
        }
        
        // Skip first few images (above the fold)
        static $image_count = 0;
        $image_count++;
        
        // Don't lazy load LCP candidate
        if ($image_count <= 2 && is_singular()) {
            $attr['fetchpriority'] = 'high';
            return $attr;
        }
        
        // Add lazy loading
        $attr['loading'] = 'lazy';
        
        // Add placeholder for LQIP (Low Quality Image Placeholder)
        if (empty($attr['data-lqip'])) {
            $attr['data-lqip'] = 'true';
        }
        
        return $attr;
    }
    
    /**
     * Handle srcset with lazy loading
     */
    public function lazy_load_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
        // Keep srcset intact but ensure proper loading
        return $sources;
    }
    
    /**
     * Lazy load video embeds
     */
    public function lazy_load_video($output, $atts, $video) {
        // Add loading="lazy" to video elements
        $output = str_replace('<video', '<video preload="metadata"', $output);
        return $output;
    }
    
    /**
     * Lazy load embeds (YouTube, Vimeo, etc.)
     */
    public function lazy_load_embeds($result, $url, $args) {
        if (!$result) {
            return $result;
        }
        
        // Add loading="lazy" to iframe embeds
        if (strpos($result, '<iframe') !== false) {
            $result = str_replace('<iframe', '<iframe loading="lazy"', $result);
        }
        
        return $result;
    }
    
    /**
     * Add responsive image attributes
     */
    public function add_responsive_attributes($attr, $size, $image_src, $image_meta) {
        // Ensure width and height are present
        if (!empty($image_meta['width']) && !empty($image_meta['height'])) {
            $attr['width'] = $image_meta['width'];
            $attr['height'] = $image_meta['height'];
        }
        
        return $attr;
    }
    
    /**
     * Preload critical images
     */
    public function preload_critical_images() {
        $critical_images = get_option('perfutturu_preload_critical_images', '');
        
        if (empty($critical_images)) {
            return;
        }
        
        $images = array_map('trim', explode("\n", $critical_images));
        
        foreach ($images as $image_url) {
            if (!empty($image_url)) {
                echo '<link rel="preload" as="image" href="' . esc_url($image_url) . '">' . "\n";
            }
        }
    }
    
    /**
     * Convert images to WebP (if supported)
     */
    public function convert_to_webp($file, $filename) {
        // Check if WebP is supported
        if (!function_exists('imagewebp')) {
            return $file;
        }
        
        $upload_dir = wp_upload_dir();
        $relative_path = str_replace($upload_dir['basedir'], '', dirname($filename));
        $webp_filename = pathinfo($filename, PATHINFO_FILENAME) . '.webp';
        $webp_path = $upload_dir['basedir'] . $relative_path . '/' . $webp_filename;
        
        // Get image info
        $image_info = getimagesize($filename);
        
        if ($image_info === false) {
            return $file;
        }
        
        // Load image based on type
        switch ($image_info[2]) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($filename);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($filename);
                break;
            default:
                return $file;
        }
        
        // Convert to WebP
        imagewebp($image, $webp_path, 80);
        imagedestroy($image);
        
        return $webp_path;
    }
}
