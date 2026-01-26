<?php
/**
 * Custom Post Type: Careers
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
    exit;
}

function earlystart_register_careers_cpt() {
    $labels = array(
        'name'                  => _x('Careers', 'Post Type General Name', 'earlystart-early-learning'),
        'singular_name'         => _x('Career', 'Post Type Singular Name', 'earlystart-early-learning'),
        'menu_name'             => __('Careers', 'earlystart-early-learning'),
        'name_admin_bar'        => __('Career', 'earlystart-early-learning'),
        'archives'              => __('Career Archives', 'earlystart-early-learning'),
        'attributes'            => __('Career Attributes', 'earlystart-early-learning'),
        'parent_item_colon'     => __('Parent Career:', 'earlystart-early-learning'),
        'all_items'             => __('All Careers', 'earlystart-early-learning'),
        'add_new_item'          => __('Add New Career', 'earlystart-early-learning'),
        'add_new'               => __('Add New', 'earlystart-early-learning'),
        'new_item'              => __('New Career', 'earlystart-early-learning'),
        'edit_item'             => __('Edit Career', 'earlystart-early-learning'),
        'update_item'           => __('Update Career', 'earlystart-early-learning'),
        'view_item'             => __('View Career', 'earlystart-early-learning'),
        'view_items'            => __('View Careers', 'earlystart-early-learning'),
        'search_items'          => __('Search Career', 'earlystart-early-learning'),
        'not_found'             => __('Not found', 'earlystart-early-learning'),
        'not_found_in_trash'    => __('Not found in Trash', 'earlystart-early-learning'),
        'featured_image'        => __('Featured Image', 'earlystart-early-learning'),
        'set_featured_image'    => __('Set featured image', 'earlystart-early-learning'),
        'remove_featured_image' => __('Remove featured image', 'earlystart-early-learning'),
        'use_featured_image'    => __('Use as featured image', 'earlystart-early-learning'),
        'insert_into_item'      => __('Insert into career', 'earlystart-early-learning'),
        'uploaded_to_this_item' => __('Uploaded to this career', 'earlystart-early-learning'),
        'items_list'            => __('Careers list', 'earlystart-early-learning'),
        'items_list_navigation' => __('Careers list navigation', 'earlystart-early-learning'),
        'filter_items_list'     => __('Filter careers list', 'earlystart-early-learning'),
    );
    $args = array(
        'label'                 => __('Career', 'earlystart-early-learning'),
        'description'           => __('Job Openings and Career Opportunities', 'earlystart-early-learning'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'excerpt', 'revisions'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-id-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // Enable Gutenberg
    );
    register_post_type('career', $args);
}
add_action('init', 'earlystart_register_careers_cpt', 0);

// Add Helper Meta Boxes for Job Details
function earlystart_add_career_meta_boxes() {
    add_meta_box(
        'earlystart_career_details',
        __('Job Details (Schema Data)', 'earlystart-early-learning'),
        'earlystart_render_career_meta_box',
        'career',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'earlystart_add_career_meta_boxes');

function earlystart_render_career_meta_box($post) {
    wp_nonce_field('earlystart_save_career_meta', 'earlystart_career_meta_nonce');

    $salary = get_post_meta($post->ID, '_career_salary', true);
    $salary_currency = get_post_meta($post->ID, '_career_salary_currency', true) ?: 'USD';
    $salary_unit = get_post_meta($post->ID, '_career_salary_unit', true) ?: 'YEAR';
    $type = get_post_meta($post->ID, '_career_type', true) ?: 'FULL_TIME';
    $location = get_post_meta($post->ID, '_career_location', true);
    $date_posted = get_post_meta($post->ID, '_career_date_posted', true) ?: get_the_date('Y-m-d', $post->ID);

    ?>
    <p>
        <label for="_career_type"><strong><?php _e('Employment Type:', 'earlystart-early-learning'); ?></strong></label><br>
        <select name="_career_type" id="_career_type" style="width:100%;">
            <option value="FULL_TIME" <?php selected($type, 'FULL_TIME'); ?>>Full Time</option>
            <option value="PART_TIME" <?php selected($type, 'PART_TIME'); ?>>Part Time</option>
            <option value="CONTRACTOR" <?php selected($type, 'CONTRACTOR'); ?>>Contractor</option>
            <option value="TEMPORARY" <?php selected($type, 'TEMPORARY'); ?>>Temporary</option>
            <option value="INTERN" <?php selected($type, 'INTERN'); ?>>Intern</option>
            <option value="VOLUNTEER" <?php selected($type, 'VOLUNTEER'); ?>>Volunteer</option>
        </select>
    </p>

    <p>
        <label for="_career_salary"><strong><?php _e('Base Salary:', 'earlystart-early-learning'); ?></strong></label><br>
        <input type="number" name="_career_salary" id="_career_salary" value="<?php echo esc_attr($salary); ?>" style="width:100px;">
        <select name="_career_salary_currency" id="_career_salary_currency">
            <option value="USD" <?php selected($salary_currency, 'USD'); ?>>USD</option>
            <option value="EUR" <?php selected($salary_currency, 'EUR'); ?>>EUR</option>
        </select>
        <label for="_career_salary_unit"><?php _e('Per:', 'earlystart-early-learning'); ?></label>
        <select name="_career_salary_unit" id="_career_salary_unit">
            <option value="HOUR" <?php selected($salary_unit, 'HOUR'); ?>>Hour</option>
            <option value="WEEK" <?php selected($salary_unit, 'WEEK'); ?>>Week</option>
            <option value="MONTH" <?php selected($salary_unit, 'MONTH'); ?>>Month</option>
            <option value="YEAR" <?php selected($salary_unit, 'YEAR'); ?>>Year</option>
        </select>
    </p>

    <p>
        <label for="_career_location"><strong><?php _e('Job Location Address:', 'earlystart-early-learning'); ?></strong></label><br>
        <input type="text" name="_career_location" id="_career_location" value="<?php echo esc_attr($location); ?>" style="width:100%;" placeholder="123 Example St, City, State, Zip">
    </p>

    <p>
        <label for="_career_date_posted"><strong><?php _e('Date Posted:', 'earlystart-early-learning'); ?></strong></label><br>
        <input type="date" name="_career_date_posted" id="_career_date_posted" value="<?php echo esc_attr($date_posted); ?>" style="width:100%;">
    </p>
    <?php
}

function earlystart_save_career_meta($post_id) {
    if (!isset($_POST['earlystart_career_meta_nonce']) || !wp_verify_nonce($_POST['earlystart_career_meta_nonce'], 'earlystart_save_career_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        '_career_type',
        '_career_salary',
        '_career_salary_currency',
        '_career_salary_unit',
        '_career_location',
        '_career_date_posted'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_career', 'earlystart_save_career_meta');


