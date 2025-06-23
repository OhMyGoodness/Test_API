<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package App\Http\Responses
 */
class ApiResponse
{
    /**
     * Создает ответ об ошибке
     *
     * @param string $message
     * @param int $statusCode
     * @param array $errors
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
     * Создает ответ для успешного создания ресурса
     *
     * @param mixed $data
     * @return JsonResponse
     */
    public static function created(mixed $data): JsonResponse
    {
        return self::success($data, Response::HTTP_CREATED);
    }

    /**
     * Создает успешный ответ с данными
     *
     * @param mixed $data
     * @param int $statusCode
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
     * Создает пустой ответ (например, для удаления)
     *
     * @return JsonResponse
     */
    public static function noContent(): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
