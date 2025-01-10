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

            // Format abilities as array
            $abilities = $card->getRawOriginal('abilities');
            $abilitiesArray = $abilities 
                ? array_values(array_filter(array_map('trim', explode("\n", $abilities))))
                : [];

            // Format mana cost
            $mana_cost = $card->getRawOriginal('mana_cost') ?: '';
            $mana_cost = implode(',', array_filter(str_split(trim($mana_cost))));

            // Format power/toughness
            $powerToughness = $card->getRawOriginal('power_toughness');
            if ($powerToughness) {
                $parts = array_map('trim', explode('/', $powerToughness));
                $powerToughness = count($parts) === 2 ? implode('/', $parts) : null;
            }

            try {
                // Move card-specific data to metadata with proper formatting
                $newMetadata = [
                    'mana_cost' => $mana_cost,
                    'card_type' => $card->getRawOriginal('card_type') ?: 'Unknown Type',
                    'abilities' => $abilitiesArray,
                    'flavor_text' => $card->getRawOriginal('flavor_text') ?: '',
                    'power_toughness' => $powerToughness,
                    'rarity' => $card->getRawOriginal('rarity') ?: 'Common',
                    'image_url' => $card->image_url
                ];

                // Ensure we're not losing any existing metadata
                $metadata = array_merge($metadata, $newMetadata);

                \Log::info('Updating card metadata:', [
                    'card_id' => $card->id,
                    'old_metadata' => $card->metadata,
                    'new_metadata' => $metadata,
                    'json_encoded' => json_encode($metadata)
                ]);

                // Update using DB to bypass any model events/mutators
                DB::table('galleries')
                    ->where('id', $card->id)
                    ->update([
                        'metadata' => json_encode($metadata, JSON_THROW_ON_ERROR)
                    ]);

                // Verify the update
                $updatedCard = Gallery::find($card->id);
                \Log::info('Verified card metadata:', [
                    'card_id' => $card->id,
                    'raw_metadata' => $updatedCard->getRawOriginal('metadata'),
                    'decoded_metadata' => $updatedCard->metadata
                ]);

            } catch (\Exception $e) {
                \Log::error('Failed to update card metadata:', [
                    'card_id' => $card->id,
                    'error' => $e->getMessage(),
                    'metadata' => $metadata
                ]);
                throw $e;
            }
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

            // Convert abilities array back to string
            $abilities = isset($metadata['abilities']) && is_array($metadata['abilities'])
                ? implode("\n", $metadata['abilities'])
                : $metadata['abilities'] ?? null;

            // Move metadata back to direct columns
            if (isset($metadata['mana_cost'])) {
                $card->mana_cost = str_replace(',', '', $metadata['mana_cost']);
            }
            if (isset($metadata['card_type'])) {
                $card->card_type = $metadata['card_type'];
            }
            if (isset($metadata['abilities'])) {
                $card->abilities = $abilities;
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
