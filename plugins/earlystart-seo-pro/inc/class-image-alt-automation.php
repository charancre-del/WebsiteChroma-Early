<?php
/**
 * Image Alt Automation
 */

if (!defined('ABSPATH')) {
    exit;
}

class earlystart_Image_Alt_Automation
{
    public function init()
    {
        add_filter('wp_get_attachment_image_attributes', [$this, 'auto_generate_alt'], 10, 2);
    }

    public function auto_generate_alt($attr, $attachment)
    {
        if (empty($attr['alt'])) {
            $post_title = get_the_title($attachment->post_parent);
            if ($post_title) {
                $attr['alt'] = $post_title;
            }
        }
        return $attr;
    }
}
