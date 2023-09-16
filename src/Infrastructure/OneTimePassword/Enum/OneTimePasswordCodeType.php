<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Enum;

enum OneTimePasswordCodeType: string
{
    case DIGIT = 'digit';
    case ALPHA = 'alpha';
}
