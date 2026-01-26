<?php
/**
 * Plugin Name: Early Start Tour Form
 * Description: Native tour request form with dynamic LeadConnector options and direct API submission for Early Start.
 * Version: 2.0.1
 * Author: Early Start Development Team
 * Text Domain: earlystart-tour-form
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fetch and Parse Dynamic Options from GHL
 */
function earlystart_tour_get_dynamic_options()
{
    $transient_key = 'earlystart_tour_ghl_options';
    $cached = get_transient($transient_key);

    if ($cached !== false && is_array($cached)) {
        return $cached;
    }

    $form_id = get_option('earlystart_tour_form_id', '848tl2LjoZVsUIhhNOxd');
    $url = 'https://api.leadconnectorhq.com/widget/form/' . $form_id;
    $response = wp_remote_get($url, array('timeout' => 15));

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return array();
    }

    $html = wp_remote_retrieve_body($response);
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $options = array('ages' => array(), 'locations' => array());

    $age_nodes = $xpath->query('//ul[contains(@id, "tjcMDuffDYLzvezpnxly")]//li//span[@class="multiselect__option" or contains(@class, "multiselect__option--highlight")]');
    if ($age_nodes->length > 0) {
        foreach ($age_nodes as $node) {
            $text = trim($node->textContent);
            if ($text && strpos($text, 'No elements found') === false && strpos($text, 'List is empty') === false) {
                $options['ages'][] = $text;
            }
        }
    } else {
        $options['ages'] = array('Infant', 'Toddler', 'Preschool', 'Pre-K', 'AfterSchool/Summer Camp');
    }

    $location_nodes = $xpath->query('//ul[contains(@id, "DKcjpcd5izdAklwt1Bby")]//li//span[@class="multiselect__option" or contains(@class, "multiselect__option--highlight")]');
    if ($location_nodes->length > 0) {
        foreach ($location_nodes as $node) {
            $text = trim($node->textContent);
            if ($text && strpos($text, 'No elements found') === false && strpos($text, 'List is empty') === false) {
                $options['locations'][] = $text;
            }
        }
    } else {
        $options['locations'] = array('1205 Upper Burris Rd, Canton, GA 30114, USA');
    }

    set_transient($transient_key, $options, 12 * HOUR_IN_SECONDS);
    return $options;
}

/**
 * Tour Form Shortcode
 */
function earlystart_tour_form_shortcode()
{
    $form_id = get_option('earlystart_tour_form_id', '848tl2LjoZVsUIhhNOxd');
    $form_height = get_option('earlystart_tour_form_height', 1125);
    $form_name = get_option('earlystart_tour_form_name', 'PARENT INFORMATION - Early Start Early Learning');
    $lazy_load = get_option('earlystart_tour_lazy_load', true);
    $lazy_delay = get_option('earlystart_tour_lazy_delay', 2000);

    $form_url = 'https://api.leadconnectorhq.com/widget/form/' . esc_attr($form_id);
    $loading_attr = $lazy_load ? 'lazy' : 'eager';

    ob_start();
    ?>
    <div class="earlystart-tour-form-wrapper" data-lazy="<?php echo $lazy_load ? 'true' : 'false'; ?>"
        data-delay="<?php echo esc_attr($lazy_delay); ?>">
        <div class="earlystart-ghl-iframe-container" style="min-height: <?php echo esc_attr($form_height); ?>px;">
            <iframe src="<?php echo esc_url($form_url); ?>"
                style="width:100%;height:100%;border:none;border-radius:3px;min-height:<?php echo esc_attr($form_height); ?>px;"
                id="inline-<?php echo esc_attr($form_id); ?>" loading="<?php echo esc_attr($loading_attr); ?>"
                data-layout="{'id':'INLINE'}" data-trigger-type="alwaysShow" data-trigger-value=""
                data-activation-type="alwaysActivated" data-activation-value="" data-deactivation-type="neverDeactivate"
                data-deactivation-value="" data-form-name="<?php echo esc_attr($form_name); ?>"
                data-height="<?php echo esc_attr($form_height); ?>"
                data-layout-iframe-id="inline-<?php echo esc_attr($form_id); ?>"
                data-form-id="<?php echo esc_attr($form_id); ?>" title="<?php echo esc_attr($form_name); ?>">
            </iframe>
        </div>
    </div>

    <style>
        .earlystart-tour-form-wrapper {
            width: 100%;
            margin: 0 auto;
        }

        .earlystart-ghl-iframe-container {
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
        }

        .earlystart-ghl-iframe-container iframe {
            display: block;
        }
    </style>

    <?php if ($lazy_load): ?>
        <script>
            (function () {
                var loaded = false;
                var container = document.querySelector('.earlystart-tour-form-wrapper');
                var delay = <?php echo intval($lazy_delay); ?>;
                function loadGHLScript() {
                    if (loaded) return;
                    loaded = true;
                    var script = document.createElement('script');
                    script.src = 'https://link.msgsndr.com/js/form_embed.js';
                    script.async = true;
                    document.body.appendChild(script);
                }
                var timer = delay > 0 ? setTimeout(loadGHLScript, delay) : null;
                if ('IntersectionObserver' in window && container) {
                    var observer = new IntersectionObserver(function (entries) {
                        if (entries[0].isIntersecting) {
                            if (timer) clearTimeout(timer);
                            loadGHLScript();
                            observer.disconnect();
                        }
                    }, { rootMargin: '200px' });
                    observer.observe(container);
                }
            })();
        </script>
    <?php else: ?>
        <script src="https://link.msgsndr.com/js/form_embed.js"></script>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}
add_shortcode('earlystart_tour_form', 'earlystart_tour_form_shortcode');

/**
 * Handle Form Submission via API
 */
