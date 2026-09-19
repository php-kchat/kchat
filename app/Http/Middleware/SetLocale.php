<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locales = collect(File::directories(base_path('lang')))
            ->map(fn ($directory) => basename($directory))
            ->values()
            ->all();

        $requestedLocale = $request->query('lang')
            ?? $request->input('lang')
            ?? $request->header('X-Language')
            ?? $request->cookie('language');

        if (!$requestedLocale) {
            $requestedLocale = collect(explode(',', (string) $request->header('Accept-Language')))
                ->map(fn ($language) => strtolower(trim(explode(';', $language)[0])))
                ->map(fn ($language) => explode('-', $language)[0])
                ->first(fn ($language) => in_array($language, $locales, true));
        }

        $locale = in_array($requestedLocale, $locales, true)
            ? $requestedLocale
            : config('app.fallback_locale', 'en');

        App::setLocale($locale);

        $response = $next($request);
        $response->headers->set('Content-Language', $locale);
        $response->withCookie(cookie('language', $locale, 60 * 24 * 365));

        return $response;
    }
}