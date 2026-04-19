<?php
/**
 * Perfutturu Script Manager View
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

$script_configs = get_option('perfutturu_script_configs', array());

?>

<div class="wrap perfutturu-admin">
    <h1><?php _e('Script Manager - Perfutturu', 'perfutturu'); ?></h1>
    
    <div class="perfutturu-card">
        <p class="description">
            <?php _e('Gerencie quais scripts (CSS e JS) carregam em seu site. Desabilite scripts desnecessários para melhorar a performance.', 'perfutturu'); ?>
        </p>
        
        <div id="perfutturu-script-list" class="perfutturu-script-list">
            <p><span class="spinner is-active"></span> <?php _e('Carregando scripts...', 'perfutturu'); ?></p>
        </div>
    </div>
    
    <!-- Script Configuration Modal -->
    <div id="perfutturu-script-modal" class="perfutturu-modal" style="display:none;">
        <div class="perfutturu-modal-content">
            <h2><?php _e('Configurar Script', 'perfutturu'); ?></h2>
            
            <input type="hidden" id="perfutturu-script-handle" value="">
            
            <div class="perfutturu-modal-body">
                <h3 id="perfutturu-script-name"></h3>
                <p class="description" id="perfutturu-script-src"></p>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Ações', 'perfutturu'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" id="perfutturu-script-disabled">
                                <?php _e('Desabilitar este script', 'perfutturu'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Estratégia de Carregamento', 'perfutturu'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" id="perfutturu-script-defer">
                                <?php _e('Adicionar defer (carregar após HTML)', 'perfutturu'); ?>
                            </label><br>
                            
                            <label>
                                <input type="checkbox" id="perfutturu-script-async">
                                <?php _e('Adicionar async (carregar assincronamente)', 'perfutturu'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('Condições de Desabilitação', 'perfutturu'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" id="perfutturu-script-disable-sitewide">
                                <?php _e('Desabilitar em todo o site', 'perfutturu'); ?>
                            </label><br>
                            
                            <label>
                                <input type="checkbox" id="perfutturu-script-disable-front-page">
                                <?php _e('Desabilitar na página inicial (front_page)', 'perfutturu'); ?>
                            </label><br>
                            
                            <label>
                                <input type="checkbox" id="perfutturu-script-disable-home">
                                <?php _e('Desabilitar na home (blog)', 'perfutturu'); ?>
                            </label><br>
                            
                            <label>
                                <input type="checkbox" id="perfutturu-script-disable-singular">
                                <?php _e('Desabilitar em páginas singulares', 'perfutturu'); ?>
                            </label><br>
                            
                            <label>
                                <input type="checkbox" id="perfutturu-script-disable-posts">
                                <?php _e('Desabilitar em posts', 'perfutturu'); ?>
                            </label><br>
                            
                            <label>
                                <input type="checkbox" id="perfutturu-script-disable-pages">
                                <?php _e('Desabilitar em páginas', 'perfutturu'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php _e('IDs Específicos', 'perfutturu'); ?></th>
                        <td>
                            <input type="text" id="perfutturu-script-disable-ids" class="regular-text" 
                                   placeholder="<?php _e('Ex: 1,2,3 (IDs de posts/páginas)', 'perfutturu'); ?>">
                            <p class="description">
                                <?php _e('Desabilite este script em IDs específicos de posts ou páginas.', 'perfutturu'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="perfutturu-modal-footer">
                <button type="button" class="button button-primary" id="perfutturu-save-script-config">
                    <?php _e('Salvar Configuração', 'perfutturu'); ?>
                </button>
                <button type="button" class="button" id="perfutturu-close-modal">
                    <?php _e('Cancelar', 'perfutturu'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
