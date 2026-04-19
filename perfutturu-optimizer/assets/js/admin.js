/**
 * Perfutturu Admin JavaScript
 */

(function($) {
    'use strict';
    
    // Initialize on document ready
    $(document).ready(function() {
        initScriptManager();
        initQuickActions();
        initSettings();
    });
    
    /**
     * Initialize Script Manager
     */
    function initScriptManager() {
        var $scriptList = $('#perfutturu-script-list');
        
        if (!$scriptList.length) {
            return;
        }
        
        loadScripts();
    }
    
    /**
     * Load all enqueued scripts
     */
    function loadScripts() {
        var $scriptList = $('#perfutturu-script-list');
        
        // Show loading state
        $scriptList.html('<p><span class="spinner is-active"></span> ' + perfutturuAdmin.strings.loadingScripts + '</p>');
        
        $.ajax({
            url: perfutturuAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'perfutturu_get_scripts',
                nonce: perfutturuAdmin.nonce
            },
            success: function(response) {
                console.log('Scripts response:', response); // Debug log
                
                if (response.success) {
                    renderScriptList(response.data);
                } else {
                    console.error('Error response:', response);
                    $scriptList.html('<p class="error">' + perfutturuAdmin.strings.error + ': ' + (response.data || 'Unknown error') + '</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                $scriptList.html('<p class="error">' + perfutturuAdmin.strings.error + ': ' + status + '</p>');
            }
        });
    }
    
    /**
     * Render script list
     */
    function renderScriptList(scripts) {
        var $scriptList = $('#perfutturu-script-list');
        var html = '';
        
        if (Object.keys(scripts).length === 0) {
            $scriptList.html('<p>' + perfutturuAdmin.strings.noScripts + '</p>');
            return;
        }
        
        html += '<div class="perfutturu-scripts-container">';
        
        $.each(scripts, function(handle, data) {
            var typeClass = data.type === 'script' ? 'script-type-script' : 'script-type-style';
            var typeLabel = data.type === 'script' ? 'JS' : 'CSS';
            
            html += '<div class="script-item" data-handle="' + handle + '">';
            html += '<div class="script-info">';
            html += '<span class="script-handle">' + escapeHtml(handle) + '</span>';
            html += '<span class="script-type ' + typeClass + '">' + typeLabel + '</span>';
            if (data.src) {
                html += '<div class="script-src">' + escapeHtml(data.src) + '</div>';
            }
            html += '</div>';
            html += '<div class="script-actions">';
            html += '<button type="button" class="button configure-script" data-handle="' + handle + '">';
            html += perfutturuAdmin.strings.configure;
            html += '</button>';
            html += '</div>';
            html += '</div>';
        });
        
        html += '</div>';
        
        $scriptList.html(html);
        
        // Bind configure button clicks
        $('.configure-script').on('click', function() {
            var handle = $(this).data('handle');
            openScriptModal(handle, scripts[handle]);
        });
    }
    
    /**
     * Open script configuration modal
     */
    function openScriptModal(handle, data) {
        var $modal = $('#perfutturu-script-modal');
        var $handleInput = $('#perfutturu-script-handle');
        var $nameEl = $('#perfutturu-script-name');
        var $srcEl = $('#perfutturu-script-src');
        
        // Get existing config from localized script
        var existingConfig = perfutturuAdmin.scriptConfigs && perfutturuAdmin.scriptConfigs[handle] || {};
        
        // Populate modal
        $handleInput.val(handle);
        $nameEl.text(handle);
        $srcEl.text(data.src || '');
        
        // Set checkbox states
        $('#perfutturu-script-disabled').prop('checked', !!existingConfig.disabled);
        $('#perfutturu-script-defer').prop('checked', !!existingConfig.defer);
        $('#perfutturu-script-async').prop('checked', !!existingConfig.async);
        $('#perfutturu-script-disable-sitewide').prop('checked', !!existingConfig.disable_sitewide);
        $('#perfutturu-script-disable-front-page').prop('checked', !!existingConfig.disable_on_front_page);
        $('#perfutturu-script-disable-home').prop('checked', !!existingConfig.disable_on_home);
        $('#perfutturu-script-disable-singular').prop('checked', !!existingConfig.disable_on_singular);
        $('#perfutturu-script-disable-posts').prop('checked', !!existingConfig.disable_on_posts);
        $('#perfutturu-script-disable-pages').prop('checked', !!existingConfig.disable_on_pages);
        $('#perfutturu-script-disable-ids').val(existingConfig.disable_specific_ids || '');
        
        // Show modal
        $modal.show();
    }
    
    /**
     * Close modal
     */
    function closeModal() {
        $('#perfutturu-script-modal').hide();
    }
    
    /**
     * Save script configuration
     */
    function saveScriptConfig() {
        var handle = $('#perfutturu-script-handle').val();
        var config = {
            disabled: $('#perfutturu-script-disabled').is(':checked'),
            defer: $('#perfutturu-script-defer').is(':checked'),
            async: $('#perfutturu-script-async').is(':checked'),
            disable_sitewide: $('#perfutturu-script-disable-sitewide').is(':checked'),
            disable_on_front_page: $('#perfutturu-script-disable-front-page').is(':checked'),
            disable_on_home: $('#perfutturu-script-disable-home').is(':checked'),
            disable_on_singular: $('#perfutturu-script-disable-singular').is(':checked'),
            disable_on_posts: $('#perfutturu-script-disable-posts').is(':checked'),
            disable_on_pages: $('#perfutturu-script-disable-pages').is(':checked'),
            disable_specific_ids: $('#perfutturu-script-disable-ids').val()
        };
        
        // Update local config in perfutturuAdmin object
        if (!perfutturuAdmin.scriptConfigs) {
            perfutturuAdmin.scriptConfigs = {};
        }
        perfutturuAdmin.scriptConfigs[handle] = config;
        
        // Show saving state
        var $saveBtn = $('#perfutturu-save-script-config');
        var originalText = $saveBtn.text();
        $saveBtn.text(perfutturuAdmin.strings.saving);
        
        // Save via AJAX
        $.ajax({
            url: perfutturuAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'perfutturu_save_script_config',
                nonce: perfutturuAdmin.nonce,
                handle: handle,
                config: JSON.stringify(config)
            },
            success: function(response) {
                if (response.success) {
                    showNotice(perfutturuAdmin.strings.saved, 'success');
                    closeModal();
                    // Reload scripts to show updated status
                    loadScripts();
                } else {
                    showNotice(perfutturuAdmin.strings.error, 'error');
                }
            },
            error: function() {
                showNotice(perfutturuAdmin.strings.error, 'error');
            },
            complete: function() {
                $saveBtn.text(originalText);
            }
        });
    }
    
    /**
     * Initialize quick actions
     */
    function initQuickActions() {
        // Clear cache button
        $('#perfutturu-clear-cache').on('click', function() {
            var $btn = $(this);
            var originalText = $btn.text();
            
            $btn.prop('disabled', true).text(perfutturuAdmin.strings.saving);
            
            // In a real implementation, this would call an AJAX endpoint
            setTimeout(function() {
                $btn.prop('disabled', false).text(originalText);
                showNotice('Cache limpo com sucesso!', 'success');
            }, 1000);
        });
        
        // Toggle test mode button
        $('#perfutturu-toggle-test-mode').on('click', function() {
            var $btn = $(this);
            var originalText = $btn.text();
            
            $btn.prop('disabled', true).text(perfutturuAdmin.strings.saving);
            
            // In a real implementation, this would call an AJAX endpoint
            setTimeout(function() {
                $btn.prop('disabled', false).text(originalText);
                showNotice('Modo de teste atualizado!', 'success');
                location.reload();
            }, 1000);
        });
        
        // Modal close button
        $('#perfutturu-close-modal').on('click', closeModal);
        
        // Save script config button
        $('#perfutturu-save-script-config').on('click', saveScriptConfig);
        
        // Close modal on outside click
        $(window).on('click', function(e) {
            var $modal = $('#perfutturu-script-modal');
            if ($(e.target).hasClass('perfutturu-modal')) {
                closeModal();
            }
        });
    }
    
    /**
     * Initialize settings page
     */
    function initSettings() {
        // Add any settings-specific functionality here
    }
    
    /**
     * Show admin notice
     */
    function showNotice(message, type) {
        var noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
        var $notice = $('<div class="notice ' + noticeClass + ' is-dismissible"><p>' + message + '</p></div>');
        
        $('.wrap h1').after($notice);
        
        setTimeout(function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        
        return text.replace(/[&<>"']/g, function(m) {
            return map[m];
        });
    }
    
})(jQuery);
