<?php
/**
 * SEO Head Ownership Orchestrator
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get active SEO head mode.
 *
 * @return string
 */
function earlystart_get_seo_head_mode()
{
    $allowed = array('theme_primary', 'plugin_primary', 'hybrid');

    $mode = get_option('earlystart_seo_head_mode', 'theme_primary');
    if (!in_array($mode, $allowed, true)) {
        $mode = 'theme_primary';
    }

    $mode = apply_filters('earlystart_seo_head_mode', $mode);
    if (!in_array($mode, $allowed, true)) {
        $mode = 'theme_primary';
    }

    return $mode;
}

/**
 * Detect SEO plugin availability.
 *
 * @return bool
 */
function earlystart_is_seo_plugin_active()
{
    return defined('EARLYSTART_SEO_VERSION') || class_exists('earlystart_Schema_Registry');
}

/**
 * Default theme schema gate.
 *
 * @param bool $should_emit Current value.
 * @return bool
 */
function earlystart_default_should_emit_theme_schema($should_emit)
{
    $mode = earlystart_get_seo_head_mode();
    if (earlystart_is_seo_plugin_active() && ($mode === 'plugin_primary' || $mode === 'hybrid')) {
        return false;
    }

    return (bool) $should_emit;
}
add_filter('earlystart_should_emit_theme_schema', 'earlystart_default_should_emit_theme_schema', 10, 1);

/**
 * Apply SEO ownership decisions once query context is available.
 */
function earlystart_apply_seo_head_mode()
{
    if (is_admin()) {
        return;
    }

    $mode = earlystart_get_seo_head_mode();
    $plugin_active = earlystart_is_seo_plugin_active();
    $multilingual_manager = null;

    if (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'get_instance')) {
        $multilingual_manager = earlystart_Multilingual_Manager::get_instance();
    }

    // Reset theme-owned hooks first to avoid duplicate adds.
    remove_action('wp_head', 'chroma_enforce_canonical', 2);
    remove_action('wp_head', 'earlystart_render_social_meta_tags', 5);
    remove_action('wp_head', 'earlystart_output_social_meta_tags', 5);
    remove_action('wp_head', 'earlystart_og_tags', 5);
    remove_action('wp_head', 'earlystart_twitter_cards', 6);
    remove_action('wp_head', 'earlystart_shared_meta_description', 2);
    remove_action('wp_head', 'earlystart_output_seo_mode_debug_comment', 0);

    // Reset plugin canonical control filters before applying mode-specific rules.
    remove_filter('pre_option_earlystart_seo_enable_canonical', '__return_zero');
    remove_filter('pre_option_earlystart_seo_redirect_canonical', '__return_zero');

    // Fallback ownership: if plugin is not active, keep core theme SEO emitters on.
    if (!$plugin_active) {
        if ($multilingual_manager) {
            remove_action('wp_head', array($multilingual_manager, 'localize_meta_description'), 1);
        }

        add_action('wp_head', 'chroma_enforce_canonical', 2);
        add_action('wp_head', 'earlystart_render_social_meta_tags', 5);

        if (defined('WP_DEBUG') && WP_DEBUG) {
            add_action('wp_head', 'earlystart_output_seo_mode_debug_comment', 0);
        }
        return;
    }

    if ($mode === 'theme_primary') {
        if ($multilingual_manager) {
            remove_action('wp_head', array($multilingual_manager, 'localize_meta_description'), 1);
        }

        add_action('wp_head', 'chroma_enforce_canonical', 2);
        add_action('wp_head', 'earlystart_render_social_meta_tags', 5);

        // Disable plugin canonical while theme is primary canonical owner.
        add_filter('pre_option_earlystart_seo_enable_canonical', '__return_zero');
        add_filter('pre_option_earlystart_seo_redirect_canonical', '__return_zero');

        // Suppress plugin schema emission to keep theme as primary source.
        remove_action('wp_head', array('earlystart_Schema_Registry', 'output_all_schemas'), 99);

        // Remove known plugin schema emitters when loaded.
        remove_action('wp_head', 'earlystart_general_content_schema_pro', 1);
        remove_action('wp_head', 'earlystart_location_schema_pro');
        remove_action('wp_head', 'earlystart_city_schema_pro');
        remove_action('wp_head', 'earlystart_program_schema_pro');
        remove_action('wp_head', 'earlystart_city_faq_schema_output');
        remove_action('wp_head', 'earlystart_organization_schema_pro', 5);
        remove_action('wp_head', 'earlystart_website_schema_pro', 6);

        // Remove plugin modular schema emitters while theme schema is primary.
        remove_action('wp_head', array('earlystart_Event_Schema_Builder', 'output'));
        remove_action('wp_head', array('earlystart_HowTo_Schema_Builder', 'output'));
        remove_action('wp_head', array('earlystart_Schema_Injector', 'output_person_schema'));
        remove_action('wp_head', array('earlystart_Schema_Injector', 'output_profile_page_schema'));
        remove_action('wp_head', array('earlystart_Schema_Injector', 'output_job_posting_schema'));
        remove_action('wp_head', array('earlystart_Schema_Injector', 'output_course_schema'));
        remove_action('wp_head', array('earlystart_Page_Type_Builder', 'output'));
        remove_action('wp_head', array('earlystart_Schema_Injector', 'output_website_schema'));
        remove_action('wp_head', array('earlystart_Archive_ItemList_Builder', 'output'));
        remove_action('wp_head', array('earlystart_Article_Builder', 'output'));
        remove_action('wp_head', array('earlystart_Special_Announcement_Builder', 'output'));
        remove_action('wp_head', array('earlystart_Learning_Resource_Builder', 'output'));
        remove_action('wp_head', array('earlystart_Schema_Injector', 'output_modular_schemas'), 20);
    } elseif ($mode === 'hybrid') {
        // Plugin owns canonical/schema, theme owns social/meta.
        if ($multilingual_manager) {
            remove_action('wp_head', array($multilingual_manager, 'localize_meta_description'), 1);
        }
        add_action('wp_head', 'earlystart_render_social_meta_tags', 5);
    }

    if (defined('WP_DEBUG') && WP_DEBUG) {
        add_action('wp_head', 'earlystart_output_seo_mode_debug_comment', 0);
    }
}
add_action('wp', 'earlystart_apply_seo_head_mode', 5);

/**
 * Debug comment for active SEO mode.
 */
function earlystart_output_seo_mode_debug_comment()
{
    echo "\n<!-- EarlyStart SEO mode: " . esc_html(earlystart_get_seo_head_mode()) . " -->\n";
}
