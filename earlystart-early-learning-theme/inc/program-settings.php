<?php
/**
 * Program settings and helpers.
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
	return;
}

/**
 * Default slug for the Program archive.
 */
function earlystart_program_base_slug_default()
{
	return 'programs';
}

/**
 * Sanitize a program base slug value.
 */
function earlystart_sanitize_program_base_slug($slug)
{
	$slug = sanitize_title($slug);

	return $slug ?: earlystart_program_base_slug_default();
}

/**
 * Retrieve the Program archive slug.
 */
function earlystart_get_program_base_slug()
{
	$slug = get_option('earlystart_program_base_slug', '');

	return earlystart_sanitize_program_base_slug($slug);
}

/**
 * Retrieve the Program archive URL.
 */
function earlystart_get_program_archive_url()
{
	return home_url('/' . earlystart_get_program_base_slug() . '/');
}

/**
 * Register Customizer controls for the Program archive slug.
 */
function earlystart_program_settings_customize_register(WP_Customize_Manager $wp_customize)
{
	$wp_customize->add_section(
		'earlystart_program_settings',
		array(
			'title' => __('Programs', 'earlystart-early-learning'),
			'description' => __('Control the URL slug for the Program archive and permalinks.', 'earlystart-early-learning'),
			'priority' => 131,
		)
	);

	$wp_customize->add_setting(
		'earlystart_program_base_slug',
		array(
			'type' => 'option',
			'default' => earlystart_program_base_slug_default(),
			'sanitize_callback' => 'earlystart_sanitize_program_base_slug',
		)
	);

	$wp_customize->add_control(
		'earlystart_program_base_slug',
		array(
			'label' => __('Program base slug', 'earlystart-early-learning'),
			'description' => __('Used for the Programs archive URL and individual program permalinks.', 'earlystart-early-learning'),
			'section' => 'earlystart_program_settings',
			'type' => 'text',
		)
	);
}
add_action('customize_register', 'earlystart_program_settings_customize_register');

/**
 * Flush rewrites when the Program base slug changes.
 */
function earlystart_maybe_flush_rewrite_on_program_slug_change($option, $old_value = '', $value = '')
{
	if ('earlystart_program_base_slug' !== $option) {
		return;
	}

	$previous = earlystart_sanitize_program_base_slug($old_value);
	$new = earlystart_sanitize_program_base_slug($value ?: $old_value);

	if ($previous === $new) {
		return;
	}

	flush_rewrite_rules();
}
add_action('updated_option', 'earlystart_maybe_flush_rewrite_on_program_slug_change', 10, 3);
add_action('added_option', 'earlystart_maybe_flush_rewrite_on_program_slug_change', 10, 2);
/**
 * Ensure rewrites are refreshed on theme activation.
 */
function earlystart_flush_rewrite_on_activation()
{
	flush_rewrite_rules();
}
add_action('after_switch_theme', 'earlystart_flush_rewrite_on_activation');


