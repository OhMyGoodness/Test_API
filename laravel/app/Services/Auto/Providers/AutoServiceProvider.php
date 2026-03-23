<?php

declare(strict_types=1);

namespace App\Services\Auto\Providers;

use App\Services\Auto\Interfaces\AutoMarkRepositoryInterface;
use App\Services\Auto\Interfaces\AutoMarkServiceInterface;
use App\Services\Auto\Interfaces\AutoModelRepositoryInterface;
use App\Services\Auto\Interfaces\AutoModelServiceInterface;
use App\Services\Auto\Interfaces\AutoRepositoryInterface;
use App\Services\Auto\Interfaces\AutoServiceInterface;
use App\Services\Auto\Repositories\AutoMarkRepository;
use App\Services\Auto\Repositories\AutoModelRepository;
use App\Services\Auto\Repositories\AutoRepository;
use App\Services\Auto\Services\AutoMarkService;
use App\Services\Auto\Services\AutoModelService;
use App\Services\Auto\Services\AutoService;
use Exception;
use Illuminate\Support\ServiceProvider;

/**
 * Провайдер сервисов для модуля Auto.
 */
class AutoServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервисов в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../application/database/migrations');

        $this->app->bind(AutoServiceInterface::class, function ($app) {
            $path = request()->path();
            preg_match('#api/(v\d+)/#', $path, $matches);
            $version = $matches[1] ?? null;

            return match ($version) {
                'v1'    => $app->make(AutoService::class),
                default => throw new Exception("AutoService {$version} not implemented")
            };
        });

        $this->app->bind(AutoMarkServiceInterface::class, AutoMarkService::class);
        $this->app->bind(AutoModelServiceInterface::class, AutoModelService::class);

        $this->app->bind(AutoRepositoryInterface::class, AutoRepository::class);
        $this->app->bind(AutoMarkRepositoryInterface::class, AutoMarkRepository::class);
        $this->app->bind(AutoModelRepositoryInterface::class, AutoModelRepository::class);
    }
}
