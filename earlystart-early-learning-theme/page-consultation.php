<?php
/**
 * Template Name: Book a Consultation
 * Specialized landing page for clinical intake and consultation scheduling.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

while (have_posts()):
    the_post();
    ?>

    <main class="pt-20 bg-stone-50 min-h-screen">
        <!-- Hero / Header -->
        <section class="bg-white py-20 border-b border-stone-100">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <span
                    class="inline-block px-4 py-2 bg-rose-50 text-rose-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6">
                    <?php _e('Clinical Intake', 'earlystart-early-learning'); ?>
                </span>
                <h1 class="text-4xl md:text-5xl font-bold text-stone-900 mb-6">
                    <?php _e('Book Your Clinical Consultation', 'earlystart-early-learning'); ?>
                </h1>
                <p class="text-xl text-stone-700 leading-relaxed max-w-2xl mx-auto">
                    <?php _e('Take the first step toward personalized, assent-based therapy. Our clinical team is ready to listen and build a plan for your child.', 'earlystart-early-learning'); ?>
                </p>
            </div>
        </section>

        <!-- Process Section -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        class="bg-white p-8 rounded-3xl border border-stone-100 shadow-sm flex flex-col items-center text-center">
                        <div
                            class="w-12 h-12 bg-rose-50 rounded-full flex items-center justify-center text-rose-700 mb-6 font-bold">
                            1</div>
                        <h3 class="font-bold text-lg mb-2 text-stone-900">
                            <?php _e('Initial Match', 'earlystart-early-learning'); ?>
                        </h3>
                        <p class="text-stone-700 text-sm">
                            <?php _e('Share basic info about your child\'s needs and your location.', 'earlystart-early-learning'); ?>
                        </p>
                    </div>
                    <div
                        class="bg-white p-8 rounded-3xl border border-stone-100 shadow-sm flex flex-col items-center text-center">
                        <div
                            class="w-12 h-12 bg-rose-50 rounded-full flex items-center justify-center text-rose-700 mb-6 font-bold">
                            2</div>
                        <h3 class="font-bold text-lg mb-2 text-stone-900">
                            <?php _e('Expert Review', 'earlystart-early-learning'); ?>
                        </h3>
                        <p class="text-stone-700 text-sm">
                            <?php _e('A Clinical Director reviews your profile to prepare for the call.', 'earlystart-early-learning'); ?>
                        </p>
                    </div>
                    <div
                        class="bg-white p-8 rounded-3xl border border-stone-100 shadow-sm flex flex-col items-center text-center">
                        <div
                            class="w-12 h-12 bg-rose-50 rounded-full flex items-center justify-center text-rose-700 mb-6 font-bold">
                            3</div>
                        <h3 class="font-bold text-lg mb-2 text-stone-900">
                            <?php _e('Consultation', 'earlystart-early-learning'); ?>
                        </h3>
                        <p class="text-stone-700 text-sm">
                            <?php _e('A 20-minute deep dive into goals, therapy models, and scheduling.', 'earlystart-early-learning'); ?>
                        </p>
                    </div>
                </div>

                <!-- Form / Scheduler Container -->
                <div
                    class="mt-16 bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-stone-100 max-w-5xl mx-auto flex flex-col lg:flex-row">
                    <!-- Intake Info -->
                    <div class="lg:w-2/5 bg-stone-900 p-12 text-white flex flex-col">
                        <h3 class="text-2xl font-bold mb-8">
                            <?php _e('Consultation Focus', 'earlystart-early-learning'); ?>
                        </h3>
                        <ul class="space-y-6 flex-grow">
                            <li class="flex items-start text-stone-300">
                                <i data-lucide="check-circle" class="w-5 h-5 text-rose-500 mr-4 shrink-0"></i>
                                <span>
                                    <?php _e('Personalized Clinical Roadmap', 'earlystart-early-learning'); ?>
                                </span>
                            </li>
                            <li class="flex items-start text-stone-300">
                                <i data-lucide="check-circle" class="w-5 h-5 text-rose-500 mr-4 shrink-0"></i>
                                <span>
                                    <?php _e('Insurance & Enrollment Support', 'earlystart-early-learning'); ?>
                                </span>
                            </li>
                            <li class="flex items-start text-stone-300">
                                <i data-lucide="check-circle" class="w-5 h-5 text-rose-500 mr-4 shrink-0"></i>
                                <span>
                                    <?php _e('Clinic & Social Dynamics Tour', 'earlystart-early-learning'); ?>
                                </span>
                            </li>
                        </ul>
                        <div class="mt-12 p-6 bg-white/5 rounded-2xl border border-white/10 italic text-stone-300 text-sm">
                            <?php _e('Note: Consultations are for discovery; a full clinical assessment follows intake when clinically appropriate.', 'earlystart-early-learning'); ?>
                        </div>
                    </div>

                    <!-- Form Side -->
                    <div class="lg:w-3/5 p-12">
                        <div id="consultation-form-placeholder" class="space-y-10">
                            <section id="intake-form" class="scroll-mt-24">
                                <div class="mb-8">
                                    <span class="text-xs font-bold uppercase tracking-widest text-rose-700"><?php _e('Step 1', 'earlystart-early-learning'); ?></span>
                                    <h4 class="text-xl font-bold text-stone-900 mt-2 mb-4">
                                        <?php _e('Clinical Intake Form', 'earlystart-early-learning'); ?>
                                    </h4>
                                    <p class="text-stone-700 text-sm leading-relaxed">
                                        <?php _e('Share the details our intake team needs to understand your child, services requested, preferred location, and next steps.', 'earlystart-early-learning'); ?>
                                    </p>
                                </div>
                                <?php
                                if (shortcode_exists('earlystart_intake_form')) {
                                    echo do_shortcode('[earlystart_intake_form]');
                                } elseif (shortcode_exists('earlystart_contact_form')) {
                                    echo do_shortcode('[earlystart_contact_form type="intake"]');
                                }
                                ?>
                            </section>

                            <section id="verification-form" class="scroll-mt-24 border-t border-stone-100 pt-10">
                                <div class="mb-8">
                                    <span class="text-xs font-bold uppercase tracking-widest text-rose-700"><?php _e('Step 2', 'earlystart-early-learning'); ?></span>
                                    <h4 class="text-xl font-bold text-stone-900 mt-2 mb-4">
                                        <?php _e('Benefits Verification Form', 'earlystart-early-learning'); ?>
                                    </h4>
                                    <p class="text-stone-700 text-sm leading-relaxed">
                                        <?php _e('Submit insurance or payer details so our team can begin verification and explain available coverage paths.', 'earlystart-early-learning'); ?>
                                    </p>
                                </div>
                                <?php
                                if (shortcode_exists('earlystart_verification_form')) {
                                    echo do_shortcode('[earlystart_verification_form]');
                                } elseif (shortcode_exists('earlystart_contact_form')) {
                                    echo do_shortcode('[earlystart_contact_form type="verification"]');
                                }
                                ?>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust Signals -->
        <section class="py-20 bg-stone-50">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <p class="text-stone-300 font-bold uppercase tracking-widest text-xs mb-8">
                    <?php _e('Why Consultation Matters', 'earlystart-early-learning'); ?>
                </p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8 grayscale opacity-60">
                    <!-- Placeholders for trust logos/badges -->
                    <div
                        class="flex items-center justify-center font-bold text-stone-900 text-lg italic border border-stone-200 p-4 rounded-xl">
                        CASP Accredited</div>
                    <div
                        class="flex items-center justify-center font-bold text-stone-900 text-lg italic border border-stone-200 p-4 rounded-xl">
                        Assent-First ABA</div>
                    <div
                        class="flex items-center justify-center font-bold text-stone-900 text-lg italic border border-stone-200 p-4 rounded-xl md:col-span-1 col-span-2">
                        BHCOE Award</div>
                </div>
            </div>
        </section>
    </main>

    <?php
endwhile;
get_footer();
