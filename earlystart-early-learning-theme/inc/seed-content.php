<?php
/**
 * Master Seed Content Script
 * Automatically creates core pages, programs, locations, and populates ALL meta boxes.
 * 
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Seed Core Content
 */
function earlystart_seed_core_content()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    // 1. Seed Global Brand Settings (Native Options API)
    $global_settings = array(
        'global_phone' => '(555) 123-4567',
        'global_email' => 'hello@earlystarttherapy.com',
        'global_address' => "123 Wellness Blvd\nTherapy City, ST 12345",
        'global_facebook_url' => 'https://facebook.com/earlystarttherapy',
        'global_instagram_url' => 'https://instagram.com/earlystarttherapy',
    );

    update_option('earlystart_global_settings', $global_settings);

    // 2. Prepare JSON data for sections that use it
    $home_services = array(
        array(
            'id' => 'aba',
            'title' => 'ABA Therapy',
            'subtitle' => 'Applied Behavior Analysis',
            'icon' => 'puzzle',
            'heading' => 'Individualized Behavioral Growth',
            'description' => 'Our ABA therapy focuses on increasing helpful behaviors and decreasing harmful ones to improve learning and social skills.',
            'image' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&q=80&fm=webp?w=800&fit=crop&q=80&fm=webp',
            'bullets' => array('One-on-one sessions', 'Data-driven progress tracking', 'Positive reinforcement focus')
        ),
        array(
            'id' => 'speech',
            'title' => 'Speech Therapy',
            'subtitle' => 'Communication Skills',
            'icon' => 'message-circle',
            'heading' => 'Unlocking Every Voice',
            'description' => 'Specialized support for articulation, language comprehension, and social communication milestones.',
            'image' => 'https://images.unsplash.com/photo-1544717305-27a734ef202e?auto=format&fit=crop&q=80&fm=webp?w=800&fit=crop&q=80&fm=webp',
            'bullets' => array('Articulation support', 'Social language skills', 'Early intervention focus')
        ),
        array(
            'id' => 'ot',
            'title' => 'Occupational Therapy',
            'subtitle' => 'Daily Living Skills',
            'icon' => 'hand',
            'heading' => 'Developing Independence',
            'description' => 'Helping children build the fine motor, sensory, and cognitive skills needed for daily life and play.',
            'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&fm=webp?w=800&fit=crop&q=80&fm=webp',
            'bullets' => array('Fine motor development', 'Sensory integration', 'Self-regulation tools')
        ),
    );

    // 3. Create Core Pages
    $pages = array(
        'home' => array(
            'title' => 'Home',
            'template' => 'front-page.php',
            'meta' => array(
                'home_hero_heading' => 'Compassionate <span class="bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-orange-600 italic">Therapy</span> for Growing Minds',
                'home_hero_subheading' => 'Specialized ABA, Speech, and Occupational Therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.',
                'home_hero_cta_label' => 'Start Your Journey',
                'home_hero_secondary_label' => 'Explore Services',
                'home_prismpath_eyebrow' => 'Proven Methodology',
                'home_prismpath_heading' => 'Grounded in <span class="italic text-rose-400">Expertise</span>, Built on Love.',
                'home_prismpath_subheading' => 'We combine clinical precision with compassionate care. Our evidence-based approach ensures every child receives the specific support they need to thrive.',
                'home_prismpath_cta_label' => 'Our Methodology',
                'home_prismpath_readiness_heading' => 'Pediatric Excellence',
                'home_prismpath_readiness_desc' => 'Integrating speech, OT, and ABA for holistic outcomes.',
                'home_locations_heading' => 'Serving Families Across <span class="italic text-rose-700">The Metro</span>',
                'home_locations_subheading' => 'Find your nearest clinical center and join our community of growth.',
                'home_locations_cta_label' => 'View All Locations',
                'home_faq_heading' => 'Frequently <span class="italic text-rose-700">Asked</span> Questions',
                'home_faq_subheading' => 'Common questions from parents',
                'home_services_json' => json_encode($home_services), // Seed the services tabs
            )
        ),
        'about' => array(
            'title' => 'About Us',
            'template' => 'page-about.php',
            'meta' => array(
                'about_hero_badge_text' => 'Established 2015',
                'about_hero_title' => 'Compassionate Care. <span class="text-rose-500 italic">Clinical Excellence.</span>',
                'about_hero_description' => 'Founded with a mission to bridge the gap in neurodiverse pediatric support, Early Start provides a sanctuary for growth and development.',
                'about_mission_quote' => 'We believe every child deserves a path to independence that honors their unique dignity and potential.',
                'about_story_title' => 'Our Story',
                'about_story_paragraph1' => 'Born from a need for integrated pediatric services, Early Start began as a small family project that grew into a regional leader in early intervention.',
                'about_story_paragraph2' => 'Today, we serve hundreds of families with a multidisciplinary team of BCBAs, SLPs, and OTs.',
                'about_stat1_value' => '10+',
                'about_stat1_label' => 'Specialists',
                'about_stat2_value' => '500+',
                'about_stat2_label' => 'Families Served',
                'about_stat3_value' => '3',
                'about_stat3_label' => 'Clinical Centers',
                'about_stat4_value' => '100%',
                'about_stat4_label' => 'Licensed',
                'about_educators_title' => 'The Heart of Early Start.',
                'about_educators_description' => 'Our team is composed of dedicated therapists who are passionate about pediatric development.',
                'about_leadership_title' => 'Led by clinicians, not investors.',
            )
        ),
        'our-approach' => array('title' => 'Our Approach', 'template' => 'page-approach.php'),
        'services' => array('title' => 'Services', 'template' => 'page-programs.php'),
        'locations' => array('title' => 'Locations', 'template' => 'page-locations.php'),
        'curriculum' => array('title' => 'Our Approach', 'template' => 'page-approach.php'),
        'parents' => array(
            'title' => 'For Families',
            'template' => 'page-families.php',
            'meta' => array(
                '_wp_page_template' => 'page-families.php'
            )
        ),
        'bridge-program' => array(
            'title' => 'Early Start Bridge Program',
            'template' => 'page-bridge-program.php',
            'meta' => array(
                'bridge_hero_badge' => 'School Readiness',
                'bridge_hero_title' => 'Clinical Pre-K: <span class="italic text-blue-600">The Ultimate Bridge.</span>',
                '_wp_page_template' => 'page-bridge-program.php'
            )
        ),
        'consultation' => array(
            'title' => 'Book Your Consultation',
            'template' => 'page-consultation.php',
            'meta' => array(
                '_wp_page_template' => 'page-consultation.php'
            )
        ),
        'contact' => array(
            'title' => 'Contact',
            'template' => 'page-contact.php'
        ),
        'careers' => array('title' => 'Careers', 'template' => 'page-careers.php'),
    );

    foreach ($pages as $slug => $page_data) {
        $existing_page = get_page_by_path($slug);
        $page_id = 0;

        if (!$existing_page) {
            $page_id = wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_name' => $slug,
                'post_status' => 'publish',
                'post_type' => 'page',
                'page_template' => $page_data['template']
            ));
        } else {
            $page_id = $existing_page->ID;
            // Force update template for existing pages to match our new architecture
            if (!empty($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
        }

        if ($page_id && !is_wp_error($page_id)) {
            // Update Meta
            if (!empty($page_data['meta'])) {
                foreach ($page_data['meta'] as $key => $value) {
                    update_post_meta($page_id, $key, $value);
                }
            }
            // Set Homepage Settings
            if ($slug === 'home') {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
        }
    }

    // 4. Create Sample Programs
    $programs = array(
        'aba-therapy' => array(
            'title' => 'ABA Therapy',
            'excerpt' => 'Applied Behavior Analysis focused on positive reinforcement and measurable outcomes.',
            'meta' => array(
                'program_icon' => '🧩',
                'program_age_range' => '18mo - 12y',
                'program_color_scheme' => 'red',
                'program_hero_title' => 'Individualized Behavioral Growth.',
                'program_hero_description' => 'Our ABA program is designed to build essential life skills through data-driven intervention.',
                'program_prism_title' => 'Behavioral Core',
                'program_prism_physical' => 40,
                'program_prism_emotional' => 90,
                'program_prism_social' => 80,
                'program_prism_academic' => 30,
                'program_prism_creative' => 50,
            )
        ),
        'speech-therapy' => array(
            'title' => 'Speech Therapy',
            'excerpt' => 'Helping children unlock their voice and communicate effectively with the world.',
            'meta' => array(
                'program_icon' => '💬',
                'program_age_range' => '2y - 12y',
                'program_color_scheme' => 'blue',
                'program_hero_title' => 'Unlocking Every Voice.',
                'program_prism_title' => 'Communication Core',
                'program_prism_physical' => 20,
                'program_prism_emotional' => 70,
                'program_prism_social' => 95,
                'program_prism_academic' => 60,
                'program_prism_creative' => 80,
            )
        ),
        'occupational-therapy' => array(
            'title' => 'Occupational Therapy',
            'excerpt' => 'Building independence through fine motor, sensory, and self-regulation skills.',
            'meta' => array(
                'program_icon' => '🖐️',
                'program_age_range' => '6mo - 12y',
                'program_color_scheme' => 'yellow',
                'program_hero_title' => 'Developing Independence.',
                'program_prism_title' => 'Motor & Sensory Core',
                'program_prism_physical' => 90,
                'program_prism_emotional' => 60,
                'program_prism_social' => 40,
                'program_prism_academic' => 50,
                'program_prism_creative' => 70,
            )
        ),
    );

    foreach ($programs as $slug => $data) {
        $existing = get_page_by_path($slug, OBJECT, 'program');
        if (!$existing) {
            $post_id = wp_insert_post(array(
                'post_title' => $data['title'],
                'post_name' => $slug,
                'post_excerpt' => $data['excerpt'],
                'post_status' => 'publish',
                'post_type' => 'program',
            ));
            if (!is_wp_error($post_id) && !empty($data['meta'])) {
                foreach ($data['meta'] as $key => $value)
                    update_post_meta($post_id, $key, $value);
            }
        }
    }

    // 5. Create Sample Locations
    $locations = array(
        'johns-creek' => array(
            'title' => 'Johns Creek',
            'region' => 'North Metro',
            'meta' => array(
                'location_city' => 'Johns Creek',
                'location_state' => 'GA',
                'location_zip' => '30022',
                'location_address' => '3580 Old Alabama Rd',
                'location_phone' => '(770) 555-0101',
                'location_email' => 'johnscreek@earlystarttherapy.com',
                'location_hours' => '8am - 6pm',
                'location_latitude' => '34.028',
                'location_longitude' => '-84.198',
                'location_tagline' => 'Serving Johns Creek families.',
                'location_director_name' => 'Clinical Director',
                'location_google_rating' => '5.0',
            )
        ),
        'ellenwood' => array(
            'title' => 'Ellenwood',
            'region' => 'South Metro',
            'meta' => array(
                'location_city' => 'Ellenwood',
                'location_state' => 'GA',
                'location_zip' => '30264',
                'location_address' => '2765 E Atlanta Rd',
                'location_phone' => '(770) 555-0102',
                'location_email' => 'ellenwood@earlystarttherapy.com',
                'location_hours' => '8am - 6pm',
                'location_latitude' => '33.600',
                'location_longitude' => '-84.200',
                'location_tagline' => 'Serving Ellenwood families.',
                'location_director_name' => 'Clinical Director',
                'location_google_rating' => '5.0',
            )
        ),
        'duluth' => array(
            'title' => 'Duluth',
            'region' => 'Gwinnett',
            'meta' => array(
                'location_city' => 'Duluth',
                'location_state' => 'GA',
                'location_zip' => '30096',
                'location_address' => '3152 Creek Dr',
                'location_phone' => '(770) 555-0103',
                'location_email' => 'duluth@earlystarttherapy.com',
                'location_hours' => '8am - 6pm',
                'location_latitude' => '34.000',
                'location_longitude' => '-84.140',
                'location_tagline' => 'Serving Duluth families.',
                'location_director_name' => 'Clinical Director',
                'location_google_rating' => '5.0',
            )
        ),
        'marietta' => array(
            'title' => 'Marietta',
            'region' => 'West Metro',
            'meta' => array(
                'location_city' => 'Marietta',
                'location_state' => 'GA',
                'location_zip' => '30064',
                'location_address' => '2424 Powder Springs Rd',
                'location_phone' => '(770) 555-0104',
                'location_email' => 'marietta@earlystarttherapy.com',
                'location_hours' => '8am - 6pm',
                'location_latitude' => '33.950',
                'location_longitude' => '-84.550',
                'location_tagline' => 'Serving Marietta families.',
                'location_director_name' => 'Clinical Director',
                'location_google_rating' => '5.0',
            )
        ),
        'tyrone' => array(
            'title' => 'Tyrone',
            'region' => 'South Metro',
            'meta' => array(
                'location_city' => 'Tyrone',
                'location_state' => 'GA',
                'location_zip' => '30290',
                'location_address' => '291 Jenkins Rd',
                'location_phone' => '(770) 555-0105',
                'location_email' => 'tyrone@earlystarttherapy.com',
                'location_hours' => '8am - 6pm',
                'location_latitude' => '33.470',
                'location_longitude' => '-84.600',
                'location_tagline' => 'Serving Tyrone families.',
                'location_director_name' => 'Clinical Director',
                'location_google_rating' => '5.0',
            )
        ),
    );

    foreach ($locations as $slug => $data) {
        $existing = get_page_by_path($slug, OBJECT, 'location');
        if (!$existing) {
            $post_id = wp_insert_post(array(
                'post_title' => $data['title'],
                'post_name' => $slug,
                'post_status' => 'publish',
                'post_type' => 'location',
            ));
            if (!is_wp_error($post_id)) {
                // Set Meta
                if (!empty($data['meta'])) {
                    foreach ($data['meta'] as $key => $value) {
                        update_post_meta($post_id, $key, $value);
                    }
                }
                // Set Region Term
                if (!empty($data['region'])) {
                    wp_set_object_terms($post_id, $data['region'], 'location_region');
                }
            }
        }
    }

    // 6. Seed Cities (GA Counties)
    $ga_cities = array(
        'Fayette' => array('Brooks', 'Fayetteville', 'Peachtree City', 'Tyrone', 'Woolsey'),
        'Coweta' => array('Newnan', 'Senoia', 'Sharpsburg', 'Moreland', 'Grantville'),
        'Cobb' => array('Acworth', 'Austell', 'Kennesaw', 'Mableton', 'Marietta', 'Powder Springs', 'Smyrna'),
        'Fulton' => array('Alpharetta', 'Atlanta', 'Chattahoochee Hills', 'College Park', 'East Point', 'Fairburn', 'Hapeville', 'Johns Creek', 'Milton', 'Mountain Park', 'Palmetto', 'Roswell', 'Sandy Springs', 'South Fulton', 'Union City'),
        'Gwinnett' => array('Auburn', 'Berkeley Lake', 'Braselton', 'Buford', 'Dacula', 'Duluth', 'Grayson', 'Lawrenceville', 'Lilburn', 'Loganville', 'Mulberry', 'Norcross', 'Peachtree Corners', 'Rest Haven', 'Snellville', 'Sugar Hill', 'Suwanee'),
        'Henry' => array('Hampton', 'Locust Grove', 'McDonough', 'Stockbridge'),
        'Cherokee' => array('Ball Ground', 'Canton', 'Holly Springs', 'Waleska', 'Woodstock'),
        'Dekalb' => array('Avondale Estates', 'Brookhaven', 'Chamblee', 'Clarkston', 'Decatur', 'Doraville', 'Dunwoody', 'Lithonia', 'Pine Lake', 'Stone Mountain', 'Stonecrest', 'Tucker'),
    );

    foreach ($ga_cities as $county => $cities) {
        foreach ($cities as $city_name) {
            $slug = sanitize_title($city_name);
            $slug_with_state = $slug . '-ga'; // Ensure generic names don't clash

            // Check if exists
            $existing = get_page_by_path($slug_with_state, OBJECT, 'city');
            if (!$existing) {
                // Try without state suffix
                $existing = get_page_by_path($slug, OBJECT, 'city');
            }

            if (!$existing) {
                $post_id = wp_insert_post(array(
                    'post_title' => $city_name,
                    'post_name' => $slug_with_state,
                    'post_status' => 'publish',
                    'post_type' => 'city',
                ));

                if (!is_wp_error($post_id)) {
                    update_post_meta($post_id, 'city_state', 'GA');
                    update_post_meta($post_id, 'city_county', $county);
                    // Add generic content seed
                    $content = sprintf(
                        '<!-- wp:paragraph --><p>Welcome to our early learning resources for families in <strong>%s, GA</strong> (%s County). At Early Start, we are dedicated to providing compassionate, evidence-based therapy and support.</p><!-- /wp:paragraph -->',
                        $city_name,
                        $county
                    );
                    wp_update_post(array('ID' => $post_id, 'post_content' => $content));
                }
            }
        }
    }

    echo '<div class="notice notice-success is-dismissible"><p>Early Start: Pages, Programs, Locations, and Global Settings seeded!</p></div>';
}

add_action('admin_init', function () {
    if (isset($_GET['earlystart_seed']) && $_GET['earlystart_seed'] === '1') {
        earlystart_seed_core_content();
    }
});
