<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package EarlyStart_Early_Start
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main flex-grow min-h-[70vh] flex items-center justify-center py-20 bg-stone-50">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

    <!-- Graphic / Icon -->
    <div class="mb-10 relative flex justify-center">
      <div class="absolute inset-0 bg-rose-100 rounded-full blur-3xl opacity-50 w-64 h-64 mx-auto"></div>
      <div class="relative bg-white p-6 rounded-3xl shadow-xl shadow-rose-900/5 ring-1 ring-stone-200/50">
        <i data-lucide="map" class="w-16 h-16 text-rose-500"></i>
      </div>
    </div>

    <!-- Error Message Section -->
    <section class="error-404 not-found">
      <header class="page-header mb-8">
        <h1
          class="text-6xl md:text-8xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-stone-900 to-stone-600 mb-4 tracking-tight">
          404
        </h1>
        <h2 class="text-2xl md:text-3xl font-semibold text-stone-800 mb-6 font-serif">
          <?php esc_html_e('Oops! We couldn\'t find that page.', 'earlystart-early-learning'); ?>
        </h2>
      </header>

      <div
        class="page-content bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-stone-100 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 text-rose-50 opacity-50">
          <i data-lucide="puzzle" class="w-40 h-40"></i>
        </div>

        <p class="text-lg text-stone-600 mb-8 max-w-xl mx-auto relative z-10 leading-relaxed">
          <?php esc_html_e('It looks like the page you were looking for was moved, removed, or might never have existed. Let\'s get you back on track.', 'earlystart-early-learning'); ?>
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center relative z-10">
          <a href="<?php echo esc_url(earlystart_get_page_link('')); ?>"
            class="inline-flex items-center justify-center gap-2 bg-rose-600 text-white px-8 py-3.5 rounded-full font-semibold hover:bg-rose-700 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 w-full sm:w-auto">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <?php esc_html_e('Return to Homepage', 'earlystart-early-learning'); ?>
          </a>
          <a href="<?php echo esc_url(earlystart_get_page_link('contact')); ?>"
            class="inline-flex items-center justify-center gap-2 bg-stone-100 text-stone-700 px-8 py-3.5 rounded-full font-semibold hover:bg-stone-200 transition-all w-full sm:w-auto border border-stone-200">
            <i data-lucide="life-buoy" class="w-4 h-4"></i>
            <?php esc_html_e('Contact Support', 'earlystart-early-learning'); ?>
          </a>
        </div>

        <!-- Optional Search Bar if needed -->
        <?php /*
   <div class="mt-12 pt-8 border-t border-stone-100 relative z-10">
     <p class="text-sm font-medium text-stone-500 mb-4"><?php esc_html_e( 'Try searching for something else:', 'earlystart-early-learning' ); ?></p>
     <?php get_search_form(); ?>
   </div>
   */ ?>
      </div>
    </section>

  </div>
</main>

<?php
get_footer();
