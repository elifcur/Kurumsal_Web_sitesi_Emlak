<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_page_with_auto_slug_and_sanitized_content(): void
    {
        $response = $this->actingAs($this->editor())->post(route('admin.pages.store'), [
            'title' => 'Şirket Tarihçesi',
            'content' => '<p>Merhaba</p><script>alert(1)</script><img src="x" onerror="alert(1)">',
            'template' => 'default',
            'is_published' => 1,
            'show_in_menu' => 1,
        ]);

        $page = Page::query()->firstOrFail();
        $response->assertRedirect(route('admin.pages.edit', $page));

        $this->assertSame('sirket-tarihcesi', $page->slug);
        $this->assertStringNotContainsString('<script', $page->content);
        $this->assertStringNotContainsString('onerror', $page->content);
        $this->assertStringContainsString('<p>Merhaba</p>', $page->content);
        $this->assertTrue($page->is_published);
        $this->assertNotNull($page->published_at);
    }

    public function test_duplicate_titles_get_unique_slugs(): void
    {
        Page::factory()->create(['title' => 'Hakkımızda', 'slug' => 'hakkimizda']);
        $page = Page::factory()->create(['title' => 'Hakkımızda']);

        $this->assertSame('hakkimizda-2', $page->slug);
    }

    public function test_admin_can_update_and_delete_page(): void
    {
        $page = Page::factory()->create();

        $this->actingAs($this->editor())->put(route('admin.pages.update', $page), [
            'title' => 'Güncel Başlık',
            'slug' => 'guncel-baslik',
            'template' => 'wide',
            'is_published' => 0,
        ])->assertRedirect();

        $this->assertDatabaseHas('pages', ['id' => $page->id, 'title' => 'Güncel Başlık', 'slug' => 'guncel-baslik', 'is_published' => false]);

        $this->actingAs($this->editor())->delete(route('admin.pages.destroy', $page))->assertRedirect(route('admin.pages.index'));
        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }

    public function test_system_pages_cannot_be_deleted(): void
    {
        $page = Page::factory()->create();
        $page->forceFill(['is_system' => true])->save();

        $this->actingAs($this->superAdmin())->delete(route('admin.pages.destroy', $page));

        $this->assertDatabaseHas('pages', ['id' => $page->id]);
    }

    public function test_validation_errors_are_returned(): void
    {
        $this->actingAs($this->editor())
            ->post(route('admin.pages.store'), ['title' => '', 'template' => 'invalid'])
            ->assertSessionHasErrors(['title', 'template']);
    }

    public function test_draft_pages_are_not_public(): void
    {
        $page = Page::factory()->draft()->create();

        $this->get(route('page.show', $page->slug))->assertNotFound();

        $page->update(['is_published' => true]);

        $this->get(route('page.show', $page->slug))->assertOk()->assertSee($page->title);
    }
}
