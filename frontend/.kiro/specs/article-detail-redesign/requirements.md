# Requirements Document

## Introduction

This feature redesigns the Article Detail page (`frontend/src/views/articles/DetailView.vue`) so that its visual treatment aligns with the brand identity of PT Cakrawala Parama Internasional. The visual system shifts from a single-orange palette to a **red-600 primary / orange-600 secondary** pairing, and adopts a dual-family typography model: **Montserrat** for headings and **Quicksand** for body and meta text. All non-visual behaviors of the page (reactive SEO via `useHead`, i18n locale switching, article fetching, table-of-contents click handling, heading id normalization, smooth scroll with a −100px navbar offset, image error fallback, and date formatting) are preserved without regression.

The implementation is constrained to three files: `frontend/index.html` (replacing a malformed multi-`href` Google Fonts link with a single well-formed link that includes Quicksand), `frontend/src/style.css` (registering a `--font-quicksand` token and brand color aliases inside the Tailwind v4 `@theme` block), and `frontend/src/views/articles/DetailView.vue` (template class updates and scoped style adjustments). No API, store, router, or `<script setup>` logic changes.

## Glossary

- **DetailView**: The Vue Single-File Component at `frontend/src/views/articles/DetailView.vue` that renders one article.
- **ArticleDetailPage**: The full rendered page produced by DetailView, including hero, article body, sticky sidebar, and related grid.
- **FontLoader**: The `<link>` element block in `frontend/index.html` that requests web fonts from Google Fonts.
- **ThemeTokens**: The `@theme` block in `frontend/src/style.css` that registers Tailwind v4 design tokens (color and font CSS variables).
- **HeadingIdNormalizer**: The `normalizeHeadingIds()` function inside DetailView that assigns ids to `<h2>`/`<h3>`/`<h4>` headings inside `<article>` so TOC anchors resolve.
- **TocHandler**: The `handleContentClick(e)` function inside DetailView that intercepts in-content anchor clicks, performs id resolution, and triggers a smooth scroll.
- **SeoHead**: The reactive head/meta declaration produced by `useHead(...)` from `@unhead/vue` inside DetailView.
- **LocaleResolver**: The set of inline ternary expressions in the DetailView template that pick an `_en` field when `locale.value === 'en'` and falls back to the base field otherwise.
- **Article**: The article payload returned by the Laravel API, with shape defined in the design's Data Models section.
- **PrimaryColor**: Tailwind class `red-600` (hex `#dc2626`).
- **SecondaryColor**: Tailwind class `orange-600` (hex `#ea580c`).
- **HeadingFont**: The Montserrat web font family.
- **BodyFont**: The Quicksand web font family.
- **TocContainer**: The element selected by `div.bg-slate-50` inside the rendered article HTML; this selector is treated as a contract used by HeadingIdNormalizer.
- **NavbarOffset**: The `−100px` vertical offset applied by TocHandler when smooth-scrolling so headings clear the fixed navbar.
- **WCAG AA**: The Web Content Accessibility Guidelines 2.1 Level AA contrast thresholds (≥ 4.5:1 for normal text, ≥ 3:1 for large or bold text).

## Requirements

### Requirement 1: Brand Color Application

**User Story:** As a visitor reading an article, I want the page to use the company's brand color pairing, so that the article detail experience is visually consistent with the rest of the Cakrawala identity.

#### Acceptance Criteria

