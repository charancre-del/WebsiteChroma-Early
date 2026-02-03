<?php
/**
 * Events Meta Box
 * Handles Open Houses and other events
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Location_Events_Meta_Box extends earlystart_Advanced_SEO_Meta_Box_Base
{
    /**
     * Get meta box ID
     *
     * @return string
     */
    public function get_id()
    {
        return 'earlystart_location_events';
    }

    /**
     * Get meta box title
     *
     * @return string
     */
    public function get_title()
    {
        return __('Upcoming Events (Open Houses)', 'earlystart-excellence');
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
        $events = get_post_meta($post->ID, 'location_events', true);
        if (!is_array($events)) {
            $events = [];
        }

        ?>
        <div class="earlystart-meta-section">
            <p class="description">
                <?php _e('Add upcoming open houses or parent nights. These will be marked up with Event schema.', 'earlystart-excellence'); ?>
            </p>

            <div class="earlystart-repeater-field" data-field-id="location_events">
                <div class="earlystart-repeater-items">
                    <?php if (!empty($events)): ?>
                        <?php foreach ($events as $event): ?>
                            <div class="earlystart-repeater-item" style="align-items: flex-start;">
                                <div style="flex: 1; display: flex; gap: 10px; flex-direction: column;">
                                    <div style="display: flex; gap: 10px;">
                                        <input type="text" name="location_events[name][]"
                                            value="<?php echo esc_attr($event['name']); ?>" class="widefat"
                                            placeholder="Event Name (e.g., Spring Open House)" />
                                        <input type="datetime-local" name="location_events[start][]"
                                            value="<?php echo esc_attr($event['start']); ?>" style="width: 200px;" />
                                    </div>
                                    <div style="display: flex; gap: 10px;">
                                        <input type="text" name="location_events[description][]"
                                            value="<?php echo esc_attr($event['description']); ?>" class="widefat"
                                            placeholder="Short Description" />
                                        <input type="url" name="location_events[url][]" value="<?php echo esc_url($event['url']); ?>"
                                            class="widefat" placeholder="RSVP URL (Optional)" />
                                    </div>
                                </div>
                                <button type="button" class="button earlystart-remove-item">Remove</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button"
                    class="button earlystart-add-item-event"><?php _e('Add Event', 'earlystart-excellence'); ?></button>
            </div>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                // Custom handler for Event repeater
                $(document).on('click', '.earlystart-add-item-event', function (e) {
                    e.preventDefault();
                    var $wrapper = $(this).closest('.earlystart-repeater-field');
                    var $items = $wrapper.find('.earlystart-repeater-items');

                    var $newItem = $('<div class="earlystart-repeater-item" style="align-items: flex-start;">' +
                        '<div style="flex: 1; display: flex; gap: 10px; flex-direction: column;">' +
                        '<div style="display: flex; gap: 10px;">' +
                        '<input type="text" name="location_events[name][]" class="widefat" placeholder="Event Name" />' +
                        '<input type="datetime-local" name="location_events[start][]" style="width: 200px;" />' +
                        '</div>' +
                        '<div style="display: flex; gap: 10px;">' +
                        '<input type="text" name="location_events[description][]" class="widefat" placeholder="Short Description" />' +
                        '<input type="url" name="location_events[url][]" class="widefat" placeholder="RSVP URL (Optional)" />' +
                        '</div>' +
                        '</div>' +
                        '<button type="button" class="button earlystart-remove-item">Remove</button>' +
                        '</div>');

                    $items.append($newItem);
                });
            });
        </script>
        <?php
    }

    /**
     * Save the meta box fields
     *
     * @param int $post_id Post ID
     */
    public function save_fields($post_id)
    {
        if (isset($_POST['location_events'])) {
            $names = $_POST['location_events']['name'] ?? [];
            $starts = $_POST['location_events']['start'] ?? [];
            $descs = $_POST['location_events']['description'] ?? [];
            $urls = $_POST['location_events']['url'] ?? [];

            $events = [];
            for ($i = 0; $i < count($names); $i++) {
                if (!empty($names[$i])) {
                    $events[] = [
                        'name' => sanitize_text_field($names[$i]),
                        'start' => sanitize_text_field($starts[$i]),
                        'description' => sanitize_text_field($descs[$i]),
                        'url' => esc_url_raw($urls[$i]),
                    ];
                }
            }

            update_post_meta($post_id, 'location_events', $events);
        }
    }
}


