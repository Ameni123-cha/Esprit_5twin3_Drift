<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnvironmentalFootprintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = \App\Models\Product::where('status', 'published')->get();

        if ($products->isEmpty()) {
            // Fallback: create some footprints normally
            \App\Models\EnvironmentalFootprint::factory()->count(12)->create();
            return;
        }

        foreach ($products as $product) {
            if ($product->environmentalFootprint) {
                continue;
            }

            \App\Models\EnvironmentalFootprint::factory()
                ->for($product, 'product')
                ->create();
        }
    }
}
