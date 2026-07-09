<?php
/**
 * Homepage data helpers (hardcoded)
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
        exit;
}

/**
 * Get Home Page ID (for thumbnail rendering)
 */
function earlystart_get_home_page_id()
{
        return get_option('page_on_front') ?: 0;
}

function earlystart_home_default_hero()
{
        return array(
                'heading' => __('Compassionate <span class="bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-orange-600 italic">Therapy</span> for Growing Minds', 'earlystart-early-learning'),
                'subheading' => __('Specialized autism assessment, behavioral assessment, ADHD assessment, ABA therapy, speech therapy, and occupational therapy tailored to your child\'s unique journey. Our integrated clinical approach helps children thrive in a supportive, play-based environment.', 'earlystart-early-learning'),
                'cta_label' => __('Start Your Journey', 'earlystart-early-learning'),
                'cta_url' => '#contact',
                'secondary_label' => __('Explore Services', 'earlystart-early-learning'),
                'secondary_url' => '#services',
        );
}

function earlystart_home_default_stats()
{
        return array(
                array('value' => 'Board Certified', 'label' => __('Specialists', 'earlystart-early-learning'), 'icon' => 'shield-check'),
                array('value' => 'Licensed', 'label' => __('Pediatric Facility', 'earlystart-early-learning'), 'icon' => 'badge-check'),
                array('value' => 'Family', 'label' => __('Focused Approach', 'earlystart-early-learning'), 'icon' => 'users'),
                array('value' => 'Evidence', 'label' => __('Based Results', 'earlystart-early-learning'), 'icon' => 'award'),
        );
}

function earlystart_home_default_services()
{
        return array(
                array(
                        'id' => 'autism-assessment',
                        'title' => __('Autism Assessment', 'earlystart-early-learning'),
                        'subtitle' => __('Developmental Assessment', 'earlystart-early-learning'),
                        'icon' => 'clipboard-check',
                        'heading' => __('Clear Answers for Next Steps', 'earlystart-early-learning'),
                        'description' => __('Comprehensive autism assessments help families understand developmental needs and plan the right care pathway.', 'earlystart-early-learning'),
                        'image' => 'https://images.unsplash.com/photo-1559757175-0eb30cd8c063?auto=format&fit=crop&w=800&q=80&fm=webp',
                        'bullets' => array(
                                __('Developmental history review', 'earlystart-early-learning'),
                                __('Standardized assessment tools', 'earlystart-early-learning'),
                                __('Care planning guidance', 'earlystart-early-learning')
                        )
                ),
                array(
                        'id' => 'aba',
                        'title' => __('ABA Therapy', 'earlystart-early-learning'),
                        'subtitle' => __('Applied Behavior Analysis', 'earlystart-early-learning'),
                        'icon' => 'puzzle',
                        'heading' => __('Individualized Behavioral Growth', 'earlystart-early-learning'),
                        'description' => __('Our ABA therapy focuses on increasing helpful behaviors and decreasing harmful ones to improve learning and social skills.', 'earlystart-early-learning'),
                        'image' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=800&q=80&fm=webp',
                        'bullets' => array(
                                __('One-on-one sessions', 'earlystart-early-learning'),
                                __('Data-driven progress tracking', 'earlystart-early-learning'),
                                __('Positive reinforcement focus', 'earlystart-early-learning')
                        )
                ),
                array(
                        'id' => 'behavioral-assessment',
                        'title' => __('Behavioral Assessment', 'earlystart-early-learning'),
                        'subtitle' => __('Behavior Support Review', 'earlystart-early-learning'),
                        'icon' => 'heart-pulse',
                        'heading' => __('Whole-Child Behavioral Assessment', 'earlystart-early-learning'),
                        'description' => __('Behavioral assessments help identify strengths, barriers, regulation needs, family routines, and the right support plan for children and caregivers.', 'earlystart-early-learning'),
                        'image' => 'https://images.unsplash.com/photo-1536640712-4d4c36ff0e4e?auto=format&fit=crop&w=800&q=80&fm=webp',
                        'bullets' => array(
                                __('Behavior pattern review', 'earlystart-early-learning'),
                                __('Family-centered recommendations', 'earlystart-early-learning'),
                                __('Coordinated next steps', 'earlystart-early-learning')
                        )
                ),
                array(
                        'id' => 'adhd-assessment',
                        'title' => __('ADHD Assessment', 'earlystart-early-learning'),
                        'subtitle' => __('Attention & Executive Function', 'earlystart-early-learning'),
                        'icon' => 'activity',
                        'heading' => __('Clarity Around Attention and Regulation', 'earlystart-early-learning'),
                        'description' => __('ADHD assessments help families understand attention, executive function, impulsivity, and regulation needs so care planning can be more precise.', 'earlystart-early-learning'),
                        'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=80&fm=webp',
                        'bullets' => array(
                                __('Attention and regulation review', 'earlystart-early-learning'),
                                __('Executive function profile', 'earlystart-early-learning'),
                                __('Practical care recommendations', 'earlystart-early-learning')
                        )
                ),
                array(
                        'id' => 'speech',
                        'title' => __('Speech Therapy', 'earlystart-early-learning'),
                        'subtitle' => __('Communication Skills', 'earlystart-early-learning'),
                        'icon' => 'message-circle',
                        'heading' => __('Unlocking Every Voice', 'earlystart-early-learning'),
                        'description' => __('Specialized support for articulation, language comprehension, and social communication milestones.', 'earlystart-early-learning'),
                        'image' => 'https://images.unsplash.com/photo-1544717305-27a734ef202e?auto=format&fit=crop&w=800&q=80&fm=webp',
                        'bullets' => array(
                                __('Articulation support', 'earlystart-early-learning'),
                                __('Social language skills', 'earlystart-early-learning'),
                                __('Early intervention focus', 'earlystart-early-learning')
                        )
                ),
                array(
                        'id' => 'ot',
                        'title' => __('Occupational Therapy', 'earlystart-early-learning'),
                        'subtitle' => __('Daily Living Skills', 'earlystart-early-learning'),
                        'icon' => 'hand',
                        'heading' => __('Developing Independence', 'earlystart-early-learning'),
                        'description' => __('Helping children build the fine motor, sensory, and cognitive skills needed for daily life and play.', 'earlystart-early-learning'),
                        'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=800&q=80&fm=webp',
                        'bullets' => array(
                                __('Fine motor development', 'earlystart-early-learning'),
                                __('Sensory integration', 'earlystart-early-learning'),
                                __('Self-regulation tools', 'earlystart-early-learning')
                        )
                ),
                array(
                        'id' => 'readiness',
                        'title' => __('Clinical Readiness', 'earlystart-early-learning'),
                        'subtitle' => __('Group Participation', 'earlystart-early-learning'),
                        'icon' => 'graduation-cap',
                        'heading' => __('Prepared for Daily Participation', 'earlystart-early-learning'),
                        'description' => __('Guided support helps children carry therapy gains into group routines, peer interaction, and everyday family life.', 'earlystart-early-learning'),
                        'image' => 'https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&w=800&q=80&fm=webp',
                        'bullets' => array(
                                __('Social-emotional prep', 'earlystart-early-learning'),
                                __('Group routine practice', 'earlystart-early-learning'),
                                __('Executive function building', 'earlystart-early-learning')
                        )
                )
        );
}

