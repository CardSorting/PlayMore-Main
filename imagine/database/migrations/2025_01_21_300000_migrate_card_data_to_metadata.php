<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Gallery;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all cards
        $cards = Gallery::where('type', 'card')->get();

        foreach ($cards as $card) {
            $metadata = $card->metadata ?? [];

            \Log::info('Migrating card:', [
                'card_id' => $card->id,
                'original_data' => [
                    'mana_cost' => $card->getRawOriginal('mana_cost'),
                    'card_type' => $card->getRawOriginal('card_type'),
                    'abilities' => $card->getRawOriginal('abilities'),
                    'flavor_text' => $card->getRawOriginal('flavor_text'),
                    'power_toughness' => $card->getRawOriginal('power_toughness'),
                    'rarity' => $card->getRawOriginal('rarity')
                ],
                'existing_metadata' => $metadata
            ]);

            // Move card-specific data to metadata with proper defaults
            $mana_cost = $card->getRawOriginal('mana_cost') ?: '';
            $metadata['mana_cost'] = implode(',', str_split($mana_cost));
            $metadata['card_type'] = $card->getRawOriginal('card_type') ?: 'Unknown Type';
            $metadata['abilities'] = $card->getRawOriginal('abilities') ?: 'No abilities';
            $metadata['flavor_text'] = $card->getRawOriginal('flavor_text') ?: '';
            $metadata['power_toughness'] = $card->getRawOriginal('power_toughness');
            $metadata['rarity'] = $card->getRawOriginal('rarity') ?: 'Common';

            \Log::info('Updated card metadata:', [
                'card_id' => $card->id,
                'new_metadata' => $metadata
            ]);

            // Update the card with the new metadata
            $card->metadata = $metadata;
            $card->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all cards
        $cards = Gallery::where('type', 'card')->get();

        foreach ($cards as $card) {
            $metadata = $card->metadata ?? [];

            \Log::info('Rolling back card:', [
                'card_id' => $card->id,
                'current_metadata' => $metadata
            ]);

            // Move metadata back to direct columns
            if (isset($metadata['mana_cost'])) {
                $card->mana_cost = $metadata['mana_cost'];
            }
            if (isset($metadata['card_type'])) {
                $card->card_type = $metadata['card_type'];
            }
            if (isset($metadata['abilities'])) {
                $card->abilities = $metadata['abilities'];
            }
            if (isset($metadata['flavor_text'])) {
                $card->flavor_text = $metadata['flavor_text'];
            }
            if (isset($metadata['power_toughness'])) {
                $card->power_toughness = $metadata['power_toughness'];
            }
            if (isset($metadata['rarity'])) {
                $card->rarity = $metadata['rarity'];
            }

            // Remove these fields from metadata
            unset(
                $metadata['mana_cost'],
                $metadata['card_type'],
                $metadata['abilities'],
                $metadata['flavor_text'],
                $metadata['power_toughness'],
                $metadata['rarity']
            );

            $card->metadata = $metadata;
            $card->save();

            \Log::info('Card rolled back:', [
                'card_id' => $card->id,
                'final_data' => [
                    'mana_cost' => $card->mana_cost,
                    'card_type' => $card->card_type,
                    'abilities' => $card->abilities,
                    'flavor_text' => $card->flavor_text,
                    'power_toughness' => $card->power_toughness,
                    'rarity' => $card->rarity
                ],
                'remaining_metadata' => $metadata
            ]);
        }
    }
};
