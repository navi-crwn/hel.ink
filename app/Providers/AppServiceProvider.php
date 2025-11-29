<?php

namespace App\Providers;

use App\Models\SeoSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }
    public function boot(): void
    {
        \URL::forceRootUrl(config('app.url'));
        
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
        
        View::composer('components.marketing-layout', function ($view) {
            $seo = cache()->rememberForever('seo.settings', fn () => SeoSetting::first());
            $view->with('seoSettings', $seo);
        });
    }
}
