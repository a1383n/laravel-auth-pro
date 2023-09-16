<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Enum;

enum OneTimePasswordError: string
{
    case RATE_LIMIT_EXCEEDED = 'rate_limit_exceeded';
}
