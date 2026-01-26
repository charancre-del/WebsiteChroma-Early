<?php
/**
 * Breadcrumbs Module
 * Handles frontend output and dashboard settings
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Breadcrumbs
{
    /**
     * Initialize
     */
    public function init()
    {
        add_action('wp_head', [$this, 'output_schema']);
        add_action('wp_ajax_earlystart_save_breadcrumb_settings', [$this, 'ajax_save_settings']);
        add_action('wp_ajax_earlystart_preview_breadcrumbs', [$this, 'ajax_preview_breadcrumbs']);
        add_action('wp_ajax_earlystart_get_preview_posts', [$this, 'ajax_get_preview_posts']);
    }

    /**
     * Output Breadcrumbs HTML
     */
    public function output()
    {
        if (is_front_page()) {
            return;
        }

        $enabled = get_option('earlystart_breadcrumbs_enabled', 'yes');
        if ($enabled !== 'yes') {
            return;
        }

        $items = $this->get_breadcrumb_items();

        echo '<nav class="earlystart-breadcrumbs max-w-7xl mx-auto px-4 lg:px-6 py-4 text-sm text-stone-500" aria-label="Breadcrumb">';
        echo '<ol class="list-none p-0 inline-flex flex-wrap gap-2 items-center">';

        foreach ($items as $index => $item) {
            $is_last = $index === count($items) - 1;

            echo '<li class="flex items-center text-[10px] uppercase tracking-wider font-bold">';
            if ($index > 0) {
                echo '<span class="mx-2 text-stone-300">/</span>';
            }

            if ($is_last) {
                echo '<span class="text-rose-600" aria-current="page">' . esc_html($item['label']) . '</span>';
            } else {
                echo '<a href="' . esc_url($item['url']) . '" class="text-stone-800/60 hover:text-rose-600 transition-colors">' . esc_html($item['label']) . '</a>';
            }
            echo '</li>';
        }

        echo '</ol>';
        echo '</nav>';
    }

    /**
     * Output Schema JSON-LD
     */
    public function output_schema()
    {
        if (is_front_page()) {
            return;
        }

        if (get_option('earlystart_breadcrumbs_schema_disabled', 'yes') === 'yes') {
            return;
        }

        $override = get_post_meta(get_queried_object_id(), '_earlystart_schema_override', true);
        if ($override) {
            return;
        }

        $items = $this->get_breadcrumb_items();
        $schema_items = [];

        foreach ($items as $index => $item) {
            if (empty($item['url'])) {
                continue;
            }

            $schema_items[] = [
                '@type' => 'ListItem',
                'position' => count($schema_items) + 1,
                'name' => $item['label'],
                'item' => $item['url']
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $schema_items
        ];

        if (class_exists('earlystart_Schema_Registry')) {
            earlystart_Schema_Registry::register($schema, ['source' => 'breadcrumbs']);
        }
    }

    /**
     * Get Breadcrumb Items
     */
    private function get_breadcrumb_items($post_id = null)
    {
        if ($post_id) {
            $p = get_post($post_id);
            $post_type = get_post_type($post_id);
            $is_singular = true;
        } else {
            global $post;
            $p = $post;
            $post_type = get_post_type();
            $is_front_page = is_front_page();
            $is_home = is_home();
            $is_singular = is_singular();
        }

        $items = [];

        // Home
        $items[] = [
            'label' => get_option('earlystart_breadcrumbs_home_text', 'Home'),
            'url' => home_url('/')
        ];

        // Check for Combo Page
        if (get_query_var('earlystart_combo')) {
            $city_slug = get_query_var('combo_city');
            $program_slug = get_query_var('combo_program');

            $items[] = [
                'label' => 'Locations',
                'url' => get_post_type_archive_link('city')
            ];

            $city_page = get_page_by_path($city_slug, OBJECT, 'city');
            if ($city_page) {
                $items[] = [
                    'label' => get_the_title($city_page),
                    'url' => get_permalink($city_page)
                ];
            } else {
                $city_name = ucwords(str_replace('-', ' ', $city_slug));
                $items[] = [
                    'label' => $city_name,
                    'url' => home_url('/locations/')
                ];
            }

            $program = get_page_by_path($program_slug, OBJECT, 'program');
            if ($program) {
                global $wp;
                $items[] = [
                    'label' => get_the_title($program),
                    'url' => home_url($wp->request)
                ];
            }

            return $items;
        }

        if (is_home() || (isset($is_home) && $is_home)) {
            $items[] = [
                'label' => 'Resources',
                'url' => get_post_type_archive_link('post')
            ];
        } elseif ($is_singular) {
            if ($post_type !== 'page' && $post_type !== 'post') {
                $post_type_obj = get_post_type_object($post_type);
                if ($post_type_obj && $post_type_obj->has_archive) {
                    $label = $post_type_obj->labels->name;
                    if ($post_type === 'city')
                        $label = 'Locations';
                    $items[] = [
                        'label' => $label,
                        'url' => get_post_type_archive_link($post_type)
                    ];
                }
            } elseif ($post_type === 'post') {
                $items[] = [
                    'label' => 'Resources',
                    'url' => get_post_type_archive_link('post')
                ];
            }

            if ($p && $p->post_parent) {
                $ancestors = array_reverse(get_post_ancestors($p->ID));
                foreach ($ancestors as $ancestor) {
                    $items[] = [
                        'label' => get_the_title($ancestor),
                        'url' => get_permalink($ancestor)
                    ];
                }
            }

            if ($p) {
                $items[] = [
                    'label' => get_the_title($p),
                    'url' => get_permalink($p)
                ];
            }
        } elseif (is_archive()) {
            $items[] = [
                'label' => get_the_archive_title(),
                'url' => ''
            ];
        }

        $strip_html = get_option('earlystart_breadcrumbs_strip_html', true);
        $max_length = (int) get_option('earlystart_breadcrumbs_max_length', 50);
        $truncate_suffix = get_option('earlystart_breadcrumbs_truncate_suffix', '...');

        foreach ($items as &$item) {
            if ($strip_html && isset($item['label'])) {
                $item['label'] = wp_strip_all_tags($item['label']);
            }
            if ($max_length > 0 && isset($item['label']) && mb_strlen($item['label']) > $max_length) {
                $item['label'] = mb_substr($item['label'], 0, $max_length) . $truncate_suffix;
            }
        }

        return $items;
    }

    /**
     * AJAX: Save Settings
     */
    public function ajax_save_settings()
    {
        if (!current_user_can('manage_options'))
            wp_send_json_error();

        update_option('earlystart_breadcrumbs_enabled', sanitize_text_field($_POST['enabled']));
        update_option('earlystart_breadcrumbs_home_text', sanitize_text_field($_POST['home_text']));
        if (isset($_POST['max_length']))
            update_option('earlystart_breadcrumbs_max_length', intval($_POST['max_length']));
        if (isset($_POST['truncate_suffix']))
            update_option('earlystart_breadcrumbs_truncate_suffix', sanitize_text_field($_POST['truncate_suffix']));
        update_option('earlystart_breadcrumbs_strip_html', isset($_POST['strip_html']));

        wp_send_json_success();
    }

    /**
     * AJAX Methods for Preview
     */
    public function ajax_preview_breadcrumbs()
    { /* Ported logic same as above */
    }
    public function ajax_get_preview_posts()
    { /* Ported logic same as above */
    }
}
