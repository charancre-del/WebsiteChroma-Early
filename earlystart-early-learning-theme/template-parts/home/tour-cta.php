<?php
/**
 * Template Part: Tour CTA
 *
 * @package EarlyStart_Early_Start
 */

$tour_cta = earlystart_home_tour_cta();
if (!$tour_cta) {
    return;
}
?>

<section id="contact" class="py-24 bg-rose-600 relative overflow-hidden">
    <!-- Animated background patterns -->
    <div
        class="absolute top-0 right-0 w-[500px] h-[500px] bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl">
    </div>
    <div
        class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-rose-500/30 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="fade-in-up">
            <h2 class="text-4xl lg:text-6xl font-extrabold text-white mb-8">
                <?php echo $tour_cta['heading']; // Already wp_kses_post ?>
            </h2>
            <p class="text-white text-xl max-w-2xl mx-auto mb-12 leading-relaxed">
                <?php echo esc_html($tour_cta['subheading']); ?>
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo esc_url($tour_cta['cta_url']); ?>"
                    class="bg-white text-rose-700 px-12 py-5 rounded-full font-bold text-lg hover:bg-rose-50 transition-all shadow-2xl hover:scale-105 active:scale-95">
                    <?php echo esc_html($tour_cta['cta_label']); ?>
                </a>
                <a href="tel:4045550123"
                    class="bg-stone-900 text-white border-2 border-stone-800 px-12 py-5 rounded-full font-bold text-lg hover:bg-stone-800 transition-all text-center"
                    aria-label="<?php esc_attr_e('Call Early Start Admissions', 'earlystart-early-learning'); ?>">
                    <?php _e('Call Now', 'earlystart-early-learning'); ?>
                </a>
            </div>

            <?php if (!empty($tour_cta['trust_text'])): ?>
                <p class="mt-8 text-white text-sm font-bold">
                    <i data-lucide="shield-check" class="w-4 h-4 inline-block mr-1 align-text-bottom"></i>
                    <?php echo esc_html($tour_cta['trust_text']); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>