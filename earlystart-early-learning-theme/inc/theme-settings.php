<?php
/**
 * Theme Settings (Native WordPress Settings API)
 * Replaces ACF Options Page dependency
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Theme Settings Menu
 */
function earlystart_register_theme_settings_menu()
{
    add_menu_page(
        __('Theme Settings', 'earlystart-early-learning'),
        __('Theme Settings', 'earlystart-early-learning'),
        'manage_options',
        'earlystart-theme-settings',
        'earlystart_render_theme_settings_page',
        'dashicons-admin-generic',
        60
    );
}
add_action('admin_menu', 'earlystart_register_theme_settings_menu');

/**
 * Register Settings
 */
function earlystart_register_theme_settings()
{
    $option_group = 'earlystart_theme_settings_group';
    $option_name = 'earlystart_global_settings';

    register_setting(
        $option_group,
        $option_name,
        array(
            'type' => 'array',
            'sanitize_callback' => 'earlystart_sanitize_global_settings',
            'default' => array(),
        )
    );
    register_setting(
        $option_group,
        'earlystart_seo_head_mode',
        array(
            'type' => 'string',
            'sanitize_callback' => 'earlystart_sanitize_seo_head_mode',
            'default' => 'theme_primary',
        )
    );

    // Section: Contact Info
    add_settings_section(
        'earlystart_contact_section',
        __('Contact Information', 'earlystart-early-learning'),
        null,
        'earlystart-theme-settings'
    );

    $fields = array(
        'global_phone' => __('Phone Number', 'earlystart-early-learning'),
        'global_email' => __('Email Address', 'earlystart-early-learning'),
        'global_tour_email' => __('Tour Email (Optional)', 'earlystart-early-learning'),
        'global_admissions_email' => __('Admissions Email', 'earlystart-early-learning'),
        'global_careers_email' => __('Careers Email', 'earlystart-early-learning'),
        'global_billing_email' => __('Billing Email', 'earlystart-early-learning'),
        'global_media_email' => __('Media Email', 'earlystart-early-learning'),
        'global_privacy_email' => __('Privacy Email', 'earlystart-early-learning'),
        'global_address' => __('Street Address', 'earlystart-early-learning'),
        'global_city' => __('City', 'earlystart-early-learning'),
        'global_state' => __('State', 'earlystart-early-learning'),
        'global_zip' => __('ZIP Code', 'earlystart-early-learning'),
    );

    foreach ($fields as $id => $label) {
        add_settings_field(
            $id,
            $label,
            'earlystart_render_text_field',
            'earlystart-theme-settings',
            'earlystart_contact_section',
            array('id' => $id, 'option_name' => $option_name)
        );
    }

    // Section: Social Media
    add_settings_section(
        'earlystart_social_section',
        __('Social Media Links', 'earlystart-early-learning'),
        null,
        'earlystart-theme-settings'
    );

    $social_fields = array(
        'global_facebook_url' => __('Facebook URL', 'earlystart-early-learning'),
        'global_instagram_url' => __('Instagram URL', 'earlystart-early-learning'),
        'global_linkedin_url' => __('LinkedIn URL', 'earlystart-early-learning'),
    );

    foreach ($social_fields as $id => $label) {
        add_settings_field(
            $id,
            $label,
            'earlystart_render_text_field',
            'earlystart-theme-settings',
            'earlystart_social_section',
            array('id' => $id, 'option_name' => $option_name)
        );
    }

    // Section: SEO Ownership
    add_settings_section(
        'earlystart_seo_ownership_section',
        __('SEO Ownership', 'earlystart-early-learning'),
        null,
        'earlystart-theme-settings'
    );

    add_settings_field(
        'earlystart_seo_head_mode',
        __('SEO Head Mode', 'earlystart-early-learning'),
        'earlystart_render_seo_head_mode_field',
        'earlystart-theme-settings',
        'earlystart_seo_ownership_section'
    );
}
add_action('admin_init', 'earlystart_register_theme_settings');

