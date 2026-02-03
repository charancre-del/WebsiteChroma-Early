<?php
/**
 * Advanced SEO/LLM Module - Bootstrap
 * Loads all modules and registers meta boxes / hooks
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

// Global to track missing files
global $earlystart_missing_seo_files;
$earlystart_missing_seo_files = [];

/**
 * Helper to safely load files
 */
if (!function_exists('earlystart_safe_require')) {
	function earlystart_safe_require($path)
	{
		global $earlystart_missing_seo_files;
		if (file_exists($path)) {
			require_once $path;
			return true;
		}
		$earlystart_missing_seo_files[] = basename($path);
		return false;
	}
}

/**
 * Helper function for debug logging.
 * Only logs when WP_DEBUG and WP_DEBUG_LOG are enabled.
 * 
 * @param mixed $message Message to log (string or array/object for print_r)
 * @param string $prefix Optional prefix for the log message
 */
if (!function_exists('earlystart_debug_log')) {
	function earlystart_debug_log($message, $prefix = 'earlystart SEO')
	{
		if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
			$log_message = is_string($message) ? $message : print_r($message, true);
			error_log('[' . $prefix . '] ' . $log_message);
		}
	}
}

/**
 * Load Base Classes & Helpers
 */
earlystart_safe_require(__DIR__ . '/class-meta-box-base.php');
earlystart_safe_require(__DIR__ . '/class-field-sanitizer.php');
earlystart_safe_require(__DIR__ . '/class-fallback-resolver.php');

/**
 * Load Core Classes
 */
earlystart_safe_require(__DIR__ . '/class-seo-dashboard.php');
earlystart_safe_require(__DIR__ . '/class-citation-datasets.php');
earlystart_safe_require(__DIR__ . '/class-image-alt-automation.php');
earlystart_safe_require(__DIR__ . '/class-admin-help.php');
earlystart_safe_require(__DIR__ . '/class-breadcrumbs.php');
earlystart_safe_require(__DIR__ . '/class-schema-types.php');
earlystart_safe_require(__DIR__ . '/class-schema-validator.php');
earlystart_safe_require(__DIR__ . '/class-llm-client.php');
earlystart_safe_require(__DIR__ . '/class-google-places-client.php');
earlystart_safe_require(__DIR__ . '/class-llm-bulk-processor.php');
earlystart_safe_require(__DIR__ . '/class-translation-engine.php');
earlystart_safe_require(__DIR__ . '/class-schema-quality.php');
earlystart_safe_require(__DIR__ . '/class-advanced-features.php');
earlystart_safe_require(__DIR__ . '/class-multilingual-manager.php');
earlystart_safe_require(__DIR__ . '/class-validation-cache.php');
earlystart_safe_require(__DIR__ . '/class-validation-logger.php');
earlystart_safe_require(__DIR__ . '/class-facts-generator.php');


// Load Admin UI
earlystart_safe_require(__DIR__ . '/admin/class-llm-admin-settings.php');
earlystart_safe_require(__DIR__ . '/admin/class-schema-inspector.php');
earlystart_safe_require(__DIR__ . '/admin/class-theme-translator.php');
earlystart_safe_require(__DIR__ . '/admin/class-content-inspector.php');
earlystart_safe_require(__DIR__ . '/admin/class-hreflang-auditor.php');
earlystart_safe_require(__DIR__ . '/class-sitemap-integrator.php');
earlystart_safe_require(__DIR__ . '/class-cli-commands.php');
earlystart_safe_require(__DIR__ . '/class-llms-txt.php');
earlystart_safe_require(__DIR__ . '/class-translation-api.php');
earlystart_safe_require(__DIR__ . '/class-homepage-translation-admin.php');
earlystart_safe_require(__DIR__ . '/class-careers-api.php');
earlystart_safe_require(__DIR__ . '/class-career-sync.php');

// Load Theme Schema Compatibility (migrated from seo-engine.php)
earlystart_safe_require(__DIR__ . '/class-schema-registry.php');
earlystart_safe_require(__DIR__ . '/class-theme-schema-compat.php');

