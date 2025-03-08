<?php

namespace Database\Factories;

use App\Services\Auto\Models\AutoMark;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @package Database\Factories
 */
class AutoMarkFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = AutoMark::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name() . ' ' . (AutoMark::count() + 1),
        ];
    }
}