function earlystart_home_services()
{
        $post_id = earlystart_get_home_page_id();
        $services_json = earlystart_get_translated_meta($post_id, 'home_services_json', true);

        $services = array();
        if ($services_json) {
                $decoded = json_decode($services_json, true);
                if (JSON_ERROR_NONE === json_last_error() && is_array($decoded)) {
                        $services = $decoded;
                }
        }

        if (empty($services)) {
                $services = earlystart_home_get_theme_mod_json('earlystart_home_services_json', earlystart_home_default_services());
        }

        return $services;
}

function earlystart_home_default_team()
{
        return array();
}

function earlystart_home_team()
{
        // Query CPT
        $args = array(
                'post_type' => 'team_member',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_status' => 'publish',
        );

        $query = earlystart_cached_query($args, 'home_team_list', DAY_IN_SECONDS);

        if ($query->have_posts()) {
                $team = array();
                while ($query->have_posts()) {
                        $query->the_post();
                        $post_id = get_the_ID();

                        $team[] = array(
                                'name' => get_the_title(),
                                'role' => get_post_meta($post_id, 'team_member_title', true) ?: '',
                                'image' => get_the_post_thumbnail_url($post_id, 'large') ?: '',
                                'linkedin' => get_post_meta($post_id, 'team_member_linkedin', true) ?: '',
                        );
                }
                wp_reset_postdata();
                return $team;
        }

        // Fallback if no CPT posts exist
        $post_id = earlystart_get_home_page_id();
        $team_json = earlystart_get_translated_meta($post_id, 'home_team_json', true);

        $team = array();
        if ($team_json) {
                $decoded = json_decode($team_json, true);
                if (JSON_ERROR_NONE === json_last_error() && is_array($decoded)) {
                        $team = $decoded;
                }
        }

        if (empty($team)) {
                $team = earlystart_home_get_theme_mod_json('earlystart_home_team_json', earlystart_home_default_team());
        }

        return $team;
}

function earlystart_home_default_prismpath()
{
        return array(
                'feature' => array(
                        'eyebrow' => __('Proven Methodology', 'earlystart-early-learning'),
                        'heading' => __('Grounded in <span class="italic text-rose-400">Expertise</span>, Built on Love.', 'earlystart-early-learning'),
                        'subheading' => __('We combine clinical precision with compassionate care. Our evidence-based approach ensures every child receives the specific support they need to thrive.', 'earlystart-early-learning'),
                        'cta_label' => __('Our Methodology', 'earlystart-early-learning'),
                        'cta_url' => '/our-approach/',
                ),
                'cards' => array(
                        array(
                                'id' => 'aba-excellence',
                                'icon' => 'brain-circuit',
                                'heading' => __('ABA Excellence', 'earlystart-early-learning'),
                                'text' => __('Board-certified clinical supervision with a focus on positive reinforcement.', 'earlystart-early-learning'),
                                'color' => 'orange'
                        ),
                        array(
                                'id' => 'integrated-care',
                                'icon' => 'heart',
                                'heading' => __('Integrated Care', 'earlystart-early-learning'),
                                'text' => __('Speech, Occupational, and ABA therapists working together for your child.', 'earlystart-early-learning'),
                                'color' => 'rose'
                        ),
                        array(
                                'id' => 'family-first',
                                'icon' => 'users',
                                'heading' => __('Family First', 'earlystart-early-learning'),
                                'text' => __('Transparent communication and parent training to support growth at home.', 'earlystart-early-learning'),
                                'color' => 'amber'
                        )
                )
        );
}

function earlystart_home_get_theme_mod_json($key, $default = array())
{
        $raw = earlystart_get_theme_mod($key, '');

        if (empty($raw)) {
                return $default;
        }

        $decoded = json_decode($raw, true);

        if (JSON_ERROR_NONE !== json_last_error() || !is_array($decoded)) {
                return $default;
        }

        return $decoded;
}

/**
 * Home Hero Data
 */
