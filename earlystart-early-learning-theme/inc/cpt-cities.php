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
        'name' => _x('Cities', 'Post Type General Name', 'earlystart-early-learning'),
        'singular_name' => _x('City', 'Post Type Singular Name', 'earlystart-early-learning'),
        'menu_name' => __('Cities', 'earlystart-early-learning'),
        'name_admin_bar' => __('City', 'earlystart-early-learning'),
        'archives' => __('City Archives', 'earlystart-early-learning'),
        'attributes' => __('City Attributes', 'earlystart-early-learning'),
        'parent_item_colon' => __('Parent City:', 'earlystart-early-learning'),
        'all_items' => __('All Cities', 'earlystart-early-learning'),
        'add_new_item' => __('Add New City', 'earlystart-early-learning'),
        'add_new' => __('Add New', 'earlystart-early-learning'),
        'new_item' => __('New City', 'earlystart-early-learning'),
        'edit_item' => __('Edit City', 'earlystart-early-learning'),
        'update_item' => __('Update City', 'earlystart-early-learning'),
        'view_item' => __('View City', 'earlystart-early-learning'),
        'view_items' => __('View Cities', 'earlystart-early-learning'),
        'search_items' => __('Search City', 'earlystart-early-learning'),
        'not_found' => __('Not found', 'earlystart-early-learning'),
        'not_found_in_trash' => __('Not found in Trash', 'earlystart-early-learning'),
        'featured_image' => __('City Image', 'earlystart-early-learning'),
        'set_featured_image' => __('Set city image', 'earlystart-early-learning'),
        'remove_featured_image' => __('Remove city image', 'earlystart-early-learning'),
        'use_featured_image' => __('Use as city image', 'earlystart-early-learning'),
        'insert_into_item' => __('Insert into city', 'earlystart-early-learning'),
        'uploaded_to_this_item' => __('Uploaded to this city', 'earlystart-early-learning'),
        'items_list' => __('Cities list', 'earlystart-early-learning'),
        'items_list_navigation' => __('Cities list navigation', 'earlystart-early-learning'),
        'filter_items_list' => __('Filter cities list', 'earlystart-early-learning'),
    );
    $args = array(
        'label' => __('City', 'earlystart-early-learning'),
        'description' => __('Hyperlocal landing pages for cities', 'earlystart-early-learning'),
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
        'rewrite' => array('slug' => 'pediatric-therapy'), // e.g., /pediatric-therapy/canton-ga
    );
    register_post_type('city', $args);
}
add_action('init', 'earlystart_register_city_cpt', 0);


