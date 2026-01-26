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

        // Google Fonts: Plus Jakarta Sans
        wp_enqueue_style(
                'earlystart-fonts',
                'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
                array(),
                null
        );

        // Lucide Icons
        wp_enqueue_script(
                'lucide-icons',
                'https://unpkg.com/lucide@latest',
                array(),
                null,
                false // Load in Header to avoid race conditions
        );

        // Tailwind CSS (CDN for immediate visual parity with HTML)
        wp_enqueue_script(
                'tailwind-cdn',
                'https://cdn.tailwindcss.com',
                array(),
                null,
                false
        );

        // Configure Tailwind (Basic Brand Colors)
        wp_add_inline_script('tailwind-cdn', "
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['\"Plus Jakarta Sans\"', 'sans-serif'],
                        },
                        colors: {
                            rose: { 50: '#fff1f2', 100: '#ffe4e6', 500: '#f43f5e', 600: '#e11d48', 700: '#be123c' },
                            stone: { 50: '#fafaf9', 100: '#f5f5f4', 800: '#292524', 900: '#1c1917' }
                        }
                    }
                }
            }
        ");

        // Compiled Tailwind CSS.
        $css_path = earlystart_THEME_DIR . '/assets/css/main.css';
        $css_version = file_exists($css_path) ? filemtime($css_path) : earlystart_VERSION;

        // Compiled Tailwind CSS - loads synchronously
        wp_enqueue_style(
                'chroma-main',
                earlystart_THEME_URI . '/assets/css/main.css',
                array(),
                $css_version,
                'all' // Load normally to prevent FOUC
        );

        // CRITICAL ACCESSIBILITY FIXES (Injected Inline to bypass cache/build)
        $custom_css = "
                /* Darkened Brand Colors for WCAG AA Compliance (Enhanced) */
                .text-chroma-red { color: #964030 !important; }
                .bg-chroma-red { background-color: #964030 !important; }
                .text-chroma-orange { color: #A8551E !important; }
                .bg-chroma-orange { background-color: #A8551E !important; }
                .text-chroma-green { color: #4D5C54 !important; }
                .bg-chroma-green { background-color: #4D5C54 !important; }
                .text-chroma-yellow { color: #8C6B2F !important; }
                .bg-chroma-yellow { background-color: #8C6B2F !important; }
                
                /* Footer Social Links - Touch Target Fix (48px) */
                footer .flex.gap-3 a {
                        width: 48px !important;
                        height: 48px !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                }
                footer .flex.gap-3 a i {
                        font-size: 1.25rem !important;
                }
                
                /* Footer Navigation Links - Touch Target Fix */
                footer nav a {
                        display: inline-block !important;
                        min-height: 48px !important;
                        min-width: 48px !important;
                        padding: 12px 16px !important;
                        line-height: 1.5 !important;
                        display: flex !important;
                        align-items: center !important;
                }
                
                /* Review Carousel Dots - Touch Target Fix (48px) */
                [data-reviews-dots] button {
                        min-width: 48px !important;
                        min-height: 48px !important;
                        padding: 12px !important;
                }

                /* Global Button Touch Targets */
                a[class*='px-8'][class*='py-4'], 
                button[class*='px-8'][class*='py-4'] {
                        min-height: 48px !important;
                        display: inline-flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                }

                /* Form Inputs Touch Targets */
                input[type='text'],
                input[type='email'],
                input[type='tel'],
                input[type='number'],
                select,
                textarea {
                        min-height: 48px !important;
                        font-size: 16px !important; /* Prevent iOS zoom */
                }
                
                /* Form Labels - Ensure visibility if hidden */
                .chroma-tour-form label {
                        display: block !important;
                        color: #263238 !important; /* Brand Ink */
                        opacity: 1 !important;
                        margin-bottom: 0.5rem !important;
                }

                        /* Force CTA Button Visibility */
                        header .container > a[href*='contact'] {
                                display: flex !important;
                        }
                }

                /* Accessibility: Increase contrast for muted text */
                .text-brand-ink\/60 { color: rgba(38, 50, 56, 0.9) !important; }
                .text-brand-ink\/70 { color: rgba(38, 50, 56, 0.95) !important; }

                /* Animations (Moved from templates for AMP compatibility) */
                .fade-in-up {
                    animation: fadeInUp 0.8s ease forwards;
                    opacity: 0;
                    transform: translateY(20px);
                }
                .delay-100 { animation-delay: 0.1s; }
                .delay-200 { animation-delay: 0.2s; }
                .delay-300 { animation-delay: 0.3s; }
                @keyframes fadeInUp {
                    to { opacity: 1; transform: translateY(0); }
                }

                /* Custom Scrollbar for Job Board */
                .custom-scrollbar::-webkit-scrollbar { width: 6px; }
                .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
                .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #334155; }
        ";
        wp_add_inline_style('chroma-main', $custom_css);

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
                        'viewCampus' => __('View campus', 'chroma-early-start'),
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
        }

        if ('dns-prefetch' === $relation_type) {

                if (is_front_page() || is_singular('program') || is_post_type_archive('program')) {
                        $urls[] = '//cdn.jsdelivr.net';
                }

                if (earlystart_should_load_maps()) {
                        $urls[] = '//unpkg.com';
                }
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
        // Defer Font Awesome AND Main CSS (Critical CSS inlined in header)
        if (in_array($handle, array('chroma-font-awesome', 'chroma-main'))) {
                // Add data-no-optimize to prevent LiteSpeed from combining/blocking this file
                $html = str_replace('<link', '<link data-no-optimize="1"', $html);

                // If media is 'all', swap to 'print' and add onload
                $html = str_replace("media='all'", "media='print' onload=\"this.media='all'\"", $html);
                // If media is already 'print' (rare but possible), ensure onload is present
                $html = str_replace("media='print'", "media='print' onload=\"this.media='all'\"", $html);

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




