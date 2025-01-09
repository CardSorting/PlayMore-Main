<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PulseController extends Controller
{
    public function index()
    {
        $pulseAmounts = [
            ['amount' => 100, 'price' => 10],
            ['amount' => 500, 'price' => 45],
            ['amount' => 1000, 'price' => 80],
        ];

        return view('pulse.index', [
            'pulseAmounts' => $pulseAmounts
        ]);
    }

    public function createOrder(Request $request)
    {
        // Validate the request
        $request->validate([
            'cart' => 'required|array',
            'cart.*.id' => 'required|string',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        // Here you would integrate with PayPal's API to create an order
        // This is a placeholder that should be replaced with actual PayPal API integration
        return response()->json([
            'id' => 'MOCK_ORDER_ID_' . time(),
        ]);
    }

    public function captureOrder(Request $request, $orderId)
    {
        // Here you would integrate with PayPal's API to capture the payment
        // This is a placeholder that should be replaced with actual PayPal API integration
        
        // After successful capture, add the pulse to the user's account
        $user = auth()->user();
        $user->addCredits(100, 'PayPal purchase', $orderId);

        return response()->json([
            'purchase_units' => [
                [
                    'payments' => [
                        'captures' => [
                            [
                                'id' => 'MOCK_CAPTURE_' . time(),
                                'status' => 'COMPLETED',
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}
