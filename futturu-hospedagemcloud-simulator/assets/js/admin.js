/**
 * Futturu Cloud Simulator - Admin JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        initAdminPanel();
    });
    
    function initAdminPanel() {
        // Handle tab switching
        handleTabs();
        
        // Handle FAQ add/remove
        handleFAQActions();
        
        // Handle feature add/remove
        handleFeatureActions();
    }
    
    // Handle tab switching
    function handleTabs() {
        $('.tab-button').on('click', function() {
            var $btn = $(this);
            var tabId = $btn.data('tab');
            
            // Update active button
            $('.tab-button').removeClass('active');
            $btn.addClass('active');
            
            // Show corresponding content
            $('.tab-content').removeClass('active');
            $('#tab-' + tabId).addClass('active');
        });
    }
    
    // Handle FAQ add/remove actions
    function handleFAQActions() {
        // Add new FAQ
        $('#add-faq').on('click', function() {
            var index = $('#faqs-container .faq-item').length;
            var newFaqHtml = `
                <div class="faq-item">
                    <label>Pergunta:</label>
                    <input type="text" name="futturu_hospedagemcloud_faqs[${index}][pergunta]" value="" class="large-text" />
                    
                    <label>Resposta:</label>
                    <textarea name="futturu_hospedagemcloud_faqs[${index}][resposta]" rows="3" class="large-text"></textarea>
                    
                    <button type="button" class="button remove-faq">Remover</button>
                </div>
            `;
            
            $('#faqs-container').append(newFaqHtml);
        });
        
        // Remove FAQ (delegated event)
        $(document).on('click', '.remove-faq', function() {
            $(this).closest('.faq-item').remove();
            reindexFAQs();
        });
    }
    
    // Re-index FAQs after removal
    function reindexFAQs() {
        $('#faqs-container .faq-item').each(function(index) {
            var $item = $(this);
            $item.find('input[name^="futturu_hospedagemcloud_faqs"], textarea[name^="futturu_hospedagemcloud_faqs"]').each(function() {
                var name = $(this).attr('name');
                var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', newName);
            });
        });
    }
    
    // Handle feature add/remove actions
    function handleFeatureActions() {
        // Add new feature
        $('#add-feature').on('click', function() {
            var index = $('#features-container .feature-item').length;
            var newFeatureHtml = `
                <div class="feature-item">
                    <input type="text" name="futturu_hospedagemcloud_features[${index}]" value="" class="large-text" />
                    <button type="button" class="button remove-feature">Remover</button>
                </div>
            `;
            
            $('#features-container').append(newFeatureHtml);
        });
        
        // Remove feature (delegated event)
        $(document).on('click', '.remove-feature', function() {
            $(this).closest('.feature-item').remove();
            reindexFeatures();
        });
    }
    
    // Re-index features after removal
    function reindexFeatures() {
        $('#features-container .feature-item').each(function(index) {
            var $item = $(this);
            $item.find('input[name^="futturu_hospedagemcloud_features"]').each(function() {
                var name = $(this).attr('name');
                var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                $(this).attr('name', newName);
            });
        });
    }
    
})(jQuery);
