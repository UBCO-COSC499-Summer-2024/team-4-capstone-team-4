<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ApplyUserSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $settings = $user->settings;

            if ($settings) {
                App::setLocale($settings->locale);
                Config::set('app.theme', $settings->theme);
                Config::set('app.timezone', $settings->timezone);
            }
        } else {
            $locale = Session::get('locale') ?? Config::get('app.locale');
            $theme = Session::get('theme') ?? Config::get('app.theme');
            $timezone = Session::get('timezone') ?? Config::get('app.timezone');

            if ($locale) {
                App::setLocale($locale);
            }

            if ($theme) {
                Config::set('app.theme', $theme);
            }

            if ($timezone) {
                Config::set('app.timezone', $timezone);
            }
        }

        return $next($request);
    }
}
