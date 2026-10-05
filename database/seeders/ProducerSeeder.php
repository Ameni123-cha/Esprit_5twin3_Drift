<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProducerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a handful of producers with associated user accounts
        \App\Models\Producer::factory()
            ->count(12)
            ->withUser()
            ->create();
    }
}
