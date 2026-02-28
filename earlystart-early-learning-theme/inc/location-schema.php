<?php
/**
 * LocalBusiness JSON-LD Schema for Locations
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
    exit;
}

function earlystart_output_location_schema()
{
    if (!is_singular('location')) {
        return;
    }

    global $post;

    $name = get_the_title($post->ID);
    $address = get_post_meta($post->ID, 'location_address', true);
    $city = get_post_meta($post->ID, 'location_city', true);
    $state = get_post_meta($post->ID, 'location_state', true);
    $zip = get_post_meta($post->ID, 'location_zip', true);
    $phone = get_post_meta($post->ID, 'location_phone', true);
    $latitude = get_post_meta($post->ID, 'location_latitude', true);
    $longitude = get_post_meta($post->ID, 'location_longitude', true);
    $image = get_the_post_thumbnail_url($post->ID, 'full');
    $url = get_permalink($post->ID);

    // Build Schema Array
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'ChildCare',
        'name' => 'Chroma Early Start - ' . esc_html($name),
        'url' => esc_url($url),
    );

    if ($image) {
        $schema['image'] = esc_url($image);
    }

    if ($phone) {
        $schema['telephone'] = esc_html($phone);
    }

    if ($address && $city && $state && $zip) {
        $schema['address'] = array(
            '@type' => 'PostalAddress',
            'streetAddress' => esc_html($address),
            'addressLocality' => esc_html($city),
            'addressRegion' => esc_html($state),
            'postalCode' => esc_html($zip),
            'addressCountry' => 'US'
        );
    }

    if ($latitude && $longitude) {
        $schema['geo'] = array(
            '@type' => 'GeoCoordinates',
            'latitude' => esc_html($latitude),
            'longitude' => esc_html($longitude)
        );
    }

    echo "<!-- LocalBusiness JSON-LD -->\n";
    echo "<script type=\"application/ld+json\">\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n";
    echo "</script>\n";
}
add_action('wp_head', 'earlystart_output_location_schema');
