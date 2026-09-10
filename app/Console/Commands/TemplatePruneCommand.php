<?php

namespace App\Console\Commands;

use App\Templates\TemplateManager;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class TemplatePruneCommand extends Command
{
    protected $signature = 'template:prune {--force : Onay sormadan sil}';

    protected $description = 'Müşteriye teslim için aktif şablon dışındaki tüm şablon klasörlerini siler';

    public function handle(TemplateManager $templates, Filesystem $files): int
    {
        $active = $templates->activeKey();
        $others = array_values(array_diff($templates->keys(), [$active]));

        if ($others === []) {
            $this->info("Silinecek şablon yok. Yalnızca '{$active}' kurulu.");

            return self::SUCCESS;
        }

        $this->line("Aktif şablon: <info>{$active}</info>");
        $this->line('Silinecek şablonlar: <comment>'.implode(', ', $others).'</comment>');

        if (! $this->option('force') && ! $this->confirm('Bu klasörler kalıcı olarak silinecek. Devam edilsin mi?')) {
            return self::SUCCESS;
        }

        foreach ($others as $key) {
            $files->deleteDirectory($templates->basePath().'/'.$key);
            $this->line("  silindi: templates/{$key}");
        }

        $this->info('Tamamlandı. Derlenmiş varlıklar (public/build) etkilenmez.');

        return self::SUCCESS;
    }
}
