<?php

namespace App\Providers;

use App\View\Components\Toolbar;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        if (Auth::check()) {
            $user = Auth::user();
            $settings = $user->settings;

            if ($settings) {
                App::setLocale($settings->locale);
                Config::set('app.theme', $settings->theme);
                Config::set('app.timezone', $settings->timezone);
            }
        }
        Blade::component('toolbar', Toolbar::class);
        Paginator::useTailwind();
    }
}
