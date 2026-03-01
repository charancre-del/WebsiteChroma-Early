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

	$phone = $location_fields['phone'];
	$email = $location_fields['email'];
	$address = earlystart_location_address_line();
	$city = $location_fields['city'];
	$state = $location_fields['state'];
	$zip = $location_fields['zip'];

	$hero_subtitle = earlystart_get_translated_meta($location_id, 'location_hero_subtitle') ?: __('Now Enrolling', 'earlystart-early-learning');
	$hero_gallery_raw = earlystart_get_translated_meta($location_id, 'location_hero_gallery');
	$tagline = earlystart_get_translated_meta($location_id, 'location_tagline') ?: sprintf(__('Personalized therapy for %s families.', 'earlystart-early-learning'), $city ?: __('your community', 'earlystart-early-learning'));
	$description = earlystart_get_translated_meta($location_id, 'location_description');
	if (empty($description)) {
		$description = get_the_content();
	}

	$google_rating = earlystart_get_translated_meta($location_id, 'location_google_rating') ?: '4.9';
	$hours = earlystart_get_translated_meta($location_id, 'location_hours') ?: __('Mon - Fri: 8:00 AM - 6:00 PM', 'earlystart-early-learning');
	$ages_served = earlystart_get_translated_meta($location_id, 'location_ages_served') ?: __('18mo - 12yrs', 'earlystart-early-learning');

	$director_name = earlystart_get_translated_meta($location_id, 'location_director_name');
	$director_bio = earlystart_get_translated_meta($location_id, 'location_director_bio');
	$director_photo = earlystart_get_translated_meta($location_id, 'location_director_photo');

	$hero_review_text = earlystart_get_translated_meta($location_id, 'location_hero_review_text');
	$hero_review_author = earlystart_get_translated_meta($location_id, 'location_hero_review_author') ?: __('Parent Review', 'earlystart-early-learning');

	$maps_embed = earlystart_get_translated_meta($location_id, 'location_maps_embed');
	$tour_booking_link = earlystart_get_translated_meta($location_id, 'location_tour_booking_link');
	$is_clinic_hub = '1' === get_post_meta($location_id, 'location_featured', true);

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

	$featured_image = get_the_post_thumbnail_url($location_id, 'full');
	$hero_image_url = '';
	if (!empty($hero_gallery)) {
		$hero_image_url = $hero_gallery[0];
	} elseif (!empty($featured_image)) {
		$hero_image_url = $featured_image;
	} else {
		$hero_image_url = 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80';
	}

	$gallery_images = $hero_gallery;
	if (empty($gallery_images) && $featured_image) {
		$gallery_images[] = $featured_image;
	}
	$gallery_images = array_slice($gallery_images, 0, 5);
	while (count($gallery_images) < 5) {
		$gallery_images[] = '';
	}

	$map_query = trim($address . ', ' . $city . ', ' . $state . ' ' . $zip);
	$map_link = $map_query ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($map_query) : '#';

	$programs_query = new WP_Query(array(
		'post_type' => 'program',
		'posts_per_page' => 6,
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

	$location_faqs = earlystart_get_location_faq_items($location_id);
	?>

	<main class="pt-20">
		<!-- Premium Hero Section -->
		<section class="relative pt-24 pb-32 lg:pt-32 lg:pb-40 bg-stone-900 flex items-center justify-center overflow-hidden">
			<div class="absolute inset-0 bg-stone-900 opacity-80 z-10"></div>
			<div class="absolute inset-0 bg-cover bg-center mix-blend-overlay" style="background-image: url('<?php echo esc_url($hero_image_url); ?>');"></div>
			<div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-stone-900/50 to-stone-900 z-10"></div>

			<div class="relative z-20 text-center text-white px-4 max-w-4xl mx-auto fade-in-up">
				<span class="inline-flex items-center px-4 py-2 bg-rose-500/20 border border-rose-400/30 text-rose-200 rounded-full text-xs font-bold tracking-widest uppercase mb-6 shadow-lg backdrop-blur-md">
					<span class="w-2 h-2 bg-rose-400 rounded-full mr-2 animate-pulse"></span>
					<?php echo esc_html($hero_subtitle); ?>
				</span>
				<span class="inline-flex items-center px-4 py-2 bg-white/10 border border-white/20 text-white rounded-full text-xs font-bold tracking-widest uppercase mb-6 shadow-lg backdrop-blur-md">
					<?php if ($is_clinic_hub): ?>
						<i data-lucide="stethoscope" class="w-4 h-4 mr-2 text-rose-300"></i>
						<?php _e('Clinic Hub', 'earlystart-early-learning'); ?>
					<?php else: ?>
						<i data-lucide="school" class="w-4 h-4 mr-2 text-blue-300"></i>
						<?php _e('Partner Campus', 'earlystart-early-learning'); ?>
					<?php endif; ?>
				</span>
				<h1 class="text-5xl md:text-7xl font-bold mb-6 tracking-tight"><?php echo esc_html($location_name); ?></h1>
				<p class="text-xl text-stone-300 mb-10 flex items-center justify-center gap-2">
					<i data-lucide="map-pin" class="w-5 h-5 text-rose-500"></i>
					<?php echo esc_html($address); ?><?php echo $city ? ', ' . esc_html($city) : ''; ?><?php echo $state ? ', ' . esc_html($state) : ''; ?><?php echo $zip ? ' ' . esc_html($zip) : ''; ?>
				</p>

				<div class="flex flex-wrap justify-center gap-4 fade-in-up delay-200">
					<div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl px-6 py-3 flex items-center gap-3">
						<i data-lucide="activity" class="text-rose-400 w-5 h-5"></i>
						<div class="text-left">
							<span class="block text-sm font-bold"><?php echo esc_html($ages_served); ?></span>
							<span class="block text-xs text-stone-400"><?php _e('Ages Served', 'earlystart-early-learning'); ?></span>
						</div>
					</div>
					<div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl px-6 py-3 flex items-center gap-3">
						<i data-lucide="clock" class="text-orange-400 w-5 h-5"></i>
						<div class="text-left">
							<span class="block text-sm font-bold"><?php echo esc_html($hours); ?></span>
							<span class="block text-xs text-stone-400"><?php _e('Clinic Hours', 'earlystart-early-learning'); ?></span>
						</div>
					</div>
					<div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl px-6 py-3 flex items-center gap-3">
						<i data-lucide="users" class="text-amber-400 w-5 h-5"></i>
						<div class="text-left">
							<span class="block text-sm font-bold"><?php echo esc_html($google_rating); ?> ★</span>
							<span class="block text-xs text-stone-400"><?php _e('Google Rating', 'earlystart-early-learning'); ?></span>
						</div>
					</div>
				</div>
			</div>

			<div class="absolute bottom-0 w-full h-16 bg-stone-50 rounded-t-[100%] z-20 translate-y-1/2 scale-x-110"></div>
		</section>

		<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
			<div class="grid lg:grid-cols-12 gap-12">
				<div class="lg:col-span-8 space-y-24">
					<div class="prose prose-lg text-stone-600 max-w-none">
						<h2 class="text-3xl md:text-4xl font-bold text-stone-900 mb-6"><?php _e('A Second Home for Your Child', 'earlystart-early-learning'); ?></h2>
						<?php echo wp_kses_post(wpautop($description)); ?>
					</div>

					<div>
						<div class="flex items-center gap-3 mb-8">
							<h3 class="text-2xl font-bold text-stone-900"><?php _e('Services at this Location', 'earlystart-early-learning'); ?></h3>
							<div class="flex-1 h-px bg-stone-200 ml-4"></div>
						</div>

						<div class="grid sm:grid-cols-2 gap-4">
							<?php if ($programs_query->have_posts()): ?>
								<?php while ($programs_query->have_posts()):
									$programs_query->the_post();
									$program_title = get_the_title();
									$program_fields = earlystart_get_program_fields();
									$program_excerpt = $program_fields['excerpt'] ?: wp_trim_words(get_the_content(), 14);
									$program_key = strtolower($program_title);
									$icon = 'sparkles';
									$bg = 'bg-stone-100';
									$text = 'text-stone-600';
									$hover = 'group-hover:bg-stone-900';
									if (strpos($program_key, 'aba') !== false) {
										$icon = 'puzzle';
										$bg = 'bg-rose-50';
										$text = 'text-rose-600';
										$hover = 'group-hover:bg-rose-500';
									} elseif (strpos($program_key, 'speech') !== false) {
										$icon = 'message-circle';
										$bg = 'bg-orange-50';
										$text = 'text-orange-600';
										$hover = 'group-hover:bg-orange-500';
									} elseif (strpos($program_key, 'occupational') !== false || strpos($program_key, 'ot') !== false) {
										$icon = 'hand-metal';
										$bg = 'bg-amber-50';
										$text = 'text-amber-600';
										$hover = 'group-hover:bg-amber-500';
									} elseif (strpos($program_key, 'bridge') !== false) {
										$icon = 'school';
										$bg = 'bg-blue-50';
										$text = 'text-blue-600';
										$hover = 'group-hover:bg-blue-500';
									}
									?>
									<div class="flex items-center p-6 bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-md transition-shadow group">
										<div class="w-12 h-12 <?php echo esc_attr($bg); ?> rounded-xl flex items-center justify-center mr-5 <?php echo esc_attr($text); ?> <?php echo esc_attr($hover); ?> group-hover:text-white transition-colors">
											<i data-lucide="<?php echo esc_attr($icon); ?>" class="w-6 h-6"></i>
										</div>
										<div>
											<span class="font-bold text-stone-900 block text-lg"><?php echo esc_html($program_title); ?></span>
											<span class="text-sm text-stone-500"><?php echo esc_html($program_excerpt); ?></span>
										</div>
									</div>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							<?php else: ?>
								<?php
								$default_services = array(
									array('title' => __('ABA Therapy', 'earlystart-early-learning'), 'desc' => __('1:1 Assent-based therapy', 'earlystart-early-learning'), 'icon' => 'puzzle', 'bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'hover' => 'group-hover:bg-rose-500'),
									array('title' => __('Speech Therapy', 'earlystart-early-learning'), 'desc' => __('Articulation & language', 'earlystart-early-learning'), 'icon' => 'message-circle', 'bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'hover' => 'group-hover:bg-orange-500'),
									array('title' => __('Occupational Therapy', 'earlystart-early-learning'), 'desc' => __('Motor skills & sensory', 'earlystart-early-learning'), 'icon' => 'hand-metal', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'hover' => 'group-hover:bg-amber-500'),
									array('title' => __('Bridge Program', 'earlystart-early-learning'), 'desc' => __('School readiness prep', 'earlystart-early-learning'), 'icon' => 'school', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'hover' => 'group-hover:bg-blue-500'),
								);
								foreach ($default_services as $service):
									?>
									<div class="flex items-center p-6 bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-md transition-shadow group">
										<div class="w-12 h-12 <?php echo esc_attr($service['bg']); ?> rounded-xl flex items-center justify-center mr-5 <?php echo esc_attr($service['text']); ?> <?php echo esc_attr($service['hover']); ?> group-hover:text-white transition-colors">
											<i data-lucide="<?php echo esc_attr($service['icon']); ?>" class="w-6 h-6"></i>
										</div>
										<div>
											<span class="font-bold text-stone-900 block text-lg"><?php echo esc_html($service['title']); ?></span>
											<span class="text-sm text-stone-500"><?php echo esc_html($service['desc']); ?></span>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>

					<div>
						<div class="flex items-center gap-3 mb-8">
							<h3 class="text-2xl font-bold text-stone-900"><?php _e('Inside Our Clinic', 'earlystart-early-learning'); ?></h3>
							<div class="flex-1 h-px bg-stone-200 ml-4"></div>
						</div>
						<div class="grid sm:grid-cols-2 gap-6">
							<div class="bg-white rounded-[2rem] p-8 border border-stone-100 shadow-sm hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
								<div class="absolute top-0 right-0 w-32 h-32 bg-rose-50 rounded-bl-[100%] z-0 transition-transform group-hover:scale-110"></div>
								<div class="relative z-10">
									<div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center text-rose-600 mb-5">
										<i data-lucide="activity" class="w-6 h-6"></i>
									</div>
									<h4 class="font-bold text-xl text-stone-900 mb-2"><?php _e('Sensory Gyms', 'earlystart-early-learning'); ?></h4>
									<p class="text-stone-600 text-sm leading-relaxed"><?php _e('Motor rooms equipped for regulation, balance, and gross motor development.', 'earlystart-early-learning'); ?></p>
								</div>
							</div>

							<div class="bg-white rounded-[2rem] p-8 border border-stone-100 shadow-sm hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
								<div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[100%] z-0 transition-transform group-hover:scale-110"></div>
								<div class="relative z-10">
									<div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 mb-5">
										<i data-lucide="school" class="w-6 h-6"></i>
									</div>
									<h4 class="font-bold text-xl text-stone-900 mb-2"><?php _e('Mock Classrooms', 'earlystart-early-learning'); ?></h4>
									<p class="text-stone-600 text-sm leading-relaxed"><?php _e('Spaces designed for school readiness and group instruction routines.', 'earlystart-early-learning'); ?></p>
								</div>
							</div>

							<div class="bg-white rounded-[2rem] p-8 border border-stone-100 shadow-sm hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
								<div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-bl-[100%] z-0 transition-transform group-hover:scale-110"></div>
								<div class="relative z-10">
									<div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 mb-5">
										<i data-lucide="mic" class="w-6 h-6"></i>
									</div>
									<h4 class="font-bold text-xl text-stone-900 mb-2"><?php _e('Therapy Suites', 'earlystart-early-learning'); ?></h4>
									<p class="text-stone-600 text-sm leading-relaxed"><?php _e('Quiet, distraction-free rooms for focused 1:1 sessions.', 'earlystart-early-learning'); ?></p>
								</div>
							</div>

							<div class="bg-white rounded-[2rem] p-8 border border-stone-100 shadow-sm hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
								<div class="absolute top-0 right-0 w-32 h-32 bg-stone-100 rounded-bl-[100%] z-0 transition-transform group-hover:scale-110"></div>
								<div class="relative z-10">
									<div class="w-12 h-12 bg-stone-100 rounded-xl flex items-center justify-center text-stone-600 mb-5">
										<i data-lucide="coffee" class="w-6 h-6"></i>
									</div>
									<h4 class="font-bold text-xl text-stone-900 mb-2"><?php _e('Parent Lounge', 'earlystart-early-learning'); ?></h4>
									<p class="text-stone-600 text-sm leading-relaxed"><?php _e('Comfortable waiting area with observation monitors and Wi-Fi.', 'earlystart-early-learning'); ?></p>
								</div>
							</div>
						</div>
					</div>

					<div>
						<div class="flex items-center justify-between mb-8">
							<h3 class="text-2xl font-bold text-stone-900"><?php _e('Take a Look Inside', 'earlystart-early-learning'); ?></h3>
							<a href="#tour" class="text-rose-600 font-bold text-sm hover:underline flex items-center"><?php _e('Book a Tour', 'earlystart-early-learning'); ?> <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i></a>
						</div>
						<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
							<div class="col-span-2 md:col-span-2 row-span-2 bg-stone-200 rounded-3xl min-h-[300px] relative overflow-hidden group">
								<?php if (!empty($gallery_images[0])): ?>
									<img src="<?php echo esc_url($gallery_images[0]); ?>" alt="<?php echo esc_attr($location_name); ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async">
								<?php else: ?>
									<div class="absolute inset-0 flex items-center justify-center text-stone-400 bg-stone-100">
										<i data-lucide="image" class="w-16 h-16"></i>
									</div>
								<?php endif; ?>
								<div class="absolute inset-0 bg-gradient-to-t from-stone-900/60 to-transparent"></div>
								<div class="absolute bottom-6 left-6 text-white font-bold text-lg"><?php _e('Main Sensory Gym', 'earlystart-early-learning'); ?></div>
							</div>

							<?php
							$labels = array(
								__('Classroom A', 'earlystart-early-learning'),
								__('Speech Suite', 'earlystart-early-learning'),
								__('Playground', 'earlystart-early-learning'),
								__('Parent Lounge & Observation', 'earlystart-early-learning'),
							);
							for ($i = 1; $i <= 4; $i++):
								$is_wide = $i === 4;
								?>
								<div class="<?php echo $is_wide ? 'col-span-2 md:col-span-2 h-48' : 'aspect-square'; ?> bg-stone-200 rounded-3xl relative overflow-hidden group">
									<?php if (!empty($gallery_images[$i])): ?>
										<img src="<?php echo esc_url($gallery_images[$i]); ?>" alt="<?php echo esc_attr($location_name); ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async">
									<?php else: ?>
										<div class="absolute inset-0 flex items-center justify-center text-stone-400 bg-stone-100">
											<i data-lucide="image" class="<?php echo $is_wide ? 'w-12 h-12' : 'w-8 h-8'; ?>"></i>
										</div>
									<?php endif; ?>
									<div class="absolute inset-0 bg-gradient-to-t from-stone-900/60 to-transparent"></div>
									<span class="absolute bottom-4 left-4 text-white font-bold text-sm"><?php echo esc_html($labels[$i - 1]); ?></span>
								</div>
							<?php endfor; ?>
						</div>
					</div>

					<?php if ($director_name || $director_bio || $director_photo): ?>
						<div>
							<div class="flex items-center gap-3 mb-8">
								<h3 class="text-2xl font-bold text-stone-900"><?php _e('Your Local Leadership', 'earlystart-early-learning'); ?></h3>
								<div class="flex-1 h-px bg-stone-200 ml-4"></div>
							</div>
							<div class="bg-white rounded-3xl p-8 border border-stone-100 flex flex-col md:flex-row gap-8 items-center shadow-sm">
								<div class="w-32 h-32 md:w-40 md:h-40 bg-stone-100 rounded-full flex-shrink-0 overflow-hidden border-4 border-rose-50 shadow-inner relative flex items-center justify-center text-stone-400">
									<?php if ($director_photo): ?>
										<img src="<?php echo esc_url($director_photo); ?>" alt="<?php echo esc_attr($director_name ?: $location_name); ?>" class="w-full h-full object-cover">
									<?php else: ?>
										<i data-lucide="user" class="w-16 h-16"></i>
									<?php endif; ?>
								</div>
								<div>
									<h4 class="text-2xl font-bold text-stone-900"><?php echo esc_html($director_name ?: __('Clinical Director', 'earlystart-early-learning')); ?></h4>
									<p class="text-rose-600 font-bold text-sm uppercase tracking-wide mb-3"><?php _e('Clinical Director', 'earlystart-early-learning'); ?></p>
									<p class="text-stone-600 italic mb-4 text-sm leading-relaxed">
										<?php echo esc_html($director_bio ?: __('Our clinical leadership team is dedicated to creating a joyful, evidence-based environment for every child.', 'earlystart-early-learning')); ?>
									</p>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<?php if ($hero_review_text): ?>
						<div class="bg-stone-900 rounded-[3rem] p-10 md:p-14 text-white relative overflow-hidden">
							<div class="absolute top-0 right-0 w-80 h-80 bg-rose-500 rounded-full blur-[80px] opacity-20 -mr-20 -mt-20 pointer-events-none"></div>
							<div class="absolute bottom-0 left-0 w-64 h-64 bg-orange-500 rounded-full blur-[80px] opacity-20 -ml-20 -mb-20 pointer-events-none"></div>
							<div class="relative z-10">
								<div class="flex justify-between items-end mb-10">
									<h3 class="text-3xl font-bold"><?php echo esc_html($location_name); ?> <?php _e('Families Say', 'earlystart-early-learning'); ?></h3>
								</div>
								<div class="grid md:grid-cols-2 gap-8">
									<div class="bg-white/5 backdrop-blur-sm border border-white/10 p-8 rounded-3xl hover:bg-white/10 transition-colors">
										<div class="flex text-amber-400 mb-4">
											<i data-lucide="star" class="w-5 h-5 fill-current"></i>
											<i data-lucide="star" class="w-5 h-5 fill-current"></i>
											<i data-lucide="star" class="w-5 h-5 fill-current"></i>
											<i data-lucide="star" class="w-5 h-5 fill-current"></i>
											<i data-lucide="star" class="w-5 h-5 fill-current"></i>
										</div>
										<p class="text-stone-300 italic mb-6 text-lg leading-relaxed">"<?php echo esc_html($hero_review_text); ?>"</p>
										<div class="flex items-center gap-3">
											<div class="w-10 h-10 bg-rose-500/20 rounded-full flex items-center justify-center text-rose-300 font-bold text-sm">ES</div>
											<span class="font-bold text-white"><?php echo esc_html($hero_review_author); ?></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<div>
						<div class="flex items-center gap-3 mb-8">
							<h3 class="text-2xl font-bold text-stone-900"><?php _e('Clinic FAQs', 'earlystart-early-learning'); ?></h3>
							<div class="flex-1 h-px bg-stone-200 ml-4"></div>
						</div>
						<div class="space-y-4">
							<?php foreach ($location_faqs as $index => $item): ?>
								<div class="bg-white border border-stone-200 rounded-2xl p-6 cursor-pointer hover:border-rose-300 transition-colors" data-faq-item>
									<div class="flex justify-between items-center">
										<h4 class="font-bold text-stone-800"><?php echo esc_html($item['question']); ?></h4>
										<i data-lucide="chevron-down" class="faq-icon w-5 h-5 text-stone-400"></i>
									</div>
									<div class="faq-answer text-stone-600 mt-0">
										<p class="pt-4"><?php echo wp_kses_post($item['answer']); ?></p>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<div class="lg:col-span-4" id="tour">
					<div class="sticky top-24 space-y-6">
						<div class="bg-white rounded-[2rem] p-8 shadow-xl border border-stone-100 relative overflow-hidden">
							<div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-rose-500 via-orange-500 to-amber-500"></div>
							<h3 class="text-2xl font-bold text-stone-900 mb-2 mt-2"><?php _e('Book a Tour', 'earlystart-early-learning'); ?></h3>
							<p class="text-sm text-stone-500 mb-6"><?php _e('Come see the facility and meet our team. No commitment required.', 'earlystart-early-learning'); ?></p>
							<div class="clinical-form">
								<?php echo do_shortcode('[earlystart_tour_form location_id="' . $location_id . '"]'); ?>
							</div>
							<?php if ($tour_booking_link): ?>
								<div class="mt-6 pt-6 border-t border-stone-100 text-center">
									<a href="<?php echo esc_url($tour_booking_link); ?>" class="booking-btn w-full inline-flex items-center justify-center bg-rose-600 text-white py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors shadow-md">
										<?php _e('Book Direct via Calendar', 'earlystart-early-learning'); ?>
									</a>
								</div>
							<?php endif; ?>
						</div>

						<div class="bg-white p-8 rounded-[2rem] shadow-sm border border-stone-100">
							<h3 class="text-lg font-bold text-stone-900 mb-6 border-b border-stone-100 pb-4"><?php _e('Clinic Details', 'earlystart-early-learning'); ?></h3>
							<div class="space-y-6 text-sm">
								<div class="flex items-start group">
									<div class="w-10 h-10 bg-rose-50 rounded-full flex items-center justify-center text-rose-600 mr-4 shrink-0 group-hover:bg-rose-100 transition-colors"><i data-lucide="map-pin" class="w-5 h-5"></i></div>
									<div class="pt-1">
										<p class="text-stone-900 font-medium">
											<?php echo esc_html($address); ?><br><?php echo esc_html($city . ', ' . $state . ' ' . $zip); ?>
										</p>
										<?php if ($map_query): ?>
											<a href="<?php echo esc_url($map_link); ?>" class="text-rose-600 font-bold mt-1 block hover:underline" target="_blank" rel="noopener">
												<?php _e('Get Directions', 'earlystart-early-learning'); ?>
											</a>
										<?php endif; ?>
									</div>
								</div>

								<div class="flex items-start group">
									<div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center text-orange-600 mr-4 shrink-0 group-hover:bg-orange-100 transition-colors"><i data-lucide="clock" class="w-5 h-5"></i></div>
									<div class="pt-1">
										<p class="text-stone-900 font-medium"><?php echo esc_html($hours); ?></p>
									</div>
								</div>

								<div class="flex items-start group">
									<div class="w-10 h-10 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mr-4 shrink-0 group-hover:bg-amber-100 transition-colors"><i data-lucide="phone" class="w-5 h-5"></i></div>
									<div class="pt-1">
										<?php if ($phone): ?>
											<p class="text-stone-900 font-bold text-base"><?php echo esc_html($phone); ?></p>
										<?php endif; ?>
										<?php if ($email): ?>
											<p class="text-stone-500"><?php echo esc_html($email); ?></p>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>

						<div class="bg-white p-8 rounded-[2rem] shadow-sm border border-stone-100">
							<h4 class="font-bold text-stone-900 mb-4 flex items-center">
								<i data-lucide="shield-check" class="w-5 h-5 text-green-500 mr-2"></i>
								<?php _e('Insurance Accepted Here', 'earlystart-early-learning'); ?>
							</h4>
							<div class="flex flex-wrap gap-2 text-xs font-bold text-stone-600">
								<span class="bg-stone-50 px-3 py-1.5 rounded-lg border border-stone-200">BlueCross</span>
								<span class="bg-stone-50 px-3 py-1.5 rounded-lg border border-stone-200">Aetna</span>
								<span class="bg-stone-50 px-3 py-1.5 rounded-lg border border-stone-200">Cigna</span>
								<span class="bg-stone-50 px-3 py-1.5 rounded-lg border border-stone-200">United</span>
								<span class="bg-stone-50 px-3 py-1.5 rounded-lg border border-stone-200">Tricare</span>
								<span class="bg-stone-50 px-3 py-1.5 rounded-lg border border-stone-200">Private Pay</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="h-[500px] w-full bg-stone-200 relative flex items-center justify-center border-t border-stone-200">
			<?php if ($maps_embed): ?>
				<div class="absolute inset-0">
					<?php
					$allowed_tags = array(
						'iframe' => array(
							'src' => true,
							'width' => true,
							'height' => true,
							'frameborder' => true,
							'allowfullscreen' => true,
							'allow' => true,
							'loading' => true,
							'style' => true,
							'class' => true,
							'title' => true,
						),
					);
					echo wp_kses($maps_embed, $allowed_tags);
					?>
				</div>
			<?php else: ?>
				<div class="absolute inset-0 bg-[url('https://upload.wikimedia.org/wikipedia/commons/e/ec/World_map_blank_without_borders.svg')] bg-cover bg-center opacity-40"></div>
			<?php endif; ?>

			<div class="relative z-10 bg-white/95 backdrop-blur-md px-10 py-8 rounded-[2.5rem] shadow-2xl text-center border border-white max-w-sm w-full mx-4 transform hover:-translate-y-2 transition-transform duration-300">
				<div class="w-16 h-16 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4 text-rose-600 shadow-sm">
					<i data-lucide="map-pin" class="w-8 h-8"></i>
				</div>
				<h3 class="font-bold text-xl text-stone-900 mb-2"><?php echo esc_html($location_name); ?></h3>
				<p class="text-stone-500 text-sm mb-6"><?php echo esc_html($address); ?><br><?php echo esc_html($city . ', ' . $state . ' ' . $zip); ?></p>
				<?php if ($map_query): ?>
					<a href="<?php echo esc_url($map_link); ?>" class="inline-flex items-center justify-center w-full bg-stone-900 text-white py-3 rounded-xl font-bold hover:bg-rose-600 transition-colors shadow-md" target="_blank" rel="noopener">
						<?php _e('Open in Google Maps', 'earlystart-early-learning'); ?>
					</a>
				<?php endif; ?>
			</div>
		</section>
	</main>

<?php endwhile; ?>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		var faqItems = document.querySelectorAll('[data-faq-item]');
		faqItems.forEach(function (item) {
			item.addEventListener('click', function () {
				var answer = item.querySelector('.faq-answer');
				var icon = item.querySelector('.faq-icon');
				if (answer) answer.classList.toggle('open');
				if (icon) icon.classList.toggle('rotate');
			});
		});
	});
</script>

<?php get_footer(); ?>
