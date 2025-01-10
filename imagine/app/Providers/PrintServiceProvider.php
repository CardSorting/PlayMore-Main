<?php

namespace App\Providers;

use App\Services\PrintQuantityService;
use Illuminate\Support\ServiceProvider;

class PrintServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PrintQuantityService::class, function ($app) {
            return new PrintQuantityService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
