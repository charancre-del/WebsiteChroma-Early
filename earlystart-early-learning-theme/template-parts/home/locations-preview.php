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
            <a href="<?php echo esc_url($locations_data['cta_link'] ?: earlystart_get_page_link('locations')); ?>"
                class="bg-white text-stone-900 border-2 border-stone-200 px-8 py-3 rounded-full font-bold hover:border-rose-600 hover:text-rose-700 transition-all">
                <?php echo esc_html($locations_data['cta_label'] ?: __('View All Locations', 'earlystart-early-learning')); ?>
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Map Section (Left, larger) -->
            <?php if (!empty($locations_data['map_points'])): ?>
                <div class="w-full lg:w-2/3">
                    <div class="rounded-[2.5rem] overflow-hidden shadow-2xl border border-stone-100 h-[500px] lg:h-[700px] relative z-10 fade-in-up"
                        data-chroma-map="true"
                        data-chroma-locations="<?php echo esc_attr(json_encode($locations_data['map_points'])); ?>">
                    </div>
                </div>
            <?php endif; ?>

            <!-- Locations List Section (Right, scrollable) -->
            <div class="w-full <?php echo !empty($locations_data['map_points']) ? 'lg:w-1/3' : ''; ?>">
                <?php if (!empty($grouped)): ?>
                    <div class="flex flex-col gap-4 overflow-y-auto pr-2" style="max-height: 700px;">
                        <?php
                        $count = 0;
                        foreach ($grouped as $group):
                            $designation = $group['designation'] ?? 'partner';
                            $designation_label = $group['designation_label'] ?? ($designation === 'clinic' ? __('Clinic Location', 'earlystart-early-learning') : __('Partner Location', 'earlystart-early-learning'));
                            $accent_wrap = $designation === 'clinic' ? 'text-rose-700 bg-rose-50' : 'text-blue-700 bg-blue-50';
                            $accent_text = $designation === 'clinic' ? 'text-rose-500' : 'text-blue-500';
                            $hover_border = $designation === 'clinic' ? 'hover:border-rose-300' : 'hover:border-blue-300';
                            $hover_text = $designation === 'clinic' ? 'group-hover:text-rose-600' : 'group-hover:text-blue-600';
                            ?>
                            <div class="flex items-center justify-between px-2 pt-4 first:pt-0">
                                <div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] <?php echo esc_attr($accent_wrap); ?>">
                                        <?php echo esc_html($designation_label); ?>
                                    </span>
                                    <h3 class="mt-3 text-sm font-bold uppercase tracking-[0.2em] text-stone-500">
                                        <?php echo esc_html($group['label']); ?>
                                    </h3>
                                </div>
                            </div>
                            <?php
                            foreach ($group['locations'] as $location):
                                $count++;
                                $image = get_the_post_thumbnail_url($location['id'] ?? 0, 'thumbnail');
                                ?>
                                <a href="<?php echo esc_url($location['url']); ?>" class="block group">
                                    <div class="bg-white rounded-3xl p-4 shadow-md border border-stone-100 <?php echo esc_attr($hover_border); ?> hover:shadow-xl transition-all flex items-center gap-4 fade-in-up"
                                        style="transition-delay: <?php echo min($count * 50, 500); ?>ms"
                                        data-lat="<?php echo esc_attr($location['lat'] ?? ''); ?>"
                                        data-lng="<?php echo esc_attr($location['lng'] ?? ''); ?>">

                                        <!-- Small Thumbnail -->
                                        <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0">
                                            <?php if ($image): ?>
                                                <img src="<?php echo esc_url($image); ?>"
                                                    alt="<?php echo esc_attr($location['title']); ?>"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center <?php echo esc_attr($designation === 'clinic' ? 'bg-rose-50 text-rose-300' : 'bg-blue-50 text-blue-300'); ?>">
                                                    <i data-lucide="<?php echo esc_attr($designation === 'clinic' ? 'building-2' : 'school'); ?>" class="w-8 h-8"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Details -->
                                        <div class="flex-grow">
                                            <h3
                                                class="text-lg font-bold text-stone-900 <?php echo esc_attr($hover_text); ?> transition-colors">
                                                <?php echo esc_html($location['title']); ?></h3>
                                            <div class="mt-1 text-[10px] font-bold uppercase tracking-[0.2em] <?php echo esc_attr($designation === 'clinic' ? 'text-rose-600' : 'text-blue-600'); ?>">
                                                <?php echo esc_html($designation_label); ?>
                                            </div>
                                            <?php if (!empty($location['address'])): ?>
                                                <div class="flex items-start text-stone-600 text-xs mt-1">
                                                    <i data-lucide="map-pin" class="w-3 h-3 mr-1 mt-0.5 <?php echo esc_attr($accent_text); ?> shrink-0"></i>
                                                    <span class="leading-tight"><?php echo esc_html($location['address']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Arrow -->
                                        <div class="shrink-0 text-stone-300 <?php echo esc_attr($hover_text); ?> transition-colors">
                                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                                        </div>
                                    </div>
                                </a>
                                <?php
                            endforeach;
                        endforeach;
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
