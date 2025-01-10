<?php

namespace App\Http\Middleware;

use App\Models\PrintOrder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrintOrderAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $printOrder = $request->route('order');

        // If no print order is being accessed, allow the request
        if (!$printOrder instanceof PrintOrder) {
            return $next($request);
        }

        // Check if user owns the print order or is an admin
        if ($request->user()->cannot('view', $printOrder)) {
            abort(403, 'You do not have permission to access this print order.');
        }

        // Check order status for specific routes
        if ($request->routeIs('prints.checkout')) {
            if ($printOrder->status !== 'pending') {
                return redirect()->route('prints.show', $printOrder)
                    ->with('error', 'This order cannot be modified.');
            }
        }

        // Prevent access to completed/cancelled orders for certain actions
        if ($request->routeIs(['prints.update', 'prints.process-payment'])) {
            if (in_array($printOrder->status, ['completed', 'cancelled'])) {
                return redirect()->route('prints.show', $printOrder)
                    ->with('error', 'This order cannot be modified.');
            }
        }

        // Check if order requires payment action
        if ($request->routeIs('prints.success') && $printOrder->requires_action) {
            return redirect()->route('prints.checkout', $printOrder)
                ->with('error', 'Payment requires additional action.');
        }

        // Add order context to view data
        if ($request->routeIs(['prints.*'])) {
            view()->share('currentOrder', $printOrder);
        }

        // Add print order to request for easy access in controllers
        $request->merge(['printOrder' => $printOrder]);

        return $next($request);
    }

    /**
     * Determine the appropriate error response.
     */
    protected function errorResponse(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'status' => 'error'
            ], 403);
        }

        return redirect()->route('prints.index')
            ->with('error', $message);
    }
}
