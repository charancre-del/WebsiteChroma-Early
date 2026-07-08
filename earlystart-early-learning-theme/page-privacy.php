<?php
/**
 * Template Name: Privacy Policy
 *
 * Privacy Policy page template
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

get_header();

$page_id = get_the_ID();

// Get last updated date.
$last_updated = earlystart_get_translated_meta($page_id, 'privacy_last_updated') ?: 'December 26, 2024';

$global_privacy_email = function_exists('earlystart_global_privacy_email') ? earlystart_global_privacy_email() : (function_exists('earlystart_global_email') ? earlystart_global_email() : '');
$global_contact_phone = function_exists('earlystart_global_phone') ? earlystart_global_phone() : '';

// Default content if no sections are set.
$default_sections = array(
    array(
        'title' => __('Information We Collect', 'earlystart-early-learning'),
        'content' => '<p>' . __('We collect information needed to provide pediatric services and operate our website. Depending on your interaction with us, this may include contact details, scheduling information, billing data, and clinical information that may be protected health information (PHI).', 'earlystart-early-learning') . '</p>
        <ul class="list-disc pl-6 mb-4 space-y-2">
            <li>' . __('Parent and guardian contact information', 'earlystart-early-learning') . '</li>
            <li>' . __('Child profile and service-related records', 'earlystart-early-learning') . '</li>
            <li>' . __('Communication preferences and appointment history', 'earlystart-early-learning') . '</li>
            <li>' . __('Website analytics and device/browser data', 'earlystart-early-learning') . '</li>
        </ul>'
    ),
    array(
        'title' => __('How We Use Information', 'earlystart-early-learning'),
        'content' => '<p>' . __('We use collected information to provide care, coordinate services, process payments, improve operations, and communicate with families about services and updates.', 'earlystart-early-learning') . '</p>
        <p>' . __('If information qualifies as PHI, we handle that information as described in our HIPAA Notice of Privacy Practices.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('How We Share Information', 'earlystart-early-learning'),
        'content' => '<p>' . __('We do not sell personal information. We may share information with service providers, referral partners, insurers, and authorities only as permitted or required by law, contract, or your authorization.', 'earlystart-early-learning') . '</p>
        <ul class="list-disc pl-6 mb-4 space-y-2">
            <li>' . __('Operational vendors supporting scheduling, billing, and communications', 'earlystart-early-learning') . '</li>
            <li>' . __('Insurers or payors when required for claims and authorizations', 'earlystart-early-learning') . '</li>
            <li>' . __('Government agencies when disclosure is legally required', 'earlystart-early-learning') . '</li>
        </ul>'
    ),
    array(
        'title' => __('Data Security and Retention', 'earlystart-early-learning'),
        'content' => '<p>' . __('We use administrative, technical, and physical safeguards designed to protect information. No system is completely secure, but we apply reasonable security controls and keep records according to legal and operational retention requirements.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Your Choices and Rights', 'earlystart-early-learning'),
        'content' => '<p>' . __('You may request access to, correction of, or updates to your information. You may also opt out of non-essential marketing communications. Rights related to PHI are detailed in our HIPAA Notice.', 'earlystart-early-learning') . '</p>
        <p>' . sprintf(esc_html__('To make a request, contact us at %s.', 'earlystart-early-learning'), esc_html($global_privacy_email)) . '</p>'
    ),
    array(
        'title' => __('Children and Family Information', 'earlystart-early-learning'),
        'content' => '<p>' . __('Our services are family-centered and involve data about minors. We collect and use child-related information only as needed to provide services, satisfy legal obligations, and maintain safe operations.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Policy Updates', 'earlystart-early-learning'),
        'content' => '<p>' . __('We may update this Privacy Policy periodically. Material updates are posted on this page with a revised Last Updated date.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Contact Us', 'earlystart-early-learning'),
        'content' => '<p><strong>' . __('Chroma Early Start', 'earlystart-early-learning') . '</strong><br>
        ' . sprintf(esc_html__('Email: %s', 'earlystart-early-learning'), esc_html($global_privacy_email)) . '<br>
        ' . sprintf(esc_html__('Phone: %s', 'earlystart-early-learning'), esc_html($global_contact_phone)) . '</p>'
    ),
);

// Get stored sections or use defaults.
$sections = array();
$has_custom_content = false;
for ($i = 1; $i <= 8; $i++) {
    $title = earlystart_get_translated_meta($page_id, "privacy_section{$i}_title");
    $content = earlystart_get_translated_meta($page_id, "privacy_section{$i}_content");

    if (!empty($title) || !empty($content)) {
        $has_custom_content = true;
        $sections[] = array(
            'title' => $title,
            'content' => $content,
        );
    }
}

// If no custom content, use defaults.
if (!$has_custom_content) {
    $sections = $default_sections;
}

$terms_url = earlystart_get_link_by_slug('terms', 'page');
if (!$terms_url) {
    $terms_url = earlystart_get_page_link('terms');
}

$hipaa_url = earlystart_get_link_by_slug('hipaa', 'page');
if (!$hipaa_url) {
    $hipaa_url = earlystart_get_page_link('hipaa');
}

$required_a2p_sections = array(
    array(
        'title' => __('Information We Collect', 'earlystart-early-learning'),
        'match' => array('information we collect', 'information collection'),
        'content' => '<p>' . __('We may collect information you provide through our website, forms, phone calls, emails, SMS messages, referrals, and service interactions. This may include names, email addresses, phone numbers, child or family information, service interests, appointment or intake details, insurance or payment-related information, communication preferences, SMS opt-in status and consent records, and any message content you choose to send us.', 'earlystart-early-learning') . '</p>
        <p>' . __('We may also collect website and device information such as IP address, browser type, pages visited, referral source, approximate location, and cookie or analytics identifiers.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('How We Use Information', 'earlystart-early-learning'),
        'match' => array('how we use', 'use information'),
        'content' => '<p>' . __('We use information to respond to inquiries, schedule and coordinate services, provide pediatric therapy and related support, communicate with families and referral partners, process administrative requests, maintain records, comply with legal obligations, improve our website and operations, and send SMS communications only when a user has opted in or when otherwise permitted by law.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('SMS Communications and Opt-In Data', 'earlystart-early-learning'),
        'match' => array('sms', 'opt-in'),
        'required_text' => 'will not be sold, rented, or shared',
        'content' => '<p>' . __('If you choose to opt in to SMS communications, Chroma Early Start may use your phone number and consent record to send inquiry follow-up, appointment reminders, care coordination messages, service updates, and other messages related to your request or services.', 'earlystart-early-learning') . '</p>
        <p><strong>' . __('SMS opt-in is optional and is not required to submit an inquiry or receive services.', 'earlystart-early-learning') . '</strong> ' . __('You may opt out at any time by replying STOP to a text message, and you may request help by replying HELP or contacting us directly.', 'earlystart-early-learning') . '</p>
        <p>' . __('Message frequency varies based on your request and relationship with us. Message and data rates may apply.', 'earlystart-early-learning') . '</p>
        <p><strong>' . __('No sharing statement:', 'earlystart-early-learning') . '</strong> ' . __('SMS opt-in data, consent records, and mobile phone numbers collected for SMS purposes will not be sold, rented, or shared with third parties or affiliates for their marketing or promotional purposes.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Cookies, Analytics, and Tracking', 'earlystart-early-learning'),
        'match' => array('cookie', 'tracking', 'analytics'),
        'content' => '<p>' . __('Our website may use cookies, pixels, analytics tools, and similar technologies to understand site usage, improve performance, measure marketing effectiveness, and support security. These tools may collect device, browser, IP address, referral, page interaction, and approximate location data.', 'earlystart-early-learning') . '</p>
        <p>' . __('You can control cookies through your browser settings. Some site features may not work as intended if cookies are disabled.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Managing Your Information and Communication Preferences', 'earlystart-early-learning'),
        'match' => array('unsubscribe', 'communication preferences'),
        'content' => '<p>' . __('You may ask us to update, correct, or delete information where applicable, and you may unsubscribe from non-essential communications. For SMS messages, reply STOP to opt out. For privacy requests, contact us using the information on this page.', 'earlystart-early-learning') . '</p>
        <p>' . __('We use reasonable administrative, technical, and physical safeguards to protect personal information, including access controls and vendor management practices. No electronic system can be guaranteed completely secure.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Data Security Practices', 'earlystart-early-learning'),
        'match' => array('data security', 'security practices', 'digital security'),
        'content' => '<p>' . __('We use reasonable administrative, technical, and physical safeguards designed to protect personal information from unauthorized access, disclosure, alteration, or destruction. These practices may include access controls, vendor oversight, secure systems, staff training, and retention practices appropriate to the information involved.', 'earlystart-early-learning') . '</p>'
    ),
);

$existing_section_titles = array_map(static function ($section) {
    return strtolower(trim((string) ($section['title'] ?? '')));
}, $sections);
$existing_section_content = strtolower(wp_strip_all_tags(wp_json_encode($sections)));

foreach ($required_a2p_sections as $required_section) {
    $already_present = false;
    if (!empty($required_section['required_text'])) {
        $already_present = strpos($existing_section_content, strtolower((string) $required_section['required_text'])) !== false;
    } else {
        foreach ($existing_section_titles as $existing_title) {
            foreach ((array) $required_section['match'] as $keyword) {
                if ($existing_title !== '' && strpos($existing_title, $keyword) !== false) {
                    $already_present = true;
                    break 2;
                }
            }
        }
    }
    if (!$already_present) {
        unset($required_section['match']);
        unset($required_section['required_text']);
        $sections[] = $required_section;
        $existing_section_titles[] = strtolower(trim((string) $required_section['title']));
        $existing_section_content .= ' ' . strtolower(wp_strip_all_tags((string) $required_section['content']));
    }
}
?>

<main id="primary" class="bg-stone-50 min-h-screen">
    <section class="relative bg-white pt-24 pb-20 lg:pt-32 border-b border-stone-100 overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[520px] h-[520px] bg-rose-50 rounded-full blur-3xl opacity-60"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="inline-block px-4 py-2 bg-rose-50 text-rose-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
                <?php _e('Legal & Compliance', 'earlystart-early-learning'); ?>
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up"><?php the_title(); ?></h1>
            <p class="text-xl text-stone-700 max-w-3xl mx-auto leading-relaxed fade-in-up">
                <?php _e('This Privacy Policy explains how Chroma Early Start collects, uses, and safeguards personal information when families access our services or website.', 'earlystart-early-learning'); ?>
            </p>
            <p class="mt-8 inline-flex items-center rounded-full bg-stone-100 px-5 py-2 text-xs font-bold tracking-widest uppercase text-stone-700">
                <?php _e('Last Updated:', 'earlystart-early-learning'); ?> <?php echo esc_html($last_updated); ?>
            </p>
        </div>
    </section>

    <section class="py-20 border-b border-stone-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-5">
                <?php foreach ($sections as $index => $section): ?>
                    <?php if (!empty($section['title'])): ?>
                        <article class="rounded-[2rem] border border-stone-100 bg-white p-8 shadow-sm fade-in-up">
                            <div class="flex items-center gap-4 mb-5">
                                <span class="w-10 h-10 rounded-xl bg-rose-50 text-rose-700 text-sm font-bold flex items-center justify-center shrink-0">
                                    <?php echo esc_html((string) ($index + 1)); ?>
                                </span>
                                <h2 class="text-2xl md:text-3xl font-bold text-stone-900"><?php echo esc_html($section['title']); ?></h2>
                            </div>
                            <?php if (!empty($section['content'])): ?>
                                <div class="legal-content prose prose-lg text-stone-700 max-w-none">
                                    <?php echo wp_kses_post($section['content']); ?>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2.5rem] border border-stone-100 bg-stone-900 p-8 md:p-10 text-white">
                <h2 class="text-3xl font-bold mb-3"><?php _e('Related Policies', 'earlystart-early-learning'); ?></h2>
                <p class="text-stone-300 mb-8"><?php _e('Review the full set of legal notices that apply to our services and website.', 'earlystart-early-learning'); ?></p>
                <div class="grid sm:grid-cols-2 gap-4">
                    <a href="<?php echo esc_url($terms_url); ?>" class="rounded-2xl border border-white/15 bg-white/5 px-5 py-4 font-bold hover:bg-white/10 transition-colors">
                        <?php _e('View Terms of Use', 'earlystart-early-learning'); ?>
                    </a>
                    <a href="<?php echo esc_url($hipaa_url); ?>" class="rounded-2xl border border-white/15 bg-white/5 px-5 py-4 font-bold hover:bg-white/10 transition-colors">
                        <?php _e('View HIPAA Notice', 'earlystart-early-learning'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
