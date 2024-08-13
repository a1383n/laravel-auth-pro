<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\BaseServiceInterface;

interface OneTimePasswordRateLimiterServiceInterface extends BaseServiceInterface
{
    public function tooManyAttempts(AuthIdentifierInterface $identifier): bool;

    public function hit(AuthIdentifierInterface $identifier): void;

    public function availableIn(AuthIdentifierInterface $identifier): int;
}
