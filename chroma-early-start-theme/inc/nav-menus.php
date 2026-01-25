<?php
/**
 * Navigation Menus with Tailwind Support
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Register navigation menus
 */
function earlystart_register_menus()
{
	register_nav_menus(array(
		'primary' => __('Primary Menu', 'chroma-early-start'),
		'primary_es' => __('Primary Menu (Spanish)', 'chroma-early-start'),
		'footer' => __('Footer Menu', 'chroma-early-start'),
		'footer_es' => __('Footer Menu (Spanish)', 'chroma-early-start'),
		'footer_contact' => __('Footer Contact Menu', 'chroma-early-start'),
		'footer_contact_es' => __('Footer Contact Menu (Spanish)', 'chroma-early-start'),
	));
}
add_action('init', 'earlystart_register_menus');

/**
 * Primary Navigation with Tailwind classes
 */
function earlystart_primary_nav()
{
	$location = (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish()) ? 'primary_es' : 'primary';

	wp_nav_menu(array(
		'theme_location' => $location,
		'container' => false,
		'menu_class' => '',
		'fallback_cb' => 'earlystart_primary_nav_fallback',
		'items_wrap' => '%3$s',
		'depth' => 1,
		'walker' => new earlystart_Primary_Nav_Walker(),
	));
}

/**
 * Primary Nav Fallback
 */
function earlystart_primary_nav_fallback()
{
	$is_es = (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish());

	$pages = $is_es ? array(
		'programs' => 'Programas',
		'locations' => 'Ubicaciones',
		'about' => 'Nosotros',
		'contact-us' => 'Contacto'
	) : array(
		'programs' => 'Programs',
		'locations' => 'Locations',
		'about' => 'About Us',
		'contact-us' => 'Contact'
	);

	foreach ($pages as $slug => $title) {
		$url = earlystart_get_page_link($slug);
		echo '<a href="' . esc_url($url) . '" class="hover:text-rose-600 transition">' . esc_html($title) . '</a>';
	}
}

/**
 * Footer Navigation
 */
function earlystart_footer_nav()
{
	$location = (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish()) ? 'footer_es' : 'footer';

	wp_nav_menu(array(
		'theme_location' => $location,
		'container' => false,
		'menu_class' => '',
		'fallback_cb' => 'earlystart_footer_nav_fallback',
		'items_wrap' => '%3$s',
		'depth' => 1,
		'walker' => new earlystart_Footer_Nav_Walker(),
	));
}

/**
 * Footer Nav Fallback
 */
function earlystart_footer_nav_fallback()
{
	$is_es = (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish());

	$pages = $is_es ? array(
		'home' => 'Inicio',
		'prismpath' => 'PrismPath',
		'programs' => 'Todos los Programas',
		'parents' => 'Padres'
	) : array(
		'home' => 'Home',
		'prismpath' => 'PrismPath',
		'programs' => 'All Programs',
		'parents' => 'Parents'
	);

	foreach ($pages as $slug => $title) {
		$url = ($slug === 'home') ? home_url('/') : home_url('/' . $slug . '/');
		echo '<a href="' . esc_url($url) . '" class="block hover:text-white transition">' . esc_html($title) . '</a>';
	}
}

/**
 * Footer Contact Navigation
 */
function earlystart_footer_contact_nav()
{
	$location = (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish()) ? 'footer_contact_es' : 'footer_contact';

	if (has_nav_menu($location)) {
		wp_nav_menu(array(
			'theme_location' => $location,
			'container' => false,
			'menu_class' => 'mt-4 space-y-2 pt-4 border-t border-white/10',
			'fallback_cb' => false,
			'items_wrap' => '<div class="%2$s">%3$s</div>',
			'depth' => 1,
			'walker' => new earlystart_Footer_Nav_Walker(),
		));
	} else {
		$is_es = (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish());
		$program_slug = earlystart_get_program_base_slug();

		$pages = $is_es ? array(
			$program_slug => 'Programas',
			'locations' => 'Ubicaciones',
			'about' => 'Nosotros',
			'contact-us' => 'Contacto',
		) : array(
			$program_slug => 'Programs',
			'locations' => 'Locations',
			'about' => 'About Us',
			'contact-us' => 'Contact',
		);

		foreach ($pages as $slug => $title) {
			$url = home_url('/' . $slug . '/');
			echo '<a href="' . esc_url($url) . '" class="block hover:text-white transition">' . esc_html($title) . '</a>';
		}
	}
}

/**
 * Custom Walker for Primary Navigation
 */
