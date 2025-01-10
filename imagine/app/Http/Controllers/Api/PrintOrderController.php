<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{PrintOrder, Gallery};
use App\Services\PrintOrderService;
use App\Http\Resources\PrintOrderResource;
use App\Http\Requests\Print\{CreatePrintOrderRequest, ProcessPaymentRequest};
use App\DTOs\{PrintOrderData, PaymentData};
use App\Exceptions\{PrintOrderException, PaymentException};
use Illuminate\Http\{JsonResponse, Request, Resources\Json\AnonymousResourceCollection};
use Symfony\Component\HttpFoundation\Response;

class PrintOrderController extends Controller
{
    public function __construct(
        protected PrintOrderService $printOrderService
    ) {}

    /**
     * List authenticated user's print orders.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = PrintOrder::with(['gallery'])
            ->where('user_id', $request->user()->id)
            ->when($request->status, fn($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate($request->per_page ?? 15);

        return PrintOrderResource::collection($orders);
    }

    /**
     * Show a specific print order.
     */
    public function show(PrintOrder $order): PrintOrderResource
    {
        $this->authorize('view', $order);

        return new PrintOrderResource($order->load(['gallery']));
    }

    /**
     * Create a new print order.
     */
    public function store(CreatePrintOrderRequest $request, Gallery $gallery): JsonResponse
    {
        try {
            $data = PrintOrderData::fromRequest(
                array_merge($request->validated(), [
                    'price' => $this->printOrderService->getPriceForSize($request->size)
                ]),
                $gallery
            );

            $order = $this->printOrderService->createOrder($data);

            return response()->json([
                'message' => 'Order created successfully',
                'order' => new PrintOrderResource($order),
            ], Response::HTTP_CREATED);

        } catch (PrintOrderException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors(),
            ], $e->getStatusCode());
        }
    }

    /**
     * Process payment for an order.
     */
    public function processPayment(ProcessPaymentRequest $request, PrintOrder $order): JsonResponse
    {
        try {
            $this->printOrderService->processPayment($order, $request->payment_method_id);

            return response()->json([
                'message' => 'Payment processed successfully',
                'order' => new PrintOrderResource($order->fresh()),
            ]);

        } catch (PaymentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => ['payment' => [$e->getMessage()]],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Cancel an order.
     */
    public function cancel(PrintOrder $order): JsonResponse
    {
        try {
            $this->authorize('cancel', $order);
            
            $this->printOrderService->cancelOrder($order);

            return response()->json([
                'message' => 'Order cancelled successfully',
                'order' => new PrintOrderResource($order->fresh()),
            ]);

        } catch (PrintOrderException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->getErrors(),
            ], $e->getStatusCode());
        }
    }

    /**
     * Get tracking information for an order.
     */
    public function tracking(string $number): JsonResponse
    {
        $order = PrintOrder::where('tracking_number', $number)->first();

        if (!$order) {
            return response()->json([
                'message' => 'Tracking number not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
            'tracking_number' => $order->tracking_number,
            'shipping_carrier' => $order->shipping_carrier,
            'shipped_at' => $order->shipped_at?->toIso8601String(),
            'estimated_delivery' => $this->printOrderService->getShippingEstimate($order)->toIso8601String(),
            'timeline' => $this->printOrderService->getOrderTimeline($order),
        ]);
    }

    /**
     * Get available print sizes and pricing.
     */
    public function sizes(): JsonResponse
    {
        return response()->json([
            'sizes' => $this->printOrderService->getSizes(),
        ]);
    }

    /**
     * Check shipping availability for a country.
     */
    public function checkShipping(Request $request): JsonResponse
    {
        $request->validate([
            'country' => ['required', 'string', 'size:2'],
        ]);

        $available = $this->printOrderService->isShippingAvailable($request->country);
        $zoneType = $this->printOrderService->getShippingZoneType($request->country);

        return response()->json([
            'available' => $available,
            'zone_type' => $zoneType,
            'shipping_methods' => $available ? config('location.shipping_methods') : null,
        ]);
    }
}
