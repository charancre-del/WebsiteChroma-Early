<?php
/**
 * Template Part: Stats Strip (Trust Bar)
 *
 * @package EarlyStart_Early_Start
 */

$stats = earlystart_home_stats();
if (!$stats) {
        return;
}
?>

<section class="py-12 bg-white border-y border-stone-100 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                        class="flex flex-wrap justify-center items-center gap-8 md:gap-16 lg:gap-24 grayscale opacity-60 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                        <?php foreach ($stats as $stat): ?>
                                <div class="flex items-center space-x-2">
                                        <?php if (!empty($stat['icon'])): ?>
                                                <i data-lucide="<?php echo esc_attr($stat['icon']); ?>"
                                                        class="w-6 h-6 text-rose-600"></i>
                                        <?php endif; ?>
                                        <span class="font-bold text-stone-900 tracking-tight">
                                                <?php echo esc_html($stat['value']); ?>
                                                <?php if (!empty($stat['label'])): ?>
                                                        <span
                                                                class="text-stone-500 font-medium ml-1"><?php echo esc_html($stat['label']); ?></span>
                                                <?php endif; ?>
                                        </span>
                                </div>
                        <?php endforeach; ?>
                </div>
        </div>
</section>
