<div 
    class="wp-ai-chat-button-container <?php echo esc_attr($settings['position']); ?>"
    data-primary-color="<?php echo esc_attr($settings['primary_color']); ?>"
    <?php echo isset($settings['enable_animation']) && $settings['enable_animation'] === 'yes' ? 'data-animate="true"' : ''; ?>
>
    <button class="wp-ai-chat-button" style="background: <?php echo esc_attr($settings['primary_color']); ?>;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </button>
    
    <div class="wp-ai-chat-popup">
        <?php 
        // Prepare attributes for the chat template
        $atts = array(
            'title' => $settings['chat_title'],
            'welcome_message' => $settings['welcome_message'],
            'primary_color' => $settings['primary_color'],
        );
        
        // Include the chat template
        include WP_AI_CHAT_PLUGIN_DIR . 'templates/chat-template.php';
        ?>
    </div>
</div>