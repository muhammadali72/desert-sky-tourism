# Desert Sky Tourism — premium travel redesign

User explicitly requested a full redesign on 24 October 2026, following the Dreamstour reference across home, listings, detail pages and CMS.

## Direction

A premium travel portal: oversized photographic banner, a floating glass navigation bar, frosted search panels, sculptural destination photography, destination/category browsing, feature cards, a real working review carousel and a clear booking journey. Use purposeful sections and different layouts for different page types. Do not retain the earlier sparse editorial layout.

## Typography

Self-hosted IBM Plex Sans for the public site; Jakarta and Inter for the dashboard. Service-led homepage headline at 48–64px desktop / 40px mobile. Body 17–18px, secondary labels 14–16px. Avoid system serif and tiny letterspaced body text.

## Brand tokens

- Ink #0b213a; text #142d45; muted #596c80
- Brand blue #0089ca; deep blue #00689e
- Yellow #f5cc16, used for meaningful accent and hero CTA
- Background #f5f8fc; white cards; border #e2e9f1
- Glass: white at 12–20% above images, 75–88% on pale backgrounds, 12–16px backdrop blur, fine translucent border. Text needs a strong scrim or opaque inner fields.

## Public layouts

- Homepage: service-led image banner, glass nav/search; compact benefits band; destinations; tab-filtered tours; two real image-led escape promotions; about image collage; attributed Google reviews in alternating vertical columns; travel journal; FAQs; newsletter signup; substantial footer.
- Listing: photographic page hero, breadcrumb, sidebar filters, category chips, 3-column cards, real search/sort.
- Tour: breadcrumb/category/title, 3-image gallery with fullscreen viewer, four fact tiles, section navigation, content/inclusions/highlights/FAQ in distinct panels, sticky glass booking card and related experiences.
- Journal: hero, featured story, editorial cards; single post has author/date, photograph, readable article and related travel links.
- Contact/About/Hotel: photographic introductions and structured content/callouts; no naked paragraphs beneath a plain heading.
- Booking/receipt: clear steps, better panel hierarchy and persistent order summary. Never present an unpaid session as a paid booking.

## CMS

Deep navy sidebar with consistent SVG icons; airy pale canvas, glass header; genuine statistics, operational overview, refined tables, clear editors with publish/SEO/image panels. Reviews support full editing and Trash/Restore. Sample reviews are visibly labelled and not presented as genuine customer feedback or aggregate rating claims.

## Motion and performance

Use CSS transitions (180–300ms), one-time IntersectionObserver reveals, native horizontal scroll snapping and user-triggered carousel navigation. Respect reduced motion. No autoplay video, animation framework, excessive blur, fabricated metrics, or copied template claims. Keep fonts local, images WebP/responsive, hero eagerly loaded and all lower images lazy.

## Skill evidence

UI/UX Pro Max design-system search: premium travel glassmorphism booking. Explicit style match: Glassmorphism, 10–20px blur, subtle borders, verified contrast/keyboard/reduced motion. Typography search returned premium DM Sans and geometric Outfit; user rejected the existing DM/Manrope treatment, so verified Google Fonts Plus Jakarta Sans + Inter is the project-specific choice. Layout choices follow the supplied Dreamstour reference, not the search's generic funnel recommendation.


## Final user-directed reference treatment

The user explicitly requested a closer reproduction of the reference on 24 October. The active implementation is `private/reference.php`, `public/assets/reference.css` and the shared tour/detail layout in `private/premium.php`. Homepage section order, centered photo-accent headings, mosaic, carousels, journey photos/statistics, dark benefits block and newsletter/contact footer follow the reference composition; the team section is omitted.

Typography now uses the reference's IBM Plex Sans, with 17–18px body copy, 17–18px navigation, 17px primary buttons and generally 14–16px secondary text. Public typography was audited in the browser to remove inherited 8–13px component text. Cart counters and decorative brand lettering are exceptions.

The homepage hero uses a single optimized desert campaign background with restrained scroll parallax, reduced-motion support and a visible motion toggle. The search panel follows the content in normal flow, preventing overlap at wide and narrow breakpoints. Avoid fake geometric masks or stale generic desert photography. Versioned asset filenames and private no-store HTML keep updates and account state accurate.

Inner pages add meaningful planning sections, FAQ blocks and contextual calls to action. Tour pages use a reference-style title/breadcrumb band, gallery/content column and adjacent tour facts/booking panels. Only actual CMS facts are displayed; do not invent capacity, supplier inclusions, review authenticity, client totals or availability.

Mobile outer gutters: exactly 15px. Image-strip grid rows use minmax(0,1fr) and images use min-height:0 to avoid intrinsic sizing overlap. Dashboard primary text is 16–17px, with metadata no smaller than 14px.
