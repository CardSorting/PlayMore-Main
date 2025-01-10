<?php

namespace App\Console\Commands;

use App\Models\PrintOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GeneratePrintOrderReport extends Command
{
    protected $signature = 'print:report {--from= : Start date (YYYY-MM-DD)} {--to= : End date (YYYY-MM-DD)}';
    protected $description = 'Generate a report of print orders';

    public function handle(): int
    {
        $from = $this->option('from') ? now()->parse($this->option('from')) : now()->startOfMonth();
        $to = $this->option('to') ? now()->parse($this->option('to')) : now()->endOfMonth();

        $orders = PrintOrder::query()
            ->whereBetween('created_at', [$from, $to])
            ->with(['user', 'gallery'])
            ->get();

        $report = [
            ['Order ID', 'User', 'Size', 'Material', 'Price', 'Status', 'Created At']
        ];

        foreach ($orders as $order) {
            $report[] = [
                $order->id,
                $order->user->name,
                $order->size_name,
                $order->material_name,
                $order->formatted_price,
                $order->status,
                $order->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $filename = "print-orders-{$from->format('Y-m-d')}-to-{$to->format('Y-m-d')}.csv";
        $path = Storage::disk('local')->path($filename);

        $file = fopen($path, 'w');
        foreach ($report as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        $this->info("Report generated: {$filename}");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Orders', $orders->count()],
                ['Total Revenue', '$' . number_format($orders->sum('price'), 2)],
                ['Average Order Value', '$' . number_format($orders->avg('price'), 2)],
                ['Most Popular Size', $orders->groupBy('size')->map->count()->sortDesc()->keys()->first()],
                ['Most Popular Material', $orders->groupBy('material')->map->count()->sortDesc()->keys()->first()],
            ]
        );

        return Command::SUCCESS;
    }
}