1. THE DetailView SHALL render the category badge with `bg-red-50` as the background color and `text-red-600` as the foreground text color.
2. THE DetailView SHALL render the article-body `<h2>` element with a bottom border whose color is `red-100`.
3. THE DetailView SHALL render the article-body `<blockquote>` element with a left border colored `red-600`, a background colored `red-50`, and body text colored `red-900`.
4. THE DetailView SHALL render the latest-articles section accent bar using `red-600` as its color.
5. THE DetailView SHALL render the related-articles section accent bar using `red-600` as its color.
6. THE DetailView SHALL render the call-to-action card background as a top-left to bottom-right linear gradient (`bg-gradient-to-br`) with three color stops: `red-600` at the start position, `red-600` at the middle position, and `orange-600` at the end position.
7. THE DetailView SHALL render prose `<a>` links inside the article body using `text-orange-600` as the link text color in their default (non-hovered) state.
8. WHILE a user is hovering a prose `<a>` link inside the article body, THE DetailView SHALL display a text underline on that link.
9. THE DetailView SHALL render the icons in the meta-row using `text-red-500` as the icon color.
10. WHILE a user is hovering a latest-article sidebar item, THE DetailView SHALL set the item background color to `red-50` and the item border color to `red-100`.

### Requirement 2: Typography Family Application

**User Story:** As a visitor reading an article, I want headings and body copy to use distinct, brand-aligned font families, so that the page reads as professionally typeset.

#### Acceptance Criteria

1. THE DetailView SHALL apply HeadingFont (Montserrat) to the hero `<h1>` and to article-body `<h2>`, `<h3>`, and `<h4>` elements.
2. THE DetailView SHALL apply HeadingFont (Montserrat) to the section labels of the latest-articles sidebar and the related-articles section, the CTA heading, the CTA button label, the share-button labels, the category-badge eyebrow, and the latest-article item eyebrow.
3. THE DetailView SHALL apply BodyFont (Quicksand) to article-body `<p>` and `<li>` elements, the meta-row text, the latest-article item title, and the CTA description paragraph.
4. THE DetailView SHALL apply BodyFont (Quicksand) to the article-body container as the baseline font family via the `font-quicksand` utility class.
5. WHERE a heading element is rendered with `font-montserrat`, THE DetailView SHALL set its computed `font-family` to a value beginning with `Montserrat`.
6. WHERE a body element is rendered with `font-quicksand`, THE DetailView SHALL set its computed `font-family` to a value beginning with `Quicksand`.

### Requirement 3: Font Loading Correctness

**User Story:** As a visitor opening an article page, I want web fonts to actually load, so that branded typography appears instead of system fallbacks.

#### Acceptance Criteria

1. THE FontLoader SHALL include exactly one `<link rel="stylesheet">` element pointing at `fonts.googleapis.com/css2` that requests Inter, Montserrat, Poppins, and Quicksand in a single combined query string.
2. THE FontLoader SHALL request Quicksand with weights `400;500;600;700`.
3. THE FontLoader SHALL include `display=swap` in the Google Fonts query string.
4. THE FontLoader SHALL include `<link rel="preconnect" href="https://fonts.googleapis.com">` and `<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>` before the stylesheet link.
5. WHEN the page is loaded over a working network, THE FontLoader SHALL produce exactly one network request to `fonts.googleapis.com/css2` for the combined family list specified in 3.1.
6. IF Quicksand specifically fails to load, THEN THE DetailView SHALL fall back through the chain Quicksand → Montserrat → `sans-serif` for any element styled with the `font-quicksand` utility class, and the fallback chain SHALL allow multiple fallbacks to remain active simultaneously rather than requiring exclusive use of one family.
7. WHILE Quicksand loads successfully, THE DetailView SHALL keep Quicksand active for `font-quicksand` elements regardless of whether other web fonts in the system (such as Montserrat) failed to load.
8. IF Montserrat fails to load, THEN THE DetailView SHALL fall back to `sans-serif` for any element styled with the `font-montserrat` utility class.

### Requirement 4: Theme Token Registration

**User Story:** As a developer styling the page with Tailwind utilities, I want brand fonts and colors to be available as design tokens, so that utility classes like `font-quicksand` and `text-primary` resolve to the intended values.

#### Acceptance Criteria

