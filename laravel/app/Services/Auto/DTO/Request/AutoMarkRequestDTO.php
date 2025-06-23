<?php

namespace App\Services\Auto\DTO\Request;

use Spatie\LaravelData\Data;

/**
 * DTO для запроса с данными марки автомобиля
 *
 * @package App\Services\Auto\DTO\Request
 */
class AutoMarkRequestDTO extends Data
{
    /**
     * @param string $name Название марки
     */
    public function __construct(
        public string $name
    )
    {
    }
}
