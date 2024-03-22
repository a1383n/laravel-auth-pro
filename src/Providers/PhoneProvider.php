<?php

namespace LaravelAuthPro\Providers;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\Providers\PhoneProviderInterface;
use LaravelAuthPro\Enums\AuthIdentifierType;
use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Enums\AuthProviderType;
use LaravelAuthPro\Model\Builder\AuthenticatableBuilder;
use LaravelAuthPro\SignInMethods\OneTimePasswordSignInMethod;
use LaravelAuthPro\SignInMethods\PasswordSignInMethod;

class PhoneProvider extends AuthProvider implements PhoneProviderInterface
{
    public const ID = 'phone';
    public const IDENTIFIER_TYPE = AuthIdentifierType::MOBILE;
    public const TYPE = AuthProviderType::INTERNAL;
    public const SUPPORTED_SIGN_IN_METHODS = [
        AuthProviderSignInMethod::PASSWORD,
        AuthProviderSignInMethod::LINK,
        AuthProviderSignInMethod::ONE_TIME_PASSWORD,
    ];
    protected const SIGN_IN_METHODS = [
        'password' => PasswordSignInMethod::class,
        'otp' => OneTimePasswordSignInMethod::class,
    ];

    public function createUserWithPhoneAndPassword(string $phone, string $password, ?callable $beforeBuildClosure = null): AuthenticatableInterface
    {
        return $this->createAuthenticatable($phone, function (AuthenticatableBuilder $authenticatableBuilder) use ($beforeBuildClosure, $password) {
            $authenticatableBuilder->withPassword($password);

            if ($beforeBuildClosure !== null) {
                $beforeBuildClosure($authenticatableBuilder);
            }
        });
    }

    public function createUserWithPhone(string $phone, ?callable $beforeBuildClosure = null): AuthenticatableInterface
    {
        return $this->createAuthenticatable($phone, $beforeBuildClosure);
    }
}
