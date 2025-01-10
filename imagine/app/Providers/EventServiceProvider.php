<?php

namespace App\Providers;

use App\Events\{
    PrintOrderCreated,
    PrintOrderRefunded,
    PrintOrderStatusChanged
};
use App\Listeners\PrintOrder\{
    HandleRefund,
    InitiatePrintProduction,
    LogOrderActivity,
    NotifyStatusChange,
    SendOrderConfirmation
};
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

        // Print Order Events
        PrintOrderCreated::class => [
            SendOrderConfirmation::class,
            InitiatePrintProduction::class,
            LogOrderActivity::class,
        ],

        PrintOrderStatusChanged::class => [
            NotifyStatusChange::class,
            LogOrderActivity::class,
        ],

        PrintOrderRefunded::class => [
            HandleRefund::class,
            LogOrderActivity::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array<int, class-string>
     */
    protected $subscribe = [
        \App\Listeners\PrintOrder\PrintOrderEventSubscriber::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Register custom event discovery paths
        $this->discoverEventsWithin([
            app_path('Events/Print'),
            app_path('Events/Marketplace'),
        ]);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array<int, string>
     */
    protected function discoverEventsWithin(): array
    {
        return [
            $this->app->path('Listeners'),
        ];
    }

    /**
     * The model observers to register.
     *
     * @var array<class-string, class-string>
     */
    protected $observers = [
        \App\Models\PrintOrder::class => \App\Observers\PrintOrderObserver::class,
    ];
}
