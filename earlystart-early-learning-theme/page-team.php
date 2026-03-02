<?php
/**
 * Template Name: Clinical Team
 * Displays the leadership and clinical staff in a premium directory.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

// Query team members
$team_query = new WP_Query(array(
    'post_type' => 'team_member',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
));
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative bg-white pt-24 pb-20 lg:pt-32 overflow-hidden">
        <div
            class="absolute top-0 left-0 -translate-y-1/4 -translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
            <span
                class="inline-block px-4 py-2 bg-rose-50 text-rose-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
                <?php _e('Clinical Leadership', 'earlystart-early-learning'); ?>
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
                <?php _e('The Heart of', 'earlystart-early-learning'); ?><br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
                    <?php _e('Early Start.', 'earlystart-early-learning'); ?>
                </span>
            </h1>
            <p class="text-xl text-stone-700 max-w-3xl mx-auto leading-relaxed fade-in-up">
                <?php _e('We don\'t just hire technicians; we hire career clinicians. Our team is selected for their passion, patience, and professional credentials.', 'earlystart-early-learning'); ?>
            </p>
        </div>
    </section>

    <!-- Team Grid -->
    <section class="py-24 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12">
                <?php if ($team_query->have_posts()): ?>
                    <?php while ($team_query->have_posts()):
                        $team_query->the_post();
                        $role = get_post_meta(get_the_ID(), 'team_member_title', true);
                        $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: '';
                        ?>
                        <div
                            class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-stone-100 group hover:shadow-xl transition-all duration-500 flex flex-col fade-in-up">
                            <div class="relative h-96 overflow-hidden">
                                <?php if ($thumb_url): ?>
                                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php the_title(); ?>"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                <?php else: ?>
                                    <div class="w-full h-full bg-stone-50 flex items-center justify-center text-stone-300">
                                        <i data-lucide="user-round" class="w-20 h-20"></i>
                                    </div>
                                <?php endif; ?>
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-stone-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                            </div>
                            <div class="p-10 flex-grow">
                                <h3 class="text-2xl font-bold text-stone-900 mb-2">
                                    <?php the_title(); ?>
                                </h3>
                                <?php if ($role): ?>
                                    <p
                                        class="text-rose-700 font-bold text-sm uppercase tracking-widest mb-6 border-b border-rose-50 pb-4 inline-block">
                                        <?php echo esc_html($role); ?>
                                    </p>
                                <?php endif; ?>
                                <div class="text-stone-700 text-sm leading-relaxed mb-6">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-20">
                        <p class="text-stone-700">
                            <?php _e('No team members found.', 'earlystart-early-learning'); ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Values / Pills (Ported from About) -->
    <section class="py-24 bg-rose-600 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 fade-in-up">
                <h2 class="text-4xl font-bold mb-4">
                    <?php _e('Our Non-Negotiables', 'earlystart-early-learning'); ?>
                </h2>
                <p class="text-white max-w-2xl mx-auto text-lg">
                    <?php _e('Four core pillars guide every clinical decision we make.', 'earlystart-early-learning'); ?>
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $pillars = array(
                    array('icon' => 'smile', 'title' => 'Unconditional Joy', 'desc' => 'Childhood should be magical. We prioritize laughter and warmth.'),
                    array('icon' => 'shield', 'title' => 'Radical Safety', 'desc' => 'Physical baseline; emotional goal. Kids learn when they feel secure.'),
                    array('icon' => 'star', 'title' => 'Clinical Excellence', 'desc' => 'Data-driven models deliver rigorous therapy that feels like play.'),
                    array('icon' => 'users', 'title' => 'Open Partnership', 'desc' => 'Parents are partners. Open doors, transparent data, and daily updates.'),
                );
                foreach ($pillars as $p): ?>
                    <div class="bg-white/10 backdrop-blur-sm p-8 rounded-[2rem] border border-rose-400/30 fade-in-up">
                        <i data-lucide="<?php echo $p['icon']; ?>" class="w-10 h-10 text-white mb-6"></i>
                        <h3 class="text-xl font-bold mb-3">
                            <?php echo esc_html($p['title']); ?>
                        </h3>
                        <p class="text-sm text-white leading-relaxed">
                            <?php echo esc_html($p['desc']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Join the Team CTA -->
    <section class="py-24 bg-white text-center">
        <div class="max-w-4xl mx-auto px-4 fade-in-up">
            <h2 class="text-4xl font-bold text-stone-900 mb-6">
                <?php _e('Want to join the mission?', 'earlystart-early-learning'); ?>
            </h2>
            <p class="text-xl text-stone-700 mb-10 leading-relaxed">
                <?php _e('We are always looking for passionate BCBAs, RBTs, and Speech Therapists to join our growing network.', 'earlystart-early-learning'); ?>
            </p>
            <a href="<?php echo esc_url(earlystart_get_page_link('careers')); ?>"
                class="bg-stone-900 text-white px-10 py-5 rounded-full font-bold text-lg hover:bg-rose-600 transition-all shadow-xl inline-block active:scale-95">
                <?php _e('Explore Careers', 'earlystart-early-learning'); ?>
                <i data-lucide="arrow-right" class="ml-2 w-5 h-5 inline"></i>
            </a>
        </div>
    </section>

</main>

<?php
get_footer();
