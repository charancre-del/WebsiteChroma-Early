<?php
/**
 * One-time launch content cleanup for stale pre-therapy branding.
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Normalize saved legacy content when an administrator opens wp-admin.
 *
 * This intentionally runs in wp-admin only. Front-end requests should not be
 * responsible for database cleanup, and public output is already normalized by
 * the SEO head orchestrator as a last-resort display guard.
 */
function earlystart_run_launch_content_cleanup(): void
{
    if (!is_admin() || wp_doing_ajax() || !current_user_can('manage_options')) {
        return;
    }

    $version = '2026-07-08.2';
    if (get_option('earlystart_launch_content_cleanup_version') === $version) {
        return;
    }

    if (!function_exists('earlystart_normalize_legacy_branding_text')) {
        return;
    }

    global $wpdb;

    $post_types = array('page', 'post', 'location', 'program', 'city', 'team_member', 'career');
    $post_ids = $wpdb->get_col(
        "SELECT ID FROM {$wpdb->posts} WHERE post_type IN ('" . implode("','", array_map('esc_sql', $post_types)) . "')"
    );

    foreach ((array) $post_ids as $post_id) {
        $post_id = (int) $post_id;
        $post = get_post($post_id);
        if (!$post) {
            continue;
        }

        $updates = array('ID' => $post_id);
        foreach (array('post_title', 'post_content', 'post_excerpt') as $field) {
            $before = (string) $post->{$field};
            $after = earlystart_normalize_legacy_branding_text($before);
            if ($after !== $before) {
                $updates[$field] = $after;
            }
        }

        if (count($updates) > 1) {
            wp_update_post(wp_slash($updates));
        }
    }

    $meta_keys = array(
        '_yoast_wpseo_title',
        '_yoast_wpseo_metadesc',
        'seo_llm_title',
        'seo_llm_description',
        'program_meta_title',
        'program_seo_heading',
        'location_tagline',
        'location_description',
        'location_hero_subtitle',
        'location_seo_content_title',
        'location_seo_content_text',
        'contact_hero_title',
        'careers_hero_title',
        'locations_hero_heading',
        'parents_hero_heading',
    );

    $placeholders = implode(',', array_fill(0, count($meta_keys), '%s'));
    $meta_rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key IN ($placeholders)",
            $meta_keys
        )
    );

    foreach ((array) $meta_rows as $row) {
        $before = maybe_unserialize($row->meta_value);
        if (!is_string($before) || $before === '') {
            continue;
        }

        $after = earlystart_normalize_legacy_branding_text($before);
        if ($after !== $before) {
            update_metadata_by_mid('post', (int) $row->meta_id, wp_slash($after));
        }
    }

    update_option('earlystart_launch_content_cleanup_version', $version, false);
}
add_action('admin_init', 'earlystart_run_launch_content_cleanup', 30);

