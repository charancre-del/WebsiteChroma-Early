# Performance Improvement Backlog

## High Priority (Immediate Fixes)

- [ ] **Disable Tailwind CDN in Production** <!-- P0 -->
    -   **File**: `inc/enqueue.php` (Line 72)
    -   **Action**: Wrap the `wp_enqueue_script('tailwind-cdn', ...)` call in `if (defined('WP_DEBUG') && WP_DEBUG)` or remove entirely if `main.css` is complete.
    -   **Impact**: Removes ~100KB JS payload and client-side processing cost.

- [ ] **Defer "Heavy" Customizers** <!-- P0 -->
    -   **File**: `functions.php` (Lines 171-176)
    -   **Action**: Move `require_once` for `customizer-*.php` files inside an `if (is_customize_preview())` check.
    -   **Impact**: Reduces PHP memory usage by ~1-2MB per request.

- [ ] **Contextualize Meta Boxes** <!-- P1 -->
    -   **File**: `functions.php` (Lines 153-163)
    -   **Action**: Wrap all page meta box requires in `if (is_admin())`.
    -   **Impact**: Significant TTFB improvement on frontend.

## Medium Priority (Optimization)

- [ ] **Cache Homepage Program Query** <!-- P1 -->
    -   **File**: `inc/homepage-data.php` (Line 606)
    -   **Action**: Replace `new WP_Query` with `earlystart_cached_query($args, 'program_wizard', HOUR_IN_SECONDS)`.
    -   **Impact**: Saves 1 database query on every homepage load.

- [ ] **Cache Schedule Tracks Query** <!-- P1 -->
    -   **File**: `inc/homepage-data.php` (Line 716)
    -   **Action**: Replace `new WP_Query` with `earlystart_cached_query($args, 'schedule_tracks', DAY_IN_SECONDS)`.
    -   **Impact**: Saves 1 slow meta-scan database query.

- [ ] **Externalize Inline CSS** <!-- P2 -->
    -   **File**: `inc/enqueue.php` (Lines 111-208)
    -   **Action**: Move the CSS string to `assets/css/critical-patches.css` and enqueue it, OR append it to the build process.
    -   **Impact**: Improves browser caching and reduces HTML document size.

## Architectural (Long Term)

- [ ] **Server-Side Icon Rendering** <!-- P3 -->
    -   **Concept**: Replace client-side Lucide JS injection with server-side SVG injection.
    -   **Action**: Create a PHP helper `earlystart_icon($name)` that returns the SVG string. Replace `<i data-lucide="...">` with `<?php echo earlystart_icon('...'); ?>`.
    -   **Impact**: Eliminates INP risk from icon rendering; works without JS.

- [ ] **JSON Data Storage Refactor** <!-- P3 -->
    -   **Concept**: `homepage-data.php` relies on `earlystart_home_get_theme_mod_json` which decodes JSON at runtime.
    -   **Action**: Switch to storing these config arrays as PHP arrays in `wp_options` or Object Cache, rather than raw JSON strings that need parsing.
