<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\PayPalService;

class PulseController extends Controller
{
    public function index()
    {
        $pulseOptions = [
            ['amount' => 100, 'price' => 10],
            ['amount' => 500, 'price' => 45],
            ['amount' => 1000, 'price' => 80],
        ];

        return view('pulse.index', [
            'pulseOptions' => $pulseOptions,
            'defaultOption' => $pulseOptions[0],
            'paypalClientId' => config('services.paypal.client_id')
        ]);
    }

    private PayPalService $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function createOrder(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'cart' => 'required|array',
                'cart.*.id' => 'required|string',
                'cart.*.quantity' => 'required|integer|min:1',
            ]);

            // Create PayPal order with cart data
            $order = $this->paypalService->createOrder($request->input('cart'));
            
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function captureOrder(Request $request, $orderId)
    {
        try {
            // Get the pulse amount from the request
            $pulseAmount = $request->input('amount', 100);
            
            // Capture the PayPal order
            $result = $this->paypalService->captureOrder($orderId);
            
            // If capture successful, add credits to user's account
            if ($result['status'] === 'COMPLETED') {
                $user = auth()->user();
                $user->addCredits($pulseAmount, 'PayPal purchase', $orderId);
            }
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
