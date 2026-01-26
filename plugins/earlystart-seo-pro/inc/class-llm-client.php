<?php
/**
 * LLM Client
 * Handles communication with OpenAI API
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_LLM_Client
{
    private $api_key;
    private $model;
    private $base_url;

    public function __construct()
    {
        $this->api_key = get_option('earlystart_openai_api_key', '');
        $this->model = get_option('earlystart_llm_model', 'gpt-4o-mini');
        $this->base_url = get_option('earlystart_llm_base_url', 'https://api.openai.com/v1');

        add_action('wp_ajax_earlystart_save_llm_settings', [$this, 'ajax_save_settings']);
        add_action('wp_ajax_earlystart_test_llm_connection', [$this, 'ajax_test_connection']);
        add_action('wp_ajax_earlystart_generate_schema', [$this, 'ajax_generate_schema']);
        add_action('wp_ajax_earlystart_translate_text', [$this, 'ajax_translate_text']);
    }

    public function ajax_save_settings()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }
        if (isset($_POST['api_key'])) {
            update_option('earlystart_openai_api_key', sanitize_text_field($_POST['api_key']));
        }
        if (isset($_POST['model'])) {
            update_option('earlystart_llm_model', sanitize_text_field($_POST['model']));
        }
        if (isset($_POST['base_url'])) {
            update_option('earlystart_llm_base_url', esc_url_raw($_POST['base_url']));
        }
        wp_send_json_success(['message' => 'Settings saved.']);
    }

    public function ajax_test_connection()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Permission denied']);
        }
        $response = $this->make_request([
            'messages' => [['role' => 'user', 'content' => 'Say "Hello" if you can hear me.']]
        ]);
        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }
        wp_send_json_success(['message' => 'Connected!']);
    }

    public function generate_schema_data($post_id, $schema_type)
    {
        $post = get_post($post_id);
        if (!$post)
            return new WP_Error('not_found', 'Post not found');

        $prompt = "You are an SEO EXPERT specializing in Schema.org and Local SEO for pediatric therapy and early intervention.\n";
        $prompt .= "Analyze the content for a '{$schema_type}' object.\n\n";
        $prompt .= "=== BUSINESS CONTEXT ===\n";
        $prompt .= "- Industry: Pediatric Therapy & Early Intervention\n";
        $prompt .= "- Service Type: ABA Therapy, Speech Therapy, Occupational Therapy\n";
        $prompt .= "- Brand: Early Start\n\n";

        switch ($schema_type) {
            case 'JobPosting':
                $prompt .= "- HIRING ORG: hiringOrganization should be 'Early Start'.\n";
                break;
            case 'Article':
            case 'NewsArticle':
            case 'BlogPosting':
                $prompt .= "- PUBLISHER: default to organization 'Early Start'\n";
                break;
            case 'Service':
                $prompt .= "- PROVIDER: Organization 'Early Start'\n";
                break;
            case 'LocalBusiness':
                $prompt .= "- Brand: Early Start\n";
                break;
        }

        $prompt .= "Return ONLY valid JSON.\n";
        $prompt .= "Title: " . $post->post_title . "\n";
        $prompt .= "URL: " . get_permalink($post_id) . "\n";
        $prompt .= "Main Content:\n" . wp_trim_words(strip_tags($post->post_content), 500) . "\n";

        $response = $this->make_request([
            'messages' => [
                ['role' => 'system', 'content' => 'You are an SEO expert. Output JSON only.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'response_format' => ['type' => 'json_object']
        ]);

        if (is_wp_error($response))
            return $response;

        $content = $response['choices'][0]['message']['content'] ?? '';
        $json = json_decode($content, true);
        if (!$json)
            return new WP_Error('json_error', 'Failed to parse AI response');

        return $json;
    }

    public function ajax_generate_schema()
    {
        if (!current_user_can('edit_posts'))
            wp_send_json_error(['message' => 'Permission denied']);
        $post_id = intval($_POST['post_id']);
        $schema_type = sanitize_text_field($_POST['schema_type']);
        $result = $this->generate_schema_data($post_id, $schema_type);
        if (is_wp_error($result))
            wp_send_json_error(['message' => $result->get_error_message()]);
        wp_send_json_success($result);
    }

    public function ajax_translate_text()
    {
        if (!current_user_can('edit_posts'))
            wp_send_json_error(['message' => 'Permission denied']);
        $text = isset($_POST['text']) ? wp_unslash($_POST['text']) : '';
        $target_lang = sanitize_text_field($_POST['target_lang'] ?? 'es');
        $translation = $this->translate_text($text, $target_lang);
        if (is_wp_error($translation))
            wp_send_json_error(['message' => $translation->get_error_message()]);
        wp_send_json_success(['translation' => $translation]);
    }

    public function translate_text($text, $target_lang = 'es')
    {
        $prompt = "You are a professional translator for a pediatric therapy brand called 'Early Start'.\n";
        $prompt .= "Translate the following content to " . ($target_lang === 'es' ? 'Spanish (Latin American)' : $target_lang) . ".\n";
        $prompt .= "Maintain HTML tags exactly if present.\n";
        $prompt .= "Tone: Warm, professional, clinical but caring.\n";
        $prompt .= "\nContent to Translate:\n" . $text;

        $response = $this->make_request([
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional translator.'],
                ['role' => 'user', 'content' => $prompt]
            ]
        ]);

        if (is_wp_error($response))
            return $response;
        return trim($response['choices'][0]['message']['content'] ?? '');
    }

    public function make_request($data)
    {
        $api_key = get_option('earlystart_openai_api_key', '');
        $model = get_option('earlystart_llm_model', 'gpt-4o-mini');
        $base_url = get_option('earlystart_llm_base_url', 'https://api.openai.com/v1');

        if (empty($api_key))
            return new WP_Error('no_api_key', 'No API Key configured.');

        $url = rtrim($base_url, '/') . '/chat/completions';
        $body = array_merge(['model' => $model, 'temperature' => 0.7], $data);

        $response = wp_remote_post($url, [
            'body' => json_encode($body),
            'headers' => ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $api_key],
            'timeout' => 60
        ]);

        if (is_wp_error($response))
            return $response;

        $code = wp_remote_retrieve_response_code($response);
        $decoded = json_decode(wp_remote_retrieve_body($response), true);

        if ($code !== 200) {
            $msg = isset($decoded['error']['message']) ? $decoded['error']['message'] : 'Unknown API Error';
            return new WP_Error('api_error', $msg);
        }

        return $decoded;
    }
}
