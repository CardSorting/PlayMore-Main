<?php

namespace App\Policies;

use App\Models\PrintOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrintOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any print orders.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view their orders
    }

    /**
     * Determine if the user can view the print order.
     */
    public function view(User $user, PrintOrder $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine if the user can create print orders.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create orders
    }

    /**
     * Determine if the user can cancel the print order.
     */
    public function cancel(User $user, PrintOrder $order): bool
    {
        if ($user->id !== $order->user_id) {
            return false;
        }

        // Can only cancel pending or processing orders
        return in_array($order->status, ['pending', 'processing']);
    }

    /**
     * Determine if the user can request a refund for the print order.
     */
    public function requestRefund(User $user, PrintOrder $order): bool
    {
        if ($user->id !== $order->user_id) {
            return false;
        }

        // Can only request refund for completed orders within 30 days
        if ($order->status !== 'completed') {
            return false;
        }

        return $order->completed_at?->diffInDays(now()) <= 30;
    }

    /**
     * Determine if the user can track the print order.
     */
    public function track(User $user, PrintOrder $order): bool
    {
        if ($user->id !== $order->user_id) {
            return false;
        }

        // Can only track shipped orders
        return $order->status === 'shipped';
    }

    /**
     * Determine if the user can download the invoice for the print order.
     */
    public function downloadInvoice(User $user, PrintOrder $order): bool
    {
        if ($user->id !== $order->user_id) {
            return false;
        }

        // Can only download invoice for paid orders
        return !is_null($order->paid_at);
    }

    /**
     * Determine if the user can reorder the print.
     */
    public function reorder(User $user, PrintOrder $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine if the user can update shipping information.
     */
    public function updateShipping(User $user, PrintOrder $order): bool
    {
        if ($user->id !== $order->user_id) {
            return false;
        }

        // Can only update shipping for pending or processing orders
        return in_array($order->status, ['pending', 'processing']);
    }

    /**
     * Determine if the user can contact support about the order.
     */
    public function contactSupport(User $user, PrintOrder $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine if the user can leave a review for the order.
     */
    public function review(User $user, PrintOrder $order): bool
    {
        if ($user->id !== $order->user_id) {
            return false;
        }

        // Can only review completed orders
        if ($order->status !== 'completed') {
            return false;
        }

        // Can only review within 60 days of completion
        return $order->completed_at?->diffInDays(now()) <= 60;
    }
}
