<?php
/**
 * Popup Template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get options with defaults
$defaults = array(
    'title' => 'OFERTA ESPECIAL',
    'content' => 'Crie seu Site Institucional Profissional por um preço imperdível! Apenas hoje ou enquanto durarem as vagas.',
    'cta_text' => 'Acesse agora o Hotsite!',
    'cta_link_type' => 'internal',
    'cta_page_id' => 0,
    'cta_custom_url' => '',
    'show_decline_button' => 1,
    'decline_text' => 'Não, obrigado',
    'width' => 'medium',
    'max_height' => '',
    'bg_color' => '#ffffff',
    'text_color' => '#333333',
    'cta_bg_color' => '#0073aa',
    'cta_text_color' => '#ffffff',
    'close_btn_color' => '#666666',
    'font_family' => 'inherit',
    'font_size' => '16',
    'font_weight' => '400',
    'enable_blur' => 0,
    'blur_intensity' => '5',
    'enable_animation' => 1,
    'animation_type' => 'fade-in'
);

$options = wp_parse_args($options, $defaults);

// Calculate CTA URL based on link type
$cta_url = '';
if ($options['cta_link_type'] === 'internal' && !empty($options['cta_page_id'])) {
    $cta_url = get_permalink($options['cta_page_id']);
} elseif ($options['cta_link_type'] === 'external' && !empty($options['cta_custom_url'])) {
    $cta_url = esc_url_raw($options['cta_custom_url']);
}

// Calculate width
$width_value = '500px';
switch ($options['width']) {
    case 'small':
        $width_value = '400px';
        break;
    case 'medium':
        $width_value = '500px';
        break;
    case 'large':
        $width_value = '600px';
        break;
    case 'custom':
        $width_value = !empty($options['max_height']) ? $options['max_height'] : '500px';
        break;
}

// Animation class
$animation_class = '';
if ($options['enable_animation']) {
    switch ($options['animation_type']) {
        case 'fade-in':
            $animation_class = 'futturu-popup-animate-fade-in';
            break;
        case 'slide-up':
            $animation_class = 'futturu-popup-animate-slide-up';
            break;
        case 'slide-down':
            $animation_class = 'futturu-popup-animate-slide-down';
            break;
        case 'zoom-in':
            $animation_class = 'futturu-popup-animate-zoom-in';
            break;
    }
}

// Blur class
$blur_class = $options['enable_blur'] ? 'futturu-popup-blur-enabled' : '';
?>

<div id="futturu-popup-overlay" class="futturu-popup-overlay <?php echo esc_attr($blur_class); ?>" style="display: none;">
    <div class="futturu-popup-container <?php echo esc_attr($animation_class); ?>" 
         role="dialog" 
         aria-modal="true" 
         aria-labelledby="futturu-popup-title"
         style="max-width: <?php echo esc_attr($width_value); ?>; <?php echo !empty($options['max_height']) ? 'max-height: ' . esc_attr($options['max_height']) . ';' : ''; ?>">
        
        <!-- Close Button -->
        <button class="futturu-popup-close" aria-label="<?php esc_attr_e('Fechar', 'futturu-popup-cta'); ?>" type="button">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18M6 6L18 18" stroke="<?php echo esc_attr($options['close_btn_color']); ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        
        <!-- Content -->
        <div class="futturu-popup-content">
            <?php if (!empty($options['title'])) : ?>
                <h2 id="futturu-popup-title" class="futturu-popup-title" style="color: <?php echo esc_attr($options['text_color']); ?>; font-family: <?php echo esc_attr($options['font_family']); ?>; font-size: <?php echo esc_attr($options['font_size']); ?>px; font-weight: <?php echo esc_attr($options['font_weight']); ?>;">
                    <?php echo esc_html($options['title']); ?>
                </h2>
            <?php endif; ?>
            
            <?php if (!empty($options['content'])) : ?>
                <div class="futturu-popup-text" style="color: <?php echo esc_attr($options['text_color']); ?>; font-family: <?php echo esc_attr($options['font_family']); ?>; font-size: <?php echo esc_attr($options['font_size']); ?>px; font-weight: <?php echo esc_attr($options['font_weight']); ?>;">
                    <?php echo wp_kses_post($options['content']); ?>
                </div>
            <?php endif; ?>
            
            <!-- CTA Button -->
            <?php if (!empty($options['cta_text']) && !empty($cta_url)) : ?>
                <a href="<?php echo esc_url($cta_url); ?>" class="futturu-popup-cta-button" style="background-color: <?php echo esc_attr($options['cta_bg_color']); ?>; color: <?php echo esc_attr($options['cta_text_color']); ?>; font-family: <?php echo esc_attr($options['font_family']); ?>; font-size: <?php echo esc_attr($options['font_size']); ?>px; font-weight: <?php echo esc_attr($options['font_weight']); ?>;">
                    <?php echo esc_html($options['cta_text']); ?>
                </a>
            <?php endif; ?>
            
            <!-- Decline Button -->
            <?php if ($options['show_decline_button'] && !empty($options['decline_text'])) : ?>
                <button class="futturu-popup-decline-button" type="button" style="color: <?php echo esc_attr($options['text_color']); ?>; font-family: <?php echo esc_attr($options['font_family']); ?>; font-size: <?php echo esc_attr($options['font_size']); ?>px; font-weight: <?php echo esc_attr($options['font_weight']); ?>;">
                    <?php echo esc_html($options['decline_text']); ?>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Store popup config for JS to use
    window.futturuPopupStyles = {
        bgColor: '<?php echo esc_js($options['bg_color']); ?>',
        textColor: '<?php echo esc_js($options['text_color']); ?>',
        ctaBgColor: '<?php echo esc_js($options['cta_bg_color']); ?>',
        ctaTextColor: '<?php echo esc_js($options['cta_text_color']); ?>',
        closeBtnColor: '<?php echo esc_js($options['close_btn_color']); ?>',
        fontFamily: '<?php echo esc_js($options['font_family']); ?>',
        fontSize: '<?php echo esc_js($options['font_size']); ?>',
        fontWeight: '<?php echo esc_js($options['font_weight']); ?>',
        enableBlur: <?php echo $options['enable_blur'] ? 'true' : 'false'; ?>,
        blurIntensity: '<?php echo esc_js($options['blur_intensity']); ?>'
    };
});
</script>
