<?php
/**
 * Force Content Update for FAQ and Locations
 * Run once then disable.
 */

// 1. Update Locations Subheading
$locations_key = 'earlystart_home_locations_preview_json';
$locations_raw = get_theme_mod($locations_key);
$locations_data = json_decode($locations_raw, true);

// If no data exists, pull default
if (empty($locations_data) || !is_array($locations_data)) {
    $locations_data = earlystart_home_default_locations_preview();
}

// FORCE the update to subheading
$new_suffix = "Serving families At School, In Home and At Clinics.";
// Check if already ends with this to avoid duplicates
if (strpos($locations_data['subheading'], 'At School') === false) {
    // Remove trailing dot if exists
    $base_text = rtrim($locations_data['subheading'], '.');
    // Append new text
    $locations_data['subheading'] = $base_text . ". " . $new_suffix;

    // Save back
    set_theme_mod($locations_key, json_encode($locations_data));
    error_log("EarlyStart Update: Updated Locations Subheading.");
}

// 2. Update FAQ
$faq_key = 'earlystart_home_faq_json';
$faq_raw = get_theme_mod($faq_key);
$faq_data = json_decode($faq_raw, true);

if (empty($faq_data) || !is_array($faq_data)) {
    $faq_data = earlystart_home_default_faq();
}

// Check if new question exists
$new_question_text = "Where do you provide services?";
$exists = false;
foreach ($faq_data['items'] as $item) {
    if ($item['question'] === $new_question_text) {
        $exists = true;
        break;
    }
}

if (!$exists) {
    // Insert after the first item (optional placement, or just append)
    // Let's Insert at index 1 (after 1st question)
    $new_item = array(
        'question' => $new_question_text,
        'answer' => "We provide services At School, In Home and At Clinics to ensure your child receives support in the environment that works best for them.",
    );

    // Splice it in or just append? Let's just append to be safe from index issues
    $faq_data['items'][] = $new_item;

    // Save back
    set_theme_mod($faq_key, json_encode($faq_data));
    error_log("EarlyStart Update: Added FAQ item.");
}
