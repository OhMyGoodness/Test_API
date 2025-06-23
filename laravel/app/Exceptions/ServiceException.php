<?php

namespace App\Exceptions;

use Exception;

/**
 * @package App\Exceptions
 */
class ServiceException extends Exception
{
    /**
     * @var int HTTP-статус ошибки
     */
    private int $httpStatusCode;

    /**
     * Конструктор ServiceException.
     *
     * @param string $message Сообщение исключения
     * @param int $httpStatusCode HTTP-статус код
     * @param int $code Код ошибки
     */
    public function __construct(string $message, int $httpStatusCode = 500, int $code = 0)
    {
        parent::__construct($message, $code);
        $this->httpStatusCode = $httpStatusCode;
    }

    /**
     * Получить HTTP-статус ошибки.
     *
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
