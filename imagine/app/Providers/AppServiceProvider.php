<?php

namespace App\Providers;

use App\Services\PulseService;
use App\Services\PayPalService;
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

        $this->app->singleton(PayPalService::class, function ($app) {
            return new PayPalService();
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
                $user = Auth::user();
                $cacheKey = 'user_pulse_balance:' . $user->id;
                
                try {
                    $pulseBalance = cache()->remember($cacheKey, now()->addMinutes(5), function () use ($user) {
                        return $user->getCreditBalance();
                    });
                } catch (\Exception $e) {
                    // Keep default 0 balance on error
                    \Log::error('Failed to fetch pulse balance: ' . $e->getMessage());
                }
            }

            $view->with([
                'pulseBalance' => $pulseBalance,
                'showPulseButton' => !request()->routeIs('pulse.index'),
            ]);
        });

    }
}
