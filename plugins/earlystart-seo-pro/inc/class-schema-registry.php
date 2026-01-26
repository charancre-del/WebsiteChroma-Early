<?php
/**
 * Centralized Schema Registry
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
    private static $schemas = [];
    private static $blocked = [];
    private static $registered_types = [];
    private static $registered_ids = [];
    private static $output_done = false;

    public static function init()
    {
        add_action('wp_head', [__CLASS__, 'output_all_schemas'], 99);
    }

    public static function register($schema, $options = [])
    {
        $source = isset($options['source']) ? $options['source'] : 'unknown';

        if (self::$output_done || empty($schema) || !is_array($schema)) {
            return false;
        }

        $type = isset($schema['@type']) ? $schema['@type'] : null;
        if (is_array($type))
            $type = $type[0];

        if (empty($type))
            return false;

        $schema_id = isset($schema['@id']) ? $schema['@id'] : null;
        if ($schema_id && isset(self::$registered_ids[$schema_id])) {
            return false;
        }

        $allow_duplicate = isset($options['allow_duplicate']) ? $options['allow_duplicate'] : false;
        if (!$allow_duplicate && isset(self::$registered_types[$type])) {
            $allowed_multiples = ['ImageObject', 'ListItem', 'Question', 'Answer', 'Review', 'Service'];
            if (!in_array($type, $allowed_multiples)) {
                return false;
            }
        }

        self::$schemas[] = [
            'schema' => $schema,
            'type' => $type,
            'source' => $source
        ];

        self::$registered_types[$type] = true;
        if ($schema_id) {
            self::$registered_ids[$schema_id] = true;
        }

        return true;
    }

    public static function output_all_schemas()
    {
        if (self::$output_done || empty(self::$schemas))
            return;
        self::$output_done = true;

        foreach (self::$schemas as $item) {
            $schema = $item['schema'];
            if (!isset($schema['@context'])) {
                $schema['@context'] = 'https://schema.org';
            }
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
        }
    }
}

add_action('init', ['earlystart_Schema_Registry', 'init']);
