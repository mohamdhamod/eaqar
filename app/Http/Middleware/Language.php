<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $supported = array_keys(config('languages', []));

        // 1. Explicit query/body parameter: ?lang=ar
        $lang = $request->input('lang') ?? $request->input('locale');

        // 2. Accept-Language header (first tag only, e.g. "ar-SA" → "ar")
        if (!$lang) {
            $header = $request->header('Accept-Language');
            if ($header) {
                $lang = strtolower(substr($header, 0, 2));
            }
        }

        // 3. Session fallback (for web-adjacent API calls)
        if (!$lang && Session()->has('applocale')) {
            $lang = Session()->get('applocale');
        }

        if ($lang && in_array($lang, $supported, true)) {
            App::setLocale($lang);
        } else {
            App::setLocale(config('app.fallback_locale'));
        }

        URL::defaults(['locale' => App::getLocale()]);
        return $next($request);
    }
}
