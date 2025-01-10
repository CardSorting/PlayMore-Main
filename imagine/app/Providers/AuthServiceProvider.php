<?php

namespace App\Providers;

use App\Models\Pack;
use App\Models\PrintOrder;
use App\Policies\PackPolicy;
use App\Policies\PrintOrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Pack::class => PackPolicy::class,
        PrintOrder::class => PrintOrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
