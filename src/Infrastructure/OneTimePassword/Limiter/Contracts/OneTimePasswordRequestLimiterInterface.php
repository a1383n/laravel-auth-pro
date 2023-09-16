<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\Contracts;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordLimiterInterface;

interface OneTimePasswordRequestLimiterInterface extends OneTimePasswordLimiterInterface
{
    public function pass(AuthIdentifierInterface $identifier): bool;
}
