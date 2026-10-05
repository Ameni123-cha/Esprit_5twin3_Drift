<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $producers = \App\Models\Producer::all();
        $transformers = \App\Models\Transformer::all();

        if ($producers->isEmpty()) {
            \App\Models\Producer::factory()->count(12)->withUser()->create();
            $producers = \App\Models\Producer::all();
        }

        // Create a mix of published and draft products tied to existing producers/transformers.
        for ($i = 0; $i < 40; $i++) {
            $producer = $producers->random();
            $transformer = $transformers->isNotEmpty() && rand(0, 100) < 70 ? $transformers->random() : null;

            \App\Models\Product::factory()
                ->for($producer, 'producer')
                ->state([
                    'transformer_id' => $transformer?->id,
                    'status' => ($i % 2 === 0) ? 'published' : 'draft',
                ])
                ->create();
        }
    }
}
