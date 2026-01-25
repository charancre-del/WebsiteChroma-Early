<?php
/**
 * Template Name: Careers Page
 * Displays the mission, benefits, and dynamic job board for clinicians.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

while (have_posts()):
	the_post();
	$page_id = get_the_ID();

	// Fetch jobs from API or internal logic
	$jobs = function_exists('earlystart_get_careers') ? earlystart_get_careers() : array();
	?>

	<main class="pt-20">
		<!-- Hero Section -->
		<section class="relative bg-white pt-24 pb-20 lg:pt-32">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
				<span
					class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
					<?php _e('Join Our Team', 'chroma-early-start'); ?>
				</span>
				<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
					<?php _e('Do Your Best Work', 'chroma-early-start'); ?><br>
					<span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
						<?php _e('With Us.', 'chroma-early-start'); ?>
					</span>
				</h1>
				<p class="text-xl text-stone-600 max-w-3xl mx-auto leading-relaxed fade-in-up">
					<?php _e('We are building a workplace that prioritizes clinician burnout prevention, professional growth, and assent-based care. If you love kids but hate "the grind," you belong here.', 'chroma-early-start'); ?>
				</p>
				<div class="mt-10 fade-in-up">
					<a href="#openings"
						class="bg-stone-900 text-white px-8 py-4 rounded-full font-bold hover:bg-stone-800 transition-all shadow-lg inline-flex items-center">
						<?php _e('View Open Positions', 'chroma-early-start'); ?>
						<i data-lucide="arrow-down" class="ml-2 w-5 h-5"></i>
					</a>
				</div>
			</div>
		</section>

		<!-- Benefits & Culture -->
		<section class="py-24 bg-stone-50">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-3xl font-bold text-stone-900 mb-4">
						<?php _e('Why Clinicians Choose Chroma', 'chroma-early-start'); ?></h2>
					<p class="text-stone-600">
						<?php _e('We take care of our team so they can take care of our families.', 'chroma-early-start'); ?>
					</p>
				</div>

				<div class="grid md:grid-cols-3 gap-8">
					<?php
					$benefits = array(
						array('icon' => 'scale', 'title' => 'Manageable Caseloads', 'desc' => 'We cap our BCBA caseloads below industry standards to ensure you have time for quality supervision and parent training.', 'color' => 'rose'),
						array('icon' => 'graduation-cap', 'title' => 'Free Supervision', 'desc' => 'For RBTs pursuing their BCBA, we offer free, high-quality restricted and unrestricted supervision hours with mentorship.', 'color' => 'orange'),
						array('icon' => 'heart-handshake', 'title' => 'Full Benefits', 'desc' => 'Competitive salary, health/dental/vision insurance, 401k matching, and generous paid time off for full-time staff.', 'color' => 'amber'),
					);
					foreach ($benefits as $b): ?>
						<div
							class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-stone-100 group hover:shadow-lg transition-all fade-in-up">
							<div
								class="w-14 h-14 bg-<?php echo $b['color']; ?>-100 rounded-2xl flex items-center justify-center mb-6 text-<?php echo $b['color']; ?>-600 group-hover:scale-110 transition-transform">
								<i data-lucide="<?php echo $b['icon']; ?>" class="w-7 h-7"></i>
							</div>
							<h3 class="text-xl font-bold text-stone-900 mb-4"><?php echo esc_html($b['title']); ?></h3>
							<p class="text-stone-600 leading-relaxed text-sm">
								<?php echo esc_html($b['desc']); ?>
							</p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- Current Openings -->
		<section id="openings" class="py-24 bg-white">
			<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex justify-between items-end mb-12 fade-in-up">
					<div>
						<span
							class="text-rose-600 font-bold tracking-widest text-sm uppercase mb-2 block"><?php _e("We're Hiring", 'chroma-early-start'); ?></span>
						<h2 class="text-3xl font-bold text-stone-900">
							<?php _e('Current Opportunities', 'chroma-early-start'); ?></h2>
					</div>
					<div class="hidden md:block">
						<span
							class="text-sm text-stone-500"><?php _e('Chroma Early Start Regional Openings', 'chroma-early-start'); ?></span>
					</div>
				</div>

				<div class="space-y-6">
					<?php if (!empty($jobs)): ?>
						<?php foreach ($jobs as $job): ?>
							<div
								class="border border-stone-200 rounded-[2rem] p-8 hover:shadow-lg transition-shadow bg-stone-50/50 fade-in-up relative group">
								<div class="md:flex justify-between items-start">
									<div>
										<div class="flex items-center gap-3 mb-2">
											<h3 class="text-2xl font-bold text-stone-900"><?php echo esc_html($job['title']); ?>
											</h3>
											<span
												class="bg-rose-100 text-rose-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest"><?php echo esc_html($job['type'] ?: 'Full Time'); ?></span>
										</div>
										<p class="text-stone-600 mb-6 max-w-2xl text-sm leading-relaxed">
											<?php echo esc_html($job['description'] ?: 'Join our clinical team and make a difference in the lives of early learners. We prioritize evidence-based care and staff support.'); ?>
										</p>
										<div class="flex flex-wrap gap-4 text-xs text-stone-500 font-bold uppercase tracking-wider">
											<span class="flex items-center"><i data-lucide="map-pin"
													class="w-4 h-4 mr-2 text-rose-400"></i>
												<?php echo esc_html($job['location'] ?: 'Regional Clinic'); ?></span>
											<span class="flex items-center"><i data-lucide="dollar-sign"
													class="w-4 h-4 mr-2 text-rose-400"></i>
												<?php _e('Competitive Package', 'chroma-early-start'); ?></span>
										</div>
									</div>
									<a href="<?php echo esc_url($job['url']); ?>"
										class="job-modal-trigger mt-6 md:mt-0 bg-stone-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-rose-600 transition-colors shadow-sm inline-block whitespace-nowrap">
										<?php _e('Apply Now', 'chroma-early-start'); ?>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="text-center py-20 border-2 border-dashed border-stone-100 rounded-[2rem] fade-in-up">
							<i data-lucide="search" class="w-12 h-12 text-stone-200 mx-auto mb-4"></i>
							<p class="text-stone-500">
								<?php _e('No current openings found. Please check back soon!', 'chroma-early-start'); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<!-- Training Program CTA -->
		<section class="py-24 bg-rose-600 text-white relative overflow-hidden">
			<div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
			</div>
			<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center fade-in-up">
				<div
					class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center mx-auto mb-8 border border-white/20">
					<i data-lucide="sparkles" class="w-10 h-10 text-amber-300"></i>
				</div>
				<h2 class="text-3xl md:text-5xl font-bold mb-6">
					<?php _e('New to the field? We\'ll train you.', 'chroma-early-start'); ?></h2>
				<p class="text-rose-100 text-xl mb-12 max-w-2xl mx-auto leading-relaxed">
					<?php _e('We offer a paid RBT Training Program for compassionate individuals. We cover your 40-hour coursework, background checks, and exam fees.', 'chroma-early-start'); ?>
				</p>
				<a href="<?php echo esc_url(home_url('/contact/')); ?>"
					class="bg-white text-rose-600 px-12 py-5 rounded-full font-bold text-lg hover:bg-rose-50 transition-colors shadow-2xl active:scale-95 inline-block">
					<?php _e('Apply for Training', 'chroma-early-start'); ?>
				</a>
			</div>
		</section>

	</main>

	<!-- Job Application Modal (Ported from existing) -->
	<div id="chroma-job-modal" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
		<div class="absolute inset-0 bg-stone-900/80 backdrop-blur-sm transition-opacity" id="chroma-job-backdrop"></div>
		<div
			class="absolute inset-4 md:inset-10 bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col animate-fade-in-up">
			<div class="bg-stone-50 border-b border-stone-200 px-8 py-4 flex items-center justify-between flex-shrink-0">
				<h3 class="text-xl font-bold text-stone-900"><?php _e('Apply for Position', 'chroma-early-start'); ?></h3>
				<button id="chroma-job-close"
					class="w-10 h-10 rounded-xl bg-white border border-stone-200 flex items-center justify-center text-stone-400 hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm">
					<i data-lucide="x" class="w-6 h-6"></i>
				</button>
			</div>
			<div class="flex-grow relative bg-white">
				<div id="chroma-job-loader" class="absolute inset-0 flex items-center justify-center bg-white z-10">
					<div class="w-12 h-12 border-4 border-rose-100 border-t-rose-600 rounded-full animate-spin"></div>
				</div>
				<iframe id="chroma-job-frame" src="" class="w-full h-full border-0" title="Job Application"></iframe>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const modal = document.getElementById('chroma-job-modal');
			const backdrop = document.getElementById('chroma-job-backdrop');
			const closeBtn = document.getElementById('chroma-job-close');
			const iframe = document.getElementById('chroma-job-frame');
			const loader = document.getElementById('chroma-job-loader');

			function openModal(url) {
				modal.classList.remove('hidden');
				document.body.style.overflow = 'hidden';
				loader.classList.remove('hidden');
				iframe.src = url;
				iframe.onload = () => loader.classList.add('hidden');
			}

			function closeModal() {
				modal.classList.add('hidden');
				document.body.style.overflow = '';
				iframe.src = '';
			}

			document.querySelectorAll('.job-modal-trigger').forEach(trigger => {
				trigger.addEventListener('click', function (e) {
					const url = this.getAttribute('href');
					if (url && url.startsWith('http')) {
						e.preventDefault();
						openModal(url);
					}
				});
			});

			if (closeBtn) closeBtn.addEventListener('click', closeModal);
			if (backdrop) backdrop.addEventListener('click', closeModal);
		});
	</script>

	<?php
endwhile;
get_footer();

