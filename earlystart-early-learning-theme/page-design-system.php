<?php
/**
 * Template Name: Design System
 * 
 * A showcase of the site's design tokens and components.
 */

get_header();
?>

<main class="pt-24 pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-6xl font-bold mb-12 text-stone-900 border-b pb-8">Design System</h1>

        <!-- Typography -->
        <section class="mb-24">
            <h2 class="text-sm font-bold uppercase tracking-widest text-stone-400 mb-8 border-b border-stone-100 pb-2">
                Typography</h2>
            <div class="space-y-8">
                <div>
                    <span class="text-xs text-stone-400 block mb-2">Display 7XL / Bold</span>
                    <h1 class="text-7xl font-bold text-stone-900">Clinical Excellence</h1>
                </div>
                <div>
                    <span class="text-xs text-stone-400 block mb-2">Display 5XL / Bold</span>
                    <h2 class="text-5xl font-bold text-stone-900">Where Therapy Meets Play</h2>
                </div>
                <div>
                    <span class="text-xs text-stone-400 block mb-2">Heading 3XL / Bold</span>
                    <h3 class="text-3xl font-bold text-stone-900">Expert Care for Growing Minds</h3>
                </div>
                <div>
                    <span class="text-xs text-stone-400 block mb-2">Body XL / Regular</span>
                    <p class="text-xl text-stone-600 max-w-3xl">We founded Early Start on a simple belief: Clinical therapy
                        should be a perfect blend of rigorous skill development and the comforting warmth of family
                        life. This is the cornerstone of our approach.</p>
                </div>
                <div>
                    <span class="text-xs text-stone-400 block mb-2">Body Base / Regular</span>
                    <p class="text-base text-stone-600 max-w-3xl">Applied Behavior Analysis (ABA) is a therapy based on
                        the science of learning and behavior. Our approach focuses on positive reinforcement to increase
                        helpful behaviors and decrease harmful ones.</p>
                </div>
            </div>
        </section>

        <!-- Colors -->
        <section class="mb-24">
            <h2 class="text-sm font-bold uppercase tracking-widest text-stone-400 mb-8 border-b border-stone-100 pb-2">
                Color Palette</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Rose -->
                <div class="space-y-2">
                    <div class="h-24 rounded-2xl bg-rose-600 shadow-lg"></div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-stone-900">Rose 600</span>
                        <span class="text-stone-500">Brand Primary</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="h-24 rounded-2xl bg-rose-500 shadow-md"></div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-stone-900">Rose 500</span>
                        <span class="text-stone-500">Interactive</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="h-24 rounded-2xl bg-rose-100"></div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-stone-900">Rose 100</span>
                        <span class="text-stone-500">Backgrounds</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="h-24 rounded-2xl bg-rose-50"></div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-stone-900">Rose 50</span>
                        <span class="text-stone-500">Subtle Wash</span>
                    </div>
                </div>

                <!-- Stone -->
                <div class="space-y-2">
                    <div class="h-24 rounded-2xl bg-stone-900 shadow-lg"></div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-stone-900">Stone 900</span>
                        <span class="text-stone-500">Headings</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="h-24 rounded-2xl bg-stone-600"></div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-stone-900">Stone 600</span>
                        <span class="text-stone-500">Body Text</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="h-24 rounded-2xl bg-stone-100"></div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-stone-900">Stone 100</span>
                        <span class="text-stone-500">Borders</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="h-24 rounded-2xl bg-stone-50 border border-stone-100"></div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-stone-900">Stone 50</span>
                        <span class="text-stone-500">Surface</span>
                    </div>
                </div>

                <!-- Accents -->
                <div class="space-y-2">
                    <div class="h-24 rounded-2xl bg-orange-500 shadow-md"></div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-stone-900">Orange 500</span>
                        <span class="text-stone-500">Gradient Start</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="h-24 rounded-2xl bg-amber-400 shadow-md"></div>
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-stone-900">Amber 400</span>
                        <span class="text-stone-500">Sparkles</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- UI Components -->
        <section class="mb-24">
            <h2 class="text-sm font-bold uppercase tracking-widest text-stone-400 mb-8 border-b border-stone-100 pb-2">
                Components</h2>

            <div class="grid md:grid-cols-2 gap-12">
                <!-- Buttons -->
                <div>
                    <h3 class="text-lg font-bold mb-6">Buttons</h3>
                    <div class="flex flex-wrap gap-4 items-center">
                        <a href="#"
                            class="bg-rose-600 text-white px-8 py-4 rounded-full font-bold hover:bg-rose-500 transition-all shadow-lg hover:shadow-rose-900/20 active:scale-95">
                            Primary Action
                        </a>
                        <a href="#"
                            class="bg-stone-900 text-white px-8 py-4 rounded-full font-bold hover:bg-stone-700 transition-all shadow-lg">
                            Secondary Action
                        </a>
                        <a href="#" class="text-rose-600 font-bold hover:underline">
                            Text Link &rarr;
                        </a>
                    </div>
                </div>

                <!-- Badges -->
                <div>
                    <h3 class="text-lg font-bold mb-6">Badges</h3>
                    <div class="flex flex-wrap gap-4">
                        <span
                            class="inline-block px-4 py-2 bg-rose-50 text-rose-600 rounded-full text-xs font-bold tracking-widest uppercase">
                            New Campus
                        </span>
                        <span
                            class="inline-block px-4 py-2 bg-stone-100 text-stone-600 rounded-full text-xs font-bold tracking-widest uppercase">
                            Coming Soon
                        </span>
                        <span
                            class="text-[10px] bg-white border border-stone-200 px-3 py-1 rounded-full text-stone-500 font-bold uppercase tracking-wider">
                            North Atlanta
                        </span>
                    </div>
                </div>

                <!-- Cards -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-bold mb-6">Cards</h3>
                    <div class="grid md:grid-cols-3 gap-8">
                        <!-- Standard Card -->
                        <div
                            class="bg-white p-8 rounded-[2.5rem] border border-stone-100 shadow-sm hover:shadow-md transition-shadow">
                            <div
                                class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center mb-6 text-rose-600">
                                <i data-lucide="star" class="w-6 h-6"></i>
                            </div>
                            <h4 class="font-bold text-xl mb-3">Service Card</h4>
                            <p class="text-sm text-stone-500 leading-relaxed">Standard card used for features, services,
                                and simple content blocks.</p>
                        </div>

                        <!-- Highlight Card -->
                        <div class="bg-stone-50 p-8 rounded-[2.5rem] border border-stone-100">
                            <h4 class="font-bold text-xl mb-3">Highlight Card</h4>
                            <p class="text-sm text-stone-500 leading-relaxed mb-6">Slightly darker background to break
                                up white space.</p>
                            <a href="#"
                                class="text-rose-600 font-bold text-sm uppercase tracking-widest flex items-center gap-2">
                                Learn More <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php get_footer(); ?>
