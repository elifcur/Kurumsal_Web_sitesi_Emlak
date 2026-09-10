<?php

namespace Tests\Unit;

use App\Templates\Template;
use App\Templates\TemplateManager;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class TemplateManagerTest extends TestCase
{
    private string $basePath;

    /** Aktif şablon; teslim paketlerinde tek şablon kaldığı için sabit isim kullanılmaz. */
    private string $activeKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->basePath = dirname(__DIR__, 2).'/templates';
        TemplateManager::registerAutoloader($this->basePath);
        $this->activeKey = getenv('APP_TEMPLATE') ?: (new TemplateManager($this->basePath, 'x'))->keys()[0] ?? 'real-estate';
    }

    public function test_it_discovers_every_template_directory(): void
    {
        $manager = new TemplateManager($this->basePath, $this->activeKey);

        $this->assertContains($this->activeKey, $manager->keys());
        $this->assertInstanceOf(Template::class, $manager->active());
        $this->assertSame($this->activeKey, $manager->active()->key());
    }

    public function test_every_template_declares_a_unique_key_matching_its_directory(): void
    {
        $manager = new TemplateManager($this->basePath, $this->activeKey);

        foreach ($manager->keys() as $key) {
            $template = $manager->make($key);

            $this->assertSame($key, $template->key(), "templates/{$key} klasörü ile Template::key() uyuşmuyor.");
            $this->assertNotEmpty($template->name());
            $this->assertFileExists($template->path('css/app.css'));
            $this->assertFileExists($template->path('js/app.js'));
            $this->assertFileExists($template->path('views/layouts/app.blade.php'));
            $this->assertFileExists($template->path('views/home.blade.php'));
        }
    }

    public function test_unknown_template_throws(): void
    {
        $this->expectException(RuntimeException::class);

        (new TemplateManager($this->basePath, 'does-not-exist'))->active();
    }
}
