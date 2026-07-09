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
        'autism-assessment' => array(
            'id' => 'autism-assessment',
            'title' => 'Autism Assessment',
            'subtitle' => 'Developmental Assessment',
            'icon' => 'clipboard-check',
            'heading' => 'Clear Answers for Next Steps',
            'description' => 'Comprehensive autism assessments help families understand developmental needs and plan the right care pathway.',
            'image' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?auto=format&fit=crop&w=800&q=80&fm=webp',
            'bullets' => array('Developmental history review', 'Standardized assessment tools', 'Care planning guidance'),
        ),
        'behavioral-assessment' => array(
            'id' => 'behavioral-assessment',
            'title' => 'Behavioral Assessment',
            'subtitle' => 'Behavior Support Review',
            'icon' => 'heart-pulse',
            'heading' => 'Whole-Child Behavioral Assessment',
            'description' => 'Behavioral assessments help identify strengths, barriers, regulation needs, family routines, and the right support plan for children and caregivers.',
            'image' => 'https://images.unsplash.com/photo-1536640712-4d4c36ff0e4e?auto=format&fit=crop&w=800&q=80&fm=webp',
            'bullets' => array('Behavior pattern review', 'Family-centered recommendations', 'Coordinated next steps'),
        ),
        'adhd-assessment' => array(
            'id' => 'adhd-assessment',
            'title' => 'ADHD Assessment',
            'subtitle' => 'Attention & Executive Function',
            'icon' => 'activity',
            'heading' => 'Clarity Around Attention and Regulation',
            'description' => 'ADHD assessments help families understand attention, executive function, impulsivity, and regulation needs so care planning can be more precise.',
            'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=80&fm=webp',
            'bullets' => array('Attention and regulation review', 'Executive function profile', 'Practical care recommendations'),
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
        'autism-assessment' => array(
            'title' => 'Autism Assessment',
            'excerpt' => 'Comprehensive autism assessments that help families understand needs and next steps.',
            'meta' => array(
                'program_icon' => 'clipboard-check',
                'program_age_range' => '18mo - 12y',
                'program_color_scheme' => 'rose',
                'program_hero_title' => 'Clear Answers for Next Steps.',
                'program_hero_description' => 'Our assessment pathway helps families understand developmental needs, document clinical findings, and plan the right support.',
                'program_prism_title' => 'Assessment Core',
                'program_prism_physical' => 20,
                'program_prism_emotional' => 80,
                'program_prism_social' => 75,
                'program_prism_academic' => 60,
                'program_prism_creative' => 40,
            ),
        ),
        'behavioral-assessment' => array(
            'title' => 'Behavioral Assessment',
            'excerpt' => 'Behavioral assessments for regulation, behavior patterns, and family-centered next steps.',
            'meta' => array(
                'program_icon' => 'heart-pulse',
                'program_age_range' => '2y - 12y',
                'program_color_scheme' => 'orange',
                'program_hero_title' => 'Whole-Child Behavioral Assessment.',
                'program_hero_description' => 'Our behavioral assessment services review regulation, coping skills, caregiver concerns, and coordinated next steps for children and families.',
                'program_prism_title' => 'Behavioral Assessment Core',
                'program_prism_physical' => 25,
                'program_prism_emotional' => 95,
                'program_prism_social' => 80,
                'program_prism_academic' => 45,
                'program_prism_creative' => 55,
            ),
        ),
        'adhd-assessment' => array(
            'title' => 'ADHD Assessment',
            'excerpt' => 'ADHD assessments for attention, executive function, impulsivity, and regulation needs.',
            'meta' => array(
                'program_icon' => 'activity',
                'program_age_range' => '4y - 12y',
                'program_color_scheme' => 'blue',
                'program_hero_title' => 'Clarity Around Attention and Regulation.',
                'program_hero_description' => 'Our ADHD assessment pathway helps families understand attention, executive function, impulsivity, and regulation needs so care planning can be more precise.',
                'program_prism_title' => 'ADHD Assessment Core',
                'program_prism_physical' => 25,
                'program_prism_emotional' => 85,
                'program_prism_social' => 70,
                'program_prism_academic' => 80,
                'program_prism_creative' => 45,
            ),
        ),
    );
}

/**
 * Add newly confirmed service lines without overwriting admin-customized content.
 */
