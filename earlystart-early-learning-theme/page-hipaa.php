<?php
/**
 * Template Name: HIPAA Notice
 *
 * HIPAA Notice of Privacy Practices page template
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

get_header();

$page_id = get_the_ID();

$last_updated = earlystart_get_translated_meta($page_id, 'hipaa_last_updated') ?: 'December 26, 2024';

$default_sections = array(
    array(
        'title' => __('Notice of Privacy Practices', 'earlystart-early-learning'),
        'content' => '<p>' . __('This notice describes how medical and protected health information (PHI) about you or your child may be used and disclosed and how you can get access to this information. Please review it carefully.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Our Legal Duties', 'earlystart-early-learning'),
        'content' => '<p>' . __('Chroma Early Start is required by law to maintain the privacy of PHI, provide this notice of legal duties and privacy practices, and follow the terms of the notice currently in effect.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('How We May Use and Disclose PHI', 'earlystart-early-learning'),
        'content' => '<p>' . __('We may use and disclose PHI without your written authorization for treatment, payment, and health care operations, and in other situations permitted or required by law.', 'earlystart-early-learning') . '</p>
        <ul class="list-disc pl-6 mb-4 space-y-2">
            <li>' . __('Treatment: to coordinate clinical care and related services', 'earlystart-early-learning') . '</li>
            <li>' . __('Payment: to bill and receive payment from health plans or other payors', 'earlystart-early-learning') . '</li>
            <li>' . __('Operations: to improve quality, staff training, compliance, and audits', 'earlystart-early-learning') . '</li>
        </ul>'
    ),
    array(
        'title' => __('Uses Requiring Authorization', 'earlystart-early-learning'),
        'content' => '<p>' . __('For certain uses and disclosures, including most marketing purposes and disclosures not otherwise permitted by HIPAA, we will request your written authorization. You may revoke authorization at any time in writing, subject to legal limits.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Your HIPAA Rights', 'earlystart-early-learning'),
        'content' => '<p>' . __('You have rights regarding PHI, including the right to request access, amendments, an accounting of disclosures, and restrictions, and to request confidential communications.', 'earlystart-early-learning') . '</p>
        <p>' . __('Some requests may be denied as allowed by law, but you will receive a written response.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Breach Notification', 'earlystart-early-learning'),
        'content' => '<p>' . __('If a breach of unsecured PHI occurs, we will provide notice in accordance with applicable law and regulatory requirements.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Changes to This Notice', 'earlystart-early-learning'),
        'content' => '<p>' . __('We reserve the right to change this notice and make revised terms effective for all PHI we maintain. The current notice will be posted on this page with an updated date.', 'earlystart-early-learning') . '</p>'
    ),
    array(
        'title' => __('Questions or Complaints', 'earlystart-early-learning'),
        'content' => '<p>' . __('If you have questions or wish to file a privacy complaint, contact us at privacy@chromaearlystart.com. You may also file a complaint with the U.S. Department of Health and Human Services. You will not be retaliated against for filing a complaint.', 'earlystart-early-learning') . '</p>'
    ),
);

$sections = array();
$has_custom_content = false;
for ($i = 1; $i <= 8; $i++) {
    $title = earlystart_get_translated_meta($page_id, "hipaa_section{$i}_title");
    $content = earlystart_get_translated_meta($page_id, "hipaa_section{$i}_content");

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

$terms_url = earlystart_get_link_by_slug('terms', 'page');
if (!$terms_url) {
    $terms_url = earlystart_get_page_link('terms');
}
?>

<main id="primary" class="bg-stone-50 min-h-screen">
    <section class="relative bg-white pt-24 pb-20 lg:pt-32 border-b border-stone-100 overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[520px] h-[520px] bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="inline-block px-4 py-2 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
                <?php _e('HIPAA Compliance', 'earlystart-early-learning'); ?>
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up"><?php the_title(); ?></h1>
            <p class="text-xl text-stone-700 max-w-3xl mx-auto leading-relaxed fade-in-up">
                <?php _e('This HIPAA Notice explains how protected health information is used, disclosed, and safeguarded by Chroma Early Start.', 'earlystart-early-learning'); ?>
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
                                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-bold flex items-center justify-center shrink-0">
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
                    <a href="<?php echo esc_url($terms_url); ?>" class="rounded-2xl border border-white/15 bg-white/5 px-5 py-4 font-bold hover:bg-white/10 transition-colors">
                        <?php _e('View Terms of Use', 'earlystart-early-learning'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
