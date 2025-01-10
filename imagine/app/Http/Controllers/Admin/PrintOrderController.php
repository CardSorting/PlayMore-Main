<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintOrder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrintOrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PrintOrder::class, 'order');
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', PrintOrder::class);
        
        $query = PrintOrder::with(['user', 'gallery'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order ID, customer name, or shipping address
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('shipping_name', 'like', "%{$search}%")
                  ->orWhere('shipping_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        // Get order statistics
        if ($this->authorize('viewReports', PrintOrder::class)) {
            $stats = [
                'total' => PrintOrder::count(),
                'pending' => PrintOrder::where('status', 'pending')->count(),
                'processing' => PrintOrder::where('status', 'processing')->count(),
                'shipped' => PrintOrder::where('status', 'shipped')->count(),
                'completed' => PrintOrder::where('status', 'completed')->count(),
                'revenue' => PrintOrder::whereIn('status', ['processing', 'shipped', 'completed'])
                    ->sum('price'),
            ];
        } else {
            $stats = [];
        }

        return view('admin.prints.index', [
            'orders' => $orders,
            'stats' => $stats,
            'filters' => $request->only(['status', 'date_from', 'date_to', 'search'])
        ]);
    }

    public function show(PrintOrder $order): View
    {
        $this->authorize('view', $order);
        
        $order->load(['user', 'gallery']);
        
        return view('admin.prints.show', [
            'order' => $order
        ]);
    }

    public function updateStatus(Request $request, PrintOrder $order): RedirectResponse
    {
        $this->authorize('manageStatus', PrintOrder::class);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Order status updated successfully');
    }

    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $this->authorize('manageStatus', PrintOrder::class);

        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'exists:print_orders,id',
            'status' => 'required|in:pending,processing,shipped,completed'
        ]);

        PrintOrder::whereIn('id', $request->orders)
            ->update(['status' => $request->status]);

        return back()->with('success', 'Orders updated successfully');
    }

    public function export(Request $request)
    {
        $this->authorize('export', PrintOrder::class);

        $query = PrintOrder::with(['user', 'gallery']);

        // Apply filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="print-orders-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'Order ID',
                'Date',
                'Customer Name',
                'Email',
                'Print Size',
                'Price',
                'Status',
                'Shipping Name',
                'Shipping Address',
                'City',
                'State',
                'ZIP',
                'Country'
            ]);

            // Add data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->user->name,
                    $order->user->email,
                    PrintOrder::getSizeName($order->size),
                    $order->price,
                    $order->status,
                    $order->shipping_name,
                    $order->shipping_address,
                    $order->shipping_city,
                    $order->shipping_state,
                    $order->shipping_zip,
                    $order->shipping_country
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
