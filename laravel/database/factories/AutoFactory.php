<?php

namespace Database\Factories;

use App\Services\Auto\Models\Auto;
use App\Services\Auto\Models\AutoMark;
use App\Services\Auto\Models\AutoModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

/**
 * @extends Factory<Auto>
 */
class AutoFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Auto::class;

    /**
     * @return array
     * @throws RandomException
     */
    public function definition(): array
    {
        return [
            'year'          => random_int(2000, 2025),
            'mileage'       => random_int(10000, 100000),
            'color'         => $this->faker->colorName(),
            'auto_model_id' => $this->faker->numberBetween(1, AutoModel::count()),
            'auto_mark_id'  => $this->faker->numberBetween(1, AutoMark::count()),
        ];
    }
}
