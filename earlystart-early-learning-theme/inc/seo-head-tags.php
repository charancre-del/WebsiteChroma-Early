<?php
/**
 * SEO Head Tags (Theme-owned renderer)
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Resolve best social share image.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function earlystart_get_social_share_image_url($post_id = 0)
{
    $post_id = absint($post_id);

    if ($post_id && has_post_thumbnail($post_id)) {
        $featured = get_the_post_thumbnail_url($post_id, 'full');
        if (!empty($featured)) {
            return $featured;
        }
    }

    $default_image = get_theme_mod('earlystart_default_og_image', '');
    if (!empty($default_image)) {
        return $default_image;
    }

    if (function_exists('earlystart_get_global_setting')) {
        $logo = earlystart_get_global_setting('global_logo', '');
        if (!empty($logo)) {
            return $logo;
        }
    }

    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo = wp_get_attachment_image_url($custom_logo_id, 'full');
        if (!empty($logo)) {
            return $logo;
        }
    }

    return '';
}

/**
 * Build one normalized meta description value.
 *
 * @return string
 */
function earlystart_get_meta_description_value()
{
    $post_id = is_singular() ? get_queried_object_id() : 0;

    if ($post_id) {
        if (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish()) {
            $es_manual = get_post_meta($post_id, '_earlystart_es_meta_description', true);
            if (!empty($es_manual)) {
                return wp_trim_words(wp_strip_all_tags($es_manual), 32, '...');
            }
        }

        $manual = get_post_meta($post_id, 'meta_description', true);
        if (!empty($manual)) {
            return wp_trim_words(wp_strip_all_tags($manual), 32, '...');
        }

        if (has_excerpt($post_id)) {
            return wp_trim_words(wp_strip_all_tags(get_the_excerpt($post_id)), 32, '...');
        }
    }

    if (is_archive()) {
        $archive_desc = get_the_archive_description();
        if (!empty($archive_desc)) {
            return wp_trim_words(wp_strip_all_tags($archive_desc), 32, '...');
        }
    }

    $blog_desc = get_bloginfo('description');
    if (!empty($blog_desc)) {
        return wp_trim_words(wp_strip_all_tags($blog_desc), 32, '...');
    }

    return 'Chroma Early Start provides pediatric therapy services across Metro Atlanta, including ABA, speech, and occupational therapy.';
}

/**
 * Output one consistent social/meta set.
 */
function earlystart_render_social_meta_tags()
{
    static $did_output = false;
    if ($did_output || is_admin() || is_feed() || is_robots() || is_trackback()) {
        return;
    }
    $did_output = true;

    $post_id = is_singular() ? get_queried_object_id() : 0;
    $title = wp_get_document_title();
    $description = earlystart_get_meta_description_value();
    $image = earlystart_get_social_share_image_url($post_id);

    if ($post_id) {
        $url = get_permalink($post_id);
    } elseif (is_front_page() || is_home()) {
        $url = home_url('/');
    } elseif (is_post_type_archive()) {
        $url = get_post_type_archive_link(get_query_var('post_type')) ?: home_url(add_query_arg(array(), $GLOBALS['wp']->request ?? ''));
    } elseif (is_tax() || is_category() || is_tag()) {
        $term = get_queried_object();
        $url = $term ? get_term_link($term) : home_url('/');
        if (is_wp_error($url)) {
            $url = home_url('/');
        }
    } else {
        global $wp;
        $url = home_url(isset($wp->request) ? '/' . ltrim((string) $wp->request, '/') : '/');
    }

    $og_type = is_singular('post') ? 'article' : 'website';

    $twitter_site = get_theme_mod('earlystart_twitter_site', '');
    if (!empty($twitter_site)) {
        $twitter_site = '@' . ltrim((string) $twitter_site, '@');
    }

    echo "\n<!-- EarlyStart Theme SEO Head -->\n";
    echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";

    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";

    if (!empty($image)) {
        echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
        echo '<meta property="og:image:width" content="1200" />' . "\n";
        echo '<meta property="og:image:height" content="630" />' . "\n";
    }

    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";

    if (!empty($image)) {
        echo '<meta name="twitter:image" content="' . esc_url($image) . '" />' . "\n";
    }

    if (!empty($twitter_site)) {
        echo '<meta name="twitter:site" content="' . esc_attr($twitter_site) . '" />' . "\n";
    }
}
