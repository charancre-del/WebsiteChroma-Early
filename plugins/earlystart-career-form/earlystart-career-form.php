<?php
/**
 * Plugin Name: Early Start Career Form
 * Description: Job application form with file upload support for Early Start.
 * Version: 1.0.0
 * Author: Early Start Development Team
 * Text Domain: earlystart-career-form
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default Fields Configuration
 */
function earlystart_career_default_fields()
{
    return array(
        array('id' => 'applicant_name', 'label' => 'Full Name', 'type' => 'text', 'required' => true, 'width' => 'half', 'placeholder' => ''),
        array('id' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'width' => 'half', 'placeholder' => ''),
        array('id' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'required' => true, 'width' => 'half', 'placeholder' => ''),
        array('id' => 'position', 'label' => 'Position Applied For', 'type' => 'text', 'required' => false, 'width' => 'half', 'placeholder' => 'e.g. Registered Behavior Technician (RBT)'),
        array('id' => 'resume', 'label' => 'Resume / Cover Letter', 'type' => 'file', 'required' => true, 'width' => 'full', 'placeholder' => ''),
        array('id' => 'message', 'label' => 'Message', 'type' => 'textarea', 'required' => false, 'width' => 'full', 'placeholder' => 'Tell us about your clinical experience...')
    );
}

/**
 * Register Settings
 */
function earlystart_career_register_settings()
{
    register_setting('earlystart_career_options', 'earlystart_career_fields', array('type' => 'string', 'sanitize_callback' => 'earlystart_career_sanitize_json', 'default' => wp_json_encode(earlystart_career_default_fields())));
    register_setting('earlystart_career_options', 'earlystart_career_webhook_url', array('type' => 'string', 'sanitize_callback' => 'esc_url_raw', 'default' => ''));
    register_setting('earlystart_career_options', 'earlystart_career_email_recipient', array('type' => 'string', 'sanitize_callback' => 'sanitize_email', 'default' => 'careers@earlystarttherapy.com'));
    register_setting('earlystart_career_options', 'earlystart_career_form_id', array('type' => 'string', 'default' => 'WYGFB2WBYuti6S6ys30H', 'sanitize_callback' => 'sanitize_text_field'));
    register_setting('earlystart_career_options', 'earlystart_career_form_height', array('type' => 'integer', 'default' => 522, 'sanitize_callback' => 'absint'));
    register_setting('earlystart_career_options', 'earlystart_career_form_name', array('type' => 'string', 'default' => 'Careers Form - Early Start Early Learning', 'sanitize_callback' => 'sanitize_text_field'));
    register_setting('earlystart_career_options', 'earlystart_career_lazy_load', array('type' => 'boolean', 'default' => true, 'sanitize_callback' => 'rest_sanitize_boolean'));
    register_setting('earlystart_career_options', 'earlystart_career_lazy_delay', array('type' => 'integer', 'default' => 2000, 'sanitize_callback' => 'absint'));
}
add_action('admin_init', 'earlystart_career_register_settings');

function earlystart_career_sanitize_json($input)
{
    $decoded = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        add_settings_error('earlystart_career_fields', 'invalid_json', 'Invalid JSON format.');
        return get_option('earlystart_career_fields');
    }
    return $input;
}

/**
 * Admin Menu
 */
function earlystart_career_admin_menu()
{
    add_options_page('Career Form Settings', 'Career Form', 'manage_options', 'earlystart-career-form', 'earlystart_career_settings_page_html');
}
add_action('admin_menu', 'earlystart_career_admin_menu');

/**
 * Settings Page HTML (Simplified)
 */
function earlystart_career_settings_page_html()
{
    if (!current_user_can('manage_options'))
        return;
    ?>
    <div class="wrap">
        <h1>Early Start Career Form Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('earlystart_career_options');
            $email_recipient = get_option('earlystart_career_email_recipient', 'careers@earlystarttherapy.com');
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Email Recipient</th>
                    <td><input type="email" name="earlystart_career_email_recipient"
                            value="<?php echo esc_attr($email_recipient); ?>" class="regular-text" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <hr>
        <h2>Usage</h2>
        <p>Use this shortcode: <code>[earlystart_career_form]</code></p>
    </div>
    <?php
}

/**
 * Career Form Shortcode
 */
function earlystart_career_form_shortcode()
{
    $form_id = get_option('earlystart_career_form_id', 'WYGFB2WBYuti6S6ys30H');
    $form_height = get_option('earlystart_career_form_height', 522);
    $form_name = get_option('earlystart_career_form_name', 'Careers Form - Early Start Early Learning');
    $lazy_load = get_option('earlystart_career_lazy_load', true);
    $lazy_delay = get_option('earlystart_career_lazy_delay', 2000);

    $form_url = 'https://api.leadconnectorhq.com/widget/form/' . esc_attr($form_id);
    $loading_attr = $lazy_load ? 'lazy' : 'eager';

    ob_start();
    ?>
    <div class="earlystart-career-form-wrapper" data-lazy="<?php echo $lazy_load ? 'true' : 'false'; ?>"
        data-delay="<?php echo esc_attr($lazy_delay); ?>">
        <div class="earlystart-ghl-iframe-container" style="min-height: <?php echo esc_attr($form_height); ?>px;">
            <iframe src="<?php echo esc_url($form_url); ?>"
                style="width:100%;height:100%;border:none;border-radius:3px;min-height:<?php echo esc_attr($form_height); ?>px;"
                id="inline-<?php echo esc_attr($form_id); ?>" loading="<?php echo esc_attr($loading_attr); ?>"
                data-layout="{'id':'INLINE'}" data-trigger-type="alwaysShow"
                data-form-name="<?php echo esc_attr($form_name); ?>" data-height="<?php echo esc_attr($form_height); ?>"
                data-form-id="<?php echo esc_attr($form_id); ?>" title="<?php echo esc_attr($form_name); ?>">
            </iframe>
        </div>
    </div>
    <?php if ($lazy_load): ?>
        <script>
            (function () {
                var loaded = false;
                var container = document.querySelector('.earlystart-career-form-wrapper');
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
add_shortcode('earlystart_career_form', 'earlystart_career_form_shortcode');
