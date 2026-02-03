<?php
/**
 * Universal FAQ Schema Builder
 * Generates JSON-LD for FAQPage Schema from universal meta box
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Universal_FAQ_Builder
{
    /**
     * Output FAQ schema
     */
    public static function output()
    {
        if (!is_singular() || is_front_page()) {
            return;
        }

        // Check if disabled globally (User Preference for Otto)
        // Default to 'yes' to ensure it is disabled by default as requested
        if (get_option('earlystart_faq_schema_disabled', 'no') === 'yes') {
            return;
        }

        // Check for manual override (AI Fixed Schema)
        $override = get_post_meta(get_queried_object_id(), '_earlystart_schema_override', true);
        if ($override) {
            return;
        }

        // Internal Duplicate Suppression
        global $earlystart_faq_output_done;
        if (!empty($earlystart_faq_output_done)) {
            return;
        }

        $post_id = get_the_ID();
        
        // Spanish Localization Support
        $is_spanish = false;
        if (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish')) {
             $is_spanish = earlystart_Multilingual_Manager::is_spanish();
        }

        $faqs = [];
        if ($is_spanish) {
            $faqs = get_post_meta($post_id, '_earlystart_es_earlystart_faq_items', true);
        }
        
        // Fallback to English if Spanish definition is missing or empty
        if (empty($faqs)) {
            $faqs = get_post_meta($post_id, 'earlystart_faq_items', true);
        }

        if (empty($faqs) || !is_array($faqs)) {
            return;
        }

        $main_entity = [];
        foreach ($faqs as $faq) {
            // Skip if question or answer is empty
            $question = isset($faq['question']) ? trim($faq['question']) : '';
            $answer = isset($faq['answer']) ? trim($faq['answer']) : '';

            if (empty($question) || empty($answer)) {
                continue;
            }

            $main_entity[] = [
                '@type' => 'Question',
                'name' => $question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $answer
                ]
            ];
        }

        // Only output if we have valid FAQ items
        if (empty($main_entity)) {
            return;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $main_entity
        ];

        earlystart_Schema_Registry::register($schema, ['source' => 'universal-faq-builder']);
        
        $earlystart_faq_output_done = true;
    }
}


