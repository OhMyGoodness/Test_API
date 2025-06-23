<?php

namespace App\Exceptions;

use Throwable;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package App\Exceptions
 */
class ResourceNotFoundException extends ServiceException
{
    /**
     * Конструктор ResourceNotFoundException.
     *
     * @param string $resource Имя ресурса
     * @param int|null $id ID ресурса (может быть null)
     * @param Throwable|null $previous Предыдущее исключение для вложенности
     */
    public function __construct(string $resource, ?int $id = null, Throwable $previous = null)
    {
        $message = $this->buildMessage($resource, $id);
        $context = $id !== null ? ['id' => $id, 'resource' => $resource] : ['resource' => $resource];

        parent::__construct(
            $message, // Сообщение об ошибке
            Response::HTTP_NOT_FOUND, // Код ответа HTTP 404
            0, // Код ошибки (по умолчанию 0)
            $context, // Дополнительные данные ошибки
            $previous // Предыдущее исключение
        );
    }

    /**
     * Построить сообщение об ошибке.
     *
     * @param string $resource Имя ресурса
     * @param int|null $id ID ресурса (может быть null)
     * @return string
     */
    private function buildMessage(string $resource, ?int $id = null): string
    {
        return $id !== null
            ? "$resource with ID $id not found"
            : "$resource not found";
    }
}
