<?php

namespace LaravelAuthPro\Contracts\Credentials;

use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\Credentials\Base\HasOneTimePasswordInterface;
use LaravelAuthPro\Contracts\Credentials\Base\HasPasswordInterface;

interface PhoneCredentialInterface extends AuthCredentialInterface, HasPasswordInterface, HasOneTimePasswordInterface
{
    public function getPhone(): string;
}
