<?php

namespace LaravelAuthPro\Contracts\Providers;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthProviderInterface;

interface GoogleProviderInterface extends AuthProviderInterface
{
    public function createUserWithGoogleIdToken(string $idToken): AuthenticatableInterface;
}