1. THE ThemeTokens SHALL declare `--font-quicksand` with the exact value `"Quicksand", "Montserrat", sans-serif` inside the `@theme` block such that the Tailwind utility `font-quicksand` resolves to this font stack.
2. THE ThemeTokens SHALL declare `--color-primary` with the exact value `var(--color-red-600)` inside the `@theme` block such that the Tailwind utility `text-primary` resolves to the red-600 color value.
3. THE ThemeTokens SHALL declare `--color-secondary` with the exact value `var(--color-orange-600)` inside the `@theme` block such that the Tailwind utility `text-secondary` resolves to the orange-600 color value.
4. THE ThemeTokens SHALL retain the existing `--font-sans`, `--font-montserrat`, `--font-poppins`, and `--color-brand` declarations with their current values unchanged and in the same `@theme` block, so that any utility class already depending on them continues to resolve to the same value as before this change.
5. THE ThemeTokens SHALL declare `--color-primary-hover` with the exact value `var(--color-red-700)` inside the `@theme` block such that the Tailwind utility `bg-primary-hover` resolves to the red-700 color value.
6. THE ThemeTokens SHALL declare `--color-secondary-hover` with the exact value `var(--color-orange-700)` inside the `@theme` block such that the Tailwind utility `bg-secondary-hover` resolves to the orange-700 color value.

### Requirement 5: Reactive SEO Preservation

**User Story:** As a visitor or as a search-engine crawler reading an article URL, I want the page metadata to reflect the current article and the current locale, so that titles, descriptions, and Open Graph previews are accurate.

#### Acceptance Criteria

1. WHILE an article is rendered, THE SeoHead SHALL set `<title>` to `"<resolved title> - Cakrawala"`, where `<resolved title>` is `article.title_en` when `locale.value === 'en'` and `article.title_en` is non-null, and otherwise `article.title`.
2. WHILE an article is rendered, THE SeoHead SHALL set the `og:title` meta content equal to the current `<title>` value.
3. WHILE an article is rendered, THE SeoHead SHALL set the `og:description` meta content using the resolution chain: `article.short_description_en` when `locale.value === 'en'` and `article.short_description_en` is non-null, otherwise `article.short_description` when non-null, otherwise `article.excerpt` when non-null, otherwise an empty string.
4. WHILE an article is rendered, THE SeoHead SHALL set the `og:image` meta content to the URL produced by `getImageUrl(article.image)`, or to an empty string when `article.image` is null.
5. THE SeoHead SHALL set the `twitter:card` meta content to `summary_large_image`.
6. WHEN the active i18n locale changes, THE SeoHead SHALL re-evaluate `<title>`, `og:title`, `og:description`, `og:image`, and `twitter:*` meta tags reactively using the resolution rules in 5.1 through 5.5, without an imperative `document.title = ...` assignment.

### Requirement 6: Locale-Aware Content Rendering

**User Story:** As a visitor toggling between Indonesian and English, I want every text region of the article page to switch language consistently, so that I never see mixed-locale content.

#### Acceptance Criteria

1. WHILE `locale.value === 'en'` and the corresponding `_en` field is non-null, THE LocaleResolver SHALL render the `_en` variant for `article.title`, `article.short_description`, `article.content`, `article.category.name`, latest-article titles, latest-article category names, related-article titles, and related-article category names.
2. IF `locale.value === 'en'` and the corresponding `_en` field is null, THEN THE LocaleResolver SHALL fall back to the base (non-suffixed) field.
3. WHILE `locale.value !== 'en'`, THE LocaleResolver SHALL render the base (non-suffixed) field for every locale-aware text region listed in 6.1.
4. WHEN `formatDate(dateString)` is called, THE DetailView SHALL format the date using locale `en-US` if `locale.value === 'en'` and locale `id-ID` otherwise, with the option `{ day: 'numeric', month: 'long', year: 'numeric' }`.
5. IF `dateString` is falsy, THEN `formatDate` SHALL return the string `"-"`.
6. WHILE `locale.value === 'en'`, THE DetailView SHALL render the CTA heading as `"Need Help?"` and the CTA description as `"Contact our team for a free consultation."`; otherwise THE DetailView SHALL render the CTA heading as `"Butuh Bantuan?"` and the CTA description as `"Hubungi tim kami untuk konsultasi gratis."`.
7. WHILE `locale.value === 'en'`, THE DetailView SHALL render the CTA button label as `"Start Now"`; otherwise THE DetailView SHALL render the CTA button label as `"Mulai Sekarang"`.
8. WHILE `locale.value === 'en'`, THE DetailView SHALL render the latest-articles sidebar section label as `"Latest Articles"`; otherwise THE DetailView SHALL render that section label as `"Artikel Terbaru"`.

