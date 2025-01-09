<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe as StripeClient;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\InvalidRequestException;

class StripeService
{
    public function __construct()
    {
        StripeClient::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent(array $cart): array
    {
        try {
            Log::info('Creating payment intent with cart:', $cart);

            if (empty($cart)) {
                throw new \InvalidArgumentException('Cart cannot be empty');
            }

            // Get the first item from cart
            $item = $cart[0];
            if (!isset($item['price'])) {
                throw new \InvalidArgumentException('Price is required in cart item');
            }

            $price = $item['price'];
            $amount = (int) ($price * 100); // Convert to cents

            Log::info('Creating Stripe payment intent', [
                'amount' => $amount,
                'currency' => 'usd',
                'metadata' => [
                    'item_id' => $item['id'] ?? 'unknown',
                    'quantity' => $item['quantity'] ?? 1,
                ],
            ]);
            
            // Create a PaymentIntent with amount and currency
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'item_id' => $item['id'] ?? 'unknown',
                    'quantity' => $item['quantity'] ?? 1,
                ],
            ]);

            Log::info('Payment intent created successfully', [
                'id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
            ]);

            return [
                'clientSecret' => $paymentIntent->client_secret,
                'id' => $paymentIntent->id,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe API error creating payment intent', [
                'message' => $e->getMessage(),
                'type' => $e->getError()->type ?? null,
                'code' => $e->getError()->code ?? null,
                'param' => $e->getError()->param ?? null,
                'cart' => $cart,
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating payment intent', [
                'message' => $e->getMessage(),
                'cart' => $cart,
                'trace' => $e->getTraceAsString(),
            ]);
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
