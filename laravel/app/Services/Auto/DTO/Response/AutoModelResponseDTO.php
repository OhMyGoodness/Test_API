<?php

namespace App\Services\Auto\DTO\Response;

use Spatie\LaravelData\Data;

/**
 * DTO для ответа с данными модели автомобиля
 *
 * @package App\Services\Auto\DTO\Response
 */
class AutoModelResponseDTO extends Data
{
    /**
     * @param int $id ID модели
     * @param string $name Название модели
     */
    public function __construct(
        public int    $id,
        public string $name
    )
    {
    }
}
