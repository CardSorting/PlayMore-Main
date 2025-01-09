<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent(array $cart): array
    {
        try {
            // Get the first item from cart
            $item = $cart[0];
            $price = $item['price'] ?? 10; // Default to $10 if price not provided
            
            // Create a PaymentIntent with amount and currency
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($price * 100), // Convert to cents
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ],
            ]);

            return [
                'clientSecret' => $paymentIntent->client_secret,
                'id' => $paymentIntent->id,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe create payment intent failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function confirmPayment(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            if ($paymentIntent->status === 'succeeded') {
                return [
                    'status' => 'COMPLETED',
                    'id' => $paymentIntent->id,
                ];
            }

            return [
                'status' => strtoupper($paymentIntent->status),
                'id' => $paymentIntent->id,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe confirm payment failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
