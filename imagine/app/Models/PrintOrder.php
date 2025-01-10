<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PrintOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gallery_id',
        'size',
        'material',
        'price',
        'status',
        'shipping_name',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_country',
        'stripe_payment_intent_id',
        'notes',
        'tracking_number',
        'shipping_carrier',
        'order_number',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Generate a unique order number if not set
            if (!$order->order_number) {
                do {
                    $order->order_number = 'PRT' . strtoupper(substr(uniqid(), -6));
                } while (static::where('order_number', $order->order_number)->exists());
            }
        });
    }

    protected $casts = [
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function getFormattedAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->shipping_address,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_zip,
            $this->shipping_country
        ]));
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2);
    }

    public function getTrackingUrlAttribute(): ?string
    {
        if (!$this->tracking_number || !$this->shipping_carrier) {
            return null;
        }

        $carriers = config('prints.shipping.carriers');
        if (!isset($carriers[$this->shipping_carrier])) {
            return null;
        }

        return $carriers[$this->shipping_carrier]['tracking_url'] . $this->tracking_number;
    }

    public function getMaterialNameAttribute(): string
    {
        $materials = config('prints.materials');
        return $materials[$this->material]['name'] ?? $this->material;
    }

    public function getSizeNameAttribute(): string
    {
        $sizes = config('prints.sizes');
        return $sizes[$this->size]['name'] ?? $this->size;
    }

    public function getEstimatedDeliveryDaysAttribute(): int
    {
        $baseDays = config('prints.shipping.carriers.usps.services.first_class.days', '3-5');
        $baseDays = explode('-', $baseDays)[1] ?? 5; // Use the higher number

        // Add extra days for international shipping
        if ($this->shipping_country !== 'US') {
            $baseDays += 3;
        }

        // Add extra processing time for canvas prints
        if ($this->material === 'canvas') {
            $baseDays += 2;
        }

        return (int) $baseDays;
    }

    public function getEstimatedDeliveryDateAttribute(): ?string
    {
        if (!$this->created_at) {
            return null;
        }

        return $this->created_at
            ->addWeekdays($this->estimated_delivery_days)
            ->format('F j, Y');
    }

    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    public function isShipped(): bool
    {
        return $this->shipped_at !== null;
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function isCancelled(): bool
    {
        return $this->cancelled_at !== null;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']) && !$this->isShipped();
    }

    public function canBeRefunded(): bool
    {
        return $this->isPaid() && !$this->isCancelled() && 
               $this->paid_at->addDays(30)->isFuture();
    }
}
