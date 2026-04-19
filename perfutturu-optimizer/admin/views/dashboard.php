<?php
/**
 * Perfutturu Admin Dashboard View
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check user capability
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

// Get plugin status
$enabled = get_option('perfutturu_enabled', 1);
$test_mode = get_option('perfutturu_test_mode', 0);

// Get cache size
$cache_size = '0 KB';
if (class_exists('Perfutturu_Cache')) {
    $cache_instance = Perfutturu_Cache::get_instance();
    $cache_size = $cache_instance->get_cache_size();
}

// Check for cache plugin
$cache_plugin = false;
if (class_exists('Perfutturu_Cache')) {
    $cache_instance = Perfutturu_Cache::get_instance();
    $cache_plugin = $cache_instance->check_cache_plugin();
}

?>

<div class="wrap perfutturu-admin">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="perfutturu-dashboard">
        <!-- Status Card -->
        <div class="perfutturu-card perfutturu-status-card">
            <h2><?php _e('Status do Plugin', 'perfutturu'); ?></h2>
            
            <div class="perfutturu-status-row">
                <span class="perfutturu-label"><?php _e('Plugin:', 'perfutturu'); ?></span>
                <span class="perfutturu-value <?php echo $enabled ? 'status-active' : 'status-inactive'; ?>">
                    <?php echo $enabled ? __('Ativo', 'perfutturu') : __('Inativo', 'perfutturu'); ?>
                </span>
            </div>
            
            <div class="perfutturu-status-row">
                <span class="perfutturu-label"><?php _e('Modo de Teste:', 'perfutturu'); ?></span>
                <span class="perfutturu-value <?php echo $test_mode ? 'status-warning' : 'status-normal'; ?>">
                    <?php echo $test_mode ? __('Ativado', 'perfutturu') : __('Desativado', 'perfutturu'); ?>
                </span>
            </div>
            
            <div class="perfutturu-status-row">
                <span class="perfutturu-label"><?php _e('Cache Size:', 'perfutturu'); ?></span>
                <span class="perfutturu-value"><?php echo esc_html($cache_size); ?></span>
            </div>
            
            <?php if ($cache_plugin): ?>
            <div class="perfutturu-status-row">
                <span class="perfutturu-label"><?php _e('Plugin de Cache Detectado:', 'perfutturu'); ?></span>
                <span class="perfutturu-value status-info"><?php echo esc_html($cache_plugin); ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="perfutturu-card perfutturu-actions-card">
            <h2><?php _e('Ações Rápidas', 'perfutturu'); ?></h2>
            
            <div class="perfutturu-quick-actions">
                <button type="button" class="button button-primary" id="perfutturu-clear-cache">
                    <?php _e('Limpar Cache', 'perfutturu'); ?>
                </button>
                
                <button type="button" class="button button-secondary" id="perfutturu-toggle-test-mode">
                    <?php echo $test_mode ? __('Desativar Modo de Teste', 'perfutturu') : __('Ativar Modo de Teste', 'perfutturu'); ?>
                </button>
                
                <a href="<?php echo admin_url('admin.php?page=perfutturu-scripts'); ?>" class="button button-secondary">
                    <?php _e('Gerenciar Scripts', 'perfutturu'); ?>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=perfutturu-settings'); ?>" class="button button-secondary">
                    <?php _e('Configurações', 'perfutturu'); ?>
                </a>
            </div>
        </div>
        
        <!-- Performance Tips Card -->
        <div class="perfutturu-card perfutturu-tips-card">
            <h2><?php _e('Dicas de Performance', 'perfutturu'); ?></h2>
            
            <ul class="perfutturu-tips-list">
                <li>
                    <strong><?php _e('Script Manager:', 'perfutturu'); ?></strong>
                    <?php _e('Desabilite scripts não utilizados em páginas específicas para reduzir o peso do carregamento.', 'perfutturu'); ?>
                </li>
                <li>
                    <strong><?php _e('Lazy Loading:', 'perfutturu'); ?></strong>
                    <?php _e('Mantenha o lazy loading ativado para imagens e iframes para melhorar o LCP.', 'perfutturu'); ?>
                </li>
                <li>
                    <strong><?php _e('Fontes Locais:', 'perfutturu'); ?></strong>
                    <?php _e('Hospede fontes localmente para reduzir requisições externas e melhorar a privacidade.', 'perfutturu'); ?>
                </li>
                <li>
                    <strong><?php _e('Minificação:', 'perfutturu'); ?></strong>
                    <?php _e('Use minificação de CSS, JS e HTML para reduzir o tamanho dos arquivos.', 'perfutturu'); ?>
                </li>
                <li>
                    <strong><?php _e('Core Web Vitals:', 'perfutturu'); ?></strong>
                    <?php _e('Monitore LCP, FID e CLS regularmente usando o PageSpeed Insights ou GTmetrix.', 'perfutturu'); ?>
                </li>
            </ul>
        </div>
        
        <!-- Core Web Vitals Info Card -->
        <div class="perfutturu-card perfutturu-cwv-card">
            <h2><?php _e('Core Web Vitals', 'perfutturu'); ?></h2>
            
            <div class="perfutturu-cwv-metrics">
                <div class="perfutturu-cwv-metric">
                    <h3>LCP</h3>
                    <p><?php _e('Largest Contentful Paint', 'perfutturu'); ?></p>
                    <p class="perfutturu-cwv-target"><?php _e('Alvo: < 2.5s', 'perfutturu'); ?></p>
                    <p class="perfutturu-cwv-desc"><?php _e('Mede o tempo de carregamento do maior elemento visível.', 'perfutturu'); ?></p>
                </div>
                
                <div class="perfutturu-cwv-metric">
                    <h3>FID/INP</h3>
                    <p><?php _e('First Input Delay / Interaction to Next Paint', 'perfutturu'); ?></p>
                    <p class="perfutturu-cwv-target"><?php _e('Alvo: < 100ms', 'perfutturu'); ?></p>
                    <p class="perfutturu-cwv-desc"><?php _e('Mede a responsividade à interações do usuário.', 'perfutturu'); ?></p>
                </div>
                
                <div class="perfutturu-cwv-metric">
                    <h3>CLS</h3>
                    <p><?php _e('Cumulative Layout Shift', 'perfutturu'); ?></p>
                    <p class="perfutturu-cwv-target"><?php _e('Alvo: < 0.1', 'perfutturu'); ?></p>
                    <p class="perfutturu-cwv-desc"><?php _e('Mede a estabilidade visual durante o carregamento.', 'perfutturu'); ?></p>
                </div>
            </div>
            
            <a href="<?php echo admin_url('admin.php?page=perfutturu-cwv'); ?>" class="button button-primary">
                <?php _e('Ver Detalhes dos Core Web Vitals', 'perfutturu'); ?>
            </a>
        </div>
        
        <!-- Optimization Summary Card -->
        <div class="perfutturu-card perfutturu-summary-card">
            <h2><?php _e('Otimizações Ativas', 'perfutturu'); ?></h2>
            
            <ul class="perfutturu-optimizations-list">
                <?php if (get_option('perfutturu_css_minify', 1)): ?>
                <li class="optimization-active">
                    <span class="dashicons dashicons-yes"></span>
                    <?php _e('Minificação de CSS', 'perfutturu'); ?>
                </li>
                <?php endif; ?>
                
                <?php if (get_option('perfutturu_js_minify', 1)): ?>
                <li class="optimization-active">
                    <span class="dashicons dashicons-yes"></span>
                    <?php _e('Minificação de JavaScript', 'perfutturu'); ?>
                </li>
                <?php endif; ?>
                
                <?php if (get_option('perfutturu_html_minify', 1)): ?>
                <li class="optimization-active">
                    <span class="dashicons dashicons-yes"></span>
                    <?php _e('Minificação de HTML', 'perfutturu'); ?>
                </li>
                <?php endif; ?>
                
                <?php if (get_option('perfutturu_lazy_load_images', 1)): ?>
                <li class="optimization-active">
                    <span class="dashicons dashicons-yes"></span>
                    <?php _e('Lazy Loading de Imagens', 'perfutturu'); ?>
                </li>
                <?php endif; ?>
                
                <?php if (get_option('perfutturu_remove_emojis', 1)): ?>
                <li class="optimization-active">
                    <span class="dashicons dashicons-yes"></span>
                    <?php _e('Remoção de Emojis', 'perfutturu'); ?>
                </li>
                <?php endif; ?>
                
                <?php if (get_option('perfutturu_remove_embeds', 1)): ?>
                <li class="optimization-active">
                    <span class="dashicons dashicons-yes"></span>
                    <?php _e('Remoção de Embeds', 'perfutturu'); ?>
                </li>
                <?php endif; ?>
                
                <?php if (get_option('perfutturu_font_display_swap', 1)): ?>
                <li class="optimization-active">
                    <span class="dashicons dashicons-yes"></span>
                    <?php _e('Font Display Swap', 'perfutturu'); ?>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <!-- Footer Info -->
    <div class="perfutturu-footer">
        <p>
            <strong>Perfutturu v<?php echo PERFUTTURU_VERSION; ?></strong> - 
            <?php _e('Otimizador de Performance Profundo para WordPress', 'perfutturu'); ?>
        </p>
        <p>
            <?php _e('Desenvolvido com foco em Core Web Vitals e compatibilidade com hospedagens gerenciadas Cloudez/Futturu.', 'perfutturu'); ?>
        </p>
    </div>
</div>
