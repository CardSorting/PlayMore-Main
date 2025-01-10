<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $query = $user->printOrders()->with('gallery');

        // Apply filters
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'last30days':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'past3months':
                    $query->where('created_at', '>=', now()->subMonths(3));
                    break;
                case '2024':
                    $query->whereYear('created_at', '2024');
                    break;
                case '2023':
                    $query->whereYear('created_at', '2023');
                    break;
            }
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('shipping_name', 'like', "%{$search}%")
                  ->orWhere('shipping_address', 'like', "%{$search}%")
                  ->orWhere('shipping_city', 'like', "%{$search}%")
                  ->orWhere('shipping_state', 'like', "%{$search}%")
                  ->orWhere('size', 'like', "%{$search}%");
            });
        }

        // Get order counts for filter badges
        $orderCounts = [
            'total' => $user->printOrders()->count(),
            'pending' => $user->printOrders()->where('status', 'pending')->count(),
            'shipped' => $user->printOrders()->where('status', 'shipped')->count(),
            'completed' => $user->printOrders()->where('status', 'completed')->count(),
        ];

        $printOrders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('profile.edit', [
            'user' => $user,
            'printOrders' => $printOrders,
            'orderCounts' => $orderCounts,
            'filters' => [
                'period' => $request->period,
                'status' => $request->status,
                'search' => $request->search
            ]
        ]);
    }

    public function settings(Request $request): View
    {
        return view('profile.settings', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function showOrder(PrintOrder $order): View
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('profile.orders.show', [
            'order' => $order->load('gallery')
        ]);
    }
}
