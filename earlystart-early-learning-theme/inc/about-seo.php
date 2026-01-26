<?php
/**
 * About page SEO helpers and meta fields
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
        exit;
}

/**
 * Determine if the current context is the About page template
 */
function earlystart_is_about_template($post_id = null)
{
        if (null === $post_id) {
                return is_page_template('page-about.php');
        }

        return 'page-about.php' === get_page_template_slug($post_id);
}

/**
 * Default SEO copy for the About page
 */
function earlystart_get_about_seo_defaults($post_id = null)
{
        $site_name = get_bloginfo('name');
        $about_url = $post_id ? get_permalink($post_id) : home_url('/about/');

        $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'AboutPage',
                'name' => sprintf('About %s', $site_name),
                'description' => 'Mission-led educators, family partnerships, and values that shape joyful early learning.',
                'url' => $about_url,
                'publisher' => array(
                        '@type' => 'Organization',
                        'name' => $site_name,
                        'url' => home_url('/'),
                ),
        );

        return array(
                'title' => sprintf('About %s | Our Story, Mission & Team', $site_name),
                'description' => 'Discover how Early Start nurtures whole-child growth through inspired educators, intentional classrooms, and a values-driven culture.',
                'structured_data' => wp_json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        );
}

/**
 * Retrieve About page SEO fields with defaults
 */
function earlystart_get_about_seo_fields($post_id = null)
{
        $post_id = $post_id ?: get_the_ID();
        $defaults = earlystart_get_about_seo_defaults($post_id);

        return array(
                'title' => earlystart_get_meta_value($post_id, 'about_meta_title', $defaults['title']),
                'description' => earlystart_get_meta_value($post_id, 'about_meta_description', $defaults['description']),
                'structured_data' => earlystart_get_meta_value($post_id, 'about_structured_data', $defaults['structured_data']),
        );
}

/**
 * Register About page SEO meta fields
 */
function earlystart_register_about_meta_fields()
{
        $meta_args = array(
                'type' => 'string',
                'single' => true,
                'show_in_rest' => true,
                'auth_callback' => function () {
                        return current_user_can('edit_pages');
                },
        );

        register_post_meta(
                'page',
                'about_meta_title',
                array_merge(
                        $meta_args,
                        array(
                                'sanitize_callback' => 'sanitize_text_field',
                        )
                )
        );

        register_post_meta(
                'page',
                'about_meta_description',
                array_merge(
                        $meta_args,
                        array(
                                'sanitize_callback' => 'sanitize_textarea_field',
                        )
                )
        );

        register_post_meta(
                'page',
                'about_structured_data',
                array_merge(
                        $meta_args,
                        array(
                                'sanitize_callback' => 'sanitize_textarea_field',
                        )
                )
        );
}
add_action('init', 'earlystart_register_about_meta_fields');

/**
 * Add meta box for About page SEO fields
 */
function earlystart_about_meta_box($post_type, $post)
{
        if ('page' !== $post_type || !earlystart_is_about_template($post->ID)) {
                return;
        }

        add_meta_box(
                'chroma-about-seo',
                __('About Page SEO', 'earlystart-early-learning'),
                'earlystart_render_about_meta_box',
                'page',
                'side',
                'default'
        );
}
add_action('add_meta_boxes', 'earlystart_about_meta_box', 10, 2);

/**
 * Render About page SEO meta box
 */
