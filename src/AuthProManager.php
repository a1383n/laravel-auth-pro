<?php

namespace LaravelAuthPro;

use Illuminate\Contracts\Config\Repository;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthProviderInterface;
use LaravelAuthPro\Contracts\AuthServiceInterface;
use LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface;
use LaravelAuthPro\Contracts\Providers\EmailProviderInterface;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Providers\EmailProvider;
use LaravelAuthPro\SignInMethods\OAuthSignInMethod;
use LaravelAuthPro\SignInMethods\OneTimePasswordSignInMethod;
use LaravelAuthPro\SignInMethods\PasswordSignInMethod;

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

    public function getDefaultSignInMethodsMapper(): array
    {
        return [
            AuthProviderSignInMethod::PASSWORD->value => PasswordSignInMethod::class,
            AuthProviderSignInMethod::ONE_TIME_PASSWORD->value => OneTimePasswordSignInMethod::class,
            AuthProviderSignInMethod::OAUTH->value => OAuthSignInMethod::class,
//            AuthProviderSignInMethod::LINK => LinkSignInMethod::class,
        ];
    }

    public function getService(): AuthServiceInterface
    {
        return app(AuthServiceInterface::class);
    }

    /**
     * @return class-string<AuthenticatableInterface>
     */
    public function getDefaultAuthenticatableModel(): string
    {
        return "App\\Models\\User";
    }
}
