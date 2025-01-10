<?php

namespace App\Http\Controllers;

use App\Actions\Print\ProcessPaymentAction;
use App\Models\Gallery;
use App\Models\PrintOrder;
use App\Services\StripeService;
use Illuminate\Support\Collection;
use App\Http\Requests\Print\InitiatePrintOrderRequest;
use App\Http\Requests\Print\ProcessPaymentRequest;
use App\Http\Requests\Print\StoreMaterialRequest;
use App\Http\Requests\Print\StoreSizeRequest;
use App\Http\Requests\Print\StoreShippingAddressRequest;
use App\Http\Requests\Print\ShowCheckoutRequest;
use App\Http\Requests\Print\ShowSuccessRequest;
use App\Http\Requests\Print\SelectMaterialRequest;
use App\Http\Requests\Print\SelectSizeRequest;
use App\Http\Requests\Print\ShowPrintRequest;
use App\Http\Requests\Print\ShowOverviewRequest;
use App\Http\Requests\Print\CancelPrintRequest;
use App\Http\Requests\Print\ReorderPrintRequest;
use App\Http\Requests\Print\DownloadInvoiceRequest;
use App\Http\Requests\Print\TrackPrintRequest;
use App\Http\Requests\Print\UpdateQuantityRequest;
use App\Services\PrintQuantityService;

class PrintOrderController extends Controller
{
    public function __construct(
        private PrintQuantityService $quantityService
    ) {}

    public function store(InitiatePrintOrderRequest $request, Gallery $gallery)
    {
        // Store quantity in session
        session(['print_order.quantity' => $request->quantity]);

        return redirect()->route('prints.select-size', ['gallery' => $gallery]);
    }

    public function create(InitiatePrintOrderRequest $request, Gallery $gallery)
    {
        return redirect()->route('prints.overview', ['gallery' => $gallery]);
    }

    public function index()
    {
        $orders = PrintOrder::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('prints.index', [
            'orders' => $orders
        ]);
    }

    public function selectSize(SelectSizeRequest $request, Gallery $gallery)
    {
        // Get sizes from config and transform into collection
        $sizes = collect(config('prints.sizes'))->map(function ($categoryData) {
            return [
                'category' => $categoryData['category'],
                'sizes' => collect($categoryData['sizes'])->map(function ($size, $name) use ($categoryData) {
                    return array_merge($size, [
                        'name' => $name,
                        'category' => $categoryData['category']
                    ]);
                })
            ];
        });
        
        return view('prints.select-size', [
            'gallery' => $gallery,
            'sizes' => $sizes
        ]);
    }

    public function storeMaterial(StoreMaterialRequest $request, Gallery $gallery)
    {
        // Calculate unit price based on size and material
        $selectedSize = session('print_order.size');
        // Get base price in cents from config
        $basePrice = collect(config('prints.sizes'))->flatMap(function ($category) {
            return collect($category['sizes']);
        })->get($selectedSize)['price'];

        // Apply material multiplier to get unit price in cents
        $materialMultiplier = config('prints.materials')[$request->material]['price_multiplier'];
        $unitPrice = (int) round($basePrice * $materialMultiplier);

        // Calculate total price in cents based on quantity
        $quantity = session('print_order.quantity', 1);
        $totalPrice = $unitPrice * $quantity;

        // Create the print order with quantity
        $order = PrintOrder::create([
            'user_id' => auth()->id(),
            'gallery_id' => $gallery->id,
            'size' => $selectedSize,
            'material' => $request->material,
            'status' => 'pending',
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice
        ]);

        // Clear the session data as we've created the order
        session()->forget(['print_order.size', 'print_order.material', 'print_order.quantity']);

        // Redirect to checkout
        return redirect()->route('prints.checkout', ['order' => $order]);
    }

