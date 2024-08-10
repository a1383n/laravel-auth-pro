<?php

namespace LaravelAuthPro\Contracts;

interface AuthProviderInterface
{
    /**
     * @throws AuthExceptionInterface
     */
    public function authenticate(AuthCredentialInterface $credential): AuthenticatableInterface;
}
