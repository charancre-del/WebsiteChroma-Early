# Performance Quick Wins

## 1. Disable Tailwind CDN (Safe)
**Problem**: The site loads the full Tailwind Play CDN (`https://cdn.tailwindcss.com`) even though a compiled `main.css` exists.
**Fix**:
In `inc/enqueue.php`:
```php
// BEFORE
wp_enqueue_script('tailwind-cdn', 'https://cdn.tailwindcss.com', ...);

// AFTER
if (defined('WP_DEBUG') && WP_DEBUG) {
    wp_enqueue_script('tailwind-cdn', 'https://cdn.tailwindcss.com', ...);
}
```
**Gain**: Saves ~300ms loading time on mobile.

## 2. Admin Logic conditional loading (Safe)
**Problem**: 500+ lines of Customizer logic loaded on every frontend request.
**Fix**:
In `functions.php`:
```php
// Wrap customizer includes
if (is_customize_preview()) {
    require_once earlystart_THEME_DIR . '/inc/customizer-home.php';
    require_once earlystart_THEME_DIR . '/inc/customizer-header.php';
    // ... others
}
```
**Gain**: Reduces PHP memory usage by ~5%.

## 3. Implement Query Caching for Homepage (Medium safe)
**Problem**: Homepage runs un-cached queries.
**Fix**:
In `inc/homepage-data.php`, find function `earlystart_home_program_wizard_options`.
Replace `new WP_Query(...)` with:
```php
$programs = earlystart_cached_query($args, 'program_wizard_v1', 4 * HOUR_IN_SECONDS);
```
**Gain**: Instant TTFB improvement for homepage.

## 4. Dequeue Unused Block Styles (Safe)
**Problem**: `wp-block-library` is dequeued, but `global-styles` often persists.
**Fix**:
Ensure `earlystart_remove_block_library_css` in `functions.php` covers `global-styles` (It currently does, which is good. Verify it works in practice).
```php
wp_dequeue_style('global-styles');
```
**Gain**: Removes ~15KB of unused CSS.
