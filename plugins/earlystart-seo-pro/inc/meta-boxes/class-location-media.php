<?php
/**
 * Media & Availability Meta Box
 * Handles video tours and real-time availability
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Location_Media_Meta_Box extends earlystart_Advanced_SEO_Meta_Box_Base
{
    /**
     * Get meta box ID
     *
     * @return string
     */
    public function get_id()
    {
        return 'earlystart_location_media';
    }

    /**
     * Get meta box title
     *
     * @return string
     */
    public function get_title()
    {
        return __('Media & Availability (Video/Tour)', 'earlystart-excellence');
    }

    /**
     * Get allowed post types
     *
     * @return array
     */
    public function get_post_types()
    {
        return ['location'];
    }

    /**
     * Render the meta box fields
     *
     * @param WP_Post $post Current post object
     */
    public function render_fields($post)
    {
        // Video Fields
        $video_url = get_post_meta($post->ID, 'location_video_tour_url', true);
        $video_thumbnail = get_post_meta($post->ID, 'location_video_thumbnail', true);
        $video_duration = get_post_meta($post->ID, 'location_video_duration', true);

        // Availability Fields
        $availability_status = get_post_meta($post->ID, 'location_availability_status', true);
        $spots_available = get_post_meta($post->ID, 'location_spots_available', true);

        ?>
        <div class="earlystart-meta-section">
            <h4><?php _e('Virtual Tour Video', 'earlystart-excellence'); ?></h4>
            <p class="description">
                <?php _e('Adding a video tour increases search visibility and engagement.', 'earlystart-excellence'); ?></p>

            <div class="earlystart-meta-field">
                <label for="location_video_tour_url"><?php _e('Video URL (YouTube/Vimeo)', 'earlystart-excellence'); ?></label>
                <input type="url" id="location_video_tour_url" name="location_video_tour_url"
                    value="<?php echo esc_url($video_url); ?>" class="widefat" placeholder="https://youtube.com/watch?v=..." />
            </div>

            <div class="earlystart-meta-field-row" style="display: flex; gap: 15px;">
                <div style="flex: 1;">
                    <label for="location_video_duration"><?php _e('Duration (ISO 8601)', 'earlystart-excellence'); ?></label>
                    <input type="text" id="location_video_duration" name="location_video_duration"
                        value="<?php echo esc_attr($video_duration); ?>" placeholder="PT2M30S" />
                    <small><?php _e('Format: PT#M#S (e.g., PT2M30S for 2 min 30 sec)', 'earlystart-excellence'); ?></small>
                </div>
                <div style="flex: 1;">
                    <label for="location_video_thumbnail"><?php _e('Thumbnail URL', 'earlystart-excellence'); ?></label>
                    <input type="url" id="location_video_thumbnail" name="location_video_thumbnail"
                        value="<?php echo esc_url($video_thumbnail); ?>" class="widefat" />
                </div>
            </div>
        </div>

        <hr>

        <div class="earlystart-meta-section">
            <h4><?php _e('Real-Time Availability', 'earlystart-excellence'); ?></h4>

            <div class="earlystart-meta-field-row" style="display: flex; gap: 15px;">
                <div style="flex: 1;">
                    <label for="location_availability_status"><?php _e('Status', 'earlystart-excellence'); ?></label>
                    <select id="location_availability_status" name="location_availability_status" class="widefat">
                        <option value="InStock" <?php selected($availability_status, 'InStock'); ?>>
                            <?php _e('Spots Available', 'earlystart-excellence'); ?></option>
                        <option value="LimitedAvailability" <?php selected($availability_status, 'LimitedAvailability'); ?>>
                            <?php _e('Limited Spots', 'earlystart-excellence'); ?></option>
                        <option value="OutOfStock" <?php selected($availability_status, 'OutOfStock'); ?>>
                            <?php _e('Waitlist Only', 'earlystart-excellence'); ?></option>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label for="location_spots_available"><?php _e('Spots Count (Optional)', 'earlystart-excellence'); ?></label>
                    <input type="number" id="location_spots_available" name="location_spots_available"
                        value="<?php echo esc_attr($spots_available); ?>" />
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Save the meta box fields
     *
     * @param int $post_id Post ID
     */
    public function save_fields($post_id)
    {
        $fields = [
            'location_video_tour_url' => 'url',
            'location_video_thumbnail' => 'url',
            'location_video_duration' => 'text',
            'location_availability_status' => 'text',
            'location_spots_available' => 'text',
        ];

        foreach ($fields as $field => $type) {
            if (isset($_POST[$field])) {
                $value = $_POST[$field];
                if ($type === 'url') {
                    $value = esc_url_raw($value);
                } else {
                    $value = sanitize_text_field($value);
                }
                update_post_meta($post_id, $field, $value);
            }
        }
    }
}


