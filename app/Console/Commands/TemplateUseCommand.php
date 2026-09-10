<?php

namespace App\Console\Commands;

use App\Templates\TemplateManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TemplateUseCommand extends Command
{
    protected $signature = 'template:use {key : Şablon anahtarı (örn. clinic)} {--seed : Şablonun varsayılan ayarlarını ve demo içeriğini yükler}';

    protected $description = 'Aktif şablonu değiştirir (.env içindeki APP_TEMPLATE) ve önbellekleri temizler';

    public function handle(TemplateManager $templates): int
    {
        $key = (string) $this->argument('key');

        if (! $templates->has($key)) {
            $this->error("Şablon bulunamadı: {$key}. Mevcut şablonlar: ".implode(', ', $templates->keys()));

            return self::FAILURE;
        }

        $envPath = base_path('.env');

        if (! is_file($envPath)) {
            $this->error('.env dosyası bulunamadı.');

            return self::FAILURE;
        }

        $env = file_get_contents($envPath);
        $line = 'APP_TEMPLATE='.$key;

        $env = preg_match('/^APP_TEMPLATE=.*$/m', $env)
            ? preg_replace('/^APP_TEMPLATE=.*$/m', $line, $env)
            : rtrim($env)."\n".$line."\n";

        file_put_contents($envPath, $env);

        Artisan::call('optimize:clear');
        $this->info("Aktif şablon: {$key}");

        if ($this->option('seed')) {
            $this->call('migrate', ['--force' => true]);
            $this->call('db:seed', ['--force' => true, '--class' => 'Database\\Seeders\\TemplateSeeder', '--env' => app()->environment()]);
        } else {
            $this->line('Şablonun tablolarını ve varsayılan içeriğini kurmak için: <comment>php artisan migrate && php artisan db:seed</comment>');
        }

        return self::SUCCESS;
    }
}
