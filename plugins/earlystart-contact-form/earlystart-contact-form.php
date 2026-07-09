<?php
/**
 * Plugin Name: Early Start Contact Form
 * Description: General contact form with GUI editor, Webhooks, and Lead Log integration for Early Start.
 * Version: 1.0.0
 * Author: Early Start Development Team
 * Text Domain: earlystart-contact-form
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default Fields Configuration
 */
function earlystart_contact_default_fields()
{
    return array(
        array('id' => 'first_name', 'label' => 'First Name', 'type' => 'text', 'required' => true, 'width' => 'half', 'placeholder' => 'Jane'),
        array('id' => 'last_name', 'label' => 'Last Name', 'type' => 'text', 'required' => true, 'width' => 'half', 'placeholder' => 'Doe'),
        array('id' => 'email', 'label' => 'Email Address', 'type' => 'email', 'required' => true, 'width' => 'half', 'placeholder' => 'you@example.com'),
        array('id' => 'phone', 'label' => 'Phone Number', 'type' => 'tel', 'required' => true, 'width' => 'half', 'placeholder' => '(404) 905-6775'),
        array('id' => 'preferred_campus', 'label' => 'Preferred Clinic', 'type' => 'select_location', 'required' => false, 'width' => 'half', 'placeholder' => 'Select a clinic...'),
        array('id' => 'topic', 'label' => 'Topic', 'type' => 'select', 'required' => false, 'width' => 'full', 'options' => 'Intake Consultation, Services Inquiry, Careers / HR, Clinical Referral, Other Inquiry', 'placeholder' => 'Select a topic...'),
        array('id' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => false, 'width' => 'full', 'placeholder' => 'Tell us about your needs...')
    );
}

function earlystart_contact_default_form_id()
{
    return 'M3WZTpTW5KHrkzf5XfYG';
}

function earlystart_contact_previous_form_ids()
{
    return array(
        'ibinKhrBmF0n4S5tFcz6',
    );
}

function earlystart_contact_maybe_migrate_form_options()
{
    $current_form_id = trim((string) get_option('earlystart_contact_form_id', ''));

    if ($current_form_id === '' || in_array($current_form_id, earlystart_contact_previous_form_ids(), true)) {
        update_option('earlystart_contact_form_id', earlystart_contact_default_form_id());

        $current_height = (int) get_option('earlystart_contact_form_height', 0);
        if ($current_height <= 1200) {
            update_option('earlystart_contact_form_height', 1600);
        }
    }

    $current_lazy = get_option('earlystart_contact_lazy_load', null);
    if ($current_lazy === null || $current_lazy === '') {
        update_option('earlystart_contact_lazy_load', false);
    }
}
add_action('init', 'earlystart_contact_maybe_migrate_form_options', 5);

/**
 * Register Settings
 */
function earlystart_contact_register_settings()
{
    register_setting('earlystart_contact_options', 'earlystart_contact_fields', array('type' => 'string', 'sanitize_callback' => 'earlystart_contact_sanitize_json', 'default' => wp_json_encode(earlystart_contact_default_fields())));
    register_setting('earlystart_contact_options', 'earlystart_contact_webhook_url', array('type' => 'string', 'sanitize_callback' => 'earlystart_contact_sanitize_webhook_url', 'default' => ''));
    register_setting('earlystart_contact_options', 'earlystart_contact_email_recipient', array('type' => 'string', 'sanitize_callback' => 'sanitize_email', 'default' => get_option('admin_email')));
    register_setting('earlystart_contact_options', 'earlystart_contact_form_id', array('type' => 'string', 'default' => earlystart_contact_default_form_id(), 'sanitize_callback' => 'sanitize_text_field'));
    register_setting('earlystart_contact_options', 'earlystart_contact_form_height', array('type' => 'integer', 'default' => 1600, 'sanitize_callback' => 'absint'));
    register_setting('earlystart_contact_options', 'earlystart_contact_form_name', array('type' => 'string', 'default' => 'Chroma Early Start - A2P Inquiry Form', 'sanitize_callback' => 'sanitize_text_field'));
    register_setting('earlystart_contact_options', 'earlystart_contact_sms_disclosure', array('type' => 'string', 'default' => earlystart_contact_default_sms_disclosure(), 'sanitize_callback' => 'wp_kses_post'));
    register_setting('earlystart_contact_options', 'earlystart_contact_lazy_load', array('type' => 'boolean', 'default' => false, 'sanitize_callback' => 'rest_sanitize_boolean'));
    register_setting('earlystart_contact_options', 'earlystart_contact_lazy_delay', array('type' => 'integer', 'default' => 2000, 'sanitize_callback' => 'absint'));
}
add_action('admin_init', 'earlystart_contact_register_settings');

