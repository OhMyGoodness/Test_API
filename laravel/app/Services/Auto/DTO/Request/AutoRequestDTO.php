<?php

declare(strict_types=1);

namespace App\Services\Auto\DTO\Request;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных автомобиля из запроса в сервис.
 *
 * @package App\Services\Auto\DTO\Request
 */
class AutoRequestDTO extends Data
{
    /**
     * @param int    $year          Год выпуска автомобиля.
     * @param int    $mileage       Пробег автомобиля в километрах.
     * @param string $color         Цвет автомобиля.
     * @param int    $auto_model_id Идентификатор модели автомобиля.
     * @param int    $auto_mark_id  Идентификатор марки автомобиля.
     */
    public function __construct(
        public int    $year,
        public int    $mileage,
        public string $color,
        public int    $auto_model_id,
        public int    $auto_mark_id,
    ) {
    }
}
