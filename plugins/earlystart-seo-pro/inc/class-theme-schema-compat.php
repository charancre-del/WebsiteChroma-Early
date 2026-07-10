<?php
/**
 * Theme Schema Compatibility Layer
 * 
 * Contains schema functions migrated from theme's seo-engine.php.
 * These functions output schema for Location, City, Program, and general content pages.
 *
 * @package earlystart_SEO_Pro
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('earlystart_theme_schema_compat_enabled')) {
    /**
     * Schema compat output is enabled only when plugin owns canonical/schema.
     *
     * @return bool
     */
    function earlystart_theme_schema_compat_enabled()
    {
        if (!function_exists('earlystart_seo_plugin_owns_canonical_schema')) {
            return true;
        }

        return earlystart_seo_plugin_owns_canonical_schema();
    }
}

/**
 * Safely output schema override JSON-LD.
 *
 * Accepts raw JSON or legacy <script type="application/ld+json"> wrappers.
 * Never echoes raw HTML directly.
 *
 * @param int    $post_id Post ID.
 * @param string $source  Source label for registry debug.
 * @return bool
 */
if (!function_exists('earlystart_output_schema_override_pro')) {
    function earlystart_output_schema_override_pro($post_id, $source = 'theme-compat-override')
    {
        $override = get_post_meta($post_id, '_earlystart_schema_override', true);
        if (!is_string($override) || trim($override) === '') {
            return false;
        }

        $raw = trim(wp_unslash($override));

        // Backward compatibility: allow stored script wrapper, but parse JSON only.
        if (stripos($raw, '<script') !== false) {
            if (preg_match('/<script[^>]*application\/ld\+json[^>]*>(.*?)<\/script>/is', $raw, $matches)) {
                $raw = trim($matches[1]);
            } else {
                return false;
            }
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return false;
        }

        $items = isset($decoded[0]) && is_array($decoded[0]) ? $decoded : [$decoded];

        foreach ($items as $schema_item) {
            if (!is_array($schema_item) || empty($schema_item)) {
                continue;
            }

            if (!isset($schema_item['@context'])) {
                $schema_item['@context'] = 'https://schema.org';
            }

            if (class_exists('earlystart_Schema_Registry') && isset($schema_item['@type'])) {
                earlystart_Schema_Registry::register($schema_item, ['source' => $source]);
                continue;
            }

            echo '<script type="application/ld+json">' . wp_json_encode($schema_item, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
        }

        return true;
    }
}

/**
 * Global Schema Override Handler (for standard pages/posts)
 * Hooks early to catch generic pages that have manual fixes
 */
if (!function_exists('earlystart_general_content_schema_pro')) {
    function earlystart_general_content_schema_pro()
    {
        if (is_singular('location') || is_singular('program') || is_singular('city') || is_front_page()) {
            return;
        }

        $post_id = get_the_ID();
        if (!$post_id)
            return;

        if (earlystart_output_schema_override_pro($post_id, 'theme-compat-override')) {
            return;
        }
    }
}
if (earlystart_theme_schema_compat_enabled()) {
    add_action('wp_head', 'earlystart_general_content_schema_pro', 1);
}

/**
 * Helper: Get Schema Value (English or Spanish)
 */
function earlystart_get_schema_val($post_id, $es_meta_key, $en_val)
{
    if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) {
        $es_val = get_post_meta($post_id, $es_meta_key, true);
        return $es_val ?: $en_val;
    }
    return $en_val;
}

/**
 * Add LocalBusiness Schema to Location Pages
 */
