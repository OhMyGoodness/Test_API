<?php

namespace Database\Seeders;

use App\Services\Auto\Models\AutoModel;
use Illuminate\Database\Seeder;

/**
 * @package Database\Seeders
 */
class AutoModelSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        AutoModel::factory(10)->create();
    }
}
