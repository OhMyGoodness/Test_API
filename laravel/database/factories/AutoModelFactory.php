<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Services\Auto\Models\AutoModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика для создания тестовых данных модели AutoModel.
 *
 * @extends Factory<AutoModel>
 */
class AutoModelFactory extends Factory
{
    /**
     * @var class-string<AutoModel> Целевая модель фабрики.
     */
    protected $model = AutoModel::class;

    /**
     * Возвращает стандартный набор атрибутов для создания модели автомобиля.
     * Имя генерируется уникальным, без обращений к БД.
     *
     * @return array<string, string>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . ' ' . $this->faker->unique()->bothify('##??'),
        ];
    }
}
