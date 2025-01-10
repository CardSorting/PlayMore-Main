<?php

namespace App\Actions\Print;

use App\Models\PrintOrder;
use App\DTOs\PrintOrderData;
use App\Events\PrintOrderCreated;
use Illuminate\Support\Str;

class CreatePrintOrderAction
{
    public function execute(PrintOrderData $data): PrintOrder
    {
        $order = new PrintOrder();
        
        // Set basic order information
        $order->fill($data->toArray());
        $order->status = 'pending';
        $order->order_number = $this->generateOrderNumber();
        $order->user_id = auth()->id();
        
        $order->save();
        
        return $order;
    }
    
    protected function generateOrderNumber(): string
    {
        do {
            $number = strtoupper(Str::random(2)) . date('y') . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (PrintOrder::where('order_number', $number)->exists());
        
        return $number;
    }
}
