<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Limiter;

use Carbon\CarbonInterval;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\Contracts\OneTimePasswordFailedLimiterInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\Contracts\OneTimePasswordVerifyLimiterInterface;
use LaravelAuthPro\Model\Contracts\OneTimePasswordEntityInterface;

class OneTimePasswordFailedAttemptsLimiter extends OneTimePasswordLimiter implements OneTimePasswordVerifyLimiterInterface, OneTimePasswordFailedLimiterInterface
{
    public function __construct(protected readonly OneTimePasswordEntityInterface $entity)
    {
        //
    }

    public function getName(): string
    {
        return $this->getKey();
    }

    public function decayInterval(): CarbonInterval
    {
        return $this->entity->getValidInterval();
    }

    public function maxAttempts(): int
    {
        return 3;
    }

    public function passes(OneTimePasswordEntityInterface $entity): bool
    {
        return $this->defaultPass();
    }

    public function error()
    {
        throw new \RuntimeException('Not implemented');
    }
}
