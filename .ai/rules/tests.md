---
paths:
  - 'tests/**'
---

# Tests

## Core tests must pass under every template; skip via requiresFeature()
Routes for services/products/blog/team only exist when the active template enables them. Core tests that touch such routes call $this->requiresFeature('products') (Tests\TestCase) so they skip instead of failing. Template-specific tests go in tests/Feature/Templates/<Studly>TemplateTest.php and markTestSkipped in setUp when APP_TEMPLATE differs. Run `composer test:templates` to verify all templates before release.
