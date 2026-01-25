<?php
/**
 * Template Name: Parents Page
 * Displays the Bridge Program (School Readiness) and Parent Resources.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

while (have_posts()):
	the_post();
	$page_id = get_the_ID();
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
							<span
								class="text-blue-600 font-bold tracking-widest text-sm uppercase"><?php _e('Signature Program', 'chroma-early-start'); ?></span>
						</div>
						<h1 class="text-5xl md:text-6xl font-bold text-stone-900 mb-6 leading-tight">
							<?php _e('The Chroma', 'chroma-early-start'); ?><br><span
								class="text-blue-600"><?php _e('Bridge Program.', 'chroma-early-start'); ?></span>
						</h1>
						<p class="text-xl text-stone-600 mb-8 leading-relaxed">
							<?php _e('Preparing early learners for the transition from 1:1 therapy to a social classroom environment. We simulate the preschool experience with clinical precision.', 'chroma-early-start'); ?>
						</p>
						<div class="flex flex-col sm:flex-row gap-4">
							<a href="#curriculum"
								class="bg-blue-600 text-white px-8 py-4 rounded-full font-bold hover:bg-blue-500 transition-all shadow-lg flex items-center justify-center">
								<?php _e('View Curriculum', 'chroma-early-start'); ?>
								<i data-lucide="arrow-down" class="ml-2 w-5 h-5"></i>
							</a>
							<a href="<?php echo esc_url(home_url('/contact/')); ?>"
								class="bg-white text-stone-900 border border-stone-200 px-8 py-4 rounded-full font-bold hover:border-blue-200 hover:text-blue-600 transition-all shadow-sm flex items-center justify-center">
								<?php _e('Request Observation', 'chroma-early-start'); ?>
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
									<i data-lucide="school" class="w-16 h-16 text-blue-500"></i>
								</div>
								<h3 class="text-3xl font-bold text-stone-900 mb-2">
									<?php _e('School Ready', 'chroma-early-start'); ?></h3>
								<p class="text-stone-500">
									<?php _e('The Ultimate Transition Pathway', 'chroma-early-start'); ?></p>
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

		<!-- Bridging the Gap -->
		<section class="py-24 bg-stone-50 overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-4xl font-bold text-stone-900 mb-4">
						<?php _e('Bridging the Gap', 'chroma-early-start'); ?></h2>
					<p class="text-stone-600 max-w-2xl mx-auto text-lg italic">
						<?php _e('Moving from a quiet clinic room to a noisy kindergarten classroom is a huge leap. Our Bridge Program is the vital stepping stone.', 'chroma-early-start'); ?>
					</p>
				</div>

				<div class="grid md:grid-cols-3 gap-8">
					<!-- Step 1 -->
					<div
						class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-stone-100 relative group hover:shadow-lg transition-all fade-in-up">
						<div
							class="absolute -top-6 left-8 bg-stone-900 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase">
							<?php _e('Step 1', 'chroma-early-start'); ?></div>
						<h3 class="text-xl font-bold text-stone-900 mb-4 mt-4">
							<?php _e('Clinical 1:1', 'chroma-early-start'); ?></h3>
						<p class="text-stone-600 text-sm mb-6">
							<?php _e('Intensive, individualized instruction to build foundational communication and behavioral skills.', 'chroma-early-start'); ?>
						</p>
						<div class="w-full bg-stone-100 h-2 rounded-full overflow-hidden">
							<div class="w-full h-full bg-stone-300"></div>
						</div>
					</div>
					<!-- Step 2 (Bridge) -->
					<div
						class="bg-blue-600 p-10 rounded-[2.5rem] shadow-xl border border-blue-500 relative transform scale-105 z-10 text-white fade-in-up">
						<div
							class="absolute -top-6 left-8 bg-white text-blue-600 px-4 py-2 rounded-lg font-bold text-xs uppercase shadow-sm">
							<?php _e('We Are Here', 'chroma-early-start'); ?></div>
						<h3 class="text-2xl font-bold mb-4 mt-4"><?php _e('The Bridge', 'chroma-early-start'); ?></h3>
						<p class="text-blue-100 text-sm mb-6">
							<?php _e('Small group dynamics (1:3 ratio). Mock classroom routines. Peer-to-peer social focus.', 'chroma-early-start'); ?>
						</p>
						<div class="w-full bg-blue-800 h-2 rounded-full overflow-hidden">
							<div class="w-2/3 h-full bg-white animate-pulse"></div>
						</div>
					</div>
					<!-- Step 3 -->
					<div
						class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-stone-100 relative group hover:shadow-lg transition-all fade-in-up">
						<div
							class="absolute -top-6 left-8 bg-stone-900 text-white px-4 py-2 rounded-lg font-bold text-xs uppercase">
							<?php _e('Step 3', 'chroma-early-start'); ?></div>
						<h3 class="text-xl font-bold text-stone-900 mb-4 mt-4">
							<?php _e('The Classroom', 'chroma-early-start'); ?></h3>
						<p class="text-stone-600 text-sm mb-6">
							<?php _e('General education or inclusion setting with minimal clinical support needed.', 'chroma-early-start'); ?>
						</p>
						<div class="w-full bg-stone-100 h-2 rounded-full overflow-hidden">
							<div class="w-0 h-full bg-stone-300"></div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Focus Areas -->
		<section id="curriculum" class="py-24 bg-white overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid lg:grid-cols-2 gap-16 items-center">
					<div class="fade-in-up">
						<span
							class="text-blue-600 font-bold tracking-widest text-sm uppercase mb-4 block"><?php _e('Curriculum', 'chroma-early-start'); ?></span>
						<h2 class="text-4xl font-bold text-stone-900 mb-10">
							<?php _e('What We Practice', 'chroma-early-start'); ?></h2>

						<div class="space-y-8">
							<?php
							$focus_areas = array(
								array('icon' => 'users', 'title' => 'Group Instruction', 'desc' => 'Sitting for circle time, following choral responses, and raising hands.'),
								array('icon' => 'clock', 'title' => 'Transitions', 'desc' => 'Moving between activities (e.g., recess to work) without distress.'),
								array('icon' => 'smile', 'title' => 'Social Navigation', 'desc' => 'Sharing materials, initiating play, and respecting personal boundaries.'),
								array('icon' => 'check-circle', 'title' => 'Functional Independence', 'desc' => 'Management of personal belongings, toileting, and hand-washing.'),
							);
							foreach ($focus_areas as $area): ?>
								<div class="flex items-start">
									<div
										class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mr-6 text-blue-600 shrink-0 border border-blue-100 shadow-sm">
										<i data-lucide="<?php echo $area['icon']; ?>" class="w-6 h-6"></i>
									</div>
									<div>
										<h4 class="text-xl font-bold text-stone-900 mb-2">
											<?php echo esc_html($area['title']); ?></h4>
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
								<?php _e('A Day in Bridge', 'chroma-early-start'); ?></h3>
							<div class="space-y-6">
								<?php
								$schedule = array(
									'9:00 AM' => 'Arrival & Unpack (Independence)',
									'9:30 AM' => 'Morning Circle (Group Skills)',
									'10:00 AM' => 'Literacy & Logic Centers',
									'11:00 AM' => 'Structured Recess (Peer Play)',
									'12:00 PM' => 'Lunch (Social & Self-Help)',
								);
								foreach ($schedule as $time => $activity): ?>
									<div class="flex items-center text-sm md:text-base">
										<span
											class="w-24 font-bold text-blue-600 shrink-0"><?php echo esc_html($time); ?></span>
										<span
											class="text-stone-600 border-l border-stone-100 pl-6"><?php echo esc_html($activity); ?></span>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Parent Essentials (Resources) -->
		<section class="py-24 bg-stone-900 text-white overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-4xl font-bold mb-4"><?php _e('Parent Essentials', 'chroma-early-start'); ?></h2>
					<p class="text-stone-400 max-w-2xl mx-auto text-lg">
						<?php _e('Everything you need to stay connected and manage your child\'s enrollment journey.', 'chroma-early-start'); ?>
					</p>
				</div>

				<div class="grid grid-cols-2 md:grid-cols-4 gap-6">
					<?php
					$resources = array(
						array('icon' => 'cloud', 'title' => 'Clinical Portal', 'desc' => 'Daily data & photos.', 'color' => 'blue'),
						array('icon' => 'credit-card', 'title' => 'Tuition', 'desc' => 'Statements & payments.', 'color' => 'rose'),
						array('icon' => 'book-open', 'title' => 'Handbook', 'desc' => 'Policies & procedures.', 'color' => 'amber'),
						array('icon' => 'file-signature', 'title' => 'Enrollment', 'desc' => 'Required state forms.', 'color' => 'blue'),
					);
					foreach ($resources as $r): ?>
						<a href="#"
							class="bg-white/5 border border-white/10 p-8 rounded-[2rem] text-center hover:bg-white/10 transition-all group fade-in-up">
							<div
								class="w-16 h-16 bg-<?php echo $r['color']; ?>-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
								<i data-lucide="<?php echo $r['icon']; ?>"
									class="w-8 h-8 text-<?php echo $r['color']; ?>-400"></i>
							</div>
							<h3 class="font-bold text-lg mb-2"><?php echo esc_html($r['title']); ?></h3>
							<p class="text-stone-500 text-xs leading-relaxed"><?php echo esc_html($r['desc']); ?></p>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- Final CTA -->
		<section class="py-24 bg-blue-600 text-white overflow-hidden text-center">
			<div class="max-w-4xl mx-auto px-4 fade-in-up">
				<h2 class="text-4xl md:text-5xl font-bold mb-8">
					<?php _e('See the classroom in action.', 'chroma-early-start'); ?></h2>
				<p class="text-blue-100 text-xl mb-12 leading-relaxed max-w-2xl mx-auto">
					<?php _e('Schedule a tour of our Bridge environment and meet the clinical team who will guide your child to success.', 'chroma-early-start'); ?>
				</p>
				<a href="<?php echo esc_url(home_url('/contact/')); ?>"
					class="bg-white text-blue-600 px-12 py-5 rounded-full font-bold text-lg hover:bg-blue-50 transition-all shadow-2xl inline-flex items-center gap-3">
					<?php _e('Schedule a Tour', 'chroma-early-start'); ?>
					<i data-lucide="calendar" class="w-6 h-6"></i>
				</a>
			</div>
		</section>

	</main>

<?php
endwhile;
get_footer();

