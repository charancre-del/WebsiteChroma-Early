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

    register_setting($option_group, $option_name);

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
}
add_action('admin_init', 'earlystart_register_theme_settings');

/**
 * Render Text Field Callback
 */
function earlystart_render_text_field($args)
{
    $options = get_option($args['option_name']);
    $value = isset($options[$args['id']]) ? $options[$args['id']] : '';
    echo '<input type="text" name="' . esc_attr($args['option_name']) . '[' . esc_attr($args['id']) . ']" value="' . esc_attr($value) . '" class="regular-text" />';
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
