<?php
/**
 * Combo Page Generator
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Combo_Page_Generator
{
    const REWRITE_TAG = 'earlystart_combo';

    public function __construct()
    {
        add_action('init', [$this, 'add_rewrite_rules']);
        add_filter('query_vars', [$this, 'add_query_vars']);
        add_action('template_redirect', [$this, 'handle_combo_page']);
    }

    public function add_rewrite_rules()
    {
        add_rewrite_rule(
            '^([a-z0-9-]+)-in-([a-z-]+)-([a-z]{2})/?$',
            'index.php?' . self::REWRITE_TAG . '=1&combo_program=$matches[1]&combo_city=$matches[2]&combo_state=$matches[3]',
            'top'
        );
    }

    public function add_query_vars($vars)
    {
        $vars[] = self::REWRITE_TAG;
        $vars[] = 'combo_program';
        $vars[] = 'combo_city';
        $vars[] = 'combo_state';
        return $vars;
    }

    public function handle_combo_page()
    {
        if (!get_query_var(self::REWRITE_TAG))
            return;

        $program_slug = sanitize_title(get_query_var('combo_program'));
        $city_slug = sanitize_title(get_query_var('combo_city'));
        $state = strtoupper(sanitize_text_field(get_query_var('combo_state')));

        $program = get_page_by_path($program_slug, OBJECT, 'program');
        if (!$program) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            return;
        }

        $city_name = ucwords(str_replace('-', ' ', $city_slug));
        $seo_title = get_the_title($program) . " in {$city_name}, {$state} | Early Start";

        add_filter('pre_get_document_title', function () use ($seo_title) {
            return $seo_title; }, PHP_INT_MAX);

        status_header(200);
        get_header();
        echo '<main class="py-20 bg-stone-50"><div class="max-w-4xl mx-auto px-4">';
        echo '<h1 class="text-4xl font-bold mb-6">' . get_the_title($program) . ' in ' . esc_html($city_name) . '</h1>';
        echo '<p class="text-lg text-stone-600 mb-8">' . sprintf(__('Discover premium %s for your child in %s. Early Start provides specialized pediatric therapy and early intervention services.', 'earlystart-seo-pro'), strtolower(get_the_title($program)), esc_html($city_name)) . '</p>';
        echo '</div></main>';
        get_footer();
        exit;
    }
}
new earlystart_Combo_Page_Generator();
