<?php

namespace App\Notifications;

use App\Models\PrintOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class PrintOrderRefunded extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected PrintOrder $order,
        protected float $amount,
        protected string $reason
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database'];

        // Only send Slack notifications for full refunds or high-value refunds
        if ($this->amount >= 50 || $this->amount === $this->order->price) {
            $channels[] = 'slack';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject("Refund Processed for Order #{$this->order->order_number}")
            ->greeting('Hello ' . $notifiable->name);

        // If this is a full refund
        if ($this->amount === $this->order->price) {
            $message->line('A full refund has been processed for your print order.');
        } else {
            $message->line("A partial refund of $" . number_format($this->amount, 2) . " has been processed for your print order.");
        }

        $message->line('Refund Details:')
            ->line("• Order Number: {$this->order->order_number}")
            ->line("• Original Amount: $" . number_format($this->order->price, 2))
            ->line("• Refunded Amount: $" . number_format($this->amount, 2))
            ->line("• Reason: {$this->reason}")
            ->line('The refund should appear on your original payment method within 5-10 business days.')
            ->action('View Order Details', route('prints.show', $this->order))
            ->line('If you have any questions about this refund, please contact our support team.');

        return $message;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'print_order_refunded',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'amount' => $this->amount,
            'reason' => $this->reason,
            'is_full_refund' => $this->amount === $this->order->price,
            'processed_at' => now(),
        ];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        $isFullRefund = $this->amount === $this->order->price;
        $refundType = $isFullRefund ? 'Full Refund' : 'Partial Refund';

        return (new SlackMessage)
            ->warning()
            ->content("Print Order {$refundType} Processed")
            ->attachment(function ($attachment) use ($isFullRefund) {
                $attachment
                    ->title("Order #{$this->order->order_number}")
                    ->fields([
                        'Customer' => $this->order->shipping_name,
                        'Original Amount' => '$' . number_format($this->order->price, 2),
                        'Refunded Amount' => '$' . number_format($this->amount, 2),
                        'Refund Type' => $isFullRefund ? 'Full Refund' : 'Partial Refund',
                        'Reason' => $this->reason,
                        'Processed By' => $this->order->refunded_by 
                            ? $this->order->refundedBy->name 
                            : 'System',
                        'Time' => now()->format('Y-m-d H:i:s'),
                    ]);
            });
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array<string, string>
     */
    public function viaQueues(): array
    {
        return [
            'mail' => 'notifications',
            'slack' => 'notifications-slack',
            'database' => 'notifications',
        ];
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<string>
     */
    public function tags(): array
    {
        return [
            'print-orders',
            'refund',
            "order:{$this->order->id}",
            $this->amount === $this->order->price ? 'full-refund' : 'partial-refund',
        ];
    }
}
