<?php
/**
 * Programs Archive Template
 * Displays all programs in a premium service directory.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

$programs_query = new WP_Query(array(
	'post_type' => 'program',
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
));
?>

<main class="pt-20">
	<!-- Hero Section -->
	<section class="relative bg-white pt-24 pb-20 lg:pt-32 overflow-hidden">
		<div
			class="absolute top-0 left-0 -translate-y-1/4 -translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
		</div>
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
			<span
				class="inline-block px-4 py-2 bg-rose-50 text-rose-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
				<?php _e('Our Programs', 'earlystart-early-learning'); ?>
			</span>
			<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
				<?php _e('Excellence in every stage of', 'earlystart-early-learning'); ?><br>
				<span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
					<?php _e('Early Development.', 'earlystart-early-learning'); ?>
				</span>
			</h1>
			<p class="text-xl text-stone-700 max-w-3xl mx-auto leading-relaxed fade-in-up">
				<?php _e('From clinical intensive ABA therapy to social-focused summer camps, our programs are designed to meet children exactly where they are in their developmental journey.', 'earlystart-early-learning'); ?>
			</p>
		</div>
	</section>

	<!-- Programs Grid -->
	<section class="py-24 bg-stone-50">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
				<?php if ($programs_query->have_posts()):
					while ($programs_query->have_posts()):
						$programs_query->the_post();
						$color_scheme = get_post_meta(get_the_ID(), 'program_color_scheme', true) ?: 'rose';
						$age_range = get_post_meta(get_the_ID(), 'program_age_range', true);
						$features = get_post_meta(get_the_ID(), 'program_features', true);

						$colors = array(
							'rose' => 'rose',
							'blue' => 'blue',
							'orange' => 'orange',
							'amber' => 'amber',
							'green' => 'emerald'
						);
						$theme_color = $colors[$color_scheme] ?? 'rose';
						?>
						<div
							class="group bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-stone-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col fade-in-up">
							<div class="relative aspect-video overflow-hidden">
								<?php if (has_post_thumbnail()): ?>
									<?php the_post_thumbnail('large', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-700']); ?>
								<?php else: ?>
									<img src="https://images.unsplash.com/photo-1516627145497-ae6968895b74?auto=format&fit=crop&q=80&fm=webp?w=800&fit=crop&q=80&fm=webp"
										class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
										alt="Program">
								<?php endif; ?>

								<?php if ($age_range): ?>
									<div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-1 rounded-full">
										<span
											class="text-[10px] font-bold text-stone-900 uppercase tracking-widest"><?php echo esc_html($age_range); ?></span>
									</div>
								<?php endif; ?>
							</div>

							<div class="p-10 flex flex-grow flex-col">
								<h3
									class="text-2xl font-bold text-stone-900 mb-4 group-hover:text-<?php echo $theme_color; ?>-600 transition-colors">
									<?php the_title(); ?>
								</h3>

								<div class="text-stone-700 text-sm leading-relaxed mb-8 flex-grow font-medium">
									<?php echo wp_trim_words(get_the_excerpt(), 25); ?>
								</div>

								<?php if ($features):
									$feat_list = array_slice(explode("\n", $features), 0, 3);
									?>
									<ul class="space-y-3 mb-10 border-t border-stone-50 pt-8">
										<?php foreach ($feat_list as $feat):
											if (trim($feat)): ?>
												<li class="flex items-center text-xs font-medium text-stone-700">
													<i data-lucide="check" class="w-4 h-4 text-<?php echo $theme_color; ?>-500 mr-3"></i>
													<?php echo esc_html(trim($feat)); ?>
												</li>
											<?php endif; endforeach; ?>
									</ul>
								<?php endif; ?>

								<div class="mt-auto">
									<a href="<?php the_permalink(); ?>"
										class="inline-flex items-center gap-2 text-<?php echo $theme_color; ?>-600 font-bold group/link">
										<?php _e('View Program Details', 'earlystart-early-learning'); ?>
										<i data-lucide="arrow-right"
											class="w-5 h-5 group-hover/link:translate-x-1 transition-transform"></i>
									</a>
								</div>
							</div>
						</div>
					<?php endwhile;
					wp_reset_postdata();
				endif; ?>
			</div>
		</div>
	</section>

	<!-- Support Bar -->
	<section class="py-20 bg-stone-900 text-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div
				class="bg-rose-600 rounded-[3rem] p-12 lg:p-20 relative overflow-hidden flex flex-col lg:flex-row items-center justify-between text-center lg:text-left gap-12">
				<div class="relative z-10 max-w-2xl">
					<h2 class="text-3xl md:text-4xl font-bold mb-6">
						<?php _e('Unsure which program fits?', 'earlystart-early-learning'); ?>
					</h2>
					<p class="text-white text-lg">
						<?php _e('Our clinical intake team can help assess your child\'s needs and recommend a personalized development path.', 'earlystart-early-learning'); ?>
					</p>
				</div>
				<div class="shrink-0 relative z-10">
					<a href="<?php echo esc_url(home_url('/contact/')); ?>"
						class="bg-white text-rose-700 px-10 py-5 rounded-full font-bold text-lg hover:bg-stone-900 hover:text-white transition-all shadow-xl">
						<?php _e('Contact Intake Team', 'earlystart-early-learning'); ?>
					</a>
				</div>
				<div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -mr-20 -mb-20"></div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>



<?php
get_footer();