if (!function_exists('earlystart_location_schema_pro')) {
    function earlystart_location_schema_pro()
    {
        if (!is_singular('location')) {
            return;
        }

        $location_id = get_the_ID();

        // Check for manual override first
        if (earlystart_output_schema_override_pro($location_id, 'theme-compat-location-override')) {
            return;
        }

        // Check for Builder Schema (Sprint 9 Fix: Suppress auto-generated if user has built schema)
        $builder_schemas = get_post_meta($location_id, '_earlystart_post_schemas', true);
        if (!empty($builder_schemas) && is_array($builder_schemas)) {
            return;
        }

        // Ensure Advanced SEO classes are available
        if (!class_exists('earlystart_Fallback_Resolver')) {
            return;
        }

        // Get location fields (theme helper function)
        if (!function_exists('earlystart_get_location_fields')) {
            return;
        }

        $location_fields = earlystart_get_location_fields($location_id);
        $service_area = earlystart_Fallback_Resolver::get_service_area_circle($location_id);

        // Meta Fields
        $en_name = get_post_meta($location_id, 'schema_loc_name', true) ?: get_the_title();
        $name = earlystart_get_schema_val($location_id, '_earlystart_es_title', $en_name);

        $en_desc = get_post_meta($location_id, 'schema_loc_description', true) ?: get_the_excerpt();
        $description = earlystart_get_schema_val($location_id, '_earlystart_es_excerpt', $en_desc);

        $telephone = get_post_meta($location_id, 'schema_loc_telephone', true) ?: ($location_fields['phone'] ?? '');
        $email = get_post_meta($location_id, 'schema_loc_email', true) ?: ($location_fields['email'] ?? '');
        $opening_hours_raw = get_post_meta($location_id, 'schema_loc_opening_hours', true) ?: ($location_fields['hours'] ?? '');
        $payment = get_post_meta($location_id, 'schema_loc_payment_accepted', true);

        // Address Fields (Localized)
        $street = earlystart_get_schema_val($location_id, '_earlystart_es_location_address', ($location_fields['address'] ?? ''));
        $city = earlystart_get_schema_val($location_id, '_earlystart_es_location_city', ($location_fields['city'] ?? ''));

        $price_range = get_post_meta($location_id, 'seo_llm_price_min', true);
        $quality_rated = get_post_meta($location_id, 'location_quality_rated', true);

        // Price Range Formatting
        if ($price_range) {
            $price_max = get_post_meta($location_id, 'seo_llm_price_max', true);
            $currency = get_post_meta($location_id, 'seo_llm_price_currency', true) ?: 'USD';
            $frequency = get_post_meta($location_id, 'seo_llm_price_frequency', true) ?: 'week';

            // Localize frequency label
            $freq_label = $frequency;
            if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) {
                $freq_map = ['week' => 'semana', 'month' => 'mes', 'year' => 'año'];
                $freq_label = $freq_map[$frequency] ?? $frequency;
                $price_range = "$currency $price_range" . ($price_max ? "-$price_max" : "") . " por $freq_label";
            } else {
                $price_range = "$currency $price_range" . ($price_max ? "-$price_max" : "") . " per $freq_label";
            }
        } else {
            $price_range = get_post_meta($location_id, 'schema_loc_price_range', true) ?: '$$';
        }

        // Schema Construction
        $types = array('MedicalBusiness', 'LocalBusiness');

        if (get_post_meta($location_id, '_earlystart_is_event_venue', true)) {
            $types[] = 'EventVenue';
        }

        // Get logo from theme or plugin setting
        $logo = '';
        if (function_exists('earlystart_get_global_setting')) {
            $logo = earlystart_get_global_setting('global_logo', '');
        }

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => $types,
            '@id' => get_permalink() . '#organization',
            'name' => $name,
            'description' => $description,
            'url' => get_permalink(), // URL is automatically filtered by Multilingual Manager? Yes.
            'image' => get_the_post_thumbnail_url($location_id, 'full'),
            'logo' => $logo,
            'telephone' => $telephone,
            'email' => $email,
            'priceRange' => $price_range,
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => $street,
                'addressLocality' => $city,
                'addressRegion' => $location_fields['state'] ?? '',
                'postalCode' => $location_fields['zip'] ?? '',
                'addressCountry' => 'US'
            ),
            'medicalSpecialty' => array(
                'Autism assessment',
                'Behavioral assessment',
                'ADHD assessment',
                'OT assessment',
                'https://schema.org/SpeechPathology',
                'Occupational Therapy',
                'Applied Behavior Analysis (ABA) therapy',
            ),
            'availableService' => function_exists('earlystart_seo_all_service_schemas') ? earlystart_seo_all_service_schemas([
                'provider' => [
                    '@type' => 'MedicalBusiness',
                    'name' => $name,
                    'url' => get_permalink($location_id),
                ],
            ]) : array(),
        );

        // Social Profiles
        $socials = array();
        if (function_exists('earlystart_global_facebook_url'))
            $socials[] = earlystart_global_facebook_url();
        if (function_exists('earlystart_global_instagram_url'))
            $socials[] = earlystart_global_instagram_url();
        if (function_exists('earlystart_global_linkedin_url'))
            $socials[] = earlystart_global_linkedin_url();
        $socials = array_filter($socials);
        if (!empty($socials)) {
            $schema['sameAs'] = array_values($socials);
        }

        // Geo Coordinates
        $area_served = array();
        if ($service_area) {
            $schema['geo'] = array(
                '@type' => 'GeoCoordinates',
                'latitude' => $service_area['lat'],
                'longitude' => $service_area['lng'],
            );
            $area_served[] = array(
                '@type' => 'GeoCircle',
                'geoMidpoint' => $schema['geo'],
                'geoRadius' => ($service_area['radius'] * 1609.34)
            );
        } elseif (!empty($location_fields['latitude']) && !empty($location_fields['longitude'])) {
            $schema['geo'] = array(
                '@type' => 'GeoCoordinates',
                'latitude' => $location_fields['latitude'],
                'longitude' => $location_fields['longitude'],
            );
        }

        if (!empty($area_served)) {
            $schema['areaServed'] = $area_served;
        }

        // Google Maps CID
        $cid = get_post_meta($location_id, '_earlystart_google_maps_cid', true);
        if ($cid) {
            $schema['hasMap'] = "https://www.google.com/maps?cid=$cid";
        } elseif (!empty($location_fields['map_link'])) {
            $schema['hasMap'] = $location_fields['map_link'];
        }

        // Opening Hours
        if ($opening_hours_raw) {
            $hours_lines = explode("\n", $opening_hours_raw);
            $specs = array();
            foreach ($hours_lines as $line) {
                if (preg_match('/(\d{1,2}(?::\d{2})?\s*[ap]m)\s*-\s*(\d{1,2}(?::\d{2})?\s*[ap]m)/i', $line, $matches)) {
                    $opens = date("H:i", strtotime($matches[1]));
                    $closes = date("H:i", strtotime($matches[2]));
                    $specs[] = array(
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
                        'opens' => $opens,
                        'closes' => $closes
                    );
                }
            }
            if (!empty($specs)) {
                $schema['openingHoursSpecification'] = $specs;
            } else {
                $schema['openingHours'] = $opening_hours_raw;
            }
        }

        // Amenities & Credentials
        $schema['amenityFeature'] = array();

        $license_num = '';
        if ($license_num) {
            $schema['hasCredential'] = array(
                '@type' => 'EducationalOccupationalCredential',
                'credentialCategory' => 'license',
                'name' => (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) ? 'Credencial interna de clinica' : 'Internal Clinic Credential',
                'identifier' => array(
                    '@type' => 'PropertyValue',
                    'propertyID' => (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) ? 'Número de Licencia' : 'License Number',
                    'value' => $license_num
                ),
            );
        }

        $amenities = get_post_meta($location_id, '_earlystart_amenities', true);
        if (is_array($amenities) && !empty($amenities)) {
            foreach ($amenities as $amenity) {
                $schema['amenityFeature'][] = array(
                    '@type' => 'LocationFeatureSpecification',
                    'name' => $amenity,
                    'value' => true
                );
            }
        }

        if (false && $quality_rated) {
            $schema['amenityFeature'][] = array(
                '@type' => 'LocationFeatureSpecification',
                'name' => (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) ? 'Calificación de Calidad' : 'Clinic Quality Reviewed',
                'value' => true
            );
        }

        if (empty($schema['amenityFeature'])) {
            unset($schema['amenityFeature']);
        }

        // Director
        $director_name = get_post_meta($location_id, 'location_director_name', true);
        if ($director_name) {
            $director_bio = get_post_meta($location_id, 'location_director_bio', true);
            $director_photo = get_post_meta($location_id, 'location_director_photo', true);

            $schema['employee'] = array(
                '@type' => 'Person',
                'name' => $director_name,
                'jobTitle' => (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) ? 'Director clinico' : 'Clinical Director',
                'description' => $director_bio ? wp_strip_all_tags($director_bio) : ''
            );
            if ($director_photo) {
                $schema['employee']['image'] = $director_photo;
            }
        }

        // Reviews
        $rating_value = get_post_meta($location_id, 'seo_llm_aggregate_rating_value', true)
            ?: get_post_meta($location_id, 'seo_llm_rating_value', true)
            ?: get_post_meta($location_id, 'location_google_rating', true);
        $rating_count = get_post_meta($location_id, 'seo_llm_aggregate_rating_count', true)
            ?: get_post_meta($location_id, 'seo_llm_rating_count', true);
        $rating_best = get_post_meta($location_id, 'seo_llm_aggregate_rating_best', true) ?: '5';
        $rating_worst = get_post_meta($location_id, 'seo_llm_aggregate_rating_worst', true) ?: '1';

        if ($rating_value) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $rating_value,
                'reviewCount' => $rating_count ?: '1',
                'bestRating' => $rating_best,
                'worstRating' => $rating_worst
            );
        }

        if ($payment) {
            $schema['paymentAccepted'] = $payment;
        }

        // Use Schema Registry for deduplication if available
        if (class_exists('earlystart_Schema_Registry')) {
            $schema['@context'] = 'https://schema.org';
            earlystart_Schema_Registry::register($schema, ['source' => 'location_schema_pro']);
        } else {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }
    }
}
if (earlystart_theme_schema_compat_enabled()) {
    add_action('wp_head', 'earlystart_location_schema_pro');
}

