<?php
/**
 * The admin-specific functionality of the plugin.
 */
class WP_AI_Chat_Admin {

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
    }

    /**
     * Register the admin menu and settings page.
     */
    public function run() {
        // Add the admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Enqueue admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Register the admin menu.
     */
    public function add_admin_menu() {
        add_menu_page(
            __('AI Chat Settings', 'wp-ai-chat'),
            __('AI Chat', 'wp-ai-chat'),
            'manage_options',
            'wp-ai-chat',
            array($this, 'display_settings_page'),
            'dashicons-format-chat',
            30
        );
    }

    /**
     * Register plugin settings.
     */
    public function register_settings() {
        register_setting(
            'wp_ai_chat_settings',
            'wp_ai_chat_settings',
            array($this, 'sanitize_settings')
        );
        
        add_settings_section(
            'wp_ai_chat_general_settings',
            __('General Settings', 'wp-ai-chat'),
            array($this, 'settings_section_callback'),
            'wp_ai_chat'
        );
        
        add_settings_field(
            'api_key',
            __('API Key', 'wp-ai-chat'),
            array($this, 'api_key_callback'),
            'wp_ai_chat',
            'wp_ai_chat_general_settings'
        );
        
        add_settings_field(
            'chat_title',
            __('Chat Title', 'wp-ai-chat'),
            array($this, 'chat_title_callback'),
            'wp_ai_chat',
            'wp_ai_chat_general_settings'
        );
        
        add_settings_field(
            'welcome_message',
            __('Welcome Message', 'wp-ai-chat'),
            array($this, 'welcome_message_callback'),
            'wp_ai_chat',
            'wp_ai_chat_general_settings'
        );
        
        add_settings_field(
            'primary_color',
            __('Primary Color', 'wp-ai-chat'),
            array($this, 'primary_color_callback'),
            'wp_ai_chat',
            'wp_ai_chat_general_settings'
        );
        
        add_settings_field(
            'show_chat_button',
            __('Show Chat Button', 'wp-ai-chat'),
            array($this, 'show_chat_button_callback'),
            'wp_ai_chat',
            'wp_ai_chat_general_settings'
        );
        
        add_settings_field(
            'position',
            __('Button Position', 'wp-ai-chat'),
            array($this, 'position_callback'),
            'wp_ai_chat',
            'wp_ai_chat_general_settings'
        );
        
        add_settings_field(
            'enable_animation',
            __('Enable Animations', 'wp-ai-chat'),
            array($this, 'enable_animation_callback'),
            'wp_ai_chat',
            'wp_ai_chat_general_settings'
        );
    }

    /**
     * Sanitize settings.
     *
     * @param array $input The settings input.
     * @return array The sanitized settings.
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        $sanitized['api_key'] = isset($input['api_key']) ? sanitize_text_field($input['api_key']) : '';
        $sanitized['chat_title'] = isset($input['chat_title']) ? sanitize_text_field($input['chat_title']) : 'AI Assistant';
        $sanitized['welcome_message'] = isset($input['welcome_message']) ? sanitize_textarea_field($input['welcome_message']) : 'Hi there! How can I help you today?';
        $sanitized['primary_color'] = isset($input['primary_color']) ? sanitize_text_field($input['primary_color']) : '#3B82F6';
        $sanitized['show_chat_button'] = isset($input['show_chat_button']) ? sanitize_key($input['show_chat_button']) : 'no';
        $sanitized['position'] = isset($input['position']) ? sanitize_key($input['position']) : 'bottom-right';
        $sanitized['enable_animation'] = isset($input['enable_animation']) ? sanitize_key($input['enable_animation']) : 'yes';
        
        return $sanitized;
    }

    /**
     * Enqueue admin scripts and styles.
     */
    public function enqueue_admin_scripts($hook) {
        if ('toplevel_page_wp-ai-chat' !== $hook) {
            return;
        }
        
        // Enqueue color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Enqueue admin script
        wp_enqueue_script(
            'wp-ai-chat-admin',
            WP_AI_CHAT_PLUGIN_URL . 'admin/js/wp-ai-chat-admin.js',
            array('jquery', 'wp-color-picker'),
            WP_AI_CHAT_VERSION,
            true
        );
        
        // Enqueue admin style
        wp_enqueue_style(
            'wp-ai-chat-admin',
            WP_AI_CHAT_PLUGIN_URL . 'admin/css/wp-ai-chat-admin.css',
            array(),
            WP_AI_CHAT_VERSION
        );
    }

    /**
     * Settings section callback.
     */
    public function settings_section_callback() {
        echo '<p>' . __('Configure the AI Chat settings below.', 'wp-ai-chat') . '</p>';
    }

    /**
     * API key field callback.
     */
    public function api_key_callback() {
        $settings = get_option('wp_ai_chat_settings');
        $api_key = isset($settings['api_key']) ? $settings['api_key'] : '';
        ?>
        <input 
            type="password" 
            id="api_key" 
            name="wp_ai_chat_settings[api_key]" 
            value="<?php echo esc_attr($api_key); ?>" 
            class="regular-text"
        >
        <p class="description">
            <?php _e('Enter your AI API key. Leave blank to use simple responses.', 'wp-ai-chat'); ?>
        </p>
        <?php
    }

    /**
     * Chat title field callback.
     */
    public function chat_title_callback() {
        $settings = get_option('wp_ai_chat_settings');
        $chat_title = isset($settings['chat_title']) ? $settings['chat_title'] : 'AI Assistant';
        ?>
        <input 
            type="text" 
            id="chat_title" 
            name="wp_ai_chat_settings[chat_title]" 
            value="<?php echo esc_attr($chat_title); ?>" 
            class="regular-text"
        >
        <?php
    }

    /**
     * Welcome message field callback.
     */
    public function welcome_message_callback() {
        $settings = get_option('wp_ai_chat_settings');
        $welcome_message = isset($settings['welcome_message']) ? $settings['welcome_message'] : 'Hi there! How can I help you today?';
        ?>
        <textarea 
            id="welcome_message" 
            name="wp_ai_chat_settings[welcome_message]" 
            class="large-text" 
            rows="3"
        ><?php echo esc_textarea($welcome_message); ?></textarea>
        <?php
    }

    /**
     * Primary color field callback.
     */
    public function primary_color_callback() {
        $settings = get_option('wp_ai_chat_settings');
        $primary_color = isset($settings['primary_color']) ? $settings['primary_color'] : '#3B82F6';
        ?>
        <input 
            type="text" 
            id="primary_color" 
            name="wp_ai_chat_settings[primary_color]" 
            value="<?php echo esc_attr($primary_color); ?>" 
            class="wp-ai-chat-color-picker"
        >
        <?php
    }

    /**
     * Show chat button field callback.
     */
    public function show_chat_button_callback() {
        $settings = get_option('wp_ai_chat_settings');
        $show_chat_button = isset($settings['show_chat_button']) ? $settings['show_chat_button'] : 'no';
        ?>
        <select id="show_chat_button" name="wp_ai_chat_settings[show_chat_button]">
            <option value="yes" <?php selected($show_chat_button, 'yes'); ?>>
                <?php _e('Yes', 'wp-ai-chat'); ?>
            </option>
            <option value="no" <?php selected($show_chat_button, 'no'); ?>>
                <?php _e('No', 'wp-ai-chat'); ?>
            </option>
        </select>
        <p class="description">
            <?php _e('Display a floating chat button on your site.', 'wp-ai-chat'); ?>
        </p>
        <?php
    }

    /**
     * Position field callback.
     */
    public function position_callback() {
        $settings = get_option('wp_ai_chat_settings');
        $position = isset($settings['position']) ? $settings['position'] : 'bottom-right';
        ?>
        <select id="position" name="wp_ai_chat_settings[position]">
            <option value="bottom-right" <?php selected($position, 'bottom-right'); ?>>
                <?php _e('Bottom Right', 'wp-ai-chat'); ?>
            </option>
            <option value="bottom-left" <?php selected($position, 'bottom-left'); ?>>
                <?php _e('Bottom Left', 'wp-ai-chat'); ?>
            </option>
        </select>
        <?php
    }

    /**
     * Enable animation field callback.
     */
    public function enable_animation_callback() {
        $settings = get_option('wp_ai_chat_settings');
        $enable_animation = isset($settings['enable_animation']) ? $settings['enable_animation'] : 'yes';
        ?>
        <select id="enable_animation" name="wp_ai_chat_settings[enable_animation]">
            <option value="yes" <?php selected($enable_animation, 'yes'); ?>>
                <?php _e('Yes', 'wp-ai-chat'); ?>
            </option>
            <option value="no" <?php selected($enable_animation, 'no'); ?>>
                <?php _e('No', 'wp-ai-chat'); ?>
            </option>
        </select>
        <p class="description">
            <?php _e('Enable smooth animations for a better user experience.', 'wp-ai-chat'); ?>
        </p>
        <?php
    }

    /**
     * Display the settings page.
     */
    public function display_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="wp-ai-chat-admin-container">
                <div class="wp-ai-chat-admin-main">
                    <form method="post" action="options.php">
                        <?php
                        settings_fields('wp_ai_chat_settings');
                        do_settings_sections('wp_ai_chat');
                        submit_button();
                        ?>
                    </form>
                </div>
                
                <div class="wp-ai-chat-admin-sidebar">
                    <div class="wp-ai-chat-admin-box">
                        <h2><?php _e('How to Use', 'wp-ai-chat'); ?></h2>
                        <p><?php _e('You can add the AI Chat to your site in one of these ways:', 'wp-ai-chat'); ?></p>
                        <ol>
                            <li>
                                <?php _e('Use the shortcode:', 'wp-ai-chat'); ?> 
                                <code>[wp_ai_chat]</code>
                            </li>
                            <li>
                                <?php _e('Add the "AI Chat" widget to a sidebar', 'wp-ai-chat'); ?>
                            </li>
                            <li>
                                <?php _e('Enable the floating chat button above', 'wp-ai-chat'); ?>
                            </li>
                        </ol>
                    </div>
                    
                    <div class="wp-ai-chat-admin-box">
                        <h2><?php _e('Shortcode Attributes', 'wp-ai-chat'); ?></h2>
                        <p><?php _e('Customize the shortcode with these attributes:', 'wp-ai-chat'); ?></p>
                        <ul>
                            <li>
                                <code>title</code> - <?php _e('Chat window title', 'wp-ai-chat'); ?>
                            </li>
                            <li>
                                <code>welcome_message</code> - <?php _e('Initial message from the AI', 'wp-ai-chat'); ?>
                            </li>
                            <li>
                                <code>primary_color</code> - <?php _e('Main chat color (hex)', 'wp-ai-chat'); ?>
                            </li>
                        </ul>
                        <p><strong><?php _e('Example:', 'wp-ai-chat'); ?></strong></p>
                        <code>[wp_ai_chat title="Help Bot" welcome_message="How can I assist you?" primary_color="#4F46E5"]</code>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}