<?php
/**
 * Plugin Name: Early Start Acquisitions Form
 * Description: Acquisitions inquiry form for potential clinic sellers to Early Start.
 * Version: 1.1.0
 * Author: Early Start Development Team
 * Text Domain: earlystart-acquisitions-form
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default Fields Configuration
 */
function earlystart_acquisition_default_fields()
{
    return array(
        array('id' => 'contact_name', 'label' => 'Your Name', 'type' => 'text', 'required' => true, 'width' => 'half', 'placeholder' => ''),
        array('id' => 'phone', 'label' => 'Phone', 'type' => 'tel', 'required' => true, 'width' => 'half', 'placeholder' => ''),
        array('id' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'width' => 'full', 'placeholder' => ''),
        array('id' => 'facility_name', 'label' => 'Clinic Name', 'type' => 'text', 'required' => true, 'width' => 'full', 'placeholder' => ''),
        array('id' => 'facility_location', 'label' => 'Clinic Location (City, State)', 'type' => 'text', 'required' => true, 'width' => 'full', 'placeholder' => ''),
        array('id' => 'details', 'label' => 'Additional Details', 'type' => 'textarea', 'required' => false, 'width' => 'full', 'placeholder' => '')
    );
}

/**
 * Register Settings
 */
function earlystart_acquisition_register_settings()
{
    register_setting('earlystart_acquisition_options', 'earlystart_acquisition_fields', array('type' => 'string', 'sanitize_callback' => 'earlystart_acquisition_sanitize_json', 'default' => wp_json_encode(earlystart_acquisition_default_fields())));
    register_setting('earlystart_acquisition_options', 'earlystart_acquisition_webhook_url', array('type' => 'string', 'sanitize_callback' => 'esc_url_raw', 'default' => ''));
    register_setting('earlystart_acquisition_options', 'earlystart_acquisition_email_recipient', array('type' => 'string', 'sanitize_callback' => 'sanitize_email', 'default' => 'acquisitions@earlystarttherapy.com'));
}
add_action('admin_init', earlystart_acquisition_register_settings);

function earlystart_acquisition_sanitize_json($input)
{
    $decoded = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        add_settings_error('earlystart_acquisition_fields', 'invalid_json', 'Invalid JSON format.');
        return get_option('earlystart_acquisition_fields');
    }
    return $input;
}

/**
 * Admin Menu
 */
function earlystart_acquisition_admin_menu()
{
    add_options_page('Acquisition Form Settings', 'Acquisition Form', 'manage_options', 'earlystart-acquisition-form', 'earlystart_acquisition_settings_page_html');
}
add_action('admin_menu', 'earlystart_acquisition_admin_menu');

/**
 * Settings Page HTML (Simplified)
 */
function earlystart_acquisition_settings_page_html()
{
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1>Early Start Acquisition Form Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('earlystart_acquisition_options');
            $email_recipient = get_option('earlystart_acquisition_email_recipient', 'acquisitions@earlystarttherapy.com');
            ?>
            <table class="form-table">
                <tr><th scope="row">Email Recipient</th><td><input type="email" name="earlystart_acquisition_email_recipient" value="<?php echo esc_attr($email_recipient); ?>" class="regular-text" /></td></tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <hr>
        <h2>Usage</h2>
        <p>Use this shortcode: <code>[earlystart_acquisition_form]</code></p>
    </div>
    <?php
}

/**
 * Acquisitions Form Shortcode
 */