// Load SEO Automations
earlystart_safe_require(__DIR__ . '/seo-automations/bootstrap.php');

// Load Editor Metabox
earlystart_safe_require(__DIR__ . '/meta-boxes/class-schema-editor-metabox.php');


// Initialize LLM Client
global $earlystart_llm_client;
$earlystart_llm_client = new earlystart_LLM_Client();

/**
 * Load Meta Boxes
 */
$meta_boxes = [
	'class-location-events.php',
	'class-location-howto.php',
	'class-general-llm-context.php', // Renamed from location-llm-context
	'class-general-llm-prompt.php',  // Renamed from location-llm-prompt
	'class-location-media.php',
	'class-location-pricing.php',
	'class-location-reviews.php',
	'class-location-service-area.php',
	'class-program-relationships.php',
	'class-universal-faq.php',
	'class-hreflang-options.php',
	'class-city-landing-meta.php',
	'class-location-citation-facts.php',
	'class-post-newsroom.php',
	'class-location-advanced-schema.php', // Tier 5: License, CID, Open House, Event Venue
	'class-spanish-content.php'
];

foreach ($meta_boxes as $file) {
	// Try loading new name first, then fallback to old name if not renamed yet (during transition)
	if (!earlystart_safe_require(__DIR__ . '/meta-boxes/' . $file)) {
		// Fallback for transition period
		$old_file = str_replace('general-', 'location-', $file);
		if (file_exists(__DIR__ . '/meta-boxes/' . $old_file)) {
			require_once __DIR__ . '/meta-boxes/' . $old_file;
		}
	}
}

/**
 * Load Endpoints
 */
earlystart_safe_require(__DIR__ . '/endpoints/kml-endpoint.php');

/**
 * Load Schema Builders
 */
$schema_builders = [
	'class-event-builder.php',
	'class-howto-builder.php',
	'class-llm-context-builder.php',
	'class-schema-injector.php',
	'class-service-area-builder.php',
	'class-universal-faq-builder.php',
	'class-page-type-builder.php',
	'class-archive-itemlist-builder.php',
	'class-job-posting-builder.php',
	'class-course-builder.php',
	'class-article-builder.php',
	'class-special-announcement-builder.php',
	'class-learning-resource-builder.php'
];

foreach ($schema_builders as $file) {
	earlystart_safe_require(__DIR__ . '/schema-builders/' . $file);
}

/**
 * Initialize Modules
 */