function earlystart_render_about_meta_box($post)
{
        wp_nonce_field('earlystart_about_meta_nonce', 'earlystart_about_meta_nonce_field');

        $defaults = earlystart_get_about_seo_defaults($post->ID);
        $meta_title = get_post_meta($post->ID, 'about_meta_title', true);
        $meta_description = get_post_meta($post->ID, 'about_meta_description', true);
        $structured_data = get_post_meta($post->ID, 'about_structured_data', true);

        if ('' === $meta_title) {
                $meta_title = $defaults['title'];
        }

        if ('' === $meta_description) {
                $meta_description = $defaults['description'];
        }

        if ('' === $structured_data) {
                $structured_data = $defaults['structured_data'];
        }
        ?>
        <p>
                <label for="about_meta_title"
                        class="screen-reader-text"><?php esc_html_e('SEO Title', 'earlystart-early-learning'); ?></label>
                <input type="text" id="about_meta_title" name="about_meta_title" value="<?php echo esc_attr($meta_title); ?>"
                        class="widefat" />
                <small><?php esc_html_e('Custom title tag shown in search results and browser tabs.', 'earlystart-early-learning'); ?></small>
        </p>
        <p>
                <label for="about_meta_description"
                        class="screen-reader-text"><?php esc_html_e('Meta Description', 'earlystart-early-learning'); ?></label>
                <textarea id="about_meta_description" name="about_meta_description" class="widefat"
                        rows="3"><?php echo esc_textarea($meta_description); ?></textarea>
                <small><?php esc_html_e('1–2 sentence summary for search snippets.', 'earlystart-early-learning'); ?></small>
        </p>
        <p>
                <label for="about_structured_data"
                        class="screen-reader-text"><?php esc_html_e('Structured Data JSON-LD', 'earlystart-early-learning'); ?></label>
                <textarea id="about_structured_data" name="about_structured_data" class="widefat"
                        rows="6"><?php echo esc_textarea($structured_data); ?></textarea>
                <small><?php esc_html_e('Optional JSON-LD tailored to the About page.', 'earlystart-early-learning'); ?></small>
        </p>
        <?php
}

/**
 * Save About page SEO meta fields
 */
function earlystart_save_about_meta_box($post_id)
{
        if (!isset($_POST['earlystart_about_meta_nonce_field']) || !wp_verify_nonce(wp_unslash($_POST['earlystart_about_meta_nonce_field']), 'earlystart_about_meta_nonce')) {
                return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
        }

        if (isset($_POST['post_type']) && 'page' === $_POST['post_type']) {
                if (!current_user_can('edit_page', $post_id)) {
                        return;
                }
        }

        if (!earlystart_is_about_template($post_id)) {
                return;
        }

        $meta_title = isset($_POST['about_meta_title']) ? sanitize_text_field(wp_unslash($_POST['about_meta_title'])) : '';
        $meta_description = isset($_POST['about_meta_description']) ? sanitize_textarea_field(wp_unslash($_POST['about_meta_description'])) : '';
        $structured_data = isset($_POST['about_structured_data']) ? sanitize_textarea_field(wp_unslash($_POST['about_structured_data'])) : '';

        update_post_meta($post_id, 'about_meta_title', $meta_title);
        update_post_meta($post_id, 'about_meta_description', $meta_description);
        update_post_meta($post_id, 'about_structured_data', $structured_data);
}
add_action('save_post_page', 'earlystart_save_about_meta_box');

/**
 * Surface About page SEO data in the document head
 */
function earlystart_about_meta_output()
{
        if (!earlystart_is_about_template()) {
                return;
        }

        $seo_fields = earlystart_get_about_seo_fields();



        if ($seo_fields['structured_data']) {
                echo '<script type="application/ld+json">' . wp_kses($seo_fields['structured_data'], array()) . '</script>' . "\n";
        }
}
// DISABLED: Schema now handled by Early Start SEO Pro plugin
// add_action('wp_head', 'earlystart_about_meta_output', 0);

/**
 * Filter the document title for the About page
 */
function earlystart_about_document_title($title)
{
        if (earlystart_is_about_template()) {
                $seo_fields = earlystart_get_about_seo_fields();

                if ($seo_fields['title']) {
                        return $seo_fields['title'];
                }
        }

        return $title;
}
add_filter('pre_get_document_title', 'earlystart_about_document_title');
add_filter('wpseo_title', 'earlystart_about_document_title');

/**
 * Filter meta description for SEO plugins
 */
function earlystart_about_meta_description_filter($description)
{
        if (earlystart_is_about_template()) {
                $seo_fields = earlystart_get_about_seo_fields();

                if ($seo_fields['description']) {
                        return $seo_fields['description'];
                }
        }

        return $description;
}
add_filter('wpseo_metadesc', 'earlystart_about_meta_description_filter');


