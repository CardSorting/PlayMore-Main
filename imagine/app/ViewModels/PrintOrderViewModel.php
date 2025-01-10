<?php

namespace App\ViewModels;

use App\Models\PrintOrder;
use App\Services\PrintOrderService;
use Illuminate\Support\Carbon;
use Spatie\ViewModels\ViewModel;

class PrintOrderViewModel extends ViewModel
{
    protected PrintOrder $order;
    protected PrintOrderService $printOrderService;

    public function __construct(PrintOrder $order)
    {
        $this->order = $order;
        $this->printOrderService = app(PrintOrderService::class);
    }

    public function order(): PrintOrder
    {
        return $this->order;
    }

    public function sizes(): array
    {
        return $this->printOrderService->getSizes();
    }

    public function selectedSize(): array
    {
        return $this->printOrderService->getSizes()[$this->order->size] ?? [];
    }

    public function formattedPrice(): string
    {
        return number_format($this->order->price, 2);
    }

    public function formattedAddress(): string
    {
        return collect([
            $this->order->shipping_address,
            $this->order->shipping_city,
            $this->order->shipping_state,
            $this->order->shipping_zip,
            $this->order->shipping_country
        ])->filter()->join(', ');
    }

    public function estimatedDeliveryDate(): string
    {
        return $this->printOrderService->getShippingEstimate($this->order)->format('F j, Y');
    }

    public function timeline(): array
    {
        return $this->printOrderService->getOrderTimeline($this->order);
    }

    public function statusBadgeColor(): string
    {
        return match($this->order->status) {
            'completed' => 'green',
            'processing' => 'blue',
            'shipped' => 'purple',
            default => 'gray'
        };
    }

    public function statusMessage(): string
    {
        return match($this->order->status) {
            'completed' => 'Delivered ' . $this->order->completed_at?->format('F j'),
            'shipped' => 'Arriving ' . Carbon::parse($this->estimatedDeliveryDate())->format('F j'),
            'processing' => 'Processing your order',
            default => 'Order placed'
        };
    }

    public function canCancel(): bool
    {
        return $this->order->status === 'pending' || $this->order->status === 'processing';
    }

    public function canTrack(): bool
    {
        return $this->order->status === 'shipped';
    }

    public function paymentStatus(): string
    {
        if ($this->order->paid_at) {
            return 'Paid on ' . $this->order->paid_at->format('F j, Y');
        }

        if ($this->order->requires_action) {
            return 'Payment requires action';
        }

        return 'Payment pending';
    }

    public function stripePublicKey(): string
    {
        return config('services.stripe.key');
    }

    public function checkoutData(): array
    {
        return [
            'clientSecret' => $this->order->stripe_payment_intent_client_secret,
            'returnUrl' => route('prints.success', $this->order),
            'cancelUrl' => route('prints.checkout', $this->order),
            'amount' => $this->order->price * 100, // Convert to cents
            'currency' => 'usd',
        ];
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'title' => 'Gallery',
                'url' => route('images.gallery')
            ],
            [
                'title' => 'Print Orders',
                'url' => route('prints.index')
            ],
            [
                'title' => "Order #{$this->order->order_number}",
                'url' => null
            ]
        ];
    }
}
