# Chroma Early Start Childcare Content Audit

Date: 2026-07-09

## Executive Summary

Chroma Early Start has mostly moved the primary service positioning toward ABA, speech, OT, autism assessment, behavioral assessment, and ADHD assessment. The production site still carries a large amount of old childcare / preschool / early-learning language in three places:

1. Public pages and blog posts that are still indexed in the live sitemap.
2. Saved WordPress meta, especially schema, SEO, LLM, and MetaSync fields.
3. Theme and plugin defaults that can reintroduce the old language after edits, migrations, or content regeneration.

The highest-risk issues are not the homepage hero or the current program cards. The bigger issues are schema output on core and city pages, the public Bridge/Curriculum/Schedule Tour content model, old blog posts about preschool and classrooms, and saved database content that still describes the organization as childcare, preschool, Pre-K, or early learning.

## Scope Checked

- Local source tree, excluding nested repos `Wptstchroma` and `base-repo`.
- Production sitemap crawl: `https://chromaearlystart.com/sitemap.xml?codex_audit=20260709`.
- Production WordPress read-only checks over SSH using WP-CLI.
- Active production components:
  - Theme: `earlystart-early-learning-theme` version `1.0.8`.
  - Plugins: `chroma-agent-api`, `earlystart-contact-form`, `earlystart-seo-pro`, `metasync`, `webp-express`, `wppusher`.
  - The old `earlystart-tour-form` plugin exists locally but is not active in production.

## Current Live Evidence

The production crawl checked 185 sitemap URLs and found 80 affected URLs.

Live crawl category totals:

| Category | Affected URLs | Hits |
| --- | ---: | ---: |
| Credential / childcare licensing | 72 | 132 |
| Hard childcare terms | 106 | 507 |
| School framing | 184 | 1171 |
| Tour / enrollment language | 73 | 256 |

Affected URL types:

| URL type | Count |
| --- | ---: |
| Blog posts | 30 |
| Core pages / taxonomy views / location pages | 23 |
| City / pediatric-therapy location pages | 24 |
| Other | 3 |

Top public URLs needing review or rewrite:

| URL | Issue |
| --- | --- |
| `/bridge-program/` | Bridge Program still uses classroom / Pre-K / childcare licensing framing. |
| `/curriculum/` | Page title is therapy-oriented, but source/content model still uses curriculum and old school-readiness framing. |
| `/schedule-tour/` | Old tour flow remains public; should become intake consultation / contact instead of school tour. |
| `/about/` | Schema and copy still reference childcare credentials and old early-learning history. |
| `/locations/` and city pages | Schema repeatedly outputs DECAL, Bright from the Start, licensed childcare, Quality Rated, or similar credentials. |
| `/team/` | Some bios still emphasize early childhood care / Quality Rated history. |
| `/2026/03/09/how-to-choose-the-right-preschool/` | Published blog post is directly preschool-focused. |
| `/2026/03/09/common-accommodations-in-early-school/` | Published blog post is school/classroom-focused. |
| `/2026/02/28/in-clinic-aba-therapy-for-toddlers-and-preschoolers/` | Published blog post uses preschooler positioning. |
| `/2026/02/28/school-based-aba-and-classroom-support-services/` | Published blog post emphasizes classroom/school services. |

## Current Database Evidence

Read-only WP-CLI checks found old language in published posts, postmeta, options, and transients.

Database category totals:

| Category | Post hits | Postmeta hits | Option hits |
| --- | ---: | ---: | ---: |
| Legacy brand terms | 0 | 194 | 24 |
| Hard childcare terms | 17 | 145 | 29 |
| School framing | 100 | 255 | 44 |
| Tour / enrollment language | 68 | 113 | 34 |
| Credential / childcare licensing | 1 | 131 | 3 |

Largest saved-content sources:

