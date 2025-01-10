<?php

namespace App\Events;

use App\Models\PrintOrder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrintOrderCreated
{
    use Dispatchable, SerializesModels;

    public PrintOrder $order;

    public function __construct(PrintOrder $order)
    {
        $this->order = $order;
    }
}
