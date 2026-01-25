<?php
/**
 * Legacy options helpers (works without ACF)
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
        exit;
}

/**
 * Register ACF Options Page
 */
if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
                'page_title' => __('Theme Settings', 'chroma-early-start'),
                'menu_title' => __('Theme Settings', 'chroma-early-start'),
                'menu_slug' => 'theme-general-settings',
                'capability' => 'edit_posts',
                'redirect' => false
        ));
}

/**
 * Global settings helper
 */
function earlystart_get_global_setting($key, $default = '')
{
        $defaults = array(
                'global_phone' => '',
                'global_email' => '',
                'global_tour_email' => '',
                'global_address' => '',
                'global_city' => '',
                'global_state' => 'GA',
                'global_zip' => '',
                'global_facebook_url' => '',
                'global_instagram_url' => '',
                'global_linkedin_url' => '',
                'global_seo_default_title' => get_bloginfo('name'),
                'global_seo_default_description' => get_bloginfo('description'),
                'global_logo' => '',
        );

        $settings = get_option('earlystart_global_settings', array());
        $value = $settings[$key] ?? get_option($key, $default);

        if ('' === $value && isset($defaults[$key])) {
                $value = $defaults[$key];
        }

        return apply_filters('earlystart_global_setting', $value, $key, $settings);
}

/**
 * Global Phone Helper
 */
function earlystart_global_phone()
{
        return earlystart_get_global_setting('global_phone', '');
}

/**
 * Global Email Helper
 */
function earlystart_global_email()
{
        return earlystart_get_global_setting('global_email', '');
}

/**
 * Global Tour Email Helper
 */
function earlystart_global_tour_email()
{
        return earlystart_get_global_setting('global_tour_email', earlystart_global_email());
}

/**
 * Global Full Address Helper
 */
function earlystart_global_full_address()
{
        $address = earlystart_get_global_setting('global_address', '');
        $city = earlystart_get_global_setting('global_city', '');
        $state = earlystart_get_global_setting('global_state', 'GA');
        $zip = earlystart_get_global_setting('global_zip', '');

        if (!$address) {
                return '';
        }

        return trim(sprintf(
                '%s, %s, %s %s',
                $address,
                $city ?: '',
                $state ?: 'GA',
                $zip ?: ''
        ));
}

/**
 * Global Facebook URL
 */
function earlystart_global_facebook_url()
{
        return earlystart_get_global_setting('global_facebook_url', '');
}

/**
 * Global Instagram URL
 */
function earlystart_global_instagram_url()
{
        return earlystart_get_global_setting('global_instagram_url', '');
}

/**
 * Global LinkedIn URL
 */
function earlystart_global_linkedin_url()
{
        return earlystart_get_global_setting('global_linkedin_url', '');
}

/**
 * Global SEO Default Title
 */
function earlystart_global_seo_default_title()
{
        return earlystart_get_global_setting('global_seo_default_title', get_bloginfo('name'));
}

/**
 * Global SEO Default Description
 */
function earlystart_global_seo_default_description()
{
        return earlystart_get_global_setting('global_seo_default_description', get_bloginfo('description'));
}


