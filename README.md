# PalGoals Locale Package

A Laravel package for **database-driven multilingual support** with language management, translation CRUD, RTL/LTR auto-detection, and ready-to-use Blade components.

## Features

- ✅ Database-driven translations with `t('key')` helper
- ✅ Auto-create missing translation keys in the DB
- ✅ Cache per key/locale pair (configurable TTL)
- ✅ Fallback locale chain
- ✅ RTL / LTR auto-detection via `current_dir()`
- ✅ Language switcher Blade components (frontend + dashboard)
- ✅ Full CRUD admin panel for languages and translations
- ✅ Supports Laravel 10, 11, 12

---

## Requirements

- PHP 8.1+
- Laravel 10 / 11 / 12

---

## Installation

### Step 1 — Install via Composer

```bash
composer require palgoals/locale-package
```

### Step 2 — Run the installer

```bash
php artisan locale:install
```

This single command will:
- ✅ Publish all files (Models, Controllers, Middleware, Views, Routes, Config)
- ✅ Run migrations (creates `languages` and `translation_values` tables)

> **Optional:** Add `--seed` to seed default languages automatically:
> ```bash
> php artisan locale:install --seed
> ```

### Step 3 — Register the Helper

In your project's `composer.json`, add the helper to autoload:

```json
"autoload": {
    "files": [
        "app/helpers_locale.php"
    ]
}
```

Then run:

```bash
composer dump-autoload
```

### Step 4 — Register the Middleware

**Laravel 11+** — in `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'setLocale' => \App\Http\Middleware\SetLocale::class,
    ]);
})
```

**Laravel 10 and below** — in `app/Http/Kernel.php`:
```php
protected $middlewareAliases = [
    'setLocale' => \App\Http\Middleware\SetLocale::class,
];
```

### Step 5 — Add Routes

In `routes/web.php`:
```php
Route::middleware(['setLocale'])->group(function () {
    require __DIR__ . '/lang.php';
    // ... your other routes
});
```

In your dashboard routes file (inside auth/admin group):
```php
require __DIR__ . '/lang_dashboard.php';
```

### Step 6 — Share Variables with All Views

In `app/Providers/AppServiceProvider.php` inside `boot()`:
```php
view()->composer('*', function ($view) {
    $view->with([
        'currentLocale'   => app()->getLocale(),
        'currentLanguage' => \App\Models\Language::where('code', app()->getLocale())->first(),
        'languages'       => \App\Models\Language::where('is_active', true)->get(),
    ]);
});
```

---

## Usage

### Translate text

```blade
{{ t('frontend.nav.home') }}
{{ t('frontend.hero.title', 'Welcome') }}
{!! t_html('frontend.about.description') !!}
```

### Page direction (RTL / LTR)

```blade
<html dir="{{ current_dir() }}" lang="{{ app()->getLocale() }}">
```

### Language Switcher (Frontend)

```blade
<x-lang.language-switcher />
<x-lang.language-switcher variant="topbar" />
```

### Language Switcher (Dashboard)

```blade
<x-lang.language-switcher-dashboard />
```

### CMS Page Slug (optional — requires `App\Models\Page`)

```blade
<a href="/{{ page_slug('about') }}">{{ t('frontend.nav.about') }}</a>
```

---

## Configuration

After installation, edit `config/palgoals-locale.php`:

```php
return [
    // Optional: resolve default language from your own settings model
    'default_language_resolver' => function () {
        return \App\Models\GeneralSetting::first()?->default_language_code
            ?? config('app.locale');
    },

    // Auto-create missing translation keys in the DB
    'auto_create' => env('TRANSLATION_AUTO_CREATE', true),

    // Cache duration in seconds
    'cache_ttl' => env('TRANSLATION_CACHE_TTL', 60),
];
```

### `.env` options

```env
TRANSLATION_AUTO_CREATE=true
TRANSLATION_CACHE_TTL=60
APP_LOCALE=ar
APP_FALLBACK_LOCALE=en
```

---

## Views Note

The dashboard views (`resources/views/dashboard/lang/`) depend on your project's layout components:
- `<x-dashboard-layout>` — your project's dashboard layout
- `<x-form.input>` — your project's input component

Edit these views after installation to match your project's component names.

---

## License

MIT — © [PalGoals](https://github.com/palgooal)
