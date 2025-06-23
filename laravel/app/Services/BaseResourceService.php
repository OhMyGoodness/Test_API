<?php

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @package App\Services
 */
abstract class BaseResourceService
{
    /**
     * Безопасное выполнение операций с обработкой исключений.
     *
     * @param callable $callback callback-функция, содержащая основное действие
     * @param string $errorMessage Сообщение об ошибке для ServiceException
     * @return mixed
     * @throws ResourceNotFoundException|ServiceException
     */
    protected function executeSafely(callable $callback, string $errorMessage): mixed
    {
        try {
            return call_user_func($callback);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Обработка ситуации, если ресурс не найден
            throw new ResourceNotFoundException('Resource', null, $e);
        } catch (Throwable $e) {
            // Обработка других исключений
            throw new ServiceException(
                $errorMessage,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
