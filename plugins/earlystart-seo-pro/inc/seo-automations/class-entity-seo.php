<?php
/**
 * Entity SEO
 * Knowledge Graph optimization, topic clustering
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Entity_SEO
{
    public function __construct() {
        // DISABLED - Moved to Early Start SEO Pro Plugin
        // add_action('wp_head', [$this, 'output_organization_schema']);
        add_filter('the_content', [$this, 'add_semantic_markup'], 5);
    }
    
    /**
     * Output Organization schema with sameAs
     */
    public function output_organization_schema() {
        if (!is_front_page()) {
            return;
        }
        
        $same_as = get_option('earlystart_seo_same_as_urls', [
            'https://www.facebook.com/chromaearlylearning',
            'https://www.instagram.com/chromaearlylearning',
            'https://www.linkedin.com/company/chroma-early-learning'
        ]);
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => home_url('/') . '#organization',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
            'logo' => get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : '',
            'description' => get_bloginfo('description'),
            'sameAs' => array_filter($same_as)
        ];
        
        // Add founder if available
        $founder = get_option('earlystart_seo_founder_name');
        if ($founder) {
            $schema['founder'] = [
                '@type' => 'Person',
                'name' => $founder
            ];
        }
        
        // Add founding date
        $founded = get_option('earlystart_seo_founded_date');
        if ($founded) {
            $schema['foundingDate'] = $founded;
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . '</script>' . "\n";
    }
    
    /**
     * Add semantic markup to content
     */
    public function add_semantic_markup($content) {
        if (!is_string($content) || $content === '' || strpos($content, 'chroma-telephone') !== false) {
            return $content;
        }

        $parts = preg_split('/(<[^>]+>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        if (!is_array($parts)) {
            return $content;
        }

        foreach ($parts as &$part) {
            if ($part === '' || $part[0] === '<') {
                continue;
            }

            $part = preg_replace_callback(
                '/(\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4})/',
                function ($matches) {
                    return '<span class="chroma-telephone" data-entity="telephone">' . esc_html($matches[1]) . '</span>';
                },
                $part
            );
        }
        unset($part);

        return implode('', $parts);
    }
    
    /**
     * Get topic clusters
     */
    public static function get_topic_clusters() {
        // Define topic clusters
        $clusters = [
            'autism-diagnosis' => [
                'name' => 'Autism Diagnosis',
                'keywords' => ['autism diagnosis', 'autism evaluation', 'diagnostic evaluation'],
                'hub_page' => null
            ],
            'aba' => [
                'name' => 'ABA Therapy',
                'keywords' => ['aba therapy', 'applied behavior analysis', 'behavior therapy'],
                'hub_page' => null
            ],
            'behavioral-health' => [
                'name' => 'Behavioral Health',
                'keywords' => ['behavioral health', 'emotional regulation', 'coping skills'],
                'hub_page' => null
            ],
            'speech' => [
                'name' => 'Speech Therapy',
                'keywords' => ['speech therapy', 'speech-language therapy', 'communication support'],
                'hub_page' => null
            ],
            'ot' => [
                'name' => 'Occupational Therapy',
                'keywords' => ['occupational therapy', 'sensory support', 'motor skills'],
                'hub_page' => null
            ]
        ];
        
        // Find hub pages
        foreach ($clusters as $slug => &$cluster) {
            $page = get_page_by_path($slug);
            if ($page) {
                $cluster['hub_page'] = $page;
            }
        }
        
        return $clusters;
    }
}

new earlystart_Entity_SEO();


