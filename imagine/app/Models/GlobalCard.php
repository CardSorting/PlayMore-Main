<?php

namespace App\Models;

use App\Contracts\Card;
use App\Models\Traits\HasCardMetadata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalCard extends Model implements Card
{
    use HasFactory, HasCardMetadata;

    protected $fillable = [
        'pack_id',
        'original_user_id',
        'type',
        'name',
        'image_url',
        'prompt',
        'aspect_ratio',
        'process_mode',
        'task_id',
        'metadata',
        'mana_cost',
        'card_type',
        'abilities',
        'flavor_text',
        'power_toughness',
        'rarity'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function pack(): BelongsTo
    {
        return $this->belongsTo(Pack::class);
    }

    public function originalUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'original_user_id');
    }

    // Card interface implementation
    public function getName(): string
    {
        return $this->name;
    }

    public function getPrompt(): ?string
    {
        return $this->prompt;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
