<?php

namespace App\Services\Auto\DTO\Response;

use Spatie\LaravelData\Data;

/**
 * DTO для ответа с данными автомобиля
 *
 * @package App\Services\Auto\DTO\Response
 */
class AutoResponseDTO extends Data
{
    /**
     * @param int $id ID автомобиля
     * @param int $year Год выпуска автомобиля
     * @param int $mileage Пробег автомобиля
     * @param string $color Цвет автомобиля
     * @param AutoMarkResponseDTO|null $mark Марка автомобиля
     * @param AutoModelResponseDTO|null $model Модель автомобиля
     */
    public function __construct(
        public int                   $id,
        public int                   $year,
        public int                   $mileage,
        public string                $color,
        public ?AutoMarkResponseDTO  $mark = null,
        public ?AutoModelResponseDTO $model = null,
    )
    {
    }
}
