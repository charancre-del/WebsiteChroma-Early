<?php

namespace ChromaAgentAPI\Routes;

use ChromaAgentAPI\Auth;
use ChromaAgentAPI\Audit_Log;
use ChromaAgentAPI\Diff;
use ChromaAgentAPI\Editable_Registry;
use ChromaAgentAPI\Snapshot_Store;
use ChromaAgentAPI\Utils;
use WP_REST_Request;

if (!defined('ABSPATH')) {
    exit;
}

class Editables_Routes
{
    private const NS = 'chroma-agent/v1';

    public static function register(): void
    {
        register_rest_route(self::NS, '/editables', [
            'methods' => 'GET',
            'callback' => [__CLASS__, 'manifest'],
            'permission_callback' => [__CLASS__, 'allow_any_valid_key'],
        ]);

        register_rest_route(self::NS, '/editables/values', [
            [
                'methods' => 'GET',
                'callback' => [__CLASS__, 'get_values'],
                'permission_callback' => [__CLASS__, 'allow_any_valid_key'],
            ],
            [
                'methods' => 'PATCH,POST',
                'callback' => [__CLASS__, 'set_values'],
                'permission_callback' => [__CLASS__, 'allow_any_valid_key'],
            ],
        ]);

        register_rest_route(self::NS, '/editables/terms', [
            [
                'methods' => 'GET',
                'callback' => [__CLASS__, 'list_terms'],
                'permission_callback' => [__CLASS__, 'read_taxonomy_permission'],
            ],
            [
                'methods' => 'POST',
                'callback' => [__CLASS__, 'create_term'],
                'permission_callback' => [__CLASS__, 'write_taxonomy_permission'],
            ],
        ]);

        register_rest_route(self::NS, '/editables/terms/(?P<term_id>\d+)', [
            [
                'methods' => 'GET',
                'callback' => [__CLASS__, 'get_term'],
                'permission_callback' => [__CLASS__, 'read_taxonomy_permission'],
            ],
            [
                'methods' => 'PATCH,POST',
                'callback' => [__CLASS__, 'update_term'],
                'permission_callback' => [__CLASS__, 'write_taxonomy_permission'],
            ],
            [
                'methods' => 'DELETE',
                'callback' => [__CLASS__, 'delete_term'],
                'permission_callback' => [__CLASS__, 'write_taxonomy_permission'],
            ],
        ]);

        register_rest_route(self::NS, '/editables/menus', [
            [
                'methods' => 'GET',
                'callback' => [__CLASS__, 'list_menus'],
                'permission_callback' => [__CLASS__, 'read_menus_permission'],
            ],
            [
                'methods' => 'POST',
                'callback' => [__CLASS__, 'create_menu'],
                'permission_callback' => [__CLASS__, 'write_menus_permission'],
            ],
        ]);

        register_rest_route(self::NS, '/editables/menus/items', [
            'methods' => 'POST',
            'callback' => [__CLASS__, 'create_menu_item'],
            'permission_callback' => [__CLASS__, 'write_menus_permission'],
        ]);

        register_rest_route(self::NS, '/editables/menus/items/(?P<item_id>\d+)', [
            [
                'methods' => 'GET',
                'callback' => [__CLASS__, 'get_menu_item'],
                'permission_callback' => [__CLASS__, 'read_menus_permission'],
            ],
            [
                'methods' => 'PATCH,POST',
                'callback' => [__CLASS__, 'update_menu_item'],
                'permission_callback' => [__CLASS__, 'write_menus_permission'],
            ],
            [
                'methods' => 'DELETE',
                'callback' => [__CLASS__, 'delete_menu_item'],
                'permission_callback' => [__CLASS__, 'write_menus_permission'],
            ],
        ]);
    }

    public static function allow_any_valid_key(WP_REST_Request $request)
    {
        return Auth::authorize($request, []);
    }

    public static function read_taxonomy_permission(WP_REST_Request $request)
    {
        return Auth::authorize($request, ['read:taxonomy']);
    }

    public static function write_taxonomy_permission(WP_REST_Request $request)
    {
        return Auth::authorize($request, ['write:taxonomy']);
    }

    public static function read_menus_permission(WP_REST_Request $request)
    {
        return Auth::authorize($request, ['read:menus']);
    }

