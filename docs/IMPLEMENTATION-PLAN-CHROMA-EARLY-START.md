# Implementation Plan: Chroma Early Start (Whitelabel Clone)

**Status:** Draft  
**Version:** 1.0.0  
**Target:** Senior Engineering Team  
**Date:** 2026-01-25

## 1. Executive Summary

This document outlines the engineering process to clone the "Chroma Preschool Platform" (Base Theme) and adapt it for a new tenant, **"Chroma Early Start"** (Pediatric Therapy).

The "Chroma Early Start" entity represents a sister company with a distinct brand identity (Rose/Orange/Amber/Stone palette) and service focus (ABA, Speech, OT), but shares the same technological backbone (Locations, Programs, Director Portal).

**Core Strategy:**
We will follow the `WHITELABEL-GUIDE.md` protocol to duplicate the platform, then apply a "Tenant Layer" overlay using the provided HTML assets. We will strictly adhere to the base theme's **Native Meta Box** architecture (removing dependency on ACF for core performance) while mapping the new UI components to WordPress templates.

---

## 2. System Inventory (Phase 0 Findings)

### 2.1 Base Platform
*   **Repo:** `Wptstchroma.git` (Cloned to `base-repo`)
*   **Theme:** `chroma-excellence-theme` (PHP 8 + Tailwind CSS v3)
*   **Architecture:** Hybrid Theme (Routing: WP Core -> `functions.php` Bootstrapper -> `inc/` Service Container).
*   **Data Model:** Native `add_meta_box` PHP implementations (No runtime ACF dependency).
*   **Key CPTs:** `location`, `program` (implied or needing creation), `chroma_school` (TV Dashboard).
*   **Plugins:** `chroma-parent-portal` (React App), `chroma-school-dashboard` (TV Signage).

### 2.2 Target Tenant (Chroma Early Start)
*   **Assets:** Static HTML files (`Index.html`, `locations.html`, etc.) + Tailwind CDN + Lucide Icons.
*   **Brand:**
    *   **Primary:** Rose 600 (`#e11d48`)
    *   **Secondary:** Orange 600 (`#ea580c`)
    *   **Accent:** Amber 600 (`#d97706`)
    *   **Neutrals:** Stone (`#fafaf9`, `#1c1917`)
    *   **Typography:** "Plus Jakarta Sans" (Google Fonts).

---

## 3. Implementation Phases

### Phase 1: Platform Cloning & Namespace (Day 1)
**Objective:** Create a running, unbranded "Acme" copy of the platform.

1.  **Clone & Rename:**
    *   Duplicate `chroma-excellence-theme` -> `chroma-early-start-theme`.
    *   Duplicate plugins: `chroma-parent-portal` -> `early-start-parent-portal`, etc.
2.  **Namespace Refactor:**
    *   Replace `chroma_` function prefixes with `earlystart_` (carefully preserving platform core logic where shared).
    *   Update `style.css` Theme Name to "Chroma Early Start Theme".
    *   Update `package.json` names.
3.  **Dependency Install:**
    *   Run `npm install` in the new theme.
    *   Run `composer install` (if applicable).

### Phase 2: Design System Integration (Day 1-2)
**Objective:** Port the "Chroma Early Start" design tokens into the specific build pipeline.

1.  **Tailwind Config:**
    *   Update `tailwind.config.js`:
        *   Map `colors.chroma` tokens to the new Rose/Orange/Stone palette.
        *   Map `colors.brand.ink` to `#1c1917` (Stone 900).
        *   Update `fontFamily` to use "Plus Jakarta Sans" (Download & Self-host .woff2 files).
2.  **Icon System:**
    *   Enqueue Lucide Icons (or strict SVG replacements) in `inc/enqueue.php`.
    *   Deprecate FontAwesome if not used in the new HTML.
3.  **Critical CSS:**
    *   Update `inc/critical-css.php` with new brand colors to prevent FOUC.
4.  **Initial Build:**
    *   Run `npm run build:css` and verify global typography/colors.

### Phase 3: Template & Component Migration (Day 2-3)
**Objective:** Convert static HTML into dynamic WP Modules.

1.  **Header/Footer:**
    *   Implementation in `header.php` / `footer.php`.
    *   Dynamic Menu: `primary-nav` (Desktop), `mobile-nav` (Slide-out).
2.  **Home Page (`front-page.php`):**
    *   **Hero Module:** Hardcoded structure with Dynamic Text (Native Meta).
    *   **Dynamic Sections:** "Trust Bar", "Services Grid" (Hardcoded or `program` CPT loop), "Testimonials".
3.  **Locations Page (`page-locations.php`):**
    *   Adapt existing CPT loop (`WP_Query('post_type' => 'location')`).
    *   Match HTML Card Design: "Map Pin" icon, "Services Available" badges.
    *   Implement "Zip Code Search" logic (JS adaptation).
4.  **Inner Pages:**
    *   `page-about.php`, `page-contact.php`, `page-careers.php`, `page-curriculum.php` (Our Approach).
    *   **Services:** Use `program` CPT for "ABA", "Speech", "Occupational Therapy". These will render via `single-program.php`.

### Phase 4: Data Layer Adaptation (Day 4)
**Objective:** Configure content entry points.

1.  **CPT Locations:**
    *   Verify `inc/cpt-locations.php` fields match "Chroma Early Start" needs (Service Areas, School Pickups, Hours).
    *   Add any missing fields via Native Meta (e.g., specific "Therapy Services" checkboxes if different from base).
2.  **Programs/Services:**
    *   **Action:** Enable `program` CPT.
    *   **Migration:** Populate `single-program.php` meta fields (Prismpath Chart, Schedule, Hero) with data from HTML files.
    *   *Note:* The "Prismpath" chart in the base theme might need relabeling for Therapy (e.g., "Physical" -> "Motor Skills"), but the underlying data structure works.
3.  **Forms:**
    *   Integrate the "Get Started" form with a backend handler (Gravity Forms or custom `admin-post.php` handler).

### Phase 5: QA & Launch (Day 5)
**Objective:** Verify parity and deploy.

1.  **Content Population:** Import content from HTML text.
2.  **Lighthouse Audit:** Target > 90 Performance/SEO.
3.  **Cross-Browser Check:** Chrome, Safari, Firefox, Mobile.

---

## 4. Risk Assessment

| Risk | Impact | Mitigation |
| :--- | :--- | :--- |
| **Namespace Collisions** | High (500 Error) | Use strict `earlystart_` prefixing. Verify with `grep`. |
| **Tailwind Conflicts** | Medium (Visual) | Ensure `safelist` in config covers all dynamic classes used in PHP. |
| **SEO Regression** | High (Traffic Loss) | Maintain schema markup from base theme. Ensure redirects if URLs change. |
| **Mobile Menu JS** | Medium (UX) | Port the Vanilla JS logic effectively from `Index.html` to `app.js`. |

## 5. Next Steps
1.  Verify approval of this plan.
2.  Execute **Phase 1** (Cloning).
