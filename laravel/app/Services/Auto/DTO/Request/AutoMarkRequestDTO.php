<?php

declare(strict_types=1);

namespace App\Services\Auto\DTO\Request;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных марки автомобиля из запроса в сервис.
 *
 * @package App\Services\Auto\DTO\Request
 */
class AutoMarkRequestDTO extends Data
{
    /**
     * @param string $name Название марки автомобиля.
     */
    public function __construct(
        public string $name,
    ) {
    }
}
