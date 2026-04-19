<?php
/**
 * Perfutturu Settings View
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

?>

<div class="wrap perfutturu-admin">
    <h1><?php _e('Configurações - Perfutturu', 'perfutturu'); ?></h1>
    
    <form method="post" action="options.php" class="perfutturu-settings-form">
        <?php settings_fields('perfutturu_group'); ?>
        <?php do_settings_sections('perfutturu_group'); ?>
        
        <!-- General Settings -->
        <div class="perfutturu-card">
            <h2><?php _e('Configurações Gerais', 'perfutturu'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Ativar Plugin', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_enabled" value="1" 
                                   <?php checked(get_option('perfutturu_enabled', 1), 1); ?>>
                            <?php _e('Habilitar otimizações do Perfutturu', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Desmarque para desativar temporariamente todas as otimizações.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Modo de Teste', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_test_mode" value="1" 
                                   <?php checked(get_option('perfutturu_test_mode', 0), 1); ?>>
                            <?php _e('Ativar modo de teste', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('No modo de teste, as otimizações não são aplicadas para usuários logados.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CSS Optimization -->
        <div class="perfutturu-card">
            <h2><?php _e('Otimização de CSS', 'perfutturu'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Minificar CSS', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_css_minify" value="1" 
                                   <?php checked(get_option('perfutturu_css_minify', 1), 1); ?>>
                            <?php _e('Habilitar minificação de CSS', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Remove espaços em branco e comentários dos arquivos CSS para reduzir o tamanho.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- JavaScript Optimization -->
        <div class="perfutturu-card">
            <h2><?php _e('Otimização de JavaScript', 'perfutturu'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Minificar JavaScript', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_js_minify" value="1" 
                                   <?php checked(get_option('perfutturu_js_minify', 1), 1); ?>>
                            <?php _e('Habilitar minificação de JavaScript', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Remove espaços em branco e comentários dos arquivos JS para reduzir o tamanho.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- HTML Optimization -->
        <div class="perfutturu-card">
            <h2><?php _e('Otimização de HTML', 'perfutturu'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Minificar HTML', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_html_minify" value="1" 
                                   <?php checked(get_option('perfutturu_html_minify', 1), 1); ?>>
                            <?php _e('Habilitar minificação de HTML', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Remove espaços em branco e comentários do HTML final.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Image Optimization -->
        <div class="perfutturu-card">
            <h2><?php _e('Otimização de Imagens', 'perfutturu'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Lazy Loading de Imagens', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_lazy_load_images" value="1" 
                                   <?php checked(get_option('perfutturu_lazy_load_images', 1), 1); ?>>
                            <?php _e('Habilitar lazy loading para imagens', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Carrega imagens apenas quando elas entram na viewport. Melhora o LCP.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Lazy Loading de Iframes', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_lazy_load_iframes" value="1" 
                                   <?php checked(get_option('perfutturu_lazy_load_iframes', 1), 1); ?>>
                            <?php _e('Habilitar lazy loading para iframes (YouTube, Vimeo, etc.)', 'perfutturu'); ?>
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Pré-carregar Imagens Críticas', 'perfutturu'); ?></th>
                    <td>
                        <textarea name="perfutturu_preload_critical_images" rows="4" class="large-text" 
                                  placeholder="<?php _e('https://example.com/image.jpg (uma URL por linha)', 'perfutturu'); ?>"><?php echo esc_textarea(get_option('perfutturu_preload_critical_images', '')); ?></textarea>
                        <p class="description">
                            <?php _e('URLs de imagens para pré-carregar (ex: hero image). Uma URL por linha.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Font Optimization -->
        <div class="perfutturu-card">
            <h2><?php _e('Otimização de Fontes', 'perfutturu'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Font Display Swap', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_font_display_swap" value="1" 
                                   <?php checked(get_option('perfutturu_font_display_swap', 1), 1); ?>>
                            <?php _e('Adicionar font-display: swap às fontes', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Evita FOIT (Flash of Invisible Text) mostrando texto com fonte fallback enquanto a fonte web carrega.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Hospedar Google Fonts Localmente', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_host_fonts_locally" value="1" 
                                   <?php checked(get_option('perfutturu_host_fonts_locally', 0), 1); ?>>
                            <?php _e('Baixar e servir Google Fonts localmente', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Reduz requisições externas e melhora a privacidade (GDPR).', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Cleanup -->
        <div class="perfutturu-card">
            <h2><?php _e('Limpeza de Código', 'perfutturu'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Remover Emojis', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_remove_emojis" value="1" 
                                   <?php checked(get_option('perfutturu_remove_emojis', 1), 1); ?>>
                            <?php _e('Remover scripts e estilos de emojis do WordPress', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Reduz requisições HTTP e melhora o FCP.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Remover Embeds', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_remove_embeds" value="1" 
                                   <?php checked(get_option('perfutturu_remove_embeds', 1), 1); ?>>
                            <?php _e('Remover funcionalidade de embeds do WordPress', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Remove scripts de embed se você não usa a funcionalidade de incorporar conteúdo externo.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Remover Dashicons', 'perfutturu'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="perfutturu_remove_dashicons" value="1" 
                                   <?php checked(get_option('perfutturu_remove_dashicons', 0), 1); ?>>
                            <?php _e('Remover Dashicons para visitantes (não-logados)', 'perfutturu'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Dashicons é usado no admin. Pode ser seguro remover para visitantes se seu tema não usar.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Resource Hints -->
        <div class="perfutturu-card">
            <h2><?php _e('Dicas de Recursos (Resource Hints)', 'perfutturu'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('DNS Prefetch', 'perfutturu'); ?></th>
                    <td>
                        <textarea name="perfutturu_dns_prefetch" rows="4" class="large-text" 
                                  placeholder="<?php _e('https://example.com (uma URL por linha)', 'perfutturu'); ?>"><?php echo esc_textarea(get_option('perfutturu_dns_prefetch', '')); ?></textarea>
                        <p class="description">
                            <?php _e('Domínios para DNS prefetch. Uma URL por linha.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Preconnect', 'perfutturu'); ?></th>
                    <td>
                        <textarea name="perfutturu_preconnect" rows="4" class="large-text" 
                                  placeholder="<?php _e('https://cdn.example.com (uma URL por linha)', 'perfutturu'); ?>"><?php echo esc_textarea(get_option('perfutturu_preconnect', '')); ?></textarea>
                        <p class="description">
                            <?php _e('Domínios para preconnect (estabelece conexão antecipada). Uma URL por linha.', 'perfutturu'); ?>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        
        <?php submit_button(__('Salvar Configurações', 'perfutturu')); ?>
    </form>
</div>
