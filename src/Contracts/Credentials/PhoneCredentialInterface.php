<?php

namespace LaravelAuthPro\Contracts\Credentials;

use LaravelAuthPro\Contracts\AuthCredentialInterface;
use LaravelAuthPro\Contracts\Credentials\Base\HasOneTimePasswordInterface;
use LaravelAuthPro\Contracts\Credentials\Base\HasPasswordInterface;
use LaravelAuthPro\Contracts\Credentials\Base\HasSignatureInterface;

interface PhoneCredentialInterface extends AuthCredentialInterface, HasOneTimePasswordInterface, HasPasswordInterface, HasSignatureInterface
{
    public function getPhone(): string;
}