function earlystart_acquisition_form_shortcode()
{
    $fields_json = get_option('earlystart_acquisition_fields', wp_json_encode(earlystart_acquisition_default_fields()));
    $fields = json_decode($fields_json, true);
    if (!is_array($fields)) { $fields = earlystart_acquisition_default_fields(); }

    ob_start();
    ?>
    <form class="earlystart-acquisition-form space-y-4" method="post" action="">
        <?php wp_nonce_field('earlystart_acquisition_submit', 'earlystart_acquisition_nonce'); ?>
        <input type="hidden" name="earlystart_acquisition_redirect" value="<?php echo esc_url(get_permalink()); ?>" />

        <div class="grid md:grid-cols-2 gap-4">
            <?php foreach ($fields as $field):
                $id = esc_attr($field['id']);
                $label = esc_html($field['label']);
                $type = esc_attr($field['type']);
                $required = !empty($field['required']) ? 'required' : '';
                $width = isset($field['width']) && $field['width'] === 'full' ? 'md:col-span-2' : '';
                $placeholder = isset($field['placeholder']) ? esc_attr($field['placeholder']) : '';
                $asterisk = !empty($field['required']) ? ' *' : '';
                ?>
                <div class="<?php echo esc_attr($width); ?>">
                    <label class="block text-xs font-bold text-stone-900 uppercase mb-1.5" for="acq_<?php echo $id; ?>">
                        <?php echo $label . $asterisk; ?>
                    </label>
                    <?php if ($type === 'textarea'): ?>
                        <textarea id="acq_<?php echo $id; ?>" name="<?php echo $id; ?>" <?php echo $required; ?> placeholder="<?php echo $placeholder; ?>"
                            class="w-full px-4 py-3 rounded-xl border border-stone-200 bg-white focus:border-rose-600 outline-none text-stone-900 h-32"></textarea>
                    <?php else: ?>
                        <input type="<?php echo $type; ?>" id="acq_<?php echo $id; ?>" name="<?php echo $id; ?>" <?php echo $required; ?>
                            placeholder="<?php echo $placeholder; ?>"
                            class="w-full px-4 py-3 rounded-xl border border-stone-200 bg-white focus:border-rose-600 outline-none text-stone-900" />
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" name="earlystart_acquisition_submit"
            class="w-full bg-stone-900 text-white text-xs font-semibold uppercase tracking-wider py-4 rounded-full shadow-soft hover:bg-rose-600 transition">
            Submit Inquiry
        </button>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('earlystart_acquisition_form', 'earlystart_acquisition_form_shortcode');

/**
 * Handle Form Submission
 */
function earlystart_handle_acquisition_submission()
{
    if (!isset($_POST['earlystart_acquisition_submit']) || !wp_verify_nonce(wp_unslash($_POST['earlystart_acquisition_nonce'] ?? ''), 'earlystart_acquisition_submit')) {
        return;
    }

    $fields_json = get_option('earlystart_acquisition_fields', wp_json_encode(earlystart_acquisition_default_fields()));
    $fields = json_decode($fields_json, true) ?: earlystart_acquisition_default_fields();

    $submission_data = array(); $has_error = false; $email = ''; $facility_name = 'Unknown Clinic';
    foreach ($fields as $field) {
        $id = $field['id']; $required = !empty($field['required']);
        $value = isset($_POST[$id]) ? sanitize_text_field(wp_unslash($_POST[$id])) : '';
        if ($field['type'] === 'email') { $value = sanitize_email($value); if ($required && !is_email($value)) $has_error = true; if (is_email($value)) $email = $value; }
        if ($required && empty($value)) $has_error = true;
        if ($id === 'facility_name') $facility_name = $value;
        $submission_data[$field['label']] = $value;
    }

    $redirect_target = !empty($_POST['earlystart_acquisition_redirect']) ? esc_url_raw(wp_unslash($_POST['earlystart_acquisition_redirect'])) : (wp_get_referer() ?: home_url('/acquisitions/'));
    if ($has_error || empty($email)) { wp_safe_redirect(add_query_arg('acquisition_sent', '0', $redirect_target)); exit; }

    $to_email = get_option('earlystart_acquisition_email_recipient', 'acquisitions@earlystarttherapy.com');
    wp_mail($to_email, 'New Acquisition Inquiry: ' . $facility_name, "New inquiry:\n\n" . print_r($submission_data, true));

    if (post_type_exists('lead_log')) {
        wp_insert_post(array(
            'post_type' => 'lead_log',
            'post_title' => 'Acquisition: ' . $facility_name,
            'post_status' => 'publish',
            'meta_input' => array('lead_type' => 'acquisition', 'lead_email' => $email, 'lead_payload' => wp_json_encode($submission_data))
        ));
    }
    wp_safe_redirect(add_query_arg('acquisition_sent', '1', $redirect_target));
    exit;
}
add_action('template_redirect', 'earlystart_handle_acquisition_submission');
