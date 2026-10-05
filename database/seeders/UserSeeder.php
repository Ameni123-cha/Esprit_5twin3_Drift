<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@nutritrace.com',
            'role' => 'admin',
        ]);

        // Producer
        User::factory()->create([
            'name' => 'Producer User',
            'email' => 'producer@nutritrace.com',
            'role' => 'producer',
        ]);

        // Transformer
        User::factory()->create([
            'name' => 'Transformer User',
            'email' => 'transformer@nutritrace.com',
            'role' => 'transformer',
        ]);

        // Distributor
        User::factory()->create([
            'name' => 'Distributor User',
            'email' => 'distributor@nutritrace.com',
            'role' => 'distributor',
        ]);

        // Consumer
        User::factory()->create([
            'name' => 'Consumer User',
            'email' => 'consumer@nutritrace.com',
            'role' => 'consumer',
        ]);

        // Random users
        User::factory(45)->create();
    }
}
