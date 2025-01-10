<?php

namespace App\Http\Controllers;

use App\Models\PrintOrder;
use App\Models\Gallery;
use App\Services\PrintOrderService;
use App\ViewModels\PrintOrderViewModel;
use App\DTOs\PrintOrderData;
use App\Http\Requests\Print\CreatePrintOrderRequest;
use App\Http\Requests\Print\ProcessPaymentRequest;
use App\Exceptions\PaymentException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrintOrderController extends Controller
{
    protected PrintOrderService $printOrderService;

    public function __construct(PrintOrderService $printOrderService)
    {
        $this->printOrderService = $printOrderService;
        $this->middleware(['auth', 'verified']);
    }

    public function index(): View
    {
        $orders = PrintOrder::with('gallery')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('prints.index', compact('orders'));
    }

    public function create(Gallery $gallery): View
    {
        $sizes = $this->printOrderService->getSizes();
        return view('prints.create', compact('gallery', 'sizes'));
    }

    public function store(CreatePrintOrderRequest $request, Gallery $gallery): RedirectResponse
    {
        $data = PrintOrderData::fromRequest(
            array_merge($request->validated(), [
                'price' => $this->printOrderService->getPriceForSize($request->size)
            ]),
            $gallery
        );

        $order = $this->printOrderService->createOrder($data);

        return redirect()->route('prints.checkout', $order);
    }

    public function show(PrintOrder $order): View
    {
        $this->authorize('view', $order);
        
        return view('prints.show', new PrintOrderViewModel($order));
    }

    public function checkout(PrintOrder $order): View
    {
        $this->authorize('view', $order);

        if ($order->status !== 'pending') {
            return redirect()->route('prints.show', $order);
        }

        return view('prints.checkout', new PrintOrderViewModel($order));
    }

    public function processPayment(ProcessPaymentRequest $request, PrintOrder $order): RedirectResponse
    {
        try {
            $this->printOrderService->processPayment($order, $request->payment_method_id);

            return redirect()->route('prints.success', $order);
        } catch (PaymentException $e) {
            return back()->withErrors(['payment' => $e->getMessage()]);
        }
    }

    public function success(PrintOrder $order): View
    {
        $this->authorize('view', $order);

        if ($order->status === 'pending') {
            return redirect()->route('prints.checkout', $order);
        }

        return view('prints.success', new PrintOrderViewModel($order));
    }

    public function cancel(PrintOrder $order): RedirectResponse
    {
        $this->authorize('cancel', $order);

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()
            ->route('prints.index')
            ->with('success', 'Order cancelled successfully.');
    }

    public function reorder(PrintOrder $order): RedirectResponse
    {
        $this->authorize('view', $order);

        return redirect()->route('prints.create', [
            'gallery' => $order->gallery_id,
            'prefill' => [
                'size' => $order->size,
                'shipping_name' => $order->shipping_name,
                'shipping_address' => $order->shipping_address,
                'shipping_city' => $order->shipping_city,
                'shipping_state' => $order->shipping_state,
                'shipping_zip' => $order->shipping_zip,
                'shipping_country' => $order->shipping_country,
            ]
        ]);
    }
}
