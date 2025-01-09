<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // PayPal domains needed for SDK
        $paypalDomains = [
            'https://*.paypal.com',
            'https://*.paypalobjects.com',
            'https://*.sandbox.paypal.com',
            'https://www.sandbox.paypal.com',
            'https://www.paypal.com',
            'https://t.paypal.com',
            'https://c.paypal.com',
            'https://c.sandbox.paypal.com'
        ];

        // Build CSP
        $csp = [
            "default-src" => ["'self'", "'unsafe-inline'", "'unsafe-eval'", ...$paypalDomains],
            "script-src" => [
                "'self'",
                "'unsafe-inline'",
                "'unsafe-eval'",
                "https://www.paypal.com",
                "https://www.sandbox.paypal.com",
                "https://*.paypal.com",
                "https://*.paypalobjects.com",
            ],
            "style-src" => ["'self'", "'unsafe-inline'", "https://fonts.bunny.net"],
            "img-src" => [
                "'self'",
                "data:",
                "https:",
                "http:",
                "https://*.paypal.com",
                "https://*.paypalobjects.com",
            ],
            "font-src" => ["'self'", "https://fonts.bunny.net", "https://*.paypalobjects.com"],
            "frame-src" => [
                "'self'",
                "https://www.sandbox.paypal.com",
                "https://www.paypal.com",
                "https://*.paypal.com",
            ],
            "connect-src" => [
                "'self'",
                "https://www.sandbox.paypal.com",
                "https://www.paypal.com",
                "https://*.paypal.com",
                "https://*.paypalobjects.com",
            ],
            "form-action" => ["'self'"],
            "frame-ancestors" => ["'self'"],
            "base-uri" => ["'self'"],
        ];

        // Build CSP header string
        $cspString = collect($csp)->map(function ($values, $directive) {
            return $directive . ' ' . implode(' ', $values);
        })->implode('; ');

        $response->headers->set('Content-Security-Policy', $cspString);
        
        // Add other security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
