<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Limiter;

use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\Contracts\OneTimePasswordRequestLimiterInterface;
use Carbon\CarbonInterval;

class OneTimePasswordIdentifierLimiter extends OneTimePasswordLimiter implements OneTimePasswordRequestLimiterInterface
{
    public function __construct(private readonly AuthIdentifierInterface $identifier)
    {
        //
    }

    public function getName(): string
    {
        //TODO: Return hash
        return $this->identifier->getIdentifierValue();
    }

    public function decayInterval(): CarbonInterval
    {
        return CarbonInterval::hour();
    }

    public function maxAttempts(): int
    {
        return 5;
    }

    public function pass(AuthIdentifierInterface $identifier): bool
    {
        return $this->defaultPass();
    }
}
