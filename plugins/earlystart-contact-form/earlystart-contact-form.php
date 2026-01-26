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
        array('id' => 'phone', 'label' => 'Phone Number', 'type' => 'tel', 'required' => true, 'width' => 'half', 'placeholder' => '(555) 123-4567'),
        array('id' => 'preferred_campus', 'label' => 'Preferred Clinic', 'type' => 'select_location', 'required' => false, 'width' => 'half', 'placeholder' => 'Select a clinic...'),
        array('id' => 'topic', 'label' => 'Topic', 'type' => 'select', 'required' => false, 'width' => 'full', 'options' => 'Schedule a Tour, Intake Inquiry, Careers / HR, Clinical Referral, Other Inquiry', 'placeholder' => 'Select a topic...'),
        array('id' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => false, 'width' => 'full', 'placeholder' => 'Tell us about your needs...')
    );
}

/**
 * Register Settings
 */
function earlystart_contact_register_settings()
{
    register_setting('earlystart_contact_options', 'earlystart_contact_fields', array('type' => 'string', 'sanitize_callback' => 'earlystart_contact_sanitize_json', 'default' => wp_json_encode(earlystart_contact_default_fields())));
    register_setting('earlystart_contact_options', 'earlystart_contact_webhook_url', array('type' => 'string', 'sanitize_callback' => 'esc_url_raw', 'default' => ''));
    register_setting('earlystart_contact_options', 'earlystart_contact_email_recipient', array('type' => 'string', 'sanitize_callback' => 'sanitize_email', 'default' => get_option('admin_email')));
    register_setting('earlystart_contact_options', 'earlystart_contact_form_id', array('type' => 'string', 'default' => 'ibinKhrBmF0n4S5tFcz6', 'sanitize_callback' => 'sanitize_text_field'));
    register_setting('earlystart_contact_options', 'earlystart_contact_form_height', array('type' => 'integer', 'default' => 779, 'sanitize_callback' => 'absint'));
    register_setting('earlystart_contact_options', 'earlystart_contact_form_name', array('type' => 'string', 'default' => 'Contact Us - Early Start Early Learning', 'sanitize_callback' => 'sanitize_text_field'));
    register_setting('earlystart_contact_options', 'earlystart_contact_lazy_load', array('type' => 'boolean', 'default' => true, 'sanitize_callback' => 'rest_sanitize_boolean'));
    register_setting('earlystart_contact_options', 'earlystart_contact_lazy_delay', array('type' => 'integer', 'default' => 2000, 'sanitize_callback' => 'absint'));
}
add_action('admin_init', 'earlystart_contact_register_settings');

function earlystart_contact_sanitize_json($input)
{
    $decoded = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        add_settings_error('earlystart_contact_fields', 'invalid_json', 'Invalid JSON format.');
        return get_option('earlystart_contact_fields');
    }
    return $input;
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
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Email Recipient</th>
                    <td><input type="email" name="earlystart_contact_email_recipient"
                            value="<?php echo esc_attr($email_recipient); ?>" class="regular-text" /></td>
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
    $form_id = get_option('earlystart_contact_form_id', 'ibinKhrBmF0n4S5tFcz6');
    $form_height = get_option('earlystart_contact_form_height', 779);
    $form_name = get_option('earlystart_contact_form_name', 'Contact Us - Early Start Early Learning');
    $lazy_load = get_option('earlystart_contact_lazy_load', true);
    $lazy_delay = get_option('earlystart_contact_lazy_delay', 2000);

    $form_url = 'https://api.leadconnectorhq.com/widget/form/' . esc_attr($form_id);
    $loading_attr = $lazy_load ? 'lazy' : 'eager';

    ob_start();
    ?>
    <div class="earlystart-contact-form-wrapper" data-lazy="<?php echo $lazy_load ? 'true' : 'false'; ?>"
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
    <?php if ($lazy_load): ?>
        <script>
            (function () {
                var loaded = false;
                var container = document.querySelector('.earlystart-contact-form-wrapper');
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
add_shortcode('earlystart_contact_form', 'earlystart_contact_form_shortcode');
