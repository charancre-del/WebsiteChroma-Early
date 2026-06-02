<?php
/**
 * Schema Bulk Operations
 * Handles bulk reset/deletion of generated schema and FAQs
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Schema_Bulk_Ops
{
    private const SCHEMA_META_KEYS = [
        '_earlystart_schema_override',
        '_earlystart_post_schemas',
        '_earlystart_schema_data',
        '_earlystart_schema_type',
    ];

    public function __construct() {
        add_action('wp_ajax_earlystart_bulk_reset_schema', [$this, 'ajax_reset_schema']);
        add_action('wp_ajax_earlystart_bulk_reset_faq', [$this, 'ajax_reset_faq']);
    }

    /**
     * Reset Schema for selected posts
     */
    public function ajax_reset_schema() {
        check_ajax_referer('earlystart_seo_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }

        $post_ids = isset($_POST['post_ids']) && is_array($_POST['post_ids']) ? array_map('absint', wp_unslash($_POST['post_ids'])) : [];
        $reset_all = filter_var(wp_unslash($_POST['reset_all'] ?? false), FILTER_VALIDATE_BOOLEAN);
        $count = 0;

        if ($reset_all) {
            $posts = get_posts([
                'post_type' => 'any',
                'posts_per_page' => -1,
                'post_status' => 'any',
                'meta_query' => $this->build_meta_exists_query(self::SCHEMA_META_KEYS),
                'fields' => 'ids'
            ]);
            foreach ($posts as $pid) {
                if ($this->delete_meta_keys($pid, self::SCHEMA_META_KEYS)) {
                    $count++;
                }
            }
        } elseif (!empty($post_ids) && is_array($post_ids)) {
            foreach ($post_ids as $pid) {
                if (!$pid || !earlystart_seo_can_edit_post($pid)) {
                    continue;
                }
                if ($this->delete_meta_keys($pid, self::SCHEMA_META_KEYS)) {
                    $count++;
                }
            }
        }

        wp_send_json_success(['message' => "Reset Schema for $count items."]);
    }

    /**
     * Reset FAQ for selected posts (Assuming FAQ is stored in post_content or specific meta)
     * Based on seo-engine.php, FAQ seems to be standard content or meta?
     * inc/seo-engine.php checks `earlystart_home_has_faq`.
     * If user means "AI Generated FAQ Schema", likely it's part of the override or a specific meta.
     * We'll assume '_earlystart_faq_schema' or similar if distinct, but schema override usually calls it.
     * However, request asks for "Reset FAQ Schema". 
     * If "FAQ Page" schema is separate, we delete that.
     * If utilizing `_earlystart_schema_override`, it's the same key?
     * I'll assume a separate key `_earlystart_faq_generated` or similar exists, OR delete generic schema if they are combined.
     * But usually FAQ is separate meta. I'll try `_earlystart_faq_data` or `_earlystart_faq_schema`.
     * Safest bet: `_earlystart_faq_schema`.
     */
    public function ajax_reset_faq() {
        check_ajax_referer('earlystart_seo_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
        }

        $post_ids = isset($_POST['post_ids']) && is_array($_POST['post_ids']) ? array_map('absint', wp_unslash($_POST['post_ids'])) : [];
        $reset_all = filter_var(wp_unslash($_POST['reset_all'] ?? false), FILTER_VALIDATE_BOOLEAN);
        $count = 0;
        
        // Potential keys for FAQ
        $keys = ['_earlystart_faq_schema', 'earlystart_faq_items', '_earlystart_es_earlystart_faq_items'];

        if ($reset_all) {
            $posts = get_posts([
                'post_type' => 'any',
                'posts_per_page' => -1,
                'post_status' => 'any',
                'meta_query' => $this->build_meta_exists_query(array_merge($keys, ['_earlystart_post_schemas'])),
                'fields' => 'ids'
            ]);
            foreach ($posts as $pid) {
                if ($this->reset_faq_for_post($pid, $keys)) {
                    $count++;
                }
            }
        } elseif (!empty($post_ids) && is_array($post_ids)) {
            foreach ($post_ids as $pid) {
                if (!$pid || !earlystart_seo_can_edit_post($pid)) {
                    continue;
                }
                if ($this->reset_faq_for_post($pid, $keys)) {
                    $count++;
                }
            }
        }

        wp_send_json_success(['message' => "Reset FAQ Schema for $count items."]);
    }

    /**
     * Build a meta query that matches posts containing any listed meta key.
     *
     * @param array $keys Meta keys.
     * @return array
     */
    private function build_meta_exists_query($keys) {
        $query = ['relation' => 'OR'];
        foreach ($keys as $key) {
            $query[] = [
                'key' => $key,
                'compare' => 'EXISTS',
            ];
        }

        return $query;
    }

    /**
     * Delete meta keys from a post and clear its cache when anything changed.
     *
     * @param int   $post_id Post ID.
     * @param array $keys Meta keys.
     * @return bool Whether any value was deleted.
     */
    private function delete_meta_keys($post_id, $keys) {
        $deleted = false;
        foreach ($keys as $key) {
            if (metadata_exists('post', $post_id, $key)) {
                delete_post_meta($post_id, $key);
                $deleted = true;
            }
        }

        if ($deleted) {
            clean_post_cache($post_id);
        }

        return $deleted;
    }

    /**
     * Reset FAQ meta and modular FAQPage schema rows for a post.
     *
     * @param int   $post_id Post ID.
     * @param array $keys FAQ meta keys.
     * @return bool Whether any data changed.
     */
    private function reset_faq_for_post($post_id, $keys) {
        $changed = false;

        foreach ($keys as $key) {
            if (metadata_exists('post', $post_id, $key)) {
                delete_post_meta($post_id, $key);
                $changed = true;
            }
        }

        $schemas = get_post_meta($post_id, '_earlystart_post_schemas', true);
        if (is_array($schemas) && !empty($schemas)) {
            $filtered = [];
            foreach ($schemas as $schema) {
                $schema_type = $this->get_schema_type($schema);
                if ($schema_type !== 'FAQPage') {
                    $filtered[] = $schema;
                }
            }

            if (count($filtered) !== count($schemas)) {
                if (empty($filtered)) {
                    delete_post_meta($post_id, '_earlystart_post_schemas');
                } else {
                    update_post_meta($post_id, '_earlystart_post_schemas', $filtered);
                }
                $changed = true;
            }
        }

        if ($changed) {
            clean_post_cache($post_id);
        }

        return $changed;
    }

    /**
     * Read schema type from builder or raw JSON-LD schema rows.
     *
     * @param mixed $schema Schema row.
     * @return string
     */
    private function get_schema_type($schema) {
        if (!is_array($schema)) {
            return '';
        }

        $type = $schema['type'] ?? ($schema['@type'] ?? '');
        if (is_array($type)) {
            $type = reset($type);
        }

        return is_string($type) ? $type : '';
    }
}

new earlystart_Schema_Bulk_Ops();


