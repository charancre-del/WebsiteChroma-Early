# Pediatric Therapy Terminology Audit

Date: March 3, 2026

## Scope

This audit reviewed the `earlystart-early-learning-theme` codebase for legacy childcare-era terminology and brand copy that conflicted with the current Chroma Early Start pediatric therapy positioning.

The audit covered:

- Frontend template copy
- Seeded fallback content
- SEO metadata and schema defaults
- Customizer defaults
- Admin metabox defaults and helper labels
- Frontend JavaScript labels

This was a codebase audit only. It does not update content already saved in the WordPress database unless those pages are reseeded or manually edited.

## Refactors Completed

The following areas were updated from childcare / campus / academy wording to pediatric therapy / clinic / Chroma Early Start wording:

- Homepage and shared location copy
- City archive and city single templates
- Location archive terminology
- About page static fallback copy and About page seeded defaults
- Contact page seeded career prompt
- Careers metabox placeholders and default job locations
- Employers page frontend fallbacks, seeded defaults, and metabox defaults
- Families page FAQ fallback language
- Privacy and Terms page frontend fallbacks
- Schedule Tour page copy
- Footer program label (`Bridge Program`)
- Design system demo label (`New Clinic`)
- AMP blog CTA copy
- Homepage customizer defaults and homepage data fallbacks
- Location schema defaults (`MedicalBusiness`)
- SEO schema and fallback metadata in `inc/seo-engine.php`
- Generated city-page SEO titles, descriptions, and keywords
- Location FAQ fallback questions and answers
- Schema metabox helper labels/documentation
- Map popup fallback label (`View clinic`)
- Theme metadata tags in `style.css`

## Remaining Intentional Exceptions

The following references remain on purpose for backward compatibility or historical data handling:

- `inc/seo-keywords-data.php`
  - This file still contains legacy keyword phrases gathered from historical SEO data.
  - The active keyword output path in `inc/seo-engine.php` now filters those phrases before rendering meta keywords.

- `inc/seo-engine.php`
  - The legacy keyword filter still contains terms like `daycare`, `child care`, and `childcare` because it needs those exact strings to strip them from output.

- `assets/js/admin-llm.js`
  - `ChildCare` is still accepted as a recognized schema type for backward compatibility when previewing older saved schema JSON in admin.

- `inc/seed-content.php`
  - Several seeded location slugs still use `-campus` in the slug key.
  - This was left unchanged to avoid breaking existing URLs, post lookups, and seeded slug expectations.
  - The visible seeded titles now use `Clinic`.

- `generate-city-pages.php`
  - The generator still strips legacy source labels like `Campus` when normalizing imported names.
  - This is compatibility logic for old source data, not frontend copy.

## Risk Notes

- Existing pages with manually edited content may still contain old childcare-era language in the database even though the fallback/template code is now updated.
- Existing metabox values saved before this refactor will continue to render until they are edited or reseeded.
- Historical blog post titles and slugs may still contain legacy topic language if they were intentionally published that way.

## Recommended Follow-Up

1. Rerun the content seeder on staging so the updated fallback meta is written into seeded pages.
2. Audit live post/page content in wp-admin for manually entered legacy copy that overrides template fallbacks.
3. If desired, run a separate database/content migration to replace legacy terminology inside already-saved post meta and page content.
