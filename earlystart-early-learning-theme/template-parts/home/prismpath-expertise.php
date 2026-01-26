<?php
/**
 * Template Part: Prismpath Expertise (Bento Grid)
 *
 * @package EarlyStart_Early_Start
 */

$panels = earlystart_home_prismpath_panels();
$feature = $panels['feature'];
$cards = $panels['cards'];
?>

<section id="why-chroma" class="py-24 bg-white relative overflow-hidden">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="grid lg:grid-cols-12 gap-8">
			<!-- Header Card -->
			<div
				class="lg:col-span-8 bg-stone-900 rounded-[2.5rem] p-12 text-white relative overflow-hidden shadow-2xl flex flex-col justify-center fade-in-up">
				<div class="relative z-10 max-w-xl">
					<span class="text-rose-400 font-bold uppercase tracking-[0.2em] text-xs mb-4 block">
						<?php echo esc_html($feature['eyebrow']); ?>
					</span>
					<h2 class="text-4xl lg:text-5xl font-extrabold mb-6 leading-tight">
						<?php echo $feature['heading']; // Already wp_kses_post ?>
					</h2>
					<p class="text-stone-400 text-lg leading-relaxed mb-8">
						<?php echo esc_html($feature['subheading']); ?>
					</p>
					<a href="<?php echo esc_url($feature['cta_url']); ?>"
						class="bg-rose-600 text-white px-8 py-3 rounded-full font-bold hover:bg-rose-700 transition-all inline-flex items-center group w-fit">
						<?php echo esc_html($feature['cta_label']); ?>
						<i data-lucide="arrow-right"
							class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform"></i>
					</a>
				</div>
			</div>

			<?php
			$colors = [
				'orange' => ['bg' => 'bg-orange-50', 'hover' => 'hover:bg-orange-100', 'icon_text' => 'text-orange-600'],
				'rose' => ['bg' => 'bg-rose-50', 'hover' => 'hover:bg-rose-100', 'icon_text' => 'text-rose-600'],
				'amber' => ['bg' => 'bg-amber-50', 'hover' => 'hover:bg-amber-100', 'icon_text' => 'text-amber-600'],
			];

			foreach ($cards as $index => $card):
				$color = $card['color'] ?? 'rose';
				$scheme = $colors[$color] ?? $colors['rose'];
				?>
				<div class="lg:col-span-4 <?php echo esc_attr($scheme['bg']); ?> rounded-[2.5rem] p-10 flex flex-col justify-between group <?php echo esc_attr($scheme['hover']); ?> transition-colors shadow-xl fade-in-up"
					style="transition-delay: <?php echo ($index + 1) * 100; ?>ms">
					<div class="w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center mb-8">
						<i data-lucide="<?php echo esc_attr($card['icon']); ?>"
							class="w-8 h-8 <?php echo esc_attr($scheme['icon_text']); ?>"></i>
					</div>
					<div>
						<h3 class="text-2xl font-bold text-stone-900 mb-2"><?php echo esc_html($card['heading']); ?></h3>
						<p class="text-stone-600 leading-relaxed"><?php echo esc_html($card['text']); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
