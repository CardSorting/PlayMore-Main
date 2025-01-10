<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'rated_by',
        'print_order_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer'
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_by');
    }

    public function printOrder(): BelongsTo
    {
        return $this->belongsTo(PrintOrder::class);
    }
}
