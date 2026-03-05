<?php
/**
 * Dynamic Link Generator
 * Generates URLs from slugs to prevent hardcoded redirect chains
 * 
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Normalize internal URLs to canonical paths.
 *
 * @param string $url URL to normalize.
 * @return string
 */
function earlystart_normalize_internal_url($url)
{
    $url = trim((string) $url);
    if ($url === '') {
        return $url;
    }

    if (strpos($url, '#') === 0) {
        return $url;
    }

    $home = home_url('/');
    $home_parts = wp_parse_url($home);
    $url_parts = wp_parse_url($url);

    if (empty($url_parts)) {
        return $url;
    }

    $is_relative = empty($url_parts['host']);
    $is_internal = $is_relative;

    if (!$is_internal && !empty($url_parts['host']) && !empty($home_parts['host'])) {
        $is_internal = (strtolower($url_parts['host']) === strtolower($home_parts['host']));
    }

    if (!$is_internal) {
        return $url;
    }

    $path = isset($url_parts['path']) ? '/' . ltrim((string) $url_parts['path'], '/') : '/';
    $path_no_slash = untrailingslashit($path);

    $legacy_map = array(
        '/programs/aba' => '/programs/aba-therapy/',
        '/programs/speech' => '/programs/speech-therapy/',
    );

    if (isset($legacy_map[$path_no_slash])) {
        $path = $legacy_map[$path_no_slash];
    } elseif (!preg_match('/\\.[a-zA-Z0-9]+$/', $path_no_slash)) {
        $path = trailingslashit($path_no_slash);
    }

    $normalized = rtrim($home, '/') . $path;

    if (!empty($url_parts['query'])) {
        $normalized .= '?' . $url_parts['query'];
    }

    if (!empty($url_parts['fragment'])) {
        $normalized .= '#' . $url_parts['fragment'];
    }

    return $normalized;
}

/**
 * Get permalink by post slug
 * 
 * @param string $slug The post/page slug
 * @param string|array $post_type Optional post type(s) (default: 'page')
 * @return string|false The permalink or false if not found
 */
function earlystart_get_link_by_slug($slug, $post_type = 'page')
{
    // Clean the slug
    $slug = trim($slug, '/');

    // Handle array of post types
    if (!is_array($post_type)) {
        $post_type = array($post_type);
    }

    // Try to find the post
    $post = get_page_by_path($slug, OBJECT, $post_type);

    if ($post) {
        return earlystart_normalize_internal_url(get_permalink($post));
    }

    return false;
}

/**
 * Get program page link by slug
 * 
 * @param string $slug Program slug (e.g., 'preschool', 'ga-pre-k', 'infant-care')
 * @return string The program permalink or fallback URL
 */
function earlystart_get_program_link($slug)
{
    // Clean the slug
    $slug = trim($slug, '/');

    $aliases = array(
        'aba' => 'aba-therapy',
        'speech' => 'speech-therapy',
        'ot' => 'occupational-therapy',
        'occupational' => 'occupational-therapy',
        'bridge' => 'bridge-program',
    );
    $slug = $aliases[$slug] ?? $slug;

    // Try program CPT first
    $post = get_page_by_path($slug, OBJECT, 'program');
    if ($post) {
        return earlystart_normalize_internal_url(get_permalink($post));
    }

    // Try as a page under /programs/
    $post = get_page_by_path('programs/' . $slug, OBJECT, 'page');
    if ($post) {
        return earlystart_normalize_internal_url(get_permalink($post));
    }

    // Fallback to constructed URL with trailing slash
    return earlystart_normalize_internal_url(home_url('/programs/' . $slug . '/'));
}

/**
 * Get location page link by slug
 * 
 * @param string $slug Location slug
 * @return string The location permalink or fallback URL
 */
function earlystart_get_location_link($slug)
{
    // Clean the slug
    $slug = trim($slug, '/');

    // Try location CPT first
    $post = get_page_by_path($slug, OBJECT, 'location');
    if ($post) {
        return earlystart_normalize_internal_url(get_permalink($post));
    }

    // Try as a page under /locations/
    $post = get_page_by_path('locations/' . $slug, OBJECT, 'page');
    if ($post) {
        return earlystart_normalize_internal_url(get_permalink($post));
    }

    // Fallback to constructed URL with trailing slash
    return earlystart_normalize_internal_url(home_url('/locations/' . $slug . '/'));
}

/**
 * Smart link - tries to find the correct URL for any slug
 * This is the primary function to use for dynamic linking
 * 
 * @param string $slug Any page/post/CPT slug
 * @return string The permalink or home_url fallback
 */
