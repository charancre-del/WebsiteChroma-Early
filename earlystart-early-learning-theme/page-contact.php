<?php
/**
 * Template Name: Contact Page
 * High-conversion contact hub with routing and dynamic forms.
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
		<section class="relative bg-white pt-24 pb-20 lg:pt-32 overflow-hidden border-b border-stone-100">
			<div
				class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[600px] h-[600px] bg-rose-50 rounded-full blur-3xl opacity-50">
			</div>
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
				<div class="text-center max-w-3xl mx-auto mb-16 fade-in-up">
					<span
						class="inline-block px-4 py-2 bg-rose-50 text-rose-700 rounded-full text-xs font-bold tracking-widest uppercase mb-6">
						<?php _e('Connect with Us', 'earlystart-early-learning'); ?>
					</span>
					<h1 class="text-5xl md:text-7xl font-bold text-stone-900 mb-8 leading-tight">
						<?php _e('How can we', 'earlystart-early-learning'); ?><br>
						<span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-600 to-orange-500">
							<?php _e('Support you?', 'earlystart-early-learning'); ?>
						</span>
					</h1>
					<p class="text-xl text-stone-700 max-w-2xl mx-auto leading-relaxed">
						<?php _e('Whether you are a family looking for care, a clinician seeking a career, or a provider wanting to refer, we are here to help.', 'earlystart-early-learning'); ?>
					</p>
				</div>

				<!-- Routing Grid -->
				<div class="grid md:grid-cols-3 gap-8">
					<?php
					$routes = array(
						array('icon' => 'baby', 'title' => 'For Families', 'desc' => 'Find a clinic near you and schedule a tour for ABA, Speech, or OT.', 'link' => earlystart_get_page_link('locations'), 'label' => 'Find a Clinic', 'color' => 'rose'),
						array('icon' => 'briefcase', 'title' => 'For Clinicians', 'desc' => 'View our open positions and learn about our culture of burnout prevention.', 'link' => earlystart_get_page_link('careers'), 'label' => 'View Careers', 'color' => 'orange'),
						array('icon' => 'heart-pulse', 'title' => 'For Providers', 'desc' => 'Easily refer a client to our clinical team for a comprehensive assessment.', 'link' => earlystart_get_page_link('contact') . '#general-form', 'label' => 'Refer a Client', 'color' => 'amber'),
					);
					foreach ($routes as $r): ?>
						<div
							class="bg-stone-50 p-10 rounded-[2.5rem] border border-stone-100 text-center hover:shadow-lg transition-all group fade-in-up">
							<div
								class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 text-<?php echo $r['color']; ?>-500 shadow-sm group-hover:scale-110 transition-transform">
								<i data-lucide="<?php echo $r['icon']; ?>" class="w-8 h-8"></i>
							</div>
							<h3 class="text-2xl font-bold text-stone-900 mb-4"><?php echo esc_html($r['title']); ?></h3>
							<p class="text-stone-700 text-sm leading-relaxed mb-8"><?php echo esc_html($r['desc']); ?></p>
							<a href="<?php echo esc_url($r['link']); ?>"
								class="inline-block w-full py-4 bg-white border border-stone-200 text-stone-900 font-bold rounded-xl hover:border-<?php echo $r['color']; ?>-200 hover:text-<?php echo $r['color']; ?>-600 transition-all text-sm">
								<?php echo esc_html($r['label']); ?>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- Detailed Contact & Form -->
		<section id="general-form" class="py-24 bg-white">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="grid lg:grid-cols-2 gap-20">
					<div class="fade-in-up">
						<h2 class="text-4xl font-bold text-stone-900 mb-8">
							<?php _e('Get Started Today', 'earlystart-early-learning'); ?>
						</h2>
						<p class="text-xl text-stone-700 mb-12 leading-relaxed">
							<?php _e('Ready to learn more? Fill out the form, and our admissions team will reach out within 24 hours to guide you through the process.', 'earlystart-early-learning'); ?>
						</p>

						<div class="space-y-8">
							<?php
							$contacts = array(
								array('icon' => 'phone', 'title' => 'Call Us', 'value' => earlystart_global_phone()),
								array('icon' => 'mail', 'title' => 'Email Us', 'value' => earlystart_global_email()),
								array('icon' => 'map-pin', 'title' => 'Main Office', 'value' => earlystart_global_full_address()),
							);
							foreach ($contacts as $c): ?>
								<div class="flex items-center">
									<div
										class="w-12 h-12 bg-stone-50 rounded-full flex items-center justify-center mr-6 text-stone-900 shadow-sm">
										<i data-lucide="<?php echo $c['icon']; ?>" class="w-5 h-5"></i>
									</div>
									<div>
										<h4 class="font-bold text-stone-900"><?php echo esc_html($c['title']); ?></h4>
										<p class="text-stone-700"><?php echo esc_html($c['value']); ?></p>
									</div>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="mt-16 pt-12 border-t border-stone-100">
							<h4 class="text-stone-900 font-bold mb-6">
								<?php _e('Departmental Emails', 'earlystart-early-learning'); ?>
							</h4>
							<div class="grid sm:grid-cols-2 gap-4 text-sm">
								<a href="mailto:admissions@earlystarttherapy.com"
									class="text-stone-700 hover:text-rose-700 transition-colors"><strong><?php _e('Admissions:', 'earlystart-early-learning'); ?></strong>
									admissions@...</a>
								<a href="mailto:careers@earlystarttherapy.com"
									class="text-stone-700 hover:text-rose-700 transition-colors"><strong><?php _e('Careers:', 'earlystart-early-learning'); ?></strong>
									careers@...</a>
								<a href="mailto:billing@earlystarttherapy.com"
									class="text-stone-700 hover:text-rose-700 transition-colors"><strong><?php _e('Billing:', 'earlystart-early-learning'); ?></strong>
									billing@...</a>
								<a href="mailto:media@earlystarttherapy.com"
									class="text-stone-700 hover:text-rose-700 transition-colors"><strong><?php _e('Media:', 'earlystart-early-learning'); ?></strong>
									media@...</a>
							</div>
						</div>
					</div>

					<div class="fade-in-up">
						<div class="bg-white p-10 rounded-[3rem] shadow-2xl border border-stone-100 relative">
							<div class="absolute -top-6 -right-6 w-24 h-24 bg-rose-50 rounded-full blur-2xl opacity-60">
							</div>
							<h3 class="text-2xl font-bold text-stone-900 mb-8">
								<?php _e('Send a Message', 'earlystart-early-learning'); ?>
							</h3>
							<?php
							// If the contact form shortcode exists, use it. Otherwise, fallback to a placeholder style.
							if (shortcode_exists('earlystart_contact_form')) {
								echo do_shortcode('[earlystart_contact_form]');
							} else {
								?>
								<form class="space-y-6"
									onsubmit="event.preventDefault(); alert('Thank you! We will contact you shortly.');">
									<div>
										<label
											class="block text-sm font-bold text-stone-700 mb-2"><?php _e('Your Name', 'earlystart-early-learning'); ?></label>
										<input type="text"
											class="w-full px-5 py-4 rounded-2xl border border-stone-200 focus:border-rose-500 outline-none bg-stone-50/30 transition-all"
											placeholder="<?php esc_attr_e('Jane Doe', 'earlystart-early-learning'); ?>" required>
									</div>
									<div class="grid grid-cols-2 gap-4">
										<div>
											<label
												class="block text-sm font-bold text-stone-700 mb-2"><?php _e('Phone', 'earlystart-early-learning'); ?></label>
											<input type="tel"
												class="w-full px-5 py-4 rounded-2xl border border-stone-200 focus:border-rose-500 outline-none bg-stone-50/30 transition-all"
												placeholder="(555) 555-5555" required>
										</div>
										<div>
											<label
												class="block text-sm font-bold text-stone-700 mb-2"><?php _e('Email', 'earlystart-early-learning'); ?></label>
											<input type="email"
												class="w-full px-5 py-4 rounded-2xl border border-stone-200 focus:border-rose-500 outline-none bg-stone-50/30 transition-all"
												placeholder="jane@example.com" required>
										</div>
									</div>
									<div>
										<label
											class="block text-sm font-bold text-stone-700 mb-2"><?php _e('Interest', 'earlystart-early-learning'); ?></label>
										<select
											class="w-full px-5 py-4 rounded-2xl border border-stone-200 focus:border-rose-500 outline-none bg-white transition-all text-stone-700">
											<option><?php _e('Inquiring for Child', 'earlystart-early-learning'); ?></option>
											<option><?php _e('Clinical Employment', 'earlystart-early-learning'); ?></option>
											<option><?php _e('Provider Referral', 'earlystart-early-learning'); ?></option>
											<option><?php _e('Media/Press', 'earlystart-early-learning'); ?></option>
										</select>
									</div>
									<div>
										<label
											class="block text-sm font-bold text-stone-700 mb-2"><?php _e('Message', 'earlystart-early-learning'); ?></label>
										<textarea rows="4"
											class="w-full px-5 py-4 rounded-2xl border border-stone-200 focus:border-rose-500 outline-none bg-stone-50/30 transition-all resize-none"
											placeholder="<?php esc_attr_e('Tell us a little about how we can help...', 'earlystart-early-learning'); ?>"></textarea>
									</div>
									<button
										class="w-full bg-stone-900 text-white font-bold py-5 rounded-2xl hover:bg-rose-600 transition-all shadow-xl active:scale-95">
										<?php _e('Request Consultation', 'earlystart-early-learning'); ?>
									</button>
								</form>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Secondary Map / Locations Hint -->
		<section class="py-24 bg-stone-50 border-t border-stone-100">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-in-up">
				<h2 class="text-3xl font-bold text-stone-900 mb-6">
					<?php _e('Visit one of our 10+ clinics.', 'earlystart-early-learning'); ?>
				</h2>
				<p class="text-stone-700 mb-10 max-w-2xl mx-auto">
					<?php _e('With specialized therapy centers across the region, there is likely a Early Start clinic in your community.', 'earlystart-early-learning'); ?>
				</p>
				<a href="<?php echo esc_url(earlystart_get_page_link('locations')); ?>"
					class="inline-flex items-center text-rose-700 font-bold hover:underline gap-2">
					<?php _e('View Location Directory', 'earlystart-early-learning'); ?>
					<i data-lucide="arrow-right" class="w-5 h-5"></i>
				</a>
			</div>
		</section>
	</main>

	<?php
endwhile;
get_footer();

