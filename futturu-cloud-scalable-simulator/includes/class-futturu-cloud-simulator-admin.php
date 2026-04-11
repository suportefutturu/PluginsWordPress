/**
 * Admin Panel - Simulador Cloud Futturu
 * Gerenciamento de planos, perfis, textos e configurações
 */

if (!defined('ABSPATH')) {
    exit;
}

$plans = get_option('futturu_cloud_plans', array());
$profiles = get_option('futturu_cloud_profiles', array());
$texts = get_option('futturu_cloud_texts', array());
$cta = get_option('futturu_cloud_cta', array());
$enabled = get_option('futturu_cloud_enabled', true);

// Merge with defaults if empty
if (empty($plans)) {
    $plans = Futturu_Cloud_Scalable_Simulator::get_instance()->get_default_plans();
}
if (empty($profiles)) {
    $profiles = Futturu_Cloud_Scalable_Simulator::get_instance()->get_default_profiles();
}
if (empty($texts)) {
    $texts = Futturu_Cloud_Scalable_Simulator::get_instance()->get_default_texts();
}
if (empty($cta)) {
    $cta = Futturu_Cloud_Scalable_Simulator::get_instance()->get_default_cta();
}
?>

<div class="wrap futturu-cloud-admin">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="fca-tabs">
        <button class="fca-tab active" data-tab="fca-plans">Planos</button>
        <button class="fca-tab" data-tab="fca-profiles">Perfis de Uso</button>
        <button class="fca-tab" data-tab="fca-texts">Textos</button>
        <button class="fca-tab" data-tab="fca-cta">Call-to-Action</button>
        <button class="fca-tab" data-tab="fca-settings">Configurações</button>
    </div>
    
    <!-- Plans Tab -->
    <div id="fca-plans" class="fca-tab-content active">
        <h2>Gerenciar Planos de Hospedagem</h2>
        <p class="description">Configure os planos oferecidos, recursos, preços e caminho de escalabilidade.</p>
        
        <button class="button button-primary" id="fcaAddPlanBtn">+ Novo Plano</button>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>RAM</th>
                    <th>CPU</th>
                    <th>SSD</th>
                    <th>Visitas/mês</th>
                    <th>Sites</th>
                    <th>Preço</th>
                    <th>Próximo Plano</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="fcaPlansList">
                <?php if (!empty($plans)): ?>
                    <?php foreach ($plans as $plan): ?>
                        <tr data-plan-id="<?php echo esc_attr($plan['id'] ?? ''); ?>">
                            <td><strong><?php echo esc_html($plan['name'] ?? ''); ?></strong></td>
                            <td><?php echo esc_html($plan['category'] ?? ''); ?></td>
                            <td><?php echo esc_html($plan['ram'] ?? 0); ?> GB</td>
                            <td><?php echo esc_html($plan['cpu'] ?? 0); ?> vCPU</td>
                            <td><?php echo esc_html($plan['disk'] ?? 0); ?> GB</td>
                            <td><?php echo number_format($plan['views'] ?? 0, 0, ',', '.'); ?></td>
                            <td><?php echo esc_html($plan['sites'] ?? '-'); ?></td>
                            <td>R$ <?php echo number_format($plan['price'] ?? 0, 2, ',', '.'); ?></td>
                            <td><?php echo esc_html($plan['next_plan'] ?? '-'); ?></td>
                            <td>
                                <button class="button fca-edit-plan" data-plan='<?php echo esc_attr(json_encode($plan)); ?>'>Editar</button>
                                <button class="button fca-delete-plan" data-plan-id="<?php echo esc_attr($plan['id'] ?? ''); ?>">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="10">Nenhum plano configurado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Profiles Tab -->
    <div id="fca-profiles" class="fca-tab-content">
        <h2>Perfis de Uso e Tráfego</h2>
        <p class="description">Defina perfis para ajudar visitantes a escolherem o plano ideal.</p>
        
        <button class="button button-primary" id="fcaAddProfileBtn">+ Novo Perfil</button>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Min. Visualizações</th>
                    <th>Max. Visualizações</th>
                    <th>Plano Recomendado</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="fcaProfilesList">
                <?php if (!empty($profiles)): ?>
                    <?php foreach ($profiles as $profile): ?>
                        <tr data-profile-id="<?php echo esc_attr($profile['id'] ?? ''); ?>">
                            <td><strong><?php echo esc_html($profile['name'] ?? ''); ?></strong></td>
                            <td><?php echo number_format($profile['min_views'] ?? 0, 0, ',', '.'); ?></td>
                            <td><?php echo number_format($profile['max_views'] ?? 0, 0, ',', '.'); ?></td>
                            <td><?php echo esc_html($profile['recommended_plan'] ?? '-'); ?></td>
                            <td><?php echo esc_html($profile['description'] ?? ''); ?></td>
                            <td>
                                <button class="button fca-edit-profile" data-profile='<?php echo esc_attr(json_encode($profile)); ?>'>Editar</button>
                                <button class="button fca-delete-profile" data-profile-id="<?php echo esc_attr($profile['id'] ?? ''); ?>">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">Nenhum perfil configurado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Texts Tab -->
    <div id="fca-texts" class="fca-tab-content">
        <h2>Textos e Conteúdo</h2>
        <p class="description">Personalize as mensagens exibidas no simulador.</p>
        
        <form id="fcaTextsForm" class="fca-form">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('futturu_cloud_nonce'); ?>">
            
            <table class="form-table">
                <tr>
                    <th><label>Título Principal</label></th>
                    <td><input type="text" name="intro_title" value="<?php echo esc_attr($texts['intro_title'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label>Subtítulo</label></th>
                    <td><input type="text" name="intro_subtitle" value="<?php echo esc_attr($texts['intro_subtitle'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label>Descrição Introdutória</label></th>
                    <td><textarea name="intro_description" rows="3" class="large-text"><?php echo esc_textarea($texts['intro_description'] ?? ''); ?></textarea></td>
                </tr>
                <tr>
                    <th><label>Pergunta do Quiz</label></th>
                    <td><input type="text" name="quiz_question" value="<?php echo esc_attr($texts['quiz_question'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label>Título da Comparação</label></th>
                    <td><input type="text" name="comparison_title" value="<?php echo esc_attr($texts['comparison_title'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label>Título da Timeline</label></th>
                    <td><input type="text" name="timeline_title" value="<?php echo esc_attr($texts['timeline_title'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label>Descrição da Timeline</label></th>
                    <td><textarea name="timeline_description" rows="2" class="large-text"><?php echo esc_textarea($texts['timeline_description'] ?? ''); ?></textarea></td>
                </tr>
            </table>
            
            <h3>Benefícios (um por linha)</h3>
            <textarea name="benefits_text" rows="8" class="large-text"><?php echo !empty($texts['benefits']) ? esc_textarea(implode("\n", $texts['benefits'])) : ''; ?></textarea>
            <p class="description">Digite cada benefício em uma linha separada.</p>
            
            <h3>Itens de Comparação - Hospedagem Compartilhada (um por linha)</h3>
            <textarea name="comparison_items_text" rows="5" class="large-text"><?php echo !empty($texts['comparison_items']) ? esc_textarea(implode("\n", $texts['comparison_items'])) : ''; ?></textarea>
            
            <p class="submit">
                <button type="submit" class="button button-primary">Salvar Textos</button>
            </p>
        </form>
    </div>
    
    <!-- CTA Tab -->
    <div id="fca-cta" class="fca-tab-content">
        <h2>Call-to-Action (CTA)</h2>
        <p class="description">Configure as mensagens e destino dos botões de ação.</p>
        
        <form id="fcaCtaForm" class="fca-form">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('futturu_cloud_nonce'); ?>">
            
            <table class="form-table">
                <tr>
                    <th><label>Texto Primário</label></th>
                    <td><input type="text" name="primary_text" value="<?php echo esc_attr($cta['primary_text'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label>Subtexto Primário</label></th>
                    <td><input type="text" name="primary_subtext" value="<?php echo esc_attr($cta['primary_subtext'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label>Texto Secundário</label></th>
                    <td><input type="text" name="secondary_text" value="<?php echo esc_attr($cta['secondary_text'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label>Subtexto Secundário</label></th>
                    <td><input type="text" name="secondary_subtext" value="<?php echo esc_attr($cta['secondary_subtext'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label>Texto do Botão</label></th>
                    <td><input type="text" name="button_text" value="<?php echo esc_attr($cta['button_text'] ?? ''); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label>E-mail de Destino</label></th>
                    <td><input type="email" name="email" value="<?php echo esc_attr($cta['email'] ?? 'suporte@futturu.com.br'); ?>" class="regular-text"></td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" class="button button-primary">Salvar CTA</button>
            </p>
        </form>
    </div>
    
    <!-- Settings Tab -->
    <div id="fca-settings" class="fca-tab-content">
        <h2>Configurações Gerais</h2>
        
        <form method="post" action="options.php">
            <?php settings_fields('futturu_cloud_group'); ?>
            <?php do_settings_sections('futturu_cloud_group'); ?>
            
            <table class="form-table">
                <tr>
                    <th>Ativar/Desativar Simulador</th>
                    <td>
                        <label>
                            <input type="checkbox" name="futturu_cloud_enabled" value="1" <?php checked($enabled, true); ?>>
                            Habilitar shortcode [futturu_cloud_scalable_simulator]
                        </label>
                        <p class="description">Desmarque para ocultar o simulador do site.</p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" class="button button-primary">Salvar Configurações</button>
            </p>
        </form>
        
        <hr>
        
        <h3>Resetar para Padrões</h3>
        <p class="description">Restaura todos os planos, perfis e textos para os valores padrão.</p>
        <button class="button" id="fcaResetDefaults">⚠️ Resetar Tudo</button>
    </div>
