<?php
/**
 * Template Name: Curriculum Page
 * Displays the curriculum page using seeded metabox content.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

while (have_posts()):
	the_post();
	$page_id = get_the_ID();

	$hero_badge = get_post_meta($page_id, 'curriculum_hero_badge', true) ?: __('The Chroma Care Model', 'earlystart-early-learning');
	$hero_title = get_post_meta($page_id, 'curriculum_hero_title', true) ?: __('Scientific rigor. <br><span class="italic text-emerald-500">Joyful delivery.</span>', 'earlystart-early-learning');
	$hero_description = get_post_meta($page_id, 'curriculum_hero_description', true) ?: __('Our approach blends structured intervention, family partnership, and play-based learning so children can build meaningful skills in a supportive environment.', 'earlystart-early-learning');

	$framework_title = get_post_meta($page_id, 'curriculum_framework_title', true) ?: __('The Chroma Framework', 'earlystart-early-learning');
	$framework_description = get_post_meta($page_id, 'curriculum_framework_description', true) ?: __('Every activity is designed to support more than one developmental domain at a time.', 'earlystart-early-learning');

	$timeline_badge = get_post_meta($page_id, 'curriculum_timeline_badge', true) ?: __('Learning Journey', 'earlystart-early-learning');
	$timeline_title = get_post_meta($page_id, 'curriculum_timeline_title', true) ?: __('How development unfolds.', 'earlystart-early-learning');
	$timeline_description = get_post_meta($page_id, 'curriculum_timeline_description', true) ?: __('We adapt expectations, routines, and supports to each stage of early development.', 'earlystart-early-learning');
	$timeline_image = get_post_meta($page_id, 'curriculum_timeline_image', true);

	$env_badge = get_post_meta($page_id, 'curriculum_env_badge', true) ?: __('Environment', 'earlystart-early-learning');
	$env_title = get_post_meta($page_id, 'curriculum_env_title', true) ?: __('The environment supports regulation.', 'earlystart-early-learning');
	$env_description = get_post_meta($page_id, 'curriculum_env_description', true) ?: __('The spaces children move through each day are designed to reduce friction and increase engagement.', 'earlystart-early-learning');

	$milestones_title = get_post_meta($page_id, 'curriculum_milestones_title', true) ?: __('Measuring Milestones', 'earlystart-early-learning');
	$milestones_subtitle = get_post_meta($page_id, 'curriculum_milestones_subtitle', true) ?: __('We track progress consistently so treatment and teaching decisions stay grounded in real progress.', 'earlystart-early-learning');

	$cta_title = get_post_meta($page_id, 'curriculum_cta_title', true) ?: __('See it in action.', 'earlystart-early-learning');
	$cta_description = get_post_meta($page_id, 'curriculum_cta_description', true) ?: __('Schedule a visit and see how our routines, spaces, and team support early learners.', 'earlystart-early-learning');

	$pillars = array(
		array('key' => 'physical', 'color' => 'rose'),
		array('key' => 'emotional', 'color' => 'amber'),
		array('key' => 'social', 'color' => 'emerald'),
		array('key' => 'academic', 'color' => 'blue'),
		array('key' => 'creative', 'color' => 'indigo'),
	);

	$stages = array('foundation', 'discovery', 'readiness');
	$zones = array('construction', 'atelier', 'literacy');
	$milestone_groups = array('tracking', 'screenings', 'assessments');
	?>

	<main class="pt-20">
		<section class="relative bg-white pt-24 pb-24 lg:pt-32 overflow-hidden">
			<div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[520px] h-[520px] bg-emerald-50 rounded-full blur-3xl opacity-70"></div>
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
				<div class="max-w-4xl">
					<span class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-bold tracking-widest uppercase mb-6 fade-in-up">
						<?php echo esc_html($hero_badge); ?>
					</span>
					<h1 class="text-4xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
						<?php echo wp_kses_post($hero_title); ?>
					</h1>
					<p class="text-lg md:text-xl text-stone-700 leading-relaxed max-w-3xl fade-in-up">
						<?php echo esc_html($hero_description); ?>
					</p>
				</div>
			</div>
		</section>

		<section class="py-24 bg-stone-50 border-y border-stone-100">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
					<h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-4"><?php echo esc_html($framework_title); ?></h2>
					<p class="text-lg text-stone-700"><?php echo esc_html($framework_description); ?></p>
				</div>

				<div class="grid md:grid-cols-2 xl:grid-cols-5 gap-6">
					<?php foreach ($pillars as $pillar):
						$icon = get_post_meta($page_id, 'curriculum_pillar_' . $pillar['key'] . '_icon', true);
						$title = get_post_meta($page_id, 'curriculum_pillar_' . $pillar['key'] . '_title', true);
						$desc = get_post_meta($page_id, 'curriculum_pillar_' . $pillar['key'] . '_desc', true);
						if (!$title) {
							continue;
						}
						?>
						<div class="bg-white rounded-[2rem] p-8 border border-stone-100 shadow-sm hover:shadow-lg transition-all fade-in-up">
							<div class="w-14 h-14 bg-<?php echo esc_attr($pillar['color']); ?>-50 text-<?php echo esc_attr($pillar['color']); ?>-600 rounded-2xl flex items-center justify-center mb-6">
								<?php if (!empty($icon) && 0 === strpos($icon, 'fa-')): ?>
									<i class="<?php echo esc_attr($icon); ?> text-2xl"></i>
								<?php else: ?>
									<i data-lucide="<?php echo esc_attr(earlystart_safe_lucide_icon($icon, 'sparkles')); ?>" class="w-7 h-7"></i>
								<?php endif; ?>
							</div>
							<h3 class="text-xl font-bold text-stone-900 mb-3"><?php echo esc_html($title); ?></h3>
							<p class="text-sm text-stone-700 leading-relaxed"><?php echo esc_html($desc); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="py-24 bg-white overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid lg:grid-cols-2 gap-16 items-center">
					<div class="fade-in-up">
						<span class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-[10px] font-bold tracking-widest uppercase mb-6">
							<?php echo esc_html($timeline_badge); ?>
						</span>
						<h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-6"><?php echo esc_html($timeline_title); ?></h2>
						<p class="text-base md:text-lg text-stone-700 leading-relaxed mb-10"><?php echo esc_html($timeline_description); ?></p>
						<div class="space-y-5">
							<?php foreach ($stages as $index => $stage):
								$title = get_post_meta($page_id, 'curriculum_stage_' . $stage . '_title', true);
								$desc = get_post_meta($page_id, 'curriculum_stage_' . $stage . '_desc', true);
								if (!$title) {
									continue;
								}
								?>
								<div class="bg-stone-50 rounded-3xl border border-stone-100 p-6">
									<div class="flex items-start gap-5">
										<div class="w-10 h-10 rounded-2xl bg-stone-900 text-white flex items-center justify-center font-bold shrink-0">
											<?php echo esc_html((string) ($index + 1)); ?>
										</div>
										<div>
											<h3 class="text-xl font-bold text-stone-900 mb-2"><?php echo esc_html($title); ?></h3>
											<p class="text-sm text-stone-700 leading-relaxed"><?php echo esc_html($desc); ?></p>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="fade-in-up">
						<div class="relative">
							<div class="absolute -inset-4 bg-blue-50 rounded-[3rem] rotate-2"></div>
							<div class="relative rounded-[3rem] overflow-hidden border border-stone-100 shadow-2xl bg-stone-100 min-h-[320px] md:min-h-[420px]">
								<?php if ($timeline_image): ?>
									<img src="<?php echo esc_url($timeline_image); ?>"
										alt="<?php echo esc_attr($timeline_title); ?>"
										class="w-full h-full min-h-[320px] md:min-h-[420px] object-cover" loading="lazy" decoding="async">
								<?php else: ?>
									<div class="absolute inset-0 flex items-center justify-center text-stone-300">
										<i data-lucide="route" class="w-16 h-16"></i>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="py-24 bg-stone-900 text-white overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
					<span class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-full text-[10px] font-bold tracking-widest uppercase mb-6">
						<?php echo esc_html($env_badge); ?>
					</span>
					<h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($env_title); ?></h2>
					<p class="text-stone-300 text-lg"><?php echo esc_html($env_description); ?></p>
				</div>

				<div class="grid md:grid-cols-3 gap-8">
					<?php foreach ($zones as $zone):
						$emoji = get_post_meta($page_id, 'curriculum_zone_' . $zone . '_emoji', true);
						$title = get_post_meta($page_id, 'curriculum_zone_' . $zone . '_title', true);
						$desc = get_post_meta($page_id, 'curriculum_zone_' . $zone . '_desc', true);
						if (!$title) {
							continue;
						}
						?>
						<div class="bg-white/5 border border-white/10 rounded-[2rem] p-8 backdrop-blur-sm fade-in-up">
							<span class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 text-[10px] font-bold tracking-widest uppercase mb-5">
								<?php echo esc_html($emoji ?: __('Zone', 'earlystart-early-learning')); ?>
							</span>
							<h3 class="text-2xl font-bold mb-3"><?php echo esc_html($title); ?></h3>
							<p class="text-stone-300 text-sm leading-relaxed"><?php echo esc_html($desc); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="py-24 bg-white overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
					<h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-4"><?php echo esc_html($milestones_title); ?></h2>
					<p class="text-lg text-stone-700"><?php echo esc_html($milestones_subtitle); ?></p>
				</div>

				<div class="grid lg:grid-cols-3 gap-8">
					<?php foreach ($milestone_groups as $group):
						$icon = get_post_meta($page_id, 'curriculum_milestone_' . $group . '_icon', true);
						$title = get_post_meta($page_id, 'curriculum_milestone_' . $group . '_title', true);
						$desc = get_post_meta($page_id, 'curriculum_milestone_' . $group . '_desc', true);
						if (!$title) {
							continue;
						}
						?>
						<div class="bg-stone-50 rounded-[2.5rem] p-8 border border-stone-100 shadow-sm fade-in-up">
							<div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-6">
								<?php if (!empty($icon) && 0 === strpos($icon, 'fa-')): ?>
									<i class="<?php echo esc_attr($icon); ?> text-2xl"></i>
								<?php else: ?>
									<i data-lucide="<?php echo esc_attr(earlystart_safe_lucide_icon($icon, 'line-chart')); ?>" class="w-7 h-7"></i>
								<?php endif; ?>
							</div>
							<h3 class="text-2xl font-bold text-stone-900 mb-3"><?php echo esc_html($title); ?></h3>
							<p class="text-sm text-stone-700 leading-relaxed mb-6"><?php echo esc_html($desc); ?></p>
							<div class="space-y-3">
								<?php for ($i = 1; $i <= 2; $i++):
									$bullet = get_post_meta($page_id, 'curriculum_milestone_' . $group . '_bullet' . $i, true);
									if (!$bullet) {
										continue;
									}
									?>
									<div class="flex items-center gap-3">
										<i data-lucide="check-circle-2" class="w-5 h-5 text-rose-500 shrink-0"></i>
										<span class="text-sm font-medium text-stone-800"><?php echo esc_html($bullet); ?></span>
									</div>
								<?php endfor; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="pb-24 bg-white overflow-hidden">
			<div class="max-w-5xl mx-auto px-4">
				<div class="rounded-[3rem] bg-gradient-to-r from-emerald-600 to-blue-600 p-8 md:p-16 text-white text-center shadow-xl fade-in-up">
					<h2 class="text-3xl md:text-4xl font-bold mb-6"><?php echo esc_html($cta_title); ?></h2>
					<p class="text-lg md:text-xl text-white/90 max-w-3xl mx-auto mb-8"><?php echo esc_html($cta_description); ?></p>
					<a href="<?php echo esc_url(earlystart_get_page_link('schedule-tour')); ?>"
						class="inline-flex items-center justify-center px-8 py-4 rounded-full bg-white text-emerald-700 font-bold hover:bg-stone-100 transition-colors">
						<?php _e('Schedule a Tour', 'earlystart-early-learning'); ?>
					</a>
				</div>
			</div>
		</section>
	</main>

	<?php
endwhile;
get_footer();
