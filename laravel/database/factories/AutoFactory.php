<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Services\Auto\Models\Auto;
use App\Services\Auto\Models\AutoMark;
use App\Services\Auto\Models\AutoModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

/**
 * Фабрика для создания тестовых данных модели Auto.
 *
 * @extends Factory<Auto>
 */
class AutoFactory extends Factory
{
    /**
     * @var class-string<Auto> Целевая модель фабрики.
     */
    protected $model = Auto::class;

    /**
     * Возвращает стандартный набор атрибутов для создания автомобиля.
     * Связанные модель и марка создаются автоматически через фабрики.
     *
     * @return array<string, mixed>
     * @throws RandomException
     */
    public function definition(): array
    {
        return [
            'year'          => random_int(2000, 2025),
            'mileage'       => random_int(10000, 100000),
            'color'         => $this->faker->colorName(),
            'auto_model_id' => AutoModel::factory(),
            'auto_mark_id'  => AutoMark::factory(),
        ];
    }
}
