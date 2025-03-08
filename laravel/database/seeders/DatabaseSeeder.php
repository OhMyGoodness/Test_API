<?php

namespace Database\Seeders;

use App\Services\User\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
                AutoModelSeeder::class,
                AutoMarkSeeder::class,
                AutoSeeder::class,
            ]
        );
    }
}
