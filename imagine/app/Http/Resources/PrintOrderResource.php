<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrintOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'price' => [
                'amount' => $this->price,
                'formatted' => '$' . number_format($this->price, 2),
                'currency' => 'USD',
            ],
            'refund' => $this->when($this->refunded_amount, fn() => [
                'amount' => $this->refunded_amount,
                'formatted' => '$' . number_format($this->refunded_amount, 2),
                'reason' => $this->refund_reason,
                'processed_at' => $this->refunded_at?->toIso8601String(),
            ]),
            'size' => $this->size,
            'shipping' => [
                'name' => $this->shipping_name,
                'address' => $this->shipping_address,
                'city' => $this->shipping_city,
                'state' => $this->shipping_state,
                'zip' => $this->shipping_zip,
                'country' => $this->shipping_country,
                'carrier' => $this->shipping_carrier,
                'tracking_number' => $this->tracking_number,
                'shipped_at' => $this->shipped_at?->toIso8601String(),
                'estimated_delivery' => $this->when($this->shipped_at, function() {
                    return $this->getShippingEstimate()?->toIso8601String();
                }),
            ],
            'gallery' => $this->whenLoaded('gallery', function() {
                return [
                    'id' => $this->gallery->id,
                    'image_url' => $this->gallery->image_url,
                    'prompt' => $this->gallery->prompt,
                    'aspect_ratio' => $this->gallery->aspect_ratio,
                ];
            }),
            'timeline' => $this->when($request->routeIs('*.show'), function() {
                return [
                    'created' => [
                        'date' => $this->created_at->toIso8601String(),
                        'message' => 'Order created',
                    ],
                    'paid' => $this->when($this->paid_at, fn() => [
                        'date' => $this->paid_at->toIso8601String(),
                        'message' => 'Payment processed',
                    ]),
                    'production' => $this->when($this->production_started_at, fn() => [
                        'date' => $this->production_started_at->toIso8601String(),
                        'message' => 'Print production started',
                    ]),
                    'shipped' => $this->when($this->shipped_at, fn() => [
                        'date' => $this->shipped_at->toIso8601String(),
                        'message' => "Shipped via {$this->shipping_carrier}",
                    ]),
                    'completed' => $this->when($this->completed_at, fn() => [
                        'date' => $this->completed_at->toIso8601String(),
                        'message' => 'Order completed',
                    ]),
                    'cancelled' => $this->when($this->cancelled_at, fn() => [
                        'date' => $this->cancelled_at->toIso8601String(),
                        'message' => $this->cancellation_reason ?? 'Order cancelled',
                    ]),
                ];
            }),
            'dates' => [
                'created_at' => $this->created_at->toIso8601String(),
                'updated_at' => $this->updated_at->toIso8601String(),
                'paid_at' => $this->paid_at?->toIso8601String(),
                'production_started_at' => $this->production_started_at?->toIso8601String(),
                'shipped_at' => $this->shipped_at?->toIso8601String(),
                'completed_at' => $this->completed_at?->toIso8601String(),
                'cancelled_at' => $this->cancelled_at?->toIso8601String(),
                'refunded_at' => $this->refunded_at?->toIso8601String(),
            ],
            'links' => [
                'self' => route('api.prints.show', $this->resource),
                'tracking' => $this->when($this->tracking_number, 
                    fn() => route('api.prints.tracking', $this->tracking_number)
                ),
            ],
            'meta' => [
                'can_cancel' => $request->user()?->can('cancel', $this->resource) ?? false,
                'can_refund' => $request->user()?->can('refund', $this->resource) ?? false,
            ],
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'includes' => [
                    'gallery' => $this->resource->relationLoaded('gallery'),
                    'timeline' => $request->routeIs('*.show'),
                ],
            ],
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     */
    public function withResponse(Request $request, \Illuminate\Http\JsonResponse $response): void
    {
        $response->header('X-Print-Order-Number', $this->order_number);
        
        if ($this->tracking_number) {
            $response->header('X-Tracking-Number', $this->tracking_number);
        }
    }

    /**
     * Get the shipping estimate for the order.
     */
    protected function getShippingEstimate(): ?\Carbon\Carbon
    {
        if (!$this->shipped_at) {
            return null;
        }

        $zoneType = config("location.shipping_zones.{$this->shipping_country}", 'international');
        $days = config("location.shipping_estimates.{$zoneType}", 14);

        return $this->shipped_at->addDays($days);
    }
}
