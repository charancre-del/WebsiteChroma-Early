<?php
/**
 * Spanish Content Meta Box
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Spanish_Content_Meta_Box extends earlystart_Advanced_SEO_Meta_Box_Base
{
    public function get_id()
    {
        return 'earlystart_spanish_content';
    }
    public function get_title()
    {
        return __('Spanish Content Overrides', 'earlystart-seo-pro');
    }
    public function get_post_types()
    {
        return ['page', 'post', 'location', 'program'];
    }

    public function render_fields($post)
    {
        $this->render_universal_fields($post);
        $post_type = get_post_type($post);
        if ($post_type === 'location') {
            $this->render_location_fields($post);
        } elseif ($post_type === 'program') {
            $this->render_program_fields($post);
        }

        echo '<div style="margin-top: 20px; padding-top: 15px; border-top: 2px solid #ddd;">';
        echo '<button type="button" id="earlystart-auto-translate-btn" class="button button-primary button-large">';
        echo '<span class="dashicons dashicons-translation"></span> ' . __('Auto-Fill with AI Translation', 'earlystart-seo-pro');
        echo '</button>';
        echo '</div>';

        ?>
        <script>
            jQuery(document).ready(function ($) {
                $('#earlystart-auto-translate-btn').click(function (e) {
                    e.preventDefault();
                    if (!confirm('This will overwrite any existing Spanish content. Continue?')) return;
                    var btn = $(this);
                    btn.prop('disabled', true).text('Translating...');
                    $.post(ajaxurl, {
                        action: 'earlystart_auto_translate_post',
                        post_id: <?php echo $post->ID; ?>,
                            nonce: '<?php echo wp_create_nonce('earlystart_seo_nonce'); ?>'
                }, function (response) {
                    btn.prop('disabled', false).text('Auto-Fill with AI Translation');
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || 'Error');
                    }
                });
            });
                });
        </script>
        <?php
    }

    private function render_universal_fields($post)
    {
        echo '<div class="earlystart-section-header"><h3>Core Content</h3></div>';
        $this->render_text_field([
            'id' => '_earlystart_es_title',
            'label' => 'Spanish Page Title',
            'value' => get_post_meta($post->ID, '_earlystart_es_title', true),
            'placeholder' => get_the_title($post),
        ]);
        $this->render_textarea_field([
            'id' => '_earlystart_es_content',
            'label' => 'Spanish Main Content',
            'value' => get_post_meta($post->ID, '_earlystart_es_content', true),
            'rows' => 8,
        ]);
        $this->render_textarea_field([
            'id' => '_earlystart_es_excerpt',
            'label' => 'Spanish Excerpt',
            'value' => get_post_meta($post->ID, '_earlystart_es_excerpt', true),
            'rows' => 3,
        ]);
    }

    private function render_location_fields($post)
    {
        echo '<div class="earlystart-section-header"><h3>Location Details</h3></div>';
        $this->render_text_field([
            'id' => '_earlystart_es_location_city',
            'label' => 'City (Spanish)',
            'value' => get_post_meta($post->ID, '_earlystart_es_location_city', true),
        ]);
        $this->render_textarea_field([
            'id' => '_earlystart_es_location_description',
            'label' => 'Description (Spanish)',
            'value' => get_post_meta($post->ID, '_earlystart_es_location_description', true),
        ]);
    }

    private function render_program_fields($post)
    {
        echo '<div class="earlystart-section-header"><h3>Program Details</h3></div>';
        $this->render_text_field([
            'id' => '_earlystart_es_program_age_range',
            'label' => 'Age Range (Spanish)',
            'value' => get_post_meta($post->ID, '_earlystart_es_program_age_range', true),
        ]);
    }

    public function save_fields($post_id)
    {
        $fields = [
            '_earlystart_es_title',
            '_earlystart_es_content',
            '_earlystart_es_excerpt',
            '_earlystart_es_location_city',
            '_earlystart_es_location_description',
            '_earlystart_es_program_age_range'
        ];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, wp_kses_post($_POST[$field]));
            }
        }
    }

    public static function register_ajax_handlers()
    {
        // Placeholder for AJAX handlers
    }
}
new earlystart_Spanish_Content_Meta_Box();
earlystart_Spanish_Content_Meta_Box::register_ajax_handlers();
