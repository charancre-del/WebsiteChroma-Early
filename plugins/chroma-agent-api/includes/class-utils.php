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
