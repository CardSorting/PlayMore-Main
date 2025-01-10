<?php

namespace App\Listeners\PrintOrder;

use App\Events\PrintOrderStatusChanged;
use App\Services\PrintProductionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class InitiatePrintProduction implements ShouldQueue
{
    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(protected PrintProductionService $productionService)
    {
    }

    public function handle(PrintOrderStatusChanged $event): void
    {
        // Only process orders that have just entered 'processing' status
        if ($event->oldStatus !== 'pending' || $event->newStatus !== 'processing') {
            return;
        }

        try {
            $this->productionService->queuePrintJob([
                'order_id' => $event->order->id,
                'order_number' => $event->order->order_number,
                'image_url' => $event->order->gallery->image_url,
                'size' => $event->order->size,
                'priority' => $this->determinePriority($event->order),
                'metadata' => [
                    'user_id' => $event->order->user_id,
                    'created_at' => $event->order->created_at->toIso8601String(),
                    'prompt' => $event->order->gallery->prompt,
                ]
            ]);

            Log::channel('production')->info('Print job queued', [
                'order_id' => $event->order->id,
                'order_number' => $event->order->order_number,
            ]);
        } catch (\Exception $e) {
            Log::channel('production')->error('Failed to queue print job', [
                'order_id' => $event->order->id,
                'order_number' => $event->order->order_number,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    protected function determinePriority($order): string
    {
        // Implement priority logic based on business rules
        // For example: rush orders, VIP customers, etc.
        return 'normal';
    }

    public function failed(PrintOrderStatusChanged $event, \Throwable $exception): void
    {
        Log::channel('production')->error('Print production initiation failed', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        // Notify admin or support team
        // You might want to create a dedicated notification for this
        \Notification::route('slack', config('services.slack.webhook_url'))
            ->notify(new \App\Notifications\PrintProductionFailed($event->order, $exception));
    }
}
