<?php
/**
 * Template Name: Terms of Use
 *
 * Terms of Use page template
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

get_header();

$page_id = get_the_ID();

// Maintain backward compatibility with older tos_* meta keys.
$last_updated = earlystart_get_translated_meta($page_id, 'tou_last_updated')
    ?: earlystart_get_translated_meta($page_id, 'tos_last_updated')
    ?: 'December 26, 2024';

// Default Terms of Use content.
$default_sections = array(
    array(
        'title' => __('Acceptance of Terms', 'earlystart-early-learning'),
        'content' => '<p>' . __('By accessing our website, contacting us, or using our services, you agree to these Terms of Use. If you do not agree, do not use the website or services.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Scope of Services', 'earlystart-early-learning'),
        'content' => '<p>' . __('Chroma Early Start provides pediatric therapy and related support services. Availability, eligibility, and care plans vary by child, location, and applicable payor requirements.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Website Content', 'earlystart-early-learning'),
        'content' => '<p>' . __('Website content is for general information only and is not medical or legal advice. Service details may change without notice.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Enrollment and Accounts', 'earlystart-early-learning'),
        'content' => '<p>' . __('You agree to provide accurate and current information during enrollment and communications. You are responsible for keeping contact and emergency details up to date.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Payments and Financial Responsibility', 'earlystart-early-learning'),
        'content' => '<p>' . __('Families are responsible for charges not covered by insurance, grants, or other programs, as described in signed enrollment or service agreements.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Permitted Use', 'earlystart-early-learning'),
        'content' => '<p>' . __('You may use this site only for lawful purposes. You may not attempt to interfere with site operation, gain unauthorized access, or misuse site content.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Intellectual Property', 'earlystart-early-learning'),
        'content' => '<p>' . __('Unless otherwise stated, website content and branding are owned by Chroma Early Start or its licensors and may not be reused without permission.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Disclaimer and Limitation of Liability', 'earlystart-early-learning'),
        'content' => '<p>' . __('The website is provided on an as-is and as-available basis. To the fullest extent permitted by law, Chroma Early Start disclaims warranties and limits liability for damages arising from site use.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Governing Law', 'earlystart-early-learning'),
        'content' => '<p>' . __('These Terms are governed by applicable state and federal law in the jurisdiction where services are provided, unless a signed agreement states otherwise.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Contact Information', 'earlystart-early-learning'),
        'content' => '<p><strong>' . __('Chroma Early Start', 'earlystart-early-learning') . '</strong><br>
        ' . __('Email: info@chromaearlystart.com', 'earlystart-early-learning') . '<br>
        ' . __('Phone: (404) 800-8000', 'earlystart-early-learning') . '</p>'
    ),
);

// Get stored sections or use defaults.
$sections = array();
$has_custom_content = false;
for ($i = 1; $i <= 12; $i++) {
    $title = earlystart_get_translated_meta($page_id, "tou_section{$i}_title");
    $content = earlystart_get_translated_meta($page_id, "tou_section{$i}_content");

    if (empty($title) && empty($content)) {
        $title = earlystart_get_translated_meta($page_id, "tos_section{$i}_title");
        $content = earlystart_get_translated_meta($page_id, "tos_section{$i}_content");
    }

    if (!empty($title) || !empty($content)) {
        $has_custom_content = true;
        $sections[] = array(
            'title' => $title,
            'content' => $content,
        );
    }
}

if (!$has_custom_content) {
    $sections = $default_sections;
}

$privacy_url = earlystart_get_link_by_slug('privacy-policy', 'page');
if (!$privacy_url) {
    $privacy_url = earlystart_get_link_by_slug('privacy', 'page');
}
if (!$privacy_url) {
    $privacy_url = earlystart_get_page_link('privacy-policy');
}

$hipaa_url = earlystart_get_link_by_slug('hipaa', 'page');
if (!$hipaa_url) {
    $hipaa_url = earlystart_get_page_link('hipaa');
}
?>

<main id="primary" class="bg-stone-50 min-h-screen">
    <section class="relative bg-white pt-24 pb-20 lg:pt-32 border-b border-stone-100 overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[520px] h-[520px] bg-amber-50 rounded-full blur-3xl opacity-60"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="inline-block px-4 py-2 bg-amber-50 text-amber-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
                <?php _e('Legal & Compliance', 'earlystart-early-learning'); ?>
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up"><?php the_title(); ?></h1>
            <p class="text-xl text-stone-700 max-w-3xl mx-auto leading-relaxed fade-in-up">
                <?php _e('These Terms of Use govern use of Chroma Early Start services and website content.', 'earlystart-early-learning'); ?>
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
                                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 text-sm font-bold flex items-center justify-center shrink-0">
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
                    <a href="<?php echo esc_url($privacy_url); ?>" class="rounded-2xl border border-white/15 bg-white/5 px-5 py-4 font-bold hover:bg-white/10 transition-colors">
                        <?php _e('View Privacy Policy', 'earlystart-early-learning'); ?>
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
