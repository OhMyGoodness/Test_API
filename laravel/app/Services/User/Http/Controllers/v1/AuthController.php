<?php

namespace App\Services\User\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Responses\ApiResponse;
use App\Resources\ResourceNotFoundException;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер для авторизации пользователей
 *
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * @OA\Post(
     *     tags={"Authorization"},
     *     path="/auth/login",
     *     @OA\RequestBody(
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
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1...")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     *
     * @param UserLoginRequest $request
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
