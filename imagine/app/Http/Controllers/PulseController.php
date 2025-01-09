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
                'cart.*.price' => 'required|numeric|min:0'
            ]);

            // Create PayPal order with cart data
            $order = $this->paypalService->createOrder($request->input('cart'));
            
            if (!isset($order['id'])) {
                throw new \Exception('Invalid order response from PayPal');
            }
            
            return response()->json($order);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Invalid request data: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            \Log::error('PayPal create order error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create order: ' . $e->getMessage()], 500);
        }
    }

    public function captureOrder(Request $request, $orderId)
    {
        try {
            // Validate the request
            $request->validate([
                'amount' => 'required|integer|min:1'
            ]);

            $pulseAmount = $request->input('amount');
            
            // Capture the PayPal order
            $result = $this->paypalService->captureOrder($orderId);
            
            // Validate the capture response
            if (!isset($result['status'])) {
                throw new \Exception('Invalid capture response from PayPal');
            }
            
            // If capture successful, add credits to user's account
            if ($result['status'] === 'COMPLETED') {
                $user = auth()->user();
                if (!$user) {
                    throw new \Exception('User not authenticated');
                }
                $user->addCredits($pulseAmount, 'PayPal purchase', $orderId);
            } else {
                throw new \Exception('Payment not completed: ' . $result['status']);
            }
            
            return response()->json($result);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Invalid request data: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            \Log::error('PayPal capture order error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to capture order: ' . $e->getMessage()], 500);
        }
    }
}