class earlystart_Primary_Nav_Walker extends Walker_Nav_Menu
{
	function start_lvl(&$output, $depth = 0, $args = null)
	{
		// No submenu wrapper needed
	}

	function end_lvl(&$output, $depth = 0, $args = null)
	{
		// No submenu wrapper needed
	}

	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
	{
		$classes = 'nav-link px-3 py-2 text-stone-600 hover:text-rose-600 font-medium transition-colors text-sm tracking-wide rounded-lg';

		if ($item->current) {
			$classes .= ' text-rose-600 bg-rose-50';
		}

		$url = $item->url;
		// Enforce trailing slash for internal links
		if (strpos($url, home_url()) !== false) {
			$parts = explode('#', $url, 2);
			$path = user_trailingslashit($parts[0]);
			$url = $path . (isset($parts[1]) ? '#' . $parts[1] : '');
		}

		$output .= '<a href="' . esc_url($url) . '" class="' . esc_attr($classes) . '">';
		$output .= esc_html($item->title);
		$output .= '</a>';
	}

	function end_el(&$output, $item, $depth = 0, $args = null)
	{
		// No closing tag needed as we are not using li
	}
}

/**
 * Custom Walker for Footer Navigation
 */
class earlystart_Footer_Nav_Walker extends Walker_Nav_Menu
{
	function start_lvl(&$output, $depth = 0, $args = null)
	{
		// No submenu wrapper needed
	}

	function end_lvl(&$output, $depth = 0, $args = null)
	{
		// No submenu wrapper needed
	}

	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
	{
		$url = $item->url;
		// Enforce trailing slash for internal links
		if (strpos($url, home_url()) !== false) {
			$parts = explode('#', $url, 2);
			$path = user_trailingslashit($parts[0]);
			$url = $path . (isset($parts[1]) ? '#' . $parts[1] : '');
		}

		$output .= '<a href="' . esc_url($url) . '" class="hover:text-rose-400 transition-colors">';
		$output .= esc_html($item->title);
		$output .= '</a>';
	}

	function end_el(&$output, $item, $depth = 0, $args = null)
	{
		// No closing tag needed as we are not using li
	}
}

/**
 * Mobile Navigation
 */
function earlystart_mobile_nav()
{
	$location = (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish()) ? 'primary_es' : 'primary';

	wp_nav_menu(array(
		'theme_location' => $location,
		'container' => false,
		'menu_class' => 'flex flex-col space-y-4',
		'fallback_cb' => 'earlystart_mobile_nav_fallback',
		'items_wrap' => '%3$s',
		'depth' => 1,
		'walker' => new earlystart_Mobile_Nav_Walker(),
	));
}

/**
 * Mobile Nav Fallback
 */
function earlystart_mobile_nav_fallback()
{
	$is_es = (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish());

	$pages = $is_es ? array(
		'home' => 'Inicio',
		'about' => 'Nosotros',
		'approach' => 'Nuestro Enfoque',
		'services' => 'Servicios',
		'contact-us' => 'Contacto'
	) : array(
		'home' => 'Home',
		'about' => 'About Us',
		'approach' => 'Our Approach',
		'services' => 'Services',
		'contact-us' => 'Contact'
	);

	foreach ($pages as $slug => $title) {
		$url = ($slug === 'home') ? home_url('/') : home_url('/' . $slug . '/');
		echo '<a href="' . esc_url($url) . '" class="block w-full text-left text-lg font-medium text-stone-600 py-3 border-b border-stone-50">' . esc_html($title) . '</a>';
	}
}

/**
 * Custom Walker for Mobile Navigation
 */
class earlystart_Mobile_Nav_Walker extends Walker_Nav_Menu
{
	function start_lvl(&$output, $depth = 0, $args = null)
	{
		// No submenu wrapper needed
	}

	function end_lvl(&$output, $depth = 0, $args = null)
	{
		// No submenu wrapper needed
	}

	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
	{
		$classes = 'block w-full text-left text-lg font-medium text-stone-600 py-3 border-b border-stone-50';

		if ($item->current) {
			$classes .= ' text-rose-600';
		}

		$url = $item->url;
		// Enforce trailing slash for internal links
		if (strpos($url, home_url()) !== false) {
			$parts = explode('#', $url, 2);
			$path = user_trailingslashit($parts[0]);
			$url = $path . (isset($parts[1]) ? '#' . $parts[1] : '');
		}

		$output .= '<a href="' . esc_url($url) . '" class="' . esc_attr($classes) . '">';
		$output .= esc_html($item->title);
		$output .= '</a>';
	}

	function end_el(&$output, $item, $depth = 0, $args = null)
	{
		// No closing tag needed as we are not using li
	}
}


