<?php
/**
 * Template Part: Locations Preview
 *
 * @package EarlyStart_Early_Start
 */

$locations_data = earlystart_home_locations_preview();
if (!$locations_data) {
    return;
}

$grouped = $locations_data['grouped'] ?? array();
?>

<section id="locations" class="py-24 bg-stone-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row justify-between items-end mb-16 gap-8 fade-in-up">
            <div class="max-w-2xl">
                <span
                    class="text-rose-700 font-bold uppercase tracking-[0.2em] text-xs mb-4 block"><?php _e('Neighborhood Clinical Centers', 'earlystart-early-learning'); ?></span>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-stone-900 mb-6">
                    <?php echo $locations_data['heading']; // Already wp_kses_post ?>
                </h2>
                <p class="text-stone-700 text-lg leading-relaxed">
                    <?php echo esc_html($locations_data['subheading']); ?>
                </p>
            </div>
            <a href="<?php echo esc_url($locations_data['cta_link'] ?: home_url('/locations/')); ?>"
                class="bg-white text-stone-900 border-2 border-stone-200 px-8 py-3 rounded-full font-bold hover:border-rose-600 hover:text-rose-700 transition-all">
                <?php echo esc_html($locations_data['cta_label'] ?: __('View All Locations', 'earlystart-early-learning')); ?>
            </a>
        </div>

        <?php if (!empty($grouped)): ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $count = 0;
                foreach ($grouped as $group):
                    foreach ($group['locations'] as $location):
                        if ($count >= 3)
                            break 2; // Show only 3 for preview
                        $count++;

                        $image = get_the_post_thumbnail_url($location['id'], 'large') ?: 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&fm=webp?w=800&fit=crop&q=80&fm=webp';
                        ?>
                        <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-xl border border-stone-100 group fade-in-up"
                            style="transition-delay: <?php echo $count * 100; ?>ms">
                            <div class="relative h-64 overflow-hidden">
                                <img src="<?php echo esc_url($image); ?>"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    alt="<?php echo esc_attr($location['title']); ?>">
                                <div
                                    class="absolute top-4 right-4 bg-rose-600 text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                    <?php _e('Clinics Open', 'earlystart-early-learning'); ?>
                                </div>
                            </div>
                            <div class="p-8">
                                <h3 class="text-2xl font-bold text-stone-900 mb-2"><?php echo esc_html($location['title']); ?></h3>
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center text-stone-700 text-sm font-medium">
                                        <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-rose-700"></i>
                                        <?php echo esc_html($location['address']); ?>
                                    </div>
                                    <div class="flex items-center text-stone-700 text-sm font-medium">
                                        <i data-lucide="phone" class="w-4 h-4 mr-2 text-rose-700"></i>
                                        <?php echo esc_html($location['phone']); ?>
                                    </div>
                                </div>
                                <a href="<?php echo esc_url($location['url']); ?>"
                                    class="flex items-center justify-center w-full bg-stone-50 group-hover:bg-rose-600 group-hover:text-white text-stone-900 font-bold py-4 rounded-2xl transition-all">
                                    <?php _e('View Center', 'earlystart-early-learning'); ?>
                                    <i data-lucide="arrow-right" class="ml-2 w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>
