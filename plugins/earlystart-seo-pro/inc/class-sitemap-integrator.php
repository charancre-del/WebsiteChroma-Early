<?php
/**
 * Sitemap Integrator
 * Injects Spanish URLs into the native WordPress XML sitemap.
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Sitemap_Integrator
{
    public function init()
    {
        add_action('init', [$this, 'register_providers'], 20);
    }

    public function register_providers()
    {
        if (
            !function_exists('wp_register_sitemap_provider')
            || !class_exists('WP_Sitemaps_Provider')
            || !class_exists('earlystart_Spanish_Sitemap_Provider')
        ) {
            return;
        }

        // One sitemap for all Spanish pages/posts (Singulars)
        wp_register_sitemap_provider('spanish', new earlystart_Spanish_Sitemap_Provider());
    }
}

/**
 * Custom Sitemap Provider for Spanish Content
 * Includes all translated post types (Singulars Only)
 */
if (class_exists('WP_Sitemaps_Provider')) {
class earlystart_Spanish_Sitemap_Provider extends WP_Sitemaps_Provider {
    
    public function __construct() {
        $this->name = 'spanish';
        $this->object_type = 'custom'; 
    }

    private $per_page = 2000;
    private $post_types = ['page', 'location', 'program', 'city', 'post', 'team_member'];

    public function get_url_list($page_num, $object_subtype = '') {
        $urls = [];
        $base = rtrim(get_option('home'), '/');

        foreach ($this->get_translated_post_ids() as $post_id) {
            // Direct URL construction (avoids context issues with get_alternates)
            $en_permalink = get_permalink($post_id);
            if ($en_permalink) {
                // Remove base and prepend /es/
                $path = str_replace($base, '', $en_permalink);
                $path = ltrim($path, '/');
                $es_url = user_trailingslashit($base . '/es/' . $path);

                $urls[] = [
                    'loc' => $es_url,
                    'lastmod' => get_the_modified_date('c', $post_id),
                ];
            }
        }

        // Pagination
        $offset = ($page_num - 1) * $this->per_page;
        return array_slice($urls, $offset, $this->per_page);
    }


    public function get_max_num_pages($object_subtype = '') {
        $count = count($this->get_translated_post_ids());
        return max(1, ceil($count / $this->per_page));
    }

    private function get_translated_post_ids() {
        $post_ids = get_posts([
            'post_type' => $this->post_types,
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'fields' => 'ids',
            'no_found_rows' => true,
        ]);

        return array_values(array_filter(array_map('absint', $post_ids), [$this, 'has_spanish_content']));
    }

    private function has_spanish_content($post_id) {
        if (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'has_spanish_content')) {
            return earlystart_Multilingual_Manager::has_spanish_content($post_id);
        }

        if (get_post_meta($post_id, 'alternate_url_es', true)) {
            return true;
        }

        foreach (get_post_meta($post_id) as $key => $values) {
            if (strpos($key, '_earlystart_es_') !== 0) {
                continue;
            }

            foreach ((array) $values as $value) {
                if ($this->value_has_content($value)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function value_has_content($value) {
        $value = maybe_unserialize($value);

        if (is_array($value)) {
            foreach ($value as $item) {
                if ($this->value_has_content($item)) {
                    return true;
                }
            }

            return false;
        }

        if (is_object($value)) {
            return $this->value_has_content((array) $value);
        }

        return trim(wp_strip_all_tags((string) $value)) !== '';
    }
}
}