function earlystart_handle_tour_submission()
{
    if (!isset($_POST['earlystart_tour_submit']) || !wp_verify_nonce(wp_unslash($_POST['earlystart_tour_nonce'] ?? ''), 'earlystart_tour_submit')) {
        return;
    }

    $fields = array('first_name', 'last_name', 'phone', 'email', 'KXEHzTOMGosdJUu1Eqri', 'dTabDQmMvBfwpMCUaPpU', '9dpin9NpFnCaEY9hTL51', 'tjcMDuffDYLzvezpnxly', 'DKcjpcd5izdAklwt1Bby');
    $payload = array();
    $form_id = get_option('earlystart_tour_form_id', '848tl2LjoZVsUIhhNOxd');
    $payload['formId'] = $form_id;
    $payload['locationId'] = 'euN4JvLvKNYTYh4Xyh3p';
    $payload['companyId'] = 'aXTQYHsTlryLiFQng6a9';
    $payload['traceId'] = '48e8401e-b945-440e-889d-210e75758ee7';
    $payload['country'] = 'US';
    $payload['inputType'] = 'form_builder';

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field(wp_unslash($_POST[$field]));
            if ($field === 'phone' && !empty($value)) {
                $digits = preg_replace('/\D/', '', $value);
                if (strlen($digits) === 10)
                    $value = '+1' . $digits;
                elseif (strlen($digits) === 11 && strpos($digits, '1') === 0)
                    $value = '+' . $digits;
            }
            $payload[$field] = $value;
        }
    }

    $response = wp_remote_post('https://backend.leadconnectorhq.com/forms/submit', array(
        'body' => wp_json_encode($payload),
        'headers' => array(
            'Content-Type' => 'application/json',
            'Referer' => 'https://api.leadconnectorhq.com/widget/form/' . $form_id,
            'Origin' => 'https://api.leadconnectorhq.com',
            'User-Agent' => 'Mozilla/5.0'
        ),
        'timeout' => 20
    ));

    $redirect_target = !empty($_POST['earlystart_tour_redirect']) ? esc_url_raw(wp_unslash($_POST['earlystart_tour_redirect'])) : (wp_get_referer() ?: home_url('/contact/'));

    if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) < 400) {
        wp_safe_redirect(add_query_arg('tour_sent', '1', $redirect_target));
    } else {
        wp_die('Form Submission Failed. Please try again.');
    }
    exit;
}
add_action('template_redirect', 'earlystart_handle_tour_submission');

/**
 * Register Settings
 */
function earlystart_tour_register_settings()
{
    register_setting('earlystart_tour_settings', 'earlystart_tour_form_id', array('type' => 'string', 'default' => '848tl2LjoZVsUIhhNOxd', 'sanitize_callback' => 'sanitize_text_field'));
    register_setting('earlystart_tour_settings', 'earlystart_tour_form_height', array('type' => 'integer', 'default' => 1125, 'sanitize_callback' => 'absint'));
    register_setting('earlystart_tour_settings', 'earlystart_tour_form_name', array('type' => 'string', 'default' => 'PARENT INFORMATION - Early Start Early Learning', 'sanitize_callback' => 'sanitize_text_field'));
    register_setting('earlystart_tour_settings', 'earlystart_tour_lazy_load', array('type' => 'boolean', 'default' => true, 'sanitize_callback' => 'rest_sanitize_boolean'));
    register_setting('earlystart_tour_settings', 'earlystart_tour_lazy_delay', array('type' => 'integer', 'default' => 2000, 'sanitize_callback' => 'absint'));
}
add_action('admin_init', 'earlystart_tour_register_settings');

/**
 * Admin Menu
 */
function earlystart_tour_admin_menu()
{
    add_options_page('Tour Form Settings', 'Tour Form', 'manage_options', 'earlystart-tour-form', 'earlystart_tour_settings_page_html');
}
add_action('admin_menu', 'earlystart_tour_admin_menu');

/**
 * Settings Page HTML
 */
function earlystart_tour_settings_page_html()
{
    if (!current_user_can('manage_options'))
        return;
    $form_id = get_option('earlystart_tour_form_id', '848tl2LjoZVsUIhhNOxd');
    $form_height = get_option('earlystart_tour_form_height', 1125);
    $form_name = get_option('earlystart_tour_form_name', 'PARENT INFORMATION - Early Start Early Learning');
    $lazy_load = get_option('earlystart_tour_lazy_load', true);
    $lazy_delay = get_option('earlystart_tour_lazy_delay', 2000);
    ?>
    <div class="wrap">
        <h1>Tour Form Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('earlystart_tour_settings'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">GHL Form ID</th>
                    <td><input type="text" name="earlystart_tour_form_id" value="<?php echo esc_attr($form_id); ?>"
                            class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Form Height (px)</th>
                    <td><input type="number" name="earlystart_tour_form_height"
                            value="<?php echo esc_attr($form_height); ?>" class="small-text"></td>
                </tr>
                <tr>
                    <th scope="row">Form Name</th>
                    <td><input type="text" name="earlystart_tour_form_name" value="<?php echo esc_attr($form_name); ?>"
                            class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">Lazy Loading</th>
                    <td><input type="checkbox" name="earlystart_tour_lazy_load" value="1" <?php checked($lazy_load, true); ?>> Enable</td>
                </tr>
                <tr>
                    <th scope="row">Lazy Load Delay (ms)</th>
                    <td><input type="number" name="earlystart_tour_lazy_delay" value="<?php echo esc_attr($lazy_delay); ?>"
                            class="small-text"></td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
        <hr>
        <h2>Usage</h2>
        <p>Use this shortcode: <code>[earlystart_tour_form]</code></p>
    </div>
    <?php
}
