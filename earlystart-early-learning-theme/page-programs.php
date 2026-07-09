<?php
/**
 * Template Name: Programs
 * Displays all services/programs in a premium grid with icon integrations.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

$page_id = get_queried_object_id();
$programs_shell_defaults = array(
    'hero_eyebrow' => __('What We Do', 'earlystart-early-learning'),
    'hero_heading' => __('Holistic Therapy,<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">Integrated Care.</span>', 'earlystart-early-learning'),
    'hero_subheading' => __('We offer a full spectrum of pediatric services. Whether you need focused behavioral support, assessment, ABA, speech, or OT, we have a pathway for you.', 'earlystart-early-learning'),
    'settings_heading' => __('Flexible Service Settings', 'earlystart-early-learning'),
    'settings_text' => __('We offer therapy in three distinct environments to best suit your family\'s needs and your child\'s goals.', 'earlystart-early-learning'),
    'settings_cards' => array(
        array(
            'icon' => 'building',
            'title' => __('Clinic Based', 'earlystart-early-learning'),
            'text' => __('Structured environments with therapy spaces designed for focused skill acquisition and regulation support.', 'earlystart-early-learning'),
        ),
        array(
            'icon' => 'home',
            'title' => __('Home Based', 'earlystart-early-learning'),
            'text' => __('Therapy in your natural environment. Perfect for working on daily routines, sleep, and family dynamics.', 'earlystart-early-learning'),
        ),
        array(
            'icon' => 'school',
            'title' => __('School Integrated', 'earlystart-early-learning'),
            'text' => __('Push-in support at partner schools and community settings. We help generalize skills into daily routines in real time.', 'earlystart-early-learning'),
        ),
    ),
    'cta_heading' => __('Unsure where to start?', 'earlystart-early-learning'),
    'cta_text' => __('Our clinical team offers free 15-minute consultations to help you understand which service is right for your child.', 'earlystart-early-learning'),
    'cta_label' => __('Speak With A Director', 'earlystart-early-learning'),
    'cta_url' => earlystart_get_page_link('contact'),
);
$programs_shell_raw = function_exists('earlystart_get_translated_meta')
    ? earlystart_get_translated_meta($page_id, 'programs_shell_json', true)
    : get_post_meta($page_id, 'programs_shell_json', true);
$programs_shell = $programs_shell_defaults;
if (is_string($programs_shell_raw) && '' !== trim($programs_shell_raw)) {
    $decoded_shell = json_decode($programs_shell_raw, true);
    if (is_array($decoded_shell)) {
        $programs_shell = array_replace_recursive($programs_shell_defaults, $decoded_shell);
    }
} elseif (is_array($programs_shell_raw)) {
    $programs_shell = array_replace_recursive($programs_shell_defaults, $programs_shell_raw);
}
foreach ($programs_shell_defaults as $shell_key => $default_value) {
    if ('settings_cards' === $shell_key) {
        continue;
    }
    $programs_shell[$shell_key] = isset($programs_shell[$shell_key]) && is_scalar($programs_shell[$shell_key])
        ? (string) $programs_shell[$shell_key]
        : $default_value;
}
if (empty($programs_shell['settings_cards']) || !is_array($programs_shell['settings_cards'])) {
    $programs_shell['settings_cards'] = $programs_shell_defaults['settings_cards'];
}

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
                class="inline-block px-4 py-2 bg-rose-50 text-rose-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
                <?php echo esc_html($programs_shell['hero_eyebrow']); ?>
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
                <?php echo wp_kses_post($programs_shell['hero_heading']); ?>
            </h1>
            <p class="text-xl text-stone-700 max-w-3xl mx-auto leading-relaxed fade-in-up">
                <?php echo esc_html($programs_shell['hero_subheading']); ?>
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
                        $image = get_the_post_thumbnail_url($program_id, 'large') ?: '';

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
                            $icon_text_class = "text-stone-300 group-hover:text-stone-700";
                            $check_text_class = "text-stone-900";
                            $btn_hover_class = "hover:border-stone-900 hover:bg-stone-900 hover:text-white";
                        }
                        ?>
                        <div
                            class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-stone-100 group hover:shadow-xl transition-all duration-300 flex flex-col fade-in-up">
                            <div
                                class="h-64 <?php echo esc_attr($bg_class); ?> flex items-center justify-center relative overflow-hidden">
                                <?php if ($image): ?>
                                    <img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>"
                                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-stone-900/25 via-transparent to-transparent">
                                    </div>
                                <?php else: ?>
                                    <div
                                        class="absolute inset-0 <?php echo esc_attr($icon_bg_class); ?> opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                    </div>
                                    <i data-lucide="<?php echo esc_attr($icon); ?>"
                                        class="w-24 h-24 <?php echo esc_attr($icon_text_class); ?> group-hover:scale-110 transition-all duration-500"></i>
                                <?php endif; ?>
                            </div>
                            <div class="p-10 flex-grow flex flex-col">
                                <h3 class="text-3xl font-bold text-stone-900 mb-4"><?php the_title(); ?></h3>
                                <div class="text-stone-700 leading-relaxed mb-6">
                                    <?php the_excerpt(); ?>
                                </div>
                                <?php if (!empty($features_array)): ?>
                                    <ul class="space-y-3 mb-8 flex-grow">
                                        <?php foreach ($features_array as $feature): ?>
                                            <li class="flex items-center text-stone-700 text-sm">
                                                <i data-lucide="check"
                                                    class="w-4 h-4 <?php echo esc_attr($check_text_class); ?> mr-3"></i>
                                                <?php echo esc_html($feature); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>"
                                    class="inline-flex items-center justify-center w-full py-4 rounded-xl border-2 border-stone-100 text-stone-900 font-bold <?php echo esc_attr($btn_hover_class); ?> transition-all">
                                    <?php _e('Explore', 'earlystart-early-learning'); ?>         <?php the_title(); ?>
                                    <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-20">
                        <p class="text-stone-700"><?php _e('No programs found.', 'earlystart-early-learning'); ?></p>
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
                    <?php echo esc_html($programs_shell['settings_heading']); ?></h2>
                <p class="text-stone-700 max-w-2xl mx-auto text-lg">
                    <?php echo esc_html($programs_shell['settings_text']); ?>
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <?php foreach (array_slice($programs_shell['settings_cards'], 0, 3) as $card): ?>
                    <?php
                    $card = is_array($card) ? $card : array();
                    $icon = earlystart_safe_lucide_icon($card['icon'] ?? 'building');
                    $title = sanitize_text_field($card['title'] ?? '');
                    $text = sanitize_text_field($card['text'] ?? '');
                    ?>
                    <div
                        class="bg-stone-50 p-10 rounded-[2.5rem] text-center border border-stone-100 hover:shadow-lg transition-all fade-in-up">
                        <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-sm">
                            <i data-lucide="<?php echo esc_attr($icon); ?>" class="w-10 h-10 text-stone-700"></i>
                        </div>
                        <h3 class="font-bold text-2xl mb-4 text-stone-900">
                            <?php echo esc_html($title); ?></h3>
                        <p class="text-stone-700 leading-relaxed">
                            <?php echo esc_html($text); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="py-24 bg-stone-900 text-white text-center relative overflow-hidden">
        <div
            class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-rose-500 via-transparent to-transparent">
        </div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 fade-in-up">
            <h2 class="text-4xl md:text-5xl font-bold mb-6"><?php echo esc_html($programs_shell['cta_heading']); ?>
            </h2>
            <p class="text-stone-300 text-xl mb-10 leading-relaxed">
                <?php echo esc_html($programs_shell['cta_text']); ?>
            </p>
            <a href="<?php echo esc_url($programs_shell['cta_url']); ?>"
                class="bg-rose-600 text-white px-12 py-5 rounded-full font-bold text-lg hover:bg-rose-500 transition-all shadow-xl hover:shadow-rose-900/20 active:scale-95 inline-block">
                <?php echo esc_html($programs_shell['cta_label']); ?>
            </a>
        </div>
    </section>

</main>

<?php
get_footer();


