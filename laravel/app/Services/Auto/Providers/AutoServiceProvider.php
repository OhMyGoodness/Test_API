<?php

namespace App\Services\Auto\Providers;

use App\Services\Auto\Interfaces\AutoServiceInterface;
use App\Services\Auto\Services\AutoService;
use Carbon\Laravel\ServiceProvider;
use phpDocumentor\Reflection\Exception;

/**
 * @package App\Services\Auto
 */
class AutoServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/application/database/migrations');

        $this->app->bind(AutoServiceInterface::class, function ($app) {
            $version = request()->route('version');
            if ($version == 'v1') {
                return new AutoService();
            } else {
                throw new Exception("AutoService $version not implemented");
            }
        });
    }
}