/**
 * Sanitize SEO head ownership mode.
 *
 * @param string $mode Raw setting.
 * @return string
 */
function earlystart_sanitize_seo_head_mode($mode)
{
    $allowed = array('theme_primary', 'plugin_primary', 'hybrid');
    $mode = sanitize_key((string) $mode);

    if (!in_array($mode, $allowed, true)) {
        return function_exists('earlystart_get_default_seo_head_mode') ? earlystart_get_default_seo_head_mode() : 'theme_primary';
    }

    return $mode;
}

/**
 * Sanitize global theme settings saved from the native settings page.
 *
 * @param mixed $settings Raw submitted settings.
 * @return array
 */
function earlystart_sanitize_global_settings($settings)
{
    if (!is_array($settings)) {
        return array();
    }

    $email_keys = array(
        'global_email',
        'global_tour_email',
        'global_admissions_email',
        'global_careers_email',
        'global_billing_email',
        'global_media_email',
        'global_privacy_email',
    );
    $url_keys = array(
        'global_facebook_url',
        'global_instagram_url',
        'global_linkedin_url',
    );
    $text_keys = array(
        'global_phone',
        'global_address',
        'global_city',
        'global_state',
        'global_zip',
        'global_seo_default_title',
        'global_seo_default_description',
        'global_logo',
    );

    $allowed_keys = array_merge($email_keys, $url_keys, $text_keys);
    $clean = array();

    foreach ($allowed_keys as $key) {
        if (!array_key_exists($key, $settings)) {
            continue;
        }

        $value = wp_unslash($settings[$key]);
        if (is_array($value)) {
            $value = '';
        }

        if (in_array($key, $email_keys, true)) {
            $value = sanitize_email($value);
        } elseif (in_array($key, $url_keys, true)) {
            $value = esc_url_raw($value);
        } else {
            $value = sanitize_text_field($value);
        }

        if (function_exists('earlystart_is_placeholder_global_setting') && earlystart_is_placeholder_global_setting($key, $value)) {
            $value = '';
        }

        $clean[$key] = $value;
    }

    return $clean;
}

/**
 * Render Text Field Callback
 */
function earlystart_render_text_field($args)
{
    $options = get_option($args['option_name']);
    $options = is_array($options) ? $options : array();
    $value = isset($options[$args['id']]) ? $options[$args['id']] : '';
    echo '<input type="text" name="' . esc_attr($args['option_name']) . '[' . esc_attr($args['id']) . ']" value="' . esc_attr($value) . '" class="regular-text" />';
}

/**
 * Render SEO head ownership select field.
 */
function earlystart_render_seo_head_mode_field()
{
    $default = function_exists('earlystart_get_default_seo_head_mode') ? earlystart_get_default_seo_head_mode() : 'theme_primary';
    $value = get_option('earlystart_seo_head_mode', $default);
    $value = earlystart_sanitize_seo_head_mode($value);

    $choices = array(
        'theme_primary' => __('Theme Primary', 'earlystart-early-learning'),
        'plugin_primary' => __('Plugin Primary', 'earlystart-early-learning'),
        'hybrid' => __('Hybrid', 'earlystart-early-learning'),
    );

    echo '<select name="earlystart_seo_head_mode" id="earlystart_seo_head_mode">';
    foreach ($choices as $mode => $label) {
        echo '<option value="' . esc_attr($mode) . '" ' . selected($value, $mode, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
    echo '<p class="description">' . esc_html__('Theme Primary = theme emits canonical/meta/schema. Plugin Primary = plugin owns SEO head. Hybrid = plugin canonical/schema + theme social/meta.', 'earlystart-early-learning') . '</p>';
}

/**
 * Render Settings Page
 */
function earlystart_render_theme_settings_page()
{
    ?>
    <div class="wrap">
        <h1>
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('earlystart_theme_settings_group');
            do_settings_sections('earlystart-theme-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
