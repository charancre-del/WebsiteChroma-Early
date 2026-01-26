# Performance Changelog

| Date | File(s) | Change | Reason |
| :--- | :--- | :--- | :--- |
| 2026-01-25 | `docs/*` | Initial Documentation | Baseline establishment |
| 2026-01-25 | `functions.php` | Conditionalized Meta Box & Customizer Includes | Reduce Frontend Bootstrap Memory |
| 2026-01-25 | `inc/homepage-data.php` | Applied `earlystart_cached_query` to Program & Schedule queries | Reduce TTFB / DB Load |
| 2026-01-25 | `inc/translation-helpers.php` | Added Memoization to `earlystart_get_translated_meta` | Minimize redundant DB calls |
| 2026-01-25 | `inc/enqueue.php` | Removed Tailwind CDN from Production | Reduce Frontend Payload (~100KB) |
| 2026-01-25 | `inc/enqueue.php`, `assets/css/utils.css` | Extracted Inline CSS to Static File | Enable Browser Caching |
| 2026-01-25 | `page-families.php` | Fragment Cached "Intake Journey" Section | Reduce Server Rendering Time |
| 2026-01-25 | `inc/enqueue.php` | Dequeued `wp-block-library` styles | Reduce CSS Payload (Unused Blocks) |
