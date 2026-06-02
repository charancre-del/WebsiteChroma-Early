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

    public static function scope_is_granted(string $required_scope, array $granted_scopes): bool
    {
        $required_scope = strtolower(trim($required_scope));
        if ($required_scope === '') {
            return true;
        }

        $granted_scopes = self::normalize_scopes($granted_scopes);
        if (in_array($required_scope, $granted_scopes, true)) {
            return true;
        }

        [$verb, $resource] = array_pad(explode(':', $required_scope, 2), 2, '');
        if (!in_array($verb, ['read', 'write'], true)) {
            return false;
        }

        $aliases = [
            $verb . ':*',
            $verb . ':all',
            $verb . ':editables',
            'editables:' . $verb,
            'all:' . $verb,
            '*',
        ];

        if ($resource !== '') {
            $aliases[] = $resource . ':' . $verb;
        }

        return !empty(array_intersect($aliases, $granted_scopes));
    }

    public static function missing_scopes(array $required_scopes, array $granted_scopes): array
    {
        $missing = [];
        foreach (self::normalize_scopes($required_scopes) as $scope) {
            if (!self::scope_is_granted($scope, $granted_scopes)) {
                $missing[] = $scope;
            }
        }

        return $missing;
    }

    public static function default_key_scopes(): array
    {
        return [
            'read:editables',
            'write:editables',
            'read:content',
            'write:content',
            'read:theme',
            'write:theme',
            'read:seo',
            'write:seo',
            'read:media',
            'write:media',
            'read:settings',
            'write:settings',
            'read:taxonomy',
            'write:taxonomy',
            'read:menus',
            'write:menus',
            'admin:keys',
            'admin:audit',
        ];
    }

    public static function complete_legacy_editable_scopes(array $scopes): array
    {
        $scopes = self::normalize_scopes($scopes);

        $has_all_reads = !empty(array_intersect(['read:*', 'read:all', 'read:editables', 'editables:read', 'all:read', '*'], $scopes));
        $has_all_writes = !empty(array_intersect(['write:*', 'write:all', 'write:editables', 'editables:write', 'all:write', '*'], $scopes));

        $legacy_read_scopes = ['read:content', 'read:theme', 'read:seo', 'read:media'];
        $legacy_write_scopes = ['write:content', 'write:theme', 'write:seo', 'write:media'];

        if ($has_all_reads || empty(array_diff($legacy_read_scopes, $scopes))) {
            $scopes = array_merge($scopes, ['read:settings', 'read:taxonomy', 'read:menus']);
        }

        if ($has_all_writes || empty(array_diff($legacy_write_scopes, $scopes))) {
            $scopes = array_merge($scopes, ['write:settings', 'write:taxonomy', 'write:menus']);
        }

        return self::normalize_scopes($scopes);
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
            self::discover_customizer_option_keys(),
            self::discover_registered_option_keys(),
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

        return self::normalize_allowlist(array_merge($defaults, self::discover_seo_option_keys(), $saved));
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
            '_earlystart_es_city_state',
            '_earlystart_es_history',
            '_earlystart_es_home_hero_heading',
            '_earlystart_es_location_address',
            '_earlystart_es_location_ages_served',
            '_earlystart_es_location_city',
            '_earlystart_es_location_description',
            '_earlystart_es_location_director_bio',
            '_earlystart_es_location_hero_review_author',
            '_earlystart_es_location_hero_review_text',
            '_earlystart_es_location_hero_subtitle',
            '_earlystart_es_location_open_text',
            '_earlystart_es_location_school_pickups',
            '_earlystart_es_location_seo_content_text',
            '_earlystart_es_location_seo_content_title',
            '_earlystart_es_location_tagline',
            '_earlystart_es_program_age_range',
            '_earlystart_es_program_cta_text',
            '_earlystart_es_program_features',
            '_earlystart_es_program_hero_description',
            '_earlystart_es_program_hero_title',
            '_earlystart_es_program_prism_description',
            '_earlystart_es_program_prism_focus_items',
            '_earlystart_es_program_prism_title',
            '_earlystart_es_program_schedule_items',
            '_earlystart_es_program_schedule_title',
            '_earlystart_es_team_member_title',
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
            '_earlystart_ai_fallback_cache',
            '_earlystart_last_validated',
            '_earlystart_place_id',
            '_earlystart_amenities',
            '_earlystart_caps_accepted',
            '_earlystart_ga_pre_k_accepted',
            '_earlystart_google_maps_cid',
            '_earlystart_is_event_venue',
            '_earlystart_learning_resource',
            '_earlystart_license_number',
            '_earlystart_open_house_date',
            '_earlystart_security_cameras',
            '_earlystart_special_announcement',
            'earlystart_faq_items',
            'seo_llm_description',
            'seo_llm_title',
            'seo_llm_when_to_recommend',
            'seo_llm_primary_intent',
            'seo_llm_target_queries',
            'seo_llm_key_differentiators',
            'seo_llm_prompt',
            'seo_llm_context',
            'seo_llm_citation_facts',
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
            'seo_llm_rating_value',
            'seo_llm_rating_count',
            'location_enrollment_steps',
            'location_events',
            'location_media',
            'location_howto',
            'location_citation_facts',
            'location_advanced_schema',
            'location_price_min',
            'location_price_max',
            'location_price_currency',
            'location_price_frequency',
            'location_video_tour_url',
            'location_video_thumbnail',
            'location_video_duration',
            'location_availability_status',
            'location_spots_available',
            'program_locations_served',
            'program_prerequisites',
            'program_related',
            'city_county',
            'city_intro_text',
            'city_neighborhoods',
            'city_nearby_locations',
            'city_hero_image',
            'related_location_ids',
            'alternate_url_en',
            'alternate_url_es',
            '_earlystart_show_in_newsroom',
            '_gmb_last_sync',
            '_gmb_rating',
            '_gmb_review_count',
            '_gmb_hours',
            '_gmb_reviews',
            '_author_team_member',
            '_career_external_url',
            '_career_salary',
            '_career_salary_currency',
            '_career_salary_unit',
            '_career_type',
            '_career_location',
            '_career_date_posted',
            '_wp_attachment_image_alt',
            '_yoast_wpseo_title',
            '_yoast_wpseo_metadesc',
            'meta_keywords',
            'city_name',
            'city_state',
            'city_major_road',
            'city_employers',
            'geo_lat',
            'geo_lng',
            'earlystart_location_address',
            'earlystart_location_city',
            'location_address',
            'location_ages_served',
            'location_capacity',
            'location_city',
            'location_county',
            'location_credentials',
            'location_decal_licensed',
            'location_description',
            'location_director_bio',
            'location_director_name',
            'location_director_photo',
            'location_email',
            'location_facebook',
            'location_gmb_url',
            'location_google_rating',
            'location_hero_gallery',
            'location_hero_review_author',
            'location_hero_review_text',
            'location_hero_subtitle',
            'location_hours',
            'location_lat',
            'location_latitude',
            'location_lng',
            'location_longitude',
            'location_phone',
            'location_quality_rated',
            'location_reviews',
            'location_school_pickups',
            'location_seo_content_text',
            'location_seo_content_title',
            'location_service_areas',
            'location_short_description',
            'location_special_programs',
            'location_state',
            'location_tagline',
            'location_tour_booking_link',
            'location_zip',
            'program_age_range',
            'program_anchor_slug',
            'program_cta_text',
            'program_features',
            'program_hero_description',
            'program_hero_title',
            'program_prism_description',
            'program_prism_focus_items',
            'program_prism_title',
            'program_schedule_items',
            'program_schedule_title',
            'team_member_title',
            'schema_org_type',
            'schema_org_data',
            'schema_org_area_served',
            'schema_org_description',
            'schema_org_email',
            'schema_org_logo',
            'schema_org_name',
            'schema_org_telephone',
            'schema_org_url',
            'schema_loc_type',
            'schema_loc_data',
            'schema_loc_description',
            'schema_loc_email',
            'schema_loc_name',
            'schema_loc_opening_hours',
            'schema_loc_payment_accepted',
            'schema_loc_price_range',
            'schema_loc_telephone',
            'schema_prog_type',
            'schema_prog_data',
            'schema_prog_area_served',
            'schema_prog_category',
            'schema_prog_description',
            'schema_prog_name',
            'schema_prog_provider_name',
            'schema_prog_service_type',
        ];

        if (!is_array($saved)) {
            $saved = [];
        }

        $inventory = self::get_theme_meta_key_inventory();

        return self::normalize_allowlist(array_merge(
            $defaults,
            self::discover_seo_meta_keys((array) ($inventory['exact'] ?? [])),
            $saved
        ));
    }

    public static function get_plugin_setting_allowlist(): array
    {
        $discovered_settings = array_filter(
            array_merge(self::discover_theme_option_keys(), self::discover_registered_option_keys()),
            static function ($key) {
                return is_string($key) && preg_match('/^(earlystart_|chroma_)/', $key);
            }
        );

        return self::normalize_allowlist(array_merge([
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
        ], $discovered_settings));
    }

    public static function get_registered_setting_details(string $key): array
    {
        global $wp_registered_settings;

        $key = trim($key);
        if ($key === '' || !is_array($wp_registered_settings) || !isset($wp_registered_settings[$key]) || !is_array($wp_registered_settings[$key])) {
            return [];
        }

        return $wp_registered_settings[$key];
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

    public static function is_sensitive_option_key(string $key): bool
    {
        $key = sanitize_key($key);
        if (in_array($key, self::get_sensitive_option_keys(), true)) {
            return true;
        }

        return (bool) preg_match('/(^|_)(api_?key|secret|token|password|private_?key|webhook(_url)?|bearer|client_secret)(_|$)/', $key);
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

    public static function sanitize_option_for_storage_by_key(string $key, $value)
    {
        $key = sanitize_key($key);

        if ($key === 'earlystart_openai_api_key' && class_exists('\earlystart_LLM_Client')) {
            return \earlystart_LLM_Client::sanitize_api_key_option($value);
        }

        $details = self::get_registered_setting_details($key);
        $callback = $details['sanitize_callback'] ?? null;
        if ($callback && is_callable($callback)) {
            if (self::is_json_setting_callback($callback) && (is_array($value) || is_object($value))) {
                $value = wp_json_encode(self::sanitize_mixed_for_storage_preserve_keys($value));
            }

            return call_user_func($callback, $value);
        }

        $type = isset($details['type']) ? strtolower((string) $details['type']) : '';
        if ($type === 'boolean') {
            return self::truthy($value);
        }
        if ($type === 'integer') {
            return (int) $value;
        }
        if ($type === 'number') {
            return is_numeric($value) ? (float) $value : 0.0;
        }
        if (in_array($type, ['array', 'object'], true)) {
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return self::sanitize_mixed_for_storage_preserve_keys($decoded);
                }
            }

            return self::sanitize_mixed_for_storage_preserve_keys($value);
        }

        return self::sanitize_mixed_for_storage($value);
    }

    private static function is_json_setting_callback($callback): bool
    {
        if (is_array($callback)) {
            $callback = end($callback);
        }

        return is_string($callback) && in_array(strtolower($callback), [
            'earlystart_contact_sanitize_json',
            'earlystart_career_sanitize_json',
            'earlystart_acquisition_sanitize_json',
        ], true);
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

        self::refresh_llms_txt_if_available();

        do_action('chroma_agent_api_content_updated', $post_id);
    }

    public static function invalidate_global_caches(string $reason = 'global'): void
    {
        global $wpdb;

        if (isset($wpdb) && $wpdb instanceof \wpdb) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
                    '_transient_earlystart_%',
                    '_transient_timeout_earlystart_%'
                )
            );
        }

        self::refresh_llms_txt_if_available();

        do_action('chroma_agent_api_global_updated', $reason);
    }

    public static function invalidate_term_caches(int $term_id, string $taxonomy = ''): void
    {
        if ($term_id > 0) {
            clean_term_cache([$term_id], $taxonomy);
        }

        self::invalidate_global_caches('term');
    }

    public static function refresh_llms_txt_if_available(): void
    {
        if (!class_exists('\earlystart_LLMs_Txt_Generator') || !method_exists('\earlystart_LLMs_Txt_Generator', 'refresh_file')) {
            return;
        }

        try {
            \earlystart_LLMs_Txt_Generator::refresh_file();
        } catch (\Throwable $e) {
            if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
                error_log('Chroma Agent API could not refresh llms.txt: ' . $e->getMessage());
            }
        }
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
            'exact' => self::normalize_allowlist(array_merge(
                $surfaces['meta'],
                self::discover_registered_post_meta_keys(),
                self::discover_existing_public_post_meta_keys()
            )),
            'patterns' => $surfaces['meta_patterns'],
        ];
    }

    public static function get_customizer_option_allowlist(): array
    {
        return self::normalize_allowlist(self::discover_customizer_option_keys());
    }

    public static function get_term_meta_key_inventory(): array
    {
        return self::normalize_allowlist(array_merge(
            [
                'region_color_bg',
                'region_color_text',
                'region_color_border',
            ],
            self::discover_registered_term_meta_keys(),
            self::discover_existing_public_term_meta_keys()
        ));
    }

    public static function get_seo_meta_patterns(): array
    {
        $inventory = self::get_theme_meta_key_inventory();

        return self::normalize_allowlist(array_filter(
            (array) ($inventory['patterns'] ?? []),
            [__CLASS__, 'is_seo_meta_key']
        ));
    }

    private static function discover_theme_mod_keys(): array
    {
        $surfaces = self::discover_theme_surfaces();
        return $surfaces['theme_mods'];
    }

    private static function discover_customizer_option_keys(): array
    {
        $surfaces = self::discover_theme_surfaces();
        return $surfaces['customizer_options'];
    }

    private static function discover_theme_option_keys(): array
    {
        $surfaces = self::discover_theme_surfaces();
        return $surfaces['options'];
    }

    private static function discover_seo_option_keys(): array
    {
        return self::normalize_allowlist(array_filter(
            array_merge(self::discover_theme_option_keys(), self::discover_registered_option_keys()),
            static function (string $key): bool {
                return (bool) preg_match('/^(earlystart_(seo|llm|breadcrumbs|citation|combo|enable|indexnow|faq|validator|careers|sitemap|multilingual|google_places|openai)|chroma_seo_)/', $key);
            }
        ));
    }

    private static function discover_seo_meta_keys(array $keys): array
    {
        return self::normalize_allowlist(array_filter($keys, [__CLASS__, 'is_seo_meta_key']));
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
            'customizer_options' => [],
            'options' => [],
        ];

        if (!function_exists('get_stylesheet_directory')) {
            return $cached;
        }

        $meta = [];
        $meta_patterns = [];
        $theme_mods = [];
        $customizer_options = [];
        $options = [];

        foreach (self::discover_surface_scan_roots() as $root) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if (!$file instanceof \SplFileInfo || !$file->isFile()) {
                    continue;
                }

                $path = $file->getPathname();
                if (!self::should_scan_surface_file($path)) {
                    continue;
                }

                $contents = file_get_contents($path);
                if (!is_string($contents) || $contents === '') {
                    continue;
                }

                self::collect_regex_keys(
                    $contents,
                    '/(?:get_post_meta|update_post_meta|delete_post_meta|metadata_exists|register_post_meta|add_post_meta)\s*\([^,]+,\s*([\'"])(.*?)\1/s',
                    $meta,
                    $meta_patterns
                );
                self::collect_regex_keys(
                    $contents,
                    '/name\s*=\s*([\'"])(.*?)\1/s',
                    $meta,
                    $meta_patterns,
                    '/^(about_|acquisition_|bridge_|careers_|contact_|consultation_|curriculum_|employers_|families_|faq_|global_|hipaa_|home_|locations_|newsroom_|parents_|privacy_|schedule_|stories_|team_|team_member_|tos_|tou_|program_|location_|city_|schema_|meta_|alternate_|_earlystart_|seo_llm_|earlystart_|_gmb_|_career_|_author_|_yoast_)/'
                );
                self::collect_regex_keys(
                    $contents,
                    '/\$_POST\s*\[\s*([\'"])(.*?)\1\s*\]/s',
                    $meta,
                    $meta_patterns,
                    '/^(about_|acquisition_|bridge_|careers_|contact_|consultation_|curriculum_|employers_|families_|faq_|global_|hipaa_|home_|locations_|newsroom_|parents_|privacy_|schedule_|stories_|team_|team_member_|tos_|tou_|program_|location_|city_|schema_|meta_|alternate_|_earlystart_|seo_llm_|earlystart_|_gmb_|_career_|_author_|_yoast_)/'
                );
                self::collect_meta_save_array_keys($contents, $meta, $meta_patterns);
                $ignored_patterns = [];
                self::collect_customizer_setting_keys($contents, $theme_mods, $customizer_options);
                self::collect_regex_keys(
                    $contents,
                    '/(?:get_theme_mod|set_theme_mod|earlystart_get_theme_mod)\s*\(\s*([\'"])(.*?)\1/s',
                    $theme_mods,
                    $ignored_patterns
                );
                $ignored_patterns = [];
                self::collect_regex_keys(
                    $contents,
                    '/(?:get_option|update_option|delete_option)\s*\(\s*([\'"])(.*?)\1/s',
                    $options,
                    $ignored_patterns,
                    '/^(blogname|blogdescription|show_on_front|page_on_front|page_for_posts|earlystart_|chroma_)/'
                );
                $ignored_patterns = [];
                self::collect_regex_keys(
                    $contents,
                    '/register_setting\s*\(\s*([\'"])(.*?)\1\s*,\s*([\'"])(.*?)\3/s',
                    $options,
                    $ignored_patterns,
                    '/^(earlystart_|chroma_)/',
                    4
                );
            }
        }

        $cached = [
            'meta' => self::normalize_allowlist(array_keys($meta)),
            'meta_patterns' => self::normalize_allowlist(array_keys($meta_patterns)),
            'theme_mods' => self::normalize_allowlist(array_keys($theme_mods)),
            'customizer_options' => self::normalize_allowlist(array_keys($customizer_options)),
            'options' => self::normalize_allowlist(array_keys($options)),
        ];

        return $cached;
    }

    private static function discover_surface_scan_roots(): array
    {
        $roots = [];

        foreach ([get_stylesheet_directory(), get_template_directory()] as $dir) {
            if (is_string($dir) && $dir !== '' && is_dir($dir)) {
                $roots[] = $dir;
            }
        }

        if (defined('WP_PLUGIN_DIR') && is_string(WP_PLUGIN_DIR) && is_dir(WP_PLUGIN_DIR)) {
            $roots[] = WP_PLUGIN_DIR;
        }

        return array_values(array_unique($roots));
    }

    private static function should_scan_surface_file(string $path): bool
    {
        if (substr($path, -4) !== '.php') {
            return false;
        }

        foreach (['node_modules', 'vendor', 'cache', 'logs'] as $segment) {
            if (strpos($path, DIRECTORY_SEPARATOR . $segment . DIRECTORY_SEPARATOR) !== false) {
                return false;
            }
        }

        return true;
    }

    private static function discover_registered_post_meta_keys(): array
    {
        if (!function_exists('get_registered_meta_keys') || !function_exists('get_post_types')) {
            return [];
        }

        $keys = [];
        foreach (get_post_types([], 'names') as $post_type) {
            $registered = get_registered_meta_keys('post', (string) $post_type);
            if (!is_array($registered)) {
                continue;
            }

            foreach (array_keys($registered) as $key) {
                $keys[] = (string) $key;
            }
        }

        return self::normalize_allowlist($keys);
    }

    private static function discover_registered_term_meta_keys(): array
    {
        if (!function_exists('get_registered_meta_keys') || !function_exists('get_taxonomies')) {
            return [];
        }

        $keys = [];
        foreach (get_taxonomies(['public' => true], 'names') as $taxonomy) {
            $registered = get_registered_meta_keys('term', (string) $taxonomy);
            if (!is_array($registered)) {
                continue;
            }

            foreach (array_keys($registered) as $key) {
                $keys[] = (string) $key;
            }
        }

        return self::normalize_allowlist($keys);
    }

    private static function discover_registered_option_keys(): array
    {
        global $wp_registered_settings;

        if (!is_array($wp_registered_settings)) {
            return [];
        }

        $keys = [];
        foreach (array_keys($wp_registered_settings) as $key) {
            if (is_string($key) && preg_match('/^(blogname|blogdescription|show_on_front|page_on_front|page_for_posts|earlystart_|chroma_)/', $key)) {
                $keys[] = $key;
            }
        }

        return self::normalize_allowlist($keys);
    }

    private static function discover_existing_public_post_meta_keys(): array
    {
        global $wpdb;

        if (!isset($wpdb) || !$wpdb instanceof \wpdb || !function_exists('get_post_types')) {
            return [];
        }

        $post_types = get_post_types(['public' => true], 'names');
        if (empty($post_types)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($post_types), '%s'));
        $sql = "SELECT DISTINCT pm.meta_key
            FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE p.post_type IN ({$placeholders})
            AND pm.meta_key <> ''
            ORDER BY pm.meta_key ASC
            LIMIT 1000";

        $keys = $wpdb->get_col($wpdb->prepare($sql, array_values($post_types)));
        if (!is_array($keys)) {
            return [];
        }

        return self::normalize_allowlist(array_filter(array_map('strval', $keys), static function (string $key): bool {
            return self::is_editable_post_meta_key($key);
        }));
    }

    private static function discover_existing_public_term_meta_keys(): array
    {
        global $wpdb;

        if (!isset($wpdb) || !$wpdb instanceof \wpdb || !function_exists('get_taxonomies')) {
            return [];
        }

        $taxonomies = get_taxonomies(['public' => true], 'names');
        if (empty($taxonomies)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($taxonomies), '%s'));
        $sql = "SELECT DISTINCT tm.meta_key
            FROM {$wpdb->termmeta} tm
            INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = tm.term_id
            WHERE tt.taxonomy IN ({$placeholders})
            AND tm.meta_key <> ''
            ORDER BY tm.meta_key ASC
            LIMIT 500";

        $keys = $wpdb->get_col($wpdb->prepare($sql, array_values($taxonomies)));
        if (!is_array($keys)) {
            return [];
        }

        return self::normalize_allowlist(array_filter(array_map('strval', $keys), static function (string $key): bool {
            return self::is_editable_term_meta_key($key);
        }));
    }

    private static function is_editable_post_meta_key(string $key): bool
    {
        if ($key === '') {
            return false;
        }

        $blocked_prefixes = [
            '_edit_',
            '_enclose',
            '_oembed_',
            '_pingme',
            '_wp_attached_',
            '_wp_attachment_',
            '_wp_old_',
            '_wp_trash_',
        ];

        foreach ($blocked_prefixes as $prefix) {
            if (strpos($key, $prefix) === 0) {
                return false;
            }
        }

        if (in_array($key, ['_thumbnail_id', '_wp_page_template'], true)) {
            return false;
        }

        if ($key[0] !== '_') {
            return true;
        }

        return (bool) preg_match('/^_(earlystart|yoast|gmb|career|author)_/', $key);
    }

    private static function is_editable_term_meta_key(string $key): bool
    {
        if ($key === '') {
            return false;
        }

        if ($key[0] !== '_') {
            return true;
        }

        return strpos($key, '_earlystart_') === 0;
    }

    private static function collect_customizer_setting_keys(string $contents, array &$theme_mods, array &$customizer_options): void
    {
        if (!preg_match_all('/add_setting\s*\(\s*([\'"])(.*?)\1/s', $contents, $matches, PREG_OFFSET_CAPTURE)) {
            return;
        }

        foreach ((array) ($matches[2] ?? []) as $match) {
            $key = trim((string) ($match[0] ?? ''));
            $offset = (int) ($match[1] ?? 0);

            if ($key === '' || strpos($key, '<?php') !== false || strpos($key, '?>') !== false) {
                continue;
            }

            $snippet = substr($contents, $offset, 1800);
            $next_control = strpos($snippet, 'add_control');
            if ($next_control !== false) {
                $snippet = substr($snippet, 0, $next_control);
            }

            if (preg_match('/[\'"]type[\'"]\s*=>\s*[\'"]option[\'"]/', $snippet)) {
                $customizer_options[$key] = true;
                unset($theme_mods[$key]);
                continue;
            }

            if (!isset($customizer_options[$key])) {
                $theme_mods[$key] = true;
            }
        }
    }

    private static function collect_meta_save_array_keys(string $contents, array &$meta, array &$meta_patterns): void
    {
        if (!preg_match_all('/\$(?:fields|meta_fields|text_fields|textarea_fields|checkbox_fields|url_fields|number_fields|image_fields|json_fields)\s*=\s*(?:array\s*\(|\[)([\s\S]*?)(?:\);\s*|\];)/', $contents, $matches)) {
            return;
        }

        foreach ((array) ($matches[1] ?? []) as $body) {
            if (!is_string($body) || $body === '') {
                continue;
            }

            if (preg_match_all('/([\'"])([A-Za-z0-9_:\-]+)\1\s*=>/', $body, $key_matches)) {
                foreach ((array) ($key_matches[2] ?? []) as $raw_key) {
                    self::add_discovered_meta_key((string) $raw_key, $meta, $meta_patterns);
                }
            }

            if (preg_match_all('/(?:^|,)\s*([\'"])([A-Za-z0-9_:\-]+)\1\s*(?:,|$)/m', $body, $value_matches)) {
                foreach ((array) ($value_matches[2] ?? []) as $raw_key) {
                    self::add_discovered_meta_key((string) $raw_key, $meta, $meta_patterns);
                }
            }
        }
    }

    private static function add_discovered_meta_key(string $key, array &$meta, array &$meta_patterns): void
    {
        $key = self::normalize_html_field_key($key);
        if ($key === '' || !self::looks_like_editable_surface_key($key)) {
            return;
        }

        if (strpos($key, '*') !== false) {
            $meta_patterns[$key] = true;
            return;
        }

        $meta[$key] = true;
    }

    private static function looks_like_editable_surface_key(string $key): bool
    {
        if ($key === '' || strpos($key, '[') !== false || strpos($key, ']') !== false) {
            return false;
        }

        if (preg_match('/(?:nonce|nonce_field|security|action|post_type|submit|save|delete|bulk)$/', $key)) {
            return false;
        }

        return (bool) preg_match('/^(about_|acquisition_|bridge_|careers_|contact_|consultation_|curriculum_|employers_|families_|faq_|global_|hipaa_|home_|locations_|newsroom_|parents_|privacy_|schedule_|stories_|team_|team_member_|tos_|tou_|program_|location_|city_|schema_|meta_|alternate_|_earlystart_|seo_llm_|earlystart_|_gmb_|_career_|_author_|_yoast_)/', $key);
    }

    private static function collect_regex_keys(
        string $contents,
        string $pattern,
        array &$exact,
        ?array &$patterns = null,
        ?string $allow_pattern = null,
        int $match_index = 2
    ): void {
        if (!preg_match_all($pattern, $contents, $matches)) {
            return;
        }

        foreach ((array) ($matches[$match_index] ?? []) as $raw_key) {
            $key = trim((string) $raw_key);
            if ($key === '') {
                continue;
            }

            if (strpos($key, '<?') !== false) {
                $key = preg_replace('/<\?(?:php|=)?[\s\S]*?\?>/', '*', $key);
                $key = is_string($key) ? trim($key) : '';
            }

            if ($key === '' || strpos($key, '<?') !== false || strpos($key, '?>') !== false) {
                continue;
            }

            $key = self::normalize_html_field_key($key);
            if ($key === '') {
                continue;
            }

            $allow_key = str_replace('*', '1', $key);
            if ($allow_pattern !== null && !preg_match($allow_pattern, $allow_key)) {
                continue;
            }

            if (strpos($key, '{$') !== false) {
                if ($patterns !== null) {
                    $patterns[preg_replace('/\{\$[^}]+\}/', '*', $key)] = true;
                }
                continue;
            }

            if (strpos($key, '*') !== false) {
                if ($patterns !== null) {
                    $patterns[$key] = true;
                }
                continue;
            }

            $exact[$key] = true;
        }
    }

    private static function normalize_html_field_key(string $key): string
    {
        $key = trim($key);
        if ($key === '') {
            return '';
        }

        if (substr($key, -2) === '[]') {
            return substr($key, 0, -2);
        }

        if (preg_match('/^([A-Za-z0-9_:\-]+)\[[^\]]+\]$/', $key, $matches)) {
            return (string) $matches[1];
        }

        return $key;
    }

    private static function is_seo_meta_key(string $key): bool
    {
        if ($key === '') {
            return false;
        }

        return (bool) preg_match('/^(seo_llm_|schema_|meta_|alternate_|city_|location_|program_|_earlystart_|_yoast_|_gmb_|_author_|_career_|earlystart_faq_items|related_location_ids)/', $key);
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
