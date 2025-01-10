<?php

namespace App\Policies;

use App\Models\{PrintOrder, User};
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrintOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can bypass all checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->can('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any print orders.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own orders
    }

    /**
     * Determine whether the user can view the print order.
     */
    public function view(User $user, PrintOrder $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can create print orders.
     */
    public function create(User $user): Response
    {
        // Check if user has any pending orders
        $pendingCount = PrintOrder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        if ($pendingCount >= 5) {
            return Response::deny('You have too many pending orders. Please complete or cancel them before creating new ones.');
        }

        // Check if user has reached daily limit
        $dailyCount = PrintOrder::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        if ($dailyCount >= 10) {
            return Response::deny('You have reached the maximum number of orders for today.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can cancel the print order.
     */
    public function cancel(User $user, PrintOrder $order): Response
    {
        if ($user->id !== $order->user_id) {
            return Response::deny('You cannot cancel orders that do not belong to you.');
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return Response::deny('This order cannot be cancelled in its current status.');
        }

        if ($order->status === 'processing' && $order->production_started_at) {
            return Response::deny('This order has already entered production and cannot be cancelled.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can request a refund for the print order.
     */
    public function refund(User $user, PrintOrder $order): Response
    {
        if ($user->id !== $order->user_id) {
            return Response::deny('You cannot request refunds for orders that do not belong to you.');
        }

        if (!$order->paid_at) {
            return Response::deny('This order has not been paid for.');
        }

        if ($order->refunded_at) {
            return Response::deny('This order has already been refunded.');
        }

        if (in_array($order->status, ['completed', 'cancelled'])) {
            return Response::deny('Refunds are not available for completed or cancelled orders.');
        }

        // Check refund window
        $refundWindow = config('prints.orders.refund_window_days', 30);
        if ($order->paid_at->addDays($refundWindow)->isPast()) {
            return Response::deny("Refunds are only available within {$refundWindow} days of payment.");
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can reorder the print.
     */
    public function reorder(User $user, PrintOrder $order): Response
    {
        if ($user->id !== $order->user_id) {
            return Response::deny('You cannot reorder prints that do not belong to you.');
        }

        if (!in_array($order->status, ['completed', 'cancelled'])) {
            return Response::deny('You can only reorder completed or cancelled orders.');
        }

        // Check if the original gallery item still exists
        if (!$order->gallery()->exists()) {
            return Response::deny('The original image is no longer available.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can track the print order.
     */
    public function track(User $user, PrintOrder $order): Response
    {
        if ($user->id !== $order->user_id) {
            return Response::deny('You cannot track orders that do not belong to you.');
        }

        if (!$order->tracking_number) {
            return Response::deny('Tracking information is not yet available for this order.');
        }

        if (!in_array($order->status, ['shipped', 'completed'])) {
            return Response::deny('This order has not been shipped yet.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can download the invoice.
     */
    public function downloadInvoice(User $user, PrintOrder $order): Response
    {
        if ($user->id !== $order->user_id) {
            return Response::deny('You cannot access invoices for orders that do not belong to you.');
        }

        if (!$order->paid_at) {
            return Response::deny('Invoice is not available for unpaid orders.');
        }

        if (in_array($order->status, ['pending', 'cancelled'])) {
            return Response::deny('Invoice is not available for pending or cancelled orders.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update shipping information.
     */
    public function updateShipping(User $user, PrintOrder $order): Response
    {
        if ($user->id !== $order->user_id) {
            return Response::deny('You cannot update shipping information for orders that do not belong to you.');
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return Response::deny('Shipping information cannot be updated once an order has been shipped.');
        }

        if ($order->status === 'processing' && $order->production_started_at) {
            return Response::deny('Shipping information cannot be updated once production has started.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the print order.
     */
    public function delete(User $user, PrintOrder $order): bool
    {
        return false; // Orders should never be deleted, only cancelled
    }

    /**
     * Determine whether the user can restore the print order.
     */
    public function restore(User $user, PrintOrder $order): bool
    {
        return false; // Orders should never be restored
    }

    /**
     * Determine whether the user can permanently delete the print order.
     */
    public function forceDelete(User $user, PrintOrder $order): bool
    {
        return false; // Orders should never be force deleted
    }
}
