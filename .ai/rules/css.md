---
paths:
  - 'templates/*/css/app.css'
---

# Css

## Shared visual language: Statamic-inspired (light hero, serif display headings, dark primary button)
All templates share one design DNA modelled on statamic.com: light warm `.hero-bg` centred hero (never a dark hero), serif `--font-display` for h1/h2 with `*word*` → italic accent via the `emphasize()` helper, `.btn-primary` dark (slate-900) + `.btn-outline` thin border + `.btn-brand` for the sector colour, `.eyebrow` soft pill, `.logo-grid` thin-bordered partner grid right under the hero, section headings left-aligned with an outline "Tümünü Gör" on the right. Dark backgrounds only for the CTA band and footer. Sector identity (colours, icons, sector sections) stays. Reference: templates/real-estate.

## Visual language (revised 2026-09-09): photo-driven hero per sector mockup in images/
Supersedes the "light Statamic hero" rule. Each template follows its mockup in images/<sector>.png: full-width photo hero (`setting('hero_image')`, dark side gradient, left-aligned white heading with the accent-coloured second line via emphasize()), icon feature strip right under the hero, serif section h2 with a short accent underline and an underlined "Tümünü Gör →" link, photo-topped cards with small radius, stats strip with icons and a quote, dark 4-column footer. Demo photos/logos cropped from the mockups live in public/images/templates/<key>/ and are assigned by each DemoSeeder. Sector colours: real-estate green #1f3d2e + gold, clinic teal #0f6f85 (light hero overlay), industry navy + orange #f5841f, law black + gold #b8965a, business brown + orange #c2591b.
