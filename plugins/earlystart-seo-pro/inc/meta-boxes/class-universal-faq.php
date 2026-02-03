<?php
/**
 * Universal FAQ Meta Box
 * Allows adding FAQ items to any page for FAQPage schema
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Universal_FAQ extends earlystart_Advanced_SEO_Meta_Box_Base
{
    /**
     * Get the meta box ID
     *
     * @return string
     */
    public function get_id()
    {
        return 'earlystart_universal_faq';
    }

    /**
     * Get the meta box title
     *
     * @return string
     */
    public function get_title()
    {
        return __('FAQ Schema (LLM Optimized)', 'earlystart-excellence');
    }

    /**
     * Get post types this meta box applies to
     *
     * @return array
     */
    public function get_post_types()
    {
        return ['page', 'location', 'program', 'post', 'city'];
    }

    /**
     * Render the meta box fields
     *
     * @param WP_Post $post Current post object
     */
    public function render_fields($post)
    {
        $faqs = get_post_meta($post->ID, 'earlystart_faq_items', true);
        if (!is_array($faqs)) {
            $faqs = [];
        }
        ?>
        <div class="earlystart-field-wrapper">
            <p class="description">
                <?php _e('Add Questions and Answers here. They will be output as <strong>FAQPage Schema</strong>, which is highly visible to Google and LLMs.', 'earlystart-excellence'); ?>
            </p>

            <div class="earlystart-repeater-field">
                <div class="earlystart-repeater-items">
                    <?php if (!empty($faqs)): ?>
                        <?php foreach ($faqs as $faq): ?>
                            <div class="earlystart-repeater-item"
                                style="display:block; border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:10px;">
                                <div style="margin-bottom:5px;">
                                    <label><?php _e('Question:', 'earlystart-excellence'); ?></label>
                                    <input type="text" name="earlystart_faq_question[]" value="<?php echo esc_attr($faq['question']); ?>"
                                        class="widefat" />
                                </div>
                                <div style="display:flex; gap:10px; align-items:flex-start;">
                                    <div style="flex:1;">
                                        <label><?php _e('Answer:', 'earlystart-excellence'); ?></label>
                                        <textarea name="earlystart_faq_answer[]" rows="2"
                                            class="widefat"><?php echo esc_textarea($faq['answer']); ?></textarea>
                                    </div>
                                    <button class="button earlystart-remove-item" style="margin-top:20px;">&times;</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="earlystart-repeater-item"
                            style="display:block; border-bottom:1px solid #eee; padding-bottom:10px; margin-bottom:10px;">
                            <div style="margin-bottom:5px;">
                                <label><?php _e('Question:', 'earlystart-excellence'); ?></label>
                                <input type="text" name="earlystart_faq_question[]" class="widefat" />
                            </div>
                            <div style="display:flex; gap:10px; align-items:flex-start;">
                                <div style="flex:1;">
                                    <label><?php _e('Answer:', 'earlystart-excellence'); ?></label>
                                    <textarea name="earlystart_faq_answer[]" rows="2" class="widefat"></textarea>
                                </div>
                                <button class="button earlystart-remove-item" style="margin-top:20px;">&times;</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="button earlystart-add-item"><?php _e('Add Question', 'earlystart-excellence'); ?></button>
            </div>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                // Enhanced repeater for block-style items
                $('.earlystart-add-item').off('click').on('click', function (e) {
                    e.preventDefault();
                    var $wrapper = $(this).closest('.earlystart-repeater-field');
                    var $items = $wrapper.find('.earlystart-repeater-items');
                    var $clone = $items.find('.earlystart-repeater-item').first().clone();
                    $clone.find('input, textarea').val('');
                    $items.append($clone);
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
        if (isset($_POST['earlystart_faq_question']) && isset($_POST['earlystart_faq_answer'])) {
            $questions = $_POST['earlystart_faq_question'];
            $answers = $_POST['earlystart_faq_answer'];
            $faqs = [];

            for ($i = 0; $i < count($questions); $i++) {
                if (!empty($questions[$i]) && !empty($answers[$i])) {
                    $faqs[] = [
                        'question' => sanitize_text_field($questions[$i]),
                        'answer' => sanitize_textarea_field($answers[$i]),
                    ];
                }
            }

            update_post_meta($post_id, 'earlystart_faq_items', $faqs);
        } else {
            delete_post_meta($post_id, 'earlystart_faq_items');
        }
    }
}


