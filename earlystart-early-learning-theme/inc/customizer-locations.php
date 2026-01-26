<?php
/**
 * Locations Customizer Settings
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Locations Settings
 */
function earlystart_customize_locations($wp_customize)
{

    // Section: Locations Archive
    $wp_customize->add_section('earlystart_locations_settings', array(
        'title' => __('Locations Archive', 'earlystart-early-learning'),
        'description' => __('Customize the title, subtitle, and labels for the Locations page.', 'earlystart-early-learning'),
        'priority' => 130,
    ));

    // Setting: Archive Title
    $wp_customize->add_setting('earlystart_locations_archive_title', array(
        'default' => 'Find your Early Start <span class="text-chroma-green italic">Community</span> - Our Locations',
        'sanitize_callback' => 'earlystart_sanitize_raw_html', // Allow HTML for spans
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('earlystart_locations_archive_title', array(
        'label' => __('Archive Page Title', 'earlystart-early-learning'),
        'description' => __('The main H1 title on the Locations page. HTML allowed.', 'earlystart-early-learning'),
        'section' => 'earlystart_locations_settings',
        'type' => 'textarea',
    ));

    // Setting: Archive Subtitle
    $wp_customize->add_setting('earlystart_locations_archive_subtitle', array(
        'default' => 'Serving families across Metro Atlanta with the same high standards of safety, curriculum, and care at every single location.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('earlystart_locations_archive_subtitle', array(
        'label' => __('Archive Page Subtitle', 'earlystart-early-learning'),
        'description' => __('The subtitle text below the main title.', 'earlystart-early-learning'),
        'section' => 'earlystart_locations_settings',
        'type' => 'textarea',
    ));

    // Setting: "All Locations" Label
    $wp_customize->add_setting('earlystart_locations_label', array(
        'default' => 'All Locations',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('earlystart_locations_label', array(
        'label' => __('"All Locations" Label', 'earlystart-early-learning'),
        'description' => __('The label used for buttons and filters (e.g., "All Areas", "View All Locations").', 'earlystart-early-learning'),
        'section' => 'earlystart_locations_settings',
        'type' => 'text',
    ));

    // Setting: Badge Fallback Text
    $wp_customize->add_setting('earlystart_locations_badge_fallback', array(
        'default' => 'Now Enrolling',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('earlystart_locations_badge_fallback', array(
        'label' => __('Badge Fallback Text', 'earlystart-early-learning'),
        'description' => __('Text to show on the location card badge if not "New Campus" (e.g., "Now Enrolling").', 'earlystart-early-learning'),
        'section' => 'earlystart_locations_settings',
        'type' => 'text',
    ));

    // Setting: Open Now Text
    $wp_customize->add_setting('earlystart_locations_open_text', array(
        'default' => 'Open Now',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('earlystart_locations_open_text', array(
        'label' => __('"Open Now" Text', 'earlystart-early-learning'),
        'description' => __('Text displayed next to the pulsing dot when location is open.', 'earlystart-early-learning'),
        'section' => 'earlystart_locations_settings',
        'type' => 'text',
    ));

}
add_action('customize_register', 'earlystart_customize_locations');


