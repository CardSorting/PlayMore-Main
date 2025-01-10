<?php

namespace App\Services;

use App\Models\PrintOrder;
use App\Models\Gallery;
use App\DTOs\PrintOrderData;
use App\Actions\Print\CreatePrintOrderAction;
use App\Actions\Print\ProcessPaymentAction;
use App\Events\PrintOrderCreated;
use Illuminate\Support\Facades\DB;

class PrintOrderService
{
    protected $createPrintOrderAction;
    protected $processPaymentAction;

    public function __construct(
        CreatePrintOrderAction $createPrintOrderAction,
        ProcessPaymentAction $processPaymentAction
    ) {
        $this->createPrintOrderAction = $createPrintOrderAction;
        $this->processPaymentAction = $processPaymentAction;
    }

    public function getSizes(): array
    {
        return [
            'small' => [
                'name' => '8x10',
                'price' => 24.99,
                'description' => 'Perfect for desks and shelves',
                'details' => [
                    'Ideal for standard 8x10" frames',
                    'Great for desk or shelf display',
                    'Detailed view at arm\'s length'
                ]
            ],
            'large' => [
                'name' => '16x20',
                'price' => 49.99,
                'description' => 'Statement piece for your walls',
                'details' => [
                    'Makes a bold visual impact',
                    'Perfect for living spaces',
                    'Visible detail from across the room'
                ]
            ]
        ];
    }

    public function createOrder(PrintOrderData $data): PrintOrder
    {
        return DB::transaction(function () use ($data) {
            $order = $this->createPrintOrderAction->execute($data);
            
            event(new PrintOrderCreated($order));
            
            return $order;
        });
    }

    public function processPayment(PrintOrder $order, string $paymentMethodId): bool
    {
        return $this->processPaymentAction->execute($order, $paymentMethodId);
    }

    public function getShippingEstimate(PrintOrder $order): \DateTimeImmutable
    {
        return now()->addDays(5)->toImmutable();
    }

    public function validateSize(string $size): bool
    {
        return array_key_exists($size, $this->getSizes());
    }

    public function getPriceForSize(string $size): float
    {
        return $this->getSizes()[$size]['price'] ?? 0;
    }

    public function getOrderTimeline(PrintOrder $order): array
    {
        $timeline = [
            [
                'status' => 'ordered',
                'title' => 'Order Placed',
                'description' => 'Order confirmed and payment processed',
                'date' => $order->created_at,
                'completed' => true
            ]
        ];

        if ($order->status !== 'pending') {
            $timeline[] = [
                'status' => 'processing',
                'title' => 'Processing Started',
                'description' => 'Print production in progress',
                'date' => $order->updated_at,
                'completed' => true
            ];
        }

        if ($order->status === 'shipped') {
            $timeline[] = [
                'status' => 'shipped',
                'title' => 'Order Shipped',
                'description' => 'Your print is on its way',
                'date' => $order->shipped_at,
                'completed' => true
            ];
        }

        if ($order->status === 'completed') {
            $timeline[] = [
                'status' => 'completed',
                'title' => 'Order Completed',
                'description' => 'Print delivered to shipping address',
                'date' => $order->completed_at,
                'completed' => true
            ];
        }

        return $timeline;
    }
}
