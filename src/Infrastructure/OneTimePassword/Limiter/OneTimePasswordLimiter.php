<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Limiter;

use Illuminate\Support\Facades\RateLimiter;
use LaravelAuthPro\Infrastructure\OneTimePassword\Contracts\OneTimePasswordLimiterInterface;

abstract class OneTimePasswordLimiter implements OneTimePasswordLimiterInterface
{
    private string $prefix = 'otp';

    /**
     * @return int
     */
    public function hit(): int
    {
        return RateLimiter::hit($this->getKey(), intval($this->decayInterval()->totalSeconds));
    }

    protected function getKey(): string
    {
        return $this->prefix. '_' . $this->getName();
    }

    protected function defaultPass(): bool
    {
        if (RateLimiter::tooManyAttempts($this->getKey(), $this->maxAttempts())) {
            return false;
        }

        $this->hit();

        return true;
    }
}