function earlystart_apply_service_expansion_migration(): void
{
    global $wpdb;

    $legacy_program_slugs = array(
        'autism-diagnosis' => array(
            'new_slug' => 'autism-assessment',
            'new_title' => 'Autism Assessment',
        ),
        'behavioral-health' => array(
            'new_slug' => 'behavioral-assessment',
            'new_title' => 'Behavioral Assessment',
        ),
    );

    foreach ($legacy_program_slugs as $old_slug => $rename) {
        $old_post = get_page_by_path($old_slug, OBJECT, 'program');
        $new_post = get_page_by_path($rename['new_slug'], OBJECT, 'program');

        if ($old_post && !$new_post) {
            wp_update_post(array(
                'ID' => (int) $old_post->ID,
                'post_title' => $rename['new_title'],
                'post_name' => $rename['new_slug'],
            ));
        }
    }

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

        wp_update_post(array(
            'ID' => $post_id,
            'post_title' => $data['title'],
            'post_excerpt' => $data['excerpt'],
        ));

        foreach ($data['meta'] as $meta_key => $meta_value) {
            update_post_meta($post_id, $meta_key, $meta_value);
        }
    }

    $home_id = (int) get_option('page_on_front');
    if ($home_id > 0) {
        $raw = get_post_meta($home_id, 'home_services_json', true);
        $services = is_string($raw) && $raw !== '' ? json_decode($raw, true) : array();

        if (is_array($services)) {
            $service_cards = earlystart_launch_service_expansion_cards();
            foreach ($services as $index => $service) {
                if (!is_array($service) || empty($service['id'])) {
                    continue;
                }

                if ((string) $service['id'] === 'autism-diagnosis') {
                    $services[$index] = $service_cards['autism-assessment'];
                }

                if ((string) $service['id'] === 'behavioral-health') {
                    $services[$index] = $service_cards['behavioral-assessment'];
                }
            }

            $seen = array();
            foreach ($services as $service) {
                if (is_array($service) && !empty($service['id'])) {
                    $seen[(string) $service['id']] = true;
                }
            }

            $changed = false;
            foreach ($service_cards as $service_id => $card) {
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

    $copy_updates = array(
        'Specialized ABA, Speech, and Occupational Therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.' => 'Specialized autism assessment, behavioral assessment, ADHD assessment, ABA therapy, speech therapy, and occupational therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.',
        'Specialized autism diagnosis, ABA therapy, behavioral health, speech therapy, and occupational therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.' => 'Specialized autism assessment, behavioral assessment, ADHD assessment, ABA therapy, speech therapy, and occupational therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.',
        'Integrating speech, OT, and ABA for holistic outcomes.' => 'Integrating assessment, speech, OT, and ABA for holistic outcomes.',
        'Integrating diagnosis, behavioral health, speech, OT, and ABA for holistic outcomes.' => 'Integrating assessment, speech, OT, and ABA for holistic outcomes.',
        'Our team includes licensed and board-certified professionals across ABA, speech, and occupational therapy disciplines.' => 'Our team includes licensed and board-certified professionals across autism assessment, behavioral assessment, ADHD assessment, ABA, speech, and occupational therapy disciplines.',
        'Our team includes licensed and board-certified professionals across autism diagnosis, ABA, behavioral health, speech, and occupational therapy disciplines.' => 'Our team includes licensed and board-certified professionals across autism assessment, behavioral assessment, ADHD assessment, ABA, speech, and occupational therapy disciplines.',
        'Find a clinic near you and schedule a tour for ABA, Speech, or OT.' => 'Find a clinic near you and schedule a tour for autism assessment, behavioral assessment, ADHD assessment, ABA, speech, or OT.',
        'Find a clinic near you and schedule a tour for autism diagnosis, ABA, behavioral health, speech, or OT.' => 'Find a clinic near you and schedule a tour for autism assessment, behavioral assessment, ADHD assessment, ABA, speech, or OT.',
        'ABA, Speech, and OT goals are synchronized in one clinical roadmap. No conflicting adviceâ€”just one unified team.' => 'Assessment, ABA, speech, and OT goals are synchronized in one clinical roadmap. No conflicting adviceâ€”just one unified team.',
        'Diagnosis, ABA, behavioral health, speech, and OT goals are synchronized in one clinical roadmap. No conflicting adviceâ€”just one unified team.' => 'Assessment, ABA, speech, and OT goals are synchronized in one clinical roadmap. No conflicting adviceâ€”just one unified team.',
        'Navigating early intervention can be overwhelming. We help families understand insurance, intake, diagnosis, and next steps with clarity and compassion.' => 'Navigating early intervention can be overwhelming. We help families understand insurance, intake, assessment, and next steps with clarity and compassion.',
        'Navigating early intervention can be overwhelming. We are here to guide you through insurance, diagnosis, and the first steps of therapy with clarity and compassion. We partner with you to unlock your child\'s potential.' => 'Navigating early intervention can be overwhelming. We are here to guide you through insurance, assessment, and the first steps of therapy with clarity and compassion. We partner with you to unlock your child\'s potential.',
    );

    foreach ($copy_updates as $before => $after) {
        $meta_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT meta_id FROM {$wpdb->postmeta} WHERE meta_value = %s",
                $before
            )
        );

        foreach ((array) $meta_ids as $meta_id) {
            update_metadata_by_mid('post', (int) $meta_id, wp_slash($after));
        }
    }

    $old_special_programs = "ABA Therapy\nSpeech Therapy\nOccupational Therapy\nParent Coaching";
    $new_special_programs = "Autism Assessment\nBehavioral Assessment\nADHD Assessment\nABA Therapy\nSpeech Therapy\nOccupational Therapy\nParent Coaching";
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

    $version = '2026-07-08.7';
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
