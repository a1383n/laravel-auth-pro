<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Contracts\Base\BaseServiceInterface;

interface OneTimePasswordRateLimiterServiceInterface extends BaseServiceInterface
{
    public function pass(AuthIdentifierInterface $identifier): bool;
}
