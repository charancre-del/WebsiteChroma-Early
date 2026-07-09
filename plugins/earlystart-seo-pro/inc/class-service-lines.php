<?php
/**
 * Early Start pediatric therapy service-line definitions.
 *
 * @package earlystart_SEO_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('earlystart_seo_service_lines')) {
    /**
     * Return the canonical service lines used by schema, SEO generators, and LLM defaults.
     *
     * @return array<string,array<string,mixed>>
     */
    function earlystart_seo_service_lines()
    {
        return [
            'autism_assessment' => [
                'key' => 'autism_assessment',
                'label' => 'Autism Assessment',
                'spanish_label' => 'Evaluacion de autismo',
                'slug' => 'autism-assessment',
                'service_type' => 'Autism assessment',
                'category' => 'Developmental assessment services',
                'schema_type' => 'Service',
                'medical_specialty' => 'Developmental and behavioral pediatrics',
                'keywords' => ['autism assessment', 'autism evaluation', 'developmental assessment'],
                'description' => 'Comprehensive autism assessments for children and families.',
            ],
            'aba' => [
                'key' => 'aba',
                'label' => 'ABA Therapy',
                'spanish_label' => 'Terapia ABA',
                'slug' => 'aba-therapy',
                'service_type' => 'Applied Behavior Analysis (ABA) therapy',
                'category' => 'Pediatric behavioral therapy',
                'schema_type' => 'Service',
                'keywords' => ['aba', 'aba therapy', 'applied behavior analysis', 'behavior therapy'],
                'description' => 'Evidence-based ABA therapy for children and families.',
            ],
            'speech' => [
                'key' => 'speech',
                'label' => 'Speech Therapy',
                'spanish_label' => 'Terapia del habla',
                'slug' => 'speech-therapy',
                'service_type' => 'Pediatric speech therapy',
                'category' => 'Speech-language therapy',
                'schema_type' => 'Service',
                'medical_specialty' => 'https://schema.org/SpeechPathology',
                'keywords' => ['speech', 'speech therapy', 'speech-language', 'language therapy'],
                'description' => 'Pediatric speech and language therapy for communication development.',
            ],
            'ot' => [
                'key' => 'ot',
                'label' => 'Occupational Therapy',
                'spanish_label' => 'Terapia ocupacional',
                'slug' => 'occupational-therapy',
                'service_type' => 'Pediatric occupational therapy',
                'category' => 'Occupational therapy',
                'schema_type' => 'OccupationalTherapy',
                'keywords' => ['ot', 'occupational', 'occupational therapy', 'sensory therapy'],
                'description' => 'Pediatric occupational therapy for sensory, motor, and daily living skills.',
            ],
            'behavioral_assessment' => [
                'key' => 'behavioral_assessment',
                'label' => 'Behavioral Assessment',
                'spanish_label' => 'Evaluacion conductual',
                'slug' => 'behavioral-assessment',
                'service_type' => 'Pediatric behavioral assessment',
                'category' => 'Behavioral assessment',
                'schema_type' => 'Service',
                'medical_specialty' => 'Behavioral assessment',
                'keywords' => ['behavioral assessment', 'behavior assessment', 'emotional regulation', 'coping skills'],
                'description' => 'Behavioral assessments for regulation, behavior patterns, and family-centered next steps.',
            ],
            'adhd_assessment' => [
                'key' => 'adhd_assessment',
                'label' => 'ADHD Assessment',
                'spanish_label' => 'Evaluacion de ADHD',
                'slug' => 'adhd-assessment',
                'service_type' => 'ADHD assessment',
                'category' => 'Attention and executive function assessment',
                'schema_type' => 'Service',
                'medical_specialty' => 'Behavioral assessment',
                'keywords' => ['adhd assessment', 'adhd evaluation', 'attention assessment', 'executive function assessment'],
                'description' => 'ADHD assessments for attention, executive function, impulsivity, and regulation needs.',
            ],
        ];
    }
}

if (!function_exists('earlystart_seo_get_service_line')) {
    /**
     * Get one canonical service-line definition.
     *
     * @param string $key Service-line key.
     * @return array<string,mixed>|null
     */
    function earlystart_seo_get_service_line($key)
    {
        $lines = earlystart_seo_service_lines();
        $key = strtolower((string) $key);

        return $lines[$key] ?? null;
    }
}

