<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php // Canonical URL is handled by Yoast SEO and class-canonical-enforcer.php via wp_head ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
		rel="stylesheet">

	<?php wp_head(); ?>
</head>

<body <?php body_class('bg-stone-50 text-stone-800 antialiased selection:bg-rose-100 selection:text-rose-900 font-sans flex flex-col min-h-screen'); ?>>

	<!-- Skip Links for Accessibility -->
	<a href="#main-content"
		class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-white text-brand-ink p-4 z-50 rounded-lg shadow-lg"><?php _e('Skip to content', 'chroma-early-start'); ?></a>

	<!-- NAVIGATION -->
	<header class="fixed top-0 w-full z-50 bg-white/95 backdrop-blur-xl shadow-sm border-b border-stone-100">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<div class="flex justify-between items-center h-20">
				<!-- Logo -->
				<a href="<?php echo esc_url(home_url('/')); ?>"
					class="flex items-center space-x-3 cursor-pointer group">
					<div class="relative w-10 h-10 flex items-center justify-center">
						<div
							class="absolute inset-0 bg-rose-100 rounded-full opacity-80 group-hover:scale-110 transition-transform">
						</div>
						<i data-lucide="puzzle" class="w-6 h-6 text-rose-600 relative z-10"></i>
					</div>
					<div class="flex flex-col">
						<?php
						$header_text_raw = earlystart_get_theme_mod('earlystart_header_text', "Chroma Early Start\nPediatric Therapy & Early Intervention");
						$header_lines = explode("\n", $header_text_raw);
						$primary_line = isset($header_lines[0]) ? $header_lines[0] : 'Chroma Early Start';
						$secondary_line = isset($header_lines[1]) ? $header_lines[1] : '';
						?>
						<span
							class="text-xl md:text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-rose-600 via-orange-600 to-amber-600 tracking-tight leading-none">
							<?php echo esc_html($primary_line); ?>
						</span>
						<?php if (!empty($secondary_line)): ?>
							<span
								class="text-[0.65rem] uppercase tracking-widest text-stone-500 font-semibold hidden md:block">
								<?php echo esc_html($secondary_line); ?>
							</span>
						<?php endif; ?>
					</div>
				</a>

				<!-- Desktop Menu -->
				<nav class="hidden xl:flex space-x-1 items-center">
					<?php earlystart_primary_nav(); ?>

					<div class="pl-4">
						<?php
						// Updated key to match inc/customizer-header.php
						$cta_url = earlystart_get_theme_mod('earlystart_book_tour_url', home_url('/contact-us/'));
						$cta_text = earlystart_get_theme_mod('earlystart_header_cta_text', 'Get Started');
						?>
						<a href="<?php echo esc_url($cta_url); ?>"
							class="bg-stone-900 text-white px-6 py-2.5 rounded-full font-bold hover:bg-rose-600 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 text-sm inline-block">
							<?php echo esc_html($cta_text); ?>
						</a>
					</div>
				</nav>

				<!-- Mobile Menu Button -->
				<div class="xl:hidden">
					<button data-mobile-nav-toggle class="text-stone-600 hover:text-rose-600 p-2">
						<i data-lucide="menu" class="w-6 h-6"></i>
					</button>
				</div>
			</div>
		</div>

		<!-- Mobile Menu Dropdown -->
		<div data-mobile-nav
			class="fixed top-20 left-0 w-full bg-white border-t border-stone-100 shadow-xl p-4 hidden flex-col space-y-4 xl:hidden h-screen overflow-y-auto pb-32">
			<?php earlystart_mobile_nav(); ?>
			<a href="<?php echo esc_url($cta_url); ?>"
				class="block w-full text-center bg-stone-900 text-white px-6 py-4 rounded-xl font-bold mt-4">
				<?php echo esc_html($cta_text); ?>
			</a>
		</div>
	</header>

	<main id="main-content" class="flex-grow">
		<?php
		// Disabled to prevent duplication with external plugins
		// do_action('earlystart_breadcrumbs'); 
		?>