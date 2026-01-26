# Build Notes: Chroma Early Start Theme

## Environment Setup
*   **Operating System**: Windows
*   **PHP Version**: 8.x (Target)
*   **Node.js**: Expected for Tailwind build
*   **Base Repo**: [chroma-excellence-theme](file:///c:/Users/chara/Documents/WebsiteChroma%20Early/base-repo/chroma-excellence-theme)

## Local Commands
```powershell
# In theme directory:
npm install
npm run dev     # Watch mode
npm run build   # Production build
```

## Seeding Automation
To automatically create all core pages and populate meta boxes:
1.  Log in to the WordPress Admin.
2.  Navigate to: `your-site-url/wp-admin/?earlystart_seed=1`
3.  The script will create Home, About, Services, Contact, etc., and populate them with default content.

## Global Search & Replace (Namespace Refactor)
The following mapping was used to refactor the base theme:
- `chroma-excellence` -> `chroma-early-start` (Text Domain)
- `chroma_` -> `earlystart_` (Function Prefix)
- `Chroma_Excellence` -> `Chroma_Early_Start` (Class/Namespace)
- `Chroma Excellence` -> `Chroma Early Start` (Human-readable)

## CHANGELOG
All changes are tracked below:

| Date | File | Action | Reason |
| :--- | :--- | :--- | :--- |
| 2026-01-25 | `chroma-early-start-theme/` | Created | Cloned from `chroma-excellence-theme` |

## Seed Content & Setup Steps
To rapidly populate the site:

1.  **Activate Theme**: Go to Appearance -> Themes and activate **Chroma Early Start Theme**.
2.  **Plugin Requirements**: None (ACF Dependency Removed).
3.  **Create Core Pages**:

## Architecture Updates
### Removal of ACF Dependency
- **Theme Settings**: Migrated from ACF Options Page to native WordPress Settings API (`inc/theme-settings.php`).
- **Global Data**: Updated `inc/acf-options.php` (legacy name kept for file compat) to act as a pure getter helper.
- **Homepage Data**: Renamed `inc/acf-homepage.php` to `inc/homepage-data.php` and verified purely native data handling.
- **Seeding**: Updated `seed-content.php` to populate native options table keys (`earlystart_global_settings`) instead of ACF fields.
- **Validation**: Zero instances of `get_field()` or `have_rows()` remain in the active codebase.
    *   **Home**: Create a page named "Home", set template to "Default", and set as Front Page in Settings -> Reading.
    *   **About**: Create a page named "About Us", set template to "About Us".
    *   **Approach**: Create a page named "Our Approach", set template to "Our Approach".
    *   **Services**: Create a page named "Services", set template to "Programs".
    *   **Contact**: Create a page named "Contact", set template to "Contact".
4.  **Create Programs**:
    *   Add new **Programs** (ABA Therapy, Speech Therapy, Occupational Therapy, School Readiness).
    *   Assign icons and colors in the meta boxes.
5.  **Global Brand Settings**:
    *   Navigate to **Theme Settings** in the sidebar.
    *   Input Phone, Email, and Address.
    *   Add Social Links (Facebook, Instagram, LinkedIn).
6.  **Navigation**:
    *   Create a menu named "Main Menu" and assign it to the "Primary Menu" location.
    *   Add the core pages created in step 3.

### Customizer Wiring verified
- **Header**: Connected `header.php` to `earlystart_header_text` and `earlystart_book_tour_url`.
- **Home Page**: Verified `inc/homepage-data.php` implements a "Meta -> Customizer -> Default" fallback chain for all sections (Hero, Stats, Prismpath, Programs).
- **Wiring Status**: Fully Functional. 

