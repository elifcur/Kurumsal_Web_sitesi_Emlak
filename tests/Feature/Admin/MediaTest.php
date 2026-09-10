<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_images_are_uploaded_re_encoded_and_thumbnailed(): void
    {
        Storage::fake('uploads');

        $file = UploadedFile::fake()->image('Şirket Logo.PNG', 2400, 1200);

        $this->actingAs($this->editor())
            ->postJson(route('admin.media.store'), ['files' => [$file]])
            ->assertOk()
            ->assertJsonCount(1, 'uploaded');

        $media = Media::query()->firstOrFail();
        $this->assertSame('image/png', $media->mime_type);
        $this->assertSame(1920, $media->width);
        $this->assertStringEndsWith('.png', $media->path);
        $this->assertStringNotContainsString('Şirket', $media->file_name);
        Storage::disk('uploads')->assertExists($media->path);
        Storage::disk('uploads')->assertExists($media->thumb_path);
    }

    public function test_dangerous_files_are_rejected(): void
    {
        Storage::fake('uploads');

        $php = UploadedFile::fake()->createWithContent('shell.php', '<?php echo 1;');
        $fakeImage = UploadedFile::fake()->createWithContent('image.jpg', '<?php echo 1;');

        $this->actingAs($this->editor())->postJson(route('admin.media.store'), ['files' => [$php]])->assertStatus(422);
        $this->actingAs($this->editor())->postJson(route('admin.media.store'), ['files' => [$fakeImage]])->assertStatus(422);

        $this->assertSame(0, Media::query()->count());
    }

    public function test_oversized_files_are_rejected(): void
    {
        Storage::fake('uploads');
        config()->set('cms.uploads.max_kb', 100);

        $file = UploadedFile::fake()->image('big.jpg')->size(200);

        $this->actingAs($this->editor())->postJson(route('admin.media.store'), ['files' => [$file]])->assertStatus(422);
    }

    public function test_media_can_be_deleted_with_files(): void
    {
        Storage::fake('uploads');

        $this->actingAs($this->editor())->postJson(route('admin.media.store'), ['files' => [UploadedFile::fake()->image('a.jpg')]]);
        $media = Media::query()->firstOrFail();

        $this->actingAs($this->editor())->delete(route('admin.media.destroy', $media))->assertRedirect();

        Storage::disk('uploads')->assertMissing($media->path);
        $this->assertModelMissing($media);
    }

    public function test_picker_returns_json(): void
    {
        Media::factory()->count(3)->create();

        $this->actingAs($this->editor())->getJson(route('admin.media.picker'))
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(['data' => [['id', 'url', 'thumb_url', 'path', 'is_image']], 'last_page']);
    }
}
