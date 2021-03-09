<?php

namespace Jargoud\LaravelApiKey\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Jargoud\LaravelApiKey\Auth\ApiTokenGuard;

class LaravelApiKeyServiceProvider extends ServiceProvider
{
    public const NAMESPACE = 'apikey';

    protected const CONFIG_PATH = __DIR__ . '/../../config/config.php';
    protected const MIGRATIONS_DIRECTORY = __DIR__ . '/../../database/migrations';
    protected const VIEWS_DIRECTORY = __DIR__ . '/../../resources/views';

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(self::VIEWS_DIRECTORY, self::NAMESPACE);
        $this->loadMigrationsFrom(self::MIGRATIONS_DIRECTORY);

        $this->bootAuthGuard();

        if ($this->app->runningInConsole()) {
            $this
                ->publishConfig()
                ->publishViews();
        }
    }

    protected function bootAuthGuard(): self
    {
        Auth::extend('api_token', function ($app, $name, array $config): ApiTokenGuard {
            return new ApiTokenGuard(
                Auth::createUserProvider($config['provider'] ?? null),
                $app['request'],
                $config['input_key'] ?? 'api_token',
                $config['storage_key'] ?? 'api_token',
                $config['hash'] ?? false
            );
        });

        return $this;
    }

    protected function publishViews(): self
    {
        $this->publishes(
            [
                self::VIEWS_DIRECTORY => resource_path('views/vendor/' . self::NAMESPACE),
            ],
            'views'
        );

        return $this;
    }

    protected function publishConfig(): self
    {
        $this->publishes(
            [
                self::CONFIG_PATH => config_path(self::NAMESPACE . '.php'),
            ],
            'config'
        );

        return $this;
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(self::CONFIG_PATH, self::NAMESPACE);

        $this->setupRoutes();
    }

    protected function setupRoutes(): self
    {
        if (config(self::NAMESPACE . '.backpack.enabled')) {
            // by default, use the routes file provided in vendor
            $routeFilePath = '/routes/backpack/' . self::NAMESPACE . '.php';
            $routeFilePathInUse = __DIR__ . '/../../' . $routeFilePath;

            // but if there's a file with the same name in routes/backpack, use that one
            $customRouteFilePath = base_path() . $routeFilePath;
            if (file_exists($customRouteFilePath)) {
                $routeFilePathInUse = $customRouteFilePath;
            }

            $this->loadRoutesFrom($routeFilePathInUse);
        }

        return $this;
    }
}
