# Implementation Plan: Article Detail Redesign

## Overview

Implement the visual redesign of `frontend/src/views/articles/DetailView.vue` so it matches the brand identity (red-600 primary / orange-600 secondary, Montserrat headings, Quicksand body) without changing any non-visual behavior. The change set is bounded to three files: `frontend/index.html` (Google Fonts link), `frontend/src/style.css` (Tailwind v4 `@theme` tokens), and `frontend/src/views/articles/DetailView.vue` (`<template>` and `<style scoped>` only — `<script setup>` is untouched).

Tasks are sequenced so the foundation (font loading + design tokens) lands first, then template/style edits flow region by region through `DetailView.vue`. Property-based verification tasks are listed last and marked optional, matching the design's "Property-Based Testing Approach: Not applicable" guidance — they capture how each correctness property could be checked if the team chooses to automate verification, but they are not required for the redesign to ship.

## Tasks

- [x] 1. Update font loading and Tailwind theme tokens
  - [x] 1.1 Fix the Google Fonts `<link>` block in `frontend/index.html`
    - Remove the malformed multi-`href` element so the parser does not silently drop Quicksand/Montserrat/Poppins
    - Insert one well-formed `<link rel="stylesheet">` requesting `Inter`, `Montserrat`, `Poppins`, and `Quicksand:wght@400;500;600;700` in a single `family=...&family=...` query with `display=swap`
    - Keep `<link rel="preconnect" href="https://fonts.googleapis.com">` and `<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>` immediately before the stylesheet link
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 17.1, 17.4_

  - [x] 1.2 Register brand tokens in the `@theme` block of `frontend/src/style.css`
    - Add `--font-quicksand: "Quicksand", "Montserrat", sans-serif;`
    - Add `--color-primary: var(--color-red-600);`, `--color-primary-hover: var(--color-red-700);`, `--color-secondary: var(--color-orange-600);`, `--color-secondary-hover: var(--color-orange-700);`
    - Preserve existing `--font-sans`, `--font-montserrat`, `--font-poppins`, and `--color-brand` declarations exactly as-is
    - Make no other changes to `style.css` outside `@theme`
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6, 16.1, 16.3, 17.1_

- [x] 2. Update DetailView hero region (template only)
  - [x] 2.1 Restyle category badge, hero `<h1>`, meta row, share buttons, and featured image
    - Category badge: `bg-orange-50 text-orange-600` → `bg-red-50 text-red-600`; add `font-montserrat`
    - Hero `<h1>`: add `font-montserrat tracking-tight`
    - Meta row: add `font-quicksand`; icons `text-orange-400` → `text-red-500`; text `text-slate-400` → `text-slate-500` (AA contrast)
    - Share buttons: pink/blue palette → red/orange brand pair; add `font-montserrat`; add `focus-visible:ring-2 focus-visible:ring-{red,orange}-500 focus-visible:ring-offset-2`
    - Featured image wrapper: add `shadow-red-100/50` on hover
    - Touch only `<template>` markup; do not modify `<script setup>`
    - _Requirements: 1.1, 1.9, 2.1, 2.2, 2.3, 11.1, 11.2, 12.1, 17.1, 17.2_

- [x] 3. Update DetailView article body container (template only)
  - [x] 3.1 Update `<article class="article-body ...">` class list
    - Add `font-quicksand` as the body baseline
    - Keep `prose prose-slate prose-lg max-w-none`, `prose-img:rounded-xl`
    - Set link colors via `prose-a:text-orange-600 prose-a:no-underline hover:prose-a:underline`
    - Preserve `@click="handleContentClick"` and `v-html` exactly
    - _Requirements: 1.7, 1.8, 2.3, 2.4, 17.1, 17.2_

- [x] 4. Update DetailView sidebar (template only)
  - [x] 4.1 Restyle latest-articles section and CTA card
    - Latest section heading: add `font-montserrat` and an accent bar `<span class="w-6 h-0.5 bg-red-600 rounded-full"></span>`
    - Latest item link: `bg-slate-50 hover:bg-red-50 border border-transparent hover:border-red-100`; add `focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2`
    - Latest item eyebrow: `text-red-600`, `font-montserrat`
    - Latest item title: `font-quicksand`, `group-hover:text-red-600`
    - CTA card background: `bg-gradient-to-br from-red-600 via-red-600 to-orange-600`
    - CTA heading: `font-montserrat`; CTA paragraph: `font-quicksand text-red-50/90`
    - CTA copy bilingual: `'Need Help?' / 'Butuh Bantuan?'` and `'Contact our team for a free consultation.' / 'Hubungi tim kami untuk konsultasi gratis.'` — keep the existing `locale === 'en' ? ... : ...` ternary pattern
    - CTA button: `bg-white text-red-600 hover:bg-red-50 hover:text-red-700`, `font-montserrat font-bold uppercase tracking-wider`, plus `focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-red-600`
    - _Requirements: 1.4, 1.6, 1.10, 2.2, 2.3, 6.6, 11.3, 12.2, 12.4, 15.1, 15.2, 17.1, 17.2_

