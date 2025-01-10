<?php

namespace App\Notifications;

use App\Models\PrintOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PrintOrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected PrintOrder $order;
    protected string $oldStatus;
    protected string $newStatus;

    public function __construct(PrintOrder $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = match($this->newStatus) {
            'processing' => 'Your order is now being processed.',
            'shipped' => 'Your order has been shipped!',
            'completed' => 'Your order has been delivered.',
            'cancelled' => 'Your order has been cancelled.',
            default => 'Your order status has been updated.'
        };

        $mailMessage = (new MailMessage)
            ->subject("Order #{$this->order->order_number} Update")
            ->greeting("Hi {$notifiable->name},")
            ->line($message);

        if ($this->newStatus === 'shipped') {
            $mailMessage->action('Track Order', route('prints.show', $this->order));
        }

        if ($this->newStatus === 'completed') {
            $mailMessage
                ->line('We hope you love your print!')
                ->action('Leave a Review', route('prints.show', $this->order));
        }

        return $mailMessage
            ->line('Thank you for choosing our service.')
            ->line("Order Number: #{$this->order->order_number}");
    }

    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => match($this->newStatus) {
                'processing' => 'Your order is now being processed',
                'shipped' => 'Your order has been shipped',
                'completed' => 'Your order has been delivered',
                'cancelled' => 'Your order has been cancelled',
                default => 'Your order status has been updated'
            },
            'action_url' => route('prints.show', $this->order),
            'action_text' => $this->newStatus === 'shipped' ? 'Track Order' : 'View Order',
        ];
    }

    public function shouldSend($notifiable): bool
    {
        // Don't send notifications for status changes to 'pending'
        if ($this->newStatus === 'pending') {
            return false;
        }

        // Don't send if the status hasn't actually changed
        if ($this->oldStatus === $this->newStatus) {
            return false;
        }

        return true;
    }
}
