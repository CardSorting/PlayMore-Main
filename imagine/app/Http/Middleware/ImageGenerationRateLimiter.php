<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Symfony\Component\HttpFoundation\Response;

class ImageGenerationRateLimiter
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
        // Use authenticated user ID for more precise rate limiting
        $key = 'generate:' . ($request->user()?->id ?: $request->ip());
        
        $limiter = RateLimiter::for($key, function () {
            // Allow 1 generations per minute per user
            return Limit::perMinute(1);
        });
        
        if ($limiter->tooManyAttempts($key, 1)) {
            return response()->json([
                'message' => 'Image generation rate limit exceeded. Please wait before generating more images.',
                'retry_after' => $limiter->availableIn($key)
            ], 429);
        }
        
        $limiter->hit($key);
        return $next($request);
    }
}
