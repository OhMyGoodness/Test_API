<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Базовое исключение сервисного слоя.
 *
 * Используется для передачи HTTP-статуса и дополнительного контекста ошибки.
 *
 * @package App\Exceptions
 */
class ServiceException extends Exception
{
    /**
     * @var int HTTP-статус ответа при данной ошибке.
     */
    private int $httpStatusCode;

    /**
     * @var array<string, mixed> Дополнительный контекст ошибки.
     */
    private array $context;

    /**
     * Создаёт новое исключение сервисного слоя.
     *
     * @param string         $message        Сообщение об ошибке.
     * @param int            $httpStatusCode HTTP-статус ответа (по умолчанию 500).
     * @param int            $code           Внутренний код ошибки (по умолчанию 0).
     * @param array<string, mixed> $context  Дополнительный контекст ошибки.
     * @param Throwable|null $previous       Предыдущее исключение для вложенности.
     */
    public function __construct(
        string $message,
        int $httpStatusCode = 500,
        int $code = 0,
        array $context = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);

        $this->httpStatusCode = $httpStatusCode;
        $this->context = $context;
    }

    /**
     * Возвращает HTTP-статус ответа.
     *
     * @return int HTTP-статус.
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    /**
     * Возвращает дополнительный контекст ошибки.
     *
     * @return array<string, mixed> Контекст ошибки.
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
