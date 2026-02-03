<?php
/**
 * Plugin Name: Early Start SEO Pro
 * Plugin URI:  https://earlystart.com
 * Description: Advanced AI-powered Schema validation, automated fixes, and SEO enhancements for Early Start.
 * Version:     1.0.1
 * Author:      Early Start Development Team
 * Text Domain: earlystart-seo-pro
 * License:     GPLv2 or later
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Temporary debug: enable error display
if (defined('WP_DEBUG') && WP_DEBUG) {
    @ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// Define Constants
define('EARLYSTART_SEO_VERSION', '1.0.1');
define('EARLYSTART_SEO_PATH', plugin_dir_path(__FILE__));
define('EARLYSTART_SEO_URL', plugin_dir_url(__FILE__));

/**
 * Load plugin textdomain for translations.
 */
function earlystart_seo_pro_load_textdomain()
{
    load_plugin_textdomain(
        'earlystart-seo-pro',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
}
add_action('plugins_loaded', 'earlystart_seo_pro_load_textdomain', 5);

/**
 * Initialize the Plugin
 */
function earlystart_seo_init()
{
    try {
        // Load Bootstrap (handles all includes and hooks)
        require_once EARLYSTART_SEO_PATH . 'inc/bootstrap.php';
    } catch (Throwable $e) {
        // Catch any PHP errors and show admin notice instead of crashing
        add_action('admin_notices', function () use ($e) {
            echo '<div class="notice notice-error"><p><strong>Early Start SEO Pro Error:</strong> ' . esc_html($e->getMessage()) . ' in ' . esc_html($e->getFile()) . ' on line ' . esc_html($e->getLine()) . '</p></div>';
        });
        return;
    }
}
add_action('plugins_loaded', 'earlystart_seo_init');

/**
 * Activation Hook
 */
function earlystart_seo_activate()
{
    // Load bootstrap to get class definitions
    require_once EARLYSTART_SEO_PATH . 'inc/bootstrap.php';

    // Register multilingual rewrite rules
    if (class_exists('earlystart_Multilingual_Manager')) {
        $manager = earlystart_Multilingual_Manager::get_instance();
        $manager->setup_rewrites();
    }

    // Flush rewrite rules to apply changes
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'earlystart_seo_activate');
