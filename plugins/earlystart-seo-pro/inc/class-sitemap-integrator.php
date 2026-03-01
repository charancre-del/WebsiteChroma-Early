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
        $this->register_providers();
    }

    public function register_providers()
    {
        // One sitemap for all Spanish pages/posts (Singulars)
        wp_register_sitemap_provider('spanish', new earlystart_Spanish_Sitemap_Provider());
    }
}

/**
 * Custom Sitemap Provider for Spanish Content
 * Includes all translated post types (Singulars Only)
 */
class earlystart_Spanish_Sitemap_Provider extends WP_Sitemaps_Provider {
    
    public function __construct() {
        $this->name = 'spanish';
        $this->object_type = 'custom'; 
    }

    private $per_page = 2000;
    private $post_types = ['page', 'location', 'program', 'city', 'post', 'team_member'];

    private function build_spanish_url($url) {
        $base = rtrim(get_option('home'), '/');
        $url = (string) $url;

        if (!$url) {
            return '';
        }

        $url = str_replace($base . '/es/', $base . '/', $url);
        if ($url === $base . '/es') {
            $url = $base . '/';
        }

        if (strpos($url, $base) !== 0) {
            return '';
        }

        $path = ltrim(substr($url, strlen($base)), '/');
        return $path ? $base . '/es/' . $path : $base . '/es/';
    }

    public function get_url_list($page_num, $object_subtype = '') {
        $urls = [];
        $seen = [];

        $register_url = function ($url, $lastmod = null) use (&$urls, &$seen) {
            $es_url = $this->build_spanish_url($url);
            if (!$es_url || isset($seen[$es_url])) {
                return;
            }

            $seen[$es_url] = true;
            $urls[] = [
                'loc' => $es_url,
                'lastmod' => $lastmod ?: current_time('c'),
            ];
        };

        $register_url(home_url('/'), current_time('c'));

        foreach ($this->post_types as $post_type) {
            if (!post_type_exists($post_type)) {
                continue;
            }

            $archive_url = get_post_type_archive_link($post_type);
            if ($archive_url) {
                $register_url($archive_url, current_time('c'));
            }
        }
        
        $posts = get_posts([
            'post_type' => $this->post_types,
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        foreach ($posts as $post) {
            $en_permalink = get_permalink($post->ID);
            if ($en_permalink) {
                $register_url($en_permalink, get_the_modified_date('c', $post->ID));
            }
        }

        // Pagination
        $offset = ($page_num - 1) * $this->per_page;
        return array_slice($urls, $offset, $this->per_page);
    }


    public function get_max_num_pages($object_subtype = '') {
        $count = 1;
        foreach ($this->post_types as $type) {
            $count += (int)wp_count_posts($type)->publish;
            if (post_type_exists($type) && get_post_type_archive_link($type)) {
                $count++;
            }
        }
        return max(1, ceil($count / $this->per_page));
    }
}


