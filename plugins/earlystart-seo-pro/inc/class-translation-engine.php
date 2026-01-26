<?php
/**
 * Translation Engine
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Translation_Engine
{
    public static function init()
    {
        add_action('wp_ajax_earlystart_auto_translate_post', [__CLASS__, 'ajax_auto_translate_post']);
    }

    public static function ajax_auto_translate_post()
    {
        if (!check_ajax_referer('earlystart_seo_nonce', 'nonce', false)) {
            wp_send_json_error(['message' => 'Invalid Nonce']);
        }

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }

        $post_id = intval($_POST['post_id']);
        $post = get_post($post_id);

        if (!$post) {
            wp_send_json_error(['message' => 'Post not found']);
        }

        try {
            $fields = [
                '_earlystart_es_title' => $post->post_title,
                '_earlystart_es_content' => $post->post_content,
                '_earlystart_es_excerpt' => $post->post_excerpt,
            ];

            // Add Post Type specific fields
            if ($post->post_type === 'location') {
                $fields['_earlystart_es_location_city'] = get_post_meta($post_id, 'location_city', true);
                $fields['_earlystart_es_location_description'] = get_post_meta($post_id, 'location_description', true);
            } elseif ($post->post_type === 'program') {
                $fields['_earlystart_es_program_age_range'] = get_post_meta($post_id, 'program_age_range', true);
            }

            // Updated context for Pediatric Therapy
            $translated = self::translate_bulk($fields, 'es', 'Translate for a pediatric therapy and early intervention website. Use Spanish (Latin American).');

            if (isset($translated['_error'])) {
                wp_send_json_error(['message' => $translated['_error']]);
            }

            foreach ($translated as $key => $value) {
                if (strpos($key, 'content') !== false) {
                    update_post_meta($post_id, $key, wp_kses_post($value));
                } else {
                    update_post_meta($post_id, $key, sanitize_text_field($value));
                }
            }

            wp_send_json_success($translated);

        } catch (Exception $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }

    public static function translate_bulk($fields, $target_lang = 'es', $context = '')
    {
        $client = new earlystart_LLM_Client();

        $fields_to_translate = array_filter($fields);
        if (empty($fields_to_translate)) {
            return $fields;
        }

        $prompt = "You are a batch translation engine. Translate the following JSON object values to " . ($target_lang === 'es' ? 'Spanish (Latin American)' : $target_lang) . ".\n";
        $prompt .= "Maintain HTML tags if present. Do not translate keys.\n";
        $prompt .= "Return ONLY valid JSON.\n";

        if ($context) {
            $prompt .= "Context: " . $context . "\n";
        }

        $prompt .= "\nInput JSON:\n" . json_encode($fields_to_translate, JSON_UNESCAPED_UNICODE);

        $response = $client->make_request([
            'messages' => [
                ['role' => 'system', 'content' => 'You are a translation API. Output JSON only.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'response_format' => ['type' => 'json_object']
        ]);

        if (is_wp_error($response)) {
            return ['_error' => $response->get_error_message()];
        }

        $content = $response['choices'][0]['message']['content'] ?? '';
        $translated = json_decode($content, true);

        if (!$translated) {
            return ['_error' => 'Failed to parse translation JSON.'];
        }

        return array_merge($fields, $translated);
    }
}