- [x] 5. Update DetailView related-articles grid (template only)
  - [x] 5.1 Restyle related-articles section labels and cards
    - Section heading: `font-montserrat` + accent bar `bg-red-600`
    - Card eyebrow: `text-red-600 font-montserrat`
    - Card title: `font-quicksand`, hover color `group-hover:text-red-600`
    - Card hover shadow: `shadow-red-100/50`
    - Card link: `focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2`
    - Keep existing image render with `getImageUrl` + `@error="handleImageError"`
    - _Requirements: 1.5, 2.2, 2.3, 12.3, 14.1, 17.1, 17.2_

- [x] 6. Update DetailView scoped styles
  - [x] 6.1 Rewrite `:deep()` rules in `<style scoped>` to the new palette
    - `:deep(h2)`: `font-family: 'Montserrat', sans-serif`, `font-weight: 800`, color `#0f172a`, `border-bottom: 2px solid var(--color-red-100)`
    - `:deep(h3)` and `:deep(h4)`: `font-family: 'Montserrat', sans-serif`; preserve existing sizes/weights
    - `:deep(p)` and `:deep(li)`: `font-family: 'Quicksand', sans-serif`, `line-height: 1.8`
    - `:deep(blockquote)`: `font-family: 'Quicksand', sans-serif`, `border-left: 4px solid var(--color-red-600)`, `background: var(--color-red-50)`, color `#7f1d1d`, `font-style: italic`, `border-radius: 0 0.5rem 0.5rem 0`, padding/margin per design
    - `:deep(strong)`: color `var(--color-red-700)`
    - Keep all changes inside the `<style scoped>` block — no global selectors
    - _Requirements: 1.2, 1.3, 2.1, 2.3, 11.4, 16.2, 16.3, 17.2_

  - [x] 6.2 Add `prefers-reduced-motion` media query in `<style scoped>`
    - Add `@media (prefers-reduced-motion: reduce) { .detail-fade-in { animation: none; } html { scroll-behavior: auto; } }`
    - Leave hover transitions, scale transforms, and skeleton pulse animations untouched
    - _Requirements: 13.1, 13.2, 16.2, 17.2_

- [x] 7. Verify loading skeleton accessibility wiring (template only)
  - [x] 7.1 Ensure the `v-if="isLoading"` skeleton container declares `aria-busy="true"` and `aria-live="polite"` and renders four placeholder blocks
    - Read the current skeleton block; add the two ARIA attributes if missing
    - Keep the four `bg-slate-100` placeholders and their pulse animation
    - Do not touch `<script setup>` or `isLoading` semantics
    - _Requirements: 10.1, 10.2, 10.3, 17.2_

