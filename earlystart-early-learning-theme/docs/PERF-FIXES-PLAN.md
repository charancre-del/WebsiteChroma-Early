# Performance Fixes Plan

## Baseline (Phase 0)
Based on Audit:
- **TTFB**: ~800ms-1200ms (Estimated) due to heavy bootstrap and uncached DB queries.
- **Frontend Payload**: Bloated by Tailwind CDN (~100KB) and Inline CSS.
- **LCP**: Delayed by fonts and potential js-based rendering.
- **DB**: Homepage runs 2 heavy uncached queries per load.

## Prioritized Fixes List

### High Impact (TTFB & Backend)
1.  **Conditional Admin Includes**
    -   **Problem**: `functions.php` loads massive meta box logic on frontend.
    -   **File**: `functions.php`
    -   **Fix**: Wrap `require_once` in `if (is_admin())`.
    -   **Risk**: Low (if files are purely admin).
2.  **Cache Homepage Queries**
    -   **Problem**: `inc/homepage-data.php` runs `new WP_Query` on every load.
    -   **File**: `inc/homepage-data.php` (2 locations).
    -   **Fix**: Use `earlystart_cached_query` helper.
    -   **Risk**: Low (Cache invalidation via save_post exists).
3.  **Memoize Meta Lookups**
    -   **Problem**: `earlystart_get_translated_meta` hits DB repetitively.
    -   **File**: `inc/translation-helpers.php` (or new helper).
    -   **Fix**: Static array cache within request.
    -   **Risk**: Low.

### Quick Wins (Assets)
4.  **Remove Tailwind CDN**
    -   **Problem**: Double loading of CSS framework.
    -   **File**: `inc/enqueue.php`
    -   **Fix**: Conditional enqueue (Debug only).
    -   **Risk**: Low (Visual verification required).
5.  **Externalize Inline CSS**
    -   **Problem**: Uncacheable CSS bytes in HTML.
    -   **File**: `inc/enqueue.php`
    -   **Fix**: Create `assets/css/utils.css`.
    -   **Risk**: Low.

### Template Optimization
6.  **Fragment Cache "Intake Journey"**
    -   **Problem**: Static HTML generated via PHP loop every time.
    -   **File**: `page-families.php`
    -   **Fix**: `get_transient` / `set_transient`.
    -   **Risk**: Low.

## Verification Strategy
See `PERF-VERIFY.md` for exact commands.
