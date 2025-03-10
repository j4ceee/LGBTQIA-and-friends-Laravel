<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        $available_locales = config('app.available_locales');
        $locale = "";

        if (Session::has('locale')) {
            $locale = Session::get('locale');
        }

        if (!in_array($locale, $available_locales)) {
            $locale = $request->getPreferredLanguage($available_locales);
            Session::put('locale', $locale);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
