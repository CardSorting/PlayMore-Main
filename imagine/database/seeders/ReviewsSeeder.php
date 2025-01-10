<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rating;
use App\Models\PrintOrder;
use Illuminate\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    public function run(): void
    {
        // Get some users to be reviewers
        $reviewers = User::where('id', '!=', 3)->take(5)->get(); // Exclude Bozo Egg (id: 3)
        
        // Get Bozo Egg's completed print orders
        $printOrders = PrintOrder::where('user_id', 3)
            ->where('status', 'completed')
            ->get();

        // Create reviews for completed orders
        foreach ($printOrders as $index => $order) {
            $reviewer = $reviewers[$index % count($reviewers)];
            
            Rating::create([
                'user_id' => 3, // Bozo Egg
                'rated_by' => $reviewer->id,
                'print_order_id' => $order->id,
                'rating' => rand(4, 5), // Mostly positive reviews
                'comment' => $this->getRandomComment(),
                'created_at' => $order->completed_at,
                'updated_at' => $order->completed_at,
            ]);
        }
    }

    private function getRandomComment(): string
    {
        $comments = [
            "Amazing quality print! The colors are vibrant and true to the digital version.",
            "Excellent service and fast shipping. The print exceeded my expectations!",
            "Beautiful artwork and professional printing. Will definitely order again!",
            "The print quality is outstanding. Very happy with my purchase.",
            "Great communication and the print arrived well-packaged. Highly recommend!",
            "Stunning print! The detail and color accuracy are impressive.",
            "Perfect addition to my collection. The print quality is superb.",
            "Very satisfied with both the artwork and print quality.",
            "Fast delivery and excellent print quality. Thank you!",
            "The colors are even more beautiful in person. Great work!"
        ];

        return $comments[array_rand($comments)];
    }
}
