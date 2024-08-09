<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Limiter;

use Carbon\CarbonInterval;
use Illuminate\Support\Facades\RateLimiter;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordLimiterInterface;

abstract class OneTimePasswordLimiter implements OneTimePasswordLimiterInterface
{
    private string $prefix = 'otp';

    protected function getKey(): string
    {
        return $this->prefix. '_' . $this->getName();
    }

    protected function defaultPass(): bool
    {
        if (RateLimiter::tooManyAttempts($this->getKey(), $this->maxAttempts())) {
            return false;
        }

        RateLimiter::hit($this->getKey(), intval($this->decayInterval()->totalSeconds));

        return true;
    }

    public function decayInterval(): CarbonInterval
    {
        return CarbonInterval::seconds(config('auth_pro.one_time_password.rate_limit')[static::class]['decay_in_seconds'] ?? 900);
    }

    public function maxAttempts(): int
    {
        return config('auth_pro.one_time_password.rate_limit')[static::class]['max_attempts'] ?? 6;
    }
}
