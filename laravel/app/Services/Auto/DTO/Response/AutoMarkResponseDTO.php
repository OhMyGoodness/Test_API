<?php

namespace App\Services\Auto\DTO\Response;

use Spatie\LaravelData\Data;

/**
 * DTO для ответа с данными марки автомобиля
 *
 * @package App\Services\Auto\DTO\Response
 */
class AutoMarkResponseDTO extends Data
{
    /**
     * @param int $id ID марки
     * @param string $name Название марки
     */
    public function __construct(
        public int    $id,
        public string $name
    )
    {
    }
}
