<?php

namespace App\Providers;

use App\Services\PrintOrderService;
use App\Services\PrintProductionService;
use Illuminate\Support\ServiceProvider;

class PrintServiceProvider extends ServiceProvider
{
    /**
     * Register any print-related services.
     */
    public function register(): void
    {
        // Register PrintOrderService as a singleton
        $this->app->singleton(PrintOrderService::class, function ($app) {
            return new PrintOrderService([
                'sizes' => [
                    'small' => [
                        'name' => 'Small (8" × 10")',
                        'dimensions' => '8" × 10" (20.3cm × 25.4cm)',
                        'price' => 29.99,
                        'comparison_width' => 80,
                        'comparison_height' => 100,
                        'popular' => false,
                    ],
                    'medium' => [
                        'name' => 'Medium (12" × 16")',
                        'dimensions' => '12" × 16" (30.5cm × 40.6cm)',
                        'price' => 49.99,
                        'comparison_width' => 120,
                        'comparison_height' => 160,
                        'popular' => true,
                    ],
                    'large' => [
                        'name' => 'Large (18" × 24")',
                        'dimensions' => '18" × 24" (45.7cm × 61.0cm)',
                        'price' => 79.99,
                        'comparison_width' => 180,
                        'comparison_height' => 240,
                        'popular' => false,
                    ],
                    'xlarge' => [
                        'name' => 'Extra Large (24" × 36")',
                        'dimensions' => '24" × 36" (61.0cm × 91.4cm)',
                        'price' => 129.99,
                        'comparison_width' => 240,
                        'comparison_height' => 360,
                        'popular' => false,
                    ],
                ],
                'shipping_methods' => config('location.shipping_methods'),
                'shipping_zones' => config('location.shipping_zones'),
            ]);
        });

        // Register PrintProductionService
        $this->app->singleton(PrintProductionService::class, function ($app) {
            return new PrintProductionService([
                'production_queue' => config('queue.connections.redis.queue'),
                'notification_channel' => config('services.slack.print_notifications_webhook'),
                'quality_settings' => [
                    'dpi' => 300,
                    'color_profile' => 'Adobe RGB (1998)',
                    'paper_type' => 'Premium Lustre',
                ],
                'production_facilities' => [
                    'us' => [
                        'name' => 'US Production Facility',
                        'timezone' => 'America/New_York',
                        'cutoff_time' => '14:00',
                        'processing_days' => 2,
                    ],
                    'eu' => [
                        'name' => 'EU Production Facility',
                        'timezone' => 'Europe/London',
                        'cutoff_time' => '14:00',
                        'processing_days' => 2,
                    ],
                ],
            ]);
        });
    }

    /**
     * Bootstrap any print-related services.
     */
    public function boot(): void
    {
        // Register custom validation rules
        \Illuminate\Support\Facades\Validator::extend('print_size', function ($attribute, $value, $parameters, $validator) {
            $service = $this->app->make(PrintOrderService::class);
            return array_key_exists($value, $service->getSizes());
        }, 'The selected print size is invalid.');

        // Register custom blade components
        \Illuminate\Support\Facades\Blade::componentNamespace('App\\View\\Components\\Prints', 'prints');

        // Register custom view composers
        \Illuminate\Support\Facades\View::composer('prints.*', function ($view) {
            $service = $this->app->make(PrintOrderService::class);
            $view->with('sizes', $service->getSizes());
        });

        // Register custom middleware
        $this->app['router']->aliasMiddleware('print.access', \App\Http\Middleware\PrintOrderAccess::class);

        // Register custom policies
        $this->app['router']->model('print', \App\Models\PrintOrder::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\PrintOrder::class, \App\Policies\PrintOrderPolicy::class);
    }
}