function earlystart_home_hero()
{
        $defaults = earlystart_home_default_hero();
        $post_id = earlystart_get_home_page_id();

        // Check for specific meta overrides (supports translation)
        $heading = earlystart_get_translated_meta($post_id, 'home_hero_heading', true);
        $subheading = earlystart_get_translated_meta($post_id, 'home_hero_subheading', true);
        $cta_label = earlystart_get_translated_meta($post_id, 'home_hero_cta_label', true);
        $secondary_label = earlystart_get_translated_meta($post_id, 'home_hero_secondary_label', true);

        return array(
                'heading' => wp_kses_post($heading ?: earlystart_get_theme_mod('earlystart_home_hero_heading', $defaults['heading'])),
                'subheading' => sanitize_text_field($subheading ?: earlystart_get_theme_mod('earlystart_home_hero_subheading', $defaults['subheading'])),
                'cta_label' => sanitize_text_field($cta_label ?: earlystart_get_theme_mod('earlystart_home_hero_cta_label', $defaults['cta_label'])),
                'cta_url' => earlystart_get_localized_url(esc_url_raw(earlystart_get_theme_mod('earlystart_home_hero_cta_url', $defaults['cta_url']))),
                'secondary_label' => sanitize_text_field($secondary_label ?: earlystart_get_theme_mod('earlystart_home_hero_secondary_label', $defaults['secondary_label'])),
                'secondary_url' => earlystart_get_localized_url(esc_url_raw(earlystart_get_theme_mod('earlystart_home_hero_secondary_url', $defaults['secondary_url']))),
        );
}

/**
 * Home Stats
 */
function earlystart_home_stats()
{
        $post_id = earlystart_get_home_page_id();
        $stats_json = earlystart_get_translated_meta($post_id, 'home_stats_json', true);

        $stats = array();
        if ($stats_json) {
                $decoded = json_decode($stats_json, true);
                if (JSON_ERROR_NONE === json_last_error() && is_array($decoded)) {
                        $stats = $decoded;
                }
        }

        if (empty($stats)) {
                $stats = earlystart_home_get_theme_mod_json('earlystart_home_stats_json', earlystart_home_default_stats());
        }

        $cleaned = array();

        // Define color cycle for stats (red, yellow, blue, green)
        $colors = array('chroma-red', 'chroma-yellow', 'chroma-blue', 'chroma-green');
        $index = 0;

        foreach ($stats as $stat) {
                $cleaned[] = array(
                        'value' => sanitize_text_field($stat['value'] ?? ''),
                        'label' => sanitize_text_field($stat['label'] ?? ''),
                        'color' => $colors[$index % count($colors)],
                );
                $index++;
        }

        return $cleaned;
}

/**
 * Prismpath expertise panels
 */
function earlystart_home_prismpath_panels()
{
        $defaults = earlystart_home_default_prismpath();
        $post_id = earlystart_get_home_page_id();

        // Feature Text
        $feature = $defaults['feature'];
        $eyebrow = earlystart_get_translated_meta($post_id, 'home_prismpath_eyebrow', true);
        $heading = earlystart_get_translated_meta($post_id, 'home_prismpath_heading', true);
        $subheading = earlystart_get_translated_meta($post_id, 'home_prismpath_subheading', true);
        $cta_label = earlystart_get_translated_meta($post_id, 'home_prismpath_cta_label', true);

        $feature = array(
                'eyebrow' => sanitize_text_field($eyebrow ?: earlystart_get_theme_mod('earlystart_home_prismpath_eyebrow', $feature['eyebrow'])),
                'heading' => sanitize_text_field($heading ?: earlystart_get_theme_mod('earlystart_home_prismpath_heading', $feature['heading'])),
                'subheading' => sanitize_text_field($subheading ?: earlystart_get_theme_mod('earlystart_home_prismpath_subheading', $feature['subheading'])),
                'cta_label' => sanitize_text_field($cta_label ?: earlystart_get_theme_mod('earlystart_home_prismpath_cta_label', $feature['cta_label'])),
                'cta_url' => earlystart_get_localized_url(esc_url_raw(earlystart_get_theme_mod('earlystart_home_prismpath_cta_url', $feature['cta_url']))),
        );

        // Cards (Check JSON Override)
        $cards_json = earlystart_get_translated_meta($post_id, 'home_prismpath_cards_json', true);
        $cards = array();
        if ($cards_json) {
                $decoded = json_decode($cards_json, true);
                if (JSON_ERROR_NONE === json_last_error() && is_array($decoded)) {
                        $cards = $decoded;
                }
        }

        if (empty($cards)) {
                $cards = earlystart_home_get_theme_mod_json('earlystart_home_prismpath_cards_json', $defaults['cards']);
        }

        // Sanitize Cards
        $cards = array_map(
                function ($card, $index) use ($defaults) {
                        $default_card = $defaults['cards'][$index] ?? array();
                        return array(
                                'badge' => sanitize_text_field($card['badge'] ?? $default_card['badge'] ?? ''),
                                'heading' => sanitize_text_field($card['heading'] ?? $default_card['heading'] ?? ''),
                                'text' => sanitize_textarea_field($card['text'] ?? $default_card['text'] ?? ''),
                                'button' => sanitize_text_field($card['button'] ?? $default_card['button'] ?? ''),
                                'url' => earlystart_get_localized_url(esc_url_raw($card['url'] ?? $default_card['url'] ?? '')),
                                'icon' => sanitize_text_field(($card['icon'] ?? '') ?: ($default_card['icon'] ?? '')),
                                'icon_bg' => sanitize_text_field(($card['icon_bg'] ?? '') ?: ($default_card['icon_bg'] ?? '')),
                                'icon_badge' => sanitize_text_field(($card['icon_badge'] ?? '') ?: ($default_card['icon_badge'] ?? '')),
                                'icon_check' => sanitize_text_field(($card['icon_check'] ?? '') ?: ($default_card['icon_check'] ?? '')),
                        );
                },
                $cards,
                array_keys($cards)
        );

        // Readiness
        $readiness = $defaults['readiness'];
        $readiness_heading = earlystart_get_translated_meta($post_id, 'home_prismpath_readiness_heading', true);
        $readiness_desc = earlystart_get_translated_meta($post_id, 'home_prismpath_readiness_desc', true);

        $readiness = array(
                'heading' => sanitize_text_field($readiness_heading ?: earlystart_get_theme_mod('earlystart_home_prismpath_readiness_heading', $readiness['heading'])),
                'description' => sanitize_textarea_field($readiness_desc ?: earlystart_get_theme_mod('earlystart_home_prismpath_readiness_desc', $readiness['description'])),
        );

        return array(
                'feature' => $feature,
                'cards' => $cards,
                'readiness' => $readiness,
        );
}

