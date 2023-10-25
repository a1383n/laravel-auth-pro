<?php

namespace LaravelAuthPro\Providers;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\Providers\GoogleProviderInterface;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Enums\AuthProviderType;
use LaravelAuthPro\SignInMethods\OAuthSignInMethod;

class GoogleProvider extends AuthProvider implements GoogleProviderInterface
{
    public const ID = 'google';
    public const TYPE = AuthProviderType::OAUTH;
    public const SUPPORTED_SIGN_IN_METHODS = [
        AuthProviderSignInMethod::OAUTH,
    ];
    protected const SIGN_IN_METHODS = [
        'oauth' => OAuthSignInMethod::class,
    ];

    public function createUserWithGoogleIdToken(string $idToken): AuthenticatableInterface
    {
        // TODO: Implement createUserWithGoogleIdToken() method.
        throw new \Exception('not implemented');
    }
}
