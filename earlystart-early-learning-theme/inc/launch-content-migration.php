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
 * Default hero images for assessment program pages.
 *
 * @return array<string,string>
 */
function earlystart_launch_assessment_program_images(): array
{
    return array(
        'autism-assessment' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?auto=format&fit=crop&w=1200&q=80&fm=webp',
        'behavioral-assessment' => 'https://images.unsplash.com/photo-1536640712-4d4c36ff0e4e?auto=format&fit=crop&w=1200&q=80&fm=webp',
        'adhd-assessment' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1200&q=80&fm=webp',
        'ot-assessment' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=1200&q=80&fm=webp',
    );
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
        return earlystart_normalize_childcare_era_content_text(earlystart_normalize_legacy_branding_text($value));
    }

    if (is_array($value)) {
        foreach ($value as $key => $item) {
            $value[$key] = earlystart_normalize_launch_content_value($item);
        }
    }

    return $value;
}

/**
 * Normalize stale childcare-era public wording.
 *
 * This is intentionally conservative and phrase-based. It removes the old
 * childcare/preschool/tour identity while preserving valid pediatric therapy
 * language such as children, families, early intervention, and school support.
 *
 * @param string $text Stored text.
 * @return string
 */
function earlystart_normalize_childcare_era_content_text($text)
{
    if (!is_string($text) || $text === '') {
        return $text;
    }

    $replacements = array(
        'Austim Diagnosis' => 'Autism Assessment',
        'Austim diagnosis' => 'autism assessment',
        'Austim' => 'Autism',
        'autism diagnosis' => 'autism assessment',
        'Autism Diagnosis' => 'Autism Assessment',
        'behavioral diagnosis' => 'behavioral assessment',
        'Behavioral Diagnosis' => 'Behavioral Assessment',
        'behavioral health' => 'behavioral assessment',
        'Behavioral Health' => 'Behavioral Assessment',
        'adhd diagnosis' => 'ADHD assessment',
        'ADHD diagnosis' => 'ADHD assessment',
        'ADHD Diagnosis' => 'ADHD Assessment',
        'Chroma Early Learning Academy' => 'Chroma Early Start',
        'Chroma Early Learning' => 'Chroma Early Start',
        'Early Learning Academy' => 'Chroma Early Start',
        'Early Start Early Learning' => 'Chroma Early Start',
        'Early Start Preschool' => 'Chroma Early Start',
        'Licensed Childcare Provider' => 'Pediatric Therapy Provider',
        'Licensed Childcare Center' => 'Pediatric Therapy Clinic',
        'Licensed Childcare' => 'Pediatric Therapy',
        'Licensed Child Care Center' => 'Pediatric Therapy Clinic',
        'Licensed Child Care' => 'Pediatric Therapy',
        'Childcare Centers' => 'Pediatric Therapy Clinics',
        'Childcare Center' => 'Pediatric Therapy Clinic',
        'childcare centers' => 'pediatric therapy clinics',
        'childcare center' => 'pediatric therapy clinic',
        'childcare provider' => 'pediatric therapy provider',
        'childcare' => 'pediatric therapy',
        'Childcare' => 'Pediatric Therapy',
        'child care center' => 'pediatric therapy clinic',
        'Child Care Center' => 'Pediatric Therapy Clinic',
        'child care' => 'pediatric care',
        'Child Care' => 'Pediatric Care',
        'daycare' => 'pediatric therapy',
        'Daycare' => 'Pediatric Therapy',
        'preschoolers' => 'young children',
        'Preschoolers' => 'Young Children',
        'preschooler' => 'young child',
        'Preschooler' => 'Young Child',
        'preschool' => 'early childhood therapy',
        'Preschool' => 'Early Childhood Therapy',
        'pre k' => 'readiness support',
        'Pre K' => 'Readiness Support',
        'Clinical Pre-K' => 'Clinical Readiness',
        'GA Lottery Pre-K' => 'early intervention support',
        'GA Pre-K' => 'early intervention support',
        'Pre-K Prep' => 'Readiness Support',
        'Pre-K' => 'Readiness Support',
        'Quality Rated' => 'Clinic Quality Reviewed',
        'Bright from the Start: Georgia Department of Early Care and Learning' => 'Chroma Early Start Clinical Quality Team',
        'Bright from the Start' => 'Chroma Early Start',
        'Georgia Department of Early Care and Learning' => 'Chroma Early Start Clinical Quality Team',
        'Georgia DECAL' => 'Chroma Early Start',
        'DECAL' => 'Chroma Early Start',
        'Now Enrolling' => 'Accepting New Families',
        'now enrolling' => 'accepting new families',
        'Schedule a Tour' => 'Request a Consultation',
        'Schedule a tour' => 'Request a consultation',
        'schedule a tour' => 'request a consultation',
        'Book a Tour' => 'Request a Consultation',
        'book a tour' => 'request a consultation',
        'Tour Form' => 'Inquiry Form',
        'tour form' => 'inquiry form',
        'Clinic Tours' => 'Clinical Consultations',
        'clinic tours' => 'clinical consultations',
        'enrollment process' => 'intake process',
        'Enrollment process' => 'Intake process',
        'Enrollment Process' => 'Intake Process',
        'enrollment team' => 'intake team',
        'Enrollment team' => 'Intake team',
        'enrollment specialist' => 'intake specialist',
        'Enrollment specialist' => 'Intake specialist',
        'speak to an enrollment specialist' => 'speak to an intake specialist',
        'Speak to an enrollment specialist' => 'Speak to an intake specialist',
        'tuition contribution' => 'care navigation',
        'tuition' => 'service cost',
        'Tuition' => 'Service Cost',
        'school readiness' => 'daily participation readiness',
        'School readiness' => 'Daily participation readiness',
        'classroom readiness' => 'group participation readiness',
        'Classroom readiness' => 'Group participation readiness',
        'classroom environment' => 'therapy setting',
        'Classroom environment' => 'Therapy setting',
        'classroom routines' => 'group routines',
        'Classroom routines' => 'Group routines',
        'mock classroom' => 'supported group',
        'Mock classroom' => 'Supported group',
        'classroom' => 'group setting',
        'Classroom' => 'Group setting',
        'curriculum' => 'clinical framework',
        'Curriculum' => 'Clinical Framework',
        'teachers' => 'clinicians',
        'Teachers' => 'Clinicians',
        'teacher' => 'clinician',
        'Teacher' => 'Clinician',
        'educators' => 'clinicians',
        'Educators' => 'Clinicians',
        'early learners' => 'young clients',
        'Early learners' => 'Young clients',
        'early learning' => 'pediatric therapy',
        'Early learning' => 'Pediatric therapy',
        'Chroma Chroma Early Start' => 'Chroma Early Start',
    );

    return str_replace(array_keys($replacements), array_values($replacements), $text);
}

