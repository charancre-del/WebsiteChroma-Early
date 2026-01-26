<?php
/**
 * Enqueue scripts and styles.
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
        exit; // Exit if accessed directly.
}

/**
 * Determine whether map assets should be enqueued.
 */
function earlystart_should_load_maps()
{
        $should_load_maps = is_post_type_archive('location') || is_singular('location') || is_page('locations');

        if (is_front_page() && function_exists('earlystart_home_locations_preview')) {
                $locations_preview = earlystart_home_locations_preview();
                $should_load_maps = $should_load_maps || (!empty($locations_preview['map_points']));
        }

        return $should_load_maps;
}

/**
 * Enqueue theme styles and scripts
 */
function earlystart_enqueue_assets()
{
        $script_dependencies = array('jquery');

        // Font Awesome (Subset) - Optional fallback
        wp_enqueue_style(
                'chroma-font-awesome',
                earlystart_THEME_URI . '/assets/css/font-awesome-subset.css',
                array(),
                '6.4.0',
                'all'
        );

        // Google Fonts: Plus Jakarta Sans & Playfair Display
        wp_enqueue_style(
                'earlystart-fonts',
                'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Outfit:wght@400;700&display=swap',
                array(),
                null
        );
        // Ensure font-display: swap is added
        wp_style_add_data('earlystart-fonts', 'media', 'all');

        // Lucide Icons - Pinned version to avoid redirects and duplication
        wp_enqueue_script(
                'lucide-icons',
                'https://unpkg.com/lucide@0.320.0/dist/umd/lucide.min.js',
                array(),
                '0.320.0',
                true // Load in Footer
        );
        wp_script_add_data('lucide-icons', 'defer', true);

        // Dequeue any other potential lucide handles from plugins
        wp_dequeue_script('lucide');
        wp_dequeue_script('lucide-js');
        wp_deregister_script('lucide');
        wp_deregister_script('lucide-js');

        // ... rest of scripts ...

        // Compiled Tailwind CSS.
        $css_path = earlystart_THEME_DIR . '/assets/css/main.css';
        $css_version = file_exists($css_path) ? filemtime($css_path) : earlystart_VERSION;

        // Compiled Tailwind CSS - loads synchronously
        wp_enqueue_style(
                'chroma-main',
                earlystart_THEME_URI . '/assets/css/main.css',
                array(),
                $css_version,
                'all'
        );

        // chroma-utils is now INLINED in header.php for performance

        // Main JavaScript.
        $js_path = earlystart_THEME_DIR . '/assets/js/main.js';
        $js_version = file_exists($js_path) ? filemtime($js_path) : earlystart_VERSION;

        wp_enqueue_script(
                'chroma-main-js',
                earlystart_THEME_URI . '/assets/js/main.js',
                $script_dependencies,
                $js_version,
                true
        );

        // Defer re-enabled for FCP optimization
        wp_script_add_data('chroma-main-js', 'defer', true);

        // Map Facade (Lazy Load Leaflet).
        $should_load_maps = earlystart_should_load_maps();

        if ($should_load_maps) {
                wp_enqueue_script(
                        'chroma-map-facade',
                        earlystart_THEME_URI . '/assets/js/map-facade.js',
                        array('chroma-main-js'), // Depend on main to ensure chromaData is available
                        $js_version,
                        true
                );
                wp_script_add_data('chroma-map-facade', 'defer', true);
        }

        // Localize script for AJAX and dynamic data.
        wp_localize_script(
                'chroma-main-js',
                'chromaData',
                array(
                        'ajaxUrl' => admin_url('admin-ajax.php'),
                        'nonce' => wp_create_nonce('earlystart_nonce'),
                        'themeUrl' => earlystart_THEME_URI,
                        'homeUrl' => home_url(),
                        'viewCampus' => __('View campus', 'earlystart-early-learning'),
                )
        );
}
add_action('wp_enqueue_scripts', 'earlystart_enqueue_assets');



/**
 * Add resource hints for external assets to improve initial page performance.
 */
function earlystart_resource_hints($urls, $relation_type)
{
        if ('preconnect' === $relation_type) {

                if (is_front_page() || is_singular('program') || is_post_type_archive('program')) {
                        $urls[] = 'https://cdn.jsdelivr.net';
                }

                if (earlystart_should_load_maps()) {
                        $urls[] = 'https://unpkg.com';
                }

                // Preconnect to external origins identified in audit
                $urls[] = 'https://widgets.leadconnectorhq.com';
                $urls[] = 'https://services.leadconnectorhq.com';
                $urls[] = 'https://images.leadconnectorhq.com';
                $urls[] = 'https://stcdn.leadconnectorhq.com';
                $urls[] = 'https://fonts.bunny.net';
                $urls[] = 'https://images.unsplash.com';
                $urls[] = 'https://unpkg.com';
        }

        if ('dns-prefetch' === $relation_type) {

                if (is_front_page() || is_singular('program') || is_post_type_archive('program')) {
                        $urls[] = '//cdn.jsdelivr.net';
                }

                $urls[] = '//unpkg.com';
                $urls[] = '//images.unsplash.com';
                $urls[] = '//widgets.leadconnectorhq.com';
                $urls[] = '//services.leadconnectorhq.com';
                $urls[] = '//images.leadconnectorhq.com';
                $urls[] = '//stcdn.leadconnectorhq.com';
                $urls[] = '//fonts.bunny.net';
        }

        return array_unique($urls, SORT_REGULAR);
}
add_filter('wp_resource_hints', 'earlystart_resource_hints', 10, 2);

