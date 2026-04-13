<?php

namespace Palgoals\LocalePackage;

use Illuminate\Support\ServiceProvider;

class LocaleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {

            // ── Publish: App files (Models, Controllers, Middleware, Components, Helpers)
            $this->publishes([
                __DIR__ . '/../publishable/app' => app_path(),
            ], 'palgoals-locale-app');

            // ── Publish: Migrations
            $this->publishes([
                __DIR__ . '/../publishable/database/migrations' => database_path('migrations'),
            ], 'palgoals-locale-migrations');

            // ── Publish: Seeders
            $this->publishes([
                __DIR__ . '/../publishable/database/seeders' => database_path('seeders'),
            ], 'palgoals-locale-seeders');

            // ── Publish: Blade Views
            $this->publishes([
                __DIR__ . '/../publishable/resources/views' => resource_path('views'),
            ], 'palgoals-locale-views');

            // ── Publish: Routes
            $this->publishes([
                __DIR__ . '/../publishable/routes' => base_path('routes'),
            ], 'palgoals-locale-routes');

            // ── Publish: Config
            $this->publishes([
                __DIR__ . '/../config/palgoals-locale.php' => config_path('palgoals-locale.php'),
            ], 'palgoals-locale-config');

            // ── Publish: Everything at once (recommended for fresh installs)
            $this->publishes([
                __DIR__ . '/../publishable/app'                  => app_path(),
                __DIR__ . '/../publishable/database/migrations'  => database_path('migrations'),
                __DIR__ . '/../publishable/database/seeders'     => database_path('seeders'),
                __DIR__ . '/../publishable/resources/views'      => resource_path('views'),
                __DIR__ . '/../publishable/routes'               => base_path('routes'),
                __DIR__ . '/../config/palgoals-locale.php'       => config_path('palgoals-locale.php'),
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
