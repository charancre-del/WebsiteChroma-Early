<?php
/**
 * Cleanup Meta Fix
 * Migrates "locked" page meta to Customizer Theme Mods, then deletes the meta.
 * This unlocks the Customizer for the user.
 */


add_action('init', function () {
    // Check if we have a static home page
    $home_id = get_option('page_on_front');

    if ($home_id) {
        // Array of keys to migrate: Meta Key => Theme Mod Key
        $map = array(
            'home_hero_heading' => 'earlystart_home_hero_heading',
            'home_hero_subheading' => 'earlystart_home_hero_subheading',
            'home_hero_cta_label' => 'earlystart_home_hero_cta_label',
            'home_hero_cta_url' => 'earlystart_home_hero_cta_url',
            'home_hero_secondary_label' => 'earlystart_home_hero_secondary_label',
            'home_hero_secondary_url' => 'earlystart_home_hero_secondary_url',

            'home_locations_heading' => 'earlystart_home_locations_heading',
            'home_locations_subheading' => 'earlystart_home_locations_subheading',
            'home_locations_cta_label' => 'earlystart_home_locations_cta_label',
            'home_locations_cta_link' => 'earlystart_home_locations_cta_link',

            'home_faq_heading' => 'earlystart_home_faq_heading',
            'home_faq_subheading' => 'earlystart_home_faq_subheading',
        );

        foreach ($map as $meta_key => $mod_key) {
            $meta_val = get_post_meta($home_id, $meta_key, true);
            $mod_val = get_theme_mod($mod_key);

            // If meta exists...
            if (!empty($meta_val)) {
                if (empty($mod_val)) {
                    set_theme_mod($mod_key, $meta_val);
                    error_log("EarlyStart Cleanup: Migrated $meta_key to $mod_key");
                } else {
                    error_log("EarlyStart Cleanup: Kept existing Mod for $mod_key, deleting meta override.");
                }

                // DELETE the meta to unlock Customizer
                delete_post_meta($home_id, $meta_key);
                error_log("EarlyStart Cleanup: Deleted meta $meta_key");
            }
        }
    }
});
