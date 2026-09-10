---
paths:
  - 'templates/**'
---

# Templates

## One active sector template per deployment; follow templates/real-estate as the contract
Each sector site lives in templates/<key>/ (src/Template.php extends App\Templates\Template, views/, css/app.css, js/app.js, routes.php, database/migrations, src/Seeders/DemoSeeder.php). APP_TEMPLATE selects the single active template; classes autoload via TemplateManager's spl autoloader (Templates\<Studly>\ → templates/<kebab>/src/), never add PSR-4 entries to composer.json. Templates must provide every template::* view the core controllers render for the features they enable (services, products, blog, team) and must define .btn-primary/.section/.section-title/.eyebrow/.card in css/app.css because resources/views/errors use them. Do not put sector code in app/. Deliver with `template:prune`.

## contact_messages.email is NOT NULL and used as Reply-To
Sector forms that make e-mail optional (e.g. business quote form) must store '' when empty and pass ContactController::notify() a clone whose email is the site address; otherwise Symfony rejects the empty Reply-To and the notification silently fails. See Templates\Business\Http\Controllers\Public\QuoteController::notifiable().
