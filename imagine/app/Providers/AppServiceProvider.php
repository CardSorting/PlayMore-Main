<?php

namespace App\Providers;

use App\Services\PulseService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        View::composer('layouts.navigation', function ($view) {
            $pulseBalance = 0;
            
            if (Auth::check()) {
                try {
                    $pulseBalance = Auth::user()->getCreditBalance();
                } catch (\Exception $e) {
                    // Keep default 0 balance on error
                }
            }

            $view->with('pulseBalance', $pulseBalance);
        });
    }
}
