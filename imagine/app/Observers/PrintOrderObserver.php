<?php

namespace App\Observers;

use App\Models\PrintOrder;
use App\Events\PrintOrderCreated;
use App\Events\PrintOrderStatusChanged;
use Illuminate\Support\Facades\{Log, Cache};
use Illuminate\Support\Str;

class PrintOrderObserver
{
    /**
     * Handle the PrintOrder "creating" event.
     */
    public function creating(PrintOrder $order): void
    {
        // Generate unique order number if not set
        if (!$order->order_number) {
            $prefix = config('prints.orders.number_prefix', 'PRT');
            $length = config('prints.orders.number_length', 8);
            
            do {
                $number = $prefix . strtoupper(Str::random($length - strlen($prefix)));
            } while (PrintOrder::where('order_number', $number)->exists());

            $order->order_number = $number;
        }

        // Set initial status if not set
        if (!$order->status) {
            $order->status = 'pending';
        }

        // Calculate shipping weight if not set
        if (!$order->shipping_weight) {
            $sizeConfig = config("prints.sizes.{$order->size}");
            $order->shipping_weight = $sizeConfig['shipping_weight'] ?? 0;
        }
    }

    /**
     * Handle the PrintOrder "created" event.
     */
    public function created(PrintOrder $order): void
    {
        event(new PrintOrderCreated($order));

        // Clear any relevant caches
        Cache::tags(['print_orders', "user:{$order->user_id}"])->flush();

        // Log order creation
        Log::channel('orders')->info("Print order created: {$order->order_number}", [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'amount' => $order->price,
            'size' => $order->size,
        ]);
    }

    /**
     * Handle the PrintOrder "updating" event.
     */
    public function updating(PrintOrder $order): void
    {
        // Track status changes
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            event(new PrintOrderStatusChanged($order, $oldStatus, $newStatus));

            // Update timestamps based on status
            switch ($newStatus) {
                case 'processing':
                    if (!$order->production_started_at) {
                        $order->production_started_at = now();
                    }
                    break;
                case 'shipped':
                    if (!$order->shipped_at) {
                        $order->shipped_at = now();
                    }
                    break;
                case 'completed':
                    if (!$order->completed_at) {
                        $order->completed_at = now();
                    }
                    break;
                case 'cancelled':
                    if (!$order->cancelled_at) {
                        $order->cancelled_at = now();
                    }
                    break;
            }

            // Log status change
            Log::channel('orders')->info("Print order status changed: {$order->order_number}", [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => auth()->id(),
            ]);
        }

        // Track shipping updates
        if ($order->isDirty(['tracking_number', 'shipping_carrier'])) {
            Log::channel('shipping')->info("Print order shipping updated: {$order->order_number}", [
                'order_id' => $order->id,
                'tracking_number' => $order->tracking_number,
                'carrier' => $order->shipping_carrier,
                'updated_by' => auth()->id(),
            ]);
        }

        // Track refund updates
        if ($order->isDirty('refunded_amount')) {
            Log::channel('refunds')->info("Print order refunded: {$order->order_number}", [
                'order_id' => $order->id,
                'amount' => $order->refunded_amount,
                'reason' => $order->refund_reason,
                'processed_by' => auth()->id(),
            ]);
        }
    }

    /**
     * Handle the PrintOrder "updated" event.
     */
    public function updated(PrintOrder $order): void
    {
        // Clear relevant caches
        Cache::tags(['print_orders', "user:{$order->user_id}"])->flush();

        if ($order->wasChanged('status')) {
            Cache::tags(['order_stats'])->flush();
        }
    }

    /**
     * Handle the PrintOrder "deleted" event.
     */
    public function deleted(PrintOrder $order): void
    {
        Log::channel('orders')->warning("Print order deleted: {$order->order_number}", [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'deleted_by' => auth()->id(),
        ]);

        // Clear relevant caches
        Cache::tags([
            'print_orders',
            "user:{$order->user_id}",
            'order_stats'
        ])->flush();
    }

    /**
     * Handle the PrintOrder "restored" event.
     */
    public function restored(PrintOrder $order): void
    {
        Log::channel('orders')->info("Print order restored: {$order->order_number}", [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'restored_by' => auth()->id(),
        ]);

        // Clear relevant caches
        Cache::tags([
            'print_orders',
            "user:{$order->user_id}",
            'order_stats'
        ])->flush();
    }

    /**
     * Handle the PrintOrder "force deleted" event.
     */
    public function forceDeleted(PrintOrder $order): void
    {
        Log::channel('orders')->alert("Print order force deleted: {$order->order_number}", [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'deleted_by' => auth()->id(),
        ]);

        // Clear relevant caches
        Cache::tags([
            'print_orders',
            "user:{$order->user_id}",
            'order_stats'
        ])->flush();
    }
}
