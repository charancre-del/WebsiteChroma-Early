<?php
/**
 * Plugin Name: Early Start Lead Log
 * Description: Lead logging system for tour and clinical inquiries for Early Start.
 * Version: 1.0.0
 * Author: Early Start Development Team
 * Text Domain: earlystart-lead-log
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Lead Log CPT
 */
function earlystart_register_lead_log_cpt()
{
    $labels = array(
        'name' => 'Lead Log',
        'singular_name' => 'Lead',
        'menu_name' => 'Lead Log',
        'all_items' => 'All Leads',
        'view_item' => 'View Lead',
        'search_items' => 'Search Leads',
    );

    $args = array(
        'label' => 'Lead',
        'labels' => $labels,
        'supports' => array('title'),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-list-view',
        'menu_position' => 25,
        'capability_type' => 'post',
        'capabilities' => array('create_posts' => 'do_not_allow'),
        'map_meta_cap' => true,
    );

    register_post_type('lead_log', $args);
}
add_action('init', 'earlystart_register_lead_log_cpt', 0);

/**
 * Add Admin Columns
 */
function earlystart_lead_log_columns($columns)
{
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = 'Lead';
    $new_columns['lead_type'] = 'Type';
    $new_columns['lead_name'] = 'Name';
    $new_columns['lead_email'] = 'Email';
    $new_columns['date'] = 'Date';
    return $new_columns;
}
add_filter('manage_lead_log_posts_columns', 'earlystart_lead_log_columns');

/**
 * Populate Admin Columns
 */
function earlystart_lead_log_column_content($column, $post_id)
{
    switch ($column) {
        case 'lead_type':
            echo esc_html(ucfirst(get_post_meta($post_id, 'lead_type', true)));
            break;
        case 'lead_name':
            echo esc_html(get_post_meta($post_id, 'lead_name', true));
            break;
        case 'lead_email':
            $email = get_post_meta($post_id, 'lead_email', true);
            echo $email ? '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>' : '—';
            break;
    }
}
add_action('manage_lead_log_posts_custom_column', 'earlystart_lead_log_column_content', 10, 2);

/**
 * Details Meta Box
 */
function earlystart_lead_log_add_meta_boxes()
{
    add_meta_box('earlystart_lead_details', 'Lead Details', 'earlystart_lead_log_render_details', 'lead_log', 'normal', 'high');
}
add_action('add_meta_boxes', 'earlystart_lead_log_add_meta_boxes');

function earlystart_lead_log_render_details($post)
{
    $payload_json = get_post_meta($post->ID, 'lead_payload', true);
    $payload = json_decode($payload_json, true);
    if (!$payload || !is_array($payload)) {
        echo '<p>No detailed data available.</p>';
        return;
    }
    echo '<table class="widefat fixed"><thead><tr><th>Field</th><th>Value</th></tr></thead><tbody>';
    foreach ($payload as $label => $value) {
        echo '<tr><td><strong>' . esc_html($label) . '</strong></td><td>' . esc_html($value) . '</td></tr>';
    }
    echo '</tbody></table>';
}

/**
 * Webhook Logic
 */
function earlystart_lead_log_trigger_webhook($post_id, $post)
{
    if ($post->post_type !== 'lead_log' || $post->post_status !== 'publish' || wp_is_post_revision($post_id))
        return;
    $webhook_url = get_option('earlystart_lead_log_webhook_url');
    if (empty($webhook_url) || get_post_meta($post_id, '_earlystart_webhook_sent', true))
        return;

    $body = array(
        'event' => 'new_lead',
        'lead_id' => $post_id,
        'lead_title' => $post->post_title,
        'lead_type' => get_post_meta($post_id, 'lead_type', true),
        'lead_name' => get_post_meta($post_id, 'lead_name', true),
        'lead_email' => get_post_meta($post_id, 'lead_email', true),
        'submitted_at' => current_time('mysql'),
        'data' => json_decode(get_post_meta($post_id, 'lead_payload', true), true) ?: array()
    );

    wp_remote_post($webhook_url, array('body' => wp_json_encode($body), 'headers' => array('Content-Type' => 'application/json'), 'timeout' => 15, 'blocking' => false));
    update_post_meta($post_id, '_earlystart_webhook_sent', time());
}
add_action('save_post', 'earlystart_lead_log_trigger_webhook', 10, 2);

/**
 * Settings
 */
function earlystart_lead_log_register_settings()
{
    register_setting('earlystart_lead_log_options', 'earlystart_lead_log_webhook_url', array('type' => 'string', 'sanitize_callback' => 'esc_url_raw'));
}
add_action('admin_init', 'earlystart_lead_log_register_settings');

function earlystart_lead_log_admin_menu()
{
    add_submenu_page('edit.php?post_type=lead_log', 'Lead Log Settings', 'Settings', 'manage_options', 'earlystart-lead-log-settings', 'earlystart_lead_log_settings_page');
}
add_action('admin_menu', 'earlystart_lead_log_admin_menu');

function earlystart_lead_log_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Early Start Lead Log Settings</h1>
        <form action="options.php" method="post">
            <?php settings_fields('earlystart_lead_log_options'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Webhook URL</th>
                    <td><input type="url" name="earlystart_lead_log_webhook_url"
                            value="<?php echo esc_attr(get_option('earlystart_lead_log_webhook_url')); ?>"
                            class="regular-text" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
