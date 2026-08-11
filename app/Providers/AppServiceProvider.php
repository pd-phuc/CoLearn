<?php

namespace App\Providers;

use App\Services\SettingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Apply DB-stored settings to Laravel config (mail, OAuth, storage)
        // Wrapped in try-catch so app can boot even without database
        try {
            app(SettingService::class)->applyToConfig();
        } catch (\Throwable) {
            // DB not available yet (migration, etc.) — skip
        }
    }
}
