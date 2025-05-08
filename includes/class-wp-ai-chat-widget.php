<?php
/**
 * The widget class for the AI Chat.
 */
class WP_AI_Chat_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'wp_ai_chat_widget',
            __('AI Chat', 'wp-ai-chat'),
            array(
                'description' => __('Display an AI chat assistant on your site.', 'wp-ai-chat'),
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('AI Assistant', 'wp-ai-chat');
        $welcome_message = !empty($instance['welcome_message']) 
            ? $instance['welcome_message'] 
            : __('Hi there! How can I help you today?', 'wp-ai-chat');
        
        // Display the widget
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        
        // Get settings
        $settings = get_option('wp_ai_chat_settings');
        
        // Prepare attributes for the shortcode
        $atts = array(
            'title' => $title,
            'welcome_message' => $welcome_message,
            'primary_color' => isset($instance['primary_color']) ? $instance['primary_color'] : $settings['primary_color'],
        );
        
        // Include the chat template with widget-specific class
        echo '<div class="wp-ai-chat-widget-container">';
        include WP_AI_CHAT_PLUGIN_DIR . 'templates/chat-template.php';
        echo '</div>';
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('AI Assistant', 'wp-ai-chat');
        $welcome_message = !empty($instance['welcome_message']) 
            ? $instance['welcome_message'] 
            : __('Hi there! How can I help you today?', 'wp-ai-chat');
        $primary_color = !empty($instance['primary_color']) ? $instance['primary_color'] : '#3B82F6';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php _e('Title:', 'wp-ai-chat'); ?>
            </label>
            <input 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                type="text" 
                value="<?php echo esc_attr($title); ?>"
            >
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('welcome_message')); ?>">
                <?php _e('Welcome Message:', 'wp-ai-chat'); ?>
            </label>
            <textarea 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('welcome_message')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('welcome_message')); ?>"
                rows="3"
            ><?php echo esc_textarea($welcome_message); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('primary_color')); ?>">
                <?php _e('Primary Color:', 'wp-ai-chat'); ?>
            </label>
            <input 
                class="widefat color-picker" 
                id="<?php echo esc_attr($this->get_field_id('primary_color')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('primary_color')); ?>" 
                type="text" 
                value="<?php echo esc_attr($primary_color); ?>"
            >
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) 
            ? sanitize_text_field($new_instance['title']) 
            : '';
        $instance['welcome_message'] = (!empty($new_instance['welcome_message'])) 
            ? sanitize_textarea_field($new_instance['welcome_message']) 
            : '';
        $instance['primary_color'] = (!empty($new_instance['primary_color'])) 
            ? sanitize_text_field($new_instance['primary_color']) 
            : '#3B82F6';
        
        return $instance;
    }
}