<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Vite::useCspNonce();
        $response = $next($request);

        if (app()->environment('local')) {
            return $response;
        }

        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains',
            $replace = true
        );

        // Content Security Policy
        $response->headers->set(
            'Content-Security-Policy',
            "script-src 'nonce-".Vite::cspNonce()."' 'strict-dynamic' 'unsafe-eval'; object-src 'none'; base-uri 'none';",
            $replace = true,
        );

        // X-Frame-Options
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-XSS-Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'no-referrer');

        return $response;
    }
}
