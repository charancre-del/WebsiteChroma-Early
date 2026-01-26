<?php
/**
 * Single Location Template
 *
 * @package EarlyStart_Early_Start
 */

get_header();

while (have_posts()):
	the_post();
	$location_fields = earlystart_get_location_fields();
	$location_id = get_the_ID();
	$location_name = get_the_title();

	// Get location meta
	$phone = $location_fields['phone'];
	$email = $location_fields['email'];
	$address = earlystart_location_address_line();
	$city = $location_fields['city'];
	$state = $location_fields['state'];
	$zip = $location_fields['zip'];
	$lat = $location_fields['latitude'];
	$lng = $location_fields['longitude'];
	$license_number = $location_fields['license_number'];

	// Additional meta fields (with defaults)
	$hero_subtitle = earlystart_get_translated_meta($location_id, 'location_hero_subtitle') ?: __('Now Enrolling: Pre-K & Toddlers', 'earlystart-early-learning');
	$hero_gallery_raw = earlystart_get_translated_meta($location_id, 'location_hero_gallery');
	$virtual_tour_embed = earlystart_get_translated_meta($location_id, 'location_virtual_tour_embed');
	$tagline = earlystart_get_translated_meta($location_id, 'location_tagline') ?: sprintf(__("%s's home for brilliant beginnings.", 'earlystart-early-learning'), $city);
	$description = earlystart_get_translated_meta($location_id, 'location_description') ?: get_the_content();

	// Parse hero gallery URLs (one per line)
	$hero_gallery = array();
	if (!empty($hero_gallery_raw)) {
		$lines = explode("\n", $hero_gallery_raw);
		foreach ($lines as $line) {
			$url = trim($line);
			if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
				$hero_gallery[] = esc_url($url);
			}
		}
	}
	$google_rating = earlystart_get_translated_meta($location_id, 'location_google_rating') ?: '4.9';
	$hours = earlystart_get_translated_meta($location_id, 'location_hours') ?: __('7am - 6pm', 'earlystart-early-learning');
	$ages_served = earlystart_get_translated_meta($location_id, 'location_ages_served') ?: __('6w - 12y', 'earlystart-early-learning');

	// Director info
	$director_name = earlystart_get_translated_meta($location_id, 'location_director_name');
	$director_heading = earlystart_get_translated_meta($location_id, 'location_director_heading');
	$director_bio = earlystart_get_translated_meta($location_id, 'location_director_bio');
	$director_photo = earlystart_get_translated_meta($location_id, 'location_director_photo');
	$director_signature = earlystart_get_translated_meta($location_id, 'location_director_signature');

	// Maps embed
	$maps_embed = earlystart_get_translated_meta($location_id, 'location_maps_embed');

	// Tour booking link
	$tour_booking_link = earlystart_get_translated_meta($location_id, 'location_tour_booking_link');

	// School pickups
	$school_pickups = earlystart_get_translated_meta($location_id, 'location_school_pickups');

	// SEO content
	$seo_content_title = earlystart_get_translated_meta($location_id, 'location_seo_content_title');
	$seo_content_text = earlystart_get_translated_meta($location_id, 'location_seo_content_text');

	// Get programs at this location
	$programs_query = new WP_Query(array(
		'post_type' => 'program',
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' => 'program_locations',
				'value' => '(^|;)i:' . intval($location_id) . ';',
				'compare' => 'REGEXP',
			),
		),
	));

	// Get Region Colors
	$location_regions = wp_get_post_terms($location_id, 'location_region');
	$region_term = !empty($location_regions) && !is_wp_error($location_regions) ? $location_regions[0] : null;
	$region_colors = $region_term ? earlystart_get_region_color_from_term($region_term->term_id) : array(
		'bg' => 'chroma-blueLight',
		'text' => 'chroma-blue',
		'border' => 'chroma-blue',
	);
	?>

	<main class="pt-20">
		<!-- Hero Section -->
		<section class="relative bg-white pt-24 pb-20 lg:pt-32 overflow-hidden border-b border-stone-50">
			<div
				class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
			</div>
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
				<div class="grid lg:grid-cols-2 gap-16 items-center">
					<div class="fade-in-up">
						<span
							class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6">
							<?php echo esc_html($hero_subtitle ?: __('Clinical Excellence', 'earlystart-early-learning')); ?>
						</span>
						<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight">
							<?php echo esc_html($location_name); ?>
						</h1>
						<p class="text-xl text-stone-600 leading-relaxed mb-10 max-w-xl">
							<?php echo esc_html($tagline); ?>
						</p>

						<div class="flex flex-wrap gap-4 mb-10">
							<a href="#tour"
								class="bg-stone-900 text-white px-10 py-5 rounded-full font-bold hover:bg-rose-600 transition-all shadow-xl active:scale-95">
								<?php _e('Schedule a Tour', 'earlystart-early-learning'); ?>
							</a>
							<?php if ($phone): ?>
								<a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $phone)); ?>"
									class="bg-stone-50 text-stone-900 px-10 py-5 rounded-full font-bold hover:bg-stone-100 transition-all">
									<i data-lucide="phone" class="w-4 h-4 inline-block mr-2"></i>
									<?php echo esc_html($phone); ?>
								</a>
							<?php endif; ?>
						</div>

						<div class="grid grid-cols-3 gap-8 pt-10 border-t border-stone-100">
							<div>
								<div class="text-2xl font-bold text-stone-900 mb-1"><?php echo esc_html($ages_served); ?>
								</div>
								<div class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">
									<?php _e('Ages', 'earlystart-early-learning'); ?>
								</div>
							</div>
							<div>
								<div class="text-2xl font-bold text-stone-900 mb-1"><?php echo esc_html($google_rating); ?>
									★</div>
								<div class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">
									<?php _e('Rating', 'earlystart-early-learning'); ?>
								</div>
							</div>
							<div>
								<div class="text-2xl font-bold text-stone-900 mb-1"><?php echo esc_html($hours); ?></div>
								<div class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">
									<?php _e('Clinic Hours', 'earlystart-early-learning'); ?>
								</div>
							</div>
						</div>
					</div>

					<!-- Hero Image / Carousel -->
					<div class="relative fade-in-up delay-200 block">
						<div
							class="absolute inset-0 bg-<?php echo esc_attr($region_colors['text']); ?>/10 rounded-[3rem] rotate-6 transform translate-x-4 translate-y-4">
						</div>
						<div class="relative rounded-[3rem] overflow-hidden shadow-2xl border-4 border-white aspect-square md:aspect-[4/3]"
							<?php if (count($hero_gallery) > 1)
								echo 'data-location-carousel'; ?>>
							<?php if (!empty($hero_gallery)): ?>
								<!-- Gallery Carousel -->
								<div class="relative w-full h-full">
									<div class="flex transition-transform duration-500 ease-in-out h-full"
										data-location-carousel-track>
										<?php foreach ($hero_gallery as $index => $image_url):
											// Try to get attachment ID to serve responsive images
											$attachment_id = attachment_url_to_postid($image_url);
											?>
											<div class="w-full h-full flex-shrink-0"
												data-location-slide="<?php echo esc_attr($index); ?>">
												<?php if ($attachment_id):
													echo wp_get_attachment_image($attachment_id, 'large', false, array(
														'class' => 'w-full h-full object-cover',
														'fetchpriority' => $index === 0 ? 'high' : 'auto',
														'loading' => $index === 0 ? 'eager' : 'lazy',
														'decoding' => 'async',
														'sizes' => '(max-width: 768px) 100vw, 50vw'
													));
												else: ?>
													<img src="<?php echo esc_url($image_url); ?>"
														alt="<?php echo esc_attr($location_name); ?> - Image <?php echo esc_attr($index + 1); ?>"
														class="w-full h-full object-cover" decoding="async"
														sizes="(max-width: 768px) 100vw, 50vw" <?php if ($index === 0)
															echo 'fetchpriority="high"';
														else
															echo 'loading="lazy"'; ?> />
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									</div>

									<?php if (count($hero_gallery) > 1): ?>
										<!-- Navigation Arrows -->
										<button
											class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center bg-white/90 rounded-full shadow-lg text-brand-ink hover:bg-white transition"
											data-location-prev aria-label="Previous image">
											<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M15 19l-7-7 7-7" />
											</svg>
										</button>
										<button
											class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center bg-white/90 rounded-full shadow-lg text-brand-ink hover:bg-white transition"
											data-location-next aria-label="Next image">
											<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
													d="M9 5l7 7-7 7" />
											</svg>
										</button>

										<!-- Dots -->
										<div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2" data-location-dots>
											<?php foreach ($hero_gallery as $index => $image_url): ?>
												<button
													class="w-2 h-2 rounded-full transition-all <?php echo 0 === $index ? 'bg-white w-6' : 'bg-white/50'; ?>"
													data-location-dot="<?php echo esc_attr($index); ?>"
													aria-label="Go to image <?php echo esc_attr($index + 1); ?>"></button>
											<?php endforeach; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php elseif (has_post_thumbnail()): ?>
								<?php the_post_thumbnail('large', array('class' => 'w-full h-full object-cover', 'fetchpriority' => 'high', 'sizes' => '(max-width: 768px) 100vw, 50vw')); ?>
							<?php else:
								// Unsplash fallback with srcset
								$base_unsplash = "https://images.unsplash.com/photo-1587654780291-39c9404d746b?q=80&auto=format&fit=crop";
								$src_mobile = $base_unsplash . "&w=600&h=600";
								$src_desktop = $base_unsplash . "&w=1000&h=750";
								?>
								<img src="<?php echo esc_url($src_desktop); ?>"
									srcset="<?php echo esc_url($src_mobile); ?> 600w, <?php echo esc_url($src_desktop); ?> 1000w"
									sizes="(max-width: 768px) 100vw, 50vw" alt="<?php echo esc_attr($location_name); ?> Campus"
									class="w-full h-full object-cover" fetchpriority="high" decoding="async" width="1000"
									height="750" />
							<?php endif; ?>

							<!-- Floating Review Badge -->
							<?php
							$hero_review_text = earlystart_get_translated_meta(get_the_ID(), 'location_hero_review_text');
							$hero_review_author = earlystart_get_translated_meta(get_the_ID(), 'location_hero_review_author') ?: __('Parent Review', 'earlystart-early-learning');

							if ($hero_review_text):
								?>
								<div
									class="hidden lg:block absolute bottom-6 left-6 bg-white/95 backdrop-blur-sm p-5 rounded-2xl shadow-float max-w-[200px] fade-in-up delay-300 z-20">
									<div class="flex items-center gap-1 mb-2">
										<?php for ($i = 0; $i < 5; $i++): ?>
											<i class="fa-solid fa-star text-chroma-yellow text-sm"></i>
										<?php endfor; ?>
									</div>
									<p class="text-xs font-serif italic text-brand-ink/90">
										"<?php echo esc_html($hero_review_text); ?>"
									</p>
									<p class="text-[10px] font-bold text-brand-ink mt-2 uppercase tracking-wide">—
										<?php echo esc_html($hero_review_author); ?>
									</p>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
		</section>

		<!-- Campus Highlights -->
		<section id="about" class="py-24 bg-stone-50 overflow-hidden relative">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
				<div class="text-center mb-20 fade-in-up">
					<h2 class="text-4xl font-bold text-stone-900 mb-6">
						<?php _e('Designed for Clinical Magic.', 'earlystart-early-learning'); ?>
					</h2>
					<p class="text-xl text-stone-500 max-w-2xl mx-auto">
						<?php printf(__('Every corner of our %s campus is intentional—from sensory-sensitive treatment rooms to stimulating learning environments.', 'earlystart-early-learning'), esc_html($city)); ?>
					</p>
				</div>

				<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
					<div
						class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-stone-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 fade-in-up">
						<div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-8">
							<i data-lucide="shield-check" class="w-7 h-7"></i>
						</div>
						<h3 class="text-xl font-bold text-stone-900 mb-4">
							<?php _e('Secure Access', 'earlystart-early-learning'); ?>
						</h3>
						<p class="text-stone-500 text-sm leading-relaxed">
							<?php _e('Biometric entry and constant clinical oversight ensure our students are safe and supported.', 'earlystart-early-learning'); ?>
						</p>
					</div>

					<div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-stone-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 fade-in-up"
						style="animation-delay: 0.1s;">
						<div
							class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-8">
							<i data-lucide="brain-circuit" class="w-7 h-7"></i>
						</div>
						<h3 class="text-xl font-bold text-stone-900 mb-4">
							<?php _e('Sensory Spaces', 'earlystart-early-learning'); ?>
						</h3>
						<p class="text-stone-500 text-sm leading-relaxed">
							<?php _e('Custom-built zones to help students regulate and focus through evidence-based sensory integration.', 'earlystart-early-learning'); ?>
						</p>
					</div>

					<div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-stone-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 fade-in-up"
						style="animation-delay: 0.2s;">
						<div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-8">
							<i data-lucide="flask-conical" class="w-7 h-7"></i>
						</div>
						<h3 class="text-xl font-bold text-stone-900 mb-4"><?php _e('STEM Labs', 'earlystart-early-learning'); ?>
						</h3>
						<p class="text-stone-500 text-sm leading-relaxed">
							<?php _e('Clinical learning hubs for early engineering, light exploration, and scientific discovery.', 'earlystart-early-learning'); ?>
						</p>
					</div>

					<div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-stone-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 fade-in-up"
						style="animation-delay: 0.3s;">
						<div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-8">
							<i data-lucide="scroll-text" class="w-7 h-7"></i>
						</div>
						<h3 class="text-xl font-bold text-stone-900 mb-4"><?php _e('GA Pre-K', 'earlystart-early-learning'); ?>
						</h3>
						<p class="text-stone-500 text-sm leading-relaxed">
							<?php _e('A proud partner of the Georgia Pre-K Program, blending clinical support with academic readiness.', 'earlystart-early-learning'); ?>
						</p>
					</div>
				</div>
			</div>
		</section>

		<?php if ($director_name): ?>
			<!-- Director's Welcome -->
			<section id="director" class="py-24 bg-stone-900 text-white overflow-hidden relative">
				<div
					class="absolute top-0 right-0 w-96 h-96 bg-rose-600/20 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2">
				</div>
				<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
					<div class="grid lg:grid-cols-2 gap-20 items-center">
						<?php if ($director_photo): ?>
							<div class="relative fade-in-up">
								<div class="aspect-[4/5] rounded-[3rem] overflow-hidden border-8 border-stone-800 shadow-2xl">
									<img src="<?php echo esc_url($director_photo); ?>" alt="<?php echo esc_attr($director_name); ?>"
										class="w-full h-full object-cover">
								</div>
								<div class="absolute -bottom-10 -right-10 w-48 h-48 bg-rose-600 rounded-full blur-3xl opacity-30">
								</div>
							</div>
						<?php endif; ?>

						<div class="fade-in-up" <?php echo !$director_photo ? 'class="lg:col-span-2 text-center max-w-3xl mx-auto"' : ''; ?>>
							<span
								class="inline-block px-4 py-2 bg-rose-900/50 text-rose-400 rounded-full text-xs font-bold tracking-widest uppercase mb-6">
								<?php _e('Meet the Director', 'earlystart-early-learning'); ?>
							</span>
							<h2 class="text-4xl md:text-5xl font-bold mb-8 leading-tight">
								<?php echo $director_heading ?: sprintf(__('Leading with Heart in %s.', 'earlystart-early-learning'), esc_html($city)); ?>
							</h2>
							<div class="text-xl text-stone-400 leading-relaxed mb-10 prose prose-invert max-w-none">
								<?php echo wpautop(wp_kses_post($director_bio)); ?>
							</div>

							<div class="flex items-center gap-6">
								<?php if ($director_signature): ?>
									<img src="<?php echo esc_url($director_signature); ?>" alt="Signature"
										class="h-16 w-auto opacity-80 invert">
								<?php endif; ?>
								<div>
									<p class="text-lg font-bold text-white"><?php echo esc_html($director_name); ?></p>
									<p class="text-sm text-stone-500 uppercase tracking-widest">
										<?php _e('Campus Director', 'earlystart-early-learning'); ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if (!empty($virtual_tour_embed)): ?>
			<!-- Virtual Tour -->
			<section id="virtual-tour" class="py-24 bg-white relative">
				<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
					<div class="text-center mb-16 fade-in-up">
						<h2 class="text-4xl font-bold text-stone-900 mb-6">
							<?php _e('Explore Our Clinical Environment.', 'earlystart-early-learning'); ?></h2>
						<p class="text-xl text-stone-500 max-w-2xl mx-auto">
							<?php printf(__('Walk through our %s campus from the comfort of your home. See our PrismaPath™ curriculum in action.', 'earlystart-early-learning'), esc_html($city)); ?>
						</p>
					</div>

					<div
						class="relative aspect-video rounded-[3rem] overflow-hidden shadow-2xl border border-stone-100 bg-stone-50 fade-in-up">
						<?php
						$allowed_tags = wp_kses_allowed_html('post');
						$allowed_tags['iframe'] = array('src' => true, 'width' => true, 'height' => true, 'frameborder' => true, 'allowfullscreen' => true, 'allow' => true, 'loading' => true, 'style' => true, 'class' => true, 'title' => true);
						echo wp_kses($virtual_tour_embed, $allowed_tags);
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- Programs Grid -->
		<?php if ($programs_query->have_posts()): ?>
			<section id="programs" class="py-24 bg-stone-50">
				<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
					<div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
						<div class="fade-in-up">
							<h2 class="text-4xl font-bold text-stone-900 mb-4">
								<?php _e('Specialized Care. Hyperlocal Delivery.', 'earlystart-early-learning'); ?></h2>
							<p class="text-stone-500 text-lg">
								<?php _e('Our campus offers clinical programs tailored to every developmental stage.', 'earlystart-early-learning'); ?>
							</p>
						</div>
						<a href="<?php echo esc_url(earlystart_get_program_archive_url()); ?>"
							class="text-rose-600 font-bold flex items-center gap-2 group fade-in-up">
							<?php _e('Explore Curriculum', 'earlystart-early-learning'); ?>
							<i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
						</a>
					</div>

					<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
						<?php while ($programs_query->have_posts()):
							$programs_query->the_post();
							$prog_fields = earlystart_get_program_fields();
							$age_range = $prog_fields['age_range'];
							$excerpt = $prog_fields['excerpt'] ?: wp_trim_words(get_the_content(), 20);
							?>
							<a href="<?php the_permalink(); ?>"
								class="group bg-white rounded-[2.5rem] overflow-hidden border border-stone-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col fade-in-up">
								<?php if (has_post_thumbnail()): ?>
									<div class="h-64 overflow-hidden">
										<?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-700']); ?>
									</div>
								<?php endif; ?>
								<div class="p-10 flex-1 flex flex-col">
									<span
										class="bg-rose-50 text-rose-600 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest mb-6 w-fit">
										<?php echo esc_html($age_range); ?>
									</span>
									<h3 class="text-2xl font-bold text-stone-900 mb-4 group-hover:text-rose-600 transition-colors">
										<?php the_title(); ?></h3>
									<p class="text-stone-500 text-sm leading-relaxed mb-8 flex-1"><?php echo esc_html($excerpt); ?>
									</p>
									<div class="flex items-center gap-2 text-rose-600 font-bold text-xs">
										<?php _e('View Program', 'earlystart-early-learning'); ?>
										<i data-lucide="chevron-right"
											class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
									</div>
								</div>
							</a>
						<?php endwhile;
						wp_reset_postdata(); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- Testimonials -->
		<section class="py-24 bg-white">
			<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
				<div class="inline-block p-4 bg-rose-50 text-rose-600 rounded-2xl mb-10">
					<i data-lucide="quote" class="w-8 h-8"></i>
				</div>
				<h2 class="text-4xl font-bold text-stone-900 mb-12">
					<?php _e('Voices of the Community.', 'earlystart-early-learning'); ?></h2>
				<blockquote class="text-3xl italic text-stone-600 leading-relaxed mb-12">
					"<?php echo esc_html($hero_review_text ?: __("The clinical support here has changed our child's trajectory. We finally feel heard and supported.", 'earlystart-early-learning')); ?>"
				</blockquote>
				<div class="flex flex-col items-center">
					<div class="w-16 h-1 bg-rose-600 rounded-full mb-6"></div>
					<cite class="not-italic">
						<span
							class="block text-lg font-bold text-stone-900 uppercase tracking-widest"><?php echo esc_html($hero_review_author ?: __("Happy Parent", 'earlystart-early-learning')); ?></span>
						<span
							class="text-sm text-stone-400"><?php _e('Georgia Campus Family', 'earlystart-early-learning'); ?></span>
					</cite>
				</div>
			</div>
		</section>

		<!-- FAQ Section -->
		<section class="py-24 bg-stone-50 border-t border-stone-100">
			<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-4xl font-bold text-stone-900 mb-6">
						<?php _e('Questions & Answers', 'earlystart-early-learning'); ?></h2>
					<p class="text-stone-500 text-lg">
						<?php _e('Common inquiries about our clinical approach and enrollment process.', 'earlystart-early-learning'); ?>
					</p>
				</div>

				<div class="space-y-4">
					<?php
					$location_faqs = earlystart_get_location_faq_items($location_id);
					foreach ($location_faqs as $item):
						?>
						<div
							class="group bg-white rounded-3xl p-8 shadow-sm border border-stone-100 hover:shadow-xl transition-all duration-300">
							<h3 class="text-lg font-bold text-stone-900 mb-4"><?php echo esc_html($item['question']); ?></h3>
							<div class="text-stone-500 text-sm leading-relaxed prose prose-stone max-w-none">
								<?php echo wp_kses_post($item['answer']); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- Visit Section -->
		<section id="contact" class="py-24 bg-white overflow-hidden relative">
			<div
				class="absolute bottom-0 left-0 w-96 h-96 bg-sky-50 rounded-full blur-3xl -translate-x-1/2 translate-y-1/2 opacity-50">
			</div>
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
				<div class="grid lg:grid-cols-2 gap-20 items-start">

					<div class="fade-in-up">
						<span
							class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6">
							<?php _e('Location Details', 'earlystart-early-learning'); ?>
						</span>
						<h2 class="text-4xl md:text-5xl font-bold text-stone-900 mb-8 leading-tight">
							<?php _e('Experience the Magic.', 'earlystart-early-learning'); ?>
						</h2>
						<p class="text-lg text-stone-500 mb-12 leading-relaxed">
							<?php _e('Clinical excellence is best experienced in person. Come see how we blend therapy and education in a premium environment.', 'earlystart-early-learning'); ?>
						</p>

						<div class="space-y-10">
							<div class="flex gap-6 text-stone-900">
								<div class="w-14 h-14 bg-stone-50 rounded-2xl flex items-center justify-center shrink-0">
									<i data-lucide="map-pin" class="w-7 h-7"></i>
								</div>
								<div>
									<h4 class="text-lg font-bold mb-2"><?php _e('Campus Address', 'earlystart-early-learning'); ?>
									</h4>
									<p class="text-stone-500 leading-relaxed">
										<?php echo esc_html($address); ?><br><?php echo esc_html("$city, $state $zip"); ?>
									</p>
								</div>
							</div>

							<div class="flex gap-6 text-stone-900">
								<div class="w-14 h-14 bg-stone-50 rounded-2xl flex items-center justify-center shrink-0">
									<i data-lucide="phone" class="w-7 h-7"></i>
								</div>
								<div>
									<h4 class="text-lg font-bold mb-2"><?php _e('Direct Line', 'earlystart-early-learning'); ?>
									</h4>
									<p class="text-stone-500 leading-relaxed"><?php echo esc_html($phone); ?></p>
								</div>
							</div>

							<div class="flex gap-6 text-stone-900">
								<div class="w-14 h-14 bg-stone-50 rounded-2xl flex items-center justify-center shrink-0">
									<i data-lucide="clock" class="w-7 h-7"></i>
								</div>
								<div>
									<h4 class="text-lg font-bold mb-2">
										<?php _e('Operational Hours', 'earlystart-early-learning'); ?></h4>
									<p class="text-stone-500 leading-relaxed"><?php echo esc_html($hours); ?>
										(<?php _e('Mon-Fri', 'earlystart-early-learning'); ?>)</p>
								</div>
							</div>
						</div>

						<?php if ($maps_embed): ?>
							<div
								class="mt-16 rounded-[2.5rem] overflow-hidden shadow-2xl border border-stone-100 aspect-video lg:aspect-square">
								<?php echo wp_kses($maps_embed, ['iframe' => ['src' => true, 'width' => true, 'height' => true, 'frameborder' => true, 'allowfullscreen' => true, 'loading' => true]]); ?>
							</div>
						<?php endif; ?>
					</div>

					<div id="tour"
						class="fade-in-up bg-stone-50 p-12 lg:p-16 rounded-[3rem] border border-stone-100 shadow-xl sticky top-32">
						<h3 class="text-3xl font-bold text-stone-900 mb-4">
							<?php _e('Schedule a Private Tour', 'earlystart-early-learning'); ?></h3>
						<p class="text-stone-500 mb-10">
							<?php _e('Enter your details below and our intake team will coordinate a time for you to visit.', 'earlystart-early-learning'); ?>
						</p>

						<div class="clinical-form">
							<?php echo do_shortcode('[earlystart_tour_form location_id="' . $location_id . '"]'); ?>
						</div>

						<?php if ($tour_booking_link): ?>
							<div class="mt-10 pt-10 border-t border-stone-100 text-center">
								<p class="text-stone-400 text-sm font-bold uppercase tracking-widest mb-6">
									<?php _e('Or Book Instantly', 'earlystart-early-learning'); ?></p>
								<a href="<?php echo esc_url($tour_booking_link); ?>"
									class="bg-rose-600 text-white px-10 py-5 rounded-full font-bold shadow-lg hover:bg-stone-900 transition-all inline-block">
									<?php _e('Book Direct via Calendar', 'earlystart-early-learning'); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<?php if ($seo_content_title || $seo_content_text): ?>
			<!-- SEO Content -->
			<section class="py-24 bg-stone-50">
				<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
					<h2 class="text-3xl font-bold text-stone-900 mb-8"><?php echo esc_html($seo_content_title); ?></h2>
					<div class="text-stone-500 leading-relaxed prose prose-stone max-w-none">
						<?php echo wp_kses_post(wpautop($seo_content_text)); ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- Inner Content -->
		<section class="pb-24 bg-stone-50">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<?php the_content(); ?>
			</div>
		</section>

	</main>

