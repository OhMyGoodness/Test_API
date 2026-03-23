<?php

declare(strict_types=1);

namespace App\Services\Auto\DTO\Response;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных автомобиля из сервиса в контроллер.
 *
 * @package App\Services\Auto\DTO\Response
 */
class AutoResponseDTO extends Data
{
    /**
     * @param int                      $id      Идентификатор автомобиля.
     * @param int                      $year    Год выпуска автомобиля.
     * @param int                      $mileage Пробег автомобиля в километрах.
     * @param string                   $color   Цвет автомобиля.
     * @param AutoMarkResponseDTO|null  $mark    Марка автомобиля или null.
     * @param AutoModelResponseDTO|null $model   Модель автомобиля или null.
     */
    public function __construct(
        public int                   $id,
        public int                   $year,
        public int                   $mileage,
        public string                $color,
        public ?AutoMarkResponseDTO  $mark = null,
        public ?AutoModelResponseDTO $model = null,
    ) {
    }
}
