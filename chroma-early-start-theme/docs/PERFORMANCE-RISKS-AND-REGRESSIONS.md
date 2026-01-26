# Performance Risks & Regressions

## Multi-Tenant Risks

### 1. Global Cache Clearing
**Risk**: `functions.php` contains:
```php
function earlystart_clear_query_cache($post_id) { ... }
add_action('save_post', 'earlystart_clear_query_cache');
```
This clears transients matching `earlystart_*`. In a shared environment (Standard WP Multisite or Separate DBs), this is fine.
**Regression Risk**: If multiple tenants share the same caching backend (e.g., a shared Redis instance without distinct prefixes), saving a post on Site A could flush cache for Site B.
**Mitigation**: Ensure `WP_CACHE_KEY_SALT` is unique per tenant in `wp-config.php`.

### 2. Hardcoded Tailwind Config
**Risk**: `inc/enqueue.php` injects inline Tailwind config:
```js
tailwind.config = { theme: { extend: { colors: { rose: ... } } } }
```
**Regression**: If a white-label tenant wants "Blue" as their primary color, this hardcoded JS overrides their settings unless dynamically generated from Theme Mods. A mismatch here causes visual branding bugs.
**Mitigation**: Generate this JS object via `earlystart_get_theme_mod()` values instead of hardcoded hex codes.

### 3. Lucide Icon Flash (INP/CLS)
**Risk**: Replacing `<i data-lucide>` with SVGs via JS causes a "flash of missing icons" or a layout shift if dimensions aren't reserved.
**Regression**: If JS fails (e.g., restrictive CSP headers blocks the script), icons disappear entirely.
**Mitigation**: Move to server-side SVG rendering (PHP) to ensure icons are present in the initial HTML response.

## Deployment Safety

### 4. CDN Removal
**Risk**: Removing `tailwind-cdn` relies on `main.css` being up-to-date.
**Regression**: If the build pipeline failed to compile `main.css` with the latest classes used in PHP templates, the site layout will break.
**Mitigation**: Verify `assets/css/main.css` file size and content before deploying the "Quick Win" #1.

### 5. Meta Box Conditional Loading
**Risk**: Wrapping files in `is_admin()` might break frontend logic if those files *also* contain helper functions used by templates (not just `add_meta_box` calls).
**Regression**: `Call to undefined function` fatal errors on frontend.
**Mitigation**: Scan `inc/*-page-meta.php` files. If they contain *only* `add_action('add_meta_boxes', ...)` they are safe. If they contain utility functions, move utilities to a separate file or don't wrap the whole file.
    -   *Audit Check*: `inc/about-page-meta.php` is 57KB. It likely contains more than just meta boxes. **Verify before applying.**
