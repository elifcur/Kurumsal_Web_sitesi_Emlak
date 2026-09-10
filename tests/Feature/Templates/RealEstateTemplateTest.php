<?php

namespace Tests\Feature\Templates;

use App\Mail\ContactMessageReceived;
use App\Models\TeamMember;
use App\Services\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Templates\RealEstate\Models\Listing;
use Tests\TestCase;

class RealEstateTemplateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->activeTemplateKey() !== 'real-estate') {
            $this->markTestSkipped('Bu test yalnızca real-estate şablonu aktifken çalışır (APP_TEMPLATE=real-estate).');
        }
    }

    public function test_home_and_listing_pages_render(): void
    {
        $agent = TeamMember::factory()->create(['name' => 'Ayşe Demir']);
        $listing = Listing::factory()->create(['title' => 'Deniz Manzaralı Daire', 'listing_type' => 'sale', 'city' => 'İstanbul', 'agent_id' => $agent->id, 'is_featured' => true]);
        Listing::factory()->inactive()->create(['title' => 'Gizli İlan']);

        $this->get('/')->assertOk()->assertSee('Deniz Manzaralı Daire')->assertSee('İlanları Ara');
        $this->get('/ilanlar')->assertOk()->assertSee('Deniz Manzaralı Daire')->assertDontSee('Gizli İlan');
        $this->get('/ilanlar?tur=rent')->assertOk()->assertDontSee('Deniz Manzaralı Daire');
        $this->get('/ilanlar?tur=sale&sehir=İstanbul')->assertOk()->assertSee('Deniz Manzaralı Daire');
        $this->get('/ilanlar?tur=invalid')->assertSessionHasErrors('tur');
        $this->get($listing->url())->assertOk()->assertSee('Ayşe Demir')->assertSee('RealEstateListing');
        $this->get('/ilanlar/gizli-ilan')->assertNotFound();
        $this->get('/sitemap.xml')->assertOk()->assertSee($listing->url());
    }

    public function test_listing_inquiry_creates_message_and_notifies(): void
    {
        Mail::fake();
        app(Settings::class)->set('email', 'ofis@test.com');
        $listing = Listing::factory()->create();

        $this->from($listing->url())->post(route('listings.inquiry', $listing->slug), [
            'name' => 'Ali Veli', 'email' => 'ali@example.com', 'phone' => '0555 111 22 33', 'kvkk' => 1,
        ])->assertRedirect($listing->url())->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', ['type' => 'listing', 'email' => 'ali@example.com']);
        Mail::assertSent(ContactMessageReceived::class);

        $this->post(route('listings.inquiry', $listing->slug), ['name' => 'Bot', 'email' => 'x@y.com', 'phone' => '1', 'kvkk' => 1, 'website' => 'spam'])
            ->assertSessionHasErrors('website');
    }

    public function test_admin_can_manage_listings(): void
    {
        $user = $this->editor();

        $this->actingAs($user)->get(route('admin.listings.index'))->assertOk();
        $this->actingAs($user)->post(route('admin.listings.store'), [
            'title' => 'Site İçinde 3+1', 'listing_type' => 'rent', 'property_type' => 'apartment', 'currency' => 'TRY',
            'price' => '25000', 'rooms' => '3+1', 'features' => 'Asansör, Otopark', 'is_active' => 1,
        ])->assertRedirect();

        $listing = Listing::query()->firstOrFail();
        $this->assertSame('site-icinde-31', $listing->slug);
        $this->assertSame(['Asansör', 'Otopark'], $listing->features);
        $this->assertSame('25.000 ₺ / ay', $listing->formattedPrice());

        $this->actingAs($user)->put(route('admin.listings.update', $listing), ['title' => 'Güncel', 'listing_type' => 'sale', 'property_type' => 'villa', 'currency' => 'USD', 'is_active' => 0])->assertRedirect();
        $this->assertFalse($listing->fresh()->is_active);

        $this->actingAs($user)->delete(route('admin.listings.destroy', $listing));
        $this->assertModelMissing($listing);
    }

    public function test_products_section_is_disabled_for_this_template(): void
    {
        $this->get('/urunler')->assertNotFound();
        $this->actingAs($this->superAdmin())->get('/admin/products')->assertNotFound();
        $this->actingAs($this->superAdmin())->get(route('admin.dashboard'))->assertOk()->assertSee('İlanlar')->assertSee('Emlak');
    }
}
