<?php

declare(strict_types=1);

namespace App\Providers;

use App\Policies\AutoPolicy;
use App\Services\Auto\Models\Auto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 * Основной провайдер приложения.
 *
 * Отвечает за регистрацию глобального обработчика исключений,
 * настройку фабрик моделей и автоматическую регистрацию версионированных API-маршрутов.
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервисов в контейнере.
     *
     * Привязывает кастомный Handler как реализацию ExceptionHandler.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \App\Exceptions\Handler::class
        );
    }

    /**
     * Загрузка сервисов после регистрации всех провайдеров.
     *
     * Настраивает фабрики моделей, регистрирует API-маршруты по версиям
     * и привязывает Policy для моделей.
     *
     * @return void
     */
    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(function (string $model_name) {
            $namespace = 'Database\\Factories\\';
            $model_name = Str::afterLast($model_name, '\\');
            return $namespace . $model_name . 'Factory';
        });

        Gate::policy(Auto::class, AutoPolicy::class);

        $this->registerApiRoutes();
    }

    /**
     * Автоматически регистрирует версионированные API-маршруты из директории `routes/`.
     *
     * Ищет поддиректории вида `v1`, `v2`, ... и регистрирует
     * файл `api.php` внутри каждой как группу маршрутов с соответствующим префиксом.
     *
     * @return void
     */
    public function registerApiRoutes(): void
    {
        $versionFolders = File::directories(base_path('routes'));

        foreach ($versionFolders as $folder) {
            $folderName = basename($folder);

            if (preg_match('/^v\d+$/', $folderName)) {
                $routeFile = $folder . '/api.php';

                if (file_exists($routeFile)) {
                    Route::prefix("api/{$folderName}")
                         ->middleware('api')
                         ->group($routeFile);
                }
            }
        }
    }
}
