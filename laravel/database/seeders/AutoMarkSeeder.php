<?php

namespace Database\Seeders;

use App\Services\Auto\Models\AutoMark;
use Illuminate\Database\Seeder;

/**
 * @package Database\Seeders
 */
class AutoMarkSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        AutoMark::factory(10)->create();
    }
}
