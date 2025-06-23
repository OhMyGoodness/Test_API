<?php

namespace App\Services\User;

use App\Models\User;
use App\Resources\ResourceNotFoundException;
use App\Services\User\DTO\UserLoginDTO;
use App\Services\User\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Hash;

/**
 * Сервис для авторизации пользователей
 *
 * @package App\Services
 */
class UserService implements UserServiceInterface
{
    /**
     * Авторизация пользователя и выдача токена
     *
     * @param UserLoginDTO $dto
     * @return string Токен авторизации
     * @throws ResourceNotFoundException
     */
    public function login(UserLoginDTO $dto): string
    {
        $user = User::query()
                    ->byEmailAndPassword($dto->email, $dto->password)
                    ->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw new ResourceNotFoundException('User', $dto->email);
        }

        $token = $user->createToken('auth_token');
        return $token->plainTextToken;
    }
}
