<?php

namespace Tests\Feature\Templates;

use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\TeamMember;
use App\Services\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CorporateTemplateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->activeTemplateKey() !== 'corporate') {
            $this->markTestSkipped('Bu test yalnızca corporate şablonu aktifken çalışır (APP_TEMPLATE=corporate).');
        }
    }

    public function test_home_page_renders_core_sections(): void
    {
        $settings = app(Settings::class);
        $settings->set('about_text', 'Yılların deneyimi.', 'home');
        $settings->set('about_points', "Uzman ekip\nHızlı teslimat", 'template');
        $settings->set('header_button_text', 'Teklif Alın', 'general');

        Service::factory()->create(['title' => 'Proje Yönetimi']);
        Product::factory()->create(['title' => 'Kurumsal Paket', 'is_featured' => true]);
        TeamMember::factory()->create(['name' => 'Ayşe Demir', 'is_featured' => true]);

        $this->get('/')->assertOk()
            ->assertSee('Proje Yönetimi')
            ->assertSee('Kurumsal Paket')
            ->assertSee('Ayşe Demir')
            ->assertSee('Uzman ekip')
            ->assertSee('Teklif Alın');
    }

    public function test_product_pages_use_core_fallback_views(): void
    {
        $category = Category::factory()->create(['name' => 'Ekipmanlar']);
        $product = Product::factory()->create(['title' => 'Kontrol Ünitesi', 'category_id' => $category->id, 'price' => 12500, 'specs' => [['label' => 'Garanti', 'value' => '2 yıl']]]);
        Product::factory()->create(['title' => 'Pasif Ürün', 'is_active' => false]);

        $this->get('/urunler')->assertOk()->assertSee('Kontrol Ünitesi')->assertSee('Ekipmanlar')->assertDontSee('Pasif Ürün');
        $this->get('/urunler?kategori='.$category->slug)->assertOk()->assertSee('Kontrol Ünitesi');
        $this->get('/urunler?q=Kontrol')->assertOk()->assertSee('Kontrol Ünitesi');
        $this->get($product->url())->assertOk()->assertSee('12.500,00 ₺')->assertSee('Garanti')->assertSee('Teklif İste');
        $this->get('/urunler/pasif-urun')->assertNotFound();
    }

    public function test_template_settings_tab_and_admin_menu(): void
    {
        $admin = $this->superAdmin();

        $this->actingAs($admin)->get(route('admin.settings.edit', ['tab' => 'template']))->assertOk()->assertSee('Kurumsal Ayarları');
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()->assertSee('Ürünler');
        $this->actingAs($admin)->get(route('admin.products.index'))->assertOk();
    }
}