### Requirement 7: Article Data Fetching

**User Story:** As a visitor navigating to an article URL, I want the page to load the article, latest articles, and related articles, so that I can read the article and discover others.

#### Acceptance Criteria

1. WHEN DetailView mounts with a `slug` route parameter, THE DetailView SHALL call `fetchDetail(slug)` to load the article.
2. WHEN `fetchDetail(slug)` resolves successfully, THE DetailView SHALL populate `article.value`, trigger `fetchLatest()` and `fetchRelated(category_id, current_article_id)`, set `isLoading` to `false`, and queue `normalizeHeadingIds()` via `nextTick`.
3. IF `fetchDetail(slug)` fails with a 404 or network error, THEN THE DetailView SHALL invoke `router.push({ name: 'NotFound' })` and SHALL set `isLoading` to `false`.
4. IF `fetchLatest()` fails, THEN THE DetailView SHALL log the error to the console and SHALL render the latest-articles section with an empty list.
5. IF `fetchRelated(...)` fails, THEN THE DetailView SHALL log the error to the console and SHALL hide the related grid via `v-if="relatedArticles.length > 0"`.
6. WHEN building the related list, THE DetailView SHALL exclude the currently displayed article id and SHALL display at most three related items.

### Requirement 8: Heading Id Normalization

**User Story:** As a visitor reading an article that contains a table of contents, I want every TOC link target to exist on the page, so that clicking a TOC entry can scroll to the right heading.

#### Acceptance Criteria

1. WHEN article content has just been rendered via `v-html` and `nextTick` fires, THE HeadingIdNormalizer SHALL run against `document.querySelector('article')`.
2. THE HeadingIdNormalizer SHALL select the TocContainer using the selector `div.bg-slate-50`, exactly as written.
3. WHEN a TocContainer is found, THE HeadingIdNormalizer SHALL match each TOC link `href="#X"` to a heading using, in order, exact-text match, contains match, contained-in match, and any-unused fallback, and SHALL assign `id = "X"` to the matched heading.
4. WHEN a TocContainer is not found, THE HeadingIdNormalizer SHALL assign each heading without an id a value derived from `generateIdFromText(heading.textContent)`, falling back to `"h-" + index` when generation yields an empty string.
5. WHEN the HeadingIdNormalizer returns successfully, THE HeadingIdNormalizer SHALL guarantee that every `<h2>`, `<h3>`, and `<h4>` inside `<article>` has a non-empty `id`.
6. THE HeadingIdNormalizer SHALL ensure that no two headings produced by TOC matching share the same `id`, and SHALL apply the fallback id generation specified in 8.4 to any heading not matched by the TOC pass so that the postcondition in 8.5 holds.

### Requirement 9: Table-of-Contents Click Handling

**User Story:** As a visitor clicking a table-of-contents link, I want the page to smooth-scroll to the right heading without a full-page navigation, so that I can jump within long articles seamlessly.

#### Acceptance Criteria

