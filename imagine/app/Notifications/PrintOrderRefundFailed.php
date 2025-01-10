<?php

namespace App\Notifications;

use App\Models\PrintOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class PrintOrderRefundFailed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected PrintOrder $order,
        protected string $error
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'slack', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject("Failed to Process Refund for Order #{$this->order->order_number}")
            ->greeting('Hello ' . $notifiable->name)
            ->line('We encountered an error while processing a refund.')
            ->line('Order Details:')
            ->line("• Order Number: {$this->order->order_number}")
            ->line("• Customer: {$this->order->shipping_name}")
            ->line("• Amount: \${$this->order->price}")
            ->line('Error Details:')
            ->line($this->error)
            ->action('View Order', route('admin.prints.show', $this->order))
            ->line('Please investigate and attempt to process the refund manually if necessary.');
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->error()
            ->content('Print Order Refund Failed')
            ->attachment(function ($attachment) {
                $attachment
                    ->title("Order #{$this->order->order_number}")
                    ->fields([
                        'Customer' => $this->order->shipping_name,
                        'Amount' => '$' . number_format($this->order->price, 2),
                        'Error' => $this->error,
                        'Time' => now()->format('Y-m-d H:i:s'),
                    ])
                    ->footer(config('app.name'))
                    ->footerIcon('https://example.com/icon.png')
                    ->timestamp(now());
            });
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'print_order_refund_failed',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $this->order->shipping_name,
            'amount' => $this->order->price,
            'error' => $this->error,
            'occurred_at' => now(),
        ];
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array<string, string>
     */
    public function viaQueues(): array
    {
        return [
            'mail' => 'notifications-high',
            'slack' => 'notifications-slack',
            'database' => 'notifications',
        ];
    }

    /**
     * Get the tags that should be assigned to the notification.
     *
     * @return array<string>
     */
    public function tags(): array
    {
        return [
            'print-orders',
            'refund',
            'error',
            "order:{$this->order->id}",
        ];
    }

    /**
     * Get the notification's delivery delay.
     */
    public function delay(): int
    {
        // Send immediately since this is an error notification
        return 0;
    }

    /**
     * Get the notification's priority level.
     */
    public function priority(): int
    {
        // High priority since this is an error that needs attention
        return 1;
    }
}