/**
 * Determine whether a stored string still contains stale childcare-era language.
 *
 * @param mixed $value Stored value.
 * @return bool
 */
function earlystart_value_contains_childcare_era_language($value): bool
{
    if (is_array($value)) {
        foreach ($value as $item) {
            if (earlystart_value_contains_childcare_era_language($item)) {
                return true;
            }
        }
        return false;
    }

    if (!is_string($value) || $value === '') {
        return false;
    }

    $normalized = strtolower($value);
    foreach (array(
        'chroma early learning',
        'early learning academy',
        'early start preschool',
        'licensed childcare',
        'licensed child care',
        'childcare provider',
        'child care center',
        'childcare center',
        'daycare',
        'preschool',
        'pre-k',
        'ga pre-k',
        'ga lottery pre-k',
        'bright from the start',
        'georgia department of early care and learning',
        'decal',
        'quality rated',
        'now enrolling',
        'schedule a tour',
        'book a tour',
        'tour form',
        'enrollment',
        'tuition',
        'clinical pre-k',
    ) as $term) {
        if (strpos($normalized, $term) !== false) {
            return true;
        }
    }

    return false;
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
        'ot-assessment' => array(
            'id' => 'ot-assessment',
            'title' => 'OT Assessment',
            'subtitle' => 'Sensory & Motor Review',
            'icon' => 'clipboard-list',
            'heading' => 'Understanding Daily Participation',
            'description' => 'OT assessments help families understand sensory processing, fine motor, self-care, regulation, and daily living needs.',
            'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=800&q=80&fm=webp',
            'bullets' => array('Sensory profile review', 'Fine motor and daily living skills', 'Care planning recommendations'),
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
                'program_hero_image' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?auto=format&fit=crop&w=1200&q=80&fm=webp',
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
                'program_hero_image' => 'https://images.unsplash.com/photo-1536640712-4d4c36ff0e4e?auto=format&fit=crop&w=1200&q=80&fm=webp',
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
                'program_hero_image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1200&q=80&fm=webp',
                'program_icon' => 'activity',
                'program_age_range' => '18mo - 12y',
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
        'ot-assessment' => array(
            'title' => 'OT Assessment',
            'excerpt' => 'Occupational therapy assessments for sensory, motor, daily living, and participation needs.',
            'meta' => array(
                'program_hero_image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=1200&q=80&fm=webp',
                'program_icon' => 'clipboard-list',
                'program_age_range' => 'Infants - 12y',
                'program_color_scheme' => 'yellow',
                'program_hero_title' => 'Understanding Daily Participation.',
                'program_hero_description' => 'Our OT assessment pathway reviews sensory processing, fine motor skills, self-care, regulation, and daily living needs so families can plan the right support.',
                'program_prism_title' => 'OT Assessment Core',
                'program_prism_physical' => 85,
                'program_prism_emotional' => 70,
                'program_prism_social' => 55,
                'program_prism_academic' => 55,
                'program_prism_creative' => 65,
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

            $preferred_order = array(
                'autism-assessment' => 10,
                'behavioral-assessment' => 20,
                'adhd-assessment' => 30,
                'ot-assessment' => 40,
                'aba' => 50,
                'speech' => 60,
                'ot' => 70,
                'readiness' => 80,
            );
            usort($services, static function ($left, $right) use ($preferred_order) {
                $left_id = is_array($left) && !empty($left['id']) ? (string) $left['id'] : '';
                $right_id = is_array($right) && !empty($right['id']) ? (string) $right['id'] : '';

                return ($preferred_order[$left_id] ?? 999) <=> ($preferred_order[$right_id] ?? 999);
            });

            $changed = true;

            if ($changed) {
                update_post_meta($home_id, 'home_services_json', wp_json_encode($services));
            }
        }
    }

    $copy_updates = array(
        'Specialized ABA, Speech, and Occupational Therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.' => 'Specialized autism assessment, behavioral assessment, ADHD assessment, OT assessment, ABA therapy, speech therapy, and occupational therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.',
        'Specialized autism assessment, behavioral assessment, ADHD assessment, ABA therapy, speech therapy, and occupational therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.' => 'Specialized autism assessment, behavioral assessment, ADHD assessment, OT assessment, ABA therapy, speech therapy, and occupational therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.',
        'Specialized autism diagnosis, ABA therapy, behavioral health, speech therapy, and occupational therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.' => 'Specialized autism assessment, behavioral assessment, ADHD assessment, OT assessment, ABA therapy, speech therapy, and occupational therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.',
        'Specialized autism diagnosis, ABA therapy, behavioral health, speech therapy, and occupational therapy tailored to your child\\\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.' => 'Specialized autism assessment, behavioral assessment, ADHD assessment, OT assessment, ABA therapy, speech therapy, and occupational therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.',
        'Integrating speech, OT, and ABA for holistic outcomes.' => 'Integrating assessment, OT assessment, speech, OT, and ABA for holistic outcomes.',
        'Integrating diagnosis, behavioral health, speech, OT, and ABA for holistic outcomes.' => 'Integrating assessment, OT assessment, speech, OT, and ABA for holistic outcomes.',
        'Integrating assessment, speech, OT, and ABA for holistic outcomes.' => 'Integrating assessment, OT assessment, speech, OT, and ABA for holistic outcomes.',
        'Our team includes licensed and board-certified professionals across ABA, speech, and occupational therapy disciplines.' => 'Our team includes licensed and board-certified professionals across autism assessment, behavioral assessment, ADHD assessment, OT assessment, ABA, speech, and occupational therapy disciplines.',
        'Our team includes licensed and board-certified professionals across autism diagnosis, ABA, behavioral health, speech, and occupational therapy disciplines.' => 'Our team includes licensed and board-certified professionals across autism assessment, behavioral assessment, ADHD assessment, OT assessment, ABA, speech, and occupational therapy disciplines.',
        'Find a clinic near you and schedule a tour for ABA, Speech, or OT.' => 'Find a clinic near you and request a consultation for autism assessment, behavioral assessment, ADHD assessment, OT assessment, ABA, speech, or OT.',
        'Find a clinic near you and schedule a tour for autism diagnosis, ABA, behavioral health, speech, or OT.' => 'Find a clinic near you and request a consultation for autism assessment, behavioral assessment, ADHD assessment, OT assessment, ABA, speech, or OT.',
        'ABA, Speech, and OT goals are synchronized in one clinical roadmap. No conflicting adviceâ€”just one unified team.' => 'Assessment, OT assessment, ABA, speech, and OT goals are synchronized in one clinical roadmap. No conflicting adviceâ€”just one unified team.',
        'Diagnosis, ABA, behavioral health, speech, and OT goals are synchronized in one clinical roadmap. No conflicting adviceâ€”just one unified team.' => 'Assessment, OT assessment, ABA, speech, and OT goals are synchronized in one clinical roadmap. No conflicting adviceâ€”just one unified team.',
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
    $new_special_programs = "Autism Assessment\nBehavioral Assessment\nADHD Assessment\nOT Assessment\nABA Therapy\nSpeech Therapy\nOccupational Therapy\nParent Coaching";
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

    $location_ids = get_posts(array(
        'post_type' => 'location',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'post_status' => 'any',
    ));

    foreach ((array) $location_ids as $location_id) {
        $programs = (string) get_post_meta((int) $location_id, 'location_special_programs', true);
        if (strpos($programs, 'OT Assessment') !== false) {
            continue;
        }

        if (trim($programs) === '') {
            update_post_meta((int) $location_id, 'location_special_programs', $new_special_programs);
            continue;
        }

        $updated_programs = $programs;
        if (stripos($programs, 'ADHD Assessment') !== false) {
            $updated_programs = preg_replace('/ADHD Assessment\\s*/i', "ADHD Assessment\nOT Assessment\n", $programs, 1);
        } elseif (stripos($programs, 'Behavioral Assessment') !== false) {
            $updated_programs = preg_replace('/Behavioral Assessment\\s*/i', "Behavioral Assessment\nOT Assessment\n", $programs, 1);
        } elseif (stripos($programs, 'Autism Assessment') !== false) {
            $updated_programs = preg_replace('/Autism Assessment\\s*/i', "Autism Assessment\nOT Assessment\n", $programs, 1);
        } elseif (stripos($programs, 'ABA Therapy') !== false) {
            $updated_programs = preg_replace('/ABA Therapy\\s*/i', "OT Assessment\nABA Therapy\n", $programs, 1);
        } else {
            $updated_programs = rtrim($programs) . "\nOT Assessment";
        }

        if ($updated_programs !== $programs) {
            update_post_meta((int) $location_id, 'location_special_programs', $updated_programs);
        }
    }

    $program_age_updates = array(
        'autism-assessment' => '18mo - 12y',
        'adhd-assessment' => '18mo - 12y',
        'ot-assessment' => 'Infants - 12y',
    );
    foreach ($program_age_updates as $slug => $age_range) {
        $program = get_page_by_path($slug, OBJECT, 'program');
        if ($program) {
            update_post_meta((int) $program->ID, 'program_age_range', $age_range);
        }
    }

    foreach (earlystart_launch_assessment_program_images() as $slug => $image_url) {
        $program = get_page_by_path($slug, OBJECT, 'program');
        if ($program) {
            update_post_meta((int) $program->ID, 'program_hero_image', $image_url);
            delete_post_meta((int) $program->ID, '_earlystart_program_hero_image_error');
        }
    }
}

/**
 * Normalize saved legacy content.
 *
 * This helper is safe to call from wp-admin or WP-CLI. Public output is also
 * normalized by the SEO head orchestrator as a last-resort display guard.
 */
function earlystart_apply_launch_content_cleanup(): void
{
    $version = '2026-07-10.3';
    if (get_option('earlystart_launch_content_cleanup_version') === $version) {
        return;
    }

    if (!function_exists('earlystart_normalize_legacy_branding_text')) {
        return;
    }

    global $wpdb;

    $post_types = array('page', 'post', 'attachment', 'location', 'program', 'city', 'team_member', 'career');
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
            $after = earlystart_normalize_childcare_era_content_text(earlystart_normalize_legacy_branding_text($before));
            if ($after !== $before) {
                $updates[$field] = $after;
            }
        }

        $slug_text = str_replace(array('-', '_'), ' ', (string) $post->post_name);
        $slug_after_text = earlystart_normalize_childcare_era_content_text(earlystart_normalize_legacy_branding_text($slug_text));
        $slug_after = sanitize_title($slug_after_text);
        if ($slug_after && $slug_after !== $post->post_name) {
            $updates['post_name'] = wp_unique_post_slug($slug_after, $post_id, $post->post_status, $post->post_type, (int) $post->post_parent);
        }

        if (count($updates) > 1) {
            wp_update_post(wp_slash($updates));
        }
    }

    $meta_keys = array(
        '_yoast_wpseo_title',
        '_yoast_wpseo_metadesc',
        '_wp_attachment_image_alt',
        'seo_llm_title',
        'seo_llm_description',
        'seo_llm_target_queries',
        'seo_llm_key_differentiators',
        'meta_keywords',
        'program_meta_title',
        'program_seo_heading',
        'program_hero_title',
        'program_hero_description',
        'location_tagline',
        'location_description',
        'location_hero_subtitle',
        'location_seo_content_title',
        'location_seo_content_text',
        'location_special_programs',
        'contact_hero_title',
        'contact_hero_description',
        'careers_hero_title',
        'locations_hero_heading',
        'parents_hero_heading',
        'parents_hero_description',
        'families_hero_desc',
        '_earlystart_es_title',
        '_earlystart_es_excerpt',
        '_earlystart_es_content',
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

        $after = earlystart_normalize_childcare_era_content_text(earlystart_normalize_legacy_branding_text($before));
        if ($after !== $before) {
            update_metadata_by_mid('post', (int) $row->meta_id, wp_slash($after));
        }
    }

    $prefix_meta_rows = $wpdb->get_results(
        "SELECT meta_id, meta_value FROM {$wpdb->postmeta}
        WHERE meta_key LIKE 'about\\_%'
        OR meta_key LIKE 'bridge\\_%'
        OR meta_key LIKE 'contact\\_%'
        OR meta_key LIKE 'curriculum\\_%'
        OR meta_key LIKE 'employers\\_%'
        OR meta_key LIKE 'families\\_%'
        OR meta_key LIKE 'home\\_%'
        OR meta_key LIKE 'locations\\_%'
        OR meta_key LIKE 'parents\\_%'
        OR meta_key LIKE 'schedule\\_%'
        OR meta_key LIKE 'stories\\_%'"
    );

    foreach ((array) $prefix_meta_rows as $row) {
        $before = maybe_unserialize($row->meta_value);
        if (!is_string($before) || $before === '') {
            continue;
        }

        $after = earlystart_normalize_childcare_era_content_text(earlystart_normalize_legacy_branding_text($before));
        if ($after !== $before) {
            update_metadata_by_mid('post', (int) $row->meta_id, wp_slash($after));
        }
    }

    $generated_meta_keys = array(
        '_earlystart_post_schemas',
        '_earlystart_ai_fallback_cache',
        '_earlystart_es_content',
        '_metasync_otto_image_alt_data',
        '_metasync_otto_structured_data',
        '_metasync_otto_keywords',
        '_metasync_otto_description',
        '_metasync_otto_title',
        '_metasync_otto_og_description',
        '_metasync_otto_headings_data',
        '_metasync_otto_og_title',
        'metasync_schema_markup',
    );

    $generated_placeholders = implode(',', array_fill(0, count($generated_meta_keys), '%s'));
    $generated_rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key IN ($generated_placeholders)",
            $generated_meta_keys
        )
    );

    foreach ((array) $generated_rows as $row) {
        $before = maybe_unserialize($row->meta_value);
        if (earlystart_value_contains_childcare_era_language($before)) {
            delete_metadata_by_mid('post', (int) $row->meta_id);
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

    $curriculum_page = get_page_by_path('curriculum', OBJECT, 'page');
    if ($curriculum_page) {
        wp_update_post(array(
            'ID' => (int) $curriculum_page->ID,
            'post_title' => 'Clinical Approach',
            'post_name' => 'clinical-approach',
        ));
    }

    $schedule_page = get_page_by_path('schedule-tour', OBJECT, 'page');
    if ($schedule_page) {
        wp_update_post(array(
            'ID' => (int) $schedule_page->ID,
            'post_title' => 'Request a Consultation',
            'post_name' => 'request-consultation',
            'post_status' => 'draft',
        ));
    }

    foreach (array(
        '_transient_earlystart_llms_txt_content_%',
        '_transient_timeout_earlystart_llms_txt_content_%',
        '_transient_otto_%',
        '_transient_timeout_otto_%',
    ) as $like) {
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                $like
            )
        );
    }

    update_option('earlystart_launch_content_cleanup_version', $version, false);
}

/**
 * Normalize saved legacy content when an administrator opens wp-admin.
 */
function earlystart_run_launch_content_cleanup(): void
{
    if (!is_admin() || wp_doing_ajax() || !current_user_can('manage_options')) {
        return;
    }

    earlystart_apply_launch_content_cleanup();
}
add_action('admin_init', 'earlystart_run_launch_content_cleanup', 30);