/**
 * Add Service Schema to City Pages
 */
if (!function_exists('earlystart_city_schema_pro')) {
    function earlystart_city_schema_pro()
    {
        if (!is_singular('city')) {
            return;
        }

        $post_id = get_the_ID();

        // Check for manual override
        if (earlystart_output_schema_override_pro($post_id, 'theme-compat-city-override')) {
            return;
        }

        // Check for Builder Schema (Sprint 9 Fix: Suppress auto-generated duplicates)
        $builder_schemas = get_post_meta($post_id, '_earlystart_post_schemas', true);
        if (!empty($builder_schemas) && is_array($builder_schemas)) {
            return;
        }

        $en_name = get_the_title();
        $city_name = earlystart_get_schema_val($post_id, '_earlystart_es_title', $en_name);

        $en_desc = get_the_excerpt() ?: "Evidence-based pediatric therapy services in $en_name, GA, including autism assessment, behavioral assessment, ADHD assessment, OT assessment, ABA therapy, speech therapy, and occupational therapy.";
        $desc = earlystart_get_schema_val($post_id, '_earlystart_es_excerpt', $en_desc);

        // Localize English Fallback if needed
        if ((class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) && !$desc) {
            $desc = "Servicios de terapia pediatrica en $city_name, GA, incluyendo ABA, habla y terapia ocupacional.";
        }

        $service_name = "Pediatric Therapy in $city_name";
        if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) {
            $service_name = "Terapia pediatrica en $city_name";
        }

        $location_ids = get_post_meta($post_id, 'city_nearby_locations', true);

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service_name,
            'serviceType' => 'Pediatric Therapy',
            'provider' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url()
            ),
            'areaServed' => array(
                '@type' => 'City',
                'name' => $city_name
            ),
            'description' => $desc,
            'url' => get_permalink(),
            'hasOfferCatalog' => array(
                '@type' => 'OfferCatalog',
                'name' => "Autism assessment, behavioral assessment, ADHD assessment, OT assessment, ABA, speech, and OT services in $city_name",
                'itemListElement' => function_exists('earlystart_seo_all_service_schemas') ? earlystart_seo_all_service_schemas() : array(),
            ),
        );

        if (!empty($location_ids) && is_array($location_ids)) {
            $offers = array();
            foreach ($location_ids as $loc_id) {
                $loc_name = earlystart_get_schema_val($loc_id, '_earlystart_es_title', get_the_title($loc_id));
                $offers[] = array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'MedicalBusiness',
                        'name' => $loc_name,
                        'url' => get_permalink($loc_id)
                    )
                );
            }
            $catalog_name = "Clinics serving $city_name";
            if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) {
                $catalog_name = "Clinicas que sirven a $city_name";
            }

            $schema['hasOfferCatalog'] = array(
                '@type' => 'OfferCatalog',
                'name' => $catalog_name,
                'itemListElement' => $offers
            );
        }

        earlystart_Schema_Registry::register($schema, ['source' => 'theme-compat-city']);
    }
}
if (earlystart_theme_schema_compat_enabled()) {
    add_action('wp_head', 'earlystart_city_schema_pro');
}

