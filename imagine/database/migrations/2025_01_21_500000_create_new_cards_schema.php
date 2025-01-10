<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create the main cards table
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('gallery_id')->nullable()->constrained()->nullOnDelete(); // Reference to original gallery image
            $table->string('name');
            $table->string('mana_cost');
            $table->string('card_type');
            $table->text('flavor_text')->nullable();
            $table->string('power_toughness')->nullable();
            $table->enum('rarity', ['Common', 'Uncommon', 'Rare', 'Mythic Rare'])->default('Common');
            $table->string('image_url');
            $table->timestamps();
            
            // Add indexes for common queries
            $table->index('user_id');
            $table->index('rarity');
            $table->index('created_at');
        });

        // Create abilities table for better normalization
        Schema::create('card_abilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->onDelete('cascade');
            $table->text('ability_text');
            $table->integer('order')->default(0); // For maintaining ability order
            $table->timestamps();

            // Add index for quick lookups
            $table->index('card_id');
        });

        // Migrate existing card data
        $cards = DB::table('galleries')
            ->where('type', 'card')
            ->get();

        foreach ($cards as $gallery) {
            // Get metadata or fallback to direct columns
            $metadata = json_decode($gallery->metadata ?? '{}', true) ?? [];
            
            // Create new card record
            $cardId = DB::table('cards')->insertGetId([
                'user_id' => $gallery->user_id,
                'gallery_id' => $gallery->id,
                'name' => $gallery->name,
                'mana_cost' => $metadata['mana_cost'] ?? $gallery->mana_cost ?? '',
                'card_type' => $metadata['card_type'] ?? $gallery->card_type ?? 'Unknown Type',
                'flavor_text' => $metadata['flavor_text'] ?? $gallery->flavor_text ?? '',
                'power_toughness' => $metadata['power_toughness'] ?? $gallery->power_toughness ?? null,
                'rarity' => $metadata['rarity'] ?? $gallery->rarity ?? 'Common',
                'image_url' => $gallery->image_url,
                'created_at' => $gallery->created_at,
                'updated_at' => $gallery->updated_at
            ]);

            // Handle abilities
            $abilities = $metadata['abilities'] ?? $gallery->abilities ?? '';
            if (is_string($abilities)) {
                $abilities = array_filter(array_map('trim', explode("\n", $abilities)));
            } elseif (is_array($abilities)) {
                $abilities = array_filter($abilities);
            } else {
                $abilities = [];
            }

            // Insert abilities
            foreach ($abilities as $index => $ability) {
                DB::table('card_abilities')->insert([
                    'card_id' => $cardId,
                    'ability_text' => $ability,
                    'order' => $index,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('card_abilities');
        Schema::dropIfExists('cards');
    }
};