function earlystart_home_default_program_wizard_options()
{
        $program_url = earlystart_get_program_archive_url();

        return array(
                array(
                        'key' => 'autism-assessment',
                        'emoji' => 'A',
                        'label' => __("Autism\nAssessment", 'earlystart-early-learning'),
                        'description' => __('Comprehensive developmental assessment and care planning for children and families.', 'earlystart-early-learning'),
                        'link' => $program_url . '#autism-assessment',
                ),
                array(
                        'key' => 'behavioral-assessment',
                        'emoji' => 'B',
                        'label' => __("Behavioral\nAssessment", 'earlystart-early-learning'),
                        'description' => __('Family-centered behavior review for regulation, routines, barriers, and next steps.', 'earlystart-early-learning'),
                        'link' => $program_url . '#behavioral-assessment',
                ),
                array(
                        'key' => 'adhd-assessment',
                        'emoji' => 'ADHD',
                        'label' => __("ADHD\nAssessment", 'earlystart-early-learning'),
                        'description' => __('Attention, executive function, impulsivity, and regulation review for clearer care planning.', 'earlystart-early-learning'),
                        'link' => $program_url . '#adhd-assessment',
                ),
                array(
                        'key' => 'aba',
                        'emoji' => 'ABA',
                        'label' => __("ABA\nTherapy", 'earlystart-early-learning'),
                        'description' => __('Individualized ABA therapy focused on meaningful behavior change and skill building.', 'earlystart-early-learning'),
                        'link' => $program_url . '#aba',
                ),
                array(
                        'key' => 'speech',
                        'emoji' => 'S',
                        'label' => __("Speech\nTherapy", 'earlystart-early-learning'),
                        'description' => __('Support for articulation, language, communication, and social interaction goals.', 'earlystart-early-learning'),
                        'link' => $program_url . '#speech',
                ),
                array(
                        'key' => 'ot',
                        'emoji' => 'OT',
                        'label' => __("Occupational\nTherapy", 'earlystart-early-learning'),
                        'description' => __('Occupational therapy for sensory, motor, daily living, and independence goals.', 'earlystart-early-learning'),
                        'link' => $program_url . '#ot',
                ),
        );
}
function earlystart_home_default_curriculum_profiles()
{
        return array(
                'labels' => array('Physical', 'Emotional', 'Social', 'Academic', 'Creative'),
                'profiles' => array(
                        array(
                                'key' => 'assessment',
                                'label' => 'Assessment',
                                'title' => 'Understanding Phase',
                                'description' => 'Assessment visits clarify developmental, behavioral, attention, sensory, and communication needs so the care plan starts from real evidence.',
                                'color' => '#D67D6B',
                                'data' => array(55, 80, 65, 55, 45),
                        ),
                        array(
                                'key' => 'aba',
                                'label' => 'ABA',
                                'title' => 'Skill Building Phase',
                                'description' => 'ABA therapy targets meaningful skills, behavior patterns, communication, routines, and caregiver goals through individualized support.',
                                'color' => '#4A6C7C',
                                'data' => array(55, 90, 80, 45, 55),
                        ),
                        array(
                                'key' => 'speech',
                                'label' => 'Speech',
                                'title' => 'Communication Phase',
                                'description' => 'Speech therapy supports language, articulation, comprehension, social communication, and confident expression.',
                                'color' => '#E6BE75',
                                'data' => array(40, 70, 85, 65, 55),
                        ),
                        array(
                                'key' => 'ot',
                                'label' => 'OT',
                                'title' => 'Independence Phase',
                                'description' => 'Occupational therapy supports sensory processing, fine motor skills, self-regulation, and everyday participation.',
                                'color' => '#2F4858',
                                'data' => array(90, 75, 60, 50, 65),
                        ),
                        array(
                                'key' => 'care-coordination',
                                'label' => 'Care Coordination',
                                'title' => 'Integrated Care Phase',
                                'description' => 'Our team aligns goals across assessment, ABA, speech, OT, and family communication so care stays coordinated.',
                                'color' => '#4A6C7C',
                                'data' => array(55, 85, 85, 65, 60),
                        ),
                        array(
                                'key' => 'family-support',
                                'label' => 'Family Support',
                                'title' => 'Everyday Carryover Phase',
                                'description' => 'Caregiver guidance helps families carry therapy gains into daily routines, peer interaction, and community life.',
                                'color' => '#E6BE75',
                                'data' => array(50, 85, 85, 60, 70),
                        ),
                ),
        );
}
/**
 * Home FAQ Items
 */
function earlystart_home_default_faq_items()
{
        return array(
                array(
                        'question' => __('What is the intake process?', 'earlystart-early-learning'),
                        'answer' => __('Our process starts with an initial consultation. We then complete assessment steps when clinically appropriate and develop a therapy plan tailored to your child\'s needs.', 'earlystart-early-learning'),
                ),
                array(
                        'question' => __('Do you accept insurance?', 'earlystart-early-learning'),
                        'answer' => __('Yes, we work with most major insurance providers. Our intake team will help you verify your benefits and understand your coverage options.', 'earlystart-early-learning'),
                ),
                array(
                        'question' => __('What ages do you serve?', 'earlystart-early-learning'),
                        'answer' => __('We specialize in early intervention and support for children typically ranging from 18 months to 12 years old.', 'earlystart-early-learning'),
                ),
                array(
                        'question' => __('Where do you provide services?', 'earlystart-early-learning'),
                        'answer' => __('We provide services At School, In Home and At Clinics to ensure your child receives support in the environment that works best for them.', 'earlystart-early-learning'),
                ),
                array(
                        'question' => __('How long are the therapy sessions?', 'earlystart-early-learning'),
                        'answer' => __('Session length vary based on the specific therapy and your child\'s needs, typically ranging from 30 to 60 minutes for speech/OT, and longer for comprehensive ABA programs.', 'earlystart-early-learning'),
                ),
        );
}