| Meta key | Hits |
| --- | ---: |
| `_earlystart_post_schemas` | 85 |
| `seo_llm_key_differentiators` | 26 |
| `_wp_attachment_image_alt` | 23 |
| `_wp_attached_file` | 22 |
| `_wp_attachment_metadata` | 22 |
| `_earlystart_es_content` | 21 |
| `_metasync_otto_image_alt_data` | 19 |
| `metasync_schema_markup` | 19 |
| `_metasync_otto_structured_data` | 19 |
| `_earlystart_ai_fallback_cache` | 19 |
| `_metasync_otto_keywords` | 18 |
| `location_hero_subtitle` | 15 |
| `location_description` | 15 |
| `location_seo_content_text` | 15 |
| `seo_llm_target_queries` | 13 |

Specific term counts:

| Term | Posts | Postmeta | Options |
| --- | ---: | ---: | ---: |
| Chroma Early Learning | 0 | 66 | 6 |
| Early Learning Academy | 0 | 56 | 4 |
| Early Start Early Learning | 0 | 15 | 7 |
| Early Start Preschool | 0 | 1 | 3 |
| childcare | 1 | 90 | 9 |
| preschool | 14 | 33 | 9 |
| Pre-K | 0 | 8 | 3 |
| daycare | 1 | 6 | 3 |
| tuition | 1 | 5 | 4 |
| classroom | 18 | 95 | 12 |
| early learners | 4 | 66 | 5 |
| teacher | 24 | 40 | 4 |
| curriculum | 15 | 23 | 13 |
| school readiness | 16 | 26 | 5 |
| enroll | 27 | 53 | 15 |
| enrollment | 24 | 33 | 11 |
| schedule a tour | 17 | 9 | 3 |
| now enrolling | 0 | 15 | 0 |
| Georgia Department of Early Care and Learning | 0 | 70 | 0 |
| Bright from the Start | 0 | 32 | 0 |
| DECAL | 0 | 26 | 0 |
| Quality Rated | 1 | 3 | 3 |

## Source Code Evidence

Local source inventory scanned 283 PHP, Markdown, CSS, and template files in the theme, plugins, and docs.

Source category totals:

| Category | Files | Hits |
| --- | ---: | ---: |
| Legacy brand terms | 7 | 12 |
| Childcare credentials | 16 | 60 |
| Childcare / daycare terms | 11 | 46 |
| Education / classroom framing | 52 | 298 |
| School-age programs | 26 | 140 |
| Tour / enrollment language | 47 | 185 |

Important local files:

| File | Why it matters |
| --- | --- |
| `earlystart-early-learning-theme/page-bridge-program.php` | Contains Bridge Program, school readiness, classroom, and clinical Pre-K framing. |
| `earlystart-early-learning-theme/page-curriculum.php` | Public page still backed by curriculum-style content. |
| `earlystart-early-learning-theme/template-parts/home/curriculum.php` | Can reintroduce curriculum copy on homepage sections. |
| `earlystart-early-learning-theme/page-schedule-tour.php` | Public "Schedule a Tour" flow should be replaced or retired. |
| `earlystart-early-learning-theme/page-families.php` | Contains enrollment and school-readiness style language. |
| `earlystart-early-learning-theme/page-about.php` | Old origin story includes classroom-style positioning. |
| `earlystart-early-learning-theme/page-stories.php` | Uses classroom / educator framing. |
| `earlystart-early-learning-theme/page-employers.php` | Uses tuition-subsidy framing that sounds like childcare. |
| `earlystart-early-learning-theme/inc/cpt-locations.php` | Location meta still supports childcare-style fields. |
| `plugins/earlystart-seo-pro/inc/meta-boxes/class-location-advanced-schema.php` | Schema fields include DECAL / Quality Rated / childcare credentials. |
| `plugins/earlystart-seo-pro/inc/schema-builders/class-schema-injector.php` | Live schema output is where many public credential hits come from. |
| `plugins/earlystart-seo-pro/inc/class-homepage-translation-admin.php` | Prompt text still describes the site as a pediatric therapy early learning academy. |
| `plugins/earlystart-seo-pro/inc/class-multilingual-manager.php` | Translation/admin source has many old childcare terms. |
| `plugins/earlystart-tour-form/earlystart-tour-form.php` | Inactive on production, but still contains old age groups like Infant, Toddler, Preschool, Pre-K, AfterSchool/Summer Camp. |

