<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Базовый абстрактный класс сервисного слоя.
 *
 * Предоставляет метод `executeSafely()` для безопасного выполнения операций
 * с централизованной обработкой исключений Eloquent и сервисного слоя.
 *
 * @package App\Services
 */
abstract class BaseResourceService
{
    /**
     * Безопасно выполняет операцию с обработкой исключений.
     *
     * Перехватывает `ModelNotFoundException` и преобразует в `ResourceNotFoundException`.
     * Все остальные исключения оборачиваются в `ServiceException` с кодом 500.
     *
     * @param callable $callback     Функция, содержащая бизнес-логику операции.
     * @param string   $errorMessage Сообщение об ошибке для ServiceException.
     * @return mixed Результат выполнения callback-функции.
     * @throws ResourceNotFoundException Если ресурс не найден в БД.
     * @throws ServiceException          При любой другой ошибке выполнения.
     */
    protected function executeSafely(callable $callback, string $errorMessage): mixed
    {
        try {
            return call_user_func($callback);
        } catch (ServiceException $e) {
            // Пробрасываем ServiceException (включая ResourceNotFoundException) без обёртки
            throw $e;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Обработка ситуации, если ресурс не найден
            throw new ResourceNotFoundException('Resource', null, $e);
        } catch (Throwable $e) {
            // Обработка других исключений
            Log::error("BaseResourceService.executeSafely: Unexpected error — {$errorMessage}", [
                'exception' => $e->getMessage(),
                'class'     => static::class,
            ]);
            throw new ServiceException(
                $errorMessage,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
