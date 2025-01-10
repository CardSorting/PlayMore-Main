<?php

namespace App\Http\Controllers;

use App\Models\PrintOrder;
use App\Models\Gallery;
use App\Services\PrintOrderService;
use App\ViewModels\PrintOrderViewModel;
use App\DTOs\PrintOrderData;
use App\Http\Requests\Print\CreatePrintOrderRequest;
use App\Http\Requests\Print\ProcessPaymentRequest;
use App\Exceptions\{PrintOrderException, PaymentException};
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrintOrderController extends Controller
{
    public function __construct(
        protected PrintOrderService $printOrderService
    ) {
        $this->middleware(['auth', 'verified']);
        $this->authorizeResource(PrintOrder::class, 'order');
    }

    public function index(): View
    {
        $orders = PrintOrder::with(['gallery'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('prints.index', compact('orders'));
    }

    public function create(Gallery $gallery): View
    {
        // Ensure the gallery belongs to the user
        if ($gallery->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to create prints from this gallery.');
        }

        // Get sizes and materials from config
        $sizes = config('prints.sizes');
        $materials = config('prints.materials');

        // Get prefilled values from query parameters
        $prefill = request()->get('prefill', []);

        return view('prints.create', [
            'gallery' => $gallery,
            'sizes' => $sizes,
            'old' => [
                'size' => old('size', $prefill['size'] ?? ''),
                'material' => old('material', 'premium_lustre'),
                'shipping_name' => old('shipping_name', $prefill['shipping_name'] ?? ''),
                'shipping_address' => old('shipping_address', $prefill['shipping_address'] ?? ''),
                'shipping_city' => old('shipping_city', $prefill['shipping_city'] ?? ''),
                'shipping_state' => old('shipping_state', $prefill['shipping_state'] ?? ''),
                'shipping_zip' => old('shipping_zip', $prefill['shipping_zip'] ?? ''),
                'shipping_country' => old('shipping_country', $prefill['shipping_country'] ?? ''),
            ],
        ]);
    }

    public function store(CreatePrintOrderRequest $request, Gallery $gallery): RedirectResponse
    {
        try {
            $data = PrintOrderData::fromRequest(
                array_merge($request->validated(), [
                    'price' => $this->printOrderService->calculatePrice(
                        $request->size,
                        $request->material ?? 'premium_lustre'
                    )
                ]),
                $gallery
            );

            $order = $this->printOrderService->createOrder($data);

            return redirect()
                ->route('prints.checkout', $order)
                ->with('success', 'Order created successfully.');

        } catch (PrintOrderException $e) {
            return back()
                ->withErrors($e->getErrors())
                ->withInput();
        }
    }

    public function show(PrintOrder $order): View
    {
        return view('prints.show', new PrintOrderViewModel($order));
    }

    public function checkout(PrintOrder $order): View|RedirectResponse
    {
        try {
            $this->printOrderService->validateOrderStatus($order, ['pending']);

            return view('prints.checkout', new PrintOrderViewModel($order));

        } catch (PrintOrderException $e) {
            return redirect()
                ->route('prints.show', $order)
                ->with('error', $e->getMessage());
        }
    }

    public function processPayment(ProcessPaymentRequest $request, PrintOrder $order): RedirectResponse
    {
        try {
            $this->printOrderService->processPayment($order, $request->payment_method_id);

            return redirect()
                ->route('prints.success', $order)
                ->with('success', 'Payment processed successfully.');

        } catch (PaymentException|PrintOrderException $e) {
            return back()
                ->withErrors(['payment' => $e->getMessage()])
                ->withInput();
        }
    }

    public function success(PrintOrder $order): View|RedirectResponse
    {
        if ($order->status === 'pending') {
            return redirect()
                ->route('prints.checkout', $order)
                ->with('error', 'Payment is required to complete this order.');
        }

        return view('prints.success', new PrintOrderViewModel($order));
    }

    public function cancel(PrintOrder $order): RedirectResponse
    {
        try {
            $this->printOrderService->cancelOrder($order);

            return redirect()
                ->route('prints.index')
                ->with('success', 'Order cancelled successfully.');

        } catch (PrintOrderException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reorder(PrintOrder $order): RedirectResponse
    {
        return redirect()->route('prints.create', [
            'gallery' => $order->gallery_id,
            'prefill' => [
                'size' => $order->size,
                'material' => $order->material,
                'shipping_name' => $order->shipping_name,
                'shipping_address' => $order->shipping_address,
                'shipping_city' => $order->shipping_city,
                'shipping_state' => $order->shipping_state,
                'shipping_zip' => $order->shipping_zip,
                'shipping_country' => $order->shipping_country,
            ]
        ]);
    }

    public function track(PrintOrder $order): View|RedirectResponse
    {
        try {
            $this->printOrderService->validateOrderStatus($order, ['shipped']);

            return view('prints.track', new PrintOrderViewModel($order));

        } catch (PrintOrderException $e) {
            return redirect()
                ->route('prints.show', $order)
                ->with('error', $e->getMessage());
        }
    }

    public function downloadInvoice(PrintOrder $order): RedirectResponse
    {
        if (!$order->paid_at) {
            return back()->with('error', 'Invoice is not available for unpaid orders.');
        }

        // Generate and return invoice...
        // This would typically use a service to generate a PDF invoice
        return back()->with('error', 'Invoice download is not yet implemented.');
    }
}