/**
 * Add Service Schema to Program Pages
 */
if (!function_exists('earlystart_program_schema_pro')) {
    function earlystart_program_schema_pro()
    {
        if (!is_singular('program')) {
            return;
        }

        $program_id = get_the_ID();

        if (earlystart_output_schema_override_pro($program_id, 'theme-compat-program-override')) {
            return;
        }

        // Check for Builder Schema (Sprint 9 Fix: Suppress auto-generated duplicates)
        $builder_schemas = get_post_meta($program_id, '_earlystart_post_schemas', true);
        if (!empty($builder_schemas) && is_array($builder_schemas)) {
            return;
        }

        $en_name = get_post_meta($program_id, 'schema_prog_name', true) ?: get_the_title();
        $name = earlystart_get_schema_val($program_id, '_earlystart_es_title', $en_name);

        $en_desc = get_post_meta($program_id, 'schema_prog_description', true) ?: get_the_excerpt();
        $description = earlystart_get_schema_val($program_id, '_earlystart_es_excerpt', $en_desc);

        $detected_line = function_exists('earlystart_seo_detect_service_line') ? earlystart_seo_detect_service_line($program_id) : '';
        $line = $detected_line && function_exists('earlystart_seo_get_service_line') ? earlystart_seo_get_service_line($detected_line) : null;
        $service_type = get_post_meta($program_id, 'schema_prog_service_type', true) ?: ($line['service_type'] ?? 'Pediatric Therapy Program');
        if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish() && $service_type === 'Pediatric Therapy Program') {
            $service_type = 'Programa de terapia pediatrica';
        }

        $provider_name = get_post_meta($program_id, 'schema_prog_provider_name', true) ?: get_bloginfo('name');

        $area_served = get_post_meta($program_id, 'schema_prog_area_served', true) ?: 'Metro Atlanta';
        if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish() && $area_served === 'Metro Atlanta') {
            $area_served = 'Área Metropolitana de Atlanta';
        }
        $category = get_post_meta($program_id, 'schema_prog_category', true) ?: ($line['category'] ?? '');

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => $line['schema_type'] ?? 'Service',
            'name' => $name,
            'description' => $description,
            'url' => get_permalink(),
            'provider' => array(
                '@type' => 'Organization',
                'name' => $provider_name,
            ),
            'serviceType' => $service_type,
            'areaServed' => $area_served,
        );

        if ($category) {
            $schema['category'] = $category;
        }

        if (!empty($line['medical_specialty'])) {
            $schema['medicalSpecialty'] = $line['medical_specialty'];
        }

        earlystart_Schema_Registry::register($schema, ['source' => 'theme-compat-program']);
    }
}
/**
 * Add FAQPage Schema to City Pages
 * Generates common FAQ questions about pediatric therapy in the specific city
 */
