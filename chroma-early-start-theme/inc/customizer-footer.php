<?php
/**
 * Footer Customizer Settings
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Register Footer Customizer Settings
 */
function earlystart_footer_customizer_settings($wp_customize)
{

	// Add Footer Section
	$wp_customize->add_section('earlystart_footer_settings', array(
		'title' => __('Footer Settings', 'chroma-early-start'),
		'priority' => 40,
	));

	/*
	 * Contact Section
	 */
	// Phone Number
	$wp_customize->add_setting('earlystart_footer_phone', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_footer_phone', array(
		'label' => __('Contact Phone', 'chroma-early-start'),
		'description' => __('Phone number to display in footer contact section', 'chroma-early-start'),
		'section' => 'earlystart_footer_settings',
		'type' => 'text',
		'input_attrs' => array(
			'placeholder' => '(404) 555-1234',
		),
	));

	// Email Address
	$wp_customize->add_setting('earlystart_footer_email', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_email',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_footer_email', array(
		'label' => __('Contact Email', 'chroma-early-start'),
		'description' => __('Email address to display in footer contact section', 'chroma-early-start'),
		'section' => 'earlystart_footer_settings',
		'type' => 'email',
		'input_attrs' => array(
			'placeholder' => 'hello@example.com',
		),
	));

	// Address
	$wp_customize->add_setting('earlystart_footer_address', array(
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_footer_address', array(
		'label' => __('Contact Address', 'chroma-early-start'),
		'description' => __('Physical address to display in footer contact section', 'chroma-early-start'),
		'section' => 'earlystart_footer_settings',
		'type' => 'textarea',
		'input_attrs' => array(
			'placeholder' => '123 Main St, Atlanta, GA 30301',
			'rows' => 2,
		),
	));

	/*
	 * Social Links Section
	 */
	// Facebook URL
	$wp_customize->add_setting('earlystart_footer_facebook', array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_footer_facebook', array(
		'label' => __('Facebook URL', 'chroma-early-start'),
		'description' => __('Full URL to your Facebook page', 'chroma-early-start'),
		'section' => 'earlystart_footer_settings',
		'type' => 'url',
		'input_attrs' => array(
			'placeholder' => 'https://facebook.com/yourpage',
		),
	));

	// Instagram URL
	$wp_customize->add_setting('earlystart_footer_instagram', array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_footer_instagram', array(
		'label' => __('Instagram URL', 'chroma-early-start'),
		'description' => __('Full URL to your Instagram profile', 'chroma-early-start'),
		'section' => 'earlystart_footer_settings',
		'type' => 'url',
		'input_attrs' => array(
			'placeholder' => 'https://instagram.com/yourprofile',
		),
	));

	// LinkedIn URL
	$wp_customize->add_setting('earlystart_footer_linkedin', array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_footer_linkedin', array(
		'label' => __('LinkedIn URL', 'chroma-early-start'),
		'description' => __('Full URL to your LinkedIn page', 'chroma-early-start'),
		'section' => 'earlystart_footer_settings',
		'type' => 'url',
		'input_attrs' => array(
			'placeholder' => 'https://linkedin.com/company/yourcompany',
		),
	));

	// Twitter/X URL
	$wp_customize->add_setting('earlystart_footer_twitter', array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_footer_twitter', array(
		'label' => __('Twitter/X URL', 'chroma-early-start'),
		'description' => __('Full URL to your Twitter/X profile', 'chroma-early-start'),
		'section' => 'earlystart_footer_settings',
		'type' => 'url',
		'input_attrs' => array(
			'placeholder' => 'https://twitter.com/yourprofile',
		),
	));

	// YouTube URL
	$wp_customize->add_setting('earlystart_footer_youtube', array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_footer_youtube', array(
		'label' => __('YouTube URL', 'chroma-early-start'),
		'description' => __('Full URL to your YouTube channel', 'chroma-early-start'),
		'section' => 'earlystart_footer_settings',
		'type' => 'url',
		'input_attrs' => array(
			'placeholder' => 'https://youtube.com/@yourchannel',
		),
	));

	// Footer Scripts
	$wp_customize->add_setting('earlystart_footer_scripts', array(
		'default' => '',
		'sanitize_callback' => 'earlystart_sanitize_raw_html',
		'transport' => 'refresh',
	));

	$wp_customize->add_control('earlystart_footer_scripts', array(
		'label' => __('Footer Scripts', 'chroma-early-start'),
		'description' => __('Scripts to be output before </body>.', 'chroma-early-start'),
		'section' => 'earlystart_footer_settings',
		'type' => 'textarea',
	));

}
add_action('customize_register', 'earlystart_footer_customizer_settings');


