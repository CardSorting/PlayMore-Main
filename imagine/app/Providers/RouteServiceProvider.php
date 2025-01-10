<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Load route files only if they exist
            $routeFiles = [
                'web.php',
                'auth.php',
                'dashboard.php',
                'admin.php',
                'prints.php',
            ];

            Route::middleware('web')->group(function () use ($routeFiles) {
                foreach ($routeFiles as $file) {
                    $path = base_path('routes/' . $file);
                    if (file_exists($path)) {
                        require $path;
                    }
                }
            });
        });

        // Custom route model bindings
        Route::bind('order', function ($value) {
            return \App\Models\PrintOrder::findOrFail($value);
        });

        Route::bind('gallery', function ($value) {
            return \App\Models\Gallery::findOrFail($value);
        });

        // Global patterns
        Route::pattern('id', '[0-9]+');
        Route::pattern('slug', '[a-z0-9-]+');
    }
}
