<?php

namespace App\Listeners\PrintOrder;

use App\Events\PrintOrderRefunded;
use App\Models\ActivityLog;
use App\Notifications\PrintOrderRefunded as PrintOrderRefundedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleRefund implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Handle the event.
     */
    public function handle(PrintOrderRefunded $event): void
    {
        $this->logRefund($event);
        $this->notifyCustomer($event);
        $this->updateOrderStatus($event);
    }

    /**
     * Log the refund activity.
     */
    protected function logRefund(PrintOrderRefunded $event): void
    {
        ActivityLog::create([
            'type' => 'print_order.refunded',
            'subject_type' => get_class($event->order),
            'subject_id' => $event->order->id,
            'causer_type' => $event->processedBy ? get_class($event->processedBy) : null,
            'causer_id' => $event->processedBy?->id,
            'description' => $event->description(),
            'properties' => $event->metadata(),
            'severity' => $event->severity(),
        ]);

        Log::channel('orders')->info($event->description(), $event->metadata());
    }

    /**
     * Notify the customer about the refund.
     */
    protected function notifyCustomer(PrintOrderRefunded $event): void
    {
        $notification = new PrintOrderRefundedNotification(
            $event->order,
            $event->amount,
            $event->reason
        );

        // Notify the customer
        $event->order->user->notify($notification);

        // If this is a full refund or high-value refund, notify admins
        if ($event->isFullRefund || $event->amount >= 50) {
            $this->notifyAdmins($event);
        }
    }

    /**
     * Notify administrators about significant refunds.
     */
    protected function notifyAdmins(PrintOrderRefunded $event): void
    {
        $notification = new PrintOrderRefundedNotification(
            $event->order,
            $event->amount,
            $event->reason
        );

        // Get users with the 'notify-refunds' permission
        $admins = \App\Models\User::permission('notify-refunds')->get();

        foreach ($admins as $admin) {
            $admin->notify($notification);
        }
    }

    /**
     * Update the order status if necessary.
     */
    protected function updateOrderStatus(PrintOrderRefunded $event): void
    {
        // If this is a full refund, update the order status
        if ($event->isFullRefund && $event->order->status !== 'cancelled') {
            $oldStatus = $event->order->status;

            $event->order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => "Full refund processed: {$event->reason}",
            ]);

            // Dispatch status changed event
            event(new \App\Events\PrintOrderStatusChanged(
                $event->order,
                $oldStatus,
                'cancelled'
            ));
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(PrintOrderRefunded $event, \Throwable $exception): void
    {
        Log::error('Failed to process print order refund', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'amount' => $event->amount,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        // Notify admins about the failure
        $admins = \App\Models\User::permission('notify-errors')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\PrintOrderRefundFailed(
                $event->order,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<string>
     */
    public function tags(): array
    {
        return ['print-orders', 'refund', 'listener'];
    }
}
