<?php

namespace App\Console\Commands;

use App\Templates\TemplateManager;
use Illuminate\Console\Command;

class TemplateListCommand extends Command
{
    protected $signature = 'template:list';

    protected $description = 'Kurulu sektör şablonlarını listeler';

    public function handle(TemplateManager $templates): int
    {
        $rows = $templates->all()->map(fn ($template) => [
            $template->key() === $templates->activeKey() ? 'aktif' : '',
            $template->key(),
            $template->name(),
            $template->description(),
        ])->all();

        $this->table(['Aktif', 'Anahtar', 'Ad', 'Açıklama'], $rows);
        $this->line('Aktif şablon: <info>'.$templates->activeKey().'</info> (APP_TEMPLATE)');

        return self::SUCCESS;
    }
}
