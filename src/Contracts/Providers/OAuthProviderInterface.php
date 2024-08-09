<?php

namespace LaravelAuthPro\Contracts\Providers;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthProviderInterface;

interface OAuthProviderInterface extends AuthProviderInterface
{
    public function createUserWithIdToken(string $driver, string $idToken): AuthenticatableInterface;
}
