<?php

namespace LaravelAuthPro\Contracts;

interface AuthProviderInterface
{
    /**
     * @param AuthCredentialInterface $credential
     * @return AuthenticatableInterface
     * @throws AuthExceptionInterface
     */
    public function authenticate(AuthCredentialInterface $credential): AuthenticatableInterface;
}
