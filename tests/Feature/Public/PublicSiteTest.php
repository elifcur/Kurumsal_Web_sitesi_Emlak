<?php

namespace Tests\Feature\Public;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use App\Services\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_pages_render(): void
    {
        $template = template();
        Page::factory()->create(['slug' => 'hakkimizda', 'title' => 'Hakkımızda']);

        $this->get('/')->assertOk()->assertSee('<h1', false);

        if ($template->supports('services')) {
            Service::factory()->count(2)->create();
            $this->get('/hizmetler')->assertOk();
        }

        if ($template->supports('products')) {
            Product::factory()->count(2)->create();
            $this->get('/urunler')->assertOk();
        }

        if ($template->supports('blog')) {
            Post::factory()->count(2)->create();
            $this->get('/blog')->assertOk();
        }

        if ($template->supports('team')) {
            TeamMember::factory()->count(2)->create();
            $this->get('/ekibimiz')->assertOk();
        }

        $this->get('/iletisim')->assertOk()->assertSee('KVKK');
        $this->get('/hakkimizda')->assertOk()->assertSee('Hakkımızda');
        $this->get('/olmayan-sayfa')->assertNotFound();
    }

    public function test_seo_tags_are_rendered(): void
    {
        $this->requiresFeature('services');
        app(Settings::class)->set('site_name', 'Test Firma');
        $service = Service::factory()->create(['title' => 'Kalite Kontrol', 'meta_description' => 'Kalite kontrol hizmeti']);

        $this->get(route('services.show', $service->slug))
            ->assertOk()
            ->assertSee('<title>Kalite Kontrol | Test Firma</title>', false)
            ->assertSee('<meta name="description" content="Kalite kontrol hizmeti">', false)
            ->assertSee('<link rel="canonical" href="'.route('services.show', $service->slug).'">', false)
            ->assertSee('property="og:title"', false);
    }

    public function test_sitemap_and_robots(): void
    {
        $page = Page::factory()->create();
        $draft = Page::factory()->draft()->create();

        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('page.show', $page->slug))
            ->assertDontSee(route('page.show', $draft->slug));

        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin')->assertSee('Sitemap:');
    }

    public function test_contact_form_stores_message_and_notifies(): void
    {
        Mail::fake();
        app(Settings::class)->set('email', 'info@test.com');

        $this->post(route('contact.store'), [
            'name' => 'Ali Veli',
            'email' => 'ali@example.com',
            'phone' => '0555 111 22 33',
            'subject' => 'Teklif',
            'message' => 'Merhaba, ürünleriniz hakkında bilgi almak istiyorum.',
            'kvkk' => 1,
        ])->assertRedirect(route('contact.show'))->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', ['email' => 'ali@example.com', 'type' => 'contact', 'is_read' => false]);
        Mail::assertSent(ContactMessageReceived::class, fn ($mail) => $mail->hasTo('info@test.com'));
    }

    public function test_contact_form_validation_and_honeypot(): void
    {
        $this->post(route('contact.store'), ['name' => '', 'email' => 'x', 'message' => 'kısa'])
            ->assertSessionHasErrors(['name', 'email', 'message', 'kvkk']);

        $this->post(route('contact.store'), ['name' => 'Bot', 'email' => 'bot@x.com', 'message' => 'spam spam spam spam', 'kvkk' => 1, 'website' => 'http://spam'])
            ->assertSessionHasErrors(['website']);

        $this->assertSame(0, ContactMessage::query()->count());
    }

    public function test_contact_form_is_rate_limited(): void
    {
        $payload = ['name' => 'Ali', 'email' => 'ali@example.com', 'message' => 'Merhaba, bilgi almak istiyorum.', 'kvkk' => 1];

        for ($i = 0; $i < 3; $i++) {
            $this->post(route('contact.store'), $payload)->assertRedirect();
        }

        $this->post(route('contact.store'), $payload)->assertStatus(429);
    }

    public function test_security_headers_are_present(): void
    {
        $this->get('/')->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_admin_prefix_is_not_captured_by_page_route(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }
}
