<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Http\Events\RequestHandled;

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
        // Attach CSP header for all responses; in local environment allow eval for dev tooling.
        Event::listen(RequestHandled::class, function (RequestHandled $event) {
            $response = $event->response;

            $scriptSrc = "'self' 'unsafe-inline'";
            $styleSrc = "'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.bunny.net";
            $connectSrc = "'self'";
            $imgSrc = "'self' data:";

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
        });
    }
}
