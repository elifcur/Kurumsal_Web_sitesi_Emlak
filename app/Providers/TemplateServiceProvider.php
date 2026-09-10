<?php

namespace App\Providers;

use App\Console\Commands\TemplateListCommand;
use App\Console\Commands\TemplatePruneCommand;
use App\Console\Commands\TemplateUseCommand;
use App\Templates\Template;
use App\Templates\TemplateManager;
use App\View\Composers\NavigationComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class TemplateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        TemplateManager::registerAutoloader(config('templates.path'));

        $this->app->singleton(TemplateManager::class, fn () => new TemplateManager(
            basePath: rtrim(config('templates.path'), '/'),
            activeKey: (string) config('templates.active'),
        ));

        $this->app->bind(Template::class, fn ($app) => $app->make(TemplateManager::class)->active());
    }

    public function boot(): void
    {
        $template = $this->app->make(TemplateManager::class)->active();

        View::addNamespace('template', $template->viewsPath());
        View::share('template', $template);
        View::composer('template::layouts.*', NavigationComposer::class);

        if (is_dir($template->viewsPath().'/components')) {
            Blade::anonymousComponentPath($template->viewsPath().'/components');
        }

        if (is_dir($template->migrationsPath())) {
            $this->loadMigrationsFrom($template->migrationsPath());
        }

        if (! $this->app->routesAreCached() && is_file($template->routesFile())) {
            Route::middleware('web')->group($template->routesFile());
        }

        if ($this->app->runningInConsole()) {
            $this->commands([TemplateListCommand::class, TemplateUseCommand::class, TemplatePruneCommand::class]);
        }
    }
}
