<?php
/**
 * City Archive Template
 * Directory of cities served with premium clinical branding.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

$cities_query = new WP_Query(array(
    'post_type' => 'city',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
));
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative bg-white pt-24 pb-20 lg:pt-32 overflow-hidden">
        <div
            class="absolute top-0 left-0 -translate-y-1/4 -translate-x-1/4 w-[600px] h-[600px] bg-sky-50 rounded-full blur-3xl opacity-50">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span
                class="inline-block px-4 py-2 bg-sky-50 text-sky-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
                <?php _e('Our Communities', 'chroma-early-start'); ?>
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
                <?php _e('Clinical Excellence in your', 'chroma-early-start'); ?><br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-indigo-500">
                    <?php _e('Neighborhood.', 'chroma-early-start'); ?>
                </span>
            </h1>
            <p class="text-xl text-stone-600 max-w-3xl mx-auto leading-relaxed fade-in-up">
                <?php _e('We serve families across Georgia with premium pediatric therapy and early education. Find your city below to discover nearby campuses and specialized programs.', 'chroma-early-start'); ?>
            </p>
        </div>
    </section>

    <!-- Cities Grid -->
    <section class="py-24 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php if ($cities_query->have_posts()):
                    while ($cities_query->have_posts()):
                        $cities_query->the_post();
                        $county = get_post_meta(get_the_ID(), 'city_county', true) ?: 'Georgia';
                        $locations = get_post_meta(get_the_ID(), 'city_nearby_locations', true);
                        $count = is_array($locations) ? count($locations) : 0;
                        ?>
                        <a href="<?php the_permalink(); ?>"
                            class="group bg-white p-8 rounded-[2rem] border border-stone-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center fade-in-up">
                            <div
                                class="w-16 h-16 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-sky-600 group-hover:text-white transition-colors">
                                <i data-lucide="building-2" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-xl font-bold text-stone-900 mb-2 truncate w-full">
                                <?php the_title(); ?>
                            </h3>
                            <p class="text-xs text-stone-400 font-bold uppercase tracking-widest mb-4">
                                <?php echo esc_html($county); ?>
                            </p>
                            <div class="mt-auto pt-4 flex items-center gap-2 text-sky-600 font-bold text-xs">
                                <span><?php printf(_n('%d Clinic', '%d Clinics', $count, 'chroma-early-start'), $count); ?></span>
                                <i data-lucide="chevron-right"
                                    class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </a>
                    <?php endwhile;
                    wp_reset_postdata(); endif; ?>
            </div>
        </div>
    </section>

    <!-- Support Bar -->
    <section class="py-20 bg-stone-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-sky-600 rounded-[3rem] p-12 lg:p-20 relative overflow-hidden flex flex-col lg:flex-row items-center justify-between text-center lg:text-left gap-12">
                <div class="relative z-10 max-w-2xl">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">
                        <?php _e('Don’t see your city?', 'chroma-early-start'); ?></h2>
                    <p class="text-sky-100 text-lg">
                        <?php _e('We are rapidly expanding our clinical footprint. Contact our intake team to see if we can serve your family via a nearby campus.', 'chroma-early-start'); ?>
                    </p>
                </div>
                <div class="shrink-0 relative z-10">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>"
                        class="bg-white text-sky-600 px-10 py-5 rounded-full font-bold text-lg hover:bg-stone-900 hover:text-white transition-all shadow-xl">
                        <?php _e('Talk to Intake', 'chroma-early-start'); ?>
                    </a>
                </div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -mr-20 -mb-20"></div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>