<?php
/**
 * Locations Archive
 * Displays all locations with search, filtering, and interactive features
 *
 * @package EarlyStart_Early_Start
 */

get_header();

// Get all location regions from taxonomy
$all_regions = get_terms(array(
	'taxonomy' => 'location_region',
	'hide_empty' => true,
));

// Get all published locations
$locations_query = earlystart_cached_query(
	array(
		'post_type' => 'location',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'orderby' => 'title',
		'order' => 'ASC',
	),
	'locations_archive',
	7 * DAY_IN_SECONDS
);

$clinic_hubs = [];
$partner_locations = [];

if ($locations_query->have_posts()) {
	foreach ($locations_query->posts as $location_post) {
		if ('1' === get_post_meta($location_post->ID, 'location_featured', true)) {
			$clinic_hubs[] = $location_post;
		} else {
			$partner_locations[] = $location_post;
		}
	}
}

$clinic_count = count($clinic_hubs);
$partner_count = count($partner_locations);
$hero_badge_text = sprintf(
	/* translators: 1: clinic count label, 2: partner location count label */
	__('%1$s, %2$s', 'earlystart-early-learning'),
	sprintf(
		_n('%d Clinic', '%d Clinics', $clinic_count, 'earlystart-early-learning'),
		$clinic_count
	),
	sprintf(
		_n('%d Partner Location', '%d Partner Locations', $partner_count, 'earlystart-early-learning'),
		$partner_count
	)
);
?>

