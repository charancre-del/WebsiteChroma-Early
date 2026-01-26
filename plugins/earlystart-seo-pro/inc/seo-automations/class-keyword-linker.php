<?php
/**
 * Keyword Linker
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Keyword_Linker
{
    public function __construct()
    {
        add_filter('the_content', [$this, 'auto_link_keywords'], 15);
    }

    public function auto_link_keywords($content)
    {
        if (is_singular(['location', 'program']))
            return $content;

        $keywords = $this->get_auto_keywords();

        foreach ($keywords as $kw) {
            $keyword = preg_quote($kw['keyword'], '/');
            // Simplified regex for compatibility and readability
            $pattern = '/(?<!["\'>])(\b' . $keyword . '\b)(?![^<]*<\/a>)(?![^<]*>)/i';
            $content = preg_replace($pattern, '<a href="' . esc_url($kw['url']) . '" class="earlystart-auto-link">$1</a>', $content, 1);
        }

        return $content;
    }

    private function get_auto_keywords()
    {
        $keywords = [];
        $programs = get_posts(['post_type' => 'program', 'posts_per_page' => -1]);
        foreach ($programs as $prog) {
            $keywords[] = ['keyword' => strtolower($prog->post_title), 'url' => get_permalink($prog)];
        }

        $locations = get_posts(['post_type' => 'location', 'posts_per_page' => -1]);
        foreach ($locations as $loc) {
            $city = get_post_meta($loc->ID, 'location_city', true);
            if ($city) {
                // Updated to Pediatric Therapy
                $keywords[] = ['keyword' => strtolower($city . ' pediatric therapy'), 'url' => get_permalink($loc)];
                $keywords[] = ['keyword' => strtolower('pediatric therapy in ' . $city), 'url' => get_permalink($loc)];
            }
        }
        return $keywords;
    }
}
new earlystart_Keyword_Linker();
