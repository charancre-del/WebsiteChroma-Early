<?php
/**
 * Template Part: Clinical Team Section
 *
 * @package EarlyStart_Early_Start
 */

$team = earlystart_home_team();
if (empty($team)) {
    return;
}
?>

<section id="team" class="py-24 bg-stone-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row justify-between items-end mb-16 gap-8">
            <div class="max-w-2xl fade-in-up">
                <span class="text-rose-600 font-bold uppercase tracking-[0.2em] text-xs mb-4 block">
                    <?php _e('Our Clinical Experts', 'earlystart-early-learning'); ?>
                </span>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-stone-900 mb-6">
                    <?php _e('Expertise Guided by', 'earlystart-early-learning'); ?> <span class="italic text-rose-600">
                        <?php _e('Compassion', 'earlystart-early-learning'); ?>
                    </span>
                </h2>
                <p class="text-stone-600 text-lg leading-relaxed">
                    <?php _e('Our leadership team brings decades of experience in ABA, speech-language pathology, and pediatric development to every child\'s care plan.', 'earlystart-early-learning'); ?>
                </p>
            </div>
            <a href="<?php echo esc_url(home_url('/about-us/')); ?>"
                class="bg-white text-stone-900 border-2 border-stone-200 px-8 py-3 rounded-full font-bold hover:border-rose-600 hover:text-rose-600 transition-all fade-in-up">
                <?php _e('Meet the Full Team', 'earlystart-early-learning'); ?>
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-12">
            <?php foreach ($team as $index => $member): ?>
                <div class="group fade-in-up" style="transition-delay: <?php echo ($index + 1) * 100; ?>ms">
                    <div class="relative mb-6 rounded-[2rem] overflow-hidden aspect-[3/4] shadow-xl">
                        <img src="<?php echo esc_url($member['image']); ?>"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            alt="<?php echo esc_attr($member['name']); ?>">
                        <?php if (!empty($member['linkedin'])): ?>
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-stone-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-8">
                                <div class="flex space-x-4">
                                    <a href="<?php echo esc_url($member['linkedin']); ?>"
                                        class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-rose-600 transition-colors"
                                        target="_blank" rel="noopener">
                                        <i data-lucide="linkedin" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h4 class="text-2xl font-bold text-stone-900">
                        <?php echo esc_html($member['name']); ?>
                    </h4>
                    <p class="text-rose-600 font-bold text-sm uppercase tracking-wider mt-1">
                        <?php echo esc_html($member['role']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
