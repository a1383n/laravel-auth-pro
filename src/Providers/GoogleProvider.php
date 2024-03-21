<?php

namespace LaravelAuthPro\Providers;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\Providers\GoogleProviderInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Enums\AuthProviderType;

class GoogleProvider extends AuthProvider implements GoogleProviderInterface
{
    public const ID = 'google';
    public const TYPE = AuthProviderType::OAUTH;
    public const IDENTIFIER_TYPE = AuthIdentifierType::EMAIL;
    public const SUPPORTED_SIGN_IN_METHODS = [
        AuthProviderSignInMethod::OAUTH,
    ];

    public function createUserWithGoogleIdToken(string $idToken): AuthenticatableInterface
    {
        // TODO: We should verify and decrypt idToken here or a layer above (Service)??.. also we must modify AuthenticatableBuilder to set name, avatar and ...
        // TODO: Implement createUserWithGoogleIdToken() method.
        throw new \Exception('not implemented');
    }
}
