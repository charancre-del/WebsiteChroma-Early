<?php
/**
 * Template Name: Our Approach
 * Displays the PrismaPath™ clinical model and therapeutic philosophy.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

while (have_posts()):
    the_post();
    ?>

    <main class="pt-20">
        <!-- Hero Section -->
        <section class="relative bg-white pt-24 pb-20 lg:pt-32 overflow-hidden">
            <div
                class="absolute top-0 left-0 -translate-y-1/4 -translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="fade-in-up">
                        <span
                            class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6">
                            <?php _e('Our Clinical Model', 'chroma-early-start'); ?>
                        </span>
                        <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight">
                            <?php _e('Building Trust,', 'chroma-early-start'); ?><br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
                                <?php _e('Not Compliance.', 'chroma-early-start'); ?>
                            </span>
                        </h1>
                        <p class="text-xl text-stone-600 leading-relaxed mb-10">
                            <?php _e('At Chroma, we use PrismaPath™—our signature assent-based ABA model. We prioritize the child\'s happiness and willingness to participate above all else.', 'chroma-early-start'); ?>
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="#model"
                                class="bg-stone-900 text-white px-8 py-4 rounded-full font-bold hover:bg-rose-600 transition-all shadow-lg active:scale-95 inline-block">
                                <?php _e('Explore the Model', 'chroma-early-start'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="relative fade-in-up">
                        <div
                            class="aspect-square rounded-[3rem] bg-stone-50 overflow-hidden shadow-2xl border-8 border-white p-4">
                            <img src="https://images.unsplash.com/photo-1544717305-27a734ef202e?q=80&w=1000&auto=format&fit=crop"
                                class="w-full h-full object-cover rounded-[2rem]" alt="PrismaPath">
                        </div>
                        <div class="absolute -bottom-8 -right-8 w-48 h-48 bg-amber-50 rounded-full blur-3xl -z-10"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- The PrismaPath Model -->
        <section id="model" class="py-24 bg-stone-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20 fade-in-up">
                    <h2 class="text-4xl font-bold text-stone-900 mb-6">
                        <?php _e('What is PrismaPath™?', 'chroma-early-start'); ?>
                    </h2>
                    <p class="text-xl text-stone-600">
                        <?php _e('PrismaPath™ is more than a curriculum; it\'s a philosophy of care that integrates modern ABA with developmental milestones and emotional security.', 'chroma-early-start'); ?>
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-12">
                    <!-- Pillar 1 -->
                    <div
                        class="bg-white p-12 rounded-[3rem] shadow-sm border border-stone-100 group hover:shadow-xl transition-all fade-in-up">
                        <div
                            class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 mb-8 group-hover:scale-110 transition-transform">
                            <i data-lucide="heart" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-stone-900 mb-4">
                            <?php _e('Assent-Based', 'chroma-early-start'); ?>
                        </h3>
                        <p class="text-stone-600 leading-relaxed">
                            <?php _e('We never force compliance. If a child is withdrawing or escaping, we pivot. We find what motivates them and build skills through joy.', 'chroma-early-start'); ?>
                        </p>
                    </div>

                    <!-- Pillar 2 -->
                    <div
                        class="bg-white p-12 rounded-[3rem] shadow-sm border border-stone-100 group hover:shadow-xl transition-all fade-in-up">
                        <div
                            class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-8 group-hover:scale-110 transition-transform">
                            <i data-lucide="sparkles" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-stone-900 mb-4">
                            <?php _e('Play-Led Goals', 'chroma-early-start'); ?>
                        </h3>
                        <p class="text-stone-600 leading-relaxed">
                            <?php _e('Goals are targetted during natural play. We don\'t sit at a desk drill-sergeant style; we sit on the floor and learn together.', 'chroma-early-start'); ?>
                        </p>
                    </div>

                    <!-- Pillar 3 -->
                    <div
                        class="bg-white p-12 rounded-[3rem] shadow-sm border border-stone-100 group hover:shadow-xl transition-all fade-in-up">
                        <div
                            class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-8 group-hover:scale-110 transition-transform">
                            <i data-lucide="layers" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-stone-900 mb-4">
                            <?php _e('Holistic Sync', 'chroma-early-start'); ?>
                        </h3>
                        <p class="text-stone-600 leading-relaxed">
                            <?php _e('ABA, Speech, and OT goals are synchronized in one clinical roadmap. No conflicting advice—just one unified team.', 'chroma-early-start'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- The 4 Pillars / Non-Negotiables (Global Component) -->
        <section class="py-24 bg-rose-600 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16 fade-in-up">
                    <h2 class="text-4xl font-bold mb-4">
                        <?php _e('Clinical Non-Negotiables', 'chroma-early-start'); ?>
                    </h2>
                    <p class="text-rose-100 max-w-2xl mx-auto text-lg">
                        <?php _e('Every session across every clinic follows these baseline standards.', 'chroma-early-start'); ?>
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php
                    $pillars = array(
                        array('icon' => 'smile', 'title' => 'Unconditional Joy', 'desc' => 'Childhood should be magical. We prioritize laughter in every interaction.'),
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
                            <p class="text-sm text-rose-100 leading-relaxed">
                                <?php echo esc_html($p['desc']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Comparison (Old vs New) -->
        <section class="py-24 bg-white">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 fade-in-up">
                    <h2 class="text-3xl font-bold text-stone-900 mb-4">
                        <?php _e('The Chroma Difference', 'chroma-early-start'); ?>
                    </h2>
                    <p class="text-stone-600">
                        <?php _e('How our assent-based model compares to traditional approaches.', 'chroma-early-start'); ?>
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <div class="bg-stone-50 p-10 rounded-[2rem] border border-stone-100 fade-in-up">
                        <h4 class="text-stone-400 font-bold uppercase tracking-widest text-xs mb-6">
                            <?php _e('Traditional ABA', 'chroma-early-start'); ?>
                        </h4>
                        <ul class="space-y-4 text-stone-500">
                            <li class="flex items-start"><i data-lucide="x-circle"
                                    class="w-5 h-5 mr-3 text-stone-300 shrink-0 mt-0.5"></i>
                                <?php _e('Forced compliance to meet trial counts.', 'chroma-early-start'); ?>
                            </li>
                            <li class="flex items-start"><i data-lucide="x-circle"
                                    class="w-5 h-5 mr-3 text-stone-300 shrink-0 mt-0.5"></i>
                                <?php _e('Rigid desk-based learning environments.', 'chroma-early-start'); ?>
                            </li>
                            <li class="flex items-start"><i data-lucide="x-circle"
                                    class="w-5 h-5 mr-3 text-stone-300 shrink-0 mt-0.5"></i>
                                <?php _e('Ignoring "problem behaviors" during tasks.', 'chroma-early-start'); ?>
                            </li>
                            <li class="flex items-start"><i data-lucide="x-circle"
                                    class="w-5 h-5 mr-3 text-stone-300 shrink-0 mt-0.5"></i>
                                <?php _e('Siloed therapy with little collaboration.', 'chroma-early-start'); ?>
                            </li>
                        </ul>
                    </div>
                    <div class="bg-rose-50 p-10 rounded-[2rem] border border-rose-100 fade-in-up">
                        <h4 class="text-rose-600 font-bold uppercase tracking-widest text-xs mb-6">
                            <?php _e('PrismaPath™ Model', 'chroma-early-start'); ?>
                        </h4>
                        <ul class="space-y-4 text-stone-700">
                            <li class="flex items-start"><i data-lucide="check-circle-2"
                                    class="w-5 h-5 mr-3 text-rose-500 shrink-0 mt-0.5"></i>
                                <?php _e('Assent is required; joy is the metric.', 'chroma-early-start'); ?>
                            </li>
                            <li class="flex items-start"><i data-lucide="check-circle-2"
                                    class="w-5 h-5 mr-3 text-rose-500 shrink-0 mt-0.5"></i>
                                <?php _e('Naturalistic, play-led environments.', 'chroma-early-start'); ?>
                            </li>
                            <li class="flex items-start"><i data-lucide="check-circle-2"
                                    class="w-5 h-5 mr-3 text-rose-500 shrink-0 mt-0.5"></i>
                                <?php _e('Behaviors are treated as communication.', 'chroma-early-start'); ?>
                            </li>
                            <li class="flex items-start"><i data-lucide="check-circle-2"
                                    class="w-5 h-5 mr-3 text-rose-500 shrink-0 mt-0.5"></i>
                                <?php _e('Unified roadmaps across all specialties.', 'chroma-early-start'); ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-stone-900 text-white text-center">
            <div class="max-w-4xl mx-auto px-4 fade-in-up">
                <h2 class="text-4xl font-bold mb-6">
                    <?php _e('See the PrismaPath™ in action.', 'chroma-early-start'); ?>
                </h2>
                <p class="text-xl text-stone-400 mb-10 leading-relaxed">
                    <?php _e('Schedule a personal tour to see how our clinicians engage with children and how our clinics are designed for discovery.', 'chroma-early-start'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/locations/')); ?>"
                    class="bg-rose-600 text-white px-10 py-5 rounded-full font-bold text-lg hover:bg-rose-500 transition-all shadow-xl inline-block active:scale-95">
                    <?php _e('Find a Clinic Near You', 'chroma-early-start'); ?>
                </a>
            </div>
        </section>
    </main>

    <?php
endwhile;
get_footer();
