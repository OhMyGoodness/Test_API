<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \App\Exceptions\Handler::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(function (string $model_name) {
            $namespace = 'Database\\Factories\\';
            $model_name = Str::afterLast($model_name, '\\');
            return $namespace . $model_name . 'Factory';
        });

        $this->registerApiRoutes();
    }

    public function registerApiRoutes(): void
    {
        // Автоматически ищем папки версий в директории routes
        $versionFolders = File::directories(base_path('routes'));

        foreach ($versionFolders as $folder) {
            // Получаем название папки (например, v1, v2, ...)
            $folderName = basename($folder);

            // Проверяем, что папка имеет префикс v и за ним следует цифра
            if (preg_match('/^v\d+$/', $folderName)) {
                // Формируем путь к файлу routes.php внутри папки
                $routeFile = $folder . '/api.php';

                if (file_exists($routeFile)) {
                    // Регистрируем группу маршрутов с соответствующим префиксом
                    Route::prefix("api/{$folderName}")
                         ->middleware('api')
                         ->group($routeFile);
                }
            }
        }
    }
}
