<?php

namespace App\Listeners\PrintOrder;

use App\Events\PrintOrderStatusChanged;
use App\Notifications\PrintOrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyStatusChange implements ShouldQueue
{
    public function handle(PrintOrderStatusChanged $event): void
    {
        $event->order->user->notify(
            new PrintOrderStatusUpdated(
                order: $event->order,
                oldStatus: $event->oldStatus,
                newStatus: $event->newStatus
            )
        );
    }
}
