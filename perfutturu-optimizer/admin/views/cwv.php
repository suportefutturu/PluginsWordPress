<?php
/**
 * Perfutturu Core Web Vitals View
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

?>

<div class="wrap perfutturu-admin">
    <h1><?php _e('Core Web Vitals - Perfutturu', 'perfutturu'); ?></h1>
    
    <div class="perfutturu-cwv-intro">
        <p>
            <?php _e('Os Core Web Vitals são métricas essenciais de performance que o Google usa para avaliar a experiência do usuário em seu site.', 'perfutturu'); ?>
        </p>
        <p>
            <?php _e('O Perfutturu ajuda a otimizar seu site para alcançar melhores pontuações nestas métricas.', 'perfutturu'); ?>
        </p>
    </div>
    
    <!-- LCP Section -->
    <div class="perfutturu-card perfutturu-cwv-section">
        <h2>LCP - Largest Contentful Paint</h2>
        
        <div class="perfutturu-metric-info">
            <div class="perfutturu-metric-target">
                <span class="perfutturu-metric-value"><?php _e('< 2.5s', 'perfutturu'); ?></span>
                <span class="perfutturu-metric-label"><?php _e('Bom', 'perfutturu'); ?></span>
            </div>
            
            <div class="perfutturu-metric-target warning">
                <span class="perfutturu-metric-value"><?php _e('2.5s - 4.0s', 'perfutturu'); ?></span>
                <span class="perfutturu-metric-label"><?php _e('Precisa Melhorar', 'perfutturu'); ?></span>
            </div>
            
            <div class="perfutturu-metric-target bad">
                <span class="perfutturu-metric-value"><?php _e('> 4.0s', 'perfutturu'); ?></span>
                <span class="perfutturu-metric-label"><?php _e('Ruim', 'perfutturu'); ?></span>
            </div>
        </div>
        
        <h3><?php _e('O que é LCP?', 'perfutturu'); ?></h3>
        <p>
            <?php _e('LCP mede o tempo necessário para carregar o maior elemento visível na viewport. Este elemento geralmente é uma imagem, vídeo ou bloco de texto grande.', 'perfutturu'); ?>
        </p>
        
        <h3><?php _e('Como o Perfutturu ajuda:', 'perfutturu'); ?></h3>
        <ul class="perfutturu-optimizations-explained">
            <li>
                <strong><?php _e('Pré-carregamento de Imagens Críticas:', 'perfutturu'); ?></strong>
                <?php _e('Permite definir URLs de imagens importantes (como hero images) para pré-carregamento.', 'perfutturu'); ?>
            </li>
            <li>
                <strong><?php _e('Lazy Loading Inteligente:', 'perfutturu'); ?></strong>
                <?php _e('Não aplica lazy loading nas primeiras imagens para evitar atrasos no LCP.', 'perfutturu'); ?>
            </li>
            <li>
                <strong><?php _e('Minificação de CSS/JS:', 'perfutturu'); ?></strong>
                <?php _e('Reduz o tamanho dos arquivos para acelerar o carregamento geral da página.', 'perfutturu'); ?>
            </li>
            <li>
                <strong><?php _e('Remoção de Scripts Desnecessários:', 'perfutturu'); ?></strong>
                <?php _e('Script Manager permite desabilitar scripts que não são usados em páginas específicas.', 'perfutturu'); ?>
            </li>
        </ul>
        
        <h3><?php _e('Dicas Adicionais:', 'perfutturu'); ?></h3>
        <ul>
            <li><?php _e('Use imagens otimizadas (WebP, AVIF)', 'perfutturu'); ?></li>
            <li><?php _e('Implemente CDN para entrega de imagens', 'perfutturu'); ?></li>
            <li><?php _e('Considere usar srcset para imagens responsivas', 'perfutturu'); ?></li>
            <li><?php _e('Minimize o CSS crítico acima da dobra', 'perfutturu'); ?></li>
        </ul>
    </div>
    
    <!-- FID/INP Section -->
    <div class="perfutturu-card perfutturu-cwv-section">
        <h2>FID / INP - First Input Delay / Interaction to Next Paint</h2>
        
        <div class="perfutturu-metric-info">
            <div class="perfutturu-metric-target">
                <span class="perfutturu-metric-value"><?php _e('< 100ms', 'perfutturu'); ?></span>
                <span class="perfutturu-metric-label"><?php _e('Bom', 'perfutturu'); ?></span>
            </div>
            
            <div class="perfutturu-metric-target warning">
                <span class="perfutturu-metric-value"><?php _e('100ms - 300ms', 'perfutturu'); ?></span>
                <span class="perfutturu-metric-label"><?php _e('Precisa Melhorar', 'perfutturu'); ?></span>
            </div>
            
            <div class="perfutturu-metric-target bad">
                <span class="perfutturu-metric-value"><?php _e('> 300ms', 'perfutturu'); ?></span>
                <span class="perfutturu-metric-label"><?php _e('Ruim', 'perfutturu'); ?></span>
            </div>
        </div>
        
        <h3><?php _e('O que é FID/INP?', 'perfutturu'); ?></h3>
        <p>
            <?php _e('FID mede o tempo entre a primeira interação do usuário e a resposta do navegador. INP (que substituirá o FID) mede a latência de todas as interações durante a vida da página.', 'perfutturu'); ?>
        </p>
        
        <h3><?php _e('Como o Perfutturu ajuda:', 'perfutturu'); ?></h3>
        <ul class="perfutturu-optimizations-explained">
            <li>
                <strong><?php _e('Defer de JavaScript:', 'perfutturu'); ?></strong>
                <?php _e('Adia a execução de JS não-crítico para liberar a thread principal.', 'perfutturu'); ?>
            </li>
            <li>
                <strong><?php _e('Async de Scripts:', 'perfutturu'); ?></strong>
                <?php _e('Carrega scripts assincronamente para não bloquear a renderização.', 'perfutturu'); ?>
            </li>
            <li>
                <strong><?php _e('Remoção de jQuery Migrate:', 'perfutturu'); ?></strong>
                <?php _e('Remove código legado desnecessário que adiciona peso ao carregamento.', 'perfutturu'); ?>
            </li>
            <li>
                <strong><?php _e('Minificação de JS:', 'perfutturu'); ?></strong>
                <?php _e('Reduz o tamanho dos arquivos JavaScript para parsing mais rápido.', 'perfutturu'); ?>
            </li>
        </ul>
        
        <h3><?php _e('Dicas Adicionais:', 'perfutturu'); ?></h3>
        <ul>
            <li><?php _e('Quebre tarefas longas de JavaScript', 'perfutturu'); ?></li>
            <li><?php _e('Use Web Workers para processamento pesado', 'perfutturu'); ?></li>
            <li><?php _e('Minimize o trabalho da thread principal', 'perfutturu'); ?></li>
            <li><?php _e('Evite layouts complexos que exigem muito cálculo', 'perfutturu'); ?></li>
        </ul>
    </div>
    
    <!-- CLS Section -->
    <div class="perfutturu-card perfutturu-cwv-section">
        <h2>CLS - Cumulative Layout Shift</h2>
        
        <div class="perfutturu-metric-info">
            <div class="perfutturu-metric-target">
                <span class="perfutturu-metric-value"><?php _e('< 0.1', 'perfutturu'); ?></span>
                <span class="perfutturu-metric-label"><?php _e('Bom', 'perfutturu'); ?></span>
            </div>
            
            <div class="perfutturu-metric-target warning">
                <span class="perfutturu-metric-value"><?php _e('0.1 - 0.25', 'perfutturu'); ?></span>
                <span class="perfutturu-metric-label"><?php _e('Precisa Melhorar', 'perfutturu'); ?></span>
            </div>
            
            <div class="perfutturu-metric-target bad">
                <span class="perfutturu-metric-value"><?php _e('> 0.25', 'perfutturu'); ?></span>
                <span class="perfutturu-metric-label"><?php _e('Ruim', 'perfutturu'); ?></span>
            </div>
        </div>
        
        <h3><?php _e('O que é CLS?', 'perfutturu'); ?></h3>
        <p>
            <?php _e('CLS mede a estabilidade visual da página. Quantifica quanto os elementos se movem durante o carregamento.', 'perfutturu'); ?>
        </p>
        
        <h3><?php _e('Como o Perfutturu ajuda:', 'perfutturu'); ?></h3>
        <ul class="perfutturu-optimizations-explained">
            <li>
                <strong><?php _e('Dimensões de Imagem:', 'perfutturu'); ?></strong>
                <?php _e('Garante que todas as imagens tenham width e height definidos para reservar espaço.', 'perfutturu'); ?>
            </li>
            <li>
                <strong><?php _e('Font Display Swap:', 'perfutturu'); ?></strong>
                <?php _e('Aplica font-display: swap para evitar FOIT e reduzir shifts de texto.', 'perfutturu'); ?>
            </li>
            <li>
                <strong><?php _e('Lazy Loading Adequado:', 'perfutturu'); ?></strong>
                <?php _e('Reserva espaço para imagens com lazy loading usando atributos adequados.', 'perfutturu'); ?>
            </li>
        </ul>
        
        <h3><?php _e('Dicas Adicionais:', 'perfutturu'); ?></h3>
        <ul>
            <li><?php _e('Sempre inclua width e height em imagens e vídeos', 'perfutturu'); ?></li>
            <li><?php _e('Reserve espaço para anúncios e embeds', 'perfutturu'); ?></li>
            <li><?php _e('Evite inserir conteúdo dinâmico acima de conteúdo existente', 'perfutturu'); ?></li>
            <li><?php _e('Use aspect-ratio CSS para manter proporções', 'perfutturu'); ?></li>
            <li><?php _e('Carregue fontes com fallback adequado', 'perfutturu'); ?></li>
        </ul>
    </div>
    
    <!-- Tools Section -->
    <div class="perfutturu-card perfutturu-cwv-section">
        <h2><?php _e('Ferramentas de Medição', 'perfutturu'); ?></h2>
        
        <div class="perfutturu-tools-grid">
            <div class="perfutturu-tool">
                <h3>PageSpeed Insights</h3>
                <p><?php _e('Ferramenta oficial do Google para medir Core Web Vitals.', 'perfutturu'); ?></p>
                <a href="https://pagespeed.web.dev/" target="_blank" class="button button-primary">
                    <?php _e('Acessar', 'perfutturu'); ?>
                </a>
            </div>
            
            <div class="perfutturu-tool">
                <h3>GTmetrix</h3>
                <p><?php _e('Análise detalhada de performance com recomendações.', 'perfutturu'); ?></p>
                <a href="https://gtmetrix.com/" target="_blank" class="button button-primary">
                    <?php _e('Acessar', 'perfutturu'); ?>
                </a>
            </div>
            
            <div class="perfutturu-tool">
                <h3>Chrome DevTools</h3>
                <p><?php _e('Ferramentas de desenvolvedor do Chrome para análise local.', 'perfutturu'); ?></p>
                <a href="https://developer.chrome.com/docs/devtools/" target="_blank" class="button button-primary">
                    <?php _e('Acessar', 'perfutturu'); ?>
                </a>
            </div>
            
            <div class="perfutturu-tool">
                <h3>WebPageTest</h3>
                <p><?php _e('Testes de performance de múltiplas localizações.', 'perfutturu'); ?></p>
                <a href="https://www.webpagetest.org/" target="_blank" class="button button-primary">
                    <?php _e('Acessar', 'perfutturu'); ?>
                </a>
            </div>
        </div>
    </div>
    
    <!-- References -->
    <div class="perfutturu-card perfutturu-references">
        <h2><?php _e('Referências e Documentação', 'perfutturu'); ?></h2>
        
        <ul>
            <li>
                <a href="https://web.dev/vitals/" target="_blank">
                    <?php _e('Google Web Vitals Documentation', 'perfutturu'); ?>
                </a>
            </li>
            <li>
                <a href="https://developers.google.com/speed/docs/insights/v5/about" target="_blank">
                    <?php _e('PageSpeed Insights API', 'perfutturu'); ?>
                </a>
            </li>
            <li>
                <a href="https://wordpress.org/support/article/optimization/" target="_blank">
                    <?php _e('WordPress Performance Optimization', 'perfutturu'); ?>
                </a>
            </li>
            <li>
                <a href="https://futuru.com.br/" target="_blank">
                    <?php _e('Futturu - Hospedagem Gerenciada', 'perfutturu'); ?>
                </a>
            </li>
        </ul>
    </div>
</div>
