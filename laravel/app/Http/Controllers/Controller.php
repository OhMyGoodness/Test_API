<?php

declare(strict_types=1);

namespace App\Http\Controllers;

/**
 * Базовый контроллер приложения.
 *
 * Содержит глобальные Swagger-аннотации для документации API.
 *
 * @OA\Info(
 *     title="API Documentation",
 *     version="0.1",
 *     @OA\Contact(
 *         email="info@mail.com"
 *     )
 * )
 * @OA\Server(
 *     description="Local server",
 *     url="http://localhost:8080/api/"
 * )
 */
abstract class Controller
{
}
