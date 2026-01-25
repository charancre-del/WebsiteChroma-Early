# HTML to WP Template Mapping: Chroma Early Start

**Status:** Draft  
**Reference:** `Index.html`, `locations.html`, Base Theme Structure

This document details how each provided HTML file maps to the WordPress Theme Hierarchy and which components will be modularized.

## 1. Route Mapping Table

| HTML File | WP Template | Purpose | Data Source |
| :--- | :--- | :--- | :--- |
| `Index.html` | `front-page.php` | Home Page | **Exists**. Overwrite content. Use Native Meta. |
| `locations.html` | `page-locations.php` | Locations Archive | **Exists**. Update loop style. `WP_Query('post_type' => 'location')` |
| `about us.html` | `page-about.php` | About Us | **Exists**. Overwrite content. |
| `services.html` | `page-programs.php` | Services Landing | **Exists**. Theme uses `page-programs.php` (Template Name: Programs) to list `program` CPT items. |
| `aba program.html` | `single-program.php` | Service Detail | **Exists**. HTML content should be migrated into `program` CPT posts. |
| `speech therapy.html`| `single-program.php` | Service Detail | **Exists**. HTML content should be migrated into `program` CPT posts. |
| `occupational therapy.html` | `single-program.php` | Service Detail | **Exists**. HTML content should be migrated into `program` CPT posts. |
| `school readiness.html` | `single-program.php` | Service Detail | **Exists**. HTML content should be migrated into `program` CPT posts. |
| `families.html` | `page-parents.php` | Parent Resources | **Exists**. Theme has `page-parents.php`. |
| `careers.html` | `page-careers.php` | Careers & Jobs | **Exists**. Theme has `page-careers.php`. |
| `out approach.html` | `page-curriculum.php` | Our Approach | **Exists**. Theme has `page-curriculum.php` ("Prismpath™ Model"). Map new HTML content here. |
| `contact us.html` | `page-contact.php` | Contact Form | **Exists**. Overwrite content. |
| `blog.html` | `index.php` / `home.php` | Blog Archive | **Exists**. Standard WP. |

## 2. Gap Analysis: Pages in Base Theme (No HTML Provided)
The following templates exist in `chroma-excellence-theme` but have no corresponding HTML file in the new Chroma Early Start asset drop. **Action Required:** Determine if these pages should be hidden, deleted, or kept with generic branding.

*   `page-schedule-tour.php`: Critical conversion page. *Recommendation: Adapt `contact us.html` styles.*
*   `page-privacy.php`: Legal requirement.
*   `page-terms.php`: Legal requirement.
*   `page-acquisitions.php`: Corporate page (Likely remove).
*   `page-employers.php`: Corporate page (Likely remove or merge).
*   `page-newsroom.php`: PR page (Likely use Blog).
*   `page-stories.php`: Success stories/Testimonials.
*   `404.php`: Error page.

## 2. Component Implementation Strategy

### 2.1 Header & Navigation (`header.php`)
*   **Source:** `Index.html` (Lines 94-150)
*   **Logic:**
    *   Logo: `get_custom_logo()` or hardcoded inline SVG (as per HTML `data-lucide="puzzle"`).
    *   Menu: `wp_nav_menu()` with custom walker for Tailwind classes.
    *   Mobile Menu: Port JS toggle logic to `assets/js/modules/mobile-menu.js`.

### 2.2 Hero Section (`template-parts/hero-[type].php`)
*   **Home Hero:**
    *   Source: `Index.html` (Lines 158-223)
    *   Fields: Title, Subtitle, CTA Links, Background Classes.
    *   Animation: Keep the "Prism" animation (Lines 191-220) as hardcoded HTML/CSS in `front-page.php`.
*   **Page Hero:**
    *   Source: `locations.html` (Lines 117-127)
    *   Standard Component for internal pages.

### 2.3 Location Cards (`template-parts/card-location.php`)
*   **Source:** `locations.html` (Lines 133-159 - "Therapy City Clinic", Lines 188-195 - "Marietta Campus")
*   **Logic:**
    *   The "Main Clinical Hub" (Lines 133-159) is a Featured Location.
    *   The "Partner Network" (Lines 186-246) is a grid of smaller cards.
    *   **Mapping:**
        *   `location_school_pickups` field -> "Location" / "Zone".
        *   `location_special_programs` -> Badges ("ABA & Speech Available").
        *   `location_address` -> Address line.

### 2.4 Service Cards (`template-parts/card-service.php`)
*   **Source:** `Index.html` (Lines 263-300)
*   **Usage:** Used on Home and Services page.
*   **Icons:** Map Lucide icons (`puzzle`, `message-circle`, etc.) to specific Service Pages.

### 2.5 Footer (`footer.php`)
*   **Source:** `Index.html` (Lines 602-648)
*   **Logic:**
    *   Dynamic Sidebar (Widgets) or Hardcoded 4-column grid?
    *   *Recommendation:* Hardcode the strict grid layout for pixel perfection as per `WHITELABEL-GUIDE` preference for control, with dynamic `wp_nav_menu` for links.

## 3. Asset Integration
*   **CSS:** Import `assets/css/input.css` in the theme. Copy the `tailwind.config` colors from HTML to local config.
*   **JS:**
    *   `lucide.createIcons()` -> Call in `assets/js/main.js`.
    *   `navigateTo()` -> Replace with native `<a href="">` links (Remove SPA-like JS unless using HTMX/Barba, but standard WP routing is safer for SEO).
    *   *Note:* The HTML uses a single-page "Section" visibility toggle (`navigateTo` in JS). We will **REPLACE** this with standard multi-page WordPress routing for better SEO and deep linking.

## 4. Special Features
*   **Zip Code Search:**
    *   Source: `locations.html` (Lines 283-288)
    *   Implementation: Custom JS module `assets/js/modules/zip-search.js` that checks an array of valid zips (managed via Options Page or hardcoded list if static).
