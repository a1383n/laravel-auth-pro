<?php

namespace LaravelAuthPro\Enums;

enum AuthProviderType: string
{
    case INTERNAL = 'internal';
    case OAUTH = 'oauth';
}
