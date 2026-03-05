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

    $normalized_unsplash = earlystart_normalize_unsplash_url($base_url, $width, $height, $quality);
    if (!empty($normalized_unsplash)) {
        return $normalized_unsplash;
    }

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
 * Normalize Unsplash URLs and enforce a clean single query string.
 *
 * @param string   $url Original URL.
 * @param int      $w Width.
 * @param int|null $h Height.
 * @param int      $q Quality.
 * @return string
 */
function earlystart_normalize_unsplash_url($url, $w, $h = null, $q = 80)
{
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }

    $parts = wp_parse_url($url);
    if (empty($parts) || empty($parts['host']) || empty($parts['path'])) {
        return '';
    }

    $host = strtolower((string) $parts['host']);
    if ($host !== 'images.unsplash.com' && $host !== 'source.unsplash.com') {
        return '';
    }

    $w = max(1, absint($w));
    $h = is_null($h) ? null : absint($h);
    if ($h !== null && $h <= 0) {
        $h = null;
    }

    $q = absint($q);
    if ($q < 30 || $q > 100) {
        $q = 80;
    }

    $scheme = !empty($parts['scheme']) ? $parts['scheme'] : 'https';
    $base = $scheme . '://' . $parts['host'] . $parts['path'];

    $params = array(
        'auto' => 'format',
        'fit' => 'crop',
        'w' => $w,
        'q' => $q,
        'fm' => 'webp',
    );

    if (!is_null($h)) {
        $params['h'] = $h;
    }

    $normalized = add_query_arg($params, $base);

    if (!empty($parts['fragment'])) {
        $normalized .= '#' . $parts['fragment'];
    }

    return $normalized;
}

/**
 * Comprehensive responsive Unsplash image helper
 * Generates <img> tag with srcset and sizes.
 */
function earlystart_responsive_unsplash($base_url, $alt = '', $class = '', $sizes = '100vw', $lcp = false, $width = 1200, $height = null)
{
    if (empty($base_url))
        return '';

    $widths = array(320, 480, 640, 800, 1024, 1200, 1600, 1920);
    $srcset = array();

    foreach ($widths as $w) {
        $srcset[] = earlystart_get_optimized_unsplash_url($base_url, $w) . ' ' . $w . 'w';
    }

    $default_src = earlystart_get_optimized_unsplash_url($base_url, $width, $height);

    $attrs = array(
        'src="' . esc_url($default_src) . '"',
        'srcset="' . esc_attr(implode(', ', $srcset)) . '"',
        'sizes="' . esc_attr($sizes) . '"',
        'alt="' . esc_attr($alt) . '"',
        'class="' . esc_attr($class) . '"',
        'decoding="async"',
        'width="' . esc_attr($width) . '"'
    );

    if ($height) {
        $attrs[] = 'height="' . esc_attr($height) . '"';
        $attrs[] = 'style="aspect-ratio: ' . $width . '/' . $height . '; object-fit: cover;"';
    }

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