- [x] 8. Checkpoint — verify the build compiles
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 9. Optional verification of redesign correctness properties
  - [ ]* 9.1 Verify Property 1: SEO_PRESERVED
    - **Property 1: SEO_PRESERVED**
    - **Validates: Requirements 5.1, 5.2, 5.3, 5.4, 5.5, 5.6**
    - Render DetailView with a fixture article and assert `document.title === '<resolved title> - Cakrawala'`, `meta[og:title].content === document.title`, `meta[og:description].content !== ''`, `meta[og:image].content` resolves correctly, and `meta[twitter:card].content === 'summary_large_image'`. Repeat the assertion after toggling locale.

  - [ ]* 9.2 Verify Property 2: I18N_LOCALE_SWITCH
    - **Property 2: I18N_LOCALE_SWITCH**
    - **Validates: Requirements 6.1, 6.2, 6.3, 6.4, 6.5, 6.6**
    - With a fixture that has all `_en` fields populated, toggle `locale.value` between `'id'` and `'en'` and assert title, short_description, content, category name, latest titles, related titles, and CTA copy switch in lockstep. Assert `formatDate('2026-01-02')` returns an `id-ID` string when `locale === 'id'` and an `en-US` string when `locale === 'en'`. Assert `formatDate(null)` returns `'-'`.

  - [ ]* 9.3 Verify Property 3: TOC_NAVIGATION_WORKS
    - **Property 3: TOC_NAVIGATION_WORKS**
    - **Validates: Requirements 8.1, 8.2, 8.3, 8.4, 8.5, 8.6, 9.1, 9.2, 9.3, 9.4, 9.5, 9.6, 18.6, 18.7**
    - For an article whose `content` contains a `<div class="bg-slate-50">` TOC listing four anchors plus four `<h2>` headings, click each anchor and assert: `e.preventDefault()` is honored, `window.scrollTo` is called with `top === max(0, headingTop − 100)` and `behavior: 'smooth'`, and `window.location.hash` matches the link. Cover one heading where exact-text matches and one that requires the keyword-overlap fallback. Assert that an unmatched `#nope` anchor logs `console.error` and does not scroll.

  - [ ]* 9.4 Verify Property 4: COLOR_CONTRAST_AA
    - **Property 4: COLOR_CONTRAST_AA**
    - **Validates: Requirements 11.1, 11.2, 11.3, 11.4**
    - Run an accessibility/contrast audit (axe-core or equivalent) against the rendered page and assert zero contrast failures. Spot-check that meta-row text resolves to `slate-500` (≥ 4.5:1 on white) and that the CTA button label resolves to `red-600` on `white` (≥ 4.5:1).

  - [ ]* 9.5 Verify Property 5: FONT_FAMILY_CONSISTENCY
    - **Property 5: FONT_FAMILY_CONSISTENCY**
    - **Validates: Requirements 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 3.1, 3.2, 3.6, 3.7, 3.8, 4.1**
    - In a real browser harness, assert `getComputedStyle(h).fontFamily` starts with `Montserrat` for every `h1`–`h4` and for any element with `.font-montserrat`; assert it starts with `Quicksand` for every `<p>`/`<li>` inside `.article-body` and for any element with `.font-quicksand`. Assert `document.fonts.check('1em Quicksand') === true` and `document.fonts.check('1em Montserrat') === true`.

  - [ ]* 9.6 Verify Property 6: LOADING_STATE_CONTRACT
    - **Property 6: LOADING_STATE_CONTRACT**
    - **Validates: Requirements 10.1, 10.2, 10.3**
    - Mount the component with `isLoading.value === true` and assert the skeleton container has `aria-busy="true"`, `aria-live="polite"`, and four placeholder children, while no `<article>` is in the DOM. Flip `isLoading` to `false` with a populated `article.value` and assert the skeleton unmounts, `<article>` mounts, and `normalizeHeadingIds` runs (e.g. spy on it via `nextTick`).

  - [ ]* 9.7 Verify Property 7: BEHAVIOR_PRESERVED
    - **Property 7: BEHAVIOR_PRESERVED**
    - **Validates: Requirements 7.1, 7.2, 7.3, 7.4, 7.5, 7.6, 14.1, 14.2, 15.1, 15.2, 18.1, 18.2, 18.3, 18.4, 18.5, 18.6, 18.7**
    - Cover (1) `<img>` `error` event triggers `handleImageError` and swaps `src` to the placeholder for featured/sidebar/related images, (2) `formatDate` returns the locale-correct string and `'-'` for falsy input, (3) the related-articles list excludes the current article id and is capped at three items, (4) the `.detail-fade-in` keyframe still applies (and is suppressed under `prefers-reduced-motion`), (5) the sidebar is `position: sticky` with `top: 6rem` at `lg`, (6) `normalizeHeadingIds` still queries `div.bg-slate-50`, and (7) `handleContentClick` uses a `−100` navbar offset.

  - [ ]* 9.8 Verify Property 8: NO_GLOBAL_LEAKAGE
    - **Property 8: NO_GLOBAL_LEAKAGE**
    - **Validates: Requirements 16.1, 16.2, 16.3, 17.1, 17.2, 17.3, 17.4**
    - Diff `frontend/src/style.css` against the baseline and assert the only additions are inside `@theme` and limited to `--font-quicksand`, `--color-primary`, `--color-primary-hover`, `--color-secondary`, `--color-secondary-hover`. Diff `DetailView.vue` and assert all CSS additions live inside `<style scoped>`. Diff the change set and assert only `frontend/index.html`, `frontend/src/style.css`, and `frontend/src/views/articles/DetailView.vue` are modified, the `<script setup>` block of `DetailView.vue` is untouched, and `package.json` is unchanged.

- [x] 10. Final checkpoint — Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional verification tasks. The design states "Property-Based Testing Approach: Not applicable" because the redesign is styling-only and `<script setup>` is unchanged. The optional sub-tasks describe how each property *could* be asserted if the team chooses to automate verification, but skipping them does not block delivery.
- Every implementation task references granular requirement clauses for traceability.
- Checkpoints (8 and 10) gate progress so the build is verified before and after the optional verification pass.
- All `DetailView.vue` edits are confined to `<template>` and `<style scoped>`; `<script setup>` is never modified, which is what preserves Properties 1, 2, 3, 6, and 7 by construction.
- Task waves serialize all `DetailView.vue` edits because they all write the same file; only `index.html` and `style.css` edits run in parallel.

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "1.2"] },
    { "id": 1, "tasks": ["2.1"] },
    { "id": 2, "tasks": ["3.1"] },
    { "id": 3, "tasks": ["4.1"] },
    { "id": 4, "tasks": ["5.1"] },
    { "id": 5, "tasks": ["6.1"] },
    { "id": 6, "tasks": ["6.2"] },
    { "id": 7, "tasks": ["7.1"] },
    { "id": 8, "tasks": ["9.1", "9.2", "9.3", "9.4", "9.5", "9.6", "9.7", "9.8"] }
  ]
}
```
