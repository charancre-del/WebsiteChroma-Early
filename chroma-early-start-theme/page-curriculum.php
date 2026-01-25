<?php
/**
 * Template Name: Our Approach Page
 * Displays the clinical methodology, assent-based philosophy, and data transparency.
 *
 * @package EarlyStart_Early_Start
 */

get_header();

while (have_posts()):
	the_post();
	$page_id = get_the_ID();
	?>

	<main class="pt-20">
		<!-- Hero Section -->
		<section class="relative bg-white pt-24 pb-24 lg:pt-32 overflow-hidden">
			<div
				class="absolute top-0 left-0 -translate-y-1/4 -translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
			</div>
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
				<span
					class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase mb-6 fade-in-up">
					<?php _e('Methodology', 'chroma-early-start'); ?>
				</span>
				<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight fade-in-up">
					<?php _e('Clinical Excellence,', 'chroma-early-start'); ?><br>
					<span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
						<?php _e('Wrapped in Play.', 'chroma-early-start'); ?>
					</span>
				</h1>
				<p class="text-xl text-stone-600 max-w-3xl mx-auto leading-relaxed fade-in-up">
					<?php _e('We don\'t just teach skills; we nurture potential. Our data-driven, assent-based model ensures therapy is effective, engaging, and respectful of every child\'s autonomy.', 'chroma-early-start'); ?>
				</p>
			</div>
		</section>

		<!-- The Chroma Clinical Spectrum -->
		<section class="py-24 bg-stone-50 relative overflow-hidden">
			<div
				class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-orange-50 rounded-full blur-3xl opacity-50">
			</div>

			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
				<div class="text-center mb-16 fade-in-up">
					<h2 class="text-4xl font-bold text-stone-900 mb-4">
						<?php _e('The Clinical Spectrum', 'chroma-early-start'); ?></h2>
					<p class="text-stone-600 max-w-2xl mx-auto text-lg">
						<?php _e('Just as a prism refracts light into color, our model breaks development down into a full spectrum of growth. We meet your child exactly where they are.', 'chroma-early-start'); ?>
					</p>
				</div>

				<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
					<?php
					$spectrum = array(
						array('icon' => 'message-circle', 'title' => 'Communication', 'desc' => 'From functional requests to complex conversation. We give every child a voice, whether vocal or using AAC.', 'color' => 'rose'),
						array('icon' => 'users', 'title' => 'Social Skills', 'desc' => 'Navigating the playground, reading social cues, and building meaningful friendships in a natural setting.', 'color' => 'orange'),
						array('icon' => 'activity', 'title' => 'Behavior Regulation', 'desc' => 'Replacing challenging behaviors with functional alternatives by identifying the "why" behind the behavior.', 'color' => 'amber'),
						array('icon' => 'star', 'title' => 'Independence', 'desc' => 'Self-help skills like toileting, feeding, and dressing. Building confidence to navigate the world.', 'color' => 'rose'),
						array('icon' => 'smile', 'title' => 'Play Skills', 'desc' => 'Expanding interests from rigid routines to imaginative, collaborative play that opens doors to connection.', 'color' => 'orange'),
						array('icon' => 'school', 'title' => 'School Readiness', 'desc' => 'Preparing for the classroom: circle time, following group instructions, and managing transitions.', 'color' => 'amber'),
					);
					foreach ($spectrum as $s): ?>
						<div
							class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-stone-100 hover:shadow-xl transition-all group fade-in-up">
							<div
								class="w-14 h-14 bg-<?php echo $s['color']; ?>-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-<?php echo $s['color']; ?>-500 transition-colors">
								<i data-lucide="<?php echo $s['icon']; ?>"
									class="w-7 h-7 text-<?php echo $s['color']; ?>-500 group-hover:text-white transition-colors"></i>
							</div>
							<h3 class="text-2xl font-bold text-stone-900 mb-3"><?php echo esc_html($s['title']); ?></h3>
							<p class="text-stone-600 leading-relaxed text-sm">
								<?php echo esc_html($s['desc']); ?>
							</p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- Assent-Based Philosophy -->
		<section class="py-24 bg-white overflow-hidden">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid lg:grid-cols-2 gap-16 items-center">
					<div class="fade-in-up">
						<div
							class="inline-block px-4 py-2 bg-stone-900 text-white rounded-full text-xs font-bold tracking-widest uppercase mb-6">
							<?php _e('Our Philosophy', 'chroma-early-start'); ?>
						</div>
						<h2 class="text-4xl font-bold text-stone-900 mb-6">
							<?php _e('Assent-Based & Compassionate', 'chroma-early-start'); ?></h2>
						<div class="prose prose-lg text-stone-600 space-y-6 max-w-none">
							<p>
								<?php _e('At Chroma Early Start, we practice Assent-Based ABA. This means we prioritize your child\'s willingness to participate above all else. If a child shows distress, we stop. We reassess. We do not use force or "extinction" to gain compliance.', 'chroma-early-start'); ?>
							</p>
							<p>
								<?php _e('By building a foundation of safety and trust (pairing), we find that children are eager to learn. Therapy becomes something they run toward, not away from.', 'chroma-early-start'); ?>
							</p>
						</div>
						<div class="mt-8 space-y-4">
							<div class="flex items-center text-stone-800 font-bold">
								<i data-lucide="check-circle" class="w-6 h-6 text-rose-500 mr-3"></i>
								<?php _e('No Forced Compliance', 'chroma-early-start'); ?>
							</div>
							<div class="flex items-center text-stone-800 font-bold">
								<i data-lucide="check-circle" class="w-6 h-6 text-rose-500 mr-3"></i>
								<?php _e('Respect for Bodily Autonomy', 'chroma-early-start'); ?>
							</div>
							<div class="flex items-center text-stone-800 font-bold">
								<i data-lucide="check-circle" class="w-6 h-6 text-rose-500 mr-3"></i>
								<?php _e('Joy is the Metric of Success', 'chroma-early-start'); ?>
							</div>
						</div>
					</div>
					<div class="relative fade-in-up">
						<div class="absolute inset-0 bg-stone-100 rounded-[3rem] transform -rotate-3"></div>
						<div
							class="relative bg-rose-600 rounded-[3rem] h-[550px] flex items-center justify-center overflow-hidden shadow-2xl p-12 text-center text-white">
							<div class="relative z-10">
								<i data-lucide="heart" class="w-24 h-24 mx-auto mb-8 text-rose-200 opacity-50"></i>
								<h3 class="text-3xl font-bold mb-6 italic">
									<?php _e('"Happy, Relaxed, and Engaged"', 'chroma-early-start'); ?></h3>
								<p class="text-rose-100 text-lg leading-relaxed">
									<?php _e('This is the only state in which true learning happens. It is our clinical mandate to create this environment for every child, every single session.', 'chroma-early-start'); ?>
								</p>
							</div>
							<div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-3xl">
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Data & Transparency -->
		<section class="py-24 bg-stone-900 text-white relative overflow-hidden">
			<div class="absolute bottom-0 left-0 w-96 h-96 bg-rose-500/10 rounded-full blur-3xl"></div>

			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
				<div class="lg:flex justify-between items-end mb-16 fade-in-up">
					<div class="lg:w-1/2">
						<h2 class="text-4xl font-bold mb-6"><?php _e('We Measure What Matters', 'chroma-early-start'); ?>
						</h2>
						<p class="text-stone-400 text-xl leading-relaxed">
							<?php _e('While we focus on joy, we are grounded in science. Our clinical team uses advanced digital data collection to track progress in real-time, ensuring transparency.', 'chroma-early-start'); ?>
						</p>
					</div>
					<div class="lg:w-1/3 mt-10 lg:mt-0 flex gap-4">
						<div class="bg-stone-800 p-6 rounded-2xl text-center flex-1 border border-stone-700">
							<span
								class="block text-4xl font-bold text-rose-500 mb-1"><?php _e('Daily', 'chroma-early-start'); ?></span>
							<span
								class="text-xs text-stone-500 uppercase font-bold tracking-widest"><?php _e('Data Entry', 'chroma-early-start'); ?></span>
						</div>
						<div class="bg-stone-800 p-6 rounded-2xl text-center flex-1 border border-stone-700">
							<span
								class="block text-4xl font-bold text-orange-500 mb-1"><?php _e('Weekly', 'chroma-early-start'); ?></span>
							<span
								class="text-xs text-stone-500 uppercase font-bold tracking-widest"><?php _e('Supervision', 'chroma-early-start'); ?></span>
						</div>
					</div>
				</div>

				<div class="grid md:grid-cols-3 gap-8">
					<?php
					$data_features = array(
						array('icon' => 'bar-chart-2', 'title' => 'Real-Time Graphs', 'desc' => 'We don\'t guess mastery; we graph it. See visual evidence of clinical growth over weeks and months.', 'color' => 'rose'),
						array('icon' => 'file-text', 'title' => 'Transparent Reporting', 'desc' => 'No jargon. We provide clear reports on goals and achievements during monthly parent updates.', 'color' => 'orange'),
						array('icon' => 'refresh-cw', 'title' => 'Dynamic Adjustments', 'desc' => 'If a child isn\'t progressing, we analyze the data and pivot teaching strategies immediately.', 'color' => 'amber'),
					);
					foreach ($data_features as $f): ?>
						<div
							class="bg-white/5 backdrop-blur-md p-10 rounded-[2.5rem] border border-white/10 hover:bg-white/10 transition-all fade-in-up">
							<i data-lucide="<?php echo $f['icon']; ?>"
								class="w-12 h-12 text-<?php echo $f['color']; ?>-400 mb-8"></i>
							<h4 class="text-2xl font-bold mb-4"><?php echo esc_html($f['title']); ?></h4>
							<p class="text-stone-400 text-sm leading-relaxed">
								<?php echo esc_html($f['desc']); ?>
							</p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- Parent Partnership -->
		<section class="py-24 bg-rose-50 overflow-hidden">
			<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-in-up">
				<div class="w-20 h-20 bg-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-xl">
					<i data-lucide="users" class="w-10 h-10 text-white"></i>
				</div>
				<h2 class="text-4xl font-bold text-stone-900 mb-6">
					<?php _e('You Are The Expert On Your Child', 'chroma-early-start'); ?></h2>
				<p class="text-2xl text-stone-600 leading-relaxed mb-12 italic font-serif">
					<?php _e('We might be the clinical experts, but you are the lifelong expert on your child. Our partnership is built on mutual respect and shared goals.', 'chroma-early-start'); ?>
				</p>
				<p class="text-lg text-stone-600 leading-relaxed mb-12">
					<?php _e('We provide robust Parent Coaching and open-door policies to ensure that the progress made in the clinic translates to a better quality of life at home.', 'chroma-early-start'); ?>
				</p>
				<a href="<?php echo esc_url(home_url('/contact/')); ?>"
					class="bg-rose-600 text-white px-12 py-5 rounded-full font-bold text-lg hover:bg-rose-500 transition-all shadow-xl inline-flex items-center gap-3">
					<?php _e('Start Your Journey', 'chroma-early-start'); ?>
					<i data-lucide="arrow-right" class="w-6 h-6"></i>
				</a>
			</div>
		</section>

	</main>

<?php
endwhile;
get_footer();


