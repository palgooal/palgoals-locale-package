<?php

namespace Palgoals\LocalePackage\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature   = 'locale:install {--seed : Seed default languages after migration}';
    protected $description = 'Install the PalGoals Locale Package (publish files, migrate, optional seed)';

    public function handle(): int
    {
        $this->info('');
        $this->info('  ┌─────────────────────────────────────────┐');
        $this->info('  │   PalGoals Locale Package — Installer   │');
        $this->info('  └─────────────────────────────────────────┘');
        $this->info('');

        // ── Step 1: Publish all files ──────────────────────────────────────────
        $this->components->task('Publishing package files', function () {
            $this->callSilent('vendor:publish', [
                '--tag'   => 'palgoals-locale',
                '--force' => false,
            ]);
        });

        // ── Step 2: Run migrations ─────────────────────────────────────────────
        $this->components->task('Running migrations', function () {
            $this->callSilent('migrate');
        });

        // ── Step 3: Seed (optional) ────────────────────────────────────────────
        if ($this->option('seed')) {
            $this->components->task('Seeding default languages', function () {
                $this->callSilent('db:seed', ['--class' => 'LanguageSeeder']);
            });
        }

        // ── Done ───────────────────────────────────────────────────────────────
        $this->info('');
        $this->components->info('PalGoals Locale Package installed successfully!');
        $this->info('');
        $this->line('  <fg=yellow>Next steps:</>');
        $this->line('  1. Register the helper in <fg=cyan>composer.json</> autoload → files:');
        $this->line('       <fg=gray>"app/helpers_locale.php"</>');
        $this->line('     Then run: <fg=cyan>composer dump-autoload</>');
        $this->info('');
        $this->line('  2. Register the middleware in <fg=cyan>bootstrap/app.php</> (Laravel 11+):');
        $this->line("       <fg=gray>->withMiddleware(fn(\$m) => \$m->alias(['setLocale' => \App\Http\Middleware\SetLocale::class]))</>");
        $this->info('');
        $this->line('  3. Add routes in <fg=cyan>routes/web.php</>:');
        $this->line("       <fg=gray>Route::middleware(['setLocale'])->group(fn() => require __DIR__.'/lang.php');</>");
        $this->info('');
        $this->line('  4. Share locale variables in <fg=cyan>AppServiceProvider::boot()</> — see README.');
        $this->info('');
        $this->line('  <fg=green>Docs:</> https://github.com/palgooal/palgoals-locale-package');
        $this->info('');

        return self::SUCCESS;
    }
}
