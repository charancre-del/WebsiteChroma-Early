<?php
/*
 * Template Name: For Families
 * Description: Admissions focused page with insurance verification, intake checklist, and logistics FAQs.
 */

get_header();

while (have_posts()):
    the_post();
    $page_id = get_the_ID();

    // Hero Data
    $hero_badge = get_post_meta($page_id, 'families_hero_badge', true) ?: 'Parent Resources';
    $hero_title = get_post_meta($page_id, 'families_hero_title', true) ?: 'You Are Not Alone<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">On This Journey.</span>';
    $hero_desc = get_post_meta($page_id, 'families_hero_desc', true) ?: 'Navigating early intervention can be overwhelming. We are here to guide you through insurance, diagnosis, and the first steps of therapy with clarity and compassion.';
    ?>

    <div class="bg-stone-50 min-h-screen">

        <!-- Hero Section -->
        <section class="relative bg-white pt-24 pb-20 lg:pt-32 border-b border-stone-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span
                    class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
                    <?php echo esc_html($hero_badge); ?>
                </span>
                <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
                    <?php echo wp_kses_post($hero_title); ?>
                </h1>
                <p class="text-xl text-stone-600 max-w-3xl mx-auto leading-relaxed fade-in-up">
                    <?php echo esc_html($hero_desc); ?>
                </p>
            </div>
        </section>

        <!-- Insurance & Financial -->
        <section class="py-24 bg-stone-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-start">
                    <!-- Left: Insurance Text -->
                    <div class="fade-in-up">
                        <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-6">
                            <?php _e('We Make Insurance Simple', 'chroma-early-start'); ?></h2>
                        <p class="text-lg text-stone-600 leading-relaxed mb-8">
                            <?php _e('Understanding your benefits shouldn\'t require a degree. Our dedicated admissions team handles the heavy lifting—verifying benefits, obtaining authorizations, and clearly explaining your coverage options before you start.', 'chroma-early-start'); ?>
                        </p>

                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-stone-100 mb-8">
                            <h4 class="font-bold text-stone-900 mb-4 flex items-center text-lg">
                                <i data-lucide="file-text" class="w-5 h-5 text-rose-500 mr-2"></i>
                                <?php _e('Documents Needed for Intake', 'chroma-early-start'); ?>
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-center text-stone-600 text-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-3 shrink-0"></i>
                                    <?php _e('Copy of Insurance Card (Front & Back)', 'chroma-early-start'); ?>
                                </li>
                                <li class="flex items-center text-stone-600 text-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-3 shrink-0"></i>
                                    <?php _e('Comprehensive Diagnostic Report (confirming ASD)', 'chroma-early-start'); ?>
                                </li>
                                <li class="flex items-center text-stone-600 text-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-3 shrink-0"></i>
                                    <?php _e('Pediatrician Referral for ABA Therapy', 'chroma-early-start'); ?>
                                </li>
                                <li class="flex items-center text-stone-600 text-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-3 shrink-0"></i>
                                    <?php _e('Any Previous IEPs or Therapy Evaluations', 'chroma-early-start'); ?>
                                </li>
                            </ul>
                        </div>

                        <a href="<?php echo esc_url(home_url('/contact-us/')); ?>"
                            class="bg-rose-600 text-white px-8 py-4 rounded-full font-bold hover:bg-rose-700 transition-colors inline-flex items-center shadow-lg transform hover:-translate-y-0.5">
                            <i data-lucide="shield-check" class="w-5 h-5 mr-2"></i>
                            <?php _e('Verify My Insurance', 'chroma-early-start'); ?>
                        </a>
                    </div>

                    <!-- Right: Logos & Private Pay -->
                    <div class="space-y-8 fade-in-up">
                        <div class="grid grid-cols-2 gap-6">
                            <div
                                class="bg-white p-8 rounded-3xl shadow-md text-center flex flex-col items-center justify-center aspect-square border border-stone-100 hover:-translate-y-1 transition-transform">
                                <span class="text-xl font-bold text-stone-800">BlueCross</span>
                                <span class="text-xs text-stone-400 mt-2">BlueShield</span>
                            </div>
                            <div
                                class="bg-white p-8 rounded-3xl shadow-md text-center flex flex-col items-center justify-center aspect-square border border-stone-100 hover:-translate-y-1 transition-transform">
                                <span class="text-xl font-bold text-stone-800">Aetna</span>
                                <span class="text-xs text-stone-400 mt-2">CVS Health</span>
                            </div>
                            <div
                                class="bg-white p-8 rounded-3xl shadow-md text-center flex flex-col items-center justify-center aspect-square border border-stone-100 hover:-translate-y-1 transition-transform">
                                <span class="text-xl font-bold text-stone-800">Cigna</span>
                                <span class="text-xs text-stone-400 mt-2">Evernorth</span>
                            </div>
                            <div
                                class="bg-white p-8 rounded-3xl shadow-md text-center flex flex-col items-center justify-center aspect-square border border-stone-100 hover:-translate-y-1 transition-transform">
                                <span class="text-xl font-bold text-stone-800">United</span>
                                <span class="text-xs text-stone-400 mt-2">Healthcare</span>
                            </div>
                        </div>

                        <!-- Private Pay Option -->
                        <div class="bg-stone-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-rose-500 rounded-full blur-3xl opacity-20 -mr-10 -mt-10">
                            </div>
                            <div class="relative z-10">
                                <h4 class="text-xl font-bold mb-3 flex items-center">
                                    <i data-lucide="credit-card" class="w-6 h-6 mr-3 text-rose-400"></i>
                                    <?php _e('Private Pay Options', 'chroma-early-start'); ?>
                                </h4>
                                <p class="text-stone-300 text-sm mb-4 leading-relaxed">
                                    <?php _e('Prefer not to use insurance? We offer competitive private pay rates for families who want to skip authorization wait times or do not have a formal ASD diagnosis.', 'chroma-early-start'); ?>
                                </p>
                                <div class="grid grid-cols-2 gap-2 text-sm text-stone-300">
                                    <span class="flex items-center"><i data-lucide="check"
                                            class="w-4 h-4 text-green-400 mr-2"></i>
                                        <?php _e('No Auth Wait Times', 'chroma-early-start'); ?></span>
                                    <span class="flex items-center"><i data-lucide="check"
                                            class="w-4 h-4 text-green-400 mr-2"></i>
                                        <?php _e('FSA / HSA Accepted', 'chroma-early-start'); ?></span>
                                    <span class="flex items-center"><i data-lucide="check"
                                            class="w-4 h-4 text-green-400 mr-2"></i>
                                        <?php _e('Flexible Hours', 'chroma-early-start'); ?></span>
                                    <span class="flex items-center"><i data-lucide="check"
                                            class="w-4 h-4 text-green-400 mr-2"></i>
                                        <?php _e('No Diagnosis Req.', 'chroma-early-start'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Logistics FAQ -->
        <section class="py-24 bg-white border-t border-stone-100">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-stone-900 mb-10 text-center">
                    <?php _e('Family Logistics FAQ', 'chroma-early-start'); ?></h2>

                <div class="space-y-4">
                    <!-- Q1 -->
                    <details
                        class="group border border-stone-200 rounded-2xl p-6 hover:shadow-sm transition-shadow cursor-pointer bg-white">
                        <summary class="flex justify-between items-center font-bold text-stone-800 list-none">
                            <span><?php _e('What is your sickness policy?', 'chroma-early-start'); ?></span>
                            <span class="text-stone-400 group-open:rotate-180 transition-transform"><i
                                    data-lucide="chevron-down" class="w-5 h-5"></i></span>
                        </summary>
                        <div class="text-stone-600 mt-4 leading-relaxed text-sm">
                            <p><?php _e('To protect our immunocompromised clients, we have a strict 24-hour symptom-free policy for fever, vomiting, and contagious illnesses. Please keep your child home if they are unwell.', 'chroma-early-start'); ?>
                            </p>
                        </div>
                    </details>

                    <!-- Q2 -->
                    <details
                        class="group border border-stone-200 rounded-2xl p-6 hover:shadow-sm transition-shadow cursor-pointer bg-white">
                        <summary class="flex justify-between items-center font-bold text-stone-800 list-none">
                            <span><?php _e('How does drop-off and pick-up work?', 'chroma-early-start'); ?></span>
                            <span class="text-stone-400 group-open:rotate-180 transition-transform"><i
                                    data-lucide="chevron-down" class="w-5 h-5"></i></span>
                        </summary>
                        <div class="text-stone-600 mt-4 leading-relaxed text-sm">
                            <p><?php _e('We use a secure QR code check-in system. An RBT will meet you at the reception area to transition your child into the clinic. For safety, only authorized guardians with ID can pick up a child.', 'chroma-early-start'); ?>
                            </p>
                        </div>
                    </details>

                    <!-- Q3 -->
                    <details
                        class="group border border-stone-200 rounded-2xl p-6 hover:shadow-sm transition-shadow cursor-pointer bg-white">
                        <summary class="flex justify-between items-center font-bold text-stone-800 list-none">
                            <span><?php _e('Do you offer transportation?', 'chroma-early-start'); ?></span>
                            <span class="text-stone-400 group-open:rotate-180 transition-transform"><i
                                    data-lucide="chevron-down" class="w-5 h-5"></i></span>
                        </summary>
                        <div class="text-stone-600 mt-4 leading-relaxed text-sm">
                            <p><?php _e('We do not provide home transport. However, if your child attends Chroma Early Learning Academy, our staff will handle the transition between the classroom and the therapy clinic for you.', 'chroma-early-start'); ?>
                            </p>
                        </div>
                    </details>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-24 bg-stone-50 text-center border-t border-stone-100">
            <div class="max-w-4xl mx-auto px-4 fade-in-up">
                <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-6">
                    <?php _e('Ready to take the first step?', 'chroma-early-start'); ?></h2>
                <p class="text-xl text-stone-600 mb-10">
                    <?php _e('We know this process is new for many families. We are here to answer every question, no matter how small.', 'chroma-early-start'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/contact-us/')); ?>"
                    class="bg-stone-900 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-rose-600 transition-colors shadow-lg inline-block transform hover:-translate-y-0.5">
                    <?php _e('Contact Admissions', 'chroma-early-start'); ?>
                </a>
            </div>
        </section>

    </div>

    <?php
endwhile;
get_footer();
?>