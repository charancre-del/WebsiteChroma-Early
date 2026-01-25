<?php
/**
 * Register City Custom Post Type
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

function earlystart_register_city_cpt()
{
    $labels = array(
        'name' => _x('Cities', 'Post Type General Name', 'chroma-early-start'),
        'singular_name' => _x('City', 'Post Type Singular Name', 'chroma-early-start'),
        'menu_name' => __('Cities', 'chroma-early-start'),
        'name_admin_bar' => __('City', 'chroma-early-start'),
        'archives' => __('City Archives', 'chroma-early-start'),
        'attributes' => __('City Attributes', 'chroma-early-start'),
        'parent_item_colon' => __('Parent City:', 'chroma-early-start'),
        'all_items' => __('All Cities', 'chroma-early-start'),
        'add_new_item' => __('Add New City', 'chroma-early-start'),
        'add_new' => __('Add New', 'chroma-early-start'),
        'new_item' => __('New City', 'chroma-early-start'),
        'edit_item' => __('Edit City', 'chroma-early-start'),
        'update_item' => __('Update City', 'chroma-early-start'),
        'view_item' => __('View City', 'chroma-early-start'),
        'view_items' => __('View Cities', 'chroma-early-start'),
        'search_items' => __('Search City', 'chroma-early-start'),
        'not_found' => __('Not found', 'chroma-early-start'),
        'not_found_in_trash' => __('Not found in Trash', 'chroma-early-start'),
        'featured_image' => __('City Image', 'chroma-early-start'),
        'set_featured_image' => __('Set city image', 'chroma-early-start'),
        'remove_featured_image' => __('Remove city image', 'chroma-early-start'),
        'use_featured_image' => __('Use as city image', 'chroma-early-start'),
        'insert_into_item' => __('Insert into city', 'chroma-early-start'),
        'uploaded_to_this_item' => __('Uploaded to this city', 'chroma-early-start'),
        'items_list' => __('Cities list', 'chroma-early-start'),
        'items_list_navigation' => __('Cities list navigation', 'chroma-early-start'),
        'filter_items_list' => __('Filter cities list', 'chroma-early-start'),
    );
    $args = array(
        'label' => __('City', 'chroma-early-start'),
        'description' => __('Hyperlocal landing pages for cities', 'chroma-early-start'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 22, // Below Locations
        'menu_icon' => 'dashicons-location',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => 'communities',
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'page',
        'show_in_rest' => true, // Enable Gutenberg
        'rewrite' => array('slug' => 'childcare'), // e.g., /childcare/canton-ga
    );
    register_post_type('city', $args);
}
add_action('init', 'earlystart_register_city_cpt', 0);


