<?php
/**
 * Analytics and Tracking Configuration (GTM)
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output Google Tag Manager Head Script
 */
function earlystart_gtm_head_script()
{
    $gtm_id = get_option('earlystart_gtm_id', 'GTM-XXXXXXX'); // Placeholder
    if (empty($gtm_id) || $gtm_id === 'GTM-XXXXXXX') {
        echo "<!-- GTM ID not configured. Set value in options. -->\n";
        return;
    }
    ?>
    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || []; w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            }); var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : ''; j.async = true; j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl; f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '<?php echo esc_attr($gtm_id); ?>');</script>
    <!-- End Google Tag Manager -->
    <?php
}
add_action('wp_head', 'earlystart_gtm_head_script', 1);

/**
 * Output Google Tag Manager Body Script
 */
function earlystart_gtm_body_script()
{
    $gtm_id = get_option('earlystart_gtm_id', 'GTM-XXXXXXX'); // Placeholder
    if (empty($gtm_id) || $gtm_id === 'GTM-XXXXXXX') {
        return;
    }
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($gtm_id); ?>" height="0"
            width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
}
add_action('wp_body_open', 'earlystart_gtm_body_script', 1);
