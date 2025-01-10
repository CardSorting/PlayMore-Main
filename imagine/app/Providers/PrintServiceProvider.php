<?php

namespace App\Providers;

use App\Models\PrintOrder;
use App\Services\{PrintOrderService, StripeService};
use App\Observers\PrintOrderObserver;
use App\Actions\Print\{CreatePrintOrderAction, ProcessPaymentAction};
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{Config, Validator};

class PrintServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register PrintOrderService
        $this->app->singleton(PrintOrderService::class, function ($app) {
            $printsConfig = Config::get('prints', []);
            $locationConfig = Config::get('location', []);
            
            // Restructure shipping zones to match expected format
            $shippingZones = [
                'domestic' => [],
                'international' => []
            ];
            
            foreach ($locationConfig['shipping_zones'] ?? [] as $country => $type) {
                if ($type === 'domestic') {
                    $shippingZones['domestic'][] = $country;
                } else if ($country !== '*') {
                    $shippingZones['international'][] = $country;
                }
            }
            
            $config = array_merge($printsConfig, [
                'shipping_zones' => $shippingZones
            ]);

            return new PrintOrderService(
                $config,
                $app->make(\App\Actions\Print\CreatePrintOrderAction::class),
                $app->make(\App\Actions\Print\ProcessPaymentAction::class)
            );
        });

        // Register config files
        $this->mergeConfigFrom(
            __DIR__.'/../../config/prints.php', 'prints'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../../config/location.php', 'location'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        PrintOrder::observe(PrintOrderObserver::class);

        // Register custom validation rules
        Validator::extend('valid_print_size', function ($attribute, $value, $parameters, $validator) {
            return array_key_exists($value, Config::get('prints.sizes', []));
        }, 'The selected print size is invalid.');

        Validator::extend('valid_shipping_country', function ($attribute, $value, $parameters, $validator) {
            $restrictedCountries = Config::get('location.restricted_destinations', []);
            return !in_array(strtoupper($value), $restrictedCountries);
        }, 'We do not ship to this country.');

        Validator::extend('valid_postal_code', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $country = $data['shipping_country'] ?? '*';
            $format = Config::get("location.address_validation.postal_code_formats.{$country}") ??
                     Config::get('location.address_validation.postal_code_formats.*');

            return preg_match("/{$format}/", $value);
        }, 'The postal code format is invalid for the selected country.');

        // Register custom blade components
        $this->loadViewComponentsAs('prints', [
            'progress-stepper' => \App\View\Components\Prints\ProgressStepper::class,
            'image-preview' => \App\View\Components\Prints\ImagePreview::class,
            'size-selector' => \App\View\Components\Prints\SizeSelector::class,
            'address-form' => \App\View\Components\Prints\AddressForm::class,
        ]);

        // Register view composers
        view()->composer('prints.*', function ($view) {
            $view->with('printSizes', Config::get('prints.sizes'));
            $view->with('printMaterials', Config::get('prints.materials'));
            $view->with('shippingServices', Config::get('location.shipping_services'));
        });

        // Register custom artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\GeneratePrintOrderReport::class,
                \App\Console\Commands\CleanupPendingOrders::class,
                \App\Console\Commands\SyncPrintOrderStatuses::class,
            ]);
        }

        // Register custom middleware
        $this->app['router']->aliasMiddleware('print.access', \App\Http\Middleware\PrintOrderAccess::class);

        // Register model binding
        $this->app['router']->model('print_order', PrintOrder::class);

        // Register custom macros
        PrintOrder::macro('timeline', function () {
            /** @var PrintOrder $this */
            return collect([
                'created' => [
                    'date' => $this->created_at,
                    'message' => 'Order created',
                ],
                'paid' => $this->when($this->paid_at, fn() => [
                    'date' => $this->paid_at,
                    'message' => 'Payment processed',
                ]),
                'production' => $this->when($this->production_started_at, fn() => [
                    'date' => $this->production_started_at,
                    'message' => 'Print production started',
                ]),
                'shipped' => $this->when($this->shipped_at, fn() => [
                    'date' => $this->shipped_at,
                    'message' => "Shipped via {$this->shipping_carrier}",
                ]),
                'completed' => $this->when($this->completed_at, fn() => [
                    'date' => $this->completed_at,
                    'message' => 'Order completed',
                ]),
                'cancelled' => $this->when($this->cancelled_at, fn() => [
                    'date' => $this->cancelled_at,
                    'message' => $this->cancellation_reason ?? 'Order cancelled',
                ]),
            ])->filter()->values();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            PrintOrderService::class,
        ];
    }
}