if (!function_exists('earlystart_seo_detect_service_line')) {
    /**
     * Detect the most likely service line from a post and optional text.
     *
     * @param int    $post_id Optional post ID.
     * @param string $extra_text Optional text to inspect.
     * @return string Empty string when no specific line is detected.
     */
    function earlystart_seo_detect_service_line($post_id = 0, $extra_text = '')
    {
        $parts = [(string) $extra_text];

        $post_id = absint($post_id);
        if ($post_id > 0) {
            $parts[] = get_the_title($post_id);
            $parts[] = get_post_field('post_name', $post_id);
            $parts[] = get_post_meta($post_id, 'schema_prog_service_type', true);
            $parts[] = get_post_meta($post_id, 'schema_prog_category', true);
            $parts[] = get_post_meta($post_id, 'location_special_programs', true);
            $parts[] = get_post_meta($post_id, 'program_anchor_slug', true);
        }

        $haystack = strtolower(implode(' ', array_filter(array_map('strval', $parts))));

        if ($haystack === '') {
            return '';
        }

        if (preg_match('/\b(autism assessment|autism evaluation|developmental assessment)\b/', $haystack)) {
            return 'autism_assessment';
        }

        if (preg_match('/\b(aba|applied behavior analysis|behavior therapy)\b/', $haystack)) {
            return 'aba';
        }

        if (preg_match('/\b(behavioral assessment|behavior assessment|emotional regulation|coping skills)\b/', $haystack)) {
            return 'behavioral_assessment';
        }

        if (preg_match('/\b(adhd assessment|adhd evaluation|attention assessment|executive function assessment)\b/', $haystack)) {
            return 'adhd_assessment';
        }

        if (preg_match('/\b(speech|speech-language|language therapy)\b/', $haystack)) {
            return 'speech';
        }

        if (preg_match('/\b(occupational|occupational therapy|sensory therapy|ot)\b/', $haystack)) {
            return 'ot';
        }

        return '';
    }
}

if (!function_exists('earlystart_seo_service_label')) {
    /**
     * Return a localized service label.
     *
     * @param string $key Service-line key.
     * @return string
     */
    function earlystart_seo_service_label($key)
    {
        $line = earlystart_seo_get_service_line($key);
        if (!$line) {
            return 'Pediatric Therapy';
        }

        $is_spanish = class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish();
        return $is_spanish ? (string) $line['spanish_label'] : (string) $line['label'];
    }
}

if (!function_exists('earlystart_seo_service_schema')) {
    /**
     * Build reusable schema for one therapy service line.
     *
     * @param string $key Service-line key.
     * @param array  $overrides Schema overrides.
     * @return array<string,mixed>|null
     */
    function earlystart_seo_service_schema($key, array $overrides = [])
    {
        $line = earlystart_seo_get_service_line($key);
        if (!$line) {
            return null;
        }

        $schema = [
            '@type' => $line['schema_type'],
            'name' => earlystart_seo_service_label($key),
            'serviceType' => $line['service_type'],
            'category' => $line['category'],
            'description' => $line['description'],
            'audience' => [
                '@type' => 'PeopleAudience',
                'audienceType' => 'Children and families',
            ],
        ];

        if (!empty($line['medical_specialty'])) {
            $schema['medicalSpecialty'] = $line['medical_specialty'];
        }

        return array_filter(array_merge($schema, $overrides), static function ($value) {
            return $value !== null && $value !== '';
        });
    }
}

if (!function_exists('earlystart_seo_all_service_schemas')) {
    /**
     * Build schema snippets for every canonical therapy service line.
     *
     * @param array $overrides Schema overrides applied to each service.
     * @return array<int,array<string,mixed>>
     */
    function earlystart_seo_all_service_schemas(array $overrides = [])
    {
        $items = [];
        foreach (array_keys(earlystart_seo_service_lines()) as $key) {
            $schema = earlystart_seo_service_schema($key, $overrides);
            if ($schema) {
                $items[] = $schema;
            }
        }

        return $items;
    }
}
