<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Фабрика для создания стандартизированных JSON-ответов API.
 *
 * Все методы возвращают JsonResponse с единой структурой:
 * - успех: `{success: true, data: ...}`
 * - ошибка: `{success: false, message: ..., errors?: ...}`
 *
 * @package App\Http\Responses
 */
class ApiResponse
{
    /**
     * Создаёт ответ об ошибке.
     *
     * @param string               $message    Сообщение об ошибке.
     * @param int                  $statusCode HTTP-статус ответа.
     * @param array<string, mixed> $errors     Детализированные ошибки (например, ошибки валидации).
     * @return JsonResponse
     */
    public static function error(string $message, int $statusCode = Response::HTTP_BAD_REQUEST, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return new JsonResponse($response, $statusCode);
    }

    /**
     * Создаёт ответ для успешного создания ресурса (HTTP 201).
     *
     * @param mixed $data Данные созданного ресурса.
     * @return JsonResponse
     */
    public static function created(mixed $data): JsonResponse
    {
        return self::success($data, Response::HTTP_CREATED);
    }

    /**
     * Создаёт успешный ответ с данными.
     *
     * Если данные — пагинированная коллекция ресурсов, возвращается без обёртки.
     * Для остальных данных используется стандартная обёртка `{success: true, data: ...}`.
     *
     * @param mixed $data       Данные для ответа (ресурс, коллекция, массив или null).
     * @param int   $statusCode HTTP-статус ответа (по умолчанию 200).
     * @return JsonResponse
     */
    public static function success(mixed $data = null, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        // Если это ResourceCollection с пагинацией - возвращаем как есть
        if ($data instanceof AnonymousResourceCollection &&
            $data->resource instanceof LengthAwarePaginator) {
            return $data->response()->setStatusCode($statusCode);
        }

        // Если это обычный пагинатор - возвращаем как есть
        if ($data instanceof LengthAwarePaginator) {
            return response()->json($data, $statusCode);
        }

        // Для остальных данных - стандартная обертка
        return new JsonResponse([
            'success' => true,
            'data'    => $data
        ], $statusCode);
    }

    /**
     * Создаёт пустой ответ без тела (HTTP 204).
     *
     * Используется при успешном удалении ресурса.
     *
     * @return JsonResponse
     */
    public static function noContent(): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
