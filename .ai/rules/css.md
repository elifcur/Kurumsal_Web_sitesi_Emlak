---
paths:
  - 'templates/*/css/app.css'
  - 'resources/css/base.css'
---

# Css

## Shared classes and default tokens live in base.css; templates only override @theme
resources/css/base.css (imported by every template's css/app.css right after tailwindcss) declares the neutral design tokens (--color-primary/-dark/-light/-contrast, --color-accent/-dark, --color-sand, --color-ink, --font-sans, --font-display) and every component class the core views need (.btn-*, .section*, .eyebrow, .link-arrow, .card, .chip, .nav-link*, .logo-grid, .hero-overlay, .page-overlay, .form-*, .prose-cms, .container-x). It also carries the @source lines for resources/views/site, components, errors and pagination. A template's app.css should contain `@source '../views/**/*.blade.php'`, a `@theme` block that overrides the colour tokens, and only sector-specific classes (e.g. real-estate's .badge-sale, .search-*). Redefining a shared class after the import is allowed (cascade order wins) but avoid it unless the sector design demands it.

## Visual language: serif display headings, photo hero, accent underline
All templates share one design DNA: Inter body + Instrument Serif h1/h2 (`*word*` → italic accent via emphasize()), full-width photo hero (`setting('hero_image')`, dark side gradient via .hero-overlay, left-aligned white heading), serif section h2 with a short accent underline (.section-line) and an underlined "Tümünü Gör →" link (.link-arrow), photo-topped cards with small radius, stats strip with icons and a quote, dark 4-column footer. Sector identity comes from the @theme colours: corporate blue #1d4ed8 + amber, real-estate green #1f3d2e + gold.
