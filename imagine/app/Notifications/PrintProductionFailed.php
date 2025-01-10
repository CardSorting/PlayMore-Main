<?php

namespace App\Notifications;

use App\Models\PrintOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class PrintProductionFailed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected PrintOrder $order,
        protected \Throwable $exception
    ) {
    }

    public function via($notifiable): array
    {
        return ['slack'];
    }

    public function toSlack($notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->error()
            ->content('Print Production Failed')
            ->attachment(function ($attachment) {
                $attachment
                    ->title("Order #{$this->order->order_number}")
                    ->fields([
                        'Order ID' => $this->order->id,
                        'User ID' => $this->order->user_id,
                        'Status' => $this->order->status,
                        'Size' => $this->order->size,
                        'Created' => $this->order->created_at->format('Y-m-d H:i:s'),
                        'Error' => $this->exception->getMessage(),
                    ])
                    ->footer(config('app.name'))
                    ->timestamp(now());
            });
    }
}
