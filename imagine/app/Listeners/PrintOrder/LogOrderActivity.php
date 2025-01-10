<?php

namespace App\Listeners\PrintOrder;

use App\Events\PrintOrderCreated;
use App\Events\PrintOrderStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogOrderActivity implements ShouldQueue
{
    public function handle(PrintOrderCreated|PrintOrderStatusChanged $event): void
    {
        if ($event instanceof PrintOrderCreated) {
            $this->logOrderCreated($event);
        } elseif ($event instanceof PrintOrderStatusChanged) {
            $this->logStatusChanged($event);
        }
    }

    protected function logOrderCreated(PrintOrderCreated $event): void
    {
        Log::channel('orders')->info('Print order created', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'user_id' => $event->order->user_id,
            'size' => $event->order->size,
            'price' => $event->order->price,
            'shipping_address' => $event->order->getFormattedAddress(),
        ]);
    }

    protected function logStatusChanged(PrintOrderStatusChanged $event): void
    {
        Log::channel('orders')->info('Print order status changed', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'user_id' => $event->order->user_id,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
            'changed_at' => now()->toIso8601String(),
        ]);
    }
}
