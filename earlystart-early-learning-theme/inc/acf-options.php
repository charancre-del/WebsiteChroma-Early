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
 * Legacy options helpers (works without ACF)
 * Now registered via inc/theme-settings.php
 */

/**
 * Global settings helper
 */
function earlystart_get_global_setting($key, $default = '')
{
        $defaults = array(
                'global_phone' => '(404) 905-6775',
                'global_email' => 'intake@chromaela.com',
                'global_tour_email' => 'intake@chromaela.com',
                'global_admissions_email' => 'intake@chromaela.com',
                'global_careers_email' => 'intake@chromaela.com',
                'global_billing_email' => 'intake@chromaela.com',
                'global_media_email' => 'intake@chromaela.com',
                'global_privacy_email' => 'intake@chromaela.com',
                'global_address' => '3554 Old Milton Pkwy',
                'global_city' => 'Alpharetta',
                'global_state' => 'GA',
                'global_zip' => '30005',
                'global_facebook_url' => 'https://facebook.com/chromaearlystart',
                'global_instagram_url' => 'https://instagram.com/chromaearlystart',
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

        if (earlystart_is_placeholder_global_setting($key, $value) && isset($defaults[$key])) {
                $value = $defaults[$key];
        }

        return apply_filters('earlystart_global_setting', $value, $key, $settings);
}

/**
 * Guard public templates from stale demo contact values stored in options.
 */
function earlystart_is_placeholder_global_setting($key, $value)
{
        $value = trim((string) $value);

        if ('' === $value) {
                return false;
        }

        if (false !== strpos($key, 'phone')) {
                $digits = preg_replace('/\D+/', '', $value);
                return in_array($digits, array('5551234567', '4045550199', '4708353263', '14708353263'), true)
                        || 0 === strpos($digits, '555');
        }

        if (false !== strpos($key, 'email')) {
                $email = strtolower($value);
                return 'hello@chromaearlystart.com' === $email
                        || false !== strpos($email, 'chromaearlystart.com')
                        || false !== strpos($email, 'chromaearlylearning.com')
                        || false !== strpos($email, 'earlystarttherapy.com');
        }

        if (
                in_array($key, array('global_address', 'global_city', 'global_state', 'global_zip'), true)
                || false !== strpos($key, 'address')
                || false !== strpos($key, 'city')
                || false !== strpos($key, 'state')
                || false !== strpos($key, 'zip')
        ) {
                $normalized = strtolower($value);
                return false !== strpos($normalized, '123 wellness blvd')
                        || false !== strpos($normalized, 'therapy city')
                        || 'st' === $normalized
                        || '12345' === $normalized;
        }

        return false;
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
 * Global Admissions Email Helper
 */
function earlystart_global_admissions_email()
{
        return earlystart_get_global_setting('global_admissions_email', earlystart_global_email());
}

/**
 * Global Careers Email Helper
 */
function earlystart_global_careers_email()
{
        return earlystart_get_global_setting('global_careers_email', earlystart_global_email());
}

/**
 * Global Billing Email Helper
 */
function earlystart_global_billing_email()
{
        return earlystart_get_global_setting('global_billing_email', earlystart_global_email());
}

/**
 * Global Media Email Helper
 */
function earlystart_global_media_email()
{
        return earlystart_get_global_setting('global_media_email', earlystart_global_email());
}

/**
 * Global Privacy Email Helper
 */
function earlystart_global_privacy_email()
{
        return earlystart_get_global_setting('global_privacy_email', earlystart_global_email());
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


