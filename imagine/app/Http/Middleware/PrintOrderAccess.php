<?php

namespace App\Http\Middleware;

use App\Models\PrintOrder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PrintOrderAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $order = $request->route('order');

        // If no order parameter in route, continue
        if (!$order) {
            return $next($request);
        }

        // Ensure order is a PrintOrder instance
        if (!($order instanceof PrintOrder)) {
            throw new NotFoundHttpException('Print order not found.');
        }

        $user = $request->user();

        // Admin users can access all orders
        if ($user->can('admin')) {
            return $next($request);
        }

        // Users can only access their own orders
        if ($order->user_id !== $user->id) {
            throw new NotFoundHttpException('Print order not found.');
        }

        // Check order status restrictions
        if ($this->isStatusRestricted($request, $order)) {
            abort(403, 'This action is not allowed for orders in this status.');
        }

        // Check action-specific permissions
        if (!$this->canPerformAction($request, $order)) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }

    /**
     * Check if the order status restricts the requested action.
     */
    protected function isStatusRestricted(Request $request, PrintOrder $order): bool
    {
        // Get the current route name without the prefix
        $action = str_replace('prints.', '', $request->route()->getName());

        // Define status restrictions for different actions
        $restrictions = [
            'cancel' => ['shipped', 'completed', 'cancelled'],
            'reorder' => ['pending'],
            'process-payment' => ['completed', 'cancelled'],
            'track' => ['pending'],
            'invoice' => ['pending', 'cancelled'],
        ];

        // If action has restrictions and order status is in restricted list
        return isset($restrictions[$action]) && 
               in_array($order->status, $restrictions[$action]);
    }

    /**
     * Check if the user can perform the requested action.
     */
    protected function canPerformAction(Request $request, PrintOrder $order): bool
    {
        $user = $request->user();
        $action = str_replace('prints.', '', $request->route()->getName());

        // Define permission checks for different actions
        $permissions = [
            'cancel' => fn() => $order->status === 'pending' || 
                              ($order->status === 'processing' && !$order->production_started_at),
            'reorder' => fn() => $order->status === 'completed' || $order->status === 'cancelled',
            'process-payment' => fn() => $order->status === 'pending' && !$order->paid_at,
            'track' => fn() => $order->tracking_number && in_array($order->status, ['shipped', 'completed']),
            'invoice' => fn() => $order->paid_at && !in_array($order->status, ['pending', 'cancelled']),
        ];

        // If action requires specific permission check
        if (isset($permissions[$action])) {
            return $permissions[$action]();
        }

        // Default to true for other actions (show, index, etc.)
        return true;
    }

    /**
     * Get the path the user should be redirected to when they are not authorized.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('prints.index');
    }

    /**
     * Determine if the middleware should be applied.
     */
    public static function shouldApply(Request $request): bool
    {
        // Skip middleware for certain routes if needed
        $excludedRoutes = [
            'prints.create',
            'prints.store',
        ];

        return !in_array($request->route()->getName(), $excludedRoutes);
    }
}
