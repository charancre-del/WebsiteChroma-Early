<?php
/**
 * Template Part: Services Tabs
 *
 * @package EarlyStart_Early_Start
 */

$services = earlystart_home_services();
if (empty($services)) {
    return;
}
?>

<section id="services" class="py-24 bg-stone-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 fade-in-up">
            <span class="text-rose-600 font-bold uppercase tracking-[0.2em] text-xs mb-4 block">
                <?php _e('Therapy in Action', 'chroma-early-start'); ?>
            </span>
            <h2 class="text-4xl lg:text-5xl font-extrabold text-stone-900 mb-6">
                <?php _e('Support for Every', 'chroma-early-start'); ?> <span class="italic text-rose-600">
                    <?php _e('Milestone', 'chroma-early-start'); ?>
                </span>
            </h2>
            <p class="text-stone-600 max-w-2xl mx-auto text-lg leading-relaxed">
                <?php _e('Our integrated therapy approach treats the whole child. Choose a service below to see how our clinical team helps your child reach their full potential.', 'chroma-early-start'); ?>
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-12 items-start" data-services-tabs>
            <!-- Tab Buttons -->
            <div class="w-full lg:w-1/3 flex flex-row lg:flex-col gap-3 overflow-x-auto pb-4 lg:pb-0 no-scrollbar">
                <?php foreach ($services as $index => $service): ?>
                    <button data-services-tab="<?php echo esc_attr($service['id']); ?>"
                        class="tab-btn <?php echo (0 === $index) ? 'active' : ''; ?> w-full text-left px-8 py-6 rounded-2xl bg-white border-2 border-transparent shadow-md hover:shadow-lg transition-all group shrink-0 lg:shrink">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center group-hover:bg-rose-100 transition-colors">
                                <i data-lucide="<?php echo esc_attr($service['icon']); ?>"
                                    class="w-6 h-6 text-rose-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-stone-900">
                                    <?php echo esc_html($service['title']); ?>
                                </h4>
                                <p class="text-xs text-stone-500 font-medium">
                                    <?php echo esc_html($service['subtitle']); ?>
                                </p>
                            </div>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Tab Content -->
            <div class="w-full lg:w-2/3">
                <?php foreach ($services as $index => $service): ?>
                    <div data-services-panel="<?php echo esc_attr($service['id']); ?>"
                        class="service-panel <?php echo (0 === $index) ? '' : 'hidden'; ?> bg-white rounded-[2.5rem] p-8 lg:p-12 shadow-xl border border-stone-100 animate-fade-in">
                        <div class="grid md:grid-cols-2 gap-12 items-center">
                            <div class="space-y-6">
                                <h3 class="text-3xl font-bold text-stone-900 leading-tight">
                                    <?php echo esc_html($service['heading']); ?>
                                </h3>
                                <p class="text-stone-600 leading-relaxed">
                                    <?php echo esc_html($service['description']); ?>
                                </p>
                                <ul class="space-y-4">
                                    <?php foreach ($service['bullets'] as $bullet): ?>
                                        <li class="flex items-center space-x-3">
                                            <div class="w-5 h-5 bg-rose-100 rounded-full flex items-center justify-center">
                                                <i data-lucide="check" class="w-3 h-3 text-rose-600"></i>
                                            </div>
                                            <span class="text-stone-700 font-medium">
                                                <?php echo esc_html($bullet); ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="pt-4">
                                    <a href="<?php echo esc_url(earlystart_get_theme_mod('earlystart_book_tour_url', home_url('/contact/'))); ?>"
                                        class="inline-flex items-center text-rose-600 font-bold group">
                                        <?php _e('Get Started with', 'chroma-early-start'); ?>
                                        <?php echo esc_html($service['title']); ?>
                                        <i data-lucide="arrow-right"
                                            class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="relative">
                                <div class="aspect-[4/5] rounded-[2rem] overflow-hidden shadow-2xl">
                                    <img src="<?php echo esc_url($service['image']); ?>" class="w-full h-full object-cover"
                                        alt="<?php echo esc_attr($service['title']); ?>">
                                </div>
                                <div
                                    class="absolute -bottom-6 -right-6 w-32 h-32 bg-stone-900 rounded-2xl flex flex-col items-center justify-center text-white p-4 shadow-xl">
                                    <p class="text-3xl font-bold mb-0 leading-none">1:1</p>
                                    <p class="text-[10px] uppercase font-bold tracking-widest mt-2 text-stone-400">Ratio</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>