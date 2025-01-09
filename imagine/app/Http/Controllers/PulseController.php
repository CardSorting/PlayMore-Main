<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\StripeService;

class PulseController extends Controller
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index()
    {
        return view('pulse.index', [
            'amount' => 500,
            'price' => 45,
            'stripeKey' => config('services.stripe.key')
        ]);
    }

    public function createPaymentIntent(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'cart' => 'required|array',
                'cart.*.id' => 'required|string',
                'cart.*.quantity' => 'required|integer|min:1',
                'cart.*.price' => 'required|numeric|min:0'
            ]);

            // Create Stripe payment intent with cart data
            try {
                $paymentIntent = $this->stripeService->createPaymentIntent($validated['cart']);
                
                return response()->json($paymentIntent, 200, [
                    'Content-Type' => 'application/json'
                ]);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                \Log::error('Stripe API error', [
                    'message' => $e->getMessage(),
                    'type' => $e->getError()->type,
                    'code' => $e->getError()->code,
                    'param' => $e->getError()->param,
                ]);
                return response()->json([
                    'error' => 'Stripe API error',
                    'message' => $e->getMessage()
                ], 400, ['Content-Type' => 'application/json']);
            } catch (\Exception $e) {
                \Log::error('Stripe create payment intent error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'error' => 'Failed to create payment intent',
                    'message' => $e->getMessage()
                ], 500, ['Content-Type' => 'application/json']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Invalid request data',
                'details' => $e->errors()
            ], 422, ['Content-Type' => 'application/json']);
        }
    }

    public function confirmPayment(Request $request, $paymentIntentId)
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

            // Confirm the Stripe payment
            $result = $this->stripeService->confirmPayment($paymentIntentId);
            
            // If payment successful, add credits to user's account
            if ($result['status'] === 'COMPLETED') {
                auth()->user()->addCredits(
                    $validated['amount'],
                    'Stripe purchase',
                    $paymentIntentId
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
            \Log::error('Stripe confirm payment error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'paymentIntentId' => $paymentIntentId
            ]);
            return response()->json([
                'error' => 'Failed to confirm payment',
                'message' => $e->getMessage()
            ], 500, ['Content-Type' => 'application/json']);
        }
    }
}
