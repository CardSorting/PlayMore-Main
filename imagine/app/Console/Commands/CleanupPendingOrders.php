<?php

namespace App\Console\Commands;

use App\Models\PrintOrder;
use Illuminate\Console\Command;

class CleanupPendingOrders extends Command
{
    protected $signature = 'print:cleanup-pending {--days=7 : Number of days before cleaning up pending orders}';
    protected $description = 'Clean up pending print orders older than specified days';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $orders = PrintOrder::query()
            ->where('status', 'pending')
            ->where('created_at', '<', $cutoffDate)
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No pending orders to clean up.');
            return Command::SUCCESS;
        }

        $count = $orders->count();
        
        foreach ($orders as $order) {
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);
        }

        $this->info("Cleaned up {$count} pending " . str('order')->plural($count) . " older than {$days} days.");
        
        return Command::SUCCESS;
    }
}
