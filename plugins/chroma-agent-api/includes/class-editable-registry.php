<?php

namespace ChromaAgentAPI;

if (!defined('ABSPATH')) {
    exit;
}

class Editable_Registry
{
    public const VALUE_ROUTE = '/wp-json/chroma-agent/v1/editables/values';

    public static function manifest(array $args = []): array
    {
        $fields = self::fields();
        $group_filter = isset($args['group']) ? sanitize_key((string) $args['group']) : '';
        $storage_filter = isset($args['storage']) ? sanitize_key((string) $args['storage']) : '';

        if ($group_filter !== '' || $storage_filter !== '') {
            $fields = array_values(array_filter($fields, static function ($field) use ($group_filter, $storage_filter) {
                if ($group_filter !== '' && ($field['group'] ?? '') !== $group_filter) {
                    return false;
                }

                return $storage_filter === '' || (($field['storage']['type'] ?? '') === $storage_filter);
            }));
        }

        $groups = [];
        foreach ($fields as $field) {
            $group = (string) ($field['group'] ?? 'other');
            if (!isset($groups[$group])) {
                $groups[$group] = [
                    'id' => $group,
                    'label' => self::group_label($group),
                    'count' => 0,
                ];
            }
            $groups[$group]['count']++;
        }

        return [
            'version' => 1,
            'value_route' => self::VALUE_ROUTE,
            'generated_at' => current_time('mysql', true),
            'field_count' => count($fields),
            'groups' => array_values($groups),
            'fields' => $fields,
        ];
    }

    public static function fields(): array
    {
        static $fields = null;

        if (is_array($fields)) {
            return $fields;
        }

        $map = [];
        self::add_content_fields($map);
        self::add_theme_fields($map);
        self::add_content_meta_fields($map);
        self::add_seo_fields($map);
        self::add_plugin_setting_fields($map);
        self::add_taxonomy_fields($map);
        self::add_menu_fields($map);

        ksort($map);
        $fields = array_values($map);

        return $fields;
    }

    public static function get_field(string $id): ?array
    {
        $id = trim($id);
        foreach (self::fields() as $field) {
            if ((string) $field['id'] === $id) {
                return $field;
            }
        }

        return null;
    }

    public static function field_ids_for_target(array $target): array
    {
        $ids = [];
        foreach (self::fields() as $field) {
            if (empty(self::target_errors($field, $target))) {
                $ids[] = (string) $field['id'];
            }
        }

        return $ids;
    }

    public static function current_key_can(string $scope): bool
    {
        $scope = strtolower(trim($scope));
        if ($scope === '') {
            return true;
        }

        $current_key = Auth::current_key();
        $scopes = is_array($current_key['scopes'] ?? null) ? $current_key['scopes'] : [];

        return in_array($scope, Utils::normalize_scopes($scopes), true);
    }

    public static function target_errors(array $field, array $target): array
    {
        $storage = $field['storage']['type'] ?? '';
        $errors = [];

        if (in_array($storage, ['post_field', 'post_meta', 'featured_image', 'post_taxonomy'], true) && empty($target['post_id'])) {
            $errors[] = 'post_id is required.';
        }

        if (in_array($storage, ['term_field', 'term_meta'], true) && empty($target['term_id'])) {
            $errors[] = 'term_id is required.';
        }

        if ($storage === 'term_field' && empty($target['taxonomy']) && empty($field['storage']['taxonomy'])) {
            $errors[] = 'taxonomy is required.';
        }

        if ($storage === 'menu_item' && empty($target['menu_item_id'])) {
            $errors[] = 'menu_item_id is required.';
        }

        return $errors;
    }

