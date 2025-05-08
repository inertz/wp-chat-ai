/**
 * WordPress AI Chat - Admin JavaScript
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Initialize color picker
        $('.wp-ai-chat-color-picker').wpColorPicker();
        
        // Toggle button position field based on show button value
        const showButtonField = $('#show_chat_button');
        const positionField = $('#position').closest('tr');
        
        function togglePositionField() {
            if (showButtonField.val() === 'yes') {
                positionField.show();
            } else {
                positionField.hide();
            }
        }
        
        // Initial state
        togglePositionField();
        
        // On change
        showButtonField.on('change', togglePositionField);
    });
    
})(jQuery);