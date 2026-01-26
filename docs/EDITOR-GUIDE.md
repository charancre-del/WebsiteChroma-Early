# Editor Guide: Early Start Theme

Welcome to the Early Start WordPress editor guide. This document explains how to manage content for your pediatric therapy website.

## 1. Global Settings
Access global brand settings (Logo, Phone, Social Links) via:
**Theme Settings** (in the sidebar)

*   **Logo**: Upload the main logo here.
*   **Phone/Email**: These will appear in the header and footer.
*   **Social Links**: Adding URLs here will enable icons in the footer.

## 2. Managing Programs (Services)
Programs are managed under the **Programs** custom post type.
*   **Title**: The name of the therapy (e.g., ABA Therapy).
*   **Icon**: Use the name of a [Lucide Icon](https://lucide.dev/icons) (e.g., `puzzle`, `message-circle`, `hand`).
*   **Color Scheme**: Choose from Rose, Blue, Orange, Amber, or Emerald.
*   **PrismaPath™ Focus**: A radar chart is generated based on the Physical, Emotional, Social, Academic, and Creative values (0-100).

## 3. Locations
Manage clinics under the **Locations** custom post type.
*   **Address/Map**: Enter full address for Google Maps integration.
*   **Features**: Checkboxes for services offered at this specific clinic.
*   **Director**: Assign a team member as the clinical director.

## 4. Home Page
The home page is modular. Editing the page assigned as the **Front Page** in Settings -> Reading will reveal section-specific meta boxes:
*   **Hero Section**: Override the main title and subtext.
*   **Locations Preview**: Edit the "Serving Families" section heading.
*   **FAQ**: Edit the common questions shown on the home page.

## 5. Bilingual Support (Spanish)
Each meta box contains fields for **Spanish Overrides** (prefixed with [ES]). 
*   If a field is filled, the Spanish variant of the page will use that content.
*   To generate Spanish pages, use the **Spanish Variant Generator** tool in the admin sidebar.

## 6. Technical Builds
If you make changes to the layout or CSS classes (for developers):
1.  Navigate to the theme folder: `earlystart-early-learning-theme/`
2.  Run `npm run build` to recompile the Tailwind CSS.
