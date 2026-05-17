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

        return self::normalize_allowlist(array_merge(
            $defaults,
            self::discover_theme_mod_keys(),
            $saved
        ));
    }

    public static function get_seo_option_allowlist(): array
    {
        $saved = get_option(self::OPTION_SEO_OPTION_ALLOWLIST, []);
        $defaults = [
            'earlystart_citation_facts',
            'earlystart_llm_brand_voice',
            'earlystart_llm_brand_context',
            'earlystart_seo_phone',
            'earlystart_seo_email',
            'earlystart_seo_phonetic_name',
            'earlystart_validator_batch_size',
            'earlystart_validator_request_delay',
            'earlystart_validator_timeout',
            'earlystart_validator_cache_ttl',
            'earlystart_validator_max_retries',
            'earlystart_validator_email_alerts',
            'earlystart_validator_post_types',
            'earlystart_careers_feed_url',
            'earlystart_combo_auto_publish',
            'earlystart_seo_manual_cities',
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
        ];

        if (!is_array($saved)) {
            $saved = [];
        }

        return self::normalize_allowlist(array_merge($defaults, $saved));
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
