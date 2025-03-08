<?php

namespace Database\Factories;

use App\Services\Auto\Models\AutoModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @package Database\Factories
 */
class AutoModelFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = AutoModel::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name() . ' ' . (AutoModel::count() + 1),
        ];
    }
}
