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
                'home_services_json' => json_encode($home_services),
                'home_faq_items_json' => json_encode(array(
                    array('question' => 'What is ABA therapy?', 'answer' => 'Applied Behavior Analysis (ABA) is a scientifically validated approach to understanding behavior and how it is affected by the environment. At Chroma Early Start, we focus on assent-based, play-led ABA to build essential life skills safely and joyfully.'),
                    array('question' => 'How many hours of ABA are recommended?', 'answer' => 'Recommendations vary based on each child\'s unique needs and the initial assessment, typically ranging from 10 to 40 hours per week. Our goal is to prescribe the exact amount needed for meaningful progress, not a universally rigid schedule.'),
                    array('question' => 'What is parent involvement like?', 'answer' => 'Parents are our most critical partners. We provide regular parent training sessions, transparent data sharing, and open-door policies at all our clinics so you can seamlessly integrate strategies at home.'),
                    array('question' => 'When should a child start Speech Therapy?', 'answer' => 'Early intervention is key. If you notice your child isn\'t meeting speech milestones, struggles to communicate their needs, or if their speech isn\'t easily understood by others, an initial evaluation is highly recommended.'),
                    array('question' => 'Does Speech Therapy help with social skills?', 'answer' => 'Absolutely! Speech Therapy addresses social communication (pragmatics), helping children navigate conversations, read social cues, and build meaningful relationships with peers.'),
                    array('question' => 'What is the difference between fine motor and sensory OT?', 'answer' => 'Fine motor OT focuses on the small muscles used for tasks like writing, buttoning, and using utensils. Sensory OT helps children process and respond appropriately to their environment (e.g., sound, touch, movement). We seamlessly integrate both as needed.'),
                    array('question' => 'What is the age range for your OT services?', 'answer' => 'We provide specialized pediatric Occupational Therapy for children from 6 months up to 12 years of age, ensuring developmental support during their most critical years of growth.'),
                )),
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
                'program_hero_description' => 'Our specialized Speech Therapy program focuses on articulation, language comprehension, and social communication milestones to give your child the tools they need to connect.',
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
                'program_hero_description' => 'We help children build fine motor skills, integrate sensory experiences, and develop self-regulation tools necessary for daily life and self-confident play.',
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

    // 5. Create Chroma Locations
    $locations = array(
        'cherokee-academy' => array(
            'title' => 'Cherokee Academy by Early Start Early Learning',
            'region' => 'Cherokee',
            'meta' => array(
                'location_address' => '1205 Upper Burris Road',
                'location_city' => 'Canton',
                'location_state' => 'GA',
                'location_zip' => '30114',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'east-cobb-campus' => array(
            'title' => 'East Cobb Campus',
            'region' => 'Cobb',
            'meta' => array(
                'location_address' => '2499 Shallowford Road NE',
                'location_city' => 'Marietta',
                'location_state' => 'GA',
                'location_zip' => '30066',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'ellenwood-campus' => array(
            'title' => 'Ellenwood Campus',
            'region' => 'Dekalb',
            'meta' => array(
                'location_address' => '2765 E Atlanta Rd',
                'location_city' => 'Ellenwood',
                'location_state' => 'GA',
                'location_zip' => '30294',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
                'location_featured' => '1',
            ),
        ),
        'johns-creek-campus' => array(
            'title' => 'Johns Creek',
            'region' => 'Fulton',
            'meta' => array(
                'location_address' => '3580 Old Alabama Rd',
                'location_city' => 'Johns Creek',
                'location_state' => 'GA',
                'location_zip' => '30005',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
                'location_featured' => '1',
            ),
        ),
        'jonesboro-campus' => array(
            'title' => 'Jonesboro Campus',
            'region' => 'Clayton',
            'meta' => array(
                'location_address' => '1832 Noahs Ark Road',
                'location_city' => 'Jonesboro',
                'location_state' => 'GA',
                'location_zip' => '30236',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'lawrenceville-campus' => array(
            'title' => 'Lawrenceville Campus',
            'region' => 'Gwinnett',
            'meta' => array(
                'location_address' => '3650 Club Drive',
                'location_city' => 'Lawrenceville',
                'location_state' => 'GA',
                'location_zip' => '30044',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'lilburn-campus' => array(
            'title' => 'Lilburn Campus',
            'region' => 'Gwinnett',
            'meta' => array(
                'location_address' => '917 Killian Hill Road Southwest',
                'location_city' => 'Lilburn',
                'location_state' => 'GA',
                'location_zip' => '30047',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'mcdonough-campus' => array(
            'title' => 'Mcdonough',
            'region' => 'Henry',
            'meta' => array(
                'location_address' => '90 Hunters Chase',
                'location_city' => 'Mcdonough',
                'location_state' => 'GA',
                'location_zip' => '30253',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'midway-campus' => array(
            'title' => 'Midway Campus',
            'region' => 'Fulton',
            'meta' => array(
                'location_address' => '4015 Discovery Dr',
                'location_city' => 'Alpharetta',
                'location_state' => 'GA',
                'location_zip' => '30004',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'newnan-campus' => array(
            'title' => 'Newnan Campus',
            'region' => 'Coweta',
            'meta' => array(
                'location_address' => '40 Bledsoe Rd',
                'location_city' => 'Newnan',
                'location_state' => 'GA',
                'location_zip' => '30265',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'north-hall-campus' => array(
            'title' => 'North Hall Campus, Murraysville',
            'region' => 'Hall',
            'meta' => array(
                'location_address' => '5760 Wade Whelchel Road',
                'location_city' => 'Murraysville',
                'location_state' => 'GA',
                'location_zip' => '30269',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'pleasanthill-campus' => array(
            'title' => 'Pleasanthill Campus, Duluth',
            'region' => 'Gwinnett',
            'meta' => array(
                'location_address' => '3152 Creek Dr NW',
                'location_city' => 'Duluth',
                'location_state' => 'GA',
                'location_zip' => '30096',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
                'location_featured' => '1',
            ),
        ),
        'rivergreen-campus' => array(
            'title' => 'Rivergreen Campus',
            'region' => 'Cherokee',
            'meta' => array(
                'location_address' => '200 River Green Avenue',
                'location_city' => 'Canton',
                'location_state' => 'GA',
                'location_zip' => '30114',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'roswell-campus' => array(
            'title' => 'Roswell Campus',
            'region' => 'Fulton',
            'meta' => array(
                'location_address' => '1255 Upper Hembree Road',
                'location_city' => 'Roswell',
                'location_state' => 'GA',
                'location_zip' => '30076',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'satellite-blvd-campus' => array(
            'title' => 'Satellite Bvd Campus',
            'region' => 'Gwinnett',
            'meta' => array(
                'location_address' => '3730 Satellite Boulevard',
                'location_city' => 'Duluth',
                'location_state' => 'GA',
                'location_zip' => '30096',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'south-cobb-campus' => array(
            'title' => 'South Cobb Campus, Austell',
            'region' => 'Cobb',
            'meta' => array(
                'location_address' => '7225 Premier Lane',
                'location_city' => 'Austell',
                'location_state' => 'GA',
                'location_zip' => '30168',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'tramore-campus' => array(
            'title' => 'Tramore Campus',
            'region' => 'Cobb',
            'meta' => array(
                'location_address' => '2081 Mesa Valley Way',
                'location_city' => 'Austell',
                'location_state' => 'GA',
                'location_zip' => '30106',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
            ),
        ),
        'tyrone-campus' => array(
            'title' => 'Tyrone Campus',
            'region' => 'Fayette',
            'meta' => array(
                'location_address' => '291 Jenkins Road',
                'location_city' => 'Tyrone',
                'location_state' => 'GA',
                'location_zip' => '30290',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
                'location_featured' => '1',
            ),
        ),
        'west-cobb-campus' => array(
            'title' => 'West Cobb Campus',
            'region' => 'Cobb',
            'meta' => array(
                'location_address' => '2424 Powder Springs Rd SW',
                'location_city' => 'Marietta',
                'location_state' => 'GA',
                'location_zip' => '30064',
                'location_hours' => 'Mon - Fri: 8:00 AM - 6:00 PM',
                'location_google_rating' => '4.9',
                'location_featured' => '1',
            ),
        ),
    );

    foreach ($locations as $slug => $data) {
        $existing = get_page_by_path($slug, OBJECT, 'location');
        if (!$existing) {
            // Fallback for older seeded locations with different slugs.
            $existing = get_page_by_title($data['title'], OBJECT, 'location');
        }

        if ($existing) {
            $post_id = $existing->ID;
            wp_update_post(array(
                'ID' => $post_id,
                'post_title' => $data['title'],
                'post_name' => $slug,
                'post_status' => 'publish',
            ));
        } else {
            $post_id = wp_insert_post(array(
                'post_title' => $data['title'],
                'post_name' => $slug,
                'post_status' => 'publish',
                'post_type' => 'location',
            ));
        }

        if (!is_wp_error($post_id)) {
            // Always refresh location meta so rerunning the seed keeps hubs in sync.
            if (!empty($data['meta'])) {
                foreach ($data['meta'] as $key => $value) {
                    update_post_meta($post_id, $key, $value);
                }
            }

            // Reset featured flag when omitted from the location record.
            $is_featured = !empty($data['meta']['location_featured']) ? '1' : '0';
            update_post_meta($post_id, 'location_featured', $is_featured);

            if (!empty($data['region'])) {
                wp_set_object_terms($post_id, $data['region'], 'location_region');
            }
        }
    }

    // 6. Create Sample Team Members
    $team_members = array(
        'charanjeev-singh' => array(
            'title' => 'Charanjeev Singh',
            'meta' => array(
                'team_member_title' => 'Chief Executive Officer',
            )
        ),
        'pam-rossman' => array(
            'title' => 'Pam Rossman',
            'meta' => array(
                'team_member_title' => 'Chief Operating Officer',
            )
        ),
        'jazmine-johnson' => array(
            'title' => 'Jazmine Johnson',
            'content' => 'Jazmine Johnson, BCBA, LBA, is a Licensed Board Certified Behavior Analyst specializing in autism services, early intervention, behavior reduction, and comprehensive caregiver and staff training. With extensive experience across home, clinic, and school settings, she is dedicated to delivering high-quality, evidence-based Applied Behavior Analysis (ABA) services that create meaningful and lasting change.',
            'meta' => array(
                'team_member_title' => 'BCBA, LBA',
            )
        ),
        'tymichia-mcgill' => array(
            'title' => 'Tymichia Mcgill',
            'content' => '',
            'meta' => array(
                'team_member_title' => 'BCBA',
            )
        ),
    );

    foreach ($team_members as $slug => $data) {
        $existing = get_page_by_path($slug, OBJECT, 'team_member');
        if (!$existing) {
            $post_id = wp_insert_post(array(
                'post_title' => $data['title'],
                'post_name' => $slug,
                'post_content' => $data['content'] ?? '',
                'post_status' => 'publish',
                'post_type' => 'team_member',
            ));
            if (!is_wp_error($post_id) && !empty($data['meta'])) {
                foreach ($data['meta'] as $key => $value) {
                    update_post_meta($post_id, $key, $value);
                }
            }
        }
    }

    // 7. Seed Cities (GA Counties)
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
