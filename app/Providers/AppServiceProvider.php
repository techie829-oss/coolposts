<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\GlobalSetting;

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
        // Force HTTPS in production
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Share global settings and branding settings to all views
        View::composer('*', function ($view) {
            $view->with('globalSettings', GlobalSetting::getSettings());
            $view->with('brandingSettings', \App\Models\BrandingSetting::getSettings());
        });
    }
}