    public static function write_menus_permission(WP_REST_Request $request)
    {
        return Auth::authorize($request, ['write:menus']);
    }

    public static function manifest(WP_REST_Request $request)
    {
        return rest_ensure_response([
            'success' => true,
            'data' => Editable_Registry::manifest([
                'group' => $request->get_param('group'),
                'storage' => $request->get_param('storage'),
            ]),
        ]);
    }

    public static function get_values(WP_REST_Request $request)
    {
        $target = self::target_from_request($request);
        $ids = self::ids_from_request($request);
        if (empty($ids)) {
            $ids = Editable_Registry::field_ids_for_target($target);
        }

        $data = [];
        $blocked = [];
        $errors = [];

        foreach ($ids as $id) {
            $field = Editable_Registry::get_field($id);
            if (!$field) {
                $errors[$id] = 'Unknown editable field.';
                continue;
            }

            $scope = (string) ($field['read_scope'] ?? '');
            if (!Editable_Registry::current_key_can($scope)) {
                $blocked[$id] = [
                    'required_scope' => $scope,
                    'reason' => 'API key does not grant the required read scope.',
                ];
                continue;
            }

            $target_errors = Editable_Registry::target_errors($field, $target);
            if (!empty($target_errors)) {
                $errors[$id] = $target_errors;
                continue;
            }

            $data[$id] = Editable_Registry::read_value($field, $target, true);
        }

        return rest_ensure_response([
            'success' => empty($errors),
            'target' => $target,
            'blocked_fields' => $blocked,
            'errors' => $errors,
            'data' => $data,
        ]);
    }

    public static function set_values(WP_REST_Request $request)
    {
        $payload = self::payload($request);
        $target = isset($payload['target']) && is_array($payload['target']) ? self::normalize_target($payload['target']) : self::target_from_request($request);
        $updates = isset($payload['updates']) && is_array($payload['updates']) ? $payload['updates'] : [];
        $dry_run = Utils::truthy($payload['dry_run'] ?? false);
        $strict_write = Utils::truthy($payload['strict_write'] ?? false);

        if (empty($updates)) {
            return new \WP_Error('caa_editables_missing_updates', 'updates is required and must be an object keyed by editable field id.', ['status' => 400]);
        }

        $before = [];
        $after = [];
        $live = [];
        $blocked = [];
        $errors = [];
        $snapshot_ids = [];
        $write_mismatches = [];

        foreach ($updates as $id => $value) {
            $id = trim((string) $id);
            $field = Editable_Registry::get_field($id);
            if (!$field) {
                $errors[$id] = 'Unknown editable field.';
                continue;
            }

            $scope = (string) ($field['write_scope'] ?? '');
            if (!Editable_Registry::current_key_can($scope)) {
                $blocked[$id] = [
                    'required_scope' => $scope,
                    'reason' => 'API key does not grant the required write scope.',
                ];
                continue;
            }

            $target_errors = Editable_Registry::target_errors($field, $target);
            if (!empty($target_errors)) {
                $errors[$id] = $target_errors;
                continue;
            }

            $before[$id] = Editable_Registry::read_value($field, $target, false);
            $sanitized = Editable_Registry::sanitize_value($field, $value);
            $after[$id] = !empty($field['sensitive']) ? '[REDACTED]' : $sanitized;

            if ($dry_run) {
                continue;
            }

            if (self::supports_snapshot($field) && $before[$id] !== $sanitized) {
                $snapshot_ids[] = Snapshot_Store::create_snapshot(
                    Auth::current_key_id(),
                    $scope,
                    (string) ($field['storage']['type'] ?? 'editable'),
                    (string) ($field['storage']['key'] ?? $id),
                    $before[$id],
                    $sanitized
                );
            }

            $result = Editable_Registry::write_value($field, $target, $value);
            if (is_wp_error($result)) {
                $errors[$id] = $result->get_error_message();
                continue;
            }

            $live_value = Editable_Registry::read_value($field, $target, false);
            $live[$id] = !empty($field['sensitive']) ? '[REDACTED]' : Editable_Registry::read_value($field, $target, true);

            if ($strict_write && !self::values_equivalent($sanitized, $live_value)) {
                $write_mismatches[$id] = [
                    'expected' => !empty($field['sensitive']) ? '[REDACTED]' : $sanitized,
                    'actual' => !empty($field['sensitive']) ? '[REDACTED]' : $live_value,
                ];
            }
        }

        $diff = Diff::compare($before, $after);
        $public_diff = self::redact_sensitive_field_diff($diff);

        Audit_Log::log_write([
            'actor_key_id' => Auth::current_key_id(),
            'scope' => 'write:editables',
            'method' => $request->get_method(),
            'route' => $request->get_route(),
            'target_type' => 'editables',
            'target_id' => self::target_id_for_log($target),
            'dry_run' => $dry_run,
            'before' => $before,
            'after' => $after,
            'diff' => $diff,
            'status_code' => (empty($errors) && empty($blocked)) ? 200 : 207,
        ]);

        if (!$dry_run && $strict_write && !empty($write_mismatches)) {
            return new \WP_Error(
                'caa_write_integrity_failed',
                'One or more editable writes were altered during persistence.',
                [
                    'status' => 409,
                    'mismatches' => $write_mismatches,
                    'data' => $live,
                ]
            );
        }

        return rest_ensure_response([
            'success' => empty($errors) && empty($blocked),
            'dry_run' => $dry_run,
            'target' => $target,
            'blocked_fields' => $blocked,
            'errors' => $errors,
            'snapshot_ids' => $snapshot_ids,
            'write_mismatches' => $write_mismatches,
            'diff' => $public_diff,
            'data' => $dry_run ? $after : $live,
        ]);
    }

