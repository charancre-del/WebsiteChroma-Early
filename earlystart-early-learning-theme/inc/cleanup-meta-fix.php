<?php
/**
 * Cleanup Meta Fix
 * Migrates "locked" page meta to Customizer Theme Mods, then deletes the meta.
 * This unlocks the Customizer for the user.
 */

$home_id = earlystart_get_home_page_id();

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
        'home_locations_cta_link' => 'earlystart_home_locations_cta_link', // Note: meta uses singular, mod uses singular? Check customizer.

        'home_faq_heading' => 'earlystart_home_faq_heading',
        'home_faq_subheading' => 'earlystart_home_faq_subheading',
    );

    foreach ($map as $meta_key => $mod_key) {
        $meta_val = get_post_meta($home_id, $meta_key, true);
        $mod_val = get_theme_mod($mod_key);

        // If meta exists...
        if (!empty($meta_val)) {
            // If theme mod is empty or default, OR if we want to ensure we don't lose the meta content:
            // Actually, if the user typed in Customizer, mod_val is NEWER.
            // But we can't easily know timestamp.
            // Assumption: User thinks Customizer is broken. The Preview shows META.
            // If they saved Customizer, MOD is set.
            // We should prioritizing keeping what they SEE? No, they said "not linked".
            // If they typed in Customizer, they want that.
            // So if Mod is set, keep Mod. If Mod is empty, copy Meta to Mod.

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
