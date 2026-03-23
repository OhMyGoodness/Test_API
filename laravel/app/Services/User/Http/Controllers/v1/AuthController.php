<?php

declare(strict_types=1);

namespace App\Services\User\Http\Controllers\v1;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\User\Http\Requests\UserLoginRequest;
use App\Services\User\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер для авторизации пользователей.
 *
 * @package App\Services\User\Http\Controllers\v1
 */
class AuthController extends Controller
{
    /**
     * @param UserServiceInterface $userService Сервис авторизации пользователей
     */
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    /**
     * Авторизация пользователя и получение токена.
     *
     * @OA\Post(
     *     tags={"Authorization"},
     *     path="/auth/login",
     *     summary="Авторизация пользователя",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/UserLoginRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1...")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     * @param UserLoginRequest $request Запрос с данными авторизации
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            $token = $this->userService->login($request->getDTO());

            return ApiResponse::success(['token' => $token]);
        } catch (ResourceNotFoundException $exception) {
            return ApiResponse::error(
                'Invalid credentials',
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
