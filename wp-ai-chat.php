<?php
/**
 * Plugin Name: WordPress AI Chat
 * Plugin URI: https://inertz.org/wp-ai-chat
 * Description: A lightweight AI chat integration for WordPress blogs
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: wp-ai-chat
 * License: GPL v2 or later
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('WP_AI_CHAT_VERSION', '1.0.0');
define('WP_AI_CHAT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_AI_CHAT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once WP_AI_CHAT_PLUGIN_DIR . 'includes/class-wp-ai-chat.php';
require_once WP_AI_CHAT_PLUGIN_DIR . 'includes/class-wp-ai-chat-widget.php';
require_once WP_AI_CHAT_PLUGIN_DIR . 'admin/class-wp-ai-chat-admin.php';

// Activation and deactivation hooks
register_activation_hook(__FILE__, 'wp_ai_chat_activate');
register_deactivation_hook(__FILE__, 'wp_ai_chat_deactivate');

/**
 * The code that runs during plugin activation.
 */
function wp_ai_chat_activate() {
    // Add default settings
    $default_settings = array(
        'api_key' => '',
        'primary_color' => '#3B82F6',
        'chat_title' => 'AI Assistant',
        'welcome_message' => 'Hi there! How can I help you today?',
        'position' => 'bottom-right',
        'enable_animation' => 'yes',
    );
    
    add_option('wp_ai_chat_settings', $default_settings);
}

/**
 * The code that runs during plugin deactivation.
 */
function wp_ai_chat_deactivate() {
    // Cleanup if needed
}

/**
 * Begin execution of the plugin.
 */
function run_wp_ai_chat() {
    $plugin = new WP_AI_Chat();
    $plugin->run();

    $admin = new WP_AI_Chat_Admin();
    $admin->run();
}

run_wp_ai_chat();