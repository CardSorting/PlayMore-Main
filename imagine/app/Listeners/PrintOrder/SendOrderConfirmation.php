<?php

namespace App\Listeners\PrintOrder;

use App\Events\PrintOrderCreated;
use App\Notifications\PrintOrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderConfirmation implements ShouldQueue
{
    public function handle(PrintOrderCreated $event): void
    {
        $event->order->user->notify(
            new PrintOrderStatusUpdated(
                order: $event->order,
                oldStatus: '',
                newStatus: $event->order->status
            )
        );
    }
}
