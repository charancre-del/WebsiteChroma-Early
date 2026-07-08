# Chroma Early Start Production Launch Audit

Last updated: 2026-07-08

## Current Git State

- Branch: `main`
- Latest pushed commit before this final launch pass: `cbde6da Add launch build version marker`
- Pending final commit includes this audit note, the launch normalizer timing fix, and the final public build marker:
  - Theme `style.css` version: `1.0.4`
  - Theme `style.css` launch marker: `Launch Build: 2026-07-08.6`

## Production Deployment

Production URL: `https://chromaearlystart.com`

Live WordPress root verified over SSH:

- SSH user/host: `hg1qcbz@185.181.252.9`
- WordPress root: `/home/hg1qcbz/public_html`
- `home`: `https://chromaearlystart.com`
- `siteurl`: `https://chromaearlystart.com`
- Active theme: `earlystart-early-learning-theme`
- Active custom plugins verified: `chroma-agent-api`, `earlystart-contact-form`, `earlystart-seo-pro`

Pre-deploy production backup:

- `/home/hg1qcbz/wordpress-backups/chromaearlystart-prelaunch-20260708-145113.tar.gz`

Deployed source paths:

- `earlystart-early-learning-theme/` to `wp-content/themes/earlystart-early-learning-theme/`
- `plugins/chroma-agent-api/` to `wp-content/plugins/chroma-agent-api/`
- `plugins/earlystart-contact-form/` to `wp-content/plugins/earlystart-contact-form/`
- `plugins/earlystart-seo-pro/` to `wp-content/plugins/earlystart-seo-pro/`

Post-deploy server checks:

- Remote PHP lint passed for the active theme and deployed custom plugins.
- Launch migration ran through WP-CLI with `earlystart_launch_content_cleanup_version=2026-07-08.4`.
- WordPress object cache flushed.
- MetaSync minification cache cleared.
- MetaSync edge purge hook called.

## Live Production Evidence

Public build marker:

- Live `style.css` reports `Version: 1.0.4`.
- Live `style.css` reports `Launch Build: 2026-07-08.6`.

Core pages:

- `/` loads with HTTP 200.
- `/contact/` loads with HTTP 200 and title `Contact | Chroma Early Start`.
- `/privacy/` loads with HTTP 200 and title `Privacy Policy | Chroma Early Start`.
- `/terms/` loads with HTTP 200 and title `Terms of Service | Chroma Early Start`.
- `/careers/` loads with HTTP 200 and title `Careers at Chroma Early Start: Join Our Team | Chroma Early Start`.
- `/locations/`, `/services/`, `/parents/`, `/programs/aba-therapy/`, `/programs/speech-therapy/`, and `/programs/occupational-therapy/` load with HTTP 200 and no browser console errors in the Chrome sweep.

Content and SEO cleanup:

- 182 sitemap URLs from `/sitemap.xml` fetched with HTTP 200.
- Sitemap crawl found zero hits for the targeted legacy phrases:
  - `Chroma Early Learning`
  - `Early Learning Academy`
  - `Early Start Early Learning`
  - `earlystart Early Learning`
  - `Chrom Early Start`
  - `Daycare & Therapy`
  - `Daycare & ABA`
  - `childcare center`
  - `childcare centers`
- Spot checks confirmed old titles are gone from `/contact/`, `/careers/`, `/locations/`, `/services/`, `/parents/`, and `/locations/duluth/`.

A2P and contact form:

- Contact page embeds the GHL inquiry form ID `M3WZTpTW5KHrkzf5XfYG`.
- Contact page includes optional SMS opt-in copy, message/data rates language, STOP/HELP language, and Privacy Policy / Terms links.
- Contact form URL includes website lead-source tracking parameters.
- Privacy Policy includes the critical SMS no-sharing statement.
- Privacy Policy includes SMS opt-in details, cookie/tracking language, data use, security, and user-rights language.
- Terms page includes SMS program description, STOP opt-out instructions, support contact, message/data rates, carrier liability, age restriction, and Privacy Policy link.

Careers:

- Careers page renders job descriptions without raw escaped HTML tags.
- Careers page uses Acquire4Hire links and includes `careers@chromaela.com`.
- Official Acquire4Hire Indeed XML feed `https://app.acquire4hire.com/feed/indeed.xml?id=8154` returned HTTP 200 with `application/xml`.
- XML feed smoke check counted 6 `<job>` records.

Agent API and technical endpoints:

- `/wp-json/chroma-agent/v1/geo-feed` returns contract `2026-02-28.4`.
- GEO feed list records include `canonical_url`, `url`, and `location_url`.
- `/feed/` returns 404 instead of 500.

## Remaining Notes

- No production blocker remains from the launch audit.
- Nested local repos `Wptstchroma` and `base-repo` are dirty but were intentionally not touched for this deployment.
- The remote backup should be retained until the site has been live without regressions for the desired rollback window.