function earlystart_home_default_locations_preview()
{
        return array(
                'heading' => __('Serving Families Across <span class="italic text-rose-700">The Metro</span>', 'earlystart-early-learning'),
                'subheading' => __('Find your nearest clinical center and join our community of growth. Serving families At School, In Home and At Clinics.', 'earlystart-early-learning'),
                'cta_label' => __('View All Locations', 'earlystart-early-learning'),
                'cta_link' => '/locations/',
        );
}

function earlystart_home_default_faq()
{
        return array(
                'heading' => __('Frequently <span class="italic text-rose-700">Asked</span> Questions', 'earlystart-early-learning'),
                'subheading' => __('Common questions from parents', 'earlystart-early-learning'),
                'items' => earlystart_home_default_faq_items(),
                'cta_text' => '',
                'cta_label' => '',
                'cta_link' => '',
        );
}

/**
 * Curriculum radar profiles
 */
function earlystart_home_default_schedule_tracks()
{
        return array(
                array(
                        'key' => 'infant',
                        'label' => 'Infants',
                        'title' => 'The Nurturing Nest',
                        'description' => 'Individualized schedules follow infants’ cues for sleeping and eating, with gentle sensory play.',
                        'color' => 'chroma-blue',
                        'background' => 'bg-chroma-blueLight',
                        'image' => 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?auto=format&fit=crop&w=800&q=80&fm=webp',
                        'steps' => array(
                                array(
                                        'time' => 'AM',
                                        'title' => 'Warm Welcome & Cuddles',
                                        'copy' => 'Transition from parent, bottle feeding, and floor play.',
                                ),
                                array(
                                        'time' => 'Mid',
                                        'title' => 'Sensory Discovery',
                                        'copy' => 'Tummy time, soft textures, and mirror play.',
                                ),
                                array(
                                        'time' => 'PM',
                                        'title' => 'Stroller Walk & Songs',
                                        'copy' => 'Fresh air (weather permitting) and gentle music.',
                                ),
                        ),
                ),
                array(
                        'key' => 'toddler',
                        'label' => 'Toddlers',
                        'title' => 'Explorers & Builders',
                        'description' => 'Structured circle time and communal meals help toddlers understand social cues and transitions.',
                        'color' => 'chroma-yellow',
                        'background' => 'bg-chroma-yellowLight',
                        'image' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=800&q=80&fm=webp',
                        'steps' => array(
                                array(
                                        'time' => '9:00',
                                        'title' => 'Morning Circle',
                                        'copy' => 'Songs, greeting friends, and introducing the daily theme.',
                                ),
                                array(
                                        'time' => '10:30',
                                        'title' => 'Prismpath Play',
                                        'copy' => 'Block building, art stations, and guided motor skills.',
                                ),
                                array(
                                        'time' => '12:00',
                                        'title' => 'Family-Style Lunch',
                                        'copy' => 'Learning to pass bowls, use utensils, and chat with friends.',
                                ),
                        ),
                ),
                array(
                        'key' => 'group-readiness',
                        'label' => 'Group Readiness',
                        'title' => 'Daily Participation',
                        'description' => 'The readiness rhythm supports stamina, focus, communication, and participation in daily routines.',
                        'color' => 'chroma-red',
                        'background' => 'bg-chroma-redLight',
                        'image' => 'https://images.unsplash.com/photo-1503919545874-86c1d9a04595?auto=format&fit=crop&w=800&q=80&fm=webp',
                        'steps' => array(
                                array(
                                        'time' => '9:00',
                                        'title' => 'Communication Warmup',
                                        'copy' => 'Language practice, choice-making, and shared attention.',
                                ),
                                array(
                                        'time' => '11:00',
                                        'title' => 'Guided Practice',
                                        'copy' => 'Therapy goals carried into structured peer and group routines.',
                                ),
                                array(
                                        'time' => '2:00',
                                        'title' => 'Social Centers',
                                        'copy' => 'Peer play, turn taking, and regulation support.',
                                ),
                        ),
                ),
        );
}

/**
 * Age-based program wizard options - Pull from Program CPT
 */
function earlystart_home_program_wizard_options()
{
        // Query all published programs (Cached)
        $args = array(
                'post_type' => 'program',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_status' => 'publish',
        );

        $programs = earlystart_cached_query($args, 'program_wizard_list', DAY_IN_SECONDS);

        if (!$programs->have_posts()) {
                // Fallback to theme mod options if no programs exist
                $options = earlystart_home_get_theme_mod_json('earlystart_home_program_wizard_json', earlystart_home_default_program_wizard_options());
                $program_url = earlystart_get_program_archive_url();

                return array_map(
                        function ($item) use ($program_url) {
                                $key = sanitize_title($item['key'] ?? '');
                                $anchor_slug = earlystart_program_anchor_for_key($key);
                                $link_target = $anchor_slug ?: $key;

                                return array(
                                        'key' => $key,
                                        'emoji' => sanitize_text_field($item['emoji'] ?? ''),
                                        'label' => sanitize_text_field($item['label'] ?? ''),
                                        'description' => sanitize_textarea_field($item['description'] ?? ''),
                                        'link' => earlystart_get_localized_url(esc_url_raw($program_url . '#' . $link_target)),
                                );
                        },
                        $options
                );
        }

        // Build options from program posts
        $options = array();
        while ($programs->have_posts()) {
                $programs->the_post();
                $post_id = get_the_ID();

                // Get program meta
                $icon = earlystart_get_translated_meta($post_id, 'program_icon', true) ?: '📚';
                $age_range = earlystart_get_translated_meta($post_id, 'program_age_range', true) ?: '';
                $excerpt = get_the_excerpt() ?: '';
                // Use post_name (slug) as the unique key to prevent collisions from cloned meta values
                $anchor_slug = get_post_field('post_name', $post_id);

                // Get image with fallback
                $image_url = get_the_post_thumbnail_url($post_id, 'large');
                if (!$image_url) {
                        $image_url = 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?auto=format&fit=crop&w=800&q=80&fm=webp';
                }

                // Build label with line break for display
                $label = get_the_title();
                if ($age_range) {
                        $clean_range = trim($age_range, '() ');
                        $label .= ' (' . $clean_range . ')';
                }

                $options[] = array(
                        'key' => $anchor_slug,
                        'emoji' => $icon,
                        'label' => $label,
                        'description' => $excerpt,
                        'link' => earlystart_get_localized_url(get_permalink($post_id)),
                        'image' => $image_url, // Added image URL
                );
        }

        wp_reset_postdata();

        return $options;
}