    public static function read_value(array $field, array $target, bool $redact = true)
    {
        $storage = $field['storage'];
        $type = (string) ($storage['type'] ?? '');

        switch ($type) {
            case 'option':
                $value = get_option((string) $storage['key'], null);
                return ($redact && !empty($field['sensitive'])) ? self::redacted_value($value) : $value;

            case 'option_path':
                $value = self::read_option_path((string) $storage['key'], (array) ($storage['path'] ?? []));
                return ($redact && !empty($field['sensitive'])) ? self::redacted_value($value) : $value;

            case 'theme_mod':
                return get_theme_mod((string) $storage['key'], null);

            case 'post_field':
                $post = get_post((int) $target['post_id']);
                if (!$post) {
                    return null;
                }
                $field_name = (string) $storage['field'];
                return property_exists($post, $field_name) ? $post->{$field_name} : null;

            case 'post_meta':
                return get_post_meta((int) $target['post_id'], (string) $storage['key'], true);

            case 'featured_image':
                return (int) get_post_thumbnail_id((int) $target['post_id']);

            case 'post_taxonomy':
                $terms = wp_get_object_terms((int) $target['post_id'], (string) $storage['taxonomy'], ['fields' => 'all']);
                if (is_wp_error($terms)) {
                    return [];
                }
                return array_map([__CLASS__, 'prepare_term'], (array) $terms);

            case 'term_field':
                $term = get_term((int) $target['term_id'], (string) ($target['taxonomy'] ?? ($storage['taxonomy'] ?? '')));
                if (!$term || is_wp_error($term)) {
                    return null;
                }
                $field_name = (string) $storage['field'];
                return property_exists($term, $field_name) ? $term->{$field_name} : null;

            case 'term_meta':
                return get_term_meta((int) $target['term_id'], (string) $storage['key'], true);

            case 'menu_location':
                $locations = get_nav_menu_locations();
                return (int) ($locations[(string) $storage['location']] ?? 0);

            case 'menu_item':
                return self::read_menu_item((int) $target['menu_item_id']);
        }

        return null;
    }

    public static function write_value(array $field, array $target, $value)
    {
        $storage = $field['storage'];
        $type = (string) ($storage['type'] ?? '');
        $new_value = self::sanitize_value($field, $value);

        switch ($type) {
            case 'option':
                $key = (string) $storage['key'];
                if ($new_value === null) {
                    delete_option($key);
                } else {
                    update_option($key, $new_value, false);
                }
                return true;

            case 'option_path':
                return self::write_option_path((string) $storage['key'], (array) ($storage['path'] ?? []), $new_value);

            case 'theme_mod':
                $key = (string) $storage['key'];
                if ($new_value === null) {
                    remove_theme_mod($key);
                } else {
                    set_theme_mod($key, $new_value);
                }
                return true;

            case 'post_field':
                $post_id = (int) $target['post_id'];
                $field_name = (string) $storage['field'];
                $updated = wp_update_post([
                    'ID' => $post_id,
                    $field_name => $new_value,
                ], true);
                if (is_wp_error($updated)) {
                    return $updated;
                }
                Utils::invalidate_content_caches_for_post($post_id);
                return true;

            case 'post_meta':
                $post_id = (int) $target['post_id'];
                $meta_key = (string) $storage['key'];
                if ($new_value === null) {
                    delete_post_meta($post_id, $meta_key);
                } else {
                    update_post_meta($post_id, $meta_key, $new_value);
                }
                Utils::invalidate_content_caches_for_post($post_id);
                return true;

            case 'featured_image':
                $post_id = (int) $target['post_id'];
                if ($new_value === null || (int) $new_value <= 0) {
                    delete_post_thumbnail($post_id);
                } else {
                    set_post_thumbnail($post_id, (int) $new_value);
                }
                Utils::invalidate_content_caches_for_post($post_id);
                return true;

            case 'post_taxonomy':
                $post_id = (int) $target['post_id'];
                $terms = is_array($new_value) ? $new_value : [$new_value];
                $result = wp_set_object_terms($post_id, $terms, (string) $storage['taxonomy'], false);
                if (is_wp_error($result)) {
                    return $result;
                }
                Utils::invalidate_content_caches_for_post($post_id);
                return true;

            case 'term_field':
                $taxonomy = (string) ($target['taxonomy'] ?? ($storage['taxonomy'] ?? ''));
                $result = wp_update_term((int) $target['term_id'], $taxonomy, [
                    (string) $storage['field'] => $new_value,
                ]);
                return is_wp_error($result) ? $result : true;

            case 'term_meta':
                $term_id = (int) $target['term_id'];
                $key = (string) $storage['key'];
                if ($new_value === null) {
                    delete_term_meta($term_id, $key);
                } else {
                    update_term_meta($term_id, $key, $new_value);
                }
                return true;

            case 'menu_location':
                $locations = get_nav_menu_locations();
                $locations[(string) $storage['location']] = (int) $new_value;
                set_theme_mod('nav_menu_locations', $locations);
                return true;

            case 'menu_item':
                return self::write_menu_item((int) $target['menu_item_id'], is_array($new_value) ? $new_value : []);
        }

        return new \WP_Error('caa_editable_storage_unsupported', 'Unsupported editable storage type.', ['status' => 400]);
    }

