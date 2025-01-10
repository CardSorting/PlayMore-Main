<?php

namespace App\DTOs;

class PaymentData
{
    public readonly string $paymentMethodId;
    public readonly float $amount;
    public readonly string $currency;
    public readonly ?string $description;
    public readonly array $metadata;

    public function __construct(
        string $paymentMethodId,
        float $amount,
        string $currency = 'usd',
        ?string $description = null,
        array $metadata = []
    ) {
        $this->paymentMethodId = $paymentMethodId;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
        $this->metadata = $metadata;
    }

    public static function fromPrintOrder(\App\Models\PrintOrder $order, string $paymentMethodId): self
    {
        return new self(
            paymentMethodId: $paymentMethodId,
            amount: $order->price * 100, // Convert to cents for Stripe
            description: "Print Order #{$order->order_number}",
            metadata: [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'size' => $order->size,
                'user_id' => $order->user_id,
            ]
        );
    }
}
