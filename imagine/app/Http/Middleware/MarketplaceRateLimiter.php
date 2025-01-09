<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Symfony\Component\HttpFoundation\Response;

class MarketplaceRateLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response 
    {
        $key = 'marketplace:' . $request->ip();
        
        $limiter = RateLimiter::for($key, function () {
            return Limit::perMinute(120);
        });
        
        if ($limiter->tooManyAttempts($key, 120)) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $limiter->availableIn($key)
            ], 429);
        }
        
        $limiter->hit($key);
        return $next($request);
    }
}
