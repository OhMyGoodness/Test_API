<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Исключение для случая, когда переданные данные запроса не могут быть преобразованы в DTO.
 *
 * @package App\Exceptions
 */
class InvalidDtoRequestException extends Exception
{
    /**
     * Создаёт исключение невалидных данных запроса.
     *
     * @param string         $message  Сообщение об ошибке.
     * @param int            $code     Внутренний код ошибки (по умолчанию 0).
     * @param Throwable|null $previous Предыдущее исключение для вложенности.
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
