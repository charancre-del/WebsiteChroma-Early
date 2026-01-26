<?php
/**
 * Header Customizer Settings
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Register Header Customizer Settings
 */
function earlystart_header_customizer_settings($wp_customize)
{

	// Add Header Section
	$wp_customize->add_section('earlystart_header_settings', array(
		'title' => __('Header Settings', 'earlystart-early-learning'),
		'priority' => 30,
	));

	// Header Text
	$wp_customize->add_setting('earlystart_header_text', array(
		'default' => "Early Learning\nAcademy",
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_header_text', array(
		'label' => __('Header Text', 'earlystart-early-learning'),
		'description' => __('Enter text to display next to the logo. First line will be bold and larger. Additional lines will be small, uppercase, and blue.', 'earlystart-early-learning'),
		'section' => 'earlystart_header_settings',
		'type' => 'textarea',
		'input_attrs' => array(
			'placeholder' => "Early Learning\nAcademy",
			'rows' => 3,
		),
	));

	// Book a Tour Button Text
	$wp_customize->add_setting('earlystart_header_cta_text', array(
		'default' => 'Book a Tour',
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_header_cta_text', array(
		'label' => __('Book a Tour Button Text', 'earlystart-early-learning'),
		'description' => __('Enter the text for the CTA button in the header.', 'earlystart-early-learning'),
		'section' => 'earlystart_header_settings',
		'type' => 'text',
		'input_attrs' => array(
			'placeholder' => 'Book a Tour',
		),
	));

	// Book a Tour Button URL
	$wp_customize->add_setting('earlystart_book_tour_url', array(
		'default' => home_url('/contact#tour'),
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_book_tour_url', array(
		'label' => __('Book a Tour Button URL', 'earlystart-early-learning'),
		'description' => __('Enter the URL for the "Book a Tour" button in the header.', 'earlystart-early-learning'),
		'section' => 'earlystart_header_settings',
		'type' => 'url',
		'input_attrs' => array(
			'placeholder' => home_url('/contact#tour'),
		),
	));

	// Header Scripts
	$wp_customize->add_setting('earlystart_header_scripts', array(
		'default' => '',
		'sanitize_callback' => 'earlystart_sanitize_raw_html',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_header_scripts', array(
		'label' => __('Header Scripts', 'earlystart-early-learning'),
		'description' => __('Scripts to be output before </head>.', 'earlystart-early-learning'),
		'section' => 'earlystart_header_settings',
		'type' => 'textarea',
	));

	// Logo Width (Desktop)
	$wp_customize->add_setting('earlystart_logo_width_desktop', array(
		'default' => 70,
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_logo_width_desktop', array(
		'label' => __('Logo Width (Desktop)', 'earlystart-early-learning'),
		'description' => __('Width in pixels (default: 70).', 'earlystart-early-learning'),
		'section' => 'earlystart_header_settings',
		'type' => 'range',
		'input_attrs' => array(
			'min' => 40,
			'max' => 200,
			'step' => 1,
		),
	));

	// Logo Width (Mobile)
	$wp_customize->add_setting('earlystart_logo_width_mobile', array(
		'default' => 56,
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_logo_width_mobile', array(
		'label' => __('Logo Width (Mobile)', 'earlystart-early-learning'),
		'description' => __('Width in pixels (default: 56).', 'earlystart-early-learning'),
		'section' => 'earlystart_header_settings',
		'type' => 'range',
		'input_attrs' => array(
			'min' => 30,
			'max' => 120,
			'step' => 1,
		),
	));

}
add_action('customize_register', 'earlystart_header_customizer_settings');


