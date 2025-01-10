<?php

namespace App\Providers;

use App\Events\PrintOrderCreated;
use App\Events\PrintOrderStatusChanged;
use App\Listeners\PrintOrder\SendOrderConfirmation;
use App\Listeners\PrintOrder\NotifyStatusChange;
use App\Listeners\PrintOrder\LogOrderActivity;
use App\Listeners\PrintOrder\InitiatePrintProduction;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        PrintOrderCreated::class => [
            SendOrderConfirmation::class,
            LogOrderActivity::class,
        ],

        PrintOrderStatusChanged::class => [
            NotifyStatusChange::class,
            LogOrderActivity::class,
            InitiatePrintProduction::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Register model observers here if needed
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