1. WHEN a click event bubbles to the `<article>` element and the click target's nearest `<a>` has an `href` containing `#` and is local (starts with `#`, `.`, or does not contain `://`), THE TocHandler SHALL call `e.preventDefault()` and `e.stopPropagation()`.
2. WHEN a TOC link click is intercepted, THE TocHandler SHALL resolve the target heading using a five-strategy lookup in the order: `getElementById`, `[name="..."]` selector, `section-N` numeric index match against `article h2, article h3, article h4`, `generateIdFromText` match, and keyword-overlap match using tokens longer than two characters.
3. WHEN a target heading is resolved, THE TocHandler SHALL scroll the window to `max(0, target.getBoundingClientRect().top + window.scrollY − 100)` with `behavior: "smooth"` via a `setTimeout(0, ...)` deferred call.
4. WHEN a target heading is resolved, THE TocHandler SHALL update `window.history` using `replaceState(null, "", hash)` so the URL hash equals the clicked link's hash.
5. IF no heading can be resolved by any of the five strategies, THEN THE TocHandler SHALL log an error to the console, SHALL leave the current scroll position unchanged, and SHALL leave the current URL hash unchanged.
6. WHEN the TocHandler intercepts an in-content TOC link click, THE TocHandler SHALL resolve the click in-page only and SHALL prevent any full-page navigation from occurring.

### Requirement 10: Loading State Contract

**User Story:** As a visitor on a slow connection, I want a clear loading state before the article appears, so that I know the page is working.

#### Acceptance Criteria

1. WHILE `isLoading.value === true`, THE DetailView SHALL render a skeleton container with four placeholder blocks and SHALL render no `<article v-html="...">` element.
2. WHILE the skeleton is rendered, THE DetailView SHALL set `aria-busy="true"` and `aria-live="polite"` on the skeleton container.
3. WHEN `isLoading` transitions from `true` to `false` and `article.value` is non-null, THE DetailView SHALL unmount the skeleton, mount the `<article>` element, and queue `normalizeHeadingIds()` via `nextTick`.

### Requirement 11: Color Contrast Compliance

**User Story:** As a visitor with low vision, I want all text on the article page to meet WCAG AA contrast, so that I can read it.

#### Acceptance Criteria

1. THE DetailView SHALL render meta-row text using `text-slate-500` (replacing the previous `text-slate-400`) on a white card surface so the foreground/background contrast ratio is at least 4.5:1.
2. THE DetailView SHALL render `red-600` text and `orange-600` text on a white surface only where the contrast ratio is at least 4.5:1 for normal text.
3. THE DetailView SHALL render the CTA button label using `red-600` on `white`, achieving a contrast ratio of at least 4.5:1.
4. WHERE a foreground/background pair is used for large or bold text only, THE DetailView SHALL ensure the contrast ratio is at least 3:1.

### Requirement 12: Focus Visibility

**User Story:** As a keyboard user, I want a visible focus indicator on every interactive element of the article page, so that I can see where I am.

#### Acceptance Criteria

1. WHEN a share button receives keyboard focus, THE DetailView SHALL display a focus ring using `focus-visible:ring-2`, `focus-visible:ring-{red,orange}-500`, and `focus-visible:ring-offset-2`.
2. WHEN a latest-article sidebar link receives keyboard focus, THE DetailView SHALL display a focus ring using `focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2`.
3. WHEN a related-article link receives keyboard focus, THE DetailView SHALL display a focus ring using `focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2`.
4. WHEN the CTA button receives keyboard focus, THE DetailView SHALL display a focus ring using `focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-red-600`.

### Requirement 13: Reduced Motion Support

**User Story:** As a visitor who has enabled reduced motion in their operating system, I want the article page to respect that preference, so that motion does not trigger discomfort.

#### Acceptance Criteria

1. WHILE the user agent reports `prefers-reduced-motion: reduce`, THE DetailView SHALL disable the `.detail-fade-in` keyframe animation and SHALL leave other motion effects (such as hover transitions, scale transforms, and loading skeleton pulses) unchanged.
2. WHILE the user agent reports `prefers-reduced-motion: reduce`, THE DetailView SHALL set `html { scroll-behavior: auto }` to disable CSS-driven smooth scrolling.

### Requirement 14: Image Fallback Behavior