    public function storeSize(StoreSizeRequest $request, Gallery $gallery)
    {
        // Store size in session for next step
        session(['print_order.size' => $request->size]);

        return redirect()->route('prints.select-material', ['gallery' => $gallery]);
    }

    public function checkout(ShowCheckoutRequest $request, PrintOrder $order)
    {
        // If shipping address is not set, show the address form first
        if (!$order->shipping_address) {
            return view('prints.checkout-address', [
                'printOrder' => $order
            ]);
        }

        // Get Stripe payment intent
        $stripe = app(StripeService::class);
        $clientSecret = $stripe->createPaymentIntent($order);

        return view('prints.checkout', [
            'printOrder' => $order,
            'clientSecret' => $clientSecret
        ]);
    }

    public function storeShippingAddress(StoreShippingAddressRequest $request, PrintOrder $order)
    {
        $order->update($request->validated());

        return redirect()->route('prints.checkout', ['order' => $order]);
    }

    public function success(ShowSuccessRequest $request, PrintOrder $order)
    {
        return view('prints.success', [
            'printOrder' => $order
        ]);
    }

    public function processPayment(ProcessPaymentRequest $request, PrintOrder $order, ProcessPaymentAction $processPayment)
    {
        try {
            $processPayment->execute($order, $request->payment_intent_id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function selectMaterial(SelectMaterialRequest $request, Gallery $gallery)
    {
        $selectedSize = session('print_order.size');

        // Get size details including price and features
        $sizeDetails = collect(config('prints.sizes'))->flatMap(function ($category) {
            return collect($category['sizes']);
        })->get($selectedSize);

        // Get materials from config with features
        $materials = collect(config('prints.materials'))->map(function ($material, $key) {
            return array_merge($material, [
                'id' => $key,
                'features' => [
                    'Professional-grade print quality',
                    'Archival-quality materials',
                    'UV-resistant inks',
                    'Color-calibrated reproduction'
                ]
            ]);
        });

        return view('prints.select-material', [
            'gallery' => $gallery,
            'materials' => $materials,
            'selectedSize' => $selectedSize,
            'sizeDetails' => $sizeDetails
        ]);
    }

    public function show(ShowPrintRequest $request, PrintOrder $order)
    {
        return view('prints.show', [
            'printOrder' => $order
        ]);
    }

    public function overview(ShowOverviewRequest $request, Gallery $gallery)
    {
        return view('prints.overview', [
            'gallery' => $gallery
        ]);
    }

    public function cancel(CancelPrintRequest $request, PrintOrder $order)
    {
        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return back()->with('success', 'Order cancelled successfully.');
    }

    public function reorder(ReorderPrintRequest $request, PrintOrder $order)
    {
        // Calculate unit price in cents based on size and material
        $basePrice = collect(config('prints.sizes'))->flatMap(function ($category) {
            return collect($category['sizes']);
        })->get($order->size)['price'];

        $materialMultiplier = config('prints.materials')[$order->material]['price_multiplier'];
        $unitPrice = (int) round($basePrice * $materialMultiplier);

        // Create a new order with the same details
        $newOrder = PrintOrder::create([
            'user_id' => auth()->id(),
            'gallery_id' => $order->gallery_id,
            'size' => $order->size,
            'material' => $order->material,
            'status' => 'pending',
            'quantity' => $order->quantity,
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $order->quantity
        ]);

        return redirect()->route('prints.checkout', ['order' => $newOrder]);
    }

    public function track(TrackPrintRequest $request, PrintOrder $order)
    {
        if (!$order->tracking_url) {
            return back()->withErrors(['tracking' => 'Tracking URL is not available.']);
        }

        return redirect($order->tracking_url);
    }

    public function downloadInvoice(DownloadInvoiceRequest $request, PrintOrder $order)
    {
        // Generate and return invoice PDF
        $pdf = app(PrintOrderService::class)->generateInvoice($order);
        
        return response()->download(
            $pdf->getPath(),
            "invoice-{$order->order_number}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }
}
