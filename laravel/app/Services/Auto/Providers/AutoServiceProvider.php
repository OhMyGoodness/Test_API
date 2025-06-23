<?php

namespace App\Services\Auto\Providers;

use App\Services\Auto\Interfaces\AutoServiceInterface;
use App\Services\Auto\Services\AutoService;
use Exception;
use Illuminate\Support\ServiceProvider;

/**
 * Провайдер сервисов для модуля Auto
 */
class AutoServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервисов в контейнере
     *
     * @return void
     */
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/application/database/migrations');

        $this->app->bind(AutoServiceInterface::class, function ($app) {
            $version = request()->route('version');

            return match ($version) {
                'v1'    => new AutoService(),
                default => throw new Exception("AutoService $version not implemented")
            };
        });
    }
}
