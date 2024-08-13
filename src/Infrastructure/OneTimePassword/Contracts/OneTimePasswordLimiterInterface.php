<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Contracts;

use Carbon\CarbonInterval;

interface OneTimePasswordLimiterInterface
{
    public function getKey(): string;

    public function decayInSeconds(): int;

    public function maxAttempts(): int;
}
