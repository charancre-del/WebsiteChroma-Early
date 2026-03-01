<?php
/**
 * The template for displaying the footer.
 *
 * @package EarlyStart_Early_Start
 */
?>
</main>

<footer class="bg-stone-900 text-stone-300 py-24 border-t border-stone-800 mt-auto">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid md:grid-cols-4 gap-12 lg:gap-16">
			<div class="col-span-1 md:col-span-1">
				<div class="flex items-center space-x-3 mb-8">
					<div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-rose-500">
						<i data-lucide="puzzle" class="w-6 h-6"></i>
					</div>
					<span class="text-2xl font-bold text-white tracking-tight">Early Start</span>
				</div>
				<p class="text-sm leading-relaxed mb-8 text-stone-300 max-w-xs">
					<?php _e('Empowering neurodivergent children through play-led, evidence-based therapy. Ages 18mo - 12yrs.', 'earlystart-early-learning'); ?>
				</p>
				<div class="flex space-x-4">
					<?php if (earlystart_global_facebook_url()): ?>
						<a href="<?php echo esc_url(earlystart_global_facebook_url()); ?>" target="_blank" rel="noopener"
							aria-label="<?php esc_attr_e('Follow Early Start on Facebook', 'earlystart-early-learning'); ?>"
							class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-stone-300 hover:bg-rose-600 hover:text-white transition-all"><i
								data-lucide="facebook" class="w-5 h-5"></i></a>
					<?php endif; ?>
					<?php if (earlystart_global_instagram_url()): ?>
						<a href="<?php echo esc_url(earlystart_global_instagram_url()); ?>" target="_blank" rel="noopener"
							aria-label="<?php esc_attr_e('Follow Early Start on Instagram', 'earlystart-early-learning'); ?>"
							class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-stone-300 hover:bg-rose-600 hover:text-white transition-all"><i
								data-lucide="instagram" class="w-5 h-5"></i></a>
					<?php endif; ?>
					<?php if (earlystart_global_linkedin_url()): ?>
						<a href="<?php echo esc_url(earlystart_global_linkedin_url()); ?>" target="_blank" rel="noopener"
							aria-label="<?php esc_attr_e('Follow Early Start on LinkedIn', 'earlystart-early-learning'); ?>"
							class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-stone-300 hover:bg-rose-600 hover:text-white transition-all"><i
								data-lucide="linkedin" class="w-5 h-5"></i></a>
					<?php endif; ?>
				</div>
			</div>


			<div>
				<h3 class="text-white font-bold mb-8 tracking-widest text-xs uppercase">
					<?php _e('Quick Links', 'earlystart-early-learning'); ?>
				</h3>
				<?php
				$location = (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish()) ? 'footer_quick_es' : 'footer_quick';
				if (has_nav_menu($location)) {
					wp_nav_menu(array(
						'theme_location' => $location,
						'container' => false,
						'menu_class' => 'flex flex-col space-y-4 text-sm font-medium',
						'fallback_cb' => false,
						'items_wrap' => '<div class="%2$s">%3$s</div>',
						'depth' => 1,
						'walker' => new earlystart_Footer_Nav_Walker(),
					));
				} else {
					?>
					<div class="flex flex-col space-y-4 text-sm font-medium">
						<a href="<?php echo esc_url(earlystart_get_page_link('about')); ?>"
							class="hover:text-rose-400 transition-colors"><?php _e('About Us', 'earlystart-early-learning'); ?></a>
						<a href="<?php echo esc_url(earlystart_get_page_link('locations')); ?>"
							class="hover:text-rose-400 transition-colors"><?php _e('Find a Clinic', 'earlystart-early-learning'); ?></a>
						<a href="<?php echo esc_url(earlystart_get_page_link('parents')); ?>"
							class="hover:text-rose-400 transition-colors"><?php _e('For Families', 'earlystart-early-learning'); ?></a>
						<a href="<?php echo esc_url(earlystart_get_page_link('careers')); ?>"
							class="hover:text-rose-400 transition-colors"><?php _e('Join Our Team', 'earlystart-early-learning'); ?></a>
						<a href="<?php echo esc_url(earlystart_get_page_link('faq')); ?>"
							class="hover:text-rose-400 transition-colors"><?php _e('FAQs', 'earlystart-early-learning'); ?></a>
					</div>
				<?php } ?>
			</div>

			<div>
				<h3 class="text-white font-bold mb-8 tracking-widest text-xs uppercase">
					<?php _e('Clinics & Programs', 'earlystart-early-learning'); ?>
				</h3>
				<?php
				$location = (class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish()) ? 'footer_programs_es' : 'footer_programs';
				if (has_nav_menu($location)) {
					wp_nav_menu(array(
						'theme_location' => $location,
						'container' => false,
						'menu_class' => 'flex flex-col space-y-4 text-sm font-medium',
						'fallback_cb' => false,
						'items_wrap' => '<div class="%2$s">%3$s</div>',
						'depth' => 1,
						'walker' => new earlystart_Footer_Nav_Walker(),
					));
				} else {
					?>
					<div class="flex flex-col space-y-4 text-sm font-medium">
						<?php
						$footer_programs = new WP_Query([
							'post_type' => 'program',
							'posts_per_page' => 5,
							'orderby' => 'menu_order',
							'order' => 'ASC',
							'post_status' => 'publish'
						]);

						if ($footer_programs->have_posts()) {
							while ($footer_programs->have_posts()) {
								$footer_programs->the_post();
								?>
								<a href="<?php the_permalink(); ?>"
									class="hover:text-rose-400 transition-colors"><?php the_title(); ?></a>
								<?php
							}
							wp_reset_postdata();
						} else {
							?>
							<a href="<?php echo esc_url(earlystart_get_program_link('aba')); ?>"
								class="hover:text-rose-400 transition-colors">ABA Therapy</a>
							<a href="<?php echo esc_url(earlystart_get_program_link('speech')); ?>"
								class="hover:text-rose-400 transition-colors">Speech Therapy</a>
							<a href="<?php echo esc_url(earlystart_get_program_link('ot')); ?>"
								class="hover:text-rose-400 transition-colors">Occupational Therapy</a>
							<a href="<?php echo esc_url(earlystart_get_program_link('bridge')); ?>"
								class="hover:text-rose-400 transition-colors">Preschool Bridge</a>
						<?php } ?>
					</div>
				<?php } ?>
			</div>


			<div>
				<h3 class="text-white font-bold mb-8 tracking-widest text-xs uppercase">
					<?php _e('Contact Admissions', 'earlystart-early-learning'); ?>
				</h3>
				<ul class="space-y-6 text-sm">
					<li class="flex items-start">
						<i data-lucide="phone" class="w-5 h-5 mr-4 text-rose-500 shrink-0"></i>
						<span><?php $phone = earlystart_global_phone();
						echo esc_html($phone ?: 'Not Provided'); ?></span>
					</li>
					<li class="flex items-start">
						<i data-lucide="mail" class="w-5 h-5 mr-4 text-rose-500 shrink-0"></i>
						<span
							class="break-all"><?php $email = earlystart_global_email();
							echo esc_html($email ?: 'hello@earlystart.com'); ?></span>
					</li>
					<li class="flex items-start">
						<i data-lucide="calendar" class="w-5 h-5 mr-4 text-rose-500 shrink-0"></i>
						<span><?php _e('Mon - Fri: 8:00 AM - 5:00 PM', 'earlystart-early-learning'); ?></span>
					</li>
				</ul>
			</div>
		</div>

		<div class="border-t border-stone-800 mt-20 pt-10 flex flex-col md:flex-row justify-between items-center gap-6">
			<?php
			$footer_alternates = function_exists('earlystart_get_alternates') ? earlystart_get_alternates() : array();
			$is_spanish = class_exists('earlystart_Multilingual_Manager') && method_exists('earlystart_Multilingual_Manager', 'is_spanish') && earlystart_Multilingual_Manager::is_spanish();
			?>
			<p class="text-xs text-stone-400">&copy; <?php echo date('Y'); ?>
				<?php _e('Early Start. All rights reserved.', 'earlystart-early-learning'); ?>
			</p>
			<?php if (!empty($footer_alternates['en']) || !empty($footer_alternates['es'])): ?>
				<div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest">
					<?php if (!empty($footer_alternates['en'])): ?>
						<a href="<?php echo esc_url($footer_alternates['en']); ?>"
							class="px-3 py-2 rounded-full border transition-colors <?php echo $is_spanish ? 'border-stone-700 text-stone-400 hover:text-white hover:border-stone-500' : 'border-rose-500 text-white bg-rose-600'; ?>">
							EN
						</a>
					<?php endif; ?>
					<?php if (!empty($footer_alternates['es'])): ?>
						<a href="<?php echo esc_url($footer_alternates['es']); ?>"
							class="px-3 py-2 rounded-full border transition-colors <?php echo $is_spanish ? 'border-blue-500 text-white bg-blue-600' : 'border-stone-700 text-stone-400 hover:text-white hover:border-stone-500'; ?>">
							ES
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="flex space-x-8 text-[10px] font-bold uppercase tracking-widest text-stone-400">
				<a href="<?php echo esc_url(earlystart_get_page_link('privacy-policy')); ?>"
					class="hover:text-stone-200 transition-colors">Privacy Policy</a>
				<a href="<?php echo esc_url(earlystart_get_page_link('terms')); ?>"
					class="hover:text-stone-200 transition-colors">Terms of Use</a>
				<a href="<?php echo esc_url(earlystart_get_page_link('hipaa')); ?>"
					class="hover:text-stone-200 transition-colors">HIPAA Notice</a>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>



</body>

</html>
