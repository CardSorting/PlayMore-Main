<?php

namespace App\Actions\Print;

use App\Models\PrintOrder;
use App\DTOs\PaymentData;
use App\Exceptions\PaymentException;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class ProcessPaymentAction
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function execute(PrintOrder $order, string $paymentMethodId): bool
    {
        try {
            $paymentData = PaymentData::fromPrintOrder($order, $paymentMethodId);
            
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) $paymentData->amount,
                'currency' => $paymentData->currency,
                'payment_method' => $paymentData->paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'description' => $paymentData->description,
                'metadata' => $paymentData->metadata,
                'return_url' => route('prints.success', $order),
            ]);

            $this->handlePaymentIntentStatus($order, $paymentIntent);

            return true;
        } catch (ApiErrorException $e) {
            throw new PaymentException(
                "Payment failed: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    protected function handlePaymentIntentStatus(PrintOrder $order, PaymentIntent $intent): void
    {
        switch ($intent->status) {
            case 'requires_action':
            case 'requires_source_action':
                $order->update([
                    'stripe_payment_intent_id' => $intent->id,
                    'stripe_payment_intent_client_secret' => $intent->client_secret,
                    'requires_action' => true,
                ]);
                break;

            case 'succeeded':
                $this->handleSuccessfulPayment($order, $intent);
                break;

            case 'requires_payment_method':
            case 'requires_source':
                throw new PaymentException('The payment was not successful, please try another payment method');

            default:
                throw new PaymentException('The payment status is invalid');
        }
    }

    protected function handleSuccessfulPayment(PrintOrder $order, PaymentIntent $intent): void
    {
        $order->update([
            'status' => 'processing',
            'stripe_payment_intent_id' => $intent->id,
            'stripe_payment_intent_client_secret' => $intent->client_secret,
            'requires_action' => false,
            'paid_at' => now(),
        ]);
    }
}
