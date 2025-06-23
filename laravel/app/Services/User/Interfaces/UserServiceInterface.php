<?php

namespace App\Services\User\Interfaces;

use App\Services\User\DTO\UserLoginDTO;

/**
 * Интерфейс для сервиса авторизации пользователей
 *
 * @package App\Services\Contracts
 */
interface UserServiceInterface
{
    /**
     * Аутентификация пользователя
     *
     * @param UserLoginDTO $dto Данные для авторизации
     * @return string Токен авторизации
     */
    public function login(UserLoginDTO $dto): string;
}
