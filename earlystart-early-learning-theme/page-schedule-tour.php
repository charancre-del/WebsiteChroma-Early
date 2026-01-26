<?php
/**
 * Template Name: Schedule a Tour
 * Displays a premium clinic selection and tour booking interface.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

// Fetch all locations
$locations_query = new WP_Query(array(
    'post_type' => 'location',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
));

// Buckets for regions using the new brand colors
$regions = array(
    'gwinnett' => array(
        'title' => 'Gwinnett County',
        'theme' => 'rose',
        'posts' => array(),
    ),
    'cobb' => array(
        'title' => 'Cobb County',
        'theme' => 'orange',
        'posts' => array(),
    ),
    'north-metro' => array(
        'title' => 'North Metro',
        'theme' => 'blue',
        'posts' => array(),
    ),
    'south-metro' => array(
        'title' => 'South Metro',
        'theme' => 'amber',
        'posts' => array(),
    ),
);

if ($locations_query->have_posts()) {
    while ($locations_query->have_posts()) {
        $locations_query->the_post();
        $id = get_the_ID();
        $terms = get_the_terms($id, 'location_region');
        $first_term = ($terms && !is_wp_error($terms)) ? $terms[0] : null;

        $post_data = array(
            'title' => get_the_title(),
            'permalink' => get_permalink(),
            'thumb' => get_the_post_thumbnail_url($id, 'large') ?: 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?auto=format&fit=crop&q=80&fm=webp?w=600&fit=crop&q=80&fm=webp',
            'address' => get_post_meta($id, 'location_address', true),
            'city' => get_post_meta($id, 'location_city', true),
            'booking' => get_post_meta($id, 'location_tour_booking_link', true),
        );

        $bucket_found = false;
        if ($first_term) {
            $slug = $first_term->slug;
            if (strpos($slug, 'gwinnett') !== false) {
                $regions['gwinnett']['posts'][] = $post_data;
                $bucket_found = true;
            } elseif (strpos($slug, 'cobb') !== false) {
                $regions['cobb']['posts'][] = $post_data;
                $bucket_found = true;
            } elseif (strpos($slug, 'north') !== false) {
                $regions['north-metro']['posts'][] = $post_data;
                $bucket_found = true;
            } elseif (strpos($slug, 'south') !== false) {
                $regions['south-metro']['posts'][] = $post_data;
                $bucket_found = true;
            }
        }
    }
    wp_reset_postdata();
}
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
                        <?php _e('Clinic Tours', 'earlystart-early-learning'); ?>
                    </span>
                    <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight">
                        <?php _e('Experience our', 'earlystart-early-learning'); ?><br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
                            <?php _e('Clinical Magic.', 'earlystart-early-learning'); ?>
                        </span>
                    </h1>
                    <p class="text-xl text-stone-600 leading-relaxed mb-10 max-w-xl">
                        <?php _e('Select your preferred campus below to schedule a private walkthrough with our Clinical Director. We look forward to welcoming your family.', 'earlystart-early-learning'); ?>
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($regions as $slug => $data):
                            if (!empty($data['posts'])): ?>
                                <a href="#<?php echo esc_attr($slug); ?>"
                                    class="px-6 py-2 rounded-full border border-stone-200 text-stone-600 font-bold text-xs uppercase tracking-widest hover:border-rose-600 hover:text-rose-600 transition-all">
                                    <?php echo esc_html($data['title']); ?>
                                </a>
                            <?php endif; endforeach; ?>
                    </div>
                </div>

                <div class="relative fade-in-up">
                    <div
                        class="aspect-[4/3] rounded-[3rem] bg-stone-50 overflow-hidden shadow-2xl border-8 border-white">
                        <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&q=80&fm=webp?w=1200&fit=crop&q=80&fm=webp"
                            class="w-full h-full object-cover" alt="Parent and child">
                    </div>
                    <div class="absolute -bottom-8 -left-8 w-48 h-48 bg-amber-50 rounded-full blur-3xl -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Clinics by Region -->
    <?php foreach ($regions as $slug => $data):
        if (empty($data['posts']))
            continue;
        $theme = $data['theme'];
        ?>
        <section id="<?php echo esc_attr($slug); ?>" class="py-24 bg-stone-50 border-b border-stone-100 last:border-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-6 mb-16 fade-in-up">
                    <div
                        class="w-12 h-12 bg-<?php echo $theme; ?>-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="map-pin" class="w-6 h-6 text-white"></i>
                    </div>
                    <h2 class="text-4xl font-bold text-stone-900"><?php echo esc_html($data['title']); ?></h2>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php foreach ($data['posts'] as $post): ?>
                        <div
                            class="group bg-white p-6 rounded-[2.5rem] shadow-sm border border-stone-100 hover:shadow-2xl transition-all duration-500 flex flex-col fade-in-up">
                            <div class="aspect-[5/4] rounded-2xl overflow-hidden mb-6 relative">
                                <img src="<?php echo esc_url($post['thumb']); ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                    alt="<?php echo esc_attr($post['title']); ?>">
                                <div class="absolute inset-0 bg-gradient-to-t from-stone-900/40 to-transparent"></div>
                            </div>

                            <h3 class="text-xl font-bold text-stone-900 mb-2 truncate">
                                <?php echo esc_html(str_replace('Location', '', $post['title'])); ?>
                            </h3>
                            <p class="text-xs text-stone-400 font-medium mb-6 uppercase tracking-widest flex items-center">
                                <i data-lucide="navigation" class="w-3 h-3 mr-2"></i>
                                <?php echo esc_html($post['city'] ?: 'Georgia'); ?>
                            </p>

                            <div class="mt-auto pt-6 border-t border-stone-50">
                                <?php if ($post['booking']): ?>
                                    <a href="<?php echo esc_url($post['booking']); ?>" target="_blank" rel="noopener noreferrer"
                                        class="block w-full py-4 bg-stone-900 text-white text-center rounded-xl font-bold text-sm tracking-widest uppercase hover:bg-rose-600 transition-all">
                                        <?php _e('Schedule Tour', 'earlystart-early-learning'); ?>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo esc_url($post['permalink']); ?>#contact"
                                        class="block w-full py-4 bg-stone-50 text-stone-600 text-center rounded-xl font-bold text-sm tracking-widest uppercase hover:bg-rose-50 transition-all">
                                        <?php _e('Inquire Now', 'earlystart-early-learning'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endforeach; ?>

    <!-- FAQ & Help -->
    <section class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-in-up">
            <h2 class="text-3xl font-bold text-stone-900 mb-8">
                <?php _e('What happens during a tour?', 'earlystart-early-learning'); ?>
            </h2>
            <div class="grid md:grid-cols-3 gap-12 text-left">
                <div>
                    <div
                        class="w-12 h-12 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center font-bold mb-6">
                        1</div>
                    <h4 class="font-bold text-stone-900 mb-2"><?php _e('Walkthrough', 'earlystart-early-learning'); ?>
                    </h4>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        <?php _e('See our clean, safe, and stimulating clinical environments in person.', 'earlystart-early-learning'); ?>
                    </p>
                </div>
                <div>
                    <div
                        class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center font-bold mb-6">
                        2</div>
                    <h4 class="font-bold text-stone-900 mb-2"><?php _e('Meet the Team', 'earlystart-early-learning'); ?>
                    </h4>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        <?php _e('Speak with the Clinical Director about your child’s unique goals.', 'earlystart-early-learning'); ?>
                    </p>
                </div>
                <div>
                    <div
                        class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center font-bold mb-6">
                        3</div>
                    <h4 class="font-bold text-stone-900 mb-2"><?php _e('Next Steps', 'earlystart-early-learning'); ?>
                    </h4>
                    <p class="text-sm text-stone-600 leading-relaxed">
                        <?php _e('Learn about our intake process, assessment, and individualized timelines.', 'earlystart-early-learning'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
