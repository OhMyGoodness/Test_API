<?php

namespace Database\Seeders;

use App\Services\Auto\Models\Auto;
use App\Services\User\Models\User;
use Illuminate\Database\Seeder;
use Random\RandomException;

/**
 * @package Database\Seeders
 */
class AutoSeeder extends Seeder
{
    /**
     * @return void
     * @throws RandomException
     */
    public function run(): void
    {
        Auto::factory()->count(3)->sequence(
            [
                'year'          => 2010,
                'mileage'       => random_int(10000, 100000),
                'color'         => 'black',
                'auto_model_id' => 1,
                'auto_mark_id'  => 1,
            ],
            [
                'year'          => 2020,
                'mileage'       => random_int(10000, 100000),
                'color'         => 'white',
                'auto_model_id' => 2,
                'auto_mark_id'  => 2,
            ],
            [
                'year'          => 2020,
                'mileage'       => random_int(10000, 100000),
                'color'         => 'white',
                'auto_model_id' => 1,
                'auto_mark_id'  => 1,
                'user_id'       => User::query()->firstOrFail()->id,
            ]
        )
            ->create();
    }
}
