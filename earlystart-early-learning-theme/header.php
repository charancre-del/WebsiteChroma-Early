<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php // Canonical URL is handled by Yoast SEO and class-canonical-enforcer.php via wp_head ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<style id="earlystart-critical-css">
		/* Darkened Brand Colors for WCAG AA Compliance (Enhanced) */
		.text-chroma-red {
			color: #964030 !important;
		}

		.bg-chroma-red {
			background-color: #964030 !important;
		}

		.text-chroma-orange {
			color: #A8551E !important;
		}

		.bg-chroma-orange {
			background-color: #A8551E !important;
		}

		.text-chroma-green {
			color: #4D5C54 !important;
		}

		.bg-chroma-green {
			background-color: #4D5C54 !important;
		}

		.text-chroma-yellow {
			color: #8C6B2F !important;
		}

		.bg-chroma-yellow {
			background-color: #8C6B2F !important;
		}

		/* touch targets & visibility */
		footer nav a,
		[data-reviews-dots] button {
			min-width: 44px;
			min-height: 44px;
		}

		.fade-in-up {
			animation: fadeInUp 0.8s ease forwards;
			opacity: 0;
			transform: translateY(20px);
		}

		@keyframes fadeInUp {
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}
	</style>

	<?php wp_head(); ?>
</head>

<body <?php body_class('bg-stone-50 text-stone-800 antialiased selection:bg-rose-100 selection:text-rose-900 font-sans flex flex-col min-h-screen'); ?>>

	<!-- Skip Links for Accessibility -->
	<a href="#main-content"
		class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-white text-brand-ink p-4 z-50 rounded-lg shadow-lg"><?php _e('Skip to content', 'earlystart-early-learning'); ?></a>

	<!-- SITE HEADER WRAPPER -->
	<div id="site-header-fixed-group" class="fixed top-0 w-full z-[100] shadow-sm">
		<!-- TOP BAR / ANNOUNCEMENT -->
		<div class="bg-stone-900 py-2.5 relative z-[110] overflow-hidden">
			<div
				class="absolute inset-0 bg-gradient-to-r from-rose-600/20 via-orange-600/20 to-amber-600/20 opacity-50 animate-pulse">
			</div>
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
				<div
					class="flex flex-col md:flex-row justify-center items-center gap-2 md:gap-8 text-white/90 text-[10px] md:text-xs font-bold tracking-[0.15em] uppercase text-center">
					<span class="flex items-center gap-2">
						<span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></span>
						<?php _e('Now Enrolling: Spring 2026 Bridge Program', 'earlystart-early-learning'); ?>
					</span>
					<span class="hidden md:block text-white/30">|</span>
					<a href="<?php echo esc_url(home_url('/consultation/')); ?>"
						class="hover:text-white transition-colors border-b border-white/20 pb-0.5">
						<?php _e('Schedule Clinical Consultation', 'earlystart-early-learning'); ?>
					</a>
				</div>
			</div>
		</div>

		<!-- NAVIGATION -->
		<header class="w-full bg-white/95 backdrop-blur-xl border-b border-stone-100 relative z-[100]">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex justify-between items-center h-20">
					<!-- Logo -->
					<a href="<?php echo esc_url(home_url('/')); ?>"
						class="flex items-center space-x-3 cursor-pointer group flex-shrink-0">
						<div class="relative w-10 h-10 flex items-center justify-center">
							<div
								class="absolute inset-0 bg-rose-100 rounded-full opacity-80 group-hover:scale-110 transition-transform">
							</div>
							<i data-lucide="puzzle" class="w-6 h-6 text-rose-600 relative z-10"></i>
						</div>
						<div class="flex flex-col">
							<?php
							$header_text_raw = earlystart_get_theme_mod('earlystart_header_text', "Early Start\nPediatric Therapy");
							$header_lines = explode("\n", $header_text_raw);
							$primary_line = isset($header_lines[0]) ? $header_lines[0] : 'Early Start';
							$secondary_line = isset($header_lines[1]) ? $header_lines[1] : 'Pediatric Therapy';
							?>
							<span
								class="text-xl md:text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-rose-600 via-orange-600 to-amber-600 tracking-tight leading-none">
								<?php echo esc_html($primary_line); ?>
							</span>
							<?php if (!empty($secondary_line)): ?>
								<span
									class="text-[0.65rem] uppercase tracking-widest text-stone-600 font-semibold hidden md:block">
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
							$cta_url = earlystart_get_theme_mod('earlystart_book_tour_url', home_url('/contact/'));
							$cta_text = earlystart_get_theme_mod('earlystart_header_cta_text', 'Get Started');
							?>
							<a href="<?php echo esc_url($cta_url); ?>"
								class="bg-stone-900 text-white px-6 py-2.5 rounded-full font-bold hover:bg-rose-600 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 text-sm inline-block">
								<?php echo esc_html($cta_text); ?>
							</a>
						</div>
					</nav>

					<!-- Mobile Menu Button -->
					<div class="xl:hidden flex items-center">
						<button data-mobile-nav-toggle
							class="text-stone-600 hover:text-rose-600 p-3 focus:outline-none bg-stone-50 rounded-lg transition-colors border border-stone-200"
							aria-label="Toggle menu">
							<i data-lucide="menu" class="w-6 h-6"></i>
						</button>
					</div>
				</div>
			</div>

			<!-- Mobile Menu Dropdown (Relative to Header) -->
			<div data-mobile-nav
				class="absolute top-full left-0 w-full bg-white border-t border-stone-100 shadow-xl p-4 hidden flex-col space-y-4 xl:hidden h-[calc(100vh-6rem)] overflow-y-auto pb-32">
				<?php earlystart_mobile_nav(); ?>
				<a href="<?php echo esc_url($cta_url); ?>"
					class="block w-full text-center bg-stone-900 text-white px-6 py-4 rounded-xl font-bold mt-4">
					<?php echo esc_html($cta_text); ?>
				</a>
			</div>
		</header>
	</div>

	<main id="main-content" class="flex-grow pt-[124px] md:pt-[104px]">
		<?php
		// Disabled to prevent duplication with external plugins
		// do_action('earlystart_breadcrumbs'); 
		?>
