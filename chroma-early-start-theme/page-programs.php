<?php
/**
 * Template Name: Programs
 * Displays all services/programs in a premium grid with icon integrations.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

// Get all programs
$programs_query = new WP_Query(array(
    'post_type' => 'program',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
));
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative bg-white pt-24 pb-20 lg:pt-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span
                class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
                <?php _e('What We Do', 'chroma-early-start'); ?>
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
                <?php _e('Holistic Therapy,', 'chroma-early-start'); ?><br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
                    <?php _e('Integrated Care.', 'chroma-early-start'); ?>
                </span>
            </h1>
            <p class="text-xl text-stone-600 max-w-3xl mx-auto leading-relaxed fade-in-up">
                <?php _e('We offer a full spectrum of pediatric services. Whether you need focused behavioral support or a comprehensive school-readiness plan, we have a pathway for you.', 'chroma-early-start'); ?>
            </p>
        </div>
    </section>

    <!-- Services Archive Grid -->
    <section class="py-24 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
                <?php if ($programs_query->have_posts()): ?>
                    <?php while ($programs_query->have_posts()):
                        $programs_query->the_post();
                        $program_id = get_the_ID();
                        $icon = get_post_meta($program_id, 'program_icon', true) ?: 'puzzle';
                        $color = get_post_meta($program_id, 'program_color_scheme', true) ?: 'rose';
                        $features = get_post_meta($program_id, 'program_features', true);
                        $features_array = $features ? array_filter(array_map('trim', explode("\n", $features))) : array();

                        // Map colors to classes
                        $bg_class = "bg-{$color}-50";
                        $icon_bg_class = "bg-{$color}-100";
                        $icon_text_class = "text-{$color}-400 group-hover:text-{$color}-600";
                        $check_text_class = "text-{$color}-500";
                        $btn_hover_class = "hover:border-{$color}-500 hover:text-{$color}-600";

                        // Special for Bridge/Stone
                        if ($color === 'stone') {
                            $bg_class = "bg-stone-100";
                            $icon_bg_class = "bg-stone-200";
                            $icon_text_class = "text-stone-400 group-hover:text-stone-600";
                            $check_text_class = "text-stone-900";
                            $btn_hover_class = "hover:border-stone-900 hover:bg-stone-900 hover:text-white";
                        }
                        ?>
                        <div
                            class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-stone-100 group hover:shadow-xl transition-all duration-300 flex flex-col fade-in-up">
                            <div
                                class="h-64 <?php echo esc_attr($bg_class); ?> flex items-center justify-center relative overflow-hidden">
                                <div
                                    class="absolute inset-0 <?php echo esc_attr($icon_bg_class); ?> opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                                <i data-lucide="<?php echo esc_attr($icon); ?>"
                                    class="w-24 h-24 <?php echo esc_attr($icon_text_class); ?> group-hover:scale-110 transition-all duration-500"></i>
                            </div>
                            <div class="p-10 flex-grow flex flex-col">
                                <h3 class="text-3xl font-bold text-stone-900 mb-4"><?php the_title(); ?></h3>
                                <div class="text-stone-600 leading-relaxed mb-6">
                                    <?php the_excerpt(); ?>
                                </div>
                                <?php if (!empty($features_array)): ?>
                                    <ul class="space-y-3 mb-8 flex-grow">
                                        <?php foreach ($features_array as $feature): ?>
                                            <li class="flex items-center text-stone-600 text-sm">
                                                <i data-lucide="check"
                                                    class="w-4 h-4 <?php echo esc_attr($check_text_class); ?> mr-3"></i>
                                                <?php echo esc_html($feature); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>"
                                    class="inline-flex items-center justify-center w-full py-4 rounded-xl border-2 border-stone-100 text-stone-900 font-bold <?php echo esc_attr($btn_hover_class); ?> transition-all">
                                    <?php _e('Explore', 'chroma-early-start'); ?>         <?php the_title(); ?>
                                    <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-20">
                        <p class="text-stone-500"><?php _e('No programs found.', 'chroma-early-start'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Service Settings Section -->
    <section class="py-24 bg-white border-t border-stone-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 fade-in-up">
                <h2 class="text-4xl font-bold text-stone-900 mb-4">
                    <?php _e('Flexible Service Settings', 'chroma-early-start'); ?></h2>
                <p class="text-stone-600 max-w-2xl mx-auto text-lg">
                    <?php _e('We offer therapy in three distinct environments to best suit your family\'s needs and your child\'s goals.', 'chroma-early-start'); ?>
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    class="bg-stone-50 p-10 rounded-[2.5rem] text-center border border-stone-100 hover:shadow-lg transition-all fade-in-up">
                    <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-sm">
                        <i data-lucide="building" class="w-10 h-10 text-stone-700"></i>
                    </div>
                    <h3 class="font-bold text-2xl mb-4 text-stone-900">
                        <?php _e('Clinic Based', 'chroma-early-start'); ?></h3>
                    <p class="text-stone-600 leading-relaxed">
                        <?php _e('Structured environments with sensory gyms and mock classrooms designed for focused skill acquisition.', 'chroma-early-start'); ?>
                    </p>
                </div>
                <div
                    class="bg-stone-50 p-10 rounded-[2.5rem] text-center border border-stone-100 hover:shadow-lg transition-all fade-in-up">
                    <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-sm">
                        <i data-lucide="home" class="w-10 h-10 text-stone-700"></i>
                    </div>
                    <h3 class="font-bold text-2xl mb-4 text-stone-900"><?php _e('Home Based', 'chroma-early-start'); ?>
                    </h3>
                    <p class="text-stone-600 leading-relaxed">
                        <?php _e('Therapy in your natural environment. Perfect for working on daily routines, sleep, and family dynamics.', 'chroma-early-start'); ?>
                    </p>
                </div>
                <div
                    class="bg-stone-50 p-10 rounded-[2.5rem] text-center border border-stone-100 hover:shadow-lg transition-all fade-in-up">
                    <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-sm">
                        <i data-lucide="school" class="w-10 h-10 text-stone-700"></i>
                    </div>
                    <h3 class="font-bold text-2xl mb-4 text-stone-900">
                        <?php _e('School Integrated', 'chroma-early-start'); ?></h3>
                    <p class="text-stone-600 leading-relaxed">
                        <?php _e('Push-in support at partner schools. We generalize skills to the classroom in real-time.', 'chroma-early-start'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 bg-stone-900 text-white text-center relative overflow-hidden">
        <div
            class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-rose-500 via-transparent to-transparent">
        </div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 fade-in-up">
            <h2 class="text-4xl md:text-5xl font-bold mb-6"><?php _e('Unsure where to start?', 'chroma-early-start'); ?>
            </h2>
            <p class="text-stone-300 text-xl mb-10 leading-relaxed">
                <?php _e('Our clinical team offers free 15-minute consultations to help you understand which service is right for your child.', 'chroma-early-start'); ?>
            </p>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>"
                class="bg-rose-600 text-white px-12 py-5 rounded-full font-bold text-lg hover:bg-rose-500 transition-all shadow-xl hover:shadow-rose-900/20 active:scale-95 inline-block">
                <?php _e('Speak With A Director', 'chroma-early-start'); ?>
            </a>
        </div>
    </section>

</main>

<?php
get_footer();


