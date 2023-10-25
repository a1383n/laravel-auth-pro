<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Enum;

enum OneTimePasswordVerifyError: string
{
    case TOKEN_NOT_FOUND = 'token_not_found';
    case INVALID_CODE = 'invalid_code';
    case TOO_MANY_FAILED_ATTEMPTS = 'too_many_failed_attempts';
}
