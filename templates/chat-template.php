<div 
    class="wp-ai-chat-container" 
    data-primary-color="<?php echo esc_attr($atts['primary_color']); ?>"
    <?php echo isset($settings['enable_animation']) && $settings['enable_animation'] === 'yes' ? 'data-animate="true"' : ''; ?>
>
    <div class="wp-ai-chat-header" style="background: <?php echo esc_attr($atts['primary_color']); ?>;">
        <div class="wp-ai-chat-title"><?php echo esc_html($atts['title']); ?></div>
        <div class="wp-ai-chat-close">&times;</div>
    </div>
    
    <div class="wp-ai-chat-messages">
        <div class="wp-ai-chat-message wp-ai-chat-message-ai">
            <div class="wp-ai-chat-message-content">
                <?php echo esc_html($atts['welcome_message']); ?>
            </div>
            <div class="wp-ai-chat-message-time"><?php echo esc_html(date_i18n(get_option('time_format'))); ?></div>
        </div>
    </div>
    
    <div class="wp-ai-chat-input-container">
        <input type="text" class="wp-ai-chat-input" placeholder="<?php esc_attr_e('Type your message...', 'wp-ai-chat'); ?>">
        <button class="wp-ai-chat-send" style="background: <?php echo esc_attr($atts['primary_color']); ?>;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="22" y1="2" x2="11" y2="13"></line>
                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
            </svg>
        </button>
    </div>
</div>