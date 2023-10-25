<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Limiter;

use Illuminate\Http\Request;
use LaravelAuthPro\Contracts\AuthIdentifierInterface;
use LaravelAuthPro\Infrastructure\OneTimePassword\Limiter\Contracts\OneTimePasswordRequestLimiterInterface;

class OneTimePasswordIpAddressLimiter extends OneTimePasswordLimiter implements OneTimePasswordRequestLimiterInterface
{
    private readonly string $ipAddress;

    public function __construct(Request $request)
    {
        $this->ipAddress = $request->ip() ?? throw new \RuntimeException('Unknown IP Address');
    }

    public function getName(): string
    {
        return $this->ipAddress;
    }

    public function pass(AuthIdentifierInterface $identifier): bool
    {
        return $this->defaultPass();
    }
}
