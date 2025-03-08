<?php

namespace App\Services\User\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\User\Http\Requests\UserLoginRequest;
use App\Services\User\Resources\UserLoginResource;
use App\Services\User\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package App\Services\User\Http\Controllers\v1
 */
class AuthController extends Controller
{
    /**
     * @param UserService $userService
     */
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
     *             @OA\Schema(ref="#/components/schemas/UserLoginResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="UNAUTHORIZED"
     *     )
     * )
     *
     * @param UserLoginRequest $request
     * @return UserLoginResource
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            return $this->userService->login($request->getDTO());
        } catch (Exception $exception) {
            return response()->json()->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }
    }
}
