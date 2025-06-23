<?php

namespace App\Services\User\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ресурс для преобразования модели пользователя в JSON-ответ
 *
 * @property User $resource
 *
 * @OA\Schema(
 *     schema="UserLoginResource",
 *     title="UserLoginResource",
 *     description="Ресурс пользователя для авторизации",
 *     @OA\Property(property="id", type="integer", example=1, description="Уникальный идентификатор пользователя"),
 *     @OA\Property(property="name", type="string", example="John Doe", description="Имя пользователя"),
 *     @OA\Property(property="email", type="string", example="user@example.com", description="Электронная почта пользователя"),
 *     @OA\Property(property="token", type="string", example="eyJhbGciOiJIUz...", description="Авторизационный токен")
 * )
 */
class UserLoginResource extends JsonResource
{
    /**
     * Преобразует ресурс в массив для ответа API
     *
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'    => $this->resource->id,
            'name'  => $this->resource->name,
            'email' => $this->resource->email,
            'token' => $this->resource->currentAccessToken()->plainTextToken ?? null,
        ];
    }
}
