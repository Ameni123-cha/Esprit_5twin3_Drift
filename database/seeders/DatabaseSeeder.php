<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // For a clean demo environment overwrite default seeding
        // Run the French demo seeder which resets selected tables
        $this->call([
            FrenchDemoSeeder::class,
            M5DemoSeeder::class,
        ]);
    }
}
