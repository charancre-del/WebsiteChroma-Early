<?php
/**
 * Template Name: Privacy Policy
 *
 * Privacy & Families' Rights Policy page template using theme header/footer
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

get_header();

$page_id = get_the_ID();

// Get last updated date
$last_updated = earlystart_get_translated_meta($page_id, 'privacy_last_updated') ?: 'December 26, 2024';

// Default content if no sections are set
$default_sections = array(
  array(
    'title' => __('Information We Collect', 'chroma-early-start'),
    'content' => '<p>' . __('At Chroma Early Learning Academy, we collect information necessary to provide excellent childcare services to your family. This includes:', 'chroma-early-start') . '</p>
        <ul>
            <li><strong>' . __('Contact Information:', 'chroma-early-start') . '</strong> ' . __('Names, addresses, phone numbers, and email addresses of parents/guardians', 'chroma-early-start') . '</li>
            <li><strong>' . __('Child Information:', 'chroma-early-start') . '</strong> ' . __('Child\'s name, date of birth, allergies, medical conditions, and emergency contacts', 'chroma-early-start') . '</li>
            <li><strong>' . __('Enrollment Data:', 'chroma-early-start') . '</strong> ' . __('Program preferences, schedules, and payment information', 'chroma-early-start') . '</li>
            <li><strong>' . __('Website Usage:', 'chroma-early-start') . '</strong> ' . __('Cookies and analytics data when you visit our website', 'chroma-early-start') . '</li>
        </ul>'
  ),
  array(
    'title' => __('How We Use Your Information', 'chroma-early-start'),
    'content' => '<p>' . __('We use the information we collect to:', 'chroma-early-start') . '</p>
        <ul>
            <li>' . __('Provide safe, quality childcare services', 'chroma-early-start') . '</li>
            <li>' . __('Communicate with you about your child\'s care and development', 'chroma-early-start') . '</li>
            <li>' . __('Process enrollment applications and payments', 'chroma-early-start') . '</li>
            <li>' . __('Comply with state licensing requirements (Georgia DECAL)', 'chroma-early-start') . '</li>
            <li>' . __('Improve our programs and services', 'chroma-early-start') . '</li>
            <li>' . __('Send occasional newsletters and updates (you may opt out at any time)', 'chroma-early-start') . '</li>
        </ul>'
  ),
  array(
    'title' => __('Information Security', 'chroma-early-start'),
    'content' => '<p>' . __('We take the security of your personal information seriously. We implement appropriate technical and organizational measures to protect your data, including:', 'chroma-early-start') . '</p>
        <ul>
            <li>' . __('Secure, encrypted storage of sensitive information', 'chroma-early-start') . '</li>
            <li>' . __('Limited access to personal data on a need-to-know basis', 'chroma-early-start') . '</li>
            <li>' . __('Regular staff training on privacy and data protection', 'chroma-early-start') . '</li>
            <li>' . __('Physical security measures at all our locations', 'chroma-early-start') . '</li>
        </ul>
        <p>' . __('While we strive to protect your information, no method of transmission over the Internet is 100% secure. We cannot guarantee absolute security.', 'chroma-early-start') . '</p>'
  ),
  array(
    'title' => __('Your Rights', 'chroma-early-start'),
    'content' => '<p>' . __('As a parent or guardian enrolled with Chroma Early Learning Academy, you have the right to:', 'chroma-early-start') . '</p>
        <ul>
            <li><strong>' . __('Access:', 'chroma-early-start') . '</strong> ' . __('Request a copy of the personal information we hold about you and your child', 'chroma-early-start') . '</li>
            <li><strong>' . __('Correction:', 'chroma-early-start') . '</strong> ' . __('Request corrections to any inaccurate information', 'chroma-early-start') . '</li>
            <li><strong>' . __('Deletion:', 'chroma-early-start') . '</strong> ' . __('Request deletion of your data, subject to legal retention requirements', 'chroma-early-start') . '</li>
            <li><strong>' . __('Opt-Out:', 'chroma-early-start') . '</strong> ' . __('Unsubscribe from marketing communications at any time', 'chroma-early-start') . '</li>
        </ul>
        <p>' . __('To exercise any of these rights, please contact your center director or email us at privacy@chromaela.com.', 'chroma-early-start') . '</p>'
  ),
  array(
    'title' => __('Contact Us', 'chroma-early-start'),
    'content' => '<p>' . __('If you have any questions about this Privacy Policy or our data practices, please contact us:', 'chroma-early-start') . '</p>
        <p><strong>' . __('Chroma Early Learning Academy', 'chroma-early-start') . '</strong><br>
        ' . __('Email: privacy@chromaela.com', 'chroma-early-start') . '<br>
        ' . __('Phone: (404) 800-8000', 'chroma-early-start') . '</p>
        <p>' . __('This policy may be updated from time to time. We will notify you of any material changes by posting the new policy on this page with an updated "Last Updated" date.', 'chroma-early-start') . '</p>'
  ),
);

// Get stored sections or use defaults
$sections = array();
$has_custom_content = false;
for ($i = 1; $i <= 5; $i++) {
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

// If no custom content, use defaults
if (!$has_custom_content) {
  $sections = $default_sections;
}
?>

<main class="min-h-screen bg-brand-cream py-24">
  <div class="max-w-3xl mx-auto px-4 lg:px-6">
    <h1 class="font-serif text-4xl md:text-5xl font-bold text-brand-ink mb-8"><?php the_title(); ?></h1>
    <p class="text-sm text-brand-ink/60 mb-12"><?php _e('Last Updated:', 'chroma-early-start'); ?> <?php echo esc_html($last_updated); ?></p>

    <div class="prose prose-lg text-brand-ink/80 max-w-none">
      <p class="text-lg leading-relaxed mb-8">
        <?php _e('Chroma Early Learning Academy ("we," "us," or "our") is committed to protecting your privacy and that of your children. This Privacy Policy explains how we collect, use, and safeguard your personal information.', 'chroma-early-start'); ?>
      </p>

      <?php foreach ($sections as $section): ?>
        <?php if (!empty($section['title'])): ?>
          <h2 class="font-serif font-bold text-2xl text-brand-ink mt-12 mb-4"><?php echo esc_html($section['title']); ?>
          </h2>
          <?php if (!empty($section['content'])): ?>
            <div class="privacy-section-content space-y-4">
              <?php echo wp_kses_post($section['content']); ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="mt-16 pt-8 border-t border-chroma-blue/20">
      <a href="<?php echo esc_url(home_url('/terms-of-service/')); ?>" class="text-chroma-blue hover:underline">
        <?php _e('View our Terms of Service →', 'chroma-early-start'); ?>
      </a>
    </div>
  </div>
</main>

<?php get_footer(); ?>

