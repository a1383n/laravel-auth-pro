<?php

namespace LaravelAuthPro\Contracts;

use LaravelAuthPro\Contracts\Exceptions\AuthException;

interface AuthSignInMethodInterface
{
    /**
     * @throws AuthException
     */
    public function __invoke(AuthCredentialInterface $credential, ?AuthenticatableInterface $user = null): void;

    /**
     * @return string[]
     */
    public function getUserRequiredColumns(): array;
}