**User Story:** As a visitor on a page where an article image fails to load, I want a placeholder rendered instead of a broken image, so that the page still looks intact.

#### Acceptance Criteria

1. WHEN any `<img>` rendered by DetailView (featured image, sidebar thumbnail, related thumbnail) raises an `error` event, THE DetailView SHALL invoke `handleImageError` to swap the `src` to a fallback placeholder.
2. WHEN an article has `image === null`, THE DetailView SHALL resolve the image URL via `getImageUrl(null)` and apply the same `handleImageError` fallback path.

### Requirement 15: Sticky Sidebar

**User Story:** As a visitor scrolling a long article on a desktop viewport, I want the sidebar to stay visible alongside the content, so that latest articles and the CTA remain reachable.

#### Acceptance Criteria

1. WHILE the viewport width meets the Tailwind `lg` breakpoint, THE DetailView SHALL render the aside as `sticky top-24 space-y-8`.
2. WHILE the user scrolls past the article header at the `lg` breakpoint or wider, THE sticky sidebar SHALL remain visible at the `top-24` offset.

### Requirement 16: Style Encapsulation

**User Story:** As a developer maintaining other pages of the frontend, I want the redesign of the article page to not leak styles globally, so that other views remain unaffected.

#### Acceptance Criteria

1. THE redesign SHALL add new global declarations only inside the existing `@theme` block of `frontend/src/style.css`, limited to `--font-quicksand`, `--color-primary`, `--color-primary-hover`, `--color-secondary`, and `--color-secondary-hover`.
2. THE redesign SHALL place all other CSS changes inside the `<style scoped>` block of DetailView, using `:deep()` for selectors that target `v-html`-rendered content.
3. THE redesign SHALL confine global stylesheet additions and modifications to the `@theme` declarations enumerated in 16.1, leaving every other global selector outside that block unchanged.

### Requirement 17: Source File Scope

**User Story:** As a reviewer of the redesign pull request, I want a small, well-bounded change set, so that the review is fast and the risk of regression is low.

#### Acceptance Criteria

1. THE redesign SHALL modify only the files `frontend/index.html`, `frontend/src/style.css`, and `frontend/src/views/articles/DetailView.vue`.
2. WHERE changes are made to `frontend/src/views/articles/DetailView.vue`, THE redesign SHALL confine those changes to the `<template>` and `<style scoped>` blocks of the file, leaving the `<script setup>` block byte-equivalent to its pre-redesign state.
3. THE redesign SHALL leave every backend file, API endpoint, Pinia store, and router configuration byte-equivalent to its pre-redesign state.
4. THE redesign SHALL leave `package.json` and the dependency lockfile byte-equivalent to their pre-redesign states (no added, removed, or upgraded npm dependencies).

### Requirement 18: Behavior Preservation

**User Story:** As a visitor who relied on existing page behaviors, I want every non-visual feature to keep working exactly as before, so that nothing I depend on regresses.

#### Acceptance Criteria

1. THE DetailView SHALL preserve the existing `handleImageError` swap-on-error behavior on every `<img>` element it renders.
2. THE DetailView SHALL preserve the existing `formatDate` locale-aware date formatting (`en-US` when `locale.value === 'en'`, otherwise `id-ID`).
3. THE DetailView SHALL preserve the existing related-article filter that excludes the current article id and slices the result to three items.
4. WHILE the user agent does not report `prefers-reduced-motion: reduce`, THE DetailView SHALL preserve the existing `.detail-fade-in` keyframe animation with a duration of `0.5s` and timing function `ease-out`.
5. THE DetailView SHALL preserve the existing sticky sidebar engagement at the `lg` breakpoint with `sticky top-24`.
6. THE HeadingIdNormalizer SHALL continue to use the literal selector `div.bg-slate-50` to locate the TocContainer.
7. THE TocHandler SHALL continue to apply a NavbarOffset of `−100` pixels when computing the smooth-scroll target.
