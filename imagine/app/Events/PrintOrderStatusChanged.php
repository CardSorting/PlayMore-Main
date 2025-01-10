<?php

namespace App\Events;

use App\Models\PrintOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrintOrderStatusChanged
{
    use Dispatchable, SerializesModels;

    public PrintOrder $order;
    public string $oldStatus;
    public string $newStatus;

    public function __construct(PrintOrder $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}
