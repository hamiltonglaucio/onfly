<?php

namespace App\AuthService\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\AuthService\Domain\Contracts\UserRepositoryInterface;
use App\AuthService\Infrastructure\Repositories\UserEloquentRepository;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind de repositórios do domínio
        $this->app->bind(
            UserRepositoryInterface::class,
            UserEloquentRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerMigrations();
    }

    public function registerMigrations()
    {
        $migrationPath = base_path('app/AuthService/Infrastructure/Database/Migrations');

        if (is_dir($migrationPath)) {
            $this->loadMigrationsFrom($migrationPath);
        }
    }
}
