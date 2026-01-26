<?php
/**
 * Multilingual Manager
 * Handles URL routing, rewrite rules, and link filtering for Spanish sub-directory structure.
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Multilingual_Manager
{
    /**
     * Singleton instance
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize hooks
     */
    public function init()
    {
        add_action('init', [$this, 'setup_rewrites']);
        add_filter('query_vars', [$this, 'add_query_vars']);

        // URL Filters
        add_filter('home_url', [$this, 'filter_home_url'], 10, 2);
        add_filter('page_link', [$this, 'filter_permalink'], 10, 2);
        add_filter('post_link', [$this, 'filter_permalink'], 10, 2);
        add_filter('post_type_link', [$this, 'filter_permalink'], 10, 2);
        add_filter('term_link', [$this, 'filter_term_link'], 10, 3);

        // Add body class for language
        add_filter('body_class', [$this, 'add_body_class']);

        // Language Attributes
        add_filter('language_attributes', [$this, 'filter_language_attributes']);

        // Hreflang Tags
        add_action('wp_head', [$this, 'output_hreflang_tags'], 1);

        // Frontend Content Swapping
        add_filter('the_title', [$this, 'swap_title'], 10, 2);
        add_filter('the_content', [$this, 'swap_content'], 10);
        add_filter('the_excerpt', [$this, 'swap_excerpt'], 10);

        // SEO Metadata Localization
        add_filter('pre_get_document_title', [$this, 'localize_seo_title'], 30);
        add_action('wp_head', [$this, 'localize_meta_description'], 1);

        // Internal Link Rewriting
        add_filter('the_content', [$this, 'rewrite_content_urls'], 20);
        add_filter('nav_menu_link_attributes', [$this, 'filter_nav_menu_link'], 10, 4);

        // Canonical URL Correction
        add_filter('get_canonical_url', [$this, 'filter_canonical_url'], 10, 2);

        // Fallback Banner CSS
        add_action('wp_head', [$this, 'output_fallback_css']);

        // Dynamic Translation of common UI strings
        add_filter('gettext', [$this, 'dynamic_translation_filter'], 20, 3);
    }

    /**
     * Output Hreflang Tags
     */
    public function output_hreflang_tags()
    {
        if (!is_singular() && !is_home() && !is_front_page()) {
            return;
        }

        $post_id = is_singular() ? get_the_ID() : null;
        if (!$post_id && (is_front_page() || is_home())) {
            $post_id = get_option('page_on_front');
        }

        $alternates = self::get_alternates($post_id);

        if (empty($alternates['en']) || empty($alternates['es'])) {
            return;
        }

        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($alternates['en']) . '" />' . "\n";
        echo '<link rel="alternate" hreflang="en-US" href="' . esc_url($alternates['en']) . '" />' . "\n";
        echo '<link rel="alternate" hreflang="es-US" href="' . esc_url($alternates['es']) . '" />' . "\n";
    }

    /**
     * Setup rewrite rules
     */
    public function setup_rewrites()
    {
        add_rewrite_tag('%earlystart_lang%', '([^&]+)');

        add_rewrite_rule('^es/?$', 'index.php?earlystart_lang=es', 'top');
        add_rewrite_rule('^es/locations/?$', 'index.php?post_type=location&earlystart_lang=es', 'top');
        add_rewrite_rule('^es/programs/?$', 'index.php?post_type=program&earlystart_lang=es', 'top');
        add_rewrite_rule('^es/locations/(.+?)/?$', 'index.php?location=$matches[1]&earlystart_lang=es', 'top');
        add_rewrite_rule('^es/programs/(.+?)/?$', 'index.php?program=$matches[1]&earlystart_lang=es', 'top');
        add_rewrite_rule('^es/(.+?)/?$', 'index.php?pagename=$matches[1]&earlystart_lang=es', 'top');
    }

    /**
     * Add query vars
     */
    public function add_query_vars($vars)
    {
        $vars[] = 'earlystart_lang';
        return $vars;
    }

    /**
     * Check if current request is Spanish
     */
    public static function is_spanish()
    {
        if (isset($_GET['earlystart_lang']) && $_GET['earlystart_lang'] === 'es') {
            return true;
        }

        if (defined('EARLYSTART_CURRENT_LANG') && EARLYSTART_CURRENT_LANG === 'es') {
            return true;
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
            if (strpos($uri, '/es/') !== false || substr($uri, -3) === '/es') {
                return true;
            }
        }

        return false;
    }

    /**
     * Get current language code
     */
    public static function get_current_language()
    {
        return self::is_spanish() ? 'es' : 'en';
    }

    /**
     * Filter Home URL
     */
    public function filter_home_url($url, $path)
    {
        if (self::is_spanish() && !is_admin()) {
            $path = ltrim($path, '/');
            if (strpos($url, '/es/') !== false || strpos($path, 'es/') === 0 || $path === 'es') {
                return $url;
            }
            $home = rtrim(get_option('home'), '/');
            return $home . '/es/' . $path;
        }
        return $url;
    }

    /**
     * Filter Permalinks
     */
    public function filter_permalink($url, $post)
    {
        if (self::is_spanish() && !is_admin()) {
            if (strpos($url, '/es/') !== false) {
                return $url;
            }
            $base = rtrim(get_option('home'), '/');
            if (strpos($url, $base) !== false) {
                $path = substr($url, strlen($base));
                return $base . '/es' . $path;
            }
        }
        return $url;
    }

    /**
     * Filter Term Links
     */
    public function filter_term_link($url, $term, $taxonomy)
    {
        if (self::is_spanish() && !is_admin()) {
            $base = rtrim(get_option('home'), '/');
            if (strpos($url, $base) !== false) {
                $path = substr($url, strlen($base));
                return $base . '/es' . $path;
            }
        }
        return $url;
    }

    /**
     * Add body class
     */
    public function add_body_class($classes)
    {
        if (self::is_spanish()) {
            $classes[] = 'lang-es';
            $classes[] = 'translate-spanish';
        }
        return $classes;
    }

    /**
     * Filter language attributes
     */
    public function filter_language_attributes($output)
    {
        if (self::is_spanish()) {
            return 'lang="es-US"';
        }
        return $output;
    }

    /**
     * Get alternate URLs
     */
    public static function get_alternates($post_id = null)
    {
        global $wp;
        if ($post_id) {
            $en_url = get_permalink($post_id);
        } elseif (is_singular()) {
            $en_url = get_permalink();
        } elseif (is_front_page() || is_home()) {
            $en_url = rtrim(get_option('home'), '/');
        } elseif (is_post_type_archive()) {
            $en_url = get_post_type_archive_link(get_query_var('post_type'));
        } elseif (is_category() || is_tag() || is_tax()) {
            $en_url = get_term_link(get_queried_object());
        } elseif (is_404()) {
            $en_url = rtrim(get_option('home'), '/');
        } else {
            $en_url = home_url($wp->request);
        }

        if (is_wp_error($en_url) || !$en_url) {
            $en_url = rtrim(get_option('home'), '/');
        }

        $base_home = rtrim(get_option('home'), '/');
        $en_url = str_replace($base_home . '/es/', $base_home . '/', $en_url);
        if (substr($en_url, -3) === '/es') {
            $en_url = substr($en_url, 0, -3);
        }

        if ($post_id || is_singular()) {
            $id = $post_id ?: get_the_ID();
            $manual_en = get_post_meta($id, 'alternate_url_en', true);
            if ($manual_en)
                $en_url = $manual_en;
            $manual_es = get_post_meta($id, 'alternate_url_es', true);
            if ($manual_es) {
                return ['en' => $en_url, 'es' => $manual_es];
            }
        }

        $path = str_replace($base_home, '', $en_url);
        $path = ltrim($path, '/');
        $es_url = $base_home . '/es/' . $path;

        return [
            'en' => $en_url,
            'es' => $es_url
        ];
    }

    /**
     * Swap Title
     */
    public function swap_title($title, $post_id = null)
    {
        if (!self::is_spanish() || is_admin())
            return $title;
        if (!$post_id)
            $post_id = get_the_ID();
        if (!$post_id)
            return $title;

        $es_title = get_post_meta($post_id, '_earlystart_es_title', true);
        return $es_title ?: $title;
    }

    /**
     * Swap Content
     */
    public function swap_content($content)
    {
        if (!self::is_spanish() || is_admin())
            return $content;
        $post_id = get_the_ID();
        if (!$post_id)
            return $content;
        $es_content = get_post_meta($post_id, '_earlystart_es_content', true);
        return empty($es_content) ? $content : $es_content;
    }

    /**
     * Swap Excerpt
     */
    public function swap_excerpt($excerpt)
    {
        if (!self::is_spanish() || is_admin())
            return $excerpt;
        $post_id = get_the_ID();
        if (!$post_id)
            return $excerpt;
        $es_excerpt = get_post_meta($post_id, '_earlystart_es_excerpt', true);
        return $es_excerpt ?: $excerpt;
    }

    /**
     * Rewrite Internal URLs in Content
     */
    public function rewrite_content_urls($content)
    {
        if (!self::is_spanish() || is_admin())
            return $content;
        $site_url = preg_quote(rtrim(get_option('home'), '/'), '/');
        $pattern = '/href=["\'](' . $site_url . ')(?!\/es\/)([^"\']*)["\']/i';
        $replacement = 'href="$1/es$2"';
        return preg_replace($pattern, $replacement, $content);
    }

    /**
     * Filter Nav Menu Link
     */
    public function filter_nav_menu_link($atts, $item, $args, $depth)
    {
        if (!self::is_spanish() || is_admin())
            return $atts;
        if (!empty($atts['href'])) {
            $href = $atts['href'];
            $site_url = rtrim(get_option('home'), '/');
            if (strpos($href, $site_url) === 0 && strpos($href, $site_url . '/es/') !== 0) {
                $path = substr($href, strlen($site_url));
                $atts['href'] = $site_url . '/es' . $path;
            }
        }
        return $atts;
    }

    /**
     * Filter Canonical URL
     */
    public function filter_canonical_url($canonical_url, $post)
    {
        if (!self::is_spanish())
            return $canonical_url;
        $site_url = rtrim(get_option('home'), '/');
        if (strpos($canonical_url, $site_url . '/es/') !== 0) {
            $path = substr($canonical_url, strlen($site_url));
            return $site_url . '/es' . $path;
        }
        return $canonical_url;
    }

    /**
     * Output Fallback Banner CSS
     */
    public function output_fallback_css()
    {
        if (!self::is_spanish())
            return;
        echo '<style>
        .earlystart-lang-fallback-notice { background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%); border: 1px solid #ffc107; border-left: 4px solid #ffc107; color: #856404; padding: 12px 16px; margin-bottom: 20px; border-radius: 4px; font-size: 14px; display: flex; align-items: center; gap: 8px; }
        .earlystart-lang-fallback-notice .dashicons { font-size: 18px; width: 18px; height: 18px; }
        </style>';
    }

    /**
     * Localize SEO Title
     */
    public function localize_seo_title($title)
    {
        if (!self::is_spanish())
            return $title;
        $post_id = get_the_ID();
        if (!$post_id)
            return $title;
        $es_seo_title = get_post_meta($post_id, '_earlystart_es_seo_title', true);
        if ($es_seo_title)
            return $es_seo_title;
        $es_title = get_post_meta($post_id, '_earlystart_es_title', true);
        if ($es_title)
            return $es_title . ' | Early Start';
        return $title;
    }

    /**
     * Localize Meta Description
     */
    public function localize_meta_description()
    {
        if (!self::is_spanish())
            return;
        $post_id = get_the_ID();
        if (!$post_id)
            return;
        $es_meta_desc = get_post_meta($post_id, '_earlystart_es_meta_description', true);
        if ($es_meta_desc) {
            remove_action('wp_head', 'earlystart_shared_meta_description', 2);
            echo '<meta name="description" content="' . esc_attr($es_meta_desc) . '" />' . "\n";
        }
    }

    /**
     * Filter gettext to handle dynamic translation of common UI strings
     */
    public function dynamic_translation_filter($translated, $text, $domain)
    {
        if ($domain !== 'earlystart-seo-pro' && $domain !== 'earlystart' && !self::is_spanish()) {
            return $translated;
        }

        static $mapping = null;
        if ($mapping === null) {
            $mapping = [
                'Quick Links' => 'Enlaces Rápidos',
                'Contact' => 'Contacto',
                'Connect With Us' => 'Conéctate con Nosotros',
                'Latest Blogs' => 'Últimos Blogs',
                'Privacy Policy' => 'Política de Privacidad',
                'Terms of Service' => 'Términos de Servicio',
                'Menu' => 'Menú',
                'Skip to content' => 'Saltar al contenido',
                'Schedule a Tour' => 'Agenda un Recorrido',
                'Book a Tour' => 'Reserva un Recorrido',
                'Early Learning' => 'Educación Temprana',
                'Academy' => 'Academia',
                'Locations' => 'Ubicaciones',
                'Students' => 'Estudiantes',
                'Educators' => 'Educadores',
                'Licensed' => 'Licenciado',
                'Safety First' => 'Seguridad Primero',
                'Academic Excellence' => 'Excelencia Académica',
                'Leadership' => 'Liderazgo',
                'Find a Location' => 'Buscar una Ubicación',
                'Parent Dashboard' => 'Panel de Padres',
                'Go Home' => 'Ir a Inicio',
                'Enrollment' => 'Inscripciones',
                'Careers' => 'Carreras',
                'Phone' => 'Teléfono',
                'Download' => 'Descargar',
                'View All Locations' => 'Ver Todas las Ubicaciones',
                'Search' => 'Buscar',
            ];
        }

        return $mapping[$text] ?? $translated;
    }
}

/**
 * Global helper for theme usage
 */
if (!function_exists('earlystart_get_alternates')) {
    function earlystart_get_alternates($post_id = null)
    {
        if (class_exists('earlystart_Multilingual_Manager')) {
            return earlystart_Multilingual_Manager::get_alternates($post_id);
        }
        return [];
    }
}

if (!function_exists('earlystart_get_translated_meta')) {
    function earlystart_get_translated_meta($post_id, $key, $single = true)
    {
        if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) {
            $es_val = get_post_meta($post_id, '_earlystart_es_' . $key, $single);
            if ($es_val)
                return $es_val;
        }
        return get_post_meta($post_id, $key, $single);
    }
}

if (!function_exists('earlystart_get_theme_mod')) {
    function earlystart_get_theme_mod($name, $default = false)
    {
        $val = get_theme_mod($name, $default);
        if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) {
            $es_val = get_theme_mod($name . '_es');
            if ($es_val)
                return $es_val;
        }
        return $val;
    }
}
