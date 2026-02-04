<?php
/**
 * Force Content Update v2 - Fix Locations Text
 * Run once then disable.
 */

// 1. Update Locations Subheading (Correct Key)
$subheading_key = 'earlystart_home_locations_subheading';
$current_subheading = get_theme_mod($subheading_key);

// Default fallback if empty
if (empty($current_subheading)) {
    $current_subheading = 'Find your nearest clinical center and join our community of growth.';
}

$new_suffix = "Serving families At School, In Home and At Clinics.";

// Check if already contains the text
if (strpos($current_subheading, 'At School') === false) {
    // Remove trailing dot if exists
    $base_text = rtrim($current_subheading, '.');
    // Append new text
    $new_subheading = $base_text . ". " . $new_suffix;

    // Save to the CORRECT key
    set_theme_mod($subheading_key, $new_subheading);
    error_log("EarlyStart Update v2: Updated Locations Subheading to: " . $new_subheading);
} else {
    error_log("EarlyStart Update v2: Locations Subheading already updated.");
}

// 2. Ensure Heading is also set correctly if needed (Optional, but safe)
$heading_key = 'earlystart_home_locations_heading';
$current_heading = get_theme_mod($heading_key);
if (empty($current_heading)) {
    set_theme_mod($heading_key, 'Serving Families Across <span class="italic text-rose-700">The Metro</span>');
}
