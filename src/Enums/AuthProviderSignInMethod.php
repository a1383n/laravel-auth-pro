<?php

namespace LaravelAuthPro\Enums;

enum AuthProviderSignInMethod: string
{
    case PASSWORD = 'password';
    case LINK = 'link';
    case OAUTH = 'oauth';
    case ONE_TIME_PASSWORD = 'otp';
}
