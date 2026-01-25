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
		<section class="relative bg-white pt-24 pb-24 lg:pt-32 overflow-hidden">
			<div
				class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
			</div>

			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
				<span
					class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
					<?php _e('Established 2022', 'chroma-early-start'); ?>
				</span>
				<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
					<?php _e('More than a clinic.', 'chroma-early-start'); ?><br>
					<span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
						<?php _e('A second home.', 'chroma-early-start'); ?>
					</span>
				</h1>
				<p class="text-xl text-stone-600 max-w-3xl mx-auto leading-relaxed fade-in-up">
					<?php _e('We founded Chroma on a simple belief: Clinical therapy should be a perfect blend of rigorous skill development and the comforting warmth of family life.', 'chroma-early-start'); ?>
				</p>
			</div>
		</section>

		<!-- Our Purpose Block -->
		<section class="bg-stone-900 text-white py-24 relative overflow-hidden">
			<div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
			</div>
			<div class="max-w-5xl mx-auto px-4 text-center relative z-10">
				<i data-lucide="sparkles" class="w-12 h-12 text-amber-400 mx-auto mb-8"></i>
				<span
					class="text-sm font-bold text-stone-400 uppercase tracking-widest mb-6 block"><?php _e('Our Purpose', 'chroma-early-start'); ?></span>
				<p class="text-2xl md:text-4xl font-serif italic leading-relaxed fade-in-up">
					<?php _e('"To cultivate a vibrant community of lifelong learners by blending clinical excellence with the nurturing warmth of home, ensuring every child feels seen, valued, and capable."', 'chroma-early-start'); ?>
				</p>
			</div>
		</section>

		<!-- Our Story -->
		<section class="py-24 bg-stone-50 overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid lg:grid-cols-2 gap-16 items-center">
					<div class="fade-in-up">
						<h2 class="text-4xl font-bold text-stone-900 mb-6">
							<?php _e('From One Classroom to a Community', 'chroma-early-start'); ?></h2>
						<div class="prose prose-lg text-stone-600 space-y-6 max-w-none">
							<p>
								<?php _e('Chroma Early Learning Academy began with a mission to redefine "daycare." We didn\'t just want to watch children; we wanted to ignite their potential. As we grew across Metro Atlanta, we saw a vital need for integrated clinical support.', 'chroma-early-start'); ?>
							</p>
							<p>
								<strong><?php _e('Chroma Early Start', 'chroma-early-start'); ?></strong>
								<?php _e('was created to bring that same "second home" philosophy to pediatric therapy. We are locally owned, operated by educators and clinicians, and driven by the success of our families.', 'chroma-early-start'); ?>
							</p>
						</div>

						<div class="grid grid-cols-3 gap-8 mt-12 border-t border-stone-200 pt-10">
							<div>
								<span class="block text-4xl font-bold text-rose-600">19+</span>
								<span
									class="text-xs text-stone-500 uppercase font-bold tracking-widest"><?php _e('Locations', 'chroma-early-start'); ?></span>
							</div>
							<div>
								<span class="block text-4xl font-bold text-rose-600">2k+</span>
								<span
									class="text-xs text-stone-500 uppercase font-bold tracking-widest"><?php _e('Families', 'chroma-early-start'); ?></span>
							</div>
							<div>
								<span class="block text-4xl font-bold text-rose-600">100%</span>
								<span
									class="text-xs text-stone-500 uppercase font-bold tracking-widest"><?php _e('Wholesome', 'chroma-early-start'); ?></span>
							</div>
						</div>
					</div>
					<div class="relative fade-in-up">
						<div class="absolute -inset-4 bg-rose-100 rounded-[3rem] transform rotate-2"></div>
						<div
							class="relative bg-white rounded-[3rem] h-[500px] overflow-hidden shadow-2xl border border-stone-100">
							<img src="https://images.unsplash.com/photo-1544717305-27a734ef202e?w=800&fit=crop"
								class="w-full h-full object-cover" alt="<?php _e('Our Story', 'chroma-early-start'); ?>">
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Timeline Section -->
		<section class="py-24 bg-white border-b border-stone-100 overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-3xl font-bold text-stone-900"><?php _e('Our Journey', 'chroma-early-start'); ?></h2>
				</div>
				<div class="relative">
					<div class="absolute top-1/2 left-0 w-full h-1 bg-stone-100 -translate-y-1/2 hidden md:block"></div>
					<div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10">
						<?php
						$milestones = array(
							array('year' => '2022', 'title' => 'The Beginning', 'desc' => 'Chroma Early Learning Academy opens its first flagship location.', 'color' => 'rose'),
							array('year' => '2023', 'title' => 'Rapid Expansion', 'desc' => 'Growth to 10+ locations identifying child development needs.', 'color' => 'orange'),
							array('year' => '2024', 'title' => 'Chroma Early Start', 'desc' => 'Official launch of our specialized ABA & Therapy division.', 'color' => 'amber'),
							array('year' => 'Today', 'title' => 'A Holistic Network', 'desc' => '19+ locations integrating education and clinical therapy.', 'color' => 'rose'),
						);
						foreach ($milestones as $m): ?>
							<div
								class="bg-white p-8 rounded-3xl border border-stone-100 text-center shadow-sm hover:shadow-md transition-shadow fade-in-up">
								<span
									class="inline-block px-4 py-1 bg-<?php echo $m['color']; ?>-50 text-<?php echo $m['color']; ?>-600 rounded-full text-sm font-bold mb-4">
									<?php echo esc_html($m['year']); ?>
								</span>
								<h4 class="font-bold text-lg mb-2 text-stone-900"><?php echo esc_html($m['title']); ?></h4>
								<p class="text-sm text-stone-500 leading-relaxed"><?php echo esc_html($m['desc']); ?></p>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>

		<!-- Clinicians Section -->
		<section class="py-24 bg-white overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
					<h2 class="text-4xl font-bold text-stone-900 mb-4">
						<?php _e('The Heart of Chroma', 'chroma-early-start'); ?></h2>
					<p class="text-stone-600 text-lg">
						<?php _e('We don\'t just hire technicians; we hire career clinicians. Our team is our most valuable asset, selected for their passion, patience, and professional credentials.', 'chroma-early-start'); ?>
					</p>
				</div>

				<div class="grid md:grid-cols-3 gap-8">
					<?php
					$clinician_traits = array(
						array('icon' => 'award', 'title' => 'Certified & Credentialed', 'desc' => 'Every therapist holds RBT or BCBA certification. We support ongoing growth.'),
						array('icon' => 'shield-check', 'title' => 'Safety First', 'desc' => 'Rigorous background checks. All staff are certified in CPR and Safety Care.'),
						array('icon' => 'trending-up', 'title' => 'Continuous Growth', 'desc' => 'Our clinicians participate in 20+ hours of annual professional training.'),
					);
					foreach ($clinician_traits as $trait): ?>
						<div
							class="bg-stone-50 p-10 rounded-[2.5rem] border border-stone-100 hover:shadow-lg transition-all fade-in-up">
							<div
								class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6 text-rose-500">
								<i data-lucide="<?php echo $trait['icon']; ?>" class="w-7 h-7"></i>
							</div>
							<h3 class="font-bold text-stone-900 text-xl mb-3"><?php echo esc_html($trait['title']); ?></h3>
							<p class="text-stone-600 text-sm leading-relaxed"><?php echo esc_html($trait['desc']); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- Pillars Section -->
		<section class="py-24 bg-rose-600 text-white relative overflow-hidden">
			<div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl"></div>
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-4xl font-bold mb-4"><?php _e('Our Non-Negotiables', 'chroma-early-start'); ?></h2>
					<p class="text-rose-100 max-w-2xl mx-auto text-lg">
						<?php _e('These four pillars guide every decision we make.', 'chroma-early-start'); ?></p>
				</div>

				<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
					<?php
					$pillars = array(
						array('icon' => 'smile', 'title' => 'Unconditional Joy', 'desc' => 'Childhood should be magical. We prioritize laughter and warmth.'),
						array('icon' => 'shield', 'title' => 'Radical Safety', 'desc' => 'Emotional safety is the goal. Kids learn best when they feel secure.'),
						array('icon' => 'star', 'title' => 'Clinical Excellence', 'desc' => 'Using our data-driven model, we deliver therapy that feels like play.'),
						array('icon' => 'users', 'title' => 'Open Partnership', 'desc' => 'Parents are partners. We maintain open doors and transparent data.'),
					);
					foreach ($pillars as $p): ?>
						<div
							class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-rose-400/30 hover:bg-white/20 transition-all fade-in-up">
							<i data-lucide="<?php echo $p['icon']; ?>" class="w-10 h-10 text-rose-200 mb-4"></i>
							<h3 class="text-xl font-bold mb-2"><?php echo esc_html($p['title']); ?></h3>
							<p class="text-sm text-rose-100 leading-relaxed"><?php echo esc_html($p['desc']); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- Leadership Section -->
		<section class="py-24 bg-stone-50">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-4xl font-bold text-stone-900 mb-4">
						<?php _e('Led By Educators & Clinicians', 'chroma-early-start'); ?></h2>
					<p class="text-stone-600 max-w-2xl mx-auto text-lg">
						<?php _e('Our leadership team combines decades of experience in clinical therapy and education.', 'chroma-early-start'); ?>
					</p>
				</div>

				<div class="grid md:grid-cols-3 gap-8">
					<?php
					$leaders = array(
						array('name' => 'Charan Singh', 'role' => 'Founder & CEO', 'bio' => 'Charan leads Chroma\'s vision of high-quality, accessible care for all families across Georgia.'),
						array('name' => 'Kimberly Williams', 'role' => 'Chief Operating Officer', 'bio' => 'Bringing 30+ years of experience, Kimberly ensures our standards never compromise on care.'),
						array('name' => 'Christi Wier', 'role' => 'VP Business Development', 'bio' => 'Christi builds community partnerships to ensure our programs meet evolving family needs.'),
					);
					foreach ($leaders as $l): ?>
						<div
							class="bg-white rounded-[2.5rem] p-8 shadow-md border border-stone-100 group hover:-translate-y-1 transition-all fade-in-up">
							<div class="bg-stone-100 rounded-3xl aspect-square mb-8 flex items-center justify-center">
								<i data-lucide="user" class="w-20 h-20 text-stone-300"></i>
							</div>
							<h3 class="text-2xl font-bold text-stone-900 mb-1"><?php echo esc_html($l['name']); ?></h3>
							<p class="text-rose-600 font-bold text-sm uppercase tracking-widest mb-4">
								<?php echo esc_html($l['role']); ?></p>
							<p class="text-stone-600 text-sm leading-relaxed"><?php echo esc_html($l['bio']); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- Community Section -->
		<section class="py-24 bg-white overflow-hidden">
			<div class="max-w-6xl mx-auto px-4">
				<div
					class="bg-stone-900 rounded-[3rem] p-12 lg:p-24 text-center text-white relative overflow-hidden fade-in-up">
					<div
						class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-rose-500 to-transparent">
					</div>
					<div class="relative z-10">
						<span
							class="text-rose-400 font-bold tracking-widest text-sm uppercase mb-4 block"><?php _e('Community Impact', 'chroma-early-start'); ?></span>
						<h2 class="text-4xl md:text-5xl font-bold mb-8">
							<?php _e('Foundations For Learning Inc.', 'chroma-early-start'); ?></h2>
						<p class="text-xl text-stone-300 leading-relaxed mb-12 max-w-3xl mx-auto">
							<?php _e('Through our non-profit arm, we work to ensure quality early intervention resources are accessible to every child in our Georgia communities.', 'chroma-early-start'); ?>
						</p>
						<div class="flex flex-wrap justify-center gap-4">
							<span
								class="px-8 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-sm font-bold"><?php _e('Scholarships', 'chroma-early-start'); ?></span>
							<span
								class="px-8 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-sm font-bold"><?php _e('Teacher Grants', 'chroma-early-start'); ?></span>
							<span
								class="px-8 py-3 bg-white/10 backdrop-blur-md rounded-full border border-white/20 text-sm font-bold"><?php _e('Community Outreach', 'chroma-early-start'); ?></span>
						</div>
					</div>
				</div>
			</div>
		</section>

	</main>

<?php
endwhile;
get_footer();


