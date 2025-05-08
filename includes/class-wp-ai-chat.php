<?php
/**
 * The core plugin class.
 */
class WP_AI_Chat {

    /**
     * Initialize the plugin.
     */
    public function run() {
        // Register shortcode
        add_shortcode('wp_ai_chat', array($this, 'render_chat_shortcode'));
        
        // Register widget
        add_action('widgets_init', function() {
            register_widget('WP_AI_Chat_Widget');
        });
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add chat button to footer if enabled
        add_action('wp_footer', array($this, 'add_chat_button'));
        
        // Register AJAX handlers
        add_action('wp_ajax_wp_ai_chat_send_message', array($this, 'process_chat_message'));
        add_action('wp_ajax_nopriv_wp_ai_chat_send_message', array($this, 'process_chat_message'));
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue_scripts() {
        // Enqueue the main stylesheet
        wp_enqueue_style(
            'wp-ai-chat-styles',
            WP_AI_CHAT_PLUGIN_URL . 'assets/css/wp-ai-chat.css',
            array(),
            WP_AI_CHAT_VERSION
        );
        
        // Enqueue the main JavaScript file
        wp_enqueue_script(
            'wp-ai-chat-script',
            WP_AI_CHAT_PLUGIN_URL . 'assets/js/wp-ai-chat.js',
            array('jquery'),
            WP_AI_CHAT_VERSION,
            true
        );
        
        // Pass variables to JavaScript
        $settings = get_option('wp_ai_chat_settings');
        
        wp_localize_script(
            'wp-ai-chat-script',
            'wpAiChat',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'settings' => $settings,
                'nonce' => wp_create_nonce('wp_ai_chat_nonce'),
            )
        );
    }

    /**
     * Render the chat shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string The chat HTML.
     */
    public function render_chat_shortcode($atts) {
        $settings = get_option('wp_ai_chat_settings');
        
        // Allow shortcode attributes to override settings
        $atts = shortcode_atts(array(
            'title' => $settings['chat_title'],
            'welcome_message' => $settings['welcome_message'],
            'primary_color' => $settings['primary_color'],
        ), $atts);
        
        // Start output buffering
        ob_start();
        
        // Include the template
        include WP_AI_CHAT_PLUGIN_DIR . 'templates/chat-template.php';
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Add chat button to footer if enabled.
     */
    public function add_chat_button() {
        $settings = get_option('wp_ai_chat_settings');
        
        // Check if the chat button is enabled
        if (isset($settings['show_chat_button']) && $settings['show_chat_button'] === 'yes') {
            // Include the chat button template
            include WP_AI_CHAT_PLUGIN_DIR . 'templates/chat-button.php';
        }
    }

    /**
     * Process chat message via AJAX.
     */
    public function process_chat_message() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wp_ai_chat_nonce')) {
            wp_send_json_error('Invalid security token');
        }
        
        // Get the message
        $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : '';
        
        if (empty($message)) {
            wp_send_json_error('Message is required');
        }
        
        // Get settings
        $settings = get_option('wp_ai_chat_settings');
        $api_key = isset($settings['api_key']) ? $settings['api_key'] : '';
        
        if (empty($api_key) && !defined('WP_AI_CHAT_API_KEY')) {
            // Use a simple response if no API key is configured
            $response = $this->get_simple_response($message);
        } else {
            // Use the actual API key
            $api_key = empty($api_key) ? WP_AI_CHAT_API_KEY : $api_key;
            $response = $this->get_ai_response($message, $api_key);
        }
        
        wp_send_json_success(array(
            'response' => $response,
        ));
    }

    /**
     * Get a simple response without API.
     *
     * @param string $message The user message.
     * @return string A simple response.
     */
    private function get_simple_response($message) {
        $message = strtolower($message);
        
        // Simple response logic
        if (strpos($message, 'hello') !== false || strpos($message, 'hi') !== false) {
            return "Hello! How can I help you with your WordPress site today?";
        }
        
        if (strpos($message, 'help') !== false) {
            return "I'm your AI assistant for WordPress. I can answer questions about your blog, help with content ideas, or assist visitors. What would you like to know?";
        }
        
        if (strpos($message, 'wordpress') !== false) {
            return "WordPress is a popular content management system that powers over 40% of all websites on the internet. What specific aspect of WordPress would you like to know more about?";
        }
        
        if (strpos($message, 'plugin') !== false || strpos($message, 'plugins') !== false) {
            return "Plugins extend WordPress functionality. This AI chat is itself a lightweight WordPress plugin that integrates with your blog. Would you like recommendations for other useful plugins?";
        }
        
        if (strpos($message, 'thank') !== false) {
            return "You're welcome! Let me know if you have any other questions.";
        }
        
        // Default response
        return "Thanks for your message. Is there anything specific about your WordPress blog I can help with?";
    }

    /**
     * Get AI response using an API.
     *
     * @param string $message The user message.
     * @param string $api_key The API key.
     * @return string The AI response.
     */
    private function get_ai_response($message, $api_key) {
        // This is where you would integrate with an actual AI API
        // For now, just return the simple response
        return $this->get_simple_response($message);
    }
}