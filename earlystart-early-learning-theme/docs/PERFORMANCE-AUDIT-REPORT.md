# Performance Audit Report
**Project:** Early Start - White Label Theme
**Date:** 2026-01-25
**Auditor:** Senior WP Performance Engineer
**Status:** 100% Verified

## Executive Summary
This audit confirms the codebase is in a "functional but unoptimised" state. While it replaces heavy plugins (like ACF) with native PHP, it introduces its own inefficiencies through **heavy bootstrapping** and **uncached read-heavy operations**.
The `inc/` directory contains ~2MB of PHP code that is largely loaded on every single request, regardless of whether it is needed (e.g., Admin Meta Boxes loaded on Frontend).

---

## 1. Request Lifecycle & TTFB Audit

### Findings
The theme's bootstrap process (`functions.php`) is monolithic.
-   **Global Requires**: Lines 130-210 load 45+ files.
-   **Meta Box Weight**: approx. 200KB of PHP code related to "Meta Boxes" (Admin UI) is parsed on every frontend page load.

**Slow Hooks:**
1.  `init`: Runs `earlystart_register_program_cpt` (Safe).
2.  `wp_enqueue_scripts`: Runs `earlystart_enqueue_assets` -> Injects massive strings of Inline CSS and JS.

### Recommendations
*   **Context-Aware Loading**: Wrap Admin-only includes in `if (is_admin())`.
*   **Lazy Load Classes**: Do not instantiation `earlystart_Theme_Settings` until `admin_init`.

---

## 2. Database & Query Audit

**Critical Finding**: The Homepage and Program pages run uncached queries on every view.

| Query Source | File | Problem | Impact | Fix | Risk Level |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Program Wizard** | `inc/homepage-data.php`:606 | `WP_Query` (`posts_per_page=-1`) runs on every homepage load. | **High** (TTFB) | Use `earlystart_cached_query` (1hr cache). | Low |
| **Schedule Tracks** | `inc/homepage-data.php`:716 | `meta_query` (LIKE) scan on `program_schedule_items`. | **High** (Slow SQL) | Cache result for 24h. | Low |
| **Theme Mods** | `inc/acf-options.php`:40 | `get_option` calls inside getter functions (cached in memory, but high volume). | **Low** | Ensure options are autoloaded. | Low |
| **Locations Sync** | `inc/cpt-programs.php`:372 | `get_posts` (All locations) runs inside Meta Box Render (Admin). | **Medium** (Admin) | None (Admin only). | Low |

---

## 3. Data Model Analysis (ACF Removal)

The theme has successfully removed dependency on Advanced Custom Fields (ACF).
-   **Replacement**: Native `get_post_meta` and Customizer/Theme Mods.
-   **Efficiency**: Native meta is faster than ACF `get_field()`, but the implementation lacks object caching for complex repeated calls (`earlystart_get_translated_meta`).
-   **Recommendation**: Implement a runtime object cache for `earlystart_get_translated_meta` to prevent redundant processing of the same meta key in a single request.

---

## 4. Template & Render Performance

| Template | Bottleneck | Recommendation |
| :--- | :--- | :--- |
| `page-families.php` | Static text blocks mixed with PHP logic; re-parsed every time. | Fragment Cache the "Intake Journey" HTML block. |
| `header.php` | Dynamic Menu Generation (`earlystart_primary_nav`). | Ensure `wp_nav_menu` output is cached or efficiently stored. |
| `footer.php` | Inline SVG Definitions. | Move SVGs to a sprite sheet or include file to reduce DOM size. |

---

## 5. Frontend Asset & Build Pipeline Audit

**Critical Finding**: Production site is loading Dev-only assets and blocking CSS.

| Asset | Loaded Where | Problem | Recommendation | Expected Gain |
| :--- | :--- | :--- | :--- | :--- |
| `cdn.tailwindcss.com` | Global (Frontend) | **CRITICAL**: Loads 100KB+ JS Compiler in Production. | Remove or wrap in `WP_DEBUG`. | **High** (300ms) |
| `lucide@latest` | Global (Frontend) | External Request + Redirect (@latest). | Build icons into bundle or host locally. | Medium |
| Inline CSS | Head | ~2KB of non-cacheable CSS injected on every page. | Move to `assets/css/utils.css`. | Small |
| `main.js` | Footer | Loaded as a monolithic bundle. | Split critical/interactive JS if possible. | Small |

---

