# QA Acceptance Checklist: Early Start

**Status:** Draft  
**Target:** QA Team / Product Owner

## 1. Global Visual Acceptance
*   [ ] **Typography:** "Plus Jakarta Sans" loads correctly (400, 500, 600, 700 weights). No fallback serif fonts visible.
*   [ ] **Color Palette:**
    *   Primary Buttons: Rose 600 (`#e11d48`)
    *   Secondary/Hover: Rose 700 or Orange 600 (`#ea580c`).
    *   Backgrounds: Stone 50 (`#fafaf9`) or White.
*   **Responsiveness:**
    *   [ ] Mobile (375px): Menu hamburger works, no horizontal scroll.
    *   [ ] Tablet (768px): Grid columns stack correctly (2-col -> 1-col).
    *   [ ] Desktop (1440px): Content centered, max-width 7xl (1280px).
*   **Icons:** All Lucide icons render (no missing squares).

## 2. Page-Specific Checks

### 2.1 Home Page
*   [ ] **Hero Animation:** "Prism" rotates/animates smoothly.
*   [ ] **Service Grid:** All 4 cards link to correct pages. Hover effects work.
*   [ ] **Tabbed Section:** Clicking "ABA" / "Speech" / "OT" switches content instantly (JS check).
*   [ ] **Forms:** "Request Consultation" CTA opens form or goes to Contact page.
*   [ ] **Service Grid:** Clicking "Age Range" badges leads to filtered view (if applicable) or Service Page.

### 2.2 Programs & Services
*   [ ] **Archive (`page-programs.php`):**
    *   Grid displays all CPT items (ABA, Speech, etc.).
    *   Cards show correct colors (Rose/Orange) mapped from `program_color_scheme`.
    *   Prismpath Diagram on this page renders correctly.
*   [ ] **Single Service (`single-program.php`):**
    *   **Hero:** Background gradient matches selected color scheme.
    *   **Chart:** Spider chart renders with data from `program_prism_*` meta fields.
    *   **Schedule:** Interactive "Day in the Life" bubbles toggle content correctly.
    *   **PDF Download:** "View Lesson Plan" button appears only if file uploaded.

### 2.2 Locations Archive
*   [ ] **List Rendering:** All locations from CPT are visible.
*   [ ] **Zip Code Search:** Entering a valid zip (e.g. "30043") shows success message. Invalid zip shows error.
*   [ ] **Map:** Interactive map loads (if API key provided) or Placeholder image displays.

### 2.3 Contact Page
*   [ ] **Form Submission:** Filling the form sends email to admin (check WP Mail Log).
*   [ ] **Validation:** Empty required fields trigger browser or JS alerts.

## 3. Technical Acceptance

### 3.1 Performance (Lighthouse)
*   [ ] **LCP (Largest Contentful Paint):** < 2.5s (Mobile).
*   [ ] **CLS (Cumulative Layout Shift):** < 0.1.
*   [ ] **Total Blocking Time:** < 200ms.

### 3.2 SEO & Schema
*   [ ] **Meta Titles:** `[Page Title] | Early Start`
*   [ ] **Meta Descriptions:** Present and unique.
*   [ ] **Schema.org:**
    *   `Organization` or `LocalBusiness` schema present on Home.
    *   `Service` schema on Service pages.

### 3.3 Functional
*   [ ] **Console Errors:** Zero red errors in DevTools Console.
*   [ ] **Broken Links:** Run Link Checker; no 404s on internal nav.
*   [ ] **PHP Logs:** No warnings/notices in `debug.log`.

## 4. Deploy Sign-off
*   [ ] Staging Verified by: _________________ (Date: __/__)
*   [ ] Production Deployed by: _________________ (Date: __/__)