## Classification

### P0: Public Identity Conflicts

These items make Chroma Early Start look like childcare, preschool, or an early learning academy instead of a therapy and assessment provider.

- Published preschool/daycare/classroom blog posts.
- Public `/bridge-program/`, `/curriculum/`, and `/schedule-tour/` pages.
- Schema on core pages and city pages describing the organization as licensed childcare, child care center, Bright from the Start, DECAL, or Quality Rated.
- Saved SEO/LLM/meta content using "Early Learning Academy", "Chroma Early Learning", "Early Start Preschool", "now enrolling", and similar terms.

### P1: Conversion Flow Mismatch

The site now needs intake and inquiry language, not tours and enrollment.

- `/schedule-tour/` should become consultation / inquiry / intake, or be redirected to the current compliant GHL inquiry form page.
- "Book a tour", "schedule a tour", "tour form", "now enrolling", and "enrollment" should be changed to "request a consultation", "start intake", "inquiry", "care coordination", or "admissions support" depending on context.

### P1: Schema and SEO Contamination

Schema is a major issue because it is repeated across many pages and may affect search engines and AI systems even when humans do not see the terms.

- `_earlystart_post_schemas`, MetaSync structured data, and SEO LLM fields contain the highest volume of old identity terms.
- The schema builder and location advanced schema metabox still allow or generate childcare credentials.
- Cached AI and MetaSync fields can preserve old terms after visible copy is fixed.

### P2: Valid But Needs Tone Review

Some language should not be blanket-deleted:

- "Children", "pediatric", "early intervention", "family", "caregiver", and "developmental" are valid for ABA, speech, OT, and assessment.
- "School" may be valid only when talking about IEP advocacy, school collaboration, or school-based support. It should not frame Chroma Early Start as a school, preschool, classroom, or childcare center.
- Team bios can mention professional background in early childhood care if factual, but the current site should not position that history as the active business model.

### P3: Internal Compatibility / Historical Docs

These are lower priority and may not need removal:

- Historical docs such as `docs/PRODUCTION-LAUNCH-AUDIT.md`.
- Output normalizers that contain legacy strings only to clean or block old content.
- Migration compatibility code that references old terms only to replace them.

## Fix Plan

### Phase 1: Stop Public Reintroduction

1. Update schema generation so childcare credentials are not emitted by default.
2. Remove or gate DECAL, Bright from the Start, Quality Rated, licensed childcare, child care center, and early childhood education credentials from public schema.
3. Confirm whether any real legal/license requirement needs those terms. If not required, remove them from all public schema and LLM outputs.
4. Add a source-level guard in the SEO plugin so generated schema prefers "Pediatric Therapy Clinic", "ABA Therapy Provider", "Speech Therapy Provider", "Occupational Therapy Provider", and "Assessment Provider".

### Phase 2: Replace Tour / Enrollment Flow

1. Retire or rewrite `/schedule-tour/` as an intake consultation page.
2. Replace CTAs:
   - "Schedule a Tour" -> "Request a Consultation"
   - "Book a Tour" -> "Start Intake"
   - "Tour Form" -> "Inquiry Form"
   - "Now Enrolling" -> "Accepting New Families" or "Now Scheduling Assessments"
   - "Enrollment" -> "Intake" or "Admissions Support"
3. Keep the inactive `earlystart-tour-form` plugin inactive. If no longer needed, remove it after verifying no shortcode references remain.

### Phase 3: Rewrite Public Page Families

Rewrite or retire these pages first:

1. `/bridge-program/`
   - Replace "Clinical Pre-K" and classroom readiness with "group readiness", "social participation", "communication readiness", or "care transition support".
