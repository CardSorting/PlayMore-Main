<?php

namespace App\Events;

use App\Models\PrintOrder;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrintOrderRefunded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public PrintOrder $order,
        public float $amount,
        public string $reason,
        public ?User $processedBy = null,
        public bool $isFullRefund = false
    ) {
        $this->isFullRefund = $amount === $order->price;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<string>
     */
    public function broadcastOn(): array
    {
        return [];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'amount' => $this->amount,
            'reason' => $this->reason,
            'is_full_refund' => $this->isFullRefund,
            'processed_by' => $this->processedBy?->id,
            'processed_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Get the tags that should be assigned to the event.
     *
     * @return array<string>
     */
    public function tags(): array
    {
        return [
            'print-orders',
            'refund',
            "order:{$this->order->id}",
            $this->isFullRefund ? 'full-refund' : 'partial-refund',
        ];
    }

    /**
     * Get the event name.
     */
    public function name(): string
    {
        return 'print-order.refunded';
    }

    /**
     * Get the event description.
     */
    public function description(): string
    {
        $type = $this->isFullRefund ? 'Full' : 'Partial';
        $amount = number_format($this->amount, 2);
        return "{$type} refund of \${$amount} processed for order #{$this->order->order_number}";
    }

    /**
     * Get the event severity level.
     */
    public function severity(): string
    {
        return $this->isFullRefund ? 'warning' : 'info';
    }

    /**
     * Get the event metadata.
     *
     * @return array<string, mixed>
     */
    public function metadata(): array
    {
        return [
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->shipping_name,
            'original_amount' => $this->order->price,
            'refunded_amount' => $this->amount,
            'refund_type' => $this->isFullRefund ? 'full' : 'partial',
            'reason' => $this->reason,
            'processed_by' => $this->processedBy?->name ?? 'System',
            'processed_at' => now()->toIso8601String(),
        ];
    }
}
