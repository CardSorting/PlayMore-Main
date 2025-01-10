<?php

namespace App\Listeners\PrintOrder;

use App\Events\{
    PrintOrderCreated,
    PrintOrderRefunded,
    PrintOrderStatusChanged
};
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class PrintOrderEventSubscriber
{
    /**
     * Handle print order created events.
     */
    public function handlePrintOrderCreated(PrintOrderCreated $event): void
    {
        // Track order creation metrics
        $this->trackMetrics('order_created', [
            'order_id' => $event->order->id,
            'user_id' => $event->order->user_id,
            'amount' => $event->order->price,
            'size' => $event->order->size,
        ]);
    }

    /**
     * Handle print order status changed events.
     */
    public function handlePrintOrderStatusChanged(PrintOrderStatusChanged $event): void
    {
        // Track status change metrics
        $this->trackMetrics('order_status_changed', [
            'order_id' => $event->order->id,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
            'time_in_status' => $event->order->updated_at->diffInHours($event->order->created_at),
        ]);

        // Track time-to-ship for shipped orders
        if ($event->newStatus === 'shipped') {
            $this->trackMetrics('order_shipped', [
                'order_id' => $event->order->id,
                'time_to_ship' => $event->order->shipped_at->diffInHours($event->order->created_at),
            ]);
        }
    }

    /**
     * Handle print order refunded events.
     */
    public function handlePrintOrderRefunded(PrintOrderRefunded $event): void
    {
        // Track refund metrics
        $this->trackMetrics('order_refunded', [
            'order_id' => $event->order->id,
            'amount' => $event->amount,
            'is_full_refund' => $event->isFullRefund,
            'time_since_order' => now()->diffInDays($event->order->created_at),
        ]);
    }

    /**
     * Handle any print order events for monitoring.
     */
    public function handleAnyPrintOrderEvent($event): void
    {
        Log::channel('orders')->info(class_basename($event), [
            'event' => get_class($event),
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Track metrics for the given event.
     */
    protected function trackMetrics(string $event, array $data): void
    {
        // This would typically integrate with a metrics service
        // like StatsD, Prometheus, or custom analytics
        try {
            // Example: Track event timing
            $timing = now()->diffInMilliseconds(
                $data['timestamp'] ?? now()
            );

            Log::channel('metrics')->info("print_orders.{$event}", [
                'timing_ms' => $timing,
                'data' => $data,
            ]);

        } catch (\Throwable $e) {
            Log::error('Failed to track print order metrics', [
                'event' => $event,
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            PrintOrderCreated::class => [
                [self::class, 'handlePrintOrderCreated'],
                [self::class, 'handleAnyPrintOrderEvent'],
            ],
            PrintOrderStatusChanged::class => [
                [self::class, 'handlePrintOrderStatusChanged'],
                [self::class, 'handleAnyPrintOrderEvent'],
            ],
            PrintOrderRefunded::class => [
                [self::class, 'handlePrintOrderRefunded'],
                [self::class, 'handleAnyPrintOrderEvent'],
            ],
        ];
    }
}
