<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Limiter;

use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\Contracts\OneTimePasswordRequestLimiterInterface;

class OneTimePasswordIpAddressLimiter extends OneTimePasswordLimiter implements OneTimePasswordRequestLimiterInterface
{
    private readonly string $ipAddress;

    public function __construct(Request $request)
    {
        $this->ipAddress = $request->ip() ?? '0.0.0.0';
    }

    public function getName(): string
    {
        return $this->ipAddress;
    }

    public function decayInterval(): CarbonInterval
    {
        return CarbonInterval::minute(30);
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