if (!function_exists('earlystart_city_faq_schema_output')) {
    function earlystart_city_faq_schema_output()
    {
        if (!is_singular('city')) {
            return;
        }

        // Check for manual override
        $override = get_post_meta(get_the_ID(), '_earlystart_schema_override', true);
        if ($override) {
            return;
        }

        // Check for Builder Schema (Sprint 9 Fix: Suppress auto-generated duplicates)
        $builder_schemas = get_post_meta(get_the_ID(), '_earlystart_post_schemas', true);
        if (!empty($builder_schemas) && is_array($builder_schemas)) {
            return;
        }

        // Internal Duplicate Suppression
        global $earlystart_faq_output_done;
        if (!empty($earlystart_faq_output_done)) {
            return;
        }

        $city = get_the_title();
        $county = get_post_meta(get_the_ID(), 'city_county', true) ?: 'Local';

        // Questions and Answers
        $faq_items = array(
            array(
                'question' => "Do you offer ABA therapy in $city?",
                'answer' => "Yes. Families in $city can access ABA therapy through nearby Chroma Early Start clinics when it is clinically appropriate."
            ),
            array(
                'question' => "Can families in $city access nearby therapy services?",
                'answer' => "Our intake team can help families in $city identify nearby clinics, service availability, and next steps."
            ),
            array(
                'question' => "What ages do you accept at your $city centers?",
                'answer' => "We serve children through pediatric therapy programs based on age, developmental needs, goals, and service availability."
            ),
            array(
                'question' => "How do I start care for my child in $city?",
                'answer' => "The best way to start is to contact our intake team. We will review your needs, scheduling preferences, and nearby clinic options."
            ),
        );
        // Spanish output is handled by the multilingual manager sanitizer.

        $faq_items = array(
            array(
                'question' => "Do you offer ABA therapy in $city?",
                'answer' => "Yes. Families in $city can access ABA therapy through nearby Chroma Early Start clinics when it is clinically appropriate for their child."
            ),
            array(
                'question' => "Can families in $city access speech therapy?",
                'answer' => "Yes. Our intake team can help determine speech therapy availability and match your family with the best nearby clinic or service option."
            ),
            array(
                'question' => "Do you provide occupational therapy for children near $city?",
                'answer' => "Yes. Occupational therapy may support sensory processing, motor skills, self-care routines, and participation in daily activities."
            ),
            array(
                'question' => "How do I start pediatric therapy near $city?",
                'answer' => "The best first step is to contact our intake team. We will review your goals, service needs, scheduling preferences, and nearby clinic options."
            ),
        );

        if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) {
            $faq_items = array(
                array(
                    'question' => "Ofrecen terapia ABA en $city?",
                    'answer' => "Si. Las familias en $city pueden acceder a terapia ABA a traves de clinicas cercanas de Chroma Early Start cuando sea clinicamente apropiado."
                ),
                array(
                    'question' => "Las familias en $city pueden acceder a terapia del habla?",
                    'answer' => "Si. Nuestro equipo de admision puede ayudar a confirmar disponibilidad y encontrar la clinica o servicio cercano mas adecuado."
                ),
                array(
                    'question' => "Ofrecen terapia ocupacional pediatrica cerca de $city?",
                    'answer' => "Si. La terapia ocupacional puede apoyar el procesamiento sensorial, habilidades motoras, autocuidado y participacion diaria."
                ),
                array(
                    'question' => "Como empiezo terapia pediatrica cerca de $city?",
                    'answer' => "El primer paso es contactar a nuestro equipo de admision. Revisaremos metas, necesidades, horarios y opciones de clinicas cercanas."
                ),
            );
        }

        $entities = array();
        foreach ($faq_items as $item) {
            $entities[] = array(
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => $item['answer'],
                ),
            );
        }

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $entities,
        );

        earlystart_Schema_Registry::register($schema, ['source' => 'theme-compat-city-faq']);
        global $earlystart_faq_output_done;
        $earlystart_faq_output_done = true;
    }
}
if (earlystart_theme_schema_compat_enabled()) {
    add_action('wp_head', 'earlystart_city_faq_schema_output');
    add_action('wp_head', 'earlystart_program_schema_pro');
}


