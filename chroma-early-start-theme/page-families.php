<?php
/*
 * Template Name: For Families
 * Description: Parent portal with resources, menus, events, and FAQs.
 */

get_header();

// Hero Data
$hero_badge = 'Parent Dashboard';
$hero_title = 'Partners in your child\'s journey.';
$hero_subtitle = 'Everything you need to manage your enrollment, stay connected, and engage with the Chroma community.';
?>

<div class="bg-stone-50 min-h-screen">

    <!-- Hero Section -->
    <section class="py-20 bg-white text-center border-b border-stone-100">
        <div class="max-w-4xl mx-auto px-4">
            <span class="text-blue-600 font-bold tracking-[0.2em] text-xs uppercase mb-3 block">
                <?php echo esc_html($hero_badge); ?>
            </span>
            <h1 class="text-5xl md:text-6xl font-bold text-stone-900 mb-6 font-display">
                <?php echo esc_html($hero_title); ?>
            </h1>
            <p class="text-lg text-stone-600">
                <?php echo esc_html($hero_subtitle); ?>
            </p>
        </div>
    </section>

    <!-- Resources Grid (Quick Links) -->
    <section id="resources" class="py-24 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-stone-900">
                    <?php _e('Parent Essentials', 'chroma-early-start'); ?>
                </h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                <!-- Procare Cloud -->
                <a href="https://schools.procareconnect.com/login" target="_blank" rel="noopener noreferrer"
                    class="bg-white p-8 rounded-[2rem] shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all group border border-stone-100 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-3xl mb-4 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i data-lucide="cloud" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-bold text-lg text-stone-900 mb-2">
                        <?php _e('Procare Cloud', 'chroma-early-start'); ?>
                    </h3>
                    <p class="text-xs text-stone-500">
                        <?php _e('Daily reports, photos, and attendance tracking.', 'chroma-early-start'); ?>
                    </p>
                </a>

                <!-- Tuition Portal -->
                <a href="https://schools.procareconnect.com/login" target="_blank" rel="noopener noreferrer"
                    class="bg-white p-8 rounded-[2rem] shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all group border border-stone-100 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-3xl mb-4 text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <i data-lucide="credit-card" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-bold text-lg text-stone-900 mb-2">
                        <?php _e('Tuition Portal', 'chroma-early-start'); ?>
                    </h3>
                    <p class="text-xs text-stone-500">
                        <?php _e('Securely view statements and make payments.', 'chroma-early-start'); ?>
                    </p>
                </a>

                <!-- Parent Handbook (Placeholder Modal Trigger) -->
                <div
                    class="bg-white p-8 rounded-[2rem] shadow-sm group border border-stone-100 flex flex-col items-center text-center opacity-75">
                    <div
                        class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-3xl mb-4 text-amber-600">
                        <i data-lucide="book-open" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-bold text-lg text-stone-900 mb-2">
                        <?php _e('Parent Handbook', 'chroma-early-start'); ?>
                    </h3>
                    <p class="text-xs text-stone-500">
                        <?php _e('Available from your Center Director.', 'chroma-early-start'); ?>
                    </p>
                </div>

                <!-- Enrollment Agreement -->
                <div
                    class="bg-white p-8 rounded-[2rem] shadow-sm group border border-stone-100 flex flex-col items-center text-center opacity-75">
                    <div
                        class="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center text-3xl mb-4 text-rose-600">
                        <i data-lucide="file-signature" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-bold text-lg text-stone-900 mb-2">
                        <?php _e('Enrollment Agreement', 'chroma-early-start'); ?>
                    </h3>
                    <p class="text-xs text-stone-500">
                        <?php _e('Update your annual enrollment documents.', 'chroma-early-start'); ?>
                    </p>
                </div>

                <!-- GA Pre-K Enrollment -->
                <div
                    class="bg-white p-8 rounded-[2rem] shadow-sm group border border-stone-100 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-stone-100 rounded-2xl flex items-center justify-center text-3xl mb-4 text-stone-600 group-hover:bg-stone-800 group-hover:text-white transition-colors">
                        <i data-lucide="graduation-cap" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-bold text-lg text-stone-900 mb-2">
                        <?php _e('GA Pre-K Enrollment', 'chroma-early-start'); ?>
                    </h3>
                    <p class="text-xs text-stone-500">
                        <?php _e('Lottery registration and required state forms.', 'chroma-early-start'); ?>
                    </p>
                </div>

                <!-- Waitlist -->
                <a href="<?php echo esc_url(home_url('/contact-us/')); ?>"
                    class="bg-white p-8 rounded-[2rem] shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all group border border-stone-100 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-stone-100 rounded-2xl flex items-center justify-center text-3xl mb-4 text-stone-600 group-hover:bg-stone-800 group-hover:text-white transition-colors">
                        <i data-lucide="clock" class="w-8 h-8"></i>
                    </div>
                    <h3 class="font-bold text-lg text-stone-900 mb-2">
                        <?php _e('Join Waitlist', 'chroma-early-start'); ?>
                    </h3>
                    <p class="text-xs text-stone-500">
                        <?php _e('Reserve a spot for siblings or future terms.', 'chroma-early-start'); ?>
                    </p>
                </a>
            </div>
        </div>
    </section>

    <!-- Nutrition & Menus -->
    <section id="nutrition" class="py-20 bg-white border-t border-stone-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-green-600 font-bold tracking-[0.2em] text-xs uppercase mb-3 block">
                    <?php _e('Wellness', 'chroma-early-start'); ?>
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-4">
                    <?php _e('What\'s for lunch?', 'chroma-early-start'); ?>
                </h2>
                <p class="text-stone-600 max-w-2xl mx-auto">
                    <?php _e('Our in-house chefs prepare balanced, CACFP-compliant meals fresh daily. We are a nut-aware facility.', 'chroma-early-start'); ?>
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Menu Downloads -->
                <div class="bg-stone-50 p-8 rounded-[2rem] border border-stone-100">
                    <h3 class="font-bold text-xl text-stone-900 mb-6 flex items-center gap-3">
                        <i data-lucide="utensils" class="w-6 h-6 text-amber-500"></i>
                        <?php _e('Monthly Menus', 'chroma-early-start'); ?>
                    </h3>
                    <div class="space-y-4">
                        <div
                            class="w-full flex items-center justify-between p-4 rounded-xl bg-white hover:bg-green-50 transition-colors group text-left cursor-pointer border border-stone-100">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-stone-50 rounded-full flex items-center justify-center text-green-600 shadow-sm">
                                    <i data-lucide="carrot" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-stone-900">
                                        <?php _e('Current Month Menu', 'chroma-early-start'); ?>
                                    </p>
                                    <p class="text-xs text-stone-500">
                                        <?php _e('Standard (Ages 1-12)', 'chroma-early-start'); ?>
                                    </p>
                                </div>
                            </div>
                            <i data-lucide="download" class="w-5 h-5 text-stone-300 group-hover:text-green-600"></i>
                        </div>

                        <div
                            class="w-full flex items-center justify-between p-4 rounded-xl bg-white hover:bg-blue-50 transition-colors group text-left cursor-pointer border border-stone-100">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 bg-stone-50 rounded-full flex items-center justify-center text-blue-600 shadow-sm">
                                    <i data-lucide="baby" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-stone-900">
                                        <?php _e('Infant Puree Menu', 'chroma-early-start'); ?>
                                    </p>
                                    <p class="text-xs text-stone-500">
                                        <?php _e('Stage 1 & 2 Solids', 'chroma-early-start'); ?>
                                    </p>
                                </div>
                            </div>
                            <i data-lucide="download" class="w-5 h-5 text-stone-300 group-hover:text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="relative h-[400px] rounded-[2rem] overflow-hidden shadow-lg border border-stone-100">
                    <div class="absolute inset-0 bg-stone-200">
                        <!-- Placeholder if image fails -->
                        <div class="flex items-center justify-center h-full text-stone-400">
                            <i data-lucide="image" class="w-16 h-16"></i>
                        </div>
                    </div>
                    <?php
                    // Using a placeholder image or theme image
                    $menu_image = 'https://images.unsplash.com/photo-1564834724105-918b73d1b9e0?q=80&w=800&auto=format&fit=crop';
                    ?>
                    <img src="<?php echo esc_url($menu_image); ?>" class="absolute inset-0 w-full h-full object-cover"
                        alt="Healthy Kids Meal" />
                    <div
                        class="absolute bottom-4 left-4 bg-white/95 backdrop-blur px-4 py-2 rounded-xl text-xs font-bold text-stone-900 shadow-sm flex items-center">
                        <i data-lucide="check-circle" class="w-4 h-4 text-green-600 mr-2"></i>
                        <?php _e('Fresh Fruit Daily', 'chroma-early-start'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section id="events" class="py-24 bg-amber-50 relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-1/2 h-full bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-amber-100/50 via-transparent to-transparent">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-amber-600 font-bold tracking-[0.2em] text-xs uppercase mb-3 block">
                        <?php _e('Community', 'chroma-early-start'); ?>
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-6">
                        <?php _e('Traditions & Celebrations', 'chroma-early-start'); ?>
                    </h2>
                    <p class="text-stone-600 mb-8 text-lg">
                        <?php _e('We believe in building a village. Our calendar is peppered with events designed to bring families together and celebrate our students\' milestones.', 'chroma-early-start'); ?>
                    </p>

                    <div class="space-y-8">
                        <div>
                            <h3 class="font-bold text-xl text-stone-900 mb-2 flex items-center gap-2">
                                <i data-lucide="calendar" class="w-5 h-5 text-amber-500"></i>
                                <?php _e('Quarterly Family Events', 'chroma-early-start'); ?>
                            </h3>
                            <p class="text-sm text-stone-500 leading-relaxed">
                                <?php _e('Every season brings a reason to gather. From our Fall Festival and Winter "Cookies & Cocoa" to our Spring Art Show and Summer Splash Days.', 'chroma-early-start'); ?>
                            </p>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl text-stone-900 mb-2 flex items-center gap-2">
                                <i data-lucide="star" class="w-5 h-5 text-rose-500"></i>
                                <?php _e('Pre-K Graduation', 'chroma-early-start'); ?>
                            </h3>
                            <p class="text-sm text-stone-500 leading-relaxed">
                                <?php _e('A cap-and-gown ceremony celebrating our 4 and 5-year-olds as they transition to Kindergarten. It’s the highlight of our academic year!', 'chroma-early-start'); ?>
                            </p>
                        </div>
                        <div>
                            <h3 class="font-bold text-xl text-stone-900 mb-2 flex items-center gap-2">
                                <i data-lucide="handshake" class="w-5 h-5 text-green-500"></i>
                                <?php _e('Parent-Teacher Conferences', 'chroma-early-start'); ?>
                            </h3>
                            <p class="text-sm text-stone-500 leading-relaxed">
                                <?php _e('Twice a year, we sit down to review your child\'s developmental portfolio, set goals, and celebrate their individual growth curve.', 'chroma-early-start'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="relative h-[500px] rounded-[3rem] overflow-hidden shadow-2xl border-4 border-white rotate-2 bg-stone-200">
                    <?php
                    $event_image = 'https://images.unsplash.com/photo-1511895426328-dc8714191300?q=80&w=800&auto=format&fit=crop';
                    ?>
                    <img src="<?php echo esc_url($event_image); ?>" class="w-full h-full object-cover"
                        alt="School event" />
                </div>
            </div>
        </div>
    </section>

    <!-- Safety & Communication -->
    <section id="safety" class="py-24 bg-stone-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    <?php _e('Safe. Secure. Connected.', 'chroma-early-start'); ?>
                </h2>
                <p class="text-stone-400 max-w-2xl mx-auto">
                    <?php _e('We employ enterprise-grade security measures and transparent communication protocols so you can have total peace of mind while you work.', 'chroma-early-start'); ?>
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white/5 p-8 rounded-3xl border border-white/10">
                    <div class="text-4xl mb-4 text-green-400"><i data-lucide="video" class="w-8 h-8"></i></div>
                    <h3 class="font-bold text-xl mb-3">
                        <?php _e('24/7 Monitored Cameras', 'chroma-early-start'); ?>
                    </h3>
                    <p class="text-sm text-stone-400 leading-relaxed">
                        <?php _e('Our facilities are equipped with high-definition closed-circuit cameras in every classroom, hallway, and playground. Feeds are monitored by leadership.', 'chroma-early-start'); ?>
                    </p>
                </div>
                <div class="bg-white/5 p-8 rounded-3xl border border-white/10">
                    <div class="text-4xl mb-4 text-blue-400"><i data-lucide="smartphone" class="w-8 h-8"></i></div>
                    <h3 class="font-bold text-xl mb-3">
                        <?php _e('Real-Time Updates', 'chroma-early-start'); ?>
                    </h3>
                    <p class="text-sm text-stone-400 leading-relaxed">
                        <?php _e('Through the Procare app, you receive real-time notifications for meals, naps, and diaper changes, plus photos of your child engaging in the curriculum.', 'chroma-early-start'); ?>
                    </p>
                </div>
                <div class="bg-white/5 p-8 rounded-3xl border border-white/10">
                    <div class="text-4xl mb-4 text-rose-400"><i data-lucide="lock" class="w-8 h-8"></i></div>
                    <h3 class="font-bold text-xl mb-3">
                        <?php _e('Secure Access Control', 'chroma-early-start'); ?>
                    </h3>
                    <p class="text-sm text-stone-400 leading-relaxed">
                        <?php _e('Our lobbies are secured with coded keypad entry systems. Codes are unique to each family and change regularly. ID is strictly required for pickup.', 'chroma-early-start'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Operational FAQ -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-stone-900">
                    <?php _e('Operational Policy FAQ', 'chroma-early-start'); ?>
                </h2>
                <p class="text-stone-500 mt-2">
                    <?php _e('Quick answers to common day-to-day questions.', 'chroma-early-start'); ?>
                </p>
            </div>

            <div class="space-y-4">
                <details class="group bg-stone-50 rounded-2xl p-6 border border-stone-100 cursor-pointer">
                    <summary class="flex items-center justify-between font-bold text-stone-900 list-none">
                        <span>
                            <?php _e('What is the sick child policy?', 'chroma-early-start'); ?>
                        </span>
                        <span class="text-blue-600 group-open:rotate-180 transition-transform"><i
                                data-lucide="chevron-down" class="w-5 h-5"></i></span>
                    </summary>
                    <p class="mt-3 text-sm text-stone-600 leading-relaxed">
                        <?php _e('Children must be symptom-free (fever under 100.4°F, no vomiting/diarrhea) for 24 hours without medication before returning to school. Please report any contagious illnesses to the Director immediately.', 'chroma-early-start'); ?>
                    </p>
                </details>

                <details class="group bg-stone-50 rounded-2xl p-6 border border-stone-100 cursor-pointer">
                    <summary class="flex items-center justify-between font-bold text-stone-900 list-none">
                        <span>
                            <?php _e('How do you handle inclement weather?', 'chroma-early-start'); ?>
                        </span>
                        <span class="text-blue-600 group-open:rotate-180 transition-transform"><i
                                data-lucide="chevron-down" class="w-5 h-5"></i></span>
                    </summary>
                    <p class="mt-3 text-sm text-stone-600 leading-relaxed">
                        <?php _e('We generally follow the local county school system for weather closures, but we make independent decisions based on staff safety. Alerts will be sent via Procare and posted on our Facebook page by 6:00 AM.', 'chroma-early-start'); ?>
                    </p>
                </details>

                <details class="group bg-stone-50 rounded-2xl p-6 border border-stone-100 cursor-pointer">
                    <summary class="flex items-center justify-between font-bold text-stone-900 list-none">
                        <span>
                            <?php _e('What is the late pickup policy?', 'chroma-early-start'); ?>
                        </span>
                        <span class="text-blue-600 group-open:rotate-180 transition-transform"><i
                                data-lucide="chevron-down" class="w-5 h-5"></i></span>
                    </summary>
                    <p class="mt-3 text-sm text-stone-600 leading-relaxed">
                        <?php _e('We close promptly at 6:00 PM. A late fee is charged to your account for pickups after 6:05 PM to compensate our staff who stay late.', 'chroma-early-start'); ?>
                    </p>
                </details>
            </div>
        </div>
    </section>

</div>

<?php
// Render Lucide icons script for this page
echo '<script>lucide.createIcons();</script>';
get_footer();
?>