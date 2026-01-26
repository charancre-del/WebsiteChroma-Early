<?php
/**
 * Home Hero Section
 *
 * @package EarlyStart_Early_Start
 */

$hero = earlystart_home_hero();
$hero_image = get_theme_mod('earlystart_home_hero_image', 'https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&q=80&fm=webp?w=800&fit=crop&q=80&fm=webp');
?>

<section class="pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden relative">
    <!-- Background Elements -->
    <div
        class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
    </div>
    <div
        class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-[500px] h-[500px] bg-orange-50 rounded-full blur-3xl opacity-50">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-8 fade-in-up">
                <div
                    class="inline-flex items-center space-x-2 bg-rose-50 border border-rose-100 px-4 py-2 rounded-full">
                    <span class="flex h-2 w-2 rounded-full bg-rose-600 animate-pulse"></span>
                    <span
                        class="text-rose-700 text-xs font-bold uppercase tracking-wider"><?php _e('Now Enrolling for 2026', 'earlystart-early-learning'); ?></span>
                </div>

                <h1 class="text-5xl lg:text-7xl font-extrabold text-stone-900 leading-[1.1] tracking-tight">
                    <?php echo $hero['heading']; // Already run through wp_kses_post ?>
                </h1>

                <p class="text-lg text-stone-600 leading-relaxed max-w-xl">
                    <?php echo esc_html($hero['subheading']); ?>
                </p>

                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a href="<?php echo esc_url($hero['cta_url']); ?>"
                        class="bg-stone-900 text-white px-10 py-4 rounded-full font-bold hover:bg-rose-600 transition-all shadow-xl hover:shadow-rose-200 hover:-translate-y-1 text-center">
                        <?php echo esc_html($hero['cta_label']); ?>
                    </a>
                    <a href="<?php echo esc_url($hero['secondary_url']); ?>"
                        class="bg-white text-stone-900 border-2 border-stone-100 px-10 py-4 rounded-full font-bold hover:border-rose-600 hover:text-rose-600 transition-all text-center">
                        <?php echo esc_html($hero['secondary_label']); ?>
                    </a>
                </div>

                <div class="flex items-center space-x-6 pt-4">
                    <div class="flex -space-x-3">
                        <div
                            class="w-12 h-12 rounded-full border-4 border-white bg-stone-100 flex items-center justify-center overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1544717305-27a734ef202e?auto=format&fit=crop&q=80&fm=webp?w=100&h=100&fit=crop&q=80&fm=webp"
                                alt="User" width="48" height="48">
                        </div>
                        <div
                            class="w-12 h-12 rounded-full border-4 border-white bg-stone-100 flex items-center justify-center overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&fm=webp?w=100&h=100&fit=crop&q=80&fm=webp"
                                alt="User" width="48" height="48">
                        </div>
                        <div
                            class="w-12 h-12 rounded-full border-4 border-white bg-stone-100 flex items-center justify-center overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&q=80&fm=webp?w=100&h=100&fit=crop&q=80&fm=webp"
                                alt="User" width="48" height="48">
                        </div>
                    </div>
                    <div class="text-sm">
                        <div class="flex text-amber-500 mb-0.5">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        </div>
                        <p class="text-stone-600 font-medium">
                            <?php _e('Trusted by 500+ Local Families', 'earlystart-early-learning'); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative">
                <!-- Main Image with Creative Frame -->
                <div
                    class="relative z-10 w-full aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white transform rotate-2 hover:rotate-0 transition-transform duration-500">
                    <img src="<?php echo esc_url($hero_image); ?>" class="w-full h-full object-cover no-lazy"
                        alt="<?php echo esc_attr__('Happy child in therapy', 'earlystart-early-learning'); ?>"
                        fetchpriority="high" data-no-lazy="1">
                </div>

                <!-- Floating Decorations -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-100 rounded-full blur-2xl -z-10 animate-blob">
                </div>
                <div
                    class="absolute -bottom-10 -left-10 w-40 h-40 bg-rose-100 rounded-full blur-2xl -z-10 animate-blob animation-delay-2000">
                </div>

                <!-- Floating Card 1 -->
                <div
                    class="absolute -left-12 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-xl z-20 hidden sm:flex items-center space-x-4 border border-white/50 animate-bounce-slow">
                    <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="heart" class="w-6 h-6 text-rose-600"></i>
                    </div>
                    <div>
                        <p class="text-stone-900 font-bold leading-none mb-1">
                            <?php _e('Clinic Center', 'earlystart-early-learning'); ?>
                        </p>
                        <p class="text-xs text-stone-600 font-medium tracking-wide uppercase">
                            <?php _e('Compassionate Care', 'earlystart-early-learning'); ?>
                        </p>
                    </div>
                </div>

                <!-- Floating Card 2 -->
                <div
                    class="absolute -right-8 bottom-12 bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-xl z-20 hidden sm:flex items-center space-x-4 border border-white/50 animate-bounce-slow animation-delay-1000">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="brain-circuit" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-stone-900 font-bold leading-none mb-1">
                            <?php _e('ABA Experts', 'earlystart-early-learning'); ?>
                        </p>
                        <p class="text-xs text-stone-600 font-medium tracking-wide uppercase">
                            <?php _e('Evidence-Based', 'earlystart-early-learning'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
