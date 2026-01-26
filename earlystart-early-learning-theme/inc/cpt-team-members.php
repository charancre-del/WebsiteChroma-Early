<?php
/**
 * Custom Post Type: Team Members
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Team Member CPT
 */
function earlystart_register_team_member_cpt()
{
    $labels = array(
        'name' => _x('Team Members', 'Post Type General Name', 'earlystart-early-learning'),
        'singular_name' => _x('Team Member', 'Post Type Singular Name', 'earlystart-early-learning'),
        'menu_name' => __('Team Members', 'earlystart-early-learning'),
        'all_items' => __('All Team Members', 'earlystart-early-learning'),
        'add_new_item' => __('Add New Team Member', 'earlystart-early-learning'),
        'edit_item' => __('Edit Team Member', 'earlystart-early-learning'),
        'view_item' => __('View Team Member', 'earlystart-early-learning'),
        'search_items' => __('Search Team Members', 'earlystart-early-learning'),
        'not_found' => __('No team members found', 'earlystart-early-learning'),
        'not_found_in_trash' => __('No team members found in Trash', 'earlystart-early-learning'),
    );

    $args = array(
        'label' => __('Team Member', 'earlystart-early-learning'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'public' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-groups',
        'has_archive' => false,
        'rewrite' => false,
        'query_var' => false,
        'show_in_rest' => true,
        'exclude_from_search' => true,
    );

    register_post_type('team_member', $args);
}
add_action('init', 'earlystart_register_team_member_cpt');

/**
 * Register meta fields for Team Member title/role.
 */
function earlystart_register_team_member_meta()
{
    register_post_meta(
        'team_member',
        'team_member_title',
        array(
            'object_subtype' => 'team_member',
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        )
    );

    register_post_meta(
        'team_member',
        'team_member_linkedin',
        array(
            'object_subtype' => 'team_member',
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true, // Make accessible via REST API
            'sanitize_callback' => 'esc_url_raw',
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        )
    );
}
add_action('init', 'earlystart_register_team_member_meta');

/**
 * Add Meta Box for Team Member Details
 */
function earlystart_team_member_add_meta_box()
{
    add_meta_box(
        'earlystart_team_member_details',
        __('Team Member Details', 'earlystart-early-learning'),
        'earlystart_team_member_render_meta_box',
        'team_member',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'earlystart_team_member_add_meta_box');

/**
 * Render Meta Box Content
 */
function earlystart_team_member_render_meta_box($post)
{
    wp_nonce_field('earlystart_team_member_save', 'earlystart_team_member_nonce');
    $title_value = get_post_meta($post->ID, 'team_member_title', true);
    $linkedin_value = get_post_meta($post->ID, 'team_member_linkedin', true);
    ?>
    <p>
        <label for="team_member_title"
            style="font-weight:bold; display:block; margin-bottom:5px;"><?php _e('Job Title / Role', 'earlystart-early-learning'); ?></label>
        <input type="text" id="team_member_title" name="team_member_title" value="<?php echo esc_attr($title_value); ?>"
            class="widefat" style="width:100%; max-width:400px;">
        <span class="description"
            style="display:block; margin-top:5px;"><?php _e('e.g. "Executive Director" or "Lead Teacher"', 'earlystart-early-learning'); ?></span>
    </p>
    <p>
        <label for="team_member_linkedin"
            style="font-weight:bold; display:block; margin-bottom:5px;"><?php _e('LinkedIn URL', 'earlystart-early-learning'); ?></label>
        <input type="url" id="team_member_linkedin" name="team_member_linkedin"
            value="<?php echo esc_url($linkedin_value); ?>" class="widefat" style="width:100%; max-width:400px;">
        <span class="description"
            style="display:block; margin-top:5px;"><?php _e('Optional: Link to LinkedIn profile.', 'earlystart-early-learning'); ?></span>
    </p>
    <?php
}

/**
 * Save Meta Box Data
 */
function earlystart_team_member_save_meta_box($post_id)
{
    if (!isset($_POST['earlystart_team_member_nonce']) || !wp_verify_nonce($_POST['earlystart_team_member_nonce'], 'earlystart_team_member_save')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['team_member_title'])) {
        update_post_meta($post_id, 'team_member_title', sanitize_text_field($_POST['team_member_title']));
    }

    if (isset($_POST['team_member_linkedin'])) {
        update_post_meta($post_id, 'team_member_linkedin', esc_url_raw($_POST['team_member_linkedin']));
    }
}
add_action('save_post', 'earlystart_team_member_save_meta_box');

/**
 * Add admin columns for Team Members.
 */
function earlystart_team_member_admin_columns($columns)
{
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = __('Name', 'earlystart-early-learning');
    $new_columns['role'] = __('Title', 'earlystart-early-learning');
    $new_columns['date'] = $columns['date'];

    return $new_columns;
}
add_filter('manage_team_member_posts_columns', 'earlystart_team_member_admin_columns');

/**
 * Populate admin columns.
 */
function earlystart_team_member_admin_column_content($column, $post_id)
{
    switch ($column) {
        case 'role':
            $title = get_post_meta($post_id, 'team_member_title', true);
            echo $title ? esc_html($title) : '—';
            break;
    }
}
add_action('manage_team_member_posts_custom_column', 'earlystart_team_member_admin_column_content', 10, 2);

/**
 * Seed default team members to populate About page cards.
 */
function earlystart_seed_default_team_members()
{
    $existing = get_posts(
        array(
            'post_type' => 'team_member',
            'posts_per_page' => 1,
            'post_status' => 'any',
            'fields' => 'ids',
        )
    );

    if (get_option('earlystart_team_members_seeded') && !empty($existing)) {
        return;
    }

    // If posts exist but the option was never set (e.g., after theme updates),
    // mark the site as seeded so we do not insert duplicates on every request.
    if (!empty($existing)) {
        update_option('earlystart_team_members_seeded', 1);
        return;
    }

    $defaults = array(
        array(
            'post_title' => __('Jordan Avery', 'earlystart-early-learning'),
            'post_content' => __('Jordan leads the Prismpath team with a passion for creating joyful, evidence-based learning experiences for every child.', 'earlystart-early-learning'),
            'role' => __('Executive Director', 'earlystart-early-learning'),
        ),
        array(
            'post_title' => __('Samira Patel', 'earlystart-early-learning'),
            'post_content' => __('Samira partners with families and educators to ensure each center reflects the needs of its community.', 'earlystart-early-learning'),
            'role' => __('Director of Family Partnerships', 'earlystart-early-learning'),
        ),
        array(
            'post_title' => __('Luis Moreno', 'earlystart-early-learning'),
            'post_content' => __('Luis coaches classroom teams, championing playful curricula that meet students where they are.', 'earlystart-early-learning'),
            'role' => __('Director of Learning', 'earlystart-early-learning'),
        ),
    );

    foreach ($defaults as $index => $member) {
        $post_id = wp_insert_post(
            array(
                'post_type' => 'team_member',
                'post_status' => 'publish',
                'post_title' => $member['post_title'],
                'post_content' => $member['post_content'],
                'menu_order' => $index,
            )
        );

        if (!is_wp_error($post_id) && !empty($member['role'])) {
            update_post_meta($post_id, 'team_member_title', $member['role']);
        }
    }

    update_option('earlystart_team_members_seeded', 1);
}
add_action('after_switch_theme', 'earlystart_seed_default_team_members');
add_action('init', 'earlystart_seed_default_team_members', 20);



