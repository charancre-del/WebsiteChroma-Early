<?php
/*
 * Template Name: For Families
 * Description: Comprehensive Admissions & Resources page (Insurance, Intake, Logistics, Parent Training).
 */

get_header();

while (have_posts()):
    the_post();
    $page_id = get_the_ID();

    // Hero Data
    $hero_badge = get_post_meta($page_id, 'families_hero_badge', true) ?: 'Parent Resources';
    $hero_title = get_post_meta($page_id, 'families_hero_title', true) ?: 'You Are Not Alone<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">On This Journey.</span>';
    $hero_desc = get_post_meta($page_id, 'families_hero_desc', true) ?: 'Navigating early intervention can be overwhelming. We are here to guide you through insurance, diagnosis, and the first steps of therapy with clarity and compassion. We partner with you to unlock your child\'s potential.';
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

        <!-- Why Early Intervention Matters (New Section) -->
        <section class="py-24 bg-white border-b border-stone-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-2 gap-16 items-center">
                    <div class="fade-in-up">
                        <span
                            class="text-rose-600 font-bold tracking-widest text-sm uppercase mb-4 block"><?php _e('The "Magic Window"', 'chroma-early-start'); ?></span>
                        <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-6">
                            <?php _e('Why Early Intervention Matters', 'chroma-early-start'); ?>
                        </h2>
                        <div class="prose prose-lg text-stone-600 space-y-6">
                            <p>
                                <?php _e('The first few years of a child\'s life are a period of rapid brain development known as <strong>neuroplasticity</strong>. During this time, the brain is incredibly adaptable, making it the ideal window for learning new skills and overcoming developmental hurdles.', 'chroma-early-start'); ?>
                            </p>
                            <p>
                                <?php _e('Research consistently shows that children who receive intensive, high-quality intervention before age 5 have significantly better long-term outcomes in communication, social skills, and independence.', 'chroma-early-start'); ?>
                            </p>
                            <p>
                                <?php _e('At Chroma Early Start, we leverage this critical time to build a strong foundation. We don\'t just focus on "catching up"; we focus on giving your child the tools to thrive for a lifetime.', 'chroma-early-start'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="relative mt-12 lg:mt-0 fade-in-up">
                        <div class="bg-rose-50 rounded-[2.5rem] p-10 border border-rose-100">
                            <h3 class="text-xl font-bold text-stone-900 mb-6">
                                <?php _e('The Benefits of Starting Early', 'chroma-early-start'); ?>
                            </h3>
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    <div
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center mr-4 text-rose-500 shadow-sm shrink-0">
                                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span
                                            class="font-bold text-stone-800 block"><?php _e('Improved Communication', 'chroma-early-start'); ?></span>
                                        <span
                                            class="text-stone-600 text-sm"><?php _e('Reducing frustration by giving children a way to express their wants and needs.', 'chroma-early-start'); ?></span>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <div
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center mr-4 text-orange-500 shadow-sm shrink-0">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span
                                            class="font-bold text-stone-800 block"><?php _e('Social Connection', 'chroma-early-start'); ?></span>
                                        <span
                                            class="text-stone-600 text-sm"><?php _e('Building the skills to play with peers, share experiences, and make friends.', 'chroma-early-start'); ?></span>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <div
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center mr-4 text-amber-500 shadow-sm shrink-0">
                                        <i data-lucide="school" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span
                                            class="font-bold text-stone-800 block"><?php _e('School Readiness', 'chroma-early-start'); ?></span>
                                        <span
                                            class="text-stone-600 text-sm"><?php _e('Developing the "learning to learn" behaviors needed for a classroom environment.', 'chroma-early-start'); ?></span>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <div
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center mr-4 text-green-500 shadow-sm shrink-0">
                                        <i data-lucide="smile" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span
                                            class="font-bold text-stone-800 block"><?php _e('Happier Homes', 'chroma-early-start'); ?></span>
                                        <span
                                            class="text-stone-600 text-sm"><?php _e('Reducing maladaptive behaviors creates a more peaceful, connected family life.', 'chroma-early-start'); ?></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Insurance & Financial -->
        <section class="py-24 bg-stone-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-start">
                    <!-- Left: Insurance Text -->
                    <div class="fade-in-up">
                        <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-6">
                            <?php _e('We Make Insurance Simple', 'chroma-early-start'); ?>
                        </h2>
                        <p class="text-lg text-stone-600 leading-relaxed mb-8">
                            <?php _e('Understanding your benefits shouldn\'t require a degree. Our dedicated admissions team handles the heavy lifting—verifying benefits, obtaining authorizations, and clearly explaining your coverage options before you start. We advocate for your child to ensure they get the coverage they deserve.', 'chroma-early-start'); ?>
                        </p>

                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-stone-100 mb-8">
                            <h4 class="font-bold text-stone-900 mb-4 flex items-center">
                                <i data-lucide="file-text" class="w-5 h-5 text-rose-500 mr-2"></i>
                                <?php _e('Documents Needed for Intake', 'chroma-early-start'); ?>
                            </h4>
                            <p class="text-sm text-stone-500 mb-4">
                                <?php _e('Having these ready can speed up the enrollment process by 1-2 weeks.', 'chroma-early-start'); ?>
                            </p>
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

                        <a href="<?php echo esc_url(home_url('/contact/')); ?>"
                            class="bg-rose-600 text-white px-8 py-4 rounded-full font-bold hover:bg-rose-700 transition-colors inline-flex items-center shadow-lg">
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
                                    <?php _e('Prefer not to use insurance? We offer competitive private pay rates for families who want to skip authorization wait times or do not have a formal ASD diagnosis. This allows for immediate access to care.', 'chroma-early-start'); ?>
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

        <!-- Intake Journey (Fragment Cached) -->
        <?php
        $cache_key = 'chroma_intake_frag_' . get_locale();
        $cached_intake = get_transient($cache_key);

        if ($cached_intake !== false) {
            echo $cached_intake;
        } else {
            ob_start();
            ?>
            <section class="py-24 bg-white">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16 fade-in-up">
                        <h2 class="text-3xl font-bold text-stone-900 mb-4">
                            <?php _e('Your Intake Journey', 'chroma-early-start'); ?>
                        </h2>
                        <p class="text-stone-600">
                            <?php _e('From first call to first day, we make the process seamless.', 'chroma-early-start'); ?>
                        </p>
                    </div>

                    <div class="relative fade-in-up">
                        <!-- Vertical Line -->
                        <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-stone-200 md:left-1/2 md:-ml-px"></div>

                        <!-- Step 1 -->
                        <div class="relative flex items-center mb-16 md:flex-row-reverse group">
                            <div
                                class="absolute left-8 w-8 h-8 rounded-full bg-rose-500 border-4 border-white shadow -ml-4 md:left-1/2 group-hover:scale-110 transition-transform">
                            </div>
                            <div class="ml-20 md:ml-0 md:w-1/2 md:pl-12"></div>
                            <div class="ml-20 md:ml-0 md:w-1/2 md:pr-12 md:text-right">
                                <span
                                    class="text-xs font-bold text-rose-500 uppercase tracking-wide"><?php _e('Step 1', 'chroma-early-start'); ?></span>
                                <h4 class="text-xl font-bold text-stone-900"><?php _e('Consultation', 'chroma-early-start'); ?>
                                </h4>
                                <p class="text-stone-600 mt-2">
                                    <?php _e('A free 15-minute call to discuss your concerns and see if we are a good fit. We\'ll answer all your initial questions about logistics, approach, and availability.', 'chroma-early-start'); ?>
                                </p>
                                <span
                                    class="inline-block mt-2 px-3 py-1 bg-stone-100 rounded-full text-xs font-bold text-stone-500"><?php _e('Timeline: Day 1', 'chroma-early-start'); ?></span>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="relative flex items-center mb-16 group">
                            <div
                                class="absolute left-8 w-8 h-8 rounded-full bg-orange-500 border-4 border-white shadow -ml-4 md:left-1/2 group-hover:scale-110 transition-transform">
                            </div>
                            <div class="ml-20 md:ml-0 md:w-1/2 md:pr-12 text-left"></div>
                            <div class="ml-20 md:ml-0 md:w-1/2 md:pl-12 text-left">
                                <span
                                    class="text-xs font-bold text-orange-500 uppercase tracking-wide"><?php _e('Step 2', 'chroma-early-start'); ?></span>
                                <h4 class="text-xl font-bold text-stone-900">
                                    <?php _e('Verification & Auth', 'chroma-early-start'); ?>
                                </h4>
                                <p class="text-stone-600 mt-2">
                                    <?php _e('We check your insurance benefits and submit the assessment request. We handle the communication with the payer so you don\'t have to wait on hold.', 'chroma-early-start'); ?>
                                </p>
                                <span
                                    class="inline-block mt-2 px-3 py-1 bg-stone-100 rounded-full text-xs font-bold text-stone-500"><?php _e('Timeline: 3-5 Days', 'chroma-early-start'); ?></span>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="relative flex items-center mb-16 md:flex-row-reverse group">
                            <div
                                class="absolute left-8 w-8 h-8 rounded-full bg-amber-500 border-4 border-white shadow -ml-4 md:left-1/2 group-hover:scale-110 transition-transform">
                            </div>
                            <div class="ml-20 md:ml-0 md:w-1/2 md:pl-12"></div>
                            <div class="ml-20 md:ml-0 md:w-1/2 md:pr-12 md:text-right">
                                <span
                                    class="text-xs font-bold text-amber-500 uppercase tracking-wide"><?php _e('Step 3', 'chroma-early-start'); ?></span>
                                <h4 class="text-xl font-bold text-stone-900">
                                    <?php _e('Skills Assessment', 'chroma-early-start'); ?>
                                </h4>
                                <p class="text-stone-600 mt-2">
                                    <?php _e('Our BCBA meets your child for a play-based skills assessment to identify strengths, barriers, and goals. We use tools like the VB-MAPP or ABLLS-R to build a baseline.', 'chroma-early-start'); ?>
                                </p>
                                <span
                                    class="inline-block mt-2 px-3 py-1 bg-stone-100 rounded-full text-xs font-bold text-stone-500"><?php _e('Timeline: 1 Week', 'chroma-early-start'); ?></span>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="relative flex items-center group">
                            <div
                                class="absolute left-8 w-8 h-8 rounded-full bg-green-500 border-4 border-white shadow -ml-4 md:left-1/2 group-hover:scale-110 transition-transform">
                            </div>
                            <div class="ml-20 md:ml-0 md:w-1/2 md:pr-12 text-left"></div>
                            <div class="ml-20 md:ml-0 md:w-1/2 md:pl-12 text-left">
                                <span
                                    class="text-xs font-bold text-green-500 uppercase tracking-wide"><?php _e('Step 4', 'chroma-early-start'); ?></span>
                                <h4 class="text-xl font-bold text-stone-900">
                                    <?php _e('Start Therapy!', 'chroma-early-start'); ?>
                                </h4>
                                <p class="text-stone-600 mt-2">
                                    <?php _e('We build a schedule that works for your family, pair you with a clinical team (BCBA & RBTs), and begin the journey of growth and connection.', 'chroma-early-start'); ?>
                                </p>
                                <span
                                    class="inline-block mt-2 px-3 py-1 bg-stone-100 rounded-full text-xs font-bold text-stone-500"><?php _e('Timeline: Immediate openings', 'chroma-early-start'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <?php
            $intake_html = ob_get_clean();
            set_transient($cache_key, $intake_html, DAY_IN_SECONDS);
            echo $intake_html;
        }
        ?>

        <!-- What to Expect: First 30 Days -->
        <section class="py-24 bg-stone-900 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-rose-500 opacity-20 rounded-full blur-3xl"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16 fade-in-up">
                    <span
                        class="text-rose-400 font-bold tracking-widest text-sm uppercase mb-4 block"><?php _e('The First Month', 'chroma-early-start'); ?></span>
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">
                        <?php _e('What to Expect: The First 30 Days', 'chroma-early-start'); ?>
                    </h2>
                    <p class="text-stone-400 max-w-2xl mx-auto">
                        <?php _e('Starting therapy is a big transition. Here is exactly what happens during your first month with us.', 'chroma-early-start'); ?>
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 mb-16 fade-in-up">
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/20">
                        <div
                            class="w-12 h-12 bg-rose-500 rounded-full flex items-center justify-center font-bold text-xl mb-6">
                            W1</div>
                        <h3 class="text-xl font-bold mb-3"><?php _e('Pairing & Fun', 'chroma-early-start'); ?></h3>
                        <p class="text-stone-300 text-sm leading-relaxed">
                            <?php _e('Zero demands. Our only goal is for your child to fall in love with their therapist and the center. We play, we explore, and we build trust. This foundation is critical for future learning.', 'chroma-early-start'); ?>
                        </p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/20">
                        <div
                            class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center font-bold text-xl mb-6">
                            W2</div>
                        <h3 class="text-xl font-bold mb-3"><?php _e('Routine Building', 'chroma-early-start'); ?></h3>
                        <p class="text-stone-300 text-sm leading-relaxed">
                            <?php _e('We gently introduce the daily schedule—arrival, circle time, snack. We start identifying what motivates your child to learn and establishing patterns of reinforcement.', 'chroma-early-start'); ?>
                        </p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/20">
                        <div
                            class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center font-bold text-xl mb-6">
                            W4</div>
                        <h3 class="text-xl font-bold mb-3"><?php _e('Initial Goals', 'chroma-early-start'); ?></h3>
                        <p class="text-stone-300 text-sm leading-relaxed">
                            <?php _e('We introduce the first few learning targets. You\'ll receive your first data update and have a check-in with your BCBA to review early progress and adjust the plan if needed.', 'chroma-early-start'); ?>
                        </p>
                    </div>
                </div>

                <!-- What to Bring Checklist -->
                <div class="bg-white/5 backdrop-blur-sm rounded-[2rem] p-8 md:p-12 border border-white/10 fade-in-up">
                    <h3 class="text-2xl font-bold mb-8 text-center text-white"><i data-lucide="backpack"
                            class="w-6 h-6 inline-block mr-2 text-rose-400"></i>
                        <?php _e('What to Bring on Day One', 'chroma-early-start'); ?></h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="flex items-center text-stone-300">
                            <i data-lucide="check-square" class="w-5 h-5 text-green-400 mr-3"></i>
                            <?php _e('Change of Clothes', 'chroma-early-start'); ?>
                        </div>
                        <div class="flex items-center text-stone-300">
                            <i data-lucide="check-square" class="w-5 h-5 text-green-400 mr-3"></i>
                            <?php _e('Diapers/Wipes (if applicable)', 'chroma-early-start'); ?>
                        </div>
                        <div class="flex items-center text-stone-300">
                            <i data-lucide="check-square" class="w-5 h-5 text-green-400 mr-3"></i>
                            <?php _e('Nut-Free Lunch/Snacks', 'chroma-early-start'); ?>
                        </div>
                        <div class="flex items-center text-stone-300">
                            <i data-lucide="check-square" class="w-5 h-5 text-green-400 mr-3"></i>
                            <?php _e('Water Bottle', 'chroma-early-start'); ?>
                        </div>
                        <div class="flex items-center text-stone-300">
                            <i data-lucide="check-square" class="w-5 h-5 text-green-400 mr-3"></i>
                            <?php _e('Comfort Item (Lovey/Toy)', 'chroma-early-start'); ?>
                        </div>
                        <div class="flex items-center text-stone-300">
                            <i data-lucide="check-square" class="w-5 h-5 text-green-400 mr-3"></i>
                            <?php _e('Communication Device (if applicable)', 'chroma-early-start'); ?>
                        </div>
                        <div class="flex items-center text-stone-300">
                            <i data-lucide="check-square" class="w-5 h-5 text-green-400 mr-3"></i>
                            <?php _e('Indoor Shoes/Socks', 'chroma-early-start'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits for the Whole Family (New Section) -->
        <section class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 fade-in-up">
                    <h2 class="text-3xl font-bold text-stone-900 mb-4">
                        <?php _e('Support Beyond the Child', 'chroma-early-start'); ?>
                    </h2>
                    <p class="text-stone-600 max-w-2xl mx-auto">
                        <?php _e('When a child thrives, the whole family thrives. Our goal is to reduce stress and increase connection in your home.', 'chroma-early-start'); ?>
                    </p>
                </div>
                <div class="grid md:grid-cols-3 gap-8 fade-in-up">
                    <div class="bg-stone-50 p-8 rounded-3xl border border-stone-100">
                        <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center mb-4 text-rose-500">
                            <i data-lucide="heart-handshake" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-xl font-bold text-stone-900 mb-2">
                            <?php _e('Parent Empowerment', 'chroma-early-start'); ?>
                        </h4>
                        <p class="text-stone-600 text-sm leading-relaxed">
                            <?php _e('We don\'t keep secrets. We teach you the exact strategies we use, so you feel confident handling behaviors and teaching new skills at home.', 'chroma-early-start'); ?>
                        </p>
                    </div>
                    <div class="bg-stone-50 p-8 rounded-3xl border border-stone-100">
                        <div
                            class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4 text-orange-500">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-xl font-bold text-stone-900 mb-2">
                            <?php _e('Sibling Harmony', 'chroma-early-start'); ?>
                        </h4>
                        <p class="text-stone-600 text-sm leading-relaxed">
                            <?php _e('Improved communication and regulation skills often lead to better play and fewer conflicts between siblings, creating a more peaceful home environment.', 'chroma-early-start'); ?>
                        </p>
                    </div>
                    <div class="bg-stone-50 p-8 rounded-3xl border border-stone-100">
                        <div
                            class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mb-4 text-amber-500">
                            <i data-lucide="coffee" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-xl font-bold text-stone-900 mb-2">
                            <?php _e('Community Access', 'chroma-early-start'); ?>
                        </h4>
                        <p class="text-stone-600 text-sm leading-relaxed">
                            <?php _e('We work on skills that make outings easier—like waiting in line, tolerating loud noises, or safety in parking lots—so you can enjoy family trips again.', 'chroma-early-start'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Parent Training & Workshops -->
        <section class="py-24 bg-rose-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="fade-in-up">
                        <span
                            class="text-rose-600 font-bold tracking-widest text-sm uppercase mb-3 block"><?php _e('Education', 'chroma-early-start'); ?></span>
                        <h2 class="text-3xl font-bold text-stone-900 mb-6">
                            <?php _e('Parent Training Workshops', 'chroma-early-start'); ?>
                        </h2>
                        <p class="text-lg text-stone-600 leading-relaxed mb-6">
                            <?php _e('Parent training isn\'t just a requirement; it\'s a resource. We offer monthly group workshops and 1:1 coaching sessions to tackle the specific challenges your family faces.', 'chroma-early-start'); ?>
                        </p>
                        <h4 class="font-bold text-stone-900 mb-4">
                            <?php _e('Popular Workshop Topics:', 'chroma-early-start'); ?>
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                <span class="w-2 h-2 bg-rose-500 rounded-full mr-3"></span>
                                <span
                                    class="text-stone-700"><?php _e('"Sleep Hygiene: Strategies for Bedtime"', 'chroma-early-start'); ?></span>
                            </div>
                            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                <span class="w-2 h-2 bg-orange-500 rounded-full mr-3"></span>
                                <span
                                    class="text-stone-700"><?php _e('"Picky Eating & Feeding"', 'chroma-early-start'); ?></span>
                            </div>
                            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mr-3"></span>
                                <span
                                    class="text-stone-700"><?php _e('"Sibling Interaction & Play"', 'chroma-early-start'); ?></span>
                            </div>
                            <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                <span
                                    class="text-stone-700"><?php _e('"Toilet Training with Confidence"', 'chroma-early-start'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="relative fade-in-up">
                        <div class="absolute inset-0 bg-white rounded-[3rem] transform rotate-3"></div>
                        <div
                            class="relative bg-rose-200 rounded-[3rem] h-[400px] flex items-center justify-center overflow-hidden shadow-lg border border-white">
                            <!-- Visual placeholder for parent meeting -->
                            <div class="text-center p-8">
                                <i data-lucide="users" class="w-24 h-24 text-rose-500 mx-auto mb-6"></i>
                                <h3 class="text-2xl font-bold text-rose-900">
                                    <?php _e('You are the expert on your child.', 'chroma-early-start'); ?>
                                </h3>
                                <p class="text-rose-800 mt-2">
                                    <?php _e('We are just here to give you more tools.', 'chroma-early-start'); ?>
                                </p>
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
                    <?php _e('Family Logistics FAQ', 'chroma-early-start'); ?>
                </h2>

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
                    <?php _e('Ready to take the first step?', 'chroma-early-start'); ?>
                </h2>
                <p class="text-xl text-stone-600 mb-10">
                    <?php _e('We know this process is new for many families. We are here to answer every question, no matter how small.', 'chroma-early-start'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>"
                    class="bg-stone-900 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-rose-600 transition-colors shadow-lg inline-block transform hover:-translate-y-0.5">
                    <?php _e('Contact Admissions', 'chroma-early-start'); ?>
                </a>
            </div>
        </section>

    </div>

    <?php
endwhile;

// Inject Lucide Script
echo '<script>lucide.createIcons();</script>';

get_footer();
?>