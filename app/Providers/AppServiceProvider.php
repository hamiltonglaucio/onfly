<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerModuleProviders();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    public function registerModuleProviders()
    {
        $modulesPath = app_path(); // App/

        foreach (scandir($modulesPath) as $module) {
            if (in_array($module, ['.', '..'])) {
                continue;
            }

            $providerClass = "App\\{$module}\\Infrastructure\\Providers\\" . $module . "Provider";

            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }
}
