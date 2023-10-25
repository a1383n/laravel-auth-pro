<?php

namespace LaravelAuthPro\Contracts\Providers;

use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthProviderInterface;

interface PhoneProviderInterface extends AuthProviderInterface
{
    public function createUserWithPhoneAndPassword(string $phone, string $password): AuthenticatableInterface;

    public function createUserWithPhone(string $phone): AuthenticatableInterface;
}
