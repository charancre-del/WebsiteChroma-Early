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
?>

<main class="pt-20">
	<!-- Hero Section -->
	<section class="relative pt-24 pb-20 lg:pt-32 bg-white overflow-hidden border-b border-stone-50">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
			<span
				class="inline-block px-4 py-2 bg-rose-50 text-rose-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
				<?php printf(__('%d Campus Locations', 'earlystart-early-learning'), $locations_query->found_posts); ?>
			</span>

			<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
				<?php _e('Clinical Excellence in your', 'earlystart-early-learning'); ?><br>
				<span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
					<?php _e('Neighborhood.', 'earlystart-early-learning'); ?>
				</span>
			</h1>

			<p class="text-xl text-stone-700 max-w-3xl mx-auto leading-relaxed mb-12 fade-in-up">
				<?php _e('Explore our state-of-the-art campuses across Georgia. Each location features premium clinical environments, the Chroma Care Model, and expert care.', 'earlystart-early-learning'); ?>
			</p>

			<!-- Filter Bar -->
			<div
				class="max-w-4xl mx-auto bg-white p-2 rounded-full shadow-2xl border border-stone-100 flex flex-col lg:flex-row gap-2 fade-in-up">
				<div class="relative flex-grow">
					<i data-lucide="search" class="w-5 h-5 absolute left-6 top-1/2 -translate-y-1/2 text-stone-300"></i>
					<input type="text" id="location-search"
						placeholder="<?php esc_attr_e('Search by City or ZIP...', 'earlystart-early-learning'); ?>"
						class="w-full pl-14 pr-6 py-4 rounded-full focus:outline-none text-stone-900 bg-transparent" />
				</div>
				<div class="flex gap-2 p-1 overflow-x-auto no-scrollbar">
					<button onclick="filterLocations('all')" data-region="all"
						class="filter-btn px-8 py-3 rounded-full font-bold text-xs uppercase tracking-widest bg-stone-900 text-white hover:bg-rose-600 transition-all duration-300 whitespace-nowrap">
						<?php _e('All Locations', 'earlystart-early-learning'); ?>
					</button>
					<?php foreach ($all_regions as $region): ?>
						<button onclick="filterLocations('<?php echo esc_attr($region->slug); ?>')"
							data-region="<?php echo esc_attr($region->slug); ?>"
							class="filter-btn px-8 py-3 rounded-full font-bold text-xs uppercase tracking-widest bg-white text-stone-700 border border-stone-100 hover:bg-stone-50 transition-all duration-300 whitespace-nowrap">
							<?php echo esc_html($region->name); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- Locations Container -->
	<section class="py-24 bg-stone-50">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10" id="locations-grid">
				<?php
				if ($locations_query->have_posts()):
					while ($locations_query->have_posts()):
						$locations_query->the_post();
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
						$badge_text = $is_new ? __('New Campus', 'earlystart-early-learning') : __('Now Enrolling', 'earlystart-early-learning');
						?>

						<div class="location-card fade-in-up" data-region="<?php echo esc_attr($region_slug); ?>"
							data-name="<?php echo esc_attr($location_name . ' ' . $city . ' ' . $zip); ?>">
							<a href="<?php the_permalink(); ?>"
								class="group block bg-white rounded-[3rem] overflow-hidden border border-stone-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 h-full flex flex-col">
								<div class="relative h-64 overflow-hidden">
									<?php if (has_post_thumbnail()): ?>
										<?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-700']); ?>
									<?php else: ?>
										<img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&q=80&fm=webp?w=800&fit=crop&q=80&fm=webp"
											class="w-full h-full object-cover" alt="Campus">
									<?php endif; ?>

									<div class="absolute top-6 left-6 flex flex-col gap-2">
										<span
											class="bg-rose-600 text-white px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest">
											<?php echo esc_html($badge_text); ?>
										</span>
									</div>
								</div>

								<div class="p-10 flex-1 flex flex-col">
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
											<?php _e('Explore Campus', 'earlystart-early-learning'); ?>
										</span>
										<div
											class="w-12 h-12 bg-stone-50 text-stone-900 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all">
											<i data-lucide="arrow-right" class="w-5 h-5"></i>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php endwhile;
					wp_reset_postdata();
				endif; ?>
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
				<?php _e('Our clinical intake team can help you identify the best campus and program for your child\'s unique developmental path.', 'earlystart-early-learning'); ?>
			</p>
			<div class="flex flex-wrap justify-center gap-6">
				<a href="<?php echo esc_url(earlystart_get_page_link('contact')); ?>"
					class="bg-rose-600 text-white px-10 py-5 rounded-full font-bold hover:bg-white hover:text-stone-900 transition-all shadow-xl">
					<?php _e('Talk to Intake', 'earlystart-early-learning'); ?>
				</a>
				<?php $global_phone = earlystart_global_phone(); ?>
				<a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $global_phone)); ?>"
					class="bg-stone-800 text-white px-10 py-5 rounded-full font-bold hover:bg-stone-700 transition-all">
					<i data-lucide="phone" class="w-4 h-4 inline-block mr-2"></i>
					<?php echo esc_html($global_phone ?: '404-555-0123'); ?>
				</a>
			</div>
		</div>
	</section>
</main>

<script>
	function filterLocations(region) {
		const cards = document.querySelectorAll('.location-card');
		const buttons = document.querySelectorAll('.filter-btn');
		const searchInput = document.getElementById('location-search');

		if (region) searchInput.value = '';

		buttons.forEach(btn => {
			if (region === btn.dataset.region) {
				btn.classList.remove('bg-white', 'text-stone-700', 'border', 'border-stone-100');
				btn.classList.add('bg-stone-900', 'text-white');
			} else {
				btn.classList.add('bg-white', 'text-stone-700', 'border', 'border-stone-100');
				btn.classList.remove('bg-stone-900', 'text-white');
			}
		});

		cards.forEach(card => {
			if (region === 'all' || card.dataset.region === region) {
				card.style.display = 'block';
				card.classList.add('fade-in-up');
			} else {
				card.style.display = 'none';
			}
		});
	}

	document.getElementById('location-search').addEventListener('keyup', function (e) {
		const term = e.target.value.toLowerCase();
		const cards = document.querySelectorAll('.location-card');
		const buttons = document.querySelectorAll('.filter-btn');

		buttons.forEach(btn => {
			btn.classList.add('bg-white', 'text-stone-700', 'border', 'border-stone-100');
			btn.classList.remove('bg-stone-900', 'text-white');
		});

		cards.forEach(card => {
			const text = card.dataset.name.toLowerCase();
			card.style.display = text.includes(term) ? 'block' : 'none';
		});
	});
</script>

<?php get_footer(); ?>