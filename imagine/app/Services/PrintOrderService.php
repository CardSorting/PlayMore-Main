<?php

namespace App\Services;

use App\Actions\Print\CreatePrintOrderAction;
use App\Actions\Print\ProcessPaymentAction;
use App\DTOs\PrintOrderData;
use App\Exceptions\PrintOrderException;
use App\Models\PrintOrder;
use Carbon\Carbon;

class PrintOrderService
{
    protected array $config;
    protected CreatePrintOrderAction $createPrintOrderAction;
    protected ProcessPaymentAction $processPaymentAction;

    public function __construct(
        array $config,
        CreatePrintOrderAction $createPrintOrderAction,
        ProcessPaymentAction $processPaymentAction
    ) {
        $this->validateConfig($config);
        $this->config = $this->mergeWithDefaultConfig($config);
        $this->createPrintOrderAction = $createPrintOrderAction;
        $this->processPaymentAction = $processPaymentAction;
    }

    protected function validateConfig(array $config): void
    {
        if (!isset($config['sizes']) || empty($config['sizes'])) {
            throw PrintOrderException::invalidConfiguration('Print sizes configuration is required');
        }

        foreach ($config['sizes'] as $size => $details) {
            if (!isset($details['name'], $details['dimensions'], $details['price'])) {
                throw PrintOrderException::invalidConfiguration("Invalid configuration for size: {$size}");
            }
        }
    }

    protected function mergeWithDefaultConfig(array $config): array
    {
        return array_merge([
            'shipping_methods' => [
                'standard' => [
                    'name' => 'Standard Shipping',
                    'description' => '5-7 business days',
                    'price' => 0,
                    'delivery_days' => 7,
                ],
            ],
            'shipping_zones' => [
                'domestic' => ['US'],
                'international' => ['CA', 'GB', 'AU'],
            ],
        ], $config);
    }

    public function getSizes(): array
    {
        return $this->config['sizes'];
    }

    public function getPriceForSize(string $size): float
    {
        if (!isset($this->config['sizes'][$size])) {
            throw PrintOrderException::invalidSize($size);
        }

        return $this->config['sizes'][$size]['price'];
    }

    public function createOrder(PrintOrderData $data): PrintOrder
    {
        // Validate size before creating order
        if (!isset($this->config['sizes'][$data->size])) {
            throw PrintOrderException::invalidSize($data->size);
        }

        // Validate shipping country
        if (!$this->isShippingAvailable($data->shipping_country)) {
            throw PrintOrderException::unsupportedShippingCountry($data->shipping_country);
        }

        return $this->createPrintOrderAction->execute($data);
    }

    public function processPayment(PrintOrder $order, string $paymentMethodId): bool
    {
        if (!in_array($order->status, ['pending'])) {
            throw PrintOrderException::invalidOrderStatus($order->status, ['pending']);
        }

        return $this->processPaymentAction->execute($order, $paymentMethodId);
    }

    public function getShippingEstimate(PrintOrder $order): Carbon
    {
        $processingDays = $this->config['shipping_methods']['standard']['delivery_days'] ?? 7;

        // Add extra days for international shipping
        if ($this->getShippingZoneType($order->shipping_country) === 'international') {
            $processingDays += 3;
        }

        return now()->addWeekdays($processingDays);
    }

    public function getOrderTimeline(PrintOrder $order): array
    {
        $timeline = [];

        // Order placed
        $timeline[] = [
            'status' => 'Order placed',
            'date' => $order->created_at->format('F j, Y'),
            'description' => 'Your order has been received',
            'completed' => true,
        ];

        // Payment processed
        if ($order->paid_at) {
            $timeline[] = [
                'status' => 'Payment processed',
                'date' => $order->paid_at->format('F j, Y'),
                'description' => 'Payment has been confirmed',
                'completed' => true,
            ];
        }

        // In production
        if ($order->status === 'processing') {
            $timeline[] = [
                'status' => 'In production',
                'date' => 'In progress',
                'description' => 'Your print is being produced',
                'completed' => false,
            ];
        }

        // Shipped
        if ($order->status === 'shipped') {
            $timeline[] = [
                'status' => 'Shipped',
                'date' => $order->shipped_at?->format('F j, Y'),
                'description' => 'Your order is on its way',
                'completed' => true,
            ];
        }

        // Delivered
        if ($order->status === 'completed') {
            $timeline[] = [
                'status' => 'Delivered',
                'date' => $order->completed_at?->format('F j, Y'),
                'description' => 'Your order has been delivered',
                'completed' => true,
            ];
        }

        return $timeline;
    }

    public function isShippingAvailable(string $countryCode): bool
    {
        $domestic = $this->config['shipping_zones']['domestic'] ?? [];
        $international = $this->config['shipping_zones']['international'] ?? [];
        return in_array($countryCode, array_merge($domestic, $international));
    }

    public function getShippingZoneType(string $countryCode): ?string
    {
        if (in_array($countryCode, $this->config['shipping_zones']['domestic'] ?? [])) {
            return 'domestic';
        }

        if (in_array($countryCode, $this->config['shipping_zones']['international'] ?? [])) {
            return 'international';
        }

        return null;
    }

    public function cancelOrder(PrintOrder $order): void
    {
        if (!in_array($order->status, ['pending', 'processing'])) {
            throw PrintOrderException::orderNotCancellable($order->order_number);
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function validateOrderStatus(PrintOrder $order, array $allowedStatuses): void
    {
        if (!in_array($order->status, $allowedStatuses)) {
            throw PrintOrderException::invalidOrderStatus($order->status, $allowedStatuses);
        }
    }
}