function earlystart_smart_link($slug)
{
    // Remove leading/trailing slashes
    $slug = trim($slug, '/');

    // If empty, return home
    if (empty($slug)) {
        return earlystart_normalize_internal_url(home_url('/'));
    }

    // Define post types to search
    $post_types = array('page', 'post', 'program', 'location', 'city');

    // Check if it's a nested path like "programs/preschool"
    if (strpos($slug, '/') !== false) {
        $post = get_page_by_path($slug, OBJECT, $post_types);
        if ($post) {
            return earlystart_normalize_internal_url(get_permalink($post));
        }

        // Try just the last part of the slug
        $parts = explode('/', $slug);
        $last_slug = end($parts);
        $post = get_page_by_path($last_slug, OBJECT, $post_types);
        if ($post) {
            return earlystart_normalize_internal_url(get_permalink($post));
        }
    }

    // Try standard pages first
    $post = get_page_by_path($slug, OBJECT, $post_types);
    if ($post) {
        return earlystart_normalize_internal_url(get_permalink($post));
    }

    // Fallback to home_url with trailing slash
    return earlystart_normalize_internal_url(trailingslashit(home_url('/' . $slug)));
}

/**
 * Get page link by common name (alias mapping)
 * Maps common/legacy names to actual slugs
 * 
 * @param string $name Common name like 'contact', 'about', etc.
 * @return string The permalink
 */
function earlystart_get_page_link($name)
{
    // Define common aliases for pages that may have changed slugs
    $aliases = array(
        'about' => 'about-us',
        'about-us' => 'about-us',
        'contact' => 'contact',
        'contact-us' => 'contact', // Map legacy to new
        'consultation' => 'contact',
        'schedule-a-tour' => 'contact',
        'locations' => 'locations',
        'location' => 'locations',
        'services' => 'programs',
        'service' => 'programs',
        'programs' => 'programs',
        'program' => 'programs',
        'families' => 'for-families',
        'parents' => 'for-families',
        'for-families' => 'for-families',
        'careers' => 'careers',
        'faq' => 'faq',
        'privacy-policy' => 'privacy-policy',
        'terms' => 'terms',
        'terms-of-use' => 'terms',
        'hipaa' => 'hipaa',
        'preschool' => 'programs/preschool',
        'ga-pre-k' => 'programs/ga-pre-k',
        'infant-care' => 'programs/infant-care',
        'toddler-care' => 'programs/toddler-care',
        'pre-k-prep' => 'programs/pre-k-prep',
        'after-school' => 'programs/after-school',
        'parents-day-out' => 'programs/parents-day-out',
        'camp-summer-winter-fall' => 'programs/camp-summer-winter-fall',
    );

    // Check if this is an aliased name
    $slug = isset($aliases[$name]) ? $aliases[$name] : $name;

    return earlystart_smart_link($slug);
}

/**
 * Shortcode for dynamic links in content
 * Usage: [earlystart_link slug="contact-us"]Contact Us[/earlystart_link]
 * 
 * @param array $atts Shortcode attributes
 * @param string $content Shortcode content (link text)
 * @return string The HTML link
 */
function earlystart_dynamic_link_shortcode($atts, $content = null)
{
    $atts = shortcode_atts(array(
        'slug' => '',
        'class' => '',
        'id' => '',
        'target' => '',
        'rel' => '',
    ), $atts, 'earlystart_link');

    if (empty($atts['slug'])) {
        return $content;
    }

    $url = earlystart_smart_link($atts['slug']);

    // Build attributes
    $link_atts = array();
    $link_atts[] = 'href="' . esc_url($url) . '"';

    if (!empty($atts['class'])) {
        $link_atts[] = 'class="' . esc_attr($atts['class']) . '"';
    }
    if (!empty($atts['id'])) {
        $link_atts[] = 'id="' . esc_attr($atts['id']) . '"';
    }
    if (!empty($atts['target'])) {
        $link_atts[] = 'target="' . esc_attr($atts['target']) . '"';
    }
    if (!empty($atts['rel'])) {
        $link_atts[] = 'rel="' . esc_attr($atts['rel']) . '"';
    }

    return '<a ' . implode(' ', $link_atts) . '>' . do_shortcode($content) . '</a>';
}
add_shortcode('earlystart_link', 'earlystart_dynamic_link_shortcode');

/**
 * Helper to check if a URL needs updating (points to a redirect)
 * 
 * @param string $url URL to check
 * @return bool True if URL might need updating
 */
function earlystart_url_needs_update($url)
{
    // List of known old URL patterns that redirect
    $redirect_patterns = array(
        // '/contact$' => true,       // DISABLED: /contact is canonical
        '/preschool/' => true,     // /preschool/ → /programs/preschool/
        '/ga-pre-k/' => true,      // /ga-pre-k/ → /programs/ga-pre-k/
        '/infant-care/' => true,   // etc.
        '/toddler-care/' => true,
        '/pre-k-prep/' => true,
        '/after-school/' => true,
        '/parents-day-out/' => true,
        '/camp-summer-winter-fall/' => true,
    );

    foreach ($redirect_patterns as $pattern => $value) {
        if (preg_match('#' . $pattern . '#', $url)) {
            return true;
        }
    }

    return false;
}

/**
 * Helper to generate localized URL
 * Wraps home_url() so it can be filtered by the plugin for Spanish routing
 * 
 * @param string $path Path relative to home
 * @return string Full URL
 */
if (!function_exists('earlystart_url')) {
    function earlystart_url($path = '/')
    {
        return home_url($path);
    }
}


