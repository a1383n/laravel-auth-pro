<?php

namespace LaravelAuthPro;

use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthProviderInterface;
use LaravelAuthPro\Contracts\AuthServiceInterface;
use LaravelAuthPro\Contracts\Providers\EmailProviderInterface;
use LaravelAuthPro\Credentials\EmailCredential;
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

    /**
     * @return array<class-string<AuthProviderInterface>, class-string<AuthProviderInterface>>
     */
    public function getAuthProvidersConfiguration(): array
    {
        return config('auth_pro.providers', [EmailProviderInterface::class => ['enabled' => true, 'class' => EmailProvider::class, 'credential' => EmailCredential::class]]);
    }

    public function getDefaultSignInMethodsMapper(): array
    {
        $default =  [
            AuthProviderSignInMethod::PASSWORD->value => PasswordSignInMethod::class,
            AuthProviderSignInMethod::ONE_TIME_PASSWORD->value => OneTimePasswordSignInMethod::class,
            AuthProviderSignInMethod::OAUTH->value => OAuthSignInMethod::class,
//            AuthProviderSignInMethod::LINK => LinkSignInMethod::class,
        ];

        return config('auth_pro.sign_in_methods', $default);
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
        return config('auth_pro.default_authenticatable_model', \App\Models\User::class);
    }
}
