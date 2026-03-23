<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Services\Auto\Models\AutoMark;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика для создания тестовых данных модели AutoMark.
 *
 * @extends Factory<AutoMark>
 */
class AutoMarkFactory extends Factory
{
    /**
     * @var class-string<AutoMark> Целевая модель фабрики.
     */
    protected $model = AutoMark::class;

    /**
     * Возвращает стандартный набор атрибутов для создания марки автомобиля.
     * Имя генерируется уникальным, без обращений к БД.
     *
     * @return array<string, string>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
        ];
    }
}