/**
 * Enqueue admin assets
 */
function earlystart_enqueue_admin_assets($hook)
{
        // Only load on post edit screens
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
                return;
        }

        // Font Awesome for icon previews in admin (using local version)
        $fa_path = earlystart_THEME_DIR . '/assets/css/font-awesome.css';
        $fa_version = file_exists($fa_path) ? filemtime($fa_path) : '6.4.0';

        wp_enqueue_style(
                'font-awesome-admin',
                earlystart_THEME_URI . '/assets/css/font-awesome.css',
                array(),
                $fa_version // Use same version as frontend for consistency
        );

        // Media uploader
        wp_enqueue_media();

        // Custom admin script for media uploader
        wp_enqueue_script(
                'chroma-admin',
                earlystart_THEME_URI . '/assets/js/admin.js',
                array('jquery'),
                earlystart_VERSION,
                true
        );
}
add_action('admin_enqueue_scripts', 'earlystart_enqueue_admin_assets');

/**
 * Async load CSS for fonts only (not main CSS to prevent FOUC)
 */
function earlystart_async_styles($html, $handle, $href, $media)
{
        // Defer Font Awesome, Google Fonts AND Main CSS (Critical CSS inlined in header)
        if (in_array($handle, array('chroma-font-awesome', 'chroma-main', 'earlystart-fonts'))) {
                // Add data-no-optimize to prevent LiteSpeed from combining/blocking this file
                $html = str_replace('<link', '<link data-no-optimize="1"', $html);

                // If media is 'all', swap to 'print' and add onload
                $html = str_replace("media='all'", "media='print' onload=\"this.media='all'\"", $html);
                // If media is already 'print' (rare but possible), ensure onload is present
                if (strpos($html, "media='print'") !== false && strpos($html, "onload=") === false) {
                        $html = str_replace("media='print'", "media='print' onload=\"this.media='all'\"", $html);
                }

                // Add fallback for no-js
                $html .= "<noscript><link rel='stylesheet' href='{$href}' media='all'></noscript>";
        }
        return $html;
}
add_filter('style_loader_tag', 'earlystart_async_styles', 10, 4);

/**
 * Move jQuery to Footer for Performance (LCP)
 * 
 * Deregisters core jQuery and re-registers it in the footer.
 * Considers admin bar and login status.
 */
function earlystart_move_jquery_to_footer()
{
        // Do not move if admin bar is showing (prevents breakage)
        if (is_admin() || is_user_logged_in()) {
                return;
        }

        wp_deregister_script('jquery');
        wp_deregister_script('jquery-core');
        wp_deregister_script('jquery-migrate');

        // Re-register jQuery in footer
        // Uses includes_url() to maintain compatibility with WP versioning
        wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), false, null, true);
        wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'earlystart_move_jquery_to_footer', 1);

/**
 * Dequeue Dashicons for non-logged in users to improve performance
 */
function earlystart_dequeue_dashicons()
{
        if (!is_user_logged_in()) {
                wp_dequeue_style('dashicons');
                wp_deregister_style('dashicons');
        }
}
/**
 * Preload Main CSS to minimize FOUC (Flash of Unstyled Content)
 */
function earlystart_preload_main_css()
{
        $css_path = earlystart_THEME_DIR . '/assets/css/main.css';
        $css_version = file_exists($css_path) ? filemtime($css_path) : earlystart_VERSION;
        $css_url = earlystart_THEME_URI . '/assets/css/main.css?ver=' . $css_version;

        echo '<link rel="preload" href="' . esc_url($css_url) . '" as="style">' . "\n";
}
add_action('wp_head', 'earlystart_preload_main_css', 1);

add_action('wp_enqueue_scripts', 'earlystart_dequeue_dashicons');


/**
 * Dequeue CDN styles (specifically Font Awesome) to force local loading.
 * Runs at priority 100 to ensure it runs after plugins.
 */
function earlystart_dequeue_cdn_styles()
{
        global $wp_styles;
        if (empty($wp_styles->queue)) {
                return;
        }

        foreach ($wp_styles->queue as $handle) {
                if (!isset($wp_styles->registered[$handle])) {
                        continue;
                }

                $src = $wp_styles->registered[$handle]->src;

                // Check if it's Font Awesome and coming from a CDN
                if (
                        (strpos($handle, 'font-awesome') !== false || strpos($handle, 'fontawesome') !== false || strpos($handle, 'fa-') !== false) &&
                        (strpos($src, 'cdnjs') !== false || strpos($src, 'cloudflare') !== false || strpos($src, 'jsdelivr') !== false)
                ) {
                        wp_dequeue_style($handle);
                        wp_deregister_style($handle);
                }
        }
}
add_action('wp_enqueue_scripts', 'earlystart_dequeue_cdn_styles', 100);

/**
 * Dequeue unused block styles to reduce payload.
 */
function earlystart_dequeue_block_styles()
{
        if (!is_admin()) {
                wp_dequeue_style('wp-block-library');
                wp_dequeue_style('wp-block-library-theme');
                wp_dequeue_style('wc-blocks-style'); // WooCommerce
        }
}
add_action('wp_enqueue_scripts', 'earlystart_dequeue_block_styles', 100);