function earlystart_home_curriculum_profiles()
{
        $defaults = earlystart_home_default_curriculum_profiles();
        $profiles = earlystart_home_get_theme_mod_json('earlystart_home_curriculum_profiles_json', $defaults['profiles']);

        $profiles = array_map(
                function ($profile) {
                        $color = $profile['color'] ?? '';
                        if (!sanitize_hex_color($color)) {
                                $color = '#4A6C7C';
                        }

                        $data = array_map('floatval', $profile['data'] ?? array());

                        return array(
                                'key' => sanitize_title($profile['key'] ?? ''),
                                'label' => sanitize_text_field($profile['label'] ?? ''),
                                'title' => sanitize_text_field($profile['title'] ?? ''),
                                'description' => sanitize_textarea_field($profile['description'] ?? ''),
                                'color' => $color,
                                'data' => $data,
                        );
                },
                $profiles
        );

        return array(
                'labels' => $defaults['labels'],
                'profiles' => $profiles,
        );
}

/**
 * Daily schedule tracks - Pull from Program CPT
 */
function earlystart_home_schedule_tracks()
{
        // Query all published programs with schedule data (Cached)
        $args = array(
                'post_type' => 'program',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'post_status' => 'publish',
                'meta_query' => array(
                        array(
                                'key' => 'program_schedule_items',
                                'compare' => '!=',
                                'value' => '',
                        ),
                ),
        );

        $programs = earlystart_cached_query($args, 'program_schedule_tracks', DAY_IN_SECONDS);

        if (!$programs->have_posts()) {
                // Fallback to theme mod/defaults if no programs have schedule data
                $tracks = earlystart_home_get_theme_mod_json('earlystart_home_schedule_tracks_json', earlystart_home_default_schedule_tracks());

                return array_map(
                        function ($track) {
                                $steps = array_map(
                                        function ($step) {
                                                return array(
                                                        'time' => sanitize_text_field($step['time'] ?? ''),
                                                        'title' => sanitize_text_field($step['title'] ?? ''),
                                                        'copy' => sanitize_textarea_field($step['copy'] ?? ''),
                                                );
                                        },
                                        $track['steps'] ?? array()
                                );

                                return array(
                                        'key' => sanitize_title($track['key'] ?? ''),
                                        'label' => sanitize_text_field($track['label'] ?? ''),
                                        'title' => sanitize_text_field($track['title'] ?? ''),
                                        'description' => sanitize_textarea_field($track['description'] ?? ''),
                                        'color' => sanitize_text_field($track['color'] ?? ''),
                                        'background' => sanitize_text_field($track['background'] ?? ''),
                                        'image' => esc_url_raw($track['image'] ?? ''),
                                        'steps' => $steps,
                                );
                        },
                        $tracks
                );
        }

        // Build tracks from program posts
        $tracks = array();
        $used_keys = array();

        while ($programs->have_posts()) {
                $programs->the_post();
                $post_id = get_the_ID();

                // Get program meta
                // Use post_name (slug) as the unique key to prevent collisions from cloned meta values
                $anchor_slug = get_post_field('post_name', $post_id);

                // Ensure unique key
                $key = $anchor_slug;
                if (isset($used_keys[$key])) {
                        $key .= '-' . $post_id;
                }
                $used_keys[$key] = true;

                $schedule_title = earlystart_get_translated_meta($post_id, 'program_schedule_title', true);
                $schedule_items = earlystart_get_translated_meta($post_id, 'program_schedule_items', true);
                $color_scheme = get_post_meta($post_id, 'program_color_scheme', true) ?: 'blue';

                // Get program title and excerpt for label and description
                $program_title = get_the_title();
                $description = get_the_excerpt() ?: '';

                // Parse schedule items (format: Badge|Title|Description, one per line)
                $steps = array();
                if (!empty($schedule_items)) {
                        $lines = explode("\n", $schedule_items);
                        foreach ($lines as $line) {
                                $line = trim($line);
                                if (empty($line)) {
                                        continue;
                                }

                                $parts = explode('|', $line);
                                if (count($parts) >= 3) {
                                        $steps[] = array(
                                                'time' => sanitize_text_field(trim($parts[0])),
                                                'title' => sanitize_text_field(trim($parts[1])),
                                                'copy' => sanitize_textarea_field(trim($parts[2])),
                                        );
                                }
                        }
                }

                // Skip if no valid steps
                if (empty($steps)) {
                        continue;
                }

                // Get featured image URL
                $image_url = get_the_post_thumbnail_url($post_id, 'large');

                // Map color scheme to Tailwind classes
                $color_map = array(
                        'red' => array('color' => 'chroma-red', 'background' => 'bg-chroma-redLight'),
                        'blue' => array('color' => 'chroma-blue', 'background' => 'bg-chroma-blueLight'),
                        'yellow' => array('color' => 'chroma-yellow', 'background' => 'bg-chroma-yellowLight'),
                        'blueDark' => array('color' => 'chroma-blueDark', 'background' => 'bg-chroma-blueDark/10'),
                        'green' => array('color' => 'chroma-green', 'background' => 'bg-chroma-greenLight'),
                );

                $colors = $color_map[$color_scheme] ?? $color_map['blue'];

                $tracks[] = array(
                        'key' => $key,
                        'label' => $program_title,
                        'title' => $schedule_title ?: $program_title,
                        'description' => $description,
                        'color' => $colors['color'],
                        'background' => $colors['background'],
                        'image' => $image_url ?: '',
                        'steps' => $steps,
                );
        }

        wp_reset_postdata();

        return $tracks;
}

