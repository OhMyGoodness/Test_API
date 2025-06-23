<?php

namespace App\Http\Requests;

use App\Services\DTO\UserLoginDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос для авторизации пользователя
 *
 * @package App\Http\Requests
 *
 * @OA\Schema(
 *     schema="UserLoginRequest",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", example="user@example.com", description="Электронная почта пользователя"),
 *     @OA\Property(property="password", type="string", format="password", example="password123", description="Пароль пользователя")
 * )
 */
class UserLoginRequest extends FormRequest
{
    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Преобразует запрос в DTO
     *
     * @return UserLoginDTO
     */
    public function getDTO(): UserLoginDTO
    {
        return new UserLoginDTO(
            $this->input('email'),
            $this->input('password')
        );
    }
}
