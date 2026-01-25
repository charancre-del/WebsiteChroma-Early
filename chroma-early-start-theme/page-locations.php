<?php
/**
 * Template Name: Locations
 * Displays all locations with specialized Clinical Hub and Partner Network views.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

// Get regions
$all_regions = get_terms(array(
	'taxonomy' => 'location_region',
	'hide_empty' => true,
));

// Get featured (Clinical Hubs)
$featured_query = new WP_Query(array(
	'post_type' => 'location',
	'posts_per_page' => -1,
	'meta_query' => array(
		array(
			'key' => 'location_featured',
			'value' => '1',
			'compare' => '='
		)
	),
));

// Get partner locations
$partner_query = new WP_Query(array(
	'post_type' => 'location',
	'posts_per_page' => -1,
	'meta_query' => array(
		'relation' => 'OR',
		array(
			'key' => 'location_featured',
			'compare' => 'NOT EXISTS'
		),
		array(
			'key' => 'location_featured',
			'value' => '0',
			'compare' => '='
		)
	),
));
?>

<main class="pt-20">
	<!-- Hero Section -->
	<section class="relative bg-white pt-24 pb-20 lg:pt-32 overflow-hidden">
		<div
			class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
		</div>

		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
			<span
				class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
				<?php _e('Serving Metro Atlanta', 'chroma-early-start'); ?>
			</span>
			<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
				<?php _e('Therapy Where You', 'chroma-early-start'); ?><br>
				<span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
					<?php _e('Need It Most.', 'chroma-early-start'); ?>
				</span>
			</h1>
			<p class="text-xl text-stone-600 max-w-3xl mx-auto leading-relaxed fade-in-up">
				<?php _e('From our specialized clinics to your living room, and integrated into partner schools. We have a setting that fits your family\'s life.', 'chroma-early-start'); ?>
			</p>
		</div>
	</section>

	<!-- Clinical Hubs Section -->
	<?php if ($featured_query->have_posts()): ?>
		<section class="py-20 bg-stone-50 overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="mb-12">
					<h2 class="text-3xl font-bold text-stone-900 mb-2"><?php _e('Clinical Hubs', 'chroma-early-start'); ?>
					</h2>
					<p class="text-stone-600">
						<?php _e('Our flagship centers for intensive therapy and early intervention.', 'chroma-early-start'); ?>
					</p>
				</div>

				<div class="space-y-16">
					<?php while ($featured_query->have_posts()):
						$featured_query->the_post();
						$location_id = get_the_ID();
						$address = get_post_meta($location_id, 'location_address', true);
						$city = get_post_meta($location_id, 'location_city', true);
						$zip = get_post_meta($location_id, 'location_zip', true);
						$phone = get_post_meta($location_id, 'location_phone', true);
						$hours = get_post_meta($location_id, 'location_hours', true) ?: 'Mon - Fri: 8:00 AM - 6:00 PM';
						$image = get_the_post_thumbnail_url($location_id, 'large') ?: 'https://images.unsplash.com/photo-1544717305-27a734ef202e?w=800&fit=crop';
						?>
						<div class="grid lg:grid-cols-2 gap-12 items-center">
							<div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-stone-100 fade-in-up">
								<div class="h-64 relative">
									<img src="<?php echo esc_url($image); ?>" class="w-full h-full object-cover"
										alt="<?php the_title_attribute(); ?>">
									<div
										class="absolute bottom-6 left-6 bg-white/90 backdrop-blur px-4 py-2 rounded-lg text-sm font-bold text-stone-800 shadow-sm">
										<i data-lucide="building" class="w-4 h-4 inline-block mr-2 text-rose-500"></i>
										<?php _e('Clinical Hub', 'chroma-early-start'); ?>
									</div>
								</div>
								<div class="p-10">
									<h3 class="text-3xl font-bold text-stone-900 mb-4"><?php the_title(); ?></h3>
									<div class="text-stone-600 mb-6 leading-relaxed">
										<?php the_excerpt(); ?>
									</div>
									<div class="space-y-4 mb-8">
										<div class="flex items-start">
											<i data-lucide="map-pin" class="w-5 h-5 text-rose-500 mr-3 mt-1"></i>
											<span
												class="text-stone-700"><?php echo esc_html($address); ?><br><?php echo esc_html($city); ?>,
												GA <?php echo esc_html($zip); ?></span>
										</div>
										<div class="flex items-start">
											<i data-lucide="clock" class="w-5 h-5 text-rose-500 mr-3 mt-1"></i>
											<span class="text-stone-700"><?php echo esc_html($hours); ?></span>
										</div>
									</div>
									<a href="<?php the_permalink(); ?>#tour"
										class="block w-full text-center bg-stone-900 text-white py-4 rounded-xl font-bold hover:bg-rose-600 transition-colors">
										<?php _e('Schedule a Tour', 'chroma-early-start'); ?>
									</a>
								</div>
							</div>

							<!-- Map/Visual decoration for featured -->
							<div
								class="bg-stone-200 rounded-[2.5rem] min-h-[400px] flex items-center justify-center relative overflow-hidden shadow-inner hidden lg:flex fade-in-up">
								<div
									class="absolute inset-0 opacity-20 bg-[url('https://upload.wikimedia.org/wikipedia/commons/e/ec/World_map_blank_without_borders.svg')] bg-cover bg-center">
								</div>
								<div
									class="text-center p-8 bg-white/80 backdrop-blur-md rounded-3xl shadow-lg border border-white/50">
									<i data-lucide="map" class="w-12 h-12 text-rose-500 mx-auto mb-4"></i>
									<h4 class="text-xl font-bold text-stone-900 mb-2">
										<?php _e('Interactive Map', 'chroma-early-start'); ?></h4>
									<p class="text-stone-600 text-sm">
										<?php _e('Google Maps integration available at', 'chroma-early-start'); ?> <a
											href="<?php the_permalink(); ?>"
											class="text-rose-600 font-bold underline"><?php _e('Campus Page', 'chroma-early-start'); ?></a>
									</p>
								</div>
							</div>
						</div>
					<?php endwhile;
					wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- Partner Network Section -->
	<section class="py-24 bg-white">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="text-center mb-16 fade-in-up">
				<span
					class="text-rose-600 font-bold tracking-widest text-sm uppercase mb-3 block"><?php _e('Integrated Therapy', 'chroma-early-start'); ?></span>
				<h2 class="text-4xl font-bold text-stone-900 mb-6">
					<?php _e('Our Partner Network', 'chroma-early-start'); ?></h2>
				<p class="text-stone-600 max-w-2xl mx-auto text-lg leading-relaxed">
					<?php _e('We partner with elite schools to provide on-site therapy. No more driving between school and clinic—we come to the classroom.', 'chroma-early-start'); ?>
				</p>
			</div>

			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php if ($partner_query->have_posts()): ?>
					<?php while ($partner_query->have_posts()):
						$partner_query->the_post();
						$location_id = get_the_ID();
						$address = get_post_meta($location_id, 'location_address', true);
						$regions = wp_get_post_terms($location_id, 'location_region');
						$region_name = !empty($regions) ? $regions[0]->name : 'Metro Atlanta';
						$services = get_post_meta($location_id, 'location_special_programs', true) ?: 'ABA & Speech Available';
						?>
						<div
							class="bg-stone-50 p-8 rounded-3xl border border-stone-100 hover:shadow-xl hover:border-rose-100 transition-all group fade-in-up">
							<div class="flex justify-between items-start mb-4">
								<h4 class="font-bold text-lg text-stone-900 group-hover:text-rose-600 transition-colors">
									<?php the_title(); ?></h4>
								<span
									class="text-[10px] bg-white border border-stone-200 px-3 py-1 rounded-full text-stone-500 font-bold uppercase tracking-wider"><?php echo esc_html($region_name); ?></span>
							</div>
							<p class="text-sm text-stone-600 mb-6 flex items-center">
								<i data-lucide="map-pin" class="w-4 h-4 inline mr-2 text-stone-400"></i>
								<?php echo esc_html($address); ?>
							</p>
							<div class="flex items-center text-xs font-bold text-rose-600 mt-auto">
								<div class="w-6 h-6 bg-rose-100 rounded-full flex items-center justify-center mr-2">
									<i data-lucide="check" class="w-3 h-3 text-rose-600"></i>
								</div>
								<?php echo esc_html($services); ?>
							</div>
						</div>
					<?php endwhile;
					wp_reset_postdata(); ?>
				<?php else: ?>
					<div class="col-span-full text-center py-12">
						<p class="text-stone-500"><?php _e('No partner locations found.', 'chroma-early-start'); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<!-- In-Home Zones Section -->
	<section class="py-24 bg-stone-900 text-white relative overflow-hidden">
		<div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-rose-600 opacity-10 rounded-full blur-3xl"></div>
		<div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-orange-600 opacity-10 rounded-full blur-3xl">
		</div>

		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
			<div class="grid lg:grid-cols-2 gap-16 items-center">
				<div class="fade-in-up">
					<div class="w-16 h-16 bg-rose-900/50 rounded-2xl flex items-center justify-center mb-8">
						<i data-lucide="home" class="w-8 h-8 text-rose-400"></i>
					</div>
					<h2 class="text-4xl font-bold mb-6"><?php _e('In-Home Therapy Zones', 'chroma-early-start'); ?></h2>
					<p class="text-stone-400 text-lg leading-relaxed mb-8">
						<?php _e('For families who prefer therapy in their natural environment, we deploy clinical teams to homes across Metro Atlanta.', 'chroma-early-start'); ?>
					</p>
					<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
						<?php
						$zones = array('Cobb County', 'Cherokee County', 'North Fulton', 'Gwinnett County', 'Dekalb County', 'Forsyth County');
						foreach ($zones as $zone):
							?>
							<div class="flex items-center p-4 bg-white/5 rounded-xl border border-white/10">
								<span
									class="w-2 h-2 bg-rose-500 rounded-full mr-4 shadow-[0_0_8px_rgba(244,63,94,0.6)]"></span>
								<span class="font-bold text-stone-200"><?php echo esc_html($zone); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div
					class="bg-white/10 backdrop-blur-md p-10 rounded-[2.5rem] border border-white/20 text-center fade-in-up">
					<h3 class="text-2xl font-bold mb-4"><?php _e('Check Your Address', 'chroma-early-start'); ?></h3>
					<p class="text-stone-400 mb-8">
						<?php _e('Enter your zip code to see if you are in our home-based service area.', 'chroma-early-start'); ?>
					</p>
					<div class="flex flex-col sm:flex-row gap-4">
						<input type="text" placeholder="<?php esc_attr_e('Zip Code', 'chroma-early-start'); ?>"
							class="flex-grow px-6 py-4 rounded-xl bg-stone-800 border border-stone-700 text-white focus:border-rose-500 focus:ring-1 focus:ring-rose-500 outline-none transition-all">
						<button
							class="bg-rose-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-rose-500 transition-all shadow-lg hover:shadow-rose-900/20 active:scale-95">
							<?php _e('Check', 'chroma-early-start'); ?>
						</button>
					</div>
					<div class="mt-6 text-stone-500 text-sm">
						<p><?php _e('Immediate availability in most areas.', 'chroma-early-start'); ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Final CTA -->
	<section class="py-24 bg-white text-center">
		<div class="max-w-4xl mx-auto px-4">
			<h2 class="text-4xl font-bold text-stone-900 mb-6">
				<?php _e('Find the perfect fit for your family.', 'chroma-early-start'); ?></h2>
			<p class="text-xl text-stone-600 mb-10 leading-relaxed">
				<?php _e('Whether it\'s in our specialized clinical clinic, your family home, or one of our partner schools, we have a spot for you.', 'chroma-early-start'); ?>
			</p>
			<a href="<?php echo esc_url(home_url('/contact/')); ?>"
				class="bg-stone-900 text-white px-12 py-5 rounded-full font-bold text-lg hover:bg-rose-600 transition-all shadow-xl hover:shadow-rose-900/10 transform hover:-translate-y-1 inline-block">
				<?php _e('Contact Admissions', 'chroma-early-start'); ?>
			</a>
		</div>
	</section>

</main>

<?php
get_footer();


