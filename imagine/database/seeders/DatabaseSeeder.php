<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Run the seeders
        $this->call([
            StoreDataSeeder::class,
            ReviewsSeeder::class
        ]);
    }
}