/**
 * Theme Schema Compatibility
 * Added via Audit Migration
 */

// Rename to avoid collision with legacy theme (Global Namespace)
if (!function_exists('earlystart_organization_schema_pro')) {
    function earlystart_organization_schema_pro()
    {
        if (!is_front_page())
            return;
        $homepage_id = get_option('page_on_front');

        if (earlystart_output_schema_override_pro($homepage_id, 'theme-compat-home-override')) {
            return;
        }

        // Check for Builder Schema (Sprint 9 Fix: Suppress auto-generated duplicates)
        $builder_schemas = get_post_meta($homepage_id, '_earlystart_post_schemas', true);
        if (!empty($builder_schemas) && is_array($builder_schemas)) {
            return;
        }

        $en_name = get_post_meta($homepage_id, 'schema_org_name', true) ?: get_bloginfo('name');
        $name = earlystart_get_schema_val($homepage_id, '_earlystart_es_title', $en_name);

        $url = get_post_meta($homepage_id, 'schema_org_url', true) ?: home_url();
        if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) {
            $url = home_url('/es');
        }

        $logo = get_post_meta($homepage_id, 'schema_org_logo', true) ?: (function_exists('earlystart_get_global_setting') ? earlystart_get_global_setting('global_logo', '') : '');

        $en_desc = get_post_meta($homepage_id, 'schema_org_description', true) ?: (function_exists('earlystart_global_seo_default_description') ? earlystart_global_seo_default_description() : '');
        $description = earlystart_get_schema_val($homepage_id, '_earlystart_es_excerpt', $en_desc);

        $area_served = get_post_meta($homepage_id, 'schema_org_area_served', true) ?: 'Atlanta';
        $telephone = get_post_meta($homepage_id, 'schema_org_telephone', true) ?: (function_exists('earlystart_global_phone') ? earlystart_global_phone() : '');
        $email = get_post_meta($homepage_id, 'schema_org_email', true) ?: (function_exists('earlystart_global_email') ? earlystart_global_email() : '');

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalBusiness',
            'name' => $name,
            'url' => $url,
            'logo' => $logo,
            'description' => $description,
            'areaServed' => [
                '@type' => 'City',
                'name' => $area_served,
            ],
            'sameAs' => [],
            'availableService' => function_exists('earlystart_seo_all_service_schemas') ? earlystart_seo_all_service_schemas() : [],
        ];

        if (function_exists('earlystart_global_facebook_url')) {
            $schema['sameAs'] = array_filter([
                earlystart_global_facebook_url(),
                earlystart_global_instagram_url(),
                earlystart_global_linkedin_url(),
            ]);
        }

        if ($telephone)
            $schema['telephone'] = $telephone;
        if ($email)
            $schema['email'] = $email;

        earlystart_Schema_Registry::register($schema, ['source' => 'theme-compat-organization']);
    }
}
if (earlystart_theme_schema_compat_enabled()) {
    add_action('wp_head', 'earlystart_organization_schema_pro', 5);
}

