<?php

namespace LaravelAuthPro\Contracts;

use LaravelAuthPro\Contracts\Exceptions\AuthException;

interface AuthSignInMethodInterface
{
    /**
     * @param AuthenticatableInterface $user
     * @param AuthCredentialInterface $credential
     * @return AuthenticatableInterface
     * @throws AuthException
     */
    public function __invoke(AuthenticatableInterface $user, AuthCredentialInterface $credential): AuthenticatableInterface;

    /**
     * @return string[]
     */
    public function getUserRequiredColumns(): array;
}
