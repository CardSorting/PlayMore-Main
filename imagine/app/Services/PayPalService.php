<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private string $baseUrl;
    private ?string $accessToken = null;

    public function __construct()
    {
        $this->baseUrl = config('services.paypal.sandbox')
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }

    private function generateAccessToken(): string
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');

        if (!$clientId || !$clientSecret) {
            throw new \Exception('PayPal API credentials are missing');
        }

        $auth = base64_encode("$clientId:$clientSecret");

        try {
            $response = Http::withHeaders([
                'Authorization' => "Basic $auth",
            ])->asForm()->post("$this->baseUrl/v1/oauth2/token", [
                'grant_type' => 'client_credentials',
            ]);

            if ($response->successful()) {
                $this->accessToken = $response->json('access_token');
                return $this->accessToken;
            }

            throw new \Exception('Failed to generate PayPal access token');
        } catch (\Exception $e) {
            Log::error('PayPal access token generation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getAccessToken(): string
    {
        return $this->accessToken ?? $this->generateAccessToken();
    }

    public function createOrder(array $cart): array
    {
        try {
            // Get the first item from cart
            $item = $cart[0];
            $price = $item['price'] ?? 10; // Default to $10 if price not provided

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("$this->baseUrl/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => number_format($price, 2, '.', ''),
                        ],
                        'description' => 'Purchase ' . $item['id'],
                    ],
                ],
                'application_context' => [
                    'return_url' => route('pulse.index'),
                    'cancel_url' => route('pulse.index'),
                ],
            ]);

            if (!$response->successful()) {
                Log::error('PayPal create order failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                throw new \Exception($response->json()['message'] ?? 'Failed to create PayPal order');
            }

            $data = $response->json();
            if (empty($data['id'])) {
                throw new \Exception('Invalid order response from PayPal');
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('PayPal create order failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function captureOrder(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("$this->baseUrl/v2/checkout/orders/$orderId/capture");

            if (!$response->successful()) {
                Log::error('PayPal capture order failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                throw new \Exception($response->json()['message'] ?? 'Failed to capture PayPal order');
            }

            $data = $response->json();
            if (empty($data['status'])) {
                throw new \Exception('Invalid capture response from PayPal');
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('PayPal capture order failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
