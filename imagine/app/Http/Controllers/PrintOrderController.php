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
use App\Http\Requests\Print\StoreSizeRequest;
use App\Http\Requests\Print\StoreMaterialRequest;
use Illuminate\Http\Request;

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

        // Get sizes from config
        $sizes = config('prints.sizes');

        return view('prints.select-size', [
            'gallery' => $gallery,
            'sizes' => $sizes,
        ]);
    }

    public function storeSize(StoreSizeRequest $request, Gallery $gallery): RedirectResponse
    {
        return redirect()
            ->route('prints.select-material', [
                'gallery' => $gallery,
                'size' => $request->validated('size'),
            ]);
    }

    public function selectMaterial(Request $request, Gallery $gallery): View|RedirectResponse
    {
        // Ensure the gallery belongs to the user
        if ($gallery->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to create prints from this gallery.');
        }

        // Validate size is present
        $size = $request->query('size');
        if (!$size || !array_key_exists($size, config('prints.sizes'))) {
            return redirect()
                ->route('prints.create', $gallery)
                ->with('error', 'Please select a size first.');
        }

        // Get sizes and materials from config
        $sizes = config('prints.sizes');
        $materials = config('prints.materials');

        return view('prints.select-material', [
            'gallery' => $gallery,
            'sizes' => $sizes,
            'materials' => $materials,
            'size' => $size,
        ]);
    }

    public function store(StoreMaterialRequest $request, Gallery $gallery): RedirectResponse
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
