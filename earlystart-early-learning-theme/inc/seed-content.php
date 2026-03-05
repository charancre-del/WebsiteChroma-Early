<?php
/**
 * Master Seed Content Script
 *
 * @package EarlyStart_Early_Start
 */

if (!defined('ABSPATH')) {
    exit;
}

function earlystart_build_seed_location_meta(array $location)
{
    $address = trim($location['address']);
    $city = trim($location['city']);
    $state = trim($location['state']);
    $zip = trim($location['zip']);
    $region = trim($location['region']);
    $title = trim($location['title']);
    $full_address = sprintf('%s, %s, %s %s', $address, $city, $state, $zip);

    return array(
        'location_hero_subtitle' => sprintf('Now enrolling families in %s.', $city),
        'location_hero_review_text' => sprintf('Families across %s trust our team for responsive communication and individualized, assent-based care.', $region),
        'location_hero_review_author' => 'Chroma Early Start Parent',
        'location_hero_gallery' => array(),
        'location_virtual_tour_embed' => '',
        'location_tagline' => sprintf('Compassionate ABA, speech, and OT support in %s.', $city),
        'location_description' => sprintf('%s serves children and families in %s County with play-led, evidence-based therapy that builds communication, independence, and school readiness.', $title, $region),
        'location_google_rating' => $location['google_rating'] ?? '4.9',
        'location_hours' => $location['hours'] ?? 'Mon - Fri: 8:00 AM - 6:00 PM',
        'location_ages_served' => $location['ages_served'] ?? '18 months - 8 years',
        'location_director_name' => $location['director_name'] ?? 'Chroma Early Start Clinical Team',
        'location_director_heading' => $location['director_heading'] ?? 'Clinical Leadership',
        'location_director_bio' => $location['director_bio'] ?? sprintf('Our %s team partners with families to deliver measurable progress through compassionate, child-centered care.', $city),
        'location_director_photo' => $location['director_photo'] ?? '',
        'location_director_signature' => $location['director_signature'] ?? '',
        'location_maps_embed' => $location['maps_embed'] ?? '',
        'location_tour_booking_link' => $location['tour_booking_link'] ?? home_url('/schedule-tour/'),
        'location_school_pickups' => $location['school_pickups'] ?? sprintf("%s Elementary\n%s Primary\nLocal private and charter schools", $city, $city),
        'location_seo_content_title' => $location['seo_title'] ?? sprintf('Early intervention therapy in %s, %s', $city, $state),
        'location_seo_content_text' => $location['seo_text'] ?? sprintf('Families in %s, %s choose Chroma Early Start for coordinated ABA, speech, and occupational therapy delivered in a supportive clinic setting designed for early learners.', $city, $state),
        'location_address' => $address,
        'location_city' => $city,
        'location_state' => $state,
        'location_zip' => $zip,
        'location_phone' => $location['phone'] ?? '(678) 555-0100',
        'location_email' => $location['email'] ?? 'hello@chromaearlystart.com',
        'location_latitude' => $location['latitude'] ?? '',
        'location_longitude' => $location['longitude'] ?? '',
        'location_service_areas' => $location['service_areas'] ?? sprintf("%s\nNearby %s County communities\nMetro Atlanta", $city, $region),
        'location_special_programs' => $location['special_programs'] ?? "ABA Therapy\nSpeech Therapy\nOccupational Therapy\nParent Coaching",
        'location_faq_items' => $location['faq_items'] ?? "What ages do you serve?|We typically support children from 18 months through early elementary years.\nDo you accept insurance?|Our admissions team helps families review eligibility and next steps.\nHow do we get started?|Complete the contact form or schedule a consultation to begin intake.",
        'location_gmb_url' => $location['gmb_url'] ?? ('https://www.google.com/maps/search/?api=1&query=' . rawurlencode($full_address)),
        'location_featured' => !empty($location['featured']) ? '1' : '',
        'location_quality_rated' => !empty($location['quality_rated']) ? '1' : '',
        '_earlystart_license_number' => $location['license_number'] ?? '',
    );
}