function earlystart_contact_default_sms_disclosure()
{
    return 'SMS opt-in is optional and is not required to submit this form or receive services. By checking the optional SMS consent box in the form, you agree to receive intake follow-up, appointment reminders, care coordination, service updates, and other inquiry-related text messages from Chroma Early Start. Message frequency varies. Message and data rates may apply. Reply STOP to opt out and HELP for help. See our Privacy Policy and Terms of Use.';
}

function earlystart_contact_sanitize_webhook_url($input)
{
    $input = wp_unslash($input);
    if (is_array($input)) {
        return '';
    }

    $url = trim(esc_url_raw((string) $input, array('http', 'https')));
    if ($url === '' || !wp_http_validate_url($url)) {
        return '';
    }

    return $url;
}

function earlystart_contact_sanitize_json($input)
{
    $decoded = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        add_settings_error('earlystart_contact_fields', 'invalid_json', 'Invalid JSON format.');
        return get_option('earlystart_contact_fields');
    }
    return $input;
}

function earlystart_contact_tracking_keys()
{
    return array(
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'utm_id',
        'gclid',
        'fbclid',
        'msclkid',
        'ttclid',
        'li_fat_id',
        'gbraid',
        'wbraid',
    );
}

function earlystart_contact_build_tracked_form_url($form_id)
{
    $form_url = 'https://api.leadconnectorhq.com/widget/form/' . rawurlencode((string) $form_id);
    $tracking = array('source' => 'website_contact_form');

    if (!empty($_SERVER['REQUEST_URI'])) {
        $request_uri = esc_url_raw(wp_unslash($_SERVER['REQUEST_URI']));
        if ($request_uri) {
            $tracking['page_url'] = home_url($request_uri);
        }
    }

    foreach (earlystart_contact_tracking_keys() as $key) {
        if (!isset($_GET[$key])) {
            continue;
        }

        $value = sanitize_text_field(wp_unslash($_GET[$key]));
        if ($value !== '') {
            $tracking[$key] = $value;
        }
    }

    if (!empty($_SERVER['HTTP_REFERER'])) {
        $referrer = esc_url_raw(wp_unslash($_SERVER['HTTP_REFERER']));
        if ($referrer) {
            $tracking['referrer'] = $referrer;
        }
    }

    return add_query_arg($tracking, $form_url);
}

/**
 * Admin Menu
 */
function earlystart_contact_admin_menu()
{
    add_options_page('Contact Form Settings', 'Contact Form', 'manage_options', 'earlystart-contact-form', 'earlystart_contact_settings_page_html');
}
add_action('admin_menu', 'earlystart_contact_admin_menu');

/**
 * Settings Page HTML (Simplified for Porting)
 */
function earlystart_contact_settings_page_html()
{
    if (!current_user_can('manage_options'))
        return;
    ?>
    <div class="wrap">
        <h1>Early Start Contact Form Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('earlystart_contact_options');
            $email_recipient = get_option('earlystart_contact_email_recipient', get_option('admin_email'));
            $form_id = get_option('earlystart_contact_form_id', earlystart_contact_default_form_id());
            $form_height = get_option('earlystart_contact_form_height', 1600);
            $form_name = get_option('earlystart_contact_form_name', 'Chroma Early Start - A2P Inquiry Form');
            $sms_disclosure = get_option('earlystart_contact_sms_disclosure', earlystart_contact_default_sms_disclosure());
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Email Recipient</th>
                    <td><input type="email" name="earlystart_contact_email_recipient"
                            value="<?php echo esc_attr($email_recipient); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">GHL Inquiry Form ID</th>
                    <td>
                        <input type="text" name="earlystart_contact_form_id"
                            value="<?php echo esc_attr($form_id); ?>" class="regular-text" />
                        <p class="description">Use the new A2P-compliant GHL Inquiry form ID after it is created in HighLevel.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">GHL Form Name</th>
                    <td><input type="text" name="earlystart_contact_form_name"
                            value="<?php echo esc_attr($form_name); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">GHL Form Height</th>
                    <td><input type="number" name="earlystart_contact_form_height"
                            value="<?php echo esc_attr($form_height); ?>" class="small-text" min="300" step="1" /> px</td>
                </tr>
                <tr>
                    <th scope="row">SMS Compliance Disclosure</th>
                    <td>
                        <textarea name="earlystart_contact_sms_disclosure" rows="5" class="large-text"><?php echo esc_textarea($sms_disclosure); ?></textarea>
                        <p class="description">This text appears above the embedded GHL Inquiry form. Keep the matching optional, unchecked SMS checkbox inside the GHL form.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <hr>
        <h2>Usage</h2>
        <p>Use this shortcode: <code>[earlystart_contact_form]</code></p>
    </div>
    <?php
}