<?php endwhile; ?>

<!-- Tour Booking Modal -->
<div id="chroma-tour-modal" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
	<div class="absolute inset-0 bg-stone-900/80 backdrop-blur-sm transition-opacity" id="chroma-tour-backdrop"></div>
	<div
		class="absolute inset-4 md:inset-10 bg-white rounded-[3rem] shadow-2xl overflow-hidden flex flex-col animate-fade-in-up">
		<div class="bg-stone-50 border-b border-stone-100 px-8 py-6 flex items-center justify-between flex-shrink-0">
			<h3 class="text-2xl font-bold text-stone-900"><?php _e('Schedule Your Visit', 'earlystart-early-learning'); ?></h3>
			<div class="flex items-center gap-6">
				<a href="#" id="chroma-tour-external" target="_blank"
					class="text-sm font-bold uppercase tracking-widest text-stone-400 hover:text-rose-600 transition-colors hidden md:block">
					<?php _e('Open in new tab', 'earlystart-early-learning'); ?> <i data-lucide="external-link"
						class="w-4 h-4 inline-block ml-1"></i>
				</a>
				<button id="chroma-tour-close"
					class="w-12 h-12 rounded-full bg-white border border-stone-100 flex items-center justify-center text-stone-900 hover:bg-rose-50 hover:text-rose-600 transition-all">
					<i data-lucide="x" class="w-6 h-6"></i>
				</button>
			</div>
		</div>
		<div class="flex-grow relative bg-white">
			<div id="chroma-tour-loader"
				class="absolute inset-0 flex items-center justify-center bg-white z-10 transition-opacity duration-300">
				<div class="w-12 h-12 border-4 border-rose-100 border-t-rose-600 rounded-full animate-spin"></div>
			</div>
			<iframe id="chroma-tour-frame" src="" class="w-full h-full border-0"
				allow="camera; microphone; autoplay; encrypted-media;"></iframe>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		const modal = document.getElementById('chroma-tour-modal');
		const backdrop = document.getElementById('chroma-tour-backdrop');
		const closeBtn = document.getElementById('chroma-tour-close');
		const iframe = document.getElementById('chroma-tour-frame');
		const externalLink = document.getElementById('chroma-tour-external');
		const loader = document.getElementById('chroma-tour-loader');

		function openModal(url) {
			modal.classList.remove('hidden');
			document.body.style.overflow = 'hidden';
			loader.style.opacity = '1';
			iframe.src = url;
			externalLink.href = url;
			iframe.onload = function () {
				loader.style.opacity = '0';
				setTimeout(() => loader.classList.add('hidden'), 300);
			};
		}

		function closeModal() {
			modal.classList.add('hidden');
			document.body.style.overflow = '';
			iframe.src = '';
			loader.classList.remove('hidden');
		}

		const bookingBtns = document.querySelectorAll('.booking-btn, a[href*="calendly.com"], a[href*="tidycal.com"]');
		bookingBtns.forEach(btn => {
			btn.addEventListener('click', function (e) {
				const url = this.getAttribute('href');
				if (url && (url.includes('calendly.com') || url.includes('tidycal.com'))) {
					e.preventDefault();
					openModal(url);
				}
			});
		});

		if (closeBtn) closeBtn.addEventListener('click', closeModal);
		if (backdrop) backdrop.addEventListener('click', closeModal);
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
		});
	});
</script>

<?php get_footer(); ?>