function earlystart_seed_core_content()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $about_meta = array(
        'about_hero_badge_text' => 'Established 2015',
        'about_hero_title' => 'More than a clinic. <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">A second home.</span>',
        'about_hero_description' => 'We founded Early Start on a simple belief: Clinical therapy should be a perfect blend of rigorous skill development and the comforting warmth of family life.',
        'about_hero_image' => 'https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=1000&q=80&fm=webp',
        'about_mission_quote' => '"To cultivate a vibrant community of lifelong learners by blending academic rigor with the nurturing warmth of home, ensuring every child feels seen, valued, and capable."',
        'about_story_title' => 'From one clinic to a regional community.',
        'about_story_paragraph1' => 'Chroma Early Start began with a mission to redefine what early intervention should feel like. We did not want families to choose between clinical rigor and human warmth.',
        'about_story_paragraph2' => 'Over the last decade, we have grown into a network of clinics across Metro Atlanta, while keeping the same family-centered standards that shaped our first center.',
        'about_story_image' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=800&q=80&fm=webp',
        'about_stat1_value' => '19+',
        'about_stat1_label' => 'Locations',
        'about_stat2_value' => '2k+',
        'about_stat2_label' => 'Families Supported',
        'about_stat3_value' => '450+',
        'about_stat3_label' => 'Team Members',
        'about_stat4_value' => '100%',
        'about_stat4_label' => 'Licensed',
        'about_educators_title' => 'The heart of Chroma Early Start.',
        'about_educators_description' => 'Our clinicians and support teams are selected for empathy, technical skill, and a commitment to child-led care.',
        'about_educator1_icon' => 'fa-solid fa-certificate',
        'about_educator1_title' => 'Credentialed Specialists',
        'about_educator1_desc' => 'Our team includes licensed and board-certified professionals across ABA, speech, and occupational therapy disciplines.',
        'about_educator2_icon' => 'fa-solid fa-user-shield',
        'about_educator2_title' => 'Safety First',
        'about_educator2_desc' => 'Every team member completes screening, compliance, and training requirements before supporting families.',
        'about_educator3_icon' => 'fa-solid fa-chalkboard-user',
        'about_educator3_title' => 'Continuous Growth',
        'about_educator3_desc' => 'We invest in ongoing staff development so our clinical standards keep improving as the field evolves.',
        'about_values_title' => 'The Chroma Standard',
        'about_values_description' => 'These four principles shape how we design programs, coach teams, and partner with parents.',
        'about_value1_icon' => 'fa-solid fa-heart',
        'about_value1_title' => 'Unconditional Joy',
        'about_value1_desc' => 'We believe childhood should still feel joyful, even in highly structured clinical work.',
        'about_value2_icon' => 'fa-solid fa-shield-halved',
        'about_value2_title' => 'Radical Safety',
        'about_value2_desc' => 'Physical and emotional safety are foundational to every care plan and every session.',
        'about_value3_icon' => 'fa-solid fa-lightbulb',
        'about_value3_title' => 'Clinical Excellence',
        'about_value3_desc' => 'We use data responsibly to support progress without sacrificing humanity or play.',
        'about_value4_icon' => 'fa-solid fa-users',
        'about_value4_title' => 'Open Partnership',
        'about_value4_desc' => 'Parents are partners, not spectators. We prioritize clarity, transparency, and shared goals.',
        'about_leadership_title' => 'Led by clinicians, not investors.',
        'about_nutrition_title' => 'Fueling growing minds.',
        'about_nutrition_description' => 'Whole-child support includes how children feel, regulate, and participate throughout the day, including mealtimes and sensory needs.',
        'about_nutrition_bullet1_icon' => 'fa-solid fa-apple-whole',
        'about_nutrition_bullet1_text' => 'Family-centered care plans',
        'about_nutrition_bullet2_icon' => 'fa-solid fa-carrot',
        'about_nutrition_bullet2_text' => 'Sensory-aware routines',
        'about_nutrition_bullet3_icon' => 'fa-solid fa-ban',
        'about_nutrition_bullet3_text' => 'Clear allergy and safety protocols',
        'about_nutrition_image' => 'https://images.unsplash.com/photo-1606914506133-2230d94922b5?auto=format&fit=crop&w=800&q=80&fm=webp',
        'about_philanthropy_title' => 'Supporting families beyond the clinic.',
        'about_philanthropy_subtitle' => 'Community-first care',
        'about_philanthropy_description' => 'Our work extends beyond direct treatment. We invest in access, parent education, and local partnerships that improve outcomes for early learners.',
        'about_philanthropy_bullet1_icon' => 'fa-solid fa-hand-holding-heart',
        'about_philanthropy_bullet1_text' => 'Family guidance resources',
        'about_philanthropy_bullet2_icon' => 'fa-solid fa-chalkboard-user',
        'about_philanthropy_bullet2_text' => 'Clinical education and training',
        'about_philanthropy_bullet3_icon' => 'fa-solid fa-people-roof',
        'about_philanthropy_bullet3_text' => 'Community outreach partnerships',
        'about_philanthropy_image' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80&fm=webp',
        'about_cta_title' => 'Ready to get started?',
        'about_cta_description' => 'Meet the team, tour a clinic, and see how Chroma Early Start supports lasting growth.',
        '_about_defaults_seeded' => '1',
    );

    $contact_meta = array(
        'contact_hero_badge' => 'Start Your Journey',
        'contact_hero_title' => 'We would love to meet you.',
        'contact_hero_description' => 'Ready to experience the Chroma Early Start difference? Schedule a tour or ask us a question below to get started.',
        'contact_form_submit_text' => 'Submit Request',
        'contact_corporate_title' => 'Corporate Office',
        'contact_corporate_name' => 'Chroma Early Start HQ',
        'contact_corporate_address' => "123 Education Way, Suite 400\nAtlanta, GA 30309",
        'contact_corporate_phone' => '(404) 555-0199',
        'contact_careers_title' => 'Careers',
        'contact_careers_description' => 'Passionate about child-centered clinical care? We are always looking for dedicated team members.',
        'contact_careers_link_text' => 'View Open Positions',
        'contact_careers_link_url' => '/careers',
        'contact_press_title' => 'Press Inquiries',
        'contact_press_description' => 'For media kits and interview requests with our leadership team.',
        'contact_press_link_text' => 'Visit Newsroom',
        'contact_press_link_url' => '/newsroom',
        '_contact_defaults_seeded' => '1',
    );

    $careers_meta = array(
        'careers_hero_badge' => 'Join Our Team',
        'careers_hero_title' => 'Shape the future. <br><span class="italic text-chroma-red">Love your work.</span>',
        'careers_hero_description' => 'We do not just hire staff; we invest in clinicians. At Chroma Early Start, you will find a supportive team, real mentorship, and sustainable caseloads.',
        'careers_hero_button_text' => 'View Current Openings',
        'careers_hero_button_url' => '#openings',
        'careers_culture_title' => 'Why Chroma Early Start?',
        'careers_culture_description' => 'We take care of our team so they can take care of our families.',
        'careers_benefit1_icon' => 'fa-solid fa-money-bill-wave',
        'careers_benefit1_title' => 'Competitive Pay & 401k',
        'careers_benefit1_desc' => 'Strong compensation, performance incentives, and retirement support for long-term career growth.',
        'careers_benefit2_icon' => 'fa-solid fa-graduation-cap',
        'careers_benefit2_title' => 'Training & Supervision',
        'careers_benefit2_desc' => 'We support credentialing, mentorship, and supervision pathways for clinicians building their careers.',
        'careers_benefit3_icon' => 'fa-solid fa-heart-pulse',
        'careers_benefit3_title' => 'Health & Wellness',
        'careers_benefit3_desc' => 'Benefits packages, paid time off, and support structures built to reduce burnout.',
        'careers_openings_title' => 'Current Opportunities',
        'careers_job1_title' => 'BCBA',
        'careers_job1_location' => 'Regional Clinics',
        'careers_job1_type' => 'Full Time',
        'careers_job1_url' => '#apply',
        'careers_job2_title' => 'RBT',
        'careers_job2_location' => 'Regional Clinics',
        'careers_job2_type' => 'Full Time',
        'careers_job2_url' => '#apply',
        'careers_job3_title' => 'Clinical Support Staff',
        'careers_job3_location' => 'Regional Clinics',
        'careers_job3_type' => 'Full Time / Part Time',
        'careers_job3_url' => '#apply',
        'careers_cta_title' => 'Do not see your role?',
        'careers_cta_description' => 'We are always growing. Send us your resume and we will keep it on file.',
        'careers_cta_button_text' => 'Email HR Team',
        'careers_cta_button_url' => 'mailto:careers@chromaearlystart.com',
        '_careers_defaults_seeded' => '1',
    );

    $families_meta = array(
        'families_hero_badge' => 'Parent Resources',
        'families_hero_title' => 'You Are Not Alone<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">On This Journey.</span>',
        'families_hero_desc' => 'Navigating early intervention can be overwhelming. We help families understand insurance, intake, diagnosis, and next steps with clarity and compassion.',
        'parents_hero_badge' => 'Parents Zone',
        'parents_hero_title' => 'Partnering in your child\'s growth',
        'parents_hero_description' => 'We believe progress is strongest when clinic and home work together. Here you will find practical resources for the journey ahead.',
        'parents_essentials_title' => 'Parent Essentials',
        'parents_events_badge' => 'Community',
        'parents_events_title' => 'Events & Celebrations',
        'parents_events_description' => 'Join us for family-friendly events, parent education, and community connection.',
        'parents_events_image' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=1200&q=80&fm=webp',
        'parents_event1_icon' => 'fa-solid fa-calendar-days',
        'parents_event1_title' => 'Quarterly Family Events',
        'parents_event1_desc' => 'Seasonal family events create space for joy, connection, and community support.',
        'parents_event2_icon' => 'fa-solid fa-star',
        'parents_event2_title' => 'Milestone Celebrations',
        'parents_event2_desc' => 'We celebrate meaningful progress and key transitions with families every step of the way.',
        'parents_event3_icon' => 'fa-solid fa-handshake',
        'parents_event3_title' => 'Parent-Clinician Conferences',
        'parents_event3_desc' => 'Regular goal reviews keep progress, home carryover, and next steps aligned.',
        'parents_nutrition_badge' => 'Wellness',
        'parents_nutrition_title' => 'What to expect day to day',
        'parents_nutrition_description' => 'Predictable routines, sensory-aware planning, and clear communication help children feel safe and ready to learn.',
        'parents_nutrition_image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80&fm=webp',
        'parents_menu1_icon' => 'fa-solid fa-apple-whole',
        'parents_menu1_title' => 'Daily Session Planning',
        'parents_menu1_subtitle' => 'Clear structure and routines',
        'parents_menu1_url' => '#',
        'parents_menu2_icon' => 'fa-solid fa-utensils',
        'parents_menu2_title' => 'Family Communication',
        'parents_menu2_subtitle' => 'Ongoing updates and support',
        'parents_menu2_url' => '#',
        'parents_menu3_icon' => 'fa-solid fa-carrot',
        'parents_menu3_title' => 'Care Coordination',
        'parents_menu3_subtitle' => 'Practical next steps',
        'parents_menu3_url' => '#',
        'parents_safety_title' => 'Safety First',
        'parents_safety_description' => 'Your child\'s safety and regulation are the baseline for effective learning.',
        'parents_safety1_icon' => 'fa-solid fa-video',
        'parents_safety1_title' => 'Secure Monitoring',
        'parents_safety1_desc' => 'Our facilities are monitored and access-controlled to support a safe clinical environment.',
        'parents_safety2_icon' => 'fa-solid fa-user-shield',
        'parents_safety2_title' => 'Trained Staff',
        'parents_safety2_desc' => 'Our team completes required training, screening, and compliance processes before working with families.',
        'parents_safety3_icon' => 'fa-solid fa-pump-medical',
        'parents_safety3_title' => 'Health Protocols',
        'parents_safety3_desc' => 'We follow documented wellness, sanitation, and incident-response protocols across clinics.',
        'parents_faq_title' => 'Frequently Asked Questions',
        'parents_faq_description' => 'Quick answers to common questions about intake, scheduling, and support.',
        'parents_faq1_question' => 'How do we get started?',
        'parents_faq1_answer' => 'Start with a consultation or contact form submission. Our admissions team will guide you through intake and next steps.',
        'parents_faq2_question' => 'Do you help with insurance?',
        'parents_faq2_answer' => 'Yes. Our admissions team helps families understand eligibility, authorizations, and supporting documents.',
        'parents_faq3_question' => 'What happens after intake?',
        'parents_faq3_answer' => 'We review clinical needs, schedule evaluations as needed, and build a plan for services and family onboarding.',
        'parents_referral_title' => 'Know a family who needs support?',
        'parents_referral_description' => 'Referrals help more children access high-quality early intervention and family-centered care.',
        'parents_referral_button_text' => 'Refer a Family',
        'parents_referral_button_url' => 'mailto:director@chromaearlystart.com?subject=Parent%20Referral',
        '_parents_defaults_seeded' => '1',
    );

    $curriculum_meta = array(
        'curriculum_hero_badge' => 'The Early Start Difference',
        'curriculum_hero_title' => 'Scientific rigor. <br><span class="italic text-chroma-green">Joyful delivery.</span>',
        'curriculum_hero_description' => 'Our proprietary Prismpath framework is not just about ABCs. It is a comprehensive model designed to build critical thinking, emotional intelligence, and social skills through structured play.',
        'curriculum_framework_title' => 'The Prismpath Framework',
        'curriculum_framework_description' => 'Just as a prism refracts light into a spectrum, our curriculum refracts play into five distinct pillars of development. Every activity targets one or more of these areas.',
        'curriculum_pillar_physical_icon' => 'fa-solid fa-person-running',
        'curriculum_pillar_physical_title' => 'Physical',
        'curriculum_pillar_physical_desc' => 'Gross motor coordination, fine motor grip strength, sensory integration, and functional independence.',
        'curriculum_pillar_emotional_icon' => 'fa-solid fa-face-smile',
        'curriculum_pillar_emotional_title' => 'Emotional',
        'curriculum_pillar_emotional_desc' => 'Self-regulation, identifying feelings, building resilience, and developing a secure sense of self.',
        'curriculum_pillar_social_icon' => 'fa-solid fa-users',
        'curriculum_pillar_social_title' => 'Social',
        'curriculum_pillar_social_desc' => 'Collaboration, empathy, communication, and understanding shared routines and community roles.',
        'curriculum_pillar_academic_icon' => 'fa-solid fa-brain',
        'curriculum_pillar_academic_title' => 'Academic',
        'curriculum_pillar_academic_desc' => 'Early literacy, numeracy, inquiry, attention, and the foundations of school readiness.',
        'curriculum_pillar_creative_icon' => 'fa-solid fa-palette',
        'curriculum_pillar_creative_title' => 'Creative',
        'curriculum_pillar_creative_desc' => 'Divergent thinking, artistic expression, music and movement, and imaginative play.',
        'curriculum_timeline_badge' => 'Learning Journey',
        'curriculum_timeline_title' => 'How learning evolves.',
        'curriculum_timeline_description' => 'Our curriculum shifts with each stage of development, moving from sensory-based discovery to more complex language, logic, and collaboration.',
        'curriculum_timeline_image' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=800&q=80&fm=webp',
        'curriculum_stage_foundation_title' => 'Foundation (0-18 Months)',
        'curriculum_stage_foundation_desc' => 'Focus on safety, connection, and sensory exploration through responsive caregiving.',
        'curriculum_stage_discovery_title' => 'Discovery (18 Months - 3 Years)',
        'curriculum_stage_discovery_desc' => 'Focus on autonomy, language, imitation, and early routines that build confidence.',
        'curriculum_stage_readiness_title' => 'Readiness (3 Years - 5 Years)',
        'curriculum_stage_readiness_desc' => 'Focus on executive function, early academics, and group participation that prepare children for school.',
        'curriculum_env_badge' => 'Environment',
        'curriculum_env_title' => 'The classroom is the "Third Teacher."',
        'curriculum_env_description' => 'Our spaces are intentionally designed to support exploration, regulation, and independence without requiring constant adult prompting.',
        'curriculum_zone_construction_emoji' => 'Blocks',
        'curriculum_zone_construction_title' => 'Construction Zone',
        'curriculum_zone_construction_desc' => 'Hands-on building tools help children learn balance, planning, and spatial reasoning.',
        'curriculum_zone_atelier_emoji' => 'Art',
        'curriculum_zone_atelier_title' => 'Atelier (Art Studio)',
        'curriculum_zone_atelier_desc' => 'Open access to creative materials invites experimentation and unrestricted expression.',
        'curriculum_zone_literacy_emoji' => 'Books',
        'curriculum_zone_literacy_title' => 'Literacy Nook',
        'curriculum_zone_literacy_desc' => 'Comfortable reading spaces help children build attention, language, and a lifelong relationship with books.',
        'curriculum_milestones_title' => 'Measuring Milestones',
        'curriculum_milestones_subtitle' => 'We do not just watch children grow. We track progress to make sure no child is left without support.',
        'curriculum_milestone_tracking_icon' => 'fa-solid fa-chart-line',
        'curriculum_milestone_tracking_title' => 'Daily Progress Tracking',
        'curriculum_milestone_tracking_desc' => 'We document meaningful moments of learning and use those observations to guide instruction and family communication.',
        'curriculum_milestone_tracking_bullet1' => 'Progress snapshots',
        'curriculum_milestone_tracking_bullet2' => 'Daily family updates',
        'curriculum_milestone_screenings_icon' => 'fa-solid fa-magnifying-glass-chart',
        'curriculum_milestone_screenings_title' => 'Developmental Screenings',
        'curriculum_milestone_screenings_desc' => 'Regular screenings help identify strengths early and highlight where extra support may be needed.',
        'curriculum_milestone_screenings_bullet1' => 'Routine checkpoints',
        'curriculum_milestone_screenings_bullet2' => 'Specialist referrals when needed',
        'curriculum_milestone_assessments_icon' => 'fa-solid fa-file-signature',
        'curriculum_milestone_assessments_title' => 'Formal Assessments',
        'curriculum_milestone_assessments_desc' => 'Structured assessments support individualized planning and informed parent conversations throughout the year.',
        'curriculum_milestone_assessments_bullet1' => 'Scheduled reviews',
        'curriculum_milestone_assessments_bullet2' => 'Individualized planning',
        'curriculum_cta_title' => 'See the curriculum in action.',
        'curriculum_cta_description' => 'Schedule a visit to see how our spaces, routines, and team bring the Chroma care model to life.',
        '_curriculum_defaults_seeded' => '1',
    );

    $employers_meta = array(
        'employers_hero_badge' => 'Workforce Solutions',
        'employers_hero_title' => 'Family support is workforce infrastructure.',
        'employers_hero_description' => 'Retain top talent and reduce absenteeism by helping employees access trusted pediatric therapy and developmental support. Chroma Early Start partners with Metro Atlanta employers to support working families.',
        'employers_solution1_title' => 'Priority Access',
        'employers_solution1_desc' => 'Skip the waitlist. Reserve dedicated spots at our locations for employee families.',
        'employers_solution2_title' => 'Tuition Subsidies',
        'employers_solution2_desc' => 'We can support employer-sponsored tuition contribution programs for working parents.',
        'employers_solution3_title' => 'Back-Up Care',
        'employers_solution3_desc' => 'Flexible care options help reduce unexpected absenteeism when school or caregiver plans change.',
        'employers_tax_badge' => 'Financial Incentives',
        'employers_tax_title' => 'Maximize Your ROI with Tax Credits',
        'employers_tax_description' => 'Partnering with Chroma Early Start is not just a culture investment. Depending on program structure, tax incentives and workforce-support strategies may help offset family benefit costs.',
        'employers_federal_icon' => 'fa-solid fa-landmark',
        'employers_federal_title' => 'Federal 45F Credit',
        'employers_federal_subtitle' => 'Employer-Provided Care Credit',
        'employers_federal_desc' => 'Businesses may qualify for significant credits tied to employer-sponsored family support investments. Confirm details with your tax advisor before relying on specific incentives.',
        'employers_federal_link_text' => 'View IRS Form 8882',
        'employers_federal_link_url' => 'https://www.irs.gov/forms-pubs/about-form-8882',
        'employers_georgia_icon' => 'fa-solid fa-map-location-dot',
        'employers_georgia_title' => 'Georgia Employer\'s Credit',
        'employers_georgia_subtitle' => 'Georgia Care Tax Credit',
        'employers_georgia_desc' => 'Georgia maintains employer care incentives that can improve the economics of offering family support to employees.',
        'employers_georgia_link_text' => 'View Georgia DOR Details',
        'employers_georgia_link_url' => 'https://dor.georgia.gov/tax-credits-business',
        'employers_tax_disclaimer' => 'Please consult with your corporate tax professional to verify eligibility and implementation details.',
        'employers_contact_title' => 'Build a family-friendly culture.',
        '_employers_defaults_seeded' => '1',
    );

    $privacy_meta = array(
        'privacy_last_updated' => 'October 1, 2024',
        'privacy_section1_title' => '1. Commitment to Privacy',
        'privacy_section1_content' => '<p class="mb-4">Chroma Early Start ("Chroma", "we", "us") respects your privacy. This policy outlines how we collect, use, and protect the personal information of families, patients, caregivers, and website visitors.</p>',
        'privacy_section2_title' => '2. Information Collection',
        'privacy_section2_content' => '<p class="mb-4">We collect information necessary for enrollment, safety, and communication, including:</p><ul class="list-disc pl-6 mb-4 space-y-2"><li>Student and parent names, addresses, and contact details.</li><li>Medical and immunization records required by state law.</li><li>Emergency contact information.</li><li>Digital media (photos/videos) for classroom documentation (with consent).</li></ul>',
        'privacy_section3_title' => '3. Digital Security',
        'privacy_section3_content' => '<p class="mb-4">We use secure platforms for student data and internal records. Access is limited to authorized team members, and operational systems are managed to protect sensitive information.</p>',
        'privacy_section4_title' => '4. Families\' Rights',
        'privacy_section4_content' => '<p class="mb-4">Early Start upholds the rights of all families to:</p><ul class="list-disc pl-6 mb-4 space-y-2"><li>Access their child\'s records upon request.</li><li>Refuse the use of their child\'s image in marketing materials.</li><li>Visit the center during operating hours under our open-door policy.</li></ul>',
        'privacy_section5_title' => '5. Contact Us',
        'privacy_section5_content' => '<p class="mb-4">If you have questions regarding this policy, please contact our corporate office at privacy@chromaearlystart.com.</p>',
        '_privacy_defaults_seeded' => '1',
    );

    update_option('earlystart_global_settings', array(
        'global_phone' => '(555) 123-4567',
        'global_email' => 'hello@chromaearlystart.com',
        'global_address' => "123 Wellness Blvd\nTherapy City, ST 12345",
        'global_facebook_url' => 'https://facebook.com/chromaearlystart',
        'global_instagram_url' => 'https://instagram.com/chromaearlystart',
    ));

    $home_services = array(
        array('id' => 'aba', 'title' => 'ABA Therapy', 'subtitle' => 'Applied Behavior Analysis', 'icon' => 'puzzle', 'heading' => 'Individualized Behavioral Growth', 'description' => 'Our ABA therapy focuses on increasing helpful behaviors and decreasing harmful ones to improve learning and social skills.', 'image' => 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&w=800&q=80&fm=webp', 'bullets' => array('One-on-one sessions', 'Data-driven progress tracking', 'Positive reinforcement focus')),
        array('id' => 'speech', 'title' => 'Speech Therapy', 'subtitle' => 'Communication Skills', 'icon' => 'message-circle', 'heading' => 'Unlocking Every Voice', 'description' => 'Specialized support for articulation, language comprehension, and social communication milestones.', 'image' => 'https://images.unsplash.com/photo-1544717305-27a734ef202e?auto=format&fit=crop&w=800&q=80&fm=webp', 'bullets' => array('Articulation support', 'Social language skills', 'Early intervention focus')),
        array('id' => 'ot', 'title' => 'Occupational Therapy', 'subtitle' => 'Daily Living Skills', 'icon' => 'hand', 'heading' => 'Developing Independence', 'description' => 'Helping children build the fine motor, sensory, and cognitive skills needed for daily life and play.', 'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=800&q=80&fm=webp', 'bullets' => array('Fine motor development', 'Sensory integration', 'Self-regulation tools')),
    );

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
                'home_services_json' => wp_json_encode($home_services),
                'home_faq_items_json' => wp_json_encode(array(
                    array('question' => 'What is ABA therapy?', 'answer' => 'Applied Behavior Analysis (ABA) is a scientifically validated approach to understanding behavior and how it is affected by the environment. At Chroma Early Start, we focus on assent-based, play-led ABA to build essential life skills safely and joyfully.'),
                    array('question' => 'How many hours of ABA are recommended?', 'answer' => 'Recommendations vary based on each child\'s unique needs and the initial assessment, typically ranging from 10 to 40 hours per week. Our goal is to prescribe the exact amount needed for meaningful progress, not a universally rigid schedule.'),
                    array('question' => 'What is parent involvement like?', 'answer' => 'Parents are our most critical partners. We provide regular parent training sessions, transparent data sharing, and open-door policies at all our clinics so you can seamlessly integrate strategies at home.'),
                    array('question' => 'When should a child start Speech Therapy?', 'answer' => 'Early intervention is key. If you notice your child is not meeting speech milestones, struggles to communicate their needs, or if their speech is not easily understood by others, an initial evaluation is highly recommended.'),
                    array('question' => 'Does Speech Therapy help with social skills?', 'answer' => 'Absolutely. Speech Therapy addresses social communication (pragmatics), helping children navigate conversations, read social cues, and build meaningful relationships with peers.'),
                    array('question' => 'What is the difference between fine motor and sensory OT?', 'answer' => 'Fine motor OT focuses on the small muscles used for tasks like writing, buttoning, and using utensils. Sensory OT helps children process and respond appropriately to their environment. We integrate both as needed.'),
                    array('question' => 'What is the age range for your OT services?', 'answer' => 'We provide specialized pediatric Occupational Therapy for children from 6 months up to 12 years of age, ensuring developmental support during their most critical years of growth.'),
                )),
            ),
        ),
        'about' => array('title' => 'About Us', 'template' => 'page-about.php', 'meta' => $about_meta),
        'our-approach' => array('title' => 'Our Approach', 'template' => 'page-approach.php'),
        'services' => array('title' => 'Services', 'template' => 'page-programs.php'),
        'locations' => array('title' => 'Locations', 'template' => 'page-locations.php'),
        'curriculum' => array('title' => 'Curriculum', 'template' => 'page-curriculum.php', 'meta' => $curriculum_meta),
        'parents' => array('title' => 'For Families', 'template' => 'page-families.php', 'meta' => $families_meta),
        'bridge-program' => array('title' => 'Early Start Bridge Program', 'template' => 'page-bridge-program.php', 'meta' => array('_wp_page_template' => 'page-bridge-program.php')),
        'consultation' => array('title' => 'Book Your Consultation', 'template' => 'page-consultation.php', 'meta' => array('_wp_page_template' => 'page-consultation.php')),
        'contact' => array('title' => 'Contact', 'template' => 'page-contact.php', 'meta' => $contact_meta),
        'careers' => array('title' => 'Careers', 'template' => 'page-careers.php', 'meta' => $careers_meta),
        'employers' => array('title' => 'Employers', 'template' => 'page-employers.php', 'meta' => $employers_meta),
        'privacy' => array('title' => 'Privacy Policy', 'template' => 'page-privacy.php', 'meta' => $privacy_meta),
        'faq' => array('title' => 'FAQ', 'template' => 'page-faq.php'),
        'newsroom' => array('title' => 'Newsroom', 'template' => 'page-newsroom.php'),
        'schedule-tour' => array('title' => 'Schedule a Tour', 'template' => 'page-schedule-tour.php'),
        'stories' => array('title' => 'Stories', 'template' => 'page-stories.php'),
        'team' => array('title' => 'Team', 'template' => 'page-team.php'),
        'terms' => array('title' => 'Terms of Service', 'template' => 'page-terms.php'),
        'acquisitions' => array('title' => 'Acquisitions', 'template' => 'page-acquisitions.php'),
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
                'page_template' => $page_data['template'],
            ));
        } else {
            $page_id = $existing_page->ID;
        }

        if ($page_id && !is_wp_error($page_id)) {
            if (!empty($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
            if (!empty($page_data['meta'])) {
                foreach ($page_data['meta'] as $key => $value) {
                    update_post_meta($page_id, $key, $value);
                }
            }
            if ($slug === 'home') {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
        }
    }

    $programs = array(
        'aba-therapy' => array('title' => 'ABA Therapy', 'excerpt' => 'Applied Behavior Analysis focused on positive reinforcement and measurable outcomes.', 'meta' => array('program_icon' => 'ABA', 'program_age_range' => '18mo - 12y', 'program_color_scheme' => 'red', 'program_hero_title' => 'Individualized Behavioral Growth.', 'program_hero_description' => 'Our ABA program is designed to build essential life skills through data-driven intervention.', 'program_prism_title' => 'Behavioral Core', 'program_prism_physical' => 40, 'program_prism_emotional' => 90, 'program_prism_social' => 80, 'program_prism_academic' => 30, 'program_prism_creative' => 50)),
        'speech-therapy' => array('title' => 'Speech Therapy', 'excerpt' => 'Helping children unlock their voice and communicate effectively with the world.', 'meta' => array('program_icon' => 'Speech', 'program_age_range' => '2y - 12y', 'program_color_scheme' => 'blue', 'program_hero_title' => 'Unlocking Every Voice.', 'program_hero_description' => 'Our specialized Speech Therapy program focuses on articulation, language comprehension, and social communication milestones to give your child the tools they need to connect.', 'program_prism_title' => 'Communication Core', 'program_prism_physical' => 20, 'program_prism_emotional' => 70, 'program_prism_social' => 95, 'program_prism_academic' => 60, 'program_prism_creative' => 80)),
        'occupational-therapy' => array('title' => 'Occupational Therapy', 'excerpt' => 'Building independence through fine motor, sensory, and self-regulation skills.', 'meta' => array('program_icon' => 'OT', 'program_age_range' => '6mo - 12y', 'program_color_scheme' => 'yellow', 'program_hero_title' => 'Developing Independence.', 'program_hero_description' => 'We help children build fine motor skills, integrate sensory experiences, and develop self-regulation tools necessary for daily life and self-confident play.', 'program_prism_title' => 'Motor & Sensory Core', 'program_prism_physical' => 90, 'program_prism_emotional' => 60, 'program_prism_social' => 40, 'program_prism_academic' => 50, 'program_prism_creative' => 70)),
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
                foreach ($data['meta'] as $key => $value) {
                    update_post_meta($post_id, $key, $value);
                }
            }
        }
    }

    $locations = array(
        'cherokee-academy' => array('title' => 'Cherokee Clinic', 'region' => 'Cherokee', 'address' => '1205 Upper Burris Road', 'city' => 'Canton', 'state' => 'GA', 'zip' => '30114', 'latitude' => '34.2368', 'longitude' => '-84.4908'),
        'east-cobb-campus' => array('title' => 'East Cobb Clinic', 'region' => 'Cobb', 'address' => '2499 Shallowford Road NE', 'city' => 'Marietta', 'state' => 'GA', 'zip' => '30066', 'latitude' => '34.0250', 'longitude' => '-84.4870'),
        'ellenwood-campus' => array('title' => 'Ellenwood Clinic', 'region' => 'Dekalb', 'address' => '2765 E Atlanta Road', 'city' => 'Ellenwood', 'state' => 'GA', 'zip' => '30294', 'latitude' => '33.6163', 'longitude' => '-84.2933', 'featured' => true),
        'johns-creek-campus' => array('title' => 'Johns Creek', 'region' => 'Fulton', 'address' => '3580 Old Alabama Road', 'city' => 'Johns Creek', 'state' => 'GA', 'zip' => '30005', 'latitude' => '34.0329', 'longitude' => '-84.1986', 'featured' => true),
        'jonesboro-campus' => array('title' => 'Jonesboro Clinic', 'region' => 'Clayton', 'address' => '1832 Noahs Ark Road', 'city' => 'Jonesboro', 'state' => 'GA', 'zip' => '30236', 'latitude' => '33.5215', 'longitude' => '-84.3538'),
        'lawrenceville-campus' => array('title' => 'Lawrenceville Clinic', 'region' => 'Gwinnett', 'address' => '3650 Club Drive', 'city' => 'Lawrenceville', 'state' => 'GA', 'zip' => '30044', 'latitude' => '33.9412', 'longitude' => '-84.0146'),
        'lilburn-campus' => array('title' => 'Lilburn Clinic', 'region' => 'Gwinnett', 'address' => '917 Killian Hill Road SW', 'city' => 'Lilburn', 'state' => 'GA', 'zip' => '30047', 'latitude' => '33.8901', 'longitude' => '-84.1421'),
        'mcdonough-campus' => array('title' => 'Mcdonough', 'region' => 'Henry', 'address' => '90 Hunters Chase', 'city' => 'Mcdonough', 'state' => 'GA', 'zip' => '30253', 'latitude' => '33.4473', 'longitude' => '-84.1469'),
        'midway-campus' => array('title' => 'Midway Clinic', 'region' => 'Fulton', 'address' => '4015 Discovery Drive', 'city' => 'Alpharetta', 'state' => 'GA', 'zip' => '30004', 'latitude' => '34.1570', 'longitude' => '-84.2577'),
        'newnan-campus' => array('title' => 'Newnan Clinic', 'region' => 'Coweta', 'address' => '40 Bledsoe Road', 'city' => 'Newnan', 'state' => 'GA', 'zip' => '30265', 'latitude' => '33.3946', 'longitude' => '-84.7477'),
        'north-hall-campus' => array('title' => 'North Hall Clinic, Murraysville', 'region' => 'Hall', 'address' => '5760 Wade Whelchel Road', 'city' => 'Murraysville', 'state' => 'GA', 'zip' => '30564', 'latitude' => '34.3387', 'longitude' => '-83.9007'),
        'pleasanthill-campus' => array('title' => 'Pleasanthill Clinic, Duluth', 'region' => 'Gwinnett', 'address' => '3152 Creek Drive NW', 'city' => 'Duluth', 'state' => 'GA', 'zip' => '30096', 'latitude' => '33.9609', 'longitude' => '-84.1226', 'featured' => true),
        'rivergreen-campus' => array('title' => 'Rivergreen Clinic', 'region' => 'Cherokee', 'address' => '200 River Green Avenue', 'city' => 'Canton', 'state' => 'GA', 'zip' => '30114', 'latitude' => '34.2357', 'longitude' => '-84.4707'),
        'roswell-campus' => array('title' => 'Roswell Clinic', 'region' => 'Fulton', 'address' => '1255 Upper Hembree Road', 'city' => 'Roswell', 'state' => 'GA', 'zip' => '30076', 'latitude' => '34.0362', 'longitude' => '-84.3310'),
        'satellite-blvd-campus' => array('title' => 'Satellite Blvd Clinic', 'region' => 'Gwinnett', 'address' => '3730 Satellite Boulevard', 'city' => 'Duluth', 'state' => 'GA', 'zip' => '30096', 'latitude' => '33.9895', 'longitude' => '-84.1360'),
        'south-cobb-campus' => array('title' => 'South Cobb Clinic, Austell', 'region' => 'Cobb', 'address' => '7225 Premier Lane', 'city' => 'Austell', 'state' => 'GA', 'zip' => '30168', 'latitude' => '33.7833', 'longitude' => '-84.6206'),
        'tramore-campus' => array('title' => 'Tramore Clinic', 'region' => 'Cobb', 'address' => '2081 Mesa Valley Way', 'city' => 'Austell', 'state' => 'GA', 'zip' => '30106', 'latitude' => '33.8182', 'longitude' => '-84.6264'),
        'tyrone-campus' => array('title' => 'Tyrone Clinic', 'region' => 'Fayette', 'address' => '291 Jenkins Road', 'city' => 'Tyrone', 'state' => 'GA', 'zip' => '30290', 'latitude' => '33.4718', 'longitude' => '-84.5916', 'featured' => true),
        'west-cobb-campus' => array('title' => 'West Cobb Clinic', 'region' => 'Cobb', 'address' => '2424 Powder Springs Road SW', 'city' => 'Marietta', 'state' => 'GA', 'zip' => '30064', 'latitude' => '33.9190', 'longitude' => '-84.5977', 'featured' => true),
    );

    foreach ($locations as $slug => $data) {
        $existing = get_page_by_path($slug, OBJECT, 'location');
        if (!$existing) {
            $existing = get_page_by_title($data['title'], OBJECT, 'location');
        }

        if ($existing) {
            $post_id = $existing->ID;
            wp_update_post(array('ID' => $post_id, 'post_title' => $data['title'], 'post_name' => $slug, 'post_status' => 'publish'));
        } else {
            $post_id = wp_insert_post(array('post_title' => $data['title'], 'post_name' => $slug, 'post_status' => 'publish', 'post_type' => 'location'));
        }

        if (!is_wp_error($post_id)) {
            foreach (earlystart_build_seed_location_meta($data) as $key => $value) {
                update_post_meta($post_id, $key, $value);
            }
            update_post_meta($post_id, 'location_featured', !empty($data['featured']) ? '1' : '');
            update_post_meta($post_id, 'location_quality_rated', !empty($data['quality_rated']) ? '1' : '');
            if (!empty($data['region'])) {
                wp_set_object_terms($post_id, $data['region'], 'location_region');
            }
        }
    }

    $team_members = array(
        'charanjeev-singh' => array('title' => 'Charanjeev Singh', 'meta' => array('team_member_title' => 'Chief Executive Officer')),
        'pam-rossman' => array('title' => 'Pam Rossman', 'meta' => array('team_member_title' => 'Chief Operating Officer')),
        'jazmine-johnson' => array('title' => 'Jazmine Johnson', 'content' => 'Jazmine Johnson, BCBA, LBA, is a Licensed Board Certified Behavior Analyst specializing in autism services, early intervention, behavior reduction, and comprehensive caregiver and staff training. With extensive experience across home, clinic, and school settings, she is dedicated to delivering high-quality, evidence-based Applied Behavior Analysis (ABA) services that create meaningful and lasting change.', 'meta' => array('team_member_title' => 'BCBA, LBA')),
        'tymichia-mcgill' => array('title' => 'Tymichia Mcgill', 'content' => '', 'meta' => array('team_member_title' => 'BCBA')),
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
            $slug_with_state = $slug . '-ga';
            $existing = get_page_by_path($slug_with_state, OBJECT, 'city');
            if (!$existing) {
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
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_content' => sprintf(
                            '<!-- wp:paragraph --><p>Welcome to our early learning resources for families in <strong>%s, GA</strong> (%s County). At Early Start, we are dedicated to providing compassionate, evidence-based therapy and support.</p><!-- /wp:paragraph -->',
                            $city_name,
                            $county
                        ),
                    ));
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