if (!function_exists('earlystart_advanced_seo_init')) {
	function earlystart_advanced_seo_init()
	{
		// Core Modules
		if (class_exists('earlystart_SEO_Dashboard'))
			(new earlystart_SEO_Dashboard())->init();
		if (class_exists('earlystart_Citation_Datasets'))
			(new earlystart_Citation_Datasets())->init();
		if (class_exists('earlystart_Image_Alt_Automation'))
			(new earlystart_Image_Alt_Automation())->init();
		if (class_exists('earlystart_Admin_Help'))
			(new earlystart_Admin_Help())->init();
		if (class_exists('earlystart_Breadcrumbs'))
			(new earlystart_Breadcrumbs())->init();
		if (class_exists('earlystart_Multilingual_Manager'))
			(new earlystart_Multilingual_Manager())->init();
		if (class_exists('earlystart_Translation_Engine'))
			earlystart_Translation_Engine::init();
		if (class_exists('earlystart_Theme_Translator'))
			(new earlystart_Theme_Translator())->init();
		if (class_exists('earlystart_Content_Inspector'))
			(new earlystart_Content_Inspector())->init();
		if (class_exists('earlystart_Sitemap_Integrator'))
			(new earlystart_Sitemap_Integrator())->init();
		if (class_exists('earlystart_Hreflang_Auditor'))
			(new earlystart_Hreflang_Auditor())->init();
		if (class_exists('earlystart_Translation_API'))
			(new earlystart_Translation_API())->init();
		if (class_exists('earlystart_LLMs_Txt_Generator'))
			(new earlystart_LLMs_Txt_Generator())->init();
		if (class_exists('earlystart_Validation_Logger'))
			(new earlystart_Validation_Logger())->init();
		if (class_exists('earlystart_Career_Sync'))
			earlystart_Career_Sync::init();
		if (class_exists('earlystart_Facts_Generator'))
			(new earlystart_Facts_Generator())->init();


		// Meta Boxes
		$meta_classes = [
			'earlystart_Location_Citation_Facts',
			'earlystart_Location_Events',
			'earlystart_Location_HowTo',
			'earlystart_General_LLM_Context', // Renamed
			'earlystart_General_LLM_Prompt',  // Renamed
			'earlystart_Location_Media',
			'earlystart_Location_Pricing',
			'earlystart_Location_Reviews',
			'earlystart_Location_Service_Area',
			'earlystart_Program_Relationships',
			'earlystart_Universal_FAQ',
			'earlystart_Hreflang_Options',
			'earlystart_City_Landing_Meta',
			'earlystart_Post_Newsroom',
			'earlystart_Location_Advanced_Schema', // Tier 5: License, CID, Open House, Event Venue
			'earlystart_Spanish_Content_Meta_Box'
		];


		// Fallback for class names if files haven't been updated yet
		if (!class_exists('earlystart_General_LLM_Context') && class_exists('earlystart_Location_LLM_Context')) {
			$meta_classes[] = 'earlystart_Location_LLM_Context';
		}
		if (!class_exists('earlystart_General_LLM_Prompt') && class_exists('earlystart_Location_LLM_Prompt')) {
			$meta_classes[] = 'earlystart_Location_LLM_Prompt';
		}

		foreach ($meta_classes as $class) {
			if (class_exists($class)) {
				(new $class())->register();
			}
		}

		// Schema Builders (Hooks)
		if (class_exists('earlystart_Event_Schema_Builder'))
			add_action('wp_head', ['earlystart_Event_Schema_Builder', 'output']);
		if (class_exists('earlystart_HowTo_Schema_Builder'))
			add_action('wp_head', ['earlystart_HowTo_Schema_Builder', 'output']);
		if (class_exists('earlystart_Schema_Injector'))
			add_action('wp_head', ['earlystart_Schema_Injector', 'output_person_schema']);
		if (class_exists('earlystart_Schema_Injector'))
			add_action('wp_head', ['earlystart_Schema_Injector', 'output_job_posting_schema']);
		if (class_exists('earlystart_Schema_Injector'))
			add_action('wp_head', ['earlystart_Schema_Injector', 'output_course_schema']);
		if (class_exists('earlystart_Universal_FAQ_Builder'))
			add_action('wp_head', ['earlystart_Universal_FAQ_Builder', 'output']);
		if (class_exists('earlystart_Page_Type_Builder'))
			add_action('wp_head', ['earlystart_Page_Type_Builder', 'output']);
		if (class_exists('earlystart_Schema_Injector'))
			add_action('wp_head', ['earlystart_Schema_Injector', 'output_website_schema']);
		if (class_exists('earlystart_Archive_ItemList_Builder'))
			add_action('wp_head', ['earlystart_Archive_ItemList_Builder', 'output']);
		if (class_exists('earlystart_Article_Builder'))
			add_action('wp_head', ['earlystart_Article_Builder', 'output']);

		// Advanced Schema (New Builders)
		if (class_exists('earlystart_Special_Announcement_Builder'))
			add_action('wp_head', ['earlystart_Special_Announcement_Builder', 'output']);
		if (class_exists('earlystart_Learning_Resource_Builder'))
			add_action('wp_head', ['earlystart_Learning_Resource_Builder', 'output']);

		// Modular Schemas from Schema Builder (stored in _earlystart_post_schemas meta)
		if (class_exists('earlystart_Schema_Injector'))
			add_action('wp_head', ['earlystart_Schema_Injector', 'output_modular_schemas'], 20);

		// Flush Rewrite Rules if KML rule is missing (One-time check)
		if (get_option('earlystart_seo_flush_rewrite_v6') !== 'done') {
			flush_rewrite_rules();
			update_option('earlystart_seo_flush_rewrite_v6', 'done');
		}
	}
	add_action('init', 'earlystart_advanced_seo_init');
}