    public static function list_terms(WP_REST_Request $request)
    {
        $taxonomy = sanitize_key((string) $request->get_param('taxonomy'));
        if ($taxonomy === '' || !taxonomy_exists($taxonomy)) {
            return new \WP_Error('caa_invalid_taxonomy', 'Valid taxonomy is required.', ['status' => 400]);
        }

        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => Utils::truthy($request->get_param('hide_empty')),
            'search' => sanitize_text_field((string) $request->get_param('search')),
        ]);

        if (is_wp_error($terms)) {
            return $terms;
        }

        $data = [];
        foreach ((array) $terms as $term) {
            $item = Editable_Registry::prepare_term($term);
            $item['meta'] = get_term_meta((int) $term->term_id);
            $data[] = $item;
        }

        return rest_ensure_response([
            'success' => true,
            'taxonomy' => $taxonomy,
            'data' => $data,
        ]);
    }

    public static function get_term(WP_REST_Request $request)
    {
        $taxonomy = sanitize_key((string) $request->get_param('taxonomy'));
        $term = get_term((int) $request['term_id'], $taxonomy);
        if (!$term || is_wp_error($term)) {
            return new \WP_Error('caa_term_not_found', 'Term not found.', ['status' => 404]);
        }

        $data = Editable_Registry::prepare_term($term);
        $data['meta'] = self::normalize_meta_rows(get_term_meta((int) $term->term_id));

        return rest_ensure_response([
            'success' => true,
            'data' => $data,
        ]);
    }

    public static function create_term(WP_REST_Request $request)
    {
        $payload = self::payload($request);
        $taxonomy = sanitize_key((string) ($payload['taxonomy'] ?? $request->get_param('taxonomy')));
        $name = sanitize_text_field((string) ($payload['name'] ?? ''));

        if ($taxonomy === '' || !taxonomy_exists($taxonomy)) {
            return new \WP_Error('caa_invalid_taxonomy', 'Valid taxonomy is required.', ['status' => 400]);
        }
        if ($name === '') {
            return new \WP_Error('caa_missing_term_name', 'name is required.', ['status' => 400]);
        }

        $args = [
            'slug' => isset($payload['slug']) ? sanitize_title((string) $payload['slug']) : '',
            'description' => isset($payload['description']) ? sanitize_textarea_field((string) $payload['description']) : '',
            'parent' => isset($payload['parent']) ? (int) $payload['parent'] : 0,
        ];

        $result = wp_insert_term($name, $taxonomy, array_filter($args, static function ($value) {
            return $value !== '' && $value !== null;
        }));
        if (is_wp_error($result)) {
            return $result;
        }

        $term_id = (int) $result['term_id'];
        if (isset($payload['meta']) && is_array($payload['meta'])) {
            self::update_term_meta_values($term_id, $payload['meta']);
        }

        Audit_Log::log_write([
            'actor_key_id' => Auth::current_key_id(),
            'scope' => 'write:taxonomy',
            'method' => $request->get_method(),
            'route' => $request->get_route(),
            'target_type' => 'term',
            'target_id' => (string) $term_id,
            'dry_run' => false,
            'before' => null,
            'after' => ['term_id' => $term_id, 'taxonomy' => $taxonomy],
            'diff' => ['create' => true],
            'status_code' => 201,
        ]);

        return new \WP_REST_Response([
            'success' => true,
            'data' => [
                'term_id' => $term_id,
                'taxonomy' => $taxonomy,
            ],
        ], 201);
    }

    public static function update_term(WP_REST_Request $request)
    {
        $payload = self::payload($request);
        $term_id = (int) $request['term_id'];
        $taxonomy = sanitize_key((string) ($payload['taxonomy'] ?? $request->get_param('taxonomy')));
        $term = get_term($term_id, $taxonomy);
        if (!$term || is_wp_error($term)) {
            return new \WP_Error('caa_term_not_found', 'Term not found.', ['status' => 404]);
        }

        $before = Editable_Registry::prepare_term($term);
        $args = [];
        foreach (['name', 'slug', 'description', 'parent'] as $field) {
            if (array_key_exists($field, $payload)) {
                if ($field === 'slug') {
                    $args[$field] = sanitize_title((string) $payload[$field]);
                } elseif ($field === 'description') {
                    $args[$field] = sanitize_textarea_field((string) $payload[$field]);
                } elseif ($field === 'parent') {
                    $args[$field] = (int) $payload[$field];
                } else {
                    $args[$field] = sanitize_text_field((string) $payload[$field]);
                }
            }
        }

        if (!empty($args)) {
            $result = wp_update_term($term_id, $taxonomy, $args);
            if (is_wp_error($result)) {
                return $result;
            }
        }

        if (isset($payload['meta']) && is_array($payload['meta'])) {
            self::update_term_meta_values($term_id, $payload['meta']);
        }

        $after_term = get_term($term_id, $taxonomy);
        $after = $after_term && !is_wp_error($after_term) ? Editable_Registry::prepare_term($after_term) : ['term_id' => $term_id];
        $after['meta'] = self::normalize_meta_rows(get_term_meta($term_id));
        $diff = Diff::compare($before, $after);

        Audit_Log::log_write([
            'actor_key_id' => Auth::current_key_id(),
            'scope' => 'write:taxonomy',
            'method' => $request->get_method(),
            'route' => $request->get_route(),
            'target_type' => 'term',
            'target_id' => (string) $term_id,
            'dry_run' => false,
            'before' => $before,
            'after' => $after,
            'diff' => $diff,
            'status_code' => 200,
        ]);

        return rest_ensure_response([
            'success' => true,
            'diff' => $diff,
            'data' => $after,
        ]);
    }

    public static function delete_term(WP_REST_Request $request)
    {
        $taxonomy = sanitize_key((string) $request->get_param('taxonomy'));
        $term_id = (int) $request['term_id'];
        $term = get_term($term_id, $taxonomy);
        if (!$term || is_wp_error($term)) {
            return new \WP_Error('caa_term_not_found', 'Term not found.', ['status' => 404]);
        }

        $before = Editable_Registry::prepare_term($term);
        $result = wp_delete_term($term_id, $taxonomy);
        if (is_wp_error($result)) {
            return $result;
        }

        Audit_Log::log_write([
            'actor_key_id' => Auth::current_key_id(),
            'scope' => 'write:taxonomy',
            'method' => $request->get_method(),
            'route' => $request->get_route(),
            'target_type' => 'term',
            'target_id' => (string) $term_id,
            'dry_run' => false,
            'before' => $before,
            'after' => ['deleted' => true],
            'diff' => ['deleted' => ['from' => false, 'to' => true]],
            'status_code' => 200,
        ]);

        return rest_ensure_response([
            'success' => true,
            'data' => ['term_id' => $term_id, 'deleted' => (bool) $result],
        ]);
    }

    public static function list_menus(WP_REST_Request $request)
    {
        $menus = wp_get_nav_menus();
        $locations = get_nav_menu_locations();
        $data = [];

        foreach ((array) $menus as $menu) {
            $items = wp_get_nav_menu_items($menu->term_id);
            if (!is_array($items)) {
                $items = [];
            }
            $data[] = [
                'term_id' => (int) $menu->term_id,
                'name' => (string) $menu->name,
                'slug' => (string) $menu->slug,
                'count' => (int) $menu->count,
                'locations' => array_keys(array_filter($locations, static function ($menu_id) use ($menu) {
                    return (int) $menu_id === (int) $menu->term_id;
                })),
                'items' => array_map(static function ($item) {
                    return Editable_Registry::read_menu_item((int) $item->ID);
                }, (array) $items),
            ];
        }

        return rest_ensure_response([
            'success' => true,
            'registered_locations' => get_registered_nav_menus(),
            'assigned_locations' => $locations,
            'data' => $data,
        ]);
    }

    public static function create_menu(WP_REST_Request $request)
    {
        $payload = self::payload($request);
        $name = sanitize_text_field((string) ($payload['name'] ?? ''));
        if ($name === '') {
            return new \WP_Error('caa_missing_menu_name', 'name is required.', ['status' => 400]);
        }

        $menu_id = wp_create_nav_menu($name);
        if (is_wp_error($menu_id)) {
            return $menu_id;
        }

        if (!empty($payload['location'])) {
            $locations = get_nav_menu_locations();
            $locations[sanitize_key((string) $payload['location'])] = (int) $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => ['menu_id' => (int) $menu_id],
        ], 201);
    }

    public static function get_menu_item(WP_REST_Request $request)
    {
        $item = Editable_Registry::read_menu_item((int) $request['item_id']);
        if (!$item) {
            return new \WP_Error('caa_menu_item_not_found', 'Menu item not found.', ['status' => 404]);
        }

        return rest_ensure_response([
            'success' => true,
            'data' => $item,
        ]);
    }

    public static function create_menu_item(WP_REST_Request $request)
    {
        $payload = self::payload($request);
        $menu_id = isset($payload['menu_id']) ? (int) $payload['menu_id'] : 0;
        if ($menu_id <= 0) {
            return new \WP_Error('caa_missing_menu_id', 'menu_id is required.', ['status' => 400]);
        }

        $args = self::menu_item_args($payload);
        $item_id = wp_update_nav_menu_item($menu_id, 0, $args);
        if (is_wp_error($item_id)) {
            return $item_id;
        }

        return new \WP_REST_Response([
            'success' => true,
            'data' => Editable_Registry::read_menu_item((int) $item_id),
        ], 201);
    }

    public static function update_menu_item(WP_REST_Request $request)
    {
        $payload = self::payload($request);
        $field = Editable_Registry::get_field('menu.item');
        if (!$field) {
            return new \WP_Error('caa_menu_field_missing', 'Menu item editable field is unavailable.', ['status' => 500]);
        }

        $result = Editable_Registry::write_value($field, ['menu_item_id' => (int) $request['item_id']], $payload);
        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response([
            'success' => true,
            'data' => Editable_Registry::read_menu_item((int) $request['item_id']),
        ]);
    }

    public static function delete_menu_item(WP_REST_Request $request)
    {
        $deleted = wp_delete_post((int) $request['item_id'], true);
        if (!$deleted) {
            return new \WP_Error('caa_menu_item_delete_failed', 'Failed to delete menu item.', ['status' => 500]);
        }

        return rest_ensure_response([
            'success' => true,
            'data' => ['item_id' => (int) $request['item_id'], 'deleted' => true],
        ]);
    }

    private static function ids_from_request(WP_REST_Request $request): array
    {
        $ids = $request->get_param('ids');
        if (is_string($ids)) {
            return array_values(array_filter(array_map('trim', explode(',', $ids))));
        }

        if (is_array($ids)) {
            return array_values(array_filter(array_map('strval', $ids)));
        }

        return [];
    }

    private static function payload(WP_REST_Request $request): array
    {
        $payload = $request->get_json_params();
        if (!is_array($payload)) {
            $payload = $request->get_params();
        }

        return is_array($payload) ? $payload : [];
    }

    private static function target_from_request(WP_REST_Request $request): array
    {
        return self::normalize_target($request->get_params());
    }

    private static function normalize_target(array $input): array
    {
        $target = [];
        foreach (['post_id', 'term_id', 'menu_id', 'menu_item_id'] as $key) {
            if (isset($input[$key]) && $input[$key] !== '') {
                $target[$key] = (int) $input[$key];
            }
        }
        if (isset($input['taxonomy']) && $input['taxonomy'] !== '') {
            $target['taxonomy'] = sanitize_key((string) $input['taxonomy']);
        }

        return $target;
    }

    private static function supports_snapshot(array $field): bool
    {
        if (!empty($field['sensitive'])) {
            return false;
        }

        return in_array((string) ($field['storage']['type'] ?? ''), ['option', 'theme_mod'], true);
    }

    private static function values_equivalent($expected, $actual): bool
    {
        return self::normalize_compare_value($expected) == self::normalize_compare_value($actual);
    }

    private static function normalize_compare_value($value)
    {
        if (is_object($value)) {
            return self::normalize_compare_value((array) $value);
        }
        if (is_array($value)) {
            $out = [];
            foreach ($value as $key => $item) {
                $out[$key] = self::normalize_compare_value($item);
            }
            return $out;
        }

        return $value;
    }

    private static function target_id_for_log(array $target): string
    {
        if (!empty($target['post_id'])) {
            return 'post:' . (int) $target['post_id'];
        }
        if (!empty($target['term_id'])) {
            return 'term:' . (int) $target['term_id'];
        }
        if (!empty($target['menu_item_id'])) {
            return 'menu_item:' . (int) $target['menu_item_id'];
        }

        return 'global';
    }

    private static function update_term_meta_values(int $term_id, array $meta): void
    {
        foreach ($meta as $key => $value) {
            $key = sanitize_key((string) $key);
            if ($key === '') {
                continue;
            }

            if ($value === null) {
                delete_term_meta($term_id, $key);
            } else {
                update_term_meta($term_id, $key, Utils::sanitize_mixed_for_storage_by_key($key, $value));
            }
        }
    }

    private static function normalize_meta_rows(array $meta): array
    {
        $normalized = [];
        foreach ($meta as $key => $values) {
            if (!is_array($values)) {
                continue;
            }
            $normalized[$key] = count($values) === 1 ? maybe_unserialize($values[0]) : array_map('maybe_unserialize', $values);
        }

        return $normalized;
    }

    private static function menu_item_args(array $payload): array
    {
        return [
            'menu-item-title' => isset($payload['title']) ? sanitize_text_field((string) $payload['title']) : '',
            'menu-item-url' => isset($payload['url']) ? esc_url_raw((string) $payload['url']) : '',
            'menu-item-description' => isset($payload['description']) ? sanitize_textarea_field((string) $payload['description']) : '',
            'menu-item-attr-title' => isset($payload['attr_title']) ? sanitize_text_field((string) $payload['attr_title']) : '',
            'menu-item-target' => isset($payload['target']) ? sanitize_key((string) $payload['target']) : '',
            'menu-item-classes' => isset($payload['classes']) && is_array($payload['classes']) ? implode(' ', array_map('sanitize_html_class', $payload['classes'])) : '',
            'menu-item-xfn' => isset($payload['xfn']) ? sanitize_text_field((string) $payload['xfn']) : '',
            'menu-item-parent-id' => isset($payload['menu_item_parent']) ? (int) $payload['menu_item_parent'] : 0,
            'menu-item-position' => isset($payload['menu_order']) ? (int) $payload['menu_order'] : 0,
            'menu-item-object-id' => isset($payload['object_id']) ? (int) $payload['object_id'] : 0,
            'menu-item-object' => isset($payload['object']) ? sanitize_key((string) $payload['object']) : 'custom',
            'menu-item-type' => isset($payload['type']) ? sanitize_key((string) $payload['type']) : 'custom',
            'menu-item-status' => isset($payload['status']) ? sanitize_key((string) $payload['status']) : 'publish',
        ];
    }

    private static function redact_sensitive_field_diff(array $diff): array
    {
        $out = [];
        foreach ($diff as $field_id => $value) {
            $field = is_string($field_id) ? Editable_Registry::get_field($field_id) : null;
            if ($field && !empty($field['sensitive'])) {
                $out[$field_id] = '[REDACTED]';
                continue;
            }

            $out[$field_id] = is_array($value) ? self::redact_sensitive_field_diff($value) : $value;
        }

        return $out;
    }
}
