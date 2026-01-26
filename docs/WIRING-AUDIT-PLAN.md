# Wiring Audit Plan: Early Start Theme

This plan details the exhaustive validation of all data flows from backend settings to frontend templates.

## 1. Global Theme Settings
**Source**: `inc/theme-settings.php` (Native Options: `earlystart_global_settings`)
**Helpers**: `inc/acf-options.php` (Getters: `earlystart_global_phone`, etc.)

| Setting ID | Expected Output | Consumer Template(s) | Wiring Check Functions |
| :--- | :--- | :--- | :--- |
| `global_phone` | `(555) 123-4567` | `header.php`, `footer.php`, `page-contact.php` | `earlystart_global_phone()` |
| `global_email` | `hello@...` | `footer.php`, `page-contact.php` | `earlystart_global_email()` |
| `global_address` | `123 Wellness...` | `footer.php`, `page-contact.php` | `earlystart_global_full_address()` |
| `global_social_links` | Facebook, Insta URL | `footer.php`, `page-contact.php` | `earlystart_global_facebook_url()` etc. |

## 2. Header & Navigation
**Source**: `inc/customizer-header.php`

| Setting Key | Consumer Template | Verification Method |
| :--- | :--- | :--- |
| `earlystart_header_text` | `header.php` | Verify strict split of "Bold Line" vs "Tagline" logic. |
| `earlystart_book_tour_url` | `header.php` | Verify `earlystart_get_theme_mod` key match. |
| `earlystart_header_cta_text` | `header.php` | Check default "Book a Tour" vs custom overrides. |

## 3. Home Page Sections
**Source**: `inc/customizer-home.php`
**Intermediary**: `inc/homepage-data.php` (Logic: Meta -> Customizer -> Default)

| Section | Data Source Key | Template Part | Critical Check |
| :--- | :--- | :--- | :--- |
| **Hero** | `earlystart_home_hero_heading` | `template-parts/home/hero.php` | HTML parsing (span classes preserved). |
| **Stats** | `earlystart_home_stats_json` | `template-parts/home/stats-strip.php` | JSON decoding & loop integrity. |
| **Prismpath** | `earlystart_home_prismpath_cards_json` | `template-parts/home/prismpath-expertise.php` | Icon class output (FontAwesome vs Lucide). |
| **Services** | `earlystart_home_services_json` | `template-parts/home/services-tabs.php` | Tab ID matching content ID. |
| **Curriculum** | `earlystart_home_curriculum_profiles_json` | `template-parts/home/curriculum-chart.php` | Radar chart data formatting. |
| **Reviews** | `earlystart_home_parent_reviews_json` | `template-parts/home/parent-reviews.php` | Star rating integer loop. |
| **FAQ** | `earlystart_home_faq_items_json` | `template-parts/home/faq.php` | Schema.org JSON-LD generation check. |
| **Locations** | `earlystart_home_locations_heading` | `template-parts/home/locations-preview.php` | Dynamic heading vs hardcoded fallback. |

## 4. Page Templates & Meta
**Source**: Native Meta Boxes (`inc/*-page-meta.php`)

| Template | Meta Keys (Prefix) | Consumer File | Notes |
| :--- | :--- | :--- | :--- |
| `page-about.php` | `about_hero_*`, `about_stat_*` | `page-about.php` | Verify stats loop logic. |
| `page-programs.php` | (Archive) | `archive-program.php` | Check "Program Wizard" data source. |
| `page-locations.php` | (Archive) | `archive-location.php` | Verify Mapbox/Leaflet data attributes. |
| `page-contact.php` | `contact_hero_*` | `page-contact.php` | Check form shortcode integration. |

## 5. Custom Post Types (Single)
**Source**: Native Meta Boxes (`inc/cpt-*.php`)

| CPT | Meta Keys | Template | Notes |
| :--- | :--- | :--- | :--- |
| **Program** | `program_icon`, `program_color_scheme` | `single-program.php` | Verify color injection (CSS variables). |
| **Location** | `location_address`, `location_phone` | `single-location.php` | Verify dynamic "Book Tour" link parameter. |

## Execution Protocol
1.  **Code Inspection**: Grep for all keys in templates to ensure they match registration.
2.  **Visual Verification**: (User) Toggle settings in Customizer and observe instant preview.
3.  **Data Integrity**: Test JSON fields with broken/malformed JSON to check fallback safety.
