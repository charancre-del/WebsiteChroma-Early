<?php
/**
 * Single City Template
 * Hyperlocal landing page for a specific city.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

// Get city data
$city = get_the_title();
$id = get_the_ID();
$state = get_post_meta($id, 'city_state', true) ?: 'GA';
$county = get_post_meta($id, 'city_county', true) ?: 'Local';
$location_ids = get_post_meta($id, 'city_nearby_locations', true);
$intro_text = get_post_meta($id, 'city_intro_text', true);

// Local fallback image
$local_fallback = 'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&q=80&fm=webp?w=1200&fit=crop&q=80&fm=webp';
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative bg-white pt-24 pb-20 lg:pt-32 overflow-hidden border-b border-stone-50">
        <div
            class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="fade-in-up">
                    <span
                        class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6">
                        <?php printf(__('Serving %s & %s County', 'earlystart-early-learning'), esc_html($city), esc_html($county)); ?>
                    </span>
                    <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight">
                        <?php printf(__('Top-Rated Clinical Care in <span class="text-rose-600">%s.</span>', 'earlystart-early-learning'), esc_html($city)); ?>
                    </h1>
                    <p class="text-xl text-stone-600 leading-relaxed mb-10 max-w-xl">
                        <?php printf(__('Are you looking for specialized pediatric therapy near you? Discover Early Start’s clinical excellence in the %s area, featuring the PrismaPath™ model.', 'earlystart-early-learning'), esc_html($city)); ?>
                    </p>

                    <a href="#locations"
                        class="bg-stone-900 text-white px-8 py-4 rounded-full font-bold hover:bg-rose-600 transition-all shadow-lg active:scale-95 inline-block">
                        <?php printf(__('View Clinics in %s', 'earlystart-early-learning'), esc_html($city)); ?>
                    </a>
                </div>

                <div class="relative fade-in-up">
                    <div
                        class="aspect-square rounded-[3rem] bg-stone-50 overflow-hidden shadow-2xl border-8 border-white">
                        <img src="<?php echo esc_url($local_fallback); ?>" class="w-full h-full object-cover"
                            alt="<?php echo esc_attr($city); ?>">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Content -->
    <section class="py-24 bg-stone-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-in-up">
            <h2 class="text-3xl font-bold text-stone-900 mb-8">
                <?php printf(__('About our %s Presence', 'earlystart-early-learning'), esc_html($city)); ?>
            </h2>
            <div class="text-xl text-stone-600 leading-relaxed prose prose-stone max-w-none">
                <?php if ($intro_text): ?>
                    <?php echo wp_kses_post(wpautop($intro_text)); ?>
                <?php else: ?>
                    <p><?php printf(__('Early Start ELA provides a haven for clinical growth in %s. Our dedicated team of therapists and educators work in harmony to provide intensive, evidence-based care in a nurturing environment.', 'earlystart-early-learning'), esc_html($city)); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Locations Grid -->
    <section id="locations" class="py-24 bg-white scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in-up">
                <h2 class="text-4xl font-bold text-stone-900 mb-4">
                    <?php printf(__('Clinics Serving %s', 'earlystart-early-learning'), esc_html($city)); ?>
                </h2>
                <p class="text-stone-600 text-lg">
                    <?php _e('Select the campus closest to your home or work.', 'earlystart-early-learning'); ?>
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php
                if (!empty($location_ids) && is_array($location_ids)):
                    $loc_query = new WP_Query([
                        'post_type' => 'location',
                        'post__in' => $location_ids,
                        'orderby' => 'post__in'
                    ]);

                    if ($loc_query->have_posts()):
                        while ($loc_query->have_posts()):
                            $loc_query->the_post();
                            $addr = get_post_meta(get_the_ID(), 'location_address', true);
                            ?>
                            <div
                                class="group bg-stone-50 p-8 rounded-[2.5rem] border border-stone-100 hover:bg-white hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 fade-in-up">
                                <div class="aspect-video rounded-2xl bg-stone-200 mb-8 overflow-hidden relative">
                                    <?php if (has_post_thumbnail()): ?>
                                        <?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover']); ?>
                                    <?php else: ?>
                                        <img src="https://images.unsplash.com/photo-1541829070764-84a7d30dee7a?auto=format&fit=crop&q=80&fm=webp?w=600&fit=crop&q=80&fm=webp"
                                            class="w-full h-full object-cover" alt="Clinic">
                                    <?php endif; ?>
                                </div>

                                <h3 class="text-2xl font-bold text-stone-900 mb-3"><?php the_title(); ?></h3>
                                <p class="text-stone-600 text-sm mb-8 line-clamp-2"><?php echo esc_html($addr); ?></p>

                                <a href="<?php the_permalink(); ?>"
                                    class="inline-flex items-center gap-2 text-rose-600 font-bold group-hover:gap-4 transition-all">
                                    <?php _e('View Campus', 'earlystart-early-learning'); ?>
                                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                </a>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Local Programs -->
    <section class="py-24 bg-stone-900 text-white overflow-hidden relative">
        <div
            class="absolute top-0 left-0 w-96 h-96 bg-rose-600/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 fade-in-up">
                <h2 class="text-4xl font-bold mb-4">
                    <?php printf(__('Specialized Care for %s Families', 'earlystart-early-learning'), esc_html($city)); ?>
                </h2>
                <p class="text-stone-400 text-lg">
                    <?php _e('We offer a full spectrum of pediatric services tailored to individual needs.', 'earlystart-early-learning'); ?>
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <?php
                $progs = new WP_Query(['post_type' => 'program', 'posts_per_page' => 4]);
                if ($progs->have_posts()):
                    while ($progs->have_posts()):
                        $progs->the_post();
                        ?>
                        <a href="<?php the_permalink(); ?>"
                            class="group block p-8 rounded-[2rem] bg-stone-800 border border-stone-700 hover:bg-stone-700 hover:border-sky-500/50 transition-all text-center fade-in-up">
                            <div class="text-4xl mb-4 group-hover:scale-110 transition-transform">
                                <?php echo get_post_meta(get_the_ID(), 'program_icon', true) ?: '🩺'; ?>
                            </div>
                            <h4 class="font-bold text-white"><?php the_title(); ?></h4>
                        </a>
                    <?php endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div>
        </div>
    </section>

    <!-- Local FAQ -->
    <section class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-stone-900 mb-12 text-center">
                <?php printf(__('Frequently Asked Questions in %s', 'earlystart-early-learning'), esc_html($city)); ?>
            </h2>
            <div class="space-y-4">
                <details
                    class="group bg-stone-50 rounded-2xl p-6 border border-stone-100 open:bg-white open:shadow-xl transition-all">
                    <summary
                        class="flex items-center justify-between font-bold text-stone-900 list-none cursor-pointer">
                        <span><?php printf(__('Are your %s clinics accepting new clients?', 'earlystart-early-learning'), esc_html($city)); ?></span>
                        <i data-lucide="chevron-down"
                            class="w-5 h-5 text-stone-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="mt-4 text-stone-600 leading-relaxed text-sm">
                        <?php printf(__('Yes, our locations serving %s are currently accepting new families for ABA therapy, Occupational Therapy, and Speech Therapy. Contact our intake team to start the assessment process.', 'earlystart-early-learning'), esc_html($city)); ?>
                    </div>
                </details>

                <details
                    class="group bg-stone-50 rounded-2xl p-6 border border-stone-100 open:bg-white open:shadow-xl transition-all">
                    <summary
                        class="flex items-center justify-between font-bold text-stone-900 list-none cursor-pointer">
                        <span><?php printf(__('Do you offer GA Pre-K in %s?', 'earlystart-early-learning'), esc_html($city)); ?></span>
                        <i data-lucide="chevron-down"
                            class="w-5 h-5 text-stone-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="mt-4 text-stone-600 leading-relaxed text-sm">
                        <?php printf(__('Several of our campuses serving %s participate in the Georgia Lottery Pre-K program. Please check individual campus pages for specific availability.', 'earlystart-early-learning'), esc_html($city)); ?>
                    </div>
                </details>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
