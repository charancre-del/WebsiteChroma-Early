<?php
/**
 * Template Name: About Page
 * Displays the About Us page with premium storytelling and leadership sections.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

while (have_posts()):
	the_post();
	$page_id = get_the_ID();
	?>

	<main class="pt-20">
		<!-- Hero: Mission & Purpose -->
		<?php
		$hero_badge = get_post_meta($page_id, 'about_hero_badge_text', true) ?: __('Established 2022', 'earlystart-early-learning');
		$hero_title = get_post_meta($page_id, 'about_hero_title', true) ?: __('More than a clinic. <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">A second home.</span>', 'earlystart-early-learning');
		$hero_desc = get_post_meta($page_id, 'about_hero_description', true) ?: __('We founded Early Start on a simple belief: Clinical therapy should be a perfect blend of rigorous skill development and the comforting warmth of family life.', 'earlystart-early-learning');
		?>
		<section class="relative bg-white pt-24 pb-24 lg:pt-32 overflow-hidden">
			<div
				class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
			</div>

			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
				<span
					class="inline-block px-4 py-2 bg-rose-50 text-rose-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
					<?php echo esc_html($hero_badge); ?>
				</span>
				<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
					<?php echo wp_kses_post($hero_title); ?>
				</h1>
				<p class="text-xl text-stone-700 max-w-3xl mx-auto leading-relaxed fade-in-up">
					<?php echo esc_html($hero_desc); ?>
				</p>
			</div>
		</section>

		<!-- Our Purpose Block -->
		<?php
		$mission_quote = get_post_meta($page_id, 'about_mission_quote', true) ?: __('"To cultivate a vibrant community of lifelong learners by blending clinical excellence with the nurturing warmth of home, ensuring every child feels seen, valued, and capable."', 'earlystart-early-learning');
		?>
		<section class="bg-stone-900 text-white py-24 relative overflow-hidden">
			<div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
			</div>
			<div class="max-w-5xl mx-auto px-4 text-center relative z-10">
				<i data-lucide="sparkles" class="w-12 h-12 text-amber-400 mx-auto mb-8"></i>
				<span class="text-sm font-bold text-stone-300 uppercase tracking-widest mb-6 block">
					<?php _e('Our Purpose', 'earlystart-early-learning'); ?>
				</span>
				<p class="text-2xl md:text-4xl font-serif italic leading-relaxed fade-in-up">
					<?php echo esc_html($mission_quote); ?>
				</p>
			</div>
		</section>

		<!-- Our Story -->
		<?php
		$story_title = get_post_meta($page_id, 'about_story_title', true) ?: __('From One Classroom to a Community', 'earlystart-early-learning');
		$story_p1 = get_post_meta($page_id, 'about_story_paragraph1', true);
		$story_p2 = get_post_meta($page_id, 'about_story_paragraph2', true);
		$story_image = get_post_meta($page_id, 'about_story_image', true) ?: 'https://images.unsplash.com/photo-1544717305-27a734ef202e?auto=format&fit=crop&q=80&fm=webp?w=800&fit=crop&q=80&fm=webp';
		?>
		<section class="py-24 bg-stone-50 overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid lg:grid-cols-2 gap-16 items-center">
					<div class="fade-in-up">
						<h2 class="text-4xl font-bold text-stone-900 mb-6">
							<?php echo esc_html($story_title); ?>
						</h2>
						<div class="prose prose-lg text-stone-700 space-y-6 max-w-none">
							<?php if ($story_p1): ?>
								<p>
									<?php echo esc_html($story_p1); ?>
								</p>
							<?php else: ?>
								<p>
									<?php _e('Early Start Early Learning Academy began with a mission to redefine "daycare." We didn\'t just want to watch children; we wanted to ignite their potential.', 'earlystart-early-learning'); ?>
								</p>
							<?php endif; ?>

							<?php if ($story_p2): ?>
								<p>
									<?php echo esc_html($story_p2); ?>
								</p>
							<?php else: ?>
								<p><strong>
										<?php _e('Early Start', 'earlystart-early-learning'); ?>
									</strong>
									<?php _e('was created to bring that same "second home" philosophy to pediatric therapy.', 'earlystart-early-learning'); ?>
								</p>
							<?php endif; ?>
						</div>

						<div class="grid grid-cols-3 gap-8 mt-12 border-t border-stone-200 pt-10">
							<?php
							// Stats Loop (1-3)
							for ($i = 1; $i <= 3; $i++) {
								$val = get_post_meta($page_id, "about_stat{$i}_value", true);
								$lbl = get_post_meta($page_id, "about_stat{$i}_label", true);

								// Intercept 'Clinical Centers' or 'Locations' label and inject dynamic count
								if (stripos((string) $lbl, 'clinical center') !== false || stripos((string) $lbl, 'location') !== false) {
									$location_count = wp_count_posts('location');
									// Use the actual count if published locations exist, otherwise fallback
									if (isset($location_count->publish) && $location_count->publish > 0) {
										$val = (string) $location_count->publish;
									}
								}

								if ($val): ?>
									<div>
										<span class="block text-4xl font-bold text-rose-700">
											<?php echo esc_html($val); ?>
										</span>
										<span class="text-xs text-stone-700 uppercase font-bold tracking-widest">
											<?php echo esc_html($lbl); ?>
										</span>
									</div>
								<?php endif;
							} ?>
						</div>
					</div>
					<div class="relative fade-in-up">
						<div class="absolute -inset-4 bg-rose-100 rounded-[3rem] transform rotate-2"></div>
						<div
							class="relative bg-white rounded-[3rem] h-[500px] overflow-hidden shadow-2xl border border-stone-100">
							<img src="<?php echo esc_url($story_image); ?>" class="w-full h-full object-cover"
								alt="<?php _e('Our Story', 'earlystart-early-learning'); ?>">
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Timeline Section (Static as per audit) -->
		<section class="py-24 bg-white border-b border-stone-100 overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-3xl font-bold text-stone-900">
						<?php _e('Our Journey', 'earlystart-early-learning'); ?>
					</h2>
				</div>
				<div class="relative">
					<div class="absolute top-1/2 left-0 w-full h-1 bg-stone-100 -translate-y-1/2 hidden md:block"></div>
					<div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10">
						<?php
						$milestones = array(
							array('year' => '2022', 'title' => 'The Beginning', 'desc' => 'Early Start Early Learning Academy opens its first flagship location.', 'color' => 'rose'),
							array('year' => '2023', 'title' => 'Rapid Expansion', 'desc' => 'Growth to 10+ locations identifying child development needs.', 'color' => 'orange'),
							array('year' => '2024', 'title' => 'Early Start', 'desc' => 'Official launch of our specialized ABA & Therapy division.', 'color' => 'amber'),
							array('year' => 'Today', 'title' => 'A Holistic Network', 'desc' => '19+ locations integrating education and clinical therapy.', 'color' => 'rose'),
						);
						foreach ($milestones as $m): ?>
							<div
								class="bg-white p-8 rounded-3xl border border-stone-100 text-center shadow-sm hover:shadow-md transition-shadow fade-in-up">
								<span
									class="inline-block px-4 py-1 bg-<?php echo $m['color']; ?>-50 text-<?php echo $m['color']; ?>-600 rounded-full text-sm font-bold mb-4">
									<?php echo esc_html($m['year']); ?>
								</span>
								<h4 class="font-bold text-lg mb-2 text-stone-900">
									<?php echo esc_html($m['title']); ?>
								</h4>
								<p class="text-sm text-stone-700 leading-relaxed">
									<?php echo esc_html($m['desc']); ?>
								</p>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>

		<!-- Clinicians Section (Mapped to Educators Meta) -->
		<?php
		$edu_title = get_post_meta($page_id, 'about_educators_title', true) ?: __('The Heart of Early Start', 'earlystart-early-learning');
		$edu_desc = get_post_meta($page_id, 'about_educators_description', true) ?: __('We don\'t just hire technicians; we hire career clinicians.', 'earlystart-early-learning');
		?>
		<section class="py-24 bg-white overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
					<h2 class="text-4xl font-bold text-stone-900 mb-4">
						<?php echo esc_html($edu_title); ?>
					</h2>
					<p class="text-stone-700 text-lg">
						<?php echo esc_html($edu_desc); ?>
					</p>
				</div>

				<div class="grid md:grid-cols-3 gap-8">
					<?php
					for ($i = 1; $i <= 3; $i++) {
						$icon = earlystart_safe_lucide_icon(get_post_meta($page_id, "about_educator{$i}_icon", true), 'star');
						$title = get_post_meta($page_id, "about_educator{$i}_title", true);
						$desc = get_post_meta($page_id, "about_educator{$i}_desc", true);

						if ($title): ?>
							<div
								class="bg-stone-50 p-10 rounded-[2.5rem] border border-stone-100 hover:shadow-lg transition-all fade-in-up">
								<div
									class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 text-rose-500">
									<i data-lucide="<?php echo esc_attr($icon); ?>" class="w-7 h-7"></i>
								</div>
								<h3 class="font-bold text-stone-900 text-xl mb-3">
									<?php echo esc_html($title); ?>
								</h3>
								<p class="text-stone-700 text-sm leading-relaxed">
									<?php echo esc_html($desc); ?>
								</p>
							</div>
						<?php endif;
					} ?>
				</div>
			</div>
		</section>

		<!-- Pillars Section (Mapped to Values Meta) -->
		<?php
		$val_title = get_post_meta($page_id, 'about_values_title', true) ?: __('Our Non-Negotiables', 'earlystart-early-learning');
		$val_desc = get_post_meta($page_id, 'about_values_description', true) ?: __('These four pillars guide every decision we make.', 'earlystart-early-learning');
		?>
		<section class="py-24 bg-rose-600 text-white relative overflow-hidden">
			<div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-4xl font-bold mb-4">
						<?php echo esc_html($val_title); ?>
					</h2>
					<p class="text-white max-w-2xl mx-auto text-lg">
						<?php echo esc_html($val_desc); ?>
					</p>
				</div>

				<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
					<?php
					for ($i = 1; $i <= 4; $i++) {
						$icon = earlystart_safe_lucide_icon(get_post_meta($page_id, "about_value{$i}_icon", true), 'circle');
						$title = get_post_meta($page_id, "about_value{$i}_title", true);
						$desc = get_post_meta($page_id, "about_value{$i}_desc", true);

						if ($title): ?>
							<div
								class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-rose-400/30 hover:bg-white/20 transition-all fade-in-up">
								<i data-lucide="<?php echo esc_attr($icon); ?>"
									class="w-10 h-10 text-rose-200 mb-4"></i>
								<h3 class="text-xl font-bold mb-2">
									<?php echo esc_html($title); ?>
								</h3>
								<p class="text-sm text-white leading-relaxed">
									<?php echo esc_html($desc); ?>
								</p>
							</div>
						<?php endif;
					} ?>
				</div>
			</div>
		</section>

		<!-- Culture & Diversity (New Section) -->
		<section class="py-24 bg-stone-900 text-white border-t border-stone-800">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid lg:grid-cols-2 gap-16 items-center">
					<div class="fade-in-up">
						<span class="text-amber-400 font-bold tracking-widest text-sm uppercase mb-4 block">
							<?php _e('Our Culture', 'earlystart-early-learning'); ?>
						</span>
						<h2 class="text-3xl md:text-4xl font-bold mb-6">
							<?php _e('A Place Where Everyone Belongs', 'earlystart-early-learning'); ?>
						</h2>
						<p class="text-stone-300 text-lg leading-relaxed mb-8">
							<?php _e('We believe that diversity strengthens our care. Early Start is committed to creating an inclusive environment where every child, family, and team member is celebrated for exactly who they are.', 'earlystart-early-learning'); ?>
						</p>
						<div class="space-y-4">
							<div class="flex items-center">
								<div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center mr-4">
									<i data-lucide="globe" class="w-5 h-5 text-amber-400"></i>
								</div>
								<span class="font-medium">
									<?php _e('Culturally Responsive Care', 'earlystart-early-learning'); ?>
								</span>
							</div>
							<div class="flex items-center">
								<div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center mr-4">
									<i data-lucide="heart-handshake" class="w-5 h-5 text-amber-400"></i>
								</div>
								<span class="font-medium">
									<?php _e('Neurodiversity-Affirming Practices', 'earlystart-early-learning'); ?>
								</span>
							</div>
						</div>
					</div>
					<div class="relative fade-in-up">
						<div
							class="absolute inset-0 bg-gradient-to-r from-rose-500 to-orange-500 rounded-[2.5rem] transform -rotate-2 opacity-50">
						</div>
						<div class="relative bg-stone-800 rounded-[2.5rem] p-10 border border-stone-700">
							<blockquote class="text-xl italic text-stone-200 mb-6">
								"
								<?php _e('Our son has never fit into a \'box\' before. At Early Start, they didn\'t try to change him; they built a world where he could succeed just as he is.', 'earlystart-early-learning'); ?>"
							</blockquote>
							<div class="flex items-center">
								<div
									class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center font-bold text-white text-sm mr-3">
									MJ</div>
								<div>
									<div class="font-bold">
										<?php _e('Marcus J.', 'earlystart-early-learning'); ?>
									</div>
									<div class="text-xs text-stone-300">
										<?php _e('Early Start Parent', 'earlystart-early-learning'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Leadership Section (Dynamic CPT) -->
		<?php
		$lead_title = get_post_meta($page_id, 'about_leadership_title', true) ?: __('Led By Educators & Clinicians', 'earlystart-early-learning');

		$team_query = new WP_Query(array(
			'post_type' => 'team_member',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		));
		?>
		<section class="py-24 bg-stone-50">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-4xl font-bold text-stone-900 mb-4">
						<?php echo esc_html($lead_title); ?>
					</h2>
					<p class="text-stone-700 max-w-2xl mx-auto text-lg">
						<?php _e('Our leadership team combines decades of experience in clinical therapy and education.', 'earlystart-early-learning'); ?>
					</p>
				</div>

				<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12">
					<?php
					if ($team_query->have_posts()):
						while ($team_query->have_posts()):
							$team_query->the_post();
							$role = get_post_meta(get_the_ID(), 'team_member_title', true);
							$image = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: '';
							$bio = get_the_content();
							?>
							<div class="group fade-in-up">
								<div
									class="relative mb-6 overflow-hidden rounded-[2.5rem] aspect-[4/5] shadow-lg group-hover:shadow-2xl transition-all duration-500">
									<?php if ($image): ?>
										<img src="<?php echo esc_url($image); ?>"
											class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
											alt="<?php the_title_attribute(); ?>">
									<?php else: ?>
										<div class="w-full h-full bg-white flex items-center justify-center text-stone-300">
											<i data-lucide="user-round" class="w-20 h-20"></i>
										</div>
									<?php endif; ?>
									<div
										class="absolute inset-0 bg-gradient-to-t from-stone-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-8">
										<button
											class="bg-white text-stone-900 px-6 py-2 rounded-full font-bold text-sm transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500"
											data-team-bio-trigger='<?php echo json_encode(array("name" => get_the_title(), "role" => $role, "bio" => wp_kses_post($bio), "image" => $image)); ?>'>
											<?php _e('View Bio', 'earlystart-early-learning'); ?>
										</button>
									</div>
								</div>
								<h4 class="text-2xl font-bold text-stone-900 mb-1 group-hover:text-rose-700 transition-colors">
									<?php the_title(); ?>
								</h4>
								<p class="text-stone-700 font-medium tracking-wide text-sm uppercase">
									<?php echo esc_html($role); ?>
								</p>
							</div>
						<?php endwhile;
						wp_reset_postdata();
					endif; ?>
				</div>
			</div>
		</section>

		<!-- Team Bio Modal Container -->
		<div id="team-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
			<div id="team-modal-overlay" class="absolute inset-0 bg-stone-900/80 backdrop-blur-sm"></div>
			<div class="relative bg-white w-full max-w-4xl rounded-[3rem] shadow-2xl overflow-hidden flex flex-col md:flex-row transform scale-95 opacity-0 transition-all duration-300"
				id="team-modal-content">
				<button id="team-modal-close"
					class="absolute top-6 right-6 z-10 w-12 h-12 bg-stone-100 rounded-full flex items-center justify-center hover:bg-stone-200 transition-colors">
					<i data-lucide="x" class="w-6 h-6 text-stone-900"></i>
				</button>
				<div class="md:w-2/5 h-64 md:h-auto">
					<img id="modal-image" src="" alt="" class="w-full h-full object-cover">
				</div>
				<div class="md:w-3/5 p-12 overflow-y-auto max-h-[80vh]">
					<span id="modal-role"
						class="text-rose-700 font-bold tracking-widest text-xs uppercase mb-2 block"></span>
					<h3 id="modal-name" class="text-4xl font-bold text-stone-900 mb-6"></h3>
					<div id="modal-bio" class="prose prose-stone text-stone-700 leading-relaxed"></div>
					<div class="mt-8 pt-8 border-t border-stone-100 flex gap-4">
						<div class="bg-stone-50 p-4 rounded-2xl flex-1">
							<span class="block text-[10px] font-bold text-stone-300 uppercase mb-1">
								<?php _e('Specialty', 'earlystart-early-learning'); ?>
							</span>
							<span class="text-sm font-bold text-stone-700">
								<?php _e('Early Intervention', 'earlystart-early-learning'); ?>
							</span>
						</div>
						<div class="bg-stone-50 p-4 rounded-2xl flex-1">
							<span class="block text-[10px] font-bold text-stone-300 uppercase mb-1">
								<?php _e('Approach', 'earlystart-early-learning'); ?>
							</span>
							<span class="text-sm font-bold text-stone-700">
								<?php _e('Assent-Based', 'earlystart-early-learning'); ?>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Community Section (Philanthropy) -->
		<?php
		$phil_title = get_post_meta($page_id, 'about_philanthropy_title', true) ?: __('Foundations For Learning Inc.', 'earlystart-early-learning');
		$phil_subtitle = get_post_meta($page_id, 'about_philanthropy_subtitle', true) ?: __('Community Impact', 'earlystart-early-learning');
		$phil_desc = get_post_meta($page_id, 'about_philanthropy_description', true) ?: __('Through our non-profit arm, we work to ensure quality early intervention resources are accessible.', 'earlystart-early-learning');
		?>
		<section class="py-24 bg-white overflow-hidden">
			<div class="max-w-6xl mx-auto px-4">
				<div
					class="bg-stone-900 rounded-[3rem] p-12 lg:p-24 text-center text-white relative overflow-hidden fade-in-up">
					<div
						class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-rose-500 to-transparent">
					</div>
					<div class="relative z-10">
						<span class="text-rose-400 font-bold tracking-widest text-sm uppercase mb-4 block">
							<?php echo esc_html($phil_subtitle); ?>
						</span>
						<h2 class="text-4xl md:text-5xl font-bold mb-8">
							<?php echo esc_html($phil_title); ?>
						</h2>
						<p class="text-xl text-stone-300 leading-relaxed mb-12 max-w-3xl mx-auto">
							<?php echo esc_html($phil_desc); ?>
						</p>
						<div class="flex flex-wrap justify-center gap-4">
							<?php
							for ($i = 1; $i <= 3; $i++) {
								$txt = get_post_meta($page_id, "about_philanthropy_bullet{$i}_text", true);
								if ($txt): ?>
									<span
										class="px-8 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-sm font-bold">
										<?php echo esc_html($txt); ?>
									</span>
								<?php endif;
							} ?>
						</div>
					</div>
				</div>
			</div>
		</section>

	</main>

	<?php
endwhile;
get_footer();


