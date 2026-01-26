<?php
/**
 * Meta Box Base Class
 * Provides reusable framework for creating meta boxes with consistent patterns
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class earlystart_Advanced_SEO_Meta_Box_Base
{
    /**
     * Get the meta box ID
     */
    abstract public function get_id();

    /**
     * Get the meta box title
     */
    abstract public function get_title();

    /**
     * Get post types this meta box applies to
     */
    abstract public function get_post_types();

    /**
     * Render the meta box fields
     */
    abstract public function render_fields($post);

    /**
     * Save the meta box fields
     */
    abstract public function save_fields($post_id);

    /**
     * Register the meta box
     */
    public function register()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('save_post', [$this, 'save_meta_box'], 10, 2);
    }

    /**
     * Add the meta box to WordPress
     */
    public function add_meta_box()
    {
        add_meta_box(
            $this->get_id(),
            $this->get_title(),
            [$this, 'render'],
            $this->get_post_types(),
            'normal',
            'default'
        );
    }

    /**
     * Render the meta box
     */
    public function render($post)
    {
        wp_nonce_field($this->get_id() . '_save', $this->get_id() . '_nonce');
        echo '<div class="earlystart-advanced-seo-meta-box">';
        $this->render_fields($post);
        echo '</div>';
    }

    /**
     * Save the meta box
     */
    public function save_meta_box($post_id, $post)
    {
        $nonce_name = $this->get_id() . '_nonce';
        if (!isset($_POST[$nonce_name]) || !wp_verify_nonce($_POST[$nonce_name], $this->get_id() . '_save')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (!in_array($post->post_type, $this->get_post_types())) {
            return;
        }

        $this->save_fields($post_id);
    }

    /**
     * Render a text input field
     */
    protected function render_text_field($args)
    {
        $defaults = [
            'id' => '',
            'label' => '',
            'value' => '',
            'description' => '',
            'fallback_notice' => '',
            'placeholder' => '',
            'class' => 'regular-text',
        ];
        $args = wp_parse_args($args, $defaults);

        echo '<div class="earlystart-field-wrapper">';
        if ($args['label']) {
            echo '<label for="' . esc_attr($args['id']) . '">' . esc_html($args['label']) . '</label>';
        }
        echo '<input type="text" id="' . esc_attr($args['id']) . '" name="' . esc_attr($args['id']) . '" value="' . esc_attr($args['value']) . '" placeholder="' . esc_attr($args['placeholder']) . '" class="' . esc_attr($args['class']) . '" />';

        if ($args['description']) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
        if ($args['fallback_notice'] && empty($args['value'])) {
            echo '<p class="description fallback-notice"><em>Fallback: ' . esc_html($args['fallback_notice']) . '</em></p>';
        }
        echo '</div>';
    }

    /**
     * Render a number input field
     */
    protected function render_number_field($args)
    {
        $defaults = [
            'id' => '',
            'label' => '',
            'value' => '',
            'description' => '',
            'fallback_notice' => '',
            'placeholder' => '',
            'step' => '1',
            'min' => '',
            'max' => '',
            'class' => 'small-text',
        ];
        $args = wp_parse_args($args, $defaults);

        echo '<div class="earlystart-field-wrapper">';
        if ($args['label']) {
            echo '<label for="' . esc_attr($args['id']) . '">' . esc_html($args['label']) . '</label>';
        }
        echo '<input type="number" id="' . esc_attr($args['id']) . '" name="' . esc_attr($args['id']) . '" value="' . esc_attr($args['value']) . '" placeholder="' . esc_attr($args['placeholder']) . '" step="' . esc_attr($args['step']) . '" ' . ($args['min'] !== '' ? 'min="' . esc_attr($args['min']) . '"' : '') . ' ' . ($args['max'] !== '' ? 'max="' . esc_attr($args['max']) . '"' : '') . ' class="' . esc_attr($args['class']) . '" />';

        if ($args['description']) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
        if ($args['fallback_notice'] && empty($args['value'])) {
            echo '<p class="description fallback-notice"><em>Fallback: ' . esc_html($args['fallback_notice']) . '</em></p>';
        }
        echo '</div>';
    }

    /**
     * Render a textarea field
     */
    protected function render_textarea_field($args)
    {
        $defaults = [
            'id' => '',
            'label' => '',
            'value' => '',
            'description' => '',
            'fallback_notice' => '',
            'placeholder' => '',
            'rows' => 4,
            'class' => 'large-text',
        ];
        $args = wp_parse_args($args, $defaults);

        echo '<div class="earlystart-field-wrapper">';
        if ($args['label']) {
            echo '<label for="' . esc_attr($args['id']) . '">' . esc_html($args['label']) . '</label>';
        }
        echo '<textarea id="' . esc_attr($args['id']) . '" name="' . esc_attr($args['id']) . '" placeholder="' . esc_attr($args['placeholder']) . '" rows="' . esc_attr($args['rows']) . '" class="' . esc_attr($args['class']) . '">' . esc_textarea($args['value']) . '</textarea>';

        if ($args['description']) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
        if ($args['fallback_notice'] && empty($args['value'])) {
            echo '<p class="description fallback-notice"><em>Fallback: ' . esc_html($args['fallback_notice']) . '</em></p>';
        }
        echo '</div>';
    }

    /**
     * Render a repeater field
     */
    protected function render_repeater_field($args)
    {
        $defaults = [
            'id' => '',
            'label' => '',
            'values' => [],
            'description' => '',
            'button_text' => 'Add Item',
            'placeholder' => '',
        ];
        $args = wp_parse_args($args, $defaults);

        echo '<div class="earlystart-field-wrapper earlystart-repeater-field" data-field-id="' . esc_attr($args['id']) . '">';
        if ($args['label']) {
            echo '<label>' . esc_html($args['label']) . '</label>';
        }

        echo '<div class="earlystart-repeater-items">';
        if (!empty($args['values'])) {
            foreach ($args['values'] as $index => $value) {
                echo '<div class="earlystart-repeater-item">';
                echo '<input type="text" name="' . esc_attr($args['id']) . '[]" value="' . esc_attr($value) . '" class="regular-text" placeholder="' . esc_attr($args['placeholder']) . '" />';
                echo '<button type="button" class="button earlystart-remove-item">Remove</button>';
                echo '</div>';
            }
        }
        echo '</div>';

        echo '<button type="button" class="button earlystart-add-item">' . esc_html($args['button_text']) . '</button>';

        if ($args['description']) {
            echo '<p class="description">' . esc_html($args['description']) . '</p>';
        }
        echo '</div>';
    }
}
