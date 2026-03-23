<?php

declare(strict_types=1);

namespace App\Services\User\Providers;

use App\Services\User\Interfaces\UserServiceInterface;
use App\Services\User\UserService;
use Illuminate\Support\ServiceProvider;

/**
 * Провайдер сервисов для модуля User.
 *
 * Регистрирует биндинг UserServiceInterface → UserService в контейнере зависимостей.
 *
 * @package App\Services\User\Providers
 */
class UserServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервисов модуля пользователей в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }
}
