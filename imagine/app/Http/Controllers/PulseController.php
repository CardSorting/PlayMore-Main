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
            $validated = $request->validate([
                'cart' => 'required|array',
                'cart.*.id' => 'required|string',
                'cart.*.quantity' => 'required|integer|min:1',
                'cart.*.price' => 'required|numeric|min:0'
            ]);

            // Create PayPal order with cart data
            $order = $this->paypalService->createOrder($validated['cart']);
            
            return response()->json($order, 200, [
                'Content-Type' => 'application/json'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Invalid request data',
                'details' => $e->errors()
            ], 422, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            \Log::error('PayPal create order error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Failed to create order',
                'message' => $e->getMessage()
            ], 500, ['Content-Type' => 'application/json']);
        }
    }

    public function captureOrder(Request $request, $orderId)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'amount' => 'required|integer|min:1'
            ]);

            // Ensure user is authenticated
            if (!auth()->check()) {
                throw new \Exception('User not authenticated');
            }

            // Capture the PayPal order
            $result = $this->paypalService->captureOrder($orderId);
            
            // If capture successful, add credits to user's account
            if ($result['status'] === 'COMPLETED') {
                auth()->user()->addCredits(
                    $validated['amount'],
                    'PayPal purchase',
                    $orderId
                );

                return response()->json([
                    'status' => 'COMPLETED',
                    'message' => 'Payment processed successfully'
                ], 200, ['Content-Type' => 'application/json']);
            }
            
            return response()->json([
                'status' => $result['status'],
                'message' => 'Payment not completed'
            ], 400, ['Content-Type' => 'application/json']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Invalid request data',
                'details' => $e->errors()
            ], 422, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            \Log::error('PayPal capture order error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'orderId' => $orderId
            ]);
            return response()->json([
                'error' => 'Failed to capture order',
                'message' => $e->getMessage()
            ], 500, ['Content-Type' => 'application/json']);
        }
    }
}
