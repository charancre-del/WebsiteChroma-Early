<?php
/**
 * Single Program Template
 * Displays clinical and educational details for specific programs (ABA, Speech, etc.)
 *
 * @package EarlyStart_Early_Start
 */

get_header();

while (have_posts()):
	the_post();
	$program_id = get_the_ID();

	// Get program meta
	$age_range = get_post_meta($program_id, 'program_age_range', true);
	$color_scheme = get_post_meta($program_id, 'program_color_scheme', true) ?: 'rose';
	$lesson_plan_url = get_post_meta($program_id, 'program_lesson_plan_file', true);

	// Use a mapping for Tailwind-friendly colors based on the scheme
	$colors = array(
		'rose' => 'rose',
		'blue' => 'blue',
		'orange' => 'orange',
		'amber' => 'amber',
		'green' => 'emerald'
	);
	$theme_color = $colors[$color_scheme] ?? 'rose';

	// Map Tailwind color to HEX for Chart.js
	$hex_colors = array(
		'rose' => '#f43f5e',
		'blue' => '#3b82f6',
		'orange' => '#f97316',
		'amber' => '#f59e0b',
		'emerald' => '#10b981'
	);
	$chart_color = $hex_colors[$theme_color] ?? '#f43f5e';

	// Hero section
	$hero_title = get_post_meta($program_id, 'program_hero_title', true) ?: get_the_title();
	$hero_description = get_post_meta($program_id, 'program_hero_description', true) ?: get_the_excerpt();

	// Prismpath section
	$prism_title = get_post_meta($program_id, 'program_prism_title', true) ?: __('Clinical & Developmental Focus', 'earlystart-early-learning');
	$prism_description = get_post_meta($program_id, 'program_prism_description', true);
	if (!$prism_description) {
		$prism_description = __('Our program centers around evidence-based interventions tailored specifically to your child\'s unique developmental profile, emphasizing both structured learning and naturalistic play.', 'earlystart-early-learning');
	}

	$prism_focus_items = get_post_meta($program_id, 'program_prism_focus_items', true);
	if (!$prism_focus_items) {
		$prism_focus_items = "Individualized Treatment Plans\nFamily-Centered Coaching\nPlay-Based Naturalistic Teaching\nData-Driven Progress Tracking";
	}

	// Chart data (Fallback to default spread if empty)
	$prism_physical = get_post_meta($program_id, 'program_prism_physical', true) ?: '60';
	$prism_emotional = get_post_meta($program_id, 'program_prism_emotional', true) ?: '85';
	$prism_social = get_post_meta($program_id, 'program_prism_social', true) ?: '90';
	$prism_academic = get_post_meta($program_id, 'program_prism_academic', true) ?: '40';
	$prism_creative = get_post_meta($program_id, 'program_prism_creative', true) ?: '75';

	// Schedule
	$schedule_title = get_post_meta($program_id, 'program_schedule_title', true) ?: __('A Typical Therapeutic Day', 'earlystart-early-learning');
	$schedule_items = get_post_meta($program_id, 'program_schedule_items', true);
	?>

	<main class="pt-20">
		<!-- Hero Section -->
		<section class="relative bg-white pt-24 pb-20 lg:pt-32 overflow-hidden">
			<div
				class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-<?php echo $theme_color; ?>-50 rounded-full blur-3xl opacity-50">
			</div>
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
				<div class="grid lg:grid-cols-2 gap-16 items-center">
					<div class="fade-in-up">
						<div
							class="inline-flex items-center space-x-2 bg-<?php echo $theme_color; ?>-50 border border-<?php echo $theme_color; ?>-100 px-4 py-2 rounded-full mb-8">
							<span
								class="text-<?php echo $theme_color; ?>-700 text-xs font-bold uppercase tracking-widest text-[10px]">
								<?php echo esc_html($age_range ?: __('Pediatric Program', 'earlystart-early-learning')); ?>
							</span>
						</div>

						<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight">
							<?php echo esc_html($hero_title); ?>
						</h1>

						<p class="text-xl text-stone-700 leading-relaxed mb-10 max-w-xl">
							<?php echo esc_html($hero_description); ?>
						</p>

						<div class="flex flex-wrap gap-4">
							<a href="<?php echo esc_url(home_url('/locations/')); ?>"
								class="bg-stone-900 text-white px-8 py-4 rounded-full font-bold hover:bg-rose-600 transition-all shadow-lg active:scale-95 inline-block">
								<?php _e('Find a Clinic', 'earlystart-early-learning'); ?>
							</a>
							<?php if ($lesson_plan_url): ?>
								<a href="<?php echo esc_url($lesson_plan_url); ?>" target="_blank" rel="noopener noreferrer"
									class="bg-white text-stone-900 border-2 border-stone-100 px-8 py-4 rounded-full font-bold hover:border-rose-600 hover:text-rose-700 transition-all inline-block">
									<?php _e('View Curriculum PDF', 'earlystart-early-learning'); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>

					<div class="relative fade-in-up">
						<div
							class="aspect-[4/3] rounded-[3rem] bg-stone-50 overflow-hidden shadow-2xl border-8 border-white">
							<?php if (has_post_thumbnail()): ?>
								<?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover']); ?>
							<?php else: ?>
								<img src="https://images.unsplash.com/photo-1503454537195-1dcabb73ffb9?auto=format&fit=crop&q=80&fm=webp?w=1200&fit=crop&q=80&fm=webp"
									class="w-full h-full object-cover" alt="Program">
							<?php endif; ?>
						</div>
						<div class="absolute -bottom-8 -right-8 w-48 h-48 bg-amber-50 rounded-full blur-3xl -z-10"></div>
					</div>
				</div>
			</div>
		</section>

		<!-- Clinical Focus & Chart -->
		<section class="py-24 bg-stone-50">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid lg:grid-cols-2 gap-20 items-center">
					<div class="fade-in-up order-2 lg:order-1">
						<div class="bg-white p-12 rounded-[3.5rem] shadow-sm border border-stone-100 relative">
							<!-- Radar Chart Placeholder -->
							<div class="aspect-square relative flex items-center justify-center">
								<canvas id="programFocusChart"></canvas>
							</div>
						</div>
					</div>

					<div class="fade-in-up order-1 lg:order-2">
						<span
							class="inline-block px-4 py-2 bg-rose-50 text-rose-700 font-bold rounded-full text-[10px] font-bold tracking-widest uppercase mb-6">
							<?php _e('The Chroma Care Model Focus', 'earlystart-early-learning'); ?>
						</span>
						<h2 class="text-4xl font-bold text-stone-900 mb-8"><?php echo esc_html($prism_title); ?></h2>
						<p class="text-xl text-stone-700 leading-relaxed mb-10">
							<?php echo esc_html($prism_description); ?>
						</p>

						<?php if ($prism_focus_items):
							$focus_items = explode("\n", $prism_focus_items);
							?>
							<div class="grid sm:grid-cols-2 gap-6">
								<?php foreach ($focus_items as $item):
									if (trim($item)): ?>
										<div class="flex items-start">
											<i data-lucide="check-circle-2" class="w-6 h-6 text-rose-500 mr-4 shrink-0 mt-0.5"></i>
											<span class="text-stone-700 font-medium"><?php echo esc_html(trim($item)); ?></span>
										</div>
									<?php endif; endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<!-- Daily Schedule / Routine -->
		<?php if ($schedule_items): ?>
			<section class="py-24 bg-white relative overflow-hidden">
				<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
					<div class="text-center mb-20 fade-in-up">
						<h2 class="text-4xl font-bold text-stone-900 mb-6"><?php echo esc_html($schedule_title); ?></h2>
						<p class="text-stone-700 text-lg font-medium">
							<?php _e('Structured routines facilitate confidence, while spontaneous play builds skills.', 'earlystart-early-learning'); ?>
						</p>
					</div>

					<div class="space-y-4 max-w-3xl mx-auto fade-in-up">
						<?php
						$schedule_lines = explode("\n", $schedule_items);
						foreach ($schedule_lines as $line):
							$parts = explode("|", $line);
							if (count($parts) >= 2):
								?>
								<div
									class="group flex items-center gap-8 p-6 bg-stone-50 rounded-2xl border border-stone-100 hover:bg-white hover:border-rose-200 hover:shadow-xl transition-all duration-300">
									<span class="text-sm font-bold text-stone-300 uppercase tracking-widest min-w-[100px] shrink-0">
										<?php echo esc_html(trim($parts[0])); ?>
									</span>
									<div class="h-10 w-px bg-stone-200 group-hover:bg-rose-200"></div>
									<div class="flex-grow">
										<h4 class="font-bold text-stone-900"><?php echo esc_html(trim($parts[1])); ?></h4>
										<?php if (isset($parts[2])): ?>
											<p class="text-sm text-stone-700 mt-1 font-medium"><?php echo esc_html(trim($parts[2])); ?></p>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- Enrollment Steps / FAQ -->
		<section class="py-24 bg-stone-50 border-t border-stone-100">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid lg:grid-cols-2 gap-20">
					<div class="fade-in-up">
						<h2 class="text-3xl font-bold text-stone-900 mb-10">
							<?php _e('Common Questions', 'earlystart-early-learning'); ?>
						</h2>
						<div class="space-y-4">
							<?php
							$faq_raw = get_post_meta($program_id, 'program_faq_items', true);

							// Fallback FAQs if not seeded
							if (!$faq_raw) {
								$faq_raw = "How early can we start therapy?|We offer early intervention services starting as early as 18 months, depending on the specific program and your child's needs.\nDo you accept insurance?|Yes, we are in-network with most major insurance providers including Medicaid. Our admissions team will verify your benefits during the intake process.\nHow involved are parents in the therapy process?|Parent training is a core component of the Chroma Care Model. We require regular caregiver participation to ensure skills transfer to the home environment.\nHow long are the therapy sessions?|Session length varies by program and clinical recommendation, typically ranging from 2 to 4 hours for our early intervention models.";
							}

							if ($faq_raw):
								$faqs = explode("\n", $faq_raw);
								foreach ($faqs as $faq):
									$q_a = explode("|", $faq);
									if (count($q_a) >= 2):
										?>
										<details
											class="group bg-white rounded-2xl p-6 border border-stone-100 open:shadow-md transition-all">
											<summary
												class="flex items-center justify-between font-bold text-stone-900 list-none cursor-pointer">
												<span><?php echo esc_html(trim($q_a[0])); ?></span>
												<i data-lucide="chevron-down"
													class="w-5 h-5 text-stone-300 group-open:rotate-180 transition-transform"></i>
											</summary>
											<div class="mt-4 text-stone-700 leading-relaxed text-sm">
												<?php echo esc_html(trim($q_a[1])); ?>
											</div>
										</details>
									<?php endif; endforeach; endif; ?>
						</div>
					</div>

					<div class="fade-in-up">
						<div class="bg-stone-900 rounded-[3rem] p-12 text-white relative overflow-hidden">
							<div
								class="absolute top-0 right-0 w-64 h-64 bg-rose-600 opacity-20 rounded-full blur-3xl -mr-32 -mt-32">
							</div>
							<h2 class="text-3xl font-bold mb-8"><?php _e('Ready to Start?', 'earlystart-early-learning'); ?>
							</h2>
							<div class="space-y-8 mb-10">
								<div class="flex gap-6">
									<div
										class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center font-bold text-rose-500 shrink-0">
										1</div>
									<div>
										<h4 class="font-bold mb-1">
											<?php _e('Find Your Clinic', 'earlystart-early-learning'); ?>
										</h4>
										<p class="text-xs text-stone-300">
											<?php _e('Select a location near you with availability.', 'earlystart-early-learning'); ?>
										</p>
									</div>
								</div>
								<div class="flex gap-6">
									<div
										class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center font-bold text-rose-500 shrink-0">
										2</div>
									<div>
										<h4 class="font-bold mb-1">
											<?php _e('Clinical Tour', 'earlystart-early-learning'); ?>
										</h4>
										<p class="text-xs text-stone-300">
											<?php _e('Tour our facilities and meet our clinical team.', 'earlystart-early-learning'); ?>
										</p>
									</div>
								</div>
								<div class="flex gap-6">
									<div
										class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center font-bold text-rose-500 shrink-0">
										3</div>
									<div>
										<h4 class="font-bold mb-1">
											<?php _e('Assessment & Start', 'earlystart-early-learning'); ?>
										</h4>
										<p class="text-xs text-stone-300">
											<?php _e('We build your child’s individualized clinical roadmap.', 'earlystart-early-learning'); ?>
										</p>
									</div>
								</div>
							</div>
							<a href="<?php echo esc_url(home_url('/locations/')); ?>"
								class="block w-full py-4 bg-rose-600 hover:bg-rose-500 transition-colors text-white text-center rounded-2xl font-bold">
								<?php _e('Book Your Clinical Tour', 'earlystart-early-learning'); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const ctx = document.getElementById('programFocusChart');
			if (ctx) {
				new Chart(ctx, {
					type: 'radar',
					data: {
						labels: ['Physical', 'Emotional', 'Social', 'Academic', 'Creative'],
						datasets: [{
							label: 'Program Balance',
							data: [
								<?php echo absint($prism_physical); ?>,
								<?php echo absint($prism_emotional); ?>,
								<?php echo absint($prism_social); ?>,
								<?php echo absint($prism_academic); ?>,
								<?php echo absint($prism_creative); ?>
							],
							backgroundColor: '<?php echo $chart_color; ?>' + '33', // 20% opacity
							borderColor: '<?php echo $chart_color; ?>',
							borderWidth: 2,
							pointBackgroundColor: '#fff',
							pointBorderColor: '<?php echo $chart_color; ?>',
						}]
					},
					options: {
						scales: {
							r: {
								angleLines: { color: '#f3f4f6' },
								grid: { color: '#f3f4f6' },
								pointLabels: { font: { family: "'Outfit', sans-serif", size: 12, weight: '600' } },
								ticks: { display: false }
							}
						},
						plugins: { legend: { display: false } }
					}
				});
			}
		});
	</script>

	<?php
endwhile;
get_footer();


