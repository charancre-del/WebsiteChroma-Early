<?php
/**
 * Speculation Rules API Support
 * Provides near-instant navigation by pre-rendering pages
 * 
 * @package earlystart_Excellence
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Speculation_Rules
{
    public static function init()
    {
        add_action('wp_head', [__CLASS__, 'output'], 1);
    }

    public static function output()
    {
        // Only for public frontend
        if (is_admin()) {
            return;
        }

        // Check if enabled (default true)
        if (get_option('earlystart_enable_speculation_rules', 'yes') !== 'yes') {
            return;
        }

        $rules = [
            'prerender' => [
                [
                    'source' => 'document',
                    'where' => [
                        'and' => [
                            ['href_matches' => '/*'],
                            ['not' => ['href_matches' => '/wp-admin/*']],
                            ['not' => ['href_matches' => '/wp-login.php*']],
                            ['not' => ['href_matches' => '/*.pdf']],
                        ]
                    ],
                    'eagerness' => 'moderate'
                ]
            ]
        ];

        echo "\n<!-- Tier 3: Instant Navigation (Speculation Rules API) -->\n";
        echo '<script type="speculationrules">' . wp_json_encode($rules, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}

earlystart_Speculation_Rules::init();


