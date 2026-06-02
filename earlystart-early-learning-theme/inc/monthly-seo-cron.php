<?php
/**
 * Monthly SEO Cron Job
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Add Monthly Cron Interval
 */
function earlystart_add_monthly_cron_interval($schedules)
{
	$schedules['monthly'] = array(
		'interval' => 30 * DAY_IN_SECONDS,
		'display' => __('Once Monthly', 'earlystart-early-learning'),
	);
	return $schedules;
}
add_filter('cron_schedules', 'earlystart_add_monthly_cron_interval');

/**
 * Schedule Monthly SEO Event on Theme Activation
 */
function earlystart_activate_monthly_seo_cron()
{
	if (!wp_next_scheduled('earlystart_monthly_seo_event')) {
		wp_schedule_event(time() + HOUR_IN_SECONDS, 'monthly', 'earlystart_monthly_seo_event');
	}
}
add_action('after_switch_theme', 'earlystart_activate_monthly_seo_cron');

/**
 * Ensure the monthly SEO event exists after deploys and restores.
 */
function earlystart_ensure_monthly_seo_cron()
{
	if (!wp_next_scheduled('earlystart_monthly_seo_event')) {
		earlystart_activate_monthly_seo_cron();
	}
}
add_action('init', 'earlystart_ensure_monthly_seo_cron');

/**
 * Unschedule on Theme Deactivation
 */
function earlystart_deactivate_monthly_seo_cron()
{
	$timestamp = wp_next_scheduled('earlystart_monthly_seo_event');
	if ($timestamp) {
		wp_unschedule_event($timestamp, 'earlystart_monthly_seo_event');
	}
}
add_action('switch_theme', 'earlystart_deactivate_monthly_seo_cron');

/**
 * Get the canonical sitemap URL used by recurring SEO pings.
 */
function earlystart_monthly_seo_sitemap_url()
{
	return apply_filters('earlystart_monthly_seo_sitemap_url', home_url('/sitemap.xml'));
}

/**
 * Fire-and-forget sitemap ping with strict transport settings.
 */
function earlystart_monthly_seo_ping($endpoint, $sitemap_url)
{
	$url = add_query_arg('sitemap', $sitemap_url, $endpoint);
	$url = esc_url_raw($url, array('https'));

	if ($url === '' || (function_exists('wp_http_validate_url') && !wp_http_validate_url($url))) {
		return false;
	}

	return wp_remote_get($url, array(
		'timeout' => 5,
		'blocking' => false,
		'sslverify' => true,
		'reject_unsafe_urls' => true,
	));
}

/**
 * Monthly SEO Callback
 * Pings Google and Bing with sitemap URL
 */
function earlystart_monthly_seo_callback()
{
	if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
		error_log('[Early Start SEO Cron] Monthly SEO event executed at ' . current_time('mysql'));
	}

	$sitemap_url = esc_url_raw(earlystart_monthly_seo_sitemap_url(), array('http', 'https'));
	if ($sitemap_url === '') {
		return;
	}

	earlystart_monthly_seo_ping('https://www.google.com/ping', $sitemap_url);
	earlystart_monthly_seo_ping('https://www.bing.com/ping', $sitemap_url);
}
add_action('earlystart_monthly_seo_event', 'earlystart_monthly_seo_callback');


