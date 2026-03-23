<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Глобальный обработчик исключений приложения.
 *
 * Для JSON-запросов возвращает структурированные JSON-ответы вместо HTML-страниц.
 * В debug-режиме добавляет трассировку стека к ответу об ошибке.
 *
 * @package App\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * Обрабатывает исключение и формирует HTTP-ответ.
     *
     * Для JSON-запросов возвращает структурированный JSON с описанием ошибки.
     * Для обычных запросов делегирует обработку родительскому классу.
     *
     * @param \Illuminate\Http\Request $request HTTP-запрос.
     * @param Throwable                $e       Перехваченное исключение.
     * @return JsonResponse|Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): JsonResponse|Response
    {
        // Если клиент ожидает JSON, возвращаем кастомные JSON-ответы
        if ($request->expectsJson()) {
            // Обработка AuthenticationException — 401
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Обработка ValidationException — 422
            if ($e instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors'  => $e->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

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
