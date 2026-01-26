<?php
/**
 * Template Name: Newsroom
 *
 * Displays posts with "Show in Newsroom" checked
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit;
}

// Query posts with "Show in Newsroom" checked
$newsroom_args = array(
  'post_type' => 'post',
  'posts_per_page' => -1, // Show all newsroom posts
  'post_status' => 'publish',
  'orderby' => 'date',
  'order' => 'DESC',
  'meta_query' => array(
    array(
      'key' => '_earlystart_show_in_newsroom',
      'value' => '1',
      'compare' => '='
    )
  )
);

$newsroom_query = earlystart_cached_query($newsroom_args, 'newsroom_page', 7 * DAY_IN_SECONDS);
?>
get_header();
?>

<main>
  <section class="py-20 bg-brand-cream border-b border-brand-ink/5">
    <div class="max-w-5xl mx-auto px-4">
      <h1 class="font-serif text-4xl md:text-5xl text-brand-ink mb-4">Press & Announcements</h1>
      <p class="text-brand-ink/80 text-lg">Latest updates from <?php bloginfo('name'); ?>.</p>
    </div>
  </section>

  <section class="py-16 max-w-5xl mx-auto px-4">
    <?php if ($newsroom_query->have_posts()): ?>
      <div class="space-y-12">
        <?php
        $post_count = 0;
        while ($newsroom_query->have_posts()):
          $newsroom_query->the_post();
          if ($post_count > 0): ?>
            <div class="h-px bg-brand-ink/10 w-full"></div>
          <?php endif; ?>

          <div class="group">
            <p class="text-xs font-bold uppercase tracking-widest text-brand-ink/60 mb-2">
              <?php echo esc_html(get_the_date('F j, Y')); ?>
            </p>
            <h2 class="font-serif text-2xl font-bold text-brand-ink mb-3 group-hover:text-chroma-blue transition-colors">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <p class="text-brand-ink/80 mb-4 max-w-3xl">
              <?php echo esc_html(wp_trim_words(get_the_excerpt(), 30)); ?>
            </p>
            <a href="<?php the_permalink(); ?>"
              class="text-sm font-bold border-b-2 border-chroma-yellow pb-0.5 hover:text-chroma-blue hover:border-chroma-blue transition-colors">
              Read Release
            </a>
          </div>

          <?php
          $post_count++;
        endwhile;
        wp_reset_postdata();
        ?>
      </div>

    <?php else: ?>
      <div class="text-center py-16">
        <p class="text-brand-ink/80 text-lg">No newsroom posts found. Check back soon!</p>
      </div>
    <?php endif; ?>
  </section>

  <section class="py-16 bg-brand-ink text-white text-center">
    <div class="max-w-2xl mx-auto px-4">
      <h2 class="font-serif text-2xl font-bold mb-4">Media Inquiries</h2>
      <p class="text-white/90 mb-8">For interviews, high-res assets, or filming requests.</p>
      <?php $contact_url = earlystart_get_page_link('contact'); ?>
      <a href="<?php echo esc_url($contact_url); ?>"
        class="inline-block px-8 py-3 bg-white text-brand-ink font-bold rounded-full text-xs uppercase tracking-widest hover:bg-chroma-yellow transition-colors">
        Contact Media Team
      </a>
    </div>
  </section>
</main>

<footer class="bg-brand-ink text-white py-8 text-center text-xs opacity-50 border-t border-white/10">
  &copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>.
</footer>

<?php wp_footer(); ?>
</body>

</html>
