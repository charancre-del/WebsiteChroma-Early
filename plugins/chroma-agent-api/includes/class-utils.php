<?php

namespace ChromaAgentAPI;

if (!defined('ABSPATH')) {
    exit;
}

class Utils
{
    public const OPTION_ENABLED = 'earlystart_agent_api_enabled';
    public const OPTION_THEME_OPTION_ALLOWLIST = 'earlystart_agent_api_theme_option_allowlist';
    public const OPTION_THEME_MOD_ALLOWLIST = 'earlystart_agent_api_theme_mod_allowlist';
    public const OPTION_SEO_OPTION_ALLOWLIST = 'earlystart_agent_api_seo_option_allowlist';
    public const OPTION_SEO_META_ALLOWLIST = 'earlystart_agent_api_seo_meta_allowlist';

    public static function table(string $suffix): string
    {
        global $wpdb;
        return $wpdb->prefix . 'earlystart_api_' . $suffix;
    }

    public static function is_https_request(): bool
    {
        if (is_ssl()) {
            return true;
        }

        $forwarded = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ? strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) : '';
        return $forwarded === 'https';
    }

    public static function get_request_ip(): string
    {
        $keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];

        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {
                $value = trim((string) wp_unslash($_SERVER[$key]));
                if ($key === 'HTTP_X_FORWARDED_FOR' && strpos($value, ',') !== false) {
                    $parts = explode(',', $value);
                    $value = trim($parts[0]);
                }

                if (filter_var($value, FILTER_VALIDATE_IP)) {
                    return $value;
                }
            }
        }

        return '0.0.0.0';
    }

    public static function truthy($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value === 1;
        }

        if (!is_string($value)) {
            return false;
        }

        $normalized = strtolower(trim($value));
        return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
    }

    public static function sanitize_recursive($value)
    {
        if (is_array($value)) {
            $out = [];
            foreach ($value as $k => $v) {
                $safe_key = is_string($k) ? sanitize_key($k) : $k;
                $out[$safe_key] = self::sanitize_recursive($v);
            }
            return $out;
        }

        if (is_object($value)) {
            return self::sanitize_recursive((array) $value);
        }

        if (is_string($value)) {
            return sanitize_text_field($value);
        }

        return $value;
    }

    public static function sanitize_mixed_for_storage($value)
    {
        if (is_array($value)) {
            $out = [];
            foreach ($value as $k => $v) {
                $safe_key = is_string($k) ? sanitize_key($k) : $k;
                $out[$safe_key] = self::sanitize_mixed_for_storage($v);
            }
            return $out;
        }

        if (is_object($value)) {
            return self::sanitize_mixed_for_storage((array) $value);
        }

        if (is_string($value)) {
            return wp_kses_post($value);
        }

        return $value;
    }

    /**
     * Sanitize nested data while preserving original string keys (e.g. @context, @type).
     */
    public static function sanitize_mixed_for_storage_preserve_keys($value)
    {
        if (is_array($value)) {
            $out = [];
            foreach ($value as $k => $v) {
                $safe_key = is_string($k) ? $k : $k;
                $out[$safe_key] = self::sanitize_mixed_for_storage_preserve_keys($v);
            }
            return $out;
        }

        if (is_object($value)) {
            return self::sanitize_mixed_for_storage_preserve_keys((array) $value);
        }

        if (is_string($value)) {
            return wp_kses_post($value);
        }

        return $value;
    }

    public static function normalize_scopes(array $scopes): array
    {
        $out = [];
        foreach ($scopes as $scope) {
            if (!is_string($scope)) {
                continue;
            }
            $scope = strtolower(trim($scope));
            if ($scope !== '') {
                $out[] = $scope;
            }
        }
        $out = array_values(array_unique($out));
        sort($out);
        return $out;
    }

    public static function get_theme_option_allowlist(): array
    {
        $saved = get_option(self::OPTION_THEME_OPTION_ALLOWLIST, []);
        $defaults = [
            'blogname',
            'blogdescription',
            'show_on_front',
            'page_on_front',
            'page_for_posts',
            'earlystart_global_cares',
            'earlystart_global_alert',
            'earlystart_agent_public_text_overrides',
        ];

        if (!is_array($saved)) {
            $saved = [];
        }

        return self::normalize_allowlist(array_merge(
            $defaults,
            self::discover_theme_option_keys(),
            $saved
        ));
    }

    public static function get_theme_mod_allowlist(): array
    {
        $saved = get_option(self::OPTION_THEME_MOD_ALLOWLIST, []);
        $defaults = [
            'custom_logo',
            'background_color',
            'header_textcolor',
        ];

        if (!is_array($saved)) {
            $saved = [];
        }

        $allowlist = self::normalize_allowlist(array_merge(
            $defaults,
            self::discover_theme_mod_keys(),
            $saved
        ));

        return self::normalize_allowlist(array_merge(
            $allowlist,
            self::get_spanish_variant_keys($allowlist)
        ));
    }

    public static function get_seo_option_allowlist(): array
    {
        $saved = get_option(self::OPTION_SEO_OPTION_ALLOWLIST, []);
        $defaults = [
            'earlystart_openai_api_key',
            'earlystart_google_places_api_key',
            'earlystart_llm_model',
            'earlystart_llm_base_url',
            'earlystart_llm_rate_limit',
            'earlystart_llm_cache_duration',
            'earlystart_citation_facts',
            'earlystart_llm_brand_voice',
            'earlystart_llm_brand_context',
            'earlystart_seo_phone',
            'earlystart_seo_email',
            'earlystart_seo_phonetic_name',
            'earlystart_homepage_translations_es',
            'earlystart_validator_batch_size',
            'earlystart_validator_request_delay',
            'earlystart_validator_timeout',
            'earlystart_validator_cache_ttl',
            'earlystart_validator_max_retries',
            'earlystart_validator_email_alerts',
            'earlystart_validator_post_types',
            'earlystart_careers_feed_url',
            'earlystart_combo_auto_publish',
            'earlystart_seo_manual_cities_raw',
            'earlystart_seo_manual_cities',
            'earlystart_seo_show_related_locations',
            'earlystart_seo_link_programs_locations',
            'earlystart_seo_enable_keyword_linking',
            'earlystart_seo_keyword_links',
            'earlystart_seo_show_footer_cities',
            'earlystart_seo_enable_dynamic_titles',
            'earlystart_seo_title_patterns',
            'earlystart_seo_enable_canonical',
            'earlystart_seo_trailing_slash',
            'earlystart_seo_show_author_meta',
            'earlystart_seo_show_author_box',
            'earlystart_seo_show_credential_badges',
            'earlystart_seo_enable_skip_nav',
            'earlystart_seo_enable_focus_indicators',
            'earlystart_enable_speculation_rules',
            'earlystart_enable_indexnow',
            'earlystart_indexnow_key',
            'earlystart_seo_enable_entity_markup',
            'earlystart_seo_same_as_urls',
            'earlystart_seo_founder_name',
            'earlystart_seo_founded_date',
            'earlystart_seo_enable_county_pages',
            'earlystart_seo_enable_zip_pages',
            'earlystart_seo_auto_generate_combos',
            'earlystart_seo_enable_combo_links',
            'earlystart_breadcrumbs_enabled',
            'earlystart_breadcrumbs_home_text',
            'earlystart_breadcrumbs_strip_html',
            'earlystart_breadcrumbs_max_length',
            'earlystart_breadcrumbs_truncate_suffix',
            'earlystart_faq_schema_disabled',
            'earlystart_breadcrumbs_schema_disabled',
        ];

        if (!is_array($saved)) {
            $saved = [];
        }

        return self::normalize_allowlist(array_merge($defaults, $saved));
    }

    public static function get_seo_meta_allowlist(): array
    {
        $saved = get_option(self::OPTION_SEO_META_ALLOWLIST, []);
        $defaults = [
            '_earlystart_es_title',
            '_earlystart_es_content',
            '_earlystart_es_excerpt',
            '_earlystart_es_seo_title',
            '_earlystart_es_meta_description',
            '_earlystart_es_earlystart_faq_items',
            '_earlystart_post_schemas',
            '_earlystart_schema_override',
            '_earlystart_schema_type',
            '_earlystart_schema_data',
            '_earlystart_schema_confidence',
            '_earlystart_needs_review',
            '_earlystart_review_reason',
            '_earlystart_schema_history',
            '_earlystart_schema_validation_status',
            '_earlystart_schema_errors',
            'earlystart_faq_items',
            'seo_llm_description',
            'seo_llm_when_to_recommend',
            'seo_llm_primary_intent',
            'seo_llm_target_queries',
            'seo_llm_key_differentiators',
            'seo_llm_prompt',
            'seo_llm_context',
            'seo_llm_service_area_lat',
            'seo_llm_service_area_lng',
            'seo_llm_service_area_radius',
            'seo_llm_service_area_cities',
            'seo_llm_service_area_state',
            'seo_llm_aggregate_rating_value',
            'seo_llm_aggregate_rating_count',
            'seo_llm_aggregate_rating_best',
            'seo_llm_aggregate_rating_worst',
            'seo_llm_price_min',
            'seo_llm_price_max',
            'seo_llm_price_currency',
            'seo_llm_price_frequency',
            'location_enrollment_steps',
            'location_events',
            'location_media',
            'location_howto',
            'location_citation_facts',
            'location_advanced_schema',
            'program_locations_served',
            'program_prerequisites',
            'program_related',
            'city_county',
            'city_intro_text',
            'city_nearby_locations',
            'alternate_url_en',
            'alternate_url_es',
            '_earlystart_show_in_newsroom',
            'schema_org_type',
            'schema_org_data',
            'schema_loc_type',
            'schema_loc_data',
            'schema_prog_type',
            'schema_prog_data',
        ];

        if (!is_array($saved)) {
            $saved = [];
        }

        return self::normalize_allowlist(array_merge($defaults, $saved));
    }

    public static function get_plugin_setting_allowlist(): array
    {
        return self::normalize_allowlist([
            'earlystart_contact_fields',
            'earlystart_contact_webhook_url',
            'earlystart_contact_email_recipient',
            'earlystart_contact_form_id',
            'earlystart_contact_form_height',
            'earlystart_contact_form_name',
            'earlystart_contact_lazy_load',
            'earlystart_contact_lazy_delay',
            'earlystart_career_fields',
            'earlystart_career_webhook_url',
            'earlystart_career_email_recipient',
            'earlystart_career_form_id',
            'earlystart_career_form_height',
            'earlystart_career_form_name',
            'earlystart_career_lazy_load',
            'earlystart_career_lazy_delay',
            'earlystart_tour_form_id',
            'earlystart_tour_form_height',
            'earlystart_tour_form_name',
            'earlystart_tour_lazy_load',
            'earlystart_tour_lazy_delay',
            'earlystart_acquisition_fields',
            'earlystart_acquisition_webhook_url',
            'earlystart_acquisition_email_recipient',
            'earlystart_lead_log_webhook_url',
        ]);
    }

    public static function get_sensitive_option_keys(): array
    {
        return self::normalize_allowlist([
            'earlystart_openai_api_key',
            'earlystart_google_places_api_key',
            'earlystart_contact_webhook_url',
            'earlystart_career_webhook_url',
            'earlystart_acquisition_webhook_url',
            'earlystart_lead_log_webhook_url',
            'earlystart_indexnow_key',
        ]);
    }

    public static function get_embed_meta_keys(): array
    {
        return self::normalize_allowlist([
            'location_maps_embed',
            'location_virtual_tour_embed',
        ]);
    }

    public static function sanitize_embed_html($value): string
    {
        $allowed_tags = wp_kses_allowed_html('post');
        $allowed_tags['iframe'] = [
            'src' => true,
            'width' => true,
            'height' => true,
            'frameborder' => true,
            'allowfullscreen' => true,
            'allow' => true,
            'loading' => true,
            'style' => true,
            'class' => true,
            'title' => true,
            'referrerpolicy' => true,
        ];
        $allowed_tags['script'] = [
            'src' => true,
            'type' => true,
            'async' => true,
            'defer' => true,
        ];

        return wp_kses((string) $value, $allowed_tags);
    }

    public static function sanitize_mixed_for_storage_by_key(string $key, $value)
    {
        if (in_array($key, self::get_embed_meta_keys(), true)) {
            return self::sanitize_embed_html($value);
        }

        return self::sanitize_mixed_for_storage($value);
    }

    public static function invalidate_content_caches_for_post(int $post_id): void
    {
        if ($post_id <= 0) {
            return;
        }

        clean_post_cache($post_id);

        if (function_exists('earlystart_clear_query_cache')) {
            earlystart_clear_query_cache($post_id);
        }

        do_action('chroma_agent_api_content_updated', $post_id);
    }

    public static function get_spanish_variant_keys(array $keys): array
    {
        $variants = [];
        foreach ($keys as $key) {
            if (!is_string($key) || $key === '' || substr($key, -3) === '_es') {
                continue;
            }
            $variants[] = $key . '_es';
        }

        return self::normalize_allowlist($variants);
    }

    public static function get_theme_meta_key_inventory(): array
    {
        $surfaces = self::discover_theme_surfaces();

        return [
            'exact' => $surfaces['meta'],
            'patterns' => $surfaces['meta_patterns'],
        ];
    }

    private static function discover_theme_mod_keys(): array
    {
        $surfaces = self::discover_theme_surfaces();
        return $surfaces['theme_mods'];
    }

    private static function discover_theme_option_keys(): array
    {
        $surfaces = self::discover_theme_surfaces();
        return $surfaces['options'];
    }

    private static function discover_theme_surfaces(): array
    {
        static $cached = null;

        if (is_array($cached)) {
            return $cached;
        }

        $cached = [
            'meta' => [],
            'meta_patterns' => [],
            'theme_mods' => [],
            'options' => [],
        ];

        if (!function_exists('get_stylesheet_directory')) {
            return $cached;
        }

        $theme_dir = get_stylesheet_directory();
        if (!is_string($theme_dir) || $theme_dir === '' || !is_dir($theme_dir)) {
            return $cached;
        }

        $meta = [];
        $meta_patterns = [];
        $theme_mods = [];
        $options = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($theme_dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file instanceof \SplFileInfo || !$file->isFile()) {
                continue;
            }

            $path = $file->getPathname();
            if (substr($path, -4) !== '.php' || strpos($path, DIRECTORY_SEPARATOR . 'node_modules' . DIRECTORY_SEPARATOR) !== false) {
                continue;
            }

            $contents = file_get_contents($path);
            if (!is_string($contents) || $contents === '') {
                continue;
            }

            self::collect_regex_keys(
                $contents,
                '/(?:get_post_meta|update_post_meta|delete_post_meta|metadata_exists|register_post_meta)\s*\([^,]+,\s*([\'"])(.*?)\1/s',
                $meta,
                $meta_patterns
            );
            self::collect_regex_keys(
                $contents,
                '/name\s*=\s*([\'"])(.*?)\1/s',
                $meta,
                $meta_patterns,
                '/^(about_|careers_|contact_|curriculum_|employers_|home_|parents_|privacy_|stories_|program_|location_|city_|schema_|meta_|alternate_|_earlystart_)/'
            );
            $ignored_patterns = [];
            self::collect_regex_keys(
                $contents,
                '/(?:add_setting|get_theme_mod|set_theme_mod|earlystart_get_theme_mod)\s*\(\s*([\'"])(.*?)\1/s',
                $theme_mods,
                $ignored_patterns
            );
            $ignored_patterns = [];
            self::collect_regex_keys(
                $contents,
                '/(?:get_option|update_option|delete_option)\s*\(\s*([\'"])(.*?)\1/s',
                $options,
                $ignored_patterns,
                '/^(blogname|blogdescription|show_on_front|page_on_front|page_for_posts|earlystart_global_settings|earlystart_gtm_id|earlystart_program_base_slug|earlystart_seo_head_mode|earlystart_sitemap_options|earlystart_twitter_handle)$/'
            );
        }

        $cached = [
            'meta' => self::normalize_allowlist(array_keys($meta)),
            'meta_patterns' => self::normalize_allowlist(array_keys($meta_patterns)),
            'theme_mods' => self::normalize_allowlist(array_keys($theme_mods)),
            'options' => self::normalize_allowlist(array_keys($options)),
        ];

        return $cached;
    }

    private static function collect_regex_keys(
        string $contents,
        string $pattern,
        array &$exact,
        ?array &$patterns = null,
        ?string $allow_pattern = null
    ): void {
        if (!preg_match_all($pattern, $contents, $matches)) {
            return;
        }

        foreach ((array) ($matches[2] ?? []) as $raw_key) {
            $key = trim((string) $raw_key);
            if ($key === '' || strpos($key, '<?php') !== false || strpos($key, '?>') !== false) {
                continue;
            }

            if ($allow_pattern !== null && !preg_match($allow_pattern, $key)) {
                continue;
            }

            if (strpos($key, '{$') !== false) {
                if ($patterns !== null) {
                    $patterns[preg_replace('/\{\$[^}]+\}/', '*', $key)] = true;
                }
                continue;
            }

            $exact[$key] = true;
        }
    }

    public static function normalize_allowlist(array $values): array
    {
        $out = [];
        foreach ($values as $value) {
            if (!is_string($value)) {
                continue;
            }
            $value = trim($value);
            if ($value !== '') {
                $out[] = $value;
            }
        }

        $out = array_values(array_unique($out));
        sort($out);
        return $out;
    }
}