/**
 * Home FAQ block
 */
/**
 * Home FAQ block
 */
function earlystart_home_faq_items()
{
        $post_id = earlystart_get_home_page_id();
        $items_json = earlystart_get_translated_meta($post_id, 'home_faq_items_json', true);

        $items = array();
        if ($items_json) {
                $decoded = json_decode($items_json, true);
                if (JSON_ERROR_NONE === json_last_error() && is_array($decoded)) {
                        $items = $decoded;
                }
        }

        if (empty($items)) {
                $items = earlystart_home_get_theme_mod_json('earlystart_home_faq_items_json', earlystart_home_default_faq_items());
        }

        return array_map(
                function ($item) {
                        return array(
                                'question' => sanitize_text_field($item['question'] ?? ''),
                                'answer' => sanitize_textarea_field($item['answer'] ?? ''),
                        );
                },
                $items
        );
}

function earlystart_home_faq()
{
        $defaults = earlystart_home_default_faq();
        $post_id = earlystart_get_home_page_id();
        $heading = earlystart_get_translated_meta($post_id, 'home_faq_heading', true);
        $subheading = earlystart_get_translated_meta($post_id, 'home_faq_subheading', true);

        return array(
                'heading' => sanitize_text_field($heading ?: earlystart_get_theme_mod('earlystart_home_faq_heading', $defaults['heading'])),
                'subheading' => sanitize_text_field($subheading ?: earlystart_get_theme_mod('earlystart_home_faq_subheading', $defaults['subheading'])),
                'items' => earlystart_home_faq_items(),
                'cta_text' => '',
                'cta_label' => '',
                'cta_link' => '',
        );
}

function earlystart_home_locations_preview()
{
        static $cached;

        if (isset($cached)) {
                return $cached;
        }

        $post_id = earlystart_get_home_page_id();
        $meta_heading = earlystart_get_translated_meta($post_id, 'home_locations_heading', true);
        $meta_subheading = earlystart_get_translated_meta($post_id, 'home_locations_subheading', true);
        $meta_cta_label = earlystart_get_translated_meta($post_id, 'home_locations_cta_label', true);

        $heading = sanitize_text_field($meta_heading ?: earlystart_get_theme_mod('earlystart_home_locations_heading', '19+ neighborhood locations across Metro Atlanta'));
        $subheading = sanitize_text_field($meta_subheading ?: earlystart_get_theme_mod('earlystart_home_locations_subheading', 'Find a Chroma Early Start clinic near your home or work. All locations share the same safety standards, care model, and family-centered culture.'));
        $cta_label = sanitize_text_field($meta_cta_label ?: earlystart_get_theme_mod('earlystart_home_locations_cta_label', 'View All Locations'));
        $cta_link = earlystart_get_localized_url(esc_url_raw(earlystart_get_theme_mod('earlystart_home_locations_cta_link', '/locations/')));
        $taxonomy = 'location_region';
        $fallback = (object) array(
                'name' => __('Other Areas', 'earlystart-early-learning'),
                'slug' => 'other-areas',
        );

        $locations = get_posts(
                array(
                        'post_type' => 'location',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                        'suppress_filters' => true,
                )
        );

        $map_points = array();
        $featured = array();
        $grouped = array();

        foreach ($locations as $location) {
                $post_id = $location->ID;
                $title = get_the_title($post_id);
                $permalink = get_permalink($post_id);

                $fields = earlystart_get_location_fields($post_id);
                $city = $fields['city'];
                $state = $fields['state'];
                $phone = $fields['phone'];
                $address = $fields['address'];
                $is_clinic = '1' === get_post_meta($post_id, 'location_featured', true);
                $kind = $is_clinic ? 'clinic' : 'partner';

                $lat = $fields['latitude'];
                $lng = $fields['longitude'];

                if ($lat && $lng) {
                        $map_points[] = array(
                                'id' => $post_id,
                                'name' => $title,
                                'lat' => (float) $lat,
                                'lng' => (float) $lng,
                                'url' => $permalink,
                                'city' => $city,
                                'state' => $state,
                                'kind' => $kind,
                        );
                }

                $location_data = array(
                        'id' => $post_id,
                        'title' => $title,
                        'city' => $city,
                        'state' => $state,
                        'address' => $address,
                        'phone' => $phone,
                        'url' => $permalink,
                        'lat' => $lat ? (float) $lat : '',
                        'lng' => $lng ? (float) $lng : '',
                        'kind' => $kind,
                );
                if ($is_clinic) {
                        $featured[] = $location_data;
                }

                $terms = get_the_terms($post_id, $taxonomy);
                if (empty($terms) || is_wp_error($terms)) {
                        $terms = array($fallback);
                }

                foreach ($terms as $term) {
                        $prefix = $is_clinic ? 'clinic' : 'partner';
                        $group_key = $prefix . '-' . ($term->slug ? sanitize_title($term->slug) : sanitize_title($term->name));

                        if (!isset($grouped[$group_key])) {
                                $grouped[$group_key] = array(
                                        'label' => $term->name,
                                        'designation' => $kind,
                                        'designation_label' => $is_clinic ? __('Clinic Location', 'earlystart-early-learning') : __('Partner Location', 'earlystart-early-learning'),
                                        'slug' => $term->slug ?: $group_key,
                                        'term_id' => $term->term_id ?? 0,
                                        'locations' => array(),
                                );
                        }

                        $grouped[$group_key]['locations'][] = $location_data;
                }
        }

        foreach ($grouped as &$group) {
                usort(
                        $group['locations'],
                        function ($a, $b) {
                                return strnatcasecmp($a['title'], $b['title']);
                        }
                );
        }
        unset($group);

        if (!empty($grouped)) {
                uasort(
                        $grouped,
                        function ($a, $b) {
                                if (($a['designation'] ?? 'partner') !== ($b['designation'] ?? 'partner')) {
                                        return ('clinic' === ($a['designation'] ?? 'partner')) ? -1 : 1;
                                }

                                return strnatcasecmp($a['label'], $b['label']);
                        }
                );
        }

        $cached = array(
                'heading' => $heading,
                'subheading' => $subheading,
                'cta_label' => $cta_label,
                'cta_link' => $cta_link,
                'map_points' => $map_points,
                'featured' => $featured,
                'grouped' => $grouped,
                'taxonomy_key' => $taxonomy,
        );

        return $cached;
}

