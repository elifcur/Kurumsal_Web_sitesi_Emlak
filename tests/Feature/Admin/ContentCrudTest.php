<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_crud_and_reorder(): void
    {
        $this->requiresFeature('services');
        $user = $this->editor();

        $this->actingAs($user)->post(route('admin.services.store'), ['title' => 'Bakım Hizmeti', 'icon' => 'wrench', 'is_active' => 1])->assertRedirect();
        $service = Service::query()->firstOrFail();
        $this->assertSame('bakim-hizmeti', $service->slug);

        $other = Service::factory()->create();

        $this->actingAs($user)->postJson(route('admin.services.reorder'), ['order' => [$other->id, $service->id]])->assertOk();
        $this->assertSame(0, $other->fresh()->sort_order);
        $this->assertSame(1, $service->fresh()->sort_order);

        $this->actingAs($user)->put(route('admin.services.update', $service), ['title' => 'Bakım', 'is_active' => 0])->assertRedirect();
        $this->assertFalse($service->fresh()->is_active);
        $this->get(route('services.show', $service->slug))->assertNotFound();

        $this->actingAs($user)->delete(route('admin.services.destroy', $service));
        $this->assertModelMissing($service);
    }

    public function test_category_slug_is_unique_per_type(): void
    {
        $this->requiresFeature('blog');
        $user = $this->editor();

        $this->actingAs($user)->post(route('admin.categories.store'), ['type' => 'product', 'name' => 'Haberler'])->assertRedirect();
        $this->actingAs($user)->post(route('admin.categories.store'), ['type' => 'post', 'name' => 'Haberler'])->assertRedirect();

        $this->assertSame(2, Category::query()->where('slug', 'haberler')->count());
    }

    public function test_product_crud_with_specs_and_gallery(): void
    {
        $this->requiresFeature('products');
        $user = $this->editor();
        $category = Category::factory()->create();

        $this->actingAs($user)->post(route('admin.products.store'), [
            'title' => 'Endüstriyel Pompa',
            'category_id' => $category->id,
            'price' => '1250.50',
            'specs' => [['label' => 'Güç', 'value' => '5 kW'], ['label' => '', 'value' => 'ignored']],
            'gallery' => ['media/a.jpg', 'media/b.jpg'],
            'is_active' => 1,
            'is_featured' => 1,
        ])->assertRedirect();

        $product = Product::query()->firstOrFail();
        $this->assertSame('endustriyel-pompa', $product->slug);
        $this->assertCount(1, $product->specs);
        $this->assertCount(2, $product->gallery);
        $this->assertSame('1.250,50 ₺', $product->formattedPrice());

        $this->get(route('products.show', $product->slug))->assertOk()->assertSee('Endüstriyel Pompa')->assertSee('5 kW');

        $this->actingAs($user)->post(route('admin.products.store'), ['title' => 'X', 'category_id' => 999])->assertSessionHasErrors('category_id');
    }

    public function test_post_crud_with_tags_and_scheduling(): void
    {
        $this->requiresFeature('blog');
        $user = $this->editor();

        $this->actingAs($user)->post(route('admin.posts.store'), [
            'title' => 'Yeni Tesisimiz Açıldı',
            'content' => '<h2>Başlık</h2><p>İçerik</p>',
            'status' => 'published',
            'published_at' => now()->addDay()->format('Y-m-d\TH:i'),
            'tags' => 'Teknoloji, kalite, teknoloji',
        ])->assertRedirect();

        $post = Post::query()->with('tags')->firstOrFail();
        $this->assertSame($user->id, $post->user_id);
        $this->assertCount(2, $post->tags);
        $this->assertFalse($post->isPublished(), 'Scheduled post must not be public yet');
        $this->get(route('blog.show', $post->slug))->assertNotFound();

        $post->update(['published_at' => now()->subMinute()]);
        $this->get(route('blog.show', $post->slug))->assertOk()->assertSee('Yeni Tesisimiz Açıldı');
        $this->assertSame(1, $post->fresh()->views);

        $this->actingAs($user)->put(route('admin.posts.update', $post), ['title' => $post->title, 'status' => 'draft', 'tags' => 'kalite'])->assertRedirect();
        $this->assertCount(1, $post->fresh()->tags);
        $this->get(route('blog.show', $post->slug))->assertNotFound();
    }
}
