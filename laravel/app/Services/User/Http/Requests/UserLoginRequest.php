<?php

declare(strict_types=1);

namespace App\Services\User\Http\Requests;

use App\Interfaces\DTOGetterInterface;
use App\Services\User\DTO\UserLoginDTO;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Запрос для авторизации пользователя.
 *
 * @package App\Services\User\Http\Requests
 *
 * @OA\Schema(
 *     schema="UserLoginRequest",
 *     required={"email", "password"},
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com", description="Электронная почта пользователя"),
 *     @OA\Property(property="password", type="string", format="password", example="password123", description="Пароль пользователя")
 * )
 */
class UserLoginRequest extends FormRequest implements DTOGetterInterface
{
    /**
     * Правила валидации входящих данных.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Пользовательские сообщения об ошибках валидации.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'Электронная почта обязательна для заполнения.',
            'email.email'       => 'Введите корректный адрес электронной почты.',
            'password.required' => 'Пароль обязателен для заполнения.',
            'password.string'   => 'Пароль должен быть строкой.',
            'password.min'      => 'Пароль должен содержать не менее 6 символов.',
        ];
    }

    /**
     * Создаёт и возвращает DTO на основе валидированных данных запроса.
     *
     * @return UserLoginDTO
     */
    public function getDTO(): UserLoginDTO
    {
        return new UserLoginDTO(
            email: (string) $this->validated('email'),
            password: (string) $this->validated('password'),
        );
    }
}
