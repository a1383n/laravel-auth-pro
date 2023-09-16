<?php

namespace LaravelAuthPro\Providers;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\Providers\EmailProviderInterface;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Enums\AuthProviderType;
use LaravelAuthPro\SignInMethods\PasswordSignInMethod;

class EmailProvider extends AuthProvider implements EmailProviderInterface
{
    public const ID = 'email';
    public const TYPE = AuthProviderType::INTERNAL;
    public const SUPPORTED_SIGN_IN_METHODS = [
        AuthProviderSignInMethod::PASSWORD,
        AuthProviderSignInMethod::LINK,
        AuthProviderSignInMethod::ONE_TIME_PASSWORD
    ];
    protected const SIGN_IN_METHODS = [
        'password' => PasswordSignInMethod::class
    ];

    public function createUserWithEmailAndPassword(string $email, string $password): AuthenticatableInterface
    {
        // TODO: Implement createUserWithEmailAndPassword() method.
        throw new \Exception('not implemented');
    }
}
