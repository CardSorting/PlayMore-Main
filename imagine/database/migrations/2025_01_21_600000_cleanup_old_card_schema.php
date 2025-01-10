<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Gallery;
use App\Models\Card;
use App\DTOs\CardMetadata;

return new class extends Migration
{
    public function up(): void
    {
        // First ensure all card data is migrated to new schema
        $cards = Gallery::where('type', 'card')->get();

        foreach ($cards as $gallery) {
            // Skip if already migrated
            if (Card::where('gallery_id', $gallery->id)->exists()) {
                continue;
            }

            try {
                $metadata = is_string($gallery->metadata) 
                    ? json_decode($gallery->metadata, true) 
                    : ($gallery->metadata ?? []);
                
                // Create card in new schema
                $card = Card::create([
                    'user_id' => $gallery->user_id,
                    'gallery_id' => $gallery->id,
                    'name' => $gallery->name,
                    'mana_cost' => $metadata['mana_cost'] ?? '',
                    'card_type' => $metadata['card_type'] ?? 'Unknown Type',
                    'flavor_text' => $metadata['flavor_text'] ?? '',
                    'power_toughness' => $metadata['power_toughness'] ?? null,
                    'rarity' => $metadata['rarity'] ?? 'Common',
                    'image_url' => $gallery->image_url,
                    'created_at' => $gallery->created_at,
                    'updated_at' => $gallery->updated_at
                ]);

                // Create abilities
                $abilities = $metadata['abilities'] ?? [];
                if (is_string($abilities)) {
                    $abilities = array_filter(array_map('trim', explode("\n", $abilities)));
                }

                foreach ($abilities as $index => $ability) {
                    DB::table('card_abilities')->insert([
                        'card_id' => $card->id,
                        'ability_text' => $ability,
                        'order' => $index,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                // Update gallery type to image and clear metadata
                DB::table('galleries')
                    ->where('id', $gallery->id)
                    ->update([
                        'type' => 'image',
                        'metadata' => '[]'
                    ]);

                \Log::info('Migrated card to new schema', [
                    'gallery_id' => $gallery->id,
                    'new_card_id' => $card->id
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to migrate card', [
                    'gallery_id' => $gallery->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Drop old card columns from galleries table if they exist
        Schema::table('galleries', function (Blueprint $table) {
            $columns = [
                'mana_cost',
                'card_type',
                'abilities',
                'flavor_text',
                'power_toughness',
                'rarity'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('galleries', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        \Log::info('Completed card schema cleanup');
    }

    public function down(): void
    {
        // Since this is a cleanup migration, down() is a no-op
        // We don't want to recreate old columns or revert data
        // as that would be handled by the main schema migration
    }
};