/**
 * Contact Form Shortcode
 */
function earlystart_contact_form_shortcode()
{
    $form_id = get_option('earlystart_contact_form_id', earlystart_contact_default_form_id());
    $form_height = get_option('earlystart_contact_form_height', 1600);
    $form_name = get_option('earlystart_contact_form_name', 'Chroma Early Start - A2P Inquiry Form');
    $sms_disclosure = get_option('earlystart_contact_sms_disclosure', earlystart_contact_default_sms_disclosure());
    $lazy_load = get_option('earlystart_contact_lazy_load', false);
    $lazy_delay = get_option('earlystart_contact_lazy_delay', 2000);

    $form_url = earlystart_contact_build_tracked_form_url($form_id);
    $loading_attr = $lazy_load ? 'lazy' : 'eager';
    $wrapper_id = 'earlystart-contact-form-' . sanitize_html_class($form_id);
    $iframe_id = 'inline-' . sanitize_html_class($form_id);

    ob_start();
    ?>
    <div id="<?php echo esc_attr($wrapper_id); ?>" class="earlystart-contact-form-wrapper" data-lazy="<?php echo $lazy_load ? 'true' : 'false'; ?>"
        data-delay="<?php echo esc_attr($lazy_delay); ?>">
        <?php if (!empty($sms_disclosure)): ?>
            <?php
            $privacy_url = function_exists('earlystart_get_link_by_slug') ? earlystart_get_link_by_slug('privacy-policy', 'page') : '';
            if (!$privacy_url && function_exists('earlystart_get_link_by_slug')) {
                $privacy_url = earlystart_get_link_by_slug('privacy', 'page');
            }
            if (!$privacy_url && function_exists('earlystart_get_page_link')) {
                $privacy_url = earlystart_get_page_link('privacy-policy');
            }
            $terms_url = function_exists('earlystart_get_link_by_slug') ? earlystart_get_link_by_slug('terms', 'page') : '';
            if (!$terms_url && function_exists('earlystart_get_page_link')) {
                $terms_url = earlystart_get_page_link('terms');
            }
            ?>
            <div class="earlystart-sms-disclosure rounded-2xl border border-stone-200 bg-stone-50 p-5 mb-6 text-xs leading-relaxed text-stone-700">
                <p><?php echo esc_html($sms_disclosure); ?></p>
                <p class="mt-3">
                    <?php if ($privacy_url): ?>
                        <a class="font-bold text-rose-700 hover:text-rose-800" href="<?php echo esc_url($privacy_url); ?>">Privacy Policy</a>
                    <?php endif; ?>
                    <?php if ($privacy_url && $terms_url): ?>
                        <span aria-hidden="true"> | </span>
                    <?php endif; ?>
                    <?php if ($terms_url): ?>
                        <a class="font-bold text-rose-700 hover:text-rose-800" href="<?php echo esc_url($terms_url); ?>">Terms of Use</a>
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
        <div class="earlystart-ghl-iframe-container" style="min-height: <?php echo esc_attr($form_height); ?>px;">
            <iframe <?php echo $lazy_load ? 'data-src="' . esc_url($form_url) . '"' : 'src="' . esc_url($form_url) . '"'; ?>
                style="width:100%;height:100%;border:none;border-radius:3px;min-height:<?php echo esc_attr($form_height); ?>px;"
                id="<?php echo esc_attr($iframe_id); ?>" loading="<?php echo esc_attr($loading_attr); ?>"
                data-layout="{'id':'INLINE'}" data-trigger-type="alwaysShow" data-trigger-value=""
                data-activation-type="alwaysActivated" data-activation-value="" data-deactivation-type="neverDeactivate"
                data-deactivation-value="" data-form-name="<?php echo esc_attr($form_name); ?>"
                data-height="<?php echo esc_attr($form_height); ?>"
                data-layout-iframe-id="<?php echo esc_attr($iframe_id); ?>"
                data-form-id="<?php echo esc_attr($form_id); ?>" title="<?php echo esc_attr($form_name); ?>">
            </iframe>
        </div>
        <?php if ($lazy_load): ?>
            <noscript>
                <iframe src="<?php echo esc_url($form_url); ?>"
                    style="width:100%;height:100%;border:none;border-radius:3px;min-height:<?php echo esc_attr($form_height); ?>px;"
                    title="<?php echo esc_attr($form_name); ?>"></iframe>
            </noscript>
        <?php endif; ?>
    </div>
    <?php if ($lazy_load): ?>
        <script>
            (function () {
                var loaded = false;
                var container = document.getElementById('<?php echo esc_js($wrapper_id); ?>');
                var iframe = container ? container.querySelector('iframe[data-src]') : null;
                var delay = <?php echo intval($lazy_delay); ?>;
                var trackingKeys = <?php echo wp_json_encode(earlystart_contact_tracking_keys()); ?>;
                function addTrackingParams(url) {
                    try {
                        var trackedUrl = new URL(url, window.location.href);
                        var pageParams = new URLSearchParams(window.location.search);
                        trackingKeys.forEach(function (key) {
                            var value = pageParams.get(key);
                            if (value && !trackedUrl.searchParams.has(key)) {
                                trackedUrl.searchParams.set(key, value);
                            }
                        });
                        trackedUrl.searchParams.set('source', trackedUrl.searchParams.get('source') || 'website_contact_form');
                        trackedUrl.searchParams.set('page_url', window.location.href);
                        if (document.referrer) {
                            trackedUrl.searchParams.set('referrer', document.referrer);
                        }
                        return trackedUrl.toString();
                    } catch (error) {
                        return url;
                    }
                }
                function loadGHLScript() {
                    if (loaded) return;
                    loaded = true;
                    if (iframe && !iframe.getAttribute('src')) {
                        iframe.setAttribute('src', addTrackingParams(iframe.getAttribute('data-src')));
                    }
                    if (document.querySelector('script[data-earlystart-ghl-embed]')) {
                        return;
                    }
                    var script = document.createElement('script');
                    script.src = 'https://link.msgsndr.com/js/form_embed.js';
                    script.async = true;
                    script.setAttribute('data-earlystart-ghl-embed', 'true');
                    document.body.appendChild(script);
                }
                if ('IntersectionObserver' in window && container) {
                    var observer = new IntersectionObserver(function (entries) {
                        if (entries[0].isIntersecting) {
                            loadGHLScript();
                            observer.disconnect();
                        }
                    }, { rootMargin: '300px 0px' });
                    observer.observe(container);
                }
                setTimeout(loadGHLScript, Math.max(delay, 0));
            })();
        </script>
    <?php else: ?>
        <script>
            (function () {
                var iframe = document.getElementById('<?php echo esc_js($iframe_id); ?>');
                var trackingKeys = <?php echo wp_json_encode(earlystart_contact_tracking_keys()); ?>;
                function addTrackingParams(url) {
                    try {
                        var trackedUrl = new URL(url, window.location.href);
                        var pageParams = new URLSearchParams(window.location.search);
                        trackingKeys.forEach(function (key) {
                            var value = pageParams.get(key);
                            if (value && !trackedUrl.searchParams.has(key)) {
                                trackedUrl.searchParams.set(key, value);
                            }
                        });
                        trackedUrl.searchParams.set('source', trackedUrl.searchParams.get('source') || 'website_contact_form');
                        trackedUrl.searchParams.set('page_url', window.location.href);
                        if (document.referrer) {
                            trackedUrl.searchParams.set('referrer', document.referrer);
                        }
                        return trackedUrl.toString();
                    } catch (error) {
                        return url;
                    }
                }
                if (iframe && iframe.getAttribute('src')) {
                    iframe.setAttribute('src', addTrackingParams(iframe.getAttribute('src')));
                }
                if (document.querySelector('script[data-earlystart-ghl-embed]')) {
                    return;
                }
                var script = document.createElement('script');
                script.src = 'https://link.msgsndr.com/js/form_embed.js';
                script.async = true;
                script.setAttribute('data-earlystart-ghl-embed', 'true');
                document.body.appendChild(script);
            })();
        </script>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}
add_shortcode('earlystart_contact_form', 'earlystart_contact_form_shortcode');
