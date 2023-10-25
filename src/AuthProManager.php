<?php

namespace LaravelAuthPro;

use Illuminate\Contracts\Config\Repository;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthProviderInterface;
use LaravelAuthPro\Contracts\AuthServiceInterface;
use LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface;
use LaravelAuthPro\Contracts\Providers\EmailProviderInterface;
use LaravelAuthPro\Providers\EmailProvider;

class AuthProManager
{
    /**
     * @var array<class-string<AuthProviderInterface>>
     */
    protected readonly array $authProvidersClass;

    /**
     * @var array<class-string<AuthCredentialInterface>>
     */
    protected readonly array $authCredentialClass;

    public function __construct(Repository $configRepository)
    {
        /**
         * @phpstan-ignore-next-line
         */
        $this->authProvidersClass = $configRepository->get('auth_pro.providers', [EmailProviderInterface::class => EmailProvider::class]);

        /**
         * @phpstan-ignore-next-line
         */
        $this->authCredentialClass = $configRepository->get('auth_pro.credentials', [EmailProviderInterface::class => EmailCredentialInterface::class]);
    }

    /**
     * @return array<class-string<AuthProviderInterface>, class-string<AuthProviderInterface>>
     */
    public function getAuthProvidersMapper(): array
    {
        return $this->authProvidersClass;
    }

    /**
     * @return array<class-string<AuthCredentialInterface>>
     */
    public function getCredentialsMapper(): array
    {
        return $this->authCredentialClass;
    }

    public function getService(): AuthServiceInterface
    {
        return app(AuthServiceInterface::class);
    }
}
