<?php
/**
 * Centralized Schema Registry
 * 
 * All schemas should be registered through this class instead of directly echoing.
 * This enables:
 * - Deduplication by @type and @id
 * - Filtering of invalid schema types
 * - Single output point for all schemas
 * - Debug visibility for admins
 *
 * @package earlystart_SEO_Pro
 * @since 1.1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Schema_Registry
{
    const STRICT_MODE_OPTION = 'earlystart_schema_strict_mode';

    /**
     * Collected schemas for this page
     * @var array
     */
    private static $schemas = [];

    /**
     * Blocked schemas (for debugging)
     * @var array
     */
    private static $blocked = [];

    /**
     * Track which types have been registered (for deduplication)
     * @var array
     */
    private static $registered_types = [];

    /**
     * Track schema IDs to prevent duplicates
     * @var array
     */
    private static $registered_ids = [];

    /**
     * Has output already happened?
     * @var bool
     */
    private static $output_done = false;

    /**
     * Field-level source and confidence metadata, keyed by node key.
     * @var array
     */
    private static $field_metadata = [];

    /**
     * Tracks fields already claimed by non-AI sources (per node key)
     * to enforce "AI can only fill missing low-risk fields".
     * @var array
     */
    private static $trusted_field_index = [];

    /**
     * Initialize the registry
     */
    public static function init()
    {
        // Output all registered schemas at priority 99 (late, after all registrations)
        add_action('wp_head', [__CLASS__, 'output_all_schemas'], 99);

        // Frontend schema debug/admin-bar UI is intentionally disabled.
        // Debug and validation should run from the dedicated admin dashboard.
    }

    /**
     * Register a schema for output
     * 
     * @param array $schema The schema array (must have @type)
     * @param array $options Optional settings:
     *   - allow_duplicate: bool (default false) - allow multiple of same @type
     *   - source: string - identifier for debugging where this came from
     * @return bool Whether the schema was registered
     */
    public static function register($schema, $options = [])
    {
        $source = isset($options['source']) ? $options['source'] : 'unknown';
        $field_source = isset($options['field_source']) && is_array($options['field_source']) ? $options['field_source'] : [];
        $field_confidence = isset($options['field_confidence']) && is_array($options['field_confidence']) ? $options['field_confidence'] : [];
        
        if (self::$output_done) {
            self::$blocked[] = [
                'type' => 'unknown',
                'reason' => 'Output already happened',
                'source' => $source
            ];
            return false;
        }

        if (empty($schema) || !is_array($schema)) {
            return false;
        }

        // Get schema type
        $type = isset($schema['@type']) ? $schema['@type'] : null;
        
        // Handle array types (e.g., ["ChildCare", "LocalBusiness"])
        if (is_array($type)) {
            $type = $type[0]; // Use first type for dedup key
        }

        if (empty($type)) {
            self::$blocked[] = [
                'type' => 'empty',
                'reason' => 'No @type specified',
                'source' => $source
            ];
            return false;
        }

        // Check if type is invalid
        if (function_exists('earlystart_is_invalid_schema_type') && earlystart_is_invalid_schema_type($type)) {
            self::$blocked[] = [
                'type' => $type,
                'reason' => 'Invalid schema type (blocklist)',
                'source' => $source
            ];
            return false;
        }

        // Check for @id-based deduplication
        $schema_id = isset($schema['@id']) ? $schema['@id'] : null;
        if ($schema_id && isset(self::$registered_ids[$schema_id])) {
            self::$blocked[] = [
                'type' => $type,
                'reason' => 'Duplicate @id: ' . $schema_id,
                'source' => $source
            ];
            return false;
        }

        // Check for type-based deduplication (unless allowed)
        $allow_duplicate = isset($options['allow_duplicate']) ? $options['allow_duplicate'] : false;
        if (!$allow_duplicate && isset(self::$registered_types[$type])) {
            // Already have this type - skip unless it's an allowed duplicate type
            $allowed_multiples = ['ImageObject', 'ListItem', 'Question', 'Answer', 'Review', 'Service'];
            if (!in_array($type, $allowed_multiples)) {
                self::$blocked[] = [
                    'type' => $type,
                    'reason' => 'Duplicate type (already registered)',
                    'source' => $source
                ];
                return false;
            }
        }

        $node_key = self::get_node_key($schema, $type);
        $is_ai_source = self::is_ai_source($source);

        if ($is_ai_source) {
            $sanitized_for_ai = self::sanitize_ai_fields($schema, $node_key, $source);
            if (empty($sanitized_for_ai)) {
                self::$blocked[] = [
                    'type' => $type,
                    'reason' => 'AI source had no allowed low-risk fields after sanitization',
                    'source' => $source
                ];
                return false;
            }
            $schema = $sanitized_for_ai;
        }

        self::capture_field_metadata($schema, $node_key, $source, $field_source, $field_confidence);

        // Register the schema
        self::$schemas[] = [
            'schema' => $schema,
            'type' => $type,
            'source' => $source,
            'node_key' => $node_key
        ];

        self::$registered_types[$type] = true;
        if ($schema_id) {
            self::$registered_ids[$schema_id] = true;
        }

        return true;
    }

    /**
     * Check if a type has already been registered
     */
    public static function has_type($type)
    {
        return isset(self::$registered_types[$type]);
    }

    /**
     * Get count of registered schemas
     */
    public static function get_count()
    {
        return count(self::$schemas);
    }

    /**
     * Get all registered schemas (for debugging)
     */
    public static function get_all()
    {
        return self::$schemas;
    }

    /**
     * Get all blocked schemas (for debugging)
     */
    public static function get_blocked()
    {
        return self::$blocked;
    }

    /**
     * Output all registered schemas
     * This runs at wp_head priority 99
     */
    public static function output_all_schemas()
    {
        if (self::$output_done) {
            return;
        }

        self::$output_done = true;

        if (empty(self::$schemas)) {
            return;
        }

        $pipeline = self::sanitize_validate_pipeline(self::$schemas);
        if (!$pipeline['valid']) {
            foreach ($pipeline['errors'] as $error) {
                self::$blocked[] = [
                    'type' => 'graph',
                    'reason' => $error,
                    'source' => 'strict-pipeline'
                ];
            }
            return;
        }

        $graph = $pipeline['graph'];

        // Output as individual scripts
        foreach ($graph as $schema) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
        }

        do_action('earlystart_schema_field_metadata_ready', self::$field_metadata, $graph);
    }

    /**
     * Output debug panel in footer for admins
     */
    public static function output_debug_panel()
    {
        // Only show for admins with debug query param
        if (!current_user_can('manage_options') || !isset($_GET['schema_debug'])) {
            return;
        }

        $registered = self::$schemas;
        $blocked = self::$blocked;
        ?>
        <div id="schema-registry-debug" style="
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            max-height: 50vh;
            overflow-y: auto;
            background: #1e1e1e;
            color: #fff;
            font-family: monospace;
            font-size: 12px;
            z-index: 999999;
            padding: 15px;
            border-top: 3px solid #00a32a;
        ">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h3 style="margin: 0; color: #00a32a;">🔍 Schema Registry Debug</h3>
                <button onclick="this.parentElement.parentElement.remove()" style="background: #d63638; color: white; border: none; padding: 5px 10px; cursor: pointer;">Close</button>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Registered Schemas -->
                <div>
                    <h4 style="color: #00a32a; margin: 0 0 10px;">✅ Registered (<?php echo count($registered); ?>)</h4>
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <tr style="background: #333;"><th style="text-align: left; padding: 5px;">Type</th><th style="text-align: left; padding: 5px;">Source</th></tr>
                        <?php foreach ($registered as $item): ?>
                        <tr style="border-bottom: 1px solid #444;">
                            <td style="padding: 5px; color: #4fc3f7;"><?php echo esc_html($item['type']); ?></td>
                            <td style="padding: 5px; color: #aaa;"><?php echo esc_html($item['source']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                
                <!-- Blocked Schemas -->
                <div>
                    <h4 style="color: #d63638; margin: 0 0 10px;">🚫 Blocked (<?php echo count($blocked); ?>)</h4>
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <tr style="background: #333;"><th style="text-align: left; padding: 5px;">Type</th><th style="text-align: left; padding: 5px;">Reason</th><th style="text-align: left; padding: 5px;">Source</th></tr>
                        <?php foreach ($blocked as $item): ?>
                        <tr style="border-bottom: 1px solid #444;">
                            <td style="padding: 5px; color: #ff8a80;"><?php echo esc_html($item['type']); ?></td>
                            <td style="padding: 5px; color: #ffcc80;"><?php echo esc_html($item['reason']); ?></td>
                            <td style="padding: 5px; color: #aaa;"><?php echo esc_html($item['source']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($blocked)): ?>
                        <tr><td colspan="3" style="padding: 10px; color: #aaa;">No schemas blocked</td></tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Add indicator to admin bar
     */
    public static function admin_bar_indicator($wp_admin_bar)
    {
        if (!current_user_can('manage_options') || is_admin()) {
            return;
        }

        $count = count(self::$schemas);
        $blocked_count = count(self::$blocked);
        
        $title = sprintf('Schema: %d', $count);
        if ($blocked_count > 0) {
            $title .= sprintf(' <span style="color:#ff6b6b;">(%d blocked)</span>', $blocked_count);
        }

        $wp_admin_bar->add_node([
            'id' => 'schema-registry',
            'title' => $title,
            'href' => add_query_arg('schema_debug', '1'),
            'meta' => [
                'title' => 'Click to toggle Schema Registry Debug Panel'
            ]
        ]);
    }

    /**
     * Clear the registry (useful for testing)
     */
    public static function clear()
    {
        self::$schemas = [];
        self::$blocked = [];
        self::$registered_types = [];
        self::$registered_ids = [];
        self::$field_metadata = [];
        self::$trusted_field_index = [];
        self::$output_done = false;
    }

    /**
     * Expose field metadata for QA/admin tools.
     *
     * @return array
     */
    public static function get_field_metadata()
    {
        return self::$field_metadata;
    }

    private static function sanitize_validate_pipeline($registered_items)
    {
        $errors = [];
        $sanitized_nodes = [];
        $hash_index = [];
        $id_index = [];

        foreach ($registered_items as $item) {
            if (empty($item['schema']) || !is_array($item['schema'])) {
                continue;
            }
            $source = isset($item['source']) ? (string) $item['source'] : 'unknown';
            $node = self::sanitize_node_recursive($item['schema'], $source);
            if (empty($node) || !is_array($node)) {
                continue;
            }
            if (!isset($node['@context'])) {
                $node['@context'] = 'https://schema.org';
            }
            if (!self::is_valid_type_shape($node)) {
                $errors[] = 'Invalid @type shape detected in strict pipeline';
                continue;
            }
            $node_hash = md5(wp_json_encode($node));
            if (isset($hash_index[$node_hash])) {
                continue;
            }
            $hash_index[$node_hash] = true;

            if (!empty($node['@id']) && is_string($node['@id'])) {
                $nid = $node['@id'];
                if (isset($id_index[$nid])) {
                    if ($id_index[$nid] !== $node_hash) {
                        $errors[] = sprintf('Duplicate @id with conflicting nodes: %s', $nid);
                    }
                    continue;
                }
                $id_index[$nid] = $node_hash;
            }

            $sanitized_nodes[] = $node;
        }

        if (empty($sanitized_nodes)) {
            return ['valid' => false, 'errors' => array_merge($errors, ['No valid schema nodes left after sanitization']), 'graph' => []];
        }

        $strict_errors = self::run_strict_graph_checks($sanitized_nodes);
        if (!empty($strict_errors)) {
            $errors = array_merge($errors, $strict_errors);
        }

        if (class_exists('earlystart_Schema_Validator')) {
            $graph_payload = [
                '@context' => 'https://schema.org',
                '@graph' => $sanitized_nodes,
            ];
            $valid = earlystart_Schema_Validator::validate_graph($graph_payload);
            if (!$valid) {
                $validator_errors = earlystart_Schema_Validator::get_errors();
                foreach ($validator_errors as $ve) {
                    $errors[] = 'Validator: ' . $ve;
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => array_values(array_unique($errors)),
            'graph' => $sanitized_nodes,
        ];
    }

    private static function run_strict_graph_checks($nodes)
    {
        $errors = [];
        $home_org_candidates = 0;
        $known_ids = [];
        $ref_ids = [];

        foreach ($nodes as $node) {
            $types = self::normalize_types($node);
            foreach ($types as $t) {
                if (!preg_match('/^[A-Za-z][A-Za-z0-9]+$/', $t)) {
                    $errors[] = sprintf('Invalid @type value: %s', $t);
                }
            }
            $id = isset($node['@id']) && is_string($node['@id']) ? $node['@id'] : '';
            if ($id !== '') {
                $known_ids[$id] = true;
            }

            if (in_array('Organization', $types, true) && self::is_primary_organization_node($node)) {
                $home_org_candidates++;
            }

            $canonical_key = '';
            if (isset($node['canonical']) && is_string($node['canonical'])) {
                $canonical_key = $node['canonical'];
            } elseif (isset($node['canonicalUrl']) && is_string($node['canonicalUrl'])) {
                $canonical_key = $node['canonicalUrl'];
            }

            if ($canonical_key !== '' && isset($node['url']) && is_string($node['url'])) {
                $canonical = trailingslashit(esc_url_raw($canonical_key));
                $url = trailingslashit(esc_url_raw($node['url']));
                if ($canonical !== '' && $url !== '' && $canonical !== $url) {
                    $errors[] = sprintf('Canonical/url conflict on node: %s', $id !== '' ? $id : '(no @id)');
                }
            }

            self::collect_ref_ids($node, $ref_ids);
        }

        if ($home_org_candidates > 1) {
            $errors[] = 'Multiple primary Organization nodes detected';
        }

        foreach (array_unique($ref_ids) as $ref) {
            if (isset($known_ids[$ref])) {
                continue;
            }

            if (strpos($ref, '#') === 0) {
                $resolved = false;
                foreach (array_keys($known_ids) as $known_id) {
                    if (substr($known_id, -strlen($ref)) === $ref) {
                        $resolved = true;
                        break;
                    }
                }
                if (!$resolved) {
                    $errors[] = sprintf('Broken @id reference: %s', $ref);
                }
                continue;
            }

            $errors[] = sprintf('Broken @id reference: %s', $ref);
        }

        if (count($nodes) === 0) {
            $errors[] = 'Schema graph empty after strict checks';
        }

        return $errors;
    }

    private static function sanitize_node_recursive($value, $source)
    {
        if (is_array($value)) {
            $is_assoc = self::is_assoc_array($value);
            $out = [];
            foreach ($value as $key => $child) {
                $clean = self::sanitize_node_recursive($child, $source);
                if ($clean === null || $clean === '' || $clean === []) {
                    continue;
                }

                if (is_string($key) && in_array($key, ['url', '@id', 'logo', 'image', 'thumbnailUrl', 'contentUrl'], true)) {
                    if (is_string($clean)) {
                        $clean = esc_url_raw(trim($clean));
                        if ($clean === '') {
                            continue;
                        }
                    }
                }
                if (is_string($key) && in_array($key, ['telephone', 'faxNumber'], true) && is_string($clean)) {
                    $clean = self::normalize_phone($clean);
                    if ($clean === '') {
                        continue;
                    }
                }

                $out[$key] = $clean;
            }

            if ($is_assoc) {
                return $out;
            }

            return array_values($out);
        }

        if (is_string($value)) {
            return trim(wp_strip_all_tags($value));
        }

        if (is_bool($value) || is_numeric($value)) {
            return $value;
        }

        return null;
    }

    private static function sanitize_ai_fields($schema, $node_key, $source)
    {
        $allowed_low_risk = [
            '@context', '@type', '@id',
            'description', 'disambiguatingDescription', 'keywords', 'slogan', 'award', 'knowsAbout',
            'additionalType', 'isSimilarTo'
        ];
        $protected_identity = [
            'name', 'legalName', 'url', 'telephone', 'email', 'address', 'geo',
            'sameAs', 'logo', 'openingHours', 'contactPoint'
        ];

        $out = [];
        foreach ($schema as $field => $value) {
            if (in_array($field, ['@context', '@type', '@id'], true)) {
                $out[$field] = $value;
                continue;
            }

            if (!in_array($field, $allowed_low_risk, true)) {
                self::$blocked[] = [
                    'type' => isset($schema['@type']) ? (is_array($schema['@type']) ? reset($schema['@type']) : $schema['@type']) : 'unknown',
                    'reason' => sprintf('AI field blocked (high risk): %s', $field),
                    'source' => $source
                ];
                continue;
            }

            if (in_array($field, $protected_identity, true)) {
                self::$blocked[] = [
                    'type' => isset($schema['@type']) ? (is_array($schema['@type']) ? reset($schema['@type']) : $schema['@type']) : 'unknown',
                    'reason' => sprintf('AI cannot overwrite identity field: %s', $field),
                    'source' => $source
                ];
                continue;
            }

            if (!empty(self::$trusted_field_index[$node_key][$field])) {
                self::$blocked[] = [
                    'type' => isset($schema['@type']) ? (is_array($schema['@type']) ? reset($schema['@type']) : $schema['@type']) : 'unknown',
                    'reason' => sprintf('AI field ignored because trusted source already provided it: %s', $field),
                    'source' => $source
                ];
                continue;
            }

            $out[$field] = $value;
        }

        $business_keys = array_diff(array_keys($out), ['@context', '@type', '@id']);
        if (empty($business_keys)) {
            return [];
        }

        return $out;
    }

    private static function capture_field_metadata($schema, $node_key, $source, $field_source, $field_confidence)
    {
        if (!isset(self::$field_metadata[$node_key])) {
            self::$field_metadata[$node_key] = [];
        }

        $is_ai_source = self::is_ai_source($source);
        $base_confidence = self::default_confidence_for_source($source);

        foreach ($schema as $field => $value) {
            if ($field === '@context') {
                continue;
            }
            $resolved_source = isset($field_source[$field]) ? (string) $field_source[$field] : (string) $source;
            $resolved_conf = isset($field_confidence[$field]) ? (float) $field_confidence[$field] : $base_confidence;
            $resolved_conf = max(0.0, min(1.0, $resolved_conf));

            self::$field_metadata[$node_key][$field] = [
                'field_source' => $resolved_source,
                'confidence' => $resolved_conf
            ];

            if (!$is_ai_source) {
                if (!isset(self::$trusted_field_index[$node_key])) {
                    self::$trusted_field_index[$node_key] = [];
                }
                self::$trusted_field_index[$node_key][$field] = true;
            }
        }
    }

    private static function default_confidence_for_source($source)
    {
        $source = strtolower((string) $source);
        if (strpos($source, 'post_meta') !== false || strpos($source, 'theme_mod') !== false || strpos($source, 'option') !== false) {
            return 0.95;
        }
        if (self::is_ai_source($source)) {
            return 0.45;
        }
        return 0.75;
    }

    private static function is_ai_source($source)
    {
        $source = strtolower((string) $source);
        return strpos($source, 'ai') !== false || strpos($source, 'llm') !== false;
    }

    private static function get_node_key($schema, $type)
    {
        if (!empty($schema['@id']) && is_string($schema['@id'])) {
            return $schema['@id'];
        }
        return 'type:' . (string) $type;
    }

    private static function normalize_types($node)
    {
        if (!isset($node['@type'])) {
            return [];
        }
        if (is_array($node['@type'])) {
            return array_values(array_filter(array_map('strval', $node['@type'])));
        }
        return [strval($node['@type'])];
    }

    private static function is_primary_organization_node($node)
    {
        $home = trailingslashit(home_url('/'));
        $org_id_1 = $home . '#organization';
        $org_id_2 = untrailingslashit(home_url()) . '#organization';
        $id = isset($node['@id']) ? (string) $node['@id'] : '';
        $url = isset($node['url']) ? trailingslashit((string) $node['url']) : '';

        if ($id === $org_id_1 || $id === $org_id_2) {
            return true;
        }
        if ($url !== '' && $url === $home) {
            return true;
        }
        return false;
    }

    private static function collect_ref_ids($value, &$refs)
    {
        if (!is_array($value)) {
            return;
        }

        if (isset($value['@id']) && count($value) === 1 && is_string($value['@id'])) {
            $refs[] = $value['@id'];
        }

        foreach ($value as $child) {
            self::collect_ref_ids($child, $refs);
        }
    }

    private static function is_valid_type_shape($node)
    {
        if (!isset($node['@type'])) {
            return false;
        }
        if (is_string($node['@type']) && trim($node['@type']) !== '') {
            return true;
        }
        if (is_array($node['@type'])) {
            foreach ($node['@type'] as $type) {
                if (!is_string($type) || trim($type) === '') {
                    return false;
                }
            }
            return !empty($node['@type']);
        }
        return false;
    }

    private static function normalize_phone($phone)
    {
        $phone = trim((string) $phone);
        if ($phone === '') {
            return '';
        }
        $clean = preg_replace('/[^\d\+]/', '', $phone);
        if ($clean === null || $clean === '') {
            return '';
        }

        if ($clean[0] !== '+' && strlen($clean) === 10) {
            $clean = '+1' . $clean;
        }
        if (!preg_match('/^\+\d{8,15}$/', $clean)) {
            return '';
        }
        return $clean;
    }

    private static function is_assoc_array($arr)
    {
        if (!is_array($arr)) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

// Initialize the registry
add_action('init', ['earlystart_Schema_Registry', 'init']);


