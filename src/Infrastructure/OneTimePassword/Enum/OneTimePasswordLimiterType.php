<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Enum;

enum OneTimePasswordLimiterType
{
    case REQUEST;
    case VERIFY;
    case FAILED;
}
