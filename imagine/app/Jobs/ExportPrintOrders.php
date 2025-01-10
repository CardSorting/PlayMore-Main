<?php

namespace App\Jobs;

use App\Models\{PrintOrder, User};
use App\Notifications\PrintOrderExportCompleted;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportPrintOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 3600;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    public function __construct(
        protected User $user,
        protected string $format,
        protected string $filename,
        protected array $filters = [],
        protected array $fields = [],
        protected bool $notify = true
    ) {}

    public function handle(): void
    {
        // Build query with filters
        $query = PrintOrder::query()
            ->when($this->filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
            ->when($this->filters['date_from'] ?? null, fn($q, $date) => $q->where('created_at', '>=', $date))
            ->when($this->filters['date_to'] ?? null, fn($q, $date) => $q->where('created_at', '<=', $date));

        // Get data
        $orders = $query->with(['user', 'gallery'])->get();

        // Generate export file
        $path = $this->format === 'csv' 
            ? $this->generateCsv($orders)
            : $this->generateXlsx($orders);

        // Store file
        $storagePath = "exports/{$this->filename}";
        Storage::disk('local')->put($storagePath, file_get_contents($path));
        unlink($path); // Clean up temp file

        // Generate download URL
        $downloadUrl = Storage::disk('local')->temporaryUrl(
            $storagePath,
            now()->addDay(),
            ['Content-Type' => $this->getContentType()]
        );

        // Notify user
        if ($this->notify) {
            $this->user->notify(new PrintOrderExportCompleted($downloadUrl));
        }
    }

    protected function generateCsv($orders): string
    {
        $path = storage_path("app/temp/{$this->filename}");
        $csv = Writer::createFromPath($path, 'w+');
        
        // Add headers
        $csv->insertOne($this->getHeaders());

        // Add data
        foreach ($orders as $order) {
            $csv->insertOne($this->formatOrderData($order));
        }

        return $path;
    }

    protected function generateXlsx($orders): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $sheet->fromArray([$this->getHeaders()], null, 'A1');

        // Add data
        $row = 2;
        foreach ($orders as $order) {
            $sheet->fromArray([$this->formatOrderData($order)], null, "A{$row}");
            $row++;
        }

        // Auto-size columns
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $path = storage_path("app/temp/{$this->filename}");
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return $path;
    }

    protected function getHeaders(): array
    {
        $headers = [];
        foreach ($this->fields as $field) {
            $headers[] = match ($field) {
                'order_number' => 'Order Number',
                'status' => 'Status',
                'created_at' => 'Order Date',
                'paid_at' => 'Payment Date',
                'shipped_at' => 'Ship Date',
                'completed_at' => 'Delivery Date',
                'cancelled_at' => 'Cancellation Date',
                'shipping_name' => 'Customer Name',
                'shipping_address' => 'Address',
                'shipping_city' => 'City',
                'shipping_state' => 'State/Province',
                'shipping_zip' => 'ZIP/Postal Code',
                'shipping_country' => 'Country',
                'tracking_number' => 'Tracking Number',
                'shipping_carrier' => 'Carrier',
                'price' => 'Price',
                'refunded_amount' => 'Refunded Amount',
                'size' => 'Print Size',
                default => $field,
            };
        }
        return $headers;
    }

    protected function formatOrderData(PrintOrder $order): array
    {
        $data = [];
        foreach ($this->fields as $field) {
            $data[] = match ($field) {
                'order_number' => $order->order_number,
                'status' => ucfirst($order->status),
                'created_at' => $this->formatDate($order->created_at),
                'paid_at' => $this->formatDate($order->paid_at),
                'shipped_at' => $this->formatDate($order->shipped_at),
                'completed_at' => $this->formatDate($order->completed_at),
                'cancelled_at' => $this->formatDate($order->cancelled_at),
                'shipping_name' => $order->shipping_name,
                'shipping_address' => $order->shipping_address,
                'shipping_city' => $order->shipping_city,
                'shipping_state' => $order->shipping_state,
                'shipping_zip' => $order->shipping_zip,
                'shipping_country' => $order->shipping_country,
                'tracking_number' => $order->tracking_number,
                'shipping_carrier' => $order->shipping_carrier,
                'price' => number_format($order->price, 2),
                'refunded_amount' => $order->refunded_amount ? number_format($order->refunded_amount, 2) : '',
                'size' => $order->size,
                default => $order->$field ?? '',
            };
        }
        return $data;
    }

    protected function formatDate(?Carbon $date): string
    {
        return $date ? $date->format('Y-m-d H:i:s') : '';
    }

    protected function getContentType(): string
    {
        return match ($this->format) {
            'csv' => 'text/csv',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            default => 'application/octet-stream',
        };
    }

    public function failed(\Throwable $exception): void
    {
        if ($this->notify) {
            $this->user->notify(new \App\Notifications\PrintOrderExportFailed($exception->getMessage()));
        }

        \Log::error('Print order export failed', [
            'user_id' => $this->user->id,
            'filename' => $this->filename,
            'filters' => $this->filters,
            'error' => $exception->getMessage(),
        ]);
    }
}
