<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use Symfony\Component\HttpFoundation\Response;

/**
 * Исключение для случая, когда запрашиваемый ресурс не найден.
 *
 * Наследует ServiceException и автоматически устанавливает HTTP 404.
 *
 * @package App\Exceptions
 */
class ResourceNotFoundException extends ServiceException
{
    /**
     * Создаёт исключение «ресурс не найден».
     *
     * @param string         $resource Название ресурса (например, 'User', 'Auto').
     * @param mixed          $id       Идентификатор ресурса (int, string или null).
     * @param Throwable|null $previous Предыдущее исключение для вложенности.
     */
    public function __construct(
        string $resource,
        mixed $id = null,
        ?Throwable $previous = null,
    ) {
        $message = $this->buildMessage($resource, $id);
        $context = $id !== null
            ? ['id' => $id, 'resource' => $resource]
            : ['resource' => $resource];

        parent::__construct(
            $message,
            Response::HTTP_NOT_FOUND,
            0,
            $context,
            $previous,
        );
    }

    /**
     * Формирует текстовое сообщение об ошибке.
     *
     * @param string $resource Название ресурса.
     * @param mixed  $id       Идентификатор ресурса или null.
     * @return string Сформированное сообщение.
     */
    private function buildMessage(string $resource, mixed $id = null): string
    {
        return $id !== null
            ? "{$resource} with ID {$id} not found"
            : "{$resource} not found";
    }
}
