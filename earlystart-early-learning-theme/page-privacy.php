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
    'title' => __('Information We Collect', 'earlystart-early-learning'),
    'content' => '<p>' . __('At Early Start Early Learning Academy, we collect information necessary to provide excellent childcare services to your family. This includes:', 'earlystart-early-learning') . '</p>
        <ul>
            <li><strong>' . __('Contact Information:', 'earlystart-early-learning') . '</strong> ' . __('Names, addresses, phone numbers, and email addresses of parents/guardians', 'earlystart-early-learning') . '</li>
            <li><strong>' . __('Child Information:', 'earlystart-early-learning') . '</strong> ' . __('Child\'s name, date of birth, allergies, medical conditions, and emergency contacts', 'earlystart-early-learning') . '</li>
            <li><strong>' . __('Enrollment Data:', 'earlystart-early-learning') . '</strong> ' . __('Program preferences, schedules, and payment information', 'earlystart-early-learning') . '</li>
            <li><strong>' . __('Website Usage:', 'earlystart-early-learning') . '</strong> ' . __('Cookies and analytics data when you visit our website', 'earlystart-early-learning') . '</li>
        </ul>'
  ),
  array(
    'title' => __('How We Use Your Information', 'earlystart-early-learning'),
    'content' => '<p>' . __('We use the information we collect to:', 'earlystart-early-learning') . '</p>
        <ul>
            <li>' . __('Provide safe, quality childcare services', 'earlystart-early-learning') . '</li>
            <li>' . __('Communicate with you about your child\'s care and development', 'earlystart-early-learning') . '</li>
            <li>' . __('Process enrollment applications and payments', 'earlystart-early-learning') . '</li>
            <li>' . __('Comply with state licensing requirements (Georgia DECAL)', 'earlystart-early-learning') . '</li>
            <li>' . __('Improve our programs and services', 'earlystart-early-learning') . '</li>
            <li>' . __('Send occasional newsletters and updates (you may opt out at any time)', 'earlystart-early-learning') . '</li>
        </ul>'
  ),
  array(
    'title' => __('Information Security', 'earlystart-early-learning'),
    'content' => '<p>' . __('We take the security of your personal information seriously. We implement appropriate technical and organizational measures to protect your data, including:', 'earlystart-early-learning') . '</p>
        <ul>
            <li>' . __('Secure, encrypted storage of sensitive information', 'earlystart-early-learning') . '</li>
            <li>' . __('Limited access to personal data on a need-to-know basis', 'earlystart-early-learning') . '</li>
            <li>' . __('Regular staff training on privacy and data protection', 'earlystart-early-learning') . '</li>
            <li>' . __('Physical security measures at all our locations', 'earlystart-early-learning') . '</li>
        </ul>
        <p>' . __('While we strive to protect your information, no method of transmission over the Internet is 100% secure. We cannot guarantee absolute security.', 'earlystart-early-learning') . '</p>'
  ),
  array(
    'title' => __('Your Rights', 'earlystart-early-learning'),
    'content' => '<p>' . __('As a parent or guardian enrolled with Early Start Early Learning Academy, you have the right to:', 'earlystart-early-learning') . '</p>
        <ul>
            <li><strong>' . __('Access:', 'earlystart-early-learning') . '</strong> ' . __('Request a copy of the personal information we hold about you and your child', 'earlystart-early-learning') . '</li>
            <li><strong>' . __('Correction:', 'earlystart-early-learning') . '</strong> ' . __('Request corrections to any inaccurate information', 'earlystart-early-learning') . '</li>
            <li><strong>' . __('Deletion:', 'earlystart-early-learning') . '</strong> ' . __('Request deletion of your data, subject to legal retention requirements', 'earlystart-early-learning') . '</li>
            <li><strong>' . __('Opt-Out:', 'earlystart-early-learning') . '</strong> ' . __('Unsubscribe from marketing communications at any time', 'earlystart-early-learning') . '</li>
        </ul>
        <p>' . __('To exercise any of these rights, please contact your center director or email us at privacy@chromaela.com.', 'earlystart-early-learning') . '</p>'
  ),
  array(
    'title' => __('Contact Us', 'earlystart-early-learning'),
    'content' => '<p>' . __('If you have any questions about this Privacy Policy or our data practices, please contact us:', 'earlystart-early-learning') . '</p>
        <p><strong>' . __('Early Start Early Learning Academy', 'earlystart-early-learning') . '</strong><br>
        ' . __('Email: privacy@chromaela.com', 'earlystart-early-learning') . '<br>
        ' . __('Phone: (404) 800-8000', 'earlystart-early-learning') . '</p>
        <p>' . __('This policy may be updated from time to time. We will notify you of any material changes by posting the new policy on this page with an updated "Last Updated" date.', 'earlystart-early-learning') . '</p>'
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
    <p class="text-sm text-brand-ink/60 mb-12"><?php _e('Last Updated:', 'earlystart-early-learning'); ?> <?php echo esc_html($last_updated); ?></p>

    <div class="prose prose-lg text-brand-ink/80 max-w-none">
      <p class="text-lg leading-relaxed mb-8">
        <?php _e('Early Start Early Learning Academy ("we," "us," or "our") is committed to protecting your privacy and that of your children. This Privacy Policy explains how we collect, use, and safeguard your personal information.', 'earlystart-early-learning'); ?>
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
      <a href="<?php echo esc_url(earlystart_get_page_link('terms')); ?>" class="text-chroma-blue hover:underline">
        <?php _e('View our Terms of Service →', 'earlystart-early-learning'); ?>
      </a>
    </div>
  </div>
</main>

<?php get_footer(); ?>

