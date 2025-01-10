<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalCard extends Model
{
    use HasFactory;

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

    public static function createFromGallery(Gallery $gallery, int $packId): self
    {
        return static::create([
            'pack_id' => $packId,
            'original_user_id' => $gallery->user_id,
            'type' => $gallery->type,
            'name' => $gallery->name,
            'image_url' => $gallery->getImageUrlAttribute(),
            'prompt' => $gallery->prompt,
            'aspect_ratio' => $gallery->aspect_ratio,
            'process_mode' => $gallery->process_mode,
            'task_id' => $gallery->task_id,
            'metadata' => $gallery->metadata,
            'mana_cost' => $gallery->metadata['mana_cost'] ?? null,
            'card_type' => $gallery->metadata['type'] ?? null,
            'abilities' => $gallery->metadata['abilities'] ?? null,
            'flavor_text' => $gallery->metadata['flavor_text'] ?? null,
            'power_toughness' => $gallery->metadata['power_toughness'] ?? null,
            'rarity' => $gallery->metadata['rarity'] ?? null
        ]);
    }
}