2. `/curriculum/`
   - Rename internally and visually to "Clinical Approach" or "Care Model".
   - Keep Chroma Care if desired, but remove curriculum/classroom/school framing.
3. `/about/`
   - Keep founder and mission copy, but remove "classroom" identity language.
4. `/locations/` and individual location pages
   - Remove childcare licensing schema and location metadata.
   - Keep address, phone, services, age range, accessibility, therapy modalities, and assessment availability.
5. `/team/`
   - Keep credentials and factual background, but avoid making early childhood care / Quality Rated the current business identity.

### Phase 4: Clean Published Blog Content

For blog posts, do not mechanically replace every word. Decide per post:

1. Rewrite posts that are relevant to therapy:
   - ABA support, school collaboration, social skills, caregiver coaching, IEP advocacy, assessments.
2. Noindex, draft, or redirect posts that are pure childcare/preschool:
   - "How to Choose the Right Preschool"
   - daycare / tuition / preschool selection topics
3. Update post titles, excerpts, schema, MetaSync fields, and hidden SEO fields after rewriting.

### Phase 5: Database Migration

Create an idempotent WP migration that:

1. Updates saved page/meta values for known safe replacements.
2. Clears generated/cached fields that should regenerate:
   - `_earlystart_post_schemas`
   - `_earlystart_ai_fallback_cache`
   - `_earlystart_es_content` only after checking whether it is displayed
   - MetaSync structured data and keyword fields where old childcare terms remain
   - `earlystart_llms_txt` transients
   - OTTO stale/suggestion transients
3. Does not alter factual team biography text without human review.
4. Does not delete compatibility normalizer strings that are only used to block old output.

Suggested replacement map:

| Old wording | Replacement direction |
| --- | --- |
| childcare / daycare | pediatric therapy / therapy clinic / care team |
| preschool / Pre-K | early childhood therapy / young children / readiness support |
| curriculum | clinical approach / care model / therapy framework |
| classroom | therapy setting / group setting / school setting only when literally school-related |
| teacher / educators | clinicians / therapists / care team unless referring to outside school partners |
| enrollment | intake / admissions / care onboarding |
| schedule a tour / book a tour | request a consultation / start intake |
| tuition | service cost / insurance and payment options |
| DECAL / Bright from the Start / Quality Rated | remove from active service schema unless legally required |
| Early Learning Academy / Chroma Early Learning | Chroma Early Start |

### Phase 6: Verification

After fixes, verify with:

1. Source search excluding docs, migrations, and compatibility filters:
   - no public templates output hard childcare terms.
   - no plugin-generated schema emits childcare credentials by default.
2. Production DB counts:
   - zero published post/page hits for preschool/daycare/tour/enrollment identity terms, except intentionally approved blog topics.
   - zero public schema meta hits for DECAL/Bright from the Start/licensed childcare unless approved.
3. Production sitemap crawl:
   - no P0 terms on core pages.
   - no childcare credential schema in rendered HTML.
4. Browser spot checks:
   - homepage
   - services
   - autism assessment
   - behavioral assessment
   - ADHD assessment
   - contact
   - locations
   - bridge/care-model replacement page
5. Structured data validation on representative core and city pages.

## Recommended Work Order

1. Fix schema generator and clear schema/meta caches first, because those issues appear across 72+ live URLs.
2. Replace `/schedule-tour/` with consultation/intake language and route CTAs to the compliant GHL inquiry form.
3. Rewrite or retire `/bridge-program/` and `/curriculum/`.
4. Clean location pages and location metadata.
5. Clean top blog posts, starting with preschool/daycare/tour/enrollment titles.
6. Run the DB migration and production crawl again.

## Open Business Confirmation

Before deleting every credential reference, confirm whether Chroma Early Start has any current, legally required childcare license disclosures. If the business is not actively presenting childcare services, the recommendation is to remove DECAL, Bright from the Start, Quality Rated, licensed childcare, child care center, preschool, Pre-K, daycare, and enrollment language from public pages and schema.