</div>

<!-- Plan Modal -->
<div id="fcaPlanModal" class="fca-modal" style="display:none;">
    <div class="fca-modal-overlay"></div>
    <div class="fca-modal-content">
        <h3 id="fcaPlanModalTitle">Adicionar/Editar Plano</h3>
        <form id="fcaPlanForm">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('futturu_cloud_nonce'); ?>">
            <input type="hidden" name="plan_id" id="fcaPlanId" value="">
            <input type="hidden" name="action_type" id="fcaPlanActionType" value="create">
            
            <table class="form-table">
                <tr>
                    <th><label>ID Único</label></th>
                    <td><input type="text" name="id" id="fcaPlanIdInput" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label>Nome do Plano</label></th>
                    <td><input type="text" name="name" id="fcaPlanName" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label>Categoria</label></th>
                    <td>
                        <select name="category" id="fcaPlanCategory">
                            <option value="starter">Starter (Inicial)</option>
                            <option value="growth">Growth (Crescimento)</option>
                            <option value="professional">Professional</option>
                            <option value="advanced">Advanced</option>
                            <option value="enterprise">Enterprise</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label>RAM (GB)</label></th>
                    <td><input type="number" name="ram" id="fcaPlanRam" min="1" value="1" required></td>
                </tr>
                <tr>
                    <th><label>CPU (vCPU)</label></th>
                    <td><input type="number" name="cpu" id="fcaPlanCpu" min="1" value="1" required></td>
                </tr>
                <tr>
                    <th><label>Disco SSD (GB)</label></th>
                    <td><input type="number" name="disk" id="fcaPlanDisk" min="10" value="25" required></td>
                </tr>
                <tr>
                    <th><label>Visualizações/mês</label></th>
                    <td><input type="number" name="views" id="fcaPlanViews" min="1000" value="100000" required></td>
                </tr>
                <tr>
                    <th><label>Sites (ex: 1-2)</label></th>
                    <td><input type="text" name="sites" id="fcaPlanSites" value="1-2"></td>
                </tr>
                <tr>
                    <th><label>Preço Mensal (R$)</label></th>
                    <td><input type="number" step="0.01" name="price" id="fcaPlanPrice" min="0" value="239.00" required></td>
                </tr>
                <tr>
                    <th><label>Próximo Plano (ID)</label></th>
                    <td><input type="text" name="next_plan" id="fcaPlanNextPlan" placeholder="ex: br2g"></td>
                </tr>
                <tr>
                    <th><label>Destaque</label></th>
                    <td>
                        <label>
                            <input type="checkbox" name="featured" id="fcaPlanFeatured" value="1">
                            Marcar como "Mais Popular"
                        </label>
                    </td>
                </tr>
                <tr>
                    <th><label>Descrição</label></th>
                    <td><textarea name="description" id="fcaPlanDescription" rows="3" class="large-text"></textarea></td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" class="button button-primary">Salvar Plano</button>
                <button type="button" class="button fca-close-modal">Cancelar</button>
            </p>
        </form>
    </div>
