<?php

declare(strict_types=1);

namespace App\Services\Auto\DTO\Request;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных модели автомобиля из запроса в сервис.
 *
 * @package App\Services\Auto\DTO\Request
 */
class AutoModelRequestDTO extends Data
{
    /**
     * @param string $name Название модели автомобиля.
     */
    public function __construct(
        public string $name,
    ) {
    }
}
