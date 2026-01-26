<?php
/**
 * Fallback Resolver
 * Computes smart default values when advanced SEO/LLM fields are not manually filled
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Fallback_Resolver
{
    /**
     * Get a cached AI-generated value for a field
     */
    public static function get_cached_ai_value($post_id, $field_key)
    {
        $cache = get_post_meta($post_id, '_earlystart_ai_fallback_cache', true);
        if (is_array($cache) && isset($cache[$field_key])) {
            return $cache[$field_key];
        }
        return null;
    }

    /**
     * Save an AI-generated value to the cache
     */
    public static function set_ai_field_cache($post_id, $field_key, $value)
    {
        $cache = get_post_meta($post_id, '_earlystart_ai_fallback_cache', true);
        if (!is_array($cache)) {
            $cache = [];
        }
        $cache[$field_key] = $value;
        update_post_meta($post_id, '_earlystart_ai_fallback_cache', $cache);
    }

    /**
     * Get service area circle data
     */
    public static function get_service_area_circle($location_id)
    {
        $lat = get_post_meta($location_id, 'seo_llm_service_area_lat', true);
        $lng = get_post_meta($location_id, 'seo_llm_service_area_lng', true);
        $radius = get_post_meta($location_id, 'seo_llm_service_area_radius', true);

        if ($lat && $lng) {
            return [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
                'radius' => $radius ? (float) $radius : 10,
            ];
        }

        $lat = get_post_meta($location_id, 'location_latitude', true);
        $lng = get_post_meta($location_id, 'location_longitude', true);

        if ($lat && $lng) {
            return [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
                'radius' => 10,
            ];
        }

        return null;
    }

    /**
     * Get service area cities
     */
    public static function get_service_area_cities($location_id)
    {
        $cities = get_post_meta($location_id, 'seo_llm_service_area_cities', true);
        if (!empty($cities) && is_array($cities)) {
            return array_filter($cities);
        }

        $city = get_post_meta($location_id, 'location_city', true);
        if ($city) {
            return [$city];
        }

        $service_areas = get_post_meta($location_id, 'location_service_areas', true);
        if ($service_areas) {
            $areas = array_map('trim', explode(',', $service_areas));
            return array_filter($areas);
        }

        return [];
    }

    /**
     * Get LLM description for a location
     */
    public static function get_llm_description($location_id)
    {
        $desc = get_post_meta($location_id, 'seo_llm_description', true);
        if ($desc)
            return $desc;

        $ai_desc = self::get_cached_ai_value($location_id, 'description');
        if ($ai_desc)
            return $ai_desc;

        $name = get_post_field('post_title', $location_id);
        $city = get_post_meta($location_id, 'location_city', true);
        $quality = get_post_meta($location_id, 'location_quality_rated', true);
        $ages = get_post_meta($location_id, 'location_ages_served', true);

        $parts = [$name, 'is'];
        if ($quality) {
            $stars = is_numeric($quality) ? $quality . '-Star' : '';
            $parts[] = $stars . ' Quality Rated';
        }
        $parts[] = 'pediatric therapy center';
        if ($city) {
            $parts[] = 'in ' . $city . ', Georgia';
        }
        if ($ages) {
            $parts[] = 'serving children ' . $ages;
        }

        return implode(' ', array_filter($parts)) . '.';
    }

    /**
     * Get LLM target queries for a location
     */
    public static function get_llm_target_queries($location_id)
    {
        $queries = get_post_meta($location_id, 'seo_llm_target_queries', true);
        if (!empty($queries) && is_array($queries))
            return array_filter($queries);

        $ai_queries = self::get_cached_ai_value($location_id, 'target_queries');
        if (!empty($ai_queries) && is_array($ai_queries))
            return array_filter($ai_queries);

        $city = get_post_meta($location_id, 'location_city', true);
        $name = get_post_field('post_title', $location_id);

        $queries = [];
        if ($city) {
            $queries[] = "best aba therapy near " . $city . " GA";
            $queries[] = "pediatric therapy in " . $city . " Georgia";
            $queries[] = "early intervention " . $city;
        }
        $queries[] = $name . " reviews";

        return array_filter($queries);
    }

    /**
     * Get LLM key differentiators for a location
     */
    public static function get_llm_key_differentiators($location_id)
    {
        $differentiators = get_post_meta($location_id, 'seo_llm_key_differentiators', true);
        if (!empty($differentiators) && is_array($differentiators))
            return array_filter($differentiators);

        $ai_diff = self::get_cached_ai_value($location_id, 'key_differentiators');
        if (!empty($ai_diff) && is_array($ai_diff))
            return array_filter($ai_diff);

        $diff = [];
        $quality = get_post_meta($location_id, 'location_quality_rated', true);
        if ($quality) {
            $diff[] = "Quality Rated Pediatric Therapy Provider";
        }
        $programs = self::get_location_programs($location_id);
        if (!empty($programs) && count($programs) > 1) {
            $diff[] = "Comprehensive multidisciplinary approach";
        }

        return array_filter($diff);
    }

    /**
     * Get programs offered at a location
     */
    public static function get_location_programs($location_id)
    {
        $all_programs = [];
        $cpt_programs = get_posts([
            'post_type' => 'program',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'program_locations',
                    'value' => '"' . $location_id . '"',
                    'compare' => 'LIKE',
                ],
            ],
        ]);

        foreach ($cpt_programs as $program_id) {
            $all_programs[] = get_post_field('post_title', $program_id);
        }

        $manual_text = get_post_meta($location_id, 'location_special_programs', true);
        if ($manual_text) {
            $manual_programs = array_map('trim', explode(',', $manual_text));
            $all_programs = array_merge($all_programs, $manual_programs);
        }

        return array_values(array_unique(array_filter($all_programs)));
    }

    /**
     * Get comparison factors for a location
     */
    public static function get_comparison_factors($location_id)
    {
        $factors = [];
        $ages = get_post_meta($location_id, 'location_ages_served', true);
        if ($ages)
            $factors['ageRangeServed'] = $ages;

        $hours = get_post_meta($location_id, 'location_hours', true);
        if ($hours)
            $factors['hoursOfOperation'] = $hours;

        $programs = self::get_location_programs($location_id);
        if (!empty($programs))
            $factors['uniqueFeatures'] = $programs;

        return $factors;
    }

    /**
     * Get citation facts for a location
     */
    public static function get_citation_facts($location_id)
    {
        $facts = [];
        $quality = get_post_meta($location_id, 'location_quality_rated', true);
        if ($quality) {
            $facts[] = [
                'label' => 'Clinical Quality Rating',
                'value' => 'Quality Rated',
                'source' => "State Regulatory Board",
                'verifiedDate' => date('Y-m-d'),
            ];
        }
        return $facts;
    }
}
