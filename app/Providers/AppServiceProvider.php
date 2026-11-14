<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer(['layouts.app', 'layouts.admin'], function ($view) {
            $settings = [];
            try {
                if (Schema::hasTable('site_settings')) {
                    $settings = Cache::remember('site_settings', 600, fn () => SiteSetting::pluck('value', 'key')->all());
                }
            } catch (\Throwable $e) {
                // Разрешает показывать страницу установки до применения миграций.
            }
            $view->with('siteSettings', $settings);
        });
    }
}