<main class="pt-20">
	<!-- Hero Section -->
	<section class="relative pt-24 pb-20 lg:pt-32 bg-white overflow-hidden border-b border-stone-50">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
			<span
				class="inline-block px-4 py-2 bg-rose-50 text-rose-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
				<?php echo esc_html($hero_badge_text); ?>
			</span>

			<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
				<?php _e('Clinical Excellence in your', 'earlystart-early-learning'); ?><br>
				<span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
					<?php _e('Neighborhood.', 'earlystart-early-learning'); ?>
				</span>
			</h1>

			<p class="text-xl text-stone-700 max-w-3xl mx-auto leading-relaxed mb-12 fade-in-up">
				<?php _e('Explore our pediatric therapy locations across Georgia. Each clinic is built around evidence-based care, family partnership, and the Chroma Care Model.', 'earlystart-early-learning'); ?>
			</p>

			<!-- Filter Bar -->
			<div
				class="max-w-4xl mx-auto bg-white p-2 rounded-full shadow-2xl border border-stone-100 flex flex-col lg:flex-row gap-2 fade-in-up">
				<div class="relative flex-grow">
					<i data-lucide="search" class="w-5 h-5 absolute left-6 top-1/2 -translate-y-1/2 text-stone-300"></i>
					<input type="text" id="location-search"
						placeholder="<?php esc_attr_e('Search by City or ZIP...', 'earlystart-early-learning'); ?>"
						aria-label="<?php esc_attr_e('Search locations by city, clinic name, or ZIP code', 'earlystart-early-learning'); ?>"
						class="w-full pl-14 pr-6 py-4 rounded-full focus:outline-none text-stone-900 bg-transparent" />
				</div>
				<div class="flex gap-2 p-1 overflow-x-auto no-scrollbar">
					<button type="button" data-region="all" aria-pressed="true"
						class="filter-btn px-8 py-3 rounded-full font-bold text-xs uppercase tracking-widest bg-stone-900 text-white hover:bg-rose-600 transition-all duration-300 whitespace-nowrap">
						<?php _e('All Locations', 'earlystart-early-learning'); ?>
					</button>
					<?php foreach ($all_regions as $region): ?>
						<button type="button" data-region="<?php echo esc_attr($region->slug); ?>" aria-pressed="false"
							class="filter-btn px-8 py-3 rounded-full font-bold text-xs uppercase tracking-widest bg-white text-stone-700 border border-stone-100 hover:bg-stone-50 transition-all duration-300 whitespace-nowrap">
							<?php echo esc_html($region->name); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
			<p id="location-results-status" class="sr-only" aria-live="polite" aria-atomic="true"></p>
		</div>
	</section>

	<!-- Locations Container -->
	<section class="py-24 bg-stone-50">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="mb-16">
				<h2 class="text-3xl font-bold text-stone-900 mb-8 border-b border-stone-200 pb-4">
					<?php _e('Clinic Locations', 'earlystart-early-learning'); ?></h2>
				<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10" id="locations-grid-hubs">
					<?php
					foreach ($clinic_hubs as $post):
						setup_postdata($post);
						$location_id = get_the_ID();
						$location_fields = earlystart_get_location_fields($location_id);
						$location_name = get_the_title();
						$city = $location_fields['city'];
						$zip = $location_fields['zip'];
						$address = earlystart_location_address_line($location_id);

						$location_regions = wp_get_post_terms($location_id, 'location_region');
						$region_term = !empty($location_regions) && !is_wp_error($location_regions) ? $location_regions[0] : null;
						$region_slug = $region_term ? $region_term->slug : 'uncategorized';
						$region_name = $region_term ? $region_term->name : __('Georgia', 'earlystart-early-learning');

						$is_new = get_post_meta($location_id, 'location_new', true);
						$badge_text = $is_new ? __('New Clinic', 'earlystart-early-learning') : __('Now Enrolling', 'earlystart-early-learning');
						?>
						<div class="location-card fade-in-up" data-region="<?php echo esc_attr($region_slug); ?>"
							data-name="<?php echo esc_attr($location_name . ' ' . $city . ' ' . $zip); ?>">
							<a href="<?php the_permalink(); ?>"
								class="group block bg-white rounded-[3rem] overflow-hidden border border-rose-200 hover:border-rose-300 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 h-full flex flex-col relative">
								<div
									class="absolute -top-10 -right-10 w-40 h-40 bg-rose-50 rounded-full blur-[40px] z-0 opacity-50 group-hover:bg-rose-100 transition-colors">
								</div>
								<div class="relative h-64 overflow-hidden z-10">
									<?php if (has_post_thumbnail()): ?>
										<?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-700']); ?>
									<?php else: ?>
										<div class="w-full h-full bg-rose-50 flex items-center justify-center text-rose-300">
											<i data-lucide="building-2" class="w-14 h-14"></i>
										</div>
									<?php endif; ?>

									<div class="absolute top-6 left-6 flex flex-col gap-2">
										<span
											class="bg-rose-600 text-white px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-lg">
											<?php echo esc_html($badge_text); ?>
										</span>
									</div>
								</div>

								<div class="p-10 flex-1 flex flex-col relative z-10">
									<div
										class="flex items-center gap-2 text-rose-700 font-bold text-[10px] uppercase tracking-widest mb-4">
										<i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
										<?php echo esc_html($region_name); ?>
									</div>
									<h2
										class="text-2xl font-bold text-stone-900 mb-4 group-hover:text-rose-700 transition-colors">
										<?php echo esc_html($location_name); ?>
									</h2>
									<p class="text-stone-700 text-sm leading-relaxed mb-10">
										<?php echo esc_html($address); ?><br>
										<?php echo esc_html("$city, GA $zip"); ?>
									</p>

									<div class="mt-auto flex items-center justify-between pt-8 border-t border-stone-50">
										<span class="text-stone-300 text-[10px] font-bold uppercase tracking-widest">
											<?php _e('Explore Clinic Location', 'earlystart-early-learning'); ?>
										</span>
										<div
											class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all shadow-inner">
											<i data-lucide="arrow-right" class="w-5 h-5"></i>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php endforeach;
					wp_reset_postdata(); ?>
				</div>
			</div>

			<div>
				<h2 class="text-3xl font-bold text-stone-900 mb-8 border-b border-stone-200 pb-4">
					<?php _e('Partner Locations', 'earlystart-early-learning'); ?></h2>
				<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10" id="locations-grid-partners">
					<?php foreach ($partner_locations as $post):
						setup_postdata($post);
						$location_id = get_the_ID();
						$location_fields = earlystart_get_location_fields($location_id);
						$location_name = get_the_title();
						$city = $location_fields['city'];
						$zip = $location_fields['zip'];
						$address = earlystart_location_address_line($location_id);

						$location_regions = wp_get_post_terms($location_id, 'location_region');
						$region_term = !empty($location_regions) && !is_wp_error($location_regions) ? $location_regions[0] : null;
						$region_slug = $region_term ? $region_term->slug : 'uncategorized';
						$region_name = $region_term ? $region_term->name : __('Georgia', 'earlystart-early-learning');

						$is_new = get_post_meta($location_id, 'location_new', true);
						$badge_text = $is_new ? __('New Clinic', 'earlystart-early-learning') : __('Now Enrolling', 'earlystart-early-learning');
						?>
						<div class="location-card fade-in-up" data-region="<?php echo esc_attr($region_slug); ?>"
							data-name="<?php echo esc_attr($location_name . ' ' . $city . ' ' . $zip); ?>">
							<a href="<?php the_permalink(); ?>"
								class="group block bg-white rounded-[3rem] overflow-hidden border border-stone-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 h-full flex flex-col">
								<div class="relative h-64 overflow-hidden">
									<?php if (has_post_thumbnail()): ?>
										<?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-700']); ?>
									<?php else: ?>
										<div class="w-full h-full bg-blue-50 flex items-center justify-center text-blue-300">
											<i data-lucide="school" class="w-14 h-14"></i>
										</div>
									<?php endif; ?>

									<div class="absolute top-6 left-6 flex flex-col gap-2">
										<span
											class="bg-blue-600 text-white px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-md">
											<?php echo esc_html($badge_text); ?>
										</span>
									</div>
								</div>

								<div class="p-10 flex-1 flex flex-col">
									<div
										class="flex items-center gap-2 text-stone-500 font-bold text-[10px] uppercase tracking-widest mb-4">
										<i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
										<?php echo esc_html($region_name); ?>
									</div>
									<h2
										class="text-2xl font-bold text-stone-900 mb-4 group-hover:text-blue-700 transition-colors">
										<?php echo esc_html($location_name); ?>
									</h2>
									<p class="text-stone-700 text-sm leading-relaxed mb-10">
										<?php echo esc_html($address); ?><br>
										<?php echo esc_html("$city, GA $zip"); ?>
									</p>

									<div class="mt-auto flex items-center justify-between pt-8 border-t border-stone-50">
										<span class="text-stone-300 text-[10px] font-bold uppercase tracking-widest">
											<?php _e('Explore Partner Location', 'earlystart-early-learning'); ?>
										</span>
										<div
											class="w-12 h-12 bg-stone-50 text-stone-900 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all shadow-inner">
											<i data-lucide="arrow-right" class="w-5 h-5"></i>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php endforeach;
					wp_reset_postdata(); ?>
				</div>
			</div>
			<div id="location-empty-state"
				class="hidden rounded-[2rem] border border-stone-200 bg-white px-8 py-12 text-center shadow-sm"
				aria-hidden="true">
				<h2 class="text-2xl font-bold text-stone-900 mb-3">
					<?php esc_html_e('No matching locations found', 'earlystart-early-learning'); ?>
				</h2>
				<p class="text-stone-700 max-w-xl mx-auto">
					<?php esc_html_e('Try another city, ZIP code, or region to find nearby pediatric therapy support.', 'earlystart-early-learning'); ?>
				</p>
			</div>
		</div>
	</section>

	<!-- Global CTA -->
	<section class="py-24 bg-stone-900 text-white relative overflow-hidden">
		<div
			class="absolute top-0 right-0 w-96 h-96 bg-rose-600/20 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2">
		</div>
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
			<h2 class="text-4xl md:text-5xl font-bold mb-8">
				<?php _e('Not sure where to start?', 'earlystart-early-learning'); ?>
			</h2>
			<p class="text-xl text-stone-300 max-w-2xl mx-auto mb-12">
				<?php _e('Our clinical intake team can help you identify the right clinic and therapy program for your child\'s needs.', 'earlystart-early-learning'); ?>
			</p>
			<div class="flex flex-wrap justify-center gap-6">
				<a href="<?php echo esc_url(earlystart_get_page_link('contact')); ?>"
					class="bg-rose-600 text-white px-10 py-5 rounded-full font-bold hover:bg-white hover:text-stone-900 transition-all shadow-xl">
					<?php _e('Talk to Intake', 'earlystart-early-learning'); ?>
				</a>
				<?php
				$global_phone = trim((string) earlystart_global_phone());
				$global_phone_href = preg_replace('/[^0-9+]/', '', $global_phone);
				?>
				<?php if ($global_phone && $global_phone_href): ?>
					<a href="tel:<?php echo esc_attr($global_phone_href); ?>"
						class="bg-stone-800 text-white px-10 py-5 rounded-full font-bold hover:bg-stone-700 transition-all">
						<i data-lucide="phone" class="w-4 h-4 inline-block mr-2"></i>
						<?php echo esc_html($global_phone); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		const cards = document.querySelectorAll('.location-card');
		const buttons = document.querySelectorAll('.filter-btn');
		const searchInput = document.getElementById('location-search');
		const resultsStatus = document.getElementById('location-results-status');
		const emptyState = document.getElementById('location-empty-state');

		function setActiveButton(region) {
			buttons.forEach(function (btn) {
				const isActive = region === btn.dataset.region;
				btn.classList.toggle('bg-white', !isActive);
				btn.classList.toggle('text-stone-700', !isActive);
				btn.classList.toggle('border', !isActive);
				btn.classList.toggle('border-stone-100', !isActive);
				btn.classList.toggle('bg-stone-900', isActive);
				btn.classList.toggle('text-white', isActive);
				btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
			});
		}

		function setCardVisibility(card, isVisible) {
			card.classList.toggle('hidden', !isVisible);
			card.setAttribute('aria-hidden', isVisible ? 'false' : 'true');
			if (isVisible) {
				card.classList.add('fade-in-up');
			}
		}

		function updateResultState(visibleCount) {
			if (resultsStatus) {
				resultsStatus.textContent = visibleCount === 1 ? '1 location found.' : visibleCount + ' locations found.';
			}

			if (emptyState) {
				const isEmpty = visibleCount === 0;
				emptyState.classList.toggle('hidden', !isEmpty);
				emptyState.setAttribute('aria-hidden', isEmpty ? 'false' : 'true');
			}
		}

		function filterLocations(region) {
			const selectedRegion = region || 'all';
			if (searchInput) {
				searchInput.value = '';
			}

			setActiveButton(selectedRegion);

			let visibleCount = 0;
			cards.forEach(function (card) {
				const isVisible = selectedRegion === 'all' || card.dataset.region === selectedRegion;
				if (isVisible) {
					visibleCount++;
				}
				setCardVisibility(card, isVisible);
			});
			updateResultState(visibleCount);
		}

		buttons.forEach(function (button) {
			button.addEventListener('click', function () {
				filterLocations(button.dataset.region || 'all');
			});
		});

		if (searchInput) {
			searchInput.addEventListener('input', function (e) {
				const term = e.target.value.trim().toLowerCase();
				setActiveButton(term === '' ? 'all' : '');

				let visibleCount = 0;
				cards.forEach(function (card) {
					const text = (card.dataset.name || '').toLowerCase();
					const isVisible = term === '' || text.includes(term);
					if (isVisible) {
						visibleCount++;
					}
					setCardVisibility(card, isVisible);
				});
				updateResultState(visibleCount);
			});
		}

		updateResultState(cards.length);
	});
</script>

<?php get_footer(); ?>
