<?php
/**
 * Program Relationships Meta Box
 * Handles semantic relationships between Programs and Locations
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Program_Relationships extends earlystart_Advanced_SEO_Meta_Box_Base
{
    /**
     * Get the meta box ID
     *
     * @return string
     */
    public function get_id()
    {
        return 'earlystart_program_relationships';
    }

    /**
     * Get the meta box title
     *
     * @return string
     */
    public function get_title()
    {
        return __('Program Relationships & Availability', 'earlystart-excellence');
    }

    /**
     * Get post types this meta box applies to
     *
     * @return array
     */
    public function get_post_types()
    {
        return ['program'];
    }

    /**
     * Render Fields
     *
     * @param WP_Post $post Current post object
     */
    public function render_fields($post)
    {
        // Get all locations
        $locations = get_posts([
            'post_type' => 'location',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        // Get all other programs
        $programs = get_posts([
            'post_type' => 'program',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'exclude' => [$post->ID],
            'orderby' => 'title',
            'order' => 'ASC'
        ]);

        // Get saved values
        $served_at = get_post_meta($post->ID, 'program_locations_served', true) ?: [];
        $prereqs = get_post_meta($post->ID, 'program_prerequisites', true) ?: [];
        $related = get_post_meta($post->ID, 'program_related', true) ?: [];

        wp_nonce_field('earlystart_program_relationships_save', 'earlystart_program_relationships_nonce');
        ?>

        <div class="earlystart-field-wrapper">
            <label><?php _e('Available at Locations', 'earlystart-excellence'); ?></label>
            <p class="description">
                <?php _e('Select which locations offer this program. This builds the "areaServed" and "provider" schema relationships.', 'earlystart-excellence'); ?>
            </p>

            <div
                style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #fff; margin-top: 5px;">
                <?php if ($locations): ?>
                    <?php foreach ($locations as $location): ?>
                        <label style="display: block; margin-bottom: 5px; font-weight: normal;">
                            <input type="checkbox" name="program_locations_served[]" value="<?php echo esc_attr($location->ID); ?>"
                                <?php checked(in_array($location->ID, $served_at)); ?>>
                            <?php echo esc_html($location->post_title); ?>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><em><?php _e('No locations found.', 'earlystart-excellence'); ?></em></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="earlystart-field-wrapper">
            <label><?php _e('Prerequisites', 'earlystart-excellence'); ?></label>
            <p class="description">
                <?php _e('Select programs that must be completed before this one (e.g., Preschool before Pre-K).', 'earlystart-excellence'); ?>
            </p>

            <select name="program_prerequisites[]" class="widefat" multiple style="height: 150px;">
                <?php foreach ($programs as $prog): ?>
                    <option value="<?php echo esc_attr($prog->ID); ?>" <?php selected(in_array($prog->ID, $prereqs)); ?>>
                        <?php echo esc_html($prog->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php _e('Hold Ctrl/Cmd to select multiple.', 'earlystart-excellence'); ?></p>
        </div>

        <div class="earlystart-field-wrapper">
            <label><?php _e('Related Programs', 'earlystart-excellence'); ?></label>
            <p class="description">
                <?php _e('Select semantically related programs (e.g., Summer Camp related to After School).', 'earlystart-excellence'); ?>
            </p>

            <select name="program_related[]" class="widefat" multiple style="height: 150px;">
                <?php foreach ($programs as $prog): ?>
                    <option value="<?php echo esc_attr($prog->ID); ?>" <?php selected(in_array($prog->ID, $related)); ?>>
                        <?php echo esc_html($prog->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php _e('Hold Ctrl/Cmd to select multiple.', 'earlystart-excellence'); ?></p>
        </div>

        <?php
    }

    /**
     * Save Fields
     *
     * @param int $post_id Post ID
     */
    public function save_fields($post_id)
    {
        // Check nonce
        if (!isset($_POST['earlystart_program_relationships_nonce']) || !wp_verify_nonce($_POST['earlystart_program_relationships_nonce'], 'earlystart_program_relationships_save')) {
            return;
        }

        // Save Locations
        if (isset($_POST['program_locations_served'])) {
            $locations = array_map('intval', $_POST['program_locations_served']);
            update_post_meta($post_id, 'program_locations_served', $locations);
        } else {
            delete_post_meta($post_id, 'program_locations_served');
        }

        // Save Prerequisites
        if (isset($_POST['program_prerequisites'])) {
            $prereqs = array_map('intval', $_POST['program_prerequisites']);
            update_post_meta($post_id, 'program_prerequisites', $prereqs);
        } else {
            delete_post_meta($post_id, 'program_prerequisites');
        }

        // Save Related
        if (isset($_POST['program_related'])) {
            $related = array_map('intval', $_POST['program_related']);
            update_post_meta($post_id, 'program_related', $related);
        } else {
            delete_post_meta($post_id, 'program_related');
        }
    }
}


