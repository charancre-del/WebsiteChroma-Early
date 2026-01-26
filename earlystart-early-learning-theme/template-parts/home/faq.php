<?php
/**
 * Template Part: FAQ Section (Accordion)
 *
 * @package EarlyStart_Early_Start
 */

$faq_data = earlystart_home_faq();
if (!$faq_data || empty($faq_data['items'])) {
    return;
}
?>

<section id="faq" class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 fade-in-up">
            <span
                class="text-rose-700 font-bold uppercase tracking-[0.2em] text-xs mb-4 block"><?php echo esc_html($faq_data['subheading'] ?: __('Common Questions', 'earlystart-early-learning')); ?></span>
            <h2 class="text-4xl font-extrabold text-stone-900 mb-6">
                <?php echo $faq_data['heading']; // Already wp_kses_post ?></h2>
        </div>

        <div class="space-y-4" data-accordion-group>
            <?php foreach ($faq_data['items'] as $index => $item):
                if (empty($item['question'])) {
                    continue;
                }
                $faq_id = 'faq-' . ($index + 1);
                $is_active = (0 === $index);
                ?>
                <div class="faq-item border-2 border-stone-100 rounded-2xl overflow-hidden <?php echo $is_active ? 'active' : ''; ?> fade-in-up"
                    data-accordion style="transition-delay: <?php echo ($index + 1) * 50; ?>ms">
                    <button
                        class="w-full flex items-center justify-between p-6 text-left hover:bg-stone-50 transition-colors"
                        data-accordion-trigger aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr($faq_id); ?>">
                        <span class="font-bold text-stone-900"><?php echo esc_html($item['question']); ?></span>
                        <i data-lucide="plus"
                            class="w-5 h-5 text-rose-700 transition-transform duration-300 <?php echo $is_active ? 'rotate-45' : ''; ?>"
                            data-accordion-icon></i>
                    </button>
                    <div id="<?php echo esc_attr($faq_id); ?>"
                        class="faq-answer px-6 pb-6 text-stone-700 leading-relaxed <?php echo $is_active ? '' : 'hidden'; ?>"
                        data-accordion-content>
                        <div class="pb-1"><?php echo wp_kses_post(wpautop($item['answer'])); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
