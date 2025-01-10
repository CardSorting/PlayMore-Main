<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintOrder;
use App\Services\PrintOrderService;
use App\Http\Resources\PrintOrderResource;
use App\Http\Requests\Admin\Print\{
    UpdateStatusRequest,
    AddTrackingRequest,
    ProcessRefundRequest,
    BatchUpdateStatusRequest,
    BatchExportRequest
};
use App\Jobs\ExportPrintOrders;
use App\Events\PrintOrderRefunded;
use App\Exceptions\PrintOrderException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PrintOrderController extends Controller
{
    public function __construct(
        protected PrintOrderService $printOrderService
    ) {
        $this->middleware(['auth:sanctum', 'verified', 'admin']);
    }

    /**
     * Get print order statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'orders' => [
                'total' => PrintOrder::count(),
                'by_status' => [
                    'pending' => PrintOrder::where('status', 'pending')->count(),
                    'processing' => PrintOrder::where('status', 'processing')->count(),
                    'shipped' => PrintOrder::where('status', 'shipped')->count(),
                    'completed' => PrintOrder::where('status', 'completed')->count(),
                    'cancelled' => PrintOrder::where('status', 'cancelled')->count(),
                ],
            ],
            'revenue' => [
                'total' => PrintOrder::whereNotNull('paid_at')->sum('price'),
                'refunded' => PrintOrder::whereNotNull('refunded_at')->sum('refunded_amount'),
                'net' => PrintOrder::whereNotNull('paid_at')->sum('price') - 
                        PrintOrder::whereNotNull('refunded_at')->sum('refunded_amount'),
            ],
            'popular_sizes' => PrintOrder::selectRaw('size, count(*) as count')
                ->groupBy('size')
                ->orderByDesc('count')
                ->limit(5)
                ->get(),
            'processing_times' => [
                'avg_to_ship' => DB::table('print_orders')
                    ->whereNotNull('shipped_at')
                    ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, shipped_at)) as hours')
                    ->value('hours'),
                'avg_to_complete' => DB::table('print_orders')
                    ->whereNotNull('completed_at')
                    ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as hours')
                    ->value('hours'),
            ],
        ];

        return response()->json($stats);
    }

    /**
     * Search print orders.
     */
    public function search(Request $request): JsonResponse
    {
        $orders = PrintOrder::with(['gallery', 'user'])
            ->when($request->term, function($query, $term) {
                $query->where(function($q) use ($term) {
                    $q->where('order_number', 'like', "%{$term}%")
                        ->orWhere('shipping_name', 'like', "%{$term}%")
                        ->orWhereHas('user', fn($q) => $q->where('email', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'orders' => PrintOrderResource::collection($orders),
        ]);
    }

    /**
     * Update order status.
     */
    public function updateStatus(UpdateStatusRequest $request, PrintOrder $order): JsonResponse
    {
        try {
            $oldStatus = $order->status;
            
            $order->update([
                'status' => $request->status,
                'tracking_number' => $request->tracking_number,
                'admin_notes' => $request->notes,
                'shipped_at' => $request->status === 'shipped' ? now() : $order->shipped_at,
                'completed_at' => $request->status === 'completed' ? now() : $order->completed_at,
                'cancelled_at' => $request->status === 'cancelled' ? now() : $order->cancelled_at,
            ]);

            event(new \App\Events\PrintOrderStatusChanged($order, $oldStatus, $request->status));

            return response()->json([
                'message' => 'Order status updated successfully',
                'order' => new PrintOrderResource($order->fresh()),
            ]);

        } catch (PrintOrderException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Add tracking information.
     */
    public function addTracking(AddTrackingRequest $request, PrintOrder $order): JsonResponse
    {
        $order->update([
            'tracking_number' => $request->tracking_number,
            'shipping_carrier' => $request->carrier,
            'admin_notes' => $request->notes,
            'shipped_at' => $order->shipped_at ?? now(),
        ]);

        if ($order->status === 'processing') {
            $oldStatus = $order->status;
            $order->update(['status' => 'shipped']);
            event(new \App\Events\PrintOrderStatusChanged($order, $oldStatus, 'shipped'));
        }

        return response()->json([
            'message' => 'Tracking information added successfully',
            'order' => new PrintOrderResource($order->fresh()),
        ]);
    }

    /**
     * Process refund.
     */
    public function refund(ProcessRefundRequest $request, PrintOrder $order): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Process refund through payment gateway...
            // This would typically use a payment service

            $order->update([
                'refunded_amount' => $request->amount,
                'refund_reason' => $request->reason,
                'refunded_at' => now(),
                'refunded_by' => $request->user()->id,
            ]);

            event(new PrintOrderRefunded(
                order: $order,
                amount: $request->amount,
                reason: $request->reason,
                processedBy: $request->user()
            ));

            DB::commit();

            return response()->json([
                'message' => 'Refund processed successfully',
                'order' => new PrintOrderResource($order->fresh()),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to process refund',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Batch update order statuses.
     */
    public function batchUpdateStatus(BatchUpdateStatusRequest $request): JsonResponse
    {
        $orders = PrintOrder::whereIn('id', $request->orders)->get();
        $updated = 0;

        foreach ($orders as $order) {
            try {
                $oldStatus = $order->status;
                
                $order->update([
                    'status' => $request->status,
                    'shipped_at' => $request->status === 'shipped' ? now() : $order->shipped_at,
                    'completed_at' => $request->status === 'completed' ? now() : $order->completed_at,
                    'cancelled_at' => $request->status === 'cancelled' ? now() : $order->cancelled_at,
                ]);

                if ($request->notify_customers) {
                    event(new \App\Events\PrintOrderStatusChanged($order, $oldStatus, $request->status));
                }

                $updated++;

            } catch (\Exception $e) {
                continue;
            }
        }

        return response()->json([
            'message' => "{$updated} orders updated successfully",
            'updated_count' => $updated,
            'total_orders' => $orders->count(),
        ]);
    }

    /**
     * Export orders.
     */
    public function batchExport(BatchExportRequest $request): JsonResponse
    {
        $validated = $request->validated();

        ExportPrintOrders::dispatch(
            user: $request->user(),
            format: $validated['format'],
            filename: $validated['filename'],
            filters: [
                'status' => $validated['status'] ?? null,
                'date_from' => $validated['date_from'] ?? null,
                'date_to' => $validated['date_to'] ?? null,
            ],
            fields: $validated['include_fields'],
            notify: $validated['notify_when_ready']
        );

        return response()->json([
            'message' => 'Export started. You will be notified when it\'s ready.',
            'job_id' => ExportPrintOrders::$jobId ?? null,
        ]);
    }
}
