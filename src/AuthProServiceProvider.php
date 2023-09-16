<?php

namespace LaravelAuthPro;

use Illuminate\Support\ServiceProvider;
use LaravelAuthPro\Contracts\AuthExceptionConverterInterface;
use LaravelAuthPro\Contracts\AuthServiceInterface;
use LaravelAuthPro\Contracts\Repositories\UserRepositoryInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\OneTimePasswordService;
use LaravelAuthPro\Repositories\UserRepository;

class AuthProServiceProvider extends ServiceProvider
{
    protected const CONTAINER_ALIAS_AUTH_PROVIDER_PREFIX = 'auth.provider.';
    public const CONTAINER_ALIAS_AUTH_PROVIDER_TEMPLATE = self::CONTAINER_ALIAS_AUTH_PROVIDER_PREFIX . '%s';

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/auth_pro.php',
            'auth_pro'
        );

        // Register facade
        $this->app->bind('auth_pro', AuthProManager::class);

        $this->registerRepositories();

        $this->registerAuthProviders();

        $this->registerInfrastructures();

        $this->app->bind(AuthExceptionConverterInterface::class, AuthExceptionConverter::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }

    private function registerRepositories(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    private function registerAuthProviders(): void
    {
        foreach (AuthPro::getAuthProvidersMapper() as $providerAbstract => $providerInstance) {
            $this->app->bind($providerAbstract, $providerInstance);
            $this->app->alias($providerAbstract, sprintf(self::CONTAINER_ALIAS_AUTH_PROVIDER_TEMPLATE, $providerInstance::ID));
        }
    }

    private function registerInfrastructures(): void
    {
        OneTimePasswordService::register($this->app);
    }

    private function registerPublishable(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/auth_pro.php' => config_path('auth_pro.php'),
        ], 'config');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPublishable();
    }
}
