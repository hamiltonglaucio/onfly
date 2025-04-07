<?php

namespace App\TravelService\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use App\TravelService\Domain\Contracts\TravelRequestRepositoryInterface;
use App\TravelService\Infrastructure\Repositories\TravelRequestEloquentRepository;

class TravelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TravelRequestRepositoryInterface::class,
            TravelRequestEloquentRepository::class
        );
    }

    public function boot(): void
    {
        $this->registerMigrations();
    }

    public function registerMigrations()
    {
        $migrationPath = base_path('app/TravelService/Infrastructure/Database/Migrations');

        if (is_dir($migrationPath)) {
            $this->loadMigrationsFrom($migrationPath);
        }
    }
}
