<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\Contracts;

use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordLimiterInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

interface OneTimePasswordVerifyLimiterInterface extends OneTimePasswordLimiterInterface
{
    public function passes(OneTimePasswordEntityInterface $entity): bool;

    public function error();
}
