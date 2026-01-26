# Rollback & Risk Mitigation Plan

**Context:** Cloning "Early Start Excellence" to "Early Start".

## 1. Risk Register

| ID | Risk Scenario | Probability | Impact | Mitigation Strategy |
| :--- | :--- | :---: | :---: | :--- |
| **R1** | **Namespace Collision**<br>PHP Fatal Error due to duplicate function names if both themes active. | High | Critical | **Strict Prefixing:** Run `grep -r "chroma_"` and replace ALL occurrences with `earlystart_`. Do not activate both themes on same site without full renaming. |
| **R2** | **CSS Bleed**<br>Tailwind classes from base theme conflict or override new theme. | Low | Low | Scoped build process. Enqueue `main.css` with a unique handle `earlystart-style`. |
| **R3** | **Data Loss**<br>Overwriting existing `location` posts during content import. | Medium | High | Use unique IDs for import. Test import on staging first. |
| **R4** | **SEO Tanking**<br>New HTML structure lacks Schema markup present in base theme. | Medium | High | *Audit:* Compare generated source code of Base vs New. Copy `inc/schema-*.php` logic faithfully. |
| **R5** | **Asset 404s**<br>Hardcoded image paths in HTML not updated to WP `get_template_directory_uri()`. | High | Medium | Search/Replace `src="images/` with PHP dynamic tags during migration. |

## 2. Rollback Protocol

### 2.1 Code Revert (Git)
If the new theme breaks the site:

1.  **Immediate Revert:**
    ```bash
    git checkout master
    # OR if committed
    git revert HEAD
    ```
2.  **Theme Switch:**
    *   Log in via WP-CLI: `wp theme activate chroma-excellence-theme`
    *   OR rename folder: `mv wp-content/themes/earlystart-early-learning-theme wp-content/themes/_BROKEN_chroma` (Forces WP to fall back).

### 2.2 Database Rollback
If a migration script corrupts data:

1.  **Pre-Migration Snapshot:**
    ```bash
    wp db export pre-migration-backup.sql
    ```
2.  **Restore:**
    ```bash
    wp db import pre-migration-backup.sql
    ```

### 2.3 "White Screen" Recovery
1.  **Debug Mode:** Enable `WP_DEBUG` in `wp-config.php`.
2.  **Disable Plugin:** `wp plugin deactivate early-start-parent-portal`
3.  **Check Logs:** `tail -f wp-content/debug.log`

## 3. Staging vs Production Rules

*   **Rule 1:** NEVER run "Search & Replace" on Production without a Dry Run.
*   **Rule 2:** All content imports must happen on Staging first.
*   **Rule 3:** The `earlystart-early-learning-theme` directory must be fully isolated (no shared symlinks with base theme).