</div>

<!-- Profile Modal -->
<div id="fcaProfileModal" class="fca-modal" style="display:none;">
    <div class="fca-modal-overlay"></div>
    <div class="fca-modal-content">
        <h3 id="fcaProfileModalTitle">Adicionar/Editar Perfil</h3>
        <form id="fcaProfileForm">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('futturu_cloud_nonce'); ?>">
            <input type="hidden" name="profile_id" id="fcaProfileId" value="">
            <input type="hidden" name="action_type" id="fcaProfileActionType" value="create">
            
            <table class="form-table">
                <tr>
                    <th><label>ID Único</label></th>
                    <td><input type="text" name="id" id="fcaProfileIdInput" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label>Nome do Perfil</label></th>
                    <td><input type="text" name="name" id="fcaProfileName" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label>Min. Visualizações</label></th>
                    <td><input type="number" name="min_views" id="fcaProfileMinViews" min="0" value="0" required></td>
                </tr>
                <tr>
                    <th><label>Max. Visualizações</label></th>
                    <td><input type="number" name="max_views" id="fcaProfileMaxViews" min="0" value="100000" required></td>
                </tr>
                <tr>
                    <th><label>Plano Recomendado (ID)</label></th>
                    <td><input type="text" name="recommended_plan" id="fcaProfileRecommendedPlan" placeholder="ex: br1g" required></td>
                </tr>
                <tr>
                    <th><label>Descrição</label></th>
                    <td><textarea name="description" id="fcaProfileDescription" rows="3" class="large-text"></textarea></td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" class="button button-primary">Salvar Perfil</button>
                <button type="button" class="button fca-close-modal">Cancelar</button>
            </p>
        </form>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.fca-tab').on('click', function() {
        var tabId = $(this).data('tab');
        $('.fca-tab').removeClass('active');
        $('.fca-tab-content').removeClass('active');
        $(this).addClass('active');
        $('#' + tabId).addClass('active');
    });
    
    // Plan modal
    $('#fcaAddPlanBtn').on('click', function() {
        $('#fcaPlanModalTitle').text('Adicionar Plano');
        $('#fcaPlanForm')[0].reset();
        $('#fcaPlanId').val('');
        $('#fcaPlanActionType').val('create');
        $('#fcaPlanModal').show();
    });
    
    $('.fca-edit-plan').on('click', function() {
        var plan = JSON.parse($(this).data('plan'));
        $('#fcaPlanModalTitle').text('Editar Plano');
        $('#fcaPlanId').val(plan.id || '');
        $('#fcaPlanIdInput').val(plan.id || '');
        $('#fcaPlanActionType').val('update');
        $('#fcaPlanName').val(plan.name || '');
        $('#fcaPlanCategory').val(plan.category || 'starter');
        $('#fcaPlanRam').val(plan.ram || 1);
        $('#fcaPlanCpu').val(plan.cpu || 1);
        $('#fcaPlanDisk').val(plan.disk || 25);
        $('#fcaPlanViews').val(plan.views || 100000);
        $('#fcaPlanSites').val(plan.sites || '1-2');
        $('#fcaPlanPrice').val(plan.price || 239);
        $('#fcaPlanNextPlan').val(plan.next_plan || '');
        $('#fcaPlanFeatured').prop('checked', plan.featured || false);
        $('#fcaPlanDescription').val(plan.description || '');
        $('#fcaPlanModal').show();
    });
    
    $('#fcaPlanForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.post(ajaxurl, formData + '&action=futturu_save_plan', function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.data.message || 'Erro ao salvar.');
            }
        });
    });
    
    $('.fca-delete-plan').on('click', function() {
        if (!confirm('Tem certeza que deseja excluir este plano?')) return;
        
        var planId = $(this).data('plan-id');
        $.post(ajaxurl, {
            action: 'futturu_delete_plan',
            nonce: '<?php echo wp_create_nonce("futturu_cloud_nonce"); ?>',
            plan_id: planId,
            action_type: 'delete'
        }, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });
    
    // Profile modal
    $('#fcaAddProfileBtn').on('click', function() {
        $('#fcaProfileModalTitle').text('Adicionar Perfil');
        $('#fcaProfileForm')[0].reset();
        $('#fcaProfileId').val('');
        $('#fcaProfileActionType').val('create');
        $('#fcaProfileModal').show();
    });
    
    $('.fca-edit-profile').on('click', function() {
        var profile = JSON.parse($(this).data('profile'));
        $('#fcaProfileModalTitle').text('Editar Perfil');
        $('#fcaProfileId').val(profile.id || '');
        $('#fcaProfileIdInput').val(profile.id || '');
        $('#fcaProfileActionType').val('update');
        $('#fcaProfileName').val(profile.name || '');
        $('#fcaProfileMinViews').val(profile.min_views || 0);
        $('#fcaProfileMaxViews').val(profile.max_views || 100000);
        $('#fcaProfileRecommendedPlan').val(profile.recommended_plan || '');
        $('#fcaProfileDescription').val(profile.description || '');
        $('#fcaProfileModal').show();
    });
    
    $('#fcaProfileForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.post(ajaxurl, formData + '&action=futturu_save_profile', function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.data.message || 'Erro ao salvar.');
            }
        });
    });
    
    $('.fca-delete-profile').on('click', function() {
        if (!confirm('Tem certeza que deseja excluir este perfil?')) return;
        
        var profileId = $(this).data('profile-id');
        $.post(ajaxurl, {
            action: 'futturu_delete_profile',
            nonce: '<?php echo wp_create_nonce("futturu_cloud_nonce"); ?>',
            profile_id: profileId,
            action_type: 'delete'
        }, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });
    
    // Texts form
    $('#fcaTextsForm').on('submit', function(e) {
        e.preventDefault();
        var benefits = $('textarea[name="benefits_text"]').val().split('\n').filter(line => line.trim() !== '');
        var comparisonItems = $('textarea[name="comparison_items_text"]').val().split('\n').filter(line => line.trim() !== '');
        
        var formData = $(this).serialize() + '&benefits[]=' + benefits.join('&benefits[]=') + '&comparison_items[]=' + comparisonItems.join('&comparison_items[]=');
        
        $.post(ajaxurl, formData + '&action=futturu_save_texts', function(response) {
            if (response.success) {
                alert('Textos atualizados com sucesso!');
            } else {
                alert(response.data.message || 'Erro ao salvar.');
            }
        });
    });
    
    // CTA form
    $('#fcaCtaForm').on('submit', function(e) {
        e.preventDefault();
        
        $.post(ajaxurl, $(this).serialize() + '&action=futturu_save_cta', function(response) {
            if (response.success) {
                alert('CTA atualizado com sucesso!');
            } else {
                alert(response.data.message || 'Erro ao salvar.');
            }
        });
    });
    
    // Close modals
    $('.fca-close-modal, .fca-modal-overlay').on('click', function() {
        $(this).closest('.fca-modal').hide();
    });
    
    // Reset defaults
    $('#fcaResetDefaults').on('click', function() {
        if (!confirm('ATENÇÃO: Isso apagará todas as personalizações e restaurará os dados padrão. Continuar?')) return;
        
        $.post(ajaxurl, {
            action: 'futturu_reset_defaults',
            nonce: '<?php echo wp_create_nonce("futturu_cloud_nonce"); ?>'
        }, function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });
});
</script>

<style>
.futturu-cloud-admin { max-width: 1200px; }
.fca-tabs { margin: 20px 0; border-bottom: 2px solid #ddd; }
.fca-tab { padding: 10px 20px; background: none; border: none; cursor: pointer; font-size: 14px; color: #666; }
.fca-tab.active { color: #2271b1; border-bottom: 2px solid #2271b1; margin-bottom: -2px; }
.fca-tab-content { display: none; padding: 20px 0; }
.fca-tab-content.active { display: block; }
.fca-modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 100000; }
.fca-modal-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
.fca-modal-content { position: relative; background: #fff; max-width: 700px; margin: 50px auto; padding: 30px; border-radius: 5px; max-height: 90vh; overflow-y: auto; }
.form-table th { width: 200px; }
</style>