/**
 * Admin Assets
 */
if (!function_exists('earlystart_advanced_seo_admin_assets')) {
	function earlystart_advanced_seo_admin_assets($hook)
	{
		// Only load on SEO Dashboard or Post Edit screens
		$screen = get_current_screen();
		$allowed_post_types = ['location', 'program', 'page', 'post', 'city'];

		$is_dashboard = (isset($_GET['page']) && $_GET['page'] === 'earlystart-seo-dashboard');
		$is_post_edit = ($hook === 'post.php' || $hook === 'post-new.php');
		$is_allowed_type = ($screen && in_array($screen->post_type, $allowed_post_types));

		if (!$is_dashboard && !($is_post_edit && $is_allowed_type)) {
			return;
		}

		?>
		<style>
			.earlystart-seo-meta-box {
				background: #fff;
			}

			.earlystart-section-title {
				font-size: 14px;
				font-weight: 600;
				margin: 15px 0 10px;
				border-bottom: 1px solid #eee;
				padding: 10px 0;
			}

			.earlystart-field-wrapper {
				margin-bottom: 20px;
			}

			.earlystart-field-wrapper label {
				display: block;
				font-weight: 600;
				margin-bottom: 5px;
			}

			.earlystart-field-wrapper .description {
				margin-top: 5px;
				font-style: normal;
				color: #666;
			}

			.earlystart-field-wrapper .fallback-notice {
				color: #2271b1;
				font-style: italic;
			}

			.earlystart-repeater-field {
				border: 1px solid #ddd;
				padding: 15px;
				background: #f9f9f9;
			}

			.earlystart-repeater-items {
				margin-bottom: 10px;
			}

			.earlystart-repeater-item {
				display: flex;
				gap: 10px;
				margin-bottom: 8px;
				align-items: center;
			}

			.earlystart-repeater-item input {
				flex: 1;
			}

			.earlystart-remove-item {
				color: #b32d2e;
			}
		</style>
		<script>
			jQuery(document).ready(function ($) {
				// Repeater Add
				$(document).on('click', '.earlystart-add-item', function (e) {
					e.preventDefault();
					var $wrapper = $(this).closest('.earlystart-repeater-field');
					var $items = $wrapper.find('.earlystart-repeater-items');
					var $clone = $items.find('.earlystart-repeater-item').first().clone();
					$clone.find('input, textarea').val('');
					$items.append($clone);
				});

				// Repeater Remove
				$(document).on('click', '.earlystart-remove-item', function (e) {
					e.preventDefault();
					if ($(this).closest('.earlystart-repeater-items').find('.earlystart-repeater-item').length > 1) {
						$(this).closest('.earlystart-repeater-item').remove();
					} else {
						$(this).closest('.earlystart-repeater-item').find('input, textarea').val('');
					}
				});
			});
		</script>
		<?php
	}

	add_action('admin_enqueue_scripts', 'earlystart_advanced_seo_admin_assets');
} // Close function_exists for earlystart_advanced_seo_admin_assets

/**
 * Admin Notice for Missing Files
 */
if (!function_exists('earlystart_seo_missing_files_notice')) {
	function earlystart_seo_missing_files_notice()
	{
		global $earlystart_missing_seo_files;
		if (!empty($earlystart_missing_seo_files) && current_user_can('manage_options')) {
			echo '<div class="notice notice-warning is-dismissible">';
			echo '<p><strong>Advanced SEO Module Warning:</strong> The following files are missing and could not be loaded:</p>';
			echo '<ul>';
			foreach ($earlystart_missing_seo_files as $file) {
				echo '<li>' . esc_html($file) . '</li>';
			}
			echo '</ul>';
			echo '<p>Please ensure all files are uploaded to <code>inc/advanced-seo-llm/</code>.</p>';
			echo '</div>';
		}
	}
	add_action('admin_notices', 'earlystart_seo_missing_files_notice');
}

