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

            // Move card-specific data to metadata if not already there
            if (!isset($metadata['mana_cost'])) {
                $metadata['mana_cost'] = $card->getRawOriginal('mana_cost');
            }
            if (!isset($metadata['type'])) {
                $metadata['type'] = $card->getRawOriginal('card_type');
            }
            if (!isset($metadata['abilities'])) {
                $metadata['abilities'] = $card->getRawOriginal('abilities');
            }
            if (!isset($metadata['flavor_text'])) {
                $metadata['flavor_text'] = $card->getRawOriginal('flavor_text');
            }
            if (!isset($metadata['power_toughness'])) {
                $metadata['power_toughness'] = $card->getRawOriginal('power_toughness');
            }
            if (!isset($metadata['rarity'])) {
                $metadata['rarity'] = $card->getRawOriginal('rarity');
            }

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

            // Move metadata back to direct columns
            if (isset($metadata['mana_cost'])) {
                $card->mana_cost = $metadata['mana_cost'];
            }
            if (isset($metadata['type'])) {
                $card->card_type = $metadata['type'];
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
            unset($metadata['mana_cost'], $metadata['type'], $metadata['abilities'], 
                  $metadata['flavor_text'], $metadata['power_toughness'], $metadata['rarity']);
            
            $card->metadata = $metadata;
            $card->save();
        }
    }
};
