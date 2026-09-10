---
paths:
  - 'templates/**'
  - 'resources/views/site/**'
---

# Templates

## One active sector template per deployment; corporate is the minimal contract, real-estate the sector-model example
Each sector site lives in templates/<key>/ (src/Template.php extends App\Templates\Template, views/home.blade.php, css/app.css, js/app.js; optionally routes.php, database/migrations, src/Models, src/Http, src/Seeders/DemoSeeder.php). APP_TEMPLATE selects the single active template (default: corporate); classes autoload via TemplateManager's spl autoloader (Templates\<Studly>\ → templates/<kebab>/src/), never add PSR-4 entries to composer.json. Do not put sector code in app/. Deliver with `template:prune`.

## Core fallback views: template files win, resources/views/site fills the gaps
TemplateServiceProvider registers the `template::` namespace with two paths: templates/<key>/views first, resources/views/site second; anonymous components resolve the same way (templates/<key>/views/components, then resources/views/site/components). A template only has to ship views/home.blade.php; layouts/app, partials/header|footer|footer-links, pages/show, contact, services/*, products/*, blog/*, team/* and the card components come from resources/views/site. To customise one, copy it to the same relative path under the template's views/ (see templates/real-estate/views/partials/footer-links.blade.php). Keep resources/views/site sector-neutral: wording that varies by sector goes through settings (header_button_text/url, contact_intro, team_title/team_intro, *_intro) or Template hooks (icon(), fonts(), contactSubjects()) and is overridden in the template's defaultSettings(). Do NOT put fallback components in resources/views/components — Blade checks that default namespace before registered component paths, so templates could not override them.

## contact_messages.email is NOT NULL and used as Reply-To
Sector forms that make e-mail optional must store '' when empty and pass ContactController::notify() a clone whose email is the site address; otherwise Symfony rejects the empty Reply-To and the notification silently fails.
