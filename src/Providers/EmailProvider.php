<?php

namespace LaravelAuthPro\Providers;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\Providers\EmailProviderInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Enums\AuthProviderType;
use LaravelAuthPro\Model\Builder\AuthenticatableBuilder;

class EmailProvider extends AuthProvider implements EmailProviderInterface
{
    public const ID = 'email';
    public const TYPE = AuthProviderType::INTERNAL;
    public const IDENTIFIER_TYPE = AuthIdentifierType::EMAIL;
    public const SUPPORTED_SIGN_IN_METHODS = [
        AuthProviderSignInMethod::PASSWORD,
        AuthProviderSignInMethod::LINK,
        AuthProviderSignInMethod::ONE_TIME_PASSWORD,
    ];

    public function createUserWithEmailAndPassword(string $email, string $password): AuthenticatableInterface
    {
        return $this->createAuthenticatable($email, fn(AuthenticatableBuilder $authenticatableBuilder) => $authenticatableBuilder->withPassword($password));
    }
}
