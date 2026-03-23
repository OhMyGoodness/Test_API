<?php

declare(strict_types=1);

namespace App\Services\Auto\DTO\Response;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных модели автомобиля из сервиса в контроллер.
 *
 * @package App\Services\Auto\DTO\Response
 */
class AutoModelResponseDTO extends Data
{
    /**
     * @param int    $id   Идентификатор модели автомобиля.
     * @param string $name Название модели автомобиля.
     */
    public function __construct(
        public int    $id,
        public string $name,
    ) {
    }
}
