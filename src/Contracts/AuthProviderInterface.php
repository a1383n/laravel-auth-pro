<?php

namespace LaravelAuthPro\Contracts;

use LaravelAuthPro\Enums\AuthProviderSignInMethod;
use LaravelAuthPro\Enums\AuthProviderType;

interface AuthProviderInterface
{
    public function getProviderId(): string;

    public function getProviderType(): AuthProviderType;

    /**
     * @return AuthProviderSignInMethod[]
     */
    public function getProviderSignInMethods(): array;

    /**
     * @param AuthCredentialInterface $credential
     * @return AuthenticatableInterface
     * @throws AuthExceptionInterface
     */
    public function authenticate(AuthCredentialInterface $credential): AuthenticatableInterface;
}
