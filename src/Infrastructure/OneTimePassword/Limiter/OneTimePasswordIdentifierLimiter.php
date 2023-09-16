<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Limiter;

use Carbon\CarbonInterval;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\Contracts\OneTimePasswordRequestLimiterInterface;

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

    public function pass(AuthIdentifierInterface $identifier): bool
    {
        return $this->defaultPass();
    }
}
