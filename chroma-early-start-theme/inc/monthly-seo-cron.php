<?php
/**
 * Monthly SEO Cron Job
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Monthly Cron Interval
 */
function earlystart_add_monthly_cron_interval( $schedules ) {
	$schedules['monthly'] = array(
		'interval' => 30 * DAY_IN_SECONDS,
		'display'  => __( 'Once Monthly', 'chroma-early-start' ),
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'earlystart_add_monthly_cron_interval' );

/**
 * Schedule Monthly SEO Event on Theme Activation
 */
function earlystart_activate_monthly_seo_cron() {
	if ( ! wp_next_scheduled( 'earlystart_monthly_seo_event' ) ) {
		wp_schedule_event( time(), 'monthly', 'earlystart_monthly_seo_event' );
	}
}
add_action( 'after_switch_theme', 'earlystart_activate_monthly_seo_cron' );

/**
 * Unschedule on Theme Deactivation
 */
function earlystart_deactivate_monthly_seo_cron() {
	$timestamp = wp_next_scheduled( 'earlystart_monthly_seo_event' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'earlystart_monthly_seo_event' );
	}
}
add_action( 'switch_theme', 'earlystart_deactivate_monthly_seo_cron' );

/**
 * Monthly SEO Callback
 * Pings Google and Bing with sitemap URL
 */
function earlystart_monthly_seo_callback() {
	if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
		error_log( '[Chroma SEO Cron] Monthly SEO event executed at ' . current_time( 'mysql' ) );
	}

	$sitemap_url = home_url( '/?sitemap=xml' );

	// Ping Google
	wp_remote_get( 'https://www.google.com/ping?sitemap=' . urlencode( $sitemap_url ), array(
		'timeout'     => 5,
		'blocking'    => false,
		'sslverify'   => false,
	) );

	// Ping Bing
	wp_remote_get( 'https://www.bing.com/ping?sitemap=' . urlencode( $sitemap_url ), array(
		'timeout'     => 5,
		'blocking'    => false,
		'sslverify'   => false,
	) );
}
add_action( 'earlystart_monthly_seo_event', 'earlystart_monthly_seo_callback' );


