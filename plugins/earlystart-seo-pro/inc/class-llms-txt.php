<?php
/**
 * LLMs.txt Generator
 * Generates a /llms.txt file for AI crawlers to better understand the site structure.
 * Standard: https://llmstxt.org/
 * 
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_LLMs_Txt_Generator
{
    const CONTENT_SCHEMA_VERSION = '2';

    /**
     * Init hooks
     */
    public function init()
    {
        // Keep a WordPress-rendered fallback when the physical root file is missing.
        $this->add_rewrite_rule();
        add_filter('query_vars', [$this, 'add_query_var']);
        add_action('template_redirect', [$this, 'render_file']);
        add_filter('robots_txt', [$this, 'add_robots_reference'], 20, 2);

        // Physical File Generation Hooks
        add_action('admin_init', [$this, 'write_physical_file']); // Force check on admin load
        add_action('save_post', [$this, 'write_physical_file']);
        add_action('added_post_meta', [$this, 'maybe_refresh_for_meta_change'], 10, 4);
        add_action('updated_post_meta', [$this, 'maybe_refresh_for_meta_change'], 10, 4);
        add_action('deleted_post_meta', [$this, 'maybe_refresh_for_meta_change'], 10, 4);
        $this->maybe_refresh_physical_file();
    }

    /**
     * Write Physical File
     */
    public static function refresh_file()
    {
        (new self())->write_physical_file(true);
    }

    /**
     * Refresh the root llms.txt once per generator signature so deployed fixes
     * are not hidden behind an older physical file until the next admin visit.
     */
    public function maybe_refresh_physical_file()
    {
        $target_version = $this->get_generation_signature();
        $file_path = ABSPATH . 'llms.txt';

        if (get_option('earlystart_llms_txt_generated_version') === $target_version && file_exists($file_path)) {
            return;
        }

        if (!is_admin() && get_option('earlystart_llms_txt_refresh_attempt_version') === $target_version) {
            return;
        }

        update_option('earlystart_llms_txt_refresh_attempt_version', $target_version, false);

        if ($this->write_physical_file(true)) {
            update_option('earlystart_llms_txt_generated_version', $target_version, false);
        }
    }

    private function get_generation_signature()
    {
        $plugin_version = defined('EARLYSTART_SEO_VERSION') ? EARLYSTART_SEO_VERSION : 'unknown';
        $generator_mtime = @filemtime(__FILE__);

        return implode(':', [
            $plugin_version,
            'llms-v' . self::CONTENT_SCHEMA_VERSION,
            $generator_mtime ? (string) $generator_mtime : 'unknown',
        ]);
    }

    public function write_physical_file($context = false)
    {
        $force = ($context === true);
        $post_id = is_numeric($context) ? (int) $context : 0;

        if (!$force) {
            if ($post_id > 0 && (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id))) {
                return false;
            }

            // Only run if we are in admin or it's an AJAX save.
            if (!is_admin() && !wp_doing_ajax()) {
                return false;
            }

            if (wp_doing_ajax()) {
                $action = sanitize_text_field($_POST['action'] ?? '');
                if ($action !== 'earlystart_save_llm_targeting') {
                    return false;
                }

                if (!current_user_can('edit_posts')) {
                    return false;
                }

                // Keep this callback independently secure, even if another callback validates first.
                if (!check_ajax_referer('earlystart_seo_dashboard_nonce', 'nonce', false)) {
                    return false;
                }
            } elseif ($post_id > 0) {
                if (!current_user_can('edit_post', $post_id)) {
                    return false;
                }
            } elseif (!current_user_can('manage_options')) {
                return false;
            }
        }

        $file_path = ABSPATH . 'llms.txt';
        $content = $this->generate_content();

        // Write file
        $handle = @fopen($file_path, 'w');
        if ($handle) {
            fwrite($handle, $content);
            fclose($handle);
            return true;
        }

        return false;
    }

    /**
     * Refresh llms.txt when Agent API/meta-only writes touch fields used in the guide.
     */
    public function maybe_refresh_for_meta_change($meta_id, $post_id, $meta_key, $meta_value)
    {
        if ($post_id <= 0 || wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return false;
        }

        $watched_keys = [
            'seo_llm_primary_intent',
            'seo_llm_target_queries',
            'seo_llm_key_differentiators',
            'location_city',
            'earlystart_location_city',
            'location_address',
            'earlystart_location_address',
        ];

        if (!in_array((string) $meta_key, $watched_keys, true)) {
            return false;
        }

        return $this->write_physical_file(true);
    }

    public function add_rewrite_rule()
    {
        add_rewrite_rule('^llms\.txt$', 'index.php?earlystart_llms_txt=1', 'top');
    }

    public function add_query_var($vars)
    {
        $vars[] = 'earlystart_llms_txt';
        return $vars;
    }

    public function render_file()
    {
        if (!get_query_var('earlystart_llms_txt')) {
            return;
        }

        status_header(200);
        nocache_headers();
        header('Content-Type: text/plain; charset=utf-8');
        echo $this->generate_content();
        exit;
    }

    public function add_robots_reference($output, $public)
    {
        if ('0' === (string) $public) {
            return $output;
        }

        $llms_url = $this->canonical_url(home_url('/llms.txt'));
        if (false !== strpos((string) $output, $llms_url)) {
            return $output;
        }

        return rtrim((string) $output) . "\n\n# LLM-readable site guide\nLLMs: {$llms_url}\n";
    }

    private function normalize_line($value)
    {
        $text = html_entity_decode(wp_strip_all_tags((string) $value), ENT_QUOTES, get_bloginfo('charset') ?: 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    private function markdown_link($label, $url)
    {
        $label = $this->normalize_line($label);
        $label = str_replace(['[', ']'], ['\\[', '\\]'], $label);
        $url = esc_url_raw($this->canonical_url((string) $url));

        if ($label === '' || $url === '') {
            return '';
        }

        return "- [{$label}]({$url})\n";
    }

    private function canonical_url($url)
    {
        $url = esc_url_raw((string) $url);

        if ($url === '' || false !== strpos($url, '?')) {
            return $url;
        }

        $path = (string) wp_parse_url($url, PHP_URL_PATH);
        if ($path !== '' && pathinfo($path, PATHINFO_EXTENSION)) {
            return $url;
        }

        return trailingslashit($url);
    }

    private function blockquote_line($value)
    {
        $line = $this->normalize_line($value);

        return $line === '' ? '' : "  > {$line}\n";
    }

    /**
     * Helper to get LLM Context
     */
    private function get_llm_context($post_id)
    {
        $parts = [];
        
        $intent = get_post_meta($post_id, 'seo_llm_primary_intent', true);
        $queries = get_post_meta($post_id, 'seo_llm_target_queries', true) ?: [];
        $diffs = get_post_meta($post_id, 'seo_llm_key_differentiators', true) ?: [];

        if ($intent) {
            $parts[] = 'Primary Intent: ' . $this->normalize_line($intent) . '.';
        }

        if (!empty($diffs)) {
            $diffs = array_filter(array_map([$this, 'normalize_line'], array_slice((array) $diffs, 0, 3)));
            if (!empty($diffs)) {
                $parts[] = 'Key Features: ' . implode('; ', $diffs) . '.';
            }
        }

        if (!empty($queries)) {
            $queries = array_filter(array_map([$this, 'normalize_line'], array_slice((array) $queries, 0, 5)));
            if (!empty($queries)) {
                $parts[] = 'Relevant for: ' . implode(', ', $queries) . '.';
            }
        }

        if (empty($parts)) {
            return "";
        }

        // Output as a single clean blockquote line for better parsing compliance
        return $this->blockquote_line(implode(' ', $parts));
    }

    /**
     * Generate Content
     */
    private function generate_content()
    {
        $site_name = get_bloginfo('name');
        $site_desc = get_bloginfo('description');
        $output = '# ' . $this->normalize_line($site_name) . "\n";
        $output .= '> ' . $this->normalize_line($site_desc) . "\n\n"; // Blockquote for description as per spec conventions
        
        $output .= "## Main Sections\n\n";
        $output .= $this->markdown_link('Home', home_url('/'));
        $output .= $this->markdown_link('Locations', home_url('/locations/'));
        $output .= $this->markdown_link('Programs', home_url('/programs/'));
        $output .= $this->markdown_link('Blog', home_url('/stories/'));
        $output .= $this->markdown_link('Careers', home_url('/careers/'));
        $output .= "\n";

        // Programs
        $output .= "## Programs (Curriculum)\n\n";
        $programs = get_posts([
            'post_type' => 'program',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        if ($programs) {
            foreach ($programs as $program) {
                $output .= $this->markdown_link($program->post_title, get_permalink($program->ID));
                // Add brief summary if available
                if (!empty($program->post_excerpt)) {
                    $output .= $this->blockquote_line($program->post_excerpt);
                }
                // Add LLM Context
                $output .= $this->get_llm_context($program->ID);
            }
            $output .= "\n";
        }

        // Locations
        $output .= "## Locations (Campuses)\n\n";
        $locations = get_posts([
            'post_type' => 'location',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        if ($locations) {
            foreach ($locations as $location) {
                // Get City for context
                $city = get_post_meta($location->ID, 'location_city', true);
                if (!$city) {
                    $city = get_post_meta($location->ID, 'earlystart_location_city', true);
                }
                if (!$city) {
                     // Try to get from address
                     $address = get_post_meta($location->ID, 'location_address', true);
                     if (!$address) {
                         $address = get_post_meta($location->ID, 'earlystart_location_address', true);
                     }
                     if ($address && preg_match('/, ([^,]+), [A-Z]{2}/', $address, $matches)) {
                         $city = trim($matches[1]);
                     }
                }

                $title = $location->post_title;
                if ($city) {
                    $title .= ' (' . $this->normalize_line($city) . ')';
                }

                $output .= $this->markdown_link($title, get_permalink($location->ID));
                
                // Add LLM Context
                $output .= $this->get_llm_context($location->ID);
            }
            $output .= "\n";
        }

        // Cities / Service Areas
        $cities = get_posts([
            'post_type' => 'city',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        if ($cities) {
            $output .= "## Service Areas (Cities)\n\n";
            foreach ($cities as $city) {
                $output .= $this->markdown_link($city->post_title, get_permalink($city->ID));
                $output .= $this->get_llm_context($city->ID);
            }
            $output .= "\n";
        }

        // Core Pages (Only those with specific LLM intent set)
        $pages = get_posts([
            'post_type' => 'page',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => 'seo_llm_primary_intent',
                    'value' => '',
                    'compare' => '!='
                ]
            ]
        ]);

        if ($pages) {
            $output .= "## Core Information\n\n";
            foreach ($pages as $page) {
                $output .= $this->markdown_link($page->post_title, get_permalink($page->ID));
                $output .= $this->get_llm_context($page->ID);
            }
            $output .= "\n";
        }

        // Blog Posts (Only those with specific LLM intent set)
        $posts = get_posts([
            'post_type' => 'post',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_query' => [
                [
                    'key' => 'seo_llm_primary_intent',
                    'value' => '',
                    'compare' => '!='
                ]
            ]
        ]);

        if ($posts) {
            $output .= "## Blog & Updates\n\n";
            foreach ($posts as $p) {
                $output .= $this->markdown_link($p->post_title, get_permalink($p->ID));
                $output .= $this->get_llm_context($p->ID);
            }
            $output .= "\n";
        }

        // Catch-All: Any other public post type with LLM data (e.g. Events, Careers if CPT)
        $exclude_types = ['location', 'program', 'page', 'city', 'post', 'attachment', 'revision', 'nav_menu_item'];
        $public_types = get_post_types(['public' => true, '_builtin' => false]);
        $check_types = array_diff($public_types, $exclude_types);

        if (!empty($check_types)) {
            $others = get_posts([
                'post_type' => array_values($check_types),
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => [
                    [
                        'key' => 'seo_llm_primary_intent',
                        'value' => '',
                        'compare' => '!='
                    ]
                ]
            ]);

            if ($others) {
                $output .= "## Other Resources\n\n";
                foreach ($others as $other) {
                    $output .= $this->markdown_link($other->post_title, get_permalink($other->ID));
                    $output .= $this->get_llm_context($other->ID);
                }
                $output .= "\n";
            }
        }

        $output .= "## About Us\n\n";
        $output .= "Chroma Early Start is a network of pediatric therapy clinics across Metro Atlanta.\n";
        $output .= "We provide coordinated ABA therapy, speech therapy, occupational therapy, and early intervention support for children and families.\n";

        return $output;
    }
}


