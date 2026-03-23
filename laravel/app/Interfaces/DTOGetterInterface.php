<?php

declare(strict_types=1);

namespace App\Interfaces;

/**
 * Контракт для FormRequest-классов, способных преобразовывать данные запроса в DTO.
 *
 * Все FormRequest, реализующие этот интерфейс, обязаны возвращать
 * типизированный DTO из валидированных данных.
 *
 * @package App\Interfaces
 */
interface DTOGetterInterface
{
    /**
     * Возвращает DTO, сформированный из валидированных данных запроса.
     *
     * @return mixed DTO-объект с данными запроса.
     */
    public function getDTO(): mixed;
}
