<?php

namespace App\Services\User\Providers;


use App\Services\User\Interfaces\UserServiceInterface;
use App\Services\User\UserService;
use Illuminate\Support\ServiceProvider;

/**
 * @package App\Services\User\Providers
 */
class UserServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервисов пользователя в контейнере
     *
     * @return void
     */
    public function register(): void
    {
        // Регистрация UserService как реализации UserServiceInterface
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Загрузка сервисов (если нужно)
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
