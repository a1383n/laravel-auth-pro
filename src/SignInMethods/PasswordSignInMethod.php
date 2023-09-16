<?php

namespace LaravelAuthPro\SignInMethods;

use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthSignInMethodInterface;
use LaravelAuthPro\Contracts\Credentials\EmailCredentialInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;
use Illuminate\Support\Facades\Hash;

class PasswordSignInMethod implements AuthSignInMethodInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(AuthenticatableInterface $user, EmailCredentialInterface|AuthCredentialInterface $credential): AuthenticatableInterface
    {
        if (!method_exists($credential, 'getPassword'))
            throw new \InvalidArgumentException('getPassword not found in given credential');

        if (empty($user->getPassword()))
            throw new \InvalidArgumentException('password not provided for this user');

        if (!Hash::check($credential->getPassword(), $user->getPassword()))
            throw new AuthException('invalid_password');

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getUserRequiredColumns(): array
    {
        return [
            'id',
            'password'
        ];
    }
}
