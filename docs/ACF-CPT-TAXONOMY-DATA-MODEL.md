# Data Model: Chroma Early Start

**Status:** Draft  
**Implementation:** Native Meta Boxes (ACF-Compatible Schema)

Although the repository uses native `add_meta_box` functions for performance (as per system blueprint), the data model below describes the fields as they would appear in an ACF Field Group for conceptual clarity.

## 1. Custom Post Types

### 1.1 Location (`location`)
**Supports:** Title, Editor, Thumbnail, Excerpt  
**Taxonomies:** `location_region` (Regions/Counties)

| Field Label | Metadata Key | Type | Description |
| :--- | :--- | :--- | :--- |
| **Hero Section** | | | |
| Hero Subtitle | `location_hero_subtitle` | Text | Small badge above title (e.g. "Now Enrolling") |
| Hero Gallery | `location_hero_gallery` | Text Area | List of image URLs (one per line) |
| Review Text | `location_hero_review_text` | Text Area | Featured review for hero |
| Review Author | `location_hero_review_author` | Text | Name of reviewer |
| **Location Stats** | | | |
| Ages Served | `location_ages_served` | Text | e.g., "18mo - 12yrs" |
| Special Programs | `location_special_programs` | Text Area | Comma-separated badges (e.g. "ABA, Speech") |
| License Number | `_chroma_license_number` | Text | DECAL License ID |
| Google Rating | `location_google_rating` | Text | e.g., "4.9" |
| Hours | `location_hours` | Text | e.g. "Mon-Fri 8am-6pm" |
| **Contact Info** | | | |
| Address | `location_address` | Text | Full street address |
| City | `location_city` | Text | |
| Validation | `location_state` | Text | |
| Zip | `location_zip` | Text | |
| Phone | `location_phone` | Text | |
| Email | `location_email` | Email | Location specific email |
| **Director** | | | |
| Director Name | `location_director_name` | Text | |
| Director Bio | `location_director_bio` | Text Area | |
| Director Photo | `location_director_photo` | Image URL | |

### 1.2 Program / Service (`program`)
**Supports:** Title, Editor, Thumbnail (Hero Image)
**Meta Fields:** (Derived from `single-program.php`)

| Field Label | Metadata Key | Type | Description |
| :--- | :--- | :--- | :--- |
| **Hero & General** | | | |
| Age Range | `program_age_range` | Text | "18mo - 5yrs" |
| Color Scheme | `program_color_scheme` | Select | red, blue, yellow, green, teal, orange |
| Lesson Plan URL | `program_lesson_plan_file` | File URL | PDF Link |
| Hero Title | `program_hero_title` | Text | Override Post Title |
| Hero Desc | `program_hero_description` | Text Area | Override Excerpt |
| **Prismpath Chart** | | | |
| Chart Title | `program_prism_title` | Text | "Our Clinical Focus" |
| Chart Description | `program_prism_description` | Text Area | |
| Chart Scores | `program_prism_physical`, `_emotional`, `_social`, `_academic`, `_creative` | Number (0-100) | Data points for the Spider Chart |
| Focus List | `program_prism_focus_items` | Repeater (Text) | List below chart |
| **Schedule** | | | |
| Schedule Title | `program_schedule_title` | Text | e.g. "Sample Session" |
| Schedule Items | `program_schedule_items` | Text Area | Format: `Time|Title|Description` (One per line) |

## 2. Page Meta Models

### 2.1 Home Page
**Template:** `front-page.php`

| Field Label | Metadata Key | Type | Description |
| :--- | :--- | :--- | :--- |
| **Hero** | | | |
| Hero Headline | `home_hero_headline` | Text | Main H1 |
| Hero Subhead | `home_hero_subhead` | Text Area | Intro paragraph |
| **Trust Bar** | | | |
| Trust Items | `home_trust_items` | Repeater (JSON) | List of {Icon, Title, Subtitle} |

## 3. Taxonomies

### 3.1 Location Region (`location_region`)
Used to group locations by county (Cobb, Gwinnett, etc.) for the Archive filter.

| Field Label | Metadata Key | Type | Description |
| :--- | :--- | :--- | :--- |
| Background Class | `region_color_bg` | Text | Tailwind class (e.g. `bg-rose-100`) |
| Text Color Class | `region_color_text` | Text | Tailwind class (e.g. `text-rose-600`) |

## 4. Options Page (Global Settings)
Managed via `inc/options-page.php`.

*   **Contact Info:** Global Phone, Email, Address.
*   **Social Links:** Facebook, Instagram URLs.
*   **API Keys:** Google Maps Key, OpenWeather Key.
