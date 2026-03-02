# Google Business Profile Upload Prep

- File: `Google-Business-Profile-Upload-Ready-en.csv`
- Rows: 19

## Applied Choices
- Website set to location slug URLs: `https://earlystarttherapy.com/locations/{slug}/`
- Hours set to Mon-Fri `08:00-17:00`, Sat/Sun `x` (closed).
- Optional field populated: `From the business`.
- Optional field populated: `Labels` with region and services.
- Category strategy set for therapy-first positioning:
  - Primary: `Behavioral therapist`
  - Additional: `Speech pathologist`, `Occupational therapist`, `Child care agency`

## Online Verification
- `5760 Wade Whelchel Rd` validated via U.S. Census Geocoder as `MURRAYVILLE, GA 30564`.
- North Hall row updated to ZIP `30564` and city `Murrayville`.

## Remaining Confirmation Needed
- Primary phone per location (currently blank in all rows).
- If GBP UI rejects `Behavioral therapist`, fallback primary recommendation: `Speech pathologist` (with same additional categories).
