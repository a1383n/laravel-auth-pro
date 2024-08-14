<?php

namespace LaravelAuthPro\SignInMethods;

use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\AuthenticatableInterface;
use LaravelAuthPro\Contracts\AuthSignInMethodInterface;
use LaravelAuthPro\Contracts\Exceptions\AuthException;

class PasswordSignInMethod implements AuthSignInMethodInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(AuthCredentialInterface $credential, ?AuthenticatableInterface $user = null): void
    {
        if (!method_exists($credential, 'getPassword')) {
            throw new InvalidArgumentException('getPassword not found in given credential');
        }

        if (empty($user->getPassword())) {
            throw new AuthException('password_not_provided');
        }

        if (!Hash::check($credential->getPassword(), $user->getPassword())) {
            throw new AuthException('invalid_password');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getUserRequiredColumns(): array
    {
        return [
            'id',
            'password',
        ];
    }
}
