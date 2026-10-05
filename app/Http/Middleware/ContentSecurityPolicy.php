<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request and attach a sane CSP header.
     * In local environment we will allow 'unsafe-eval' to support dev tools (vite, source-maps).
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Base CSP: allow same-origin scripts/styles and small trusted CDNs
        $scriptSrc = "'self' 'unsafe-inline'";
        $styleSrc = "'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.bunny.net";
        $connectSrc = "'self'";
        $imgSrc = "'self' data:";

        // In local/dev allow eval and local Vite dev server for HMR/source-maps
        if (app()->isLocal()) {
            $scriptSrc .= " 'unsafe-eval' http://127.0.0.1:5173";
            $connectSrc .= " http://127.0.0.1:5173 ws://127.0.0.1:5173";
        }

        $csp = implode('; ', [
            "default-src 'self'",
            "script-src {$scriptSrc}",
            "style-src {$styleSrc}",
            "connect-src {$connectSrc}",
            "img-src {$imgSrc}",
            "font-src https://fonts.bunny.net https://cdn.jsdelivr.net",
            "frame-ancestors 'none'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
