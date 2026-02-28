<?php
/**
 * Front Page Template (Homepage)
 * Uses hardcoded helpers for modular sections (ACF optional)
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

get_header();
?>

<!-- Hero Section -->
<?php get_template_part('template-parts/home/hero'); ?>

<!-- Trust Bar (Stats Strip) -->
<?php get_template_part('template-parts/home/stats-strip'); ?>

<!-- Services Tabs (Therapy in Action) -->
<?php get_template_part('template-parts/home/services-tabs'); ?>

<!-- Why Early Start (Bento Grid) -->
<?php get_template_part('template-parts/home/chroma-care-model'); ?>

<!-- Clinical Team Section (Removed for Launch) -->

<!-- FAQ Section -->
<?php get_template_part('template-parts/home/faq'); ?>

<!-- Locations Preview -->
<?php get_template_part('template-parts/home/locations-preview'); ?>

<!-- Tour CTA Section -->
<?php get_template_part('template-parts/home/tour-cta'); ?>

<?php get_footer(); ?>