    private static function read_option_path(string $option_key, array $path)
    {
        if ($option_key === '' || empty($path)) {
            return null;
        }

        $value = get_option($option_key, []);
        foreach ($path as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return null;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    private static function write_option_path(string $option_key, array $path, $value)
    {
        if ($option_key === '' || empty($path)) {
            return new \WP_Error('caa_editable_option_path_invalid', 'Editable option path is invalid.', ['status' => 400]);
        }

        $option = get_option($option_key, []);
        if (!is_array($option)) {
            $option = [];
        }

        $cursor =& $option;
        $last_index = count($path) - 1;
        foreach ($path as $index => $segment) {
            $segment = (string) $segment;
            if ($index === $last_index) {
                if ($value === null) {
                    unset($cursor[$segment]);
                } else {
                    $cursor[$segment] = $value;
                }
                break;
            }

            if (!isset($cursor[$segment]) || !is_array($cursor[$segment])) {
                $cursor[$segment] = [];
            }
            $cursor =& $cursor[$segment];
        }

        update_option($option_key, $option, false);
        return true;
    }

    public static function sanitize_value(array $field, $value)
    {
        if ($value === null) {
            return null;
        }

        $sanitize = (string) ($field['sanitize'] ?? 'mixed');
        $type = (string) ($field['type'] ?? 'mixed');

        if (($type === 'array' || $type === 'object') && is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed !== '' && in_array(substr($trimmed, 0, 1), ['[', '{'], true)) {
                $decoded = json_decode($trimmed, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }
        }

        switch ($sanitize) {
            case 'bool':
                return Utils::truthy($value);

            case 'int':
                return (int) $value;

            case 'number':
                return is_numeric($value) ? (float) $value : 0.0;

            case 'url':
                return esc_url_raw((string) $value);

            case 'email':
                return sanitize_email((string) $value);

            case 'slug':
                return sanitize_title((string) $value);

            case 'key':
                return sanitize_key((string) $value);

            case 'status':
                $status = sanitize_key((string) $value);
                return get_post_status_object($status) ? $status : 'draft';

            case 'html':
                return wp_kses_post((string) $value);

            case 'textarea':
                return sanitize_textarea_field((string) $value);

            case 'embed':
                return Utils::sanitize_embed_html($value);

            case 'classes':
                if (!is_array($value)) {
                    $value = preg_split('/\s+/', (string) $value) ?: [];
                }
                return array_values(array_filter(array_map('sanitize_html_class', $value)));

            case 'array':
            case 'object':
                return Utils::sanitize_mixed_for_storage_preserve_keys($value);

            case 'mixed':
                $key = (string) ($field['storage']['key'] ?? '');
                return $key !== '' ? Utils::sanitize_mixed_for_storage_by_key($key, $value) : Utils::sanitize_mixed_for_storage($value);

            case 'text':
            default:
                return sanitize_text_field((string) $value);
        }
    }

    public static function prepare_term($term): array
    {
        return [
            'term_id' => (int) $term->term_id,
            'taxonomy' => (string) $term->taxonomy,
            'name' => (string) $term->name,
            'slug' => (string) $term->slug,
            'description' => (string) $term->description,
            'parent' => (int) $term->parent,
            'count' => (int) $term->count,
        ];
    }

    public static function read_menu_item(int $item_id): ?array
    {
        $post = get_post($item_id);
        if (!$post) {
            return null;
        }

        $item = wp_setup_nav_menu_item($post);
        if (!$item || empty($item->ID)) {
            return null;
        }

        return [
            'ID' => (int) $item->ID,
            'title' => (string) $item->title,
            'url' => (string) $item->url,
            'description' => (string) $item->description,
            'attr_title' => (string) $item->attr_title,
            'target' => (string) $item->target,
            'classes' => array_values(array_filter((array) $item->classes)),
            'xfn' => (string) $item->xfn,
            'object_id' => (int) $item->object_id,
            'object' => (string) $item->object,
            'type' => (string) $item->type,
            'type_label' => (string) $item->type_label,
            'menu_item_parent' => (int) $item->menu_item_parent,
            'menu_order' => (int) $item->menu_order,
        ];
    }

    private static function add_content_fields(array &$fields): void
    {
        $post_fields = [
            'post_title' => ['Title', 'string', 'text'],
            'post_content' => ['Content', 'html', 'html'],
            'post_excerpt' => ['Excerpt', 'string', 'textarea'],
            'post_name' => ['Slug', 'slug', 'slug'],
            'post_status' => ['Status', 'string', 'status'],
            'menu_order' => ['Menu order', 'integer', 'int'],
            'post_parent' => ['Parent post', 'integer', 'int'],
            'post_author' => ['Author', 'integer', 'int'],
            'post_date' => ['Publish date', 'string', 'text'],
        ];

        foreach ($post_fields as $field => $info) {
            self::add_field($fields, [
                'id' => 'content.' . $field,
                'label' => $info[0],
                'group' => 'content',
                'type' => $info[1],
                'sanitize' => $info[2],
                'storage' => ['type' => 'post_field', 'field' => $field],
                'target' => ['requires' => ['post_id']],
                'read_scope' => 'read:content',
                'write_scope' => 'write:content',
            ]);
        }

        self::add_field($fields, [
            'id' => 'content.page_template',
            'label' => 'Page template',
            'group' => 'content',
            'type' => 'string',
            'sanitize' => 'text',
            'storage' => ['type' => 'post_meta', 'key' => '_wp_page_template'],
            'target' => ['requires' => ['post_id']],
            'read_scope' => 'read:content',
            'write_scope' => 'write:content',
        ]);

        self::add_field($fields, [
            'id' => 'content.featured_media',
            'label' => 'Featured image attachment ID',
            'group' => 'media',
            'type' => 'integer',
            'sanitize' => 'int',
            'storage' => ['type' => 'featured_image'],
            'target' => ['requires' => ['post_id']],
            'read_scope' => 'read:content',
            'write_scope' => 'write:content',
        ]);
    }

    private static function add_theme_fields(array &$fields): void
    {
        foreach (Utils::get_theme_option_allowlist() as $key) {
            self::add_field($fields, [
                'id' => 'theme.option.' . $key,
                'label' => self::label_from_key($key),
                'group' => 'theme',
                'type' => self::guess_type($key),
                'sanitize' => self::guess_sanitizer($key),
                'storage' => ['type' => 'option', 'key' => $key],
                'read_scope' => 'read:theme',
                'write_scope' => 'write:theme',
            ]);
        }

        self::add_global_setting_fields($fields);

        foreach (Utils::get_theme_mod_allowlist() as $key) {
            self::add_field($fields, [
                'id' => 'theme.mod.' . $key,
                'label' => self::label_from_key($key),
                'group' => 'customizer',
                'type' => self::guess_type($key),
                'sanitize' => self::guess_sanitizer($key),
                'storage' => ['type' => 'theme_mod', 'key' => $key],
                'read_scope' => 'read:theme',
                'write_scope' => 'write:theme',
                'multilingual_variant' => substr($key, -3) === '_es',
            ]);
        }
    }

    private static function add_global_setting_fields(array &$fields): void
    {
        $keys = [
            'global_phone',
            'global_email',
            'global_tour_email',
            'global_admissions_email',
            'global_careers_email',
            'global_billing_email',
            'global_media_email',
            'global_privacy_email',
            'global_address',
            'global_city',
            'global_state',
            'global_zip',
            'global_facebook_url',
            'global_instagram_url',
            'global_linkedin_url',
            'global_seo_default_title',
            'global_seo_default_description',
            'global_logo',
        ];

        foreach ($keys as $key) {
            self::add_field($fields, [
                'id' => 'theme.global_setting.' . $key,
                'label' => self::label_from_key($key),
                'group' => 'theme',
                'type' => self::guess_type($key),
                'sanitize' => self::guess_sanitizer($key),
                'storage' => ['type' => 'option_path', 'key' => 'earlystart_global_settings', 'path' => [$key]],
                'read_scope' => 'read:theme',
                'write_scope' => 'write:theme',
            ]);
        }
    }

    private static function add_content_meta_fields(array &$fields): void
    {
        $inventory = Utils::get_theme_meta_key_inventory();
        $keys = array_merge(
            (array) ($inventory['exact'] ?? []),
            self::public_content_meta_keys()
        );
        $keys = Utils::normalize_allowlist($keys);
        $keys = Utils::normalize_allowlist(array_merge($keys, self::spanish_meta_variants($keys)));

        foreach ($keys as $key) {
            self::add_field($fields, [
                'id' => 'content.meta.' . $key,
                'label' => self::label_from_key($key),
                'group' => 'content_meta',
                'type' => self::guess_type($key),
                'sanitize' => self::guess_sanitizer($key),
                'storage' => ['type' => 'post_meta', 'key' => $key],
                'target' => ['requires' => ['post_id']],
                'read_scope' => 'read:content',
                'write_scope' => 'write:content',
                'multilingual_variant' => strpos($key, '_earlystart_es_') === 0,
            ]);
        }

        $taxonomies = get_taxonomies(['public' => true], 'objects');
        foreach ($taxonomies as $taxonomy => $object) {
            self::add_field($fields, [
                'id' => 'content.taxonomy.' . $taxonomy,
                'label' => ($object->label ?: self::label_from_key($taxonomy)) . ' terms',
                'group' => 'taxonomy',
                'type' => 'array',
                'sanitize' => 'array',
                'storage' => ['type' => 'post_taxonomy', 'taxonomy' => $taxonomy],
                'target' => ['requires' => ['post_id']],
                'read_scope' => 'read:content',
                'write_scope' => 'write:content',
            ]);
        }
    }

    private static function add_seo_fields(array &$fields): void
    {
        $sensitive = Utils::get_sensitive_option_keys();
        foreach (Utils::get_seo_option_allowlist() as $key) {
            self::add_field($fields, [
                'id' => 'seo.option.' . $key,
                'label' => self::label_from_key($key),
                'group' => 'seo_settings',
                'type' => self::guess_type($key),
                'sanitize' => self::guess_sanitizer($key),
                'storage' => ['type' => 'option', 'key' => $key],
                'read_scope' => 'read:seo',
                'write_scope' => 'write:seo',
                'sensitive' => in_array($key, $sensitive, true),
            ]);
        }

        $keys = Utils::get_seo_meta_allowlist();
        $keys = Utils::normalize_allowlist(array_merge($keys, self::spanish_meta_variants($keys)));
        foreach ($keys as $key) {
            self::add_field($fields, [
                'id' => 'seo.meta.' . $key,
                'label' => self::label_from_key($key),
                'group' => 'seo_content',
                'type' => self::guess_type($key),
                'sanitize' => self::guess_sanitizer($key),
                'storage' => ['type' => 'post_meta', 'key' => $key],
                'target' => ['requires' => ['post_id']],
                'read_scope' => 'read:seo',
                'write_scope' => 'write:seo',
                'multilingual_variant' => strpos($key, '_earlystart_es_') === 0,
            ]);
        }
    }

    private static function add_plugin_setting_fields(array &$fields): void
    {
        $sensitive = Utils::get_sensitive_option_keys();
        foreach (Utils::get_plugin_setting_allowlist() as $key) {
            self::add_field($fields, [
                'id' => 'plugin.setting.' . $key,
                'label' => self::label_from_key($key),
                'group' => 'plugin_settings',
                'type' => self::guess_type($key),
                'sanitize' => self::guess_sanitizer($key),
                'storage' => ['type' => 'option', 'key' => $key],
                'read_scope' => 'read:settings',
                'write_scope' => 'write:settings',
                'sensitive' => in_array($key, $sensitive, true),
            ]);
        }
    }

    private static function add_taxonomy_fields(array &$fields): void
    {
        foreach (['name', 'slug', 'description', 'parent'] as $term_field) {
            self::add_field($fields, [
                'id' => 'taxonomy.term.' . $term_field,
                'label' => 'Term ' . self::label_from_key($term_field),
                'group' => 'taxonomy',
                'type' => $term_field === 'parent' ? 'integer' : 'string',
                'sanitize' => $term_field === 'parent' ? 'int' : ($term_field === 'slug' ? 'slug' : ($term_field === 'description' ? 'textarea' : 'text')),
                'storage' => ['type' => 'term_field', 'field' => $term_field],
                'target' => ['requires' => ['term_id', 'taxonomy']],
                'read_scope' => 'read:taxonomy',
                'write_scope' => 'write:taxonomy',
            ]);
        }

        foreach (['region_color_bg', 'region_color_text', 'region_color_border'] as $key) {
            self::add_field($fields, [
                'id' => 'taxonomy.meta.' . $key,
                'label' => self::label_from_key($key),
                'group' => 'taxonomy',
                'type' => 'string',
                'sanitize' => 'text',
                'storage' => ['type' => 'term_meta', 'key' => $key],
                'target' => ['requires' => ['term_id']],
                'read_scope' => 'read:taxonomy',
                'write_scope' => 'write:taxonomy',
            ]);
        }
    }

    private static function add_menu_fields(array &$fields): void
    {
        $locations = get_registered_nav_menus();
        foreach ($locations as $location => $label) {
            self::add_field($fields, [
                'id' => 'menu.location.' . $location,
                'label' => 'Menu location: ' . $label,
                'group' => 'menus',
                'type' => 'integer',
                'sanitize' => 'int',
                'storage' => ['type' => 'menu_location', 'location' => $location],
                'read_scope' => 'read:menus',
                'write_scope' => 'write:menus',
            ]);
        }

        self::add_field($fields, [
            'id' => 'menu.item',
            'label' => 'Navigation menu item',
            'group' => 'menus',
            'type' => 'object',
            'sanitize' => 'object',
            'storage' => ['type' => 'menu_item'],
            'target' => ['requires' => ['menu_item_id']],
            'read_scope' => 'read:menus',
            'write_scope' => 'write:menus',
        ]);
    }

    private static function add_field(array &$fields, array $field): void
    {
        $field = array_merge([
            'id' => '',
            'label' => '',
            'description' => '',
            'group' => 'other',
            'type' => 'mixed',
            'sanitize' => 'mixed',
            'storage' => ['type' => 'unknown'],
            'target' => ['requires' => []],
            'read_scope' => '',
            'write_scope' => '',
            'sensitive' => false,
            'multilingual_variant' => false,
            'canonical_route' => self::VALUE_ROUTE,
        ], $field);

        $id = trim((string) $field['id']);
        if ($id === '') {
            return;
        }

        $field['id'] = $id;
        $fields[$id] = $field;
    }

    private static function write_menu_item(int $item_id, array $updates)
    {
        $post = get_post($item_id);
        if (!$post) {
            return new \WP_Error('caa_menu_item_not_found', 'Menu item not found.', ['status' => 404]);
        }

        $item = wp_setup_nav_menu_item($post);
        if (!$item || empty($item->ID)) {
            return new \WP_Error('caa_menu_item_not_found', 'Menu item not found.', ['status' => 404]);
        }

        $menu_id = self::menu_id_for_item($item_id);
        if ($menu_id <= 0) {
            return new \WP_Error('caa_menu_not_found', 'Menu for item not found.', ['status' => 404]);
        }

        $args = [
            'menu-item-title' => isset($updates['title']) ? sanitize_text_field((string) $updates['title']) : (string) $item->title,
            'menu-item-url' => isset($updates['url']) ? esc_url_raw((string) $updates['url']) : (string) $item->url,
            'menu-item-description' => isset($updates['description']) ? sanitize_textarea_field((string) $updates['description']) : (string) $item->description,
            'menu-item-attr-title' => isset($updates['attr_title']) ? sanitize_text_field((string) $updates['attr_title']) : (string) $item->attr_title,
            'menu-item-target' => isset($updates['target']) ? sanitize_key((string) $updates['target']) : (string) $item->target,
            'menu-item-classes' => isset($updates['classes']) ? self::sanitize_menu_classes($updates['classes']) : implode(' ', (array) $item->classes),
            'menu-item-xfn' => isset($updates['xfn']) ? sanitize_text_field((string) $updates['xfn']) : (string) $item->xfn,
            'menu-item-parent-id' => isset($updates['menu_item_parent']) ? (int) $updates['menu_item_parent'] : (int) $item->menu_item_parent,
            'menu-item-position' => isset($updates['menu_order']) ? (int) $updates['menu_order'] : (int) $item->menu_order,
            'menu-item-status' => isset($updates['status']) ? sanitize_key((string) $updates['status']) : 'publish',
        ];

        $result = wp_update_nav_menu_item($menu_id, $item_id, $args);
        return is_wp_error($result) ? $result : true;
    }

    private static function menu_id_for_item(int $item_id): int
    {
        $terms = wp_get_object_terms($item_id, 'nav_menu');
        if (is_wp_error($terms) || empty($terms)) {
            return 0;
        }

        return (int) $terms[0]->term_id;
    }

    private static function sanitize_menu_classes($classes): string
    {
        if (!is_array($classes)) {
            $classes = preg_split('/\s+/', (string) $classes) ?: [];
        }

        return implode(' ', array_values(array_filter(array_map('sanitize_html_class', $classes))));
    }

    private static function redacted_value($value): array
    {
        return [
            'configured' => !empty($value),
            'redacted' => true,
            'value' => null,
        ];
    }

    private static function public_content_meta_keys(): array
    {
        $keys = [
            'home_tour_cta_heading',
            'home_tour_cta_subheading',
            'home_tour_cta_trust_text',
            'home_tour_cta_label',
            'home_tour_cta_url',
            'home_featured_stories_json',
            'approach_hero_eyebrow',
            'approach_hero_heading',
            'approach_hero_highlight',
            'approach_hero_subheading',
            'approach_prism_stats_json',
            'approach_model_cards_json',
            'approach_non_negotiables_json',
            'approach_comparison_json',
            'bridge_hero_eyebrow',
            'bridge_hero_heading',
            'bridge_hero_subheading',
            'bridge_sections_json',
            'consultation_hero_eyebrow',
            'consultation_hero_heading',
            'consultation_hero_subheading',
            'consultation_form_intro',
            'faq_hero_eyebrow',
            'faq_hero_heading',
            'faq_hero_highlight',
            'faq_hero_subheading',
            'faq_categories_json',
            'faq_cta_heading',
            'faq_cta_text',
            'locations_hero_heading',
            'locations_hero_subheading',
            'locations_partner_zone_heading',
            'locations_partner_zone_text',
            'locations_zones_json',
            'families_extra_sections_json',
            'families_early_intervention_json',
            'families_first_30_days_json',
            'families_parent_training_json',
            'families_logistics_faq_json',
            'newsroom_hero_heading',
            'newsroom_hero_subheading',
            'newsroom_cta_heading',
            'newsroom_cta_text',
            'programs_shell_json',
            'team_shell_json',
            'stories_shell_json',
            'schedule_tour_shell_json',
            'acquisition_benefits',
            'acquisition_process',
            'hipaa_last_updated',
            'tou_last_updated',
            'tos_last_updated',
        ];

        for ($i = 1; $i <= 12; $i++) {
            $keys[] = 'tou_section' . $i . '_title';
            $keys[] = 'tou_section' . $i . '_content';
            $keys[] = 'tos_section' . $i . '_title';
            $keys[] = 'tos_section' . $i . '_content';
        }

        for ($i = 1; $i <= 8; $i++) {
            $keys[] = 'privacy_section' . $i . '_title';
            $keys[] = 'privacy_section' . $i . '_content';
            $keys[] = 'hipaa_section' . $i . '_title';
            $keys[] = 'hipaa_section' . $i . '_content';
        }

        return $keys;
    }

    private static function spanish_meta_variants(array $keys): array
    {
        $variants = [];
        foreach ($keys as $key) {
            if (!is_string($key) || $key === '' || strpos($key, '_earlystart_es_') === 0 || strpos($key, '_') === 0) {
                continue;
            }
            $variants[] = '_earlystart_es_' . $key;
        }

        return $variants;
    }

    private static function guess_type(string $key): string
    {
        if (preg_match('/(^|_)(id|height|delay|limit|size|ttl|timeout|retries|count|order|parent|radius|max|min)$/', $key)) {
            return 'integer';
        }

        if (preg_match('/(enabled|disabled|lazy_load|alerts|canonical|slash|markup|publish|show_|enable_|featured|rated)/', $key)) {
            return 'boolean';
        }

        if (preg_match('/(_json|_items|_fields|_patterns|_links|_urls|_cities|_locations|_schemas|_data|_facts|_overrides|translations)/', $key)) {
            return 'array';
        }

        if (preg_match('/(url|link|feed)/', $key)) {
            return 'url';
        }

        if (preg_match('/email/', $key)) {
            return 'email';
        }

        if (preg_match('/(content|description|excerpt|bio|text|context|voice|prompt|embed)/', $key)) {
            return 'html';
        }

        return 'string';
    }

    private static function guess_sanitizer(string $key): string
    {
        if (in_array($key, Utils::get_embed_meta_keys(), true) || preg_match('/embed$/', $key)) {
            return 'embed';
        }

        $type = self::guess_type($key);
        if ($type === 'integer') {
            return 'int';
        }
        if ($type === 'boolean') {
            return 'bool';
        }
        if ($type === 'array') {
            return 'array';
        }
        if ($type === 'url') {
            return 'url';
        }
        if ($type === 'email') {
            return 'email';
        }
        if ($type === 'html') {
            return preg_match('/(content|description|excerpt|bio|text|context|voice|prompt)/', $key) ? 'html' : 'text';
        }
        if (preg_match('/slug|post_name/', $key)) {
            return 'slug';
        }

        return 'text';
    }

    private static function label_from_key(string $key): string
    {
        $key = preg_replace('/^_earlystart_es_/', 'Spanish ', $key);
        $key = preg_replace('/^_earlystart_/', '', (string) $key);
        $key = str_replace(['_', '-'], ' ', (string) $key);
        return ucwords(trim($key));
    }

    private static function group_label(string $group): string
    {
        $labels = [
            'content' => 'Public Content',
            'content_meta' => 'Public Content Metaboxes',
            'customizer' => 'Customizer Theme Mods',
            'media' => 'Media',
            'menus' => 'Navigation Menus',
            'plugin_settings' => 'Plugin Settings',
            'seo_content' => 'SEO Plugin Content',
            'seo_settings' => 'SEO Plugin Settings',
            'taxonomy' => 'Tags, Taxonomies, and Terms',
            'theme' => 'Theme Options',
        ];

        return $labels[$group] ?? self::label_from_key($group);
    }
}