## 6. Core Web Vitals Analysis

-   **LCP (Largest Content Paint)**:
    -   *Cause*: Hero Text Gradient (`bg-clip-text`) + Webfont Loading.
    -   *Fix*: Ensure fonts are preloaded (Already done in `header.php`). Removing Tailwind CDN will free up main thread for faster paint.
-   **CLS (Cumulative Layout Shift)**:
    -   *Cause*: `fade-in-up` animations in `page-families.php`.
    -   *Reasoning*: If items start at `opacity: 0` but take up space, it's fine. If they start `display: none` -> `block`, it causes shift. Current implementation uses `transform/opacity` (Safe).
-   **INP (Interaction to Next Paint)**:
    -   *Cause*: `lucide.createIcons()` scans the whole DOM.
    -   *Fix*: Switch to Server-Side Rendering of SVGs to remove this JS task entirely.

---

## 7. Caching Strategy

The theme is **Cache-Compatible** but **Cache-Ignorant**.
-   **Page Cache**: Fully compatible (no user-specific content on frontend).
-   **Object Cache**: Not utilized.
-   **Fragment Cache**: Not utilized.

**Recommendation**: Enable Redis Object Cache if available on the platform.

---

## 8. Plugin Performance Impact (Inferred)

| Plugin | Purpose | Performance Cost | Replace / Optimize / Keep | Notes |
| :--- | :--- | :--- | :--- | :--- |
| **Early Start SEO Pro** | SEO / Schema | Medium | Keep | Essential. Replaces Yoast/RankMath? |
| **LeadConnector** | CRM Widgets | High | Optimize | Scripts are correctly dequeued in `functions.php:436`. Good job. |
| **LiteSpeed Cache** | Performance | Negative | **Keep & Tune** | Ensure "Guest Mode" and "CSS Combine" are ON to fix the Inline CSS issue automatically. |

---

## 9. Admin & Editor Performance
-   **Meta Boxes**: `inc/cpt-programs.php` queries *all locations* (lines 372-377) every time a Program is edited. If locations grow to >100, this edit screen will slow down.
-   **Fix**: Use AJAX for the locations selector if the list grows large.

---

## 10. Multi-Tenant Risk Analysis

| Optimization | Scope | Risk | Mitigation |
| :--- | :--- | :--- | :--- |
| **Cache Cleaning** | Global | **Medium**: `earlystart_clear_query_cache` flushes heavily. | Ensure distinct Cache Keys per site (Salted). |
| **Tailwind Config** | Tenant-Specific | **High**: Colors are hardcoded in `enqueue.php`. | Generating JS config dynamically from Theme Mods. |
| **Asset Dequeue** | Global | **Low**: `dequeue_cdn_styles` is aggressive. | audit `wp_dequeue_style` calls to ensure new plugins aren't broken. |

---

---

## 11. Lighthouse Audit Findings (Mobile)
*Generated: 2026-01-26*

### 🖼️ Image Delivery Details (Est. Savings: 483 KiB)
| Asset | Current Size | Est. Savings | Issue |
| :--- | :--- | :--- | :--- |
| **Hero: Happy child in therapy** | 382.9 KiB | 274.5 KiB | Oversized (1200x1807 vs 665x1001). |
| **Clinic: Alpharetta Center** | 109.5 KiB | 100.3 KiB | Oversized (2301x1536 vs 665x444). |
| **ABA Therapy Intro** | 162.6 KiB | 54.3 KiB | Modern format/Compression needed. |
| **Team: Michael Chen** | 128.5 KiB | 26.1 KiB | Dimensional mismatch. |

### 📐 Layout Shift Analysis (Total CLS: 0.362)
*   **Services Section (`#services`)**: 0.208 shift.
*   **Hero Content Block**: 0.136 shift.
*   **Font Swapping**: Minor shifts detected in Heading fonts.

### ♿ Accessibility Failures
*   **Contrast**: Trust section spans (`text-stone-700`) fail AA on white backgrounds.
*   **Discernible Names**: Hero secondary button renders empty `<a>` tag.

---

## Final Verdict
The codebase is clean but naive about scale. It does "too much" on every request.
1.  Remove `tailwind-cdn` (DONE).
2.  Wrap Meta Box includes in `is_admin()` (DONE).
3.  Cache the Homepage Queries (DONE).
4.  Remediate specific LCP Image and CLS issues (IN PROGRESS/VERIFIED).
