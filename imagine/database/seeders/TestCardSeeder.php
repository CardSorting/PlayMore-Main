<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\CardAbility;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestCardSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);
        }

        // Create a test gallery image first
        $gallery = Gallery::create([
            'user_id' => $user->id,
            'type' => 'image',
            'name' => 'Test Bear',
            'image_url' => 'https://cdn.midjourney.com/test-image.png',
            'metadata' => '[]'
        ]);

        // Create a test card
        $card = Card::create([
            'user_id' => $user->id,
            'gallery_id' => $gallery->id,
            'name' => 'Test Bear',
            'mana_cost' => '2G',
            'card_type' => 'Creature - Bear',
            'flavor_text' => 'Don\'t poke the bear.',
            'power_toughness' => '2/2',
            'rarity' => 'Common',
            'image_url' => $gallery->image_url
        ]);

        // Add abilities
        CardAbility::create([
            'card_id' => $card->id,
            'ability_text' => 'Trample',
            'order' => 0
        ]);

        \Log::info('Test card seeded', [
            'card_id' => $card->id,
            'gallery_id' => $gallery->id,
            'user_id' => $user->id
        ]);
    }
}
