<?php
/**
 * Frontend Renderer - Simulador Cloud Futturu
 * Renderiza o simulador com foco em conversão
 */

if (!defined('ABSPATH')) {
    exit;
}

// Helper function to get plan by ID
function futturu_get_plan_by_id($plans, $plan_id) {
    if (empty($plans) || empty($plan_id)) {
        return null;
    }
    foreach ($plans as $plan) {
        if (isset($plan['id']) && $plan['id'] === $plan_id) {
            return $plan;
        }
    }
    return null;
}

// Helper to format price
function futturu_format_price($price) {
    return 'R$ ' . number_format(floatval($price), 2, ',', '.');
}

// Helper to format views
function futturu_format_views($views) {
    if ($views >= 1000000) {
        return number_format($views / 1000000, 1, ',', '.') . 'M';
    } elseif ($views >= 1000) {
        return number_format($views / 1000, 0, ',', '.') . 'K';
    }
    return number_format($views, 0, ',', '.');
}
?>

<div class="futturu-cloud-simulator" id="futturuCloudSimulator">
    
    <!-- Section A: Introduction -->
    <div class="fcs-intro-section">
        <div class="fcs-container">
            <h1 class="fcs-main-title"><?php echo esc_html($texts['intro_title'] ?? 'Comece Pequeno, Cresça com Segurança'); ?></h1>
            <h2 class="fcs-subtitle"><?php echo esc_html($texts['intro_subtitle'] ?? 'Descubra como começar com uma hospedagem poderosa e econômica'); ?></h2>
            <p class="fcs-description"><?php echo esc_html($texts['intro_description'] ?? ''); ?></p>
            
            <div class="fcs-benefits-grid">
                <?php if (!empty($texts['benefits']) && is_array($texts['benefits'])): ?>
                    <?php foreach ($texts['benefits'] as $benefit): ?>
                        <div class="fcs-benefit-item">
                            <span class="fcs-benefit-icon">✓</span>
                            <span><?php echo esc_html($benefit); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="fcs-partnership-badge">
                <span class="fcs-badge-text">Tecnologia e Infraestrutura</span>
                <strong>Cloudez</strong>
            </div>
        </div>
    </div>

    <!-- Section B: Micro-Quiz -->
    <div class="fcs-quiz-section">
        <div class="fcs-container">
            <h3 class="fcs-quiz-title"><?php echo esc_html($texts['quiz_question'] ?? 'Quantas visualizações seu site recebe por mês?'); ?></h3>
            
            <div class="fcs-quiz-options">
                <?php if (!empty($profiles) && is_array($profiles)): ?>
                    <?php foreach ($profiles as $profile): ?>
                        <button class="fcs-quiz-option" 
                                data-profile-id="<?php echo esc_attr($profile['id'] ?? ''); ?>"
                                data-recommended-plan="<?php echo esc_attr($profile['recommended_plan'] ?? ''); ?>"
                                data-min-views="<?php echo esc_attr($profile['min_views'] ?? 0); ?>"
                                data-max-views="<?php echo esc_attr($profile['max_views'] ?? 0); ?>">
                            <span class="fcs-option-name"><?php echo esc_html($profile['name'] ?? ''); ?></span>
                            <span class="fcs-option-views">
                                <?php echo futturu_format_views($profile['min_views'] ?? 0); ?> - <?php echo futturu_format_views($profile['max_views'] ?? 0); ?> visitas/mês
                            </span>
                            <span class="fcs-option-desc"><?php echo esc_html($profile['description'] ?? ''); ?></span>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="fcs-quiz-result" id="fcsQuizResult" style="display:none;">
                <div class="fcs-result-message">
                    <h4>Recomendamos para você:</h4>
                    <p id="fcsRecommendedPlanName"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section C: Plans Table -->
    <div class="fcs-plans-section" id="fcsPlansSection">
        <div class="fcs-container">
            <h3 class="fcs-section-title">Planos Econômicos & Escaláveis</h3>
            <p class="fcs-section-subtitle">Comece com o plano ideal para o seu momento e escale quando precisar</p>
            
            <div class="fcs-plans-grid">
                <?php if (!empty($plans) && is_array($plans)): ?>
                    <?php foreach ($plans as $plan): 
                        $is_featured = isset($plan['featured']) && $plan['featured'];
                        $next_plan = futturu_get_plan_by_id($plans, $plan['next_plan'] ?? '');
                    ?>
                        <div class="fcs-plan-card <?php echo $is_featured ? 'fcs-featured' : ''; ?>" 
                             data-plan-id="<?php echo esc_attr($plan['id'] ?? ''); ?>">
                            
                            <?php if ($is_featured): ?>
                                <div class="fcs-featured-badge">Mais Popular</div>
                            <?php endif; ?>
                            
                            <div class="fcs-plan-header">
                                <h4 class="fcs-plan-name"><?php echo esc_html($plan['name'] ?? ''); ?></h4>
                                <p class="fcs-plan-description"><?php echo esc_html($plan['description'] ?? ''); ?></p>
                            </div>
                            
                            <div class="fcs-plan-price">
                                <span class="fcs-price-value"><?php echo futturu_format_price($plan['price'] ?? 0); ?></span>
                                <span class="fcs-price-period">/mês</span>
                            </div>
                            
                            <div class="fcs-plan-resources">
                                <div class="fcs-resource">
                                    <span class="fcs-resource-value"><?php echo esc_html($plan['ram'] ?? 0); ?> GB</span>
                                    <span class="fcs-resource-label">RAM</span>
                                </div>
                                <div class="fcs-resource">
                                    <span class="fcs-resource-value"><?php echo esc_html($plan['cpu'] ?? 0); ?> vCPU</span>
                                    <span class="fcs-resource-label">Processamento</span>
                                </div>
                                <div class="fcs-resource">
                                    <span class="fcs-resource-value"><?php echo esc_html($plan['disk'] ?? 0); ?> GB</span>
                                    <span class="fcs-resource-label">SSD</span>
                                </div>
                                <div class="fcs-resource">
                                    <span class="fcs-resource-value"><?php echo futturu_format_views($plan['views'] ?? 0); ?></span>
                                    <span class="fcs-resource-label">Visitas/mês</span>
                                </div>
                                <div class="fcs-resource">
                                    <span class="fcs-resource-value"><?php echo esc_html($plan['sites'] ?? '-'); ?></span>
                                    <span class="fcs-resource-label">Sites</span>
                                </div>
                            </div>
                            
                            <?php if ($next_plan): ?>
                                <div class="fcs-upgrade-path">
                                    <span class="fcs-upgrade-label">Cresça para:</span>
                                    <strong><?php echo esc_html($next_plan['name'] ?? ''); ?></strong>
                                    <span class="fcs-upgrade-price">(<?php echo futturu_format_price($next_plan['price'] ?? 0); ?>)</span>
                                </div>
                            <?php endif; ?>
                            
                            <button class="fcs-cta-button fcs-select-plan-btn" 
                                    data-plan-id="<?php echo esc_attr($plan['id'] ?? ''); ?>"
                                    data-plan-name="<?php echo esc_attr($plan['name'] ?? ''); ?>"
                                    data-plan-price="<?php echo esc_attr($plan['price'] ?? 0); ?>">
                                <?php echo esc_html($cta['button_text'] ?? 'Solicitar Cotação'); ?>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Comparison Section -->
    <div class="fcs-comparison-section">
        <div class="fcs-container">
            <h3 class="fcs-section-title"><?php echo esc_html($texts['comparison_title'] ?? 'Por que evitar Hospedagem Compartilhada?'); ?></h3>
            
            <div class="fcs-comparison-grid">
                <div class="fcs-comparison-item fcs-shared-hosting">
                    <h4>Hospedagem Compartilhada</h4>
                    <ul class="fcs-cons-list">
                        <?php if (!empty($texts['comparison_items']) && is_array($texts['comparison_items'])): ?>
                            <?php foreach ($texts['comparison_items'] as $item): ?>
                                <li><span class="fcs-icon-cross">✕</span> <?php echo esc_html($item); ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="fcs-comparison-item fcs-cloud-hosting">
                    <h4>Cloud Futturu + Cloudez</h4>
                    <ul class="fcs-pros-list">
                        <li><span class="fcs-icon-check">✓</span> Recursos dedicados e garantidos</li>
                        <li><span class="fcs-icon-check">✓</span> Performance estável em qualquer cenário</li>
                        <li><span class="fcs-icon-check">✓</span> Segurança avançada e backups automáticos</li>
                        <li><span class="fcs-icon-check">✓</span> Escalabilidade instantânea com 1 clique</li>
                        <li><span class="fcs-icon-check">✓</span> Suporte técnico especializado e humano</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="fcs-timeline-section">
        <div class="fcs-container">
            <h3 class="fcs-section-title"><?php echo esc_html($texts['timeline_title'] ?? 'Seu Caminho de Crescimento'); ?></h3>
            <p class="fcs-section-subtitle"><?php echo esc_html($texts['timeline_description'] ?? ''); ?></p>
            
            <div class="fcs-timeline">
                <?php 
                $timeline_plans = array('br1g', 'br4g', 'br8g');
                $timeline_months = array(0, 6, 12);
                $timeline_labels = array('Início', '6 meses', '1 ano');
                
                foreach ($timeline_plans as $index => $plan_id): 
                    $plan = futturu_get_plan_by_id($plans, $plan_id);
                    if (!$plan) continue;
                ?>
                    <div class="fcs-timeline-item">
                        <div class="fcs-timeline-marker">
                            <span class="fcs-timeline-month"><?php echo esc_html($timeline_labels[$index]); ?></span>
                        </div>
                        <div class="fcs-timeline-content">
                            <h4><?php echo esc_html($plan['name']); ?></h4>
                            <p class="fcs-timeline-price"><?php echo futturu_format_price($plan['price']); ?>/mês</p>
                            <p class="fcs-timeline-views"><?php echo futturu_format_views($plan['views']); ?> visitas/mês</p>
                        </div>
                    </div>
                    
                    <?php if ($index < count($timeline_plans) - 1): ?>
                        <div class="fcs-timeline-arrow">→</div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <div class="fcs-timeline-note">
                <p><strong>Economia inteligente:</strong> Comece com R$ 239/mês e escale apenas quando seu negócio crescer. Evite pagar por recursos que não usa!</p>
            </div>
        </div>
    </div>

    <!-- Section E: CTA Final -->
    <div class="fcs-cta-section">
        <div class="fcs-container">
            <div class="fcs-cta-content">
                <h3><?php echo esc_html($cta['primary_text'] ?? 'Pronto para começar?'); ?></h3>
                <p><?php echo esc_html($cta['primary_subtext'] ?? ''); ?></p>
                <button class="fcs-cta-button fcs-cta-large" id="fcsOpenModalBtn">
                    <?php echo esc_html($cta['button_text'] ?? 'Solicite uma Cotação Gratuita'); ?>
                </button>
            </div>
            
            <div class="fcs-cta-secondary">
                <p><strong><?php echo esc_html($cta['secondary_text'] ?? ''); ?></strong></p>
                <p><?php echo esc_html($cta['secondary_subtext'] ?? ''); ?></p>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div class="fcs-modal" id="fcsContactModal">
        <div class="fcs-modal-overlay"></div>
        <div class="fcs-modal-content">
            <button class="fcs-modal-close" id="fcsCloseModalBtn">&times;</button>
            
            <h3 id="fcsModalTitle">Solicite sua Cotação</h3>
            <p id="fcsModalSubtitle">Preencha o formulário e entraremos em contato em até 24 horas.</p>
            
            <form id="fcsLeadForm" class="fcs-form">
                <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('futturu_cloud_nonce'); ?>">
                <input type="hidden" name="selected_plan" id="fcsSelectedPlan" value="">
                <input type="hidden" name="traffic_profile" id="fcsTrafficProfile" value="">
                
                <div class="fcs-form-group">
                    <label for="fcsName">Nome completo *</label>
                    <input type="text" id="fcsName" name="name" required>
                </div>
                
                <div class="fcs-form-group">
                    <label for="fcsEmail">E-mail *</label>
                    <input type="email" id="fcsEmail" name="email" required>
                </div>
                
                <div class="fcs-form-group">
                    <label for="fcsPhone">Telefone / WhatsApp</label>
                    <input type="tel" id="fcsPhone" name="phone">
                </div>
                
                <div class="fcs-form-group">
                    <label for="fcsMessage">Como podemos ajudar?</label>
                    <textarea id="fcsMessage" name="message" rows="4"></textarea>
                </div>
                
                <div class="fcs-form-actions">
                    <button type="submit" class="fcs-cta-button fcs-submit-btn">
                        <span class="fcs-btn-text">Enviar Solicitação</span>
                        <span class="fcs-btn-loading" style="display:none;">Enviando...</span>
                    </button>
                </div>
                
                <div class="fcs-form-message" id="fcsFormMessage"></div>
            </form>
        </div>
    </div>

</div>
