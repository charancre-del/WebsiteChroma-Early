<?php
/**
 * Meta Box: Show in Newsroom
 * Adds a checkbox to posts to toggle visibility in the Newsroom.
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Post_Newsroom
{
    public function register()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('save_post', [$this, 'save_meta_box']);
    }

    public function add_meta_box()
    {
        add_meta_box(
            'earlystart_post_newsroom',
            'Newsroom Settings',
            [$this, 'render_meta_box'],
            'post',
            'side',
            'high'
        );
    }

    public function render_meta_box($post)
    {
        wp_nonce_field('earlystart_post_newsroom_nonce', 'earlystart_post_newsroom_nonce');
        $show_in_newsroom = get_post_meta($post->ID, '_earlystart_show_in_newsroom', true);
        ?>
        <div class="chroma-meta-box">
            <label for="earlystart_show_in_newsroom">
                <input type="checkbox" id="earlystart_show_in_newsroom" name="earlystart_show_in_newsroom" value="1" <?php checked($show_in_newsroom, '1'); ?>>
                Show in Newsroom
            </label>
            <p class="description">Check this box to display this post on the Newsroom page.</p>
        </div>
        <?php
    }

    public function save_meta_box($post_id)
    {
        if (!isset($_POST['earlystart_post_newsroom_nonce']) || !wp_verify_nonce($_POST['earlystart_post_newsroom_nonce'], 'earlystart_post_newsroom_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $val = isset($_POST['earlystart_show_in_newsroom']) ? '1' : '0';
        update_post_meta($post_id, '_earlystart_show_in_newsroom', $val);
    }
}