/**
 * Tour CTA content
 */
function earlystart_home_tour_cta()
{
        $post_id = earlystart_get_home_page_id();
        $defaults = array(
                'heading' => __('Request a private consultation', 'earlystart-early-learning'),
                'subheading' => __('Share a few details and your preferred clinic. A Chroma Early Start team member will reach out to discuss care options.', 'earlystart-early-learning'),
                'trust_text' => __('No obligation. We’ll never share your information.', 'earlystart-early-learning'),
                'cta_label' => __('Request a Consultation', 'earlystart-early-learning'),
                'cta_url' => '#contact',
        );

        $heading = earlystart_get_translated_meta($post_id, 'home_tour_cta_heading', true);
        $subheading = earlystart_get_translated_meta($post_id, 'home_tour_cta_subheading', true);
        $trust_text = earlystart_get_translated_meta($post_id, 'home_tour_cta_trust_text', true);
        $cta_label = earlystart_get_translated_meta($post_id, 'home_tour_cta_label', true);
        $cta_url = earlystart_get_translated_meta($post_id, 'home_tour_cta_url', true);

        return array(
                'heading' => wp_kses_post($heading ?: earlystart_get_theme_mod('earlystart_home_tour_cta_heading', $defaults['heading'])),
                'subheading' => sanitize_text_field($subheading ?: earlystart_get_theme_mod('earlystart_home_tour_cta_subheading', $defaults['subheading'])),
                'trust_text' => sanitize_text_field($trust_text ?: earlystart_get_theme_mod('earlystart_home_tour_cta_trust_text', $defaults['trust_text'])),
                'cta_label' => sanitize_text_field($cta_label ?: earlystart_get_theme_mod('earlystart_home_tour_cta_label', $defaults['cta_label'])),
                'cta_url' => earlystart_get_localized_url(esc_url_raw($cta_url ?: earlystart_get_theme_mod('earlystart_home_tour_cta_url', $defaults['cta_url']))),
        );
}

/**
 * Home Featured Locations (static)
 */
function earlystart_home_featured_locations()
{
        $locations = earlystart_home_locations_preview();
        return $locations['featured'];
}

/**
 * Home Featured Stories (static placeholders)
 */
function earlystart_home_featured_stories()
{
        return array(
                array(
                        'title' => __('Inside the Chroma Care Model', 'earlystart-early-learning'),
                        'excerpt' => __('See how our clinical team aligns assessment, therapy goals, and family communication.', 'earlystart-early-learning'),
                        'url' => '/stories/chroma-care-model/',
                ),
                array(
                        'title' => __('Supporting Everyday Progress', 'earlystart-early-learning'),
                        'excerpt' => __('Why coordinated care and caregiver guidance matter for meaningful therapy gains.', 'earlystart-early-learning'),
                        'url' => '/stories/everyday-progress',
                ),
                array(
                        'title' => __('Partnering with Parents', 'earlystart-early-learning'),
                        'excerpt' => __('See how we communicate daily to keep families connected to care progress.', 'earlystart-early-learning'),
                        'url' => '/stories/partnering-with-parents',
                ),
        );
}
/**
 * Parent Reviews for homepage carousel
 */
function earlystart_home_default_parent_reviews()
{
        return array(
                array(
                        'name' => 'Sarah M.',
                        'location' => __('Marietta Clinic', 'earlystart-early-learning'),
                        'rating' => '5',
                        'review' => __('Our daughter has flourished at Early Start. The clinicians genuinely care, and the coordinated therapy plan helped us understand each next step.', 'earlystart-early-learning'),
                ),
                array(
                        'name' => 'James & Lisa T.',
                        'location' => __('Johns Creek Clinic', 'earlystart-early-learning'),
                        'rating' => '5',
                        'review' => __('After speaking with several providers, Early Start stood out immediately. The transparency, warmth, and expert care made our decision easy.', 'earlystart-early-learning'),
                ),
                array(
                        'name' => 'Maria G.',
                        'location' => __('Austell Clinic', 'earlystart-early-learning'),
                        'rating' => '5',
                        'review' => __('The daily communication, welcoming team, and coordinated therapy support exceeded expectations. Early Start feels like a true partner for our family.', 'earlystart-early-learning'),
                ),
        );
}

function earlystart_home_parent_reviews()
{
        $reviews = earlystart_home_get_theme_mod_json('earlystart_home_parent_reviews_json', earlystart_home_default_parent_reviews());

        return array_map(
                function ($review) {
                        return array(
                                'name' => sanitize_text_field($review['name'] ?? ''),
                                'location' => sanitize_text_field($review['location'] ?? ''),
                                'rating' => absint($review['rating'] ?? 5),
                                'review' => sanitize_textarea_field($review['review'] ?? ''),
                        );
                },
                $reviews
        );
}

/**
 * Checkers for optional sections
 */
function earlystart_home_has_prismpath_panels()
{
        return true;
}

function earlystart_home_has_program_wizard()
{
        return true;
}

function earlystart_home_has_curriculum_profiles()
{
        return true;
}

function earlystart_home_has_schedule_tracks()
{
        return true;
}

function earlystart_home_has_faq()
{
        return true;
}

function earlystart_home_has_stats()
{
        return true;
}

function earlystart_home_has_parent_reviews()
{
        $reviews = earlystart_home_parent_reviews();
        return !empty($reviews);
}


