<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class MarketplaceRateLimiter
{
    /**
     * The rate limiter instance.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Cache\RateLimiter  $limiter
     * @return void
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $redis = Redis::connection('rate_limit');
        $key = 'marketplace:' . $request->ip();
        
        // Get current hits for this IP
        $hits = (int) $redis->get($key) ?: 0;
        
        // Allow 60 requests per minute
        if ($hits >= 60) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $redis->ttl($key)
            ], 429);
        }
        
        // Increment hits and set expiry if not set
        $redis->incr($key);
        $redis->expire($key, 60);
        
        return $next($request);
    }
}
