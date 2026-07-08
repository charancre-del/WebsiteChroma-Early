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
 * Recursively normalize legacy strings inside option arrays.
 *
 * @param mixed $value Stored option value.
 * @return mixed
 */
function earlystart_normalize_launch_content_value($value)
{
    if (is_string($value)) {
        return earlystart_normalize_legacy_branding_text($value);
    }

    if (is_array($value)) {
        foreach ($value as $key => $item) {
            $value[$key] = earlystart_normalize_launch_content_value($item);
        }
    }

    return $value;
}

/**
 * New service cards added after launch.
 *
 * @return array<string,array<string,mixed>>
 */
function earlystart_launch_service_expansion_cards(): array
{
    return array(
        'autism-diagnosis' => array(
            'id' => 'autism-diagnosis',
            'title' => 'Autism Diagnosis',
            'subtitle' => 'Diagnostic Evaluation',
            'icon' => 'clipboard-check',
            'heading' => 'Clear Answers for Next Steps',
            'description' => 'Comprehensive autism diagnostic evaluations help families understand developmental needs and plan the right care pathway.',
            'image' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?auto=format&fit=crop&w=800&q=80&fm=webp',
            'bullets' => array('Developmental history review', 'Standardized diagnostic tools', 'Care planning guidance'),
        ),
        'behavioral-health' => array(
            'id' => 'behavioral-health',
            'title' => 'Behavioral Health',
            'subtitle' => 'Mental & Emotional Support',
            'icon' => 'heart-pulse',
            'heading' => 'Whole-Child Behavioral Support',
            'description' => 'Behavioral health services support emotional regulation, coping skills, family routines, and coordinated care for children and caregivers.',
            'image' => 'https://images.unsplash.com/photo-1536640712-4d4c36ff0e4e?auto=format&fit=crop&w=800&q=80&fm=webp',
            'bullets' => array('Emotional regulation support', 'Family-centered care plans', 'Coordinated clinical guidance'),
        ),
    );
}

/**
 * Program post definitions for service lines added after launch.
 *
 * @return array<string,array<string,mixed>>
 */
function earlystart_launch_service_expansion_programs(): array
{
    return array(
        'autism-diagnosis' => array(
            'title' => 'Autism Diagnosis',
            'excerpt' => 'Comprehensive autism diagnostic evaluations that help families understand needs and next steps.',
            'meta' => array(
                'program_icon' => 'clipboard-check',
                'program_age_range' => '18mo - 12y',
                'program_color_scheme' => 'rose',
                'program_hero_title' => 'Clear Answers for Next Steps.',
                'program_hero_description' => 'Our diagnostic evaluation pathway helps families understand developmental needs, document clinical findings, and plan the right support.',
                'program_prism_title' => 'Diagnostic Core',
                'program_prism_physical' => 20,
                'program_prism_emotional' => 80,
                'program_prism_social' => 75,
                'program_prism_academic' => 60,
                'program_prism_creative' => 40,
            ),
        ),
        'behavioral-health' => array(
            'title' => 'Behavioral Health',
            'excerpt' => 'Behavioral health support for emotional regulation, coping skills, and family-centered care.',
            'meta' => array(
                'program_icon' => 'heart-pulse',
                'program_age_range' => '2y - 12y',
                'program_color_scheme' => 'orange',
                'program_hero_title' => 'Whole-Child Behavioral Support.',
                'program_hero_description' => 'Our behavioral health services support emotional regulation, coping skills, caregiver guidance, and coordinated care for children and families.',
                'program_prism_title' => 'Behavioral Health Core',
                'program_prism_physical' => 25,
                'program_prism_emotional' => 95,
                'program_prism_social' => 80,
                'program_prism_academic' => 45,
                'program_prism_creative' => 55,
            ),
        ),
    );
}

/**
 * Add newly confirmed service lines without overwriting admin-customized content.
 */
function earlystart_apply_service_expansion_migration(): void
{
    foreach (earlystart_launch_service_expansion_programs() as $slug => $data) {
        $post = get_page_by_path($slug, OBJECT, 'program');
        $post_id = $post ? (int) $post->ID : 0;

        if (!$post_id) {
            $post_id = wp_insert_post(array(
                'post_title' => $data['title'],
                'post_name' => $slug,
                'post_excerpt' => $data['excerpt'],
                'post_status' => 'publish',
                'post_type' => 'program',
            ));
        }

        if (!$post_id || is_wp_error($post_id)) {
            continue;
        }

        foreach ($data['meta'] as $meta_key => $meta_value) {
            if (get_post_meta($post_id, $meta_key, true) === '') {
                update_post_meta($post_id, $meta_key, $meta_value);
            }
        }
    }

    $home_id = (int) get_option('page_on_front');
    if ($home_id > 0) {
        $raw = get_post_meta($home_id, 'home_services_json', true);
        $services = is_string($raw) && $raw !== '' ? json_decode($raw, true) : array();

        if (is_array($services)) {
            $seen = array();
            foreach ($services as $service) {
                if (is_array($service) && !empty($service['id'])) {
                    $seen[(string) $service['id']] = true;
                }
            }

            $changed = false;
            foreach (earlystart_launch_service_expansion_cards() as $service_id => $card) {
                if (empty($seen[$service_id])) {
                    $services[] = $card;
                    $changed = true;
                }
            }

            if ($changed) {
                update_post_meta($home_id, 'home_services_json', wp_json_encode($services));
            }
        }
    }

    $old_special_programs = "ABA Therapy\nSpeech Therapy\nOccupational Therapy\nParent Coaching";
    $new_special_programs = "Autism Diagnosis\nABA Therapy\nBehavioral Health\nSpeech Therapy\nOccupational Therapy\nParent Coaching";
    $location_ids = get_posts(array(
        'post_type' => 'location',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'post_status' => 'any',
        'meta_key' => 'location_special_programs',
        'meta_value' => $old_special_programs,
    ));

    foreach ((array) $location_ids as $location_id) {
        update_post_meta((int) $location_id, 'location_special_programs', $new_special_programs);
    }
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

    $version = '2026-07-08.5';
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

    $option_rows = $wpdb->get_results(
        "SELECT option_name, option_value FROM {$wpdb->options}
        WHERE option_name IN ('blogname', 'blogdescription')
        OR option_name LIKE 'theme\\_mods\\_%'
        OR option_name LIKE 'earlystart\\_%'"
    );

    foreach ((array) $option_rows as $row) {
        $before = maybe_unserialize($row->option_value);
        $after = earlystart_normalize_launch_content_value($before);

        if ($after !== $before) {
            update_option($row->option_name, $after);
        }
    }

    earlystart_apply_service_expansion_migration();

    update_option('earlystart_launch_content_cleanup_version', $version, false);
}
add_action('admin_init', 'earlystart_run_launch_content_cleanup', 30);
