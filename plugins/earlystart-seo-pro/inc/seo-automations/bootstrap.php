<?php
/**
 * SEO Automations - Bootstrap
 */

if (!defined('ABSPATH')) {
    exit;
}

// Global helper to load automation files
if (!function_exists('earlystart_seo_load_automation')) {
    function earlystart_seo_load_automation($file)
    {
        $path = __DIR__ . '/' . $file;
        if (file_exists($path)) {
            require_once $path;
            return true;
        }
        return false;
    }
}

// Load Automations
earlystart_seo_load_automation('class-entity-auto-linker.php');
earlystart_seo_load_automation('class-keyword-auto-linker.php');
earlystart_seo_load_automation('class-related-entities.php');

/**
 * Initialize Automations
 */
function earlystart_seo_automations_init()
{
    if (class_exists('earlystart_Entity_Auto_Linker')) {
        (new earlystart_Entity_Auto_Linker())->init();
    }
}
add_action('init', 'earlystart_seo_automations_init', 20);
