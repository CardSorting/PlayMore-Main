<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gallery;
use App\Models\Rating;
use Illuminate\Database\Seeder;

class StoreDataSeeder extends Seeder
{
    public function run(): void
    {
        // Update user with seller info
        $user = User::where('name', 'Bozo Egg')->first();
        if ($user) {
            $user->update([
                'country' => 'United States',
                'state' => 'California',
                'city' => 'San Francisco',
                'bio' => 'Digital artist specializing in AI-generated artwork. Creating unique pieces that blend technology with creativity.',
                'website' => 'https://bozoegg.com',
                'social_instagram' => 'bozoegg',
                'social_twitter' => 'bozoegg',
                'avg_response_minutes' => 30,
                'last_response_at' => now(),
                'last_active_at' => now(),
                'is_seller' => true,
                'shipping_countries' => ['United States', 'Canada', 'United Kingdom', 'Australia'],
                'seller_settings' => [
                    'auto_fulfill' => true,
                    'notification_preferences' => [
                        'email' => true,
                        'push' => true
                    ]
                ]
            ]);

            // Update galleries with prices and availability
            foreach ($user->galleries as $gallery) {
                $gallery->update([
                    'price' => rand(1999, 9999) / 100,
                    'is_available' => true,
                    'views_count' => rand(50, 500)
                ]);
            }

            // Add some sample ratings
            $ratings = [
                [
                    'rating' => 5,
                    'comment' => 'Amazing quality and fast shipping! The print looks even better in person.',
                ],
                [
                    'rating' => 4,
                    'comment' => 'Great artwork, very unique style. Would buy again.',
                ],
                [
                    'rating' => 5,
                    'comment' => 'Excellent communication and the print quality is outstanding.',
                ],
            ];

            foreach ($ratings as $rating) {
                Rating::create([
                    'user_id' => $user->id,
                    'rated_by' => User::where('id', '!=', $user->id)->inRandomOrder()->first()->id,
                    'rating' => $rating['rating'],
                    'comment' => $rating['comment'],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
