<?php

namespace App\Services\Auto\DTO\Request;

use Spatie\LaravelData\Data;

/**
 * DTO для запроса с данными модели автомобиля
 *
 * @package App\Services\Auto\DTO\Request
 */
class AutoModelRequestDTO extends Data
{
    /**
     * @param string $name Название модели
     */
    public function __construct(
        public string $name
    )
    {
    }
}
