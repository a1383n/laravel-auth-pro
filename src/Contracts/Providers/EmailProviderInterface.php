<?php

namespace LaravelAuthPro\Contracts\Providers;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthProviderInterface;

interface EmailProviderInterface extends AuthProviderInterface
{
    public function createUserWithEmailAndPassword(string $email, string $password, ?callable $beforeBuildClosure = null): AuthenticatableInterface;
}
