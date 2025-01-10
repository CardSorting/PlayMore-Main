<?php

namespace App\Providers;

use App\Livewire\CardCreator;
use App\Livewire\CardDetailsModal;
use App\Livewire\CardDisplay;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        Livewire::component('card-creator', CardCreator::class);
        Livewire::component('card-details-modal', CardDetailsModal::class);
        Livewire::component('card-display', CardDisplay::class);

        // Share pulse balance data with all views
        view()->composer('*', function ($view) {
            $view->with([
                'pulseBalance' => auth()->check() ? auth()->user()->pulse_balance ?? 0 : 0,
                'showPulseButton' => auth()->check()
            ]);
        });
    }
}
