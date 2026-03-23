<?php

declare(strict_types=1);

namespace App\Services\Auto\DTO\Response;

use Spatie\LaravelData\Data;

/**
 * DTO для передачи данных марки автомобиля из сервиса в контроллер.
 *
 * @package App\Services\Auto\DTO\Response
 */
class AutoMarkResponseDTO extends Data
{
    /**
     * @param int    $id   Идентификатор марки автомобиля.
     * @param string $name Название марки автомобиля.
     */
    public function __construct(
        public int    $id,
        public string $name,
    ) {
    }
}
