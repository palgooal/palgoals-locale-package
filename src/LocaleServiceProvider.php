<?php

namespace Palgoals\LocalePackage;

use Illuminate\Support\ServiceProvider;
use Palgoals\LocalePackage\Console\InstallCommand;

class LocaleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {

            // ── Register Artisan commands
            $this->commands([
                InstallCommand::class,
            ]);

            // ── Publish: App files (Models, Controllers, Middleware, Components, Helpers)
            $this->publishes([
                __DIR__ . '/../app' => app_path(),
            ], 'palgoals-locale-app');

            // ── Publish: Migrations
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'palgoals-locale-migrations');

            // ── Publish: Seeders
            $this->publishes([
                __DIR__ . '/../database/seeders' => database_path('seeders'),
            ], 'palgoals-locale-seeders');

            // ── Publish: Blade Views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views'),
            ], 'palgoals-locale-views');

            // ── Publish: Routes
            $this->publishes([
                __DIR__ . '/../routes' => base_path('routes'),
            ], 'palgoals-locale-routes');

            // ── Publish: Config
            $this->publishes([
                __DIR__ . '/../config/palgoals-locale.php' => config_path('palgoals-locale.php'),
            ], 'palgoals-locale-config');

            // ── Publish: Everything at once (recommended for fresh installs)
            $this->publishes([
                __DIR__ . '/../app'                        => app_path(),
                __DIR__ . '/../database/migrations'        => database_path('migrations'),
                __DIR__ . '/../database/seeders'           => database_path('seeders'),
                __DIR__ . '/../resources/views'            => resource_path('views'),
                __DIR__ . '/../routes'                     => base_path('routes'),
                __DIR__ . '/../config/palgoals-locale.php' => config_path('palgoals-locale.php'),
            ], 'palgoals-locale');
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../config/palgoals-locale.php',
            'palgoals-locale'
        );
    }

    public function register(): void
    {
        //
    }
}
