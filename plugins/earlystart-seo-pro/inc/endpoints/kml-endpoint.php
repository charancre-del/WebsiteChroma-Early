<?php
/**
 * KML Endpoint
 * Generates a KML file for all locations
 *
 * @package earlystart_Excellence
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class earlystart_KML_Endpoint
{
    /**
     * Initialize
     */
    public static function init()
    {
        add_action('init', [__CLASS__, 'add_rewrite_rule']);
        add_filter('query_vars', [__CLASS__, 'add_query_vars']);
        add_action('template_redirect', [__CLASS__, 'render_kml']);
    }

    /**
     * Add rewrite rule for /locations.kml
     */
    public static function add_rewrite_rule()
    {
        add_rewrite_rule('^locations\.kml$', 'index.php?earlystart_kml=1', 'top');
        add_rewrite_tag('%earlystart_kml%', '([^&]+)');
    }

    /**
     * Register query vars for hosts where rewrite tags are not persisted yet.
     */
    public static function add_query_vars($vars)
    {
        $vars[] = 'earlystart_kml';
        return $vars;
    }

    /**
     * Determine whether the current request is for the KML feed.
     */
    private static function is_kml_request()
    {
        if (get_query_var('earlystart_kml')) {
            return true;
        }

        $path = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
        $path = parse_url($path, PHP_URL_PATH);

        return is_string($path) && trim($path, '/') === 'locations.kml';
    }

    /**
     * Render KML
     */
    public static function render_kml()
    {
        if (self::is_kml_request()) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            status_header(200);
            nocache_headers();
            header('Content-Type: application/vnd.google-earth.kml+xml; charset=UTF-8');
            header('Content-Disposition: attachment; filename="locations.kml"');
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            ?>
            <kml xmlns="http://www.opengis.net/kml/2.2">
                <Document>
                    <name><?php echo esc_html(get_bloginfo('name')); ?> Locations</name>
                    <description>Locations for <?php echo esc_html(get_bloginfo('name')); ?></description>
                    <?php
                    $locations = get_posts([
                        'post_type' => 'location',
                        'posts_per_page' => -1,
                        'post_status' => 'publish'
                    ]);

                    foreach ($locations as $location) {
                        $lat = get_post_meta($location->ID, 'location_latitude', true);
                        $lng = get_post_meta($location->ID, 'location_longitude', true);
                        $address = get_post_meta($location->ID, 'location_address', true);
                        $city = get_post_meta($location->ID, 'location_city', true);
                        $state = get_post_meta($location->ID, 'location_state', true);
                        $zip = get_post_meta($location->ID, 'location_zip', true);
                        $phone = get_post_meta($location->ID, 'location_phone', true);

                        if ($lat && $lng) {
                            $lat = (float) $lat;
                            $lng = (float) $lng;
                            ?>
                            <Placemark>
                                <name><?php echo esc_html($location->post_title); ?></name>
                                <description>
                                    <![CDATA[
                                    <p><strong>Address:</strong> <?php echo esc_html("$address, $city, $state $zip"); ?></p>
                                    <p><strong>Phone:</strong> <?php echo esc_html($phone); ?></p>
                                    <p><a href="<?php echo esc_url(get_permalink($location->ID)); ?>">View Details</a></p>
                                    ]]>
                                </description>
                                <Point>
                                    <coordinates><?php echo esc_html($lng . ',' . $lat . ',0'); ?></coordinates>
                                </Point>
                            </Placemark>
                            <?php
                        } elseif ($address) {
                            // Fallback to address if coordinates are missing
                            ?>
                            <Placemark>
                                <name><?php echo esc_html($location->post_title); ?></name>
                                <description>
                                    <![CDATA[
                                    <p><strong>Address:</strong> <?php echo esc_html("$address, $city, $state $zip"); ?></p>
                                    <p><strong>Phone:</strong> <?php echo esc_html($phone); ?></p>
                                    <p><a href="<?php echo esc_url(get_permalink($location->ID)); ?>">View Details</a></p>
                                    <p><em>(Coordinates missing, using address)</em></p>
                                    ]]>
                                </description>
                                <address><?php echo esc_html("$address, $city, $state $zip"); ?></address>
                            </Placemark>
                            <?php
                        }
                    }
                    ?>
                </Document>
            </kml>
            <?php
            exit;
        }
    }
}
earlystart_KML_Endpoint::init();


