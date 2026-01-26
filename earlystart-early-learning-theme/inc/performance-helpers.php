<?php
/**
 * Performance Helpers
 * Highly optimized functions for image delivery and asset management.
 * 
 * @package EarlyStart_Early_Start
 */

/**
 * Generate a responsive Unsplash URL with optimal compression and formatting.
 * 
 * @param string $base_url The original Unsplash URL.
 * @param int    $width    Desired width.
 * @param int    $height   Optional desired height.
 * @param int    $quality  Compression quality (default 80).
 * @return string Optimized URL.
 */
function earlystart_get_optimized_unsplash_url($base_url, $width, $height = null, $quality = 80)
{
    if (empty($base_url))
        return '';

    // Clean up existing parameters
    $url_parts = explode('?', $base_url);
    $clean_url = $url_parts[0];

    $params = array(
        'auto' => 'format',
        'fit' => 'crop',
        'q' => $quality,
        'w' => $width,
        'fm' => 'webp' // Force WebP for modern browsers
    );

    if ($height) {
        $params['h'] = $height;
    }

    return add_query_arg($params, $clean_url);
}

/**
 * Comprehensive responsive Unsplash image helper
 * Generates <img> tag with srcset and sizes.
 */
function earlystart_responsive_unsplash($base_url, $alt = '', $class = '', $sizes = '100vw', $lcp = false)
{
    if (empty($base_url))
        return '';

    $widths = array(320, 480, 640, 800, 1024, 1200, 1600, 1920);
    $srcset = array();

    foreach ($widths as $w) {
        $srcset[] = earlystart_get_optimized_unsplash_url($base_url, $w) . ' ' . $w . 'w';
    }

    $default_src = earlystart_get_optimized_unsplash_url($base_url, 1200);

    $attrs = array(
        'src="' . esc_url($default_src) . '"',
        'srcset="' . esc_attr(implode(', ', $srcset)) . '"',
        'sizes="' . esc_attr($sizes) . '"',
        'alt="' . esc_attr($alt) . '"',
        'class="' . esc_attr($class) . '"',
        'decoding="async"'
    );

    if ($lcp) {
        $attrs[] = 'fetchpriority="high"';
        $attrs[] = 'class="' . esc_attr($class . ' no-lazy') . '"';
        $attrs[] = 'data-no-lazy="1"';
    } else {
        $attrs[] = 'loading="lazy"';
    }

    return '<img ' . implode(' ', $attrs) . '>';
}

/**
 * Inject critical localized data early
 */
function earlystart_inject_critical_js_globals()
{
    ?>
    <script>
        /** Zero-Reflow Performance Globals */
        window.earlyStartPerf = {
            lcpStartTime: performance.now(),
            isLcpLoaded: false
        };
    </script>
    <?php
}
add_action('wp_head', 'earlystart_inject_critical_js_globals', 0);
