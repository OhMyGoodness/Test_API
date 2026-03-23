<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\ResourceNotFoundException;
use App\Services\User\DTO\UserLoginDTO;
use App\Services\User\Interfaces\UserServiceInterface;
use App\Services\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Сервис для авторизации пользователей.
 *
 * @package App\Services\User
 */
class UserService implements UserServiceInterface
{
    /**
     * Авторизация пользователя и выдача токена.
     *
     * @param UserLoginDTO $dto Данные для авторизации (email и пароль)
     * @return string Токен авторизации
     * @throws ResourceNotFoundException Если пользователь не найден или пароль неверен
     */
    public function login(UserLoginDTO $dto): string
    {
        /** @var User|null $user */
        $user = User::query()
            ->byEmail($dto->email)
            ->first();

        if ($user === null || !Hash::check($dto->password, $user->password)) {
            throw new ResourceNotFoundException('User', $dto->email);
        }

        $token = $user->createToken('auth_token');

        Log::info("UserService.login: User '{$user->id}' successfully logged in");

        return $token->plainTextToken;
    }
}
