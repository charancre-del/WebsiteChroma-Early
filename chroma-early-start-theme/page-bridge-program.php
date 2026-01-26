<?php
/**
 * Template Name: Chroma Bridge Program
 * Focuses on School Readiness and the transition from 1:1 to small group learning.
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
                class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-blue-50 rounded-full blur-3xl opacity-50">
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="fade-in-up">
                        <div class="inline-flex items-center space-x-2 mb-6">
                            <span class="w-8 h-1 bg-blue-500 rounded-full"></span>
                            <span class="text-blue-600 font-bold tracking-widest text-sm uppercase">
                                <?php _e('Signature Clinical Pre-K', 'chroma-early-start'); ?>
                            </span>
                        </div>
                        <h1 class="text-5xl md:text-6xl font-bold text-stone-900 mb-6 leading-tight">
                            <?php _e('The Chroma', 'chroma-early-start'); ?><br><span class="text-blue-600">
                                <?php _e('Bridge Program.', 'chroma-early-start'); ?>
                            </span>
                        </h1>
                        <p class="text-xl text-stone-600 mb-8 leading-relaxed">
                            <?php _e('Preparing early learners for the transition from 1:1 therapy to a social classroom environment. Our "Bridge" simulates the preschool experience with clinical precision to ensure true school readiness.', 'chroma-early-start'); ?>
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?php echo esc_url(home_url('/consultation/')); ?>"
                                class="bg-blue-600 text-white px-8 py-4 rounded-full font-bold hover:bg-blue-500 transition-all shadow-lg flex items-center justify-center">
                                <?php _e('Book a Consultation', 'chroma-early-start'); ?>
                                <i data-lucide="calendar" class="ml-2 w-5 h-5"></i>
                            </a>
                        </div>
                    </div>
                    <div class="relative fade-in-up">
                        <div class="absolute inset-0 bg-blue-100 rounded-[3rem] transform -rotate-3"></div>
                        <div
                            class="relative bg-white rounded-[3rem] h-[500px] overflow-hidden shadow-2xl border border-stone-100 flex items-center justify-center">
                            <div class="text-center p-10">
                                <div
                                    class="bg-blue-50 w-32 h-32 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i data-lucide="graduation-cap" class="w-16 h-16 text-blue-500"></i>
                                </div>
                                <h3 class="text-3xl font-bold text-stone-900 mb-2">
                                    <?php _e('School Ready', 'chroma-early-start'); ?>
                                </h3>
                                <p class="text-stone-500">
                                    <?php _e('The Ultimate Transition Pathway', 'chroma-early-start'); ?>
                                </p>
                                <div class="flex justify-center space-x-2 mt-6">
                                    <span class="w-3 h-3 rounded-full bg-blue-300"></span>
                                    <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bridging the Gap (Transition Model) -->
        <section class="py-24 bg-stone-50 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 fade-in-up">
                    <h2 class="text-4xl font-bold text-stone-900 mb-4">
                        <?php _e('Bridging the Gap', 'chroma-early-start'); ?>
                    </h2>
                    <p class="text-stone-600 max-w-2xl mx-auto text-lg italic leading-relaxed">
                        <?php _e('Moving from a quiet clinic room to a noisy school classroom is a huge leap. Our Bridge Program provides the structured support to navigate that jump successfully.', 'chroma-early-start'); ?>
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Clinical 1:1 -->
                    <div
                        class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-stone-100 relative group hover:shadow-lg transition-all fade-in-up">
                        <div
                            class="absolute -top-6 left-8 bg-stone-900 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase">
                            <?php _e('Phase 1', 'chroma-early-start'); ?>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900 mb-4 mt-4">
                            <?php _e('Foundation 1:1', 'chroma-early-start'); ?>
                        </h3>
                        <p class="text-stone-600 text-sm mb-6 leading-relaxed">
                            <?php _e('Individualized clinical focus to master core communication and self-regulation.', 'chroma-early-start'); ?>
                        </p>
                    </div>
                    <!-- The Bridge -->
                    <div
                        class="bg-blue-600 p-10 rounded-[2.5rem] shadow-xl border border-blue-500 relative transform scale-105 z-10 text-white fade-in-up">
                        <div
                            class="absolute -top-6 left-8 bg-white text-blue-600 px-4 py-2 rounded-lg font-bold text-xs uppercase shadow-sm">
                            <?php _e('Chroma Bridge', 'chroma-early-start'); ?>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 mt-4">
                            <?php _e('Classroom Skills', 'chroma-early-start'); ?>
                        </h3>
                        <p class="text-blue-100 text-sm mb-6 leading-relaxed">
                            <?php _e('Small group dynamics (1:3 ratio). Mock classroom routines. Functional social play with peers.', 'chroma-early-start'); ?>
                        </p>
                    </div>
                    <!-- Graduation -->
                    <div
                        class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-stone-100 relative group hover:shadow-lg transition-all fade-in-up">
                        <div
                            class="absolute -top-6 left-8 bg-stone-900 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase">
                            <?php _e('Goal', 'chroma-early-start'); ?>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900 mb-4 mt-4">
                            <?php _e('Social Integration', 'chroma-early-start'); ?>
                        </h3>
                        <p class="text-stone-600 text-sm mb-6 leading-relaxed">
                            <?php _e('Successful transition to a traditional educational setting with minimal support.', 'chroma-early-start'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Curriculum / Readiness Metrics -->
        <section class="py-24 bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="fade-in-up">
                        <span class="text-blue-600 font-bold tracking-widest text-sm uppercase mb-4 block">
                            <?php _e('Readiness Metrics', 'chroma-early-start'); ?>
                        </span>
                        <h2 class="text-4xl font-bold text-stone-900 mb-10">
                            <?php _e('Clinical Pre-K Focus', 'chroma-early-start'); ?>
                        </h2>

                        <div class="space-y-8">
                            <?php
                            $focus_areas = array(
                                array('icon' => 'users', 'title' => 'Circle Time Skills', 'desc' => 'Staying engaged during group instruction, following choral responses, and raising hands.'),
                                array('icon' => 'clock', 'title' => 'Routine Navigation', 'desc' => 'Moving through the school day (recess, lunch, work) with independence and no distress.'),
                                array('icon' => 'smile', 'title' => 'Peer Collaboration', 'desc' => 'Sharing classroom materials, initiating joint play, and solving social conflicts.'),
                                array('icon' => 'check-circle', 'title' => 'Self-Help Agency', 'desc' => 'Mastering personal belongings, independence in hygiene, and following classroom rules.'),
                            );
                            foreach ($focus_areas as $area): ?>
                                <div class="flex items-start">
                                    <div
                                        class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mr-6 text-blue-600 shrink-0 border border-blue-100 shadow-sm">
                                        <i data-lucide="<?php echo $area['icon']; ?>" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xl font-bold text-stone-900 mb-2">
                                            <?php echo esc_html($area['title']); ?>
                                        </h4>
                                        <p class="text-stone-600 text-sm leading-relaxed">
                                            <?php echo esc_html($area['desc']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="relative fade-in-up">
                        <div class="absolute inset-0 bg-stone-100 rounded-[3rem] transform rotate-3"></div>
                        <div class="relative bg-white p-12 rounded-[3rem] shadow-2xl border border-stone-100">
                            <h3 class="text-2xl font-bold text-stone-900 mb-8 text-center border-b border-stone-100 pb-6">
                                <?php _e('Bridge Daily Routine', 'chroma-early-start'); ?>
                            </h3>
                            <div class="space-y-6">
                                <?php
                                $schedule = array(
                                    '9:00 AM' => 'Arrival & Unpack (Independence)',
                                    '9:30 AM' => 'Morning Circle (Group Skills)',
                                    '10:30 AM' => 'Literacy & Play Centers',
                                    '11:30 AM' => 'Structured Social Recess',
                                    '12:30 PM' => 'Lunch & Departure Skills',
                                );
                                foreach ($schedule as $time => $activity): ?>
                                    <div class="flex items-center text-sm md:text-base">
                                        <span class="w-24 font-bold text-blue-600 shrink-0">
                                            <?php echo esc_html($time); ?>
                                        </span>
                                        <span class="text-stone-600 border-l border-stone-100 pl-6">
                                            <?php echo esc_html($activity); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="py-24 bg-blue-600 text-white overflow-hidden text-center">
            <div class="max-w-4xl mx-auto px-4 fade-in-up">
                <h2 class="text-4xl md:text-5xl font-bold mb-8">
                    <?php _e('Is your child ready for the Bridge?', 'chroma-early-start'); ?>
                </h2>
                <p class="text-blue-100 text-xl mb-12 leading-relaxed max-w-2xl mx-auto">
                    <?php _e('Our clinical directors offer specialized consultations to assess if the Bridge Program is the right next step for your child\'s educational journey.', 'chroma-early-start'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/consultation/')); ?>"
                    class="bg-white text-blue-600 px-12 py-5 rounded-full font-bold text-lg hover:bg-blue-50 transition-all shadow-2xl inline-flex items-center gap-3">
                    <?php _e('Book a Consultation', 'chroma-early-start'); ?>
                    <i data-lucide="arrow-right" class="w-6 h-6"></i>
                </a>
            </div>
        </section>

    </main>

    <?php
endwhile;
get_footer();
