<?php

namespace App\Console\Commands;

use App\Models\PrintOrder;
use Illuminate\Console\Command;

class SyncPrintOrderStatuses extends Command
{
    protected $signature = 'print:sync-statuses';
    protected $description = 'Synchronize print order statuses based on their timestamps';

    public function handle(): int
    {
        $orders = PrintOrder::query()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No orders to synchronize.');
            return Command::SUCCESS;
        }

        $now = now();
        $count = 0;

        foreach ($orders as $order) {
            $newStatus = null;

            // Mark as completed if shipped more than 14 days ago
            if ($order->shipped_at && $order->shipped_at->addDays(14)->isPast()) {
                $newStatus = 'completed';
                $order->completed_at = $now;
            }
            // Mark as shipped if processing for more than 3 days
            elseif ($order->status === 'processing' && $order->paid_at && $order->paid_at->addDays(3)->isPast()) {
                $newStatus = 'shipped';
                $order->shipped_at = $now;
            }

            if ($newStatus) {
                $order->status = $newStatus;
                $order->save();
                $count++;
            }
        }

        if ($count > 0) {
            $this->info("Synchronized {$count} " . str('order')->plural($count) . ".");
        } else {
            $this->info('No orders needed synchronization.');
        }

        return Command::SUCCESS;
    }
}
