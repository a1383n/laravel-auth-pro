<?php

namespace LaravelAuthPro\Infrastructure\OneTimePassword\Enum;

enum OneTimePasswordTokenType: string
{
    case RANDOM_STRING = 'random_string';
    case RANDOM_INT = 'random_int';
    case ULID = 'ulid';
    case UUID = 'uuid';
}
