<?php

namespace App\Services\Auto\DTO\Request;

use Illuminate\Support\Optional;
use Spatie\LaravelData\Data;

/**
 * DTO для создания автомобиля
 *
 * @package App\Services\Auto\DTO\Request
 */
class AutoRequestDTO extends Data
{
    /**
     * @param int $year Год выпуска автомобиля
     * @param int $mileage Пробег автомобиля
     * @param string $color Цвет автомобиля
     * @param int $auto_model_id ID модели автомобиля
     * @param int $auto_mark_id ID марки автомобиля
     */
    public function __construct(
        public int    $year,
        public int    $mileage,
        public string $color,
        public int    $auto_model_id,
        public int    $auto_mark_id
    )
    {
    }
}
