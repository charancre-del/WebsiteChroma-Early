<?php
/**
 * Schema Editor Metabox
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Schema_Editor_Metabox
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'register_metabox']);
        add_action('save_post', [$this, 'on_save_post'], 10, 2);
    }

    public function register_metabox()
    {
        add_meta_box(
            'earlystart_schema_tools',
            '🔧 Schema Tools',
            [$this, 'render_metabox'],
            ['location', 'program', 'post', 'page'],
            'side',
            'high'
        );
    }

    public function render_metabox($post)
    {
        wp_nonce_field('earlystart_schema_tools', 'earlystart_schema_nonce');

        $schema = get_post_meta($post->ID, '_earlystart_schema_data', true);
        $has_schema = !empty($schema);
        ?>
        <div class="earlystart-schema-tools">
            <div class="schema-status">
                <?php if ($has_schema): ?>
                    <span class="status-badge good">✓ Schema exists</span>
                <?php else: ?>
                    <span class="status-badge warning">⚠ No schema</span>
                <?php endif; ?>
            </div>
            <div class="schema-actions" style="margin-top:10px;">
                <button type="button" class="button button-primary" id="earlystart-generate-schema"
                    data-post="<?php echo $post->ID; ?>">
                    🤖 Generate Schema
                </button>
            </div>
        </div>
        <script>
            jQuery(function ($) {
                $('#earlystart-generate-schema').on('click', function () {
                    var $btn = $(this);
                    $btn.prop('disabled', true).text('Generating...');
                    $.post(ajaxurl, {
                        action: 'earlystart_generate_schema',
                        nonce: '<?php echo wp_create_nonce('earlystart_seo_nonce'); ?>',
                        post_id: $btn.data('post')
                    }, function (response) {
                        if (response.success) {
                            $btn.text('✓ Generated!');
                            setTimeout(function () { location.reload(); }, 1000);
                        } else {
                            $btn.text('Error').prop('disabled', false);
                            alert(response.data.message || 'Error');
                        }
                    });
                });
            });
        </script>
        <?php
    }

    public function on_save_post($post_id, $post)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!current_user_can('edit_post', $post_id))
            return;
    }
}

new earlystart_Schema_Editor_Metabox();
