<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * Обработка исключений.
     *
     * @param $request
     * @param Throwable $e
     * @return JsonResponse|Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): JsonResponse|Response
    {
        // Если клиент ожидает JSON, возвращаем кастомные JSON-ответы
        if ($request->expectsJson()) {
            // Обработка ServiceException
            if ($e instanceof ServiceException) {
                $error = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];

                if ($this->isDebugMode()) {
                    $error['context'] = $e->getTrace();
                }

                return response()->json($error, $e->getHttpStatusCode());
            }

            // Обработка остальных исключений
            return response()->json([
                'success' => false,
                'message' => $this->isDebugMode()
                    ? $e->getMessage()
                    : 'Произошла ошибка. Обратитесь к администратору.',
            ], $this->getStatusCode($e));
        }

        // Для невеб-запросов или HTML - стандартный рендеринг
        return parent::render($request, $e);
    }

    /**
     * Проверяем, активен ли debug-режим.
     *
     * @return bool
     */
    private function isDebugMode(): bool
    {
        return config('app.debug');
    }

    /**
     * Определить HTTP-статус для исключения.
     *
     * @param Throwable $e
     * @return int
     */
    private function getStatusCode(Throwable $e): int
    {
        return method_exists($e, 'getStatusCode')
            ? $e->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
