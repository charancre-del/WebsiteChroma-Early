<?php
/**
 * Template Name: FAQ Page
 * Displays a comprehensive FAQ section with categories.
 *
 * @package EarlyStart_Early_Start
 */

get_header();
?>

<main class="pt-20">
    <!-- Hero Section -->
    <section class="relative bg-white pt-24 pb-20 lg:pt-32 overflow-hidden">
        <div
            class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-amber-50 rounded-full blur-3xl opacity-50">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
            <span
                class="inline-block px-4 py-2 bg-amber-50 text-amber-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
                <?php _e('Support & Clarity', 'earlystart-early-learning'); ?>
            </span>
            <h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
                <?php _e('How can we', 'earlystart-early-learning'); ?><br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-600 to-orange-500">
                    <?php _e('Help you today?', 'earlystart-early-learning'); ?>
                </span>
            </h1>
            <p class="text-xl text-stone-700 max-w-3xl mx-auto leading-relaxed fade-in-up">
                <?php _e('Find answers to common questions about our clinical model, enrollment process, and how we support your child\'s unique journey.', 'earlystart-early-learning'); ?>
            </p>
        </div>
    </section>

    <!-- FAQ Categories & Questions -->
    <section class="py-24 bg-stone-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <?php
            $faq_categories = array(
                array(
                    'title' => 'Therapy & Services',
                    'questions' => array(
                        array('q' => 'What is the "Early Start Standard" for ABA?', 'a' => 'We practice assent-based, play-led ABA. This means we prioritize the child\'s happiness and willingness to participate. We don\'t use forced compliance; we build skills through trust and meaningful reinforcement.'),
                        array('q' => 'Do you offer Speech and OT alongside ABA?', 'a' => 'Yes. We are a holistic provider. Our BCBAs collaborate daily with our Speech and Occupational therapists to ensure goals are aligned and the child isn\'t overwhelmed by conflicting approaches.'),
                        array('q' => 'How many hours of therapy will my child need?', 'a' => 'This is determined during our initial assessment. We look at your child\'s specific needs, age, and goals. Some children thrive with 15 hours, while others require more intensive support.'),
                    )
                ),
                array(
                    'title' => 'Enrollment & Insurance',
                    'questions' => array(
                        array('q' => 'Do you accept insurance?', 'a' => 'We accept most major commercial insurance providers. Our admissions team handles all benefit verifications and authorizations so you can focus on your child.'),
                        array('q' => 'What is the typical age range for your programs?', 'a' => 'We specialize in early intervention, typically serving children from 18 months through 12 years of age.'),
                        array('q' => 'Is there a waitlist?', 'a' => 'We strive to provide immediate access to care. While some specific time slots may be full, we usually have openings at our various clinic locations across the region.'),
                    )
                ),
                array(
                    'title' => 'The Bridge Program',
                    'questions' => array(
                        array('q' => 'What makes the Bridge Program different from standard ABA?', 'a' => 'The Bridge Program simulates a preschool environment. While a child still has clinical support, they are learning "group skills" like sitting in a circle, transitions, and peer play that are vital for success in a general education classroom.'),
                        array('q' => 'Is the Bridge Program full-time?', 'a' => 'We offer both part-time and full-time tracks for Bridge, depending on the child\'s current readiness and the family\'s goals for school transition.'),
                    )
                )
            );

            foreach ($faq_categories as $cat): ?>
                <div class="mb-20 fade-in-up">
                    <h2 class="text-3xl font-bold text-stone-900 mb-8 flex items-center gap-4">
                        <span class="w-12 h-1 bg-stone-200 rounded-full"></span>
                        <?php echo esc_html($cat['title']); ?>
                    </h2>
                    <div class="space-y-4">
                        <?php foreach ($cat['questions'] as $faq): ?>
                            <details
                                class="group bg-white rounded-[2rem] p-8 border border-stone-100 cursor-pointer hover:shadow-md transition-all">
                                <summary class="flex items-center justify-between font-bold text-xl text-stone-900 list-none">
                                    <span class="pr-8">
                                        <?php echo esc_html($faq['q']); ?>
                                    </span>
                                    <span
                                        class="w-10 h-10 bg-stone-50 rounded-full flex items-center justify-center text-stone-300 group-open:rotate-180 transition-transform group-hover:bg-amber-50 group-hover:text-amber-600 shrink-0">
                                        <i data-lucide="chevron-down" class="w-5 h-5"></i>
                                    </span>
                                </summary>
                                <div class="mt-6 text-stone-700 leading-relaxed border-t border-stone-50 pt-6">
                                    <?php echo wp_kses_post($faq['a']); ?>
                                </div>
                            </details>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </section>

    <!-- Outreach CTA -->
    <section class="py-24 bg-stone-900 text-white text-center relative overflow-hidden">
        <div
            class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-amber-500 via-transparent to-transparent">
        </div>
        <div class="max-w-4xl mx-auto px-4 relative z-10 fade-in-up">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">
                <?php _e('Still have questions?', 'earlystart-early-learning'); ?>
            </h2>
            <p class="text-stone-300 text-xl mb-10 leading-relaxed">
                <?php _e('Our admissions coordinators are happy to jump on a call and talk through your specific situation. We are here to help.', 'earlystart-early-learning'); ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo esc_url(earlystart_get_page_link('contact')); ?>"
                    class="bg-amber-600 text-white px-10 py-5 rounded-full font-bold text-lg hover:bg-amber-500 transition-all shadow-xl inline-block active:scale-95">
                    <?php _e('Message Admissions', 'earlystart-early-learning'); ?>
                </a>
                <a href="tel:5551234567"
                    class="bg-white/10 text-white border border-white/20 px-10 py-5 rounded-full font-bold text-lg hover:bg-white/20 transition-all inline-block">
                    <?php _e('Call (555) 123-4567', 'earlystart-early-learning'); ?>
                </a>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