if (!function_exists('earlystart_website_schema_pro')) {
    function earlystart_website_schema_pro()
    {
        if (!is_front_page())
            return;
        $homepage_id = get_option('page_on_front');
        $url = home_url();
        $override = get_post_meta($homepage_id, '_earlystart_schema_override', true);
        if ($override)
            return;

        // Check for Builder Schema (Sprint 9 Fix: Suppress auto-generated duplicates)
        $builder_schemas = get_post_meta($homepage_id, '_earlystart_post_schemas', true);
        if (!empty($builder_schemas) && is_array($builder_schemas)) {
            return;
        }
        if (class_exists('earlystart_Multilingual_Manager') && earlystart_Multilingual_Manager::is_spanish()) {
            $url = home_url('/es');
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo('name'),
            'url' => $url,
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];
        earlystart_Schema_Registry::register($schema, ['source' => 'theme-compat-website']);
    }
}
if (earlystart_theme_schema_compat_enabled()) {
    add_action('wp_head', 'earlystart_website_schema_pro', 6);
}

if (!function_exists('earlystart_faq_schema_pro')) {
    function earlystart_faq_schema_pro()
    {
        return;
    }
}
// add_action('wp_head', 'earlystart_faq_schema_pro', 10);

/**
 * Suppress Legacy Theme Schema
 * Since this file is loaded on 'init', we can remove the theme hooks immediately 
 * (as they would have been added during theme load).
 */
function earlystart_remove_legacy_theme_schema()
{
    if (!earlystart_theme_schema_compat_enabled()) {
        return;
    }

    // Legacy theme used default priority 10 for these (mostly)
    remove_action('wp_head', 'earlystart_organization_schema', 10);
    remove_action('wp_head', 'earlystart_website_schema', 10);
    remove_action('wp_head', 'earlystart_faq_schema', 10);
    remove_action('wp_head', 'earlystart_general_content_schema', 1); // Explicitly 1 in theme

    remove_action('wp_head', 'earlystart_location_schema', 10);
    remove_action('wp_head', 'earlystart_city_schema', 10);
    remove_action('wp_head', 'earlystart_program_schema', 10);
}
// Execute immediately if we are past theme setup, or hook to late init
add_action('wp_head', 'earlystart_remove_legacy_theme_schema', 0);


