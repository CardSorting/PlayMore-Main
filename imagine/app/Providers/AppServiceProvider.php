<?php

namespace App\Providers;

use App\Services\PulseService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Redis\RedisManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PulseService::class, function ($app) {
            return new PulseService($app->make(RedisManager::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
