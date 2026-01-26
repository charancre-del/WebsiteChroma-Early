<?php
/**
 * Schema Validator
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Schema_Validator
{
    private static $errors = [];
    private static $warnings = [];

    private static $required_fields = [
        'LocalBusiness' => ['name', 'address'],
        'ChildCare' => ['name', 'address'],
        'Article' => ['headline', 'author', 'datePublished'],
        'Event' => ['name', 'startDate', 'location'],
        'FAQPage' => ['mainEntity'],
        'JobPosting' => ['title', 'description', 'datePosted', 'hiringOrganization'],
        'BreadcrumbList' => ['itemListElement'],
    ];

    private static $valid_types = [
        'Thing',
        'Organization',
        'Person',
        'Place',
        'Product',
        'Article',
        'BlogPosting',
        'LocalBusiness',
        'ChildCare',
        'Preschool',
        'EducationalOrganization',
        'Service',
        'Review',
        'AggregateRating',
        'FAQPage',
        'Question',
        'Answer',
        'HowTo',
        'JobPosting',
        'VideoObject',
        'ImageObject',
        'WebPage',
        'WebSite',
        'BreadcrumbList',
        'ListItem',
        'PostalAddress',
        'GeoCoordinates',
        'Course',
    ];

    public static function validate($schema, $context = '')
    {
        self::$errors = [];
        self::$warnings = [];

        if (!is_array($schema)) {
            self::$errors[] = "{$context}: Schema must be an array";
            return false;
        }

        $type = isset($schema['@type']) ? $schema['@type'] : null;
        if (is_array($type))
            $type = $type[0];

        if (!$type) {
            self::$errors[] = "{$context}: Missing @type";
            return false;
        }

        if (isset(self::$required_fields[$type])) {
            foreach (self::$required_fields[$type] as $field) {
                if (empty($schema[$field])) {
                    self::$errors[] = "{$context}: Missing required field '{$field}' for type '{$type}'";
                }
            }
        }

        return empty(self::$errors);
    }

    public static function validate_json_ld($json_ld)
    {
        $parsed = json_decode($json_ld, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['valid' => false, 'errors' => ['Invalid JSON'], 'warnings' => []];
        }

        if (isset($parsed['@graph'])) {
            $valid = true;
            foreach ($parsed['@graph'] as $idx => $node) {
                if (!self::validate($node, "Node $idx"))
                    $valid = false;
            }
            return ['valid' => $valid, 'errors' => self::$errors, 'warnings' => self::$warnings];
        }

        return ['valid' => self::validate($parsed), 'errors' => self::$errors, 'warnings' => self::$warnings];
    }
}
